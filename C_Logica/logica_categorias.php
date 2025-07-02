<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/Proyecto-Web2/C_Datos/crud_categorias.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/Proyecto-Web2/C_Entidad/class_categorias.php';

header('Content-Type: application/json');

$crudCategoria = new CategoriaDAO();

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        registrarCategoria();
        break;
    case 'GET':
        if (isset($_GET['action'])) {
            switch ($_GET['action']) {
                case 'listar':
                    listarCategorias();
                    break;
                case 'obtenerCategoria':
                    if (isset($_GET['id'])) {
                        obtenerCategoriaPorId($_GET['id']);
                    } else {
                        echo json_encode([
                            'status' => 'error',
                            'message' => 'Falta el parámetro id.'
                        ]);
                    }
                    break;
                default:
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Acción GET no reconocida.'
                    ]);
                    break;
            }
        } else {
            listarCategorias();
        }
        break;
    case 'PUT':
        actualizarCategoria();
        break;
    case 'PATCH':
        actualizarEstadoCategoria();
        break;
    case 'DELETE':
        eliminarCategoria();
        break;
    default:
        echo json_encode([
            'status' => 'error',
            'message' => 'Método no permitido'
        ]);
        break;
}

function registrarCategoria()
{
    global $crudCategoria;
    $categoria = new Categoria();
    $inputData = json_decode(file_get_contents('php://input'), true);
    $categoria->setNombre($inputData['nombre'] ?? '');
    $categoria->setDescripcion($inputData['descripcion'] ?? '');
    $categoria->setEstado($inputData['estado'] ?? '');
    try {
        $crudCategoria->insertar($categoria);
        echo json_encode([
            'status' => 'success',
            'message' => 'Categoría registrada correctamente'
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al registrar categoría: ' . $e->getMessage()
        ]);
    }
}

function listarCategorias()
{
    global $crudCategoria;
    try {
        $categorias = $crudCategoria->obtenerTodos();
        $response = [
            'status' => 'success',
            'data' => []
        ];
        foreach ($categorias as $categoria) {
            $response['data'][] = [
                'id_categoria' => $categoria->getIdCategoria(),
                'nombre' => $categoria->getNombre(),
                'descripcion' => $categoria->getDescripcion(),
                'estado' => $categoria->getEstado()
            ];
        }
        echo json_encode($response);
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al obtener categorías: ' . $e->getMessage()
        ]);
    }
}

function actualizarCategoria()
{
    global $crudCategoria;
    $putData = json_decode(file_get_contents("php://input"), true);
    if ($putData === null) {
        echo json_encode([
            'status' => 'error',
            'message' => 'No se pudieron leer los datos de la solicitud'
        ]);
        return;
    }
    $categoria = new Categoria();
    $categoria->setIdCategoria($putData['id_categoria_act'] ?? '');
    $categoria->setNombre($putData['nombre_act'] ?? '');
    $categoria->setDescripcion($putData['descripcion_act'] ?? '');
    $categoria->setEstado($putData['estado_act'] ?? '');
    try {
        $crudCategoria->actualizar($categoria);
        echo json_encode([
            'status' => 'success',
            'message' => 'Categoría actualizada correctamente'
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al actualizar categoría: ' . $e->getMessage()
        ]);
    }
}

function actualizarEstadoCategoria()
{
    global $crudCategoria;
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id_categoria'] ?? $data['id'] ?? '';
    $estado = $data['estado'] ?? 'inactivo';
    if (empty($id)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Falta el parámetro id_categoria o id.'
        ]);
        return;
    }
    try {
        $crudCategoria->actualizarEstado($id, $estado);
        echo json_encode([
            'status' => 'success',
            'message' => 'Estado actualizado correctamente'
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al actualizar estado: ' . $e->getMessage()
        ]);
    }
}

function eliminarCategoria()
{
    global $crudCategoria;
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'] ?? '';
    try {
        $crudCategoria->eliminar($id);
        echo json_encode([
            'status' => 'success',
            'message' => 'Categoría eliminada correctamente'
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al eliminar categoría: ' . $e->getMessage()
        ]);
    }
}

function obtenerCategoriaPorId($id)
{
    global $crudCategoria;
    try {
        $categoria = $crudCategoria->obtenerPorId($id);
        if ($categoria) {
            echo json_encode([
                'status' => 'success',
                'data' => [
                    'id_categoria' => $categoria->getIdCategoria(),
                    'nombre' => $categoria->getNombre(),
                    'descripcion' => $categoria->getDescripcion(),
                    'estado' => $categoria->getEstado()
                ]
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Categoría no encontrada.'
            ]);
        }
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al obtener categoría: ' . $e->getMessage()
        ]);
    }
    exit();
}

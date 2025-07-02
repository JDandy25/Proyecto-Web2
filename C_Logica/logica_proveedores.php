<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/Proyecto-Web2/C_Datos/crud_proveedores.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/Proyecto-Web2/C_Entidad/class_proveedores.php';

header('Content-Type: application/json');

$crudProveedor = new ProveedorDAO();

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        registrarProveedor();
        break;
    case 'GET':
        if (isset($_GET['action'])) {
            switch ($_GET['action']) {
                case 'listar':
                    listarProveedores();
                    break;
                case 'obtenerProveedor':
                    if (isset($_GET['id'])) {
                        obtenerProveedorPorId($_GET['id']);
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
            listarProveedores();
        }
        break;
    case 'PUT':
        actualizarProveedor();
        break;
    case 'PATCH':
        actualizarEstadoProveedor();
        break;
    case 'DELETE':
        eliminarProveedor();
        break;
    default:
        echo json_encode([
            'status' => 'error',
            'message' => 'Método no permitido'
        ]);
        break;
}

function registrarProveedor()
{
    global $crudProveedor;
    $proveedor = new Proveedor();
    $inputData = json_decode(file_get_contents('php://input'), true);
    $proveedor->setNombres($inputData['nombres'] ?? '');
    $proveedor->setApellidos($inputData['apellidos'] ?? '');
    $proveedor->setRUC($inputData['RUC'] ?? '');
    $proveedor->setTelefono($inputData['telefono'] ?? '');
    $proveedor->setCorreo($inputData['correo'] ?? '');
    $proveedor->setEstado($inputData['estado'] ?? '');
    try {
        $crudProveedor->insertar($proveedor);
        echo json_encode([
            'status' => 'success',
            'message' => 'Proveedor registrado correctamente'
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al registrar proveedor: ' . $e->getMessage()
        ]);
    }
}

function listarProveedores()
{
    global $crudProveedor;
    try {
        $proveedores = $crudProveedor->obtenerTodos();
        $response = [
            'status' => 'success',
            'data' => []
        ];
        foreach ($proveedores as $proveedor) {
            $response['data'][] = [
                'id_proveedor' => $proveedor->getIdProveedor(),
                'nombres' => $proveedor->getNombres(),
                'apellidos' => $proveedor->getApellidos(),
                'RUC' => $proveedor->getRUC(),
                'telefono' => $proveedor->getTelefono(),
                'correo' => $proveedor->getCorreo(),
                'estado' => $proveedor->getEstado()
            ];
        }
        echo json_encode($response);
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al obtener proveedores: ' . $e->getMessage()
        ]);
    }
}

function actualizarProveedor()
{
    global $crudProveedor;
    $putData = json_decode(file_get_contents("php://input"), true);
    if ($putData === null) {
        echo json_encode([
            'status' => 'error',
            'message' => 'No se pudieron leer los datos de la solicitud'
        ]);
        return;
    }
    $proveedor = new Proveedor();
    $proveedor->setIdProveedor($putData['id_proveedor_act'] ?? '');
    $proveedor->setNombres($putData['nombres_act'] ?? '');
    $proveedor->setApellidos($putData['apellidos_act'] ?? '');
    $proveedor->setRUC($putData['RUC_act'] ?? '');
    $proveedor->setTelefono($putData['telefono_act'] ?? '');
    $proveedor->setCorreo($putData['correo_act'] ?? '');
    $proveedor->setEstado($putData['estado_act'] ?? '');
    try {
        $crudProveedor->actualizar($proveedor);
        echo json_encode([
            'status' => 'success',
            'message' => 'Proveedor actualizado correctamente'
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al actualizar proveedor: ' . $e->getMessage()
        ]);
    }
}

function actualizarEstadoProveedor()
{
    global $crudProveedor;
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id_proveedor'] ?? $data['id'] ?? '';
    $estado = $data['estado'] ?? 'inactivo';
    if (empty($id)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Falta el parámetro id_proveedor o id.'
        ]);
        return;
    }
    try {
        $crudProveedor->actualizarEstado($id, $estado);
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

function eliminarProveedor()
{
    global $crudProveedor;
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'] ?? '';
    try {
        $crudProveedor->eliminar($id);
        echo json_encode([
            'status' => 'success',
            'message' => 'Proveedor eliminado correctamente'
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al eliminar proveedor: ' . $e->getMessage()
        ]);
    }
}

function obtenerProveedorPorId($id)
{
    global $crudProveedor;
    try {
        $proveedor = $crudProveedor->obtenerPorId($id);
        if ($proveedor) {
            echo json_encode([
                'status' => 'success',
                'data' => [
                    'id_proveedor' => $proveedor->getIdProveedor(),
                    'nombres' => $proveedor->getNombres(),
                    'apellidos' => $proveedor->getApellidos(),
                    'RUC' => $proveedor->getRUC(),
                    'telefono' => $proveedor->getTelefono(),
                    'correo' => $proveedor->getCorreo(),
                    'estado' => $proveedor->getEstado()
                ]
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Proveedor no encontrado.'
            ]);
        }
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al obtener proveedor: ' . $e->getMessage()
        ]);
    }
    exit();
}

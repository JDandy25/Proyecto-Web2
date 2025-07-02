<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/Proyecto-Web2/C_Datos/crud_productos.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/Proyecto-Web2/C_Entidad/class_producto.php';

header('Content-Type: application/json');

$crudProducto = new ProductoDAO();

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        registrarProducto();
        break;

    case 'GET':
        if (isset($_GET['action'])) { 
            switch ($_GET['action']) {
                case 'listar':
                    listarProductos();
                    break;
                case 'obtenerCategorias':
                    obtenerCategorias();
                    break;
                case 'obtenerProducto':
                    if (isset($_GET['id'])) {
                        obtenerProductoPorId($_GET['id']);
                    } else {
                        echo json_encode([
                            'status' => 'error',
                            'message' => 'Falta el parámetro id.'
                        ]);
                    }
                    break;
                case 'buscar':
                    if (isset($_GET['nombre'])) {
                        buscarProductos($_GET['nombre']);
                    } else {
                        echo json_encode([
                            'status' => 'error',
                            'message' => 'Falta el parámetro nombre.'
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
            listarProductos(); // Si no hay acción, listar productos por defecto
        }
        break;

    case 'PUT':
        actualizarProducto();
        break;

    case 'PATCH':
        actualizarEstadoProducto();
        break;

    case 'DELETE':
        eliminarProducto();
        break;

    default:
        echo json_encode([
            'status' => 'error',
            'message' => 'Método no permitido'
        ]);
        break;
}

function registrarProducto()
{
    global $crudProducto;
    
    $producto = new Producto();

    $inputData = json_decode(file_get_contents('php://input'), true);

    $producto->setNombre($inputData['nombre'] ?? '');
    $producto->setDescripcion($inputData['descripcion'] ?? '');
    $producto->setCodigoProd($inputData['codigo'] ?? '');
    $producto->setStock($inputData['stock'] ?? 0);
    $producto->setImagen($inputData['imagen'] ?? '');
    $producto->setIdCategoria($inputData['id_categoria'] ?? null);
    $producto->setEstado($inputData['estado'] ?? 1);

    try {
        $crudProducto->insertar($producto);
        $ultimo = $crudProducto->obtenerUltimoProductoYCategoria();
        if ($ultimo) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Producto registrado correctamente',
                'id_producto' => $ultimo['id_producto'],
                'nombre_categoria' => $ultimo['nombre_categoria']
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'No se pudo obtener el producto recién creado.'
            ]);
        }
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al registrar producto: ' . $e->getMessage()
        ]);
    }
}

function listarProductos()
{
    global $crudProducto;

    try {
        $productos = $crudProducto->obtenerTodos();
        $response = [
            'status' => 'success',
            'data' => []
        ];

        foreach ($productos as $producto) {
            $response['data'][] = [
                'id_producto' => $producto->getIdProducto(),
                'nombre' => $producto->getNombre(),
                'descripcion' => $producto->getDescripcion(),
                'codigo' => $producto->getCodigoProd(),
                'stock' => $producto->getStock(),
                'imagen' => $producto->getImagen(),
                'categoria' => $crudProducto->obtenerNombreCategoriaPorId($producto->getIdCategoria()),
                'estado' => $producto->getEstado()
            ];
        }

        echo json_encode($response);
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al obtener productos: ' . $e->getMessage()
        ]);
    }
}

function actualizarProducto()
{
    global $crudProducto;

    $putData = json_decode(file_get_contents("php://input"), true);

    if ($putData === null) {
        echo json_encode([
            'status' => 'error',
            'message' => 'No se pudieron leer los datos de la solicitud'
        ]);
        return;
    }

    $producto = new Producto();
    $producto->setIdProducto($putData['id_producto'] ?? '');
    $producto->setNombre($putData['nombre'] ?? '');
    $producto->setDescripcion($putData['descripcion'] ?? '');
    $producto->setCodigoProd($putData['codigo'] ?? '');
    $producto->setStock($putData['stock'] ?? 0);
    $producto->setImagen($putData['imagen'] ?? '');
    $producto->setIdCategoria($putData['id_categoria'] ?? null);
    $producto->setEstado($putData['estado'] ?? 1);

    try {
        $crudProducto->actualizar($producto);
        echo json_encode([
            'status' => 'success',
            'message' => 'Producto actualizado correctamente'
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al actualizar producto: ' . $e->getMessage()
        ]);
    }
}

function actualizarEstadoProducto()
{
    global $crudProducto;

    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id_producto'] ?? $data['id'] ?? '';
    $estado = $data['estado'] ?? 0;

    if (empty($id)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Falta el parámetro id_producto o id.'
        ]);
        return;
    }

    try {
        $crudProducto->actualizarEstado($id, $estado);
        echo json_encode([
            'status' => 'success',
            'message' => 'Estado del producto actualizado correctamente'
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al actualizar estado: ' . $e->getMessage()
        ]);
    }
}

function eliminarProducto()
{
    global $crudProducto;

    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'] ?? '';

    try {
        $crudProducto->eliminar($id);
        echo json_encode([
            'status' => 'success',
            'message' => 'Producto eliminado correctamente'
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al eliminar producto: ' . $e->getMessage()
        ]);
    }
}

function obtenerCategorias() {
    global $crudProducto;
    try {
        $categorias = $crudProducto->obtenerCategorias();
        echo json_encode([
            'status' => 'success',
            'categorias' => $categorias
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al obtener categorías: ' . $e->getMessage()
        ]);
    }
    exit();
}

function obtenerProductoPorId($id)
{
    global $crudProducto;
    try {
        $producto = $crudProducto->obtenerPorId($id);
        if ($producto) {
            echo json_encode([
                'status' => 'success',
                'data' => [
                    'id_producto' => $producto->getIdProducto(),
                    'nombre' => $producto->getNombre(),
                    'descripcion' => $producto->getDescripcion(),
                    'codigo' => $producto->getCodigoProd(),
                    'stock' => $producto->getStock(),
                    'imagen' => $producto->getImagen(),
                    'id_categoria' => $producto->getIdCategoria(),
                    'estado' => $producto->getEstado()
                ]
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Producto no encontrado.'
            ]);
        }
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al obtener producto: ' . $e->getMessage()
        ]);
    }
    exit();
}

function buscarProductos($nombre)
{
    global $crudProducto;
    try {
        $productos = $crudProducto->buscarPorNombre($nombre);
        $response = [
            'status' => 'success',
            'data' => []
        ];

        foreach ($productos as $producto) {
            $response['data'][] = [
                'id_producto' => $producto->getIdProducto(),
                'nombre' => $producto->getNombre(),
                'descripcion' => $producto->getDescripcion(),
                'codigo' => $producto->getCodigoProd(),
                'stock' => $producto->getStock(),
                'imagen' => $producto->getImagen(),
                'categoria' => $crudProducto->obtenerNombreCategoriaPorId($producto->getIdCategoria()),
                'estado' => $producto->getEstado()
            ];
        }

        echo json_encode($response);
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al buscar productos: ' . $e->getMessage()
        ]);
    }
    exit();
}
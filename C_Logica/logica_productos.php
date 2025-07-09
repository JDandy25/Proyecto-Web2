<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/Proyecto-Web2/C_Datos/crud_productos.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/Proyecto-Web2/C_Entidad/class_productos.php';

header('Content-Type: application/json');

$crudProducto = new ProductoDAO();

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        if (
            (isset($_POST['action']) && $_POST['action'] === 'actualizar') ||
            isset($_POST['id_producto_act'])
        ) {
            actualizarProducto();
        } else {
            registrarProducto();
        }
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
                case 'contarCodigoProd':
                    if (isset($_GET['prefijo'])) {
                        contarCodigoProd($_GET['prefijo']);
                    } else {
                        echo json_encode([
                            'status' => 'error',
                            'message' => 'Falta el parámetro prefijo.'
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

    error_log('ID CATEGORIA RECIBIDO: ' . print_r($_POST['id_categoria'], true));

    $producto = new Producto();

    // Usar $_POST porque el JS envía FormData
    $producto->setNombre($_POST['nombre_producto'] ?? '');
    $producto->setDescripcion($_POST['descripcion'] ?? '');
    $producto->setCodigoProd($_POST['codigo'] ?? '');
    $producto->setPrecio($_POST['precio'] ?? 0);
    $producto->setStock($_POST['stock'] ?? 0);
    $producto->setIdCategoria($_POST['id_categoria'] ?? '');
    $producto->setEstado($_POST['estado_producto'] ?? 1);

    // Manejo de imagen
    $rutaImagen = null;
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
        $nombreUnico = uniqid('prod_', true) . '.' . strtolower($ext);
        $carpetaDestino = $_SERVER['DOCUMENT_ROOT'] . '/Proyecto-Web2/imgProductos/';
        if (!is_dir($carpetaDestino)) {
            mkdir($carpetaDestino, 0777, true);
        }
        $rutaCompleta = $carpetaDestino . $nombreUnico;
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaCompleta)) {
            // Guarda la ruta relativa para usarla en el frontend
            $rutaImagen = '/Proyecto-Web2/imgProductos/' . $nombreUnico;
        }
    }
    $producto->setImagen($rutaImagen);

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
                'precio' => $producto->getPrecio(),
                'stock' => $producto->getStock(),
                'imagen' => $producto->getImagen(),
                'categoria' => $producto->getIdCategoria(),
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

    $producto = new Producto();
    $producto->setIdProducto($_POST['id_producto_act'] ?? '');
    $producto->setNombre($_POST['nombre_producto_act'] ?? '');
    $producto->setDescripcion($_POST['descripcion_act'] ?? '');
    $producto->setCodigoProd($_POST['codigo_act'] ?? '');
    $producto->setPrecio($_POST['precio_act'] ?? 0);
    $producto->setStock($_POST['stock_act'] ?? 0);
    $producto->setIdCategoria($_POST['categoria_act'] ?? '');
    $producto->setEstado($_POST['estado_producto_act'] ?? 1);

    // Imagen
    $rutaImagen = $_POST['imagen_actual'] ?? null;

    if (isset($_FILES['imagen_actualizar']) && $_FILES['imagen_actualizar']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['imagen_actualizar']['name'], PATHINFO_EXTENSION);
        $nombreUnico = uniqid('prod_', true) . '.' . strtolower($ext);
        $carpetaDestino = $_SERVER['DOCUMENT_ROOT'] . '/Proyecto-Web2/imgProductos/';
        if (!is_dir($carpetaDestino)) {
            mkdir($carpetaDestino, 0777, true);
        }
        $rutaCompleta = $carpetaDestino . $nombreUnico;
        if (move_uploaded_file($_FILES['imagen_actualizar']['tmp_name'], $rutaCompleta)) {
            $rutaImagen = '/Proyecto-Web2/imgProductos/' . $nombreUnico;
        }
    }

    $producto->setImagen($rutaImagen);

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
                    'precio' => $producto->getPrecio(),
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
                'precio' => $producto->getPrecio(),
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

function contarCodigoProd($prefijo)
{
    global $crudProducto;
    try {
        $productos = $crudProducto->contarPorCodigoProd($prefijo);
        $response = [
            'status' => 'success',
            'data' => $productos
        ];
        echo json_encode($response);
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al contar productos por código: ' . $e->getMessage()
        ]);
    }
    exit();
}
<?php
require_once $_SERVER["DOCUMENT_ROOT"] . '/Proyecto-Web2/C_Datos/crud_compras.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/Proyecto-Web2/C_Entidad/compras.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/Proyecto-Web2/C_Entidad/comprasDetalles.php';

header('Content-Type: application/json');

$crudCompra = new CompraDAO();

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        registrarCompra();
        break;

    case 'GET':
        if (isset($_GET['action'])) { 
            switch ($_GET['action']) {
                case 'listar':
                    listarCompras();
                    break;
                case 'obtener_proveedores_productos':
                    obtenerProveedoresYProductos();
                    break;
                case 'obtener_por_id':
                    obtenerCompraPorId();
                    break;
                case 'obtener_ultimos_numeros':  // <-- NUEVO CASE AQUÍ
                    obtenerUltimosNumeros();
                    break;
                default:
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Acción GET no reconocida.'
                    ]);
                    break;
            }
        } else {
            listarCompras();
        }
        break;

    case 'PUT':
        actualizarCompra();
        break;

    case 'PATCH':
        cambiarEstadoCompra();
        break;

    default:
        echo json_encode([
            'status' => 'error',
            'message' => 'Método no permitido'
        ]);
        break;
}

function registrarCompra() {
    global $crudCompra;

    $data = json_decode(file_get_contents('php://input'), true);

    $compra = new Compra();
    $compra->setTipoComprobante($data['tipoComprobante'] ?? '');
    $compra->setSerieComprobante($data['serieComprobante'] ?? '');
    $compra->setNumComprobante($data['numComprobante'] ?? '');
    $compra->setFechaHora($data['fechaHora'] ?? date('Y-m-d H:i:s'));
    $compra->setImpuesto($data['impuesto'] ?? 0);
    $compra->setTotalCompra($data['totalCompra'] ?? 0);
    $compra->setIdEmpleado($data['idEmpleado'] ?? 0);
    $compra->setIdProveedor($data['idProveedor'] ?? 0);
    $compra->setEstado($data['estado'] ?? '1');

    // Procesar detalles
    $detalles = $data['detalles'] ?? [];
    foreach ($detalles as $detalleData) {
        $detalle = new DetalleCompra();
        $detalle->setIdProducto($detalleData['idProducto'] ?? 0);
        $detalle->setCantidad($detalleData['cantidad'] ?? 0);
        $detalle->setPrecioCompra($detalleData['precioCompra'] ?? 0);
        $detalle->setPrecioVenta($detalleData['precioVenta'] ?? 0);
        
        $compra->agregarDetalle($detalle);
    }

    try {
        $idCompra = $crudCompra->insertar($compra);
        echo json_encode([
            'status' => 'success',
            'message' => 'Compra registrada correctamente',
            'id_compra' => $idCompra
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al registrar compra: ' . $e->getMessage()
        ]);
    }
}

function obtenerProveedoresYProductos() {
    global $crudCompra;

    try {
        $proveedores = $crudCompra->obtenerProveedoresActivos();
        $productos = $crudCompra->obtenerProductosActivos();

        echo json_encode([
            'status' => 'success',
            'proveedores' => $proveedores,
            'productos' => $productos
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al obtener proveedores y productos: ' . $e->getMessage()
        ]);
    }
    exit();
}

function listarCompras() {
    global $crudCompra;

    try {
        $compras = $crudCompra->obtenerTodos();
        $response = [
            'status' => 'success',
            'data' => []
        ];

        foreach ($compras as $compra) {
            $response['data'][] = [
                'id_compra' => $compra->getIdCompra(),
                'tipoComprobante' => $compra->getTipoComprobante(),
                'serieComprobante' => $compra->getSerieComprobante(),
                'numComprobante' => $compra->getNumComprobante(),
                'fechaHora' => $compra->getFechaHora(),
                'impuesto' => $compra->getImpuesto(),
                'totalCompra' => $compra->getTotalCompra(),
                'id_empleado' => $compra->getIdEmpleado(),
                'empleado' => $compra->getEmpleadoNombre(), // Asumiendo que agregaste este método
                'id_proveedor' => $compra->getIdProveedor(),
                'proveedor' => $compra->getProveedorNombre(), // Asumiendo que agregaste este método
                'estado' => $compra->getEstado()
            ];
        }

        echo json_encode($response);
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al obtener compras: ' . $e->getMessage()
        ]);
    }
}

function obtenerCompraPorId() {
    global $crudCompra;

    $id = $_GET['id'] ?? 0;

    try {
        $compra = $crudCompra->obtenerPorId($id);
        
        if (!$compra) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Compra no encontrada'
            ]);
            return;
        }

        $detalles = [];
        foreach ($compra->getDetalles() as $detalle) {
            $detalles[] = [
                'id_detalle' => $detalle->getIdDetalleCompra(),
                'id_producto' => $detalle->getIdProducto(),
                'producto' => $detalle->getProductoNombre(), // Asumiendo que agregaste este método
                'cantidad' => $detalle->getCantidad(),
                'precioCompra' => $detalle->getPrecioCompra(),
                'precioVenta' => $detalle->getPrecioVenta()
            ];
        }

        echo json_encode([
            'status' => 'success',
            'data' => [
                'compra' => [
                    'id_compra' => $compra->getIdCompra(),
                    'tipoComprobante' => $compra->getTipoComprobante(),
                    'serieComprobante' => $compra->getSerieComprobante(),
                    'numComprobante' => $compra->getNumComprobante(),
                    'fechaHora' => $compra->getFechaHora(),
                    'impuesto' => $compra->getImpuesto(),
                    'totalCompra' => $compra->getTotalCompra(),
                    'id_empleado' => $compra->getIdEmpleado(),
                    'empleado' => $compra->getEmpleadoNombre(),
                    'id_proveedor' => $compra->getIdProveedor(),
                    'proveedor' => $compra->getProveedorNombre(),
                    'estado' => $compra->getEstado()
                ],
                'detalles' => $detalles
            ]
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al obtener compra: ' . $e->getMessage()
        ]);
    }
}

function actualizarCompra() {
    global $crudCompra;

    $putData = json_decode(file_get_contents("php://input"), true);

    if ($putData === null) {
        echo json_encode([
            'status' => 'error',
            'message' => 'No se pudieron leer los datos de la solicitud'
        ]);
        return;
    }

    $compra = new Compra();
    $compra->setIdCompra($putData['id_compra'] ?? 0);
    $compra->setTipoComprobante($putData['tipoComprobante'] ?? '');
    $compra->setSerieComprobante($putData['serieComprobante'] ?? '');
    $compra->setNumComprobante($putData['numComprobante'] ?? '');
    $compra->setFechaHora($putData['fechaHora'] ?? '');
    $compra->setImpuesto($putData['impuesto'] ?? 0);
    $compra->setTotalCompra($putData['totalCompra'] ?? 0);
    $compra->setIdEmpleado($putData['id_empleado'] ?? 0);
    $compra->setIdProveedor($putData['id_proveedor'] ?? 0);
    $compra->setEstado($putData['estado'] ?? '1');

    try {
        $crudCompra->actualizar($compra);
        echo json_encode([
            'status' => 'success',
            'message' => 'Compra actualizada correctamente'
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al actualizar compra: ' . $e->getMessage()
        ]);
    }
}

function cambiarEstadoCompra() {
    global $crudCompra;

    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'] ?? 0;
    $estado = $data['estado'] ?? '0'; // Por defecto inactivo

    try {
        $crudCompra->cambiarEstado($id, $estado);
        echo json_encode([
            'status' => 'success',
            'message' => 'Estado de compra actualizado correctamente'
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al actualizar estado: ' . $e->getMessage()
        ]);
    }


    function obtenerUltimosNumeros() {
        global $crudCompras;
    try {
        // Obtener datos mediante el CRUD
        $ultimosNumeros = $crudCompras->obtenerUltimosNumerosComprobante();
        $recientes = $crudCompras->obtenerComprobantesRecientes();

        // Inicializar valores por defecto
        $respuesta = [
            'ultimaFactura' => 0,
            'ultimaBoleta' => 0
        ];

        // Procesar últimos números
        if ($ultimosNumeros) {
            foreach ($ultimosNumeros as $tipo => $datos) {
                if ($tipo === 'Factura') {
                    $respuesta['ultimaFactura'] = (int)$datos[0]['ultimo_numero'];
                } elseif ($tipo === 'Boleta') {
                    $respuesta['ultimaBoleta'] = (int)$datos[0]['ultimo_numero'];
                }
            }
        }

        // Preferir números recientes si existen
        if ($recientes) {
            foreach ($recientes as $tipo => $datos) {
                if ($tipo === 'Factura' && $datos[0]['ultimo_numero'] > $respuesta['ultimaFactura']) {
                    $respuesta['ultimaFactura'] = (int)$datos[0]['ultimo_numero'];
                } elseif ($tipo === 'Boleta' && $datos[0]['ultimo_numero'] > $respuesta['ultimaBoleta']) {
                    $respuesta['ultimaBoleta'] = (int)$datos[0]['ultimo_numero'];
                }
            }
        }

        echo json_encode([
            'status' => 'success',
            'data' => $respuesta,
            'message' => 'Números obtenidos correctamente'
        ]);

    } catch (Exception $e) {
        error_log("Error en obtenerUltimosNumeros: " . $e->getMessage());
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al obtener los últimos números',
            'error' => $e->getMessage()
        ]);
    }
}
}


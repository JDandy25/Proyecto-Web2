<?php
require_once $_SERVER["DOCUMENT_ROOT"] . '/Proyecto-Web2/C_Datos/crud_compras.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/Proyecto-Web2/C_Entidad/class_compras.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/Proyecto-Web2/C_Entidad/class_comprasDetalles.php';

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
                case 'proveedores':
                    obtenerProveedores();
                    break;
                case 'empleados':
                    obtenerEmpleados();
                    break;
                case 'productos':
                    obtenerProductos();
                    break;
                case 'obtener':
                    obtenerCompraPorId();
                    break;
                case 'ultimos_numeros':
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

// Registrar una compra con detalles
function registrarCompra() {
    global $crudCompra;

    $data = json_decode(file_get_contents("php://input"), true);

    if (!$data) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Datos no recibidos'
        ]);
        return;
    }

    // Generar serie y número de comprobante
    $tipoComprobante = $data['tipoComprobante'];
    $serieComprobante = ($tipoComprobante === 'Factura') ? 'F001' : 'B001';

    // Obtener el último número de comprobante para el tipo
    $ultimos = $crudCompra->obtenerUltimosNumerosComprobante();
    $ultimoNumero = 0;
    if ($ultimos && isset($ultimos[$tipoComprobante][0]['ultimo_numero'])) {
        $ultimoNumero = (int)$ultimos[$tipoComprobante][0]['ultimo_numero'];
    }
    $nuevoNumero = str_pad($ultimoNumero + 1, 8, '0', STR_PAD_LEFT);

    $compra = new Compra();
    $compra->setTipoComprobante($tipoComprobante);
    $compra->setSerieComprobante($serieComprobante);
    $compra->setNumComprobante($nuevoNumero);
    $compra->setFechaHora(date('Y-m-d H:i:s'));
    $compra->setIdEmpleado($data['id_empleado']);
    $compra->setIdProveedor($data['id_proveedor']);
    $compra->setEstado(1);

    // Calcular total e impuesto
    $total = 0;
    $detalles = [];
    foreach ($data['detalles'] as $detalleData) {
        $subtotal = $detalleData['cantidad'] * $detalleData['precioCompra'];
        $total += $subtotal;

        $detalle = new DetalleCompra();
        $detalle->setIdProducto($detalleData['id_producto']);
        $detalle->setCantidad($detalleData['cantidad']);
        $detalle->setPrecioCompra($detalleData['precioCompra']);
        $detalle->setPrecioVenta($detalleData['precioVenta']);
        $detalles[] = $detalle;
    }
    $impuesto = round($total * 0.18, 2); // 18% IGV Perú
    $compra->setImpuesto($impuesto);
    $compra->setTotalCompra($total);

    // Asignar detalles
    foreach ($detalles as $detalle) {
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

// Listar todas las compras con detalles
function listarCompras() {
    global $crudCompra;

    try {
        $compras = $crudCompra->obtenerTodos();
        $response = [
            'status' => 'success',
            'data' => []
        ];

        foreach ($compras as $compra) {
            $detalles = [];
            foreach ($compra->getDetalles() as $detalle) {
                $detalles[] = [
                    'id_detalleCompra' => $detalle->getIdDetalleCompra(),
                    'id_producto' => $detalle->getIdProducto(),
                    'cantidad' => $detalle->getCantidad(),
                    'precioCompra' => $detalle->getPrecioCompra(),
                    'precioVenta' => $detalle->getPrecioVenta()
                ];
            }

            $response['data'][] = [
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
                'estado' => $compra->getEstado(),
                'detalles' => $detalles
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

// Obtener proveedores activos
function obtenerProveedores() {
    global $crudCompra;
    try {
        $proveedores = $crudCompra->obtenerProveedoresActivos();
        echo json_encode([
            'status' => 'success',
            'data' => $proveedores
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al obtener proveedores: ' . $e->getMessage()
        ]);
    }
}

// Obtener proveedores activos
function obtenerEmpleados() {
    global $crudCompra;
    try {
        $empleados = $crudCompra->obtenerEmpleadosActivos();
        echo json_encode([
            'status' => 'success',
            'data' => $empleados
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al obtener proveedores: ' . $e->getMessage()
        ]);
    }
}

// Obtener productos activos (para autocompletar)
function obtenerProductos() {
    global $crudCompra;
    try {
        $productos = $crudCompra->obtenerProductosActivos();
        echo json_encode([
            'status' => 'success',
            'data' => $productos
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al obtener productos: ' . $e->getMessage()
        ]);
    }
}

// Obtener una compra por ID (con detalles)
function obtenerCompraPorId() {
    global $crudCompra;
    $id = $_GET['id'] ?? null;
    if (!$id) {
        echo json_encode([
            'status' => 'error',
            'message' => 'ID de compra no proporcionado.'
        ]);
        return;
    }
    try {
        $compra = $crudCompra->obtenerPorId($id);
        if (!$compra) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Compra no encontrada.'
            ]);
            return;
        }
        $detalles = [];
        foreach ($compra->getDetalles() as $detalle) {
            $detalles[] = [
                'id_detalleCompra' => $detalle->getIdDetalleCompra(),
                'id_producto' => $detalle->getIdProducto(),
                'cantidad' => $detalle->getCantidad(),
                'precioCompra' => $detalle->getPrecioCompra(),
                'precioVenta' => $detalle->getPrecioVenta()
            ];
        }
        echo json_encode([
            'status' => 'success',
            'data' => [
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
                'estado' => $compra->getEstado(),
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

// Cambiar estado de compra (activo/inactivo)
function cambiarEstadoCompra() {
    global $crudCompra;
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'] ?? null;
    $estado = $data['estado'] ?? null;
    if (!$id || $estado === null) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Datos insuficientes para cambiar estado.'
        ]);
        return;
    }
    try {
        $crudCompra->cambiarEstado($id, $estado);
        echo json_encode([
            'status' => 'success',
            'message' => 'Estado de compra actualizado correctamente'
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al cambiar estado: ' . $e->getMessage()
        ]);
    }
}

// Obtener últimos números de comprobante (para autogenerar)
function obtenerUltimosNumeros() {
    global $crudCompra;
    try {
        $ultimos = $crudCompra->obtenerUltimosNumerosComprobante();
        echo json_encode([
            'status' => 'success',
            'data' => $ultimos
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al obtener últimos números: ' . $e->getMessage()
        ]);
    }
}
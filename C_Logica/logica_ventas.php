
<?php
require_once $_SERVER["DOCUMENT_ROOT"] . '/Proyecto-Web2/C_Datos/crud_ventas.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/Proyecto-Web2/C_Entidad/class_ventas.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/Proyecto-Web2/C_Entidad/class_ventasDetalles.php';

header('Content-Type: application/json');

$crudVenta = new VentaDAO();

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        registrarVenta();
        break;

    case 'GET':
        if (isset($_GET['action'])) {
            switch ($_GET['action']) {
                case 'listar':
                    listarVentas();
                    break;
                case 'clientes':
                    obtenerClientes();
                    break;
                case 'productos':
                    obtenerProductos();
                    break;
                case 'obtener':
                    obtenerVentaPorId();
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
            listarVentas();
        }
        break;

    case 'PATCH':
        cambiarEstadoVenta();
        break;

    default:
        echo json_encode([
            'status' => 'error',
            'message' => 'Método no permitido'
        ]);
        break;
}

// Registrar una venta con detalles
function registrarVenta() {
    global $crudVenta;

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
    $ultimos = $crudVenta->obtenerUltimosNumerosComprobante();
    $ultimoNumero = 0;
    if ($ultimos && isset($ultimos[$tipoComprobante][0]['ultimo_numero'])) {
        $ultimoNumero = (int)$ultimos[$tipoComprobante][0]['ultimo_numero'];
    }
    $nuevoNumero = str_pad($ultimoNumero + 1, 8, '0', STR_PAD_LEFT);

    $venta = new Venta();
    $venta->setTipoComprobante($tipoComprobante);
    $venta->setSerieComprobante($serieComprobante);
    $venta->setNumComprobante($nuevoNumero);
    $venta->setFechaHora(date('Y-m-d H:i:s'));
    $venta->setIdEmpleado($data['id_empleado']);
    $venta->setIdCliente($data['id_cliente']);
    $venta->setEstado(1);

    // Calcular total e impuesto
    $total = 0;
    $detalles = [];
    foreach ($data['detalles'] as $detalleData) {
        $subtotal = $detalleData['cantidad'] * $detalleData['precio_venta'] - $detalleData['descuento'];
        $total += $subtotal;

        $detalle = new DetalleVenta();
        $detalle->setIdProducto($detalleData['id_producto']);
        $detalle->setCantidad($detalleData['cantidad']);
        $detalle->setPrecioVenta($detalleData['precio_venta']);
        $detalle->setDescuento($detalleData['descuento']);
        $detalles[] = $detalle;
    }
    $impuesto = round($total * 0.18, 2); // 18% IGV Perú
    $venta->setImpuesto($impuesto);
    $venta->setTotalVenta($total);

    // Asignar detalles
    foreach ($detalles as $detalle) {
        $venta->agregarDetalle($detalle);
    }

    try {
        $idVenta = $crudVenta->insertar($venta);
        echo json_encode([
            'status' => 'success',
            'message' => 'Venta registrada correctamente',
            'id_venta' => $idVenta
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al registrar venta: ' . $e->getMessage()
        ]);
    }
}

// Listar todas las ventas con detalles
function listarVentas() {
    global $crudVenta;

    try {
        $ventas = $crudVenta->obtenerTodos();
        $response = [
            'status' => 'success',
            'data' => []
        ];

        foreach ($ventas as $venta) {
            $detalles = [];
            foreach ($venta->getDetalles() as $detalle) {
                $detalles[] = [
                    'id_detalleVenta' => $detalle->getIdDetalleVenta(),
                    'id_producto' => $detalle->getIdProducto(),
                    'cantidad' => $detalle->getCantidad(),
                    'precio_venta' => $detalle->getPrecioVenta(),
                    'descuento' => $detalle->getDescuento()
                ];
            }

            $response['data'][] = [
                'id_venta' => $venta->getIdVenta(),
                'tipoComprobante' => $venta->getTipoComprobante(),
                'serieComprobante' => $venta->getSerieComprobante(),
                'numComprobante' => $venta->getNumComprobante(),
                'fechaHora' => $venta->getFechaHora(),
                'impuesto' => $venta->getImpuesto(),
                'totalVenta' => $venta->getTotalVenta(),
                'id_empleado' => $venta->getIdEmpleado(),
                'empleado' => $venta->getEmpleadoNombre(),
                'id_cliente' => $venta->getIdCliente(),
                'cliente' => $venta->getClienteNombre(),
                'estado' => $venta->getEstado(),
                'detalles' => $detalles
            ];
        }

        echo json_encode($response);
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al obtener ventas: ' . $e->getMessage()
        ]);
    }
}

// Obtener clientes activos
function obtenerClientes() {
    global $crudVenta;
    try {
        $clientes = $crudVenta->obtenerClientesActivos();
        echo json_encode([
            'status' => 'success',
            'data' => $clientes
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al obtener clientes: ' . $e->getMessage()
        ]);
    }
}

// Obtener productos activos (para autocompletar)
function obtenerProductos() {
    global $crudVenta;
    try {
        $productos = $crudVenta->obtenerProductosActivos();
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

// Obtener una venta por ID (con detalles)
function obtenerVentaPorId() {
    global $crudVenta;
    $id = $_GET['id'] ?? null;
    if (!$id) {
        echo json_encode([
            'status' => 'error',
            'message' => 'ID de venta no proporcionado.'
        ]);
        return;
    }
    try {
        $venta = $crudVenta->obtenerPorId($id);
        if (!$venta) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Venta no encontrada.'
            ]);
            return;
        }
        $detalles = [];
        foreach ($venta->getDetalles() as $detalle) {
            $detalles[] = [
                'id_detalleVenta' => $detalle->getIdDetalleVenta(),
                'id_producto' => $detalle->getIdProducto(),
                'cantidad' => $detalle->getCantidad(),
                'precio_venta' => $detalle->getPrecioVenta(),
                'descuento' => $detalle->getDescuento()
            ];
        }
        echo json_encode([
            'status' => 'success',
            'data' => [
                'id_venta' => $venta->getIdVenta(),
                'tipoComprobante' => $venta->getTipoComprobante(),
                'serieComprobante' => $venta->getSerieComprobante(),
                'numComprobante' => $venta->getNumComprobante(),
                'fechaHora' => $venta->getFechaHora(),
                'impuesto' => $venta->getImpuesto(),
                'totalVenta' => $venta->getTotalVenta(),
                'id_empleado' => $venta->getIdEmpleado(),
                'empleado' => $venta->getEmpleadoNombre(),
                'id_cliente' => $venta->getIdCliente(),
                'cliente' => $venta->getClienteNombre(),
                'estado' => $venta->getEstado(),
                'detalles' => $detalles
            ]
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al obtener venta: ' . $e->getMessage()
        ]);
    }
}

// Cambiar estado de venta (activo/inactivo)
function cambiarEstadoVenta() {
    global $crudVenta;
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
        $crudVenta->cambiarEstado($id, $estado);
        echo json_encode([
            'status' => 'success',
            'message' => 'Estado de venta actualizado correctamente'
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
    global $crudVenta;
    try {
        $ultimos = $crudVenta->obtenerUltimosNumerosComprobante();
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
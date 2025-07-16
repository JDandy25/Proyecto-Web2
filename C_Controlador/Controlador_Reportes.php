<?php
header('Content-Type: application/json');
require_once '../C_Entidad/class_reportes.php';

try {
    if (!isset($_GET['reporte'])) {
        echo json_encode(["error" => "No se especificó el tipo de reporte"]);
        exit;
    }

    $tipo = $_GET['reporte'];

    switch ($tipo) {

        case 'comprasPorFecha':
            $inicio = $_GET['inicio'] ?? '';
            $fin = $_GET['fin'] ?? '';

            if (empty($inicio) || empty($fin)) {
                echo json_encode(["error" => "Fechas incompletas"]);
                exit;
            }

            $datos = Reportes::comprasPorFecha($inicio, $fin);

            if (!is_array($datos)) {
                echo json_encode(["error" => "Error al obtener datos del reporte"]);
            } else {
                echo json_encode($datos);
            }
            break;

        case 'ventasPorFecha':
            $inicio = $_GET['inicio'] ?? '';
            $fin = $_GET['fin'] ?? '';

            if (empty($inicio) || empty($fin)) {
                echo json_encode(["error" => "Fechas incompletas"]);
                exit;
            }

            $datos = Reportes::ventasPorFecha($inicio, $fin);

            if (!is_array($datos)) {
                echo json_encode(["error" => "Error al obtener datos del reporte"]);
            } else {
                echo json_encode($datos);
            }
            break;

        case 'empleadoDelMes':
    $anio = $_GET['anio'] ?? '';
    $mes = $_GET['mes'] ?? '';

    if (empty($anio) || empty($mes)) {
        echo json_encode(["error" => "Año o mes no especificado"]);
        exit;
    }

    $datos = Reportes::empleadoDelMes($anio, $mes);

    if (!is_array($datos)) {
        echo json_encode(["error" => "Error al obtener datos del reporte"]);
    } else {
        echo json_encode($datos);
    }
    break;

    case 'ingresosPorDia':
    $inicio = $_GET['inicio'] ?? '';
    $fin = $_GET['fin'] ?? '';

    if (empty($inicio) || empty($fin)) {
        echo json_encode(["error" => "Fechas incompletas"]);
        exit;
    }

    $datos = Reportes::ingresosPorDiaDetallado($inicio, $fin);

    if (!is_array($datos)) {
        echo json_encode(["error" => "Error al obtener datos del reporte"]);
    } else {
        echo json_encode($datos);
    }
    break;

case 'stockMinimo':
    $datos = Reportes::stockMinimo();

    if (!is_array($datos)) {
        echo json_encode(["error" => "Error al obtener datos del reporte"]);
    } else {
        echo json_encode($datos);
    }
    break;



        default:
            echo json_encode(["error" => "Tipo de reporte no reconocido"]);
    }
} catch (Throwable $e) {
    echo json_encode(["error" => "Error inesperado: " . $e->getMessage()]);
}

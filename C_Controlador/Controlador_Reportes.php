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

            // Validar que $datos sea un array
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

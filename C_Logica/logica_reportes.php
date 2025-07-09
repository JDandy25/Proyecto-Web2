<?php
require_once '../Entidad/class_reportes.php';

if (isset($_GET['reporte']) && $_GET['reporte'] == "comprasPorFecha") {
    $inicio = $_GET['inicio'];
    $fin = $_GET['fin'];

    $datos = Reportes::comprasPorFecha($inicio, $fin);
    echo json_encode($datos);
}

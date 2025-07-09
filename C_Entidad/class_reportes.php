<?php
require_once '../C_Datos/crud_reportes.php';

class Reportes {
    public static function comprasPorFecha($inicio, $fin) {
        return crud_reportes::obtenerComprasPorFecha($inicio, $fin);
    }
}

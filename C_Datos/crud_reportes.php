<?php
require_once __DIR__ . '/conexion.php'; 

class crud_reportes {
    public static function obtenerComprasPorFecha($inicio, $fin) {
        $conexion = Db::conectar();
        $sql = "SELECT c.id_compra, c.fechaHora, CONCAT(p.nombres, ' ', p.apellidos) AS proveedor, c.total_compra
                FROM compra c
                JOIN proveedor p ON c.id_proveedor = p.id_proveedor
                WHERE DATE(c.fechaHora) BETWEEN :inicio AND :fin
                ORDER BY c.fechaHora ASC";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':inicio', $inicio);
        $stmt->bindParam(':fin', $fin);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}


?>  

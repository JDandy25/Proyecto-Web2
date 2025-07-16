<?php
require_once '../C_Datos/conexion.php';

class Reportes
{
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

    public static function ventasPorFecha($inicio, $fin) {
        $conexion = Db::conectar();
        $sql = "SELECT v.id_venta, v.fechaHora, CONCAT(c.nombre, ' ', c.apellidos) AS cliente, v.total_venta
                FROM venta v
                LEFT JOIN cliente c ON v.id_cliente = c.id_cliente
                WHERE DATE(v.fechaHora) BETWEEN :inicio AND :fin
                ORDER BY v.fechaHora ASC";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':inicio', $inicio);
        $stmt->bindParam(':fin', $fin);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function empleadoDelMes($anio, $mes) {
        $conexion = Db::conectar();
        $sql = "SELECT 
                    e.id_empleado,
                    CONCAT(e.nombre, ' ', e.apePater, ' ', e.apeMater) AS nombre_completo,
                    SUM(v.total_venta) AS total_vendido
                FROM venta v
                JOIN empleado e ON v.id_empleado = e.id_empleado
                WHERE YEAR(v.fechaHora) = :anio AND MONTH(v.fechaHora) = :mes
                AND v.estado = 1
                GROUP BY e.id_empleado
                ORDER BY total_vendido DESC
                LIMIT 1";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':anio', $anio);
        $stmt->bindParam(':mes', $mes);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function ingresosPorDiaDetallado($inicio, $fin) {
        $conexion = Db::conectar();
        $sql = "SELECT 
                    DATE(v.fechaHora) AS fecha,
                    CONCAT(e.nombre, ' ', e.apePater, ' ', e.apeMater) AS empleado,
                    COUNT(v.id_venta) AS ventas,
                    SUM(v.total_venta) AS total_vendido
                FROM venta v
                INNER JOIN empleado e ON v.id_empleado = e.id_empleado
                WHERE DATE(v.fechaHora) BETWEEN :inicio AND :fin
                GROUP BY fecha, empleado
                ORDER BY fecha ASC";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':inicio', $inicio);
        $stmt->bindParam(':fin', $fin);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

   public static function stockMinimo() {
    try {
        $conexion = Db::conectar();
        $sql = "SELECT 
                    p.id_producto, 
                    p.nombre, 
                    p.descripcion, 
                    p.codigoProd, 
                    p.stock, 
                    p.stock_minimo,
                    c.nombre AS categoria
                FROM producto p
                JOIN categoria c ON p.id_categoria = c.id_categoria
                WHERE p.stock <= p.stock_minimo";
        $stmt = $conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return ["error" => $e->getMessage()];
    }
}


}
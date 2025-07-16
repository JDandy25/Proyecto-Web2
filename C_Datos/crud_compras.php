<?php
require_once $_SERVER["DOCUMENT_ROOT"] . '/Proyecto-Web2/C_Datos/conexion.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/Proyecto-Web2/C_Entidad/class_compras.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/Proyecto-Web2/C_Entidad/class_comprasDetalles.php';

class CompraDAO
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = Db::conectar();
    }

    // Insertar compra con sus detalles (transacción)
    public function insertar(Compra $compra)
    {
        try {
            $this->conexion->beginTransaction();

            // Insertar la compra principal
            $sqlCompra = "INSERT INTO compra (
                tipoComprobante, serie_Comprobante, numComprobante, 
                fechaHora, impuesto, total_compra, 
                id_empleado, id_proveedor, estado
            ) VALUES (
                :tipoComprobante, :serieComprobante, :numComprobante, 
                :fechaHora, :impuesto, :totalCompra, 
                :idEmpleado, :idProveedor, :estado
            )";

            $stmtCompra = $this->conexion->prepare($sqlCompra);
            $stmtCompra->bindValue(':tipoComprobante', $compra->getTipoComprobante());
            $stmtCompra->bindValue(':serieComprobante', $compra->getSerieComprobante());
            $stmtCompra->bindValue(':numComprobante', $compra->getNumComprobante());
            $stmtCompra->bindValue(':fechaHora', $compra->getFechaHora());
            $stmtCompra->bindValue(':impuesto', $compra->getImpuesto());
            $stmtCompra->bindValue(':totalCompra', $compra->getTotalCompra());
            $stmtCompra->bindValue(':idEmpleado', $compra->getIdEmpleado());
            $stmtCompra->bindValue(':idProveedor', $compra->getIdProveedor());
            $stmtCompra->bindValue(':estado', $compra->getEstado());
            $stmtCompra->execute();

            // Obtener el ID de la compra insertada
            $idCompra = $this->conexion->lastInsertId();

            // Insertar los detalles de la compra
            foreach ($compra->getDetalles() as $detalle) {
                $sqlDetalle = "INSERT INTO detallecompra (
                    id_compra, id_producto, cantidad, 
                    precio_compra, precio_venta
                ) VALUES (
                    :idCompra, :idProducto, :cantidad, 
                    :precioCompra, :precioVenta
                )";

                $stmtDetalle = $this->conexion->prepare($sqlDetalle);
                $stmtDetalle->bindValue(':idCompra', $idCompra);
                $stmtDetalle->bindValue(':idProducto', $detalle->getIdProducto());
                $stmtDetalle->bindValue(':cantidad', $detalle->getCantidad());
                $stmtDetalle->bindValue(':precioCompra', $detalle->getPrecioCompra());
                $stmtDetalle->bindValue(':precioVenta', $detalle->getPrecioVenta());
                $stmtDetalle->execute();
            }

            $this->conexion->commit();
            return $idCompra;
        } catch (Exception $e) {
            $this->conexion->rollBack();
            throw $e;
        }
    }

    // Obtener compra por ID con sus detalles
    public function obtenerPorId($id)
    {
        // Obtener la compra principal
        $sqlCompra = "SELECT c.*, e.nombre as empleado, p.nombre as proveedor 
                     FROM compra c
                     LEFT JOIN empleado e ON c.id_empleado = e.id_empleado
                     LEFT JOIN proveedor p ON c.id_proveedor = p.id_proveedor
                     WHERE c.id_compra = :idCompra";
        
        $stmtCompra = $this->conexion->prepare($sqlCompra);
        $stmtCompra->bindValue(':idCompra', $id);
        $stmtCompra->execute();
        $compraData = $stmtCompra->fetch(PDO::FETCH_ASSOC);

        if (!$compraData) {
            return null;
        }

        // Mapear datos de la compra principal
        $compra = new Compra();
        $compra->setIdCompra($compraData['id_compra']);
        $compra->setTipoComprobante($compraData['tipoComprobante']);
        $compra->setSerieComprobante($compraData['serie_Comprobante']);
        $compra->setNumComprobante($compraData['numComprobante']);
        $compra->setFechaHora($compraData['fechaHora']);
        $compra->setImpuesto($compraData['impuesto']);
        $compra->setTotalCompra($compraData['total_compra']);
        $compra->setIdEmpleado($compraData['id_empleado']);
        $compra->setIdProveedor($compraData['id_proveedor']);
        $compra->setEstado($compraData['estado']);

        // Obtener los detalles de la compra
        $sqlDetalle = "SELECT dc.*, pr.nombre as producto 
                      FROM detallecompra dc
                      LEFT JOIN producto pr ON dc.id_producto = pr.id_producto
                      WHERE dc.id_compra = :idCompra";
        
        $stmtDetalle = $this->conexion->prepare($sqlDetalle);
        $stmtDetalle->bindValue(':idCompra', $id);
        $stmtDetalle->execute();
        $detallesData = $stmtDetalle->fetchAll(PDO::FETCH_ASSOC);

        // Mapear los detalles
        foreach ($detallesData as $detalleData) {
            $detalle = new DetalleCompra();
            $detalle->setIdDetalleCompra($detalleData['id_detalleCompra']);
            $detalle->setIdCompra($detalleData['id_compra']);
            $detalle->setIdProducto($detalleData['id_producto']);
            $detalle->setCantidad($detalleData['cantidad']);
            $detalle->setPrecioCompra($detalleData['precio_compra']);
            $detalle->setPrecioVenta($detalleData['precio_venta']);
            
            $compra->agregarDetalle($detalle);
        }

        return $compra;
    }

    // Obtener todas las compras (sin detalles)
public function obtenerTodos()
{
    $sql = "SELECT 
                c.id_compra, 
                c.tipoComprobante, 
                c.serie_Comprobante,
                c.numComprobante, 
                c.fechaHora, 
                c.impuesto,
                c.total_compra,
                c.estado,
                c.id_empleado,
                e.nombre as empleado, 
                c.id_proveedor,
                p.nombres as proveedor, 
                pt.id_producto,
                pt.nombre as producto, 
                dc.id_detalleCompra,
                dc.cantidad, 
                dc.precio_compra, 
                dc.precio_venta
            FROM compra c 
            INNER JOIN detallecompra dc ON c.id_compra = dc.id_compra
            INNER JOIN empleado e ON c.id_empleado = e.id_empleado
            INNER JOIN proveedor p ON c.id_proveedor = p.id_proveedor
            INNER JOIN producto pt ON dc.id_producto = pt.id_producto
            WHERE c.estado = 1
            ORDER BY c.id_compra DESC";
    
    $stmt = $this->conexion->prepare($sql);
    $stmt->execute();
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $compras = [];

    foreach ($resultados as $fila) {
        $id_compra = $fila['id_compra'];

        // Si aún no se creó esta compra, la creamos
        if (!isset($compras[$id_compra])) {
            $compra = new Compra();
            $compra->setIdCompra($fila['id_compra']);
            $compra->setTipoComprobante($fila['tipoComprobante']);
            $compra->setSerieComprobante($fila['serie_Comprobante']);
            $compra->setNumComprobante($fila['numComprobante']);
            $compra->setFechaHora($fila['fechaHora']);
            $compra->setImpuesto($fila['impuesto']);
            $compra->setTotalCompra($fila['total_compra']);
            $compra->setIdEmpleado($fila['id_empleado']);
            $compra->setEmpleadoNombre($fila['empleado']);
            $compra->setIdProveedor($fila['id_proveedor']);
            $compra->setProveedorNombre($fila['proveedor']);
            $compra->setEstado($fila['estado']);

            $compras[$id_compra] = $compra;
        }

        // Crear detalle y asociarlo a la compra
        $detalle = new DetalleCompra();
        $detalle->setIdDetalleCompra($fila['id_detalleCompra']);
        $detalle->setIdCompra($id_compra);
        $detalle->setIdProducto($fila['id_producto']);
        $detalle->setCantidad($fila['cantidad']);
        $detalle->setPrecioCompra($fila['precio_compra']);
        $detalle->setPrecioVenta($fila['precio_venta']);

        $compras[$id_compra]->agregarDetalle($detalle);
    }

    // Devolver solo los objetos Compra
    return array_values($compras);
}

    // Actualizar compra (sin detalles para simplificar)
    public function actualizar(Compra $compra)
    {
        $sql = "UPDATE compra SET
                tipoComprobante = :tipoComprobante,
                serie_Comprobante = :serieComprobante,
                numComprobante = :numComprobante,
                fechaHora = :fechaHora,
                impuesto = :impuesto,
                total_compra = :totalCompra,
                id_empleado = :idEmpleado,
                id_proveedor = :idProveedor,
                estado = :estado
                WHERE id_compra = :idCompra";
        
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindValue(':tipoComprobante', $compra->getTipoComprobante());
        $stmt->bindValue(':serieComprobante', $compra->getSerieComprobante());
        $stmt->bindValue(':numComprobante', $compra->getNumComprobante());
        $stmt->bindValue(':fechaHora', $compra->getFechaHora());
        $stmt->bindValue(':impuesto', $compra->getImpuesto());
        $stmt->bindValue(':totalCompra', $compra->getTotalCompra());
        $stmt->bindValue(':idEmpleado', $compra->getIdEmpleado());
        $stmt->bindValue(':idProveedor', $compra->getIdProveedor());
        $stmt->bindValue(':estado', $compra->getEstado());
        $stmt->bindValue(':idCompra', $compra->getIdCompra());
        
        return $stmt->execute();
    }

    // Cambiar estado de la compra (anular)
    public function cambiarEstado($id, $estado)
    {
        $sql = "UPDATE compra SET estado = :estado WHERE id_compra = :idCompra";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindValue(':estado', $estado);
        $stmt->bindValue(':idCompra', $id);
        return $stmt->execute();
    }

    // Métodos adicionales útiles
    public function obtenerProveedoresActivos()
    {
        $sql = "SELECT id_proveedor, nombres FROM proveedor WHERE estado = 1 ORDER BY nombres ASC";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerEmpleadosActivos()
    {
        $sql = "SELECT id_empleado, nombre FROM empleado WHERE estado = 1 ORDER BY nombre ASC";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerProductosActivos()
    {
        $sql = "SELECT id_producto, nombre FROM producto WHERE estado = 1 ORDER BY nombre ASC";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerUltimosNumerosComprobante() {
        try {
            $sql = "SELECT 
                        tipoComprobante,
                        MAX(CAST(SUBSTRING_INDEX(numComprobante, '-', -1) AS UNSIGNED)) as ultimo_numero
                    FROM compra
                    WHERE tipoComprobante IN ('Factura', 'Boleta')
                    GROUP BY tipoComprobante";
            
            $stmt = $this->conexion->query($sql);
            return $stmt->fetchAll(PDO::FETCH_GROUP|PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Error en CrudCompras::obtenerUltimosNumerosComprobante: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerComprobantesRecientes() {
        try {
            $sql = "SELECT 
                        tipoComprobante,
                        MAX(CAST(SUBSTRING_INDEX(numComprobante, '-', -1) AS UNSIGNED)) as ultimo_numero
                    FROM compra
                    WHERE tipoComprobante IN ('Factura', 'Boleta')
                    AND fechaHora >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
                    GROUP BY tipoComprobante";
            
            $stmt = $this->conexion->query($sql);
            return $stmt->fetchAll(PDO::FETCH_GROUP|PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Error en CrudCompras::obtenerComprobantesRecientes: " . $e->getMessage());
            return false;
        }
    }
}
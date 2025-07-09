
<?php
require_once $_SERVER["DOCUMENT_ROOT"] . '/Proyecto-Web2/C_Datos/conexion.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/Proyecto-Web2/C_Entidad/class_ventas.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/Proyecto-Web2/C_Entidad/class_ventasDetalles.php';

class VentaDAO
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = Db::conectar();
    }

    // Insertar venta con sus detalles (transacción)
    public function insertar(Venta $venta)
    {
        try {
            $this->conexion->beginTransaction();

            // Insertar la venta principal
            $sqlVenta = "INSERT INTO venta (
                tipoComprobante, serie_Comprobante, numComprobante, 
                fechaHora, impuesto, total_venta, 
                id_empleado, id_cliente, estado
            ) VALUES (
                :tipoComprobante, :serieComprobante, :numComprobante, 
                :fechaHora, :impuesto, :totalVenta, 
                :idEmpleado, :idCliente, :estado
            )";

            $stmtVenta = $this->conexion->prepare($sqlVenta);
            $stmtVenta->bindValue(':tipoComprobante', $venta->getTipoComprobante());
            $stmtVenta->bindValue(':serieComprobante', $venta->getSerieComprobante());
            $stmtVenta->bindValue(':numComprobante', $venta->getNumComprobante());
            $stmtVenta->bindValue(':fechaHora', $venta->getFechaHora());
            $stmtVenta->bindValue(':impuesto', $venta->getImpuesto());
            $stmtVenta->bindValue(':totalVenta', $venta->getTotalVenta());
            $stmtVenta->bindValue(':idEmpleado', $venta->getIdEmpleado());
            $stmtVenta->bindValue(':idCliente', $venta->getIdCliente());
            $stmtVenta->bindValue(':estado', $venta->getEstado());
            $stmtVenta->execute();

            // Obtener el ID de la venta insertada
            $idVenta = $this->conexion->lastInsertId();

            // Insertar los detalles de la venta
            foreach ($venta->getDetalles() as $detalle) {
                $sqlDetalle = "INSERT INTO detalleventa (
                    id_venta, id_producto, cantidad, 
                    precio_venta, descuento
                ) VALUES (
                    :idVenta, :idProducto, :cantidad, 
                    :precioVenta, :descuento
                )";

                $stmtDetalle = $this->conexion->prepare($sqlDetalle);
                $stmtDetalle->bindValue(':idVenta', $idVenta);
                $stmtDetalle->bindValue(':idProducto', $detalle->getIdProducto());
                $stmtDetalle->bindValue(':cantidad', $detalle->getCantidad());
                $stmtDetalle->bindValue(':precioVenta', $detalle->getPrecioVenta());
                $stmtDetalle->bindValue(':descuento', $detalle->getDescuento());
                $stmtDetalle->execute();
            }

            $this->conexion->commit();
            return $idVenta;
        } catch (Exception $e) {
            $this->conexion->rollBack();
            throw $e;
        }
    }

    // Obtener venta por ID con sus detalles
    public function obtenerPorId($id)
    {
        // Obtener la venta principal
        $sqlVenta = "SELECT v.*, e.nombre as empleado, c.nombres as cliente 
                     FROM venta v
                     LEFT JOIN empleado e ON v.id_empleado = e.id_empleado
                     LEFT JOIN cliente c ON v.id_cliente = c.id_cliente
                     WHERE v.id_venta = :idVenta";
        
        $stmtVenta = $this->conexion->prepare($sqlVenta);
        $stmtVenta->bindValue(':idVenta', $id);
        $stmtVenta->execute();
        $ventaData = $stmtVenta->fetch(PDO::FETCH_ASSOC);

        if (!$ventaData) {
            return null;
        }

        // Mapear datos de la venta principal
        $venta = new Venta();
        $venta->setIdVenta($ventaData['id_venta']);
        $venta->setTipoComprobante($ventaData['tipoComprobante']);
        $venta->setSerieComprobante($ventaData['serie_Comprobante']);
        $venta->setNumComprobante($ventaData['numComprobante']);
        $venta->setFechaHora($ventaData['fechaHora']);
        $venta->setImpuesto($ventaData['impuesto']);
        $venta->setTotalVenta($ventaData['total_venta']);
        $venta->setIdEmpleado($ventaData['id_empleado']);
        $venta->setEmpleadoNombre($ventaData['empleado']);
        $venta->setIdCliente($ventaData['id_cliente']);
        $venta->setClienteNombre($ventaData['cliente']);
        $venta->setEstado($ventaData['estado']);

        // Obtener los detalles de la venta
        $sqlDetalle = "SELECT dv.*, p.nombre as producto 
                      FROM detalleventa dv
                      LEFT JOIN producto p ON dv.id_producto = p.id_producto
                      WHERE dv.id_venta = :idVenta";
        
        $stmtDetalle = $this->conexion->prepare($sqlDetalle);
        $stmtDetalle->bindValue(':idVenta', $id);
        $stmtDetalle->execute();
        $detallesData = $stmtDetalle->fetchAll(PDO::FETCH_ASSOC);

        // Mapear los detalles
        foreach ($detallesData as $detalleData) {
            $detalle = new DetalleVenta();
            $detalle->setIdDetalleVenta($detalleData['id_detalleVenta']);
            $detalle->setIdVenta($detalleData['id_venta']);
            $detalle->setIdProducto($detalleData['id_producto']);
            $detalle->setCantidad($detalleData['cantidad']);
            $detalle->setPrecioVenta($detalleData['precio_venta']);
            $detalle->setDescuento($detalleData['descuento']);
            $venta->agregarDetalle($detalle);
        }

        return $venta;
    }

    // Obtener todas las ventas (con detalles)
    public function obtenerTodos()
    {
        $sql = "SELECT 
                    v.id_venta, 
                    v.tipoComprobante, 
                    v.serie_Comprobante,
                    v.numComprobante, 
                    v.fechaHora, 
                    v.impuesto,
                    v.total_venta,
                    v.estado,
                    v.id_empleado,
                    e.nombre as empleado, 
                    v.id_cliente,
                    c.nombre as cliente, 
                    dv.id_detalleVenta,
                    dv.id_producto,
                    p.nombre as producto, 
                    dv.cantidad, 
                    dv.precioVenta, 
                    dv.descuento
                FROM venta v 
                INNER JOIN detalleventa dv ON v.id_venta = dv.id_venta
                INNER JOIN empleado e ON v.id_empleado = e.id_empleado
                INNER JOIN cliente c ON v.id_cliente = c.id_cliente
                INNER JOIN producto p ON dv.id_producto = p.id_producto
                WHERE v.estado = 1
                ORDER BY v.id_venta DESC";
        
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $ventas = [];

        foreach ($resultados as $fila) {
            $id_venta = $fila['id_venta'];

            // Si aún no se creó esta venta, la creamos
            if (!isset($ventas[$id_venta])) {
                $venta = new Venta();
                $venta->setIdVenta($fila['id_venta']);
                $venta->setTipoComprobante($fila['tipoComprobante']);
                $venta->setSerieComprobante($fila['serie_Comprobante']);
                $venta->setNumComprobante($fila['numComprobante']);
                $venta->setFechaHora($fila['fechaHora']);
                $venta->setImpuesto($fila['impuesto']);
                $venta->setTotalVenta($fila['total_venta']);
                $venta->setIdEmpleado($fila['id_empleado']);
                $venta->setEmpleadoNombre($fila['empleado']);
                $venta->setIdCliente($fila['id_cliente']);
                $venta->setClienteNombre($fila['cliente']);
                $venta->setEstado($fila['estado']);

                $ventas[$id_venta] = $venta;
            }

            // Crear detalle y asociarlo a la venta
            $detalle = new DetalleVenta();
            $detalle->setIdDetalleVenta($fila['id_detalleVenta']);
            $detalle->setIdVenta($id_venta);
            $detalle->setIdProducto($fila['id_producto']);
            $detalle->setCantidad($fila['cantidad']);
            $detalle->setPrecioVenta($fila['precioVenta']);
            $detalle->setDescuento($fila['descuento']);

            $ventas[$id_venta]->agregarDetalle($detalle);
        }

        // Devolver solo los objetos Venta
        return array_values($ventas);
    }

    // Actualizar venta (sin detalles para simplificar)
    public function actualizar(Venta $venta)
    {
        $sql = "UPDATE venta SET
                tipoComprobante = :tipoComprobante,
                serie_Comprobante = :serieComprobante,
                numComprobante = :numComprobante,
                fechaHora = :fechaHora,
                impuesto = :impuesto,
                total_venta = :totalVenta,
                id_empleado = :idEmpleado,
                id_cliente = :idCliente,
                estado = :estado
                WHERE id_venta = :idVenta";
        
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindValue(':tipoComprobante', $venta->getTipoComprobante());
        $stmt->bindValue(':serieComprobante', $venta->getSerieComprobante());
        $stmt->bindValue(':numComprobante', $venta->getNumComprobante());
        $stmt->bindValue(':fechaHora', $venta->getFechaHora());
        $stmt->bindValue(':impuesto', $venta->getImpuesto());
        $stmt->bindValue(':totalVenta', $venta->getTotalVenta());
        $stmt->bindValue(':idEmpleado', $venta->getIdEmpleado());
        $stmt->bindValue(':idCliente', $venta->getIdCliente());
        $stmt->bindValue(':estado', $venta->getEstado());
        $stmt->bindValue(':idVenta', $venta->getIdVenta());
        
        return $stmt->execute();
    }

    // Cambiar estado de la venta (anular)
    public function cambiarEstado($id, $estado)
    {
        $sql = "UPDATE venta SET estado = :estado WHERE id_venta = :idVenta";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindValue(':estado', $estado);
        $stmt->bindValue(':idVenta', $id);
        return $stmt->execute();
    }

    // Métodos adicionales útiles
    public function obtenerClientesActivos()
    {
        $sql = "SELECT id_cliente, nombre FROM cliente WHERE estado = 1 ORDER BY nombre ASC";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerProductosActivos()
    {
        $sql = "SELECT id_producto, nombre, precio FROM producto WHERE estado = 1 ORDER BY nombre ASC";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerUltimosNumerosComprobante() {
        try {
            $sql = "SELECT 
                        tipoComprobante,
                        MAX(CAST(SUBSTRING_INDEX(numComprobante, '-', -1) AS UNSIGNED)) as ultimo_numero
                    FROM venta
                    WHERE tipoComprobante IN ('Factura', 'Boleta')
                    GROUP BY tipoComprobante";
            
            $stmt = $this->conexion->query($sql);
            return $stmt->fetchAll(PDO::FETCH_GROUP|PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Error en CrudVentas::obtenerUltimosNumerosComprobante: " . $e->getMessage());
            return false;
        }
    }
}
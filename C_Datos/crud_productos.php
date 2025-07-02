<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/Proyecto-Web2/C_Datos/conexion.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/Proyecto-Web2/C_Entidad/class_producto.php';

class ProductoDAO
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = Db::conectar();
    }

    public function insertar(Producto $producto)
    {
        $sql = "INSERT INTO producto (
                    nombre, descripcion, codigoProd, stock, imagen, id_categoria, estado
                ) 
                VALUES (
                    :nombre, :descripcion, :codigoProd, :stock, :imagen, :id_categoria, :estado
                )";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindValue(':nombre', $producto->getNombre());
        $stmt->bindValue(':descripcion', $producto->getDescripcion());
        $stmt->bindValue(':codigoProd', $producto->getCodigoProd());
        $stmt->bindValue(':stock', $producto->getStock());
        $stmt->bindValue(':imagen', $producto->getImagen());
        $stmt->bindValue(':id_categoria', $producto->getIdCategoria());
        $stmt->bindValue(':estado', $producto->getEstado());
        return $stmt->execute();
    }

    public function obtenerPorId($id)
    {
        $sql = "SELECT p.*, c.nombre as nombre_categoria 
                FROM producto p
                LEFT JOIN categoria c ON p.id_categoria = c.id_categoria
                WHERE p.id_producto = :id_producto";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindValue(':id_producto', $id);
        $stmt->execute();
        $result = $stmt->fetch();

        if ($result) {
            $producto = new Producto();
            $producto->setIdProducto($result['id_producto']);
            $producto->setNombre($result['nombre']);
            $producto->setDescripcion($result['descripcion']);
            $producto->setCodigoProd($result['codigoProd']);
            $producto->setStock($result['stock']);
            $producto->setImagen($result['imagen']);
            $producto->setIdCategoria($result['id_categoria']);
            $producto->setEstado($result['estado']);
            return $producto;
        } else {
            return null;
        }
    }

    public function obtenerTodos()
    {
        $sql = "SELECT 
                    p.id_producto,
                    p.nombre,
                    p.descripcion,
                    p.codigoProd,
                    p.stock,
                    p.imagen,
                    c.nombre AS categoria,
                    p.estado
                FROM producto p
                LEFT JOIN categoria c ON p.id_categoria = c.id_categoria
                WHERE p.estado = 1
                ORDER BY p.id_producto ASC";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $productos = [];
        foreach ($results as $result) {
            $producto = new Producto();
            $producto->setIdProducto($result['id_producto']);
            $producto->setNombre($result['nombre']);
            $producto->setDescripcion($result['descripcion']);
            $producto->setCodigoProd($result['codigoProd']);
            $producto->setStock($result['stock']);
            $producto->setImagen($result['imagen']);
            $producto->setIdCategoria($result['id_categoria']);
            $producto->setEstado($result['estado']);
            $productos[] = $producto;
        }

        return $productos;
    }

    public function actualizar(Producto $producto)
    {
        $sql = "UPDATE producto 
                SET nombre = :nombre,
                    descripcion = :descripcion,
                    codigoProd = :codigoProd,
                    stock = :stock,
                    imagen = :imagen,
                    id_categoria = :id_categoria,
                    estado = :estado
                WHERE id_producto = :id_producto";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindValue(':nombre', $producto->getNombre());
        $stmt->bindValue(':descripcion', $producto->getDescripcion());
        $stmt->bindValue(':codigoProd', $producto->getCodigoProd());
        $stmt->bindValue(':stock', $producto->getStock());
        $stmt->bindValue(':imagen', $producto->getImagen());
        $stmt->bindValue(':id_categoria', $producto->getIdCategoria());
        $stmt->bindValue(':estado', $producto->getEstado());
        $stmt->bindValue(':id_producto', $producto->getIdProducto());
        return $stmt->execute();
    }

    public function eliminar($id)
    {
        // Eliminación lógica (cambio de estado)
        $sql = "UPDATE producto SET estado = 0 WHERE id_producto = :id_producto";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindValue(':id_producto', $id);
        return $stmt->execute();
    }

    public function actualizarEstado($id, $estado)
    {
        $sql = "UPDATE producto SET estado = :estado WHERE id_producto = :id_producto";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindValue(':estado', $estado);
        $stmt->bindValue(':id_producto', $id);
        return $stmt->execute();
    }

    public function obtenerCategorias()
    {
        $sql = "SELECT id_categoria, nombre FROM categoria WHERE estado = 1 ORDER BY nombre ASC";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerNombreCategoriaPorId($id_categoria)
    {
        $sql = "SELECT nombre FROM categoria WHERE id_categoria = :id_categoria LIMIT 1";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindValue(':id_categoria', $id_categoria);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result ? $result['nombre'] : null;
    }

    public function obtenerUltimoProductoYCategoria()
    {
        $sql = "SELECT p.id_producto, c.nombre as nombre_categoria
                FROM producto p
                LEFT JOIN categoria c ON p.id_categoria = c.id_categoria
                ORDER BY p.id_producto DESC LIMIT 1";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result : null;
    }

    public function buscarPorNombre($nombre)
    {
        $sql = "SELECT * FROM producto WHERE nombre LIKE :nombre AND estado = 1";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindValue(':nombre', '%' . $nombre . '%');
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $productos = [];
        foreach ($results as $result) {
            $producto = new Producto();
            $producto->setIdProducto($result['id_producto']);
            $producto->setNombre($result['nombre']);
            $producto->setDescripcion($result['descripcion']);
            $producto->setCodigoProd($result['codigoProd']);
            $producto->setStock($result['stock']);
            $producto->setImagen($result['imagen']);
            $producto->setIdCategoria($result['id_categoria']);
            $producto->setEstado($result['estado']);
            $productos[] = $producto;
        }

        return $productos;
    }
}
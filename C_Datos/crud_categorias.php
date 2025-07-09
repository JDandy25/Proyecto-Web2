<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/Proyecto-Web2/C_Datos/conexion.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/Proyecto-Web2/C_Entidad/class_categorias.php';

class CategoriaDAO
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = Db::conectar();
    }

    public function insertar(Categoria $categoria)
    {
        $sql = "INSERT INTO categoria (
                    nombre, descripcion, estado
                ) 
                VALUES (
                    :nombre, :descripcion, :estado
                )";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindValue(':nombre', $categoria->getNombre());
        $stmt->bindValue(':descripcion', $categoria->getDescripcion());
        $stmt->bindValue(':estado', $categoria->getEstado());
        return $stmt->execute();
    }

    public function obtenerPorId($id)
    {
        $sql = "SELECT * FROM categoria WHERE id_categoria = :id_categoria";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindValue(':id_categoria', $id);
        $stmt->execute();
        $result = $stmt->fetch();

        if ($result) {
            $categoria = new Categoria();
            $categoria->setIdCategoria($result['id_categoria']);
            $categoria->setNombre($result['nombre']);
            $categoria->setDescripcion($result['descripcion']);
            $categoria->setEstado($result['estado']);
            return $categoria;
        } else {
            return null;
        }
    }

    public function obtenerTodos()
    {
        $sql = "SELECT * FROM categoria WHERE estado = 1 ORDER BY id_categoria ASC";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $categorias = [];
        foreach ($results as $result) {
            $categoria = new Categoria();
            $categoria->setIdCategoria($result['id_categoria']);
            $categoria->setNombre($result['nombre']);
            $categoria->setDescripcion($result['descripcion']);
            $categoria->setEstado($result['estado']);
            $categorias[] = $categoria;
        }
        return $categorias;
    }

    public function actualizar(Categoria $categoria)
    {
        $sql = "UPDATE categoria 
                SET nombre = :nombre,
                    descripcion = :descripcion,
                    estado = :estado
                WHERE id_categoria = :id_categoria";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindValue(':nombre', $categoria->getNombre());
        $stmt->bindValue(':descripcion', $categoria->getDescripcion());
        $stmt->bindValue(':estado', $categoria->getEstado());
        $stmt->bindValue(':id_categoria', $categoria->getIdCategoria());
        return $stmt->execute();
    }

    public function eliminar($id)
    {
        $sql = "DELETE FROM categoria WHERE id_categoria = :id_categoria";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindValue(':id_categoria', $id);
        return $stmt->execute();
    }

    public function actualizarEstado($id, $estado)
    {
        $sql = "UPDATE categoria SET estado = :estado WHERE id_categoria = :id_categoria";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindValue(':estado', $estado);
        $stmt->bindValue(':id_categoria', $id);
        return $stmt->execute();
    }
}

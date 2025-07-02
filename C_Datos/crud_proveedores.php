<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/Proyecto-Web2/C_Datos/conexion.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/Proyecto-Web2/C_Entidad/class_proveedores.php';

class ProveedorDAO
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = Db::conectar();
    }

    public function insertar(Proveedor $proveedor)
    {
        $sql = "INSERT INTO proveedor (
                    nombres, apellidos, RUC, telefono, correo, estado
                ) 
                VALUES (
                    :nombres, :apellidos, :RUC, :telefono, :correo, :estado
                )";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindValue(':nombres', $proveedor->getNombres());
        $stmt->bindValue(':apellidos', $proveedor->getApellidos());
        $stmt->bindValue(':RUC', $proveedor->getRUC());
        $stmt->bindValue(':telefono', $proveedor->getTelefono());
        $stmt->bindValue(':correo', $proveedor->getCorreo());
        $stmt->bindValue(':estado', $proveedor->getEstado());
        return $stmt->execute();
    }

    public function obtenerPorId($id)
    {
        $sql = "SELECT * FROM proveedor WHERE id_proveedor = :id_proveedor";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindValue(':id_proveedor', $id);
        $stmt->execute();
        $result = $stmt->fetch();

        if ($result) {
            $proveedor = new Proveedor();
            $proveedor->setIdProveedor($result['id_proveedor']);
            $proveedor->setNombres($result['nombres']);
            $proveedor->setApellidos($result['apellidos']);
            $proveedor->setRUC($result['RUC']);
            $proveedor->setTelefono($result['telefono']);
            $proveedor->setCorreo($result['correo']);
            $proveedor->setEstado($result['estado']);
            return $proveedor;
        } else {
            return null;
        }
    }

    public function obtenerTodos()
    {
        $sql = "SELECT * FROM proveedor WHERE estado = 1 ORDER BY id_proveedor ASC";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $proveedores = [];
        foreach ($results as $result) {
            $proveedor = new Proveedor();
            $proveedor->setIdProveedor($result['id_proveedor']);
            $proveedor->setNombres($result['nombres']);
            $proveedor->setApellidos($result['apellidos']);
            $proveedor->setRUC($result['RUC']);
            $proveedor->setTelefono($result['telefono']);
            $proveedor->setCorreo($result['correo']);
            $proveedor->setEstado($result['estado']);
            $proveedores[] = $proveedor;
        }
        return $proveedores;
    }

    public function actualizar(Proveedor $proveedor)
    {
        $sql = "UPDATE proveedor 
                SET nombres = :nombres,
                    apellidos = :apellidos,
                    RUC = :RUC,
                    telefono = :telefono,
                    correo = :correo,
                    estado = :estado
                WHERE id_proveedor = :id_proveedor";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindValue(':nombres', $proveedor->getNombres());
        $stmt->bindValue(':apellidos', $proveedor->getApellidos());
        $stmt->bindValue(':RUC', $proveedor->getRUC());
        $stmt->bindValue(':telefono', $proveedor->getTelefono());
        $stmt->bindValue(':correo', $proveedor->getCorreo());
        $stmt->bindValue(':estado', $proveedor->getEstado());
        $stmt->bindValue(':id_proveedor', $proveedor->getIdProveedor());
        return $stmt->execute();
    }

    public function eliminar($id)
    {
        $sql = "DELETE FROM proveedor WHERE id_proveedor = :id_proveedor";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindValue(':id_proveedor', $id);
        return $stmt->execute();
    }

    public function actualizarEstado($id, $estado)
    {
        $sql = "UPDATE proveedor SET estado = :estado WHERE id_proveedor = :id_proveedor";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindValue(':estado', $estado);
        $stmt->bindValue(':id_proveedor', $id);
        return $stmt->execute();
    }
}

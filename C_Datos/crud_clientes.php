<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/Proyecto-Web2/C_Datos/conexion.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/Proyecto-Web2/C_Entidad/class_clientes.php';

class ClienteDAO
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = Db::conectar();
    }

    public function insertar(Cliente $cliente)
    {
        $sql = "INSERT INTO cliente (
                    nombre, apellidos, dni, correo, telefono, direccion, estado
                ) 
                VALUES (
                    :nombre, :apellidos, :dni, :correo, :telefono, :direccion, :estado
                )";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindValue(':nombre', $cliente->getNombre());
        $stmt->bindValue(':apellidos', $cliente->getApellidos());
        $stmt->bindValue(':dni', $cliente->getDni());
        $stmt->bindValue(':correo', $cliente->getCorreo());
        $stmt->bindValue(':telefono', $cliente->getTelefono());
        $stmt->bindValue(':direccion', $cliente->getDireccion());
        $stmt->bindValue(':estado', $cliente->getEstado());
        return $stmt->execute();
    }

    public function obtenerPorId($id)
    {
        $sql = "SELECT * FROM cliente WHERE id_cliente = :id_cliente";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindValue(':id_cliente', $id);
        $stmt->execute();
        $result = $stmt->fetch();

        if ($result) {
            $cliente = new Cliente();
            $cliente->setIdCliente($result['id_cliente']);
            $cliente->setNombre($result['nombre']);
            $cliente->setApellidos($result['apellidos']);
            $cliente->setDni($result['dni']);
            $cliente->setCorreo($result['correo']);
            $cliente->setTelefono($result['telefono']);
            $cliente->setDireccion($result['direccion']);
            $cliente->setEstado($result['estado']);
            return $cliente;
        } else {
            return null;
        }
    }

    public function obtenerTodos()
    {
        $sql = "SELECT * FROM cliente WHERE estado = 1 ORDER BY id_cliente ASC";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $clientes = [];
        foreach ($results as $result) {
            $cliente = new Cliente();
            $cliente->setIdCliente($result['id_cliente']);
            $cliente->setNombre($result['nombre']);
            $cliente->setApellidos($result['apellidos']);
            $cliente->setDni($result['dni']);
            $cliente->setCorreo($result['correo']);
            $cliente->setTelefono($result['telefono']);
            $cliente->setDireccion($result['direccion']);
            $cliente->setEstado($result['estado']);
            $clientes[] = $cliente;
        }
        return $clientes;
    }

    public function actualizar(Cliente $cliente)
    {
        $sql = "UPDATE cliente 
                SET nombre = :nombre,
                    apellidos = :apellidos,
                    dni = :dni,
                    correo = :correo,
                    telefono = :telefono,
                    direccion = :direccion,
                    estado = :estado
                WHERE id_cliente = :id_cliente";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindValue(':nombre', $cliente->getNombre());
        $stmt->bindValue(':apellidos', $cliente->getApellidos());
        $stmt->bindValue(':dni', $cliente->getDni());
        $stmt->bindValue(':correo', $cliente->getCorreo());
        $stmt->bindValue(':telefono', $cliente->getTelefono());
        $stmt->bindValue(':direccion', $cliente->getDireccion());
        $stmt->bindValue(':estado', $cliente->getEstado());
        $stmt->bindValue(':id_cliente', $cliente->getIdCliente());
        return $stmt->execute();
    }

    public function eliminar($id)
    {
        // Eliminación física (puedes cambiar a lógica si lo prefieres)
        $sql = "DELETE FROM cliente WHERE id_cliente = :id_cliente";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindValue(':id_cliente', $id);
        return $stmt->execute();
    }

    public function actualizarEstado($id, $estado)
    {
        $sql = "UPDATE cliente SET estado = :estado WHERE id_cliente = :id_cliente";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindValue(':estado', $estado);
        $stmt->bindValue(':id_cliente', $id);
        return $stmt->execute();
    }
}

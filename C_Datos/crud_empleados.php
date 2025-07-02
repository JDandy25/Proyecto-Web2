<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/Proyecto-Web2/C_Datos/conexion.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/Proyecto-Web2/C_Entidad/class_empleados.php';

class EmpleadoDAO
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = Db::conectar();
    }

// En tu CrudEmpleado.php
public function insertar(Empleado $empleado)
{
    $sql = "INSERT INTO empleado (
                nombre, apePater, apeMater, dni, direccion, 
                telefono, correo, id_usuario, id_turno, estado 
            ) 
            VALUES (
                :nombre, :apePater, :apeMater, :dni, :direccion, 
                :telefono, :correo, :id_usuario, :id_turno, :estado
            )";
    $stmt = $this->conexion->prepare($sql);
    $stmt->bindValue(':nombre', $empleado->getNombre());
    $stmt->bindValue(':apePater', $empleado->getApellidoPaterno());
    $stmt->bindValue(':apeMater', $empleado->getApellidoMaterno());
    $stmt->bindValue(':dni', $empleado->getDni());
    $stmt->bindValue(':direccion', $empleado->getDireccion());
    $stmt->bindValue(':telefono', $empleado->getTelefono());
    $stmt->bindValue(':correo', $empleado->getCorreo());
    $stmt->bindValue(':id_usuario', $empleado->getTipoEmpleado()); // Usar getTipoEmpleado para id_usuario
    $stmt->bindValue(':id_turno', $empleado->getTurno()); // Usar getTurno para id_turno
    $stmt->bindValue(':estado', $empleado->getEstado());
    return $stmt->execute();
}

    public function obtenerPorId($id)
    {
        $sql = "SELECT * FROM empleado WHERE id_empleado = :id_empleado";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindValue(':id_empleado', $id);
        $stmt->execute();
        $result = $stmt->fetch();

        if ($result) {
            $empleado = new Empleado();
            $empleado->setId($result['id_empleado']);
            $empleado->setNombre($result['nombre']);
            $empleado->setApellidoPaterno($result['apePater']);
            $empleado->setApellidoMaterno($result['apeMater']);
            $empleado->setDni($result['dni']);
            $empleado->setDireccion($result['direccion']);
            $empleado->setTelefono($result['telefono']);
            $empleado->setCorreo($result['correo']);
            $empleado->setTipoEmpleado($result['id_usuario']); // Usar setTipoEmpleado para id_usuario
            $empleado->setTurno($result['id_turno']); // Usar setTurno para id_turno
            $empleado->setEstado($result['estado']);
            return $empleado;
        } else {
            return null;
        }
    }

public function obtenerTodos()
{
    $sql = "SELECT 
                e.id_empleado,
                e.nombre,
                e.apePater,
                e.apeMater,
                e.dni,
                e.telefono,
                e.correo,
                e.direccion,
                u.id_usuario,
                r.nombre AS rol,
                t.nombre AS turno,
                e.estado
            FROM empleado e
            INNER JOIN usuario u ON e.id_usuario = u.id_usuario
            INNER JOIN rol r ON u.id_rol = r.id_rol
            INNER JOIN turno t ON e.id_turno = t.id_turno
            WHERE e.estado = 1
            ORDER BY e.id_empleado ASC";

    $stmt = $this->conexion->prepare($sql);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $empleados = [];
    foreach ($results as $result) {
        $empleado = new Empleado();
        $empleado->setId($result['id_empleado']);
        $empleado->setNombre($result['nombre']);
        $empleado->setApellidoPaterno($result['apePater']);
        $empleado->setApellidoMaterno($result['apeMater']);
        $empleado->setDni($result['dni']);
        $empleado->setDireccion($result['direccion']);
        $empleado->setTelefono($result['telefono']);
        $empleado->setCorreo($result['correo']);
        $empleado->setTipoEmpleado($result['rol']); // Ahora es el nombre del rol
        $empleado->setTurno($result['turno']);      // Ahora es el nombre del turno
        $empleado->setEstado($result['estado']);
        $empleados[] = $empleado;
    }

    return $empleados;
}


    public function actualizar(Empleado $empleado)
    {
        $sql = "UPDATE empleado 
                SET nombre = :nombre,
                    apePater = :apePater,
                    apeMater = :apeMater,
                    dni = :dni,
                    direccion = :direccion,
                    telefono = :telefono,
                    correo = :correo,
                    id_usuario = :id_usuario,
                    id_turno = :id_turno,
                    estado = :estado
                WHERE id_empleado = :id_empleado";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindValue(':nombre', $empleado->getNombre());
        $stmt->bindValue(':apePater', $empleado->getApellidoPaterno());
        $stmt->bindValue(':apeMater', $empleado->getApellidoMaterno());
        $stmt->bindValue(':dni', $empleado->getDni());
        $stmt->bindValue(':direccion', $empleado->getDireccion());
        $stmt->bindValue(':telefono', $empleado->getTelefono());
        $stmt->bindValue(':correo', $empleado->getCorreo());
        $stmt->bindValue(':id_usuario', $empleado->getTipoEmpleado()); // Usar getTipoEmpleado para id_usuario
        $stmt->bindValue(':id_turno', $empleado->getTurno()); // Usar getTurno para id_turno
        $stmt->bindValue(':estado', $empleado->getEstado());
        $stmt->bindValue(':id_empleado', $empleado->getId());
        return $stmt->execute();
    }

    public function eliminar($id)
    {
        $sql = "DELETE FROM empleado WHERE id_empleado = :id_empleado";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindValue(':id_empleado', $id);
        return $stmt->execute();
    }

    public function actualizarEstado($id, $estado)
    {
        $sql = "UPDATE empleado SET estado = :estado WHERE id_empleado = :id_empleado";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindValue(':estado', $estado);
        $stmt->bindValue(':id_empleado', $id);
        return $stmt->execute();
    }

        public function obtenerRoles()
    {
        $sql = "SELECT id_rol, nombre FROM rol WHERE estado = 1 ORDER BY nombre ASC";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerTurnos()
    {
        $sql = "SELECT id_turno, nombre FROM turno WHERE estado = 1 ORDER BY nombre ASC";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function obtenerIdUsuarioPorIdEmpleado($id_empleado)
    {
        $sql = "SELECT id_usuario FROM empleado WHERE id_empleado = :id_empleado";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindValue(':id_empleado', $id_empleado);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['id_usuario'] : null;
    }
}
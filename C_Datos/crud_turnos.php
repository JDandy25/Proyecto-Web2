<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/Proyecto-Web2/C_Datos/conexion.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/Proyecto-Web2/C_Entidad/class_turnos.php';

class TurnoDAO
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = Db::conectar();
    }

    public function insertar(Turno $turno)
    {
        $sql = "INSERT INTO turno (
                    nombre, horaIngreso, horaSalida, estado
                ) 
                VALUES (
                    :nombre, :horaIngreso, :horaSalida, :estado
                )";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindValue(':nombre', $turno->getNombre());
        $stmt->bindValue(':horaIngreso', $turno->getHoraIngreso());
        $stmt->bindValue(':horaSalida', $turno->getHoraSalida());
        $stmt->bindValue(':estado', $turno->getEstado());
        return $stmt->execute();
    }

    public function obtenerPorId($id)
    {
        $sql = "SELECT * FROM turno WHERE id_turno = :id_turno";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindValue(':id_turno', $id);
        $stmt->execute();
        $result = $stmt->fetch();

        if ($result) {
            $turno = new Turno();
            $turno->setIdTurno($result['id_turno']);
            $turno->setNombre($result['nombre']);
            $turno->setHoraIngreso($result['horaIngreso']);
            $turno->setHoraSalida($result['horaSalida']);
            $turno->setEstado($result['estado']);
            return $turno;
        } else {
            return null;
        }
    }

    public function obtenerTodos()
    {
        $sql = "SELECT * FROM turno WHERE estado = 1 ORDER BY id_turno ASC";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $turnos = [];
        foreach ($results as $result) {
            $turno = new Turno();
            $turno->setIdTurno($result['id_turno']);
            $turno->setNombre($result['nombre']);
            $turno->setHoraIngreso($result['horaIngreso']);
            $turno->setHoraSalida($result['horaSalida']);
            $turno->setEstado($result['estado']);
            $turnos[] = $turno;
        }
        return $turnos;
    }

    public function actualizar(Turno $turno)
    {
        $sql = "UPDATE turno 
                SET nombre = :nombre,
                    horaIngreso = :horaIngreso,
                    horaSalida = :horaSalida,
                    estado = :estado
                WHERE id_turno = :id_turno";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindValue(':nombre', $turno->getNombre());
        $stmt->bindValue(':horaIngreso', $turno->getHoraIngreso());
        $stmt->bindValue(':horaSalida', $turno->getHoraSalida());
        $stmt->bindValue(':estado', $turno->getEstado());
        $stmt->bindValue(':id_turno', $turno->getIdTurno());
        return $stmt->execute();
    }

    public function eliminar($id)
    {
        $sql = "DELETE FROM turno WHERE id_turno = :id_turno";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindValue(':id_turno', $id);
        return $stmt->execute();
    }

    public function actualizarEstado($id, $estado)
    {
        $sql = "UPDATE turno SET estado = :estado WHERE id_turno = :id_turno";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindValue(':estado', $estado);
        $stmt->bindValue(':id_turno', $id);
        return $stmt->execute();
    }
}

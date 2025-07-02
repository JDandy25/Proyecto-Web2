<?php

class Turno
{
    private $id_turno;
    private $nombre;
    private $horaIngreso;
    private $horaSalida;
    private $estado;

    // Getters y Setters
    public function getIdTurno()
    {
        return $this->id_turno;
    }
    public function setIdTurno($id_turno)
    {
        $this->id_turno = $id_turno;
    }
    public function getNombre()
    {
        return $this->nombre;
    }
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }
    public function getHoraIngreso()
    {
        return $this->horaIngreso;
    }
    public function setHoraIngreso($horaIngreso)
    {
        $this->horaIngreso = $horaIngreso;
    }
    public function getHoraSalida()
    {
        return $this->horaSalida;
    }
    public function setHoraSalida($horaSalida)
    {
        $this->horaSalida = $horaSalida;
    }
    public function getEstado()
    {
        return $this->estado;
    }
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }
}

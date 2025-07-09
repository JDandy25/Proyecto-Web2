<?php

class Empleado
{
    private $id;
    private $nombre;
    private $apellido_paterno;
    private $apellido_materno;
    private $dni;
    private $direccion;
    private $telefono;
    private $correo;
    private $tipo_empleado;
    private $turno;
    private $estado;
    private $id_usuario;
    private $nombre_usuario;

    // Getters y Setters

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    public function getApellidoPaterno()
    {
        return $this->apellido_paterno;
    }

    public function setApellidoPaterno($apellido_paterno)
    {
        $this->apellido_paterno = $apellido_paterno;
    }

    public function getApellidoMaterno()
    {
        return $this->apellido_materno;
    }

    public function setApellidoMaterno($apellido_materno)
    {
        $this->apellido_materno = $apellido_materno;
    }

    public function getDni()
    {
        return $this->dni;
    }

    public function setDni($dni)
    {
        $this->dni = $dni;
    }

    public function getDireccion()
    {
        return $this->direccion;
    }

    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;
    }

    public function getTelefono()
    {
        return $this->telefono;
    }

    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;
    }

    public function getCorreo()
    {
        return $this->correo;
    }

    public function setCorreo($correo)
    {
        $this->correo = $correo;
    }

    public function getTipoEmpleado()
    {
        return $this->tipo_empleado;
    }

    public function setTipoEmpleado($tipo_empleado)
    {
        $this->tipo_empleado = $tipo_empleado;
    }

    public function getTurno()
    {
        return $this->turno;
    }

    public function setTurno($turno)
    {
        $this->turno = $turno;
    }

    public function getEstado()
    {
        return $this->estado;
    }

    public function setEstado($estado)
    {
        $this->estado = $estado;
    }

    public function getIdUsuario()
    {
        return $this->id_usuario;
    }

    public function setIdUsuario($id_usuario)
    {
        $this->id_usuario = $id_usuario;
    }

    public function getNombreUsuario()
    {
        return $this->nombre_usuario;
    }

    public function setNombreUsuario($nombre_usuario)
    {
        $this->nombre_usuario = $nombre_usuario;
    }
}

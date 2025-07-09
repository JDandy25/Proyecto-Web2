<?php

class Proveedor
{
    private $id_proveedor;
    private $nombres;
    private $apellidos;
    private $RUC;
    private $telefono;
    private $correo;
    private $estado;

    // Getters y Setters
    public function getIdProveedor()
    {
        return $this->id_proveedor;
    }
    public function setIdProveedor($id_proveedor)
    {
        $this->id_proveedor = $id_proveedor;
    }
    public function getNombres()
    {
        return $this->nombres;
    }
    public function setNombres($nombres)
    {
        $this->nombres = $nombres;
    }
    public function getApellidos()
    {
        return $this->apellidos;
    }
    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;
    }
    public function getRUC()
    {
        return $this->RUC;
    }
    public function setRUC($RUC)
    {
        $this->RUC = $RUC;
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
    public function getEstado()
    {
        return $this->estado;
    }
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }
}

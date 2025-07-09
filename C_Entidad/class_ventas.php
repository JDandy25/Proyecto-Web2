<?php

class Venta
{
    private $id_venta;
    private $tipoComprobante;
    private $serie_Comprobante;
    private $numComprobante;
    private $fechaHora;
    private $impuesto;
    private $total_venta;
    private $id_cliente;
    private $ClienteNombre;
    private $id_empleado;
    private $EmpleadoNombre;
    private $estado;
    private $detalles = [];

    // Getters
    public function getIdVenta() { return $this->id_venta; }
    public function getTipoComprobante() { return $this->tipoComprobante; }
    public function getSerieComprobante() { return $this->serie_Comprobante; }
    public function getNumComprobante() { return $this->numComprobante; }
    public function getFechaHora() { return $this->fechaHora; }
    public function getImpuesto() { return $this->impuesto; }
    public function getTotalVenta() { return $this->total_venta; }
    public function getIdCliente() { return $this->id_cliente; }
    public function getClienteNombre() { return $this->ClienteNombre; }
    public function getIdEmpleado() { return $this->id_empleado; }
    public function getEmpleadoNombre() { return $this->EmpleadoNombre; }
    public function getEstado() { return $this->estado; }
    public function getDetalles() { return $this->detalles; }

    // Setters
    public function setIdVenta($id) { $this->id_venta = $id; }
    public function setTipoComprobante($tipo) { $this->tipoComprobante = $tipo; }
    public function setSerieComprobante($serie) { $this->serie_Comprobante = $serie; }
    public function setNumComprobante($numero) { $this->numComprobante = $numero; }
    public function setFechaHora($fecha) { $this->fechaHora = $fecha; }
    public function setImpuesto($impuesto) { $this->impuesto = $impuesto; }
    public function setTotalVenta($total) { $this->total_venta = $total; }
    public function setIdCliente($id) { $this->id_cliente = $id; }
    public function setClienteNombre($nombre) { $this->ClienteNombre = $nombre; }
    public function setIdEmpleado($id) { $this->id_empleado = $id; }
    public function setEmpleadoNombre($nombre) { $this->EmpleadoNombre = $nombre; }
    public function setEstado($estado) { $this->estado = $estado; }
    public function setDetalles($detalles) { $this->detalles = $detalles; }

    // Método para agregar un detalle individual
    public function agregarDetalle($detalle) {
        $this->detalles[] = $detalle;
    }
}

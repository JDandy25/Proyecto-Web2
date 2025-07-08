<?php
class Compra 
{
    private $id_compra;
    private $tipoComprobante;
    private $serie_Comprobante;
    private $numComprobante;
    private $fechaHora;
    private $impuesto;
    private $total_compra;
    private $id_empleado;
    private $EmpleadoNombre;
    private $id_proveedor;
    private $ProveedorNombre;
    private $estado;
    private $detalles = [];

    // Getters
    public function getIdCompra() { return $this->id_compra; }
    public function getTipoComprobante() { return $this->tipoComprobante; }
    public function getSerieComprobante() { return $this->serie_Comprobante; }
    public function getNumComprobante() { return $this->numComprobante; }
    public function getFechaHora() { return $this->fechaHora; }
    public function getImpuesto() { return $this->impuesto; }
    public function getTotalCompra() { return $this->total_compra; }
    public function getIdEmpleado() { return $this->id_empleado; }
    public function getEmpleadoNombre() { return $this->EmpleadoNombre; }
    public function getIdProveedor() { return $this->id_proveedor; }
    public function getProveedorNombre() { return $this->ProveedorNombre; }
    public function getEstado() { return $this->estado; }
    public function getDetalles() { return $this->detalles; }

    // Setters
    public function setIdCompra($id) { $this->id_compra = $id; }
    public function setTipoComprobante($tipo) { $this->tipoComprobante = $tipo; }
    public function setSerieComprobante($serie) { $this->serie_Comprobante = $serie; }
    public function setNumComprobante($numero) { $this->numComprobante = $numero; }
    public function setFechaHora($fecha) { $this->fechaHora = $fecha; }
    public function setImpuesto($impuesto) { $this->impuesto = $impuesto; }
    public function setTotalCompra($total) { $this->total_compra = $total; }
    public function setIdEmpleado($id) { $this->id_empleado = $id; }
    public function setEmpleadoNombre($nombre) { $this->EmpleadoNombre = $nombre; }
    public function setIdProveedor($id) { $this->id_proveedor = $id; }
    public function setProveedorNombre($nombre) { $this->ProveedorNombre = $nombre; }
    public function setEstado($estado) { $this->estado = $estado; }
    public function setDetalles($detalles) { $this->detalles = $detalles; }
    
    public function agregarDetalle($detalle) {
        $this->detalles[] = $detalle;
    }
}
<?php
class DetalleCompra 
{
    private $id_detalleCompra;
    private $cantidad;
    private $precio_compra;
    private $precio_venta;
    private $id_producto;
    private $id_compra;

    // Getters
    public function getIdDetalleCompra() { return $this->id_detalleCompra; }
    public function getCantidad() { return $this->cantidad; }
    public function getPrecioCompra() { return $this->precio_compra; }
    public function getPrecioVenta() { return $this->precio_venta; }
    public function getIdProducto() { return $this->id_producto; }
    public function getIdCompra() { return $this->id_compra; }

    // Setters
    public function setIdDetalleCompra($id) { $this->id_detalleCompra = $id; }
    public function setCantidad($cantidad) { $this->cantidad = $cantidad; }
    public function setPrecioCompra($precio) { $this->precio_compra = $precio; }
    public function setPrecioVenta($precio) { $this->precio_venta = $precio; }
    public function setIdProducto($id) { $this->id_producto = $id; }
    public function setIdCompra($id) { $this->id_compra = $id; }
}
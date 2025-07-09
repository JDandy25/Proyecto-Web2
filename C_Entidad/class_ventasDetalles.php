<?php

class DetalleVenta 
{
    private $id_detalleVenta;
    private $cantidad;
    private $precio_venta;
    private $descuento;
    private $id_producto;
    private $id_venta;

    // Getters
    public function getIdDetalleVenta() { return $this->id_detalleVenta; }
    public function getCantidad() { return $this->cantidad; }
    public function getPrecioVenta() { return $this->precio_venta; }
    public function getDescuento() { return $this->descuento; }
    public function getIdProducto() { return $this->id_producto; }
    public function getIdVenta() { return $this->id_venta; }

    // Setters
    public function setIdDetalleVenta($id) { $this->id_detalleVenta = $id; }
    public function setCantidad($cantidad) { $this->cantidad = $cantidad; }
    public function setPrecioVenta($precio) { $this->precio_venta = $precio; }
    public function setDescuento($descuento) { $this->descuento = $descuento; }
    public function setIdProducto($id) { $this->id_producto = $id; }
    public function setIdVenta($id) { $this->id_venta = $id; }
}

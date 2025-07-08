<div class="contenidoRegistrarCompra hidden">
    <form id="FormRegistrarCompra" autocomplete="off" action="" method="post">
        <h1 class="tituloContenido">Registrar Compra</h1>

        <div class="datosRL">
            <h2 class="tituloContenedor">Datos de la Compra</h2>
            <div class="datosAgrupados">

                <div class="groupDatos">
                    <label for="tipoComprobante">Tipo Comprobante <span class="requerido">*</span></label>
                    <select class="inputDatos" name="tipoComprobante" id="tipoComprobante" required>
                        <option value="">Seleccionar</option>
                        <option value="Factura">Factura</option>
                        <option value="Boleta">Boleta</option>

                    </select>
                </div>

                <div class="groupDatos">
                    <label for="serieComprobante">Serie Comprobante</label>
                    <input type="text" class="inputDatos" name="serieComprobante" id="serieComprobante" readonly>
                </div>

                <div class="groupDatos">
                    <label for="numComprobante">N° Comprobante <span class="requerido">*</span></label>
                    <input type="text" class="inputDatos" name="numComprobante" id="numComprobante" readonly>
                </div>

                <div class="groupDatos">
                    <label for="fechaHora">Fecha y Hora</label>
                    <input type="datetime-local" class="inputDatos" name="fechaHora" id="fechaHora" readonly>
                </div>

                <div class="groupDatos">
                    <label for="idProveedor">Proveedor <span class="requerido">*</span></label>
                    <select class="inputDatos" name="idProveedor" id="idProveedor" required>
                        <option value="">Seleccionar proveedor</option>
                        <!-- Opciones se cargarán dinámicamente -->
                    </select>
                </div>

                <div class="groupDatos">
                    <label for="impuesto">Impuesto (%)</label>
                    <input type="number" class="inputDatos" name="impuesto" id="impuesto" value="18" min="0" max="100" step="0.01">
                </div>

                <div class="groupDatos">
                    <label for="totalCompra">Total Compra</label>
                    <input type="number" class="inputDatos" name="totalCompra" id="totalCompra" readonly>
                </div>

                <div class="groupDatos hidden">
                    <label for="idEmpleado">ID Empleado</label>
                    <input type="text" class="inputDatos" name="idEmpleado" id="idEmpleado" value="<?php echo $_SESSION['id_empleado']; ?>">
                </div>
            </div>
        </div>

        <div class="datosRL">
            <h2 class="tituloContenedor">Detalle de la Compra</h2>
            <div class="datosAgrupados">
                <div class="groupDatos" style="grid-column: span 3;">
                    <label for="idProducto">Producto <span class="requerido">*</span></label>
                    <select class="inputDatos" name="idProducto" id="idProducto" required>
                        <option value="">Seleccionar producto</option>
                        <!-- Opciones se cargarán dinámicamente -->
                    </select>
                </div>

                <div class="groupDatos">
                    <label for="cantidad">Cantidad <span class="requerido">*</span></label>
                    <input type="number" class="inputDatos" name="cantidad" id="cantidad" min="1" value="1" required>
                </div>

                <div class="groupDatos">
                    <label for="precioCompra">Precio Compra <span class="requerido">*</span></label>
                    <input type="number" class="inputDatos" name="precioCompra" id="precioCompra" min="0" step="0.01" required>
                </div>

                <div class="groupDatos">
                    <label for="precioVenta">Precio Venta <span class="requerido">*</span></label>
                    <input type="number" class="inputDatos" name="precioVenta" id="precioVenta" min="0" step="0.01" required>
                </div>

                <div class="groupDatos" style="grid-column: span 4; text-align: right;">
                    <button type="button" class="btn" id="btnAgregarDetalle"><i class="fa-solid fa-plus"></i> Agregar Producto</button>
                </div>
            </div>
        </div>

        <div class="contenedorTablaDetalle">
            <table class="tablaDetalle">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio Compra</th>
                        <th>Precio Venta</th>
                        <th>Subtotal</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="tbodyDetalleCompra">
                    <!-- Detalles se agregarán dinámicamente -->
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" style="text-align: right;"><strong>Total:</strong></td>
                        <td id="totalDetalle">0.00</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="botones">
            <button type="submit" class="btn" id="btnRegistrarCompra"><i class="fa-solid fa-check A"></i> Registrar</button>
            <button type="button" class="btncancelar" id="cancelar_registro_compra"><i class="fa-solid fa-trash A"></i> Cancelar</button>
        </div>
    </form>
</div>



<div class="contenidoListaCompra hidden">
    <h1 class="tituloContenido">Lista de Compras</h1>
    <div class="buscador">
        <div class="contenedorBuscador">
            <input type="text" id="inputBusquedaCompra" class="inputBusqueda" placeholder="Buscar compra">
            <button type="button" class="botonBusqueda">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </div>
    </div>
    <div class="contenedorTablaprestamos">
        <table class="tablprestamos">
            <thead class="cabeceraPrestamos">
                <tr>
                    <th>N° Compra</th>
                    <th>Tipo Comprobante</th>
                    <th>N° Comprobante</th>
                    <th>Fecha</th>
                    <th>Proveedor</th>
                    <th>Total</th>
                    <th>Impuesto</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody class="tbodyCompras">
                <!-- Aquí se agregarán los registros dinámicamente -->
            </tbody>
        </table>
        <div class="navegacionTabla">
            <button class="btnAnterior btnAnteriorCompras" type="button"><i class="fa-solid fa-angle-left"></i></button>
            <p class="paginacion paginacionCompras">1 - 1</p>
            <button class="btnSiguiente btnSiguienteCompras" type="button"><i class="fa-solid fa-angle-right"></i></button>
        </div>
    </div>
</div>




<dialog id="modalActualizarcompra" class="modal">
    <div class="dialog-content">
        <div class="modal-header">
            <h1 class="tituloContenido">Detalle de Compra #<span id="numeroCompraModal"></span></h1>
            <button type="button" class="btnCerrarModal" id="btnCerrarDetalleCompra">&times;</button>
        </div>
        
        <div class="datosCompra">
            <div class="infoCompra">
                <p><strong>Proveedor:</strong> <span id="proveedorModal"></span></p>
                <p><strong>Fecha:</strong> <span id="fechaModal"></span></p>
                <p><strong>Tipo Comprobante:</strong> <span id="tipoComprobanteModal"></span></p>
                <p><strong>N° Comprobante:</strong> <span id="numComprobanteModal"></span></p>
                <p><strong>Total:</strong> <span id="totalModal"></span></p>
                <p><strong>Estado:</strong> <span id="estadoModal"></span></p>
            </div>
            
            <div class="contenedorTablaDetalle">
                <table class="tablaDetalle">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio Compra</th>
                            <th>Precio Venta</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody id="tbodyDetalleCompraModal">
                        <!-- Detalles se cargarán dinámicamente -->
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="botonesModal">
            <button type="button" class="btncancelar" id="btnAnularCompra" style="display: none;">
                <i class="fa-solid fa-ban"></i> Anular Compra
            </button>
            <button type="button" class="btncancelar" id="btnCerrarModalDetalle">
                <i class="fa-solid fa-times"></i> Cerrar
            </button>
        </div>
    </div>
</dialog>




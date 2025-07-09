
<div class="contenidoRegistrarCompra">
    <form id="FormRegistrarCompra" autocomplete="off" action="" method="post">
        <h1 class="tituloContenido">Registrar Compra</h1>
        <!-- Encabezado de comprobante -->
        <div class="datosCompraEncabezado" style="display: flex; gap: 20px; align-items: flex-end;">
            <div class="groupDatos">
                <label for="tipoComprobante">Tipo de Comprobante <span class="requerido">*</span></label>
                <select name="tipoComprobante" id="tipoComprobante" class="inputDatos" required>
                    <option value="Factura">Factura</option>
                    <option value="Boleta">Boleta</option>
                </select>
            </div>
            <div class="groupDatos">
                <label for="serieComprobante">Serie</label>
                <input type="text" class="inputDatos" name="serieComprobante" id="serieComprobante" readonly>
            </div>
            <div class="groupDatos">
                <label for="numComprobante">N° Comprobante</label>
                <input type="text" class="inputDatos" name="numComprobante" id="numComprobante" readonly>
            </div>
            <div class="groupDatos">
                <label for="fechaHora">Fecha y Hora</label>
                <input type="text" class="inputDatos" name="fechaHora" id="fechaHora" readonly>
            </div>
        </div>

        <!-- Proveedor e impuesto -->
        <div class="datosCompraSecundario" style="display: flex; gap: 20px; margin-top: 15px;">
            <div class="groupDatos">
                <label for="proveedor">Proveedor <span class="requerido">*</span></label>
                <select name="proveedor" id="proveedor" class="inputDatos" required>
                    <option value="">Seleccione</option>
                </select>
            </div>
            <div class="groupDatos">
                <label for="impuesto">Impuesto (%)</label>
                <input type="number" class="inputDatos" name="impuesto" id="impuesto" value="18" min="0" max="100" step="0.01" required>
            </div>
        </div>

        <!-- Detalle de compra y tabla de detalles -->
        <div class="detalleCompraContenedor" style="display: flex; gap: 40px; margin-top: 25px;">
            <!-- Formulario para agregar detalle -->
            <div class="detalleCompraForm" style="flex: 1;">
                <h2 class="tituloContenedor">Agregar Detalle de Producto</h2>
                <div class="groupDatos">
                    <label for="productoBuscar">Producto <span class="requerido">*</span></label>
                    <input type="text" class="inputDatos" id="productoBuscar" placeholder="Buscar producto..." autocomplete="off">
                    <input type="hidden" id="idProductoSeleccionado">
                    <div id="sugerenciasProducto" class="sugerencias"></div>
                </div>
                <div class="groupDatos">
                    <label for="cantidadDetalle">Cantidad <span class="requerido">*</span></label>
                    <input type="number" class="inputDatos" id="cantidadDetalle" min="1" value="1" required>
                </div>
                <div class="groupDatos">
                    <label for="precioCompraDetalle">Precio Compra <span class="requerido">*</span></label>
                    <input type="number" class="inputDatos" id="precioCompraDetalle" min="0" step="0.01" required>
                </div>
                <div class="groupDatos">
                    <label for="precioVentaDetalle">Precio Venta</label>
                    <input type="number" class="inputDatos" id="precioVentaDetalle" min="0" step="0.01">
                </div>
                <button type="button" class="btn" id="btnAgregarDetalle"><i class="fa-solid fa-plus"></i> Agregar Producto</button>
            </div>

            <!-- Tabla de detalles agregados -->
            <div class="detalleCompraTabla" style="flex: 2;">
                <h2 class="tituloContenedor">Detalles de la Compra</h2>
                <table class="tablprestamos" id="tablaDetallesCompra">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio Compra</th>
                            <th>Precio Venta</th>
                            <th>Subtotal</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody class="tbodyDetallesCompra">
                        <!-- Detalles agregados dinámicamente -->
                    </tbody>
                </table>
                <div style="margin-top: 10px; text-align: right;">
                    <strong>Total: S/ <span id="totalCompra">0.00</span></strong><br>
                    <strong>Impuesto: S/ <span id="impuestoCompra">0.00</span></strong>
                </div>
            </div>
        </div>

        <!-- Botones de acción -->
        <div class="botones" style="margin-top: 25px;">
            <button type="submit" class="btn" id="btnRegistrarCompra"><i class="fa-solid fa-check"></i> Registrar Compra</button>
            <button type="button" class="btn" id="btnImprimirCompra"><i class="fa-solid fa-print"></i> Imprimir / PDF</button>
        </div>
    </form>
</div>

<!-- Lista de compras -->
<div class="contenidoListaCompra" style="margin-top: 40px;">
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
        <table class="tablprestamos" id="tablaCompras">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Comprobante</th>
                    <th>Proveedor</th>
                    <th>Fecha</th>
                    <th>Total</th>
                    <th>Impuesto</th>
                    <th>Estado</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody class="tbodyCompras">
                <!-- Compras listadas dinámicamente -->
            </tbody>
        </table>
        <div class="navegacionTabla">
            <button class="btnAnterior btnAnteriorCompras" type="button"><i class="fa-solid fa-angle-left"></i></button>
            <p class="paginacion paginacionCompras">1 - 1</p>
            <button class="btnSiguiente btnSiguienteCompras" type="button"><i class="fa-solid fa-angle-right"></i></button>
        </div>
    </div>
</div>

<!-- Modal para ver detalles de compra -->
<dialog id="modalDetalleCompra" class="modal">
  <div class="dialog-content">
    <h2>Detalle de Compra</h2>
    <table class="tablprestamos">
      <thead>
        <tr>
          <th>Producto</th>
          <th>Cantidad</th>
          <th>Precio Compra</th>
          <th>Precio Venta</th>
          <th>Subtotal</th>
        </tr>
      </thead>
      <tbody id="detalleCompraBody">
        <!-- Detalles dinámicos -->
      </tbody>
    </table>
    <button type="button" class="btn" id="btnCerrarDetalleCompra">Cerrar</button>
  </div>
</dialog>
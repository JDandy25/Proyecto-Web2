
<div class="contenidoRegistrarVenta hidden" style="margin-left: 40px;">
    <form id="FormRegistrarVenta" autocomplete="off" action="" method="post">
        <h1 class="tituloContenido">Registrar Venta</h1>
        <!-- Encabezado de comprobante -->
        <div class="datosVentaEncabezado" style="display: flex; gap: 20px; align-items: flex-end;">
            <div class="groupDatos">
                <label for="tipoComprobanteVenta">Tipo de Comprobante <span class="requerido">*</span></label>
                <select name="tipoComprobanteVenta" id="tipoComprobanteVenta" class="inputDatos" required>
                    <option value="Factura">Factura</option>
                    <option value="Boleta">Boleta</option>
                </select>
            </div>
            <div class="groupDatos">
                <label for="serieComprobanteVenta">Serie</label>
                <input type="text" class="inputDatos" name="serieComprobanteVenta" id="serieComprobanteVenta" readonly>
            </div>
            <div class="groupDatos">
                <label for="numComprobanteVenta">N° Comprobante</label>
                <input type="text" class="inputDatos" name="numComprobanteVenta" id="numComprobanteVenta" readonly>
            </div>
            <div class="groupDatos">
                <label for="fechaHoraVenta">Fecha y Hora</label>
                <input type="text" class="inputDatos" name="fechaHoraVenta" id="fechaHoraVenta" readonly>
            </div>
        </div>

        <!-- Cliente e impuesto -->
        <div class="datosVentaSecundario" style="display: flex; gap: 20px; margin-top: 15px;">
            <div class="groupDatos">
                <label for="cliente">Cliente <span class="requerido">*</span></label>
                <select name="cliente" id="cliente" class="inputDatos" required>
                    <option value="">Seleccione</option>
                </select>
            </div>
            <div class="groupDatos">
                <label for="empleado">Empleado <span class="requerido">*</span></label>
                <select name="empleado" id="empleado" class="inputDatos" required>
                    <option value="">Seleccione</option>
                </select>
            </div>
            <div class="groupDatos">
                <label for="impuestoVenta">Impuesto (%)</label>
                <input type="number" class="inputDatos" name="impuestoVenta" id="impuestoVenta" value="18" min="0" max="100" step="0.01" required>
            </div>
        </div>

        <!-- Detalle de venta y tabla de detalles -->
        <div class="detalleVentaContenedor" style="display: flex; gap: 40px; margin-top: 25px;">
            <!-- Formulario para agregar detalle -->
            <div class="detalleVentaForm" style="flex: 1;">
                <h2 class="tituloContenedor">Agregar Detalle de Producto</h2>
                <div class="groupDatos">
                    <label for="productoBuscarVenta">Producto <span class="requerido">*</span></label>
                    <input type="text" class="inputDatos" id="productoBuscarVenta" placeholder="Buscar producto..." autocomplete="off">
                    <input type="hidden" id="idProductoSeleccionadoVenta">
                    <div id="sugerenciasProductoVenta" class="sugerencias"></div>
                </div>
                <div class="groupDatos">
                    <label for="cantidadDetalleVenta">Cantidad <span class="requerido">*</span></label>
                    <input type="number" class="inputDatos" id="cantidadDetalleVenta" min="1" value="1" required>
                </div>
                <div class="groupDatos">
                    <label for="precioVentaDetalleVenta">Precio Venta <span class="requerido">*</span></label>
                    <input type="number" class="inputDatos" id="precioVentaDetalleVenta" min="0" step="0.01" required>
                </div>
                <div class="groupDatos">
                    <label for="descuentoDetalleVenta">Descuento</label>
                    <input type="number" class="inputDatos" id="descuentoDetalleVenta" min="0" step="0.01" value="0">
                </div>
                <button type="button" class="btn" id="btnAgregarDetalleVenta"><i class="fa-solid fa-plus"></i> Agregar Producto</button>
            </div>

            <!-- Tabla de detalles agregados -->
            <div class="detalleVentaTabla" style="flex: 2;">
                <h2 class="tituloContenedor">Detalles de la Venta</h2>
                <table class="tablprestamos" id="tablaDetallesVenta">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio Venta</th>
                            <th>Descuento</th>
                            <th>Subtotal</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody class="tbodyDetallesVenta">
                        <!-- Detalles agregados dinámicamente -->
                    </tbody>
                </table>
                <div style="margin-top: 10px; text-align: right;">
                    <strong>Total: S/ <span id="totalVenta">0.00</span></strong><br>
                    <strong>Impuesto: S/ <span id="impuestoVentaTotal">0.00</span></strong>
                </div>
            </div>
           
        </div>

        <!-- Botones de acción -->
        <div class="botones" style="margin-top: 25px;">
            <button type="submit" class="btn" id="btnRegistrarVenta"><i class="fa-solid fa-check"></i> Registrar Venta</button>
            <button type="button" class="btn" id="btnImprimirVenta"><i class="fa-solid fa-print"></i> Imprimir / PDF</button>
        </div>
    </form>
</div>

<!-- Lista de ventas -->
<div class="contenidoListaVenta hidden" style="margin-top: 40px;">
    <h1 class="tituloContenido">Lista de Ventas</h1>
    <div class="buscador">
        <div class="contenedorBuscador">
            <input type="text" id="inputBusquedaVenta" class="inputBusqueda" placeholder="Buscar venta">
            <button type="button" class="botonBusqueda">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </div>
    </div>
    <div class="contenedorTablaprestamos">
        <table class="tablprestamos" id="tablaVentas">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Comprobante</th>
                    <th>Cliente</th>
                    <th>Fecha</th>
                    <th>Total</th>
                    <th>Impuesto</th>
                    <th>Estado</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody class="tbodyVentas">
                <!-- Ventas listadas dinámicamente -->
            </tbody>
        </table>
        <div class="navegacionTabla">
            <button class="btnAnterior btnAnteriorVentas" type="button"><i class="fa-solid fa-angle-left"></i></button>
            <p class="paginacion paginacionVentas">1 - 1</p>
            <button class="btnSiguiente btnSiguienteVentas" type="button"><i class="fa-solid fa-angle-right"></i></button>
        </div>
    </div>
</div>

<!-- Modal para ver detalles de venta -->
<dialog id="modalDetalleVenta" class="modal" >
  <div class="dialog-content">
    <h2>Detalle de Venta</h2>
    <table class="tablprestamos">
      <thead>
        <tr>
          <th>Producto</th>
          <th>Cantidad</th>
          <th>Precio Venta</th>
          <th>Descuento</th>
          <th>Subtotal</th>
        </tr>
      </thead>
      <tbody id="detalleVentaBody">
        <!-- Detalles dinámicos -->
      </tbody>
    </table>
    <button type="button" class="btn" id="btnCerrarDetalleVenta">Cerrar</button>
  </div>
</dialog>
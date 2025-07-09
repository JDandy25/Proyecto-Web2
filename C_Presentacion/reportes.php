<!-- Módulo de Reporte: Compras por Fecha -->
<div id="moduloReporteCompras" class="contenedor-reporte" style="display: none;">
    <h2>Reporte: Compras por Fecha</h2>

    <div class="filtros">
        <label>Desde: <input type="date" id="fechaInicio"></label>
        <label>Hasta: <input type="date" id="fechaFin"></label>
        <button onclick="generarReporteCompras()">Generar</button>
    </div>

    <table border="1" id="tablaReporteCompras">
        <thead>
            <tr>
                <th>ID Compra</th>
                <th>Fecha y Hora</th>
                <th>Proveedor</th>
                <th>Total (S/.)</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<!-- Módulo de Reporte: Ventas por Fecha -->
<div id="moduloReporteVentas" class="contenedor-reporte" style="display: none;">
    <h2>Reporte: Ventas por Fecha</h2>

    <div class="filtros">
        <label>Desde: <input type="date" id="fechaInicioVentas"></label>
        <label>Hasta: <input type="date" id="fechaFinVentas"></label>
        <button id="btnGenerarReporteVentas">Generar Reporte</button>
    </div>

    <table border="1" id="tablaReporteVentas">
        <thead>
            <tr>
                <th>ID Venta</th>
                <th>Fecha y Hora</th>
                <th>Cliente</th>
                <th>Total (S/.)</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>


<!-- Módulo: Empleado del Mes -->
<div id="moduloReporteEmpleado" class="contenedor-reporte" style="display: none;">
    <h2>Empleado que más ha vendido en un mes</h2>

    <div class="filtros">
        <label for="mesEmpleado">Mes:</label>
        <input type="month" id="mesEmpleado">
        <button id="btnGenerarReporteEmpleado">Generar Reporte</button>
    </div>

    <table id="tablaReporteEmpleado">
        <thead>
            <tr>
                <th>ID Empleado</th>
                <th>Nombre Completo</th>
                <th>Total Vendido (S/.)</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<!-- Módulo: Ingresos por Día -->
<div id="moduloReporteIngresos" class="contenedor-reporte" style="display: none;">
    <h2>Reporte: Ingresos por Día</h2>

    <div class="filtros">
        <label>Desde:
            <input type="date" id="fechaInicioIngresos">
        </label>
        <label>Hasta:
            <input type="date" id="fechaFinIngresos">
        </label>
        <button id="btnGenerarReporteIngresos">Generar Reporte</button>
    </div>

    <table id="tablaReporteIngresos" border="1">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Empleado</th>
                <th>Nro. Ventas</th>
                <th>Total Vendido (S/.)</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<!-- Módulo: Productos con Stock Bajo -->
<div id="moduloReporteStock" class="contenedor-reporte" style="display: none;">
    <h2>Productos con Stock Bajo</h2>

    <div class="filtros">
        <button id="btnGenerarReporteStock">Generar Reporte</button>
    </div>

  <table id="tablaReporteStock">
  <thead>
    <tr>
      <th>ID Producto</th>
      <th>Nombre</th>
      <th>Descripción</th>
      <th>Código</th>
      <th>Stock</th>
      <th>Stock Mínimo</th>
      <th>Categoría</th>
    </tr>
  </thead>
  <tbody></tbody>
</table>


</div>



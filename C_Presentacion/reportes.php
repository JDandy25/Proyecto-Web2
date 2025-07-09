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

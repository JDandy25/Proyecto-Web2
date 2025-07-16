document.addEventListener("DOMContentLoaded", () => {
    // --- Función para abrir módulos ---
    const abrirModulo = (btnId, moduloId) => {
        const btn = document.getElementById(btnId);
        if (btn) {
            btn.addEventListener("click", () => {
                ocultarModulos();
                document.getElementById(moduloId).style.display = "block";
            });
        }
    };

    // Abrir los 4 módulos
    abrirModulo("abrirReporteCompras", "moduloReporteCompras");
    abrirModulo("abrirReporteVentas", "moduloReporteVentas");
    abrirModulo("abrirReporteEmpleado", "moduloReporteEmpleado");
    abrirModulo("abrirReporteIngresos", "moduloReporteIngresos");
    abrirModulo("abrirReporteStock", "moduloReporteStock");

    // --- Generar reportes ---
    const btnCompras = document.getElementById("btnGenerarReporteCompras");
    if (btnCompras) btnCompras.addEventListener("click", generarReporteCompras);

    const btnVentas = document.getElementById("btnGenerarReporteVentas");
    if (btnVentas) btnVentas.addEventListener("click", generarReporteVentas);

    const btnEmpleado = document.getElementById("btnGenerarReporteEmpleado");
    if (btnEmpleado) btnEmpleado.addEventListener("click", generarReporteEmpleado);

    const btnIngresos = document.getElementById("btnGenerarReporteIngresos");
    if (btnIngresos) btnIngresos.addEventListener("click", generarReporteIngresos);

    const btnStock = document.getElementById("btnGenerarReporteStock");
if (btnStock) btnStock.addEventListener("click", generarReporteStock);
});

// --- Ocultar todos los módulos ---
function ocultarModulos() {
    const modulos = document.querySelectorAll(".contenedorContendido > div");
    modulos.forEach(m => m.style.display = "none");
}

// --- Reporte Compras ---
function generarReporteCompras() {
    const inicio = document.getElementById("fechaInicio").value;
    const fin = document.getElementById("fechaFin").value;

    if (!inicio || !fin) {
        alert("Por favor, selecciona un rango de fechas.");
        return;
    }

    fetch(`C_Controlador/Controlador_Reportes.php?reporte=comprasPorFecha&inicio=${inicio}&fin=${fin}`)
        .then(response => response.json())
        .then(data => {
            const tbody = document.querySelector("#tablaReporteCompras tbody");
            tbody.innerHTML = "";
            if (data.error) {
                tbody.innerHTML = `<tr><td colspan="4">Error: ${data.error}</td></tr>`;
                return;
            }
            if (data.length > 0) {
                data.forEach(row => {
                    tbody.innerHTML += `
                        <tr>
                            <td>${row.id_compra}</td>
                            <td>${row.fechaHora}</td>
                            <td>${row.proveedor}</td>
                            <td>${row.total_compra}</td>
                        </tr>`;
                });
            } else {
                tbody.innerHTML = "<tr><td colspan='4'>No se encontraron registros</td></tr>";
            }
        });
}

// --- Reporte Ventas ---
function generarReporteVentas() {
    const inicio = document.getElementById("fechaInicioVentas").value;
    const fin = document.getElementById("fechaFinVentas").value;

    if (!inicio || !fin) {
        alert("Por favor, selecciona un rango de fechas.");
        return;
    }

    fetch(`C_Controlador/Controlador_Reportes.php?reporte=ventasPorFecha&inicio=${inicio}&fin=${fin}`)
        .then(response => response.json())
        .then(data => {
            const tbody = document.querySelector("#tablaReporteVentas tbody");
            tbody.innerHTML = "";
            if (data.error) {
                tbody.innerHTML = `<tr><td colspan="4">Error: ${data.error}</td></tr>`;
                return;
            }
            if (data.length > 0) {
                data.forEach(row => {
                    tbody.innerHTML += `
                        <tr>
                            <td>${row.id_venta}</td>
                            <td>${row.fechaHora}</td>
                            <td>${row.cliente}</td>
                            <td>${row.total_venta}</td>
                        </tr>`;
                });
            } else {
                tbody.innerHTML = "<tr><td colspan='4'>No se encontraron registros</td></tr>";
            }
        });
}

// --- Reporte Empleado del Mes ---
function generarReporteEmpleado() {
    const mesSeleccionado = document.getElementById("mesEmpleado").value;

    if (!mesSeleccionado) {
        alert("Selecciona un mes.");
        return;
    }

    const [anio, mes] = mesSeleccionado.split("-");

    fetch(`C_Controlador/Controlador_Reportes.php?reporte=empleadoDelMes&anio=${anio}&mes=${mes}`)
        .then(response => response.json())
        .then(data => {
            const tbody = document.querySelector("#tablaReporteEmpleado tbody");
            tbody.innerHTML = "";
            if (data.error) {
                tbody.innerHTML = `<tr><td colspan="3">Error: ${data.error}</td></tr>`;
                return;
            }
            if (data.length > 0) {
                data.forEach(emp => {
                    tbody.innerHTML += `
                        <tr>
                            <td>${emp.id_empleado}</td>
                            <td>${emp.nombre_completo}</td>
                            <td>${emp.total_vendido}</td>
                        </tr>`;
                });
            } else {
                tbody.innerHTML = "<tr><td colspan='3'>No hay datos disponibles</td></tr>";
            }
        });
}

// --- Reporte Ingresos por Día ---
function generarReporteIngresos() {
    const inicioInput = document.getElementById("fechaInicioIngresos");
    const finInput = document.getElementById("fechaFinIngresos");

    if (!inicioInput || !finInput) {
        alert("Faltan los campos de fecha en el formulario.");
        return;
    }

    const inicio = inicioInput.value;
    const fin = finInput.value;

    if (!inicio || !fin) {
        alert("Selecciona un rango de fechas.");
        return;
    }

    fetch(`C_Controlador/Controlador_Reportes.php?reporte=ingresosPorDia&inicio=${inicio}&fin=${fin}`)
        .then(response => response.json())
        .then(data => {
            const tbody = document.querySelector("#tablaReporteIngresos tbody");
            tbody.innerHTML = "";
            if (data.error) {
                tbody.innerHTML = `<tr><td colspan="4">Error: ${data.error}</td></tr>`;
                return;
            }
            if (data.length > 0) {
                data.forEach(row => {
                    tbody.innerHTML += `
                        <tr>
                            <td>${row.fecha}</td>
                            <td>${row.empleado}</td>
                            <td>${row.ventas}</td>
                            <td>${row.total_vendido}</td>
                        </tr>`;
                });
            } else {
                tbody.innerHTML = "<tr><td colspan='4'>No hay ingresos registrados</td></tr>";
            }
        });
}

function generarReporteStock() {
    fetch("C_Controlador/Controlador_Reportes.php?reporte=stockMinimo")
        .then(res => res.json())
        .then(data => {
            const tbody = document.querySelector("#tablaReporteStock tbody");
            tbody.innerHTML = "";
            if (data.error) {
                tbody.innerHTML = `<tr><td colspan="7">Error: ${data.error}</td></tr>`;
                return;
            }
            if (data.length === 0) {
                tbody.innerHTML = `<tr><td colspan="7">No hay productos con stock por debajo del mínimo.</td></tr>`;
                return;
            }

            data.forEach(p => {
                tbody.innerHTML += `
                    <tr>
                        <td>${p.id_producto}</td>
                        <td>${p.nombre}</td>
                        <td>${p.descripcion}</td>
                        <td>${p.codigoProd}</td>
                        <td>${p.stock}</td>
                        <td>${p.stock_minimo}</td>
                        <td>${p.categoria}</td>
                    </tr>`;
            });
        })
        .catch(err => {
            console.error("Error al generar reporte de stock:", err);
            alert("Ocurrió un error inesperado.");
        });
}





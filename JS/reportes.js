document.addEventListener("DOMContentLoaded", () => {
    const btnAbrirReporte = document.getElementById("abrirReporteCompras");

    if (btnAbrirReporte) {
        btnAbrirReporte.addEventListener("click", () => {
            ocultarModulos(); // Oculta los demás módulos
            document.getElementById("moduloReporteCompras").style.display = "block";
        });
    }

    const btnGenerar = document.getElementById("btnGenerarReporteCompras");
    if (btnGenerar) {
        btnGenerar.addEventListener("click", generarReporteCompras);
    }
});

function generarReporteCompras() {
    const inicio = document.getElementById("fechaInicio").value;
    const fin = document.getElementById("fechaFin").value;

    if (!inicio || !fin) {
        alert("Por favor, selecciona un rango de fechas.");
        return;
    }

    fetch(`C_Controlador/Controlador_Reportes.php?reporte=comprasPorFecha&inicio=${inicio}&fin=${fin}`)
        .then(async response => {
            const contentType = response.headers.get("content-type");
            if (!contentType || !contentType.includes("application/json")) {
                const text = await response.text();
                throw new Error("Respuesta inesperada del servidor:\n" + text);
            }
            return response.json();
        })
        .then(data => {
    const tbody = document.querySelector("#tablaReporteCompras tbody");
    tbody.innerHTML = "";

    if (data.error) {
        alert("Error del servidor: " + data.error);
        tbody.innerHTML = "<tr><td colspan='4'>Error: " + data.error + "</td></tr>";
        return;
    }

    if (Array.isArray(data) && data.length > 0) {
        data.forEach(row => {
            tbody.innerHTML += `
                <tr>
                    <td>${row.id_compra}</td>
                    <td>${row.fechaHora}</td>
                    <td>${row.proveedor}</td>
                    <td>${row.total_compra}</td>
                </tr>
            `;
        });
    } else {
        tbody.innerHTML = "<tr><td colspan='4'>No se encontraron registros</td></tr>";
    }
})

        .catch(error => {
            console.error("Error al cargar el reporte:", error);
            alert("Ocurrió un error al generar el reporte.\n" + error.message);
        });
}

function ocultarModulos() {
    const modulos = document.querySelectorAll(".contenedorContendido > div");
    modulos.forEach(m => m.style.display = "none");
}

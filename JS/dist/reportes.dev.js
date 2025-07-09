"use strict";

document.addEventListener("DOMContentLoaded", function () {
  var btnAbrirReporte = document.getElementById("abrirReporteCompras");

  if (btnAbrirReporte) {
    btnAbrirReporte.addEventListener("click", function () {
      ocultarModulos(); // Oculta los demás módulos

      document.getElementById("moduloReporteCompras").style.display = "block";
    });
  }

  var btnGenerar = document.getElementById("btnGenerarReporteCompras");

  if (btnGenerar) {
    btnGenerar.addEventListener("click", generarReporteCompras);
  }
});

function generarReporteCompras() {
  var inicio = document.getElementById("fechaInicio").value;
  var fin = document.getElementById("fechaFin").value;

  if (!inicio || !fin) {
    alert("Por favor, selecciona un rango de fechas.");
    return;
  }

  fetch("C_Controlador/Controlador_Reportes.php?reporte=comprasPorFecha&inicio=".concat(inicio, "&fin=").concat(fin)).then(function _callee(response) {
    var contentType, text;
    return regeneratorRuntime.async(function _callee$(_context) {
      while (1) {
        switch (_context.prev = _context.next) {
          case 0:
            contentType = response.headers.get("content-type");

            if (!(!contentType || !contentType.includes("application/json"))) {
              _context.next = 6;
              break;
            }

            _context.next = 4;
            return regeneratorRuntime.awrap(response.text());

          case 4:
            text = _context.sent;
            throw new Error("Respuesta inesperada del servidor:\n" + text);

          case 6:
            return _context.abrupt("return", response.json());

          case 7:
          case "end":
            return _context.stop();
        }
      }
    });
  }).then(function (data) {
    var tbody = document.querySelector("#tablaReporteCompras tbody");
    tbody.innerHTML = "";

    if (data.error) {
      alert("Error del servidor: " + data.error);
      tbody.innerHTML = "<tr><td colspan='4'>Error: " + data.error + "</td></tr>";
      return;
    }

    if (Array.isArray(data) && data.length > 0) {
      data.forEach(function (row) {
        tbody.innerHTML += "\n                <tr>\n                    <td>".concat(row.id_compra, "</td>\n                    <td>").concat(row.fechaHora, "</td>\n                    <td>").concat(row.proveedor, "</td>\n                    <td>").concat(row.total_compra, "</td>\n                </tr>\n            ");
      });
    } else {
      tbody.innerHTML = "<tr><td colspan='4'>No se encontraron registros</td></tr>";
    }
  })["catch"](function (error) {
    console.error("Error al cargar el reporte:", error);
    alert("Ocurrió un error al generar el reporte.\n" + error.message);
  });
}

function ocultarModulos() {
  var modulos = document.querySelectorAll(".contenedorContendido > div");
  modulos.forEach(function (m) {
    return m.style.display = "none";
  });
}
//# sourceMappingURL=reportes.dev.js.map

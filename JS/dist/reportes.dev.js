"use strict";

function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _nonIterableRest(); }

function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance"); }

function _iterableToArrayLimit(arr, i) { if (!(Symbol.iterator in Object(arr) || Object.prototype.toString.call(arr) === "[object Arguments]")) { return; } var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"] != null) _i["return"](); } finally { if (_d) throw _e; } } return _arr; }

function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }

document.addEventListener("DOMContentLoaded", function () {
  // --- Función para abrir módulos ---
  var abrirModulo = function abrirModulo(btnId, moduloId) {
    var btn = document.getElementById(btnId);

    if (btn) {
      btn.addEventListener("click", function () {
        ocultarModulos();
        document.getElementById(moduloId).style.display = "block";
      });
    }
  }; // Abrir los 4 módulos


  abrirModulo("abrirReporteCompras", "moduloReporteCompras");
  abrirModulo("abrirReporteVentas", "moduloReporteVentas");
  abrirModulo("abrirReporteEmpleado", "moduloReporteEmpleado");
  abrirModulo("abrirReporteIngresos", "moduloReporteIngresos");
  abrirModulo("abrirReporteStock", "moduloReporteStock"); // --- Generar reportes ---

  var btnCompras = document.getElementById("btnGenerarReporteCompras");
  if (btnCompras) btnCompras.addEventListener("click", generarReporteCompras);
  var btnVentas = document.getElementById("btnGenerarReporteVentas");
  if (btnVentas) btnVentas.addEventListener("click", generarReporteVentas);
  var btnEmpleado = document.getElementById("btnGenerarReporteEmpleado");
  if (btnEmpleado) btnEmpleado.addEventListener("click", generarReporteEmpleado);
  var btnIngresos = document.getElementById("btnGenerarReporteIngresos");
  if (btnIngresos) btnIngresos.addEventListener("click", generarReporteIngresos);
  var btnStock = document.getElementById("btnGenerarReporteStock");
  if (btnStock) btnStock.addEventListener("click", generarReporteStock);
}); // --- Ocultar todos los módulos ---

function ocultarModulos() {
  var modulos = document.querySelectorAll(".contenedorContendido > div");
  modulos.forEach(function (m) {
    return m.style.display = "none";
  });
} // --- Reporte Compras ---


function generarReporteCompras() {
  var inicio = document.getElementById("fechaInicio").value;
  var fin = document.getElementById("fechaFin").value;

  if (!inicio || !fin) {
    alert("Por favor, selecciona un rango de fechas.");
    return;
  }

  fetch("C_Controlador/Controlador_Reportes.php?reporte=comprasPorFecha&inicio=".concat(inicio, "&fin=").concat(fin)).then(function (response) {
    return response.json();
  }).then(function (data) {
    var tbody = document.querySelector("#tablaReporteCompras tbody");
    tbody.innerHTML = "";

    if (data.error) {
      tbody.innerHTML = "<tr><td colspan=\"4\">Error: ".concat(data.error, "</td></tr>");
      return;
    }

    if (data.length > 0) {
      data.forEach(function (row) {
        tbody.innerHTML += "\n                        <tr>\n                            <td>".concat(row.id_compra, "</td>\n                            <td>").concat(row.fechaHora, "</td>\n                            <td>").concat(row.proveedor, "</td>\n                            <td>").concat(row.total_compra, "</td>\n                        </tr>");
      });
    } else {
      tbody.innerHTML = "<tr><td colspan='4'>No se encontraron registros</td></tr>";
    }
  });
} // --- Reporte Ventas ---


function generarReporteVentas() {
  var inicio = document.getElementById("fechaInicioVentas").value;
  var fin = document.getElementById("fechaFinVentas").value;

  if (!inicio || !fin) {
    alert("Por favor, selecciona un rango de fechas.");
    return;
  }

  fetch("C_Controlador/Controlador_Reportes.php?reporte=ventasPorFecha&inicio=".concat(inicio, "&fin=").concat(fin)).then(function (response) {
    return response.json();
  }).then(function (data) {
    var tbody = document.querySelector("#tablaReporteVentas tbody");
    tbody.innerHTML = "";

    if (data.error) {
      tbody.innerHTML = "<tr><td colspan=\"4\">Error: ".concat(data.error, "</td></tr>");
      return;
    }

    if (data.length > 0) {
      data.forEach(function (row) {
        tbody.innerHTML += "\n                        <tr>\n                            <td>".concat(row.id_venta, "</td>\n                            <td>").concat(row.fechaHora, "</td>\n                            <td>").concat(row.cliente, "</td>\n                            <td>").concat(row.total_venta, "</td>\n                        </tr>");
      });
    } else {
      tbody.innerHTML = "<tr><td colspan='4'>No se encontraron registros</td></tr>";
    }
  });
} // --- Reporte Empleado del Mes ---


function generarReporteEmpleado() {
  var mesSeleccionado = document.getElementById("mesEmpleado").value;

  if (!mesSeleccionado) {
    alert("Selecciona un mes.");
    return;
  }

  var _mesSeleccionado$spli = mesSeleccionado.split("-"),
      _mesSeleccionado$spli2 = _slicedToArray(_mesSeleccionado$spli, 2),
      anio = _mesSeleccionado$spli2[0],
      mes = _mesSeleccionado$spli2[1];

  fetch("C_Controlador/Controlador_Reportes.php?reporte=empleadoDelMes&anio=".concat(anio, "&mes=").concat(mes)).then(function (response) {
    return response.json();
  }).then(function (data) {
    var tbody = document.querySelector("#tablaReporteEmpleado tbody");
    tbody.innerHTML = "";

    if (data.error) {
      tbody.innerHTML = "<tr><td colspan=\"3\">Error: ".concat(data.error, "</td></tr>");
      return;
    }

    if (data.length > 0) {
      data.forEach(function (emp) {
        tbody.innerHTML += "\n                        <tr>\n                            <td>".concat(emp.id_empleado, "</td>\n                            <td>").concat(emp.nombre_completo, "</td>\n                            <td>").concat(emp.total_vendido, "</td>\n                        </tr>");
      });
    } else {
      tbody.innerHTML = "<tr><td colspan='3'>No hay datos disponibles</td></tr>";
    }
  });
} // --- Reporte Ingresos por Día ---


function generarReporteIngresos() {
  var inicioInput = document.getElementById("fechaInicioIngresos");
  var finInput = document.getElementById("fechaFinIngresos");

  if (!inicioInput || !finInput) {
    alert("Faltan los campos de fecha en el formulario.");
    return;
  }

  var inicio = inicioInput.value;
  var fin = finInput.value;

  if (!inicio || !fin) {
    alert("Selecciona un rango de fechas.");
    return;
  }

  fetch("C_Controlador/Controlador_Reportes.php?reporte=ingresosPorDia&inicio=".concat(inicio, "&fin=").concat(fin)).then(function (response) {
    return response.json();
  }).then(function (data) {
    var tbody = document.querySelector("#tablaReporteIngresos tbody");
    tbody.innerHTML = "";

    if (data.error) {
      tbody.innerHTML = "<tr><td colspan=\"4\">Error: ".concat(data.error, "</td></tr>");
      return;
    }

    if (data.length > 0) {
      data.forEach(function (row) {
        tbody.innerHTML += "\n                        <tr>\n                            <td>".concat(row.fecha, "</td>\n                            <td>").concat(row.empleado, "</td>\n                            <td>").concat(row.ventas, "</td>\n                            <td>").concat(row.total_vendido, "</td>\n                        </tr>");
      });
    } else {
      tbody.innerHTML = "<tr><td colspan='4'>No hay ingresos registrados</td></tr>";
    }
  });
}

function generarReporteStock() {
  fetch("C_Controlador/Controlador_Reportes.php?reporte=stockMinimo").then(function (res) {
    return res.json();
  }).then(function (data) {
    var tbody = document.querySelector("#tablaReporteStock tbody");
    tbody.innerHTML = "";

    if (data.error) {
      tbody.innerHTML = "<tr><td colspan=\"7\">Error: ".concat(data.error, "</td></tr>");
      return;
    }

    if (data.length === 0) {
      tbody.innerHTML = "<tr><td colspan=\"7\">No hay productos con stock por debajo del m\xEDnimo.</td></tr>";
      return;
    }

    data.forEach(function (p) {
      tbody.innerHTML += "\n                    <tr>\n                        <td>".concat(p.id_producto, "</td>\n                        <td>").concat(p.nombre, "</td>\n                        <td>").concat(p.descripcion, "</td>\n                        <td>").concat(p.codigoProd, "</td>\n                        <td>").concat(p.stock, "</td>\n                        <td>").concat(p.stock_minimo, "</td>\n                        <td>").concat(p.categoria, "</td>\n                    </tr>");
    });
  })["catch"](function (err) {
    console.error("Error al generar reporte de stock:", err);
    alert("Ocurrió un error inesperado.");
  });
}
//# sourceMappingURL=reportes.dev.js.map

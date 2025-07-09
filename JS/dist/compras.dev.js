"use strict";

// Variables globales
var detallesCompra = [];
var productosDisponibles = [];
var proveedoresDisponibles = [];
var compras = [];
var currentPageCompras = 1;
var itemsPerPageCompras = 10; // Elementos del DOM

var tipoComprobante = document.getElementById('tipoComprobante');
var serieComprobante = document.getElementById('serieComprobante');
var numComprobante = document.getElementById('numComprobante');
var fechaHora = document.getElementById('fechaHora');
var proveedor = document.getElementById('proveedor');
var impuestoInput = document.getElementById('impuesto');
var productoBuscar = document.getElementById('productoBuscar');
var idProductoSeleccionado = document.getElementById('idProductoSeleccionado');
var cantidadDetalle = document.getElementById('cantidadDetalle');
var precioCompraDetalle = document.getElementById('precioCompraDetalle');
var precioVentaDetalle = document.getElementById('precioVentaDetalle');
var btnAgregarDetalle = document.getElementById('btnAgregarDetalle');
var tablaDetallesCompra = document.getElementById('tablaDetallesCompra');
var tbodyDetallesCompra = document.querySelector('.tbodyDetallesCompra');
var totalCompraSpan = document.getElementById('totalCompra');
var impuestoCompraSpan = document.getElementById('impuestoCompra');
var btnRegistrarCompra = document.getElementById('btnRegistrarCompra');
var btnImprimirCompra = document.getElementById('btnImprimirCompra');
var inputBusquedaCompra = document.getElementById('inputBusquedaCompra');
var tablaCompras = document.getElementById('tablaCompras');
var tbodyCompras = document.querySelector('.tbodyCompras');
var btnAnteriorCompras = document.querySelector('.btnAnteriorCompras');
var btnSiguienteCompras = document.querySelector('.btnSiguienteCompras');
var paginacionCompras = document.querySelector('.paginacionCompras');
var modalDetalleCompra = document.getElementById('modalDetalleCompra');
var detalleCompraBody = document.getElementById('detalleCompraBody');
var btnCerrarDetalleCompra = document.getElementById('btnCerrarDetalleCompra');
var sugerenciasProducto = document.getElementById('sugerenciasProducto'); // --- Inicialización ---

document.addEventListener('DOMContentLoaded', function () {
  cargarProveedores();
  cargarProductos();
  setFechaHoraActual();
  cargarUltimosNumeros();
  listarCompras();
}); // --- Autollenar serie y número de comprobante ---

tipoComprobante.addEventListener('change', cargarUltimosNumeros);

function cargarUltimosNumeros() {
  fetch('/Proyecto-Web2/C_Logica/logica_compras.php?action=ultimos_numeros').then(function (res) {
    return res.json();
  }).then(function (data) {
    if (data.status === 'success' && data.data) {
      var tipo = tipoComprobante.value;
      var serie = tipo === 'Factura' ? 'F001' : 'B001';
      var ultimo = 0;

      if (data.data[tipo] && data.data[tipo][0]) {
        ultimo = parseInt(data.data[tipo][0].ultimo_numero) || 0;
      }

      serieComprobante.value = serie;
      numComprobante.value = (ultimo + 1).toString().padStart(8, '0');
    }
  });
} // --- Fecha y hora actual ---


function setFechaHoraActual() {
  var now = new Date();
  var fecha = now.toISOString().slice(0, 19).replace('T', ' ');
  fechaHora.value = fecha;
} // --- Cargar proveedores ---


function cargarProveedores() {
  fetch('/Proyecto-Web2/C_Logica/logica_compras.php?action=proveedores').then(function (res) {
    return res.json();
  }).then(function (data) {
    if (data.status === 'success') {
      proveedoresDisponibles = data.data;
      proveedor.innerHTML = '<option value="">Seleccione</option>';
      data.data.forEach(function (prov) {
        var option = document.createElement('option');
        option.value = prov.id_proveedor;
        option.textContent = prov.nombre;
        proveedor.appendChild(option);
      });
    }
  });
} // --- Cargar productos para autocompletar ---


function cargarProductos() {
  fetch('/Proyecto-Web2/C_Logica/logica_compras.php?action=productos').then(function (res) {
    return res.json();
  }).then(function (data) {
    if (data.status === 'success') {
      productosDisponibles = data.data;
    }
  });
} // --- Autocompletar producto ---


var sugerenciaIndex = -1;
productoBuscar.addEventListener('keydown', function (e) {
  var items = sugerenciasProducto.querySelectorAll('.sugerencia-item');
  if (items.length === 0) return;

  if (e.key === 'ArrowDown') {
    sugerenciaIndex = (sugerenciaIndex + 1) % items.length;
    items.forEach(function (item, idx) {
      item.classList.toggle('active', idx === sugerenciaIndex);
    });
    e.preventDefault();
  } else if (e.key === 'ArrowUp') {
    sugerenciaIndex = (sugerenciaIndex - 1 + items.length) % items.length;
    items.forEach(function (item, idx) {
      item.classList.toggle('active', idx === sugerenciaIndex);
    });
    e.preventDefault();
  } else if (e.key === 'Enter') {
    if (sugerenciaIndex >= 0 && items[sugerenciaIndex]) {
      items[sugerenciaIndex].click();
      sugerenciaIndex = -1;
      e.preventDefault();
    }
  }
});
productoBuscar.addEventListener('input', function () {
  sugerenciaIndex = -1; // Reinicia el índice al escribir

  var query = productoBuscar.value.trim().toLowerCase();
  sugerenciasProducto.innerHTML = '';

  if (query.length === 0) {
    idProductoSeleccionado.value = '';
    return;
  }

  var sugerencias = productosDisponibles.filter(function (p) {
    return p.nombre.toLowerCase().includes(query);
  });
  sugerencias.forEach(function (prod) {
    var div = document.createElement('div');
    div.className = 'sugerencia-item';
    div.textContent = prod.nombre;
    div.addEventListener('click', function () {
      productoBuscar.value = prod.nombre;
      idProductoSeleccionado.value = prod.id_producto;
      sugerenciasProducto.innerHTML = '';
    });
    sugerenciasProducto.appendChild(div);
  });
}); // --- Agregar detalle de producto ---

btnAgregarDetalle.addEventListener('click', function () {
  var idProd = idProductoSeleccionado.value;
  var nombreProd = productoBuscar.value.trim();
  var cantidad = parseFloat(cantidadDetalle.value);
  var precioCompra = parseFloat(precioCompraDetalle.value);
  var precioVenta = parseFloat(precioVentaDetalle.value) || 0;

  if (!idProd || !nombreProd || isNaN(cantidad) || isNaN(precioCompra) || cantidad <= 0 || precioCompra < 0) {
    Swal.fire({
      icon: 'warning',
      title: 'Completa los datos del producto correctamente.'
    });
    return;
  } // Evitar duplicados


  if (detallesCompra.some(function (det) {
    return det.id_producto === idProd;
  })) {
    Swal.fire({
      icon: 'warning',
      title: 'Este producto ya fue agregado.'
    });
    return;
  }

  detallesCompra.push({
    id_producto: idProd,
    nombre: nombreProd,
    cantidad: cantidad,
    precioCompra: precioCompra,
    precioVenta: precioVenta
  });
  limpiarDetalleForm();
  renderDetallesCompra();
});

function limpiarDetalleForm() {
  productoBuscar.value = '';
  idProductoSeleccionado.value = '';
  cantidadDetalle.value = 1;
  precioCompraDetalle.value = '';
  precioVentaDetalle.value = '';
  sugerenciasProducto.innerHTML = '';
} // --- Renderizar detalles en la tabla ---


function renderDetallesCompra() {
  tbodyDetallesCompra.innerHTML = '';
  var total = 0;
  detallesCompra.forEach(function (det, idx) {
    var subtotal = det.cantidad * det.precioCompra;
    total += subtotal;
    var row = document.createElement('tr');
    row.innerHTML = "\n            <td>".concat(det.nombre, "</td>\n            <td>").concat(det.cantidad, "</td>\n            <td>").concat(det.precioCompra.toFixed(2), "</td>\n            <td>").concat(det.precioVenta ? det.precioVenta.toFixed(2) : '-', "</td>\n            <td>").concat(subtotal.toFixed(2), "</td>\n            <td><button type=\"button\" class=\"btn btnQuitarDetalle\" data-idx=\"").concat(idx, "\"><i class=\"fa-solid fa-trash\"></i></button></td>\n        ");
    tbodyDetallesCompra.appendChild(row);
  }); // Quitar detalle

  document.querySelectorAll('.btnQuitarDetalle').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var idx = parseInt(this.dataset.idx);
      detallesCompra.splice(idx, 1);
      renderDetallesCompra();
    });
  }); // Calcular totales

  var impuesto = parseFloat(impuestoInput.value) || 0;
  var montoImpuesto = total * (impuesto / 100);
  totalCompraSpan.textContent = total.toFixed(2);
  impuestoCompraSpan.textContent = montoImpuesto.toFixed(2);
} // --- Registrar compra ---


btnRegistrarCompra.addEventListener('click', function (e) {
  e.preventDefault();

  if (!proveedor.value || detallesCompra.length === 0) {
    Swal.fire({
      icon: 'warning',
      title: 'Completa todos los campos y agrega al menos un producto.'
    });
    return;
  }

  var compraData = {
    tipoComprobante: tipoComprobante.value,
    serieComprobante: serieComprobante.value,
    numComprobante: numComprobante.value,
    fechaHora: fechaHora.value,
    id_empleado: 1,
    // Cambia esto por el ID real del empleado logueado
    id_proveedor: proveedor.value,
    impuesto: parseFloat(impuestoInput.value),
    detalles: detallesCompra
  };
  fetch('/Proyecto-Web2/C_Logica/logica_compras.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(compraData)
  }).then(function (res) {
    return res.json();
  }).then(function (data) {
    if (data.status === 'success') {
      Swal.fire({
        icon: 'success',
        title: 'Compra registrada correctamente'
      });
      detallesCompra = [];
      renderDetallesCompra();
      listarCompras();
      cargarUltimosNumeros();
      setFechaHoraActual();
      document.getElementById('FormRegistrarCompra').reset();
    } else {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: data.message
      });
    }
  })["catch"](function (err) {
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'No se pudo registrar la compra.'
    });
    console.error(err);
  });
}); // --- Listar compras ---

function listarCompras() {
  fetch('/Proyecto-Web2/C_Logica/logica_compras.php?action=listar').then(function (res) {
    return res.json();
  }).then(function (data) {
    if (data.status === 'success') {
      compras = data.data;
      renderCompras();
    }
  });
}

function renderCompras() {
  tbodyCompras.innerHTML = '';
  var totalPages = Math.ceil(compras.length / itemsPerPageCompras) || 1;
  if (currentPageCompras > totalPages) currentPageCompras = totalPages;
  if (currentPageCompras < 1) currentPageCompras = 1;
  var startIdx = (currentPageCompras - 1) * itemsPerPageCompras;
  var endIdx = startIdx + itemsPerPageCompras;
  var paginated = compras.slice(startIdx, endIdx);
  paginated.forEach(function (compra) {
    var row = document.createElement('tr');
    row.innerHTML = "\n            <td>".concat(compra.id_compra, "</td>\n            <td>").concat(compra.tipoComprobante, " ").concat(compra.serieComprobante, "-").concat(compra.numComprobante, "</td>\n            <td>").concat(compra.proveedor, "</td>\n            <td>").concat(compra.fechaHora, "</td>\n            <td>").concat(parseFloat(compra.totalCompra).toFixed(2), "</td>\n            <td>").concat(parseFloat(compra.impuesto).toFixed(2), "</td>\n            <td>").concat(compra.estado == 1 ? 'Activo' : 'Inactivo', "</td>\n            <td>\n                <button class=\"btn btnVerDetalle\" data-id=\"").concat(compra.id_compra, "\"><i class=\"fa-solid fa-eye\"></i></button>\n                <button class=\"btn btnCambiarEstado\" data-id=\"").concat(compra.id_compra, "\" data-estado=\"").concat(compra.estado, "\"><i class=\"fa-solid fa-ban\"></i></button>\n            </td>\n        ");
    tbodyCompras.appendChild(row);
  }); // Eventos para ver detalle y cambiar estado

  document.querySelectorAll('.btnVerDetalle').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var id = this.dataset.id;
      verDetalleCompra(id);
    });
  });
  document.querySelectorAll('.btnCambiarEstado').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var id = this.dataset.id;
      var estado = this.dataset.estado == 1 ? 0 : 1;
      cambiarEstadoCompra(id, estado);
    });
  });
  paginacionCompras.textContent = "".concat(currentPageCompras, " - ").concat(totalPages);
  btnAnteriorCompras.disabled = currentPageCompras <= 1;
  btnSiguienteCompras.disabled = currentPageCompras >= totalPages;
} // --- Paginación compras ---


btnAnteriorCompras.addEventListener('click', function () {
  if (currentPageCompras > 1) {
    currentPageCompras--;
    renderCompras();
  }
});
btnSiguienteCompras.addEventListener('click', function () {
  var totalPages = Math.ceil(compras.length / itemsPerPageCompras) || 1;

  if (currentPageCompras < totalPages) {
    currentPageCompras++;
    renderCompras();
  }
}); // --- Buscar compras ---

inputBusquedaCompra.addEventListener('input', function () {
  var query = this.value.trim().toLowerCase();

  if (!query) {
    renderCompras();
    return;
  }

  var filtradas = compras.filter(function (c) {
    return c.proveedor && c.proveedor.toLowerCase().includes(query) || c.tipoComprobante && c.tipoComprobante.toLowerCase().includes(query) || c.numComprobante && c.numComprobante.toLowerCase().includes(query);
  });
  tbodyCompras.innerHTML = '';
  filtradas.forEach(function (compra) {
    var row = document.createElement('tr');
    row.innerHTML = "\n            <td>".concat(compra.id_compra, "</td>\n            <td>").concat(compra.tipoComprobante, " ").concat(compra.serieComprobante, "-").concat(compra.numComprobante, "</td>\n            <td>").concat(compra.proveedor, "</td>\n            <td>").concat(compra.fechaHora, "</td>\n            <td>").concat(parseFloat(compra.totalCompra).toFixed(2), "</td>\n            <td>").concat(parseFloat(compra.impuesto).toFixed(2), "</td>\n            <td>").concat(compra.estado == 1 ? 'Activo' : 'Inactivo', "</td>\n            <td>\n                <button class=\"btn btnVerDetalle\" data-id=\"").concat(compra.id_compra, "\"><i class=\"fa-solid fa-eye\"></i></button>\n                <button class=\"btn btnCambiarEstado\" data-id=\"").concat(compra.id_compra, "\" data-estado=\"").concat(compra.estado, "\"><i class=\"fa-solid fa-ban\"></i></button>\n            </td>\n        ");
    tbodyCompras.appendChild(row);
  });
}); // --- Ver detalle de compra (modal) ---

function verDetalleCompra(id) {
  fetch("/Proyecto-Web2/C_Logica/logica_compras.php?action=obtener&id=".concat(id)).then(function (res) {
    return res.json();
  }).then(function (data) {
    if (data.status === 'success') {
      detalleCompraBody.innerHTML = '';
      data.data.detalles.forEach(function (det) {
        var subtotal = det.cantidad * det.precioCompra;
        var row = document.createElement('tr');
        row.innerHTML = "\n                        <td>".concat(det.nombre || '', "</td>\n                        <td>").concat(det.cantidad, "</td>\n                        <td>").concat(parseFloat(det.precioCompra).toFixed(2), "</td>\n                        <td>").concat(det.precioVenta ? parseFloat(det.precioVenta).toFixed(2) : '-', "</td>\n                        <td>").concat(subtotal.toFixed(2), "</td>\n                    ");
        detalleCompraBody.appendChild(row);
      });
      modalDetalleCompra.showModal();
    }
  });
}

btnCerrarDetalleCompra.addEventListener('click', function () {
  modalDetalleCompra.close();
}); // --- Cambiar estado de compra ---

function cambiarEstadoCompra(id, estado) {
  fetch('/Proyecto-Web2/C_Logica/logica_compras.php', {
    method: 'PATCH',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      id: id,
      estado: estado
    })
  }).then(function (res) {
    return res.json();
  }).then(function (data) {
    if (data.status === 'success') {
      Swal.fire({
        icon: 'success',
        title: 'Estado actualizado'
      });
      listarCompras();
    } else {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: data.message
      });
    }
  });
} // --- Imprimir o PDF (puedes mejorar esto con librerías como jsPDF) ---


btnImprimirCompra.addEventListener('click', function () {
  window.print();
});
//# sourceMappingURL=compras.dev.js.map

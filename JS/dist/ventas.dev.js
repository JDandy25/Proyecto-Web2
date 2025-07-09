"use strict";

document.addEventListener('DOMContentLoaded', function () {
  var btnRegistrarVenta = document.getElementById('registrarVenta');
  var btnBuscarVenta = document.getElementById('buscarVenta');
  var seccionRegistrarVenta = document.querySelector('.contenidoRegistrarVenta');
  var seccionListaVenta = document.querySelector('.contenidoListaVenta');

  if (btnRegistrarVenta) {
    btnRegistrarVenta.addEventListener('click', function (e) {
      e.preventDefault();
      seccionRegistrarVenta.classList.remove('hidden');
      seccionListaVenta.classList.add('hidden');
      cargarClientes();
      cargarProductos();
      obtenerUltimosNumeros();
      document.getElementById('fechaHoraVenta').value = new Date().toLocaleString();
    });
  }

  if (btnBuscarVenta) {
    btnBuscarVenta.addEventListener('click', function (e) {
      e.preventDefault();
      seccionRegistrarVenta.classList.add('hidden');
      seccionListaVenta.classList.remove('hidden');
      listarVentas();
    });
  }
}); // VARIABLES GLOBALES

var listaDetallesVenta = [];

function cargarClientes() {
  fetch('C_Logica/logica_ventas.php?action=clientes').then(function (res) {
    return res.json();
  }).then(function (data) {
    var select = document.getElementById('cliente');
    select.innerHTML = '<option value="">Seleccione</option>';
    data.forEach(function (c) {
      var option = document.createElement('option');
      option.value = c.id_cliente;
      option.text = c.nombre;
      select.appendChild(option);
    });
  });
}

function cargarProductos() {
  fetch('C_Logica/logica_ventas.php?action=productos').then(function (res) {
    return res.json();
  }).then(function (data) {
    window.productosVenta = data;
  });
} // AUTOCOMPLETADO PRODUCTO


var inputProducto = document.getElementById('productoBuscarVenta');

if (inputProducto) {
  inputProducto.addEventListener('input', function () {
    var input = this.value.toLowerCase();

    if (window.productosVenta) {
      var resultado = window.productosVenta.find(function (p) {
        return p.nombre.toLowerCase().includes(input);
      });

      if (resultado) {
        document.getElementById('idProductoSeleccionadoVenta').value = resultado.id_producto;
        document.getElementById('precioVentaDetalleVenta').value = resultado.precio;
      }
    }
  });
} // AGREGAR DETALLE


var btnAgregar = document.getElementById('btnAgregarDetalleVenta');

if (btnAgregar) {
  btnAgregar.addEventListener('click', function () {
    var id = document.getElementById('idProductoSeleccionadoVenta').value;
    var nombre = document.getElementById('productoBuscarVenta').value;
    var cantidad = parseFloat(document.getElementById('cantidadDetalleVenta').value);
    var precio = parseFloat(document.getElementById('precioVentaDetalleVenta').value);
    var descuento = parseFloat(document.getElementById('descuentoDetalleVenta').value);

    if (id && nombre && cantidad > 0 && precio > 0) {
      listaDetallesVenta.push({
        id_producto: id,
        nombre: nombre,
        cantidad: cantidad,
        precio_venta: precio,
        descuento: descuento
      });
      renderTablaDetalle();
    }
  });
}

function renderTablaDetalle() {
  var tbody = document.querySelector('.tbodyDetallesVenta');
  tbody.innerHTML = '';
  var total = 0;
  var impuesto = parseFloat(document.getElementById('impuestoVenta').value || 0);
  listaDetallesVenta.forEach(function (d, i) {
    var subtotal = d.cantidad * d.precio_venta - d.descuento;
    total += subtotal;
    var row = "<tr>\n            <td>".concat(d.nombre, "</td><td>").concat(d.cantidad, "</td><td>").concat(d.precio_venta, "</td><td>").concat(d.descuento, "</td>\n            <td>").concat(subtotal.toFixed(2), "</td>\n            <td><button onclick=\"quitarDetalle(").concat(i, ")\">Quitar</button></td>\n        </tr>");
    tbody.innerHTML += row;
  });
  document.getElementById('totalVenta').textContent = total.toFixed(2);
  document.getElementById('impuestoVentaTotal').textContent = (total * impuesto / 100).toFixed(2);
}

function quitarDetalle(index) {
  listaDetallesVenta.splice(index, 1);
  renderTablaDetalle();
} // FORMULARIO REGISTRO DE VENTA


var form = document.getElementById('FormRegistrarVenta');

if (form) {
  form.addEventListener('submit', function (e) {
    e.preventDefault();
    var venta = {
      tipoComprobante: document.getElementById('tipoComprobanteVenta').value,
      serie_Comprobante: document.getElementById('serieComprobanteVenta').value,
      numComprobante: document.getElementById('numComprobanteVenta').value,
      fechaHora: document.getElementById('fechaHoraVenta').value,
      impuesto: parseFloat(document.getElementById('impuestoVenta').value),
      total_venta: parseFloat(document.getElementById('totalVenta').textContent),
      id_cliente: document.getElementById('cliente').value,
      id_empleado: document.getElementById('idEmpleadoLogueadoVenta').value,
      estado: 1,
      detalles: listaDetallesVenta
    };
    fetch('C_Logica/logica_ventas.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(venta)
    }).then(function (res) {
      return res.json();
    }).then(function (data) {
      alert('Venta registrada correctamente');
      listaDetallesVenta = [];
      renderTablaDetalle();
    });
  });
}

function obtenerUltimosNumeros() {
  fetch('C_Logica/logica_ventas.php?action=ultimos').then(function (res) {
    return res.json();
  }).then(function (data) {
    var tipo = document.getElementById('tipoComprobanteVenta').value;
    var ult = 0;

    if (data[tipo] && data[tipo][0] && data[tipo][0].ultimo_numero) {
      ult = data[tipo][0].ultimo_numero;
    }

    var nuevoNumero = parseInt(ult) + 1;
    document.getElementById('serieComprobanteVenta').value = tipo === 'Factura' ? 'F001' : 'B001';
    document.getElementById('numComprobanteVenta').value = nuevoNumero.toString().padStart(6, '0');
  });
}

function listarVentas() {
  fetch('C_Logica/logica_ventas.php?action=listar').then(function (res) {
    return res.json();
  }).then(function (ventas) {
    var tbody = document.querySelector('.tbodyVentas');
    tbody.innerHTML = '';
    ventas.forEach(function (v) {
      var row = "<tr>\n                    <td>".concat(v.id_venta, "</td>\n                    <td>").concat(v.ClienteNombre, "</td>\n                    <td>").concat(v.tipoComprobante, " ").concat(v.serie_Comprobante, "-").concat(v.numComprobante, "</td>\n                    <td>").concat(v.fechaHora, "</td>\n                    <td>").concat(v.total_venta, "</td>\n                    <td><button onclick=\"verDetalleVenta(").concat(v.id_venta, ")\">Ver Detalle</button></td>\n                </tr>");
      tbody.innerHTML += row;
    });
  });
}

function verDetalleVenta(id) {
  fetch("C_Logica/logica_ventas.php?action=obtener&id=".concat(id)).then(function (res) {
    return res.json();
  }).then(function (data) {
    var tbody = document.getElementById('detalleVentaBody');
    tbody.innerHTML = '';
    data.detalles.forEach(function (d) {
      var subtotal = d.cantidad * d.precio_venta - d.descuento;
      tbody.innerHTML += "<tr>\n                    <td>".concat(d.nombre, "</td><td>").concat(d.cantidad, "</td><td>").concat(d.precio_venta, "</td>\n                    <td>").concat(d.descuento, "</td><td>").concat(subtotal.toFixed(2), "</td>\n                </tr>");
    });
    document.getElementById('modalDetalleVenta').showModal();
  });
}

var btnCerrarDetalle = document.getElementById('btnCerrarDetalleVenta');

if (btnCerrarDetalle) {
  btnCerrarDetalle.addEventListener('click', function () {
    document.getElementById('modalDetalleVenta').close();
  });
}
//# sourceMappingURL=ventas.dev.js.map

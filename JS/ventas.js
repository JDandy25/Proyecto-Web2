// Variables globales
let detallesVenta = [];
let productosDisponiblesVenta = [];
let clientesDisponibles = [];
let ventas = [];
let currentPageVentas = 1;
const itemsPerPageVentas = 10;

// Elementos del DOM
const tipoComprobanteVenta = document.getElementById('tipoComprobanteVenta');
const serieComprobanteVenta = document.getElementById('serieComprobanteVenta');
const numComprobanteVenta = document.getElementById('numComprobanteVenta');
const fechaHoraVenta = document.getElementById('fechaHoraVenta');
const cliente = document.getElementById('cliente');
const empleado = document.getElementById('empleado');
const impuestoVentaInput = document.getElementById('impuestoVenta');
const productoBuscarVenta = document.getElementById('productoBuscarVenta');
const idProductoSeleccionadoVenta = document.getElementById('idProductoSeleccionadoVenta');
const cantidadDetalleVenta = document.getElementById('cantidadDetalleVenta');
const precioVentaDetalleVenta = document.getElementById('precioVentaDetalleVenta');
const descuentoDetalleVenta = document.getElementById('descuentoDetalleVenta');
const btnAgregarDetalleVenta = document.getElementById('btnAgregarDetalleVenta');
const tablaDetallesVenta = document.getElementById('tablaDetallesVenta');
const tbodyDetallesVenta = document.querySelector('.tbodyDetallesVenta');
const totalVentaSpan = document.getElementById('totalVenta');
const impuestoVentaTotalSpan = document.getElementById('impuestoVentaTotal');
const btnRegistrarVenta = document.getElementById('btnRegistrarVenta');
const btnImprimirVenta = document.getElementById('btnImprimirVenta');
const inputBusquedaVenta = document.getElementById('inputBusquedaVenta');
const tablaVentas = document.getElementById('tablaVentas');
const tbodyVentas = document.querySelector('.tbodyVentas');
const btnAnteriorVentas = document.querySelector('.btnAnteriorVentas');
const btnSiguienteVentas = document.querySelector('.btnSiguienteVentas');
const paginacionVentas = document.querySelector('.paginacionVentas');
const modalDetalleVenta = document.getElementById('modalDetalleVenta');
const detalleVentaBody = document.getElementById('detalleVentaBody');
const btnCerrarDetalleVenta = document.getElementById('btnCerrarDetalleVenta');
const sugerenciasProductoVenta = document.getElementById('sugerenciasProductoVenta');
const idEmpleadoLogueadoVenta = document.getElementById('idEmpleadoLogueadoVenta');

// --- Inicialización ---
document.addEventListener('DOMContentLoaded', () => {
    cargarClientes();
    cargarEmpleados();
    cargarProductosVenta();
    setFechaHoraActualVenta();
    cargarUltimosNumerosVenta();
    listarVentas();
});

// --- Autollenar serie y número de comprobante ---
tipoComprobanteVenta.addEventListener('change', cargarUltimosNumerosVenta);

function cargarUltimosNumerosVenta() {
    fetch('/Proyecto-Web2/C_Logica/logica_ventas.php?action=ultimos_numeros')
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success' && data.data) {
                const tipo = tipoComprobanteVenta.value;
                let serie = tipo === 'Factura' ? 'F001' : 'B001';
                let ultimo = 0;
                if (data.data[tipo] && data.data[tipo][0]) {
                    ultimo = parseInt(data.data[tipo][0].ultimo_numero) || 0;
                }
                serieComprobanteVenta.value = serie;
                numComprobanteVenta.value = (ultimo + 1).toString().padStart(8, '0');
            }
        });
}

// --- Fecha y hora actual ---
function setFechaHoraActualVenta() {
    const now = new Date();
    const fecha = now.toISOString().slice(0, 19).replace('T', ' ');
    fechaHoraVenta.value = fecha;
}

// --- Cargar clientes ---
function cargarClientes() {
    fetch('/Proyecto-Web2/C_Logica/logica_ventas.php?action=clientes')
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                clientesDisponibles = data.data;
                cliente.innerHTML = '<option value="">Seleccione</option>';
                data.data.forEach(cli => {
                    const option = document.createElement('option');
                    option.value = cli.id_cliente;
                    option.textContent = cli.nombre; // <-- CORREGIDO
                    cliente.appendChild(option);
                });
            }
        });
}

// --- Cargar Empleados ---
function cargarEmpleados() {
    fetch('/Proyecto-Web2/C_Logica/logica_ventas.php?action=empleados')
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                empleadosDisponibles = data.data;
                empleado.innerHTML = '<option value="">Seleccione</option>';
                data.data.forEach(cli => {
                    const option = document.createElement('option');
                    option.value = cli.id_empleado;
                    option.textContent = cli.nombre; // <-- CORREGIDO
                    empleado.appendChild(option);
                });
            }
        });
}

// --- Cargar productos para autocompletar ---
function cargarProductosVenta() {
    fetch('/Proyecto-Web2/C_Logica/logica_ventas.php?action=productos')
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                productosDisponiblesVenta = data.data;
            }
        });
}

// --- Autocompletar producto ---
let sugerenciaIndexVenta = -1;

productoBuscarVenta.addEventListener('keydown', function(e) {
    const items = sugerenciasProductoVenta.querySelectorAll('.sugerencia-item');
    if (items.length === 0) return;

    if (e.key === 'ArrowDown') {
        sugerenciaIndexVenta = (sugerenciaIndexVenta + 1) % items.length;
        items.forEach((item, idx) => {
            item.classList.toggle('active', idx === sugerenciaIndexVenta);
        });
        e.preventDefault();
    } else if (e.key === 'ArrowUp') {
        sugerenciaIndexVenta = (sugerenciaIndexVenta - 1 + items.length) % items.length;
        items.forEach((item, idx) => {
            item.classList.toggle('active', idx === sugerenciaIndexVenta);
        });
        e.preventDefault();
    } else if (e.key === 'Enter') {
        if (sugerenciaIndexVenta >= 0 && items[sugerenciaIndexVenta]) {
            items[sugerenciaIndexVenta].click();
            sugerenciaIndexVenta = -1;
            e.preventDefault();
        }
    }
});

productoBuscarVenta.addEventListener('input', function () {
    sugerenciaIndexVenta = -1;
    const query = productoBuscarVenta.value.trim().toLowerCase();
    sugerenciasProductoVenta.innerHTML = '';
    if (query.length === 0) {
        idProductoSeleccionadoVenta.value = '';
        precioVentaDetalleVenta.value = ''; // Limpia el precio si no hay producto
        return;
    }
    const sugerencias = productosDisponiblesVenta.filter(p =>
        p.nombre.toLowerCase().includes(query)
    );
    sugerencias.forEach(prod => {
        const div = document.createElement('div');
        div.className = 'sugerencia-item';
        div.textContent = prod.nombre;
        div.addEventListener('click', () => {
            productoBuscarVenta.value = prod.nombre;
            idProductoSeleccionadoVenta.value = prod.id_producto;
            precioVentaDetalleVenta.value = prod.precio; // <-- Autollenar precio aquí
            sugerenciasProductoVenta.innerHTML = '';
        });
        sugerenciasProductoVenta.appendChild(div);
    });
});

// --- Agregar detalle de producto ---
btnAgregarDetalleVenta.addEventListener('click', function () {
    const idProd = idProductoSeleccionadoVenta.value;
    const nombreProd = productoBuscarVenta.value.trim();
    const cantidad = parseFloat(cantidadDetalleVenta.value);
    const precioVenta = parseFloat(precioVentaDetalleVenta.value); // <--- CORREGIDO
    const descuento = parseFloat(descuentoDetalleVenta.value) || 0;

    if (!idProd || !nombreProd || isNaN(cantidad) || isNaN(precioVenta) || cantidad <= 0 || precioVenta < 0) {
        Swal.fire({ icon: 'warning', title: 'Completa los datos del producto correctamente.' });
        return;
    }

    // Evitar duplicados
    if (detallesVenta.some(det => det.id_producto === idProd)) {
        Swal.fire({ icon: 'warning', title: 'Este producto ya fue agregado.' });
        return;
    }

    detallesVenta.push({
        id_producto: idProd,
        nombre: nombreProd,
        cantidad,
        precioVenta: precioVenta,
        descuento
    });

    limpiarDetalleFormVenta();
    renderDetallesVenta();
});

function limpiarDetalleFormVenta() {
    productoBuscarVenta.value = '';
    idProductoSeleccionadoVenta.value = '';
    cantidadDetalleVenta.value = 1;
    precioVentaDetalle.value = '';
    descuentoDetalleVenta.value = 0;
    sugerenciasProductoVenta.innerHTML = '';
}

// --- Renderizar detalles en la tabla ---
function renderDetallesVenta() {
    tbodyDetallesVenta.innerHTML = '';
    let total = 0;
    detallesVenta.forEach((det, idx) => {
        const subtotal = det.cantidad * det.precioVenta - det.descuento;
        total += subtotal;
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${det.nombre}</td>
            <td>${det.cantidad}</td>
            <td>${det.precioVenta.toFixed(2)}</td>
            <td>${det.descuento ? det.descuento.toFixed(2) : '-'}</td>
            <td>${subtotal.toFixed(2)}</td>
            <td><button type="button" class="btn btnQuitarDetalleVenta" data-idx="${idx}"><i class="fa-solid fa-trash"></i></button></td>
        `;
        tbodyDetallesVenta.appendChild(row);
    });

    // Quitar detalle
    document.querySelectorAll('.btnQuitarDetalleVenta').forEach(btn => {
        btn.addEventListener('click', function () {
            const idx = parseInt(this.dataset.idx);
            detallesVenta.splice(idx, 1);
            renderDetallesVenta();
        });
    });

    // Calcular totales
    const impuesto = parseFloat(impuestoVentaInput.value) || 0;
    const montoImpuesto = total * (impuesto / 100);
    totalVentaSpan.textContent = total.toFixed(2);
    impuestoVentaTotalSpan.textContent = montoImpuesto.toFixed(2);
}

// --- Registrar venta ---
btnRegistrarVenta.addEventListener('click', function (e) {
    e.preventDefault();

    if (!cliente.value || detallesVenta.length === 0) {
        Swal.fire({ icon: 'warning', title: 'Completa todos los campos y agrega al menos un producto.' });
        return;
    }

    const ventaData = {
        tipoComprobante: tipoComprobanteVenta.value,
        serieComprobante: serieComprobanteVenta.value,
        numComprobante: numComprobanteVenta.value,
        fechaHora: fechaHoraVenta.value,
        id_empleado: empleado.value,
        id_cliente: cliente.value,
        impuesto: parseFloat(impuestoVentaInput.value),
        detalles: detallesVenta
    };

    fetch('/Proyecto-Web2/C_Logica/logica_ventas.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(ventaData)
    })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                Swal.fire({ icon: 'success', title: 'Venta registrada correctamente' });
                detallesVenta = [];
                renderDetallesVenta();
                listarVentas();
                cargarUltimosNumerosVenta();
                setFechaHoraActualVenta();
                document.getElementById('FormRegistrarVenta').reset();
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: data.message });
            }
        })
        .catch(err => {
            Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudo registrar la venta.' });
            console.error(err);
        });
});

// --- Listar ventas ---
function listarVentas() {
    fetch('/Proyecto-Web2/C_Logica/logica_ventas.php?action=listar')
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                ventas = data.data;
                renderVentas();
            }
        });
}

function renderVentas() {
    tbodyVentas.innerHTML = '';
    const totalPages = Math.ceil(ventas.length / itemsPerPageVentas) || 1;
    if (currentPageVentas > totalPages) currentPageVentas = totalPages;
    if (currentPageVentas < 1) currentPageVentas = 1;
    const startIdx = (currentPageVentas - 1) * itemsPerPageVentas;
    const endIdx = startIdx + itemsPerPageVentas;
    const paginated = ventas.slice(startIdx, endIdx);

    paginated.forEach(venta => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${venta.id_venta}</td>
            <td>${venta.tipoComprobante} ${venta.serieComprobante}-${venta.numComprobante}</td>
            <td>${venta.cliente}</td>
            <td>${venta.fechaHora}</td>
            <td>${parseFloat(venta.totalVenta).toFixed(2)}</td>
            <td>${parseFloat(venta.impuesto).toFixed(2)}</td>
            <td>${venta.estado == 1 ? 'Activo' : 'Inactivo'}</td>
            <td>
                <button class="btn btnVerDetalleVenta" data-id="${venta.id_venta}"><i class="fa-solid fa-eye"></i></button>
                <button class="btn btnCambiarEstadoVenta" data-id="${venta.id_venta}" data-estado="${venta.estado}"><i class="fa-solid fa-ban"></i></button>
            </td>
        `;
        tbodyVentas.appendChild(row);
    });

    // Eventos para ver detalle y cambiar estado
    document.querySelectorAll('.btnVerDetalleVenta').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;
            verDetalleVenta(id);
        });
    });
    document.querySelectorAll('.btnCambiarEstadoVenta').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;
            const estado = this.dataset.estado == 1 ? 0 : 1;
            cambiarEstadoVenta(id, estado);
        });
    });

    paginacionVentas.textContent = `${currentPageVentas} - ${totalPages}`;
    btnAnteriorVentas.disabled = currentPageVentas <= 1;
    btnSiguienteVentas.disabled = currentPageVentas >= totalPages;
}

// --- Paginación ventas ---
btnAnteriorVentas.addEventListener('click', () => {
    if (currentPageVentas > 1) {
        currentPageVentas--;
        renderVentas();
    }
});
btnSiguienteVentas.addEventListener('click', () => {
    const totalPages = Math.ceil(ventas.length / itemsPerPageVentas) || 1;
    if (currentPageVentas < totalPages) {
        currentPageVentas++;
        renderVentas();
    }
});

// --- Buscar ventas ---
inputBusquedaVenta.addEventListener('input', function () {
    const query = this.value.trim().toLowerCase();
    if (!query) {
        renderVentas();
        return;
    }
    const filtradas = ventas.filter(v =>
        (v.cliente && v.cliente.toLowerCase().includes(query)) ||
        (v.tipoComprobante && v.tipoComprobante.toLowerCase().includes(query)) ||
        (v.numComprobante && v.numComprobante.toLowerCase().includes(query))
    );
    tbodyVentas.innerHTML = '';
    filtradas.forEach(venta => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${venta.id_venta}</td>
            <td>${venta.tipoComprobante} ${venta.serieComprobante}-${venta.numComprobante}</td>
            <td>${venta.cliente}</td>
            <td>${venta.fechaHora}</td>
            <td>${parseFloat(venta.totalVenta).toFixed(2)}</td>
            <td>${parseFloat(venta.impuesto).toFixed(2)}</td>
            <td>${venta.estado == 1 ? 'Activo' : 'Inactivo'}</td>
            <td>
                <button class="btn btnVerDetalleVenta" data-id="${venta.id_venta}"><i class="fa-solid fa-eye"></i></button>
                <button class="btn btnCambiarEstadoVenta" data-id="${venta.id_venta}" data-estado="${venta.estado}"><i class="fa-solid fa-ban"></i></button>
            </td>
        `;
        tbodyVentas.appendChild(row);
    });
});

// --- Ver detalle de venta (modal) ---
function verDetalleVenta(id) {
    fetch(`/Proyecto-Web2/C_Logica/logica_ventas.php?action=obtener&id=${id}`)
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                detalleVentaBody.innerHTML = '';
                data.data.detalles.forEach(det => {
                    const subtotal = det.cantidad * det.precioVenta - det.descuento;
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${det.nombre || ''}</td>
                        <td>${det.cantidad}</td>
                        <td>${parseFloat(det.precioVenta).toFixed(2)}</td>
                        <td>${det.descuento ? parseFloat(det.descuento).toFixed(2) : '-'}</td>
                        <td>${subtotal.toFixed(2)}</td>
                    `;
                    detalleVentaBody.appendChild(row);
                });
                modalDetalleVenta.showModal();
            }
        });
}
btnCerrarDetalleVenta.addEventListener('click', () => {
    modalDetalleVenta.close();
});

// --- Cambiar estado de venta ---
function cambiarEstadoVenta(id, estado) {
    fetch('/Proyecto-Web2/C_Logica/logica_ventas.php', {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id, estado })
    })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                Swal.fire({ icon: 'success', title: 'Estado actualizado' });
                listarVentas();
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: data.message });
            }
        });
}

// --- Imprimir o PDF (puedes mejorar esto con librerías como jsPDF) ---
btnImprimirVenta.addEventListener('click', function () {
    window.print();
});

// --- Exportar a PDF (opcional) ---
// Puedes implementar una función para exportar los detalles de la venta a PDF usando jsPDF o
btnImprimirVenta.addEventListener('click', function () {
    // Obtén los datos del formulario y los detalles
    const clienteNombre = cliente.options[cliente.selectedIndex]?.text || '';
    const tipoComprobante = tipoComprobanteVenta.value;
    const serie = serieComprobanteVenta.value;
    const numero = numComprobanteVenta.value;
    const fecha = fechaHoraVenta.value;
    const impuesto = impuestoVentaInput.value;
    const total = totalVentaSpan.textContent;

    if (detallesVenta.length === 0) {
        Swal.fire({ icon: 'warning', title: 'Agrega al menos un producto para imprimir.' });
        return;
    }

    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    doc.setFontSize(16);
    doc.text('Comprobante de Venta', 14, 15);

    doc.setFontSize(11);
    doc.text(`Tipo: ${tipoComprobante}`, 14, 25);
    doc.text(`Serie: ${serie}`, 80, 25);
    doc.text(`Número: ${numero}`, 140, 25);
    doc.text(`Fecha: ${fecha}`, 14, 32);
    doc.text(`Cliente: ${clienteNombre}`, 14, 39);

    // Tabla de detalles
    const columns = [
        { header: 'Producto', dataKey: 'nombre' },
        { header: 'Cantidad', dataKey: 'cantidad' },
        { header: 'Precio Venta', dataKey: 'precioVenta' },
        { header: 'Descuento', dataKey: 'descuento' },
        { header: 'Subtotal', dataKey: 'subtotal' }
    ];

    const rows = detallesVenta.map(det => ({
        nombre: det.nombre,
        cantidad: det.cantidad,
        precioVenta: parseFloat(det.precioVenta).toFixed(2),
        descuento: det.descuento ? parseFloat(det.descuento).toFixed(2) : '0.00',
        subtotal: (det.cantidad * det.precioVenta - det.descuento).toFixed(2)
    }));

    doc.autoTable({
        columns,
        body: rows,
        startY: 45,
        theme: 'grid'
    });

    // Totales
    let finalY = doc.lastAutoTable.finalY || 45;
    doc.text(`Impuesto: S/ ${impuestoVentaTotalSpan.textContent}`, 14, finalY + 10);
    doc.text(`Total: S/ ${total}`, 14, finalY + 17);

    doc.save(`Venta_${serie}-${numero}.pdf`);
});
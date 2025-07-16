// Variables globales
let detallesCompra = [];
let productosDisponibles = [];
let proveedoresDisponibles = [];
let compras = [];
let currentPageCompras = 1;
const itemsPerPageCompras = 10;

// Elementos del DOM
const tipoComprobante = document.getElementById('tipoComprobante');
const serieComprobante = document.getElementById('serieComprobante');
const numComprobante = document.getElementById('numComprobante');
const fechaHora = document.getElementById('fechaHora');
const proveedor = document.getElementById('proveedor');
const empleadosc = document.getElementById('empleadosc');
const impuestoInput = document.getElementById('impuesto');
const productoBuscar = document.getElementById('productoBuscar');
const idProductoSeleccionado = document.getElementById('idProductoSeleccionado');
const cantidadDetalle = document.getElementById('cantidadDetalle');
const precioCompraDetalle = document.getElementById('precioCompraDetalle');
const precioVentaDetalle = document.getElementById('precioVentaDetalle');
const btnAgregarDetalle = document.getElementById('btnAgregarDetalle');
const tablaDetallesCompra = document.getElementById('tablaDetallesCompra');
const tbodyDetallesCompra = document.querySelector('.tbodyDetallesCompra');
const totalCompraSpan = document.getElementById('totalCompra');
const impuestoCompraSpan = document.getElementById('impuestoCompra');
const btnRegistrarCompra = document.getElementById('btnRegistrarCompra');
const btnImprimirCompra = document.getElementById('btnImprimirCompra');
const inputBusquedaCompra = document.getElementById('inputBusquedaCompra');
const tablaCompras = document.getElementById('tablaCompras');
const tbodyCompras = document.querySelector('.tbodyCompras');
const btnAnteriorCompras = document.querySelector('.btnAnteriorCompras');
const btnSiguienteCompras = document.querySelector('.btnSiguienteCompras');
const paginacionCompras = document.querySelector('.paginacionCompras');
const modalDetalleCompra = document.getElementById('modalDetalleCompra');
const detalleCompraBody = document.getElementById('detalleCompraBody');
const btnCerrarDetalleCompra = document.getElementById('btnCerrarDetalleCompra');
const sugerenciasProducto = document.getElementById('sugerenciasProducto');

// --- Inicialización ---
document.addEventListener('DOMContentLoaded', () => {
    cargarProveedores();
    cargarEmpleadosc();
    cargarProductos();
    
    setFechaHoraActual();
    cargarUltimosNumeros();
    listarCompras();
});

// --- Autollenar serie y número de comprobante ---
tipoComprobante.addEventListener('change', cargarUltimosNumeros);

function cargarUltimosNumeros() {
    fetch('/Proyecto-Web2/C_Logica/logica_compras.php?action=ultimos_numeros')
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success' && data.data) {
                const tipo = tipoComprobante.value;
                let serie = tipo === 'Factura' ? 'F001' : 'B001';
                let ultimo = 0;
                if (data.data[tipo] && data.data[tipo][0]) {
                    ultimo = parseInt(data.data[tipo][0].ultimo_numero) || 0;
                }
                serieComprobante.value = serie;
                numComprobante.value = (ultimo + 1).toString().padStart(8, '0');
            }
        });
}

// --- Fecha y hora actual ---
function setFechaHoraActual() {
    const now = new Date();
    const fecha = now.toISOString().slice(0, 19).replace('T', ' ');
    fechaHora.value = fecha;
}

// --- Cargar proveedores ---
function cargarProveedores() {
    fetch('/Proyecto-Web2/C_Logica/logica_compras.php?action=proveedores')
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                proveedoresDisponibles = data.data;
                proveedor.innerHTML = '<option value="">Seleccione</option>';
                data.data.forEach(prov => {
                    const option = document.createElement('option');
                    option.value = prov.id_proveedor;
                    option.textContent = prov.nombres;
                    proveedor.appendChild(option);
                });
            }
        });
}

function cargarEmpleadosc() {
    fetch('/Proyecto-Web2/C_Logica/logica_compras.php?action=empleados')
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                empleadoscDisponibles = data.data;
                empleadosc.innerHTML = '<option value="">Seleccione</option>';
                data.data.forEach(emp => {
                    const option = document.createElement('option');
                    option.value = emp.id_empleado;
                    option.textContent = emp.nombre;
                    empleadosc.appendChild(option);
                });
            }
        });
}

// --- Cargar productos para autocompletar ---
function cargarProductos() {
    fetch('/Proyecto-Web2/C_Logica/logica_compras.php?action=productos')
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                productosDisponibles = data.data;
            }
        });
}

// --- Autocompletar producto ---
let sugerenciaIndex = -1;

productoBuscar.addEventListener('keydown', function(e) {
    const items = sugerenciasProducto.querySelectorAll('.sugerencia-item');
    if (items.length === 0) return;

    if (e.key === 'ArrowDown') {
        sugerenciaIndex = (sugerenciaIndex + 1) % items.length;
        items.forEach((item, idx) => {
            item.classList.toggle('active', idx === sugerenciaIndex);
        });
        e.preventDefault();
    } else if (e.key === 'ArrowUp') {
        sugerenciaIndex = (sugerenciaIndex - 1 + items.length) % items.length;
        items.forEach((item, idx) => {
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
    const query = productoBuscar.value.trim().toLowerCase();
    sugerenciasProducto.innerHTML = '';
    if (query.length === 0) {
        idProductoSeleccionado.value = '';
        return;
    }
    const sugerencias = productosDisponibles.filter(p =>
        p.nombre.toLowerCase().includes(query)
    );
    sugerencias.forEach(prod => {
        const div = document.createElement('div');
        div.className = 'sugerencia-item';
        div.textContent = prod.nombre;
        div.addEventListener('click', () => {
            productoBuscar.value = prod.nombre;
            idProductoSeleccionado.value = prod.id_producto;
            sugerenciasProducto.innerHTML = '';
        });
        sugerenciasProducto.appendChild(div);
    });
});

// --- Agregar detalle de producto ---
btnAgregarDetalle.addEventListener('click', function () {
    const idProd = idProductoSeleccionado.value;
    const nombreProd = productoBuscar.value.trim();
    const cantidad = parseFloat(cantidadDetalle.value);
    const precioCompra = parseFloat(precioCompraDetalle.value);
    const precioVenta = parseFloat(precioVentaDetalle.value) || 0;

    if (!idProd || !nombreProd || isNaN(cantidad) || isNaN(precioCompra) || cantidad <= 0 || precioCompra < 0) {
        Swal.fire({ icon: 'warning', title: 'Completa los datos del producto correctamente.' });
        return;
    }

    // Evitar duplicados
    if (detallesCompra.some(det => det.id_producto === idProd)) {
        Swal.fire({ icon: 'warning', title: 'Este producto ya fue agregado.' });
        return;
    }

    detallesCompra.push({
        id_producto: idProd,
        nombre: nombreProd,
        cantidad,
        precioCompra,
        precioVenta
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
}

// --- Renderizar detalles en la tabla ---
function renderDetallesCompra() {
    tbodyDetallesCompra.innerHTML = '';
    let total = 0;
    detallesCompra.forEach((det, idx) => {
        const subtotal = det.cantidad * det.precioCompra;
        total += subtotal;
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${det.nombre}</td>
            <td>${det.cantidad}</td>
            <td>${det.precioCompra.toFixed(2)}</td>
            <td>${det.precioVenta ? det.precioVenta.toFixed(2) : '-'}</td>
            <td>${subtotal.toFixed(2)}</td>
            <td><button type="button" class="btn btnQuitarDetalle" data-idx="${idx}"><i class="fa-solid fa-trash"></i></button></td>
        `;
        tbodyDetallesCompra.appendChild(row);
    });

    // Quitar detalle
    document.querySelectorAll('.btnQuitarDetalle').forEach(btn => {
        btn.addEventListener('click', function () {
            const idx = parseInt(this.dataset.idx);
            detallesCompra.splice(idx, 1);
            renderDetallesCompra();
        });
    });

    // Calcular totales
    const impuesto = parseFloat(impuestoInput.value) || 0;
    const montoImpuesto = total * (impuesto / 100);
    totalCompraSpan.textContent = total.toFixed(2);
    impuestoCompraSpan.textContent = montoImpuesto.toFixed(2);
}

// --- Registrar compra ---
btnRegistrarCompra.addEventListener('click', function (e) {
    e.preventDefault();

    if (!proveedor.value || detallesCompra.length === 0) {
        Swal.fire({ icon: 'warning', title: 'Completa todos los campos y agrega al menos un producto.' });
        return;
    }

    const compraData = {
        tipoComprobante: tipoComprobante.value,
        serieComprobante: serieComprobante.value,
        numComprobante: numComprobante.value,
        fechaHora: fechaHora.value,
        id_empleado: empleadosc.value, 
        id_proveedor: proveedor.value,
        impuesto: parseFloat(impuestoInput.value),
        detalles: detallesCompra
    };

    fetch('/Proyecto-Web2/C_Logica/logica_compras.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(compraData)
    })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                Swal.fire({ icon: 'success', title: 'Compra registrada correctamente' });
                detallesCompra = [];
                renderDetallesCompra();
                listarCompras();
                cargarUltimosNumeros();
                setFechaHoraActual();
                document.getElementById('FormRegistrarCompra').reset();
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: data.message });
            }
        })
        .catch(err => {
            Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudo registrar la compra.' });
            console.error(err);
        });
});

// --- Listar compras ---
function listarCompras() {
    fetch('/Proyecto-Web2/C_Logica/logica_compras.php?action=listar')
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                compras = data.data;
                renderCompras();
            }
        });
}

function renderCompras() {
    tbodyCompras.innerHTML = '';
    const totalPages = Math.ceil(compras.length / itemsPerPageCompras) || 1;
    if (currentPageCompras > totalPages) currentPageCompras = totalPages;
    if (currentPageCompras < 1) currentPageCompras = 1;
    const startIdx = (currentPageCompras - 1) * itemsPerPageCompras;
    const endIdx = startIdx + itemsPerPageCompras;
    const paginated = compras.slice(startIdx, endIdx);

    paginated.forEach(compra => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${compra.id_compra}</td>
            <td>${compra.tipoComprobante} ${compra.serieComprobante}-${compra.numComprobante}</td>
            <td>${compra.proveedor}</td>
            <td>${compra.fechaHora}</td>
            <td>${parseFloat(compra.totalCompra).toFixed(2)}</td>
            <td>${parseFloat(compra.impuesto).toFixed(2)}</td>
            <td>${compra.estado == 1 ? 'Activo' : 'Inactivo'}</td>
            <td>
                <button class="btn btnVerDetalle" data-id="${compra.id_compra}"><i class="fa-solid fa-eye"></i></button>
                <button class="btn btnCambiarEstado" data-id="${compra.id_compra}" data-estado="${compra.estado}"><i class="fa-solid fa-ban"></i></button>
            </td>
        `;
        tbodyCompras.appendChild(row);
    });

    // Eventos para ver detalle y cambiar estado
    document.querySelectorAll('.btnVerDetalle').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;
            verDetalleCompra(id);
        });
    });
    document.querySelectorAll('.btnCambiarEstado').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;
            const estado = this.dataset.estado == 1 ? 0 : 1;
            cambiarEstadoCompra(id, estado);
        });
    });

    paginacionCompras.textContent = `${currentPageCompras} - ${totalPages}`;
    btnAnteriorCompras.disabled = currentPageCompras <= 1;
    btnSiguienteCompras.disabled = currentPageCompras >= totalPages;
}

// --- Paginación compras ---
btnAnteriorCompras.addEventListener('click', () => {
    if (currentPageCompras > 1) {
        currentPageCompras--;
        renderCompras();
    }
});
btnSiguienteCompras.addEventListener('click', () => {
    const totalPages = Math.ceil(compras.length / itemsPerPageCompras) || 1;
    if (currentPageCompras < totalPages) {
        currentPageCompras++;
        renderCompras();
    }
});

// --- Buscar compras ---
inputBusquedaCompra.addEventListener('input', function () {
    const query = this.value.trim().toLowerCase();
    if (!query) {
        renderCompras();
        return;
    }
    const filtradas = compras.filter(c =>
        (c.proveedor && c.proveedor.toLowerCase().includes(query)) ||
        (c.tipoComprobante && c.tipoComprobante.toLowerCase().includes(query)) ||
        (c.numComprobante && c.numComprobante.toLowerCase().includes(query))
    );
    tbodyCompras.innerHTML = '';
    filtradas.forEach(compra => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${compra.id_compra}</td>
            <td>${compra.tipoComprobante} ${compra.serieComprobante}-${compra.numComprobante}</td>
            <td>${compra.proveedor}</td>
            <td>${compra.fechaHora}</td>
            <td>${parseFloat(compra.totalCompra).toFixed(2)}</td>
            <td>${parseFloat(compra.impuesto).toFixed(2)}</td>
            <td>${compra.estado == 1 ? 'Activo' : 'Inactivo'}</td>
            <td>
                <button class="btn btnVerDetalle" data-id="${compra.id_compra}"><i class="fa-solid fa-eye"></i></button>
                <button class="btn btnCambiarEstado" data-id="${compra.id_compra}" data-estado="${compra.estado}"><i class="fa-solid fa-ban"></i></button>
            </td>
        `;
        tbodyCompras.appendChild(row);
    });
});

// --- Ver detalle de compra (modal) ---
function verDetalleCompra(id) {
    fetch(`/Proyecto-Web2/C_Logica/logica_compras.php?action=obtener&id=${id}`)
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                detalleCompraBody.innerHTML = '';
                data.data.detalles.forEach(det => {
                    const subtotal = det.cantidad * det.precioCompra;
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${det.nombre || ''}</td>
                        <td>${det.cantidad}</td>
                        <td>${parseFloat(det.precioCompra).toFixed(2)}</td>
                        <td>${det.precioVenta ? parseFloat(det.precioVenta).toFixed(2) : '-'}</td>
                        <td>${subtotal.toFixed(2)}</td>
                    `;
                    detalleCompraBody.appendChild(row);
                });
                modalDetalleCompra.showModal();
            }
        });
}
btnCerrarDetalleCompra.addEventListener('click', () => {
    modalDetalleCompra.close();
});

// --- Cambiar estado de compra ---
function cambiarEstadoCompra(id, estado) {
    fetch('/Proyecto-Web2/C_Logica/logica_compras.php', {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id, estado })
    })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                Swal.fire({ icon: 'success', title: 'Estado actualizado' });
                listarCompras();
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: data.message });
            }
        });
}

// --- Imprimir o PDF (puedes mejorar esto con librerías como jsPDF) ---
btnImprimirCompra.addEventListener('click', function () {
    window.print();
});

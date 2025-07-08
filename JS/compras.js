// Variables globales para compras
let compras = [];
let filteredCompras = [];
let currentPageCompras = 1;
const itemsPerPageCompras = 10;
let detallesCompra = []; // Array para almacenar los detalles temporales de la compra
let contadorFacturas = 1;
let contadorBoletas = 1;

// Elementos del DOM
const btnRegistrarCompra = document.getElementById('btnRegistrarCompra');
const btnAgregarDetalle = document.getElementById('btnAgregarDetalle');
const btnCancelarCompra = document.getElementById('cancelar_registro_compra');
const modalDetalleCompra = document.getElementById('modalDetalleCompra');
const btnCerrarDetalleCompra = document.getElementById('btnCerrarModalDetalle');
const modalAnularCompra = document.getElementById('modalAnularCompra');
const btnAnularCompra = document.getElementById('btnAnularCompra');
const btnCancelarAnulacion = document.getElementById('btnCancelarAnulacion');
const btnConfirmarAnulacion = document.getElementById('btnConfirmarAnulacion');

// Cargar datos iniciales
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar controles de comprobante
    inicializarControlesComprobante();
    
    // Resto de tu inicialización...
    cargarProveedoresYProductos();
    listarCompras();
});

cargarProveedoresYProductos();

// Función para cargar proveedores y productos en los selects
function cargarProveedoresYProductos() {
    fetch('/Proyecto-Web2/C_Logica/logica_compras.php?action=obtener_proveedores_productos')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // Cargar proveedores
                const selectProveedor = document.getElementById('idProveedor');
                data.proveedores.forEach(proveedor => {
                    const option = document.createElement('option');
                    option.value = proveedor.id_proveedor;
                    option.textContent = proveedor.nombre;
                    selectProveedor.appendChild(option);
                });

                // Cargar productos
                const selectProducto = document.getElementById('idProducto');
                data.productos.forEach(producto => {
                    const option = document.createElement('option');
                    option.value = producto.id_producto;
                    option.textContent = producto.nombre;
                    option.dataset.precioCompra = producto.precio_compra || 0;
                    option.dataset.precioVenta = producto.precio_venta || 0;
                    selectProducto.appendChild(option);
                });

                // Evento para cargar precios cuando se selecciona un producto
                selectProducto.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    document.getElementById('precioCompra').value = selectedOption.dataset.precioCompra;
                    document.getElementById('precioVenta').value = selectedOption.dataset.precioVenta;
                });
            }
        })
        .catch(error => console.error('Error:', error));
}

// Función para agregar un detalle a la compra
btnAgregarDetalle.addEventListener('click', function() {
    const idProducto = document.getElementById('idProducto').value;
    const productoText = document.getElementById('idProducto').options[document.getElementById('idProducto').selectedIndex].text;
    const cantidad = parseFloat(document.getElementById('cantidad').value);
    const precioCompra = parseFloat(document.getElementById('precioCompra').value);
    const precioVenta = parseFloat(document.getElementById('precioVenta').value);

    if (!idProducto || isNaN(cantidad) || cantidad <= 0 || isNaN(precioCompra) || precioCompra <= 0 || isNaN(precioVenta) || precioVenta <= 0) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Complete todos los campos del detalle correctamente'
        });
        return;
    }

    // Verificar si el producto ya está en los detalles
    const detalleExistente = detallesCompra.find(d => d.id_producto == idProducto);
    if (detalleExistente) {
        detalleExistente.cantidad += cantidad;
    } else {
        detallesCompra.push({
            id_producto: idProducto,
            producto: productoText,
            cantidad: cantidad,
            precio_compra: precioCompra,
            precio_venta: precioVenta,
            subtotal: cantidad * precioCompra
        });
    }

    actualizarTablaDetalles();
    calcularTotal();
    document.getElementById('idProducto').value = '';
    document.getElementById('cantidad').value = 1;
    document.getElementById('precioCompra').value = '';
    document.getElementById('precioVenta').value = '';
});

// Función para actualizar la tabla de detalles
function actualizarTablaDetalles() {
    const tbody = document.getElementById('tbodyDetalleCompra');
    tbody.innerHTML = '';

    detallesCompra.forEach((detalle, index) => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${detalle.producto}</td>
            <td>${detalle.cantidad}</td>
            <td>${detalle.precio_compra.toFixed(2)}</td>
            <td>${detalle.precio_venta.toFixed(2)}</td>
            <td>${(detalle.cantidad * detalle.precio_compra).toFixed(2)}</td>
            <td>
                <button class="btnEliminarDetalle" data-index="${index}"><i class="fa-solid fa-trash"></i></button>
            </td>
        `;
        tbody.appendChild(row);
    });

    // Agregar eventos a los botones de eliminar
    document.querySelectorAll('.btnEliminarDetalle').forEach(btn => {
        btn.addEventListener('click', function() {
            const index = parseInt(this.getAttribute('data-index'));
            detallesCompra.splice(index, 1);
            actualizarTablaDetalles();
            calcularTotal();
        });
    });
}

// Función para calcular el total de la compra
function calcularTotal() {
    const impuesto = parseFloat(document.getElementById('impuesto').value) || 0;
    const subtotal = detallesCompra.reduce((sum, detalle) => sum + (detalle.cantidad * detalle.precio_compra), 0);
    const total = subtotal * (1 + (impuesto / 100));
    
    document.getElementById('totalDetalle').textContent = subtotal.toFixed(2);
    document.getElementById('totalCompra').value = total.toFixed(2);
}

// Evento para registrar la compra
btnRegistrarCompra.addEventListener('click', function(e) {
    e.preventDefault();

    if (detallesCompra.length === 0) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Debe agregar al menos un producto al detalle'
        });
        return;
    }

    const formData = {
        tipoComprobante: document.getElementById('tipoComprobante').value,
        serieComprobante: document.getElementById('serieComprobante').value,
        numComprobante: document.getElementById('numComprobante').value,
        fechaHora: document.getElementById('fechaHora').value,
        impuesto: parseFloat(document.getElementById('impuesto').value) || 0,
        totalCompra: parseFloat(document.getElementById('totalCompra').value),
        idEmpleado: document.getElementById('idEmpleado').value,
        idProveedor: document.getElementById('idProveedor').value,
        estado: '1', // Activo por defecto
        detalles: detallesCompra
    };

    fetch('/Proyecto-Web2/C_Logica/logica_compras.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            Swal.fire({
                icon: 'success',
                title: 'Compra registrada',
                text: `N° de compra: ${data.id_compra}`,
                showConfirmButton: true
            });
            // Limpiar formulario
            document.getElementById('FormRegistrarCompra').reset();
            detallesCompra = [];
            actualizarTablaDetalles();
            calcularTotal();
            listarCompras();
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message || 'Error al registrar la compra'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error al conectar con el servidor'
        });
    });
});

// Función para listar compras
function listarCompras() {
    fetch('/Proyecto-Web2/C_Logica/logica_compras.php?action=listar')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                compras = data.data;
                renderCompras();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || 'Error al obtener compras'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error al conectar con el servidor'
            });
        });
}

// Función para renderizar las compras en la tabla
function renderCompras() {
    const tbody = document.querySelector('.tbodyCompras');
    tbody.innerHTML = '';

    const dataToPaginate = getComprasToPaginate();
    const totalPages = getTotalPagesCompras();

    if (currentPageCompras > totalPages) currentPageCompras = totalPages;
    if (currentPageCompras < 1) currentPageCompras = 1;

    const startIndex = (currentPageCompras - 1) * itemsPerPageCompras;
    const endIndex = startIndex + itemsPerPageCompras;
    const paginatedCompras = dataToPaginate.slice(startIndex, endIndex);

    if (paginatedCompras.length === 0) {
        tbody.innerHTML = '<tr><td colspan="9">No hay compras para mostrar</td></tr>';
        updatePaginationCompras();
        return;
    }

    paginatedCompras.forEach(compra => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${compra.id_compra}</td>
            <td>${compra.tipoComprobante}</td>
            <td>${compra.numComprobante}</td>
            <td>${new Date(compra.fechaHora).toLocaleDateString()}</td>
            <td>${compra.proveedor || 'N/A'}</td>
            <td>${parseFloat(compra.total_compra).toFixed(2)}</td>
            <td>${parseFloat(compra.impuesto).toFixed(2)}%</td>
            <td>${compra.estado === '1' ? 'Activo' : 'Anulado'}</td>
            <td class="acciones">
                <button class="btnVerDetalle" data-id="${compra.id_compra}"><i class="fa-solid fa-eye"></i></button>
                ${compra.estado === '1' ? '<button class="btnAnular" data-id="${compra.id_compra}"><i class="fa-solid fa-ban"></i></button>' : ''}
            </td>
        `;
        tbody.appendChild(row);

        // Eventos para los botones
        row.querySelector('.btnVerDetalle').addEventListener('click', () => verDetalleCompra(compra.id_compra));
        if (compra.estado === '1') {
            row.querySelector('.btnAnular').addEventListener('click', () => prepararAnulacionCompra(compra.id_compra));
        }
    });

    updatePaginationCompras();
}

// Función para ver el detalle de una compra
function verDetalleCompra(idCompra) {
    fetch(`/Proyecto-Web2/C_Logica/logica_compras.php?action=obtener_por_id&id=${idCompra}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const compra = data.data.compra;
                const detalles = data.data.detalles;

                document.getElementById('numeroCompraModal').textContent = compra.id_compra;
                document.getElementById('proveedorModal').textContent = compra.proveedor;
                document.getElementById('fechaModal').textContent = new Date(compra.fechaHora).toLocaleString();
                document.getElementById('tipoComprobanteModal').textContent = compra.tipoComprobante;
                document.getElementById('numComprobanteModal').textContent = compra.numComprobante;
                document.getElementById('totalModal').textContent = parseFloat(compra.total_compra).toFixed(2);
                document.getElementById('estadoModal').textContent = compra.estado === '1' ? 'Activo' : 'Anulado';

                const tbody = document.getElementById('tbodyDetalleCompraModal');
                tbody.innerHTML = '';
                detalles.forEach(detalle => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${detalle.producto}</td>
                        <td>${detalle.cantidad}</td>
                        <td>${parseFloat(detalle.precio_compra).toFixed(2)}</td>
                        <td>${parseFloat(detalle.precio_venta).toFixed(2)}</td>
                        <td>${(detalle.cantidad * detalle.precio_compra).toFixed(2)}</td>
                    `;
                    tbody.appendChild(row);
                });

                // Mostrar u ocultar botón de anular según estado
                document.getElementById('btnAnularCompra').style.display = compra.estado === '1' ? 'block' : 'none';
                document.getElementById('btnAnularCompra').dataset.id = compra.id_compra;

                modalDetalleCompra.showModal();
            }
        });
}

// Función para preparar la anulación de una compra
function prepararAnulacionCompra(idCompra) {
    document.getElementById('idCompraAnular').value = idCompra;
    document.getElementById('numeroCompraAnular').textContent = idCompra;
    modalAnularCompra.showModal();
}

// Evento para confirmar anulación
btnConfirmarAnulacion.addEventListener('click', function(e) {
    e.preventDefault();
    const idCompra = document.getElementById('idCompraAnular').value;

    fetch('/Proyecto-Web2/C_Logica/logica_compras.php', {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            id: idCompra,
            estado: '0' // Anulado
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            Swal.fire({
                icon: 'success',
                title: 'Compra anulada',
                showConfirmButton: false,
                timer: 1500
            });
            modalAnularCompra.close();
            listarCompras();
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message || 'Error al anular la compra'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error al conectar con el servidor'
        });
    });
});

// Funciones de paginación para compras
function getComprasToPaginate() {
    const query = document.getElementById('inputBusquedaCompra').value.trim().toLowerCase();
    if (query === '') {
        return compras;
    }
    return compras.filter(compra =>
        (compra.tipoComprobante && compra.tipoComprobante.toLowerCase().includes(query)) ||
        (compra.numComprobante && compra.numComprobante.toLowerCase().includes(query)) ||
        (compra.proveedor && compra.proveedor.toLowerCase().includes(query))
    );
}

function getTotalPagesCompras() {
    const dataToPaginate = getComprasToPaginate();
    return Math.ceil(dataToPaginate.length / itemsPerPageCompras) || 1;
}

function updatePaginationCompras() {
    const totalPages = getTotalPagesCompras();
    document.querySelector('.paginacionCompras').textContent = `${currentPageCompras} - ${totalPages}`;
    document.querySelector('.btnAnteriorCompras').disabled = currentPageCompras <= 1;
    document.querySelector('.btnSiguienteCompras').disabled = currentPageCompras >= totalPages;
}

// Eventos de paginación
document.querySelector('.btnAnteriorCompras').addEventListener('click', () => {
    if (currentPageCompras > 1) {
        currentPageCompras--;
        renderCompras();
    }
});

document.querySelector('.btnSiguienteCompras').addEventListener('click', () => {
    if (currentPageCompras < getTotalPagesCompras()) {
        currentPageCompras++;
        renderCompras();
    }
});

// Evento de búsqueda
document.getElementById('inputBusquedaCompra').addEventListener('input', (e) => {
    currentPageCompras = 1;
    renderCompras();
});

// Eventos para cerrar modales
btnCerrarDetalleCompra.addEventListener('click', () => modalDetalleCompra.close());
btnCancelarAnulacion.addEventListener('click', () => modalAnularCompra.close());

// Evento para cancelar el registro de compra
btnCancelarCompra.addEventListener('click', function() {
    detallesCompra = [];
    actualizarTablaDetalles();
    document.getElementById('FormRegistrarCompra').reset();
    calcularTotal();
});


async function obtenerUltimosNumeros() {
    try {
        const response = await fetch('/Proyecto-Web2/C_Logica/logica_compras.php?action=obtener_ultimos_numeros');
        const data = await response.json();
        
        if (data.status === 'success') {
            contadorFacturas = data.ultimaFactura + 1 || 1;
            contadorBoletas = data.ultimaBoleta + 1 || 1;
        }
    } catch (error) {
        console.error('Error al obtener últimos números:', error);
    }
}

// Función para generar el número de comprobante
function generarNumeroComprobante(tipo, numero) {
    const serie = tipo === 'Factura' ? 'F001' : 'B001';
    const numeroStr = String(numero).padStart(8, '0');
    return `${serie}-${numeroStr}`;
}

// Evento cuando cambia el tipo de comprobante
document.getElementById('tipoComprobante').addEventListener('change', function() {
    const tipo = this.value;
    if (tipo) {
        // Establecer serie automática
        document.getElementById('serieComprobante').value = tipo === 'Factura' ? 'F001' : 'B001';
        
        // Generar número de comprobante
        const contador = tipo === 'Factura' ? contadorFacturas : contadorBoletas;
        document.getElementById('numComprobante').value = generarNumeroComprobante(tipo, contador);
    } else {
        document.getElementById('serieComprobante').value = '';
        document.getElementById('numComprobante').value = '';
    }
});

// Establecer fecha y hora actual al cargar la página
function establecerFechaHoraActual() {
    const now = new Date();
    const fechaHoraInput = document.getElementById('fechaHora');
    
    // Formatear a YYYY-MM-DDTHH:MM (formato compatible con datetime-local)
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const day = String(now.getDate()).padStart(2, '0');
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    
    fechaHoraInput.value = `${year}-${month}-${day}T${hours}:${minutes}`;
}


// Al enviar el formulario, incrementar el contador correspondiente
document.getElementById('FormRegistrarCompra').addEventListener('submit', function() {
    const tipo = document.getElementById('tipoComprobante').value;
    if (tipo === 'Factura') {
        contadorFacturas++;
    } else if (tipo === 'Boleta') {
        contadorBoletas++;
    }
});

// Función para inicializar los controles de comprobante
function inicializarControlesComprobante() {
    establecerFechaHoraActual();
    obtenerUltimosNumeros();
    configurarEventoCambioTipoComprobante();
}

// Función para establecer fecha y hora actual
function establecerFechaHoraActual() {
    const now = new Date();
    const fechaHoraInput = document.getElementById('fechaHora');
    const fechaHoraFormateada = now.toISOString().slice(0, 16); // Formato YYYY-MM-DDTHH:MM
    fechaHoraInput.value = fechaHoraFormateada;
}

// Función para obtener los últimos números usados
async function obtenerUltimosNumeros() {
    try {
        const response = await fetch('/Proyecto-Web2/C_Logica/logica_compras.php?action=obtener_ultimos_numeros');
        const data = await response.json();
        
        if (data.status === 'success') {
            window.contadorFacturas = data.ultimaFactura + 1 || 1;
            window.contadorBoletas = data.ultimaBoleta + 1 || 1;
            actualizarCamposComprobante();
        }
    } catch (error) {
        console.error('Error al obtener últimos números:', error);
    }
}

// Función para configurar el evento de cambio en el tipo de comprobante
function configurarEventoCambioTipoComprobante() {
    document.getElementById('tipoComprobante').addEventListener('change', actualizarCamposComprobante);
}

// Función principal para actualizar los campos del comprobante
function actualizarCamposComprobante() {
    const tipo = document.getElementById('tipoComprobante').value;
    
    if (tipo) {
        // Establecer serie según el tipo
        document.getElementById('serieComprobante').value = tipo === 'Factura' ? 'F001' : 'B001';
        
        // Generar número de comprobante completo
        const contador = tipo === 'Factura' ? window.contadorFacturas : window.contadorBoletas;
        document.getElementById('numComprobante').value = generarNumeroComprobante(tipo, contador);
    } else {
        document.getElementById('serieComprobante').value = '';
        document.getElementById('numComprobante').value = '';
    }
}

// Función para formatear el número de comprobante
function generarNumeroComprobante(tipo, numero) {
    const serie = tipo === 'Factura' ? 'F001' : 'B001';
    const numeroStr = String(numero).padStart(8, '0');
    return `${serie}-${numeroStr}`;
}

// Función para incrementar contador después de registrar
function incrementarContadorComprobante() {
    const tipo = document.getElementById('tipoComprobante').value;
    if (tipo === 'Factura') {
        window.contadorFacturas++;
    } else if (tipo === 'Boleta') {
        window.contadorBoletas++;
    }
}


// Al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    obtenerUltimosNumeros();
    establecerFechaHoraActual();
});
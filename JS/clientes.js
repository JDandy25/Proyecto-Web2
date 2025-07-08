let clientes = [];
let filteredClientes = [];
let currentPageClientes = 1;
const itemsPerPageClientes = 10;

// Registrar nuevo cliente
const btnRegistrarCliente = document.getElementById('btnRegistrarCliente');

btnRegistrarCliente.addEventListener('click', function (event) {
    event.preventDefault();
    const formularioCliente = document.getElementById('FormRegistrarCliente');
    const nombre = document.getElementById('nombre_cliente_reg').value;
    const apellidos = document.getElementById('apellidos_cliente_reg').value;
    const dni = document.getElementById('dni_cliente_reg').value;
    const correo = document.getElementById('correo_cliente_reg').value;
    const telefono = document.getElementById('telefono_cliente_reg').value;
    const direccion = document.getElementById('direccion_cliente_reg').value;
    const estado = document.getElementById('estado_cliente_reg').value;

    if (!nombre || !apellidos || !dni) {
        alert('Por favor, completa los campos obligatorios.');
        return;
    }

    const clienteData = {
        nombre,
        apellidos,
        dni,
        correo,
        telefono,
        direccion,
        estado
    };

    fetch('/Proyecto-Web2/C_Logica/logica_clientes.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(clienteData)
    })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                Swal.fire({
                    icon: "success",
                    title: "Cliente registrado exitosamente",
                    showConfirmButton: true,
                    timer: 1800
                });
                formularioCliente.reset();
                listClientes();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al registrar cliente');
        });
});

// Listar clientes
function listClientes() {
    fetch('/Proyecto-Web2/C_Logica/logica_clientes.php', { method: 'GET' })
        .then(response => response.json())
        .then(data => {
            if (data && data.data) {
                clientes = data.data;
                filteredClientes = clientes;
                currentPageClientes = 1;
                renderClientes();
            } else {
                clientes = [];
                filteredClientes = [];
                currentPageClientes = 1;
                renderClientes();
                alert('No se encontraron clientes');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            clientes = [];
            filteredClientes = [];
            currentPageClientes = 1;
            renderClientes();
            alert('Error al listar clientes');
        });
}

function getClientesToPaginate() {
    const query = document.getElementById('inputBusquedaCliente').value.trim();
    if (query.length > 0) {
        return filteredClientes;
    }
    return clientes;
}

function getTotalPagesClientes() {
    const dataToPaginate = getClientesToPaginate();
    return Math.ceil(dataToPaginate.length / itemsPerPageClientes) || 1;
}

function renderClientes() {
    const tbody = document.querySelector('.tbodyCliente');
    tbody.innerHTML = '';
    const dataToPaginate = getClientesToPaginate();
    const totalData = dataToPaginate.length;
    const contadorDiv = document.querySelector('.contadorClientes');
    if (contadorDiv) {
        contadorDiv.textContent = `Total: ${totalData}`;
    }
    if (!dataToPaginate || totalData === 0) {
        tbody.innerHTML = '<tr><td colspan="8">No hay clientes para mostrar.</td></tr>';
        updatePaginationClientes([]);
        return;
    }
    const totalPages = getTotalPagesClientes();
    if (currentPageClientes > totalPages) currentPageClientes = totalPages;
    if (currentPageClientes < 1) currentPageClientes = 1;
    const startIndex = (currentPageClientes - 1) * itemsPerPageClientes;
    const endIndex = startIndex + itemsPerPageClientes;
    const paginatedClientes = dataToPaginate.slice(startIndex, endIndex);

    paginatedClientes.forEach(cliente => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <input type="hidden" name="idCliente-${cliente.id_cliente}" value="${cliente.id_cliente}">
            <td>${cliente.nombre}</td>
            <td>${cliente.apellidos}</td>
            <td>${cliente.dni}</td>
            <td>${cliente.correo}</td>
            <td>${cliente.telefono}</td>
            <td>${cliente.direccion}</td>
            <td>${cliente.estado == 1 ? 'Activo' : 'Inactivo'}</td>
            <td class="acciones">
                <button class="btnEditarCliente"><i class="fa-solid fa-pen-to-square"></i></button>
                <button class="btnBorrarCliente"><i class="fa-solid fa-trash"></i></button>
            </td>
        `;
        tbody.appendChild(row);
        row.querySelector('.btnEditarCliente')?.addEventListener('click', () => actualizarCliente(cliente.id_cliente));
        row.querySelector('.btnBorrarCliente')?.addEventListener('click', () => {
            Swal.fire({
                title: '¿Estás seguro?',
                text: 'El cliente será desactivado (estado 0) y no se mostrará más en la tabla.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, desactivar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    actualizarEstadoCliente(cliente.id_cliente, 0);
                }
            });
        });
    });
    updatePaginationClientes(dataToPaginate);
}

function updatePaginationClientes(data) {
    const totalPages = getTotalPagesClientes();
    document.querySelector('.contenidoListaCliente .paginacionCliente').textContent = `${currentPageClientes} - ${totalPages}`;
    document.querySelector('.contenidoListaCliente .btnAnteriorCliente').disabled = currentPageClientes <= 1;
    document.querySelector('.contenidoListaCliente .btnSiguienteCliente').disabled = currentPageClientes >= totalPages;
}

// Botones de paginación
const btnAnteriorCliente = document.querySelector('.contenidoListaCliente .btnAnteriorCliente');
const btnSiguienteCliente = document.querySelector('.contenidoListaCliente .btnSiguienteCliente');
if (btnAnteriorCliente && btnSiguienteCliente) {
    btnAnteriorCliente.addEventListener('click', () => {
        if (currentPageClientes > 1) {
            currentPageClientes--;
            renderClientes();
        }
    });
    btnSiguienteCliente.addEventListener('click', () => {
        if (currentPageClientes < getTotalPagesClientes()) {
            currentPageClientes++;
            renderClientes();
        }
    });
}

// Búsqueda y filtro
const inputBusquedaCliente = document.getElementById('inputBusquedaCliente');
inputBusquedaCliente.addEventListener('input', (event) => {
    const query = event.target.value.trim().toLowerCase();
    if (query === '') {
        filteredClientes = clientes;
    } else {
        filteredClientes = clientes.filter(cliente =>
            cliente.nombre.toLowerCase().includes(query) ||
            cliente.apellidos.toLowerCase().includes(query) ||
            cliente.dni.toLowerCase().includes(query) ||
            (cliente.correo && cliente.correo.toLowerCase().includes(query)) ||
            (cliente.telefono && cliente.telefono.toLowerCase().includes(query)) ||
            (cliente.direccion && cliente.direccion.toLowerCase().includes(query))
        );
    }
    currentPageClientes = 1;
    renderClientes();
});

document.querySelector('.botonBusquedaCliente').addEventListener('click', function() {
    const input = document.getElementById('inputBusquedaCliente');
    const query = input.value.trim().toLowerCase();
    if (query === '') {
        filteredClientes = clientes;
    } else {
        filteredClientes = clientes.filter(cliente =>
            cliente.nombre.toLowerCase().includes(query) ||
            cliente.apellidos.toLowerCase().includes(query) ||
            cliente.dni.toLowerCase().includes(query) ||
            (cliente.correo && cliente.correo.toLowerCase().includes(query)) ||
            (cliente.telefono && cliente.telefono.toLowerCase().includes(query)) ||
            (cliente.direccion && cliente.direccion.toLowerCase().includes(query))
        );
    }
    currentPageClientes = 1;
    renderClientes();
});

// Actualizar cliente
function actualizarCliente(idCliente) {
    const modalActualizarCliente = document.getElementById('modalActualizarCliente');
    if (modalActualizarCliente) {
        modalActualizarCliente.showModal();
    }
    fetch(`/Proyecto-Web2/C_Logica/logica_clientes.php?action=obtenerCliente&id=${idCliente}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success' && data.data) {
                const cliente = data.data;
                document.getElementById('idActualizarCliente').value = cliente.id_cliente;
                document.getElementById('nombre_cliente_act').value = cliente.nombre;
                document.getElementById('apellidos_cliente_act').value = cliente.apellidos;
                document.getElementById('dni_cliente_act').value = cliente.dni;
                document.getElementById('correo_cliente_act').value = cliente.correo;
                document.getElementById('telefono_cliente_act').value = cliente.telefono;
                document.getElementById('direccion_cliente_act').value = cliente.direccion;
                document.getElementById('estado_cliente_act').value = cliente.estado;
            } else {
                console.error('Cliente no encontrado o error en backend:', data);
            }
        })
        .catch(error => {
            console.error('Error al obtener datos del cliente:', error);
        });
}

const btnActualizarCliente = document.getElementById('btnActualizarCliente');
btnActualizarCliente.addEventListener('click', (event) => {
    event.preventDefault();
    const formActualizarCliente = document.getElementById('FormActualizarCliente');
    const formDataObj = {
        id_cliente_act: document.getElementById('idActualizarCliente').value,
        nombre_act: document.getElementById('nombre_cliente_act').value,
        apellidos_act: document.getElementById('apellidos_cliente_act').value,
        dni_act: document.getElementById('dni_cliente_act').value,
        correo_act: document.getElementById('correo_cliente_act').value,
        telefono_act: document.getElementById('telefono_cliente_act').value,
        direccion_act: document.getElementById('direccion_cliente_act').value,
        estado_act: document.getElementById('estado_cliente_act').value
    };
    const jsonData = JSON.stringify(formDataObj);
    fetch('/Proyecto-Web2/C_Logica/logica_clientes.php', {
        method: 'PUT',
        body: jsonData
    })
        .then(response => response.text())
        .then(data => {
            data = JSON.parse(data);
            if (data.status === 'success') {
                alert('Cliente actualizado exitosamente');
                listClientes();
                document.getElementById('modalActualizarCliente').close();
            } else {
                console.error('Error:', data);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al actualizar el cliente');
        });
});

function actualizarEstadoCliente(id, estado) {
    fetch('/Proyecto-Web2/C_Logica/logica_clientes.php', {
        method: 'PATCH',
        body: JSON.stringify({ id_cliente: id, estado })
    })
        .then(response => response.text())
        .then(data => {
            const parseData = JSON.parse(data);
            if (parseData.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Cliente desactivado',
                    text: 'El cliente ya no se mostrará en la tabla.',
                    timer: 1500,
                    showConfirmButton: false
                });
                listClientes();
            } else {
                console.error('Error:', parseData);
                Swal.fire({
                    icon: 'error',
                    title: 'Error al desactivar',
                    text: parseData.message || 'No se pudo actualizar el estado.'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error en la conexión',
                text: 'No se pudo conectar al servidor'
            });
        });
}

document.addEventListener('DOMContentLoaded', function() {
    listClientes();
});

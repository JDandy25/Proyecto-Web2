let proveedores = [];
let filteredProveedores = [];
let currentPageProveedores = 1;
const itemsPerPageProveedores = 10;

// Registrar nuevo proveedor
const btnRegistrarProveedor = document.getElementById('btnRegistrarProveedor');

btnRegistrarProveedor.addEventListener('click', function (event) {
    event.preventDefault();
    const formularioProveedor = document.getElementById('FormRegistrarProveedor');
    const nombres = document.getElementById('nombres_proveedor_reg').value;
    const apellidos = document.getElementById('apellidos_proveedor_reg').value;
    const RUC = document.getElementById('RUC_proveedor_reg').value;
    const telefono = document.getElementById('telefono_proveedor_reg').value;
    const correo = document.getElementById('correo_proveedor_reg').value;
    const estado = document.getElementById('estado_proveedor_reg').value;

    if (!nombres || !apellidos || !RUC) {
        alert('Por favor, completa los campos obligatorios.');
        return;
    }

    const proveedorData = {
        nombres,
        apellidos,
        RUC,
        telefono,
        correo,
        estado
    };

    fetch('/Proyecto-Web2/C_Logica/logica_proveedores.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(proveedorData)
    })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                Swal.fire({
                    icon: "success",
                    title: "Proveedor registrado exitosamente",
                    showConfirmButton: true,
                    timer: 1800
                });
                formularioProveedor.reset();
                listProveedores();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al registrar proveedor');
        });
});

// Listar proveedores
function listProveedores() {
    fetch('/Proyecto-Web2/C_Logica/logica_proveedores.php', { method: 'GET' })
        .then(response => response.json())
        .then(data => {
            if (data && data.data) {
                proveedores = data.data;
                filteredProveedores = proveedores;
                currentPageProveedores = 1;
                renderProveedores();
            } else {
                proveedores = [];
                filteredProveedores = [];
                currentPageProveedores = 1;
                renderProveedores();
                alert('No se encontraron proveedores');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            proveedores = [];
            filteredProveedores = [];
            currentPageProveedores = 1;
            renderProveedores();
            alert('Error al listar proveedores');
        });
}

function getProveedoresToPaginate() {
    const query = document.getElementById('inputBusquedaProveedor').value.trim();
    if (query.length > 0) {
        return filteredProveedores;
    }
    return proveedores;
}

function getTotalPagesProveedores() {
    const dataToPaginate = getProveedoresToPaginate();
    return Math.ceil(dataToPaginate.length / itemsPerPageProveedores) || 1;
}

function renderProveedores() {
    const tbody = document.querySelector('.tbodyProveedor');
    tbody.innerHTML = '';
    const dataToPaginate = getProveedoresToPaginate();
    const totalData = dataToPaginate.length;
    const contadorDiv = document.querySelector('.contadorProveedores');
    if (contadorDiv) {
        contadorDiv.textContent = `Total: ${totalData}`;
    }
    if (!dataToPaginate || totalData === 0) {
        tbody.innerHTML = '<tr><td colspan="7">No hay proveedores para mostrar.</td></tr>';
        updatePaginationProveedores([]);
        return;
    }
    const totalPages = getTotalPagesProveedores();
    if (currentPageProveedores > totalPages) currentPageProveedores = totalPages;
    if (currentPageProveedores < 1) currentPageProveedores = 1;
    const startIndex = (currentPageProveedores - 1) * itemsPerPageProveedores;
    const endIndex = startIndex + itemsPerPageProveedores;
    const paginatedProveedores = dataToPaginate.slice(startIndex, endIndex);

    paginatedProveedores.forEach(proveedor => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <input type="hidden" name="idProveedor-${proveedor.id_proveedor}" value="${proveedor.id_proveedor}">
            <td>${proveedor.nombres}</td>
            <td>${proveedor.apellidos}</td>
            <td>${proveedor.RUC}</td>
            <td>${proveedor.telefono}</td>
            <td>${proveedor.correo}</td>
            <td>${proveedor.estado == 1 ? 'Activo' : 'Inactivo'}</td>
            <td class="acciones">
                <button class="btnEditarProveedor"><i class="fa-solid fa-pen-to-square"></i></button>
                <button class="btnBorrarProveedor"><i class="fa-solid fa-trash"></i></button>
            </td>
        `;
        tbody.appendChild(row);
        row.querySelector('.btnEditarProveedor')?.addEventListener('click', () => actualizarProveedor(proveedor.id_proveedor));
        row.querySelector('.btnBorrarProveedor')?.addEventListener('click', () => {
            Swal.fire({
                title: '¿Estás seguro?',
                text: 'El proveedor será desactivado (estado 0) y no se mostrará más en la tabla.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, desactivar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    actualizarEstadoProveedor(proveedor.id_proveedor, 0);
                }
            });
        });
    });
    updatePaginationProveedores(dataToPaginate);
}

function updatePaginationProveedores(data) {
    const totalPages = getTotalPagesProveedores();
    document.querySelector('.contenidoListaProveedor .paginacionProveedor').textContent = `${currentPageProveedores} - ${totalPages}`;
    document.querySelector('.contenidoListaProveedor .btnAnteriorProveedor').disabled = currentPageProveedores <= 1;
    document.querySelector('.contenidoListaProveedor .btnSiguienteProveedor').disabled = currentPageProveedores >= totalPages;
}

// Botones de paginación
const btnAnteriorProveedor = document.querySelector('.contenidoListaProveedor .btnAnteriorProveedor');
const btnSiguienteProveedor = document.querySelector('.contenidoListaProveedor .btnSiguienteProveedor');
if (btnAnteriorProveedor && btnSiguienteProveedor) {
    btnAnteriorProveedor.addEventListener('click', () => {
        if (currentPageProveedores > 1) {
            currentPageProveedores--;
            renderProveedores();
        }
    });
    btnSiguienteProveedor.addEventListener('click', () => {
        if (currentPageProveedores < getTotalPagesProveedores()) {
            currentPageProveedores++;
            renderProveedores();
        }
    });
}

// Búsqueda y filtro
const inputBusquedaProveedor = document.getElementById('inputBusquedaProveedor');
inputBusquedaProveedor.addEventListener('input', (event) => {
    const query = event.target.value.trim().toLowerCase();
    if (query === '') {
        filteredProveedores = proveedores;
    } else {
        filteredProveedores = proveedores.filter(proveedor =>
            proveedor.nombres.toLowerCase().includes(query) ||
            proveedor.apellidos.toLowerCase().includes(query) ||
            proveedor.RUC.toLowerCase().includes(query) ||
            (proveedor.telefono && proveedor.telefono.toLowerCase().includes(query)) ||
            (proveedor.correo && proveedor.correo.toLowerCase().includes(query))
        );
    }
    currentPageProveedores = 1;
    renderProveedores();
});

document.querySelector('.botonBusquedaProveedor').addEventListener('click', function() {
    const input = document.getElementById('inputBusquedaProveedor');
    const query = input.value.trim().toLowerCase();
    if (query === '') {
        filteredProveedores = proveedores;
    } else {
        filteredProveedores = proveedores.filter(proveedor =>
            proveedor.nombres.toLowerCase().includes(query) ||
            proveedor.apellidos.toLowerCase().includes(query) ||
            proveedor.RUC.toLowerCase().includes(query) ||
            (proveedor.telefono && proveedor.telefono.toLowerCase().includes(query)) ||
            (proveedor.correo && proveedor.correo.toLowerCase().includes(query))
        );
    }
    currentPageProveedores = 1;
    renderProveedores();
});

// Actualizar proveedor
function actualizarProveedor(idProveedor) {
    const modalActualizarProveedor = document.getElementById('modalActualizarProveedor');
    if (modalActualizarProveedor) {
        modalActualizarProveedor.showModal();
    }
    fetch(`/Proyecto-Web2/C_Logica/logica_proveedores.php?action=obtenerProveedor&id=${idProveedor}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success' && data.data) {
                const proveedor = data.data;
                document.getElementById('idActualizarProveedor').value = proveedor.id_proveedor;
                document.getElementById('nombres_proveedor_act').value = proveedor.nombres;
                document.getElementById('apellidos_proveedor_act').value = proveedor.apellidos;
                document.getElementById('RUC_proveedor_act').value = proveedor.RUC;
                document.getElementById('telefono_proveedor_act').value = proveedor.telefono;
                document.getElementById('correo_proveedor_act').value = proveedor.correo;
                document.getElementById('estado_proveedor_act').value = proveedor.estado;
            } else {
                console.error('Proveedor no encontrado o error en backend:', data);
            }
        })
        .catch(error => {
            console.error('Error al obtener datos del proveedor:', error);
        });
}

const btnActualizarProveedor = document.getElementById('btnActualizarProveedor');
btnActualizarProveedor.addEventListener('click', (event) => {
    event.preventDefault();
    const formActualizarProveedor = document.getElementById('FormActualizarProveedor');
    const formDataObj = {
        id_proveedor_act: document.getElementById('idActualizarProveedor').value,
        nombres_act: document.getElementById('nombres_proveedor_act').value,
        apellidos_act: document.getElementById('apellidos_proveedor_act').value,
        RUC_act: document.getElementById('RUC_proveedor_act').value,
        telefono_act: document.getElementById('telefono_proveedor_act').value,
        correo_act: document.getElementById('correo_proveedor_act').value,
        estado_act: document.getElementById('estado_proveedor_act').value
    };
    const jsonData = JSON.stringify(formDataObj);
    fetch('/Proyecto-Web2/C_Logica/logica_proveedores.php', {
        method: 'PUT',
        body: jsonData
    })
        .then(response => response.text())
        .then(data => {
            data = JSON.parse(data);
            if (data.status === 'success') {
                alert('Proveedor actualizado exitosamente');
                listProveedores();
                document.getElementById('modalActualizarProveedor').close();
            } else {
                console.error('Error:', data);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al actualizar el proveedor');
        });
});

function actualizarEstadoProveedor(id, estado) {
    fetch('/Proyecto-Web2/C_Logica/logica_proveedores.php', {
        method: 'PATCH',
        body: JSON.stringify({ id_proveedor: id, estado })
    })
        .then(response => response.text())
        .then(data => {
            const parseData = JSON.parse(data);
            if (parseData.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Proveedor desactivado',
                    text: 'El proveedor ya no se mostrará en la tabla.',
                    timer: 1500,
                    showConfirmButton: false
                });
                listProveedores();
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
    listProveedores();
});

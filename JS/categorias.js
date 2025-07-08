let categorias = [];
let filteredCategorias = [];
let currentPageCategorias = 1;
const itemsPerPageCategorias = 10;

// Registrar nueva categoría
const btnRegistrarCategoria = document.getElementById('btnRegistrarCategoria');

btnRegistrarCategoria.addEventListener('click', function (event) {
    event.preventDefault();
    const formularioCategoria = document.getElementById('FormRegistrarCategoria');
    const nombre = document.getElementById('nombre_categoria_reg').value;
    const descripcion = document.getElementById('descripcion_categoria_reg').value;
    const estado = document.getElementById('estado_categoria_reg').value;

    if (!nombre) {
        alert('Por favor, completa el campo nombre.');
        return;
    }

    const categoriaData = {
        nombre,
        descripcion,
        estado
    };

    fetch('/Proyecto-Web2/C_Logica/logica_categorias.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(categoriaData)
    })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                Swal.fire({
                    icon: "success",
                    title: "Categoría registrada exitosamente",
                    showConfirmButton: true,
                    timer: 1800
                });
                formularioCategoria.reset();
                listCategorias();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al registrar categoría');
        });
});

// Listar categorías
function listCategorias() {
    fetch('/Proyecto-Web2/C_Logica/logica_categorias.php', { method: 'GET' })
        .then(response => response.json())
        .then(data => {
            if (data && data.data) {
                categorias = data.data;
                filteredCategorias = categorias;
                currentPageCategorias = 1;
                renderCategorias();
            } else {
                categorias = [];
                filteredCategorias = [];
                currentPageCategorias = 1;
                renderCategorias();
                alert('No se encontraron categorías');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            categorias = [];
            filteredCategorias = [];
            currentPageCategorias = 1;
            renderCategorias();
            alert('Error al listar categorías');
        });
}

function getCategoriasToPaginate() {
    const query = document.getElementById('inputBusquedaCategoria').value.trim();
    if (query.length > 0) {
        return filteredCategorias;
    }
    return categorias;
}

function getTotalPagesCategorias() {
    const dataToPaginate = getCategoriasToPaginate();
    return Math.ceil(dataToPaginate.length / itemsPerPageCategorias) || 1;
}

function renderCategorias() {
    const tbody = document.querySelector('.tbodyCategoria');
    tbody.innerHTML = '';
    const dataToPaginate = getCategoriasToPaginate();
    const totalData = dataToPaginate.length;
    const contadorDiv = document.querySelector('.contadorCategorias');
    if (contadorDiv) {
        contadorDiv.textContent = `Total: ${totalData}`;
    }
    if (!dataToPaginate || totalData === 0) {
        tbody.innerHTML = '<tr><td colspan="4">No hay categorías para mostrar.</td></tr>';
        updatePaginationCategorias([]);
        return;
    }
    const totalPages = getTotalPagesCategorias();
    if (currentPageCategorias > totalPages) currentPageCategorias = totalPages;
    if (currentPageCategorias < 1) currentPageCategorias = 1;
    const startIndex = (currentPageCategorias - 1) * itemsPerPageCategorias;
    const endIndex = startIndex + itemsPerPageCategorias;
    const paginatedCategorias = dataToPaginate.slice(startIndex, endIndex);

    paginatedCategorias.forEach(categoria => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <input type="hidden" name="idCategoria-${categoria.id_categoria}" value="${categoria.id_categoria}">
            <td>${categoria.nombre}</td>
            <td>${categoria.descripcion}</td>
            <td>${categoria.estado == 1 ? 'Activo' : 'Inactivo'}</td>
            <td class="acciones">
                <button class="btnEditarCategoria"><i class="fa-solid fa-pen-to-square"></i></button>
                <button class="btnBorrarCategoria"><i class="fa-solid fa-trash"></i></button>
            </td>
        `;
        tbody.appendChild(row);
        row.querySelector('.btnEditarCategoria')?.addEventListener('click', () => actualizarCategoria(categoria.id_categoria));
        row.querySelector('.btnBorrarCategoria')?.addEventListener('click', () => {
            Swal.fire({
                title: '¿Estás seguro?',
                text: 'La categoría será desactivada (estado 0) y no se mostrará más en la tabla.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, desactivar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    actualizarEstadoCategoria(categoria.id_categoria, 0);
                }
            });
        });
    });
    updatePaginationCategorias(dataToPaginate);
}

function updatePaginationCategorias(data) {
    const totalPages = getTotalPagesCategorias();
    document.querySelector('.contenidoListaCategoria .paginacionCategoria').textContent = `${currentPageCategorias} - ${totalPages}`;
    document.querySelector('.contenidoListaCategoria .btnAnteriorCategoria').disabled = currentPageCategorias <= 1;
    document.querySelector('.contenidoListaCategoria .btnSiguienteCategoria').disabled = currentPageCategorias >= totalPages;
}

// Botones de paginación
const btnAnteriorCategoria = document.querySelector('.contenidoListaCategoria .btnAnteriorCategoria');
const btnSiguienteCategoria = document.querySelector('.contenidoListaCategoria .btnSiguienteCategoria');
if (btnAnteriorCategoria && btnSiguienteCategoria) {
    btnAnteriorCategoria.addEventListener('click', () => {
        if (currentPageCategorias > 1) {
            currentPageCategorias--;
            renderCategorias();
        }
    });
    btnSiguienteCategoria.addEventListener('click', () => {
        if (currentPageCategorias < getTotalPagesCategorias()) {
            currentPageCategorias++;
            renderCategorias();
        }
    });
}

// Búsqueda y filtro
const inputBusquedaCategoria = document.getElementById('inputBusquedaCategoria');
inputBusquedaCategoria.addEventListener('input', (event) => {
    const query = event.target.value.trim().toLowerCase();
    if (query === '') {
        filteredCategorias = categorias;
    } else {
        filteredCategorias = categorias.filter(categoria =>
            categoria.nombre.toLowerCase().includes(query) ||
            (categoria.descripcion && categoria.descripcion.toLowerCase().includes(query))
        );
    }
    currentPageCategorias = 1;
    renderCategorias();
});

document.querySelector('.botonBusquedaCategoria').addEventListener('click', function() {
    const input = document.getElementById('inputBusquedaCategoria');
    const query = input.value.trim().toLowerCase();
    if (query === '') {
        filteredCategorias = categorias;
    } else {
        filteredCategorias = categorias.filter(categoria =>
            categoria.nombre.toLowerCase().includes(query) ||
            (categoria.descripcion && categoria.descripcion.toLowerCase().includes(query))
        );
    }
    currentPageCategorias = 1;
    renderCategorias();
});

// Actualizar categoría
function actualizarCategoria(idCategoria) {
    const modalActualizarCategoria = document.getElementById('modalActualizarCategoria');
    if (modalActualizarCategoria) {
        modalActualizarCategoria.showModal();
    }
    fetch(`/Proyecto-Web2/C_Logica/logica_categorias.php?action=obtenerCategoria&id=${idCategoria}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success' && data.data) {
                const categoria = data.data;
                document.getElementById('idActualizarCategoria').value = categoria.id_categoria;
                document.getElementById('nombre_categoria_act').value = categoria.nombre;
                document.getElementById('descripcion_categoria_act').value = categoria.descripcion;
                document.getElementById('estado_categoria_act').value = categoria.estado;
            } else {
                console.error('Categoría no encontrada o error en backend:', data);
            }
        })
        .catch(error => {
            console.error('Error al obtener datos de la categoría:', error);
        });
}

const btnActualizarCategoria = document.getElementById('btnActualizarCategoria');
btnActualizarCategoria.addEventListener('click', (event) => {
    event.preventDefault();
    const formActualizarCategoria = document.getElementById('FormActualizarCategoria');
    const formDataObj = {
        id_categoria_act: document.getElementById('idActualizarCategoria').value,
        nombre_act: document.getElementById('nombre_categoria_act').value,
        descripcion_act: document.getElementById('descripcion_categoria_act').value,
        estado_act: document.getElementById('estado_categoria_act').value
    };
    const jsonData = JSON.stringify(formDataObj);
    fetch('/Proyecto-Web2/C_Logica/logica_categorias.php', {
        method: 'PUT',
        body: jsonData
    })
        .then(response => response.text())
        .then(data => {
            data = JSON.parse(data);
            if (data.status === 'success') {
                alert('Categoría actualizada exitosamente');
                listCategorias();
                document.getElementById('modalActualizarCategoria').close();
            } else {
                console.error('Error:', data);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al actualizar la categoría');
        });
});

function actualizarEstadoCategoria(id, estado) {
    fetch('/Proyecto-Web2/C_Logica/logica_categorias.php', {
        method: 'PATCH',
        body: JSON.stringify({ id_categoria: id, estado })
    })
        .then(response => response.text())
        .then(data => {
            const parseData = JSON.parse(data);
            if (parseData.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Categoría desactivada',
                    text: 'La categoría ya no se mostrará en la tabla.',
                    timer: 1500,
                    showConfirmButton: false
                });
                listCategorias();
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
    listCategorias();
});

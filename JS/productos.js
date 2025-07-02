let productos = [];
let filteredProductos = [];
let currentPageProductos = 1;
const itemsPerPageProductos = 10;

const btnRegistrarProducto = document.getElementById('btnRegistrarProducto');
cargarCategorias();

// Registro de nuevo producto
btnRegistrarProducto.addEventListener('click', function (event) {
    event.preventDefault();
    const formularioProducto = document.getElementById('FormRegistrarProducto');
    const formData = new FormData(formularioProducto);
    
    // Validación simple
    if (!formData.get('nombre_producto') || !formData.get('codigo') || !formData.get('stock') || !formData.get('id_categoria')) {
        alert('Por favor, completa todos los campos requeridos.');
        return;
    }

    fetch('/Proyecto-Web2/C_Logica/logica_productos.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        console.log('Respuesta del backend al registrar:', data);
        if (data.status === 'success') {
            Swal.fire({
                icon: "success",
                title: "Producto registrado exitosamente",
                showConfirmButton: true,
                timer: 1800
            });

            formularioProducto.reset();
            listProductos();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al registrar producto');
    });
});

// Listar productos
function listProductos() {
    fetch('/Proyecto-Web2/C_Logica/logica_productos.php', { method: 'GET' })
    .then(response => response.json())
    .then(data => {
        console.log('Datos recibidos del servidor:', data); 

        if (data && data.data) {
            productos = data.data;
            filteredProductos = productos;
            currentPageProductos = 1;
            renderProductos();
        } else {
            productos = [];
            filteredProductos = [];
            currentPageProductos = 1;
            renderProductos();
            alert('No se encontraron productos');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        productos = [];
        filteredProductos = [];
        currentPageProductos = 1;
        renderProductos();
        alert('Error al listar productos');
    });
}

// Paginación
function getProductosToPaginate() {
    const query = document.getElementById('inputBusquedaProducto').value.trim();
    return query.length > 0 ? filteredProductos : productos;
}

function getTotalPagesProductos() {
    const dataToPaginate = getProductosToPaginate();
    return Math.ceil(dataToPaginate.length / itemsPerPageProductos) || 1;
}

function renderProductos() {
    const tbody = document.querySelector('.tbodyProducto');
    tbody.innerHTML = '';

    const dataToPaginate = getProductosToPaginate();
    const totalData = dataToPaginate.length;
    document.querySelector('.contadorProductos').textContent = `Total: ${totalData}`;

    if (!dataToPaginate || totalData === 0) {
        tbody.innerHTML = '<tr><td colspan="7">No hay productos para mostrar.</td></tr>';
        updatePaginationProductos([]);
        return;
    }

    const totalPages = getTotalPagesProductos();
    if (currentPageProductos > totalPages) currentPageProductos = totalPages;
    if (currentPageProductos < 1) currentPageProductos = 1;
    
    const startIndex = (currentPageProductos - 1) * itemsPerPageProductos;
    const endIndex = startIndex + itemsPerPageProductos;
    const paginatedProductos = dataToPaginate.slice(startIndex, endIndex);

    paginatedProductos.forEach(producto => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <input type="hidden" name="idProducto-${producto.id_producto}" value="${producto.id_producto}">
            <td><img src="${producto.imagen || 'img/default-product.png'}" alt="Imagen producto" class="producto-imagen"></td>
            <td>${producto.nombre}</td>
            <td>${producto.codigo}</td>
            <td>${producto.stock}</td>
            <td>${producto.categoria || 'Sin categoría'}</td>
            <td>${getEstadoTexto(producto.estado)}</td>
            <td class="acciones">
                <button class="btnEditar"><i class="fa-solid fa-pen-to-square"></i></button>
                <button class="btnBorrar"><i class="fa-solid fa-trash"></i></button>
            </td>
        `;
        tbody.appendChild(row);

        row.querySelector('.btnEditar').addEventListener('click', () => actualizarProducto(producto.id_producto));
        row.querySelector('.btnBorrar').addEventListener('click', () => {
            Swal.fire({
                title: '¿Estás seguro?',
                text: 'El producto será marcado como descontinuado.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, descontinuar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    actualizarEstadoProducto(producto.id_producto, 3); // 3 = Descontinuado
                }
            });
        });
    });

    updatePaginationProductos(dataToPaginate);
}

function getEstadoTexto(estado) {
    const estados = {
        1: 'Disponible',
        2: 'Agotado',
        3: 'Descontinuado'
    };
    return estados[estado] || estado;
}

function updatePaginationProductos(data) {
    const totalPages = getTotalPagesProductos();
    document.querySelector('.contenidoListaProducto .paginacion').textContent = `${currentPageProductos} - ${totalPages}`;
    document.querySelector('.contenidoListaProducto .btnAnterior').disabled = currentPageProductos <= 1;
    document.querySelector('.contenidoListaProducto .btnSiguiente').disabled = currentPageProductos >= totalPages;
}

// Eventos de paginación
document.querySelector('.contenidoListaProducto .btnAnterior')?.addEventListener('click', () => {
    if (currentPageProductos > 1) {
        currentPageProductos--;
        renderProductos();
    }
});

document.querySelector('.contenidoListaProducto .btnSiguiente')?.addEventListener('click', () => {
    if (currentPageProductos < getTotalPagesProductos()) {
        currentPageProductos++;
        renderProductos();
    }
});

// Búsqueda de productos
document.getElementById('inputBusquedaProducto').addEventListener('input', (event) => {
    const query = event.target.value.trim().toLowerCase();
    if (query === '') {
        filteredProductos = productos;
    } else {
        filteredProductos = productos.filter(producto =>
            producto.nombre.toLowerCase().includes(query) ||
            (producto.codigo && producto.codigo.toLowerCase().includes(query)) ||
            (producto.categoria && producto.categoria.toLowerCase().includes(query))
        );
    }
    currentPageProductos = 1;
    renderProductos();
});

document.getElementById('btnBuscarProducto').addEventListener('click', function() {
    const input = document.getElementById('inputBusquedaProducto');
    const query = input.value.trim().toLowerCase();
    if (query === '') {
        filteredProductos = productos;
    } else {
        filteredProductos = productos.filter(producto =>
            producto.nombre.toLowerCase().includes(query) ||
            (producto.codigo && producto.codigo.toLowerCase().includes(query)) ||
            (producto.categoria && producto.categoria.toLowerCase().includes(query))
        );
    }
    currentPageProductos = 1;
    renderProductos();
});

// Actualizar producto
function cargarCategoriasActualizar(selectedCategoriaId) {
    fetch('/Proyecto-Web2/C_Logica/logica_productos.php?action=obtenerCategorias')
    .then(response => response.json())
    .then(data => {
        const selectCategoria = document.getElementById('categoria_act');
        selectCategoria.innerHTML = '<option value="">Seleccione una categoría</option>';
        if (data && data.status === 'success') {
            data.categorias.forEach(categoria => {
                const option = document.createElement('option');
                option.value = categoria.id_categoria;
                option.textContent = categoria.nombre;
                if (categoria.id_categoria == selectedCategoriaId) {
                    option.selected = true;
                }
                selectCategoria.appendChild(option);
            });
        }
    })
    .catch(error => {
        console.error('Error al cargar categorías para actualizar:', error);
    });
}

function actualizarProducto(idProducto) {
    const modalActualizarProducto = document.getElementById('modalActualizarProducto');
    modalActualizarProducto.showModal();

    fetch(`/Proyecto-Web2/C_Logica/logica_productos.php?action=obtenerProducto&id=${idProducto}`)
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success' && data.data) {
            const producto = data.data;
            document.getElementById('idActualizarProducto').value = producto.id_producto;
            document.getElementById('nombre_producto_act').value = producto.nombre;
            document.getElementById('descripcion_act').value = producto.descripcion;
            document.getElementById('codigo_act').value = producto.codigo;
            document.getElementById('stock_act').value = producto.stock;
            document.getElementById('estado_producto_act').value = producto.estado;
            
            // Mostrar preview de imagen actual
            const previewDiv = document.getElementById('previewImagen');
            if (producto.imagen) {
                previewDiv.innerHTML = `<img src="${producto.imagen}" alt="Imagen actual" style="max-width: 100px;">`;
            } else {
                previewDiv.innerHTML = 'Sin imagen';
            }
            
            cargarCategoriasActualizar(producto.id_categoria);
        } else {
            console.error('Producto no encontrado o error en backend:', data);
        }
    })
    .catch(error => {
        console.error('Error al obtener datos del producto:', error);
    });
}

document.getElementById('btnActualizarProducto').addEventListener('click', (event) => {
    event.preventDefault();
    const formData = new FormData(document.getElementById('FormActualizarProducto'));

    fetch('/Proyecto-Web2/C_Logica/logica_productos.php', {
        method: 'PUT',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            Swal.fire({
                icon: 'success',
                title: 'Producto actualizado',
                timer: 1500,
                showConfirmButton: false
            });
            listProductos();
            document.getElementById('modalActualizarProducto').close();
        } else {
            console.error('Error:', data);
            Swal.fire({
                icon: 'error',
                title: 'Error al actualizar',
                text: data.message || 'No se pudo actualizar el producto.'
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
});

// Actualizar estado del producto
function actualizarEstadoProducto(id, estado) {
    fetch('/Proyecto-Web2/C_Logica/logica_productos.php', {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ id, estado })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            Swal.fire({
                icon: 'success',
                title: 'Estado actualizado',
                text: 'El estado del producto ha sido modificado.',
                timer: 1500,
                showConfirmButton: false
            });
            listProductos();
        } else {
            console.error('Error:', data);
            Swal.fire({
                icon: 'error',
                title: 'Error al actualizar',
                text: data.message || 'No se pudo actualizar el estado.'
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

// Cargar categorías
function cargarCategorias() {
    fetch('/Proyecto-Web2/C_Logica/logica_productos.php?action=obtenerCategorias')
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            const selectCategoria = document.getElementById('id_categoria_reg');
            selectCategoria.innerHTML = '<option value="">Seleccione</option>';
            data.categorias.forEach(categoria => {
                const option = document.createElement('option');
                option.value = categoria.id_categoria;
                option.textContent = categoria.nombre;
                selectCategoria.appendChild(option);
            });
        } else {
            console.error('Error al cargar categorías desde el servidor:', data.message);
        }
    })
    .catch(error => {
        console.error('Error en la solicitud Fetch para categorías:', error);
    });
}

// Inicialización
document.addEventListener('DOMContentLoaded', function() {
    cargarCategorias();
    listProductos();
});
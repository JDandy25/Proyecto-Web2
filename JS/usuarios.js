let usuarios = [];
let filteredUsuarios = [];
let currentPageUsuarios = 1;
const itemsPerPageUsuarios = 10;

const btnRegistrarUsuarios = document.getElementById('btnRegistrarUsuario');
cargarRoles();
// Verificar el valor del rol seleccionado in consola
setTimeout(() => {
    const selectRol = document.getElementById('id_rol_reg');
    if (selectRol) {
        selectRol.addEventListener('change', function() {
            console.log('Rol seleccionado:', this.value);
        });
    }
}, 500);
//registro del nuevo usuario


btnRegistrarUsuarios.addEventListener('click', function (event) {
    event.preventDefault();
    const formularioUsuario = document.getElementById('FormRegistrarUsuario');
    const nombreUsuario = document.getElementById('nombre_usuario_reg').value;
    const contrasena = document.getElementById('contrasena_reg').value;
    const idRol = document.getElementById('id_rol_reg').value;
    const estado = document.getElementById('estado_reg').value;

    // Log para verificar los datos antes de enviar
    console.log('Datos a enviar:', {
        nombre_usuario: nombreUsuario,
        contrasena: contrasena,
        id_rol: idRol,
        estado: estado
    });

    // Validación simple: solo verifica si hay texto y un rol seleccionado
    if (!nombreUsuario || !contrasena || !idRol) {
        alert('Por favor, completa todos los campos requeridos.');
        return;
    }

    const usuarioData = {
        nombre_usuario: nombreUsuario,
        contrasena: contrasena,
        id_rol: idRol,
        estado: estado
    };

    fetch('/Proyecto-Web2/C_Logica/logica_usuarios.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(usuarioData)
    })
        .then(response => response.json())
        .then(data => {
            console.log('Respuesta del backend al registrar:', data);
            if (data.status === 'success') {
                Swal.fire({
                    icon: "success",
                    title: "Usuario registrado exitosamente",
                    showConfirmButton: true,
                    timer: 1800
                });

                formularioUsuario.reset();
                listUsuarios();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al registrar usuario');
        });
});

function isFormValid(formData, requiredFields) {
    for (const field of requiredFields) {
        if (!formData.has(field) || formData.get(field).trim() === '') {
            return false;
        }
    }
    return true;
}


//Listar los usuarios

function listUsuarios() {
    fetch('/Proyecto-Web2/C_Logica/logica_usuarios.php', { method: 'GET' })
        .then(response => response.json())
        .then (data => {
            console.log('Datos recibidos del servidor:', data); 

            if (data && data.data) {
                usuarios = data.data;
                filteredUsuarios = usuarios;
                currentPageUsuarios = 1;
                renderUsuarios();
            } else {
                usuarios = [];
                filteredUsuarios = [];
                currentPageUsuarios = 1;
                renderUsuarios();
                alert('No se encontraron usuarios');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            usuarios = [];
            filteredUsuarios = [];
            currentPageUsuarios = 1;
            renderUsuarios();
            alert('Error al listar usuarios');
        });
}

// --- Encapsulamiento de paginación para evitar conflicto con empleados.js ---
function getUsuariosToPaginate() {
    const query = document.getElementById('inputBusquedaUsuario').value.trim();
    // Si hay texto en el input, usar el filtro, si no, usar todos
    if (query.length > 0) {
        return filteredUsuarios;
    }
    return usuarios;
}

// --- Getter global para total de páginas ---
function getTotalPagesUsuarios() {
    const dataToPaginate = getUsuariosToPaginate();
    return Math.ceil(dataToPaginate.length / itemsPerPageUsuarios) || 1;
}

function renderUsuarios() {
    const tbody = document.querySelector('.tbodyUsuario');
    const userRole = document.querySelector('.rol') ? document.querySelector('.rol').textContent.trim() : '';
    tbody.innerHTML = '';

    const dataToPaginate = getUsuariosToPaginate();
    // Mostrar contador de datos obtenidos
    const totalData = dataToPaginate.length;
    const contadorDiv = document.querySelector('.contadorUsuarios');
    if (contadorDiv) {
        contadorDiv.textContent = `Total: ${totalData}`;
    }

    // Si no hay datos, mostrar mensaje
    if (!dataToPaginate || totalData === 0) {
        tbody.innerHTML = '<tr><td colspan="4">No hay usuarios para mostrar.</td></tr>';
        updatePaginationUsuarios([]);
        return;
    }
    // Calcular límites de paginación
    const totalPages = getTotalPagesUsuarios();
    if (currentPageUsuarios > totalPages) currentPageUsuarios = totalPages;
    if (currentPageUsuarios < 1) currentPageUsuarios = 1;
    const startIndex = (currentPageUsuarios - 1) * itemsPerPageUsuarios;
    const endIndex = startIndex + itemsPerPageUsuarios;
    const paginatedUsuarios = dataToPaginate.slice(startIndex, endIndex);

    paginatedUsuarios.forEach(usuario => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <input type="hidden" name="idUsuario-${usuario.id_usuario}" value="${usuario.id_usuario}">
            <td>${usuario.nombre}</td>
            <td>${usuario.rol}</td>
            <td>${usuario.estado}</td>
            <td class="acciones">
                ${userRole === '?' ? '<button class="btnEditar"><i class="fa-solid fa-pen-to-square"></i></button>' : ''}
                ${userRole === '?' ? `<button class="btnBorrar"><i class="fa-solid fa-trash"></i></button>` : ''}
            </td>
        `;
        tbody.appendChild(row);

        if (userRole === '?') {
            row.querySelector('.btnEditar')?.addEventListener('click', () => actualizarUsuario(usuario.id_usuario));
            row.querySelector('.btnBorrar')?.addEventListener('click', () => {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: 'El usuario será desactivado (estado 0) y no se mostrará más en la tabla.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, desactivar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        actualizarEstadoUsuario(usuario.id_usuario, 0);
                    }
                });
            });
        }
    });

    updatePaginationUsuarios(dataToPaginate);
}

function updatePaginationUsuarios(data) {
    const totalPages = getTotalPagesUsuarios();
    // Mostrar paginador tipo 1-n
    document.querySelector('.contenidoListaUsuario .paginacion').textContent = `${currentPageUsuarios} - ${totalPages}`;
    document.querySelector('.contenidoListaUsuario .btnAnterior').disabled = currentPageUsuarios <= 1;
    document.querySelector('.contenidoListaUsuario .btnSiguiente').disabled = currentPageUsuarios >= totalPages;
}

// --- Botones de paginación de usuarios ---
document.addEventListener('DOMContentLoaded', function() {
    const btnAnterior = document.querySelector('.contenidoListaUsuario .btnAnterior');
    const btnSiguiente = document.querySelector('.contenidoListaUsuario .btnSiguiente');
    if (btnAnterior && btnSiguiente) {
        btnAnterior.addEventListener('click', () => {
            console.log('Click btnAnterior USUARIOS', { currentPageUsuarios, totalPages: getTotalPagesUsuarios() });
            if (currentPageUsuarios > 1) {
                currentPageUsuarios--;
                renderUsuarios();
            }
        });
        btnSiguiente.addEventListener('click', () => {
            console.log('Click btnSiguiente USUARIOS', { currentPageUsuarios, totalPages: getTotalPagesUsuarios() });
            if (currentPageUsuarios < getTotalPagesUsuarios()) {
                currentPageUsuarios++;
                renderUsuarios();
            }
        });
    }
});

// --- Búsqueda y filtro de usuarios ---
document.getElementById('inputBusquedaUsuario').addEventListener('input', (event) => {
    const query = event.target.value.trim().toLowerCase();
    if (query === '') {
        filteredUsuarios = usuarios;
    } else {
        filteredUsuarios = usuarios.filter(user =>
            user.nombre.toLowerCase().includes(query) ||
            (user.rol && user.rol.toLowerCase().includes(query))
        );
    }
    currentPageUsuarios = 1;
    renderUsuarios();
});

document.querySelector('.botonBusqueda').addEventListener('click', function() {
    const input = document.getElementById('inputBusquedaUsuario');
    const query = input.value.trim().toLowerCase();
    if (query === '') {
        filteredUsuarios = usuarios;
    } else {
        filteredUsuarios = usuarios.filter(user =>
            user.nombre.toLowerCase().includes(query) ||
            (user.rol && user.rol.toLowerCase().includes(query))
        );
    }
    currentPageUsuarios = 1;
    renderUsuarios();
});

//Actualizar usuarios

function cargarRolesActualizar(selectedRolId) {
    fetch('/Proyecto-Web2/C_Logica/logica_usuarios.php?action=obtenerRoles')
        .then(response => response.json())
        .then(data => {
            const selectRol = document.getElementById('rol_act');
            selectRol.innerHTML = '<option value="">Seleccione un rol</option>';
            if (data && data.status === 'success') {
                data.roles.forEach(rol => {
                    const nombreRol = rol.nombre_rol || rol.nombre;
                    const option = document.createElement('option');
                    option.value = rol.id_rol;
                    option.textContent = nombreRol;
                    if (rol.id_rol == selectedRolId) {
                        option.selected = true;
                    }
                    selectRol.appendChild(option);
                });
            }
        })
        .catch(error => {
            console.error('Error al cargar roles para actualizar:', error);
        });
}

function actualizarUsuario(idUsuario) {
    const modalActualizarUsuario = document.getElementById('modalActualizarUsuario');
    if (modalActualizarUsuario) {
        modalActualizarUsuario.showModal();
    }

    // Obtener datos completos del usuario desde el backend (incluyendo id_rol)
    fetch(`/Proyecto-Web2/C_Logica/logica_usuarios.php?action=obtenerUsuario&id=${idUsuario}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success' && data.data) {
                const usuario = data.data;
                document.getElementById('idActualizarUsuario').value = usuario.id_usuario;
                document.getElementById('nombre_usuario_act').value = usuario.nombre;
                document.getElementById('contrasena_act').value = '';
                document.getElementById('estado_act').value = usuario.estado;

                // Cargar roles y seleccionar el del usuario
                cargarRolesActualizar(usuario.id_rol);
            } else {
                console.error('Usuario no encontrado o error en backend:', data);
            }
        })
        .catch(error => {
            console.error('Error al obtener datos del usuario:', error);
        });
}

const btnActualizarUsuario = document.getElementById('btnActualizarUsuario');

btnActualizarUsuario.addEventListener('click', (event) => {
    event.preventDefault();
    const formActualizarUsuario = document.getElementById('FormActualizarUsuario');
    // Crea el objeto con los nombres de campos correctos
    const formDataObj = {
        id_usuario_act: document.getElementById('idActualizarUsuario').value,
        nombre_usuario_act: document.getElementById('nombre_usuario_act').value,
        contrasena_act: document.getElementById('contrasena_act').value,
        id_rol_act: document.getElementById('rol_act').value,
        estado_act: document.getElementById('estado_act').value
    };
    const jsonData = JSON.stringify(formDataObj);

    fetch('/Proyecto-Web2/C_Logica/logica_usuarios.php', {
        method: 'PUT',
        body: jsonData
    })
        .then(response => response.text())
        .then(data => {
            data = JSON.parse(data);
            if (data.status === 'success') {
                alert('Usuario actualizado exitosamente');
                listUsuarios();
                document.getElementById('modalActualizarUsuario').close();
            } else {
                console.error('Error:', data);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al actualizar el usuario');
        });
});

function actualizarEstadoUsuario(id, estado) {
    fetch('/Proyecto-Web2/C_Logica/logica_usuarios.php', {
        method: 'PATCH',
        body: JSON.stringify({ id, estado })
    })
        .then(response => response.text())
        .then(data => {
            const parseData = JSON.parse(data);
            if (parseData.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Usuario desactivado',
                    text: 'El usuario ya no se mostrará en la tabla.',
                    timer: 1500,
                    showConfirmButton: false
                });
                listUsuarios();
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

// --- Cargar roles dinámicamente en el select de roles al cargar la página ---
document.addEventListener('DOMContentLoaded', function() {
    cargarRoles();
    listUsuarios();
});

function cargarRoles() {
    fetch('/Proyecto-Web2/C_Logica/logica_usuarios.php?action=obtenerRoles')
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la red o servidor: ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            if (data.status === 'success') {
                const selectRol = document.getElementById('id_rol_reg');
                selectRol.innerHTML = '<option value="">Seleccione</option>';
                data.roles.forEach(rol => {
                    const option = document.createElement('option');
                    option.value = rol.id_rol;
                    option.textContent = rol.nombre;
                    selectRol.appendChild(option);
                });
            } else {
                console.error('Error al cargar roles desde el servidor:', data.message);
            }
        })
        .catch(error => {
            console.error('Error en la solicitud Fetch para roles:', error);
        });
}


document.addEventListener('DOMContentLoaded', listUsuarios);
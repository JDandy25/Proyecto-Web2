// Variables globales
let empleados = [];
let filteredEmpleados = [];
let currentPageEmpleados = 1;
const itemsPerPageEmpleados = 10;

const btnRegistrarEmpleado = document.getElementById('btnRegistrarEmpleado');
cargarTurnosParaFormulario();


const btnCrearNuevoUsuario = document.getElementById('btnCrearNuevoUsuario');
const modalCrearUsuario = document.getElementById('modalCrearUsuario');
const btnCancelarCrearUsuario = document.getElementById('btnCancelarCrearUsuario');
const btnGuardarUsuario = document.getElementById('btnGuardarUsuario');
const idRolModal = document.getElementById('id_rol_modal');
const idRolSelect = document.getElementById('id_rol_select');
const idUsuarioRegistrado = document.getElementById('idUsuarioRegistrado');

// Botón para crear usuario en registro
btnCrearNuevoUsuario.addEventListener('click', () => {
    cargarRolesParaModalUsuario();
    document.getElementById('FormCrearUsuario').reset();
    modalCrearUsuario.showModal();
    // Guardar referencia para saber a qué select actualizar
    modalCrearUsuario.dataset.context = 'registro';
});

// Botón para crear usuario en actualización
const btnCrearNuevoUsuarioActualizar = document.getElementById('btnCrearNuevoUsuarioActualizar');
const idUsuarioRegistradoActualizar = document.getElementById('idUsuarioRegistradoActualizar');

btnCrearNuevoUsuarioActualizar.addEventListener('click', () => {
    cargarRolesParaModalUsuario();
    document.getElementById('FormCrearUsuario').reset();
    modalCrearUsuario.showModal();
    // Guardar referencia para saber a qué select actualizar
    modalCrearUsuario.dataset.context = 'actualizar';
    // Si hay datos del usuario actual, puedes precargar aquí si lo deseas
});

function cargarRolesParaModalUsuario() {
    fetch('/Proyecto-Web2/C_Logica/logica_empleados.php?action=obtener_roles')
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la red o servidor: ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            if (data.status === 'success') {
                const selectRol = document.getElementById('id_rol_modal');
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

// Evento para guardar el nuevo usuario
btnGuardarUsuario.addEventListener('click', function(event) {
    event.preventDefault();
    
    const nombreUsuario = document.getElementById('nombreUsuario').value;
    const contrasena = document.getElementById('contrasena').value;
    const confirmarContrasena = document.getElementById('confirmarContrasena').value;
    const idRol = idRolModal.value;
    const rolNombre = idRolModal.options[idRolModal.selectedIndex]?.textContent || '';
    
    if (!nombreUsuario || !contrasena || !confirmarContrasena || !idRol) {
        alert('Por favor, complete todos los campos requeridos.');
        return;
    }
    if (contrasena !== confirmarContrasena) {
        alert('Las contraseñas no coinciden.');
        return;
    }
    const usuarioData = {
        nombre_usuario: nombreUsuario,
        contrasena: contrasena,
        id_rol: idRol,
        estado: document.querySelector('.contenidoRegistrarEmpleado select[name="estado"]')?.value || 1
    };
    // LOG: Mostrar datos enviados al backend para crear usuario
    console.log('[EmpleadoJS] Datos enviados para crear usuario:', usuarioData);
    fetch('/Proyecto-Web2/C_Logica/logica_usuarios.php?action=crear_usuario', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(usuarioData)
    })
    .then(response => response.json())
    .then(data => {
        // LOG: Mostrar respuesta recibida del backend al crear usuario
        console.log('[EmpleadoJS] Respuesta backend al crear usuario:', data);
        if (data.status === 'success') {
            document.getElementById('idUsuarioRegistrado').value = data.id_usuario;
            document.getElementById('tipoUsuario').value = data.nombre_rol;
            // Seleccionar el rol en el select de roles
            const selectRol = document.getElementById('id_rol_select');
            if (selectRol) {
                // Si el rol no está en el select, agregarlo
                let found = false;
                for (let i = 0; i < selectRol.options.length; i++) {
                    if (selectRol.options[i].value == data.id_rol) {
                        selectRol.selectedIndex = i;
                        found = true;
                        break;
                    }
                }
                if (!found) {
                    const option = document.createElement('option');
                    option.value = data.id_rol;
                    option.textContent = data.nombre_rol;
                    option.selected = true;
                    selectRol.appendChild(option);
                }
            }
            // LOG: Mostrar el ID de usuario y el rol seleccionado
            console.log('[EmpleadoJS] ID de usuario registrado:', data.id_usuario);
            console.log('[EmpleadoJS] Rol seleccionado:', data.id_rol, data.nombre_rol);
            if (modalCrearUsuario.dataset.context === 'actualizar') {
                document.getElementById('idUsuarioRegistradoActualizar').value = data.id_usuario;
                document.getElementById('tipoUsuarioActualizar').value = data.nombre_rol;
                const selectRolActualizar = document.getElementById('id_rol_select_actualizar');
                if (selectRolActualizar) {
                    let found = false;
                    for (let i = 0; i < selectRolActualizar.options.length; i++) {
                        if (selectRolActualizar.options[i].value == data.id_rol) {
                            selectRolActualizar.selectedIndex = i;
                            found = true;
                            break;
                        }
                    }
                    if (!found) {
                        const option = document.createElement('option');
                        option.value = data.id_rol;
                        option.textContent = data.nombre_rol;
                        option.selected = true;
                        selectRolActualizar.appendChild(option);
                    }
                }
            }
            Swal.fire({
                icon: "success",
                title: "Usuario creado exitosamente",
                showConfirmButton: true,
                timer: 1800
            });
            modalCrearUsuario.close();
            document.getElementById('FormCrearUsuario').reset();
        } else {
            Swal.fire({
                icon: "error",
                title: "Error al crear usuario",
                text: data.message,
                showConfirmButton: true
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: "error",
            title: "Error en la conexión",
            text: "No se pudo conectar al servidor",
            showConfirmButton: true
        });
    });
});

function agregarUsuarioAlSelect(selectId, idUsuario, nombreUsuario, idRol) {
    const select = document.getElementById(selectId);
    // Buscar el nombre del rol en el select de roles del modal
    let rolNombre = '';
    const rolOptions = idRolModal.options;
    for (let i = 0; i < rolOptions.length; i++) {
        if (rolOptions[i].value === idRol) {
            rolNombre = rolOptions[i].textContent;
            break;
        }
    }
    // Mostrar el nombre de usuario y el nombre del rol
    const option = document.createElement('option');
    option.value = idUsuario;
    option.textContent = `${nombreUsuario} (Rol: ${rolNombre})`;
    option.selected = true;
    select.appendChild(option);
}

function cargarTurnosParaFormulario() {
    fetch('/Proyecto-Web2/C_Logica/logica_empleados.php?action=obtener_turnos')
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la red o servidor: ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            if (data.status === 'success') {
                const selectTurno = document.getElementById('id_turno');
                if (selectTurno) {
                    selectTurno.innerHTML = '<option value="">Seleccione</option>';
                    data.turnos.forEach(turno => {
                        const option = document.createElement('option');
                        option.value = turno.id_turno;
                        option.textContent = turno.nombre;
                        selectTurno.appendChild(option);
                    });
                }
                // Para actualización
                const selectTurnoActualizar = document.getElementById('id_turno_actualizar');
                if (selectTurnoActualizar) {
                    selectTurnoActualizar.innerHTML = '<option value="">Seleccione</option>';
                    data.turnos.forEach(turno => {
                        const option = document.createElement('option');
                        option.value = turno.id_turno;
                        option.textContent = turno.nombre;
                        selectTurnoActualizar.appendChild(option);
                    });
                }
            } else {
                console.error('Error al cargar turnos desde el servidor:', data.message);
            }
        })
        .catch(error => {
            console.error('Error en la solicitud Fetch para turnos:', error);
        });
}


//registro del nuevo empleado

btnRegistrarEmpleado.addEventListener('click', function (event) {
    event.preventDefault();
    const idUsuario = document.getElementById('idUsuarioRegistrado').value;
    if (!idUsuario) {
        alert('Debe crear un usuario y asignar un tipo de usuario antes de registrar el empleado.');
        return;
    }
    //campos obligatorios
    const formularioEmpleado = document.getElementById('FormRegistrarEmpleado');
    let formData = new FormData(formularioEmpleado);

    // Usar los nuevos names de los campos
    const requiredFields = [
        'nombre',
        'apePater',
        'apeMater',
        'dni',
        'telefono',
        'correo',
        'direccion',
        'id_usuario',
        'id_turno'
        // 'id_rol' eliminado, ya no se usa
    ];

    if (!isFormValid(formData, requiredFields)) {
        alert('Por favor, completa todos los campos requeridos.');
        return;
    }
    // Log para depuración: mostrar los datos que se enviarán
    for (let pair of formData.entries()) {
        if (pair[0] !== 'id_rol') { // No mostrar id_rol porque ya no se usa
            console.log(pair[0]+ ': ' + pair[1]);
        }
    }
    fetch('/Proyecto-Web2/C_Logica/logica_empleados.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then (data => {
            if (data.status === 'success') {
                Swal.fire({
                    icon: "success",
                    title: "Empleado registrado exitosamente",
                    showConfirmButton: true,
                    timer: 1800
                });

                formularioEmpleado.reset();
                listEmpleados();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al registrar empleado');
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


//Listar los empleados

function listEmpleados() {
    fetch('/Proyecto-Web2/C_Logica/logica_empleados.php', { method: 'GET' })
        .then(response => response.json())
        .then(data => {

            if (data && data.data) {
                empleados = data.data;
                renderEmpleados(empleados);
            } else {
                alert('No se encontraron empleados');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al listar empleados');
        });
}


// --- Encapsulamiento de paginación para empleados (solo afecta empleados, con clases únicas) ---
function getEmpleadosToPaginate() {
    const query = document.getElementById('inputBusquedaEmpleado').value.trim();
    if (query.length > 0) {
        return filteredEmpleados;
    }
    return empleados;
}

function getTotalPagesEmpleados() {
    const dataToPaginate = getEmpleadosToPaginate();
    return Math.ceil(dataToPaginate.length / itemsPerPageEmpleados) || 1;
}

function renderEmpleados() {
    const tbody = document.querySelector('.tbodyEmpleados');
    const userRole = document.querySelector('.rol')?.textContent.trim() || '';
    tbody.innerHTML = '';

    const dataToPaginate = getEmpleadosToPaginate();
    const totalData = dataToPaginate.length;
    // Si no hay datos, mostrar mensaje
    if (!dataToPaginate || totalData === 0) {
        tbody.innerHTML = '<tr><td colspan="11">No hay empleados para mostrar.</td></tr>';
        updatePaginationEmpleados([]);
        return;
    }
    const totalPages = getTotalPagesEmpleados();
    if (currentPageEmpleados > totalPages) currentPageEmpleados = totalPages;
    if (currentPageEmpleados < 1) currentPageEmpleados = 1;
    const startIndex = (currentPageEmpleados - 1) * itemsPerPageEmpleados;
    const endIndex = startIndex + itemsPerPageEmpleados;
    const paginatedEmpleados = dataToPaginate.slice(startIndex, endIndex);

    paginatedEmpleados.forEach(empleado => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <input type="hidden" name="idEmpleado-${empleado.id}" value="${empleado.id}">
            <td>${empleado.nombre}</td>
            <td>${empleado.apePater}</td>
            <td>${empleado.apeMater}</td>
            <td>${empleado.dni}</td>
            <td>${empleado.telefono}</td>
            <td>${empleado.correo}</td>
            <td>${empleado.direccion}</td>
            <td>${empleado.rol ? empleado.rol : ''}</td>
            <td>${empleado.turno}</td>
            <td>${empleado.estado}</td>
            <td class="acciones">
                ${userRole === '?' ? '<button class="btnEditar"><i class="fa-solid fa-pen-to-square"></i></button>' : ''}
                ${userRole === '?' ? '<button class="btnBorrar"><i class="fa-solid fa-trash"></i></button>' : ''}
            </td>
        `;
        tbody.appendChild(row);

        if (userRole === '?') {
            row.querySelector('.btnEditar')?.addEventListener('click', () => actualizarEmpleado(empleado.id));
            row.querySelector('.btnBorrar')?.addEventListener('click', () => actualizarEstadoEmpleado(empleado.id, empleado.estado));
        }
    });

    updatePaginationEmpleados(dataToPaginate);
}

function updatePaginationEmpleados(data) {
    const totalPages = getTotalPagesEmpleados();
    document.querySelector('.paginacionEmpleados').textContent = `${currentPageEmpleados} - ${totalPages}`;
    document.querySelector('.btnAnteriorEmpleados').disabled = currentPageEmpleados <= 1;
    document.querySelector('.btnSiguienteEmpleados').disabled = currentPageEmpleados >= totalPages;
}

// --- Botones de paginación de empleados ---
document.addEventListener('DOMContentLoaded', function() {
    const btnAnterior = document.querySelector('.btnAnteriorEmpleados');
    const btnSiguiente = document.querySelector('.btnSiguienteEmpleados');
    if (btnAnterior && btnSiguiente) {
        btnAnterior.addEventListener('click', () => {
            console.log('Click btnAnterior EMPLEADOS', { currentPageEmpleados, totalPages: getTotalPagesEmpleados() });
            if (currentPageEmpleados > 1) {
                currentPageEmpleados--;
                renderEmpleados();
            }
        });
        btnSiguiente.addEventListener('click', () => {
            console.log('Click btnSiguiente EMPLEADOS', { currentPageEmpleados, totalPages: getTotalPagesEmpleados() });
            if (currentPageEmpleados < getTotalPagesEmpleados()) {
                currentPageEmpleados++;
                renderEmpleados();
            }
        });
    }
});

//Busqueda y filtro del empleado

document.getElementById('inputBusquedaEmpleado').addEventListener('input', (event) => {
    const query = event.target.value.trim().toLowerCase();
    if (query === '') {
        filteredEmpleados = empleados;
    } else {
        filteredEmpleados = empleados.filter(emp =>
            (emp.nombre && emp.nombre.toLowerCase().includes(query)) ||
            (emp.apePater && emp.apePater.toLowerCase().includes(query)) ||
            (emp.apeMater && emp.apeMater.toLowerCase().includes(query))
        );
    }
    currentPageEmpleados = 1;
    renderEmpleados(filteredEmpleados);
});


//Actualizar empleados

function actualizarEmpleado(idEmpleado) {
    const modalActualizarEmpleado = document.getElementById('modalActualizarEmpleado');
    modalActualizarEmpleado.showModal();

    const empleado = empleados.find(emp => emp.id === idEmpleado);

    // LOG: Mostrar el objeto empleado para depuración
    console.log('[EmpleadoJS] Objeto empleado al actualizar:', empleado);

    if (empleado) {
        document.getElementById('idActualizarEmpleado').value = empleado.id;
        document.getElementById('nombre_actualizar').value = empleado.nombre;
        document.getElementById('apellido_paterno_actualizar').value = empleado.apePater;
        document.getElementById('apellido_materno_actualizar').value = empleado.apeMater;
        document.getElementById('dni_actualizar').value = empleado.dni;
        document.getElementById('telefono_actualizar').value = empleado.telefono;
        document.getElementById('correo_actualizar').value = empleado.correo;
        document.getElementById('direccion_actualizar').value = empleado.direccion;
        document.getElementById('tipoUsuarioActualizar').value = empleado.rol || '';
        const idUsuarioInput = document.getElementById('idUsuarioRegistradoActualizar');
        // Si no hay id_usuario, consultarlo por AJAX
        if (!empleado.id_usuario) {
            fetch(`/Proyecto-Web2/C_Logica/logica_empleados.php?action=obtener_id_usuario&id_empleado=${empleado.id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success' && data.id_usuario) {
                        idUsuarioInput.value = data.id_usuario;
                        idUsuarioInput.readOnly = true;
                        idUsuarioInput.placeholder = '';
                    } else {
                        idUsuarioInput.value = '';
                        idUsuarioInput.placeholder = 'Debe crear/seleccionar un usuario';
                    }
                })
                .catch(error => {
                    idUsuarioInput.value = '';
                    idUsuarioInput.placeholder = 'Error al obtener usuario';
                    console.error('Error al obtener id_usuario:', error);
                });
        } else if (empleado.id_usuario && empleado.nombre_usuario && empleado.rol) {
            idUsuarioInput.value = `${empleado.id_usuario} - ${empleado.nombre_usuario} (Rol: ${empleado.rol})`;
            idUsuarioInput.readOnly = true;
            idUsuarioInput.placeholder = '';
        } else if (empleado.id_usuario) {
            idUsuarioInput.value = empleado.id_usuario;
            idUsuarioInput.readOnly = true;
            idUsuarioInput.placeholder = '';
        } else {
            idUsuarioInput.value = '';
            idUsuarioInput.placeholder = 'Debe crear/seleccionar un usuario';
        }
        document.getElementById('id_turno_actualizar').value = empleado.id_turno || '';
    } else {
        console.error('Empleado no encontrado');
    }
}

const btnActualizarEmpleado = document.getElementById('btnActualizarEmpleado');

btnActualizarEmpleado.addEventListener('click', (event) => {
    event.preventDefault();
    const idUsuario = document.getElementById('idUsuarioRegistradoActualizar').value;
    if (!idUsuario) {
        alert('Debe crear un usuario y asignar un tipo de usuario antes de actualizar el empleado.');
        return;
    }
    const formActualizarEmpleado = document.getElementById('FormActualizarEmpleado');
    const formDataObj = Object.fromEntries(new FormData(formActualizarEmpleado).entries());
    const jsonData = JSON.stringify(formDataObj);

    fetch('/Proyecto-Web2/C_Logica/logica_empleados.php', {
        method: 'PUT',
        body: jsonData
    })
        .then(response => response.text())
        .then(data => {
            data = JSON.parse(data);
            if (data.status === 'success') {
                Swal.fire({
                    icon: "success",
                    title: "Empleado actualizado exitosamente",
                    showConfirmButton: true,
                    timer: 1800
                });
                listEmpleados();
                document.getElementById('modalActualizarEmpleado').close();
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Error al actualizar empleado",
                    text: data.message || 'Ocurrió un error',
                    showConfirmButton: true
                });
                console.error('Error:', data);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: "error",
                title: "Error en la conexión",
                text: "No se pudo conectar al servidor",
                showConfirmButton: true
            });
        });
});

function actualizarEstadoEmpleado(id, estado) {
    fetch('/Proyecto-Web2/C_Logica/logica_empleados.php', {
        method: 'PATCH',
        body: JSON.stringify({ id, estado })
    })
        .then(response => response.text())
        .then(data => {
            const parseData = JSON.parse(data);
            if (parseData.status === 'success') {
                alert('Estado actualizado exitosamente');
                listEmpleados();
            } else {
                console.error('Error:', parseData);
                alert('Error al actualizar el estado del empleado');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al actualizar el estado del empleado');
        });
}

document.addEventListener('DOMContentLoaded', listEmpleados);

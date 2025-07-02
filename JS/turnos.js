let turnos = [];
let filteredTurnos = [];
let currentPageTurnos = 1;
const itemsPerPageTurnos = 10;

// Registrar nuevo turno
const btnRegistrarTurno = document.getElementById('btnRegistrarTurno');

btnRegistrarTurno.addEventListener('click', function (event) {
    event.preventDefault();
    const formularioTurno = document.getElementById('FormRegistrarTurno');
    const nombre = document.getElementById('nombre_turno_reg').value;
    const horaIngreso = document.getElementById('horaIngreso_turno_reg').value;
    const horaSalida = document.getElementById('horaSalida_turno_reg').value;
    const estado = document.getElementById('estado_turno_reg').value;

    if (!nombre || !horaIngreso || !horaSalida) {
        alert('Por favor, completa todos los campos obligatorios.');
        return;
    }
    // Validación: hora de salida debe ser mayor a hora de ingreso
    if (horaIngreso >= horaSalida) {
        Swal.fire({
            icon: 'warning',
            title: 'Horario inválido',
            text: 'La hora de salida debe ser mayor que la hora de ingreso.'
        });
        return;
    }

    const turnoData = {
        nombre,
        horaIngreso,
        horaSalida,
        estado
    };

    fetch('/Proyecto-Web2/C_Logica/logica_turnos.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(turnoData)
    })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                Swal.fire({
                    icon: "success",
                    title: "Turno registrado exitosamente",
                    showConfirmButton: true,
                    timer: 1800
                });
                formularioTurno.reset();
                listTurnos();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al registrar turno');
        });
});

// Listar turnos
function listTurnos() {
    fetch('/Proyecto-Web2/C_Logica/logica_turnos.php', { method: 'GET' })
        .then(response => response.json())
        .then(data => {
            if (data && data.data) {
                turnos = data.data;
                filteredTurnos = turnos;
                currentPageTurnos = 1;
                renderTurnos();
            } else {
                turnos = [];
                filteredTurnos = [];
                currentPageTurnos = 1;
                renderTurnos();
                alert('No se encontraron turnos');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            turnos = [];
            filteredTurnos = [];
            currentPageTurnos = 1;
            renderTurnos();
            alert('Error al listar turnos');
        });
}

function getTurnosToPaginate() {
    const query = document.getElementById('inputBusquedaTurno').value.trim();
    if (query.length > 0) {
        return filteredTurnos;
    }
    return turnos;
}

function getTotalPagesTurnos() {
    const dataToPaginate = getTurnosToPaginate();
    return Math.ceil(dataToPaginate.length / itemsPerPageTurnos) || 1;
}

function renderTurnos() {
    const tbody = document.querySelector('.tbodyTurno');
    tbody.innerHTML = '';
    const dataToPaginate = getTurnosToPaginate();
    const totalData = dataToPaginate.length;
    const contadorDiv = document.querySelector('.contadorTurnos');
    if (contadorDiv) {
        contadorDiv.textContent = `Total: ${totalData}`;
    }
    if (!dataToPaginate || totalData === 0) {
        tbody.innerHTML = '<tr><td colspan="5">No hay turnos para mostrar.</td></tr>';
        updatePaginationTurnos([]);
        return;
    }
    const totalPages = getTotalPagesTurnos();
    if (currentPageTurnos > totalPages) currentPageTurnos = totalPages;
    if (currentPageTurnos < 1) currentPageTurnos = 1;
    const startIndex = (currentPageTurnos - 1) * itemsPerPageTurnos;
    const endIndex = startIndex + itemsPerPageTurnos;
    const paginatedTurnos = dataToPaginate.slice(startIndex, endIndex);

    paginatedTurnos.forEach(turno => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <input type="hidden" name="idTurno-${turno.id_turno}" value="${turno.id_turno}">
            <td>${turno.nombre}</td>
            <td>${turno.horaIngreso}</td>
            <td>${turno.horaSalida}</td>
            <td>${turno.estado == 1 ? 'Activo' : 'Inactivo'}</td>
            <td class="acciones">
                <button class="btnEditarTurno"><i class="fa-solid fa-pen-to-square"></i></button>
                <button class="btnBorrarTurno"><i class="fa-solid fa-trash"></i></button>
            </td>
        `;
        tbody.appendChild(row);
        row.querySelector('.btnEditarTurno')?.addEventListener('click', () => actualizarTurno(turno.id_turno));
        row.querySelector('.btnBorrarTurno')?.addEventListener('click', () => {
            Swal.fire({
                title: '¿Estás seguro?',
                text: 'El turno será desactivado (estado 0) y no se mostrará más en la tabla.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, desactivar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    actualizarEstadoTurno(turno.id_turno, 0);
                }
            });
        });
    });
    updatePaginationTurnos(dataToPaginate);
}

function updatePaginationTurnos(data) {
    const totalPages = getTotalPagesTurnos();
    document.querySelector('.contenidoListaTurno .paginacionTurno').textContent = `${currentPageTurnos} - ${totalPages}`;
    document.querySelector('.contenidoListaTurno .btnAnteriorTurno').disabled = currentPageTurnos <= 1;
    document.querySelector('.contenidoListaTurno .btnSiguienteTurno').disabled = currentPageTurnos >= totalPages;
}

// Botones de paginación
const btnAnteriorTurno = document.querySelector('.contenidoListaTurno .btnAnteriorTurno');
const btnSiguienteTurno = document.querySelector('.contenidoListaTurno .btnSiguienteTurno');
if (btnAnteriorTurno && btnSiguienteTurno) {
    btnAnteriorTurno.addEventListener('click', () => {
        if (currentPageTurnos > 1) {
            currentPageTurnos--;
            renderTurnos();
        }
    });
    btnSiguienteTurno.addEventListener('click', () => {
        if (currentPageTurnos < getTotalPagesTurnos()) {
            currentPageTurnos++;
            renderTurnos();
        }
    });
}

// Búsqueda y filtro
const inputBusquedaTurno = document.getElementById('inputBusquedaTurno');
inputBusquedaTurno.addEventListener('input', (event) => {
    const query = event.target.value.trim().toLowerCase();
    if (query === '') {
        filteredTurnos = turnos;
    } else {
        filteredTurnos = turnos.filter(turno =>
            turno.nombre.toLowerCase().includes(query)
        );
    }
    currentPageTurnos = 1;
    renderTurnos();
});

document.querySelector('.botonBusquedaTurno').addEventListener('click', function() {
    const input = document.getElementById('inputBusquedaTurno');
    const query = input.value.trim().toLowerCase();
    if (query === '') {
        filteredTurnos = turnos;
    } else {
        filteredTurnos = turnos.filter(turno =>
            turno.nombre.toLowerCase().includes(query)
        );
    }
    currentPageTurnos = 1;
    renderTurnos();
});

// Actualizar turno
function actualizarTurno(idTurno) {
    const modalActualizarTurno = document.getElementById('modalActualizarTurno');
    if (modalActualizarTurno) {
        modalActualizarTurno.showModal();
    }
    fetch(`/Proyecto-Web2/C_Logica/logica_turnos.php?action=obtenerTurno&id=${idTurno}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success' && data.data) {
                const turno = data.data;
                document.getElementById('idActualizarTurno').value = turno.id_turno;
                document.getElementById('nombre_turno_act').value = turno.nombre;
                document.getElementById('horaIngreso_turno_act').value = turno.horaIngreso;
                document.getElementById('horaSalida_turno_act').value = turno.horaSalida;
                document.getElementById('estado_turno_act').value = turno.estado;
            } else {
                console.error('Turno no encontrado o error en backend:', data);
            }
        })
        .catch(error => {
            console.error('Error al obtener datos del turno:', error);
        });
}

const btnActualizarTurno = document.getElementById('btnActualizarTurno');
btnActualizarTurno.addEventListener('click', (event) => {
    event.preventDefault();
    const formActualizarTurno = document.getElementById('FormActualizarTurno');
    const horaIngreso = document.getElementById('horaIngreso_turno_act').value;
    const horaSalida = document.getElementById('horaSalida_turno_act').value;
    // Validación: hora de salida debe ser mayor a hora de ingreso
    if (horaIngreso >= horaSalida) {
        Swal.fire({
            icon: 'warning',
            title: 'Horario inválido',
            text: 'La hora de salida debe ser mayor que la hora de ingreso.'
        });
        return;
    }
    const formDataObj = {
        id_turno_act: document.getElementById('idActualizarTurno').value,
        nombre_act: document.getElementById('nombre_turno_act').value,
        horaIngreso_act: horaIngreso,
        horaSalida_act: horaSalida,
        estado_act: document.getElementById('estado_turno_act').value
    };
    const jsonData = JSON.stringify(formDataObj);
    fetch('/Proyecto-Web2/C_Logica/logica_turnos.php', {
        method: 'PUT',
        body: jsonData
    })
        .then(response => response.text())
        .then(data => {
            data = JSON.parse(data);
            if (data.status === 'success') {
                alert('Turno actualizado exitosamente');
                listTurnos();
                document.getElementById('modalActualizarTurno').close();
            } else {
                console.error('Error:', data);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al actualizar el turno');
        });
});

function actualizarEstadoTurno(id, estado) {
    fetch('/Proyecto-Web2/C_Logica/logica_turnos.php', {
        method: 'PATCH',
        body: JSON.stringify({ id_turno: id, estado })
    })
        .then(response => response.text())
        .then(data => {
            const parseData = JSON.parse(data);
            if (parseData.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Turno desactivado',
                    text: 'El turno ya no se mostrará en la tabla.',
                    timer: 1500,
                    showConfirmButton: false
                });
                listTurnos();
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
    listTurnos();
});

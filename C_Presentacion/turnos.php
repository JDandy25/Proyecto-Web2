<div class="contenidoRegistrarTurno hidden">
    <form id="FormRegistrarTurno" autocomplete="off" action="" method="post">
        <h1 class="tituloContenido">Registrar Turno</h1>
        <div class="datosRL">
            <h2 class="tituloContenedor">Datos del Turno</h2>
            <div class="datosAgrupados">
                <div class="groupDatos">
                    <label for="nombre_turno_reg">Nombre <span class="requerido">*</span></label>
                    <input type="text" class="inputDatos" name="nombre" id="nombre_turno_reg" placeholder="Ingrese nombre" required>
                </div>
                <div class="groupDatos">
                    <label for="horaIngreso_turno_reg">Hora Ingreso <span class="requerido">*</span></label>
                    <input type="time" class="inputDatos" name="horaIngreso" id="horaIngreso_turno_reg" required>
                </div>
                <div class="groupDatos">
                    <label for="horaSalida_turno_reg">Hora Salida <span class="requerido">*</span></label>
                    <input type="time" class="inputDatos" name="horaSalida" id="horaSalida_turno_reg" required>
                </div>
                <div class="groupDatos">
                    <label for="estado_turno_reg">Estado</label>
                    <select name="estado" class="inputDatos" id="estado_turno_reg">
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="botones">
            <button type="submit" class="btn" id="btnRegistrarTurno"><i class="fa-solid fa-check A"></i> Registrar</button>
            <button type="button" class="btncancelar" id="cancelarTurno"><i class="fa-solid fa-trash A"></i> Cancelar</button>
        </div>
    </form>
</div>

<!-- INICIO Lista de Turnos -->
<div class="contenidoListaTurno hidden">
    <h1 class="tituloContenido">Lista de Turnos</h1>
    <div class="buscador">
        <div class="contenedorBuscador">
            <input type="text" id="inputBusquedaTurno" class="inputBusqueda" placeholder="Buscar Turno">
            <button type="button" class="botonBusquedaTurno">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </div>
    </div>
    <div class="contenedorTablaprestamos">
        <div class="contadorTurnos" style="margin-bottom: 8px; font-weight: bold;">Total: 0</div>
        <table class="tablprestamos">
            <thead class="cabeceraPrestamos">
                <tr>
                    <th>Nombre</th>
                    <th>Hora Ingreso</th>
                    <th>Hora Salida</th>
                    <th>Estado</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody class="tbodyTurno">
                <!-- Aquí se agregarán los registros dinámicamente -->
            </tbody>
        </table>
        <div class="navegacionTabla">
            <button class="btnAnteriorTurno" type="button"><i class="fa-solid fa-angle-left"></i></button>
            <p class="paginacionTurno">1 - 1</p>
            <button class="btnSiguienteTurno" type="button"><i class="fa-solid fa-angle-right"></i></button>
        </div>
    </div>
</div>
<!-- FIN Lista de Turnos -->

<!--
NOTA: Asegúrate de que este bloque de lista de turnos y sus scripts solo estén en turnos.php.
NO incluyas turnos.js ni este bloque en clientes.php ni en otras vistas.
-->

<dialog id="modalActualizarTurno" class="modal">
  <div class="dialog-content">
    <form id="FormActualizarTurno" autocomplete="off" action="" method="post">
      <h1 class="tituloContenido">Actualizar Turno</h1>
      <div class="datos">
        <h2 class="tituloContenedor">Datos del Turno</h2>
        <div class="datosAgrupados">
          <div class="groupDatos hidden">
            <label for="idActualizarTurno" class="label">ID <span class="requerido">*</span></label>
            <input type="text" class="inputDatos" name="idActualizarTurno" id="idActualizarTurno" required>
          </div>
          <div class="groupDatos">
            <label for="nombre_turno_act" class="label">Nombre <span class="requerido">*</span></label>
            <input type="text" class="inputDatos" name="nombre_actualizar" id="nombre_turno_act" required>
          </div>
          <div class="groupDatos">
            <label for="horaIngreso_turno_act" class="label">Hora Ingreso <span class="requerido">*</span></label>
            <input type="time" class="inputDatos" name="horaIngreso_actualizar" id="horaIngreso_turno_act" required>
          </div>
          <div class="groupDatos">
            <label for="horaSalida_turno_act" class="label">Hora Salida <span class="requerido">*</span></label>
            <input type="time" class="inputDatos" name="horaSalida_actualizar" id="horaSalida_turno_act" required>
          </div>
          <div class="groupDatos">
            <label for="estado_turno_act" class="label">Estado</label>
            <select class="inputDatos" name="estado_actualizar" id="estado_turno_act">
              <option value="1">Activo</option>
              <option value="0">Inactivo</option>
            </select>
          </div>
        </div>
      </div>
      <div class="botones">
        <button type="submit" class="btnActualizarTurno" id="btnActualizarTurno">
          <i class="fa-solid fa-check A"></i> Actualizar
        </button>
        <button type="button" class="btncancelarActualizarTurno" id="btncancelarActualizarTurno">
          <i class="fa-solid fa-trash A"></i> Cancelar
        </button>
      </div>
    </form>
  </div>
</dialog>

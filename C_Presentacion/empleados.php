<div class="contenidoRegistrarEmpleado hidden">
    <form id="FormRegistrarEmpleado" autocomplete="off" action="" method="post">
        <h1 class="tituloContenido">Registrar Empleado</h1>

        <div class="datosRL">
            <h2 class="tituloContenedor">Datos del Empleado</h2>
            <div class="datosAgrupados">

                <div class="groupDatos">
                    <label for="nombre_registro">Nombre <span class="requerido">*</span></label>
                    <input type="text" class="inputDatos" name="nombre" id="nombre_registro" placeholder="Ingrese nombre" required>
                </div>

                <div class="groupDatos">
                    <label for="apePater_registro">Apellido Paterno <span class="requerido">*</span></label>
                    <input type="text" class="inputDatos" name="apePater" id="apePater_registro" placeholder="Ingrese apellido paterno" required>
                </div>

                <div class="groupDatos">
                    <label for="apeMater_registro">Apellido Materno</label>
                    <input type="text" class="inputDatos" name="apeMater" id="apeMater_registro" placeholder="Ingrese apellido materno">
                </div>

                <div class="groupDatos">
                    <label for="dni_registro">DNI <span class="requerido">*</span></label>
                    <input type="tel" class="inputDatos" name="dni" id="dni_registro" placeholder="Ingrese DNI" minlength="8" maxlength="8" pattern="[0-9]{8}" inputmode="numeric" required>
                </div>

                <div class="groupDatos">
                    <label for="direccion_registro">Dirección</label>
                    <input type="text" class="inputDatos" name="direccion" id="direccion_registro" placeholder="Ingrese dirección">
                </div>

                <div class="groupDatos">
                    <label for="telefono_registro">Teléfono <span class="requerido">*</span></label>
                    <input type="tel" class="inputDatos" name="telefono" id="telefono_registro" placeholder="Ingrese teléfono" minlength="9" maxlength="9" pattern="[0-9]{9}" inputmode="numeric" required>
                </div>

                <div class="groupDatos">
                    <label for="correo_registro">Correo</label>
                    <input type="email" class="inputDatos" name="correo" id="correo_registro" placeholder="Ingrese correo">
                </div>

                <div class="groupDatos">
                    <label for="tipoUsuario" class="label">Tipo de Usuario <span class="requerido">*</span></label>
                    <div style="display: flex; align-items: center; gap: 5px;">
                        <input type="text" id="tipoUsuario" class="inputDatos" placeholder="Seleccione o cree un usuario" readonly required>
                        <button type="button" id="btnCrearNuevoUsuario" class="btn-sm"><i class="fa-solid fa-user-plus"></i></button>
                    </div>
                    <input type="text" id="idUsuarioRegistrado" name="id_usuario" class="inputDatos" placeholder="ID Usuario" readonly style="width: 120px; margin-top: 5px;">
                </div>

                <div class="groupDatos">
                    <label for="id_turno">Turno <span class="requerido">*</span></label>
                    <select name="id_turno" id="id_turno" class="inputDatos" required>
                        <option value="">Seleccione</option>
                        </select>
                </div>

                <div class="groupDatos">
                    <label for="estado">Estado</label>
                    <select name="estado" class="inputDatos">
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                </div>

            </div>
        </div>

        <div class="botones">
            <button type="submit" class="btn" id="btnRegistrarEmpleado"><i class="fa-solid fa-check A"></i> Registrar</button>
            <button type="button" class="btncancelar" id="cancelar_registro"><i class="fa-solid fa-trash A"></i> Cancelar</button>
        </div>
    </form>
</div>



<!-- INICIO Lista de Empleados -->
<div class="contenidoListaEmpleado hidden">
    <h1 class="tituloContenido">Lista de Empleados</h1>
    <div class="buscador">
        <div class="contenedorBuscador">
            <input type="text" id="inputBusquedaEmpleado" class="inputBusqueda" placeholder="Buscar empleado">
            <button type="button" class="botonBusqueda">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </div>
    </div>
    <div class="contenedorTablaprestamos">
        <table class="tablprestamos">
            <thead class="cabeceraPrestamos">
                <tr>
                    <th>Nombre</th>
                    <th>Apellido Paterno</th>
                    <th>Apellido Materno</th>
                    <th>DNI</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Dirección</th>
                    <th>Tipo de Usuario</th>
                    <th>Turno</th>
                    <th>Estado</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody class="tbodyEmpleados">
                <!-- Aquí se agregarán los registros dinámicamente -->
            </tbody>
        </table>
        <div class="navegacionTabla">
            <button class="btnAnterior btnAnteriorEmpleados" type="button"><i class="fa-solid fa-angle-left"></i></button>
            <p class="paginacion paginacionEmpleados">1 - 1</p>
            <button class="btnSiguiente btnSiguienteEmpleados" type="button"><i class="fa-solid fa-angle-right"></i></button>
        </div>
    </div>
</div>
<!-- FIN Lista de Empleados -->

<!--
NOTA: Asegúrate de que este bloque de lista de empleados y sus scripts solo estén en empleados.php.
NO incluyas empleados.js ni este bloque en usuarios.php ni en otras vistas.
-->



<dialog id="modalActualizarEmpleado" class="modal">
  <div class="dialog-content">
    <form id="FormActualizarEmpleado" autocomplete="off" action="" method="post">
      <h1 class="tituloContenido">Actualizar Empleado</h1>

      <div class="datos">
        <h2 class="tituloContenedor">Datos del Empleado</h2>

        <div class="datosAgrupados">
          <div class="groupDatos hidden">
            <label for="idActualizarEmpleado" class="label">ID <span class="requerido">*</span></label>
            <input type="text" class="inputDatos" name="idActualizarEmpleado" id="idActualizarEmpleado" required>
          </div>

          <div class="groupDatos">
            <label for="nombre_actualizar" class="label">Nombre <span class="requerido">*</span></label>
            <input type="text" class="inputDatos" name="nombre" id="nombre_actualizar" required>
          </div>

          <div class="groupDatos">
            <label for="apellido_paterno_actualizar" class="label">Apellido Paterno <span class="requerido">*</span></label>
            <input type="text" class="inputDatos" name="apePater" id="apellido_paterno_actualizar" required>
          </div>

          <div class="groupDatos">
            <label for="apellido_materno_actualizar" class="label">Apellido Materno</label>
            <input type="text" class="inputDatos" name="apeMater" id="apellido_materno_actualizar">
          </div>

          <div class="groupDatos">
            <label for="dni_actualizar" class="label">DNI <span class="requerido">*</span></label>
            <input type="text" class="inputDatos" name="dni" id="dni_actualizar" maxlength="8" pattern="\d{8}" required>
          </div>

          <div class="groupDatos">
            <label for="direccion_actualizar" class="label">Dirección</label>
            <input type="text" class="inputDatos" name="direccion" id="direccion_actualizar">
          </div>

          <div class="groupDatos">
            <label for="telefono_actualizar" class="label">Teléfono <span class="requerido">*</span></label>
            <input type="text" class="inputDatos" name="telefono" id="telefono_actualizar" required>
          </div>

          <div class="groupDatos">
            <label for="correo_actualizar" class="label">Correo</label>
            <input type="email" class="inputDatos" name="correo" id="correo_actualizar">
          </div>

          <div class="groupDatos">
            <label for="tipoUsuarioActualizar" class="label">Tipo de Usuario <span class="requerido">*</span></label>
            <div style="display: flex; align-items: center; gap: 5px;">
              <input type="text" id="tipoUsuarioActualizar" class="inputDatos" placeholder="Seleccione o cree un usuario" readonly required>
              <button type="button" id="btnCrearNuevoUsuarioActualizar" class="btn-sm"><i class="fa-solid fa-user-plus"></i></button>
            </div>
            <input type="text" id="idUsuarioRegistradoActualizar" name="id_usuario" class="inputDatos" placeholder="ID Usuario" readonly style="width: 120px; margin-top: 5px;">
          </div>

          <div class="groupDatos">
            <label for="id_turno_actualizar" class="label">Turno <span class="requerido">*</span></label>
            <select name="id_turno" id="id_turno_actualizar" class="inputDatos" required>
              <option value="">Seleccione</option>
            </select>
          </div>

          <div class="groupDatos">
            <label for="estado" class="label">Estado</label>
            <select name="estado" id="estado" class="inputDatos">
              <option value="1">Activo</option>
              <option value="0">Inactivo</option>
            </select>
          </div>
        </div>
      </div>

      <div class="botones">
        <button type="submit" class="btnActualizarEmpleado" id="btnActualizarEmpleado">
          <i class="fa-solid fa-check A"></i> Actualizar
        </button>
        <button type="button" class="btncancelarActualizarEmpleado" id="btncancelarActualizarEmpleado">
          <i class="fa-solid fa-trash A"></i> Cancelar
        </button>
      </div>
    </form>
  </div>
</dialog>



<dialog id="modalCrearUsuario" class="modal">
  <div class="dialog-content">
    <form id="FormCrearUsuario" autocomplete="off" action="" method="post">
      <h1 class="tituloContenido">Crear Nuevo Usuario</h1>

      <div class="datos">
        <h2 class="tituloContenedor">Datos del Usuario</h2>

        <div class="datosAgrupados">
          <div class="groupDatos">
            <label for="nombreUsuario" class="label">Nombre de Usuario <span class="requerido">*</span></label>
            <input type="text" class="inputDatos" name="nombreUsuario" id="nombreUsuario" required>
          </div>

          <div class="groupDatos">
            <label for="contrasena" class="label">Contraseña <span class="requerido">*</span></label>
            <input type="password" class="inputDatos" name="contrasena" id="contrasena" required>
          </div>

          <div class="groupDatos">
            <label for="confirmarContrasena" class="label">Confirmar Contraseña <span class="requerido">*</span></label>
            <input type="password" class="inputDatos" name="confirmarContrasena" id="confirmarContrasena" required>
          </div>

          <div class="groupDatos">
            <label for="id_rol_modal" class="label">Rol <span class="requerido">*</span></label>
            <select name="id_rol_modal" id="id_rol_modal" class="inputDatos" required>
              <option value="">Seleccione</option>
            </select>
          </div>
        </div>
      </div>

      <div class="botones">
        <button type="submit" class="btn" id="btnGuardarUsuario">
          <i class="fa-solid fa-check A"></i> Guardar
        </button>
        <button type="button" class="btncancelar" id="btnCancelarCrearUsuario">
          <i class="fa-solid fa-trash A"></i> Cancelar
        </button>
      </div>
    </form>
  </div>
</dialog>

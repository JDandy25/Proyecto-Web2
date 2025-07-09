<div class="contenidoRegistrarUsuario hidden">
    <form id="FormRegistrarUsuario" autocomplete="off" action="" method="post">
        <h1 class="tituloContenido">Registrar Usuario</h1>

        <div class="datosRL">
            <h2 class="tituloContenedor">Datos del Usuario</h2>
            <div class="datosAgrupados">

                <div class="groupDatos">
                    <label for="nombre_usuario_reg">Usuario <span class="requerido">*</span></label>
                    <input type="text" class="inputDatos" name="nombre_usuario" id="nombre_usuario_reg" placeholder="Ingrese nombre de usuario" required>
                </div>

                <div class="groupDatos">
                    <label for="contrasena_reg">Contraseña <span class="requerido">*</span></label>
                    <input type="password" class="inputDatos" name="contrasena" id="contrasena_reg" placeholder="Ingrese contraseña" required>
                </div>

                <div class="groupDatos">
                    <label for="id_rol_reg">Rol <span class="requerido">*</span></label>
                    <select name="id_rol" id="id_rol_reg" class="inputDatos" required>
                        <option value="">Seleccione un rol</option>
                        <!-- Opciones de roles se llenan dinámicamente con JS -->
                    </select>
                </div>

                <div class="groupDatos">
                    <label for="estado_reg">Estado</label>
                    <select name="estado" class="inputDatos" id="estado_reg">
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="botones">
            <button type="submit" class="btn" id="btnRegistrarUsuario"><i class="fa-solid fa-check A"></i> Registrar</button>
            <button type="button" class="btncancelar" id="cancelar"><i class="fa-solid fa-trash A"></i> Cancelar</button>
        </div>
    </form>
</div>

<!-- INICIO Lista de Usuarios -->
<div class="contenidoListaUsuario hidden">
    <h1 class="tituloContenido">Lista de Usuarios</h1>
    <div class="buscador">
        <div class="contenedorBuscador">
            <input type="text" id="inputBusquedaUsuario" class="inputBusqueda" placeholder="Buscar Usuario">
            <button type="button" class="botonBusqueda">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </div>
    </div>
    <div class="contenedorTablaprestamos">
        <div class="contadorUsuarios" style="margin-bottom: 8px; font-weight: bold;">Total: 0</div>
        <table class="tablprestamos">
            <thead class="cabeceraPrestamos">
                <tr>
                    <th>Usuario</th>
                    <th>Tipo de Rol</th>
                    <th>Estado</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody class="tbodyUsuario">
                <!-- Aquí se agregarán los registros dinámicamente -->
            </tbody>
        </table>
        <div class="navegacionTabla">
            <button class="btnAnterior" type="button"><i class="fa-solid fa-angle-left"></i></button>
            <p class="paginacion">1 - 1</p>
            <button class="btnSiguiente" type="button"><i class="fa-solid fa-angle-right"></i></button>
        </div>
    </div>
</div>
<!-- FIN Lista de Usuarios -->

<!--
NOTA: Asegúrate de que este bloque de lista de usuarios y sus scripts solo estén en usuarios.php.
NO incluyas usuarios.js ni este bloque en empleados.php ni en otras vistas.
-->

<dialog id="modalActualizarUsuario" class="modal">
  <div class="dialog-content">
    <form id="FormActualizarUsuario" autocomplete="off" action="" method="post">
      <h1 class="tituloContenido">Actualizar Usuario</h1>

      <div class="datos">
        <h2 class="tituloContenedor">Datos del Usuario</h2>

        <div class="datosAgrupados">
          <div class="groupDatos hidden">
            <label for="idActualizarUsuario" class="label">ID <span class="requerido">*</span></label>
            <input type="text" class="inputDatos" name="idActualizarUsuario" id="idActualizarUsuario" required>
          </div>

          <div class="groupDatos">
            <label for="nombre_usuario_act" class="label">Usuario <span class="requerido">*</span></label>
            <input type="text" class="inputDatos" name="nombre_actualizar" id="nombre_usuario_act" required>
          </div>

          <div class="groupDatos">
            <label for="contrasena_act" class="label">Contraseña <span class="requerido">*</span></label>
            <input type="password" class="inputDatos" name="contrasena_actualizar" id="contrasena_act" required>
          </div>

          <div class="groupDatos">
            <label for="rol_act" class="label">Rol</label>
            <select class="inputDatos" name="rol_actualizar" id="rol_act"></select>
          </div>

          <div class="groupDatos">
            <label for="estado_act" class="label">Estado</label>
            <select class="inputDatos" name="estado_actualizar" id="estado_act">
              <option value="1">Activo</option>
              <option value="0">Inactivo</option>
            </select>
          </div>
        </div>
      </div>

      <div class="botones">
        <button type="submit" class="btnActualizarUsuario" id="btnActualizarUsuario">
          <i class="fa-solid fa-check A"></i> Actualizar
        </button>
        <button type="button" class="btncancelarActualizarUsuario" id="btncancelarActualizarUsuario">
          <i class="fa-solid fa-trash A"></i> Cancelar
        </button>
      </div>
    </form>
  </div>
</dialog>




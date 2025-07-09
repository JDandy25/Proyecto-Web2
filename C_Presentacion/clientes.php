<div class="contenidoRegistrarCliente hidden">
    <form id="FormRegistrarCliente" autocomplete="off" action="" method="post">
        <h1 class="tituloContenido">Registrar Cliente</h1>
        <div class="datosRL">
            <h2 class="tituloContenedor">Datos del Cliente</h2>
            <div class="datosAgrupados">
                <div class="groupDatos">
                    <label for="nombre_cliente_reg">Nombre <span class="requerido">*</span></label>
                    <input type="text" class="inputDatos" name="nombre" id="nombre_cliente_reg" placeholder="Ingrese nombre" required>
                </div>
                <div class="groupDatos">
                    <label for="apellidos_cliente_reg">Apellidos <span class="requerido">*</span></label>
                    <input type="text" class="inputDatos" name="apellidos" id="apellidos_cliente_reg" placeholder="Ingrese apellidos" required>
                </div>
                <div class="groupDatos">
                    <label for="dni_cliente_reg">DNI <span class="requerido">*</span></label>
                    <input type="text" class="inputDatos" name="dni" id="dni_cliente_reg" placeholder="Ingrese DNI" required maxlength="15">
                </div>
                <div class="groupDatos">
                    <label for="correo_cliente_reg">Correo</label>
                    <input type="email" class="inputDatos" name="correo" id="correo_cliente_reg" placeholder="Ingrese correo">
                </div>
                <div class="groupDatos">
                    <label for="telefono_cliente_reg">Teléfono</label>
                    <input type="text" class="inputDatos" name="telefono" id="telefono_cliente_reg" placeholder="Ingrese teléfono">
                </div>
                <div class="groupDatos">
                    <label for="direccion_cliente_reg">Dirección</label>
                    <input type="text" class="inputDatos" name="direccion" id="direccion_cliente_reg" placeholder="Ingrese dirección">
                </div>
                <div class="groupDatos">
                    <label for="estado_cliente_reg">Estado</label>
                    <select name="estado" class="inputDatos" id="estado_cliente_reg">
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="botones">
            <button type="submit" class="btn" id="btnRegistrarCliente"><i class="fa-solid fa-check A"></i> Registrar</button>
            <button type="button" class="btncancelar" id="cancelarCliente"><i class="fa-solid fa-trash A"></i> Cancelar</button>
        </div>
    </form>
</div>

<!-- INICIO Lista de Clientes -->
<div class="contenidoListaCliente hidden">
    <h1 class="tituloContenido">Lista de Clientes</h1>
    <div class="buscador">
        <div class="contenedorBuscador">
            <input type="text" id="inputBusquedaCliente" class="inputBusqueda" placeholder="Buscar Cliente">
            <button type="button" class="botonBusquedaCliente">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </div>
    </div>
    <div class="contenedorTablaprestamos">
        <div class="contadorClientes" style="margin-bottom: 8px; font-weight: bold;">Total: 0</div>
        <table class="tablprestamos">
            <thead class="cabeceraPrestamos">
                <tr>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>DNI</th>
                    <th>Correo</th>
                    <th>Teléfono</th>
                    <th>Dirección</th>
                    <th>Estado</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody class="tbodyCliente">
                <!-- Aquí se agregarán los registros dinámicamente -->
            </tbody>
        </table>
        <div class="navegacionTabla">
            <button class="btnAnteriorCliente" type="button"><i class="fa-solid fa-angle-left"></i></button>
            <p class="paginacionCliente">1 - 1</p>
            <button class="btnSiguienteCliente" type="button"><i class="fa-solid fa-angle-right"></i></button>
        </div>
    </div>
</div>
<!-- FIN Lista de Clientes -->

<!--
NOTA: Asegúrate de que este bloque de lista de clientes y sus scripts solo estén en clientes.php.
NO incluyas clientes.js ni este bloque en usuarios.php ni en otras vistas.
-->

<dialog id="modalActualizarCliente" class="modal">
  <div class="dialog-content">
    <form id="FormActualizarCliente" autocomplete="off" action="" method="post">
      <h1 class="tituloContenido">Actualizar Cliente</h1>
      <div class="datos">
        <h2 class="tituloContenedor">Datos del Cliente</h2>
        <div class="datosAgrupados">
          <div class="groupDatos hidden">
            <label for="idActualizarCliente" class="label">ID <span class="requerido">*</span></label>
            <input type="text" class="inputDatos" name="idActualizarCliente" id="idActualizarCliente" required>
          </div>
          <div class="groupDatos">
            <label for="nombre_cliente_act" class="label">Nombre <span class="requerido">*</span></label>
            <input type="text" class="inputDatos" name="nombre_actualizar" id="nombre_cliente_act" required>
          </div>
          <div class="groupDatos">
            <label for="apellidos_cliente_act" class="label">Apellidos <span class="requerido">*</span></label>
            <input type="text" class="inputDatos" name="apellidos_actualizar" id="apellidos_cliente_act" required>
          </div>
          <div class="groupDatos">
            <label for="dni_cliente_act" class="label">DNI <span class="requerido">*</span></label>
            <input type="text" class="inputDatos" name="dni_actualizar" id="dni_cliente_act" required maxlength="15">
          </div>
          <div class="groupDatos">
            <label for="correo_cliente_act" class="label">Correo</label>
            <input type="email" class="inputDatos" name="correo_actualizar" id="correo_cliente_act">
          </div>
          <div class="groupDatos">
            <label for="telefono_cliente_act" class="label">Teléfono</label>
            <input type="text" class="inputDatos" name="telefono_actualizar" id="telefono_cliente_act">
          </div>
          <div class="groupDatos">
            <label for="direccion_cliente_act" class="label">Dirección</label>
            <input type="text" class="inputDatos" name="direccion_actualizar" id="direccion_cliente_act">
          </div>
          <div class="groupDatos">
            <label for="estado_cliente_act" class="label">Estado</label>
            <select class="inputDatos" name="estado_actualizar" id="estado_cliente_act">
              <option value="1">Activo</option>
              <option value="0">Inactivo</option>
            </select>
          </div>
        </div>
      </div>
      <div class="botones">
        <button type="submit" class="btnActualizarCliente" id="btnActualizarCliente">
          <i class="fa-solid fa-check A"></i> Actualizar
        </button>
        <button type="button" class="btncancelarActualizarCliente" id="btncancelarActualizarCliente">
          <i class="fa-solid fa-trash A"></i> Cancelar
        </button>
      </div>
    </form>
  </div>
</dialog>

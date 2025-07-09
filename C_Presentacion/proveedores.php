<div class="contenidoRegistrarProveedor hidden">
    <form id="FormRegistrarProveedor" autocomplete="off" action="" method="post">
        <h1 class="tituloContenido">Registrar Proveedor</h1>
        <div class="datosRL">
            <h2 class="tituloContenedor">Datos del Proveedor</h2>
            <div class="datosAgrupados">
                <div class="groupDatos">
                    <label for="nombres_proveedor_reg">Nombres <span class="requerido">*</span></label>
                    <input type="text" class="inputDatos" name="nombres" id="nombres_proveedor_reg" placeholder="Ingrese nombres" required>
                </div>
                <div class="groupDatos">
                    <label for="apellidos_proveedor_reg">Apellidos <span class="requerido">*</span></label>
                    <input type="text" class="inputDatos" name="apellidos" id="apellidos_proveedor_reg" placeholder="Ingrese apellidos" required>
                </div>
                <div class="groupDatos">
                    <label for="RUC_proveedor_reg">RUC <span class="requerido">*</span></label>
                    <input type="text" class="inputDatos" name="RUC" id="RUC_proveedor_reg" placeholder="Ingrese RUC" required maxlength="15">
                </div>
                <div class="groupDatos">
                    <label for="telefono_proveedor_reg">Teléfono</label>
                    <input type="text" class="inputDatos" name="telefono" id="telefono_proveedor_reg" placeholder="Ingrese teléfono">
                </div>
                <div class="groupDatos">
                    <label for="correo_proveedor_reg">Correo</label>
                    <input type="email" class="inputDatos" name="correo" id="correo_proveedor_reg" placeholder="Ingrese correo">
                </div>
                <div class="groupDatos">
                    <label for="estado_proveedor_reg">Estado</label>
                    <select name="estado" class="inputDatos" id="estado_proveedor_reg">
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="botones">
            <button type="submit" class="btn" id="btnRegistrarProveedor"><i class="fa-solid fa-check A"></i> Registrar</button>
            <button type="button" class="btncancelar" id="cancelarProveedor"><i class="fa-solid fa-trash A"></i> Cancelar</button>
        </div>
    </form>
</div>

<!-- INICIO Lista de Proveedores -->
<div class="contenidoListaProveedor hidden">
    <h1 class="tituloContenido">Lista de Proveedores</h1>
    <div class="buscador">
        <div class="contenedorBuscador">
            <input type="text" id="inputBusquedaProveedor" class="inputBusqueda" placeholder="Buscar Proveedor">
            <button type="button" class="botonBusquedaProveedor">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </div>
    </div>
    <div class="contenedorTablaprestamos">
        <div class="contadorProveedores" style="margin-bottom: 8px; font-weight: bold;">Total: 0</div>
        <table class="tablprestamos">
            <thead class="cabeceraPrestamos">
                <tr>
                    <th>Nombres</th>
                    <th>Apellidos</th>
                    <th>RUC</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Estado</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody class="tbodyProveedor">
                <!-- Aquí se agregarán los registros dinámicamente -->
            </tbody>
        </table>
        <div class="navegacionTabla">
            <button class="btnAnteriorProveedor" type="button"><i class="fa-solid fa-angle-left"></i></button>
            <p class="paginacionProveedor">1 - 1</p>
            <button class="btnSiguienteProveedor" type="button"><i class="fa-solid fa-angle-right"></i></button>
        </div>
    </div>
</div>
<!-- FIN Lista de Proveedores -->

<!--
NOTA: Asegúrate de que este bloque de lista de proveedores y sus scripts solo estén en proveedores.php.
NO incluyas proveedores.js ni este bloque en clientes.php ni en otras vistas.
-->

<dialog id="modalActualizarProveedor" class="modal">
  <div class="dialog-content">
    <form id="FormActualizarProveedor" autocomplete="off" action="" method="post">
      <h1 class="tituloContenido">Actualizar Proveedor</h1>
      <div class="datos">
        <h2 class="tituloContenedor">Datos del Proveedor</h2>
        <div class="datosAgrupados">
          <div class="groupDatos hidden">
            <label for="idActualizarProveedor" class="label">ID <span class="requerido">*</span></label>
            <input type="text" class="inputDatos" name="idActualizarProveedor" id="idActualizarProveedor" required>
          </div>
          <div class="groupDatos">
            <label for="nombres_proveedor_act" class="label">Nombres <span class="requerido">*</span></label>
            <input type="text" class="inputDatos" name="nombres_actualizar" id="nombres_proveedor_act" required>
          </div>
          <div class="groupDatos">
            <label for="apellidos_proveedor_act" class="label">Apellidos <span class="requerido">*</span></label>
            <input type="text" class="inputDatos" name="apellidos_actualizar" id="apellidos_proveedor_act" required>
          </div>
          <div class="groupDatos">
            <label for="RUC_proveedor_act" class="label">RUC <span class="requerido">*</span></label>
            <input type="text" class="inputDatos" name="RUC_actualizar" id="RUC_proveedor_act" required maxlength="15">
          </div>
          <div class="groupDatos">
            <label for="telefono_proveedor_act" class="label">Teléfono</label>
            <input type="text" class="inputDatos" name="telefono_actualizar" id="telefono_proveedor_act">
          </div>
          <div class="groupDatos">
            <label for="correo_proveedor_act" class="label">Correo</label>
            <input type="email" class="inputDatos" name="correo_actualizar" id="correo_proveedor_act">
          </div>
          <div class="groupDatos">
            <label for="estado_proveedor_act" class="label">Estado</label>
            <select class="inputDatos" name="estado_actualizar" id="estado_proveedor_act">
              <option value="1">Activo</option>
              <option value="0">Inactivo</option>
            </select>
          </div>
        </div>
      </div>
      <div class="botones">
        <button type="submit" class="btnActualizarProveedor" id="btnActualizarProveedor">
          <i class="fa-solid fa-check A"></i> Actualizar
        </button>
        <button type="button" class="btncancelarActualizarProveedor" id="btncancelarActualizarProveedor">
          <i class="fa-solid fa-trash A"></i> Cancelar
        </button>
      </div>
    </form>
  </div>
</dialog>

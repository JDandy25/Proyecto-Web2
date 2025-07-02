<div class="contenidoRegistrarCategoria hidden">
    <form id="FormRegistrarCategoria" autocomplete="off" action="" method="post">
        <h1 class="tituloContenido">Registrar Categoría</h1>
        <div class="datosRL">
            <h2 class="tituloContenedor">Datos de la Categoría</h2>
            <div class="datosAgrupados">
                <div class="groupDatos">
                    <label for="nombre_categoria_reg">Nombre <span class="requerido">*</span></label>
                    <input type="text" class="inputDatos" name="nombre" id="nombre_categoria_reg" placeholder="Ingrese nombre" required>
                </div>
                <div class="groupDatos">
                    <label for="descripcion_categoria_reg">Descripción</label>
                    <input type="text" class="inputDatos" name="descripcion" id="descripcion_categoria_reg" placeholder="Ingrese descripción">
                </div>
                <div class="groupDatos">
                    <label for="estado_categoria_reg">Estado</label>
                    <select name="estado" class="inputDatos" id="estado_categoria_reg">
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="botones">
            <button type="submit" class="btn" id="btnRegistrarCategoria"><i class="fa-solid fa-check A"></i> Registrar</button>
            <button type="button" class="btncancelar" id="cancelarCategoria"><i class="fa-solid fa-trash A"></i> Cancelar</button>
        </div>
    </form>
</div>

<!-- INICIO Lista de Categorías -->
<div class="contenidoListaCategoria hidden">
    <h1 class="tituloContenido">Lista de Categorías</h1>
    <div class="buscador">
        <div class="contenedorBuscador">
            <input type="text" id="inputBusquedaCategoria" class="inputBusqueda" placeholder="Buscar Categoría">
            <button type="button" class="botonBusquedaCategoria">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </div>
    </div>
    <div class="contenedorTablaprestamos">
        <div class="contadorCategorias" style="margin-bottom: 8px; font-weight: bold;">Total: 0</div>
        <table class="tablprestamos">
            <thead class="cabeceraPrestamos">
                <tr>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Estado</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody class="tbodyCategoria">
                <!-- Aquí se agregarán los registros dinámicamente -->
            </tbody>
        </table>
        <div class="navegacionTabla">
            <button class="btnAnteriorCategoria" type="button"><i class="fa-solid fa-angle-left"></i></button>
            <p class="paginacionCategoria">1 - 1</p>
            <button class="btnSiguienteCategoria" type="button"><i class="fa-solid fa-angle-right"></i></button>
        </div>
    </div>
</div>
<!-- FIN Lista de Categorías -->

<!--
NOTA: Asegúrate de que este bloque de lista de categorías y sus scripts solo estén en categorias.php.
NO incluyas categorias.js ni este bloque en clientes.php ni en otras vistas.
-->

<dialog id="modalActualizarCategoria" class="modal">
  <div class="dialog-content">
    <form id="FormActualizarCategoria" autocomplete="off" action="" method="post">
      <h1 class="tituloContenido">Actualizar Categoría</h1>
      <div class="datos">
        <h2 class="tituloContenedor">Datos de la Categoría</h2>
        <div class="datosAgrupados">
          <div class="groupDatos hidden">
            <label for="idActualizarCategoria" class="label">ID <span class="requerido">*</span></label>
            <input type="text" class="inputDatos" name="idActualizarCategoria" id="idActualizarCategoria" required>
          </div>
          <div class="groupDatos">
            <label for="nombre_categoria_act" class="label">Nombre <span class="requerido">*</span></label>
            <input type="text" class="inputDatos" name="nombre_actualizar" id="nombre_categoria_act" required>
          </div>
          <div class="groupDatos">
            <label for="descripcion_categoria_act" class="label">Descripción</label>
            <input type="text" class="inputDatos" name="descripcion_actualizar" id="descripcion_categoria_act">
          </div>
          <div class="groupDatos">
            <label for="estado_categoria_act" class="label">Estado</label>
            <select class="inputDatos" name="estado_actualizar" id="estado_categoria_act">
              <option value="1">Activo</option>
              <option value="0">Inactivo</option>
            </select>
          </div>
        </div>
      </div>
      <div class="botones">
        <button type="submit" class="btnActualizarCategoria" id="btnActualizarCategoria">
          <i class="fa-solid fa-check A"></i> Actualizar
        </button>
        <button type="button" class="btncancelarActualizarCategoria" id="btncancelarActualizarCategoria">
          <i class="fa-solid fa-trash A"></i> Cancelar
        </button>
      </div>
    </form>
  </div>
</dialog>

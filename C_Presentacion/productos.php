<!-- Formulario de Registro de Producto -->
<div class="contenidoRegistrarProducto hidden">
    <form id="FormRegistrarProducto" autocomplete="off" action="" method="post" enctype="multipart/form-data">
        <h1 class="tituloContenido">Registrar Producto</h1>

        <div class="datosRL">
            <h2 class="tituloContenedor">Datos del Producto</h2>
            <div class="datosAgrupados">

                <div class="groupDatos">
                    <label for="nombre_producto_reg">Nombre <span class="requerido">*</span></label>
                    <input type="text" class="inputDatos" name="nombre_producto" id="nombre_producto_reg" placeholder="Ingrese nombre del producto" required>
                </div>

                <div class="groupDatos">
                    <label for="descripcion_reg">Descripción</label>
                    <textarea class="inputDatos" name="descripcion" id="descripcion_reg" placeholder="Ingrese descripción del producto" rows="3"></textarea>
                </div>

                <div class="groupDatos">
                    <label for="codigo_reg">Código <span class="requerido">*</span></label>
                    <input type="text" class="inputDatos" name="codigo" id="codigo_reg" placeholder="Ingrese código del producto" required>
                </div>

                <div class="groupDatos">
                    <label for="precio_reg">Precio <span class="requerido">*</span></label>
                    <input type="number" class="inputDatos" name="precio" id="precio_reg" placeholder="Ingrese precio del producto" min="0" step="0.01" required>
                </div>

                <div class="groupDatos">
                    <label for="stock_reg">Stock <span class="requerido">*</span></label>
                    <input type="number" class="inputDatos" name="stock" id="stock_reg" placeholder="Ingrese cantidad en stock" min="0" required>
                </div>

                <div class="groupDatos">
                    <label for="imagen_reg">Imagen</label>
                    <input type="file" class="inputDatos" name="imagen" id="imagen_reg" accept="image/*">
                </div>

                <div class="groupDatos">
                    <label for="id_categoria_reg">Categoría <span class="requerido">*</span></label>
                    <select name="id_categoria" id="id_categoria_reg" class="inputDatos" required>
                        <option value="">Seleccione una categoría</option>
                        <!-- Opciones de categorías se llenan dinámicamente con JS -->
                    </select>
                </div>

                <div class="groupDatos">
                    <label for="estado_producto_reg">Estado</label>
                    <select name="estado_producto" class="inputDatos" id="estado_producto_reg">
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
¿                    </select>
                </div>
            </div>
        </div>

        <div class="botones">
            <button type="submit" class="btn" id="btnRegistrarProducto"><i class="fa-solid fa-check A"></i> Registrar</button>
            <button type="button" class="btncancelar" id="cancelarProducto"><i class="fa-solid fa-trash A"></i> Cancelar</button>
        </div>
    </form>
</div>

<!-- Lista de Productos -->
<div class="contenidoListaProducto hidden">
    <h1 class="tituloContenido">Lista de Productos</h1>
    <div class="buscador">
        <div class="contenedorBuscador">
            <input type="text" id="inputBusquedaProducto" class="inputBusqueda" placeholder="Buscar Producto">
            <button type="button" class="botonBusqueda" id="btnBuscarProducto">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </div>
    </div>
    <div class="contenedorTablaprestamos">
        <div class="contadorProductos" style="margin-bottom: 8px; font-weight: bold;">Total: 0</div>
        <table class="tablprestamos">
            <thead class="cabeceraPrestamos">
                <tr>
                    
                    <th>Nombre</th>
                    <th>Código</th>
                    <th>Precio</th>
                    <th>Imagen</th>
                    <th>Stock</th>
                    
                    <th>Categoría</th>
                    <th>Estado</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody class="tbodyProducto">
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

<!-- Modal para Actualizar Producto -->
<dialog id="modalActualizarProducto" class="modal">
  <div class="dialog-content">
    <form id="FormActualizarProducto" autocomplete="off" action="" method="post" enctype="multipart/form-data">
      <h1 class="tituloContenido">Actualizar Producto</h1>

      <div class="datos">
        <h2 class="tituloContenedor">Datos del Producto</h2>

        <div class="datosAgrupados">
          <div class="groupDatos hidden">
            <label for="idActualizarProducto" class="label">ID <span class="requerido">*</span></label>
            <input type="text" class="inputDatos" name="idActualizarProducto" id="idActualizarProducto" required>
          </div>

          <div class="groupDatos">
            <label for="nombre_producto_act" class="label">Nombre <span class="requerido">*</span></label>
            <input type="text" class="inputDatos" name="nombre_producto_act" id="nombre_producto_act" required>
          </div>

          <div class="groupDatos">
            <label for="descripcion_act" class="label">Descripción</label>
            <textarea class="inputDatos" name="descripcion_act" id="descripcion_act" rows="3"></textarea>
          </div>

          <div class="groupDatos">
            <label for="codigo_act" class="label">Código <span class="requerido">*</span></label>
            <input type="text" class="inputDatos" name="codigo_act" id="codigo_act" required>
          </div>
          <div class="groupDatos">
            <label for="precio_act" class="label">Precio <span class="requerido">*</span></label>
            <input type="number" class="inputDatos" name="precio_act" id="precio_act" min="0" step="0.01" required>
          </div>

          <div class="groupDatos">
            <label for="stock_act" class="label">Stock <span class="requerido">*</span></label>
            <input type="number" class="inputDatos" name="stock_act" id="stock_act" min="0" required>
          </div>

          <div class="groupDatos">
            <label for="imagen_act" class="label">Imagen</label>
            <input type="file" class="inputDatos" name="imagen_actualizar" id="imagen_act" accept="image/*">
            <div id="previewImagen" style="margin-top: 10px;"></div>
          </div>

          <div class="groupDatos">
            <label for="categoria_act" class="label">Categoría</label>
            <select class="inputDatos" name="categoria_actualizar" id="categoria_act"></select>
          </div>

          <div class="groupDatos">
            <label for="estado_producto_act" class="label">Estado</label>
            <select class="inputDatos" name="estado_producto_actualizar" id="estado_producto_act">
              <option value="1">Activo</option>
              <option value="0">Inactivo</option>
            </select>
          </div>
        </div>
      </div>

      <div class="botones">
        <button type="submit" class="btnActualizarProducto" id="btnActualizarProducto">
          <i class="fa-solid fa-check A"></i> Actualizar
        </button>
        <button type="button" class="btncancelarActualizarProducto" id="btncancelarActualizarProducto">
          <i class="fa-solid fa-trash A"></i> Cancelar
        </button>
      </div>
    </form>
  </div>
</dialog>
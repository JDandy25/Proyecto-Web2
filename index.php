<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/menu.css">
    <link rel="stylesheet" href="CSS/empleado.css">
    <link rel="stylesheet" href="CSS/proveedor.css">
    <link rel="stylesheet" href="CSS/cliente.css">
    <link rel="stylesheet" href="CSS/StylosGenerales.css">
    <link rel="stylesheet" href="CSS/usuario.css">
    <link rel="stylesheet" href="CSS/reportes.css">
    <title>Administracion Gasolinera</title>
</head>

<body>

    <div class="ContendorPrincipal">
        <div class="menu">
            <div class="contenedorLogo">
                <img src="recursos/logogasolinera.jpeg" alt="">
            </div>
            <nav class="nav">
                <ul class="list">

                    <li class="list__item list__item--click">
                        <div class="list__button list__button--click">
                            <i class="fa-solid fa-shop"></i>
                            <a href="#" class="nav__link">Compras</a>
                            <i class="fa-solid fa-angle-right list__arrow"></i>
                        </div>

                        <ul class="list__show">
                            <li class="list__inside">

                                <a href="#" id="registrarCompra" class="nav__link nav__link--inside registrarCompra ">
                                    <img src="" alt="" class="list__img">
                                    Registrar Compras</a>
                            </li>
                            <li class="list__inside">

                                <a href="" id="buscarCompra" class="nav__link nav__link--inside buscarCompra">
                                    <img src="" alt="" class="list__img">Buscar
                                    Compras</a>
                            </li>
                            <li class="list__inside" hidden>

                                <a href="" class="nav__link nav__link--inside">
                                    <img src="" alt="" class="list__img"> Editar
                                    Compras</a>
                            </li>
                            <li class="list__inside" hidden>

                                <a href="" class="nav__link nav__link--inside">
                                    <img src="" alt="" class="list__img">
                                    Eliminar
                                    Compras</a>
                            </li>
                        </ul>
                    </li>

                    <li class="list__item list__item--click">
                        <div class="list__button list__button--click">
                            <i class="fa-solid fa-receipt"></i>
                            <a href="#" class="nav__link">Ventas</a>
                            <i class="fa-solid fa-angle-right list__arrow"></i>
                        </div>

                        <ul class="list__show">
                            <li class="list__inside">

                                <a href="#" id="registrarVenta" class="nav__link nav__link--inside registrarVenta ">
                                    <img src="" alt="" class="list__img">
                                    Registrar Ventas</a>
                            </li>
                            <li class="list__inside">

                                <a href="" id="buscarVenta" class="nav__link nav__link--inside buscarVenta">
                                    <img src="" alt="" class="list__img">Buscar
                                    Ventas</a>
                            </li>
                            <li class="list__inside" hidden>

                                <a href="" class="nav__link nav__link--inside">
                                    <img src="" alt="" class="list__img"> Editar
                                    Ventas</a>
                            </li>
                            <li class="list__inside" hidden>

                                <a href="" class="nav__link nav__link--inside">
                                    <img src="" alt="" class="list__img">
                                    Eliminar
                                    Ventas</a>
                            </li>
                        </ul>
                    </li>

                    <li class="list__item list__item--click">
                        <div class="list__button list__button--click">
                            <i class="fa-solid fa-box"></i>
                            <a href="#" class="nav__link">Productos</a>
                            <i class="fa-solid fa-angle-right list__arrow"></i>

                        </div>

                        <ul class="list__show">
                            <li class="list__inside">

                                <a href="" id="registrarProducto" class="nav__link nav__link--inside registrarProducto">

                                    Registrar Producto</a>
                            </li>
                            <li class="list__inside">

                                <a href="" id="buscarProducto" class="nav__link nav__link--inside buscarProducto">
                                    Buscar Producto
                                    </a>
                            </li>
                            <li class="list__inside" hidden>

                                <a href="" class="nav__link nav__link--inside">
                                    <img src="" alt="" class="list__img"> Editar
                                    Producto</a>
                            </li>
                            <li class="list__inside" hidden>

                                <a href="" class="nav__link nav__link--inside">
                                    <img src="" alt="" class="list__img">
                                    Eliminar
                                    Producto</a>
                            </li>
                        </ul>
                    </li>

                    <li class="list__item list__item--click">
                        <div class="list__button list__button--click">
                            <i class="fa-solid fa-users-gear"></i>
                            <a href="#" class="nav__link">Empleados</a>
                            <i class="fa-solid fa-angle-right list__arrow"></i>

                        </div>

                        <ul class="list__show">
                            <li class="list__inside">

                                <a href="" id="registrarEmpleado" class="nav__link nav__link--inside registrarEmpleado">

                                    Registrar Empleado</a>
                            </li>
                            <li class="list__inside">

                                <a href="" id="buscarEmpleado" class="nav__link nav__link--inside buscarEmpleado">
                                    Buscar Empleado
                                    </a>
                            </li>
                            <li class="list__inside" hidden>

                                <a href="" class="nav__link nav__link--inside">
                                    <img src="" alt="" class="list__img"> Editar
                                    Empleado</a>
                            </li>
                            <li class="list__inside" hidden>

                                <a href="" class="nav__link nav__link--inside">
                                    <img src="" alt="" class="list__img">
                                    Eliminar
                                    Empleado</a>
                            </li>
                        </ul>
                    </li>
                    


                    <li class="list__item list__item--click">
                        <div class="list__button list__button--click">
                            <i class="fa-solid fa-user"></i>
                            <a href="#" class="nav__link">Usuarios</a>
                            <i class="fa-solid fa-angle-right list__arrow"></i>
                        </div>

                        <ul class="list__show">
                            <li class="list__inside">

                                <a href="#" id="registrarUsuario" class="nav__link nav__link--inside registrarUsuario ">
                                    <img src="" alt="" class="list__img">
                                    Registrar Usuario</a>
                            </li>
                            <li class="list__inside">

                                <a href="" id="buscarUsuario" class="nav__link nav__link--inside buscarUsuario">
                                    <img src="" alt="" class="list__img">Buscar
                                    Usuario</a>
                            </li>
                            <li class="list__inside" hidden>

                                <a href="" class="nav__link nav__link--inside">
                                    <img src="" alt="" class="list__img"> Editar
                                    Usuario</a>
                            </li>
                            <li class="list__inside" hidden>

                                <a href="" class="nav__link nav__link--inside">
                                    <img src="" alt="" class="list__img">
                                    Eliminar
                                    Usuario</a>
                            </li>
                        </ul>
                    </li>


                    <li class="list__item list__item--click">
                        <div class="list__button list__button--click">
                            <i class="fa-solid fa-users-line"></i>
                            <a href="#" class="nav__link">Clientes</a>
                            <i class="fa-solid fa-angle-right list__arrow"></i>
                        </div>

                        <ul class="list__show">
                            <li class="list__inside">

                                <a href="#" id="registrarCliente" class="nav__link nav__link--inside registrarCliente ">
                                    <img src="" alt="" class="list__img">
                                    Registrar Cliente</a>
                            </li>
                            <li class="list__inside">

                                <a href="" id="buscarCliente" class="nav__link nav__link--inside buscarCliente">
                                    <img src="" alt="" class="list__img">Buscar
                                    Cliente</a>
                            </li>
                            <li class="list__inside" hidden>

                                <a href="" class="nav__link nav__link--inside">
                                    <img src="" alt="" class="list__img"> Editar
                                    Cliente</a>
                            </li>
                            <li class="list__inside" hidden>

                                <a href="" class="nav__link nav__link--inside">
                                    <img src="" alt="" class="list__img">
                                    Eliminar
                                    Cliente</a>
                            </li>
                        </ul>
                    </li>
                

                                        <li class="list__item list__item--click">
                        <div class="list__button list__button--click">
                            <i class="fa-solid fa-brands fa-truck-field"></i>
                            <a href="#" class="nav__link">Proveedores</a>
                            <i class="fa-solid fa-angle-right list__arrow"></i>
                        </div>

                        <ul class="list__show">
                            <li class="list__inside">

                                <a href="" id="registrarProveedor" class="nav__link nav__link--inside registrarProveedor ">
                                    <img src="" alt="" class="list__img">
                                    Registrar Proveedor</a>
                            </li>
                            <li class="list__inside">

                                <a href="" id="buscarProveedor" class="nav__link nav__link--inside buscarProveedor">
                                    <img src="" alt="" class="list__img">Buscar Proveedor</a>
                            </li>
                            <li class="list__inside" hidden>

                                <a href="" class="nav__link nav__link--inside">
                                    <img src="" alt="" class="list__img"> Editar
                                    Proveedor</a>
                            </li>
                            <li class="list__inside" hidden>

                                <a href="" class="nav__link nav__link--inside">
                                    <img src="" alt="" class="list__img">
                                    Eliminar
                                    Proveedor</a>
                            </li>
                        </ul>
                    </li>

                    <li class="list__item list__item--click">
                        <div class="list__button list__button--click">
                            <i class="fa-solid fa-tags"></i>
                            <a href="#" class="nav__link">Categoria</a>
                            <i class="fa-solid fa-angle-right list__arrow"></i>
                        </div>

                        <ul class="list__show">
                            <li class="list__inside">

                                <a href="#" id="registrarCategoria" class="nav__link nav__link--inside registrarCategoria ">
                                    <img src="" alt="" class="list__img">
                                    Registrar Categoria</a>
                            </li>
                            <li class="list__inside">

                                <a href="" id="buscarCategoria" class="nav__link nav__link--inside buscarCategoria">
                                    <img src="" alt="" class="list__img">Buscar
                                    Categoria</a>
                            </li>
                            <li class="list__inside" hidden>

                                <a href="" class="nav__link nav__link--inside">
                                    <img src="" alt="" class="list__img"> Editar
                                    Categoria</a>
                            </li>
                            <li class="list__inside" hidden>

                                <a href="" class="nav__link nav__link--inside">
                                    <img src="" alt="" class="list__img">
                                    Eliminar
                                    Categoria</a>
                            </li>
                        </ul>
                    </li>

                    
                    <li class="list__item list__item--click">
                        <div class="list__button list__button--click">
                            <i class="fa-solid fa-chart-line"></i>
                            <a href="#" class="nav__link">Reportes</a>
                            <i class="fa-solid fa-angle-right list__arrow"></i>
                        </div>

                        <ul class="list__show">
                            <li class="list__inside">
                                <a href="#" id="abrirReporteTurnos" class="nav__link nav__link--inside">
                                    Turnos por Fecha
                                </a>
                            </li>
                            <li class="list__inside">
                            <a href="#" id="abrirReporteEmpleado" class="nav__link nav__link--inside">
                                Empleados
                            </a>
                        </li>
                        <li class="list__inside">
                            <a href="#" id="abrirReporteIngresos" class="nav__link nav__link--inside">
                                Ingresos por Día
                            </a>
                        </li>
                        <li class="list__inside">
                        <a href="#" id="abrirReporteStock" class="nav__link nav__link--inside">
                            Productos con Stock
                        </a>
                    </li>
                        </ul>
                    </li>

                     <li class="list__item list__item--click">
                        <div class="list__button list__button--click">
                            <i class="fa-solid fa-business-time"></i>
                            <a href="#" class="nav__link">Turnos</a>
                            <i class="fa-solid fa-angle-right list__arrow"></i>
                        </div>

                        <ul class="list__show">
                            <li class="list__inside">

                                <a href="#" id="registrarTurno" class="nav__link nav__link--inside registrarTurno ">
                                    <img src="" alt="" class="list__img">
                                    Registrar Turno</a>
                            </li>
                            <li class="list__inside">

                                <a href="" id="buscarTurno" class="nav__link nav__link--inside buscarTurno">
                                    <img src="" alt="" class="list__img">Buscar
                                    Turno</a>
                            </li>
                            <li class="list__inside" hidden>

                                <a href="" class="nav__link nav__link--inside">
                                    <img src="" alt="" class="list__img"> Editar
                                    Turno</a>
                            </li>
                            <li class="list__inside" hidden>

                                <a href="" class="nav__link nav__link--inside">
                                    <img src="" alt="" class="list__img">
                                    Eliminar
                                    Turno</a>
                            </li>
                        </ul>
                    </li>

                </ul>

            </nav>
        </div>
        <div class="contenedorContendido">
            <div class="informacionAdicional">
                <a href="" class="botonInicio " id="BotonInicio">
                    <i class="fa-solid fa-house"></i>Inicio</a>
                <div class="informacion">
                    <div class="groupInfoUsuario">
                        <img src="" alt="">
                        <label for="">Bienvenido </label>
                        <span class="usuario">?</span>
                        <label for="">/ Rol: </label>
                        <span class="rol">?</span>
                    </div>

                    <a href=""><img src="" alt="" class="Ajustes" hidden></a>

                </div>
            </div>

            <?php
            include($_SERVER["DOCUMENT_ROOT"] . '/Proyecto-Web2/C_Presentacion/ventas.php');
            ?>
          
            <?php
            include($_SERVER["DOCUMENT_ROOT"] . '/Proyecto-Web2/C_Presentacion/compras.php');
            ?>
            
            <?php
            include($_SERVER["DOCUMENT_ROOT"] . '/Proyecto-Web2/C_Presentacion/productos.php');
            ?>

            <?php
            include($_SERVER["DOCUMENT_ROOT"] . '/Proyecto-Web2/C_Presentacion/empleados.php');
            ?>

            <?php
            include($_SERVER["DOCUMENT_ROOT"] . '/Proyecto-Web2/C_Presentacion/usuarios.php');
            ?>

            <?php
            include($_SERVER["DOCUMENT_ROOT"] . '/Proyecto-Web2/C_Presentacion/clientes.php');
            ?>

            <?php
            include($_SERVER["DOCUMENT_ROOT"] . '/Proyecto-Web2/C_Presentacion/proveedores.php');
            ?>

            <?php
            include($_SERVER["DOCUMENT_ROOT"] . '/Proyecto-Web2/C_Presentacion/categorias.php');
            ?>

            <?php
            include($_SERVER["DOCUMENT_ROOT"] . '/Proyecto-Web2/C_Presentacion/turnos.php');
            ?>

            <?php
            include($_SERVER["DOCUMENT_ROOT"] . '/Proyecto-Web2/C_Presentacion/reportes.php');
            ?>

        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://kit.fontawesome.com/b31b4339ed.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>

    <script src="JS/menu.js"></script>
    <script src="JS/compras.js"></script>
    <script src="JS/ventas.js"></script>
    <script src="JS/productos.js"></script>
    <script src="JS/empleados.js"></script>
    <script src="JS/usuarios.js"></script>
    <script src="JS/clientes.js"></script>
    <script src="JS/proveedores.js"></script>
    <script src="JS/categorias.js"></script>
    <script src="JS/turnos.js"></script>
    <script src="JS/reportes.js"></script>


</body>

<footer>

</footer>

</html>
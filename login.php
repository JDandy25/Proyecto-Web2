
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="css/login.css" type="text/css">
      
</head>
<body>
    <div class="login-container">
        <div class="icon">
            <i class="fa-solid fa-user-lock"></i>
        </div>
        <h1>Iniciar Sesión</h1>
        <form id="FormLogin" autocomplete="off" method="post" action="/Proyecto-web2/C_Logica/logica_login.php">
            <div class="groupDatos">
                <label for="usuario">Usuario</label>
                <input type="text" id="usuario" name="usuario" required autofocus>
            </div>
            <div class="groupDatos">
                <label for="clave">Contraseña</label>
                <input type="password" id="clave" name="clave" required>
            </div>
            <button type="submit" class="btn" id="btnLogin">Ingresar</button>
        </form>
    </div>
    <script src="js/login.js">

    </script>



    </body>
</html>
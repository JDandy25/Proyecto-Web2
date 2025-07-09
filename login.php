<?php
session_start();

// Ajuste correcto para rutas si login.php está en la raíz
require_once __DIR__ . '/C_Datos/crud_usuarios.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'] ?? '';
    $clave = $_POST['clave'] ?? '';

    $crudUsuario = new UsuarioDAO();
    $datos = $crudUsuario->autenticar($usuario, $clave);

    if ($datos) {
        $_SESSION['usuario'] = $datos['usuario'];
        $_SESSION['rol'] = $crudUsuario->obtenerNombreRolPorId($datos['id_rol']);
        $_SESSION['id_rol'] = $datos['id_rol'];

        header("Location: index.php");
        exit;
    } else {
        $error = "Usuario o contraseña incorrectos";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="CSS/login.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h2><i class="fas fa-user-shield"></i> Acceso al Sistema</h2>
            <form method="POST" action="login.php">
                <div class="input-group">
                    <label><i class="fas fa-user"></i> Usuario</label>
                    <input type="text" name="usuario" required>
                </div>
                <div class="input-group">
                    <label><i class="fas fa-lock"></i> Contraseña</label>
                    <input type="password" name="clave" required>
                </div>
                <button type="submit" class="btn-login"><i class="fas fa-sign-in-alt"></i> Ingresar</button>
            </form>
            <?php if (isset($error)): ?>
                <div class="error-msg"><i class="fas fa-exclamation-triangle"></i> <?= $error ?></div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

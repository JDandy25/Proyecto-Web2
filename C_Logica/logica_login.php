<?php
session_start();
error_log("======= LOGIN INTENTADO =======");

require_once $_SERVER["DOCUMENT_ROOT"] . '/Proyecto-Web2/C_Datos/crud_usuarios.php';

// Antes de enviar los datos
$usuario = $_POST['usuario'] ?? '';
$contrasena = $_POST['clave'] ?? '';


error_log("Usuario enviado: $usuario");
error_log("Contraseña enviada: $contrasena");

$crudUsuario = new UsuarioDAO();
$result = $crudUsuario->autenticar($usuario, $contrasena);

if ($result) {
    // Guardar datos importantes en sesión
    $_SESSION['id_usuario'] = $result['id_usuario'];
    $_SESSION['usuario'] = $result['usuario'];
    $_SESSION['id_rol'] = $result['id_rol'];
    $_SESSION['estado'] = $result['estado'];
    // Redirigir a la página principal (ajusta la ruta si es necesario)
    header('Location: /Proyecto-Web2/index.php');
    exit;
} else {
    // Redirigir al login con error
    header('Location: /Proyecto-Web2/login.php');
    exit;
}

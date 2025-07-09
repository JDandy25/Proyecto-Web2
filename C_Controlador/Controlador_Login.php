<?php
session_start();
require_once "C_Controlador/Controlador_Login.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';

    if ($accion === 'registrar') {
        $usuario = $_POST['nombre_usuario'];
        $clave = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);
        $idRol = $_POST['id_rol'];
        $estado = $_POST['estado'];

        $db = Db::conectar();
        $stmt = $db->prepare("INSERT INTO usuario (usuario, clave, id_rol, estado, fechaCreado) VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute([$usuario, $clave, $idRol, $estado]);
        echo json_encode(['success' => true]);
        exit;
    }

    if ($accion === 'actualizar') {
        $id = $_POST['idActualizarUsuario'];
        $usuario = $_POST['nombre_actualizar'];
        $clave = password_hash($_POST['contrasena_actualizar'], PASSWORD_DEFAULT);
        $idRol = $_POST['rol_actualizar'];
        $estado = $_POST['estado_actualizar'];

        $db = Db::conectar();
        $stmt = $db->prepare("UPDATE usuario SET usuario = ?, clave = ?, id_rol = ?, estado = ? WHERE id_usuario = ?");
        $stmt->execute([$usuario, $clave, $idRol, $estado, $id]);
        echo json_encode(['success' => true]);
        exit;
    }
}
?>

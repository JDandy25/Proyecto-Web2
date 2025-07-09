<?php
require_once "C_Datos/crud_usuarios.php";
require_once "C_Entidad/class_usuarios.php";

$usuario = new Usuario();
$usuario->setUsuario("admin");
$usuario->setClave("123456"); // Texto plano
$usuario->setRol(1);          // ID de rol
$usuario->setEstado(1);

$dao = new UsuarioDAO();
$dao->insertar($usuario);

echo "Usuario de prueba creado con clave 123456";

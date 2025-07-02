<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/Proyecto-Web2/C_Datos/conexion.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/Proyecto-Web2/C_Entidad/class_usuarios.php'; // Asegúrate que esta ruta sea correcta para tu class_usuarios.php

class UsuarioDAO
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = Db::conectar();
    }

    public function insertar(Usuario $usuario)
    {
        // Hashear la clave antes de guardar
        $hashedClave = password_hash($usuario->getClave(), PASSWORD_DEFAULT);
        $sql = "INSERT INTO usuario (
                    usuario, clave, id_rol, estado
                ) 
                VALUES (
                    :usuario, :clave, :id_rol, :estado
                )";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindValue(':usuario', $usuario->getUsuario());
        $stmt->bindValue(':clave', $hashedClave);
        $stmt->bindValue(':id_rol', $usuario->getRol()); // Asume que getRol() devuelve el ID del rol
        $stmt->bindValue(':estado', $usuario->getEstado());
        return $stmt->execute();
    }

    public function obtenerPorId($id)
    {
        $sql = "SELECT * FROM usuario WHERE id_usuario = :id_usuario";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindValue(':id_usuario', $id);
        $stmt->execute();
        $result = $stmt->fetch();

        if ($result) {
            $usuario = new Usuario();
            $usuario->setId($result['id_usuario']);
            $usuario->setUsuario($result['usuario']);
            $usuario->setClave($result['clave']);
            $usuario->setRol($result['id_rol']); // Asume que la columna es id_rol
            $usuario->setEstado($result['estado']);
            return $usuario;
        } else {
            return null;
        }
    }

    public function obtenerTodos()
    {
        $sql = "SELECT 
                    u.id_usuario,
                    u.usuario,
                    r.nombre AS rol, 
                    u.estado
                FROM usuario u
                INNER JOIN rol r ON u.id_rol = r.id_rol
                WHERE u.estado = 1
                ORDER BY u.id_usuario ASC";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $usuarios = [];
        foreach ($results as $result) {
            $usuario = new Usuario();
            $usuario->setId($result['id_usuario']);
            $usuario->setUsuario($result['usuario']);
            $usuario->setRol($result['rol']); 
            $usuario->setEstado($result['estado']);
            $usuarios[] = $usuario;
        }

        return $usuarios;
    }

    public function actualizar(Usuario $usuario)
    {
        // Si la contraseña viene vacía, no la actualices
        if (empty($usuario->getClave())) {
            $sql = "UPDATE usuario 
                    SET usuario = :usuario,
                        id_rol = :id_rol,
                        estado = :estado
                    WHERE id_usuario = :id_usuario";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(':usuario', $usuario->getUsuario());
            $stmt->bindValue(':id_rol', $usuario->getRol());
            $stmt->bindValue(':estado', $usuario->getEstado());
            $stmt->bindValue(':id_usuario', $usuario->getId());
        } else {
            // Hashear la nueva clave antes de guardar
            $hashedClave = password_hash($usuario->getClave(), PASSWORD_DEFAULT);
            $sql = "UPDATE usuario 
                    SET usuario = :usuario,
                        clave = :clave,
                        id_rol = :id_rol,
                        estado = :estado
                    WHERE id_usuario = :id_usuario";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(':usuario', $usuario->getUsuario());
            $stmt->bindValue(':clave', $hashedClave);
            $stmt->bindValue(':id_rol', $usuario->getRol());
            $stmt->bindValue(':estado', $usuario->getEstado());
            $stmt->bindValue(':id_usuario', $usuario->getId());
        }
        return $stmt->execute();
    }

    public function eliminar($id)
    {
        // La eliminación física de usuarios a menudo se reemplaza por un cambio de estado
        $sql = "DELETE FROM usuario WHERE id_usuario = :id_usuario";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindValue(':id_usuario', $id);
        return $stmt->execute();
    }

    public function actualizarEstado($id, $estado)
    {
        $sql = "UPDATE usuario SET estado = :estado WHERE id_usuario = :id_usuario";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindValue(':estado', $estado);
        $stmt->bindValue(':id_usuario', $id);
        return $stmt->execute();
    }

    public function obtenerRoles()
    {
        $sql = "SELECT id_rol, nombre FROM rol WHERE estado = 1 ORDER BY nombre ASC";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function autenticar($usuario, $contrasena)
    {
        $sql = "SELECT * FROM usuario WHERE usuario = :usuario AND estado = 1";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindValue(':usuario', $usuario);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result && password_verify($contrasena, $result['clave'])) {
            // Contraseña correcta, retorna el usuario (puedes retornar el array o un objeto Usuario)
            return $result;
        } else {
            // Contraseña incorrecta o usuario no existe
            return false;
        }
    }

    public function obtenerNombreRolPorId($id_rol)
    {
        $sql = "SELECT nombre FROM rol WHERE id_rol = :id_rol LIMIT 1";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindValue(':id_rol', $id_rol);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result ? $result['nombre'] : null;
    }

    public function obtenerUltimoUsuarioYRol()
    {
        $sql = "SELECT u.id_usuario, r.nombre as nombre_rol
                FROM usuario u
                INNER JOIN rol r ON u.id_rol = r.id_rol
                ORDER BY u.id_usuario DESC LIMIT 1";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result : null;
    }

}
<?php

class Db {
    private static $conexion = null;

    private function __construct() {}

    public static function conectar() {
        if (self::$conexion === null) {
            try {
                $pdo_options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ];
                self::$conexion = new PDO("mysql:host=localhost;dbname=proyecto-web2;charset=utf8mb4", "root", "", $pdo_options);
            } catch (PDOException $e) {
                die("Error de conexión: " . $e->getMessage());
            }
        }
        return self::$conexion;
    }
}
?>

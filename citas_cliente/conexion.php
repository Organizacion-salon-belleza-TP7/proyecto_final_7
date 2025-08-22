<?php
class Conexion {
    public static function conectar() {
        $host = "localhost";
        $user = "root";
        $pass = "";
        $db   = "trabajo_final_7"; // tu base de datos

        $conexion = new mysqli($host, $user, $pass, $db);
        if ($conexion->connect_error) {
            die("Error de conexión: " . $conexion->connect_error);
        }
        return $conexion;
    }
}

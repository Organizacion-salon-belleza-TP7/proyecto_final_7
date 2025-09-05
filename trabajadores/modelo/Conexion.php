<?php
class Conexion {
    private static $conexion;

    public static function getConexion() {
        if (!isset(self::$conexion)) {
            self::$conexion = new mysqli("localhost", "root", "", "trabajo_final_7");
            if (self::$conexion->connect_error) {
                die("Error de conexión: " . self::$conexion->connect_error);
            }
            self::$conexion->set_charset("utf8");
        }
        return self::$conexion;
    }
}
?>

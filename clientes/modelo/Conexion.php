<?php
class Conexion {
    private static $host = "localhost";
    private static $usuario = "root";
    private static $clave = "";
    private static $bd = "trabajo_final_7"; // tu base de datos

    public static function conectar() {
        $conn = new mysqli(self::$host, self::$usuario, self::$clave, self::$bd);
        if ($conn->connect_error) {
            die("Error de conexión: " . $conn->connect_error);
        }
        return $conn;
    }
}
?>

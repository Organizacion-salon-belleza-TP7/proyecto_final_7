<?php
require_once("conexion.php");

class TrabajadorModelo {
    public static function obtenerTrabajadores() {
        global $conn;
        $sql = "SELECT id_trabajador, nombre_trabajador, dni, id_tipo_trabajador, id_nivel_profesionalismo, activo FROM trabajadores";
        $resultado = $conn->query($sql);
        return $resultado;
    }
}

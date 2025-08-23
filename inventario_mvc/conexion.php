<?php
class Conexion {
    public static function conectar() {
        return new mysqli("localhost", "root", "", "trabajo_final_7");
    }
}

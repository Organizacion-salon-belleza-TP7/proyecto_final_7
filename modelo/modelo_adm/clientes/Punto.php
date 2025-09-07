<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');

class Punto {
    public static function obtenerPorCliente($conn, int $idCliente): ?array {
        $sql = "SELECT * FROM puntos_descuentos WHERE id_cliente = $idCliente";
        $resultado = $conn->query($sql);
        return $resultado->fetch_assoc() ?: null;
    }
}
?>
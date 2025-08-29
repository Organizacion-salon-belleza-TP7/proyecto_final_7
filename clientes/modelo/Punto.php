<?php
require_once __DIR__ . '/Conexion.php';

class Punto {
    public static function obtenerPorCliente(int $idCliente): ?array {
        $cn = Conexion::getConexion();
        $st = $cn->prepare("SELECT * FROM puntos_descuentos WHERE id_cliente = ?");
        $st->execute([$idCliente]);
        $row = $st->fetch();
        return $row ?: null;
    }
}

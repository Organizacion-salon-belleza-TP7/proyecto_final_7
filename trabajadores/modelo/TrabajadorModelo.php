<?php
require_once("conexion.php");

class TrabajadorModelo {
    private $conn;

    public function __construct($conexion) {
        $this->conn = $conexion;
    }

    public function obtenerTrabajadores($buscar = "") {
        $trabajadores = [];

        if (!empty($buscar)) {
            $stmt = $this->conn->prepare("SELECT id_trabajador, nombre_trabajador, dni, id_tipo_trabajador, id_nivel_profesionalismo, activo FROM trabajadores WHERE nombre_trabajador LIKE ?");
            $param = "%$buscar%";
            $stmt->bind_param("s", $param);
            $stmt->execute();
            $resultado = $stmt->get_result();
        } else {
            $resultado = $this->conn->query("SELECT id_trabajador, nombre_trabajador, dni, id_tipo_trabajador, id_nivel_profesionalismo, activo FROM trabajadores");
        }

        if ($resultado && $resultado->num_rows > 0) {
            while ($fila = $resultado->fetch_assoc()) {
                $trabajadores[] = $fila;
            }
        }

        return $trabajadores;

    }public function obtenerTrabajadores($buscar = "") {
    $trabajadores = [];

    if (!empty($buscar)) {
        $stmt = $this->conn->prepare("
            SELECT t.id_trabajador, t.nombre_trabajador, t.dni, 
                   tt.tipo_trabajador, t.id_nivel_profesionalismo, t.activo
            FROM trabajadores t
            JOIN tipo_trabajador tt ON t.id_tipo_trabajador = tt.id_tipo_trabajador
            WHERE t.nombre_trabajador LIKE ?
        ");
        $param = "%$buscar%";
        $stmt->bind_param("s", $param);
        $stmt->execute();
        $resultado = $stmt->get_result();
    } else {
        $resultado = $this->conn->query("
            SELECT t.id_trabajador, t.nombre_trabajador, t.dni, 
                   tt.tipo_trabajador, t.id_nivel_profesionalismo, t.activo
            FROM trabajadores t
            JOIN tipo_trabajador tt ON t.id_tipo_trabajador = tt.id_tipo_trabajador
        ");
    }

    if ($resultado && $resultado->num_rows > 0) {
        while ($fila = $resultado->fetch_assoc()) {
            $trabajadores[] = $fila;
        }
    }

    return $trabajadores;
}

}


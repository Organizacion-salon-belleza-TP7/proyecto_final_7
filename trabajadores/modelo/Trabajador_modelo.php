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
            $stmt = $this->conn->prepare("
                SELECT t.id_trabajador, t.nombre, t.apellido, t.dni, 
                       t.id_tipo_trabajador, tt.tipo_trabajador,
                       t.id_nivel_profesionalismo, np.nivel_profesionalismo,
                       t.activo
                FROM trabajadores t
                JOIN tipo_trabajador tt ON t.id_tipo_trabajador = tt.id_tipo_trabajador
                JOIN nivel_profesionalismo np ON t.id_nivel_profesionalismo = np.id_nivel_profesionalismo
                WHERE t.nombre LIKE ? OR t.apellido LIKE ?
            ");
            $param = "%$buscar%";
            $stmt->bind_param("ss", $param, $param);
            $stmt->execute();
            $resultado = $stmt->get_result();
        } else {
            $resultado = $this->conn->query("
                SELECT t.id_trabajador, t.nombre, t.apellido, t.dni, 
                       t.id_tipo_trabajador, tt.tipo_trabajador,
                       t.id_nivel_profesionalismo, np.nivel_profesionalismo,
                       t.activo
                FROM trabajadores t
                JOIN tipo_trabajador tt ON t.id_tipo_trabajador = tt.id_tipo_trabajador
                JOIN nivel_profesionalismo np ON t.id_nivel_profesionalismo = np.id_nivel_profesionalismo
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

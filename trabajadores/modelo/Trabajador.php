<?php
require_once "Conexion.php";

class Trabajador {
    private $conexion;

    public function __construct() {
        $this->conexion = Conexion::getConexion();
    }

    public function obtenerTodos() {
        $resultado = $this->conexion->query("SELECT * FROM trabajadores");
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerPorId($id) {
        $stmt = $this->conexion->prepare("SELECT * FROM trabajadores WHERE id_trabajador=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->fetch_assoc();
    }

    public function agregar($datos) {
        $stmt = $this->conexion->prepare("INSERT INTO trabajadores (nombre_trabajador, apellido_trabajador, dni, id_tipo_trabajador, id_nivel_profesionalismo, activo) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param(
            "ssiiii",
            $datos['nombre_trabajador'],
            $datos['apellido_trabajador'],
            $datos['dni'],
            $datos['id_tipo_trabajador'],
            $datos['id_nivel_profesionalismo'],
            $datos['activo']
        );
        return $stmt->execute();
    }

    public function actualizar($id, $datos) {
        $stmt = $this->conexion->prepare("UPDATE trabajadores SET nombre_trabajador=?, apellido_trabajador=?, dni=?, id_tipo_trabajador=?, id_nivel_profesionalismo=?, activo=? WHERE id_trabajador=?");
        $stmt->bind_param(
            "ssiiiii",
            $datos['nombre_trabajador'],
            $datos['apellido_trabajador'],
            $datos['dni'],
            $datos['id_tipo_trabajador'],
            $datos['id_nivel_profesionalismo'],
            $datos['activo'],
            $id
        );
        return $stmt->execute();
    }

    public function eliminar($id) {
        $stmt = $this->conexion->prepare("DELETE FROM trabajadores WHERE id_trabajador=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');


class Trabajador {
   private $conn;

    public function __construct($conn){
        $this->conn = $conn;

    }

    public function obtenerTodos() {
        $resultado = $this->conn->query("SELECT * FROM trabajadores");
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerPorId($id) {
        $stmt = $this->conn->prepare("SELECT * FROM trabajadores WHERE id_trabajador=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->fetch_assoc();
    }

    public function agregar($datos) {
        $stmt = $this->conn->prepare("INSERT INTO trabajadores (nombre_trabajador, apellido_trabajador, dni, id_tipo_trabajador, id_nivel_profesionalismo, activo) VALUES (?, ?, ?, ?, ?, ?)");
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
        $stmt = $this->conn->prepare("UPDATE trabajadores SET nombre_trabajador=?, apellido_trabajador=?, dni=?, id_tipo_trabajador=?, id_nivel_profesionalismo=?, activo=? WHERE id_trabajador=?");
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
        $stmt = $this->conn->prepare("DELETE FROM trabajadores WHERE id_trabajador=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>

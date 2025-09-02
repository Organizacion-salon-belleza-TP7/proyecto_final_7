<?php
class ModeloProveedor {
    private $conexion;

    public function __construct() {
        $this->conexion = new mysqli("localhost", "root", "", "trabajo_final_7");
        if ($this->conexion->connect_error) {
            die("Error de conexión: " . $this->conexion->connect_error);
        }
    }

    public function obtenerProveedores() {
        $sql = "SELECT * FROM proveedores";
        $resultado = $this->conexion->query($sql);
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    public function agregarProveedor($nombre, $apellido, $dni) {
        $stmt = $this->conexion->prepare(
            "INSERT INTO proveedores (nombre_proveedor, apellido_proveedor, dni) VALUES (?, ?, ?)"
        );
        $stmt->bind_param("sss", $nombre, $apellido, $dni);
        return $stmt->execute();
    }

    public function eliminarProveedor($id) {
        $stmt = $this->conexion->prepare("DELETE FROM proveedores WHERE id_proveedor = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>

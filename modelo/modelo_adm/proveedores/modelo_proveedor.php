<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');

class ModeloProveedor {
    private $conn;

    public function __construct($conn){
        $this->conn = $conn;

    }

    public function obtenerProveedores() {
        $sql = "SELECT * FROM proveedores";
        $resultado = $this->conn->query($sql);
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    public function agregarProveedor($nombre, $apellido, $dni) {
        $stmt = $this->conn->prepare(
            "INSERT INTO proveedores (nombre_proveedor, apellido_proveedor, dni) VALUES (?, ?, ?)"
        );
        $stmt->bind_param("sss", $nombre, $apellido, $dni);
        return $stmt->execute();
    }

    public function eliminarProveedor($id) {
        $stmt = $this->conn->prepare("DELETE FROM proveedores WHERE id_proveedor = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>

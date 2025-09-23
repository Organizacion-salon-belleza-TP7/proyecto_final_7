<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../config/db.php');

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

    
}
?>

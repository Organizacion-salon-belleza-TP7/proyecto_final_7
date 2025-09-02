<?php
require_once __DIR__ . "/../../variable_global.php";

class Lugar {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    public function agregarLugar($nombre, $coordenadas, $imagen, $activo) {
        $stmt = $this->conn->prepare("INSERT INTO lugares (nombre_lugar, cooordenadas, imagen_lugar, activo) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $nombre, $coordenadas, $imagen, $activo);
        return $stmt->execute();
    }

    public function obtenerLugares() {
        return $this->conn->query("SELECT * FROM lugares");
    }

    public function eliminarLugar($id) {
        $stmt = $this->conn->prepare("DELETE FROM lugares WHERE id_lugar = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>



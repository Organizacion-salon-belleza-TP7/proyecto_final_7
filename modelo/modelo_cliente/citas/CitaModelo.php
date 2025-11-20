<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../variable_global.php');

require_once(ROOT_PATH . '/modelo/BD.php');


class CitaModelo {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function obtenerServicios() {
        $sql = "SELECT id_servicios AS id, nombre, precio_servicio FROM servicios WHERE activo=1";
        $resultado = $this->conn->query($sql);
        return $resultado ? $resultado->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function obtenerServiciosPorId($id) {
        $stmt = $this->conn->prepare("SELECT nombre, precio_servicio FROM servicios WHERE id_servicios=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->fetch_assoc() ?? [];
    }

    public function obtenerCombos() {
        $sql = "SELECT id_combos AS id, nombre, precio FROM combos WHERE activo=1";
        $resultado = $this->conn->query($sql);
        return $resultado ? $resultado->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function obtenerCombosPorId($id) {
        $stmt = $this->conn->prepare("SELECT nombre, precio FROM combos WHERE id_combos=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->fetch_assoc() ?? [];
    }

    public function obtenerLugares() {
        $sql = "SELECT id_lugar, nombre_lugar FROM lugares";
        $resultado = $this->conn->query($sql);
        return $resultado ? $resultado->fetch_all(MYSQLI_ASSOC) : [];
    }

   public function guardarCitaConLugar($id_cliente, $fecha_cita, $id_lugar, $servicios = [], $combos = []) {
    $activo = 1;
    $fecha_cita = date('Y-m-d H:i:s', strtotime($fecha_cita));

    // CORREGIDO: AHORA SON 4 ? Y 4 COLUMNAS
    $stmt = $this->conn->prepare(
        "INSERT INTO citas (id_cliente, fecha_cita, activo, id_lugar) 
         VALUES (?, ?, ?, ?)"
    );
    
    if (!$stmt) {
        error_log("Error prepare citas: " . $this->conn->error);
        return false;
    }

    // CORREGIDO: "isii" → i (int), s (string), i (int), i (int)
    $stmt->bind_param("isii", $id_cliente, $fecha_cita, $activo, $id_lugar);
    
    if (!$stmt->execute()) {
        error_log("Error execute citas: " . $stmt->error);
        return false;
    }

    $id_cita = $this->conn->insert_id;
    $stmt->close();

    // Insertar servicios
    if (!empty($servicios)) {
        $stmtS = $this->conn->prepare("INSERT INTO detalle_cita (id_cita, id_servicios) VALUES (?, ?)");
        if (!$stmtS) {
            error_log("Error prepare detalle_servicios: " . $this->conn->error);
            return $id_cita;
        }
        foreach ($servicios as $s) {
            $stmtS->bind_param("ii", $id_cita, $s);
            $stmtS->execute();
        }
        $stmtS->close();
    }

    // Insertar combos
    if (!empty($combos)) {
        $stmtC = $this->conn->prepare("INSERT INTO detalle_cita (id_cita, id_combos) VALUES (?, ?)");
        if (!$stmtC) {
            error_log("Error prepare detalle_combos: " . $this->conn->error);
            return $id_cita;
        }
        foreach ($combos as $c) {
            $stmtC->bind_param("ii", $id_cita, $c);
            $stmtC->execute();
        }
        $stmtC->close();
    }

    return $id_cita;
}
}

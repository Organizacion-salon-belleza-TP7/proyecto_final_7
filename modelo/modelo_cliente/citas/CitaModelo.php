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
        $sql = "SELECT id_servicios AS id, nombre, precio FROM servicios WHERE activo=1";
        $resultado = $this->conn->query($sql);
        return $resultado ? $resultado->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function obtenerServiciosPorId($id) {
        $stmt = $this->conn->prepare("SELECT nombre, precio FROM servicios WHERE id_servicios=?");
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
        $hash = md5(uniqid());
        $activo = 1;
        $fecha_cita = date('Y-m-d H:i:s', strtotime($fecha_cita));

        $stmt = $this->conn->prepare(
            "INSERT INTO citas (id_cliente, fecha_cita, activo, hash_identificacion, id_lugar)
            VALUES (?, ?, ?, ?, ?)"
        );
        if (!$stmt) die("Error prepare: ".$this->conn->error);

        $stmt->bind_param("isisi", $id_cliente, $fecha_cita, $activo, $hash, $id_lugar);
        $stmt->execute();
        $id_cita = $this->conn->insert_id;

        foreach ($servicios as $s) {
            $stmtS = $this->conn->prepare("INSERT INTO detalle_cita (id_cita, id_servicios) VALUES (?, ?)");
            if (!$stmtS) die("Error detalle_servicios: ".$this->conn->error);
            $stmtS->bind_param("ii", $id_cita, $s);
            $stmtS->execute();
        }

        foreach ($combos as $c) {
            $stmtC = $this->conn->prepare("INSERT INTO detalle_cita (id_cita, id_combos) VALUES (?, ?)");
            if (!$stmtC) die("Error detalle_combos: ".$this->conn->error);
            $stmtC->bind_param("ii", $id_cita, $c);
            $stmtC->execute();
        }

        return $id_cita;
    }
}

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("conexion.php");


class CitaModelo {
    private $conexion;

    public function __construct() {
        $this->conexion = Conexion::conectar();
    }

    public function obtenerServicios() {
        $sql = "SELECT id_servicios AS id, nombre, precio FROM servicios WHERE activo=1";
        $resultado = $this->conexion->query($sql);
        return $resultado ? $resultado->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function obtenerServiciosPorId($id) {
        $stmt = $this->conexion->prepare("SELECT nombre, precio FROM servicios WHERE id_servicios=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->fetch_assoc() ?? [];
    }

    public function obtenerCombos() {
        $sql = "SELECT id_combos AS id, nombre, precio FROM combos WHERE activo=1";
        $resultado = $this->conexion->query($sql);
        return $resultado ? $resultado->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function obtenerCombosPorId($id) {
        $stmt = $this->conexion->prepare("SELECT nombre, precio FROM combos WHERE id_combos=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->fetch_assoc() ?? [];
    }

    public function obtenerLugares() {
        $sql = "SELECT id_lugar, nombre_lugar FROM lugares";
        $resultado = $this->conexion->query($sql);
        return $resultado ? $resultado->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function guardarCitaConLugar($id_cliente, $fecha_cita, $id_lugar, $servicios = [], $combos = []) {
        $hash = md5(uniqid());
        $activo = 1;
        $fecha_cita = date('Y-m-d H:i:s', strtotime($fecha_cita));

        $stmt = $this->conexion->prepare(
            "INSERT INTO citas (id_cliente, fecha_cita, activo, hash_identificacion, id_lugar)
            VALUES (?, ?, ?, ?, ?)"
        );
        if (!$stmt) die("Error prepare: ".$this->conexion->error);

        $stmt->bind_param("isisi", $id_cliente, $fecha_cita, $activo, $hash, $id_lugar);
        $stmt->execute();
        $id_cita = $this->conexion->insert_id;

        foreach ($servicios as $s) {
            $stmtS = $this->conexion->prepare("INSERT INTO detalle_cita (id_cita, id_servicios) VALUES (?, ?)");
            if (!$stmtS) die("Error detalle_servicios: ".$this->conexion->error);
            $stmtS->bind_param("ii", $id_cita, $s);
            $stmtS->execute();
        }

        foreach ($combos as $c) {
            $stmtC = $this->conexion->prepare("INSERT INTO detalle_cita (id_cita, id_combos) VALUES (?, ?)");
            if (!$stmtC) die("Error detalle_combos: ".$this->conexion->error);
            $stmtC->bind_param("ii", $id_cita, $c);
            $stmtC->execute();
        }

        return $id_cita;
    }
}

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../config/db.php');

class CitaModeloApi {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // ✅ Obtener servicios activos
    public function obtenerServicios() {
        $sql = "SELECT id_servicios AS id, nombre, precio_servicio FROM servicios WHERE activo = 1";
        $result = $this->conn->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    // ✅ Obtener combos activos
    public function obtenerCombos() {
        $sql = "SELECT id_combos AS id, nombre, precio FROM combos WHERE activo = 1";
        $result = $this->conn->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    // ✅ Obtener lugares
    public function obtenerLugares() {
        $sql = "SELECT id_lugar, nombre_lugar FROM lugares";
        $result = $this->conn->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    // ✅ Guardar una cita
    public function guardarCita($id_cliente, $fecha_cita, $id_lugar, $servicios = [], $combos = []) {
        $hash = md5(uniqid('', true));
        $activo = 1;
        $fecha_cita = date('Y-m-d H:i:s', strtotime($fecha_cita));

        // Insertar cita
        $stmt = $this->conn->prepare("
            INSERT INTO citas (id_cliente, fecha_cita, activo, hash_identificacion, id_lugar)
            VALUES (?, ?, ?, ?, ?)
        ");
        if (!$stmt) {
            return ['error' => 'Error al preparar la consulta de cita: ' . $this->conn->error];
        }

        $stmt->bind_param("isisi", $id_cliente, $fecha_cita, $activo, $hash, $id_lugar);
        if (!$stmt->execute()) {
            return ['error' => 'Error al guardar la cita: ' . $stmt->error];
        }

        $id_cita = $this->conn->insert_id;

        // Insertar servicios seleccionados
        foreach ($servicios as $s) {
            $stmtS = $this->conn->prepare("INSERT INTO detalle_cita (id_cita, id_servicios) VALUES (?, ?)");
            if ($stmtS) {
                $stmtS->bind_param("ii", $id_cita, $s);
                $stmtS->execute();
            }
        }

        // Insertar combos seleccionados
        foreach ($combos as $c) {
            $stmtC = $this->conn->prepare("INSERT INTO detalle_cita (id_cita, id_combos) VALUES (?, ?)");
            if ($stmtC) {
                $stmtC->bind_param("ii", $id_cita, $c);
                $stmtC->execute();
            }
        }

        return ['id_cita' => $id_cita, 'hash' => $hash];
    }

    // ✅ Obtener los detalles de una cita por ID
    public function obtenerCitaPorId($id_cita) {
        $stmt = $this->conn->prepare("
            SELECT c.id_cita, c.fecha_cita, l.nombre_lugar, c.activo 
            FROM citas c
            LEFT JOIN lugares l ON c.id_lugar = l.id_lugar
            WHERE c.id_cita = ?
        ");
        $stmt->bind_param("i", $id_cita);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc() ?? [];
    }

    // ✅ Obtener los servicios/combos asociados a una cita
    public function obtenerDetallesCita($id_cita) {
        $sql = "
            SELECT s.nombre AS nombre, s.precio_servicio AS precio, 'servicio' AS tipo
            FROM detalle_cita d
            INNER JOIN servicios s ON d.id_servicios = s.id_servicios
            WHERE d.id_cita = ?
            UNION
            SELECT c.nombre AS nombre, c.precio AS precio, 'combo' AS tipo
            FROM detalle_cita d
            INNER JOIN combos c ON d.id_combos = c.id_combos
            WHERE d.id_cita = ?
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $id_cita, $id_cita);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->fetch_all(MYSQLI_ASSOC);
    }
}

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../config/db.php');

class CitasModelo {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function listar() {
        $sql = "
        SELECT 
            c.id_cita,
            cli.nombre AS nombre_cliente,
            c.fecha_cita,
            c.activo,
            GROUP_CONCAT(DISTINCT s.nombre SEPARATOR ', ') AS servicios,
            GROUP_CONCAT(DISTINCT co.nombre SEPARATOR ', ') AS combos
        FROM citas c
        LEFT JOIN clientes cli ON c.id_cliente = cli.id_cliente
        LEFT JOIN detalle_cita dc ON c.id_cita = dc.id_cita
        LEFT JOIN servicios s ON dc.id_servicios = s.id_servicios
        LEFT JOIN combos co ON dc.id_combos = co.id_combos
        GROUP BY c.id_cita, cli.nombre, c.fecha_cita, c.activo
        ORDER BY c.fecha_cita DESC
        ";

        $resultado = $this->conn->query($sql);

        if (!$resultado) {
            die("Error en la consulta SQL: " . $this->conn->error);
        }

        $citas = [];
        while ($row = $resultado->fetch_assoc()) {
            $citas[] = $row;
        }

        return $citas;
    }

    public function obtenerDetalle($id_cita) {
        $sql = "
        SELECT 
            c.id_cita,
            cli.nombre AS nombre_cliente,
            c.fecha_cita,
            c.activo,
            c.hash_identificacion,
            l.nombre_lugar AS lugar,
            s.id_servicios,
            s.nombre AS servicio_nombre,
            s.precio_servicio AS servicio_precio,
            co.id_combos,
            co.nombre AS combo_nombre,
            co.precio AS combo_precio
        FROM citas c
        LEFT JOIN clientes cli ON c.id_cliente = cli.id_cliente
        LEFT JOIN lugares l ON c.id_lugar = l.id_lugar
        LEFT JOIN detalle_cita dc ON c.id_cita = dc.id_cita
        LEFT JOIN servicios s ON dc.id_servicios = s.id_servicios
        LEFT JOIN combos co ON dc.id_combos = co.id_combos
        WHERE c.id_cita = ?
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id_cita);
        $stmt->execute();
        $resultado = $stmt->get_result();

        $detalle = [];
        while ($row = $resultado->fetch_assoc()) {
            $detalle[] = $row;
        }

        return $detalle;
    }
}
?>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');


class CitasModelo {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function listar() {
$sql = "
SELECT 
    c.id_cita,
    cli.nombre,
    s.nombre AS nombre,
    co.nombre AS nombre_combo,
    c.fecha_cita,
    c.activo
FROM citas c
LEFT JOIN clientes cli ON c.id_cliente = cli.id_cliente
LEFT JOIN servicios s ON s.id_servicios = c.id_servicio_combos_select
LEFT JOIN combos co ON co.id_combos = c.id_servicio_combos_select
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
}

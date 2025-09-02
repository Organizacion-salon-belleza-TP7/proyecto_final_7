<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');

class Servicio {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Solo los servicios contratados por el cliente
    public function obtenerPorCliente($id_cliente) {
        $sql = "SELECT DISTINCT s.* 
                FROM servicios s
                JOIN servicios_combos_select scs ON s.id_servicios = scs.id_servicio
                JOIN citas c ON scs.id_servicio = c.id_servicio_combos_select
                WHERE c.id_cliente = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id_cliente);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
?>

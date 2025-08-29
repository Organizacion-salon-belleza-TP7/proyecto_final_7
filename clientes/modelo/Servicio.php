<?php
require_once 'Conexion.php';

class Servicio {
    private $conexion;

    public function __construct() {
        $this->conexion = Conexion::conectar();
    }

    // Solo los servicios contratados por el cliente
    public function obtenerPorCliente($id_cliente) {
        $sql = "SELECT DISTINCT s.* 
                FROM servicios s
                JOIN servicios_combos_select scs ON s.id_servicios = scs.id_servicio
                JOIN citas c ON scs.id_servicio = c.id_servicio_combos_select
                WHERE c.id_cliente = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id_cliente);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
?>

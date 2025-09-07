<?php
class Cliente {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Obtener todos los clientes
    public function obtenerTodos() {
        $sql = "SELECT * FROM clientes";
        $resultado = $this->conn->query($sql);
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    // Obtener detalle de un cliente (con puntos y servicios)
    public function obtenerDetalle($id) {
        $datos = [];

        // Datos básicos
        $sql = "SELECT * FROM clientes WHERE id_cliente = $id";
        $datos['cliente'] = $this->conn->query($sql)->fetch_assoc();

        // Puntos
        $sql = "SELECT puntos_acumulados, descuento 
                FROM puntos_descuentos 
                WHERE id_cliente = $id";
        $datos['puntos'] = $this->conn->query($sql)->fetch_assoc();

        // Servicios contratados
        $sql = "SELECT s.nombre AS servicio, s.descripcion, c.fecha_cita
                FROM citas c
                INNER JOIN detalle_cita dc ON c.id_cita = dc.id_cita
                INNER JOIN servicios s ON dc.id_servicios = s.id_servicios
                WHERE c.id_cliente = $id";
        $datos['servicios'] = $this->conn->query($sql)->fetch_all(MYSQLI_ASSOC);

        return $datos;
    }
}
?>
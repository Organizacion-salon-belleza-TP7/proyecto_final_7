<?php
class Cliente {
    private $conexion;

    public function __construct() {
        $this->conexion = new mysqli("localhost", "root", "", "trabajo_final_7");
        if ($this->conexion->connect_error) {
            die("Error de conexión: " . $this->conexion->connect_error);
        }
    }

    // Obtener todos los clientes
    public function obtenerTodos() {
        $sql = "SELECT * FROM clientes";
        $resultado = $this->conexion->query($sql);
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    // Obtener detalle de un cliente (con puntos y servicios)
    public function obtenerDetalle($id) {
        $datos = [];

        // Datos básicos
        $sql = "SELECT * FROM clientes WHERE id_cliente = $id";
        $datos['cliente'] = $this->conexion->query($sql)->fetch_assoc();

        // Puntos
        $sql = "SELECT puntos_acumulados, descuento 
                FROM puntos_descuentos 
                WHERE id_cliente = $id";
        $datos['puntos'] = $this->conexion->query($sql)->fetch_assoc();

        // Servicios contratados (citas + detalle_cita)
        $sql = "SELECT s.nombre AS servicio, s.descripcion, c.fecha_cita
                FROM citas c
                INNER JOIN detalle_cita dc ON c.id_cita = dc.id_cita
                INNER JOIN servicios s ON dc.id_servicios = s.id_servicios
                WHERE c.id_cliente = $id";
        $datos['servicios'] = $this->conexion->query($sql)->fetch_all(MYSQLI_ASSOC);

        return $datos;
    }
}
?>

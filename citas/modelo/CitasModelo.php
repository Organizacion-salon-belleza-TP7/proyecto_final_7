<?php
require_once("conexion.php");

class CitasModelo {
    private $conexion;

    public function __construct() {
        $this->conexion = Conexion::conectar();
        if (!$this->conexion) {
            die("No se pudo conectar a la base de datos");
        }
    }

    public function listar() {
$sql = "
SELECT 
    c.id_cita,
    cli.nombres,
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



        $resultado = $this->conexion->query($sql);

        if (!$resultado) {
            die("Error en la consulta SQL: " . $this->conexion->error);
        }

        $citas = [];
        while ($row = $resultado->fetch_assoc()) {
            $citas[] = $row;
        }

        return $citas;
    }
}

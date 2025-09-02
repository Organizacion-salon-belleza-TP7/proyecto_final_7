<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');

class pagina_landing{
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function traer_servicios(){
        $traer_servicios = "SELECT id_servicios, nombre, descripcion, duracion, id_tiempo_servicio, precio, activo, id_tipo_servicio, imagen 
        FROM servicios WHERE 1
        LIMIT 6";

        $resultado = $this->conn->query($traer_servicios);

        return $resultado;

    }

    public function traer_inventario(){
        $traer_inventario = "SELECT id_inventario, nombre_producto, imagen_producto FROM inventario WHERE 1
        LIMIT 6";

        $resultado_inventario = $this->conn->query($traer_inventario);

        return $resultado_inventario;
    }

    public function traer_lugares(){
        $traer_lugares = "SELECT id_lugar, nombre_lugar, cooordenadas, imagen_lugar, activo FROM lugares WHERE 1
        LIMIT 2";

        $resultado_traer_lugares = $this->conn->query($traer_lugares);

        return $resultado_traer_lugares;
    }


}

?>
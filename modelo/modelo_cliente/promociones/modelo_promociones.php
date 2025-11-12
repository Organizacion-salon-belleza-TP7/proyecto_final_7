<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../variable_global.php');

require_once(ROOT_PATH . '/modelo/BD.php');

class promociones_cli{
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function traer_promociones(){
        $traer_promos = "SELECT 
        prom.id_promocion, 
        comb.nombre AS nombre_combo, 
        serv.nombre AS nombre_servicio, 
        prom.dias_promocion, 
        prom.descuento, 
        prom.puntos,
        prom.activo 
        FROM promociones prom
        LEFT JOIN combos comb ON prom.id_combos = comb.id_combos
        LEFT JOIN servicios serv ON serv.id_servicios = prom.id_servicios
        WHERE prom.activo = 1";
        
        $resultado_traer_promos = $this->conn->query($traer_promos);

        return $resultado_traer_promos;
    }


}

?>
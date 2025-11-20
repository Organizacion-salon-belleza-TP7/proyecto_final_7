<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');

class promociones_cliente{
    private $conn;

    public function __construct($conn){
        $this->conn = $conn;

    }

    public function traer_servicios_combos_promocionados(){
        $traer_promociones = "SELECT 
        prom.id_promocion, 
        comb.nombre AS nombre_combo, 
        serv.nombre AS nombre_servicio, 
        prom.dias_promocion, 
        prom.descuento, 
        prom.puntos 
        FROM promociones prom
        LEFT JOIN combos comb ON prom.id_combos = comb.id_combos
        LEFT JOIN servicios serv ON serv.id_servicios = prom.id_servicios
        WHERE prom.activo = 1
        ";

        $resultado_traer_promociones = $this->conn->query($traer_promociones);

        return $resultado_traer_promociones;
    }

    public function traer_servicios_combos_promocionados_carrito($id_promo_carrito){
        $sql = "SELECT 
        prom.id_promocion,
        comb.nombre AS nombre_combo,
        serv.nombre AS nombre_servicio,
        prom.dias_promocion,
        prom.descuento,
        prom.puntos
        FROM promociones prom
        LEFT JOIN combos comb ON prom.id_combos = comb.id_combos
        LEFT JOIN servicios serv ON serv.id_servicios = prom.id_servicios
        WHERE prom.id_promocion IN ($id_promo_carrito)";

        $resultado = $this->conn->query($sql);

        return $resultado;

    }

    public function traer_metodos_pagos(){
        $traer_metodos_pagos = "SELECT id_metodo_pago, metodo_pago, incremento, decremento, activo FROM metodos_pagos";
        $resultado_metods_pagos = $this->conn->query($traer_metodos_pagos);

        return $resultado_metods_pagos;

    }

    public function comprar_promocion($id_promocion,$lugar,$metodo_pago){
        $insertar_cita = $this->conn->prepare("");
        
    }

    

}

?>
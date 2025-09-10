<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/modelo_cliente/modelo_venta/modelo_venta.php');
require_once(ROOT_PATH . '/modelo/BD.php');
date_default_timezone_set('America/Argentina/Buenos_Aires');

$modelo_venta = new venta($conn);


if(isset($_POST['traer_id_cita']) && $_POST['traer_id_cita'] === 'confirmar_cita'){
    $id_cita = $_POST['id_cita'];

    header("Location: " . BASE_URL . "/vista/vista_cliente/vista_venta/venta.php?id=$id_cita");
    exit;

}elseif(isset($_POST['vista_pagar']) && $_POST['vista_pagar'] === 'venta.php'){
    $id_cita = $_POST['id_cita'];
    $fecha_actual = date('Y-m-d H:i:s');

    $funcion_traer_precio_cita = $modelo_venta->traer_datos_cita($id_cita);

    $precio_cita = intval($funcion_traer_precio_cita);

    $funcion_insertar_caja = $modelo_venta->insertar_caja($id_cita,$fecha_actual,$precio_cita);

    $id_caja_insertada = intval($funcion_insertar_caja);

    $metodos = $_POST['metodos'] ?? [];

    foreach ($metodos as $m) {
        $id_metodo = intval($m['id_metodo'] ?? 0);
        $cantidad_pagar = floatval($m['cantidad'] ?? 0);

        if ($id_metodo > 0 && $cantidad_pagar > 0) {
            $funcion_insertar_varios_metodos_pagos = $modelo_venta->insertar_varios_metodos_pagos($id_caja_insertada, $id_metodo, $cantidad_pagar);

            if($funcion_insertar_varios_metodos_pagos == true){
                $traer_detalle_cita = $modelo_venta->traer_datos_detalle_cita($id_cita);

                foreach($traer_detalle_cita as $dc){
                    $id_servicio = !empty($dc['id_servicios']) ? intval($dc['id_servicios']) : null;
                    $id_combo = !empty($dc['id_combos']) ? intval($dc['id_combos']) : null;

                    $cantidad = 1;

                    $precio_unitario = !empty($dc['precio_servicio']) ? floatval($dc['precio_servicio']) : floatval($dc['precio_combo']);

                    $subtotal = $precio_unitario * $cantidad;

                    $funcion_insertar_detalle_venta = $modelo_venta->insertar_detalle_caja($id_caja_insertada,$id_servicio,$id_combo,$cantidad,$precio_unitario,$subtotal);

                }

            }
        }
    }
    if($funcion_insertar_detalle_venta == true){

        
        echo '<script language = javascript>
            alert("venta completada")
            self.location = "' . BASE_URL . '/vista/vista_adm/servicios_combos/vista_inicio_adm.php"
            </script>';
            exit;

            

    }


    

    
}

?>
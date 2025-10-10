<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/modelo_cliente/modelo_venta/modelo_venta.php');
require_once(ROOT_PATH . '/modelo/BD.php');
date_default_timezone_set('America/Argentina/Buenos_Aires');

session_start();



$modelo_venta = new venta($conn);

if(isset($_POST['traer_id_cita']) && $_POST['traer_id_cita'] === 'confirmar_cita'){
    $id_cita = $_POST['id_cita'];
    header("Location: " . BASE_URL . "/vista/vista_cliente/vista_venta/venta.php?id=$id_cita");
    exit;
    
}elseif(isset($_POST['vista_pagar']) && $_POST['vista_pagar'] === 'venta.php'){
    $id_cita = $_POST['id_cita'];
    $fecha_actual = date('Y-m-d H:i:s');

    // Traer monto original de la cita
    $funcion_traer_precio_cita = $modelo_venta->traer_datos_cita($id_cita);
    $precio_cita = floatval($funcion_traer_precio_cita); // MONTO ORIGINAL

    // Datos del formulario
    $id_metodo = intval($_POST['id_metodo'] ?? 0);
    $cantidad_pagar = floatval($_POST['cantidad'] ?? 0);
    $cantidad_calculada = floatval($_POST['cantidad_calculada'] ?? 0); // MONTO AJUSTADO

    // Validación de montos
    if (abs($cantidad_pagar - $cantidad_calculada) > 0.01) {
        echo '<script language="javascript">
            alert("El monto a pagar debe ser exactamente $' . number_format($cantidad_calculada, 2) . '")
            history.back()
        </script>';
        exit;
    }

    if ($id_metodo <= 0) {
        echo '<script language="javascript">
            alert("Debe seleccionar un método de pago válido")
            history.back()
        </script>';
        exit;
    }

    // ✅ Insertar en caja: monto original y monto ajustado
    $funcion_insertar_caja = $modelo_venta->insertar_caja($id_cita, $fecha_actual, $precio_cita, $cantidad_calculada);
    
    if (!$funcion_insertar_caja) {
        echo '<script language="javascript">
            alert("Error al crear el registro en caja")
            history.back()
        </script>';
        exit;
    }
    
    $id_caja_insertada = intval($funcion_insertar_caja);

    if ($id_caja_insertada > 0) {
        // Insertar método de pago
        $funcion_insertar_metodo_pago = $modelo_venta->insertar_metodo_pago($id_caja_insertada, $id_metodo, $cantidad_pagar);

        if($funcion_insertar_metodo_pago == true){
            // Insertar detalle de la caja
            $traer_detalle_cita = $modelo_venta->traer_datos_detalle_cita($id_cita);
            $todo_correcto = true;

            foreach($traer_detalle_cita as $dc){
                $id_servicio = !empty($dc['id_servicios']) ? intval($dc['id_servicios']) : null;
                $id_combo = !empty($dc['id_combos']) ? intval($dc['id_combos']) : null;
                $cantidad = 1;
                $precio_unitario = !empty($dc['precio_servicio']) ? floatval($dc['precio_servicio']) : floatval($dc['precio_combo']);
                $subtotal = $precio_unitario * $cantidad;

                $funcion_insertar_detalle_venta = $modelo_venta->insertar_detalle_caja(
                    $id_caja_insertada, 
                    $id_servicio, 
                    $id_combo, 
                    $cantidad, 
                    $precio_unitario, 
                    $subtotal,
                    $id_metodo
                );
                
                if(!$funcion_insertar_detalle_venta){
                    $todo_correcto = false;
                    error_log("Error insertando detalle para servicio: $id_servicio, combo: $id_combo");
                }
            }

            if($todo_correcto){
                $id_usuario = $_SESSION['user'];

                $funcion_insertar_historial_venta = $modelo_venta->insertar_historial_venta($id_caja_insertada,$id_usuario);
                if($funcion_insertar_historial_venta == true){
                    echo '<script language="javascript">
                    alert("Venta completada exitosamente")
                    self.location = "' . BASE_URL . '/vista/vista_cliente/vista_inicio/vista_inicio_cli.php"
                </script>';
                exit;

                }else{
                    echo '<script language="javascript">
                    alert("Error en la funcion de insertar el historial de venta")
                    history.back()
                </script>';
                exit;
                }
            } else {
                echo '<script language="javascript">
                    alert("Error al procesar algunos detalles de la venta")
                    history.back()
                </script>';
                exit;
            }
        } else {
            echo '<script language="javascript">
                alert("Error al procesar el método de pago")
                history.back()
            </script>';
            exit;
        }
    } else {
        echo '<script language="javascript">
            alert("Error al crear el registro en caja")
            history.back()
        </script>';
        exit;
    }

}
?>

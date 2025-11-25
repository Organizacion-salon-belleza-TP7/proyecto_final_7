<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/modelo_cliente/promociones/modelo_promociones.php');
require_once(ROOT_PATH . '/modelo/BD.php');
date_default_timezone_set('America/Argentina/Buenos_Aires');

session_start();

$clase_promociones = new promociones_cliente($conn);

if(isset($_GET['agregar_carrito']) && $_GET['agregar_carrito'] === 'vista_promociones'){
    $id_promocion = $_GET['id'];

    if (!isset($_SESSION['carrito_promos'])) {
        $_SESSION['carrito_promos'] = [];
    }

    if(!in_array($id_promocion,$_SESSION['carrito_promos'])){
        $_SESSION['carrito_promos'][] = $id_promocion;

    }else{
        echo "esta promocion ya esta en el carrito";
    }

    echo '<script language="javascript">
        alert("Agregado al carrito")
        self.location = "' . BASE_URL . '/vista/vista_cliente/vista_promociones/vista_promociones.php"
        </script>';
    exit;
}elseif(isset($_GET['ver_carrito']) && $_GET['ver_carrito'] === 'vista_promociones'){
    header("Location: " . BASE_URL . "/vista/vista_cliente/vista_promociones/vista_carrito_promos.php");
    exit;
}elseif(isset($_GET['carrito']) && $_GET['carrito'] === 'vista_carrito_promos'){
    if(!isset($_SESSION['carrito_promos']) || empty($_SESSION['carrito_promos'])){
        echo "<h2>Tu carrito esta vacio</h2>";
        echo "<a href='". BASE_URL ."/vista/vista_cliente/vista_promociones/vista_promociones.php'>Volver a promociones</a>";
        exit;

    }

    header("Location: " . BASE_URL . "/vista/vista_cliente/vista_promociones/vista_agendar_cita_promo.php");
    exit;
    

}elseif(isset($_POST['proceso']) && $_POST['proceso'] === 'agendar_paso1'){
    $_SESSION['lugar_seleccionado'] = $_POST['lugar'];
    $_SESSION['fecha_hora_seleccionada'] = $_POST['fecha_hora'];

    header("Location: " . BASE_URL . "/vista/vista_cliente/vista_promociones/vista_pagar_promos.php");
    exit;




}elseif(isset($_POST['proceso']) && $_POST['proceso'] === 'confirmar_pago'){
        $total_sin_pago = floatval($_POST['total_sin_pago']);
        $total_final = floatval($_POST['total_final']);
        $id_metodo_pago = intval($_POST['metodo_pago']);
        $id_lugar = $_SESSION['lugar_seleccionado'];
        $fecha_hora = $_SESSION['fecha_hora_seleccionada'];
        $id_usuario = $_SESSION['user'];
        $hora_actual = date('Y-m-d H:i:s');
        $activo = 1;

        $obtener_id_cliente = $clase_promociones->buscar_relacion_personas_usr($id_usuario);

        if(!empty($obtener_id_cliente)){
            $insertar_cita = $clase_promociones->insertar_cita($obtener_id_cliente,$fecha_hora,$activo,$id_lugar);

            if(!empty($insertar_cita)){
                foreach($_SESSION['carrito_promos'] as $id_promocion){
                    $funcion_detalle_insertar_detalle_cita = $clase_promociones->insertar_detalle_cita($insertar_cita,$id_promocion);

                    if($funcion_insertar_detalle_cita != TRUE){
                        echo "error al insertar el detalle de la cita";
                    }

                }
                
                $insertar_caja = $clase_promociones->insertar_caja_promociones($hora_actual,$insertar_cita,$total_final);

                if(!empty($insertar_caja)){
                    $insertar_detalle_caja = $clase_promociones->insertar_detalle_caja_promociones($insertar_caja,$total_sin_pago,$id_metodo_pago);

                    unset($_SESSION['carrito_promos']);
                    unset($_SESSION['lugar_seleccionado']);
                    unset($_SESSION['fecha_hora_seleccionada']);

                    echo '<script language="javascript">
                    alert("Compra realizada exitosamente")
                    self.location = "' . BASE_URL . '/vista/vista_cliente/vista_promociones/vista_promociones.php"
                    </script>';
                    exit;

                }

            }
        }


    
}elseif(isset($_GET['eliminar_prom_carrito']) && $_GET['eliminar_prom_carrito'] === 'vista_carrito_promos'){
    $id_eliminar = intval($_GET['id']);

    if(isset($_SESSION['carrito_promos'])){
        
        // FILTRAR LOS ID QUE NO COINCIDAN
        $_SESSION['carrito_promos'] = array_filter(
            $_SESSION['carrito_promos'],
            function($id) use ($id_eliminar){
                return intval($id) !== $id_eliminar;
            }
        );

        // REINDEXAR ARRAY (para evitar huecos)
        $_SESSION['carrito_promos'] = array_values($_SESSION['carrito_promos']);
    }

    echo '<script>
        alert("Promoción eliminada del carrito");
        window.location.href = "' . BASE_URL . '/vista/vista_cliente/vista_promociones/vista_carrito_promos.php";
    </script>';

    exit;

}
?>
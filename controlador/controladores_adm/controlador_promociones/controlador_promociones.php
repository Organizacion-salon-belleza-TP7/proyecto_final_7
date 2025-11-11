<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/modelo_adm/promociones/modelo_promociones.php');
require_once(ROOT_PATH . '/modelo/BD.php');

$clase_promociones = new promociones($conn);

if(isset($_GET['agregar']) && $_GET['agregar'] === 'vista_promociones'){
    header("Location: " . BASE_URL . "/vista/vista_adm/promociones/vista_agregar_promocion.php");
    exit;
    
}elseif(isset($_POST['agregar']) && $_POST['agregar'] === 'vista_agregar_promocion'){
    $tipo = $_POST['tipo_promocion'];
    $combo_select = $_POST['combo_select'];
    $servicio_select = $_POST['servicio_select'];
    $dias_promo = $_POST['dias_semana'];
    $descuento_promo = $_POST['descuento'];
    $puntos_descuento = $_POST['cantidad_puntos'];

    if($tipo == 'combo' && $combo_select != null){
        $funcion_insertar_promo_combo = $clase_promociones->insertar_promo_combo($combo_select,$dias_promo,$descuento_promo,$puntos_descuento);

        if($funcion_insertar_promo_combo == TRUE){
            echo '<script language = javascript>
                alert("Promo añadida correctamente")
                self.location = "' . BASE_URL . '/vista/vista_adm/promociones/vista_promociones.php"
                </script>';
                exit;

        }else{
            echo '<script language = javascript>
                alert("hubo un bug insertando la promo del combo")
                self.location = "' . BASE_URL . '/vista/vista_adm/promociones/vista_promociones.php"
                </script>';
                exit;
        }

    }elseif($tipo == 'servicio' && $servicio_select != null){
        $funcion_insertar_servicio_combo = $clase_promociones->insertar_promo_servicio($servicio_select,$dias_promo,$descuento_promo,$puntos_descuento);

        if($funcion_insertar_servicio_combo == TRUE){
            echo '<script language = javascript>
                alert("Promo añadida correctamente")
                self.location = "' . BASE_URL . '/vista/vista_adm/promociones/vista_promociones.php"
                </script>';
                exit;
        }else{
            echo '<script language = javascript>
                alert("hubo un bug insertando la promo del servicio")
                self.location = "' . BASE_URL . '/vista/vista_adm/promociones/vista_promociones.php"
                </script>';
                exit;
        }

    }else{
        echo "no se recibieron bien los datos y hubo un error";
        die();
    }

}elseif(isset($_GET['modificar']) && $_GET['modificar'] === 'vista_promociones'){
    $id_promocion = $_GET['id'];
    header("Location: " . BASE_URL . "/vista/vista_adm/promociones/vista_modificar_promocion.php?id=$id_promocion");
    exit;

}elseif(isset($_POST['modificar']) && $_POST['modificar'] === 'vista_modificar_promocion'){
    $id_promocion = $_POST['id_promocion'];
    $tipo = $_POST['tipo_promocion'];
    $combo_select = $_POST['combo_select'];
    $servicio_select = $_POST['servicio_select'];
    $dias_promo = $_POST['dias_semana'];
    $descuento_promo = $_POST['descuento'];
    $puntos_descuento = $_POST['puntos'];

    $funcion_updatear_promo = $clase_promociones->modificar_promocion($tipo,$combo_select,$servicio_select,$dias_promo,$descuento_promo,$puntos_descuento,$id_promocion);

    if($funcion_updatear_promo == TRUE){
        echo '<script language = javascript>
                alert("Se modifico correctamente la promocion")
                self.location = "' . BASE_URL . '/vista/vista_adm/promociones/vista_promociones.php"
                </script>';
                exit;


    }else{
        echo $funcion_insertar_promo_combo;
    }

}elseif(isset($_GET['cambiar_estado']) && $_GET['cambiar_estado'] === 'vista_promociones'){
    $id_promocion = $_GET['id'];

    $funcion_cambiar_estado = $clase_promociones->dar_baja_promo($id_promocion);

    if($funcion_cambiar_estado == true){
        echo '<script language = javascript>
                alert("Se cambio el estado de la promo correctamente")
                self.location = "' . BASE_URL . '/vista/vista_adm/promociones/vista_promociones.php"
                </script>';
                exit;

    }else{
        echo "hubo un bug en el controlador updateando el estado";
    }

}
?>
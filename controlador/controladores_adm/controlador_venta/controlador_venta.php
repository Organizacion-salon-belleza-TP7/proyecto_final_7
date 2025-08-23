<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');
require_once(ROOT_PATH . '/modelo/modelo_adm/modelo_venta/modelo_venta.php');
date_default_timezone_set('America/Argentina/Buenos_Aires');

session_start();

if(isset($_GET['dar_baja_medio_pago']) && $_GET['dar_baja_medio_pago'] === 'vista_medios_pagos'){
    $id_metodo_pago = $_GET['id'];

    $modelo_venta = new modelo_venta($conn);

    $funcion_dar_baja_medio_pago = $modelo_venta->dar_baja_medio_pago($id_metodo_pago);
    
    if($funcion_dar_baja_medio_pago == true){
        echo '<script>
                alert("Se dio de baja al medio de pago correctamente");
                window.location.href = "' . BASE_URL . '/vista/vista_adm/venta/vista_medios_pagos.php";
                </script>';
                exit;

    }elseif($funcion_dar_baja_medio_pago == false){
         echo '<script>
                alert("Hubo un fallo al dar de baja el medio de pago");
                window.location.href = "' . BASE_URL . '/vista/vista_adm/venta/vista_medios_pagos.php";
                </script>';
                exit;

    }else{
         echo '<script>
                alert("Hubo un fallo en el controlador");
                window.location.href = "' . BASE_URL . '/vista/vista_adm/venta/vista_medios_pagos.php";
                </script>';
                exit;
    }

}elseif(isset($_GET['agregar_metodo_pago']) && $_GET['agregar_metodo_pago'] === 'vista_agregar_metodo_pago'){
    header("Location: " . BASE_URL . "/vista/vista_adm/venta/vista_crear_medios_pagos.php");
    exit;

}elseif(isset($_POST['enviar']) && $_POST['enviar'] === 'agregar_medios_pagos'){
    $nombre_metodo_pago = $_POST['nombre_metodo_pago'];
    $importe = $_POST['importe'];
    $cantidad_importe = $_POST['cantidad_importe'];
    $activo = $_POST['activo'];


    $modelo_venta = new modelo_venta($conn);

    $funcion_crear_metodo_pago = $modelo_venta->agregar_metodo_pago($nombre_metodo_pago,$importe,$cantidad_importe,$activo);

    echo '<script>
        alert("Se agrego un medio de pago correctamente");
        window.location.href = "' . BASE_URL . '/vista/vista_adm/venta/vista_medios_pagos.php";
        </script>';
        exit;

}elseif(isset($_GET['modificar_medios_pagos']) && $_GET['modificar_medios_pagos'] === 'vista_medios_pagos'){
    $id_metodo_pago = $_GET['id'];

    $modelo_venta = new modelo_venta($conn);

    $funcion_formulario_modificar = $modelo_venta->formulario_modificar_medios_pagos($id_metodo_pago);

    include ROOT_PATH . '/vista/vista_adm/venta/vista_modificar_medios_pagos.php';
    exit;
}elseif(isset($_POST['vista_modificar_medios_pagos']) && $_POST['vista_modificar_medios_pagos'] === 'vista_modificar'){
    $id_metodo_pago = $_POST['id_metodo_pago'];
    $nombre_metodo_pago = $_POST['nombre_metodo_pago'];
    $importe = $_POST['importe'];
    $cantidad_importe = $_POST['cantidad_importe'];

    $modelo_venta = new modelo_venta($conn);

    $funcion_modificar = $modelo_venta->modificar_medios_pagos($id_metodo_pago,$nombre_metodo_pago,$importe,$cantidad_importe);

    if($funcion_modificar == true){
        echo '<script>
        alert("Se modifico un medio de pago correctamente");
        window.location.href = "' . BASE_URL . '/vista/vista_adm/venta/vista_medios_pagos.php";
        </script>';
        exit;
    }elseif($funcion_modificar == false){
        echo '<script>
        alert("hubo un fallo dentro del controlador");
        window.location.href = "' . BASE_URL . '/vista/vista_adm/venta/vista_medios_pagos.php";
        </script>';
        exit;
    }

}
?>
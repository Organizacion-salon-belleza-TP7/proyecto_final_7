<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/modelo_cliente/promociones/modelo_promociones.php');
require_once(ROOT_PATH . '/modelo/BD.php');

session_start();

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
    

}
?>
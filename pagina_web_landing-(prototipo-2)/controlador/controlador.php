<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');

if(isset($_GET['accion']) && $_GET['accion'] == 'login_nav'){
    header("Location: " . BASE_URL . "/vista/vista_login/vista_login.php");
    exit;

}elseif(isset($_GET['accion']) && $_GET['accion'] == 'login_cita'){
    header("Location: " . BASE_URL . "/vista/vista_login/vista_login.php");
    exit;

}else{
    echo "hubo un fallo en el rediccionamiento de la pagina landing";
}

?>
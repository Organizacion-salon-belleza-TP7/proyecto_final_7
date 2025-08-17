<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');
require_once(ROOT_PATH . '/modelo/modelo_adm/modelo_logout/modelo_logout.php');
date_default_timezone_set('America/Argentina/Buenos_Aires');

session_start();

if(isset($_GET['logout']) && $_GET['logout'] === 'vista_inicio_adm'){
    $logouts = new logout($conn);
    $id_usuario = $_SESSION['user'];
    $cerrar_session = $logouts->cerrar_session($id_usuario);

    if($cerrar_session = true){
        echo '<script language = javascript>
                alert("sesion cerrada.... redirigiendo")
                self.location = "' . BASE_URL . '/vista/vista_login/vista_login.php"
                </script>';
                exit;
    }else{
        echo '<script language = javascript>
                alert("hubo un fallo cerrando session")
                self.location = "' . BASE_URL . '/vista/vista_adm/servicios_combos/vista_inicio_adm.php"
                </script>';
                exit;
    }
    
}
?>
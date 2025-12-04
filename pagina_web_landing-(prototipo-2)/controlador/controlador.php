<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');
require_once(ROOT_PATH . '/pagina_web_landing-(prototipo-2)/modelo/modelo.php');

$clase_pagina_landing = new pagina_landing($conn);

if(isset($_GET['accion']) && $_GET['accion'] == 'login_nav'){
    header("Location: " . BASE_URL . "/vista/vista_login/vista_login.php");
    exit;

}elseif(isset($_GET['accion']) && $_GET['accion'] == 'login_cita'){
    header("Location: " . BASE_URL . "/vista/vista_login/vista_login.php");
    exit;

}elseif(isset($_POST['contactos']) && $_POST['contactos'] == 'formulario_contactos_landing'){
    $nombre = $_POST['name'];
    $email = $_POST['email'];
    $telefono = $_POST['phone'];
    $servicios_interes = $_POST['service'];
    $mensaje_personal = $_POST['message'];

    $funcion_enviar_email = $clase_pagina_landing->enviar_mail($nombre,$email,$telefono,$servicios_interes,$mensaje_personal);

    if($funcion_enviar_email == true){
        echo '<script language = javascript>
                alert("Email enviado correctamente")
                self.location = "' . BASE_URL . '/pagina_web_landing-(prototipo-2)/vista/prueba_landing.php"
                </script>';
                exit;

    }
    

}else{
    echo "hubo un fallo en el rediccionamiento de la pagina landing";
}

?>
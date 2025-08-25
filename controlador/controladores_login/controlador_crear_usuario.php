<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once(__DIR__ . '/../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');
require_once(ROOT_PATH . '/modelo/modelo_login/modelo_crear_usuario.php');
date_default_timezone_set('America/Argentina/Buenos_Aires');


if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_POST['crear_usuario'])){
        $nombre_user = $_POST['nombre'];
        $contrasena_user = $_POST['contrasena'];

        $nombre_cliente = $_POST['nombre_cliente'];
        $apellido_cliente = $_POST['apellido_cliente'];
        $alergias_cliente = $_POST['alergias_cliente'];
        $fecha_nacimiento_cliente = $_POST['fecha_nacimiento_cliente'];
        $dni_cliente = $_POST['dni_cliente'];

       

        $modelo_crear_usuario = new crear_usuario($conn);

        $crear_usuario = $modelo_crear_usuario->crear_usuario($nombre_user,$contrasena_user,$nombre_cliente,$apellido_cliente,$alergias_cliente,$fecha_nacimiento_cliente,$dni_cliente);

        if($crear_usuario === true){
            echo '<script>
                alert("usuario creado correctamente");
                window.location.href = "' . BASE_URL . '/vista/vista_login/vista_login.php";
                </script>';
                exit;

        }else{
            echo '<script>
                alert("hubo un fallo creando su usuario intentelo de nuevo");
                window.location.href = "' . BASE_URL . '/vista/vista_login/vista_crear_usuario.php";
                </script>';
                exit;
        }


    }
}
?>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once(__DIR__ . '/../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');
require_once(ROOT_PATH . '/modelo/modelo_login/modelo_login.php');

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_POST['send_form'])){
        $nombre_usuario = $_POST['name_user'];
        $contrasena = $_POST['password'];

        $logeo = new iniciar_session($nombre_usuario,$contrasena);
        $resultado = $logeo->buscar_usuario($conn);


        if($resultado && $resultado->num_rows > 0){
            $usuario = $resultado->fetch_assoc();

            $resultado_adm = $logeo->discriminar_adm($usuario,$conn);
            $resultado_emp = $logeo->discriminar_empleados($usuario,$conn);
            $resultado_cli = $logeo->discriminar_cliente($usuario,$conn);

            if($resultado_adm && $resultado_adm->num_rows > 0){
                header("Location: ". BASE_URL ."/vista/vista_adm/vista_inicio_adm.php");
                exit;

            }elseif($resultado_emp && $resultado_emp->num_rows > 0){
                header("Location: ". BASE_URL ."/vista/vista_empleados/vista_inicio_empleados.php");
                exit;
                
            }elseif($resultado_cli && $resultado_cli->num_rows > 0){
                header("Location: " . BASE_URL . "/vista/vista_cliente/vista_inicio_cli.php");
                exit;

            }else{
                echo '<script>
                alert("su usuario no cumple condiciones");
                window.location.href = "' . BASE_URL . '/vista/vista_login/vista_login.php";
                </script>';
                exit;
                

            }
        }else{
            echo '<script>
            alert("no se encontro un usuario");
            window.location.href = "' . BASE_URL . '/vista/vista_login/vista_login.php";
            </script>';
            exit;
        }
        
    }else{
        echo '<script>
        alert("hubo un fallo en el envio del formulario");
        window.location.href = "' . BASE_URL . '/vista/vista_login/vista_login.php";
        </script>';
        exit;

    }

}else{
    echo '<script>
    alert("hubo un fallo en el servidor");
    window.location.href = "' . BASE_URL . '/vista/vista_login/vista_login.php";
    </script>';
    exit;
}



?>
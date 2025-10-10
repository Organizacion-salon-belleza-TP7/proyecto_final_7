<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once(__DIR__ . '/../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');
require_once(ROOT_PATH . '/modelo/modelo_login/modelo_login.php');
date_default_timezone_set('America/Argentina/Buenos_Aires');

session_start();

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_POST['send_form'])){
        $nombre_usuario = $_POST['name_user'];
        $contrasena = $_POST['password'];

        $logeo = new iniciar_session($nombre_usuario,$contrasena);
        $resultado = $logeo->buscar_usuario($conn);




        if($resultado && $resultado->num_rows > 0){
            $usuario = $resultado->fetch_assoc();
            $fecha_actual = date('Y-m-d H:i:s');

            if($bucle = $usuario){
                $id_usuario = $bucle['id_usuario'];
                $_SESSION['user'] = $id_usuario;
                $insertar_logueo = $logeo->insertar_historial_login($conn,$id_usuario,$fecha_actual);
            }

            $resultado_adm = $logeo->discriminar_adm($usuario,$conn);
            $resultado_emp = $logeo->discriminar_empleados($usuario,$conn);
            $resultado_cli = $logeo->discriminar_cliente($usuario,$conn);

            if($usuario['id_tipo_usuario'] == 1 && $resultado_adm['resultado_adm'] && $resultado_adm['resultado_adm']->num_rows > 0){

                $traer_relacion = $resultado_adm['traer_adm']->fetch_assoc();
                $id_admin = $traer_relacion['id_trabajador'];

                $_SESSION['id_admin'] = $id_admin;

                header("Location: ". BASE_URL ."/vista/vista_adm/servicios_combos/vista_inicio_adm.php");
                exit;

            }elseif($usuario['id_tipo_usuario'] == 3 && $resultado_emp['resultado_emp'] && $resultado_emp['resultado_emp']->num_rows > 0){

                $traer_relacion = $resultado_emp['traer_emp']->fetch_assoc();
                $id_emp = $traer_relacion['id_trabajador'];

                $_SESSION['id_emp'] = $id_emp;

                header("Location: ". BASE_URL ."/vista/vista_empleados/vista_inicio_empleados.php");
                exit;
                
            }elseif($usuario['id_tipo_usuario'] == 2 && $resultado_cli['resultado_cli'] && $resultado_cli['resultado_cli']->num_rows > 0){

                $traer_relacion = $resultado_cli['traer_cli']->fetch_assoc();
                $id_cli = $traer_relacion['id_cliente'];

                $_SESSION['id_cliente'] = $id_cli;

                header("Location: " . BASE_URL . "/vista/vista_cliente/vista_inicio/vista_inicio_cli.php");
                exit;

            }else{
                echo '<script>
                alert("su usuario no cumple condiciones");
                window.location.href = "' . BASE_URL . '/vista/vista_login/vista_login.php";
                </script>';
                exit;
                

            }
            exit;
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
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/modelo_cliente/modelo_inicio/modelo_inicio.php');
require_once(ROOT_PATH . '/modelo/BD.php');
date_default_timezone_set('America/Argentina/Buenos_Aires');

session_start();

if(isset($_GET['reembolsar_cita']) && $_GET['reembolsar_cita'] === 'vista_inicio_cli'){
    $id_caja = $_GET['id_caja'];
    $id_cita = $_GET['id_cita'];
    $id_usuario = $_SESSION['user'];

    $modelo_cliente = new modelo_inicio($conn);

    $reembolsar_cita = $modelo_cliente->reembolsar_cita($id_caja,$id_usuario,$id_cita);

    if($reembolsar_cita){
        $precio_total = $reembolsar_cita['precio_total'];
        $porcentaje_reembolsado = $reembolsar_cita['porcentaje_reembolsado'];
        $reembolso = $reembolsar_cita['reembolso'];

        echo '<script language="javascript">
            alert("Se Reembolso correctamente.\\n\\n' .
            'Precio total: $' . $precio_total . '\\n' .
            'Porcentaje reembolsado: ' . $porcentaje_reembolsado . '%\\n' .
            'Monto reembolsado: $' . $reembolso . '");
            self.location = "' . BASE_URL . '/vista/vista_cliente/vista_inicio/vista_inicio_cli.php";
        </script>';

        exit;



    }

    

}
?>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../variable_global.php');// Asegúrate de que esta ruta sea correcta

$request = $_GET['route'] ?? '';

switch ($request) {
    case 'login':
        require_once(__DIR__ . '/routes/login/login.php'); 
        break;
    
    case 'inicio':
        // CORREGIDO: Asegúrate de que la ruta sea correcta
        require_once(__DIR__ . '/routes/adm/inicio/inicio.php');
        break;

    case 'proveedores':
        require_once(__DIR__ . '/routes/adm/inicio/proveedores.php');
        break;
    
    case 'citas':
        require_once(__DIR__ . '/routes/adm/citas/citas.php');
        break;

    case 'logouts':
        require_once(__DIR__ . '/routes/adm/logouts/logouts.php');
        break;

    case 'client_interface':
        require_once(__DIR__ . '/routes/cli/inicio_cli/inicio_cli.php');
        break;

   case 'elegir_cita':
        require_once(__DIR__ . '/routes/cli/citas_cli/elegir_cita.php');
        break;
        
    case 'citas_cli':
        require_once(__DIR__ . '/routes/cli/citas_cli/citas_cli.php');
        break;

    case 'ventas':
        require_once(__DIR__ . '/routes/cli/ventas/venta.php');
        break;

    default:
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode([
            'status' => 'error',
            'message' => 'Ruta no encontrada: ' . $request
        ]);
        break;
}
?>

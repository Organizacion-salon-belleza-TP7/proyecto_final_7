<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../variable_global.php');

$request = $_GET['route'] ?? '';
$method = $_SERVER['REQUEST_METHOD'];

switch ($request) {
    case 'login':
        require_once(__DIR__ . '/routes/login/login.php'); 
        break;
    
    case 'inicio':
        require_once(__DIR__ . '/routes/adm/inicio/inicio.php');
        break;

    case 'proveedores':
        require_once(__DIR__ . '/routes/adm/inicio/proveedores.php');
        break;
    
    case 'citas':
        require_once(__DIR__ . '/routes/adm/citas/citas.php');
        break;

    case 'logouts':
        require_once(__DIR__ . '/routes/logouts/logouts.php');
        break;

    case 'client_interface':
        require_once(__DIR__ . '/routes/cli/inicio_cli/inicio_cli.php');
        break;

    case 'elegir_cita':
        // ✅ CORREGIDO: Permitir tanto GET como POST
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
            'success' => false,
            'message' => 'Ruta no encontrada: ' . $request
        ]);
        break;
}
?>
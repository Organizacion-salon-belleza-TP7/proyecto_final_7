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
    
    default:
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode([
            'status' => 'error',
            'message' => 'Ruta no encontrada: ' . $request
        ]);
        break;
}
?>

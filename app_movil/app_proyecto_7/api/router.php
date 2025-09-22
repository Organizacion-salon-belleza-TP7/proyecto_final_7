<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../variable_global.php');

$request = $_GET['route'] ?? '';

switch ($request) {
    case 'login':
        require_once(__DIR__ . '/routes/login/login.php'); // apunta al archivo correcto
        break;
    
    case 'usuarios':
        require_once(__DIR__ . '/routes/usuarios.php');
        break;
    
    default:
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode([
            'status' => 'error',
            'message' => 'Ruta no encontrada'
        ]);
        break;
}
?>

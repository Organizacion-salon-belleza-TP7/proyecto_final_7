<?php
require_once(__DIR__ . '/../../../variable_global.php');

$request = $_GET['route'] ?? '';

switch ($request) {
    case 'login':
        require_once(__DIR__ . '/routes/login.php');
        break;
    
    case 'usuarios':
        require_once(__DIR__ . '/routes/usuarios.php');
        break;
    
    default:
        echo json_encode([
            'status' => 'error',
            'message' => 'Ruta no encontrada'
        ]);
        break;
}

?>

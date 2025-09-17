<?php
require_once(__DIR__ . '/../../controladores/controlador_login/controlador_login.php');

// Siempre responder en JSON
header('Content-Type: application/json; charset=UTF-8');

// Solo permitir POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Incluir el controlador que ya hace todo
    require_once(__DIR__ . '/../controllers/controlador_login.php');
} else {
    echo json_encode([
        'status' => 'error',
        'message'=> 'Método no permitido. Use POST.'
    ]);
}

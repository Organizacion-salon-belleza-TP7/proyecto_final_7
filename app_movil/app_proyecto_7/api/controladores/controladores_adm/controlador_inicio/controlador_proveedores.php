<?php
header('Content-Type: application/json; charset=UTF-8');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once(__DIR__ . '/../../../config/db.php');

require_once(__DIR__ . '/../../../modelos/modelos_adm/modelo_inicio/modelo_proveedores.php');

$method = $_SERVER['REQUEST_METHOD'];


if ($method === 'GET') {
    
    $modeloProveedor = new ModeloProveedor($conn);

   
    $proveedores = $modeloProveedor->obtenerProveedores();

    
    echo json_encode($proveedores);
} else {
    http_response_code(405); 
    echo json_encode(['error' => 'Método no permitido. Solo se acepta GET.']);
}

$conn->close();
?>
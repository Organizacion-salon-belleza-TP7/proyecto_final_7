<?php
// Mostrar errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=UTF-8');

require_once(__DIR__ . '/../../../config/db.php');
require_once(__DIR__ . '/../../../modelos/modelos_adm/modelo_citas/modelo_citas.php');

$citas = new CitasModelo($conn);

// Obtener el método HTTP
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            // Obtener detalle de una cita específica
            $id = intval($_GET['id']);
            $result = $citas->obtenerDetalle($id);
            echo json_encode($result ?: []);
        } else {
            // Listar todas las citas
            $result = $citas->listar();
            echo json_encode($result);
        }
        break;

    default:
        echo json_encode(['error' => 'Método no permitido']);
        break;
}

$conn->close();
?>

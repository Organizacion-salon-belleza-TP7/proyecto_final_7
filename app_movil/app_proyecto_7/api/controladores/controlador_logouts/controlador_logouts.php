<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=UTF-8');

require_once(__DIR__ . '/../../config/db.php');
require_once(__DIR__ . '/../../modelos/modelo_logouts/modelo_logouts.php'); // Ajusta la ruta según tu estructura

session_start();

// Crear instancia del modelo
$logout_model = new logout($conn);

// Manejar diferentes métodos HTTP
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'POST':
        // POST para cerrar sesión
        handleLogout($logout_model);
        break;
        
    case 'GET':
        // GET para verificar estado de sesión o forzar logout
        if (isset($_GET['check_session'])) {
            checkSessionStatus();
        } else {
            handleLogout($logout_model);
        }
        break;
        
    default:
        http_response_code(405);
        echo json_encode([
            'success' => false,
            'message' => 'Método no permitido'
        ]);
        break;
}

function handleLogout($logout_model) {
    // Obtener el ID de usuario de diferentes formas
    $id_usuario = null;
    
    // 1. Desde parámetros GET (para React Native)
    if (isset($_GET['id_usuario']) && is_numeric($_GET['id_usuario'])) {
        $id_usuario = intval($_GET['id_usuario']);
    }
    // 2. Desde JSON body (para peticiones POST con JSON)
    elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);
        if (isset($input['id_usuario']) && is_numeric($input['id_usuario'])) {
            $id_usuario = intval($input['id_usuario']);
        }
    }
    // 3. Desde la sesión PHP (para web tradicional)
    elseif (isset($_SESSION['id_usuario'])) {
        $id_usuario = $_SESSION['id_usuario'];
    }
    
    // Validar que tenemos un ID de usuario
    if (!$id_usuario || $id_usuario <= 0) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'ID de usuario no válido o no proporcionado'
        ]);
        return;
    }
    
    // Ejecutar el logout
    $result = $logout_model->cerrar_session($id_usuario);
    
    if ($result) {
        // Limpiar la sesión si existe
        if (isset($_SESSION)) {
            session_unset();
            session_destroy();
        }
        
        echo json_encode([
            'success' => true,
            'message' => 'Sesión cerrada exitosamente',
            'id_usuario' => $id_usuario,
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Error al cerrar la sesión',
            'id_usuario' => $id_usuario
        ]);
    }
}

function checkSessionStatus() {
    // Verificar si hay una sesión activa
    if (isset($_SESSION['id_usuario']) && !empty($_SESSION['id_usuario'])) {
        echo json_encode([
            'success' => true,
            'is_logged_in' => true,
            'id_usuario' => $_SESSION['id_usuario'],
            'user_data' => [
                'nombre_usuario' => $_SESSION['nombre_usuario'] ?? '',
                'rol' => $_SESSION['rol'] ?? ''
            ]
        ]);
    } else {
        echo json_encode([
            'success' => true,
            'is_logged_in' => false,
            'message' => 'No hay sesión activa'
        ]);
    }
}
?>
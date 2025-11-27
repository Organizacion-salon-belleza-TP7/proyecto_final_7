<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../config/db.php');
require_once(__DIR__ . '/../../../modelos/modelo_cli/modelo_venta/modelo_venta.php');
date_default_timezone_set('America/Argentina/Buenos_Aires');

header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Manejar preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

session_start();

$modelo_venta = new venta($conn);

// Obtener método de la solicitud
$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

function sendResponse($status, $data = null, $message = '') {
    http_response_code($status);
    echo json_encode([
        'status' => $status >= 200 && $status < 300 ? 'success' : 'error',
        'message' => $message,
        'data' => $data
    ]);
    exit();
}

try {
    switch($method) {
        case 'GET':
            handleGetRequest();
            break;
            
        case 'POST':
            handlePostRequest($input);
            break;
            
        default:
            sendResponse(405, null, 'Método no permitido');
    }
} catch (Exception $e) {
    sendResponse(500, null, 'Error interno del servidor: ' . $e->getMessage());
}

function handleGetRequest() {
    global $modelo_venta;
    
    // Obtener métodos de pago
    if (isset($_GET['action']) && $_GET['action'] === 'metodos_pago') {
        $metodos = $modelo_venta->traer_medios_pagos();
        $metodosArray = [];
        
        while($metodo = $metodos->fetch_assoc()) {
            $metodosArray[] = $metodo;
        }
        
        sendResponse(200, $metodosArray, 'Métodos de pago obtenidos');
    }
    
    // Obtener datos de cita
    if (isset($_GET['action']) && $_GET['action'] === 'datos_cita' && isset($_GET['id_cita'])) {
        $id_cita = intval($_GET['id_cita']);
        $total = $modelo_venta->traer_datos_cita($id_cita);
        
        // Obtener detalle de la cita
        $detalle = $modelo_venta->traer_datos_detalle_cita($id_cita);
        
        sendResponse(200, [
            'total' => floatval($total),
            'detalle' => $detalle,
            'id_cita' => $id_cita
        ], 'Datos de cita obtenidos');
    }
    
    sendResponse(400, null, 'Acción no válida o parámetros faltantes');
}

function handlePostRequest($input) {
    global $modelo_venta;
    
    if (!isset($input['action'])) {
        sendResponse(400, null, 'Acción no especificada');
    }
    
    switch($input['action']) {
        case 'procesar_pago':
            procesarPago($input);
            break;
            
        case 'confirmar_cita':
            confirmarCita($input);
            break;
            
        default:
            sendResponse(400, null, 'Acción no válida');
    }
}

function procesarPago($data) {
    global $modelo_venta;
    
    // Validar datos requeridos
    $required = ['id_cita', 'id_metodo', 'cantidad', 'cantidad_calculada'];
    foreach($required as $field) {
        if (!isset($data[$field])) {
            sendResponse(400, null, "Campo requerido faltante: $field");
        }
    }
    
    $id_cita = intval($data['id_cita']);
    $id_metodo = intval($data['id_metodo']);
    $cantidad_pagar = floatval($data['cantidad']);
    $cantidad_calculada = floatval($data['cantidad_calculada']);
    $fecha_actual = date('Y-m-d H:i:s');
    
    // Validar sesión de usuario
    if (!isset($_SESSION['user'])) {
        sendResponse(401, null, 'Usuario no autenticado');
    }
    $id_usuario = $_SESSION['user'];
    
    // Validación de montos
    if (abs($cantidad_pagar - $cantidad_calculada) > 0.01) {
        sendResponse(400, null, "El monto a pagar debe ser exactamente $" . number_format($cantidad_calculada, 2));
    }
    
    if ($id_metodo <= 0) {
        sendResponse(400, null, "Debe seleccionar un método de pago válido");
    }
    
    // Traer monto original de la cita
    $precio_cita = floatval($modelo_venta->traer_datos_cita($id_cita));
    
    // ✅ Insertar en caja
    $id_caja_insertada = $modelo_venta->insertar_caja($id_cita, $fecha_actual, $precio_cita, $cantidad_calculada);
    
    if (!$id_caja_insertada) {
        sendResponse(500, null, "Error al crear el registro en caja");
    }
    
    // Insertar método de pago
    $funcion_insertar_metodo_pago = $modelo_venta->insertar_metodo_pago($id_caja_insertada, $id_metodo, $cantidad_pagar);
    
    if (!$funcion_insertar_metodo_pago) {
        sendResponse(500, null, "Error al procesar el método de pago");
    }
    
    // Insertar detalle de la caja
    $traer_detalle_cita = $modelo_venta->traer_datos_detalle_cita($id_cita);
    $todo_correcto = true;
    
    foreach($traer_detalle_cita as $dc) {
        $id_servicio = !empty($dc['id_servicios']) ? intval($dc['id_servicios']) : null;
        $id_combo = !empty($dc['id_combos']) ? intval($dc['id_combos']) : null;
        $cantidad = 1;
        $precio_unitario = !empty($dc['precio_servicio']) ? floatval($dc['precio_servicio']) : floatval($dc['precio_combo']);
        $subtotal = $precio_unitario * $cantidad;
        
        $funcion_insertar_detalle_venta = $modelo_venta->insertar_detalle_caja(
            $id_caja_insertada, 
            $id_servicio, 
            $id_combo, 
            $cantidad, 
            $precio_unitario, 
            $subtotal,
            $id_metodo
        );
        
        if(!$funcion_insertar_detalle_venta) {
            $todo_correcto = false;
            error_log("Error insertando detalle para servicio: $id_servicio, combo: $id_combo");
        }
    }
    
    if (!$todo_correcto) {
        sendResponse(500, null, "Error al procesar algunos detalles de la venta");
    }
    
    // Insertar historial de venta
    $funcion_insertar_historial_venta = $modelo_venta->insertar_historial_venta($id_caja_insertada, $id_usuario);
    
    if (!$funcion_insertar_historial_venta) {
        sendResponse(500, null, "Error en la función de insertar el historial de venta");
    }
    
    sendResponse(200, [
        'id_venta' => $id_caja_insertada,
        'monto_total' => $cantidad_calculada,
        'fecha_venta' => $fecha_actual
    ], "Venta completada exitosamente");
}

function confirmarCita($data) {
    if (!isset($data['id_cita'])) {
        sendResponse(400, null, 'ID de cita requerido');
    }
    
    $id_cita = intval($data['id_cita']);
    
    sendResponse(200, [
        'redirect_url' => BASE_URL . "/vista/vista_cliente/vista_venta/venta.php?id=$id_cita",
        'id_cita' => $id_cita
    ], 'Cita confirmada para pago');
}
?>
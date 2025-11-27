<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../config/db.php');
// ✅ CORREGIDO: Usar include_once en lugar de require_once
include_once(__DIR__ . '/../../../modelos/modelo_cli/modelo_venta/modelo_venta.php');
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

// ✅ VERIFICAR SI LA CLASE EXISTE ANTES DE INSTANCIAR
if (!class_exists('venta')) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Error: Clase venta no encontrada'
    ]);
    exit();
}

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
        
        // Validar que la cita existe
        if ($id_cita <= 0) {
            sendResponse(400, null, 'ID de cita no válido');
        }
        
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
    
    if (!$input || !isset($input['action'])) {
        sendResponse(400, null, 'Datos JSON inválidos o acción no especificada');
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
    
    // ✅ VALIDAR DATOS REQUERIDOS (INCLUYENDO user_id)
    $required = ['id_cita', 'id_metodo', 'cantidad', 'cantidad_calculada', 'user_id'];
    foreach($required as $field) {
        if (!isset($data[$field]) || empty($data[$field])) {
            sendResponse(400, null, "Campo requerido faltante: $field");
        }
    }
    
    $id_cita = intval($data['id_cita']);
    $id_metodo = intval($data['id_metodo']);
    $cantidad_pagar = floatval($data['cantidad']);
    $cantidad_calculada = floatval($data['cantidad_calculada']);
    $fecha_actual = date('Y-m-d H:i:s');
    
    // ✅ USAR user_id DEL BODY EN LUGAR DE SESSION
    $id_usuario = intval($data['user_id']);
    
    if ($id_usuario <= 0) {
        sendResponse(401, null, 'Usuario no válido o no autenticado');
    }
    
    // Validaciones adicionales
    if ($id_cita <= 0) {
        sendResponse(400, null, 'ID de cita no válido');
    }
    
    if ($id_metodo <= 0) {
        sendResponse(400, null, 'Método de pago no válido');
    }
    
    if ($cantidad_pagar <= 0 || $cantidad_calculada <= 0) {
        sendResponse(400, null, 'Los montos deben ser mayores a cero');
    }
    
    // Validación de montos
    if (abs($cantidad_pagar - $cantidad_calculada) > 0.01) {
        sendResponse(400, null, "El monto a pagar debe ser exactamente $" . number_format($cantidad_calculada, 2));
    }
    
    // Traer monto original de la cita
    $precio_cita = floatval($modelo_venta->traer_datos_cita($id_cita));
    
    if ($precio_cita <= 0) {
        sendResponse(400, null, "No se pudo obtener el precio de la cita o la cita no existe");
    }
    
    // ✅ Insertar en caja
    $id_caja_insertada = $modelo_venta->insertar_caja($id_cita, $fecha_actual, $precio_cita, $cantidad_calculada);
    
    if (!$id_caja_insertada) {
        sendResponse(500, null, "Error al crear el registro en caja");
    }
    
    // Insertar método de pago
    $funcion_insertar_metodo_pago = $modelo_venta->insertar_metodo_pago($id_caja_insertada, $id_metodo, $cantidad_pagar);
    
    if (!$funcion_insertar_metodo_pago) {
        // Revertir la inserción en caja si falla el método de pago
        sendResponse(500, null, "Error al procesar el método de pago");
    }
    
    // Insertar detalle de la caja
    $traer_detalle_cita = $modelo_venta->traer_datos_detalle_cita($id_cita);
    $todo_correcto = true;
    $errores_detalle = [];
    
    if (empty($traer_detalle_cita)) {
        sendResponse(400, null, "No se encontraron servicios/combos para la cita especificada");
    }
    
    foreach($traer_detalle_cita as $index => $dc) {
        $id_servicio = !empty($dc['id_servicios']) ? intval($dc['id_servicios']) : null;
        $id_combo = !empty($dc['id_combos']) ? intval($dc['id_combos']) : null;
        $cantidad = 1;
        $precio_unitario = !empty($dc['precio_servicio']) ? floatval($dc['precio_servicio']) : floatval($dc['precio_combo']);
        $subtotal = $precio_unitario * $cantidad;
        
        // Validar que al menos uno de los IDs no sea nulo
        if ($id_servicio === null && $id_combo === null) {
            $errores_detalle[] = "Item $index no tiene servicio ni combo asociado";
            $todo_correcto = false;
            continue;
        }
        
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
            $errores_detalle[] = "Error insertando detalle para item $index";
        }
    }
    
    if (!$todo_correcto) {
        error_log("Errores en detalle de venta: " . implode(', ', $errores_detalle));
        sendResponse(500, null, "Error al procesar algunos detalles de la venta: " . implode(', ', $errores_detalle));
    }
    
    // Insertar historial de venta
    $funcion_insertar_historial_venta = $modelo_venta->insertar_historial_venta($id_caja_insertada, $id_usuario);
    
    if (!$funcion_insertar_historial_venta) {
        sendResponse(500, null, "Error al registrar el historial de venta");
    }
    
    sendResponse(200, [
        'id_venta' => $id_caja_insertada,
        'monto_total' => $cantidad_calculada,
        'fecha_venta' => $fecha_actual,
        'id_cita' => $id_cita,
        'id_usuario' => $id_usuario,
        'metodo_pago_id' => $id_metodo
    ], "Venta completada exitosamente");
}

function confirmarCita($data) {
    // Validar datos requeridos
    if (!isset($data['id_cita']) || !isset($data['user_id'])) {
        sendResponse(400, null, 'ID de cita y usuario requeridos');
    }
    
    $id_cita = intval($data['id_cita']);
    $id_usuario = intval($data['user_id']);
    
    if ($id_cita <= 0) {
        sendResponse(400, null, 'ID de cita no válido');
    }
    
    if ($id_usuario <= 0) {
        sendResponse(401, null, 'Usuario no válido');
    }
    
    sendResponse(200, [
        'id_cita' => $id_cita,
        'id_usuario' => $id_usuario,
        'message' => 'Cita confirmada para pago',
        'redirect_url' => '/vista/vista_cli/vista_venta/venta.php?id=' . $id_cita
    ], 'Cita confirmada para pago');
}
?>
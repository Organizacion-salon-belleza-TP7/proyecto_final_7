<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=UTF-8');

require_once(__DIR__ . '/../../../config/db.php');

require_once(__DIR__ . '/../../../modelos/modelo_cli/modelo_elegir_cita/modelo_elegir_cita.php');

// Crear instancia del modelo
$modelo = new CitaModeloApi($conn);

// Determinar método de solicitud
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {

    // ✅ OBTENER LISTAS (servicios, combos, lugares)
    case 'GET':
        // Ejemplo de rutas:
        // /api/citas?tipo=servicios
        // /api/citas?tipo=combos
        // /api/citas?tipo=lugares
        // /api/citas?id_cita=12

        if (isset($_GET['tipo'])) {
            $tipo = $_GET['tipo'];

            if ($tipo === 'servicios') {
                echo json_encode($modelo->obtenerServicios());
            } elseif ($tipo === 'combos') {
                echo json_encode($modelo->obtenerCombos());
            } elseif ($tipo === 'lugares') {
                echo json_encode($modelo->obtenerLugares());
            } else {
                echo json_encode(['error' => 'Tipo no válido. Usa servicios, combos o lugares.']);
            }
        }
        // Si se pasa un ID de cita, traer detalles completos
        elseif (isset($_GET['id_cita'])) {
            $id_cita = intval($_GET['id_cita']);
            $cita = $modelo->obtenerCitaPorId($id_cita);
            $detalles = $modelo->obtenerDetallesCita($id_cita);
            echo json_encode(['cita' => $cita, 'detalles' => $detalles]);
        }
        else {
            echo json_encode(['error' => 'Parámetros inválidos']);
        }
        break;

    // ✅ GUARDAR UNA NUEVA CITA
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data) {
            echo json_encode(['error' => 'No se recibieron datos']);
            break;
        }

        $id_cliente = $data['id_cliente'] ?? null;
        $fecha_cita = $data['fecha_cita'] ?? null;
        $id_lugar = $data['id_lugar'] ?? null;
        $servicios = $data['servicios'] ?? [];
        $combos = $data['combos'] ?? [];

        if (!$id_cliente || !$fecha_cita || !$id_lugar) {
            echo json_encode(['error' => 'Faltan datos obligatorios']);
            break;
        }

        $resultado = $modelo->guardarCita($id_cliente, $fecha_cita, $id_lugar, $servicios, $combos);

        if (isset($resultado['error'])) {
            echo json_encode($resultado);
        } else {
            echo json_encode([
                'message' => 'Cita registrada correctamente',
                'data' => $resultado
            ]);
        }
        break;

    // 🚫 MÉTODO NO PERMITIDO
    default:
        echo json_encode(['error' => 'Método no permitido']);
        break;
}

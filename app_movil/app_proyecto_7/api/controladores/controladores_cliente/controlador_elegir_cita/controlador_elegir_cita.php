<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=UTF-8');

require_once(__DIR__ . '/../../../config/db.php');
require_once(__DIR__ . '/../../../modelos/modelo_cli/modelo_elegir_cita/modelo_elegir_cita.php');

// Crear instancia del modelo
$modelo = new CitaModeloApi($conn);

// MÉTODO HTTP
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {

    /* ================================
       GET → servicios, combos, lugares
       ================================ */
    case 'GET':

        // /api/router.php?route=elegir_cita&tipo=servicios
        if (isset($_GET['route']) && $_GET['route'] === 'elegir_cita') {

            if (!isset($_GET['tipo'])) {
                echo json_encode([
                    "success" => false,
                    "message" => "Tipo no especificado."
                ]);
                exit;
            }

            $tipo = $_GET['tipo'];

            if ($tipo === 'servicios') {
                $data = $modelo->obtenerServicios();
                echo json_encode([
                    "success" => true,
                    "servicios" => $data
                ]);
                exit;
            }

            if ($tipo === 'combos') {
                $data = $modelo->obtenerCombos();
                echo json_encode([
                    "success" => true,
                    "combos" => $data
                ]);
                exit;
            }

            if ($tipo === 'lugares') {
                $data = $modelo->obtenerLugares();
                echo json_encode([
                    "success" => true,
                    "lugares" => $data
                ]);
                exit;
            }

            if ($tipo === 'horarios') {
                 $fecha = $_GET['fecha'] ?? date('Y-m-d');
                $data = $modelo->obtenerHorariosDisponibles($fecha);
                echo json_encode([
                "success" => true,
                "horarios" => $data
                ]);
                exit;
            }

            echo json_encode([
                "success" => false,
                "message" => "Tipo no válido."
            ]);
            exit;
        }

        // /api/router.php?route=client_interface&accion=obtener_detalle_cita&id_cita=X
        if (isset($_GET['route']) && $_GET['route'] === 'client_interface' &&
            isset($_GET['accion']) && $_GET['accion'] === 'obtener_detalle_cita'
        ) {
            if (!isset($_GET['id_cita'])) {
                echo json_encode([
                    "success" => false,
                    "message" => "ID de cita no especificado."
                ]);
                exit;
            }

            $id = intval($_GET['id_cita']);

            $cita = $modelo->obtenerCitaPorId($id);
            $detalles = $modelo->obtenerDetallesCita($id);

            echo json_encode([
                "success" => true,
                "cita" => $cita,
                "detalles" => $detalles
            ]);
            exit;
        }

        echo json_encode([
            "success" => false,
            "message" => "Ruta GET inválida."
        ]);
        exit;


    /* ================================
       POST → guardar cita
       ================================ */
    case 'POST':

        // ✅ NUEVO: Manejar POST para elegir_cita
        if (isset($_GET['route']) && $_GET['route'] === 'elegir_cita') {

            $data = json_decode(file_get_contents("php://input"), true);

            if (!$data) {
                echo json_encode([
                    "success" => false,
                    "message" => "JSON inválido."
                ]);
                exit;
            }

            $id_cliente = $data['id_cliente'] ?? null;
            $fecha = $data['fecha_cita'] ?? null;
            $id_lugar = $data['id_lugar'] ?? null;
            $servicios = $data['servicios'] ?? [];
            $combos = $data['combos'] ?? [];

            // Validación
            if (!$id_cliente || !$fecha || !$id_lugar) {
                echo json_encode([
                    "success" => false,
                    "message" => "Faltan parámetros obligatorios."
                ]);
                exit;
            }

            $resultado = $modelo->guardarCita(
                $id_cliente,
                $fecha,
                $id_lugar,
                $servicios,
                $combos
            );

            if (isset($resultado['error'])) {
                echo json_encode([
                    "success" => false,
                    "message" => $resultado['error']
                ]);
                exit;
            }

            echo json_encode([
                "success" => true,
                "message" => "Cita guardada correctamente.",
                "id_cita" => $resultado['id_cita']
            ]);
            exit;
        }

        // Manejar POST para client_interface (existente)
        if (isset($_GET['route']) && $_GET['route'] === 'client_interface' &&
            isset($_GET['accion']) && $_GET['accion'] === 'guardar_cita'
        ) {

            $data = json_decode(file_get_contents("php://input"), true);

            if (!$data) {
                echo json_encode([
                    "success" => false,
                    "message" => "JSON inválido."
                ]);
                exit;
            }

            $id_cliente = $data['id_cliente'] ?? null;
            $fecha = $data['fecha_cita'] ?? null;
            $id_lugar = $data['id_lugar'] ?? null;
            $servicios = $data['servicios'] ?? [];
            $combos = $data['combos'] ?? [];

            // Validación
            if (!$id_cliente || !$fecha || !$id_lugar) {
                echo json_encode([
                    "success" => false,
                    "message" => "Faltan parámetros obligatorios."
                ]);
                exit;
            }

            $resultado = $modelo->guardarCita(
                $id_cliente,
                $fecha,
                $id_lugar,
                $servicios,
                $combos
            );

            if (isset($resultado['error'])) {
                echo json_encode([
                    "success" => false,
                    "message" => $resultado['error']
                ]);
                exit;
            }

            echo json_encode([
                "success" => true,
                "message" => "Cita guardada correctamente.",
                "id_cita" => $resultado['id_cita']
            ]);
            exit;
        }

        echo json_encode([
            "success" => false,
            "message" => "Ruta POST inválida."
        ]);
        exit;


    /* ================================
       MÉTODO NO PERMITIDO
       ================================ */
    default:
        echo json_encode([
            "success" => false,
            "message" => "Método no permitido."
        ]);
        exit;
}
?>
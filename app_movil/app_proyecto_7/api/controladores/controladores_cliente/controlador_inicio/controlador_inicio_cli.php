<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=UTF-8');

require_once(__DIR__ . '/../../../config/db.php');
require_once(__DIR__ . '/../../../modelos/modelo_cli/modelo_inicio/modelo_inicio_cli.php');
date_default_timezone_set('America/Argentina/Buenos_Aires');

session_start();

// 👇 NUEVO: ver citas compradas
if (isset($_GET['accion']) && $_GET['accion'] === 'ver_citas_compradas') {

    // Primero intentar leer id_usuario desde GET (cuando la app lo envía)
    if (isset($_GET['id_usuario']) && is_numeric($_GET['id_usuario'])) {
        $id_usuario = intval($_GET['id_usuario']);
    } else {
        // Si no viene por GET, intentar desde la sesión (compatibilidad con sesiones)
        $id_usuario = isset($_SESSION['user']) ? intval($_SESSION['user']) : 0;
    }

    // DEBUG temporal (descomentar para ver qué está llegando)
    // echo json_encode(['debug' => $_GET, 'session' => $_SESSION, 'id_usuario' => $id_usuario]); exit;

    if ($id_usuario <= 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Usuario no autenticado o id_usuario no provisto'
        ]);
        exit;
    }

    $modelo_cliente = new modelo_inicio($conn);
    $resultado = $modelo_cliente->traer_citas_compradas($id_usuario);

    if ($resultado && $resultado->num_rows > 0) {
        $citas = [];
        while ($fila = $resultado->fetch_assoc()) {
            $citas[] = $fila;
        }

        echo json_encode([
            'success' => true,
            'citas' => $citas
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'No se encontraron citas compradas'
        ]);
    }

    exit;
}

// 👇 Tu código existente de reembolso (sin cambios)
if (isset($_GET['reembolsar_cita']) && $_GET['reembolsar_cita'] === 'vista_inicio_cli') {
    if (!isset($_GET['id_caja']) || !isset($_GET['id_cita'])) {
        echo json_encode([
            'error' => 'Parámetros faltantes: id_caja o id_cita'
        ]);
        exit;
    }

    $id_caja = intval($_GET['id_caja']);
    $id_cita = intval($_GET['id_cita']);
    $id_usuario = isset($_SESSION['user']) ? intval($_SESSION['user']) : 0;

    if ($id_usuario <= 0) {
        echo json_encode(['error' => 'Usuario no autenticado']);
        exit;
    }

    $modelo_cliente = new modelo_inicio($conn);
    $reembolso = $modelo_cliente->reembolsar_cita($id_caja, $id_usuario, $id_cita);

    if ($reembolso) {
        echo json_encode([
            'success' => true,
            'message' => 'Reembolso procesado correctamente',
            'data' => $reembolso
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'error' => 'Error al procesar el reembolso'
        ]);
    }

    exit;
}

echo json_encode([
    'error' => 'Acción no válida o parámetro faltante'
]);
exit;
?>

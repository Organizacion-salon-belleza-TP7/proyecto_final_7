<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../config/db.php');
require_once(__DIR__ . '/../../../modelos/modelos_adm/modelo_logouts/modelo_logouts.php');


header('Content-Type: application/json; charset=UTF-8');

$logout = new logout($conn);

// Cerrar sesión de un usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data['id_usuario'])) {
        echo json_encode(["error" => "Falta el parámetro id_usuario"]);
        exit;
    }

    $id_usuario = intval($data['id_usuario']);
    $cerrado = $logout->cerrar_session($id_usuario);

    if ($cerrado) {
        echo json_encode(["message" => "Sesión cerrada correctamente"]);
    } else {
        echo json_encode(["error" => "No se pudo cerrar la sesión"]);
    }
    exit;
}

// Mostrar historial de logueos
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $result = $logout->mostrar_logueos();

    $logueos = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $logueos[] = $row;
        }
    }

    echo json_encode($logueos);
    exit;
}
?>

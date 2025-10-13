<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');
require_once(ROOT_PATH . '/controlador/controlador_trabajador/TrabajadorController.php');

// Crear instancia del controlador con la conexión $conn
$controller = new TrabajadorController($conn);

// Obtener el ID desde la URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Cancelar solo si el ID es válido
if ($id > 0) {
    $controller->cancelar($id);
} else {
    // Redirigir a la lista de espera si el ID no es válido
    header("Location:". BASE_URL ."vista/vista_trabajadores/vista_inicio/listaEspera.php");
    exit;
}


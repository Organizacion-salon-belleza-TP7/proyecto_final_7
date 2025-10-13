<?php
require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');
require_once(ROOT_PATH . '/controlador/controlador_trabajadores/controlador_inicio/TrabajadorController.php');

// Crear instancia del controlador
$controller = new TrabajadorController($conn);

// Obtener el ID desde la URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Confirmar solo si el ID es válido
if ($id > 0) {
    $controller->confirmar($id);
} else {
    // Opcional: podrías mostrar un mensaje de error o redirigir
    header("Location:". BASE_URL ."/vista/vista_trabajadores/vista_inicio/listaEspera.php");
    exit;
}

<?php
require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');
require_once(ROOT_PATH . '/controlador/controlador_trabajadores/controlador_inicio/TrabajadorController.php');

$controller = new TrabajadorController($conn);

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    $controller->confirmar($id);
}

header("Location: " . BASE_URL . "/vista/vista_trabajadores/vista_inicio/listaEspera.php");
exit;

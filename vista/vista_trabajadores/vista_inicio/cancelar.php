<?php
require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');
require_once(ROOT_PATH . '/modelo/modelo_trabajadores/inicio_trabajador/Trabajador.php');


if (!isset($_GET['id'])) {
    die("ID no recibido.");
}

$id = intval($_GET['id']);

$lista = new ListaEspera($conn);
$lista->cancelar($id);

header("Location: listaEspera.php");
exit;

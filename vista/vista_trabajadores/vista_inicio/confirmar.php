<?php
require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');
require_once(ROOT_PATH . '/modelo/modelo_trabajadores/inicio_trabajador/Trabajador.php');

$lista = new ListaEspera($conn);
$lista->confirmar($_GET['id']);

header("Location: listaEspera.php");
exit;

<?php
require_once("conexion.php");
require_once("controlador/TrabajadorControlador.php");

$controlador = new TrabajadorControlador($conn);
$controlador->mostrarTrabajadores();

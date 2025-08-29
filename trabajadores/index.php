<?php
require_once("conexion.php");
require_once("controlador/Trabajador_controlador.php");

$controlador = new TrabajadorControlador($conn);
$controlador->mostrarTrabajadores();

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "controlador/CitaControlador.php";
$controlador = new CitaControlador();

// Determinar acción desde GET
$accion = $_GET['accion'] ?? 'seleccionar';

// Inicializamos la variable $vista
$vista = 'seleccionar_servicios';

switch ($accion) {
    case 'seleccionar':
        $data = $controlador->seleccionar();
        extract($data);
        $vista = 'seleccionar_servicios';
        break;

    case 'guardar':
        $data = $controlador->guardar();
        extract($data);
        $vista = 'confirmar_cita';
        break;

    default:
        $data = $controlador->seleccionar();
        extract($data);
        $vista = 'seleccionar_servicios';
        break;
}

// Incluir el layout principal que cargará la vista correspondiente
include 'vista/layout.php';

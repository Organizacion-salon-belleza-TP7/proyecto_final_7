<?php

// Cargar conexión BD
require_once(__DIR__ . "/../../../modelo/BD.php");

// Cargar modelo
require_once(__DIR__ . "/../../../modelo/modelo_adm/ventas/modelo_ventas_historial.php");

// Crear instancia del modelo
$modelo = new ModeloHistorialVentas($conn);

/* ========================
   PETICIÓN AJAX DETALLE
   ======================== */
if (isset($_GET['detalle'])) {

    $id = intval($_GET['detalle']);
    $detalle = $modelo->obtenerDetalleVenta($id);

    $servicios = [];
    while ($d = $detalle->fetch_assoc()) {
        $servicios[] = $d;
    }

    header("Content-Type: application/json");
    echo json_encode([
        "servicios" => $servicios
    ]);
    exit;
}

/* ========================
   CARGAR DATOS PARA LA VISTA
   ======================== */
$ventas      = $modelo->obtenerVentas();
$topItems    = $modelo->topItemsVendidos();
$mediosStats = $modelo->mediosPagoEstadisticas();
$productos   = $modelo->ventasProductos();
$promos      = $modelo->ventasPromociones();

/* ========================
   CARGAR LA VISTA
   ======================== */
include_once(__DIR__ . "/../../../vista/vista_adm/venta/historial_ventas.php");

<?php

require_once(__DIR__ . "/../../../modelo/BD.php");
require_once(__DIR__ . "/../../../modelo/modelo_adm/dashboard/modelo_dashboard.php");

$modelo = new ModeloDashboard($conn);

// CONSULTAS AL MODELO
$data = [
    "ventas_por_dia" => $modelo->ventasPorDia()->fetch_all(MYSQLI_ASSOC),
    "top_servicios"  => $modelo->topServicios()->fetch_all(MYSQLI_ASSOC),
    "metodos_pago"   => $modelo->metodosPago()->fetch_all(MYSQLI_ASSOC),
    "productos"      => $modelo->ventasProductos()->fetch_all(MYSQLI_ASSOC),
    "promos"         => $modelo->ventasPromos()->fetch_all(MYSQLI_ASSOC),
    "total_general"  => $modelo->totalGeneral()->fetch_assoc(),
    "resumen_global" => $modelo->resumenGlobal()->fetch_all(MYSQLI_ASSOC)
];

// CARGAR LA VISTA
include_once(__DIR__ . "/../../../vista/vista_adm/dashboard/dashboard.php");

?>

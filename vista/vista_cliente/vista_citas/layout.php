<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');

require_once(ROOT_PATH . '/modelo/modelo_cliente/citas/CitaModelo.php');

require_once(ROOT_PATH . '/controlador/controladores_cliente/citas/CitaControlador.php');

$controlador = new CitaControlador($conn);



$modelo_citas = new CitaModelo($conn);
$traer_servicios = $modelo_citas->obtenerServicios();

$traer_combos = $modelo_citas->obtenerCombos();

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
        $controlador->guardar(); // ← YA MUESTRA confirmar_cita.php
        exit; // ← PARA QUE NO SIGA
        break;

    default:
        $data = $controlador->seleccionar();
        extract($data);
        $vista = 'seleccionar_servicios';
        break;
}


?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spa - Reservar Cita</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

    <header class="bg-pink-600 text-white py-4 shadow">
        <h1 class="text-3xl text-center font-bold">Centro de Belleza SPA</h1>
    </header>

    <main class="py-10">
        <?php
        // Aquí se incluye la vista según la acción
        if(isset($vista)) {
            include __DIR__ . "/$vista.php";
        }
        ?>
    </main>

    <footer class="bg-gray-200 text-center py-4 mt-10">
    </footer>
      <script src="<?= BASE_URL ?>/modelo/modelo_adm/servicios_combos/menu_desplegable.js"></script>


</body>
</html>

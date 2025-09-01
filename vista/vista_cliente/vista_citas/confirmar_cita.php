<link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.3/dist/tailwind.min.css" rel="stylesheet">

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../variable_global.php');


$detalles = $detalles ?? [];
$lugares = $lugares ?? [];
$id_cita = $id_cita ?? '';
$id_lugar = $id_lugar ?? '';
$fecha_cita = $fecha_cita ?? '';
?>

<div class="max-w-3xl mx-auto p-6 bg-white rounded-2xl shadow-lg mt-10 text-center">
    <h2 class="text-3xl font-bold text-pink-600 mb-6">¡Cita Registrada!</h2>

    <p class="text-lg mb-4">ID de la cita: <span class="font-semibold"><?= $id_cita ?></span></p>

    <h3 class="text-2xl font-semibold mb-2">Servicios y Combos Seleccionados:</h3>
    <?php if (!empty($detalles)): ?>
    <ul class="mb-4 text-left">
        <?php foreach($detalles as $item): ?>
            <li><?= $item['tipo'] ?>: <?= $item['nombre'] ?> - $<?= $item['precio'] ?></li>
        <?php endforeach; ?>
    </ul>
    <?php else: ?>
        <p>No se seleccionaron servicios ni combos.</p>
    <?php endif; ?>

    <p class="mb-4">Lugar: 
        <span class="font-semibold">
            <?= isset($id_lugar) && !empty($lugares) ? $lugares[array_search($id_lugar, array_column($lugares,'id_lugar'))]['nombre_lugar'] ?? '' : '' ?>
        </span>
    </p>

    <p class="mb-6">Fecha y Hora: <span class="font-semibold"><?= $fecha_cita ? date('d/m/Y H:i', strtotime($fecha_cita)) : '' ?></span></p>

    <a href="<?= BASE_URL ?>/vista/vista/cliente/vista_citas/layout.php?accion=seleccionar" class="bg-pink-500 text-white font-semibold px-6 py-3 rounded-lg shadow hover:bg-pink-600 transition-colors">
        Volver
    </a>
</div>

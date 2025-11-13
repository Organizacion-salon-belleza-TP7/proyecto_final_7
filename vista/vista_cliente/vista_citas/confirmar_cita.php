<?php
$id_cita = $GLOBALS['id_cita'] ?? 'N/A';
$fecha_cita = $GLOBALS['fecha_cita'] ?? '';
$id_lugar = $GLOBALS['id_lugar'] ?? '';
$detalles = $GLOBALS['detalles'] ?? [];
$lugares = $GLOBALS['lugares'] ?? [];

// Nombre del lugar
$lugar_nombre = 'No seleccionado';
foreach ($lugares as $l) {
    if ($l['id_lugar'] == $id_lugar) {
        $lugar_nombre = $l['nombre_lugar'];
        break;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cita Registrada - RoseSpa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-br from-pink-50 to-purple-50 min-h-screen">

    <!-- Fondo sutil -->
    <div class="fixed inset-0 -z-10 opacity-20">
        <img src="https://images.unsplash.com/photo-1600334089648-b0d9d3028eb2?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80" 
             class="w-full h-full object-cover" alt="spa">
    </div>

    <div class="container max-w-4xl mx-auto py-16 px-6">

        <!-- Título simple y bonito -->
        <div class="text-center mb-12">
            <h1 class="text-5xl font-bold text-pink-700 mb-2">¡Cita Registrada!</h1>
            <p class="text-xl text-gray-600">Tu turno ha sido reservado con éxito</p>
        </div>

        <!-- Tarjeta principal -->
        <div class="bg-white rounded-2xl shadow-xl border border-pink-200 overflow-hidden">

            <!-- Header rosa -->
            <div class="bg-gradient-to-r from-pink-500 to-purple-500 text-white p-8 text-center">
                <p class="text-lg font-medium">ID de tu cita</p>
                <p class="text-6xl font-bold mt-2">#<?= htmlspecialchars($id_cita) ?></p>
            </div>

            <!-- Contenido -->
            <div class="p-8 space-y-8">

                <!-- Servicios -->
                <?php if (!empty($detalles)): ?>
                <div>
                    <h3 class="text-2xl font-semibold text-gray-800 mb-4">Servicios seleccionados</h3>
                    <div class="space-y-4">
                        <?php foreach($detalles as $item): ?>
                        <div class="flex justify-between items-center bg-pink-50 rounded-xl p-5 border border-pink-200">
                            <div>
                                <p class="font-medium text-gray-700">
                                    <?= ucfirst(htmlspecialchars($item['tipo'])) ?>:
                                    <span class="font-semibold"><?= htmlspecialchars($item['nombre']) ?></span>
                                </p>
                            </div>
                            <p class="text-xl font-bold text-pink-600">
                                $<?= number_format($item['precio'] ?? $item['precio_servicio'] ?? 0, 0) ?>
                            </p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Lugar y fecha -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-purple-50 rounded-xl p-6 border border-purple-200 text-center">
                        <p class="text-sm text-purple-600 font-medium">Lugar</p>
                        <p class="text-2xl font-bold text-gray-800 mt-1"><?= htmlspecialchars($lugar_nombre) ?></p>
                    </div>
                    <div class="bg-blue-50 rounded-xl p-6 border border-blue-200 text-center">
                        <p class="text-sm text-blue-600 font-medium">Fecha y Hora</p>
                        <p class="text-2xl font-bold text-gray-800 mt-1">
                            <?= $fecha_cita ? date('d/m/Y \a \l\a\s H:i', strtotime($fecha_cita)) : 'No seleccionada' ?>
                        </p>
                    </div>
                </div>

                <!-- Botones -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center pt-6">
                    <a href="layout.php" 
                       class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-4 px-10 rounded-xl text-center transition">
                        Reservar otra cita
                    </a>

               <form action="<?= BASE_URL ?>/vista/vista_cliente/vista_venta/venta.php" method="POST">
    <input type="hidden" name="id_cita" value="<?= $id_cita ?>">
    <button type="submit" class="...">
        Ir a Pagar
    </button>
</form>
                </div>

            </div>
        </div>

        <!-- Mensaje final -->
        <div class="text-center mt-12">
            <p class="text-lg text-gray-600">Gracias por confiar en <span class="font-semibold text-pink-600">RoseSpa</span></p>
            <p class="text-4xl mt-4">Te esperamos pronto</p>
        </div>

    </div>
</body>
</html>
<?php
// === TUS VARIABLES (reemplaza con las tuyas reales) ===
$detalles = $GLOBALS['detalles'] ?? [];  // servicios y combos
$lugares  = $GLOBALS['lugares']  ?? [];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservar Cita - RoseSpa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .peer-checked\:bg-pink-100:checked + div { background-color: #fce7f3; border-color: #ec4899; }
    </style>
</head>
<body class="bg-gradient-to-br from-pink-50 via-purple-50 to-indigo-100 min-h-screen py-12">

    <div class="max-w-6xl mx-auto px-4">
        <h1 class="text-5xl font-black text-center text-pink-700 mb-10">Reserva tu Cita</h1>

        <form method="POST" action="layout.php?accion=guardar" class="bg-white rounded-3xl shadow-2xl p-10 border-4 border-pink-200">

            <!-- SERVICIOS -->
            <div class="mb-12">
                <h3 class="text-3xl font-bold mb-6 text-gray-800">Servicios</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($detalles as $item): ?>
                        <?php if ($item['tipo'] == 'servicio'): ?>
                            <label class="block cursor-pointer">
                                <input type="checkbox" name="servicios[]" value="<?= htmlspecialchars($item['id']) ?>" class="hidden peer">
                                <div class="border-2 border-gray-300 rounded-2xl p-6 shadow hover:shadow-xl transition-all 
                                            peer-checked:bg-pink-100 peer-checked:border-pink-500 peer-checked:scale-105">
                                    <h4 class="font-bold text-xl mb-2 text-gray-800"><?= htmlspecialchars($item['nombre']) ?></h4>
                                    <p class="text-pink-600 font-black text-2xl">$<?= number_format($item['precio_servicio'], 0) ?></p>
                                </div>
                            </label>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- COMBOS -->
            <div class="mb-12">
                <h3 class="text-3xl font-bold mb-6 text-gray-800">Combos</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($detalles as $item): ?>
                        <?php if ($item['tipo'] == 'combo'): ?>
                            <label class="block cursor-pointer">
                                <input type="checkbox" name="combos[]" value="<?= htmlspecialchars($item['id']) ?>" class="hidden peer">
                                <div class="border-2 border-gray-300 rounded-2xl p-6 shadow hover:shadow-xl transition-all 
                                            peer-checked:bg-pink-100 peer-checked:border-pink-500 peer-checked:scale-105">
                                    <h4 class="font-bold text-xl mb-2 text-gray-800"><?= htmlspecialchars($item['nombre']) ?></h4>
                                    <p class="text-pink-600 font-black text-2xl">$<?= number_format($item['precio'], 0) ?></p>
                                </div>
                            </label>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- FECHA Y HORA -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <div>
                    <label class="block text-xl font-semibold text-gray-700 mb-2">Fecha y hora</label>
                    <input type="datetime-local" name="fecha_hora" required
                           class="w-full border-2 border-pink-200 rounded-xl px-5 py-4 text-lg focus:outline-none focus:ring-4 focus:ring-pink-300">
                </div>

                <div>
                    <label class="block text-xl font-semibold text-gray-700 mb-2">Lugar</label>
                    <select name="id_lugar" required
                            class="w-full border-2 border-pink-200 rounded-xl px-5 py-4 text-lg focus:outline-none focus:ring-4 focus:ring-pink-300">
                        <option value="">Seleccione un lugar...</option>
                        <?php foreach ($lugares as $lugar): ?>
                            <option value="<?= $lugar['id_lugar'] ?>"><?= htmlspecialchars($lugar['nombre_lugar']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- BOTÓN -->
            <div class="text-center">
                <button type="submit"
                        class="bg-gradient-to-r from-pink-600 to-purple-600 hover:from-pink-700 hover:to-purple-700 
                               text-white font-black text-2xl px-16 py-6 rounded-2xl shadow-2xl transform hover:scale-105 transition-all">
                    Confirmar Cita
                </button>
            </div>
        </form>
    </div>
    
</body>
</html>
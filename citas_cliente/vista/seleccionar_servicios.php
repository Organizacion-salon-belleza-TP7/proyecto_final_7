<?php
$detalles = $detalles ?? [];
$lugares = $lugares ?? [];
?>

<div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-lg p-8">
    <h2 class="text-3xl font-bold mb-6 text-center text-pink-600">Reservar Cita</h2>

    <form method="POST" action="index.php?accion=guardar" class="space-y-6">
        <!-- Servicios -->
        <div>
            <h3 class="text-2xl font-semibold mb-4 text-gray-700">Servicios:</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <?php foreach ($detalles as $item): ?>
                    <?php if ($item['tipo'] == 'servicio'): ?>
                        <label class="block cursor-pointer">
                            <input type="checkbox" name="servicios[]" value="<?= htmlspecialchars($item['id']) ?>" class="hidden peer">
                            <div class="border border-gray-200 rounded-lg p-4 shadow hover:shadow-lg transition-all peer-checked:bg-pink-100 peer-checked:border-pink-500">
                                <h4 class="font-semibold text-lg mb-2"><?= htmlspecialchars($item['nombre']) ?></h4>
                                <p class="text-pink-600 font-bold text-lg">$<?= htmlspecialchars($item['precio']) ?></p>
                            </div>
                        </label>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Combos -->
        <div>
            <h3 class="text-2xl font-semibold mb-4 text-gray-700">Combos:</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <?php foreach ($detalles as $item): ?>
                    <?php if ($item['tipo'] == 'combo'): ?>
                        <label class="block cursor-pointer">
                            <input type="checkbox" name="combos[]" value="<?= htmlspecialchars($item['id']) ?>" class="hidden peer">
                            <div class="border border-gray-200 rounded-lg p-4 shadow hover:shadow-lg transition-all peer-checked:bg-pink-100 peer-checked:border-pink-500">
                                <h4 class="font-semibold text-lg mb-2"><?= htmlspecialchars($item['nombre']) ?></h4>
                                <p class="text-pink-600 font-bold text-lg">$<?= htmlspecialchars($item['precio']) ?></p>
                            </div>
                        </label>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Fecha y hora -->
        <div>
            <label class="block text-gray-700 font-medium mb-1">Fecha y hora:</label>
            <input type="datetime-local" name="fecha_hora" required
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-400">
        </div>

        <!-- Lugar -->
        <div>
            <label class="block text-gray-700 font-medium mb-1">Lugar:</label>
            <select name="id_lugar" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-400">
                <option value="">Seleccione...</option>
                <?php foreach ($lugares as $lugar): ?>
                    <option value="<?= htmlspecialchars($lugar['id_lugar']) ?>"><?= htmlspecialchars($lugar['nombre_lugar']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Botón -->
        <div class="text-center">
            <button type="submit"
                    class="bg-pink-500 text-white font-semibold px-6 py-3 rounded-lg shadow hover:bg-pink-600 transition-colors">
                Confirmar Cita
            </button>
        </div>
    </form>
</div>

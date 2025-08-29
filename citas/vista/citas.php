<?php
$citas = $citas ?? [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Citas</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">
<div class="max-w-7xl mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6 text-center text-pink-600">Listado de Citas</h1>

    <!-- Barra de búsqueda -->
    <div class="mb-4 flex gap-4 flex-col sm:flex-row">
        <input type="text" id="buscador" placeholder="Buscar por cliente, servicio o fecha..." class="px-4 py-2 border rounded-lg w-full focus:ring focus:ring-pink-300">
    </div>

    <div class="overflow-x-auto">
        <table class="w-full border-collapse bg-white shadow-md rounded-lg overflow-hidden" id="tablaCitas">
            <thead class="bg-pink-600 text-white">
                <tr>
                    <th class="px-4 py-2">Cliente</th>
                    <th class="px-4 py-2">Servicio</th>
                    <th class="px-4 py-2">Combo</th>
                    <th class="px-4 py-2">Fecha</th>
                    <th class="px-4 py-2">Activo</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($citas)): ?>
                    <?php foreach($citas as $index => $row): 
                        // Color alternado de filas
                        $colorFila = $index % 2 === 0 ? 'bg-pink-50' : 'bg-pink-100';
                        $hoverColor = 'hover:bg-pink-200';
                    ?>
                    <tr class="<?= $colorFila ?> <?= $hoverColor ?> border-b">
                        <td class="px-4 py-2"><?= htmlspecialchars($row['nombres']) ?></td>
                        <td class="px-4 py-2"><?= $row['nombre'] ?  htmlspecialchars($row['nombre']) : '-' ?></td>
                        <td class="px-4 py-2"><?= $row['nombre_combo'] ? htmlspecialchars($row['nombre_combo']) : '-' ?></td>
                        <td class="px-4 py-2 text-center"><?= date('d/m/Y H:i', strtotime($row['fecha_cita'])) ?></td>
                        <td class="px-4 py-2 text-center font-bold <?= $row['activo'] ? 'text-green-600' : 'text-red-600' ?>">
                            <?= $row['activo'] ? 'Sí' : 'No' ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center py-4 text-gray-500">No hay citas registradas</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Script para filtro de búsqueda -->
<script>
document.getElementById('buscador').addEventListener('keyup', function() {
    let filtro = this.value.toLowerCase();
    let filas = document.querySelectorAll('#tablaCitas tbody tr');

    filas.forEach(function(fila) {
        let texto = fila.textContent.toLowerCase();
        fila.style.display = texto.includes(filtro) ? '' : 'none';
    });
});
</script>
</body>
</html>

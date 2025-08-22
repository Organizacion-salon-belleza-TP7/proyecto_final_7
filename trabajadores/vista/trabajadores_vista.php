<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Trabajadores</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800 p-8">
    <div class="max-w-6xl mx-auto bg-white rounded-xl shadow p-6">
        <h1 class="text-3xl font-bold mb-6">Listado de Trabajadores</h1>

        <!-- Filtro -->
        <form method="GET" class="mb-4 flex">
            <input type="text" name="buscar" placeholder="Buscar por nombre" class="flex-grow border p-2 rounded-l">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-r hover:bg-blue-600">Buscar</button>
        </form>

        <!-- Tabla -->
        <div class="overflow-x-auto">
        <table class="min-w-full bg-white text-sm">
            <thead>
                <tr class="bg-gray-200 text-left">
                    <th class="border px-4 py-2">ID</th>
                    <th class="border px-4 py-2">Nombre</th>
                    <th class="border px-4 py-2">DNI</th>
                    <th class="border px-4 py-2">Tipo</th>
                    <th class="border px-4 py-2">Nivel</th>
                    <th class="border px-4 py-2">Activo</th>
                    <th class="border px-4 py-2">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($trabajadores)): ?>
                    <tr><td colspan="7" class="text-center p-4">No se encontraron trabajadores.</td></tr>
                <?php else: ?>
                    <?php foreach ($trabajadores as $trabajador): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="border px-4 py-2"><?= $trabajador['id_trabajador'] ?></td>
                            <td class="border px-4 py-2"><?= htmlspecialchars($trabajador['nombre_trabajador']) ?></td>
                            <td class="border px-4 py-2"><?= htmlspecialchars($trabajador['dni']) ?></td>
                            <td class="border px-4 py-2"><?= $trabajador['id_tipo_trabajador'] ?></td>
                            <td class="border px-4 py-2"><?= $trabajador['id_nivel_profesionalismo'] ?></td>
                            <td class="border px-4 py-2"><?= $trabajador['activo'] ? 'Sí' : 'No' ?></td>
                            <td class="border px-4 py-2 space-x-2">
                                <a href="editar_trabajador.php?id=<?= $trabajador['id_trabajador'] ?>" class="bg-yellow-400 text-white px-2 py-1 rounded hover:bg-yellow-500">Editar</a>
                                <a href="eliminar_trabajador.php?id=<?= $trabajador['id_trabajador'] ?>" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600" onclick="return confirm('¿Estás seguro de eliminar?');">Eliminar</a>
                                <a href="agregar_trabajador.php" class="inline-block bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 mb-4">+ Nuevo Trabajador</a>

                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        </div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Trabajadores</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-gray-100 to-gray-200 text-gray-800 p-8 min-h-screen">
    <div class="max-w-7xl mx-auto bg-white rounded-xl shadow-xl p-8">
        <h1 class="text-4xl font-bold mb-6 text-center text-blue-600">Listado de Trabajadores</h1>

        <!-- Filtro + Botón nuevo -->
        <div class="mb-6 flex flex-wrap justify-between items-center gap-4">
            <form method="GET" class="flex flex-grow max-w-md w-full">
                <input type="text" name="buscar" placeholder="Buscar por nombre o apellido"
                       class="flex-grow border border-gray-300 p-3 rounded-l-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                <button type="submit"
                        class="bg-blue-500 text-white px-5 py-3 rounded-r-lg hover:bg-blue-600 transition">Buscar</button>
            </form>

            <a href="agregar_trabajador.php"
               class="bg-green-500 text-white px-5 py-3 rounded-lg hover:bg-green-600 transition flex items-center gap-2">
                <span class="text-lg font-medium">+ Nuevo Trabajador</span>
            </a>
        </div>

        <!-- Tabla -->
        <div class="overflow-x-auto rounded-lg shadow">
            <table class="min-w-full text-sm text-left">
                <thead>
                    <tr class="bg-blue-100 text-blue-700 uppercase text-xs tracking-wider">
                        <th class="border px-4 py-3">Nombre</th>
                        <th class="border px-4 py-3">Apellido</th>
                        <th class="border px-4 py-3">DNI</th>
                        <th class="border px-4 py-3">Tipo</th>
                        <th class="border px-4 py-3">Nivel</th>
                        <th class="border px-4 py-3">Activo</th>
                        <th class="border px-4 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($trabajadores)): ?>
                        <tr>
                            <td colspan="7" class="text-center p-5 text-gray-500">No se encontraron trabajadores.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($trabajadores as $trabajador): ?>
                            <tr class="hover:bg-gray-100 transition">
                                <td class="border px-4 py-3"><?= htmlspecialchars($trabajador['nombre']) ?></td>
                                <td class="border px-4 py-3"><?= htmlspecialchars($trabajador['apellido']) ?></td>
                                <td class="border px-4 py-3"><?= htmlspecialchars($trabajador['dni']) ?></td>
                                <td class="border px-4 py-3"><?= htmlspecialchars($trabajador['tipo_trabajador']) ?></td>
                                <td class="border px-4 py-3"><?= htmlspecialchars($trabajador['nivel_profesionalismo']) ?></td>
                                <td class="border px-4 py-3"><?= $trabajador['activo'] ? 'Sí' : 'No' ?></td>
                                <td class="border px-4 py-3 flex gap-2">
                                    <a href="editar_trabajador.php?id=<?= $trabajador['id_trabajador'] ?>"
                                       class="bg-yellow-400 text-white px-3 py-1 rounded hover:bg-yellow-500 transition text-sm">Editar</a>
                                    <a href="eliminar_trabajador.php?id=<?= $trabajador['id_trabajador'] ?>"
                                       onclick="return confirm('¿Estás seguro de eliminar?');"
                                       class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition text-sm">Eliminar</a>
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

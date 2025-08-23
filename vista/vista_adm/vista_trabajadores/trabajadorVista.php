<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Trabajadores</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <h1 class="text-2xl font-bold mb-4">Lista de Trabajadores</h1>

    <table class="min-w-full bg-white rounded shadow">
        <thead>
            <tr class="bg-gray-200 text-left">
                <th class="p-3">ID</th>
                <th class="p-3">Nombre</th>
                <th class="p-3">DNI</th>
                <th class="p-3">Tipo</th>
                <th class="p-3">Nivel</th>
                <th class="p-3">Activo</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $trabajadores->fetch_assoc()): ?>
            <tr class="border-b">
                <td class="p-3"><?= htmlspecialchars($row['id_trabajador']) ?></td>
                <td class="p-3"><?= htmlspecialchars($row['nombre_trabajador']) ?></td>
                <td class="p-3"><?= htmlspecialchars($row['dni']) ?></td>
                <td class="p-3"><?= htmlspecialchars($row['id_tipo_trabajador']) ?></td>
                <td class="p-3"><?= htmlspecialchars($row['id_nivel_profesionalismo']) ?></td>
                <td class="p-3"><?= $row['activo'] ? "Sí" : "No" ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>

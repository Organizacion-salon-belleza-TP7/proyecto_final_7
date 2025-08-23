<?php
require_once("conexion.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST['nombre_trabajador'];
    $dni = $_POST['dni'];
    $tipo = $_POST['id_tipo_trabajador'];
    $nivel = $_POST['id_nivel_profesionalismo'];
    $activo = isset($_POST['activo']) ? 1 : 0;

    $stmt = $conn->prepare("INSERT INTO trabajadores (nombre_trabajador, dni, id_tipo_trabajador, id_nivel_profesionalismo, activo) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiii", $nombre, $dni, $tipo, $nivel, $activo);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit;
    } else {
        echo "Error al agregar: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Trabajador</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800 p-8">
    <div class="max-w-xl mx-auto bg-white rounded-xl shadow p-6">
        <h1 class="text-2xl font-bold mb-4">Agregar Nuevo Trabajador</h1>
        <form method="POST" class="space-y-4">
            <input type="text" name="nombre_trabajador" placeholder="Nombre" class="w-full border p-2 rounded" required>
            <input type="text" name="dni" placeholder="DNI" class="w-full border p-2 rounded" required>
            <input type="number" name="id_tipo_trabajador" placeholder="ID Tipo de Trabajador" class="w-full border p-2 rounded" required>
            <input type="number" name="id_nivel_profesionalismo" placeholder="ID Nivel Profesionalismo" class="w-full border p-2 rounded" required>

            <label class="inline-flex items-center">
                <input type="checkbox" name="activo" class="form-checkbox">
                <span class="ml-2">Activo</span>
            </label>

            <div class="flex gap-4 mt-4">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Guardar</button>
                <a href="index.php" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>

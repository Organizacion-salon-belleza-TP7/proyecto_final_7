<?php
require_once("conexion.php");

// Consultar tipos de trabajador
$tipos = [];
$resultadoTipos = $conn->query("SELECT id_tipo_trabajador, tipo_trabajador FROM tipo_trabajador");
if ($resultadoTipos) {
    while ($fila = $resultadoTipos->fetch_assoc()) {
        $tipos[] = $fila;
    }
}

// Consultar niveles de profesionalismo
$niveles = [];
$resultadoNiveles = $conn->query("SELECT id_nivel_profesionalismo, nivel_profesionalismo FROM nivel_profesionalismo");
if ($resultadoNiveles) {
    while ($fila = $resultadoNiveles->fetch_assoc()) {
        $niveles[] = $fila;
    }
}

// Guardar datos al enviar formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $dni = $_POST['dni'];
    $tipo = $_POST['id_tipo_trabajador'];
    $nivel = $_POST['id_nivel_profesionalismo'];
    $activo = isset($_POST['activo']) ? 1 : 0;

    $stmt = $conn->prepare("INSERT INTO trabajadores (nombre, apellido, dni, id_tipo_trabajador, id_nivel_profesionalismo, activo) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssiii", $nombre, $apellido, $dni, $tipo, $nivel, $activo);

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
<body class="bg-gradient-to-br from-gray-100 to-gray-200 min-h-screen p-8">
    <div class="max-w-xl mx-auto bg-white rounded-xl shadow-lg p-8">
        <h1 class="text-3xl font-bold text-center text-blue-600 mb-6">Agregar Nuevo Trabajador</h1>
        
        <form method="POST" class="space-y-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                <input type="text" name="nombre" class="w-full border border-gray-300 rounded p-3 shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Apellido</label>
                <input type="text" name="apellido" class="w-full border border-gray-300 rounded p-3 shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">DNI</label>
                <input type="text" name="dni" class="w-full border border-gray-300 rounded p-3 shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Trabajador</label>
                <select name="id_tipo_trabajador" class="w-full border border-gray-300 rounded p-3 shadow-sm" required>
                    <option value="" disabled selected>Seleccione tipo</option>
                    <?php foreach ($tipos as $tipo): ?>
                        <option value="<?= $tipo['id_tipo_trabajador'] ?>"><?= htmlspecialchars($tipo['tipo_trabajador']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nivel de Profesionalismo</label>
                <select name="id_nivel_profesionalismo" class="w-full border border-gray-300 rounded p-3 shadow-sm" required>
                    <option value="" disabled selected>Seleccione nivel</option>
                    <?php foreach ($niveles as $nivel): ?>
                        <option value="<?= $nivel['id_nivel_profesionalismo'] ?>"><?= htmlspecialchars($nivel['nivel_profesionalismo']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="activo" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                <label class="text-gray-700">Activo</label>
            </div>

            <div class="flex justify-between mt-6">
                <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded hover:bg-blue-700 transition">
                    Guardar
                </button>
                <a href="index.php" class="bg-gray-300 text-gray-800 px-6 py-3 rounded hover:bg-gray-400 transition">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</body>
</html>

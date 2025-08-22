<?php
require_once("conexion.php");

$id = $_GET['id'] ?? null;
if (!$id) {
    die("ID no válido.");
}

// Obtener datos actuales
$sql = "SELECT * FROM trabajadores WHERE id_trabajador = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();
$trabajador = $resultado->fetch_assoc();

if (!$trabajador) {
    die("Trabajador no encontrado.");
}

// Si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST['nombre_trabajador'];
    $dni = $_POST['dni'];
    $tipo = $_POST['id_tipo_trabajador'];
    $nivel = $_POST['id_nivel_profesionalismo'];
    $activo = isset($_POST['activo']) ? 1 : 0;

    $update = $conn->prepare("UPDATE trabajadores SET nombre_trabajador=?, dni=?, id_tipo_trabajador=?, id_nivel_profesionalismo=?, activo=? WHERE id_trabajador=?");
    $update->bind_param("ssiiii", $nombre, $dni, $tipo, $nivel, $activo, $id);
    if ($update->execute()) {
        header("Location: index.php");
        exit;
    } else {
        echo "Error al actualizar: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Trabajador</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800 p-8">
    <div class="max-w-xl mx-auto bg-white rounded-xl shadow p-6">
        <h1 class="text-2xl font-bold mb-4">Editar Trabajador</h1>
        <form method="POST" class="space-y-4">
            <input type="text" name="nombre_trabajador" value="<?= htmlspecialchars($trabajador['nombre_trabajador']) ?>" placeholder="Nombre" class="w-full border p-2 rounded" required>
            <input type="text" name="dni" value="<?= htmlspecialchars($trabajador['dni']) ?>" placeholder="DNI" class="w-full border p-2 rounded" required>
            <input type="number" name="id_tipo_trabajador" value="<?= $trabajador['id_tipo_trabajador'] ?>" placeholder="Tipo" class="w-full border p-2 rounded" required>
            <input type="number" name="id_nivel_profesionalismo" value="<?= $trabajador['id_nivel_profesionalismo'] ?>" placeholder="Nivel" class="w-full border p-2 rounded" required>
            
            <label class="inline-flex items-center">
                <input type="checkbox" name="activo" <?= $trabajador['activo'] ? 'checked' : '' ?> class="form-checkbox">
                <span class="ml-2">Activo</span>
            </label>

            <div class="flex gap-4 mt-4">
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Guardar</button>
                <a href="index.php" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>

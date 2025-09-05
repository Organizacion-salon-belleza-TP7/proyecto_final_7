<?php
require_once "../controlador/TrabajadorControlador.php";
require_once "../modelo/Conexion.php";

$controlador = new TrabajadorControlador();
$conexion = Conexion::getConexion();

$id = $_GET['id'] ?? null;
$trabajador = $id ? $controlador->ver($id) : null;

// Traer niveles profesionales
$result_niveles = $conexion->query("SELECT id_nivel_profesionalismo, nivel_profesionalismo FROM nivel_profesionalismo");
$niveles = $result_niveles->fetch_all(MYSQLI_ASSOC);

// Traer tipos de trabajador
$result_tipos = $conexion->query("SELECT id_tipo_trabajador, tipo_trabajador FROM tipo_trabajador");
$tipos = $result_tipos->fetch_all(MYSQLI_ASSOC);

// Guardar datos si se envía el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datos = [
        'nombre_trabajador' => $_POST['nombre_trabajador'],
        'apellido_trabajador' => $_POST['apellido_trabajador'],
        'dni' => $_POST['dni'],
        'id_tipo_trabajador' => $_POST['id_tipo_trabajador'],
        'id_nivel_profesionalismo' => $_POST['id_nivel_profesionalismo'],
        'activo' => isset($_POST['activo']) ? 1 : 0
    ];
    $controlador->guardar($datos, $id);
    header("Location: trabajadores_lista.php");
    exit;
}
?>

<h1><?= $id ? 'Editar' : 'Agregar' ?> Trabajador</h1>
<form method="POST">
    <label>Nombre:</label><br>
    <input type="text" name="nombre_trabajador" value="<?= $trabajador['nombre_trabajador'] ?? '' ?>" required><br>

    <label>Apellido:</label><br>
    <input type="text" name="apellido_trabajador" value="<?= $trabajador['apellido_trabajador'] ?? '' ?>" required><br>

    <label>DNI:</label><br>
    <input type="number" name="dni" value="<?= $trabajador['dni'] ?? '' ?>" required><br>

    <label>Tipo de Trabajador:</label><br>
    <select name="id_tipo_trabajador" required>
        <?php foreach($tipos as $tipo): ?>
            <option value="<?= $tipo['id_tipo_trabajador'] ?>" <?= ($trabajador['id_tipo_trabajador'] ?? '') == $tipo['id_tipo_trabajador'] ? 'selected' : '' ?>>
                <?= $tipo['tipo_trabajador'] ?>
            </option>
        <?php endforeach; ?>
    </select><br>

    <label>Nivel Profesional:</label><br>
    <select name="id_nivel_profesionalismo" required>
        <?php foreach($niveles as $nivel): ?>
            <option value="<?= $nivel['id_nivel_profesionalismo'] ?>" <?= ($trabajador['id_nivel_profesionalismo'] ?? '') == $nivel['id_nivel_profesionalismo'] ? 'selected' : '' ?>>
                <?= $nivel['nivel_profesionalismo'] ?>
            </option>
        <?php endforeach; ?>
    </select><br>

    <label>Activo:</label>
    <input type="checkbox" name="activo" <?= (!isset($trabajador['activo']) || $trabajador['activo']) ? 'checked' : '' ?>><br><br>

    <button type="submit"><?= $id ? 'Actualizar' : 'Agregar' ?></button>
    <a href="trabajadores_lista.php">Volver</a>
</form>

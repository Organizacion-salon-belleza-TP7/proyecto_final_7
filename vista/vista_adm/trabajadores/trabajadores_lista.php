<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');
require_once(ROOT_PATH . '/controlador/controladores_adm/trabajadores/TrabajadorControlador.php');

// Crear controlador pasando la conexión
$controlador = new TrabajadorControlador($conn);

// Eliminar si se recibe parámetro
if (isset($_GET['eliminar'])) {
    $controlador->borrar($_GET['eliminar']);
    header("Location:" . BASE_URL ."/vista/vista_adm/trabajadores/trabajadores_lista.php");
    exit;
}

// Obtener todos los trabajadores
$trabajadores = $controlador->listar();

// Traer tipos de trabajador (usa tu conexión $conn)
$result_tipos = $conn->query("SELECT id_tipo_trabajador, tipo_trabajador FROM tipo_trabajador");
$tipos = [];
while($row = $result_tipos->fetch_assoc()) {
    $tipos[$row['id_tipo_trabajador']] = $row['tipo_trabajador'];
}

// Traer niveles profesionales (usa tu conexión $conn)
$result_niveles = $conn->query("SELECT id_nivel_profesionalismo, nivel_profesionalismo FROM nivel_profesionalismo");
$niveles = [];
while($row = $result_niveles->fetch_assoc()) {
    $niveles[$row['id_nivel_profesionalismo']] = $row['nivel_profesionalismo'];
}
?>

<h1>Lista de Trabajadores</h1>
<a href="trabajador_form.php">Agregar Trabajador</a>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Apellido</th>
        <th>DNI</th>
        <th>Tipo de Trabajador</th>
        <th>Nivel Profesional</th>
        <th>Activo</th>
        <th>Acciones</th>
    </tr>
    <?php foreach($trabajadores as $trabajador): ?>
    <tr>
        <td><?= $trabajador['id_trabajador'] ?></td>
        <td><?= $trabajador['nombre_trabajador'] ?></td>
        <td><?= $trabajador['apellido_trabajador'] ?></td>
        <td><?= $trabajador['dni'] ?></td>
        <td><?= $tipos[$trabajador['id_tipo_trabajador']] ?? 'Sin definir' ?></td>
        <td><?= $niveles[$trabajador['id_nivel_profesionalismo']] ?? 'Sin definir' ?></td>
        <td><?= $trabajador['activo'] ? 'Sí' : 'No' ?></td>
        <td>
            <a href="<?= BASE_URL ?>/vista/vista_adm/trabajadores/trabajador_form.php?id=<?= $trabajador['id_trabajador'] ?>">Editar</a> | 
            <a href="<?= BASE_URL ?>/vista/vista_adm/trabajadores/trabajadores_lista.php?eliminar=<?= $trabajador['id_trabajador'] ?>" onclick="return confirm('¿Desea eliminar este trabajador?')">Eliminar</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
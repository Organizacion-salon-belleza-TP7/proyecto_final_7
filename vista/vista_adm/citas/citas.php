<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');
require_once(ROOT_PATH . '/modelo/modelo_adm/modelo_citas/CitasModelo.php');

// Crear conexión y modelo
$citas_modelo = new CitasModelo($conn);
$resultado_citas = $citas_modelo->listar();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Citas</title>
</head>
<body>
    <!-- Menú de navegación -->
    <div>
        <a href="<?= BASE_URL ?>/vista/vista_adm/servicios_combos/vista_inicio_adm.php">Servicios y Combos</a>
        <a href="<?= BASE_URL ?>/vista/vista_adm/inventario/vista_inventario.php">Productos</a>
        <a href="">Ventas y Compras</a>
        <a href="">Proveedores</a>
        <a href="<?= BASE_URL ?>/vista/vista_adm/vista_logouts/vista_logouts_adm.php">Logeos y Movimientos</a>
        <a href="<?= BASE_URL ?>/vista/vista_adm/citas/citas.php">Citas</a>
        <a href="<?= BASE_URL ?>/controlador/controladores_adm/controlador_logout/controlador_logout.php?logout=vista_citas" class="logout">Cerrar sesión</a>
    </div>

    <h1>Citas</h1>
    <?php
    if (!empty($resultado_citas)) {
        echo "<table border='1'>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Servicio</th>
                        <th>Combo</th>
                        <th>Fecha</th>
                        <th>Activo</th>
                        <th>Detalle</th>
                    </tr>
                </thead>
                <tbody>";
        foreach ($resultado_citas as $row) {
            echo "<tr>
                    <td>{$row['id_cita']}</td>
                    <td>{$row['nombre']}</td>
                    <td>" . ($row['nombre'] ?? '-') . "</td>
                    <td>" . ($row['nombre_combo'] ?? '-') . "</td>
                    <td>{$row['fecha_cita']}</td>
                    <td>" . ($row['activo'] ? 'Sí' : 'No') . "</td>
                    <td><a href='" . BASE_URL . "/controlador/controladores_adm/controlador_citas/CitasControlador.php?id={$row['id_cita']}&detalle=vista_citas'>Detalle</a></td>
                </tr>";
        }
        echo "</tbody></table>";
        echo "<a href='" . BASE_URL . "/controlador/controladores_adm/controlador_citas/CitasControlador.php?agregar=vista_citas' class='add-btn'>Agendar Cita</a>";
    } else {
        echo "<p>No hay citas registradas</p>";
    }
    ?>
</body>
</html>

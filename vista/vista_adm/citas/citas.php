<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');
require_once(ROOT_PATH . '/modelo/modelo_adm/modelo_citas/CitasModelo.php');

// Crear conexión y modelo directamente aquí
$citas_modelo = new CitasModelo($conn);
$resultado_citas = $citas_modelo->listar();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Citas</title>
  <style>
    table { border-collapse: collapse; width: 100%; }
    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
    th { background-color: #f2f2f2; }
  </style>
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
        echo "<table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Servicios</th>
                        <th>Combos</th>
                        <th>Fecha</th>
                        <th>Activo</th>
                        <th>Detalle</th>
                    </tr>
                </thead>
                <tbody>";
        foreach ($resultado_citas as $row) {
            echo "<tr>
                    <td>{$row['id_cita']}</td>
                    <td>" . ($row['nombre_cliente'] ?? 'No asignado') . "</td>
                    <td>" . ($row['servicios'] ?? '-') . "</td>
                    <td>" . ($row['combos'] ?? '-') . "</td>
                    <td>{$row['fecha_cita']}</td>
                    <td>" . ($row['activo'] ? 'Sí' : 'No') . "</td>
                    <td><a href='" . BASE_URL . "/controlador/controladores_adm/controlador_citas/CitasControlador.php?detalle=true&id={$row['id_cita']}'>Ver Detalle</a></td>
                </tr>";
        }
        echo "</tbody></table>";
        
    } else {
        echo "<p>No hay citas registradas</p>";
    }
    ?>
</body>
</html>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Los datos vienen del controlador
$detalle_cita = $detalle_cita; // Variable que pasa el controlador
$cita = $detalle_cita[0]; // Tomar la primera fila para datos generales
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Detalle de Cita #<?= $cita['id_cita'] ?></title>
  <style>
    .detalle-container { max-width: 800px; margin: 20px auto; }
    .info-section { background: #f9f9f9; padding: 15px; margin: 10px 0; border-radius: 5px; }
    .servicio-item, .combo-item { padding: 5px; border-bottom: 1px solid #eee; }
    .volver-btn { display: inline-block; margin: 10px 0; padding: 10px; background: #6c757d; color: white; text-decoration: none; }
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

    <div class="detalle-container">
        <h1>Detalle de Cita #<?= $cita['id_cita'] ?></h1>
        
        <div class="info-section">
            <h3>Información General</h3>
            <p><strong>Cliente:</strong> <?= $cita['nombre_cliente'] ?? 'No asignado' ?></p>
            <p><strong>Fecha:</strong> <?= $cita['fecha_cita'] ?></p>
            <p><strong>ID Lugar:</strong> <?= $cita['id_lugar'] ?? 'No especificado' ?></p>
            <p><strong>Estado:</strong> <?= $cita['activo'] ? 'Activa' : 'Inactiva' ?></p>
            <p><strong>Hash Identificación:</strong> <?= $cita['hash_identificacion'] ?></p>
        </div>

        <div class="info-section">
            <h3>Servicios Contratados</h3>
            <?php
            $servicios = array_filter($detalle_cita, function($item) {
                return !empty($item['id_servicios']);
            });
            
            if (!empty($servicios)) {
                foreach ($servicios as $servicio) {
                    if (!empty($servicio['servicio_nombre'])) {
                        echo "<div class='servicio-item'>";
                        echo "<strong>{$servicio['servicio_nombre']}</strong>";
                        if (isset($servicio['servicio_precio'])) {
                            echo " - $" . $servicio['servicio_precio'];
                        }
                        echo "</div>";
                    }
                }
            } else {
                echo "<p>No hay servicios contratados</p>";
            }
            ?>
        </div>

        <div class="info-section">
            <h3>Combos Contratados</h3>
            <?php
            $combos = array_filter($detalle_cita, function($item) {
                return !empty($item['id_combos']);
            });
            
            if (!empty($combos)) {
                foreach ($combos as $combo) {
                    if (!empty($combo['combo_nombre'])) {
                        echo "<div class='combo-item'>";
                        echo "<strong>{$combo['combo_nombre']}</strong>";
                        if (isset($combo['combo_precio'])) {
                            echo " - $" . $combo['combo_precio'];
                        }
                        echo "</div>";
                    }
                }
            } else {
                echo "<p>No hay combos contratados</p>";
            }
            ?>
        </div>

        <a href="<?= BASE_URL ?>/vista/vista_adm/citas/citas.php" class="volver-btn">Volver a la lista</a>
    </div>
</body>
</html>
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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detalle Cita #<?= $cita['id_cita'] ?> - RoseSpa</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    :root {
      --bg: #1e1e2f;
      --bg-sidebar: #2a2a3d;
      --primary: #ff6b9d;
      --primary-dark: #e05585;
      --text: #f1f1f1;
      --text-muted: #aaa;
      --card: #2e2e44;
      --danger: #e74c3c;
      --warning: #f39c12;
      --success: #27ae60;
      --shadow: 0 4px 12px rgba(0,0,0,0.3);
    }
    *{margin:0;padding:0;box-sizing:border-box;}
    body{
      font-family: 'Segoe UI', sans-serif;
      background: url('../../../imagenes/lugares/istockphoto-1856117770-612x612.jpg') no-repeat center center fixed;
      background-size: cover;
      color: var(--text);
      display: flex;
      min-height: 100vh;
      position: relative;
      z-index: 1;
    }
    body::before {
      content: "";
      position: fixed;
      top:0; left:0; right:0; bottom:0;
      background: rgba(0,0,0,0.5);
      z-index: -1;
    }

    /* Sidebar */
    .sidebar{
      width: 240px;
      background: rgba(42,42,61,0.9);
      padding: 20px;
      display:flex;
      flex-direction:column;
      box-shadow: var(--shadow);
      position: fixed;
      top:0;left:0;bottom:0;
      transition: transform .3s ease;
      z-index: 1000;
    }
    .sidebar h2{
      color: var(--primary);
      margin-bottom: 30px;
      text-align: center;
    }
    .sidebar a{
      display:flex;
      align-items:center;
      gap:10px;
      color: var(--text);
      text-decoration:none;
      padding:12px;
      border-radius:6px;
      margin-bottom:6px;
      transition:.3s;
    }
    .sidebar a:hover{
      background: var(--primary);
      color:#fff;
    }
    .sidebar.hidden { transform: translateX(-100%); }

    /* Toggle */
    .toggle-btn{
      position: fixed;
      top: 20px;
      left: 20px;
      background: rgba(255, 107, 157, 0.6);
      color:#fff;
      border:none;
      padding:10px 14px;
      font-size:1.4rem;
      border-radius:8px;
      cursor:pointer;
      z-index:1100;
      transition:.3s;
      box-shadow: var(--shadow);
    }
    .toggle-btn:hover{ background: rgba(224, 85, 133, 0.8); }

    /* Content */
    .content{
      margin-left: 240px;
      flex:1;
      padding:30px;
      transition: margin-left .3s ease;
      width: 100%;
    }
    .content.expanded{ margin-left: 0; }
    h1{
      font-size:2rem;
      margin-bottom:25px;
      color: var(--primary);
      text-shadow: 2px 2px 6px rgba(0,0,0,0.6);
      text-align: center;
    }

    /* Detalle Card */
    .detalle-card{
      background: rgba(46,46,68,0.9);
      padding: 30px;
      border-radius: 12px;
      box-shadow: var(--shadow);
      max-width: 800px;
      margin: 0 auto;
    }

    .info-section{
      background: rgba(37,37,56,0.9);
      padding: 20px;
      border-radius: 10px;
      margin-bottom: 20px;
      border-left: 4px solid var(--primary);
    }
    .info-section h3{
      color: var(--primary);
      margin-bottom: 15px;
      font-size: 1.3rem;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .info-section p{
      margin-bottom: 10px;
      font-size: 1rem;
      line-height: 1.5;
    }
    .info-section strong{
      color: var(--primary);
    }

    .item-list{
      display: flex;
      flex-direction: column;
      gap: 8px;
    }
    .item{
      background: rgba(255,255,255,0.05);
      padding: 10px 14px;
      border-radius: 6px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: 0.95rem;
    }
    .item strong{
      color: #fff;
    }

    .empty{
      color: var(--text-muted);
      font-style: italic;
      text-align: center;
      padding: 20px;
    }

    /* Estado */
    .estado-activa{
      color: var(--success);
      font-weight: 600;
    }
    .estado-inactiva{
      color: var(--danger);
      font-weight: 600;
    }

    /* Botón Volver */
    .btn-volver{
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 12px 24px;
      background: #555;
      color: #fff;
      text-decoration: none;
      border-radius: 8px;
      font-weight: 600;
      transition: all 0.3s;
      box-shadow: var(--shadow);
      margin-top: 20px;
    }
    .btn-volver:hover{
      background: #666;
      transform: translateY(-2px);
    }
  </style>
</head>
<body>

  <!-- Toggle -->
  <button class="toggle-btn" onclick="toggleSidebar()">
    <i class="fas fa-bars"></i>
  </button>

  <!-- Sidebar -->
  <div class="sidebar" id="sidebar">
    <h2 class="titulo_menu">RoseSpa</h2>
    <a href="<?= BASE_URL ?>/vista/vista_adm/servicios_combos/vista_inicio_adm.php"><i class="fas fa-spa"></i> Servicios y Combos</a>
    <a href="<?= BASE_URL ?>/vista/vista_adm/inventario/InventarioVista.php"><i class="fas fa-boxes"></i> Productos</a>
    <a href="<?= BASE_URL ?>/vista/vista_adm/venta/vista_medios_pagos.php"><i class="fas fa-money-check-alt"></i> Medios de pago</a>
    <a href="<?= BASE_URL ?>/vista/vista_adm/lugares/lugares.php"><i class="fas fa-map-marker-alt"></i> Lugares</a>
    <a href="<?= BASE_URL ?>/vista/vista_adm/proveedores/vista_proveedores.php"><i class="fas fa-truck"></i> Proveedores</a>
    <a href="<?= BASE_URL ?>/vista/vista_adm/trabajadores/trabajadores_lista.php"><i class="fas fa-user-tie"></i> Trabajadores</a>
    <a href="<?= BASE_URL ?>/vista/vista_adm/clientes/clientes_lista.php"><i class="fas fa-users"></i> Clientes</a>
    <a href="<?= BASE_URL ?>/vista/vista_adm/vista_logouts/vista_logouts_adm.php"><i class="fas fa-history"></i> Logeos y Movimientos</a>
    <a href="<?= BASE_URL ?>/vista/vista_adm/citas/citas.php"><i class="fas fa-calendar-check"></i> Citas</a>
    <a href="<?= BASE_URL ?>/vista/vista_adm/promociones/vista_promociones.php"><i class="fas fa-tag"></i> Promociones</a>
    <a href="<?= BASE_URL ?>/controlador/controladores_adm/ventas/controlador_ventas_historial.php"><i class="fas fa-cash-register"></i> Historial de compras</a>
    <a href="<?= BASE_URL ?>/controlador/controladores_adm/controlador_logout/controlador_logout.php?logout=vista_inicio_adm"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a>
  </div>

  <!-- Content -->
  <div class="content" id="content">
    <h1>Detalle de Cita #<?= $cita['id_cita'] ?></h1>

    <div class="detalle-card">

      <!-- Información General -->
      <div class="info-section">
        <h3>Información General</h3>
        <p><strong>Cliente:</strong> <?= htmlspecialchars($cita['nombre_cliente'] ?? 'No asignado') ?></p>
        <p><strong>Fecha:</strong> <?= date('d/m/Y \a \l\a\s H:i', strtotime($cita['fecha_cita'])) ?></p>
        <p><strong>Lugar:</strong> <?= htmlspecialchars($cita['id_lugar'] ?? 'No especificado') ?></p>
        <p><strong>Estado:</strong> 
          <span class="<?= $cita['activo'] ? 'estado-activa' : 'estado-inactiva' ?>">
            <?= $cita['activo'] ? 'Activa' : 'Inactiva' ?>
          </span>
        </p>
      </div>

      <!-- Servicios -->
      <div class="info-section">
        <h3>Servicios Contratados</h3>
        <?php
        $servicios = array_filter($detalle_cita, fn($item) => !empty($item['id_servicios']));
        if (!empty($servicios)):
        ?>
          <div class="item-list">
            <?php foreach ($servicios as $servicio): ?>
              <?php if (!empty($servicio['servicio_nombre'])): ?>
                <div class="item">
                  <strong><?= htmlspecialchars($servicio['servicio_nombre']) ?></strong>
                  <?php if (isset($servicio['servicio_precio'])): ?>
                    <span>$<?= number_format($servicio['servicio_precio'], 2) ?></span>
                  <?php endif; ?>
                </div>
              <?php endif; ?>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <p class="empty">No hay servicios contratados</p>
        <?php endif; ?>
      </div>

      <!-- Combos -->
      <div class="info-section">
        <h3>Combos Contratados</h3>
        <?php
        $combos = array_filter($detalle_cita, fn($item) => !empty($item['id_combos']));
        if (!empty($combos)):
        ?>
          <div class="item-list">
            <?php foreach ($combos as $combo): ?>
              <?php if (!empty($combo['combo_nombre'])): ?>
                <div class="item">
                  <strong><?= htmlspecialchars($combo['combo_nombre']) ?></strong>
                  <?php if (isset($combo['combo_precio'])): ?>
                    <span>$<?= number_format($combo['combo_precio'], 2) ?></span>
                  <?php endif; ?>
                </div>
              <?php endif; ?>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <p class="empty">No hay combos contratados</p>
        <?php endif; ?>
      </div>

      <!-- Botón Volver -->
      <div style="text-align: center;">
        <a href="<?= BASE_URL ?>/vista/vista_adm/citas/citas.php" class="btn-volver">
          Volver a la Lista
        </a>
      </div>

    </div>
  </div>

    <div class="detalle-container">
        <h1>Detalle de Cita #<?= $cita['id_cita'] ?></h1>
        
        <div class="info-section">
            <h3>Información General</h3>
            <p><strong>Cliente:</strong> <?= $cita['nombre_cliente'] ?? 'No asignado' ?></p>
            <p><strong>Fecha:</strong> <?= $cita['fecha_cita'] ?></p>
            <p><strong>ID Lugar:</strong> <?= $cita['id_lugar'] ?? 'No especificado' ?></p>
            <p><strong>Estado:</strong> <?= $cita['activo'] ? 'Activa' : 'Inactiva' ?></p>

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
        <script src="<?= BASE_URL ?>/modelo/modelo_adm/servicios_combos/menu_desplegable.js"></script>
        
    </div>
</body>
</html>
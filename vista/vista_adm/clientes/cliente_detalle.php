<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');
require_once(ROOT_PATH . '/controlador/controladores_adm/clientes/ClienteControlador.php');

$controlador = new ClienteControlador($conn);
$id = $_GET['id'] ?? 0;
$datos = $controlador->detalle($id);

if (!$datos['cliente']) {
    die("<h2 style='color:red; text-align:center; margin:50px;'>Cliente no encontrado</h2>");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detalle de Cliente - RoseSpa</title>
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
      background: rgba(0,0,0,0.6);
      z-index: -1;
    }

    /* Sidebar igual que en lugares.php */
    .sidebar{
      width: 240px;
      background: rgba(42,42,61,0.95);
      padding: 20px;
      display:flex;
      flex-direction:column;
      box-shadow: var(--shadow);
      position: fixed;
      top:0;left:0;bottom:0;
      transition: transform .3s ease;
      z-index: 1000;
      overflow-y: auto;
    }
    .sidebar h2{
      color: var(--primary);
      margin-bottom: 30px;
      text-align: center;
      font-size: 1.8rem;
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

    .toggle-btn{
      position: fixed;
      top: 20px;
      left: 20px;
      background: rgba(255, 107, 157, 0.7);
      color:#fff;
      border:none;
      padding:10px 14px;
      font-size:1.4rem;
      border-radius:8px;
      cursor:pointer;
      z-index:1100;
      box-shadow: var(--shadow);
      transition:.3s;
    }
    .toggle-btn:hover{ background: rgba(224, 85, 133, 0.9); }

    .content{
      margin-left: 240px;
      flex:1;
      padding:30px;
      transition: margin-left .3s ease;
    }
    .content.expanded{ margin-left: 0; }

    h1{
      font-size:2.5rem;
      margin-bottom:20px;
      color: var(--primary);
      text-shadow: 2px 2px 8px rgba(0,0,0,0.7);
      text-align: center;
    }

    .card{
      background: rgba(46,46,68,0.95);
      padding: 25px;
      border-radius: 12px;
      box-shadow: var(--shadow);
      margin-bottom: 25px;
      border-left: 5px solid var(--primary);
    }
    .card h2{
      color: var(--primary);
      margin-bottom: 15px;
      font-size: 1.6rem;
      border-bottom: 2px solid var(--primary);
      padding-bottom: 8px;
    }
    .info-grid{
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 15px;
      margin: 15px 0;
    }
    .info-item{
      background: rgba(255,255,255,0.05);
      padding: 12px;
      border-radius: 8px;
    }
    .info-item b{
      color: var(--primary);
    }

    .servicios-lista{
      list-style: none;
    }
    .servicios-lista li{
      background: rgba(255,107,157,0.15);
      padding: 12px;
      border-radius: 8px;
      margin: 8px 0;
      border-left: 4px solid var(--primary);
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .servicios-lista li i{
      color: var(--primary);
    }

    .btn-volver{
      display: inline-block;
      padding: 12px 24px;
      background: var(--primary);
      color: white;
      text-decoration: none;
      border-radius: 8px;
      font-weight: 600;
      transition: .3s;
      box-shadow: var(--shadow);
    }
    .btn-volver:hover{
      background: var(--primary-dark);
      transform: translateY(-2px);
    }

    .puntos-destacado{
      font-size: 2rem;
      color: #f1c40f;
      text-shadow: 0 0 10px rgba(241,196,15,0.5);
    }
  </style>
</head>
<body>

  <button class="toggle-btn" onclick="toggleSidebar()">
    <i class="fas fa-bars"></i>
  </button>

  <!-- MENÚ LATERAL IGUAL QUE EN LUGARES -->
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

  <div class="content" id="content">
    <h1>Detalle de Cliente</h1>

    <!-- DATOS PERSONALES -->
    <div class="card">
      <h2>Datos Personales</h2>
      <div class="info-grid">
        <div class="info-item"><b>Nombre:</b> <?= htmlspecialchars($datos['cliente']['nombre'] . ' ' . $datos['cliente']['apellido']) ?></div>
        <div class="info-item"><b>DNI:</b> <?= htmlspecialchars($datos['cliente']['dni']) ?></div>
        <div class="info-item"><b>Alergias:</b> <?= htmlspecialchars($datos['cliente']['alergias'] ?: 'Ninguna') ?></div>
        <div class="info-item"><b>Fecha Nac.:</b> <?= date('d/m/Y', strtotime($datos['cliente']['fecha_nacimiento'])) ?></div>
      </div>
    </div>

    <!-- PUNTOS -->
    <div class="card">
      <h2>Puntos y Descuentos</h2>
      <div style="text-align:center; padding:20px;">
        <p class="puntos-destacado"><?= $datos['puntos']['puntos_acumulados'] ?? 0 ?> puntos</p>
        <p style="font-size:1.2rem; color:#f1c40f;">
          Descuento actual: <strong><?= $datos['puntos']['descuento'] ?? 0 ?>%</strong>
        </p>
      </div>
    </div>

    <!-- SERVICIOS CONTRATADOS -->
    <div class="card">
      <h2>Servicios Contratados (<?= count($datos['servicios']) ?>)</h2>
      <?php if (!empty($datos['servicios'])): ?>
        <ul class="servicios-lista">
          <?php foreach ($datos['servicios'] as $s): ?>
            <li>
              <span><i class="fas fa-scissors"></i> <?= htmlspecialchars($s['servicio']) ?> - <?= htmlspecialchars($s['descripcion']) ?></span>
              <small style="color:#ff6b9d;">Fecha: <?= date('d/m/Y H:i', strtotime($s['fecha_cita'])) ?></small>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php else: ?>
        <p style="text-align:center; color:var(--text-muted); padding:20px;">Aún no ha contratado servicios.</p>
      <?php endif; ?>
    </div>

    <div style="text-align:center; margin-top:30px;">
      <a href="<?= BASE_URL ?>/vista/vista_adm/clientes/clientes_lista.php" class="btn-volver">
        Volver a Lista de Clientes
      </a>
    </div>
  </div>

  <script>
    function toggleSidebar() {
      document.getElementById('sidebar').classList.toggle('hidden');
      document.getElementById('content').classList.toggle('expanded');
    }
  </script>
</body>
</html>
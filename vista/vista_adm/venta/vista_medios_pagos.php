<?php
require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');
require_once(ROOT_PATH . '/modelo/modelo_adm/modelo_venta/modelo_venta.php');

$modelo_venta = new modelo_venta($conn);
$funcion_traer_medios_pagos = $modelo_venta->mostrar_medios_pago();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Medios de Pago - RoseSpa</title>
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
      background: rgba(0,0,0,0.65);
      z-index: -1;
    }

    /* === SIDEBAR === */
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
      margin-bottom:30px;
      color: var(--primary);
      text-shadow: 2px 2px 8px rgba(0,0,0,0.7);
      text-align: center;
    }

    /* === TABLA MODERNA === */
    .table-container {
      background: rgba(46,46,68,0.95);
      border-radius: 12px;
      overflow: hidden;
      box-shadow: var(--shadow);
      margin-bottom: 30px;
    }
    table{
      width:100%;
      border-collapse:collapse;
    }
    th{
      background: var(--primary-dark);
      color:#fff;
      padding:16px;
      text-align:center;
      font-weight:600;
    }
    td{
      padding:14px;
      text-align:center;
      border-bottom: 1px solid rgba(255,255,255,0.1);
    }
    tr:nth-child(even){
      background: rgba(37,37,56,0.7);
    }
    tr:hover{
      background: rgba(255,107,157,0.15);
    }

    /* === BOTONES === */
    .btn{
      padding:8px 14px;
      border-radius:6px;
      font-size:0.9rem;
      font-weight:600;
      text-decoration:none;
      margin:0 4px;
      display:inline-block;
      transition:.3s;
    }
    .btn-edit{
      background: var(--warning);
      color:#fff;
    }
    .btn-delete{
      background: var(--danger);
      color:#fff;
    }
    .btn:hover{opacity:0.9; transform:translateY(-1px);}

    .add-btn{
      display:inline-block;
      padding:12px 24px;
      background: var(--primary);
      color:#fff;
      text-decoration:none;
      border-radius:8px;
      font-weight:600;
      box-shadow: var(--shadow);
      transition:.3s;
      margin-top: 10px;
    }
    .add-btn:hover{
      background: var(--primary-dark);
      transform:translateY(-2px);
    }

    .status-activo{
      color: var(--success);
      font-weight: bold;
    }
    .status-inactivo{
      color: var(--danger);
      font-weight: bold;
    }
  </style>
</head>
<body>

  <button class="toggle-btn" onclick="toggleSidebar()">
    <i class="fas fa-bars"></i>
  </button>

  <!-- MENÚ LATERAL -->
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
    <a href="<?= BASE_URL ?>/vista/vista_adm/venta/historial_ventas.php"><i class="fas fa-cash-register"></i> Historial de compras</a>
    <a href="<?= BASE_URL ?>/controlador/controladores_adm/controlador_logout/controlador_logout.php?logout=vista_inicio_adm"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a>
  </div>

  <div class="content" id="content">
    <h1>Medios de Pago</h1>

    <?php if ($funcion_traer_medios_pagos && $funcion_traer_medios_pagos->num_rows > 0): ?>
      <div class="table-container">
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Método de Pago</th>
              <th>Incremento</th>
              <th>Decremento</th>
              <th>Activo</th>
              <th>Modificar</th>
              <th>Dar de baja</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = $funcion_traer_medios_pagos->fetch_assoc()): ?>
              <tr>
                <td>#<?= $row['id_metodo_pago'] ?></td>
                <td><strong><?= htmlspecialchars($row['metodo_pago']) ?></strong></td>
                <td>
                  <?php if ($row['incremento'] !== null): ?>
                    <span style="color:#f1c40f;">+<?= $row['incremento'] ?>%</span>
                  <?php else: echo "-"; endif; ?>
                </td>
                <td>
                  <?php if ($row['decremento'] !== null): ?>
                    <span style="color:#27ae60;">-<?= $row['decremento'] ?>%</span>
                  <?php else: echo "-"; endif; ?>
                </td>
                <td>
                  <?php if ($row['activo'] == 1): ?>
                    <span class="status-activo">Activo</span>
                  <?php else: ?>
                    <span class="status-inactivo">Inactivo</span>
                  <?php endif; ?>
                </td>
                <td>
                  <a href="<?= BASE_URL ?>/controlador/controladores_adm/controlador_venta/controlador_venta.php?id=<?= $row['id_metodo_pago'] ?>&modificar_medios_pagos=vista_medios_pagos" 
                     class="btn btn-edit">
                     Modificar
                  </a>
                </td>
                <td>
                  <a href="<?= BASE_URL ?>/controlador/controladores_adm/controlador_venta/controlador_venta.php?id=<?= $row['id_metodo_pago'] ?>&dar_baja_medio_pago=vista_medios_pagos" 
                     class="btn btn-delete"
                     onclick="return confirm('¿Dar de baja este medio de pago?')">
                     Dar de baja
                  </a>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>

      <div style="text-align:center;">
        <a href="<?= BASE_URL ?>/controlador/controladores_adm/controlador_venta/controlador_venta.php?agregar_metodo_pago=vista_agregar_metodo_pago" 
           class="add-btn">
           + Agregar Medio de Pago
        </a>
      </div>

    <?php else: ?>
      <p style="text-align:center; padding:50px; background:rgba(46,46,68,0.9); border-radius:12px; color:var(--text-muted);">
        No hay medios de pago registrados aún.
      </p>
      <div style="text-align:center; margin-top:20px;">
        <a href="<?= BASE_URL ?>/controlador/controladores_adm/controlador_venta/controlador_venta.php?agregar_metodo_pago=vista_agregar_metodo_pago" 
           class="add-btn">
           + Agregar Primer Medio de Pago
        </a>
      </div>
    <?php endif; ?>
  </div>

  <script>
    function toggleSidebar() {
      document.getElementById('sidebar').classList.toggle('hidden');
      document.getElementById('content').classList.toggle('expanded');
    }
  </script>
</body>
</html>
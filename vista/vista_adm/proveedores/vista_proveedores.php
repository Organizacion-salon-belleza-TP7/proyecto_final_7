<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/modelo_adm/proveedores/modelo_proveedor.php');
require_once(ROOT_PATH . '/modelo/BD.php');

$modelo = new ModeloProveedor($conn);
$proveedores = $modelo->obtenerProveedores();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Proveedores - RoseSpa</title>
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

    /* Sidebar - 100% IGUAL QUE EN VISTA_INICIO_ADM */
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
      padding-bottom: 20px;
      overflow-y: auto;
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

    /* Botón toggle */
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

    /* TÍTULO QUE NUNCA SE TAPA */
    h1{
      font-size:2rem;
      margin-bottom:20px;
      color: var(--primary);
      text-shadow: 2px 2px 6px rgba(0,0,0,0.6);
      margin-left: 60px !important;
    }

    .title{margin-left: 50px;}
    .titulo_menu{margin-left: 20px;}

    /* Card */
    .card{
      background: rgba(46,46,68,0.9);
      padding: 25px;
      border-radius: 8px;
      box-shadow: var(--shadow);
      margin-bottom: 25px;
    }

    /* Tabla */
    table{
      width:100%;
      border-collapse:collapse;
      background: rgba(37,37,56,0.9);
      border-radius:8px;
      overflow:hidden;
      box-shadow: var(--shadow);
    }
    th,td{
      padding:14px 16px;
      text-align:center;
      font-size:0.95rem;
    }
    th{
      background: var(--primary-dark);
      color:#fff;
      font-weight:600;
      text-transform: uppercase;
      font-size: 0.8rem;
      letter-spacing: 0.5px;
    }
    tr:nth-child(even){background: rgba(37,37,56,0.9);}
    tr:hover{background: rgba(255,107,157,0.1);}

    /* Botones */
    .btn{
      padding:6px 12px;
      border-radius:6px;
      font-size:0.85rem;
      font-weight:600;
      text-decoration:none;
      display:inline-block;
      margin:0 3px;
      transition:.3s;
    }
    .btn-delete{
      background: var(--danger);
      color:#fff;
    }
    .btn-delete:hover{
      background: #c82333;
      opacity: .9;
    }
    .add-btn{
      display:inline-block;
      padding:10px 18px;
      background: var(--primary);
      color:#fff;
      text-decoration:none;
      border-radius:6px;
      font-weight:600;
      transition:.3s;
      box-shadow: var(--shadow);
      margin-bottom: 20px;
    }
    .add-btn:hover{
      background: var(--primary-dark);
    }

    /* Empty */
    .empty-message{
      text-align: center;
      padding: 50px 20px;
      background: rgba(46,46,68,0.9);
      border-radius: 8px;
      color: var(--text-muted);
      box-shadow: var(--shadow);
    }
    .empty-message i{
      font-size: 3rem;
      color: var(--primary);
      margin-bottom: 15px;
    }
    .add-contact-btn {
    position: fixed;
    bottom: 25px;
    right: 25px;
    background: var(--primary);
    color: #fff;
    padding: 14px 20px;
    border-radius: 10px;
    font-size: 1rem;
    font-weight: 600;
    text-decoration: none;
    box-shadow: var(--shadow);
    display: flex;
    align-items: center;
    gap: 10px;
    transition: .3s;
    z-index: 1200;
  }
  .add-contact-btn:hover {
    background: var(--primary-dark);
    transform: translateY(-2px);
  }

  </style>
</head>
<body>

  <!-- Toggle -->
  <button class="toggle-btn" onclick="toggleSidebar()">
    <i class="fas fa-bars"></i>
  </button>

  <!-- Sidebar - MENÚ OFICIAL DEL ADMIN -->
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

  <!-- Contenido -->
  <div class="content" id="content">
    <h1>Proveedores</h1>

    <div class="card">
      <a href="<?= BASE_URL ?>/vista/vista_adm/proveedores/vista_agregar_proveedor.php" class="add-btn">
        + Agregar Proveedor
      </a>

      <?php if (!empty($proveedores) && count($proveedores) > 0): ?>
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Nombre</th>
              <th>Apellido</th>
              <th>DNI</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($proveedores as $p): ?>
              <tr>
                <td>#<?= $p['id_proveedor'] ?></td>
                <td><?= htmlspecialchars($p['nombre_proveedor']) ?></td>
                <td><?= htmlspecialchars($p['apellido_proveedor']) ?></td>
                <td><?= htmlspecialchars($p['dni']) ?></td>
                <td>
                  <a class="btn btn-delete" 
                     href="<?= BASE_URL ?>/controlador/controladores_adm/proveedores/controlador_eliminar_proveedor.php?id=<?= $p['id_proveedor'] ?>"
                     onclick="return confirm('¿Eliminar este proveedor?')">
                     Eliminar
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php else: ?>
        <div class="empty-message">
          <i class="fas fa-truck"></i>
          <p>No hay proveedores registrados</p>
          <a href="<?= BASE_URL ?>/vista/vista_adm/proveedores/vista_agregar_proveedor.php" class="add-btn">
            + Agregar Primer Proveedor
          </a>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <a href="<?= BASE_URL ?>/controlador/controladores_adm/controlador_contactos/ControladorContacto.php?agregar_contacto_proveedor=vista_proveedores"
   class="add-contact-btn">
    <i class="fas fa-address-book"></i> Añadir Contacto de Proveedor
  </a>


  <!-- JS -->
  <script src="<?= BASE_URL ?>/modelo/modelo_adm/servicios_combos/menu_desplegable.js"></script>
  <script>
    function toggleSidebar() {
      document.getElementById('sidebar').classList.toggle('hidden');
      document.getElementById('content').classList.toggle('expanded');
    }
  </script>
</body>
</html>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');
require_once(ROOT_PATH . '/modelo/modelo_adm/promociones/modelo_promociones.php');

$clase_promociones = new promociones($conn);
$resultado_traer_promociones = $clase_promociones->traer_servicios_combos_promocionados();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Promociones - Panel Administrativo</title>
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

    /* Overlay oscuro para mejorar legibilidad */
    body::before {
      content: "";
      position: fixed;
      top:0;
      left:0;
      right:0;
      bottom:0;
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

    /* Sidebar oculto */
    .sidebar.hidden {
      transform: translateX(-100%);
    }

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
    .toggle-btn:hover{
      background: rgba(224, 85, 133, 0.8);
    }

    /* Content */
    .content{
      margin-left: 240px;
      flex:1;
      padding:30px;
      transition: margin-left .3s ease;
      width: 100%;
    }
    .content.expanded{
      margin-left: 0;
    }
    h1{
      font-size:2rem;
      margin-bottom:20px;
      color: var(--primary);
      text-shadow: 2px 2px 6px rgba(0,0,0,0.6);
    }

    .title{
      margin-left: 50px;
    }

    .titulo_menu{
      margin-left: 20px;
    }

    /* Tables */
    table{
      width:100%;
      border-collapse:collapse;
      background: rgba(46,46,68,0.9);
      border-radius:8px;
      overflow:hidden;
      box-shadow: var(--shadow);
      margin-bottom:25px;
    }
    th,td{
      padding:14px 16px;
      text-align:left;
      font-size:0.95rem;
    }
    th{
      background: var(--primary-dark);
      color:#fff;
      font-weight:600;
    }
    tr:nth-child(even){background: rgba(37,37,56,0.9);}
    tr:hover{background: rgba(255,107,157,0.1);}

    /* Estados activo/inactivo */
    .estado-activo{
      color: var(--success);
      font-weight: bold;
    }
    .estado-inactivo{
      color: var(--danger);
      font-weight: bold;
    }

    /* Buttons */
    .btn{
      padding:6px 12px;
      border-radius:6px;
      font-size:0.85rem;
      font-weight:600;
      text-decoration:none;
      margin-right:5px;
      display:inline-block;
      transition:.3s;
    }
    .btn-view{background: var(--success);color:#fff;}
    .btn-edit{background: var(--warning);color:#fff;}
    .btn-delete{background: var(--danger);color:#fff;}
    .btn:hover{opacity:.85;}
    
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
    }
    .add-btn:hover{background: var(--primary-dark);}

    /* Badge para tipos */
    .badge {
      background: var(--primary);
      color: white;
      padding: 4px 8px;
      border-radius: 4px;
      font-size: 0.8rem;
      font-weight: bold;
    }

    .badge-combo {
      background: var(--warning);
    }

    .badge-servicio {
      background: var(--info);
    }

    /* Mensaje sin datos */
    .no-data {
      background: rgba(46,46,68,0.9);
      padding: 20px;
      border-radius: 8px;
      text-align: center;
      color: var(--text-muted);
      margin-bottom: 20px;
    }
  </style>
</head>
<body>
  <!-- Botón Toggle -->
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
    <h1><i class="fas fa-tags"></i> Promociones</h1>

    <?php
    if ($resultado_traer_promociones && $resultado_traer_promociones->num_rows > 0) {

        echo "<table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Tipo</th>
                    <th>Días de promoción</th>
                    <th>Descuento</th>
                    <th>Puntos</th>
                    <th>Estado</th>
                    <th colspan='3'>Acciones</th>
                </tr>
            </thead>
            <tbody>";

        
        while ($row = $resultado_traer_promociones->fetch_assoc()) {

            //se define que tipo es dentro del while
            if (!empty($row['nombre_combo'])) {
                $nombre = $row['nombre_combo'];
                $tipo = "Combo";
                $badge_class = "badge badge-combo";
            } elseif (!empty($row['nombre_servicio'])) {
                $nombre = $row['nombre_servicio'];
                $tipo = "Servicio";
                $badge_class = "badge badge-servicio";
            } else {
                $nombre = "—";
                $tipo = "Desconocido";
                $badge_class = "badge";
            }

            //y ya solo se recorre :p
            echo "<tr>
                <td>{$nombre}</td>
                <td><span class='{$badge_class}'>{$tipo}</span></td>
                <td>{$row['dias_promocion']}</td>
                <td>{$row['descuento']}%</td>
                <td>{$row['puntos']}</td>";
                
                if($row['activo'] == 1){
                    echo "<td class='estado-activo'>Activo</td>";
                } else {
                    echo "<td class='estado-inactivo'>Inactivo</td>";
                }
                
                echo"
                <td><a class='btn btn-view' href='".BASE_URL."/controlador/controladores_adm/controlador_promociones/controlador_promociones.php?id={$row['id_promocion']}&detalle_promo=vista_promociones'><i class='fas fa-eye'></i> Detalle</a></td>
                <td><a class='btn btn-edit' href='".BASE_URL."/controlador/controladores_adm/controlador_promociones/controlador_promociones.php?id={$row['id_promocion']}&modificar=vista_promociones'><i class='fas fa-edit'></i> Modificar</a></td>
                <td><a class='btn btn-delete' href='".BASE_URL."/controlador/controladores_adm/controlador_promociones/controlador_promociones.php?id={$row['id_promocion']}&cambiar_estado=vista_promociones'><i class='fas fa-ban'></i> " . ($row['activo'] == 1 ? 'Desactivar' : 'Activar') . "</a></td>
            </tr>";
        }

        echo "</tbody></table>";

        echo "<a href='".BASE_URL."/controlador/controladores_adm/controlador_promociones/controlador_promociones.php?agregar=vista_promociones' class='add-btn'><i class='fas fa-plus'></i> Agregar Promoción</a>";

    } else {
        echo "<div class='no-data'>
                <p><i class='fas fa-tags' style='font-size: 2rem; margin-bottom: 10px;'></i></p>
                <p>No hay promociones registradas.</p>
                <p style='margin-top: 10px;'><a href='".BASE_URL."/controlador/controladores_adm/controlador_promociones/controlador_promociones.php?agregar=vista_promociones' class='add-btn'><i class='fas fa-plus'></i> Crear primera promoción</a></p>
              </div>";
    }
    ?>
  </div>

  <script src="<?= BASE_URL ?>/modelo/modelo_adm/servicios_combos/menu_desplegable.js"></script>
  
</body>
</html>
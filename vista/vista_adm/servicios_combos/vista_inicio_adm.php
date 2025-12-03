<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');
require_once(ROOT_PATH . '/modelo/modelo_adm/servicios_combos/modelo_inicio_adm.php');
$servicio_modelo = new servicios($conn);
$resultado_traer_servicios = $servicio_modelo->mostrar_servicios();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel Administrativo</title>
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
      padding-bottom: 20px; /* 👈 Espacio extra para que no quede pegado al pie */
      overflow-y: auto; /* 👈 Permite hacer scroll interno */
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
      background: rgba(255, 107, 157, 0.6); /* Transparente */
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
    td img{border-radius:6px;}

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
    <a href="<?= BASE_URL ?>/vista/vista_adm/venta/historial_ventas.php"><i class="fas fa-cash-register"></i> Historial de compras</a>
    <a href="<?= BASE_URL ?>/controlador/controladores_adm/controlador_logout/controlador_logout.php?logout=vista_inicio_adm"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a>
  </div>

  <!-- Content -->
  <div class="content" id="content">
    <h1>Servicios</h1>
    <?php
    if($resultado_traer_servicios && $resultado_traer_servicios->num_rows > 0){
        echo "<table>
            <thead>
                <tr>
                    <th>Nombre</th><th>Descripcion</th><th>Duracion</th>
                    <th>Precio</th><th>Trabajador</th>
                    <th>Activo</th><th>Tipo</th><th>Imagen</th><th colspan='3'>Acciones</th>
                </tr>
            </thead><tbody>";
        
        while($row = $resultado_traer_servicios->fetch_assoc()){
            echo "<tr>
                    <td>{$row['nombre']}</td>
                    <td>{$row['descripcion']}</td>
                    ";
                    if($row['tiempo_servicio'] == 'horas'){
                      echo "
                      <td>{$row['duracion']}Hs</td>
                      <td>\${$row['precio_servicio']}</td>
                      <td>{$row['nombre_trabajador']}</td>
                      <td>".($row['activo']==1?'Activo':'Inactivo')."</td>
                      <td>{$row['tipo_servicio']}</td>
                      <td><img src='".BASE_URL."/imagenes/servicios/{$row['imagen']}' width='80'></td>
                      <td><a class='btn btn-view' href='".BASE_URL."/controlador/controladores_adm/servicios_combos/controlador_inicio_adm.php?id={$row['id_servicios']}&detalle_servicio=vista_inicio_adm'>Detalle</a></td>
                      <td><a class='btn btn-edit' href='".BASE_URL."/controlador/controladores_adm/servicios_combos/controlador_inicio_adm.php?id={$row['id_servicios']}&modificar=vista_inicio_adm'>Editar</a></td>
                      <td><a class='btn btn-delete' href='".BASE_URL."/controlador/controladores_adm/servicios_combos/controlador_inicio_adm.php?id={$row['id_servicios']}&eliminar=vista_inicio_adm'>Borrar</a></td>
                      ";

                    }elseif($row['tiempo_servicio'] == 'minutos'){
                      echo "
                      <td>{$row['duracion']}Min</td>
                      <td>\${$row['precio_servicio']}</td>
                      <td>{$row['nombre_trabajador']}</td>
                      <td>".($row['activo']==1?'Activo':'Inactivo')."</td>
                      <td>{$row['tipo_servicio']}</td>
                      <td><img src='".BASE_URL."/imagenes/servicios/{$row['imagen']}' width='80'></td>
                      <td><a class='btn btn-view' href='".BASE_URL."/controlador/controladores_adm/servicios_combos/controlador_inicio_adm.php?id={$row['id_servicios']}&detalle_servicio=vista_inicio_adm'>Detalle</a></td>
                      <td><a class='btn btn-edit' href='".BASE_URL."/controlador/controladores_adm/servicios_combos/controlador_inicio_adm.php?id={$row['id_servicios']}&modificar=vista_inicio_adm'>Editar</a></td>
                      <td><a class='btn btn-delete' href='".BASE_URL."/controlador/controladores_adm/servicios_combos/controlador_inicio_adm.php?id={$row['id_servicios']}&eliminar=vista_inicio_adm'>Borrar</a></td>
                      ";

                    }elseif($row['tiempo_servicio'] == 'segundos'){
                      echo "
                      <td>{$row['duracion']}Sec</td>
                      <td>\${$row['precio_servicio']}</td>
                      <td>{$row['nombre_trabajador']}</td>
                      <td>".($row['activo']==1?'Activo':'Inactivo')."</td>
                      <td>{$row['tipo_servicio']}</td>
                      <td><img src='".BASE_URL."/imagenes/servicios/{$row['imagen']}' width='80'></td>
                      <td><a class='btn btn-view' href='".BASE_URL."/controlador/controladores_adm/servicios_combos/controlador_inicio_adm.php?id={$row['id_servicios']}&detalle_servicio=vista_inicio_adm'>Detalle</a></td>
                      <td><a class='btn btn-edit' href='".BASE_URL."/controlador/controladores_adm/servicios_combos/controlador_inicio_adm.php?id={$row['id_servicios']}&modificar=vista_inicio_adm'>Editar</a></td>
                      <td><a class='btn btn-delete' href='".BASE_URL."/controlador/controladores_adm/servicios_combos/controlador_inicio_adm.php?id={$row['id_servicios']}&eliminar=vista_inicio_adm'>Borrar</a></td>
                      ";
                    }
                    
                echo "</tr>";
        }
        echo "</tbody></table>";
        echo "<a href='".BASE_URL."/controlador/controladores_adm/servicios_combos/controlador_inicio_adm.php?agregar=vista_inicio_adm' class='add-btn'>+ Agregar Servicio</a>";
    }
    ?>

    <h1>Combos</h1>
    <?php
    $resultado_traer_combos = $servicio_modelo->mostrar_combos();
    if($resultado_traer_combos && $resultado_traer_combos->num_rows > 0){
        echo "<table>
            <thead>
                <tr>
                    <th>Nombre</th><th>Descripcion</th><th>Precio</th><th>Imagen</th>
                    <th>Activo</th><th>Fecha</th><th colspan='3'>Acciones</th>
                </tr>
            </thead><tbody>";
        while($row_combos = $resultado_traer_combos->fetch_assoc()){
            echo "<tr>
                <td>{$row_combos['nombre']}</td>
                <td>{$row_combos['descripcion_combo']}</td>
                <td>\${$row_combos['precio']}</td>
                <td><img src='".BASE_URL."/imagenes/imagenes_combos/{$row_combos['imagen']}' width='80'></td>
                <td>".($row_combos['activo']==1?'Activo':'Inactivo')."</td>
                <td>{$row_combos['fecha_creacion']}</td>
                <td><a class='btn btn-view' href='".BASE_URL."/controlador/controladores_adm/servicios_combos/controlador_inicio_adm.php?id={$row_combos['id_combos']}&detalle_combo=vista_inicio_adm'>Detalle</a></td>
                <td><a class='btn btn-edit' href='".BASE_URL."/controlador/controladores_adm/servicios_combos/controlador_inicio_adm.php?id={$row_combos['id_combos']}&modificar_combo=vista_inicio_adm''>Editar</a></td>
                <td><a class='btn btn-delete' href='".BASE_URL."/controlador/controladores_adm/servicios_combos/controlador_inicio_adm.php?id={$row_combos['id_combos']}&dar_baja_combo=vista_inicio_adm'>Borrar</a></td>
            </tr>";
        }
        echo "</tbody></table>";
        echo "<a href='".BASE_URL."/controlador/controladores_adm/servicios_combos/controlador_inicio_adm.php?agregar_combo=vista_inicio_adm' class='add-btn'>+ Agregar Combo</a>";
    }
    ?>
  </div>

  <script src="<?= BASE_URL ?>/modelo/modelo_adm/servicios_combos/menu_desplegable.js"></script>
  
</body>
</html>

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');
require_once(ROOT_PATH . '/modelo/modelo_cliente/modelo_inicio/modelo_inicio.php');

session_start();

$id_usuario = $_SESSION['user'];

$modelo_inicio = new modelo_inicio($conn);
$funcion_traer_citas = $modelo_inicio->traer_citas_compradas($id_usuario);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mis Citas Compradas</title>
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
      --success: #27ae60;
      --info: #3498db;
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
      text-align: center;
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

    .estado-activo{color: var(--success); font-weight:bold;}
    .estado-inactivo{color: var(--danger); font-weight:bold;}

    /* Buttons */
    .btn{
      padding:6px 12px;
      border-radius:6px;
      font-size:0.85rem;
      font-weight:600;
      text-decoration:none;
      display:inline-block;
      transition:.3s;
      border: none;
      cursor: pointer;
    }
    .btn-view{background: var(--danger);color:#fff;}
    .btn-disabled{
      background: #7f8c8d;
      color:#fff;
      cursor: not-allowed;
      opacity: 0.6;
    }
    .btn:hover:not(.btn-disabled){opacity:.85;}

    /* No citas */
    .no-citas {
      background: rgba(46,46,68,0.95);
      padding: 20px;
      border-radius: 10px;
      text-align: center;
      font-size: 1.1rem;
      color: #f39c12;
      box-shadow: var(--shadow);
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
    <h2>RoseSpa</h2>
    <a href="<?= BASE_URL ?>/vista/vista_cliente/vista_citas/layout.php"><i class="fas fa-spa"></i> Reservar cita</a>
    <a href="<?= BASE_URL ?>/vista/vista_cliente/vista_venta_productos/vista_venta.php"><i class="fas fa-boxes"></i>Comprar Productos</a>
    <a href="<?= BASE_URL ?>/controlador/controladores_adm/controlador_logout/controlador_logout.php?logout=vista_inicio_adm"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a>
  </div>

  <!-- Contenido -->
  <div class="content" id="content">
    <h1>Mis Citas Compradas</h1>
    <?php
    if($funcion_traer_citas && $funcion_traer_citas->num_rows > 0){
        echo "<table>
            <thead>
                <tr>
                    <th>ID</th><th>Nombre</th><th>Usuario</th><th>Fecha Cita</th>
                    <th>Estado</th><th>Lugar</th><th>Acción</th>
                </tr>
            </thead><tbody>";

        while($array_citas_compradas = $funcion_traer_citas->fetch_assoc()){
            echo "<tr>
                <td>{$array_citas_compradas['id_cita']}</td>
                <td>{$array_citas_compradas['nombre']}</td>
                <td>{$array_citas_compradas['nombre_usuario']}</td>
                <td>{$array_citas_compradas['fecha_cita']}</td>";

            if($array_citas_compradas['activo'] == 1){
                echo "<td class='estado-activo'>Activo</td>
                <td>{$array_citas_compradas['nombre_lugar']}</td>
                <td><a class='btn btn-view' href='".BASE_URL."/controlador/controladores_cliente/controladores_inicio/controlador_inicio_cli.php?id_caja={$array_citas_compradas['id_caja']}&id_cita={$array_citas_compradas['id_cita']}&reembolsar_cita=vista_inicio_cli'>Reembolsar Cita</a></td>";
            }else{
                echo "<td class='estado-inactivo'>Inactivo</td>
                <td>{$array_citas_compradas['nombre_lugar']}</td>
                <td><a class='btn btn-disabled' href='#' onclick='alert(\"⚠️ No podés reembolsar una cita que ya está inactiva.\"); return false;'>Reembolsar Cita</a></td>";
            }

            echo "</tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p class='no-citas'>No tenés citas compradas actualmente. ¡Es hora de agendar una! 📅✨</p>";
    }
    ?>
  </div>

  <script src="<?= BASE_URL ?>/modelo/modelo_adm/servicios_combos/menu_desplegable.js"></script>

</body>
</html>

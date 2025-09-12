<?php
require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');
require_once(ROOT_PATH . '/modelo/modelo_adm/modelo_logout/modelo_logout.php');
$logouts_modelo = new logout($conn);
$traer_logueos = $logouts_modelo->mostrar_logueos();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Logueos y Movimientos</title>
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
        --shadow: 0 4px 12px rgba(0,0,0,0.3);
    }
    *{margin:0;padding:0;box-sizing:border-box;}
    body{
        font-family:'Segoe UI', sans-serif;
        background: url('../../../imagenes/lugares/istockphoto-1856117770-612x612.jpg') no-repeat center center fixed;
        background-size: cover;
        color: var(--text);
        display:flex;
        min-height:100vh;
        position: relative;
        z-index:1;
    }
    body::before{
        content:"";
        position: fixed;
        top:0; left:0; right:0; bottom:0;
        background: rgba(0,0,0,0.5);
        z-index: -1;
    }
    .sidebar{
        width:240px;
        background: rgba(42,42,61,0.9);
        padding:20px;
        display:flex;
        flex-direction:column;
        box-shadow: var(--shadow);
        position: fixed;
        top:0; left:0; bottom:0;
        transition: transform .3s ease;
        z-index:1000;
    }
    .sidebar h2{
        color: var(--primary);
        margin-bottom:30px;
        text-align:center;
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
    .sidebar.hidden {transform: translateX(-100%);}
    .toggle-btn{
        position: fixed;
        top:20px;
        left:20px;
        background: rgba(255,107,157,0.6);
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
    .toggle-btn:hover{background: rgba(224,85,133,0.8);}
    .content{
        margin-left: 240px;
        flex:1;
        padding:30px;
        transition: margin-left .3s ease;
        width:100%;
    }
    .content.expanded{margin-left:0;}
    h1{
        font-size:2rem;
        margin-bottom:20px;
        color: var(--primary);
        text-shadow: 2px 2px 6px rgba(0,0,0,0.6);
    }
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
    .no-data{
        text-align:center;
        color: var(--text-muted);
        font-size:1.1rem;
        margin-top:20px;
    }
</style>
</head>
<body>
<!-- Botón Toggle -->
<button class="toggle-btn" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <h2>RoseSpa</h2>
    <a href="<?= BASE_URL ?>/vista/vista_adm/servicios_combos/vista_inicio_adm.php"><i class="fas fa-spa"></i> Servicios y Combos</a>
    <a href="<?= BASE_URL ?>/vista/vista_adm/inventario/InventarioVista.php"><i class="fas fa-boxes"></i> Productos</a>
    <a href="<?= BASE_URL ?>/vista/vista_adm/venta/vista_medios_pagos.php"><i class="fas fa-cash-register"></i> Ventas y Compras</a>
    <a href="<?= BASE_URL ?>/vista/vista_adm/lugares/lugares.php"><i class="fas fa-map-marker-alt"></i> Lugares</a>
    <a href="<?= BASE_URL ?>/vista/vista_adm/proveedores/vista_proveedores.php"><i class="fas fa-truck"></i> Proveedores</a>
    <a href="<?= BASE_URL ?>/vista/vista_adm/trabajadores/trabajadores_lista.php"><i class="fas fa-user-tie"></i> Trabajadores</a>
    <a href="<?= BASE_URL ?>/vista/vista_adm/clientes/clientes_lista.php"><i class="fas fa-users"></i> Clientes</a>
    <a href="<?= BASE_URL ?>/vista/vista_adm/vista_logouts/vista_logouts_adm.php"><i class="fas fa-history"></i> Logeos y Movimientos</a>
    <a href="<?= BASE_URL ?>/vista/vista_adm/citas/citas.php"><i class="fas fa-calendar-check"></i> Citas</a>
    <a href="<?= BASE_URL ?>/controlador/controladores_adm/controlador_logout/controlador_logout.php?logout=vista_inicio_adm"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a>
</div>


<!-- Content -->
<div class="content" id="content">
    <h1>Logueos y Movimientos</h1>
    <?php
    if($traer_logueos && $traer_logueos->num_rows > 0){
        echo "<table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Fecha Logueo</th>
                        <th>Fecha Logout</th>
                    </tr>
                </thead>
                <tbody>";
        while($bucle_logouts = $traer_logueos->fetch_assoc()){
            echo "<tr>
                    <td>{$bucle_logouts['id_historial_logueos']}</td>
                    <td>{$bucle_logouts['nombre_usuario']}</td>
                    <td>{$bucle_logouts['fecha_logueo']}</td>";
                    if($bucle_logouts['fecha_logout'] === null){
                        echo "<td>Sesion activa</td>";
                    }else{
                        echo "<td>{$bucle_logouts['fecha_logout']}</td>";
                    }
            echo "</tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<div class='no-data'>No hay logueos registrados</div>";
    }
    ?>
</div>

<script>
function toggleSidebar(){
    document.getElementById("sidebar").classList.toggle("hidden");
    document.getElementById("content").classList.toggle("expanded");
}
</script>
</body>
</html>

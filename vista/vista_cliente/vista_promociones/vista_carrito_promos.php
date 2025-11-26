<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/modelo_cliente/promociones/modelo_promociones.php');
require_once(ROOT_PATH . '/modelo/BD.php');

session_start();

if(!isset($_SESSION['carrito_promos']) || empty($_SESSION['carrito_promos'])){
    echo "<!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Carrito Vacío</title>
        <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css'>
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
                align-items: center;
                justify-content: center;
            }

            body::before {
                content: '';
                position: fixed;
                top:0; left:0; right:0; bottom:0;
                background: rgba(0,0,0,0.6);
                z-index: -1;
            }

            .empty-cart {
                background: rgba(46,46,68,0.95);
                padding: 40px;
                border-radius: 12px;
                text-align: center;
                box-shadow: var(--shadow);
                max-width: 500px;
                width: 90%;
            }

            .empty-cart h2 {
                color: var(--primary);
                margin-bottom: 20px;
                font-size: 1.8rem;
            }

            .empty-cart p {
                color: var(--text-muted);
                margin-bottom: 25px;
                font-size: 1.1rem;
            }

            .btn {
                padding: 12px 24px;
                border-radius: 6px;
                font-size: 1rem;
                font-weight: 600;
                text-decoration: none;
                display: inline-block;
                transition: .3s;
                border: none;
                cursor: pointer;
                margin: 0 10px;
            }

            .btn-primary {
                background: var(--primary);
                color: #fff;
            }

            .btn-primary:hover {
                background: var(--primary-dark);
            }
        </style>
    </head>
    <body>
        <div class='empty-cart'>
            <h2><i class='fas fa-shopping-cart'></i> Tu carrito está vacío</h2>
            <p>¡No hay promociones en tu carrito! Agrega algunas ofertas especiales.</p>
            <a href='". BASE_URL ."/vista/vista_cliente/vista_promociones/vista_promociones.php' class='btn btn-primary'>
                <i class='fas fa-arrow-left'></i> Volver a promociones
            </a>
        </div>
    </body>
    </html>";
    exit;
}

$ids = $_SESSION['carrito_promos'];
$id_strings = implode(",",$ids);

$clase_promos = new promociones_cliente($conn);
$funcion_traer_datos_carrito = $clase_promos->traer_servicios_combos_promocionados_carrito($id_strings);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Promociones</title>
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

        h1.title {
            margin-left: 60px; /* Ajuste cuando el sidebar está oculto */
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

        /* Buttons */
        .btn{
            padding:8px 16px;
            border-radius:6px;
            font-size:0.9rem;
            font-weight:600;
            text-decoration:none;
            display:inline-block;
            transition:.3s;
            border: none;
            cursor: pointer;
            text-align: center;
        }
        .btn-primary{
            background: var(--primary);
            color:#fff;
        }
        .btn-primary:hover{
            background: var(--primary-dark);
        }
        .btn-success{
            background: var(--success);
            color:#fff;
        }
        .btn-success:hover{
            opacity: 0.85;
        }
        .btn-danger{
            background: var(--danger);
            color:#fff;
        }
        .btn-danger:hover{
            opacity: 0.85;
        }
        .btn-secondary{
            background: var(--info);
            color:#fff;
        }
        .btn-secondary:hover{
            opacity: 0.85;
        }

        /* Action buttons container */
        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        /* Cart summary */
        .cart-summary {
            background: rgba(46,46,68,0.9);
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: var(--shadow);
            text-align: center;
        }

        .cart-summary h3 {
            color: var(--primary);
            margin-bottom: 15px;
        }

        .item-count {
            font-size: 1.1rem;
            color: var(--text-muted);
        }

        /* Empty state */
        .empty-cart {
            background: rgba(46,46,68,0.95);
            padding: 40px;
            border-radius: 12px;
            text-align: center;
            box-shadow: var(--shadow);
        }

        .empty-cart h2 {
            color: var(--primary);
            margin-bottom: 20px;
        }

        .empty-cart p {
            color: var(--text-muted);
            margin-bottom: 25px;
        }

        /* Badge for items */
        .badge {
            background: var(--primary);
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: bold;
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
        <a href="<?= BASE_URL ?>/vista/vista_cliente/vista_venta_productos/vista_venta.php"><i class="fas fa-boxes"></i> Comprar Productos</a>
        <a href="<?= BASE_URL ?>/vista/vista_cliente/vista_promociones/vista_promociones.php"><i class="fas fa-tags"></i> Promociones</a>
        <a href="<?= BASE_URL ?>/controlador/controladores_adm/controlador_logout/controlador_logout.php?logout=vista_inicio_adm"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a>
    </div>

    <!-- Contenido -->
    <div class="content" id="content">
        <h1><i class="fas fa-shopping-cart"></i> Mi Carrito</h1>

        <div class="cart-summary">
            <h3>Resumen del Carrito</h3>
            <p class="item-count">Tienes <span class="badge"><?php echo count($ids); ?></span> promociones en tu carrito</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Tipo</th>
                    <th>Días</th>
                    <th>Descuento</th>
                    <th>Puntos</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>

            <?php
            while($row = $funcion_traer_datos_carrito->fetch_assoc()){
                if (!empty($row['nombre_combo'])) {
                    $nombre = $row['nombre_combo'];
                    $tipo = "Combo";
                } elseif (!empty($row['nombre_servicio'])) {
                    $nombre = $row['nombre_servicio'];
                    $tipo = "Servicio";
                } else {
                    $nombre = "—";
                    $tipo = "Desconocido";
                }

                echo "<tr>
                    <td>{$nombre}</td>
                    <td><span class='badge'>{$tipo}</span></td>
                    <td>{$row['dias_promocion']}</td>
                    <td>{$row['descuento']}%</td>
                    <td>{$row['puntos']}</td>
                    <td>
                        <a class='btn btn-danger' href='".BASE_URL."/controlador/controladores_cliente/controlador_promociones/controlador_promociones.php?id={$row['id_promocion']}&eliminar_prom_carrito=vista_carrito_promos'>
                            <i class='fas fa-trash'></i> Eliminar
                        </a>
                    </td>
                </tr>";
            }
            ?>
            </tbody>
        </table>

        <div class="action-buttons">
            <a class="btn btn-success" href="<?= BASE_URL ?>/controlador/controladores_cliente/controlador_promociones/controlador_promociones.php?carrito=vista_carrito_promos">
                <i class="fas fa-check-circle"></i> Terminar Compra
            </a>
            <a class="btn btn-primary" href="<?= BASE_URL ?>/vista/vista_cliente/vista_promociones/vista_promociones.php">
                <i class="fas fa-plus-circle"></i> Seguir Comprando
            </a>
        </div>
    </div>

    <script src="<?= BASE_URL ?>/modelo/modelo_adm/servicios_combos/menu_desplegable.js"></script>


</body>
</html>
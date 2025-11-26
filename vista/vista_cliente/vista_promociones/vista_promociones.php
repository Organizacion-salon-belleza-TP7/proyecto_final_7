<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');
require_once(ROOT_PATH . '/modelo/modelo_cliente/promociones/modelo_promociones.php');

$clase_promociones = new promociones_cliente($conn);

$funcion_traer_promos = $clase_promociones->traer_servicios_combos_promocionados();

session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Promociones</title>
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
        .btn-primary{
            background: var(--primary);
            color:#fff;
        }
        .btn-primary:hover{
            background: var(--primary-dark);
        }
        .btn-carrito{
            background: var(--success);
            color:#fff;
            padding: 10px 20px;
            font-size: 1rem;
            margin-top: 20px;
            display: inline-block;
        }
        .btn-carrito:hover{
            opacity: 0.85;
        }

        /* No promociones */
        .no-promociones {
            background: rgba(46,46,68,0.95);
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            font-size: 1.1rem;
            color: #f39c12;
            box-shadow: var(--shadow);
        }

        /* Estilos para precios */
        .precio-sin-desc {
            color: var(--text-muted);
            text-decoration: line-through;
        }
        .precio-final {
            color: var(--success);
            font-weight: bold;
        }
        .ahorro {
            color: var(--primary);
            font-weight: bold;
        }

        /* Badge para promoción activa */
        .badge-promo {
            background: var(--success);
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
        <h1>Promociones del Día</h1>

        <?php
        // FILTRO POR DÍA ACTUAL
        $dia_actual = date('N'); // 1–7 (Lunes–Domingo)

        $mapa_dias = [
            'Lunes' => 1,
            'Martes' => 2,
            'Miércoles' => 3,
            'Miercoles' => 3,
            'Jueves' => 4,
            'Viernes' => 5,
            'Sábado' => 6,
            'Sabado' => 6,
            'Domingo' => 7
        ];

        if($funcion_traer_promos && $funcion_traer_promos->num_rows > 0){

            echo "<table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Días de promoción</th>
                        <th>Descuento</th>
                        <th>Puntos</th>
                        <th>Precio sin desc.</th>
                        <th>Precio final</th>
                        <th>Ahorras</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>";

            while($row = $funcion_traer_promos->fetch_assoc()){

                $dias_promo = trim($row['dias_promocion']);

                if ($dias_promo === '' || $dias_promo === '0') {
                    continue;
                }

                // VERIFICAR SI APLICA HOY
                $mostrar = false;

                if (strpos($dias_promo, '–') !== false) {

                    // Rango: "Lunes – Miércoles"
                    list($inicio, $fin) = array_map('trim', explode('–', $dias_promo));

                    if (isset($mapa_dias[$inicio]) && isset($mapa_dias[$fin])) {
                        $inicio_num = $mapa_dias[$inicio];
                        $fin_num    = $mapa_dias[$fin];

                        if ($dia_actual >= $inicio_num && $dia_actual <= $fin_num) {
                            $mostrar = true;
                        }
                    }

                } else {

                    // Día suelto
                    if (isset($mapa_dias[$dias_promo])) {
                        if ($mapa_dias[$dias_promo] == $dia_actual) {
                            $mostrar = true;
                        }
                    }
                }

                if (!$mostrar) continue;

                // NOMBRE Y TIPO
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

                // =============================
                // CALCULAR PRECIOS
                // =============================
                $precio_final = $clase_promociones->calcular_precio_promocion($row, $clase_promociones);

                // Precio sin descuento
                $row_sin_desc = $row;
                $row_sin_desc['descuento'] = 0; // quitar descuento

                $precio_sin_desc = $clase_promociones->calcular_precio_promocion($row_sin_desc, $clase_promociones);

                // Ahorro
                $ahorro = $precio_sin_desc - $precio_final;

                echo "<tr>
                    <td>{$nombre}</td>
                    <td>{$tipo}</td>
                    <td>{$row['dias_promocion']}</td>
                    <td>{$row['descuento']}%</td>
                    <td>{$row['puntos']}</td>
                    <td class='precio-sin-desc'>$ " . number_format($precio_sin_desc, 2) . "</td>
                    <td class='precio-final'>$ " . number_format($precio_final, 2) . "</td>
                    <td class='ahorro'>$ " . number_format($ahorro, 2) . "</td>
                    <td>
                        <a class='btn btn-primary' href='".BASE_URL."/controlador/controladores_cliente/controlador_promociones/controlador_promociones.php?id={$row['id_promocion']}&agregar_carrito=vista_promociones'>
                        Agregar
                        </a>
                    </td>
                </tr>";
            }

            echo "</tbody></table>";

            echo "<a class='btn btn-carrito' href='".BASE_URL."/controlador/controladores_cliente/controlador_promociones/controlador_promociones.php?ver_carrito=vista_promociones'>
                    <i class='fas fa-shopping-cart'></i> Ver Carrito
                 </a>";

        }else{
            echo "<p class='no-promociones'>No hay promociones disponibles para hoy. ¡Vuelve otro día para ver nuestras ofertas especiales! 💖✨</p>";
        }
        ?>
    </div>

    <script src="<?= BASE_URL ?>/modelo/modelo_adm/servicios_combos/menu_desplegable.js"></script>

</body>
</html>
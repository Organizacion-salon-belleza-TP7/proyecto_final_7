<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/modelo_cliente/promociones/modelo_promociones.php');
require_once(ROOT_PATH . '/modelo/BD.php');

$clase_promociones = new promociones_cliente($conn);

$funcion_traer_lugares = $clase_promociones->traer_lugares();
$funcion_traer_medios_pagos = $clase_promociones->traer_metodos_pagos();

session_start();

$ids = $_SESSION['carrito_promos'];
$id_strings = implode(",",$ids);

$funcion_traer_datos_carrito = $clase_promociones->traer_servicios_combos_promocionados_carrito($id_strings);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resumen de Venta - Promociones</title>
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
            min-height: 100vh;
            position: relative;
            z-index: 1;
            padding: 20px;
        }

        body::before {
            content: "";
            position: fixed;
            top:0; left:0; right:0; bottom:0;
            background: rgba(0,0,0,0.6);
            z-index: -1;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        h1{
            font-size:2.5rem;
            margin-bottom:30px;
            color: var(--primary);
            text-shadow: 2px 2px 6px rgba(0,0,0,0.6);
            text-align: center;
        }

        h2 {
            color: var(--primary);
            margin: 30px 0 15px 0;
            font-size: 1.5rem;
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

        /* Form Styles */
        .form-container {
            background: rgba(46,46,68,0.9);
            padding: 25px;
            border-radius: 8px;
            box-shadow: var(--shadow);
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--primary);
            font-weight: 600;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid rgba(255,107,157,0.3);
            border-radius: 6px;
            background: rgba(37,37,56,0.9);
            color: var(--text);
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(255,107,157,0.2);
        }

        select.form-control {
            cursor: pointer;
        }

        /* Buttons */
        .btn{
            padding:12px 24px;
            border-radius:6px;
            font-size:1rem;
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
        .btn-secondary{
            background: var(--info);
            color:#fff;
        }
        .btn-secondary:hover{
            opacity: 0.85;
        }

        .btn-submit {
            width: 100%;
            padding: 15px;
            font-size: 1.1rem;
            margin-top: 10px;
        }

        /* Action buttons */
        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        /* Badge */
        .badge {
            background: var(--primary);
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: bold;
        }

        /* Summary card */
        .summary-card {
            background: rgba(46,46,68,0.9);
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            box-shadow: var(--shadow);
        }

        .summary-card h3 {
            color: var(--primary);
            margin-bottom: 15px;
            text-align: center;
        }

        .item-count {
            text-align: center;
            color: var(--text-muted);
            font-size: 1.1rem;
        }

        /* Header navigation */
        .header-nav {
            text-align: center;
            margin-bottom: 30px;
        }

        .header-nav a {
            color: var(--primary);
            text-decoration: none;
            margin: 0 15px;
            font-weight: 600;
            transition: color 0.3s;
        }

        .header-nav a:hover {
            color: var(--primary-dark);
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Navegación simple -->
        <div class="header-nav">
            <a href="<?= BASE_URL ?>/vista/vista_cliente/vista_promociones/vista_promociones.php">
                <i class="fas fa-tags"></i> Promociones
            </a>
            <a href="<?= BASE_URL ?>/vista/vista_cliente/vista_promociones/vista_carrito_promos.php">
                <i class="fas fa-shopping-cart"></i> Carrito
            </a>
            <a href="<?= BASE_URL ?>/vista/vista_cliente/vista_citas/layout.php">
                <i class="fas fa-spa"></i> Citas
            </a>
        </div>

        <h1><i class="fas fa-file-invoice-dollar"></i> Resumen de Venta</h1>

        <div class="summary-card">
            <h3>Detalles de tu Compra</h3>
            <p class="item-count">Estás a punto de comprar <span class="badge"><?php echo count($ids); ?></span> promociones</p>
        </div>

        <h2><i class="fas fa-list"></i> Promociones en el Carrito</h2>
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Tipo</th>
                    <th>Días</th>
                    <th>Descuento</th>
                    <th>Puntos</th>
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
                </tr>";
            }
            ?>
            </tbody>
        </table>

        <div class="form-container">
            <h2><i class="fas fa-calendar-check"></i> Información de la Cita</h2>
            <form action="<?= BASE_URL ?>/controlador/controladores_cliente/controlador_promociones/controlador_promociones.php" method="post">
                <input type="hidden" name="proceso" value="agendar_paso1">
                
                <div class="form-group">
                    <label for="lugar"><i class="fas fa-map-marker-alt"></i> Lugar</label>
                    <select name="lugar" id="lugar" class="form-control" required>
                        <?php
                        if($funcion_traer_lugares && $funcion_traer_lugares->num_rows > 0){
                            echo "<option value=''>Selecciona un lugar</option>";
                            while($row_lugares = $funcion_traer_lugares->fetch_assoc()){
                                echo "<option value='{$row_lugares['id_lugar']}'>" . htmlspecialchars($row_lugares['nombre_lugar']) . "</option>";
                            }
                        } else {
                            echo "<option value=''>No hay lugares disponibles</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="fecha_hora"><i class="fas fa-clock"></i> Fecha y Hora</label>
                    <input type="datetime-local" name="fecha_hora" id="fecha_hora" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-success btn-submit">
                    <i class="fas fa-check-circle"></i> Confirmar y Proceder al Pago
                </button>
            </form>
        </div>

        <div class="action-buttons">
            <a class="btn btn-secondary" href="<?= BASE_URL ?>/vista/vista_cliente/vista_promociones/vista_carrito_promos.php">
                <i class="fas fa-arrow-left"></i> Volver al Carrito
            </a>
            <a class="btn btn-primary" href="<?= BASE_URL ?>/vista/vista_cliente/vista_promociones/vista_promociones.php">
                <i class="fas fa-shopping-cart"></i> Seguir Comprando
            </a>
        </div>
    </div>

    <script>
        // Establecer fecha mínima como hoy
        document.addEventListener('DOMContentLoaded', function() {
            const now = new Date();
            // Ajustar a la zona horaria local
            const timezoneOffset = now.getTimezoneOffset() * 60000;
            const localTime = new Date(now - timezoneOffset);
            const minDateTime = localTime.toISOString().slice(0, 16);
            
            document.getElementById('fecha_hora').min = minDateTime;
        });
    </script>

</body>
</html>
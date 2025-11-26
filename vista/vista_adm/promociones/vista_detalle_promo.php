<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');
require_once(ROOT_PATH . '/modelo/modelo_adm/promociones/modelo_promociones.php');

$id_promo = $_GET['id'];

$clase_promociones = new promociones($conn);

$funcion_traer_detalle = $clase_promociones->traer_detalle_promo($id_promo);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Promoción</title>
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
            display: flex;
            align-items: center;
            justify-content: center;
        }

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

        .container {
            max-width: 800px;
            width: 100%;
        }

        h1{
            font-size:2.5rem;
            margin-bottom:30px;
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

        /* Buttons */
        .btn{
            padding:10px 20px;
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

        /* Action buttons */
        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        /* Estados activo/inactivo */
        .estado-activo{
            color: var(--success);
            font-weight: bold;
        }
        .estado-inactivo{
            color: var(--danger);
            font-weight: bold;
        }

        /* Info card */
        .info-card {
            background: rgba(46,46,68,0.9);
            padding: 25px;
            border-radius: 8px;
            box-shadow: var(--shadow);
            margin-bottom: 25px;
            border-left: 4px solid var(--primary);
        }

        .info-card h3 {
            color: var(--primary);
            margin-bottom: 15px;
            font-size: 1.3rem;
            text-align: center;
        }

        .info-item {
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .info-label {
            color: var(--primary);
            font-weight: 600;
            min-width: 150px;
        }

        .info-value {
            color: var(--text);
        }

        /* Error message */
        .error-message {
            background: rgba(231, 76, 60, 0.9);
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 20px;
            box-shadow: var(--shadow);
        }

        .type-badge {
            background: var(--primary);
            color: white;
            padding: 8px 12px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: bold;
            display: inline-block;
            margin-bottom: 20px;
        }

        .type-combo {
            background: var(--warning);
        }

        .type-servicio {
            background: var(--info);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><i class="fas fa-info-circle"></i> Detalle de Promoción</h1>

        <?php
        if($funcion_traer_detalle['tipo'] == 'combo'){
            echo "<div class='type-badge type-combo'><i class='fas fa-cube'></i> TIPO: COMBO</div>";
            
            if($funcion_traer_detalle['resultado'] && $funcion_traer_detalle['resultado']->num_rows > 0){

                echo "<div class='info-card'>";

                while($row = $funcion_traer_detalle['resultado']->fetch_assoc()){
                    echo "<div class='info-item'>
                            <span class='info-label'><i class='fas fa-tag'></i> Nombre:</span>
                            <span class='info-value'>{$row['nombre']}</span>
                          </div>
                          <div class='info-item'>
                            <span class='info-label'><i class='fas fa-file-alt'></i> Descripción:</span>
                            <span class='info-value'>{$row['descripcion_combo']}</span>
                          </div>
                          <div class='info-item'>
                            <span class='info-label'><i class='fas fa-dollar-sign'></i> Precio:</span>
                            <span class='info-value'>$ {$row['precio']}</span>
                          </div>
                          <div class='info-item'>
                            <span class='info-label'><i class='fas fa-power-off'></i> Estado:</span>
                            <span class='info-value " . ($row['activo'] == 1 ? 'estado-activo' : 'estado-inactivo') . "'>
                                " . ($row['activo'] == 1 ? 'Activo' : 'Inactivo') . "
                            </span>
                          </div>
                          <div class='info-item'>
                            <span class='info-label'><i class='fas fa-calendar'></i> Fecha de creación:</span>
                            <span class='info-value'>{$row['fecha_creacion']}</span>
                          </div>";
                }

                echo "</div>";

            } else {
                echo "<div class='error-message'>
                        <p><i class='fas fa-exclamation-triangle'></i> No se encontró información del combo.</p>
                      </div>";
            }

        } elseif($funcion_traer_detalle['tipo'] == 'servicio') {
            echo "<div class='type-badge type-servicio'><i class='fas fa-spa'></i> TIPO: SERVICIO</div>";
            
            if($funcion_traer_detalle['resultado'] && $funcion_traer_detalle['resultado']->num_rows > 0){

                echo "<div class='info-card'>";

                while($row = $funcion_traer_detalle['resultado']->fetch_assoc()){
                    echo "<div class='info-item'>
                            <span class='info-label'><i class='fas fa-tag'></i> Nombre:</span>
                            <span class='info-value'>{$row['nombre']}</span>
                          </div>
                          <div class='info-item'>
                            <span class='info-label'><i class='fas fa-file-alt'></i> Descripción:</span>
                            <span class='info-value'>{$row['descripcion']}</span>
                          </div>
                          <div class='info-item'>
                            <span class='info-label'><i class='fas fa-clock'></i> Duración:</span>
                            <span class='info-value'>{$row['duracion']} " . 
                            ($row['tiempo_servicio'] == 'horas' ? 'Horas' : 
                             ($row['tiempo_servicio'] == 'minutos' ? 'Minutos' : 'Segundos')) . "</span>
                          </div>
                          <div class='info-item'>
                            <span class='info-label'><i class='fas fa-stopwatch'></i> Tipo de tiempo:</span>
                            <span class='info-value'>{$row['tiempo_servicio']}</span>
                          </div>
                          <div class='info-item'>
                            <span class='info-label'><i class='fas fa-dollar-sign'></i> Precio:</span>
                            <span class='info-value'>$ {$row['precio_servicio']}</span>
                          </div>
                          <div class='info-item'>
                            <span class='info-label'><i class='fas fa-power-off'></i> Estado:</span>
                            <span class='info-value " . ($row['activo'] == 1 ? 'estado-activo' : 'estado-inactivo') . "'>
                                " . ($row['activo'] == 1 ? 'Activo' : 'Inactivo') . "
                            </span>
                          </div>
                          <div class='info-item'>
                            <span class='info-label'><i class='fas fa-cube'></i> Tipo de servicio:</span>
                            <span class='info-value'>{$row['tipo_servicio']}</span>
                          </div>";
                }

                echo "</div>";

            } else {
                echo "<div class='error-message'>
                        <p><i class='fas fa-exclamation-triangle'></i> No se encontró información del servicio.</p>
                      </div>";
            }

        } else {
            echo "<div class='error-message'>
                    <p><i class='fas fa-bug'></i> Error: Hubo un problema al cargar los detalles de la promoción.</p>
                  </div>";
        }
        ?>

        <div class="action-buttons">
            <a class="btn btn-primary" href="<?= BASE_URL ?>/vista/vista_adm/promociones/vista_promociones.php">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>
</body>
</html>
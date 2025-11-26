<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');
require_once(ROOT_PATH . '/modelo/modelo_adm/promociones/modelo_promociones.php');

$clase_promociones = new promociones($conn);
$funcion_traer_servicios = $clase_promociones->traer_servicios();
$funcion_traer_combos = $clase_promociones->traer_combos();

// Convertir resultados a arrays para usar en el loop
$servicios = [];
while ($row = $funcion_traer_servicios->fetch_assoc()) {
    $servicios[] = $row;
}

$combos = [];
while ($row = $funcion_traer_combos->fetch_assoc()) {
    $combos[] = $row;
}

$dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Promoción</title>
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
            background: rgba(46,46,68,0.95);
            border-radius: 12px;
            box-shadow: var(--shadow);
            padding: 30px;
        }

        h1{
            font-size:2.2rem;
            margin-bottom:30px;
            color: var(--primary);
            text-shadow: 2px 2px 6px rgba(0,0,0,0.6);
            text-align: center;
        }

        /* Form Styles */
        .form-container {
            background: rgba(37,37,56,0.9);
            padding: 25px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--primary);
            font-weight: 600;
            font-size: 1.1rem;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid rgba(255,107,157,0.3);
            border-radius: 6px;
            background: rgba(46,46,68,0.9);
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
            width: 100%;
            padding: 15px;
            font-size: 1.1rem;
            margin-top: 10px;
        }
        .btn-success:hover{
            opacity: 0.85;
        }

        /* Action buttons */
        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        /* Options container */
        .options-container {
            background: rgba(46,46,68,0.9);
            padding: 20px;
            border-radius: 8px;
            margin: 15px 0;
            border-left: 4px solid var(--primary);
            min-height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .options-container label {
            color: var(--primary);
            font-weight: 600;
            margin-bottom: 10px;
            display: block;
        }

        .options-content {
            width: 100%;
        }

        /* Info message */
        .info-message {
            text-align: center;
            color: var(--text-muted);
            font-style: italic;
        }

        /* Form row */
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 15px;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }

        /* Required field indicator */
        .required::after {
            content: " *";
            color: var(--danger);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><i class="fas fa-plus-circle"></i> Agregar Nueva Promoción</h1>

        <form action="<?= BASE_URL ?>/controlador/controladores_adm/controlador_promociones/controlador_promociones.php" method="post">
            <input type="hidden" name="agregar" value="vista_agregar_promocion">
            
            <div class="form-container">
                <div class="form-group">
                    <label for="tipo_promocion" class="required"><i class="fas fa-tag"></i> Tipo de promoción</label>
                    <select name="tipo_promocion" id="tipo_promocion" class="form-control" required>
                        <option value="">Seleccione el tipo...</option>
                        <option value="combo">Combo</option>
                        <option value="servicio">Servicio</option>
                    </select>
                </div>

                <div id="contenedor-opciones" class="options-container">
                    <div class="options-content">
                        <p class="info-message">
                            <i class="fas fa-info-circle"></i> Seleccione un tipo de promoción para continuar
                        </p>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="dias_semana" class="required"><i class="fas fa-calendar"></i> Día de la promoción</label>
                        <select name="dias_semana" id="dias_semana" class="form-control" required>
                            <?php foreach($dias as $dia): ?>
                                <option value="<?= $dia ?>"><?= $dia ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="descuento" class="required"><i class="fas fa-percentage"></i> Descuento (%)</label>
                        <input type="number" name="descuento" id="descuento" class="form-control" 
                               min="0" max="100" required placeholder="Ingrese el porcentaje de descuento">
                    </div>
                </div>

                <div class="form-group">
                    <label for="cantidad_puntos"><i class="fas fa-star"></i> Puntos del descuento</label>
                    <input type="text" name="cantidad_puntos" id="cantidad_puntos" class="form-control" 
                           placeholder="Ingrese la cantidad de puntos">
                </div>

                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Crear Promoción
                </button>
            </div>
        </form>

        <div class="action-buttons">
            <a class="btn btn-primary" href="<?= BASE_URL ?>/vista/vista_adm/promociones/vista_promociones.php">
                <i class="fas fa-arrow-left"></i> Volver a Promociones
            </a>
        </div>
    </div>

    <script>
        const selectTipo = document.getElementById('tipo_promocion');
        const contenedor = document.getElementById('contenedor-opciones');

        // Datos desde PHP
        const servicios = <?= json_encode($servicios) ?>;
        const combos = <?= json_encode($combos) ?>;

        selectTipo.addEventListener('change', function() {
            const tipo = this.value;
            const optionsContent = contenedor.querySelector('.options-content');
            
            if (tipo === 'combo') {
                let html = `<label><i class="fas fa-cube"></i> Seleccione un combo:</label>
                    <select name="combo_select" class="form-control" required>`;
                
                combos.forEach(combo => {
                    html += `<option value="${combo.id_combos}">${combo.nombre_combo}</option>`;
                });
                
                html += `</select>`;
                optionsContent.innerHTML = html;
                
            } else if (tipo === 'servicio') {
                let html = `<label><i class="fas fa-spa"></i> Seleccione un servicio:</label>
                    <select name="servicio_select" class="form-control" required>`;
                
                servicios.forEach(serv => {
                    html += `<option value="${serv.id_servicios}">${serv.nombre_servicio}</option>`;
                });
                
                html += `</select>`;
                optionsContent.innerHTML = html;
                
            } else {
                optionsContent.innerHTML = `
                    <p class="info-message">
                        <i class="fas fa-info-circle"></i> Seleccione un tipo de promoción para continuar
                    </p>
                `;
            }
        });
    </script>
</body>
</html>
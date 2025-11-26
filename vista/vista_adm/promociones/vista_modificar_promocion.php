<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');
require_once(ROOT_PATH . '/modelo/modelo_adm/promociones/modelo_promociones.php');

$clase_promociones = new promociones($conn);

// Obtener ID de la promoción por GET
$id_promocion = $_GET['id'];

if (!$id_promocion) {
    die("Error: Falta el ID de la promoción.");
}

// Obtener datos de la promoción
$promo = $clase_promociones->obtener_datos_promocion($id_promocion);

// Si no se encontró
if (!$promo) {
    die("No se encontró la promoción.");
}

//Obtener listas para selects
$combos_result = $clase_promociones->traer_combos();
$servicios_result = $clase_promociones->traer_servicios();

//Convertir resultados en arrays para poder pasarlos a JS
$combos = [];
while ($row = $combos_result->fetch_assoc()) {
    $combos[] = $row;
}

$servicios = [];
while ($row = $servicios_result->fetch_assoc()) {
    $servicios[] = $row;
}

$dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];

$datos_promo = $promo['datos_promo'];
$tipo = $promo['tipo'];
$datos_resultado = $promo['resultado']->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Promoción</title>
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
        }

        .options-container label {
            color: var(--primary);
            font-weight: 600;
            margin-bottom: 10px;
            display: block;
        }

        /* Info badge */
        .info-badge {
            background: var(--primary);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: bold;
            display: inline-block;
            margin-bottom: 20px;
            text-align: center;
            width: 100%;
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
    </style>
</head>
<body>
    <div class="container">
        <h1><i class="fas fa-edit"></i> Modificar Promoción</h1>

        <div class="info-badge">
            <i class="fas fa-info-circle"></i> Editando promoción ID: <?= $datos_promo['id_promocion'] ?>
        </div>

        <form action="<?= BASE_URL ?>/controlador/controladores_adm/controlador_promociones/controlador_promociones.php" method="post">
            <input type="hidden" name="modificar" value="vista_modificar_promocion">
            <input type="hidden" name="id_promocion" value="<?= $datos_promo['id_promocion'] ?>">

            <div class="form-container">
                <div class="form-group">
                    <label for="tipo_promocion"><i class="fas fa-tag"></i> Tipo de promoción</label>
                    <select name="tipo_promocion" id="tipo_promocion" class="form-control">
                        <option value="combo" <?= $tipo == 'combo' ? 'selected' : '' ?>>Combo</option>
                        <option value="servicio" <?= $tipo == 'servicio' ? 'selected' : '' ?>>Servicio</option>
                    </select>
                </div>

                <div id="contenedor-opciones" class="options-container">
                    <!-- Se carga dinámicamente con JS -->
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="dias_semana"><i class="fas fa-calendar"></i> Día de la promoción</label>
                        <select name="dias_semana" id="dias_semana" class="form-control">
                            <?php foreach ($dias as $dia): ?>
                                <option value="<?= $dia ?>" <?= $dia == $datos_promo['dias_promocion'] ? 'selected' : '' ?>><?= $dia ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="descuento"><i class="fas fa-percentage"></i> Descuento (%)</label>
                        <input type="number" name="descuento" id="descuento" class="form-control" 
                               value="<?= $datos_promo['descuento'] ?>" min="0" max="100">
                    </div>
                </div>

                <div class="form-group">
                    <label for="puntos"><i class="fas fa-star"></i> Puntos</label>
                    <input type="text" name="puntos" id="puntos" class="form-control" 
                           value="<?= $datos_promo['puntos'] ?>">
                </div>

                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Guardar Cambios
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
        const tipoActual = '<?= $tipo ?>';
        const idComboActual = '<?= $datos_resultado['id_combos'] ?? '' ?>';
        const idServicioActual = '<?= $datos_resultado['id_servicios'] ?? '' ?>';

        // ✅ Arrays pasados desde PHP correctamente
        const combos = <?= json_encode($combos) ?>;
        const servicios = <?= json_encode($servicios) ?>;

        function cargarOpciones(tipo) {
            contenedor.innerHTML = '';

            if (tipo === 'combo') {
                let html = `<label><i class="fas fa-cube"></i> Seleccione un combo:</label>
                    <select name="combo_select" class="form-control">`;

                combos.forEach(combo => {
                    const seleccionado = combo.id_combos == idComboActual ? 'selected' : '';
                    html += `<option value="${combo.id_combos}" ${seleccionado}>${combo.nombre_combo}</option>`;
                });

                html += `</select>`;
                contenedor.innerHTML = html;

            } else if (tipo === 'servicio') {
                let html = `<label><i class="fas fa-spa"></i> Seleccione un servicio:</label>
                    <select name="servicio_select" class="form-control">`;

                servicios.forEach(serv => {
                    const seleccionado = serv.id_servicios == idServicioActual ? 'selected' : '';
                    html += `<option value="${serv.id_servicios}" ${seleccionado}>${serv.nombre_servicio}</option>`;
                });

                html += `</select>`;
                contenedor.innerHTML = html;
            }
        }

        // Cargar al iniciar con el tipo actual
        cargarOpciones(tipoActual);

        // Cambiar dinámicamente
        selectTipo.addEventListener('change', (e) => {
            cargarOpciones(e.target.value);
        });
    </script>
</body>
</html>
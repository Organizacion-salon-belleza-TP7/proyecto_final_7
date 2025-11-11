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
    <title>Modificar Promoción</title>
</head>
<body>
    <h1>Modificar Promoción</h1>

    <form action="<?= BASE_URL ?>/controlador/controladores_adm/controlador_promociones/controlador_promociones.php" method="post">
        <input type="hidden" name="modificar" value="vista_modificar_promocion">
        <input type="hidden" name="id_promocion" value="<?= $datos_promo['id_promocion'] ?>">

        <table border="1">
            <tr>
                <td>Tipo de promoción</td>
                <td>
                    <select name="tipo_promocion" id="tipo_promocion">
                        <option value="combo" <?= $tipo == 'combo' ? 'selected' : '' ?>>Combo</option>
                        <option value="servicio" <?= $tipo == 'servicio' ? 'selected' : '' ?>>Servicio</option>
                    </select>
                </td>
            </tr>

            <tr>
                <td colspan="2" id="contenedor-opciones">
                    <!-- Se carga dinámicamente con JS -->
                </td>
            </tr>

            <tr>
                <td>Día de la promoción</td>
                <td>
                    <select name="dias_semana">
                        <?php foreach ($dias as $dia): ?>
                            <option value="<?= $dia ?>" <?= $dia == $datos_promo['dias_promocion'] ? 'selected' : '' ?>><?= $dia ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>

            <tr>
                <td>Descuento (%)</td>
                <td><input type="number" name="descuento" value="<?= $datos_promo['descuento'] ?>"></td>
            </tr>

            <tr>
                <td>Puntos</td>
                <td><input type="text" name="puntos" value="<?= $datos_promo['puntos'] ?>"></td>
            </tr>

            <tr>
                <td colspan="2" style="text-align:center;">
                    <input type="submit" value="Guardar cambios">
                </td>
            </tr>
        </table>
    </form>

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
                let html = `<label>Seleccione un combo:</label>
                    <select name="combo_select">`;

                combos.forEach(combo => {
                    const seleccionado = combo.id_combos == idComboActual ? 'selected' : '';
                    html += `<option value="${combo.id_combos}" ${seleccionado}>${combo.nombre_combo}</option>`;
                });

                html += `</select>`;
                contenedor.innerHTML = html;

            } else if (tipo === 'servicio') {
                let html = `<label>Seleccione un servicio:</label>
                    <select name="servicio_select">`;

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

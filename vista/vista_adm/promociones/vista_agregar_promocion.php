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

$dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Promoción</title>
</head>
<body>
    <h1>Agregar Promoción</h1>

    <form action="<?= BASE_URL ?>/controlador/controladores_adm/controlador_promociones/controlador_promociones.php" method="post">
        <input type="hidden" name="agregar" value="vista_agregar_promocion">
        <table border="1">
            <tr>
                <td>Elija el tipo</td>
                <td>
                    <select name="tipo_promocion" id="tipo_promocion">
                        <option value="">Seleccione...</option>
                        <option value="combo">Combo</option>
                        <option value="servicio">Servicio</option>
                    </select>
                </td>
            </tr>

            <tr>
                <td colspan="2" id="contenedor-opciones"></td>
            </tr>
            <tr>
                <td>Dias</td>
                <td>
                    <select name="dias_semana">
                        <?php
                        foreach($dias as $dia){
                            echo "<option value='$dia'>$dia</option>";
                        }
                        ?>

                    </select>
                </td>
            </tr>
            <tr>
                <td>Descuento de la promo</td>
                <td><input type="number" name="descuento"></td>
            </tr>
            <tr>
                <td>Puntos del descuento</td>
                <td><input type="text" name="cantidad_puntos"></td>
            </tr>
            <tr>
                <td><input type="submit"></td>
            </tr>
        </table>
    </form>

    <a href="<?= BASE_URL ?>/vista/vista_adm/promociones/vista_promociones.php">Volver</a>

    <script>
        const selectTipo = document.getElementById('tipo_promocion');
        const contenedor = document.getElementById('contenedor-opciones');

        selectTipo.addEventListener('change', function() {
            const tipo = this.value;
            contenedor.innerHTML = ''; // limpiar contenido anterior

            if (tipo === 'combo') {
                contenedor.innerHTML = `
                    <label>Seleccione un combo:</label>
                    <select name="combo_select">
                        <?php foreach ($funcion_traer_combos as $combo): ?>
                            <option value="<?= $combo['id_combos'] ?>"><?= $combo['nombre_combo'] ?></option>
                        <?php endforeach; ?>
                    </select>
                `;
            } else if (tipo === 'servicio') {
                contenedor.innerHTML = `
                    <label>Seleccione un servicio:</label>
                    <select name="servicio_select">
                        <?php foreach ($funcion_traer_servicios as $serv): ?>
                            <option value="<?= $serv['id_servicios'] ?>"><?= $serv['nombre_servicio'] ?></option>
                        <?php endforeach; ?>
                    </select>
                `;
            }
        });
    </script>
</body>
</html>

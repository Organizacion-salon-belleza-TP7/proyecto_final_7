<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');
require_once(ROOT_PATH . '/modelo/modelo_adm/servicios_combos/modelo_inicio_adm.php');

$id = $_GET['id'];

$servicio_modelo = new servicios($conn);
$modelo_modificar = $servicio_modelo->formulario_modificar_combo($id);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Modificar Combo</h1>

    <table border="1">
        <form action="<?= BASE_URL ?>/controlador/controladores_adm/servicios_combos/controlador_inicio_adm.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="modificar" value="vista_modificar_combo_adm">
            <input type="hidden" name="id" value="<?= $id ?>">
            <input type="hidden" name="imagen_actual" value="<?= htmlspecialchars($modelo_modificar['imagen']) ?>">
            <tr>
                <td>Nombre</td>
                <td><input type="text" name="nombre_combo" value="<?= htmlspecialchars($modelo_modificar['nombre']) ?>"></td>
            </tr>
            <tr>
                <td>Descripcion</td>
                <td><input type="text" name="descripcion" value="<?= htmlspecialchars($modelo_modificar['descripcion_combo']) ?>"></td>
            </tr>
            <tr>
                <td>Precio</td>
                <td><input type="number" name="precio_combo" value="<?= htmlspecialchars($modelo_modificar['precio']) ?>"></td>
            </tr>
            <tr>
                <td>Imagen actual</td>
                <td>
                    <img src="<?= BASE_URL ?>/imagenes/imagenes_combos/<?= htmlspecialchars($modelo_modificar['imagen']) ?>" width="150" height="120" alt="Imagen del servicio">
                    <br><input type="file" name="imagen_nueva">
                </td>
            </tr>
            <tr>
                <td>Estado</td>
                <td>
                    <select name="estado">
                            <option value="1" <?= $modelo_modificar['activo'] == 1 ? 'selected' : '' ?>>Activo</option>
                            <option value="0" <?= $modelo_modificar['activo'] == 0 ? 'selected' : '' ?>>Inactivo</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Servicios</td>
                <td>
                <?php foreach($servicio_modelo->formulario_agregar_combo() as $servicio): 
                    $checked = in_array($servicio['id_servicios'], array_column($modelo_modificar['servicios'], 'id_servicios')) ? 'checked' : '';
                ?>
                <label>
                    <input type="checkbox" name="servicios_combos[]" value="<?= $servicio['id_servicios'] ?>" <?= $checked ?>>
                <?= htmlspecialchars($servicio['nombre']) ?>
                </label><br>
                <?php endforeach; ?>
                </td>
            </tr>
            <tr>
                <td><input type="submit" name="enviar"></td>
            </tr>

        </form>

    </table>
    
</body>
</html>
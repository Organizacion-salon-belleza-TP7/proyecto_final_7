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
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Combo</title>

    <!-- Iconos FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --bg: #1c1b29;
            --card: #2a2a40;
            --text: #f2f2f2;
            --primary: #ff6b9d;
            --primary-hover: #e25587;
            --input-bg: #32324a;
            --shadow: 0 4px 14px rgba(0,0,0,0.4);
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: url('../../../imagenes/lugares/istockphoto-1856117770-612x612.jpg') no-repeat center/cover fixed;
            color: var(--text);
        }

        body::before {
            content: "";
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.6);
            z-index: -1;
        }

        .container {
            max-width: 900px;
            margin: 40px auto;
            padding: 30px;
            border-radius: 14px;
            background: var(--card);
            box-shadow: var(--shadow);
        }

        h1 {
            text-align: center;
            color: var(--primary);
            margin-bottom: 25px;
            font-size: 2rem;
        }

        table {
            width: 100%;
        }

        td {
            padding: 12px;
            font-size: 1.05rem;
        }

        input[type="text"],
        input[type="number"],
        select {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #444;
            background: var(--input-bg);
            color: var(--text);
            font-size: 1rem;
        }

        input[type="file"] {
            margin-top: 10px;
            color: var(--text);
        }

        img {
            border-radius: 10px;
            box-shadow: var(--shadow);
        }

        label {
            display: block;
            background: rgba(255,255,255,0.05);
            padding: 8px;
            margin-bottom: 6px;
            border-radius: 6px;
            cursor: pointer;
        }

        input[type="checkbox"] {
            transform: scale(1.2);
            margin-right: 8px;
        }

        input[type="submit"] {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 10px;
            margin-top: 15px;
            background: var(--primary);
            color: white;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            transition: 0.25s;
        }

        input[type="submit"]:hover {
            background: var(--primary-hover);
        }
    </style>
</head>

<body>

<div class="container">
    <h1><i class="fas fa-edit"></i> Modificar Combo</h1>

    <form action="<?= BASE_URL ?>/controlador/controladores_adm/servicios_combos/controlador_inicio_adm.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="modificar" value="vista_modificar_combo_adm">
        <input type="hidden" name="id" value="<?= $id ?>">
        <input type="hidden" name="imagen_actual" value="<?= htmlspecialchars($modelo_modificar['imagen']) ?>">

        <table>
            <tr>
                <td>Nombre</td>
                <td><input type="text" name="nombre_combo" value="<?= htmlspecialchars($modelo_modificar['nombre']) ?>"></td>
            </tr>

            <tr>
                <td>Descripción</td>
                <td><input type="text" name="descripcion" value="<?= htmlspecialchars($modelo_modificar['descripcion_combo']) ?>"></td>
            </tr>

            <tr>
                <td>Precio</td>
                <td><input type="number" name="precio_combo" value="<?= htmlspecialchars($modelo_modificar['precio']) ?>"></td>
            </tr>

            <tr>
                <td>Imagen actual</td>
                <td>
                    <img src="<?= BASE_URL ?>/imagenes/imagenes_combos/<?= htmlspecialchars($modelo_modificar['imagen']) ?>" width="160" height="130">
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
                    </label>
                    <?php endforeach; ?>
                </td>
            </tr>
        </table>

        <input type="submit" name="enviar" value="Guardar Cambios">
    </form>

</div>

</body>
</html>

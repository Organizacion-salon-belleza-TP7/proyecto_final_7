<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Servicio</title>
    <style>
        :root {
            --bg: #1e1e2f;
            --primary: #ff6b9d;
            --primary-dark: #e05585;
            --text: #f1f1f1;
            --card: rgba(46,46,68,0.95);
            --shadow: 0 4px 12px rgba(0,0,0,0.3);
        }
        * {margin:0;padding:0;box-sizing:border-box;}
        body {
            font-family: 'Segoe UI', sans-serif;
            background: url('../../../imagenes/lugares/istockphoto-1856117770-612x612.jpg') no-repeat center center fixed;
            background-size: cover;
            color: var(--text);
            min-height: 100vh;
        }
        body::before {
            content:"";
            position:fixed;
            top:0;left:0;right:0;bottom:0;
            background: rgba(0,0,0,0.65);
            z-index:-1;
        }
        .container {
            max-width: 900px;
            margin: 40px auto;
            background: var(--card);
            padding: 30px;
            border-radius: 12px;
            box-shadow: var(--shadow);
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: var(--primary);
        }
        form {
            width: 100%;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        td {
            padding: 12px;
            border-bottom: 1px solid #444;
        }
        td:first-child {
            font-weight: bold;
            color: var(--primary);
            width: 30%;
        }
        input[type="text"],
        input[type="number"],
        select,
        input[type="file"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #666;
            border-radius: 6px;
            background: #2e2e44;
            color: var(--text);
        }
        input[type="file"] {
            background: transparent;
            color: var(--text);
        }
        .product-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
        }
        .product-row select,
        .product-row input {
            flex: 1;
        }
        .remove-product {
            color: #ff6666;
            cursor: pointer;
            font-size: 18px;
            transition: 0.2s;
        }
        .remove-product:hover {
            color: #ff3333;
        }
        button, input[type="submit"] {
            background: var(--primary);
            color: #fff;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
            box-shadow: var(--shadow);
        }
        button:hover,
        input[type="submit"]:hover {
            background: var(--primary-dark);
        }
        .actions {
            text-align: center;
            margin-top: 25px;
        }
    </style>
</head>
<body>
    <?php
    require_once(__DIR__ . '/../../../variable_global.php');
    require_once(ROOT_PATH . '/modelo/BD.php');
    require_once(ROOT_PATH . '/modelo/modelo_adm/servicios_combos/modelo_inicio_adm.php');

    $servicio_modelo = new servicios($conn);
    $resultado_traer_datos_form = $servicio_modelo->formulario_agregar_servicio();

    $resultado_tiempo = $resultado_traer_datos_form['tiempos'];
    $resultado_trabajador = $resultado_traer_datos_form['trabajadores'];
    $resultado_tipo_servicio = $resultado_traer_datos_form['tipo_servicio'];
    $productosJS = $resultado_traer_datos_form['productosJS'];
    ?>

    <script src="<?php echo BASE_URL; ?>/modelo/modelo_adm/servicios_combos/modelo_inicio_adm.js"></script>

    <div class="container">
        <h1>Agregar Servicio</h1>
        <form action="<?= BASE_URL ?>/controlador/controladores_adm/servicios_combos/controlador_inicio_adm.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="agregar" value="vista_agregar_servicio_adm">
            <table>
                <tr>
                    <td>Nombre</td>
                    <td><input type="text" name="nombre"></td>
                </tr>
                <tr>
                    <td>Descripción</td>
                    <td><input type="text" name="descripcion_servicio"></td>
                </tr>
                <tr>
                    <td>Duración Servicio</td>
                    <td><input type="number" name="duracion_servicio"></td>
                </tr>
                <tr>
                    <td>Tiempo a Realizar</td>
                    <td>
                        <select name="tiempo_realizar">
                            <?php
                            if($resultado_tiempo && $resultado_tiempo->num_rows > 0){
                                while($row_tiempo = $resultado_tiempo->fetch_assoc()){
                                    echo "<option value='{$row_tiempo['id_tiempo_servicio']}'>" . htmlspecialchars($row_tiempo['tiempo_servicio']) . "</option>";
                                }
                            }else{
                                echo "<option value=''>Error al cargar datos</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Precio</td>
                    <td><input type="number" name="precio_servicio"></td>
                </tr>
                <tr>
                    <td>Trabajador a cargo</td>
                    <td>
                        <select name="trabajador_cargo">
                            <option value="">Elija el trabajador a cargo</option>
                            <?php
                            if($resultado_trabajador && $resultado_trabajador->num_rows > 0){
                                while($row_trabajador_cargo = $resultado_trabajador->fetch_assoc()){
                                    echo "<option value='{$row_trabajador_cargo['id_trabajador']}'>" . htmlspecialchars($row_trabajador_cargo['nombre_trabajador']) . "</option>";
                                }
                            }else{
                                echo "<option value=''>Error al cargar datos</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Activo</td>
                    <td>
                        <select name="activo">
                            <option value="activo">Activo</option>
                            <option value="inactivo">Inactivo</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Tipo de servicio</td>
                    <td>
                        <select name="tipo_servicio">
                            <option value="">Elija un tipo de servicio</option>
                            <?php
                            if($resultado_tipo_servicio && $resultado_tipo_servicio->num_rows > 0){
                                while($row_tipo_servicio = $resultado_tipo_servicio->fetch_assoc()){
                                    echo "<option value='{$row_tipo_servicio['id_tipo_servicio']}'>" . htmlspecialchars($row_tipo_servicio['tipo_servicio']) . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Imagen Servicio</td>
                    <td><input type="file" name="imagen_servicio"></td>
                </tr>
            </table>

            <h1>Productos a utilizar</h1>
            <div id="productos-container">
                <div class="product-row">
                    <select name="productos[]" required></select>
                    <input type="number" name="cantidades[]" placeholder="Cantidad" min="1" required>
                    <span class="remove-product" onclick="removeProduct(this)">✖</span>
                </div>
            </div>

            <button type="button" onclick="addProduct()">➕ Añadir otro producto</button>

            <div class="actions">
                <input type="submit" name="enviar_servicio_new" value="Guardar Servicio">
            </div>
        </form>

        <!-- Plantilla oculta -->
        <template id="product-template">
            <div class="product-row">
                <select name="productos[]" required></select>
                <input type="number" name="cantidades[]" placeholder="Cantidad" min="1" required>
                <span class="remove-product" onclick="removeProduct(this)">✖</span>
            </div>
        </template>

        <script>
            // Pasamos los productos desde PHP a JavaScript
            const productosDisponibles = <?php echo json_encode($productosJS); ?>;
        </script>
    </div>
</body>
</html>

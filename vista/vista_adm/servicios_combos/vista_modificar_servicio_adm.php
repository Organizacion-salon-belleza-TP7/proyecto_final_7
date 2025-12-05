<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');
require_once(ROOT_PATH . '/modelo/modelo_adm/servicios_combos/modelo_inicio_adm.php');
require_once(ROOT_PATH . '/controlador/controladores_adm/servicios_combos/controlador_inicio_adm.php');

$servicio_modelo = new servicios($conn);
$id_servicio = $_GET['id'];
$datos_formulario_modificar = $servicio_modelo->formulario_modificar($id_servicio);

$servicio = $datos_formulario_modificar['servicio'];
$tiempos = $datos_formulario_modificar['tiempos'];
$trabajadores = $datos_formulario_modificar['trabajadores'];
$tipo_servicio = $datos_formulario_modificar['tipo_servicio'];

// Copia de productosJS para evitar múltiples fetch_assoc()
$productosJS_array = [];
$datos_formulario_modificar['productosJS']->data_seek(0);
while ($row = $datos_formulario_modificar['productosJS']->fetch_assoc()) {
    $productosJS_array[] = $row;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Modificar Servicio</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --bg: #1e1e2f;
            --primary: #ff6b9d;
            --primary-dark: #e05585;
            --text: #f1f1f1;
            --card: rgba(46,46,68,0.95);
            --shadow: 0 4px 12px rgba(0,0,0,0.3);
            --danger: #e74c3c;
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
            background: rgba(0,0,0,0.6);
            z-index:-1;
        }
        .container {
            max-width: 1000px;
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
        h2 {
            margin: 25px 0 10px;
            color: var(--primary);
            font-size: 1.2rem;
        }
        form table {
            width: 100%;
            border-collapse: collapse;
        }
        form td {
            padding: 10px;
            vertical-align: middle;
        }
        input[type="text"], 
        input[type="number"], 
        select, 
        input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #444;
            border-radius: 6px;
            background: #2e2e44;
            color: var(--text);
        }
        input[type="file"] {
            background: none;
            border: none;
        }
        img {
            border-radius: 6px;
            box-shadow: var(--shadow);
        }
        .product-row {
            display: flex;
            gap: 10px;
            align-items: center;
            margin-bottom: 12px;
        }
        .remove-product {
            color: var(--danger);
            cursor: pointer;
            font-weight: bold;
            font-size: 1.2rem;
        }
        button, input[type="submit"] {
            background: var(--primary);
            color: #fff;
            border: none;
            padding: 12px 18px;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            font-weight: bold;
            transition: 0.3s;
            margin-top: 15px;
            box-shadow: var(--shadow);
        }
        button:hover, input[type="submit"]:hover {
            background: var(--primary-dark);
        }
        .add-btn {
            display: inline-block;
            background: var(--primary);
            color: #fff;
            padding: 12px 18px;
            border-radius: 8px;
            font-size: 1rem;
            text-decoration: none;
            font-weight: bold;
            box-shadow: var(--shadow);
            transition: 0.3s;
            margin-top: 15px;
        }
.add-btn:hover {
    background: var(--primary-dark);
}

    </style>
</head>
<body>
    <div class="container">
        <h1><i class="fas fa-edit"></i> Modificar servicio</h1>

        <form action="<?= BASE_URL ?>/controlador/controladores_adm/servicios_combos/controlador_inicio_adm.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="vista_modificar_servicio_adm" value="vista_modificar_servicio_adm">
            <input type="hidden" name="id_servicio" value="<?= htmlspecialchars($servicio['id_servicios']) ?>">

            <table>
                <tr>
                    <td>Nombre</td>
                    <td><input type="text" name="nombre" value="<?= htmlspecialchars($servicio['nombre']) ?>"></td>
                </tr>
                <tr>
                    <td>Descripción</td>
                    <td><input type="text" name="descripcion" value="<?= htmlspecialchars($servicio['descripcion']) ?>"></td>
                </tr>
                <tr>
                    <td>Duración</td>
                    <td><input type="number" name="duracion" value="<?= htmlspecialchars($servicio['duracion']) ?>"></td>
                </tr>
                <tr>
                    <td>Tiempo servicio</td>
                    <td>
                        <select name="tiempo_servicio">
                            <?php while($row = $tiempos->fetch_assoc()): ?>
                                <option value="<?= $row['id_tiempo_servicio'] ?>" <?= $row['id_tiempo_servicio'] == $servicio['id_tiempo_servicio'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($row['tiempo_servicio']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Precio</td>
                    <td><input type="number" name="precio" value="<?= htmlspecialchars($servicio['precio_servicio']) ?>"></td>
                </tr>
                <tr>
                    <td>Trabajador</td>
                    <td>
                        <select name="trabajadoraCargo">
                            <?php while ($row2 = $trabajadores->fetch_assoc()): ?>
                                <option value="<?= $row2['id_trabajador'] ?>" <?= $row2['id_trabajador'] == $servicio['id_trabajador'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($row2['nombre_trabajador']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Activo</td>
                    <td>
                        <select name="activo">
                            <option value="1" <?= $servicio['activo'] == 1 ? 'selected' : '' ?>>Activo</option>
                            <option value="0" <?= $servicio['activo'] == 0 ? 'selected' : '' ?>>Inactivo</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Tipo Servicio</td>
                    <td>
                        <select name="tipo_servicio">
                            <?php while($row_tipo_servicio = $tipo_servicio->fetch_assoc()): ?>
                                <option value="<?= $row_tipo_servicio['id_tipo_servicio'] ?>" 
                                <?= $row_tipo_servicio['id_tipo_servicio'] == $servicio['id_tipo_servicio'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($row_tipo_servicio['tipo_servicio']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Imagen actual</td>
                    <td>
                        <img src="<?= BASE_URL ?>/imagenes/servicios/<?= htmlspecialchars($servicio['imagen']) ?>" width="150" height="120" alt="Imagen del servicio">
                        <br><input type="file" name="imagen_nueva">
                    </td>
                </tr>
            </table>

            <h2><i class="fas fa-box"></i> Productos a utilizar</h2>
            <div id="productos-container">
                <?php foreach ($datos_formulario_modificar['productosUsados'] as $producto): ?>
                    <div class="product-row">
                        <select name="productos[]" required>
                            <option value="">Elija el producto</option>
                            <?php foreach ($productosJS_array as $productoJS): ?>
                                <option value="<?= $productoJS['id_inventario'] ?>" <?= $producto['id_inventario'] == $productoJS['id_inventario'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($productoJS['nombre_producto']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <input type="number" name="cantidades[]" placeholder="Cantidad" min="1" value="<?= htmlspecialchars($producto['cantidad_usada']) ?>" required>
                        <span class="remove-product" onclick="removeProduct(this)">✖</span>
                    </div>
                <?php endforeach; ?>
            </div>

            <button type="button" onclick="addProduct()"><i class="fas fa-plus-circle"></i> Añadir otro producto</button>

            <div style="text-align:center;">
                <input type="submit" name="modificar_servicio" value="Guardar Cambios">
            </div>
        </form>
        <div style='text-align:center;'>
                    <a href="<?= BASE_URL ?>/vista/vista_adm/servicios_combos/vista_inicio_adm.php" class='add-btn'>Volver</a>
                  </div>
        </div>

    <template id="product-template">
        <div class="product-row">
            <select name="productos[]" required></select>
            <input type="number" name="cantidades[]" placeholder="Cantidad" min="1" required>
            <span class="remove-product" onclick="removeProduct(this)">✖</span>
        </div>
    </template>

    <script>
        const productosDisponibles = <?= json_encode($productosJS_array) ?>;

        function addProduct() {
            const container = document.getElementById('productos-container');
            const template = document.getElementById('product-template');
            const clone = template.content.cloneNode(true);
            const select = clone.querySelector('select');

            select.innerHTML = '<option value="">Elija el producto</option>';
            productosDisponibles.forEach(producto => {
                const option = document.createElement('option');
                option.value = producto.id_inventario;
                option.textContent = producto.nombre_producto;
                select.appendChild(option);
            });

            container.appendChild(clone);
        }

        function removeProduct(element) {
            const row = element.closest('.product-row');
            if (document.querySelectorAll('.product-row').length > 1) {
                row.remove();
            } else {
                alert("Debe haber al menos un producto");
            }
        }
    </script>
</body>
</html>

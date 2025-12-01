<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');
require_once(ROOT_PATH . '/modelo/modelo_adm/servicios_combos/modelo_inicio_adm.php');
require_once(ROOT_PATH . '/controlador/controladores_adm/servicios_combos/controlador_inicio_adm.php');

$servicio_modelo = new servicios($conn);
$id_servicio = $_GET['id'] ?? 0;

$datos = $servicio_modelo->formulario_modificar($id_servicio);

// Valores seguros (nunca null ni undefined)
$servicio        = $datos['servicio']        ?? [];
$tiempos         = $datos['tiempos']         ?? null;
$trabajadores    = $datos['trabajadores']    ?? null;
$tipo_servicio   = $datos['tipo_servicio']   ?? null;
$productosUsados = $datos['productosUsados'] ?? [];
$productosJS     = $datos['productosJS']     ?? null;

// Array para JavaScript y selects
$productosJS_array = [];
if ($productosJS && $productosJS->num_rows > 0) {
    $productosJS->data_seek(0);
    while ($row = $productosJS->fetch_assoc()) {
        $productosJS_array[] = $row;
    }
}

// Reset punteros
if ($tiempos)       $tiempos->data_seek(0);
if ($trabajadores) $trabajadores->data_seek(0);
if ($tipo_servicio) $tipo_servicio->data_seek(0);

// Funciones auxiliares seguras
function v($key, $default = '') {
    global $servicio;
    return isset($servicio[$key]) ? htmlspecialchars($servicio[$key]) : $default;
}
function sel($key, $value) {
    global $servicio;
    return (isset($servicio[$key]) && $servicio[$key] == $value) ? 'selected' : '';
}
function activo($val) {
    global $servicio;
    return (isset($servicio['activo']) && $servicio['activo'] == $val) ? 'selected' : '';
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
        h1 {text-align: center; margin-bottom: 20px; color: var(--primary);}
        h2 {margin: 25px 0 10px; color: var(--primary); font-size: 1.2rem;}
        form table {width: 100%; border-collapse: collapse;}
        form td {padding: 10px; vertical-align: middle;}
        input[type="text"], input[type="number"], select, input[type="file"] {
            width: 100%; padding: 10px; border: 1px solid #444; border-radius: 6px; background: #2e2e44; color: var(--text);
        }
        input[type="file"] {background: none; border: none;}
        img {border-radius: 6px; box-shadow: var(--shadow);}
        .product-row {display: flex; gap: 10px; align-items: center; margin-bottom: 12px;}
        .remove-product {color: var(--danger); cursor: pointer; font-weight: bold; font-size: 1.2rem;}
        button, input[type="submit"] {
            background: var(--primary); color: #fff; border: none; padding: 12px 18px; border-radius: 8px;
            font-size: 1rem; cursor: pointer; font-weight: bold; transition: 0.3s; margin-top: 15px; box-shadow: var(--shadow);
        }
        button:hover, input[type="submit"]:hover {background: var(--primary-dark);}
    </style>
</head>
<body>
    <div class="container">
        <h1>Modificar servicio</h1>

        <form action="<?= BASE_URL ?>/controlador/controladores_adm/servicios_combos/controlador_inicio_adm.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="vista_modificar_servicio_adm" value="vista_modificar_servicio_adm">
            <input type="hidden" name="id_servicio" value="<?= v('id_servicios') ?>">

            <table>
                <tr><td>Nombre</td><td><input type="text" name="nombre" value="<?= v('nombre') ?>"></td></tr>
                <tr><td>Descripción</td><td><input type="text" name="descripcion" value="<?= v('descripcion') ?>"></td></tr>
                <tr><td>Duración</td><td><input type="number" name="duracion" value="<?= v('duracion') ?>"></td></tr>
                <tr>
                    <td>Tiempo servicio</td>
                    <td>
                        <select name="tiempo_servicio">
                            <?php while($row = $tiempos?->fetch_assoc() ?? false): ?>
                                <option value="<?= $row['id_tiempo_servicio'] ?>" <?= sel('id_tiempo_servicio', $row['id_tiempo_servicio']) ?>>
                                    <?= htmlspecialchars($row['tiempo_servicio']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </td>
                </tr>
                <tr><td>Precio</td><td><input type="number" name="precio" value="<?= v('precio_servicio') ?>"></td></tr>
                <tr>
                    <td>Trabajador</td>
                    <td>
                        <select name="trabajadoraCargo">
                            <?php while($row = $trabajadores?->fetch_assoc() ?? false): ?>
                                <option value="<?= $row['id_trabajador'] ?>" <?= sel('id_trabajador', $row['id_trabajador']) ?>>
                                    <?= htmlspecialchars($row['nombre_trabajador']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Activo</td>
                    <td>
                        <select name="activo">
                            <option value="1" <?= activo(1) ?>>Activo</option>
                            <option value="0" <?= activo(0) ?>>Inactivo</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Tipo Servicio</td>
                    <td>
                        <select name="tipo_servicio">
                            <?php while($row = $tipo_servicio?->fetch_assoc() ?? false): ?>
                                <option value="<?= $row['id_tipo_servicio'] ?>" <?= sel('id_tipo_servicio', $row['id_tipo_servicio']) ?>>
                                    <?= htmlspecialchars($row['tipo_servicio']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Imagen actual</td>
                    <td>
                        <?php if (!empty($servicio['imagen'])): ?>
                            <img src="<?= BASE_URL ?>/imagenes/servicios/<?= v('imagen') ?>" width="150" height="120" alt="Imagen del servicio"><br>
                        <?php endif; ?>
                        <input type="file" name="imagen_nueva">
                    </td>
                </tr>
            </table>

            <h2>Productos a utilizar</h2>
            <div id="productos-container">
                <?php foreach ($productosUsados as $producto): ?>
                    <div class="product-row">
                        <select name="productos[]" required>
                            <option value="">Elija el producto</option>
                            <?php foreach ($productosJS_array as $p): ?>
                                <option value="<?= $p['id_inventario'] ?>" <?= $producto['id_inventario'] == $p['id_inventario'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($p['nombre_producto']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <input type="number" name="cantidades[]" placeholder="Cantidad" min="1" value="<?= htmlspecialchars($producto['cantidad_usada']) ?>" required>
                        <span class="remove-product" onclick="removeProduct(this)">X</span>
                    </div>
                <?php endforeach; ?>
            </div>

            <button type="button" onclick="addProduct()">Añadir otro producto</button>

            <div style="text-align:center;">
                <input type="submit" name="modificar_servicio" value="Guardar Cambios">
            </div>
        </form>
    </div>

    <template id="product-template">
        <div class="product-row">
            <select name="productos[]" required></select>
            <input type="number" name="cantidades[]" placeholder="Cantidad" min="1" required>
            <span class="remove-product" onclick="removeProduct(this)">X</span>
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
            productosDisponibles.forEach(p => {
                const opt = document.createElement('option');
                opt.value = p.id_inventario;
                opt.textContent = p.nombre_producto;
                select.appendChild(opt);
            });

            container.appendChild(clone);
        }

        function removeProduct(el) {
            if (document.querySelectorAll('.product-row').length > 1) {
                el.closest('.product-row').remove();
            } else {
                alert("Debe haber al menos un producto");
            }
        }
    </script>
</body>
</html>
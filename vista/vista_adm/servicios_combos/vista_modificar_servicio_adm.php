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
</head>
<body>
<script>
    // Pasamos los productos disponibles al JS
    const productosDisponibles = <?= json_encode($productosJS_array) ?>;

    function addProduct() {
        const container = document.getElementById('productos-container');
        const template = document.getElementById('product-template');
        const clone = template.content.cloneNode(true);
        const select = clone.querySelector('select');

        // Llenamos el select con productos
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

<h1>Modificar servicio</h1>
<form action="<?= BASE_URL ?>/controlador/controladores_adm/servicios_combos/controlador_inicio_adm.php" method="post">
    <input type="hidden" name="vista_modificar_servicio_adm" value="vista_modificar_servicio_adm">
    <input type="hidden" name="id_servicio" value="<?= htmlspecialchars($servicio['id_servicios']) ?>">

    <table border="1">
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
            <td><input type="number" name="precio" value="<?= htmlspecialchars($servicio['precio']) ?>"></td>
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
    </table>

    <h2>Productos a utilizar</h2>
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

    <button type="button" onclick="addProduct()">Añadir otro producto</button>

    <div style="margin-top: 20px;">
        <input type="submit" name="modificar_servicio" value="Guardar Cambios">
    </div>
</form>

<template id="product-template">
    <div class="product-row">
        <select name="productos[]" required></select>
        <input type="number" name="cantidades[]" placeholder="Cantidad" min="1" required>
        <span class="remove-product" onclick="removeProduct(this)">✖</span>
    </div>
</template>

</body>
</html>

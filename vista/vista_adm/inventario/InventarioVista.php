<?php
require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');
require_once(ROOT_PATH . '/modelo/modelo_adm/modelo_inventario/InventarioModelo.php');

// Crear conexión
$inventario_modelo = new Inventario($conn);
$resultado_inventario = $inventario_modelo->mostrar_inventario();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Inventario</title>
</head>
<body>
    <!-- Menú de navegación -->
    <div>
        <a href="<?= BASE_URL ?>/vista/vista_adm/servicios_combos/vista_inicio_adm.php">Servicios y Combos</a>
        <a href="<?= BASE_URL ?>/vista/vista_adm/inventario/vista_inventario.php">Productos</a>
        <a href="">Ventas y Compras</a>
        <a href="">Proveedores</a>
        <a href="<?= BASE_URL ?>/vista/vista_adm/vista_logouts/vista_logouts_adm.php">Logeos y Movimientos</a>
        <a href="<?= BASE_URL ?>/vista/vista_adm/citas/citas.php">Citas</a>
        <a href="<?= BASE_URL ?>/controlador/controladores_adm/controlador_logout/controlador_logout.php?logout=vista_inventario" class="logout">Cerrar sesión</a>
    </div>

    <h1>Inventario</h1>
    <?php
    if ($resultado_inventario && $resultado_inventario->num_rows > 0) {
        echo "<table border='1'>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre Producto</th>
                        <th>Stock</th>
                        <th>Vencimiento</th>
                        <th>Precio Compra</th>
                        <th>Precio Venta</th>
                        <th>Imagen</th>
                        <th>Proveedor</th>
                        <th>Detalle</th>
                        <th>Modificar</th>
                        <th>Eliminar</th>
                    </tr>
                </thead>
                <tbody>";
        while ($row = $resultado_inventario->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['id_inventario']}</td>
                    <td>{$row['nombre_producto']}</td>
                    <td>{$row['stock']}</td>
                    <td>{$row['vencimiento']}</td>
                    <td>{$row['precio_producto']}</td>
                    <td>{$row['precio_venta']}</td>
                    <td><img src='" . BASE_URL . "/imagenes/inventario/{$row['imagen_producto']}' width='100' height='80'></td>
                    <td>{$row['nombre_proveedor']}</td>
                    <td><a href='" . BASE_URL . "/controlador/controladores_adm/controlador_inventario/InventarioControlador.php?id={$row['id_inventario']}&detalle=vista_inventario'>Detalle</a></td>
                    <td><a href='" . BASE_URL . "/controlador/controladores_adm/controlador_inventario/InventarioControlador.php?id={$row['id_inventario']}&modificar=vista_inventario'>Modificar</a></td>
                    <td><a href='" . BASE_URL . "/controlador/controladores_adm/controlador_inventario/InventarioControlador.php?id={$row['id_inventario']}&eliminar=vista_inventario' onclick=\"return confirm('¿Seguro que deseas eliminar este producto?')\">Eliminar</a></td>
                </tr>";
        }
        echo "</tbody></table>";
        echo "<a href='" . BASE_URL . "/controlador/controladores_adm/controlador_inventario/InventarioControlador.php?agregar=vista_inventario' class='add-btn'>Agregar Producto</a>";
    } else {
        echo "<p>No hay productos en el inventario</p>";
    }
    ?>
</body>
</html>

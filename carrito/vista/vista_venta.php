<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
    
require_once __DIR__ . '/../controlador/controlador_venta.php';

// Obtener inventario, carrito y métodos de pago
$inventario = ControladorVenta::mostrarInventario();
$carrito = ControladorVenta::mostrarCarrito();
$metodos = ControladorVenta::mostrarMetodosPago();

$resumen = false;
$metodoSeleccionado = null;
$compraFinalizada = false;
$detalleCompra = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['agregar'])) {
        ControladorVenta::agregarAlCarrito($_POST['id_inventario'], $_POST['cantidad']);
        header("Location: vista_venta.php");
        exit;
    } elseif (isset($_POST['eliminar'])) {
        ControladorVenta::eliminarDelCarrito($_POST['id_inventario']);
        header("Location: vista_venta.php");
        exit;
    } elseif (isset($_POST['vaciar_carrito'])) {
        ControladorVenta::vaciarCarrito();
        header("Location: vista_venta.php");
        exit;
    } elseif (isset($_POST['ver_resumen'])) {
        foreach ($metodos as $m) {
            if ($m['id_metodo_pago'] == $_POST['metodo_pago']) {
                $metodoSeleccionado = $m;
                break;
            }
        }
        $resumen = true;
    } elseif (isset($_POST['confirmar_compra'])) {
        $id_metodo = $_POST['id_metodo_pago'];
        $detalleCompra = ControladorVenta::confirmarCompra($id_metodo);
        if ($detalleCompra) $compraFinalizada = true;
        $resumen = false;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Sistema de Compras - Inventario</title>
<style>
    body { font-family: 'Poppins', sans-serif; background: #ffe6f2; margin: 0; padding: 0; display: flex; flex-direction: column; align-items: center; color: #333; }
    h2 { color: #d63384; text-align: center; margin-top: 30px; }
    table { border-collapse: collapse; width: 80%; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 0 10px rgba(0,0,0,0.1); margin: 20px auto; }
    th { background: #ffb6c1; color: #fff; font-weight: bold; padding: 10px; }
    td { border-bottom: 1px solid #f2f2f2; padding: 10px; text-align: center; }
    tr:hover { background: #fff0f5; }
    button { background: #ff66b2; color: white; border: none; padding: 8px 14px; border-radius: 6px; cursor: pointer; transition: 0.3s; }
    button:hover { background: #ff3385; }
    input[type=number] { width: 60px; text-align: center; border-radius: 6px; border: 1px solid #ccc; padding: 4px; }
    select { padding: 8px; border-radius: 6px; border: 1px solid #ccc; background: #fff; cursor: pointer; }
    .resumen, .gracias { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 0 15px rgba(0,0,0,0.1); width: 70%; margin: 20px auto; text-align: center; }
    .resumen table, .gracias table { width: 100%; margin-top: 10px; }
    .resumen th, .gracias th { background: #ff99cc; }
    .total { font-size: 18px; color: #d63384; font-weight: bold; }
    form { display: inline-block; margin: 0; }
    .botones { text-align: center; margin-top: 10px; }
    .vaciar { background: #ff9999; }
    .vaciar:hover { background: #ff6666; }
</style>
</head>
<body>

<h2>productos</h2>
<table>
<tr><th>ID</th><th>Nombre</th><th>Precio Venta</th><th>cantidad</th><th>Agregar</th></tr>
<?php if (!empty($inventario)): ?>
    <?php foreach ($inventario as $item): ?>
    <tr>
        <form method="POST">
            <td><?= $item['id_inventario'] ?></td>
            <td><?= htmlspecialchars($item['nombre_producto']) ?></td>
            <td>$<?= number_format($item['precio_venta'], 2) ?></td>
            <td><input type="number" name="cantidad" value="1" min="1" max="<?= $item['stock'] ?>" required></td>
            <input type="hidden" name="id_inventario" value="<?= $item['id_inventario'] ?>">
            <td><button type="submit" name="agregar">Agregar</button></td>
        </form>
    </tr>
    <?php endforeach; ?>
<?php else: ?>
<tr><td colspan="5">No hay productos en inventario.</td></tr>
<?php endif; ?>
</table>

<h2>Carrito de Compras</h2>
<?php if (!empty($carrito)): ?>
<div class="botones">
    <form method="POST">
        <button type="submit" name="vaciar_carrito" class="vaciar">Vaciar Carrito</button>
    </form>
</div>

<table>
<tr><th>Producto</th><th>Cantidad</th><th>Precio Unitario</th><th>Subtotal</th><th>Eliminar</th></tr>
<?php 
$total = 0;
foreach ($carrito as $c):
    $total += $c['subtotal'];
?>
<tr>
    <td><?= htmlspecialchars($c['nombre_producto']) ?></td>
    <td><?= $c['cantidad'] ?></td>
    <td>$<?= number_format($c['precio_unitario'], 2) ?></td>
    <td>$<?= number_format($c['subtotal'], 2) ?></td>
    <td>
        <form method="POST">
            <input type="hidden" name="id_inventario" value="<?= $c['id_inventario'] ?>">
            <button type="submit" name="eliminar">Eliminar</button>
        </form>
    </td>
</tr>
<?php endforeach; ?>
<tr>
    <td colspan="3" class="total">Total:</td>
    <td colspan="2" class="total">$<?= number_format($total,2) ?></td>
</tr>
</table>

<?php if (!$compraFinalizada): ?>
<div class="botones">
<form method="POST">
    <label for="metodo_pago"><b>Método de pago:</b></label>
    <select name="metodo_pago" required>
        <option value="">Seleccionar...</option>
        <?php foreach ($metodos as $m): ?>
            <?php if ($m['activo']): ?>
            <option value="<?= $m['id_metodo_pago'] ?>"><?= htmlspecialchars($m['metodo_pago']) ?>
                <?php if ($m['decremento'] > 0) echo "(Descuento {$m['decremento']}%)"; ?>
                <?php if ($m['incremento'] > 0) echo "(Recargo {$m['incremento']}%)"; ?>
            </option>
            <?php endif; ?>
        <?php endforeach; ?>
    </select>
    <button type="submit" name="ver_resumen">Ver Resumen</button>
</form>
</div>
<?php endif; ?>
<?php endif; ?>

<?php if ($resumen && $metodoSeleccionado): ?>
<div class="resumen">
    <h2>Resumen de la Compra</h2>
    <p><b>Método de pago:</b> <?= htmlspecialchars($metodoSeleccionado['metodo_pago']) ?>
        <?php if ($metodoSeleccionado['decremento'] > 0) echo "(Descuento {$metodoSeleccionado['decremento']}%)"; ?>
        <?php if ($metodoSeleccionado['incremento'] > 0) echo "(Recargo {$metodoSeleccionado['incremento']}%)"; ?>
    </p>
    <table>
        <tr><th>Producto</th><th>Cantidad</th><th>Precio Unitario</th><th>Subtotal</th></tr>
        <?php foreach ($carrito as $c): ?>
        <tr>
            <td><?= htmlspecialchars($c['nombre_producto']) ?></td>
            <td><?= $c['cantidad'] ?></td>
            <td>$<?= number_format($c['precio_unitario'], 2) ?></td>
            <td>$<?= number_format($c['subtotal'], 2) ?></td>
        </tr>
        <?php endforeach; ?>
        <tr><td colspan="3" class="total">Total:</td><td class="total">$<?= number_format($total,2) ?></td></tr>
        <?php
        $totalFinal = $total;
        if ($metodoSeleccionado['incremento'] > 0) $totalFinal += $total * $metodoSeleccionado['incremento']/100;
        if ($metodoSeleccionado['decremento'] > 0) $totalFinal -= $total * $metodoSeleccionado['decremento']/100;
        ?>
        <tr><td colspan="3" class="total">Total Final:</td><td class="total">$<?= number_format($totalFinal,2) ?></td></tr>
    </table>
    <form method="POST">
        <input type="hidden" name="id_metodo_pago" value="<?= $metodoSeleccionado['id_metodo_pago'] ?>">
        <button type="submit" name="confirmar_compra">Confirmar Compra</button>
    </form>
</div>
<?php endif; ?>

<?php if ($compraFinalizada && $detalleCompra): ?>
<div class="gracias">
    <h2>¡Gracias por su compra!</h2>
    <p><b>N° de Venta:</b> #<?= $detalleCompra['id_caja_product'] ?></p>
    <p><b>Método de pago:</b> <?= htmlspecialchars($detalleCompra['metodo']['metodo_pago']) ?></p>
    <table>
        <tr><th>Producto</th><th>Cantidad</th><th>Precio Unitario</th><th>Subtotal</th></tr>
        <?php foreach ($detalleCompra['items'] as $item): ?>
        <tr>
            <td><?= htmlspecialchars($item['nombre_producto']) ?></td>
            <td><?= $item['cantidad'] ?></td>
            <td>$<?= number_format($item['precio_unitario'], 2) ?></td>
            <td>$<?= number_format($item['subtotal'], 2) ?></td>
        </tr>
        <?php endforeach; ?>
        <tr><td colspan="3" class="total">Total Final:</td><td class="total">$<?= number_format($detalleCompra['total'],2) ?></td></tr>
    </table>
</div>
<?php endif; ?>

</body>
</html>
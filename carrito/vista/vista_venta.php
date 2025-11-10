<?php
require_once __DIR__ . '/../controlador/controlador_venta.php';

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
<title>Ventas - RoseSpa</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
:root {
  --bg: #1e1e2f;
  --bg-sidebar: #2a2a3d;
  --primary: #ff6b9d;
  --primary-dark: #e05585;
  --text: #f1f1f1;
  --text-muted: #aaa;
  --card: #2e2e44;
  --danger: #e74c3c;
  --warning: #f39c12;
  --success: #27ae60;
  --shadow: 0 4px 12px rgba(0,0,0,0.3);
}
* { margin:0; padding:0; box-sizing:border-box; }
body {
  font-family: 'Segoe UI', sans-serif;
  background: url('../../imagenes/lugares/istockphoto-1856117770-612x612.jpg') no-repeat center center fixed;
  background-size: cover;
  color: var(--text);
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  align-items: center;
}
body::before {
  content: "";
  position: fixed;
  top:0; left:0; right:0; bottom:0;
  background: rgba(0,0,0,0.6);
  z-index: -1;
}
h2 {
  color: var(--primary);
  text-align: center;
  margin: 30px 0 15px;
  text-shadow: 2px 2px 6px rgba(0,0,0,0.5);
}
table {
  width: 85%;
  border-collapse: collapse;
  background: rgba(46,46,68,0.9);
  border-radius: 8px;
  box-shadow: var(--shadow);
  margin: 15px 0;
  overflow: hidden;
}
th, td {
  padding: 14px 16px;
  text-align: center;
  font-size: 0.95rem;
}
th {
  background: var(--primary-dark);
  color: #fff;
  font-weight: 600;
}
tr:nth-child(even) { background: rgba(37,37,56,0.9); }
tr:hover { background: rgba(255,107,157,0.1); }
button {
  background: var(--primary);
  color: #fff;
  border: none;
  padding: 8px 14px;
  border-radius: 6px;
  font-weight: 600;
  cursor: pointer;
  box-shadow: var(--shadow);
  transition: .3s;
}
button:hover { background: var(--primary-dark); }
.vaciar { background: var(--danger); }
.vaciar:hover { opacity: .85; }
input[type=number], select {
  border-radius: 6px;
  border: none;
  padding: 6px 10px;
  font-size: 0.9rem;
  text-align: center;
  background: rgba(255,255,255,0.8);
  color: #000;
}
.resumen, .gracias {
  background: rgba(46,46,68,0.9);
  padding: 25px;
  border-radius: 12px;
  box-shadow: var(--shadow);
  width: 80%;
  margin: 20px 0;
  text-align: center;
}
.total {
  font-size: 18px;
  color: var(--primary);
  font-weight: bold;
}
form { display: inline-block; margin: 0; }
.botones { text-align: center; margin-top: 10px; }
a.volver {
  display:inline-block;
  margin-top:20px;
  background: var(--primary);
  color:#fff;
  padding:10px 20px;
  border-radius:8px;
  text-decoration:none;
  font-weight:600;
  box-shadow: var(--shadow);
  transition:.3s;
}
a.volver:hover { background: var(--primary-dark); }
</style>
</head>
<body>

<h2><i class="fas fa-boxes"></i> Productos</h2>
<table>
<tr><th>ID</th><th>Nombre</th><th>Precio Venta</th><th>Cantidad</th><th>Agregar</th></tr>
<?php if (!empty($inventario)): ?>
    <?php foreach ($inventario as $item): ?>
    <tr>
        <form method="POST">
            <td><?= $item['id_inventario'] ?></td>
            <td><?= htmlspecialchars($item['nombre_producto']) ?></td>
            <td>$<?= number_format($item['precio_venta'], 2) ?></td>
            <td><input type="number" name="cantidad" value="1" min="1" max="<?= $item['stock'] ?>" required></td>
            <input type="hidden" name="id_inventario" value="<?= $item['id_inventario'] ?>">
            <td><button type="submit" name="agregar"><i class="fas fa-cart-plus"></i> Agregar</button></td>
        </form>
    </tr>
    <?php endforeach; ?>
<?php else: ?>
<tr><td colspan="5">No hay productos en inventario.</td></tr>
<?php endif; ?>
</table>

<h2><i class="fas fa-shopping-cart"></i> Carrito de Compras</h2>
<?php if (!empty($carrito)): ?>
<div class="botones">
    <form method="POST">
        <button type="submit" name="vaciar_carrito" class="vaciar"><i class="fas fa-trash"></i> Vaciar Carrito</button>
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
            <button type="submit" name="eliminar"><i class="fas fa-times"></i></button>
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
    <button type="submit" name="ver_resumen"><i class="fas fa-eye"></i> Ver Resumen</button>
</form>
</div>
<?php endif; ?>
<?php endif; ?>

<?php if ($resumen && $metodoSeleccionado): ?>
<div class="resumen">
    <h2><i class="fas fa-file-invoice-dollar"></i> Resumen de la Compra</h2>
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
        <button type="submit" name="confirmar_compra"><i class="fas fa-check"></i> Confirmar Compra</button>
    </form>
</div>
<?php endif; ?>

<?php if ($compraFinalizada && $detalleCompra): ?>
<div class="gracias">
    <h2><i class="fas fa-heart"></i> ¡Gracias por su compra!</h2>
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
    <a href="vista_venta.php" class="volver"><i class="fas fa-arrow-left"></i> Volver a la tienda</a>
</div>
<?php endif; ?>

</body>
</html>

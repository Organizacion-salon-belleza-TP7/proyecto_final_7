<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php'); // ✅ conexión $conn
require_once(ROOT_PATH . '/modelo/modelo_cliente/modelo_venta_productos/modelo_venta.php');
require_once(ROOT_PATH . '/controlador/controladores_cliente/controlador_venta_producto/controlador_venta.php');

session_start();

// ✅ Comprobar sesión activa
if (!isset($_SESSION['user'])) {
    header("Location: " . BASE_URL . "/vista/vista_login/vista_login.php");
    exit;
}

$id_sesion = session_id();
$modelo = new ModeloVenta($conn);
$controlador = new ControladorVenta($modelo);

$inventario = $controlador->mostrarInventario();
$carrito = $controlador->mostrarCarrito($id_sesion);
$metodos_pago = $controlador->mostrarMetodosPago();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Venta de Productos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ffe4f2;
            margin: 0;
            padding: 20px;
        }
        h2 {
            background-color: #ff66b2;
            color: white;
            padding: 10px;
            border-radius: 8px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #f8a1d1;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #ffb6c1;
        }
        button {
            background-color: #ff66b2;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 6px;
            cursor: pointer;
            transition: 0.3s;
        }
        button:hover { background-color: #ff3385; }
        select, input[type="number"] {
            padding: 5px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body>

<h2>🛍️ Inventario</h2>
<table>
    <tr>
        <th>Nombre</th>
        <th>Stock</th>
        <th>Precio</th>
        <th>Acción</th>
    </tr>
    <?php foreach ($inventario as $prod): ?>
        <tr>
            <td><?= htmlspecialchars($prod['nombre_producto']) ?></td>
            <td><?= htmlspecialchars($prod['stock']) ?></td>
            <td>$<?= htmlspecialchars($prod['precio_venta']) ?></td>
            <td>
                <form method="POST" action="<?= BASE_URL ?>/controlador/controlador_cliente/controlador_venta_producto/controlador_venta.php">
                    <input type="hidden" name="accion" value="agregar">
                    <input type="hidden" name="id_producto" value="<?= $prod['id_inventario'] ?>">
                    <input type="number" name="cantidad" value="1" min="1" max="<?= $prod['stock'] ?>" required>
                    <button type="submit">Agregar</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<h2>🛒 Carrito</h2>
<table>
    <tr>
        <th>Producto</th>
        <th>Cantidad</th>
        <th>Subtotal</th>
        <th>Acción</th>
    </tr>
    <?php
    $total = 0;
    foreach ($carrito as $item):
        $subtotal = $item['cantidad'] * $item['precio_unitario'];
        $total += $subtotal;
    ?>
        <tr>
            <td><?= htmlspecialchars($item['nombre_producto']) ?></td>
            <td><?= htmlspecialchars($item['cantidad']) ?></td>
            <td>$<?= number_format($subtotal, 2) ?></td>
            <td>
                <form method="POST" action="<?= BASE_URL ?>/controlador/controlador_cliente/controlador_venta_producto/controlador_venta.php">
                    <input type="hidden" name="accion" value="eliminar">
                    <input type="hidden" name="id_producto" value="<?= $item['id_inventario'] ?>">
                    <button type="submit">Eliminar</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    <tr>
        <th colspan="2">Total</th>
        <th colspan="2">$<?= number_format($total, 2) ?></th>
    </tr>
</table>

<h2>💳 Finalizar Compra</h2>
<form method="POST" action="<?= BASE_URL ?>/controlador/controlador_cliente/controlador_venta_producto/controlador_venta.php">
    <input type="hidden" name="accion" value="finalizar">
    <label for="id_metodo_pago">Método de pago:</label>
    <select name="id_metodo_pago" id="id_metodo_pago" required>
        <option value="">Seleccione...</option>
        <?php foreach ($metodos_pago as $m): ?>
            <option value="<?= $m['id_metodo_pago'] ?>">
                <?= htmlspecialchars($m['metodo_pago']) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <button type="submit">Pagar</button>
</form>

</body>
</html>

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');
require_once(ROOT_PATH . '/modelo/modelo_cliente/modelo_venta_productos/modelo_venta.php');
require_once(ROOT_PATH . '/controlador/controladores_cliente/controlador_venta_producto/controlador_venta.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    header("Location: " . BASE_URL . "/vista/vista_login/vista_login.php");
    exit;
}

$id_sesion = session_id();
$modelo = new ModeloVenta($conn);
$controlador = new ControladorVenta($modelo);

$inventario = $controlador->mostrarInventario();
$carrito = $controlador->mostrarCarrito($id_sesion);
$metodos = $controlador->mostrarMetodosPago();

$resumen = false;
$metodoSeleccionado = null;
$compraFinalizada = false;
$detalleCompra = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['agregar'])) {
        $id_inventario = $_POST['id_inventario'];
        $cantidad = $_POST['cantidad'];
        $modelo->agregarAlCarrito($id_sesion, $id_inventario, $cantidad);
        header("Location: vista_venta.php");
        exit;
    }
    if (isset($_POST['eliminar'])) {
        $id_inventario = $_POST['id_inventario'];
        $modelo->eliminarDelCarrito($id_sesion, $id_inventario);
        header("Location: vista_venta.php");
        exit;
    }
    if (isset($_POST['vaciar_carrito'])) {
        $modelo->vaciarCarrito($id_sesion);
        header("Location: vista_venta.php");
        exit;
    }
    if (isset($_POST['ver_resumen'])) {
        foreach ($metodos as $m) {
            if ($m['id_metodo_pago'] == $_POST['metodo_pago']) {
                $metodoSeleccionado = $m;
                break;
            }
        }
        $resumen = true;
    }
    if (isset($_POST['confirmar_compra'])) {
        $detalleCompra = $modelo->finalizarCompra($id_sesion, $_POST['id_metodo_pago']);
        if ($detalleCompra) $compraFinalizada = true;
        $resumen = false;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
      --success: #27ae60;
      --info: #3498db;
      --shadow: 0 4px 12px rgba(0,0,0,0.3);
    }

    *{margin:0;padding:0;box-sizing:border-box;}
    body{
      font-family: 'Segoe UI', sans-serif;
      background: url('../../imagenes/lugares/istockphoto-1856117770-612x612.jpg') no-repeat center center fixed;
      background-size: cover;
      color: var(--text);
      display: flex;
      min-height: 100vh;
      position: relative;
      z-index: 1;
    }

    body::before {
      content: "";
      position: fixed;
      top:0; left:0; right:0; bottom:0;
      background: rgba(0,0,0,0.6);
      z-index: -1;
    }

    /* Sidebar */
    .sidebar{
      width: 240px;
      background: rgba(42,42,61,0.9);
      padding: 20px;
      display:flex;
      flex-direction:column;
      box-shadow: var(--shadow);
      position: fixed;
      top:0;left:0;bottom:0;
      transition: transform .3s ease;
      z-index: 1000;
      overflow-y: auto;
    }
    .sidebar h2{
      color: var(--primary);
      margin-bottom: 30px;
      text-align: center;
      font-size: 1.4rem;
    }
    .sidebar a{
      display:flex;
      align-items:center;
      gap:10px;
      color: var(--text);
      text-decoration:none;
      padding:14px;
      border-radius:8px;
      margin-bottom:8px;
      transition:.3s;
      font-weight: 500;
    }
    .sidebar a:hover{
      background: var(--primary);
      color:#fff;
      transform: translateX(5px);
    }
    .sidebar a i {
      font-size: 1.3rem;
    }

    /* Sidebar oculto */
    .sidebar.hidden {
      transform: translateX(-100%);
    }

    /* Botón toggle */
    .toggle-btn{
      position: fixed;
      top: 20px;
      left: 20px;
      background: rgba(255, 107, 157, 0.8);
      color:#fff;
      border:none;
      padding:12px 16px;
      font-size:1.5rem;
      border-radius:10px;
      cursor:pointer;
      z-index:1100;
      transition:.3s;
      box-shadow: var(--shadow);
    }
    .toggle-btn:hover{
      background: var(--primary-dark);
      transform: scale(1);
    }

    /* Content */
    .content{
      margin-left: 240px;
      flex:1;
      padding:30px;
      transition: margin-left .3s ease;
      width: 100%;
    }
    .content.expanded{
      margin-left: 0;
    }

    h1{
      font-size:2.3rem;
      margin-bottom:25px;
      color: var(--primary);
      text-shadow: 2px 2px 8px rgba(0,0,0,0.6);
      text-align: center;
    }

    /* Tables */
    table{
      width:100%;
      border-collapse:collapse;
      background: rgba(46,46,68,0.95);
      border-radius:10px;
      overflow:hidden;
      box-shadow: var(--shadow);
      margin:20px 0;
    }
    th,td{
      padding:16px;
      text-align:center;
      font-size:1rem;
    }
    th{
      background: var(--primary-dark);
      color:#fff;
      font-weight:600;
      text-transform: uppercase;
      letter-spacing: 1px;
    }
    tr:nth-child(even){background: rgba(37,37,56,0.9);}
    tr:hover{background: rgba(255,107,157,0.15);}

    /* Botones */
    button, .btn {
      background: var(--primary);
      color: #fff;
      border: none;
      padding: 10px 18px;
      border-radius: 8px;
      font-weight: 600;
      cursor: pointer;
      transition: .3s;
      box-shadow: var(--shadow);
    }
    button:hover, .btn:hover {
      background: var(--primary-dark);
      transform: translateY(-2px);
    }
    .vaciar { background: var(--danger); }
    .vaciar:hover { background: #c0392b; }

    input[type=number], select {
      padding: 10px;
      border-radius: 8px;
      border: none;
      background: rgba(255,255,255,0.9);
      color: #000;
      font-size: 1rem;
    }

    .resumen, .gracias {
      background: rgba(46,46,68,0.95);
      padding: 30px;
      border-radius: 15px;
      text-align: center;
      box-shadow: 0 15px 35px rgba(0,0,0,0.5);
      margin: 30px 0;
    }

    .total {
      font-size: 1.4rem;
      color: #ffd700;
      font-weight: bold;
    }

    .no-items {
      background: rgba(46,46,68,0.95);
      padding: 25px;
      border-radius: 12px;
      text-align: center;
      font-size: 1.2rem;
      color: #f39c12;
      box-shadow: var(--shadow);
    }
  </style>
</head>
<body>
  <!-- Botón Toggle -->
  <button class="toggle-btn" onclick="toggleSidebar()">
    <i class="fas fa-bars"></i>
  </button>

  <!-- Sidebar -->
  <div class="sidebar" id="sidebar">
    <h2>RoseSpa</h2>
    <a href="<?= BASE_URL ?>/vista/vista_cliente/vista_citas/layout.php"><i class="fas fa-spa"></i> Reservar cita</a>
    <a href="<?= BASE_URL ?>/vista/vista_cliente/vista_venta_productos/vista_venta.php"><i class="fas fa-boxes"></i>Comprar Productos</a>
    <a href="<?= BASE_URL ?>/controlador/controladores_adm/controlador_logout/controlador_logout.php?logout=vista_inicio_adm"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a>
  </div>


  <!-- Contenido -->
  <div class="content" id="content">
    <h1>Comprar Productos</h1>

    <!-- Productos Disponibles -->
    <h2 style="color:var(--primary); margin:25px 0 15px; text-align:center;">Productos Disponibles</h2>
    <table>
      <tr><th>ID</th><th>Nombre</th><th>Precio</th><th>Stock</th><th>Cantidad</th><th>Agregar</th></tr>
      <?php if (!empty($inventario)): ?>
        <?php foreach ($inventario as $item): ?>
        <tr>
          <form method="POST">
            <td><?= $item['id_inventario'] ?></td>
            <td><?= htmlspecialchars($item['nombre_producto']) ?></td>
            <td>$<?= number_format($item['precio_venta'], 2) ?></td>
            <td><?= $item['stock'] ?></td>
            <td><input type="number" name="cantidad" value="1" min="1" max="<?= $item['stock'] ?>" required style="width:70px;"></td>
            <input type="hidden" name="id_inventario" value="<?= $item['id_inventario'] ?>">
            <td><button type="submit" name="agregar">Agregar</button></td>
          </form>
        </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr><td colspan="6" class="no-items">No hay productos disponibles.</td></tr>
      <?php endif; ?>
    </table>

    <!-- Carrito -->
    <?php if (!empty($carrito)): ?>
      <div style="text-align:center; margin:20px 0;">
        <form method="POST" style="display:inline;">
          <button type="submit" name="vaciar_carrito" class="vaciar" onclick="return confirm('¿Vaciar todo el carrito?')">Vaciar Carrito</button>
        </form>
      </div>

      <h2 style="color:var(--primary); margin:25px 0 15px; text-align:center;">Carrito de Compras</h2>
      <table>
        <tr><th>Producto</th><th>Cantidad</th><th>Precio Unit.</th><th>Subtotal</th><th>Acción</th></tr>
        <?php $total = 0; foreach ($carrito as $c): $total += $c['subtotal']; ?>
        <tr>
          <td><?= htmlspecialchars($c['nombre_producto']) ?></td>
          <td><?= $c['cantidad'] ?></td>
          <td>$<?= number_format($c['precio_unitario'], 2) ?></td>
          <td>$<?= number_format($c['subtotal'], 2) ?></td>
          <td>
            <form method="POST" style="display:inline;">
              <input type="hidden" name="id_inventario" value="<?= $c['id_inventario'] ?>">
              <button type="submit" name="eliminar" style="background:var(--danger);">Eliminar</button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
        <tr><td colspan="4" class="total">TOTAL:</td><td class="total">$<?= number_format($total, 2) ?></td></tr>
      </table>

      <div style="text-align:center; margin:30px 0;">
        <form method="POST">
          <label><b>Método de pago:</b></label>
          <select name="metodo_pago" required style="padding:12px; border-radius:8px; margin:0 15px;">
            <option value="">Seleccionar...</option>
            <?php foreach ($metodos as $m): if ($m['activo']): ?>
              <option value="<?= $m['id_metodo_pago'] ?>">
                <?= htmlspecialchars($m['metodo_pago']) ?>
                <?= $m['decremento'] > 0 ? "(Desc. {$m['decremento']}%)" : "" ?>
                <?= $m['incremento'] > 0 ? "(+{$m['incremento']}%)" : "" ?>
              </option>
            <?php endif; endforeach; ?>
          </select>
          <button type="submit" name="ver_resumen">Ver Resumen</button>
        </form>
      </div>
    <?php else: ?>
      <p class="no-items">Tu carrito está vacío</p>
    <?php endif; ?>

    <!-- Resumen -->
    <?php if ($resumen && $metodoSeleccionado): ?>
    <div class="resumen">
      <h2>Resumen de Compra</h2>
      <p><b>Método:</b> <?= htmlspecialchars($metodoSeleccionado['metodo_pago']) ?></p>
      <table>
        <tr><th>Producto</th><th>Cant.</th><th>P. Unit.</th><th>Subtotal</th></tr>
        <?php foreach ($carrito as $c): ?>
        <tr>
          <td><?= htmlspecialchars($c['nombre_producto']) ?></td>
          <td><?= $c['cantidad'] ?></td>
          <td>$<?= number_format($c['precio_unitario'], 2) ?></td>
          <td>$<?= number_format($c['subtotal'], 2) ?></td>
        </tr>
        <?php endforeach; ?>
        <tr><td colspan="3" class="total">Total:</td><td>$<?= number_format($total, 2) ?></td></tr>
        <?php
        $totalFinal = $total;
        if ($metodoSeleccionado['incremento'] > 0) $totalFinal += $total * $metodoSeleccionado['incremento']/100;
        if ($metodoSeleccionado['decremento'] > 0) $totalFinal -= $total * $metodoSeleccionado['decremento']/100;
        ?>
        <tr><td colspan="3" class="total">TOTAL FINAL:</td><td class="total">$<?= number_format($totalFinal, 2) ?></td></tr>
      </table>
      <form method="POST">
        <input type="hidden" name="id_metodo_pago" value="<?= $metodoSeleccionado['id_metodo_pago'] ?>">
        <button type="submit" name="confirmar_compra" style="font-size:1.3em; padding:15px 40px; margin-top:20px;">Confirmar Compra</button>
      </form>
    </div>
    <?php endif; ?>

    <!-- Compra Exitosa -->
    <?php if ($compraFinalizada && $detalleCompra): ?>
    <div class="gracias">
      <h2>¡COMPRA EXITOSA!</h2>
      <p><b>N° de Venta:</b> #<?= $detalleCompra['id_caja'] ?></p>
      <p><b>Total pagado:</b> $<span style="color:#ffd700; font-size:1.5em;"><?= number_format($detalleCompra['total'], 2) ?></span></p>
      <p><b>Método:</b> <?= htmlspecialchars($detalleCompra['metodo']['metodo_pago']) ?></p>
      <a href="vista_venta.php" style="display:inline-block; margin-top:25px; padding:15px 35px; background:var(--primary); color:white; border-radius:50px; text-decoration:none; font-weight:600;">Volver a Comprar</a>
    </div>
    <?php endif; ?>

  </div>

  <script>
    function toggleSidebar() {
      const sidebar = document.getElementById('sidebar');
      const content = document.getElementById('content');
      sidebar.classList.toggle('hidden');
      content.classList.toggle('expanded');
    }
  </script>

</body>
</html>
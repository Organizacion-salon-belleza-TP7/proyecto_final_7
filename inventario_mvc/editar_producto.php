<?php
require_once("modelo/InventarioModelo.php");
$modelo = new InventarioModelo();

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$producto = $modelo->obtener($_GET['id']);
if (!$producto) {
    echo "Producto no encontrado.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar producto</title>
</head>
<body>
  <h2>Editar producto</h2>
  <form method="POST" action="index.php" enctype="multipart/form-data">
    <input type="hidden" name="accion" value="modificar">
    <input type="hidden" name="id" value="<?= $producto['id_inventario'] ?>">

    <label>Nombre:</label>
    <input type="text" name="nombre" value="<?= $producto['nombre_producto'] ?>" required><br>

    <label>Stock:</label>
    <input type="number" name="stock" value="<?= $producto['stock'] ?>" required><br>

    <label>Vencimiento:</label>
    <input type="date" name="vencimiento" value="<?= $producto['vencimiento'] ?>" required><br>

    <label>Precio Compra:</label>
    <input type="number" step="0.01" name="precio_producto" value="<?= $producto['precio_producto'] ?>" required><br>

    <label>Precio Venta:</label>
    <input type="number" step="0.01" name="precio_venta" value="<?= $producto['precio_venta'] ?>" required><br>

    <label>Imagen:</label>
    <input type="file" name="imagen">
    <?php if (!empty($producto['imagen_producto'])): ?>
      <img src="<?= $producto['imagen_producto'] ?>" width="50">
      <input type="hidden" name="imagen_existente" value="<?= $producto['imagen_producto'] ?>">
    <?php endif; ?>
    <br>

    <label>Proveedor (ID):</label>
    <input type="number" name="proveedor" value="<?= $producto['id_proveedor'] ?>" required><br><br>

    <button type="submit">Actualizar producto</button>
  </form>

  <a href="index.php">← Volver</a>
</body>
</html>

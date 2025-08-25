<?php
require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');
require_once(ROOT_PATH . '/modelo/modelo_adm/modelo_inventario/InventarioModelo.php');

if (!isset($_GET['id'])) {
    echo "ID de producto no especificado.";
    exit;
}

$inventario_modelo = new Inventario($conn);
$resultado = $inventario_modelo->detalle_producto($_GET['id']);

if ($resultado && $resultado->num_rows > 0) {
    $producto = $resultado->fetch_assoc();
} else {
    echo "Producto no encontrado.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Detalle Producto</title>
  <style>
    body {
        font-family: Arial, sans-serif;
        margin: 20px;
        background-color: #f4f4f9;
        color: #333;
    }
    .detalle-container {
        background: #fff;
        padding: 20px;
        border-radius: 10px;
        max-width: 600px;
        margin: auto;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    h1 {
        text-align: center;
        margin-bottom: 20px;
    }
    .detalle-item {
        margin: 10px 0;
    }
    .detalle-item strong {
        display: inline-block;
        width: 150px;
    }
    .imagen-producto {
        display: flex;
        justify-content: center;
        margin-top: 20px;
    }
    .imagen-producto img {
        max-width: 200px;
        border-radius: 10px;
    }
    .acciones {
        text-align: center;
        margin-top: 20px;
    }
    .acciones a {
        margin: 0 10px;
        padding: 8px 15px;
        border-radius: 5px;
        text-decoration: none;
        color: white;
        background-color: #3498db;
    }
    .acciones a:hover {
        background-color: #2980b9;
    }
  </style>
</head>
<body>
  <div class="detalle-container">
      <h1>Detalle del Producto</h1>

      <div class="detalle-item"><strong>ID:</strong> <?= $producto['id_inventario'] ?></div>
      <div class="detalle-item"><strong>Nombre:</strong> <?= $producto['nombre_producto'] ?></div>
      <div class="detalle-item"><strong>Stock:</strong> <?= $producto['stock'] ?></div>
      <div class="detalle-item"><strong>Vencimiento:</strong> <?= $producto['vencimiento'] ?></div>
      <div class="detalle-item"><strong>Precio Compra:</strong> $<?= number_format($producto['precio_producto'], 2) ?></div>
      <div class="detalle-item"><strong>Precio Venta:</strong> $<?= number_format($producto['precio_venta'], 2) ?></div>
      <div class="detalle-item"><strong>Proveedor:</strong> <?= $producto['nombre_proveedor'] ?></div>
      

      <div class="imagen-producto">
          <img src="<?= BASE_URL ?>/imagenes/inventario/<?= $producto['imagen_producto'] ?>" alt="Imagen del producto">
      </div>

      <div class="acciones">
          <a href="<?= BASE_URL ?>/vista/vista_adm/inventario/InventarioVista.php">Volver al Inventario</a>
          <a href="<?= BASE_URL ?>/vista/vista_adm/inventario/InventarioControlador.php?id=<?= $producto['id_inventario'] ?>&modificar=vista_inventario">Modificar</a>
          <a href="<?= BASE_URL ?>/vista/vista_adm/inventario/InventarioControlador.php?id=<?= $producto['id_inventario'] ?>&eliminar=vista_inventario" onclick="return confirm('¿Seguro que deseas eliminar este producto?')">Eliminar</a>
      </div>
  </div>
</body>
</html>

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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detalle del Producto</title>

  <!-- Google Fonts: Poppins -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
    /* ===== ESTILOS GENERALES ===== */
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #fdf6f9 0%, #fceef5 100%);
      color: #333;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    /* ===== CONTENEDOR PRINCIPAL ===== */
    .detalle-container {
      background: white;
      padding: 35px;
      border-radius: 20px;
      max-width: 600px;
      width: 100%;
      box-shadow: 0 15px 35px rgba(214, 51, 132, 0.15);
      border: 1px solid #f8d7da;
      position: relative;
      overflow: hidden;
    }

    .detalle-container::before {
      content: '';
      position: absolute;
      top: 0; left: 0;
      width: 100%; height: 6px;
      background: linear-gradient(135deg, #d63384, #e91e63);
    }

    /* ===== TÍTULO ===== */
    h1 {
      text-align: center;
      color: #b21f5a;
      margin-bottom: 30px;
      font-size: 2.2rem;
      font-weight: 700;
      text-shadow: 0 2px 5px rgba(214,51,132,0.1);
    }

    /* ===== DETALLES ===== */
    .detalle-item {
      display: flex;
      justify-content: space-between;
      padding: 12px 0;
      border-bottom: 1px dashed #f8d7da;
      font-size: 1.05rem;
    }

    .detalle-item:last-child {
      border-bottom: none;
    }

    .detalle-item strong {
      color: #d63384;
      font-weight: 600;
      min-width: 140px;
    }

    .detalle-item span {
      font-weight: 500;
      color: #555;
    }

    /* Stock bajo */
    .stock-bajo {
      color: #e74c3c !important;
      font-weight: 700;
      animation: pulse 2s infinite;
    }
    @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.7; } }

    /* ===== IMAGEN ===== */
    .imagen-producto {
      text-align: center;
      margin: 25px 0;
      padding: 20px;
      background: #fdf6f9;
      border-radius: 18px;
      border: 2px dashed #f8d7da;
    }

    .imagen-producto img {
      max-width: 220px;
      max-height: 220px;
      width: auto;
      height: auto;
      border-radius: 16px;
      object-fit: cover;
      box-shadow: 0 8px 20px rgba(214,51,132,0.2);
      transition: all 0.4s ease;
    }

    .imagen-producto img:hover {
      transform: scale(1.08);
      box-shadow: 0 12px 30px rgba(214,51,132,0.3);
    }

    /* ===== BOTONES DE ACCIÓN ===== */
    .acciones {
      display: flex;
      justify-content: center;
      gap: 15px;
      margin-top: 30px;
      flex-wrap: wrap;
    }

    .btn {
      padding: 12px 24px;
      font-size: 1rem;
      font-weight: 600;
      text-decoration: none;
      color: white;
      border-radius: 30px;
      transition: all 0.4s ease;
      box-shadow: 0 6px 15px rgba(0,0,0,0.1);
      display: inline-flex;
      align-items: center;
      gap: 8px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .btn-volver {
      background: linear-gradient(135deg, #6c757d, #5a6268);
    }
    .btn-volver:hover {
      background: linear-gradient(135deg, #5a6268, #495057);
      transform: translateY(-3px);
      box-shadow: 0 10px 20px rgba(108,117,125,0.3);
    }

    .btn-modificar {
      background: linear-gradient(135deg, #f39c12, #e67e22);
    }
    .btn-modificar:hover {
      background: linear-gradient(135deg, #e67e22, #d35400);
      transform: translateY(-3px);
      box-shadow: 0 10px 20px rgba(243,156,18,0.3);
    }

    .btn-eliminar {
      background: linear-gradient(135deg, #dc3545, #c82333);
    }
    .btn-eliminar:hover {
      background: linear-gradient(135deg, #c82333, #bd2130);
      transform: translateY(-3px);
      box-shadow: 0 10px 20px rgba(220,53,69,0.3);
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 480px) {
      .detalle-container { padding: 25px; }
      h1 { font-size: 1.8rem; }
      .detalle-item { flex-direction: column; gap: 5px; }
      .detalle-item strong { min-width: auto; }
      .acciones { flex-direction: column; align-items: center; }
      .btn { width: 100%; justify-content: center; }
      .imagen-producto img { max-width: 180px; }
    }
  </style>
</head>
<body>

  <div class="detalle-container">
    <h1>Detalle del Producto</h1>

    <div class="detalle-item">
      <strong>ID:</strong>
      <span>#<?= htmlspecialchars($producto['id_inventario']) ?></span>
    </div>

    <div class="detalle-item">
      <strong>Nombre:</strong>
      <span><?= htmlspecialchars($producto['nombre_producto']) ?></span>
    </div>

    <div class="detalle-item">
      <strong>Stock:</strong>
      <span class="<?= $producto['stock'] < 10 ? 'stock-bajo' : '' ?>">
        <?= $producto['stock'] ?> <?= $producto['stock'] < 10 ? '¡STOCK BAJO!' : '' ?>
      </span>
    </div>

    <div class="detalle-item">
      <strong>Vencimiento:</strong>
      <span><?= date('d/m/Y', strtotime($producto['vencimiento'])) ?></span>
    </div>

    <div class="detalle-item">
      <strong>Precio Compra:</strong>
      <span>S/ <?= number_format($producto['precio_producto'], 2) ?></span>
    </div>

    <div class="detalle-item">
      <strong>Precio Venta:</strong>
      <span>S/ <?= number_format($producto['precio_venta'], 2) ?></span>
    </div>

    <div class="detalle-item">
      <strong>Proveedor:</strong>
      <span><?= htmlspecialchars($producto['nombre_proveedor'] ?? 'Sin proveedor') ?></span>
    </div>

    <!-- IMAGEN -->
    <div class="imagen-producto">
      <?php 
      $img_path = ROOT_PATH . "/imagenes/inventario/{$producto['imagen_producto']}";
      $default_img = BASE_URL . "/imagenes/inventario/imagenes/inventario/Shampoo-Alfakeratin-Altamoda-300-Ml-1-33663.webp.";
      $img_src = (file_exists($img_path) && $producto['imagen_producto']) ? BASE_URL . "/imagenes/inventario/{$producto['imagen_producto']}" : $default_img;
      ?>
      <img src="<?= $img_src ?>" alt="<?= htmlspecialchars($producto['nombre_producto']) ?>">
    </div>

    <!-- BOTONES -->
    <div class="acciones">
      <a href="<?= BASE_URL ?>/vista/vista_adm/inventario/InventarioVista.php" class="btn btn-volver">
        Volver
      </a>
      <a href="<?= BASE_URL ?>/vista/vista_adm/inventario/editar_producto.php?id=<?= $producto['id_inventario'] ?>&modificar=vista_inventario" class="btn btn-modificar">
        Modificar
      </a>
      <a href="<?= BASE_URL ?>/vista/vista_adm/inventario/InventarioControlador.php?id=<?= $producto['id_inventario'] ?>&eliminar=vista_inventario" 
         class="btn btn-eliminar"
         onclick="return confirm('¿Estás seguro de eliminar este producto permanentemente?')">
        Eliminar
      </a>
    </div>
  </div>

</body>
</html>
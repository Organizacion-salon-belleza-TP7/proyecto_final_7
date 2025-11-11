<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');
require_once(ROOT_PATH . '/modelo/modelo_adm/modelo_inventario/InventarioModelo.php');

$inventario_modelo = new Inventario($conn);

// Verificar que llegue el ID
if (!isset($_GET['id'])) {
    header("Location:" . BASE_URL . "/vista/vista_adm/inventario/inventarioVista.php");
    exit;
}

$id_producto = $_GET['id'];
$detalle = $inventario_modelo->formulario_modificar_producto($id_producto);
$producto = $detalle['producto'];
$proveedores = $detalle['proveedores'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Editar Producto</title>

  <!-- Google Fonts: Poppins -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
    /* ===== ESTILOS GENERALES ===== */
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #fdf6f9 0%, #fceef5 100%);
      color: #333;
      min-height: 100vh;
      padding: 20px;
    }

    /* ===== CONTENEDOR PRINCIPAL ===== */
    .form-container {
      max-width: 650px;
      margin: 40px auto;
      background: white;
      padding: 35px;
      border-radius: 20px;
      box-shadow: 0 15px 35px rgba(214, 51, 132, 0.15);
      border: 1px solid #f8d7da;
      position: relative;
      overflow: hidden;
    }

    .form-container::before {
      content: '';
      position: absolute;
      top: 0; left: 0;
      width: 100%; height: 6px;
      background: linear-gradient(135deg, #d63384, #e91e63);
    }

    /* ===== TÍTULO ===== */
    h2 {
      text-align: center;
      color: #b21f5a;
      margin-bottom: 30px;
      font-size: 2.2rem;
      font-weight: 700;
      text-shadow: 0 2px 5px rgba(214,51,132,0.1);
    }

    /* ===== FORMULARIO ===== */
    .form-group {
      margin-bottom: 20px;
    }

    .form-group label {
      display: block;
      font-weight: 600;
      color: #d63384;
      margin-bottom: 8px;
      font-size: 1rem;
    }

    .form-group input,
    .form-group select {
      width: 100%;
      padding: 12px 16px;
      border: 2px solid #f8d7da;
      border-radius: 30px;
      font-size: 1rem;
      transition: all 0.3s ease;
      outline: none;
    }

    .form-group input:focus,
    .form-group select:focus {
      border-color: #d63384;
      box-shadow: 0 0 0 3px rgba(214,51,132,0.2);
    }

    .form-group input[type="file"] {
      padding: 8px 0;
      border: none;
      background: none;
    }

    /* ===== IMAGEN ACTUAL ===== */
    .current-image {
      text-align: center;
      margin: 15px 0;
      padding: 15px;
      background: #fdf6f9;
      border-radius: 18px;
      border: 2px dashed #f8d7da;
    }

    .current-image img {
      max-width: 120px;
      max-height: 120px;
      border-radius: 14px;
      object-fit: cover;
      box-shadow: 0 6px 15px rgba(214,51,132,0.2);
      transition: all 0.3s ease;
    }

    .current-image img:hover {
      transform: scale(1.1);
      box-shadow: 0 10px 25px rgba(214,51,132,0.3);
    }

    /* ===== BOTONES ===== */
    .btn-container {
      text-align: center;
      margin-top: 30px;
    }

    .btn {
      padding: 14px 32px;
      font-size: 1.1rem;
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
      cursor: pointer;
      border: none;
    }

    .btn-submit {
      background: linear-gradient(135deg, #d63384, #e91e63);
    }
    .btn-submit:hover {
      background: linear-gradient(135deg, #c2185b, #d63384);
      transform: translateY(-3px);
      box-shadow: 0 12px 25px rgba(214,51,132,0.4);
    }

    .btn-volver {
      display: block;
      margin: 25px auto 0;
      background: linear-gradient(135deg, #6c757d, #5a6268);
      padding: 10px 20px;
      font-size: 0.95rem;
      width: fit-content;
    }
    .btn-volver:hover {
      background: linear-gradient(135deg, #5a6268, #495057);
      transform: translateY(-2px);
      box-shadow: 0 8px 18px rgba(108,117,125,0.3);
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 480px) {
      .form-container { padding: 25px; margin: 20px auto; }
      h2 { font-size: 1.8rem; }
      .form-group input, .form-group select { padding: 10px 14px; }
      .btn { padding: 12px 24px; font-size: 1rem; }
      .current-image img { max-width: 100px; }
    }
  </style>
</head>
<body>

  <div class="form-container">
    <h2>Editar producto</h2>

    <form method="POST" action="<?= BASE_URL ?>/controlador/controladores_adm/controlador_inventario/InventarioControlador.php" enctype="multipart/form-data">
      <input type="hidden" name="accion" value="modificar">
      <input type="hidden" name="id" value="<?= $producto['id_inventario'] ?>">
      <input type="hidden" name="imagen_existente" value="<?= $producto['imagen_producto'] ?>">

      <div class="form-group">
        <label>Nombre:</label>
        <input type="text" name="nombre" value="<?= htmlspecialchars($producto['nombre_producto']) ?>" required>
      </div>

      <div class="form-group">
        <label>Stock:</label>
        <input type="number" name="stock" value="<?= $producto['stock'] ?>" required>
      </div>

      <div class="form-group">
        <label>Vencimiento:</label>
        <input type="date" name="vencimiento" value="<?= $producto['vencimiento'] ?>" required>
      </div>

      <div class="form-group">
        <label>Precio Compra:</label>
        <input type="number" step="0.01" name="precio_producto" value="<?= $producto['precio_producto'] ?>" required>
      </div>

      <div class="form-group">
        <label>Precio Venta:</label>
        <input type="number" step="0.01" name="precio_venta" value="<?= $producto['precio_venta'] ?>" required>
      </div>

      <div class="form-group">
        <label>Imagen:</label>
        <input type="file" name="imagen" accept="image/*">
        <?php if (!empty($producto['imagen_producto'])): ?>
          <div class="current-image">
            <p style="margin-bottom: 10px; color: #d63384; font-weight: 500;">Imagen actual:</p>
            <?php 
            $img_path = ROOT_PATH . "/imagenes/inventario/{$producto['imagen_producto']}";
            $default_img = BASE_URL . "/imagenes/inventario/default.jpg";
            $img_src = (file_exists($img_path) && $producto['imagen_producto']) ? BASE_URL . "/imagenes/inventario/{$producto['imagen_producto']}" : $default_img;
            ?>
            <img src="<?= $img_src ?>" alt="Imagen actual">
          </div>
        <?php endif; ?>
      </div>

      <div class="form-group">
        <label>Proveedor:</label>
        <select name="proveedor" required>
          <option value="">Seleccione un proveedor</option>
          <?php 
          // Reiniciamos el puntero del resultset
          $proveedores->data_seek(0);
          while($row = $proveedores->fetch_assoc()): 
          ?>
            <option value="<?= $row['id_proveedor'] ?>" <?= ($row['id_proveedor'] == $producto['id_proveedor']) ? 'selected' : '' ?>>
              <?= htmlspecialchars($row['nombre_proveedor']) ?>
            </option>
          <?php endwhile; ?>
        </select>
      </div>

      <div class="btn-container">
        <button type="submit" class="btn btn-submit">
          Actualizar producto
        </button>
      </div>
    </form>

    <a href="<?= BASE_URL ?>/vista/vista_adm/inventario/InventarioVista.php" class="btn btn-volver">
      Volver
    </a>
  </div>

</body>
</html>
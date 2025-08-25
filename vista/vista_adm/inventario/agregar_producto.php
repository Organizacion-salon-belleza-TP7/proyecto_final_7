<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Agregar Producto</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<?php
require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');
require_once(ROOT_PATH . '/modelo/modelo_adm/modelo_inventario/InventarioModelo.php');


$inventario_modelo = new Inventario($conn);

// Obtener proveedores para el select
$proveedores = $inventario_modelo->formulario_agregar_producto();
?>

<body class="bg-gray-100 text-gray-800">

  <div class="max-w-xl mx-auto mt-10 p-8 bg-white shadow-lg rounded-lg">
    <h2 class="text-2xl font-bold mb-6 text-center text-blue-600">Agregar nuevo producto</h2>

    <form method="POST" action="<?= BASE_URL ?>/controlador/controladores_adm/controlador_inventario/inventarioControlador.php" enctype="multipart/form-data" class="space-y-4">
      <input type="hidden" name="accion" value="agregar">

      <div>
        <label class="block font-semibold mb-1">Nombre:</label>
        <input type="text" name="nombre" required class="w-full border rounded px-3 py-2">
      </div>

      <div>
        <label class="block font-semibold mb-1">Stock:</label>
        <input type="number" name="stock" required class="w-full border rounded px-3 py-2">
      </div>

      <div>
        <label class="block font-semibold mb-1">Vencimiento:</label>
        <input type="date" name="vencimiento" required class="w-full border rounded px-3 py-2">
      </div>

      <div>
        <label class="block font-semibold mb-1">Precio Compra:</label>
        <input type="number" step="0.01" name="precio_producto" required class="w-full border rounded px-3 py-2">
      </div>

      <div>
        <label class="block font-semibold mb-1">Precio Venta:</label>
        <input type="number" step="0.01" name="precio_venta" required class="w-full border rounded px-3 py-2">
      </div>

      <div>
        <label class="block font-semibold mb-1">Imagen:</label>
        <input type="file" name="imagen" class="w-full">
      </div>

      <div>
        <label class="block font-semibold mb-1">Proveedor:</label>
        <select name="proveedor" required class="w-full border rounded px-3 py-2">
          <option value="">Seleccione un proveedor</option>
          <?php while($row = $proveedores->fetch_assoc()): ?>
            <option value="<?= $row['id_proveedor'] ?>"><?= $row['nombre_proveedor'] ?></option>
          <?php endwhile; ?>
        </select>
      </div>

      <div class="text-center">
        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">Guardar</button>
      </div>
    </form>

    <div class="mt-6 text-center">
      <a href="<?= BASE_URL ?>/vista/vista_adm/inventario/InventarioVista.php" class="text-blue-500 hover:underline">← Volver</a>
    </div>
  </div>

</body>
</html>

<?php
    require_once(__DIR__ . '/../../../variable_global.php');
    require_once(ROOT_PATH . '/img'); 
    $variableimg= . BASE . "/img"
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Inventario</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">
  <div class="max-w-7xl mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6">Inventario de Productos</h1>
    <!-- Formulario para agregar -->
<div class="mb-6 p-4 bg-white rounded-2xl shadow">
  <h2 class="text-xl font-semibold mb-4">Agregar producto</h2>
  <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <input name="nombre" placeholder="Nombre" required class="p-2 border rounded" />
    <input name="stock" type="number" placeholder="Stock" required class="p-2 border rounded" />
    <input name="vencimiento" type="date" class="p-2 border rounded" />
    <input name="precio" type="number" step="0.01" placeholder="Precio" class="p-2 border rounded" />
    <input name="venta" type="number" step="0.01" placeholder="Precio Venta" class="p-2 border rounded" />
    <input name="imagen" placeholder="URL Imagen" class="p-2 border rounded" />
    <input name="proveedor" placeholder="ID Proveedor" class="p-2 border rounded" />
    <input type="hidden" name="accion" value="agregar" />
    <button class="col-span-2 bg-blue-600 text-white py-2 rounded">Guardar</button>
  </form>
</div>

    <div class="overflow-x-auto shadow-lg rounded-2xl bg-white">
      <table class="min-w-full text-left">
        <thead class="bg-blue-600 text-white">
          <tr>
            <th class="px-6 py-3">ID</th>
            <th class="px-6 py-3">Nombre</th>
            <th class="px-6 py-3">Stock</th>
            <th class="px-6 py-3">Vencimiento</th>
            <th class="px-6 py-3">Precio</th>
            <th class="px-6 py-3">Venta</th>
            <th class="px-6 py-3">Imagen</th>
            <th class="px-6 py-3">Proveedor</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          <?php foreach($datos as $row): ?>
          <tr class="hover:bg-gray-50">
            <td class="px-6 py-4"><?= $row["id_inventario"] ?></td>
            <td class="px-6 py-4"><?= htmlspecialchars($row["nombre_producto"]) ?></td>
            <td class="px-6 py-4"><?= $row["stock"] ?></td>
            <td class="px-6 py-4"><?= $row["vencimiento"] ?></td>
            <td class="px-6 py-4">€<?= $row["precio_producto"] ?></td>
            <td class="px-6 py-4">€<?= $row["precio_venta"] ?></td>
            <td class="px-6 py-4">
<img src="<?= $variableimg . htmlspecialchars($row["imagen_producto"]) ?>" class="w-12 h-12 rounded" alt="Imagen producto">            </td>
            <td class="px-6 py-4"><?= $row["id_proveedor"] ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>

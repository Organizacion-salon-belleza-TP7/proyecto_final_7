 <?php
    require_once(__DIR__ . '/../../../variable_global.php');
    require_once(ROOT_PATH . '/modelo/BD.php');
    require_once(ROOT_PATH . '/modelo/modelo_adm/servicios_combos/modelo_inicio_adm.php');
    ?>
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
          <?php
          $sql = "SELECT id_inventario, nombre_producto, stock, vencimiento, precio_producto, precio_venta, imagen_producto, id_proveedor FROM inventario";
          $resultado = $conn->query($sql);

          while($row = $resultado->fetch_assoc()):
          ?>
          <tr class="hover:bg-gray-50">
            <td class="px-6 py-4"><?php echo $row["id_inventario"]; ?></td>
            <td class="px-6 py-4"><?php echo htmlspecialchars($row["nombre_producto"]); ?></td>
            <td class="px-6 py-4"><?php echo $row["stock"]; ?></td>
            <td class="px-6 py-4"><?php echo $row["vencimiento"]; ?></td>
            <td class="px-6 py-4">€<?php echo $row["precio_producto"]; ?></td>
            <td class="px-6 py-4">€<?php echo $row["precio_venta"]; ?></td>
            <td class="px-6 py-4">
              <img src="<?php echo htmlspecialchars($row["imagen_producto"]); ?>" class="w-12 h-12 rounded" alt="Imagen producto">
            </td>
            <td class="px-6 py-4"><?php echo $row["id_proveedor"]; ?></td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>

<?php $conn->close(); ?>

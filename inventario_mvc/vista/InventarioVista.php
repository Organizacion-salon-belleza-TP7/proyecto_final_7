<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Inventario</title>
  <style>
    body {
      font-family: sans-serif;
      background-color: #f7fafc;
      color: #2d3748;
      padding: 1rem;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 1rem;
      background-color: white;
    }

    th, td {
      padding: 0.5rem;
      border: 1px solid #e2e8f0;
    }

    th {
      background-color: #edf2f7;
      text-align: left;
    }

    input, button, select {
      padding: 0.5rem;
      border: 1px solid #cbd5e0;
      border-radius: 0.25rem;
      margin-top: 0.25rem;
      width: 100%;
    }
.btn {
  padding: 0.4rem 0.8rem;
  border-radius: 0.375rem;
  font-size: 0.9rem;
  text-decoration: none;
  text-align: center;
  display: inline-block;
  font-weight: 500;
  transition: background-color 0.3s ease;
}

.btn-agregar {
  background-color: #38a169;
  color: white;
}

.btn-agregar:hover {
  background-color: #2f855a;
}

.btn-editar {
  background-color: #4299e1;
  color: white;
}

.btn-editar:hover {
  background-color: #2b6cb0;
}

.btn-eliminar {
  background-color: #e53e3e;
  color: white;
  border: none;
  cursor: pointer;
}

.btn-eliminar:hover {
  background-color: #c53030;
}

    button {
      background-color: #4299e1;
      color: white;
      cursor: pointer;
    }

    button:hover {
      background-color: #2b6cb0;
    }

    .flex {
      display: flex;
      gap: 0.5rem;
    }

    .text-blue-600 { color: #3182ce; }
    .text-red-600 { color: #e53e3e; }
    .hover\:underline:hover { text-decoration: underline; }

    .bg-white { background-color: white; }
    .rounded { border-radius: 0.5rem; }
    .shadow { box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    .text-sm { font-size: 0.875rem; }
    .mb-4 { margin-bottom: 1rem; }
    .mb-6 { margin-bottom: 1.5rem; }
    .p-2 { padding: 0.5rem; }
    .p-4 { padding: 1rem; }
    .p-6 { padding: 1.5rem; }
    .w-full { width: 100%; }
    .w-fit { width: fit-content; }
    .grid { display: grid; }
    .gap-4 { gap: 1rem; }
    .text-2xl { font-size: 1.5rem; }
    .font-bold { font-weight: bold; }
  </style>
</head>
<body>

<div class="p-4">
  <h1 class="text-2xl font-bold mb-4">Inventario</h1>
<div style="max-width: 600px; margin: 1rem auto;">
  <a href="agregar_producto.php" class="btn btn-agregar">+ Agregar producto</a>
</div>





  <!-- Tabla -->
  <table class="w-full bg-white rounded shadow text-sm">
    <thead class="bg-gray-100">
      <tr>
        <th>Nombre</th>
        <th>Stock</th>
        <th>Vencimiento</th>
        <th>Precio Compra</th>
        <th>Precio Venta</th>
        <th>Imagen</th>
        <th>Proveedor</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($inventario as $item): ?>
      <tr class="border-t hover:bg-gray-50">
        <td><?= $item['nombre_producto'] ?></td>
        <td><?= $item['stock'] ?></td>
        <td><?= $item['vencimiento'] ?></td>
        <td>$<?= $item['precio_producto'] ?></td>
        <td>$<?= $item['precio_venta'] ?></td>
        <td>
          <?php if (!empty($item['imagen_producto'])): ?>
            <img src="<?= htmlspecialchars($item['imagen_producto']) ?>" width="50" height="50" alt="Imagen producto">
          <?php else: ?>
            Sin imagen
          <?php endif; ?>
        </td>
        <td><?= $item['nombre_proveedor'] ?></td>
  <td class="flex gap-2">
<a href="editar_producto.php?id=<?= $item['id_inventario'] ?>" class="btn btn-editar">Editar</a>
  <form method="POST" onsubmit="return confirm('¿Eliminar este producto?')" style="display:inline;">
    <input type="hidden" name="id" value="<?= $item['id_inventario'] ?>">
    <input type="hidden" name="accion" value="eliminar">
    <button class="btn btn-eliminar">Eliminar</button>
  </form>
</td>

      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

</body>
</html>

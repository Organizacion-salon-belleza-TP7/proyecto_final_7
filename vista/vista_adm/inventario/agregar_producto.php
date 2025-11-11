<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Agregar Producto</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: '#EC4899',      // Rosa principal (Pink 500)
            'primary-hover': '#DB2777', // Rosa oscuro (Pink 600)
            accent: '#F43F5E',        // Rosa fucsia (Rose 500)
            light: '#FDF2F8',         // Fondo rosa claro
            gold: '#F59E0B'           // Acento dorado
          }
        }
      }
    }
  </script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<?php
require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');
require_once(ROOT_PATH . '/modelo/modelo_adm/modelo_inventario/InventarioModelo.php');

$inventario_modelo = new Inventario($conn);
$proveedores = $inventario_modelo->formulario_agregar_producto();
?>

<body class="bg-gradient-to-br from-pink-50 via-rose-50 to-pink-100 min-h-screen flex items-center justify-center p-4 font-sans">

  <div class="w-full max-w-2xl">
    <!-- Card Principal con borde rosa suave -->
    <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border border-pink-200">

      <!-- Header ROSA con degradado -->
      <div class="bg-gradient-to-r from-pink-500 to-rose-600 p-6 text-white relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
          <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=%2260%22 height=%2260%22 viewBox=%220 0 60 60%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cg fill=%22none%22 fill-rule=%22evenodd%22%3E%3Cg fill=%22%23ffffff%22 fill-opacity=%220.4%22%3E%3Ccircle cx=%2230%22 cy=%2230%22 r=%228%22/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')]"></div>
        </div>
        
        <div class="flex items-center justify-between relative z-10">
          <div>
            <h1 class="text-2xl font-bold flex items-center gap-3">
              <div class="bg-white/20 backdrop-blur-sm rounded-full p-2">
                <i class="fas fa-heart text-white"></i>
              </div>
              Agregar Producto
            </h1>
            <p class="text-pink-100 text-sm mt-1">Registra un nuevo ítem en tu inventario</p>
          </div>
          <div class="bg-white/20 backdrop-blur-sm rounded-full p-3">
            <i class="fas fa-box-open text-2xl"></i>
          </div>
        </div>
      </div>

      <!-- Formulario -->
      <form method="POST" action="<?= BASE_URL ?>/controlador/controladores_adm/controlador_inventario/inventarioControlador.php" 
            enctype="multipart/form-data" class="p-6 space-y-5">

        <input type="hidden" name="accion" value="agregar">

        <!-- Nombre -->
        <div class="group">
          <label class="flex items-center gap-2 text-sm font-semibold text-pink-800 mb-2">
            <i class="fas fa-spa text-pink-600"></i> Nombre del producto
          </label>
          <input type="text" name="nombre" required 
                 class="w-full px-4 py-3 border-2 border-pink-200 rounded-xl focus:ring-4 focus:ring-pink-300 focus:border-pink-500 transition-all duration-300 outline-none placeholder-pink-300"
                 placeholder="Ej: Crema hidratante rosa mosqueta">
        </div>

        <!-- Stock -->
        <div class="group">
          <label class="flex items-center gap-2 text-sm font-semibold text-pink-800 mb-2">
            <i class="fas fa-cubes-stacked text-pink-600"></i> Stock inicial
          </label>
          <input type="number" name="stock" required min="0"
                 class="w-full px-4 py-3 border-2 border-pink-200 rounded-xl focus:ring-4 focus:ring-pink-300 focus:border-pink-500 transition-all duration-300"
                 placeholder="0">
        </div>

        <!-- Vencimiento -->
        <div class="group">
          <label class="flex items-center gap-2 text-sm font-semibold text-pink-800 mb-2">
            <i class="fas fa-calendar-heart text-pink-600"></i> Fecha de vencimiento
          </label>
          <input type="date" name="vencimiento" required
                 class="w-full px-4 py-3 border-2 border-pink-200 rounded-xl focus:ring-4 focus:ring-pink-300 focus:border-pink-500 transition-all duration-300">
        </div>

        <!-- Precios -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
          <div class="group">
            <label class="flex items-center gap-2 text-sm font-semibold text-pink-800 mb-2">
              <i class="fas fa-tag text-green-600"></i> Precio de compra
            </label>
            <div class="relative">
              <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-pink-600 font-bold">$</span>
              <input type="number" step="0.01" name="precio_producto" required
                     class="w-full pl-8 pr-4 py-3 border-2 border-pink-200 rounded-xl focus:ring-4 focus:ring-green-300 focus:border-green-500 transition-all"
                     placeholder="0.00">
            </div>
          </div>

          <div class="group">
            <label class="flex items-center gap-2 text-sm font-semibold text-pink-800 mb-2">
              <i class="fas fa-coins text-amber-600"></i> Precio de venta
            </label>
            <div class="relative">
              <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-pink-600 font-bold">$</span>
              <input type="number" step="0.01" name="precio_venta" required
                     class="w-full pl-8 pr-4 py-3 border-2 border-pink-200 rounded-xl focus:ring-4 focus:ring-amber-300 focus:border-amber-500 transition-all"
                     placeholder="0.00">
            </div>
          </div>
        </div>

        <!-- Imagen -->
        <div class="group">
          <label class="flex items-center gap-2 text-sm font-semibold text-pink-800 mb-2">
            <i class="fas fa-image-polaroid text-pink-600"></i> Imagen del producto
          </label>
          <div class="border-3 border-dashed border-pink-300 rounded-2xl p-8 text-center hover:border-pink-500 hover:bg-pink-50 transition-all duration-300 cursor-pointer group">
            <input type="file" name="imagen" accept="image/*" class="hidden" id="file-input-rosa">
            <label for="file-input-rosa" class="cursor-pointer">
              <i class="fas fa-cloud-arrow-up text-5xl text-pink-400 mb-3 block group-hover:text-pink-600 transition-colors"></i>
              <p class="text-pink-700 font-medium">Haz clic para subir una imagen</p>
              <p class="text-xs text-pink-500 mt-1">PNG, JPG, WEBP • Máximo 5MB</p>
            </label>
          </div>
        </div>

        <!-- Proveedor -->
        <div class="group">
          <label class="flex items-center gap-2 text-sm font-semibold text-pink-800 mb-2">
            <i class="fas fa-truck-fast text-pink-600"></i> Proveedor
          </label>
          <select name="proveedor" required
                  class="w-full px-4 py-3 border-2 border-pink-200 rounded-xl focus:ring-4 focus:ring-pink-300 focus:border-pink-500 transition-all duration-300">
            <option value="">Seleccione un proveedor</option>
            <?php while($row = $proveedores->fetch_assoc()): ?>
              <option value="<?= htmlspecialchars($row['id_proveedor']) ?>">
                <?= htmlspecialchars($row['nombre_proveedor']) ?>
              </option>
            <?php endwhile; ?>
          </select>
        </div>

        <!-- Botones -->
        <div class="flex flex-col sm:flex-row gap-4 pt-6">
          <button type="submit"
                  class="flex-1 bg-gradient-to-r from-pink-500 to-rose-600 text-white font-bold py-4 px-6 rounded-xl hover:from-pink-600 hover:to-rose-700 transform hover:scale-105 transition-all duration-300 shadow-lg flex items-center justify-center gap-2 text-lg">
            <i class="fas fa-sparkles"></i>
            Guardar Producto
          </button>

          <a href="<?= BASE_URL ?>/vista/vista_adm/inventario/InventarioVista.php"
             class="flex-1 text-center bg-pink-100 text-pink-800 font-bold py-4 px-6 rounded-xl hover:bg-pink-200 transform hover:scale-105 transition-all duration-300 flex items-center justify-center gap-2">
            <i class="fas fa-arrow-left"></i>
            Volver
          </a>
        </div>
      </form>
    </div>

    <!-- Footer rosa -->
    <p class="text-center text-xs text-pink-600 mt-8 font-medium">
      <i class="fas fa-heart text-rose-500"></i> 
      Sistema de Inventario • 
      <i class="fas fa-heart text-rose-500"></i>
    </p>
  </div>

  <!-- Vista previa de imagen -->
  <script>
    document.getElementById('file-input-rosa')?.addEventListener('change', function(e) {
      const file = e.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          const container = document.querySelector('.border-dashed');
          container.innerHTML = `
            <img src="${e.target.result}" class="mx-auto max-h-48 rounded-2xl shadow-lg border-4 border-pink-200" alt="Vista previa">
            <p class="text-xs text-pink-600 mt-3 font-medium">${file.name}</p>
          `;
        }
        reader.readAsDataURL(file);
      }
    });
  </script>
</body>
</html>
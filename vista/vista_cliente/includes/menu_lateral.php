<!-- menu_lateral.php -->
<div class="fixed inset-y-0 left-0 w-80 bg-gradient-to-b from-purple-950 via-black to-purple-950 text-white shadow-2xl z-50 overflow-y-auto">
    <div class="p-8">
        <h1 class="text-5xl font-bold text-pink-400 mb-16 tracking-wider drop-shadow-lg">
            RoseSpa
        </h1>
        <nav class="space-y-6">
            <a href="<?= BASE_URL ?>/vista/vista_cliente/vista_citas/layout.php" 
               class="flex items-center gap-5 text-pink-100 hover:bg-pink-600 hover:bg-opacity-30 rounded-2xl px-7 py-5 transition-all text-xl font-medium <?= (strpos($_SERVER['REQUEST_URI'], 'layout.php') !== false) ? 'bg-pink-600 bg-opacity-50 shadow-lg' : '' ?>">
                Calendario Reservar cita
            </a>
            <a href="<?= BASE_URL ?>/vista/vista_cliente/vista_tienda/tienda.php" 
               class="flex items-center gap-5 text-pink-100 hover:bg-pink-600 hover:bg-opacity-30 rounded-2xl px-7 py-5 transition-all text-xl font-medium <?= (strpos($_SERVER['REQUEST_URI'], 'tienda.php') !== false) ? 'bg-pink-600 bg-opacity-50 shadow-lg' : '' ?>">
                Tienda Comprar Productos
            </a>
            <a href="<?= BASE_URL ?>/vista/vista_cliente/vista_inicio/vista_inicio_cli.php" 
               class="flex items-center gap-5 text-pink-100 hover:bg-pink-600 hover:bg-opacity-30 rounded-2xl px-7 py-5 transition-all text-xl font-medium <?= (strpos($_SERVER['REQUEST_URI'], 'vista_inicio_cli.php') !== false) ? 'bg-pink-600 bg-opacity-50 shadow-lg' : '' ?>">
                Lista Mis Citas Compradas
            </a>
            <a href="<?= BASE_URL ?>/controlador/cerrar_sesion.php" 
               class="flex items-center gap-5 text-pink-100 hover:bg-red-600 hover:bg-opacity-40 rounded-2xl px-7 py-5 transition-all text-xl font-medium mt-32">
                Salir Cerrar sesión
            </a>
        </nav>
    </div>
</div>

<!-- Fondo spa sutil -->
<div class="fixed inset-0 -z-10 opacity-40 pointer-events-none">
    <img src="<?= BASE_URL ?>/imagenes/lugares/istockphoto-1856117770-612x612.jpg" class="w-full h-full object-cover" alt="spa">
</div>
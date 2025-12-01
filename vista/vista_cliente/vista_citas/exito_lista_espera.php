<!-- vista_citas/exito_lista_espera.php -->
<div class="max-w-2xl mx-auto bg-white rounded-3xl shadow-2xl p-12 text-center">
    <div class="mb-8">
        <svg class="w-24 h-24 mx-auto text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
    </div>
    <h2 class="text-4xl font-bold text-pink-600 mb-4">¡Te sumamos a la lista de espera!</h2>
    <p class="text-xl text-gray-700 mb-6">
        Te avisaremos apenas se libere un turno el <br>
        <strong><?= date('d/m/Y \a \l\a\s H:i', strtotime($GLOBALS['fecha_deseada'])) ?></strong>
    </p>
    <a href="../vista_inicio/vista_inicio_cli.php" class="bg-gradient-to-r from-pink-600 to-purple-600 hover:from-pink-700 hover:to-purple-700 
           text-white font-bold text-xl px-12 py-5 rounded-full inline-block">
        Volver al inicio
    </a>
</div>
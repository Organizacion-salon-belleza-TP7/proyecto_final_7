<!-- vistas/lista_espera_form.php -->
<div class="max-w-2xl mx-auto bg-white rounded-3xl shadow-2xl p-10 text-center">
    <h2 class="text-4xl font-bold text-pink-600 mb-6">Turno Ocupado</h2>
    <p class="text-xl text-gray-700 mb-8">
        Lo sentimos, el turno del <strong><?= date('d/m/Y \a \l\a\s H:i', strtotime($fecha_deseada)) ?></strong> 
        ya está reservado.
    </p>
    <div class="bg-yellow-50 border-2 border-yellow-300 rounded-2xl p-8 mb-8">
        <p class="text-2xl font-bold text-yellow-800 mb-4">¿Querés unirte a la lista de espera?</p>
        <p class="text-lg text-gray-700">Te avisaremos apenas se libere un turno en ese horario.</p>
    </div>

    <div class="flex gap-6 justify-center">
        <form method="POST" action="layout.php?accion=unirse_lista">
            <input type="hidden" name="fecha_hora" value="<?= $fecha_deseada ?>">
            <input type="hidden" name="id_lugar" value="<?= $id_lugar_deseado ?>">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white text-xl font-bold py-5 px-12 rounded-full transition">
                Sí, unirte a la lista
            </button>
        </form>
        <a href="layout.php" class="bg-gray-600 hover:bg-gray-700 text-white text-xl font-bold py-5 px-12 rounded-full transition">
            No, volver
        </a>
    </div>
</div>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');
require_once(ROOT_PATH . '/modelo/modelo_cliente/modelo_venta/modelo_venta.php');

$modelo_venta = new venta($conn);

// Métodos de pago
$funcion_traer_metodo_pago = $modelo_venta->traer_medios_pagos();

$id_cita = $_GET['id'];

// Precio total de la cita
$funcion_traer_precio_total = $modelo_venta->traer_datos_cita($id_cita);
$total_cita = floatval($funcion_traer_precio_total);

// Obtener todos los métodos de pago con sus incrementos/decrementos para JavaScript
$metodos_pago_data = [];
$primer_metodo_id = null;
$primer_metodo_nombre = "";

if($funcion_traer_metodo_pago && $funcion_traer_metodo_pago->num_rows > 0){
    $primer_metodo = true;
    while($metodo = $funcion_traer_metodo_pago->fetch_assoc()){
        $metodos_pago_data[$metodo['id_metodo_pago']] = [
            'incremento' => $metodo['incremento'] ?? 0,
            'decremento' => $metodo['decremento'] ?? 0,
            'nombre' => $metodo['metodo_pago']
        ];
        
        // Guardar el primer método para autocompletar
        if($primer_metodo){
            $primer_metodo_id = $metodo['id_metodo_pago'];
            $primer_metodo_nombre = $metodo['metodo_pago'];
            $primer_metodo = false;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagar Cita</title>
    <style>
        .info-pago {
            margin: 10px 0;
            padding: 10px;
            border-radius: 5px;
            font-weight: bold;
        }
        .incremento {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
        }
        .descuento {
            background-color: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
        }
        .sin-cambio {
            background-color: #e2e3e5;
            border: 1px solid #d6d8db;
            color: #383d41;
        }
        .auto-complete {
            background-color: #e8f5e8;
            border: 1px solid #c8e6c9;
            color: #2e7d32;
            padding: 8px;
            margin: 10px 0;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <h1>Pagar Cita</h1>

    <div id="auto-complete-info" class="auto-complete" style="display: none;">
        <!-- Información de autocompletado -->
    </div>

    <p><strong>Total original:</strong> $<span id="total-original"><?= number_format($total_cita, 2) ?></span></p>
    
    <div id="info-pago" class="info-pago" style="display: none;">
        <!-- Aquí se mostrará la información del incremento/descuento -->
    </div>
    
    <p><strong>Total a pagar:</strong> $<span id="total-final"><?= number_format($total_cita, 2) ?></span></p>

    <form action="<?= BASE_URL ?>/controlador/controladores_cliente/controlador_venta/controlador_venta.php" method="POST" onsubmit="return validarPago()">
    <input type="hidden" name="id_cita" value="<?= htmlspecialchars($id_cita) ?>">
    <input type="hidden" name="vista_pagar" value="venta.php">

    <table border="1">
        <tr>
            <th>Medio de pago</th>
            <th>Cantidad a pagar</th>
        </tr>
        <tr>
            <td>
                <select name="id_metodo" id="id_metodo" required onchange="actualizarPrecio()">
                    <option value="">Seleccione un método de pago</option>
                    <?php
                    $funcion_traer_metodo_pago->data_seek(0);
                    if($funcion_traer_metodo_pago && $funcion_traer_metodo_pago->num_rows > 0){
                        while($bucle_metodos_pagos = $funcion_traer_metodo_pago->fetch_assoc()){
                            $info_extra = "";
                            if(!empty($bucle_metodos_pagos['incremento'])) {
                                $info_extra = " (+{$bucle_metodos_pagos['incremento']}%)";
                            } elseif(!empty($bucle_metodos_pagos['decremento'])) {
                                $info_extra = " (-{$bucle_metodos_pagos['decremento']}%)";
                            }
                            $selected = ($bucle_metodos_pagos['id_metodo_pago'] == $primer_metodo_id) ? 'selected' : '';
                            echo "<option value='{$bucle_metodos_pagos['id_metodo_pago']}' data-incremento='{$bucle_metodos_pagos['incremento']}' data-decremento='{$bucle_metodos_pagos['decremento']}' $selected>" . 
                                 htmlspecialchars($bucle_metodos_pagos['metodo_pago']) . $info_extra . "</option>";
                        }
                    } else {
                        echo "<option value=''>Error al traer métodos</option>";
                    }
                    ?>
                </select>
            </td>
            <td>
                <input type="number" name="cantidad" id="cantidad" step="0.01" min="0.01" value="<?= $total_cita ?>" required>
                <!-- Campo oculto con el valor calculado real -->
                <input type="hidden" id="cantidad-calculada" name="cantidad_calculada" value="<?= $total_cita ?>">
            </td>
        </tr>
    </table>

    <br>
    <button type="submit">Confirmar Pago</button>
</form>


    <script>
    const totalOriginal = parseFloat(<?= $total_cita ?>);
    const metodosPago = <?= json_encode($metodos_pago_data) ?>;
    const primerMetodoId = <?= $primer_metodo_id ? json_encode($primer_metodo_id) : 'null' ?>;
    const primerMetodoNombre = <?= $primer_metodo_nombre ? json_encode($primer_metodo_nombre) : '""' ?>;

    function actualizarPrecio() {
        const select = document.getElementById('id_metodo');
        const cantidadInput = document.getElementById('cantidad');
        const cantidadCalculadaInput = document.getElementById('cantidad-calculada');
        const infoPagoDiv = document.getElementById('info-pago');
        const autoCompleteDiv = document.getElementById('auto-complete-info');
        const totalFinalSpan = document.getElementById('total-final');
        const selectedOption = select.options[select.selectedIndex];
        
        console.log('Método seleccionado:', selectedOption.value); // Para debug
        
        // Ocultar información de autocompletado si el usuario cambia manualmente
        if (selectedOption.value !== primerMetodoId) {
            autoCompleteDiv.style.display = 'none';
        }
        
        if (!selectedOption.value) {
            infoPagoDiv.style.display = 'none';
            cantidadInput.value = totalOriginal.toFixed(2);
            cantidadCalculadaInput.value = totalOriginal.toFixed(2);
            totalFinalSpan.textContent = totalOriginal.toFixed(2);
            return;
        }

        const metodoId = selectedOption.value;
        const metodo = metodosPago[metodoId]; // CORREGIDO: era "metetoId"
        
        console.log('Datos del método:', metodo); // Para debug
        
        let nuevoTotal = totalOriginal;
        let mensaje = '';
        let claseCss = '';

        if (metodo.incremento > 0) {
            const incremento = (totalOriginal * metodo.incremento) / 100;
            nuevoTotal = totalOriginal + incremento;
            mensaje = `Incremento del ${metodo.incremento}%: +$${incremento.toFixed(2)}`;
            claseCss = 'incremento';
        } else if (metodo.decremento > 0) {
            const descuento = (totalOriginal * metodo.decremento) / 100;
            nuevoTotal = totalOriginal - descuento;
            mensaje = `Descuento del ${metodo.decremento}%: -$${descuento.toFixed(2)}`;
            claseCss = 'descuento';
        } else {
            mensaje = 'Sin cambios en el precio';
            claseCss = 'sin-cambio';
        }

        // Mostrar información de autocompletado si es el primer método
        if (metodoId === primerMetodoId) {
            autoCompleteDiv.innerHTML = `<strong>✓ Autocompletado:</strong> Se ha seleccionado automáticamente "${metodo.nombre}" como método de pago. Puedes cambiarlo si lo deseas.`;
            autoCompleteDiv.style.display = 'block';
        }

        // Actualizar la interfaz
        infoPagoDiv.innerHTML = `<strong>${metodo.nombre}:</strong> ${mensaje}`;
        infoPagoDiv.className = `info-pago ${claseCss}`;
        infoPagoDiv.style.display = 'block';
        
        // Actualizar AMBOS campos - el visible y el oculto
        cantidadInput.value = nuevoTotal.toFixed(2);
        cantidadCalculadaInput.value = nuevoTotal.toFixed(2);
        totalFinalSpan.textContent = nuevoTotal.toFixed(2);
        
        // Actualizar el máximo permitido
        cantidadInput.max = nuevoTotal;
        
        console.log('Nuevo total:', nuevoTotal); // Para debug
    }

    function validarPago() {
        const cantidad = parseFloat(document.getElementById('cantidad').value);
        const cantidadCalculada = parseFloat(document.getElementById('cantidad-calculada').value);
        const metodo = document.getElementById('id_metodo').value;
        const totalFinal = parseFloat(document.getElementById('total-final').textContent);
        
        if (!metodo) {
            alert('Por favor, seleccione un método de pago');
            return false;
        }
        
        if (cantidad <= 0) {
            alert('La cantidad a pagar debe ser mayor a 0');
            return false;
        }
        
        // Validar contra el valor calculado (más preciso)
        if (Math.abs(cantidad - cantidadCalculada) > 0.01) {
            alert('Debe pagar el monto exacto: $' + cantidadCalculada.toFixed(2));
            return false;
        }
        
        return true;
    }

    // Inicializar cuando carga la página
    document.addEventListener('DOMContentLoaded', function() {
        // Si hay un primer método disponible, seleccionarlo automáticamente
        if (primerMetodoId) {
            document.getElementById('id_metodo').value = primerMetodoId;
            actualizarPrecio();
        }
        
        // Agregar evento para cuando cambie la selección
        document.getElementById('id_metodo').addEventListener('change', actualizarPrecio);
        
        // Prevenir que el usuario modifique manualmente el campo de cantidad
        document.getElementById('cantidad').addEventListener('input', function(e) {
            // Restaurar el valor calculado si el usuario intenta modificarlo
            const cantidadCalculada = document.getElementById('cantidad-calculada').value;
            this.value = cantidadCalculada;
            alert('El monto se calcula automáticamente según el método de pago seleccionado.');
        });
    });
    </script>
</body>
</html>
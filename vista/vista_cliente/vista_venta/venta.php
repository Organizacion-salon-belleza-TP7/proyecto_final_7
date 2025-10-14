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

// Validación básica para evitar errores si 'id' no está seteado
$id_cita = $_GET['id'] ?? null;
if (!$id_cita) {
    // Manejo de error si falta el ID de la cita
    // Puedes redirigir o mostrar un mensaje
    // exit("Error: ID de cita no proporcionado.");
}

// Precio total de la cita
$funcion_traer_precio_total = $modelo_venta->traer_datos_cita($id_cita);
$total_cita = floatval($funcion_traer_precio_total);

// Obtener todos los métodos de pago con sus incrementos/decrementos para JavaScript
$metodos_pago_data = [];
$primer_metodo_id = null;
$primer_metodo_nombre = "";

if($funcion_traer_metodo_pago && $funcion_traer_metodo_pago->num_rows > 0){
    $primer_metodo = true;
    $temp_metodos = [];
    while($metodo = $funcion_traer_metodo_pago->fetch_assoc()){
        $metodos_pago_data[$metodo['id_metodo_pago']] = [
            'incremento' => $metodo['incremento'] ?? 0,
            'decremento' => $metodo['decremento'] ?? 0,
            'nombre' => $metodo['metodo_pago']
        ];
        $temp_metodos[] = $metodo; // Guardar para el bucle del select
        
        // Guardar el primer método para autocompletar
        if($primer_metodo){
            $primer_metodo_id = $metodo['id_metodo_pago'];
            $primer_metodo_nombre = $metodo['metodo_pago'];
            $primer_metodo = false;
        }
    }
    // Devolvemos el puntero al inicio para que el segundo bucle funcione
    $funcion_traer_metodo_pago->data_seek(0);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagar Cita</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Variables y Estilos Generales (Tomados de la plantilla RoseSpa) */
        :root {
            --bg: #1e1e2f;
            --primary: #ff6b9d;
            --primary-dark: #e05585;
            --text: #f1f1f1;
            --text-dark: #333;
            --card: #2e2e44;
            --danger: #e74c3c;
            --success: #27ae60;
            --warning: #f39c12;
            --info: #3498db;
            --shadow: 0 8px 25px rgba(0,0,0,0.5);
            --input-bg: #3c3c54;
        }
        *{margin:0;padding:0;box-sizing:border-box;}
        body{
            font-family: 'Segoe UI', sans-serif;
            background: url('../../../imagenes/lugares/istockphoto-1856117770-612x612.jpg') no-repeat center center fixed;
            background-size: cover;
            color: var(--text);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            z-index: 1;
        }

        /* Overlay oscuro */
        body::before {
            content: "";
            position: fixed;
            top:0;
            left:0;
            right:0;
            bottom:0;
            background: rgba(0,0,0,0.7);
            z-index: -1;
        }

        /* Contenedor principal de la caja de pago */
        .payment-box {
            background: rgba(46,46,68,0.95);
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: var(--shadow);
            width: 100%;
            max-width: 500px;
            animation: fadeIn 0.5s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        h1{
            font-size:2rem;
            margin-bottom:25px;
            color: var(--primary);
            text-align: center;
            text-shadow: 1px 1px 4px rgba(0,0,0,0.5);
        }

        /* Estilos de inputs y selects */
        select, input[type="number"], .btn-submit{
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 6px;
            border: 1px solid var(--primary-dark);
            background-color: var(--input-bg);
            color: var(--text);
            font-size: 1rem;
            appearance: none; /* Eliminar estilo nativo del select */
        }
        select:focus, input[type="number"]:focus{
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(255, 107, 157, 0.3);
        }
        /* Estilos específicos del select para el icono */
        .select-wrapper {
            position: relative;
            margin-bottom: 20px;
        }
        .select-wrapper::after {
            content: '\f0d7'; /* Icono de flecha hacia abajo (Font Awesome) */
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            top: 50%;
            right: 15px;
            transform: translateY(-50%);
            color: var(--text);
            pointer-events: none;
        }

        /* Información de totales */
        .total-info p {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            font-size: 1.1rem;
            border-bottom: 1px dashed rgba(255, 255, 255, 0.1);
        }
        .total-info strong {
            color: var(--primary);
        }
        #total-final {
            font-size: 1.5rem;
            color: var(--success);
            font-weight: bold;
        }
        .total-final-line {
            border-top: 2px solid var(--primary-dark) !important;
            margin-top: 10px;
            padding-top: 15px !important;
        }

        /* Tabla (la simplificamos para que se vea bien en el formulario) */
        .form-table {
            width: 100%;
            margin: 20px 0;
            border-collapse: separate;
            border-spacing: 0 10px; /* Espacio entre filas */
        }
        .form-table th, .form-table td {
            padding: 0; /* Quitamos padding en celdas */
            text-align: left;
        }
        .form-table th {
            color: var(--text);
            font-weight: 600;
            padding-bottom: 5px;
        }

        /* Botón de Confirmar Pago */
        .btn-submit{
            background: var(--success);
            color:#fff;
            font-weight: 700;
            cursor: pointer;
            transition:.3s;
            margin-top: 20px;
        }
        .btn-submit:hover{
            background: #219d53; /* Success Darker */
        }

        /* Estilos de mensajes dinámicos */
        .info-pago {
            margin: 10px 0;
            padding: 12px;
            border-radius: 8px;
            font-weight: bold;
            font-size: 0.95rem;
        }
        .incremento {
            background-color: rgba(231, 76, 60, 0.2); /* Danger light */
            border: 1px solid var(--danger);
            color: var(--danger);
        }
        .descuento {
            background-color: rgba(39, 174, 96, 0.2); /* Success light */
            border: 1px solid var(--success);
            color: var(--success);
        }
        .sin-cambio {
            background-color: rgba(52, 152, 219, 0.2); /* Info light */
            border: 1px solid var(--info);
            color: var(--info);
        }
        .auto-complete {
            background-color: rgba(255, 107, 157, 0.15); /* Primary light */
            border: 1px solid var(--primary);
            color: var(--primary);
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 8px;
            font-size: 0.9rem;
        }
        .auto-complete strong { color: var(--text); }
        
    </style>
</head>
<body>
    
    <div class="payment-box">
        <h1><i class="fas fa-credit-card"></i> Pago de Cita</h1>

        <div id="auto-complete-info" class="auto-complete" style="display: none;">
            </div>

        <div class="total-info">
            <p><strong>Total Original:</strong> $<span id="total-original"><?= number_format($total_cita, 2) ?></span></p>
        </div>
        
        <div id="info-pago" class="info-pago" style="display: none;">
            </div>
        
        <form action="<?= BASE_URL ?>/controlador/controladores_cliente/controlador_venta/controlador_venta.php" method="POST" onsubmit="return validarPago()">
            <input type="hidden" name="id_cita" value="<?= htmlspecialchars($id_cita) ?>">
            <input type="hidden" name="vista_pagar" value="venta.php">

            <div class="form-table">
                <div>
                    <div><label for="id_metodo">Método de Pago</label></div>
                    <div class="select-wrapper">
                        <select name="id_metodo" id="id_metodo" required onchange="actualizarPrecio()">
                            <option value="">Seleccione un método de pago</option>
                            <?php
                            if(!empty($temp_metodos)){
                                foreach($temp_metodos as $bucle_metodos_pagos){
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
                    </div>
                </div>
                
                <div>
                    <div><label for="cantidad">Cantidad a Pagar (Ajustado)</label></div>
                    <div>
                        <input type="number" name="cantidad" id="cantidad" step="0.01" min="0.01" value="<?= number_format($total_cita, 2, '.', '') ?>" required>
                        <input type="hidden" id="cantidad-calculada" name="cantidad_calculada" value="<?= number_format($total_cita, 2, '.', '') ?>">
                    </div>
                </div>
            </div>

            <div class="total-info total-final-line">
                <p><strong>TOTAL FINAL:</strong> $<span id="total-final"><?= number_format($total_cita, 2) ?></span></p>
            </div>
            
            <button type="submit" class="btn-submit"><i class="fas fa-check-circle"></i> Confirmar Pago</button>
        </form>
    </div>

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
        
        // Ocultar información de autocompletado si el usuario cambia manualmente
        if (selectedOption.value !== primerMetodoId && primerMetodoId !== null) {
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
        const metodo = metodosPago[metodoId];
        
        let nuevoTotal = totalOriginal;
        let mensaje = '';
        let claseCss = '';
        let cambioMonetario = 0;

        if (metodo.incremento > 0) {
            const incremento = (totalOriginal * metodo.incremento) / 100;
            nuevoTotal = totalOriginal + incremento;
            cambioMonetario = incremento;
            mensaje = `Aplica un recargo del ${metodo.incremento}%`;
            claseCss = 'incremento';
        } else if (metodo.decremento > 0) {
            const descuento = (totalOriginal * metodo.decremento) / 100;
            nuevoTotal = totalOriginal - descuento;
            cambioMonetario = -descuento;
            mensaje = `Aplica un descuento del ${metodo.decremento}%`;
            claseCss = 'descuento';
        } else {
            mensaje = 'Sin recargos ni descuentos';
            claseCss = 'sin-cambio';
        }
        
        // Formatear el cambio monetario con signo
        const cambioFormateado = (cambioMonetario >= 0 ? '+$' : '-$') + Math.abs(cambioMonetario).toFixed(2);

        // Mostrar información de autocompletado si es el primer método
        if (metodoId === primerMetodoId && primerMetodoId !== null) {
            autoCompleteDiv.innerHTML = `<strong><i class="fas fa-check"></i> Autoselección:</strong> "${metodo.nombre}" ha sido elegido.`;
            autoCompleteDiv.style.display = 'block';
        }

        // Actualizar la interfaz
        infoPagoDiv.innerHTML = `<strong>${metodo.nombre}:</strong> ${mensaje}. Total: ${cambioFormateado}`;
        infoPagoDiv.className = `info-pago ${claseCss}`;
        infoPagoDiv.style.display = 'block';
        
        // Actualizar AMBOS campos - el visible y el oculto
        cantidadInput.value = nuevoTotal.toFixed(2);
        cantidadCalculadaInput.value = nuevoTotal.toFixed(2);
        totalFinalSpan.textContent = nuevoTotal.toFixed(2);
    }

    function validarPago() {
        const cantidad = parseFloat(document.getElementById('cantidad').value);
        const cantidadCalculada = parseFloat(document.getElementById('cantidad-calculada').value);
        const metodo = document.getElementById('id_metodo').value;
        
        if (!metodo) {
            alert('🚫 Por favor, seleccione un método de pago.');
            return false;
        }
        
        if (cantidad <= 0) {
            alert('🚫 La cantidad a pagar debe ser mayor a 0.');
            return false;
        }
        
        // Validar contra el valor calculado (tolerancia de 0.01)
        if (Math.abs(cantidad - cantidadCalculada) > 0.01) {
            alert(`⚠️ Debe pagar el monto exacto: $${cantidadCalculada.toFixed(2)}. El campo no puede ser modificado manualmente.`);
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
        
        // Bloquear la modificación manual del campo de cantidad
        document.getElementById('cantidad').readOnly = true;
        document.getElementById('cantidad').style.cursor = 'not-allowed';
        
        // Mensaje preventivo al intentar enfocar el campo bloqueado
        document.getElementById('cantidad').addEventListener('focus', function(e) {
             alert('⚠️ El monto se ajusta automáticamente al seleccionar el método de pago y no puede ser modificado manualmente.');
             this.blur(); // Quita el foco inmediatamente
        });
    });
    </script>
</body>
</html>
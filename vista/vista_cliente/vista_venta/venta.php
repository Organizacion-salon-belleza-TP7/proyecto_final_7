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

// ID de cita - AHORA TAMBIÉN ACEPTO POST
$id_cita = $_GET['id'] ?? $_POST['id_cita'] ?? null;
if (!$id_cita) {
    die("Error: ID de cita no proporcionado. <br><a href='" . BASE_URL . "/vista/vista_cliente/vista_inicio/vista_inicio_cli.php'>Volver al inicio</a>");
}

// Precio total de la cita
$funcion_traer_precio_total = $modelo_venta->traer_datos_cita($id_cita);
$total_cita = floatval($funcion_traer_precio_total);

// Datos de métodos para JS
$metodos_pago_data = [];
$primer_metodo_id = null;
$primer_metodo_nombre = "";
$temp_metodos = [];

if ($funcion_traer_metodo_pago && $funcion_traer_metodo_pago->num_rows > 0) {
    $primer = true;
    while ($metodo = $funcion_traer_metodo_pago->fetch_assoc()) {
        $temp_metodos[] = $metodo;
        $metodos_pago_data[$metodo['id_metodo_pago']] = [
            'incremento' => $metodo['incremento'] ?? 0,
            'decremento' => $metodo['decremento'] ?? 0,
            'nombre' => $metodo['metodo_pago']
        ];
        if ($primer) {
            $primer_metodo_id = $metodo['id_metodo_pago'];
            $primer_metodo_nombre = $metodo['metodo_pago'];
            $primer = false;
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
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
        body::before {
            content: "";
            position: fixed;
            top:0;left:0;right:0;bottom:0;
            background: rgba(0,0,0,0.7);
            z-index: -1;
        }
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
        select, input[type="number"], .btn-submit{
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 6px;
            border: 1px solid var(--primary-dark);
            background-color: var(--input-bg);
            color: var(--text);
            font-size: 1rem;
        }
        select:focus, input[type="number"]:focus{
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(255, 107, 157, 0.3);
        }
        .select-wrapper {
            position: relative;
            margin-bottom: 20px;
        }
        .select-wrapper::after {
            content: '\f0d7';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            top: 50%;
            right: 15px;
            transform: translateY(-50%);
            color: var(--text);
            pointer-events: none;
        }
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
        .form-table {
            width: 100%;
            margin: 20px 0;
            border-collapse: separate;
            border-spacing: 0 10px;
        }
        .form-table th, .form-table td {
            padding: 0;
            text-align: left;
        }
        .form-table th {
            color: var(--text);
            font-weight: 600;
            padding-bottom: 5px;
        }
        .btn-submit{
            background: var(--success);
            color:#fff;
            font-weight: 700;
            cursor: pointer;
            transition:.3s;
            margin-top: 20px;
        }
        .btn-submit:hover{
            background: #219d53;
        }
        .info-pago {
            margin: 10px 0;
            padding: 12px;
            border-radius: 8px;
            font-weight: bold;
            font-size: 0.95rem;
        }
        .incremento { background-color: rgba(231, 76, 60, 0.2); border: 1px solid var(--danger); color: var(--danger); }
        .descuento { background-color: rgba(39, 174, 96, 0.2); border: 1px solid var(--success); color: var(--success); }
        .sin-cambio { background-color: rgba(52, 152, 219, 0.2); border: 1px solid var(--info); color: var(--info); }
        .auto-complete {
            background-color: rgba(255, 107, 157, 0.15);
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
        <h1>Pago de Cita</h1>

        <div id="auto-complete-info" class="auto-complete" style="display: none;"></div>

        <div class="total-info">
            <p><strong>Total Original:</strong> $<span id="total-original"><?= number_format($total_cita, 2) ?></span></p>
        </div>
        
        <div id="info-pago" class="info-pago" style="display: none;"></div>
        
        <form action="<?= BASE_URL ?>/controlador/controladores_cliente/controlador_venta/controlador_venta.php" method="POST" onsubmit="return validarPago()">
            <input type="hidden" name="id_cita" value="<?= htmlspecialchars($id_cita) ?>">
            <input type="hidden" name="vista_pagar" value="venta.php">

            <div class="form-table">
                <div>
                    <div><label for="id_metodo">Método de Pago</label></div>
                    <div class="select-wrapper">
                        <select name="id_metodo" id="id_metodo" required onchange="actualizarPrecio()">
                            <option value="">Seleccione un método de pago</option>
                            <?php foreach($temp_metodos as $m): 
                                $info = $m['incremento'] ? " (+{$m['incremento']}%)" : ($m['decremento'] ? " (-{$m['decremento']}%)" : "");
                                $selected = ($m['id_metodo_pago'] == $primer_metodo_id) ? 'selected' : '';
                            ?>
                                <option value="<?= $m['id_metodo_pago'] ?>" 
                                        data-incremento="<?= $m['incremento'] ?? 0 ?>" 
                                        data-decremento="<?= $m['decremento'] ?? 0 ?>" <?= $selected ?>>
                                    <?= htmlspecialchars($m['metodo_pago']) . $info ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div>
                    <div><label for="cantidad">Cantidad a Pagar (Ajustado)</label></div>
                    <div>
                        <input type="number" name="cantidad" id="cantidad" step="0.01" min="0.01" value="<?= number_format($total_cita, 2) ?>" required readonly>
                        <input type="hidden" id="cantidad-calculada" name="cantidad_calculada" value="<?= number_format($total_cita, 2) ?>">
                    </div>
                </div>
            </div>

            <div class="total-info total-final-line">
                <p><strong>TOTAL FINAL:</strong> $<span id="total-final"><?= number_format($total_cita, 2) ?></span></p>
            </div>
            
            <button type="submit" class="btn-submit">Confirmar Pago</button>
        </form>
    </div>

    <script>
        const totalOriginal = <?= $total_cita ?>;
        const metodosPago = <?= json_encode($metodos_pago_data) ?>;
        const primerMetodoId = <?= json_encode($primer_metodo_id) ?>;
        const primerMetodoNombre = <?= json_encode($primer_metodo_nombre) ?>;

        function actualizarPrecio() {
            const select = document.getElementById('id_metodo');
            const opcion = select.options[select.selectedIndex];
            const metodoId = opcion.value;
            const infoPago = document.getElementById('info-pago');
            const autoInfo = document.getElementById('auto-complete-info');
            const totalFinal = document.getElementById('total-final');
            const cantidadInput = document.getElementById('cantidad');
            const cantidadCalc = document.getElementById('cantidad-calculada');

            if (!metodoId) {
                infoPago.style.display = 'none';
                autoInfo.style.display = 'none';
                cantidadInput.value = totalOriginal.toFixed(2);
                cantidadCalc.value = totalOriginal.toFixed(2);
                totalFinal.textContent = totalOriginal.toFixed(2);
                return;
            }

            const metodo = metodosPago[metodoId];
            let nuevoTotal = totalOriginal;
            let mensaje = '';
            let clase = '';

            if (metodo.incremento > 0) {
                nuevoTotal += totalOriginal * (metodo.incremento / 100);
                mensaje = `${metodo.nombre}: Aplica recargo del ${metodo.incremento}%. +$${ (totalOriginal * metodo.incremento / 100).toFixed(2) }`;
                clase = 'incremento';
            } else if (metodo.decremento > 0) {
                nuevoTotal -= totalOriginal * (metodo.decremento / 100);
                mensaje = `${metodo.nombre}: Aplica descuento del ${metodo.decremento}%. -$${ (totalOriginal * metodo.decremento / 100).toFixed(2) }`;
                clase = 'descuento';
            } else {
                mensaje = `${metodo.nombre}: Sin cambios`;
                clase = 'sin-cambio';
            }

            if (metodoId == primerMetodoId) {
                autoInfo.innerHTML = `<strong>Autoselección:</strong> "${primerMetodoNombre}" ha sido elegido.`;
                autoInfo.style.display = 'block';
            } else {
                autoInfo.style.display = 'none';
            }

            infoPago.innerHTML = mensaje;
            infoPago.className = `info-pago ${clase}`;
            infoPago.style.display = 'block';

            const final = nuevoTotal.toFixed(2);
            cantidadInput.value = final;
            cantidadCalc.value = final;
            totalFinal.textContent = final;
        }

        function validarPago() {
            const metodo = document.getElementById('id_metodo').value;
            const cantidad = parseFloat(document.getElementById('cantidad').value);
            const calculada = parseFloat(document.getElementById('cantidad-calculada').value);

            if (!metodo) {
                alert('Seleccione un método de pago');
                return false;
            }
            if (Math.abs(cantidad - calculada) > 0.01) {
                alert(`El monto debe ser exactamente $${calculada.toFixed(2)}`);
                return false;
            }
            return true;
        }

        // Al cargar
        document.addEventListener('DOMContentLoaded', () => {
            if (primerMetodoId) {
                document.getElementById('id_metodo').value = primerMetodoId;
                actualizarPrecio();
            }
            document.getElementById('cantidad').readOnly = true;
        });
    </script>
</body>
</html>
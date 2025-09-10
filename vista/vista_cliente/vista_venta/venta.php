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
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagar Cita</title>
</head>
<body>
    <h1>Elija su método de pago</h1>

    <p><strong>Total a pagar:</strong> $<span id="total"><?= $total_cita ?></span></p>
    <p><strong>Total ingresado:</strong> $<span id="pagado">0</span></p>
    <p><strong>Falta pagar:</strong> $<span id="restante"><?= $total_cita ?></span></p>

    <form action="<?= BASE_URL ?>/controlador/controladores_cliente/controlador_venta/controlador_venta.php" method="POST">
        <input type="hidden" name="id_cita" value="<?= htmlspecialchars($id_cita) ?>">
        <input type="hidden" name="vista_pagar" value="venta.php">

        <table border="1" id="tabla-pagos">
            <tr>
                <th>Medio de pago</th>
                <th>Cantidad a pagar</th>
            </tr>
            <tr>
                <td>
                    <select name="metodos[0][id_metodo]">
                        <?php
                        if($funcion_traer_metodo_pago && $funcion_traer_metodo_pago->num_rows > 0){
                            while($bucle_metodos_pagos = $funcion_traer_metodo_pago->fetch_assoc()){
                                echo "<option value='{$bucle_metodos_pagos['id_metodo_pago']}'>" . htmlspecialchars($bucle_metodos_pagos['metodo_pago']) . "</option>";
                            }
                        } else {
                            echo "<option value=''>Error al traer métodos</option>";
                        }
                        ?>
                    </select>
                </td>
                <td>
                    <input type="number" name="metodos[0][cantidad]" step="0.01" class="cantidad">
                </td>
            </tr>
        </table>

        <button type="button" onclick="agregarFila()">+ Agregar otro método</button>
        <br><br>
        <button type="submit">Confirmar Pago</button>
    </form>

    <script>
    let contador = 1;
    const total = parseFloat(document.getElementById('total').textContent);

    function agregarFila() {
        const tabla = document.getElementById('tabla-pagos');
        const fila = document.createElement('tr');

        fila.innerHTML = `
            <td>
                <select name="metodos[${contador}][id_metodo]">
                    <?php
                    $funcion_traer_metodo_pago2 = $modelo_venta->traer_medios_pagos();
                    $metodos = "";
                    while($row = $funcion_traer_metodo_pago2->fetch_assoc()) {
                        $metodos .= "<option value='{$row['id_metodo_pago']}'>" . htmlspecialchars($row['metodo_pago']) . "</option>";
                    }
                    echo str_replace("\n", "", $metodos);
                    ?>
                </select>
            </td>
            <td>
                <input type="number" name="metodos[${contador}][cantidad]" step="0.01" class="cantidad">
            </td>
        `;

        tabla.appendChild(fila);
        contador++;
        actualizarEventos();
    }

    function calcularTotales() {
        let pagado = 0;
        document.querySelectorAll('.cantidad').forEach(input => {
            pagado += parseFloat(input.value) || 0;
        });

        document.getElementById('pagado').textContent = pagado.toFixed(2);
        document.getElementById('restante').textContent = (total - pagado).toFixed(2);
    }

    function actualizarEventos() {
        document.querySelectorAll('.cantidad').forEach(input => {
            input.removeEventListener('input', calcularTotales); 
            input.addEventListener('input', calcularTotales);
        });
    }

    actualizarEventos();
    </script>
</body>
</html>

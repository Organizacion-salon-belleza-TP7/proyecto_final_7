<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/modelo_cliente/promociones/modelo_promociones.php');
require_once(ROOT_PATH . '/modelo/BD.php');

session_start();

if (!isset($_SESSION['carrito_promos'])) {
    echo "Error: no hay carrito.";
    exit;
}

$clase_promos = new promociones_cliente($conn);
$ids = $_SESSION['carrito_promos'];
$id_strings = implode(",", $ids);

// traer promociones del carrito
$carrito = $clase_promos->traer_servicios_combos_promocionados_carrito($id_strings);

// traer métodos de pago
$metodos_pago = $clase_promos->traer_metodos_pagos();

$total_final = 0;
?>

<h1>Resumen Final de Compra</h1>

<table border="1">
    <tr>
        <th>Nombre</th>
        <th>Tipo</th>
        <th>Precio Calculado</th>
    </tr>

<?php
while($row = $carrito->fetch_assoc()) {

    $precio_final = 0;

    // ===== SERVICIO =====
    if (!empty($row['nombre_servicio'])) {

        // ⚠️ Necesitas agregar "prom.id_servicios" en el SELECT del modelo
        $id_serv = $row['id_servicios'];

        $data_serv = $clase_promos->buscar_importe_servicio($id_serv)->fetch_assoc();
        $precio_base = $data_serv['precio_servicio'];

        // tipo servicio
        $tipo = $clase_promos->obtener_tipo_servicio($data_serv['id_tipo_servicio'])->fetch_assoc();
        $interes_tipo = $tipo['intereses'];

        // trabajadores
        $trab = $clase_promos->obtener_trabajadores_servicio($id_serv);
        $suma_intereses_trabajadores = 0;

        while ($t = $trab->fetch_assoc()) {
            $suma_intereses_trabajadores += $t['intereses'];
        }

        // cálculo final
        $precio = $precio_base;
        $precio *= (1 + $suma_intereses_trabajadores / 100);
        $precio *= (1 + $interes_tipo / 100);
        $precio *= (1 - $row['descuento'] / 100);

        $precio_final = $precio;
    }

    // ===== COMBO =====
    if (!empty($row['nombre_combo'])) {

        // ⚠️ Necesitas agregar "prom.id_combos" al SELECT del modelo
        $id_combo = $row['id_combos'];
        $servicios_combo = $clase_promos->buscar_combos_servicios($id_combo);

        $total_combo = 0;

        while ($serv = $servicios_combo->fetch_assoc()) {

            $id_serv = $serv['id_servicios'];

            $data_serv = $clase_promos->buscar_importe_servicio($id_serv)->fetch_assoc();
            $precio_base = $data_serv['precio_servicio'];

            $tipo = $clase_promos->obtener_tipo_servicio($data_serv['id_tipo_servicio'])->fetch_assoc();
            $interes_tipo = $tipo['intereses'];

            $trab = $clase_promos->obtener_trabajadores_servicio($id_serv);
            $suma_intereses_trabajadores = 0;

            while ($t = $trab->fetch_assoc()) {
                $suma_intereses_trabajadores += $t['intereses'];
            }

            $precio = $precio_base;
            $precio *= (1 + $suma_intereses_trabajadores / 100);
            $precio *= (1 + $interes_tipo / 100);

            $total_combo += $precio;
        }

        // aplicar descuento promoción combo
        $precio_final = $total_combo * (1 - $row['descuento'] / 100);
    }

    $total_final += $precio_final;

    echo "
    <tr>
        <td>" . (!empty($row['nombre_combo']) ? $row['nombre_combo'] : $row['nombre_servicio']) . "</td>
        <td>" . (!empty($row['nombre_combo']) ? "Combo" : "Servicio") . "</td>
        <td>" . number_format($precio_final, 2) . "</td>
    </tr>";
}
?>

</table>

<h2>Total sin método de pago: <span id="total_base"><?= number_format($total_final, 2) ?></span></h2>
<h2>Total final: <span id="total_final"><?= number_format($total_final, 2) ?></span></h2>

<form method="post" action="<?= BASE_URL ?>/controlador/controladores_cliente/controlador_promociones/controlador_promociones.php">

    <input type="hidden" name="proceso" value="confirmar_pago">
    <input type="hidden" name="total_sin_pago" value="<?= $total_final ?>">
    <input type="hidden" name="total_final" id="input_total_final" value="<?= $total_final ?>">

    <label>Método de pago:</label>
    <select name="metodo_pago" id="metodo_pago">
        <?php while($mp = $metodos_pago->fetch_assoc()): ?>
            <option
                value="<?= $mp['id_metodo_pago'] ?>"
                data-inc="<?= $mp['incremento'] ?>"
                data-dec="<?= $mp['decremento'] ?>"
            >
                <?= htmlspecialchars($mp['metodo_pago']) ?>
            </option>
        <?php endwhile; ?>
    </select>

    <h3 id="info_pago"></h3>

    <button type="submit">Finalizar compra</button>
</form>

<script>
const selectPago = document.getElementById("metodo_pago");
const totalBase = parseFloat(document.getElementById("total_base").innerText);
const spanTotalFinal = document.getElementById("total_final");
const inputTotalFinal = document.getElementById("input_total_final");
const infoPago = document.getElementById("info_pago");

function actualizarPrecio() {

    let inc = parseFloat(selectPago.selectedOptions[0].dataset.inc);
    let dec = parseFloat(selectPago.selectedOptions[0].dataset.dec);

    let total = totalBase;
    let texto = "";

    // Recargo
    if (inc > 0) {
        let montoInc = totalBase * (inc / 100);
        texto += `Recargo aplicado: +${inc}% ( +$${montoInc.toFixed(2)} )<br>`;
        total *= (1 + inc / 100);
    }

    // Descuento
    if (dec > 0) {
        let montoDec = totalBase * (dec / 100);
        texto += `Descuento aplicado: -${dec}% ( -$${montoDec.toFixed(2)} )<br>`;
        total *= (1 - dec / 100);
    }

    // Si no hay ni inc ni dec
    if (texto === "") {
        texto = "Método de pago sin recargos ni descuentos.";
    }

    // Actualizar HTML
    infoPago.innerHTML = texto;
    spanTotalFinal.innerText = total.toFixed(2);
    inputTotalFinal.value = total.toFixed(2);
}

// Ejecutar cuando cambia el select
selectPago.addEventListener("change", actualizarPrecio);

// Ejecutar una vez al cargar la página
actualizarPrecio();
</script>

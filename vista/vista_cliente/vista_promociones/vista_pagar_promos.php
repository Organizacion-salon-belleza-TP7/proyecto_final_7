<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/modelo_cliente/promociones/modelo_promociones.php');
require_once(ROOT_PATH . '/modelo/BD.php');

session_start();

if (!isset($_SESSION['carrito_promos'])) {
    echo "<!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Error - Carrito Vacío</title>
        <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css'>
        <style>
            :root {
                --bg: #1e1e2f;
                --bg-sidebar: #2a2a3d;
                --primary: #ff6b9d;
                --primary-dark: #e05585;
                --text: #f1f1f1;
                --text-muted: #aaa;
                --card: #2e2e44;
                --danger: #e74c3c;
                --success: #27ae60;
                --info: #3498db;
                --shadow: 0 4px 12px rgba(0,0,0,0.3);
            }

            *{margin:0;padding:0;box-sizing:border-box;}
            body{
                font-family: 'Segoe UI', sans-serif;
                background: url('../../../imagenes/lugares/istockphoto-1856117770-612x612.jpg') no-repeat center center fixed;
                background-size: cover;
                color: var(--text);
                display: flex;
                min-height: 100vh;
                position: relative;
                z-index: 1;
                align-items: center;
                justify-content: center;
            }

            body::before {
                content: '';
                position: fixed;
                top:0; left:0; right:0; bottom:0;
                background: rgba(0,0,0,0.6);
                z-index: -1;
            }

            .error-container {
                background: rgba(46,46,68,0.95);
                padding: 40px;
                border-radius: 12px;
                text-align: center;
                box-shadow: var(--shadow);
                max-width: 500px;
                width: 90%;
            }

            .error-container h1 {
                color: var(--danger);
                margin-bottom: 20px;
                font-size: 1.8rem;
            }

            .error-container p {
                color: var(--text-muted);
                margin-bottom: 25px;
                font-size: 1.1rem;
            }

            .btn {
                padding: 12px 24px;
                border-radius: 6px;
                font-size: 1rem;
                font-weight: 600;
                text-decoration: none;
                display: inline-block;
                transition: .3s;
                border: none;
                cursor: pointer;
            }

            .btn-primary {
                background: var(--primary);
                color: #fff;
            }

            .btn-primary:hover {
                background: var(--primary-dark);
            }
        </style>
    </head>
    <body>
        <div class='error-container'>
            <h1><i class='fas fa-exclamation-triangle'></i> Error en el Carrito</h1>
            <p>No se encontraron productos en el carrito. Por favor, regresa a las promociones y agrega algunos items.</p>
            <a href='". BASE_URL ."/vista/vista_cliente/vista_promociones/vista_promociones.php' class='btn btn-primary'>
                <i class='fas fa-arrow-left'></i> Volver a Promociones
            </a>
        </div>
    </body>
    </html>";
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

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resumen Final - Promociones</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --bg: #1e1e2f;
            --bg-sidebar: #2a2a3d;
            --primary: #ff6b9d;
            --primary-dark: #e05585;
            --text: #f1f1f1;
            --text-muted: #aaa;
            --card: #2e2e44;
            --danger: #e74c3c;
            --success: #27ae60;
            --info: #3498db;
            --shadow: 0 4px 12px rgba(0,0,0,0.3);
        }

        *{margin:0;padding:0;box-sizing:border-box;}
        body{
            font-family: 'Segoe UI', sans-serif;
            background: url('../../../imagenes/lugares/istockphoto-1856117770-612x612.jpg') no-repeat center center fixed;
            background-size: cover;
            color: var(--text);
            min-height: 100vh;
            position: relative;
            z-index: 1;
            padding: 20px;
        }

        body::before {
            content: "";
            position: fixed;
            top:0; left:0; right:0; bottom:0;
            background: rgba(0,0,0,0.6);
            z-index: -1;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        h1{
            font-size:2.5rem;
            margin-bottom:30px;
            color: var(--primary);
            text-shadow: 2px 2px 6px rgba(0,0,0,0.6);
            text-align: center;
        }

        h2 {
            color: var(--primary);
            margin: 25px 0 15px 0;
            font-size: 1.5rem;
        }

        h3 {
            color: var(--text);
            margin: 20px 0 10px 0;
            font-size: 1.2rem;
        }

        /* Tables */
        table{
            width:100%;
            border-collapse:collapse;
            background: rgba(46,46,68,0.9);
            border-radius:8px;
            overflow:hidden;
            box-shadow: var(--shadow);
            margin-bottom:25px;
        }
        th,td{
            padding:14px 16px;
            text-align:left;
            font-size:0.95rem;
        }
        th{
            background: var(--primary-dark);
            color:#fff;
            font-weight:600;
        }
        tr:nth-child(even){background: rgba(37,37,56,0.9);}
        tr:hover{background: rgba(255,107,157,0.1);}

        /* Form Styles */
        .form-container {
            background: rgba(46,46,68,0.9);
            padding: 25px;
            border-radius: 8px;
            box-shadow: var(--shadow);
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--primary);
            font-weight: 600;
            font-size: 1.1rem;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid rgba(255,107,157,0.3);
            border-radius: 6px;
            background: rgba(37,37,56,0.9);
            color: var(--text);
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(255,107,157,0.2);
        }

        select.form-control {
            cursor: pointer;
        }

        /* Buttons */
        .btn{
            padding:12px 24px;
            border-radius:6px;
            font-size:1rem;
            font-weight:600;
            text-decoration:none;
            display:inline-block;
            transition:.3s;
            border: none;
            cursor: pointer;
            text-align: center;
        }
        .btn-primary{
            background: var(--primary);
            color:#fff;
        }
        .btn-primary:hover{
            background: var(--primary-dark);
        }
        .btn-success{
            background: var(--success);
            color:#fff;
        }
        .btn-success:hover{
            opacity: 0.85;
        }
        .btn-secondary{
            background: var(--info);
            color:#fff;
        }
        .btn-secondary:hover{
            opacity: 0.85;
        }

        .btn-submit {
            width: 100%;
            padding: 15px;
            font-size: 1.1rem;
            margin-top: 20px;
        }

        /* Action buttons */
        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        /* Badge */
        .badge {
            background: var(--primary);
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: bold;
        }

        /* Summary cards */
        .summary-card {
            background: rgba(46,46,68,0.9);
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            box-shadow: var(--shadow);
        }

        .summary-card h3 {
            color: var(--primary);
            margin-bottom: 15px;
            text-align: center;
        }

        .total-section {
            background: rgba(37,37,56,0.9);
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid var(--primary);
        }

        .total-base {
            color: var(--text-muted);
            font-size: 1.1rem;
            margin-bottom: 10px;
        }

        .total-final {
            color: var(--success);
            font-size: 1.4rem;
            font-weight: bold;
        }

        .payment-info {
            background: rgba(37,37,56,0.9);
            padding: 15px;
            border-radius: 6px;
            margin-top: 15px;
            border-left: 4px solid var(--info);
        }

        .payment-info p {
            margin: 5px 0;
            font-size: 0.95rem;
        }

        .recargo {
            color: var(--danger);
        }

        .descuento {
            color: var(--success);
        }

        .price {
            color: var(--success);
            font-weight: bold;
        }

        .price-base {
            color: var(--text-muted);
            text-decoration: line-through;
        }

        .header-nav {
            text-align: center;
            margin-bottom: 30px;
        }

        .header-nav a {
            color: var(--primary);
            text-decoration: none;
            margin: 0 15px;
            font-weight: 600;
            transition: color 0.3s;
        }

        .header-nav a:hover {
            color: var(--primary-dark);
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Navegación simple -->
        <div class="header-nav">
            <a href="<?= BASE_URL ?>/vista/vista_cliente/vista_promociones/vista_promociones.php">
                <i class="fas fa-tags"></i> Promociones
            </a>
            <a href="<?= BASE_URL ?>/vista/vista_cliente/vista_promociones/vista_carrito_promos.php">
                <i class="fas fa-shopping-cart"></i> Carrito
            </a>
            <a href="<?= BASE_URL ?>/vista/vista_cliente/vista_citas/layout.php">
                <i class="fas fa-spa"></i> Citas
            </a>
        </div>

        <h1><i class="fas fa-file-invoice-dollar"></i> Resumen Final de Compra</h1>

        <div class="summary-card">
            <h3>Detalles de tu Pedido</h3>
            <p style="text-align: center; color: var(--text-muted);">Revisa los items y selecciona tu método de pago</p>
        </div>

        <h2><i class="fas fa-shopping-basket"></i> Items en el Carrito</h2>
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Tipo</th>
                    <th>Precio Calculado</th>
                </tr>
            </thead>
            <tbody>
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
                    <td><span class='badge'>" . (!empty($row['nombre_combo']) ? "Combo" : "Servicio") . "</span></td>
                    <td class='price'>$ " . number_format($precio_final, 2) . "</td>
                </tr>";
            }
            ?>
            </tbody>
        </table>

        <div class="total-section">
            <div class="total-base">
                <i class="fas fa-receipt"></i> Total sin método de pago: 
                <span id="total_base" class="price">$ <?= number_format($total_final, 2) ?></span>
            </div>
            <div class="total-final">
                <i class="fas fa-money-bill-wave"></i> Total final: 
                <span id="total_final" class="price">$ <?= number_format($total_final, 2) ?></span>
            </div>
        </div>

        <div class="form-container">
            <h2><i class="fas fa-credit-card"></i> Método de Pago</h2>
            <form method="post" action="<?= BASE_URL ?>/controlador/controladores_cliente/controlador_promociones/controlador_promociones.php">

                <input type="hidden" name="proceso" value="confirmar_pago">
                <input type="hidden" name="total_sin_pago" value="<?= $total_final ?>">
                <input type="hidden" name="total_final" id="input_total_final" value="<?= $total_final ?>">

                <div class="form-group">
                    <label for="metodo_pago"><i class="fas fa-wallet"></i> Selecciona tu método de pago:</label>
                    <select name="metodo_pago" id="metodo_pago" class="form-control" required>
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
                </div>

                <div class="payment-info" id="info_pago">
                    <p>Selecciona un método de pago para ver los detalles...</p>
                </div>

                <button type="submit" class="btn btn-success btn-submit">
                    <i class="fas fa-check-circle"></i> Finalizar Compra y Confirmar Pago
                </button>
            </form>
        </div>

        <div class="action-buttons">
            <a class="btn btn-secondary" href="<?= BASE_URL ?>/vista/vista_cliente/vista_promociones/vista_carrito_promos.php">
                <i class="fas fa-arrow-left"></i> Volver al Carrito
            </a>
            <a class="btn btn-primary" href="<?= BASE_URL ?>/vista/vista_cliente/vista_promociones/vista_promociones.php">
                <i class="fas fa-plus-circle"></i> Seguir Comprando
            </a>
        </div>
    </div>

    <script>
        const selectPago = document.getElementById("metodo_pago");
        const totalBase = parseFloat(document.getElementById("total_base").innerText.replace('$', '').trim());
        const spanTotalFinal = document.getElementById("total_final");
        const inputTotalFinal = document.getElementById("input_total_final");
        const infoPago = document.getElementById("info_pago");

        function actualizarPrecio() {

            let inc = parseFloat(selectPago.selectedOptions[0].dataset.inc);
            let dec = parseFloat(selectPago.selectedOptions[0].dataset.dec);

            let total = totalBase;
            let texto = "<h4>Detalles del método de pago:</h4>";

            // Recargo
            if (inc > 0) {
                let montoInc = totalBase * (inc / 100);
                texto += `<p class='recargo'><i class='fas fa-arrow-up'></i> Recargo aplicado: +${inc}% ( +$${montoInc.toFixed(2)} )</p>`;
                total *= (1 + inc / 100);
            }

            // Descuento
            if (dec > 0) {
                let montoDec = totalBase * (dec / 100);
                texto += `<p class='descuento'><i class='fas fa-arrow-down'></i> Descuento aplicado: -${dec}% ( -$${montoDec.toFixed(2)} )</p>`;
                total *= (1 - dec / 100);
            }

            // Si no hay ni inc ni dec
            if (inc === 0 && dec === 0) {
                texto += `<p><i class='fas fa-equals'></i> Método de pago sin recargos ni descuentos.</p>`;
            }

            // Resumen final
            texto += `<p style='margin-top: 10px; border-top: 1px solid var(--text-muted); padding-top: 10px;'>
                        <strong>Total a pagar: $${total.toFixed(2)}</strong>
                      </p>`;

            // Actualizar HTML
            infoPago.innerHTML = texto;
            spanTotalFinal.innerText = '$ ' + total.toFixed(2);
            inputTotalFinal.value = total.toFixed(2);
        }

        // Ejecutar cuando cambia el select
        selectPago.addEventListener("change", actualizarPrecio);

        // Ejecutar una vez al cargar la página
        actualizarPrecio();
    </script>

</body>
</html>
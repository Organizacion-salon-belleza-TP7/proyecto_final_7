<?php
// VARIABLES DISPONIBLES DESDE EL CONTROLADOR:
// $ventas
// $topItems
// $mediosStats
// $productos
// $promos
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Historial de Ventas</title>

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    :root {
        --bg: #0b1220;
        --card: #0f1724;
        --accent: #ff6b9d;
        --muted: #9aa4b2;
        --text: #e6eef8;
    }
    body {
        background: var(--bg);
        color: var(--text);
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 25px;
    }
    .wrap {
        max-width: 1200px;
        margin: auto;
    }
    h1, h2 {
        color: var(--accent);
        margin-bottom: 10px;
    }
    .card {
        background: var(--card);
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 20px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.4);
    }
    table {
        width: 100%;
        border-collapse: collapse;
        color: var(--text);
        font-size: 14px;
    }
    th, td {
        padding: 10px;
        border-bottom: 1px solid rgba(255,255,255,0.06);
        text-align: left;
    }
    th {
        color: var(--muted);
        background: rgba(255,255,255,0.04);
    }
    tr:hover {
        background: rgba(255,107,157,0.05);
    }
    .btn {
        background: var(--accent);
        border: none;
        color: white;
        padding: 8px 12px;
        border-radius: 6px;
        cursor: pointer;
    }
    .small { font-size: 13px; }
    .charts-container {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }
    canvas {
        max-height: 200px;
    }
    input[type="text"] {
        padding: 7px;
        border-radius: 6px;
        border: 1px solid #333;
        background: #081022;
        color: white;
    }
</style>

</head>
<body>

<div class="wrap">
    <h1>Historial de Ventas</h1>

    <!-- BUSCADOR -->
    <div class="card">
        <h2>Buscar Venta</h2>
        <input type="text" id="filterSearch" placeholder="Buscar ID o texto...">
        <button class="btn" onclick="filtrar()">Buscar</button>
        <span class="small">Resultados: <strong id="totalBadge">0</strong></span>
    </div>

    <!-- TABLA PRINCIPAL -->
    <div class="card">
        <h2>Ventas</h2>
        <table id="tablaVentas">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fecha</th>
                    <th>Monto</th>
                    <th>Monto Total</th>
                    <th>Detalle</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($ventas && $ventas->num_rows > 0): ?>
                <?php while ($v = $ventas->fetch_assoc()): ?>
                    <tr>
                        <td class="col-id"><?= $v["id_caja"] ?></td>
                        <td><?= $v["fecha_venta"] ?></td>
                        <td>$<?= number_format($v["monto"],2) ?></td>
                        <td>$<?= number_format($v["monto_total"],2) ?></td>
                        <td><button class="btn" onclick="verDetalle(<?= $v['id_caja'] ?>)">Ver</button></td>
                    </tr>
                <?php endwhile; ?>
            <?php endif; ?>
            </tbody>
        </table>

        <div id="detalleBox" class="card" style="display:none; margin-top:15px;">
            <h3>Detalle de venta</h3>
            <div id="detalleContenido"></div>
        </div>
    </div>

    <!-- GRAFICOS -->
    <div class="card">
        <h2>Gráficos</h2>

        <div class="charts-container">
            <div>
                <h4>Top Servicios Vendidos</h4>
                <canvas id="topItemsChart"></canvas>
            </div>

            <div>
                <h4>Métodos de Pago</h4>
                <canvas id="metodosChart"></canvas>
            </div>

            <div>
                <h4>Ventas Productos</h4>
                <canvas id="productosChart"></canvas>
            </div>

            <div>
                <h4>Ventas Promociones</h4>
                <canvas id="promosChart"></canvas>
            </div>
        </div>
    </div>

</div>

<script>
// BUSCADOR MEJORADO
function filtrar(){
    let q = document.getElementById("filterSearch").value.trim().toLowerCase();
    let rows = document.querySelectorAll("#tablaVentas tbody tr");
    let count = 0;

    rows.forEach(r=>{
        let id = r.querySelector(".col-id").textContent.toLowerCase();
        let rowText = r.textContent.toLowerCase();

        if(q === id){
            // Coincidencia exacta con ID
            r.style.display = "";
            count++;
        } else if (q !== "" && rowText.includes(q)){
            // Coincidencia parcial en otras columnas
            r.style.display = "";
            count++;
        } else if (q === ""){
            // Si el campo está vacío, mostrar todo
            r.style.display = "";
            count++;
        } else {
            r.style.display = "none";
        }
    });

    document.getElementById("totalBadge").innerText = count;
}

// DETALLE AJAX
function verDetalle(id){
    fetch("?detalle=" + id)
    .then(res => res.json())
    .then(data => {
        let html = "<table><tr><th>Servicio</th><th>Cant</th><th>Unit</th><th>Subtotal</th><th>Pago</th></tr>";
        data.servicios.forEach(s => {
            html += `<tr>
                        <td>${s.servicio_nombre ?? s.combo_nombre ?? "-"}</td>
                        <td>${s.cantidad}</td>
                        <td>${s.precio_unitario}</td>
                        <td>${s.subtotal}</td>
                        <td>${s.id_metodo_pago}</td>
                    </tr>`;
        });
        html += "</table>";
        document.getElementById("detalleContenido").innerHTML = html;
        document.getElementById("detalleBox").style.display = "block";
    })
    .catch(() => alert("Error al cargar detalle"));
}

// DATOS DESDE PHP
const topItems  = <?= json_encode($topItems? $topItems->fetch_all(MYSQLI_ASSOC) : []) ?>;
const medios    = <?= json_encode($mediosStats) ?>;
const productos = <?= json_encode($productos) ?>;
const promos    = <?= json_encode($promos) ?>;

// CREAR CHART
function crearChart(id, labels, data, color){
    new Chart(document.getElementById(id), {
        type: "bar",
        data: {
            labels: labels,
            datasets: [{ data: data, backgroundColor: color }]
        },
        options: { responsive: true, plugins:{legend:{display:false}} }
    });
}

window.onload = () => {

    // TOP SERVICIOS
    crearChart(
        "topItemsChart",
        topItems.map(x => x.nombre),
        topItems.map(x => x.total_cantidad),
        "rgba(255,107,157,0.9)"
    );

    // METODOS PAGO
    crearChart(
        "metodosChart",
        medios.map(x => "Método " + x.metodo),
        medios.map(x => x.monto),
        "rgba(100,150,255,0.9)"
    );

    // PRODUCTOS
    crearChart(
        "productosChart",
        productos.map(x => `#${x.id} - ${x.fecha_venta}`),
        productos.map(x => x.monto_total),
        "rgba(255,200,80,0.9)"
    );

    // PROMOS
    crearChart(
        "promosChart",
        promos.map(x => `#${x.id} - ${x.fecha_venta}`),
        promos.map(x => x.monto_total),
        "rgba(120,255,120,0.9)"
    );
};
</script>

</body>
</html>

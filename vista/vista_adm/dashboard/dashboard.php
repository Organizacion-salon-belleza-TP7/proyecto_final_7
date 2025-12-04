<?php

// Recibir los datos enviados desde el controlador
$ventas_por_dia = $data["ventas_por_dia"];
$top_servicios  = $data["top_servicios"];
$metodos_pago   = $data["metodos_pago"];
$productos      = $data["productos"];
$promos         = $data["promos"];
$total_general  = $data["total_general"];
$resumen_global = $data["resumen_global"];

?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Dashboard</title>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    body { 
        background:#0b1220; 
        color:white; 
        font-family:Arial; 
        padding:20px; 
    }
    h1 { 
        color:#ff6b9d; 
        text-align:center; 
        margin-bottom:25px; 
        font-size:28px;
    }
    .grid { 
        display:grid; 
        grid-template-columns: repeat(2,1fr); 
        gap:20px; 
        width:90%; 
        margin:auto; 
    }
    .card {
        background:#101a2c;
        padding:20px;
        border-radius:12px;
        box-shadow:0 0 10px rgba(0,0,0,0.4);
    }
    h3 { 
        margin-bottom:15px; 
        color:#ff88aa; 
        font-size:18px; 
    }
    canvas {
        width:100% !important;
        height:300px !important;
    }
</style>
</head>
<body>

<h1>📊 Dashboard con Gráficos Variados</h1>

<div class="grid">

    <div class="card">
        <h3>📈 Ventas por Día (Línea)</h3>
        <canvas id="diaChart"></canvas>
    </div>

    <div class="card">
        <h3>🏆 Top Servicios (Barras Horizontales)</h3>
        <canvas id="serviciosChart"></canvas>
    </div>

    <div class="card">
        <h3>💳 Métodos de Pago (Doughnut)</h3>
        <canvas id="metodosChart"></canvas>
    </div>

    <div class="card">
        <h3>🛒 Ventas de Productos (Bar)</h3>
        <canvas id="productosChart"></canvas>
    </div>

    <div class="card">
        <h3>🎁 Ventas de Promociones (Pie)</h3>
        <canvas id="promosChart"></canvas>
    </div>

    <div class="card">
        <h3>📡 Resumen Global (Radar)</h3>
        <canvas id="globalChart"></canvas>
    </div>

</div>

<script>
const ventasPorDia  = <?= json_encode($ventas_por_dia) ?>;
const topServicios  = <?= json_encode($top_servicios) ?>;
const metodosPago   = <?= json_encode($metodos_pago) ?>;
const productos     = <?= json_encode($productos) ?>;
const promos        = <?= json_encode($promos) ?>;
const resumenGlobal = <?= json_encode($resumen_global) ?>;

/* ======================================================
      GRÁFICO LÍNEA — Ventas por Día
====================================================== */
new Chart(document.getElementById("diaChart"), {
    type: "line",
    data: {
        labels: ventasPorDia.map(x=>x.fecha_venta),
        datasets: [{
            label: "Total Vendido",
            data: ventasPorDia.map(x=>x.total),
            borderColor:"#4ea6ff",
            backgroundColor:"rgba(78,166,255,0.25)",
            tension:0.4,
            fill:true
        }]
    }
});

/* ======================================================
      BARRAS HORIZONTALES — Top Servicios
====================================================== */
new Chart(document.getElementById("serviciosChart"), {
    type: "bar",
    data: {
        labels: topServicios.map(x=>x.nombre),
        datasets: [{
            label: "Veces vendido",
            data: topServicios.map(x=>x.total),
            backgroundColor:"rgba(255,107,157,0.9)"
        }]
    },
    options: {
        indexAxis: "y"
    }
});

/* ======================================================
      DOUGHNUT — Métodos de Pago
====================================================== */
new Chart(document.getElementById("metodosChart"), {
    type:"doughnut",
    data:{
        labels: metodosPago.map(x=>"Método "+x.metodo),
        datasets:[{
            data: metodosPago.map(x=>x.total),
            backgroundColor:[
                "#ff6b9d","#4ea6ff","#5aff8b","#ffc34e","#a77bff"
            ]
        }]
    }
});

/* ======================================================
      BARRAS — Ventas de Productos
====================================================== */
new Chart(document.getElementById("productosChart"), {
    type:"bar",
    data:{
        labels: productos.map(x=>"#"+x.id),
        datasets:[{
            data: productos.map(x=>x.monto_total),
            backgroundColor:"rgba(255,200,80,0.9)"
        }]
    }
});

/* ======================================================
      PIE — Ventas de Promociones
====================================================== */
new Chart(document.getElementById("promosChart"), {
    type:"pie",
    data:{
        labels: promos.map(x=>"#"+x.id),
        datasets:[{
            data: promos.map(x=>x.monto_total),
            backgroundColor:[
                "#4ea6ff","#ff88aa","#7dff9c","#ffe288","#c77dff"
            ]
        }]
    }
});

/* ======================================================
      RADAR — Resumen Global
====================================================== */
new Chart(document.getElementById("globalChart"), {
    type:"radar",
    data:{
        labels: resumenGlobal.map(x=>x.tipo),
        datasets:[{
            data: resumenGlobal.map(x=>x.total),
            backgroundColor:"rgba(255,100,100,0.4)",
            borderColor:"#ff6b9d",
            borderWidth:2
        }]
    }
});
</script>

</body>
</html>

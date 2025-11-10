<?php
require_once "conexion.php";
require_once "fpdf/fpdf.php";

$id_caja = $_GET['id_caja'] ?? null;
if (!$id_caja) die("Falta el ID de la compra.");

$conn = Conexion::conectar();
$venta = $conn->query("SELECT * FROM caja_product WHERE id_caja_product = $id_caja")->fetch_assoc();

if (!$venta) {
    die("No se encontró la compra.");
}

// Generar PDF temporal
class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial','B',16);
        $this->Cell(0,10,utf8_decode('Ticket de Compra'),0,1,'C');
        $this->Ln(5);
    }
}
$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,10,utf8_decode('Fecha: ' . $venta['fecha_venta']),0,1);
$pdf->Cell(0,10,utf8_decode('Total pagado: $' . number_format($venta['monto_total'],2)),0,1);
$pdf->Ln(10);
$pdf->Cell(0,10,utf8_decode('¡Gracias por su compra!'),0,1,'C');
$pdf->Output('F', 'ticket.pdf'); // Guarda temporalmente el ticket
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Ticket de Compra | RoseSpa</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
:root {
  --primary: #ff6b9d;
  --primary-dark: #e05585;
  --bg: #1e1e2f;
  --sidebar: #2e2e44;
  --text: #f1f1f1;
  --shadow: 0 4px 12px rgba(0,0,0,0.3);
}
* {margin:0; padding:0; box-sizing:border-box; font-family:'Segoe UI',sans-serif;}
body {
  background: url('imagenes/fondo_ticket.jpg') no-repeat center center fixed;
  background-size: cover;
  display: flex;
  color: var(--text);
  min-height: 100vh;
}
body::before {
  content:"";
  position:fixed; top:0; left:0; right:0; bottom:0;
  background: rgba(0,0,0,0.6);
  z-index:-1;
}
/* SIDEBAR */
.sidebar {
  width: 240px;
  background: var(--sidebar);
  display: flex;
  flex-direction: column;
  align-items: center;
  padding-top: 30px;
  box-shadow: var(--shadow);
}
.sidebar h2 {
  color: var(--primary);
  margin-bottom: 30px;
  font-size: 1.6rem;
}
.sidebar a {
  text-decoration: none;
  color: var(--text);
  width: 90%;
  padding: 12px;
  margin: 5px 0;
  border-radius: 8px;
  display: flex;
  align-items: center;
  transition: .3s;
}
.sidebar a i {
  margin-right: 10px;
}
.sidebar a:hover {
  background: var(--primary-dark);
  color: #fff;
}
/* CONTENIDO */
.main {
  flex: 1;
  padding: 50px;
  display: flex;
  justify-content: center;
  align-items: center;
}
.ticket-box {
  background: rgba(46,46,68,0.95);
  padding: 40px;
  border-radius: 15px;
  width: 420px;
  box-shadow: var(--shadow);
  text-align: center;
}
.ticket-box h1 {
  color: var(--primary);
  margin-bottom: 25px;
  font-size: 1.8rem;
  text-shadow: 2px 2px 5px rgba(0,0,0,0.5);
}
.ticket-info p {
  margin-bottom: 10px;
  font-size: 1.05rem;
}
.btn-download {
  display: inline-block;
  background: var(--primary);
  color: #fff;
  padding: 12px 24px;
  border-radius: 8px;
  text-decoration: none;
  font-weight: bold;
  transition: .3s;
  margin-top: 20px;
  box-shadow: var(--shadow);
}
.btn-download:hover {
  background: var(--primary-dark);
}
.footer {
  margin-top: 30px;
  font-size: 0.9rem;
  color: #ccc;
}
</style>
</head>
<body>
  <div class="sidebar">
    <h2><i class="fas fa-spa"></i> RoseSpa</h2>
    <a href="panel.php"><i class="fas fa-home"></i> Inicio</a>
    <a href="ventas.php"><i class="fas fa-shopping-cart"></i> Ventas</a>
    <a href="productos.php"><i class="fas fa-box"></i> Productos</a>
    <a href="clientes.php"><i class="fas fa-user"></i> Clientes</a>
    <a href="reportes.php"><i class="fas fa-chart-line"></i> Reportes</a>
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Salir</a>
  </div>

  <div class="main">
    <div class="ticket-box">
      <h1><i class="fas fa-receipt"></i> Ticket de Compra</h1>
      <div class="ticket-info">
        <p><strong>Fecha:</strong> <?= htmlspecialchars($venta['fecha_venta']) ?></p>
        <p><strong>Total pagado:</strong> $<?= number_format($venta['monto_total'], 2) ?></p>
        <p>¡Gracias por su compra!</p>
      </div>
      <a href="ticket.pdf" class="btn-download" download>
        <i class="fas fa-download"></i> Descargar Ticket PDF
      </a>
      <div class="footer">
        © <?= date("Y") ?> RoseSpa | Todos los derechos reservados
      </div>
    </div>
  </div>
</body>
</html>

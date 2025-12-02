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

// Generar PDF
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
$pdf->Output('F', 'ticket.pdf'); // Guardar temporalmente

?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Ticket de Compra</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
:root {
  --bg: #1e1e2f;
  --primary: #ff6b9d;
  --primary-dark: #e05585;
  --text: #f1f1f1;
  --card: #2e2e44;
  --shadow: 0 4px 12px rgba(0,0,0,0.3);
}
*{margin:0;padding:0;box-sizing:border-box;}
body {
  font-family: 'Segoe UI', sans-serif;
  background: url('imagenes/fondo_ticket.jpg') no-repeat center center fixed;
  background-size: cover;
  color: var(--text);
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  position: relative;
}
body::before {
  content: "";
  position: fixed;
  top:0; left:0; right:0; bottom:0;
  background: rgba(0,0,0,0.6);
  z-index: -1;
}
.ticket-container {
  background: rgba(46,46,68,0.9);
  padding: 30px;
  border-radius: 12px;
  box-shadow: var(--shadow);
  width: 400px;
  text-align: center;
}
h1 {
  color: var(--primary);
  margin-bottom: 20px;
  text-shadow: 2px 2px 5px rgba(0,0,0,0.5);
}
.ticket-info {
  font-size: 1rem;
  line-height: 1.6;
  margin-bottom: 20px;
}
.btn-download {
  display: inline-block;
  background: var(--primary);
  color: #fff;
  padding: 10px 20px;
  border-radius: 8px;
  text-decoration: none;
  font-weight: bold;
  transition: .3s;
  box-shadow: var(--shadow);
}
.btn-download:hover {
  background: var(--primary-dark);
}
</style>
</head>
<body>
  <div class="ticket-container">
    <h1><i class="fas fa-receipt"></i> Ticket de Compra</h1>
    <div class="ticket-info">
      <p><strong>Fecha:</strong> <?= htmlspecialchars($venta['fecha_venta']) ?></p>
      <p><strong>Total pagado:</strong> $<?= number_format($venta['monto_total'], 2) ?></p>
      <p>¡Gracias por su compra!</p>
    </div>
    <a href="ticket.pdf" class="btn-download" download>
      <i class="fas fa-download"></i> Descargar Ticket PDF
    </a>
  </div>
</body>
</html>

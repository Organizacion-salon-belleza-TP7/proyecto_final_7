<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');

session_start();
if (!isset($_SESSION['user']) || !$_SESSION['user']) {
    header("Location: ../../../login.php");
    exit;
}
$id_usuario = (int)$_SESSION['user'];

// Obtener citas
$citas = $conn->query("
    SELECT c.id_cita, c.fecha_cita, c.activo, c.id_lugar,
           cl.nombre AS nombre_cliente,
           l.nombre_lugar
    FROM citas c
    JOIN clientes cl ON c.id_cliente = cl.id_cliente
    LEFT JOIN lugares l ON c.id_lugar = l.id_lugar
    WHERE cl.id_usuario = $id_usuario
    ORDER BY c.fecha_cita DESC
");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mis Turnos - RoseSpa</title>
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
  --warning: #f39c12;
  --success: #27ae60;
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
}

body::before {
  content: "";
  position: fixed;
  top:0; left:0; right:0; bottom:0;
  background: rgba(0,0,0,0.5);
  z-index: -1;
}

/* === MENÚ AJUSTADO === */
.sidebar{
  width: 240px;
  background: rgba(42,42,61,0.9);
  padding: 20px;
  display:flex;
  flex-direction:column;
  box-shadow: var(--shadow);
  position: fixed;
  top:0;left:0;bottom:0;
  transition: transform .3s ease;
  z-index: 1000;
  padding-bottom: 20px;
  overflow-y: auto;
}

.sidebar h2{
  color: var(--primary);
  text-align:center;
  margin-bottom: 45px;
  margin-top: 10px;
}

.sidebar a{
  display:flex;
  align-items:center;
  gap:10px;
  color: var(--text);
  text-decoration:none;
  padding:12px;
  border-radius:6px;
  margin-bottom:6px;
  transition:.3s;
}

.sidebar a:hover{
  background: var(--primary);
  color:#fff;
}

.sidebar.hidden { transform: translateX(-100%); }

/* === BOTÓN MENU AJUSTADO === */
.toggle-btn{
  position: fixed;
  top: 12px;
  left: 12px;
  background: rgba(255, 107, 157, 0.75);
  color:#fff;
  border:none;
  padding:8px 10px;
  font-size:1.3rem;
  border-radius:6px;
  cursor:pointer;
  z-index:1100;
  transition:.3s;
  box-shadow: var(--shadow);
}

.toggle-btn i {
  pointer-events: none;
}

.toggle-btn:hover{
  background: rgba(224, 85, 133, 0.9);
}

.content{
  margin-left: 240px;
  flex:1;
  padding:30px;
  transition: margin-left .3s ease;
  width: 100%;
}

.content.expanded{
  margin-left: 0;
}

h1{
  font-size:2rem;
  margin-bottom:20px;
  color: var(--primary);
  text-shadow: 2px 2px 6px rgba(0,0,0,0.6);
  margin-left: 60px !important;
}

/* Tarjetas */
.card{
  background: rgba(46,46,68,0.95);
  padding:28px;
  border-radius:12px;
  margin:30px auto;
  max-width:900px;
  box-shadow: var(--shadow);
  border-left: 6px solid transparent;
}

.esperando{ border-left-color: var(--warning); }
.activa{ border-left-color: var(--primary); }
.terminada{ border-left-color: var(--success); background:rgba(39,174,96,0.15); }

.info{ font-size:1.15rem; line-height:2.1; margin-bottom:25px; }
.estado{ text-align:center; margin:35px 0; }

.btn{
  padding:12px 32px;
  border:none;
  border-radius:50px;
  font-weight:600;
  cursor:pointer;
  margin:10px;
  transition:.3s;
  font-size:1rem;
}

.btn-resena{ background:var(--primary); color:#fff; }
.btn-reembolso{ background:var(--danger); color:#fff; }

/* NUEVO BOTÓN GRANDE DE REEMBOLSO */
.btn-cancelar-reembolso {
  display: block;
  width: 90%;
  max-width: 420px;
  margin: 30px auto 10px auto;
  padding: 18px 20px;
  background: #e74c3c;
  color: white;
  font-size: 1.4rem;
  font-weight: bold;
  border: none;
  border-radius: 12px;
  box-shadow: 0 6px 15px rgba(231,76,60,0.4);
  cursor: pointer;
  transition: all 0.3s;
}

.btn-cancelar-reembolso:hover {
  background: #c0392b;
  transform: translateY(-2px);
  box-shadow: 0 10px 20px rgba(231,76,60,0.5);
}

  </style>
</head>
<body>

  <!-- Botón Toggle EXACTO -->
<button class="toggle-btn" onclick="toggleSidebar()">
    <i class="fas fa-bars"></i>
</button>

<div class="sidebar" id="sidebar">
    <h2>RoseSpa</h2>

    <a href="<?= BASE_URL ?>/vista/vista_cliente/vista_citas/layout.php">
        <i class="fas fa-calendar-alt"></i> Reservar Turno
    </a>

    <a href="<?= BASE_URL ?>/vista/vista_cliente/vista_venta_productos/vista_venta.php">
        <i class="fas fa-shopping-bag"></i> Comprar Productos
    </a>

    <a href="<?= BASE_URL ?>/controlador/controladores_adm/controlador_logout/controlador_logout.php?logout=vista_inicio_adm">
        <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
    </a>
</div>

  <div class="content" id="content">
    <h1>Mis Turnos</h1>

    <?php if ($citas->num_rows == 0): ?>
      <div class="card" style="text-align:center;padding:70px;">
        <p style="font-size:1.4rem;color:#ccc;">Aún no tenés turnos reservados</p>
      </div>
    <?php endif; ?>

    <?php while ($c = $citas->fetch_assoc()):
        $id_cita = $c['id_cita'];
        $duracion_total = 0;
        $servicios = [];
        $detalle = $conn->query("SELECT id_combos, id_servicios FROM detalle_cita WHERE id_cita = $id_cita");
        while ($d = $detalle->fetch_assoc()) {
            if ($d['id_combos']) {
                $nom = $conn->query("SELECT nombre FROM combos WHERE id_combos = {$d['id_combos']}")->fetch_assoc()['nombre'] ?? 'Combo';
                $servicios[] = $nom;
                $sum = $conn->query("SELECT SUM(duracion) as t FROM servicios WHERE id_servicios IN (SELECT id_servicios FROM combo_servicios WHERE id_combos = {$d['id_combos']})")->fetch_assoc()['t'] ?? 0;
                $duracion_total += (int)$sum;
            }
            if ($d['id_servicios']) {
                $s = $conn->query("SELECT nombre, duracion FROM servicios WHERE id_servicios = {$d['id_servicios']}")->fetch_assoc();
                $servicios[] = $s['nombre'] ?? 'Servicio';
                $duracion_total += (int)($s['duracion'] ?? 0);
            }
        }
        if (empty($servicios)) $servicios[] = 'Sin detalle';

        $inicio = new DateTime($c['fecha_cita']);
        $fin = clone $inicio;
        $fin->modify("+$duracion_total minutes");
        $ahora = new DateTime();

        $en_espera = $ahora < $inicio;
        $en_curso = $ahora >= $inicio && $ahora <= $fin;
        $terminada = $ahora > $fin;
    ?>
      <div class="card <?= $en_espera ? 'esperando' : ($terminada ? 'terminada' : 'activa') ?>">
        <h3 style="text-align:center;color:var(--primary);font-size:2rem;margin-bottom:18px;">
          Turno #<?= $id_cita ?> - <?= htmlspecialchars($c['nombre_cliente']) ?>
        </h3>
        <div class="info">
          <strong>Servicios:</strong> <?= implode(' + ', $servicios) ?><br>
          <strong>Duración:</strong> <?= $duracion_total >= 60 ? floor($duracion_total/60).'h ' : '' ?><?= $duracion_total%60 ?>min<br>
          <strong>Lugar:</strong> <?= htmlspecialchars($c['nombre_lugar'] ?? 'Sede Principal') ?><br>
          <strong>Inicio:</strong> <?= $inicio->format('d/m/Y H:i') ?><br>
          <strong>Fin estimado:</strong> <?= $fin->format('H:i') ?>
        </div>

        <div class="estado">
          <?php if ($en_espera): ?>
            <i class="fas fa-clock" style="font-size:5.5rem;color:var(--warning);"></i>
            <h4 style="color:var(--warning);margin:18px 0;font-size:2rem;">EN LISTA DE ESPERA</h4>
            <p style="font-size:1.2rem;">Comienza a las <?= $inicio->format('H:i') ?></p>

            <!-- BOTÓN REEMBOLSO GIGANTE (solo si está activo y no terminado) -->
            <?php if ($c['activo']): ?>
              <a href="?cancelar=<?= $id_cita ?>" 
                 onclick="return confirm('¿Cancelar este turno y solicitar el reembolso completo?')"
                 class="btn-cancelar-reembolso">
                CANCELAR Y REEMBOLSAR
              </a>
              <p style="color:#ff6b6b;font-size:0.95rem;margin-top:8px;">
                Se cancelará el turno y se te devolverá el dinero automáticamente
              </p>
            <?php endif; ?>

          <?php elseif ($en_curso): ?>
            <i class="fas fa-spa" style="font-size:5.5rem;color:var(--primary);"></i>
            <h4 style="color:var(--primary);margin:18px 0;font-size:2rem;">¡EN CURSO AHORA!</h4>

            <?php if ($c['activo']): ?>
              <a href="?cancelar=<?= $id_cita ?>" 
                 onclick="return confirm('¿Cancelar este turno y solicitar el reembolso completo?')"
                 class="btn-cancelar-reembolso">
                CANCELAR Y REEMBOLSAR
              </a>
            <?php endif; ?>

          <?php elseif ($terminada): ?>
            <i class="fas fa-check-circle" style="font-size:5.5rem;color:var(--success);"></i>
            <h4 style="color:var(--success);margin:18px 0;font-size:2rem;">TURNO FINALIZADO</h4>
            <?php 
            $resena = $conn->query("SELECT resena_estrellas FROM citas WHERE id_cita=$id_cita")->fetch_assoc()['resena_estrellas'];
            if (!$resena): ?>
              <button onclick="abrirResena(<?= $id_cita ?>)" class="btn btn-resena">Dejar Reseña</button>
            <?php else: ?>
              <p style="color:#aaa;margin-top:20px;">Gracias por tu reseña</p>
            <?php endif; ?>
          <?php endif; ?>
        </div>

        <!-- EL BOTÓN VIEJO DE ABAJO LO SACO PARA QUE NO HAYA DUPLICADOS -->
      </div>
    <?php endwhile; ?>
  </div>

  <!-- Modal Reseña -->
  <div id="modalResena" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.9);z-index:9999;align-items:center;justify-content:center;">
    <div style="background:#2a2a3d;padding:45px;border-radius:20px;width:90%;max-width:520px;text-align:center;">
      <h3 style="color:var(--primary);margin-bottom:30px;font-size:2rem;">¿Cómo fue tu experiencia?</h3>
      <div style="font-size:4rem;margin:35px 0;letter-spacing:12px;">
        <span onclick="rate(1)">Star</span><span onclick="rate(2)">Star</span><span onclick="rate(3)">Star</span><span onclick="rate(4)">Star</span><span onclick="rate(5)">Star</span>
      </div>
      <input type="hidden" id="estrellas" value="5">
      <input type="hidden" id="id_cita">
      <textarea id="comentario" placeholder="Comentario opcional..." style="width:100%;height:110px;padding:18px;background:#333;color:#fff;border:none;border-radius:12px;margin:25px 0;font-size:1rem;"></textarea>
      <br>
      <button onclick="enviar()" class="btn btn-resena" style="padding:16px 45px;font-size:1.1rem;">Enviar</button>
      <button onclick="document.getElementById('modalResena').style.display='none'" style="background:#666;padding:16px 35px;" class="btn">Cancelar</button>
    </div>
  </div>

  <script>
    function toggleSidebar() {
      document.getElementById('sidebar').classList.toggle('hidden');
      document.getElementById('content').classList.toggle('expanded');
    }
    setInterval(() => location.reload(), 30000);

    function rate(n) {
      document.getElementById('estrellas').value = n;
      document.querySelectorAll('#modalResena span').forEach((s,i) => s.style.color = i < n ? '#ffd700' : '#999');
    }
    function abrirResena(id) {
      document.getElementById('id_cita').value = id;
      document.getElementById('modalResena').style.display = 'flex';
      rate(5);
    }
    function enviar() {
      let f = new FormData();
      f.append('id', document.getElementById('id_cita').value);
      f.append('estrellas', document.getElementById('estrellas').value);
      f.append('comentario', document.getElementById('comentario').value);
      fetch('guardar_resena.php', {method:'POST', body:f})
        .then(() => location.reload());
    }
  </script>
</body>
</html>
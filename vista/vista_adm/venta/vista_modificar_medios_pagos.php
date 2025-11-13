<?php
require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');
require_once(ROOT_PATH . '/modelo/modelo_adm/modelo_venta/modelo_venta.php');

$modelo_venta = new modelo_venta($conn);

$id = $_GET['id'] ?? 0;
$funcion_formulario_modificar = $modelo_venta->formulario_modificar_medios_pagos($id);

if (!$funcion_formulario_modificar || $funcion_formulario_modificar->num_rows == 0) {
    die("<div style='text-align:center; padding:50px; background:#2e2e44; color:#ff6b9d; font-size:1.5rem; border-radius:12px; margin:50px auto; max-width:600px;'>
            Medio de pago no encontrado
         </div>");
}

$medio = $funcion_formulario_modificar->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Editar Medio de Pago - RoseSpa</title>
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
      background: url('../../../imagenes/pagos/fondo_pagos.jpg') no-repeat center center fixed;
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
      background: rgba(0,0,0,0.65);
      z-index: -1;
    }

    .sidebar{
      width: 240px;
      background: rgba(42,42,61,0.95);
      padding: 20px;
      display:flex;
      flex-direction:column;
      box-shadow: var(--shadow);
      position: fixed;
      top:0;left:0;bottom:0;
      transition: transform .3s ease;
      z-index: 1000;
      overflow-y: auto;
    }
    .sidebar h2{color: var(--primary); margin-bottom: 30px; text-align: center; font-size: 1.8rem;}
    .sidebar a{display:flex; align-items:center; gap:10px; color: var(--text); text-decoration:none; padding:12px; border-radius:6px; margin-bottom:6px; transition:.3s;}
    .sidebar a:hover{background: var(--primary); color:#fff;}
    .sidebar.hidden { transform: translateX(-100%); }

    .toggle-btn{
      position: fixed;
      top: 20px;
      left: 20px;
      background: rgba(255, 107, 157, 0.7);
      color:#fff;
      border:none;
      padding:10px 14px;
      font-size:1.4rem;
      border-radius:8px;
      cursor:pointer;
      z-index:1100;
      box-shadow: var(--shadow);
    }
    .toggle-btn:hover{ background: rgba(224, 85, 133, 0.9); }

    .content{
      margin-left: 240px;
      flex:1;
      padding:30px;
      transition: margin-left .3s ease;
    }
    .content.expanded{ margin-left: 0; }

    h1{
      font-size:2.5rem;
      margin-bottom:30px;
      color: var(--primary);
      text-shadow: 2px 2px 8px rgba(0,0,0,0.7);
      text-align: center;
    }

    .form-card{
      background: rgba(46,46,68,0.95);
      padding: 35px;
      border-radius: 14px;
      box-shadow: var(--shadow);
      max-width: 700px;
      margin: 0 auto;
      border-left: 6px solid var(--primary);
    }
    .form-grid{
      display: grid;
        grid-template-columns: 1fr 1fr;
      gap: 25px;
      margin-bottom: 25px;
    }
    .form-group{
      display: flex;
      flex-direction: column;
    }
    .form-group label{
      margin-bottom: 10px;
      font-weight: 600;
      color: var(--primary);
      font-size: 1.1rem;
    }
    .form-group input, .form-group select{
      padding: 14px;
      border-radius: 10px;
      border: 1px solid #555;
      background: rgba(255,255,255,0.1);
      color: var(--text);
      font-size: 1rem;
      transition: .3s;
    }
    .form-group input:focus, .form-group select:focus{
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 12px rgba(255,107,157,0.5);
    }

    .btn-submit{
      background: var(--primary);
      color: white;
      padding: 16px 32px;
      border: none;
      border-radius: 10px;
      font-size: 1.2rem;
      font-weight: 700;
      cursor: pointer;
      transition: .3s;
      box-shadow: var(--shadow);
      width: 100%;
      margin-top: 20px;
      text-transform: uppercase;
      letter-spacing: 1px;
    }
    .btn-submit:hover{
      background: var(--primary-dark);
      transform: translateY(-3px);
      box-shadow: 0 8px 20px rgba(255,107,157,0.4);
    }

    .btn-volver{
      display: block;
      text-align: center;
      margin-top: 20px;
      padding: 12px 24px;
      background: #555;
      color: white;
      text-decoration: none;
      border-radius: 8px;
      font-weight: 600;
      transition: .3s;
    }
    .btn-volver:hover{
      background: #777;
      transform: translateY(-2px);
    }
  </style>
</head>
<body>

  <button class="toggle-btn" onclick="toggleSidebar()">
    <i class="fas fa-bars"></i>
  </button>

  <div class="sidebar" id="sidebar">
    <h2>RoseSpa</h2>
    <a href="<?= BASE_URL ?>/vista/vista_adm/servicios_combos/vista_inicio_adm.php">Servicios y Combos</a>
    <a href="<?= BASE_URL ?>/vista/vista_adm/inventario/InventarioVista.php">Productos</a>
    <a href="<?= BASE_URL ?>/vista/vista_adm/venta/vista_medios_pagos.php">Ventas y Compras</a>
    <a href="<?= BASE_URL ?>/vista/vista_adm/lugares/lugares.php">Lugares</a>
    <a href="<?= BASE_URL ?>/vista/vista_adm/proveedores/vista_proveedores.php">Proveedores</a>
    <a href="<?= BASE_URL ?>/vista/vista_adm/trabajadores/trabajadores_lista.php">Trabajadores</a>
    <a href="<?= BASE_URL ?>/vista/vista_adm/clientes/clientes_lista.php">Clientes</a>
    <a href="<?= BASE_URL ?>/vista/vista_adm/vista_logouts/vista_logouts_adm.php">Logeos y Movimientos</a>
    <a href="<?= BASE_URL ?>/vista/vista_adm/citas/citas.php">Citas</a>
    <a href="<?= BASE_URL ?>/controlador/controladores_adm/controlador_logout/controlador_logout.php?logout=vista_inicio_adm">Cerrar sesión</a>
  </div>

  <div class="content" id="content">
    <h1>Modificar Medio de Pago</h1>

    <div class="form-card">
      <form action="<?= BASE_URL ?>/controlador/controladores_adm/controlador_venta/controlador_venta.php" method="post">
        <input type="hidden" name="vista_modificar_medios_pagos" value="vista_modificar">
        <input type="hidden" name="id_metodo_pago" value="<?= $medio['id_metodo_pago'] ?>">

        <div class="form-grid">
          <div class="form-group">
            <label>Nombre del Método</label>
            <input type="text" name="nombre_metodo_pago" value="<?= htmlspecialchars($medio['metodo_pago']) ?>" required>
          </div>

          <div class="form-group">
            <label>Tipo de Ajuste</label>
            <select name="importe" required>
              <?php if ($medio['incremento'] !== null): ?>
                <option value="1" selected>Incremento (+%)</option>
                <option value="0">Decremento (-%)</option>
              <?php else: ?>
                <option value="0" selected>Decremento (-%)</option>
                <option value="1">Incremento (+%)</option>
              <?php endif; ?>
            </select>
          </div>
        </div>

        <div class="form-grid">
          <div class="form-group">
            <label>Cantidad (%)</label>
            <input type="number" name="cantidad_importe" min="0" max="100" step="0.5"
                   value="<?= $medio['incremento'] ?? $medio['decremento'] ?>" required>
          </div>

          <div class="form-group">
            <label>Estado</label>
            <select name="activo">
              <option value="1" <?= $medio['activo'] == 1 ? 'selected' : '' ?>>Activo</option>
              <option value="0" <?= $medio['activo'] == 0 ? 'selected' : '' ?>>Inactivo</option>
            </select>
          </div>
        </div>

        <button type="submit" class="btn-submit">
          Guardar Cambios
        </button>
      </form>

      <a href="<?= BASE_URL ?>/vista/vista_adm/venta/vista_medios_pagos.php" class="btn-volver">
        Cancelar y volver
      </a>
    </div>
  </div>

  <script>
    function toggleSidebar() {
      document.getElementById('sidebar').classList.toggle('hidden');
      document.getElementById('content').classList.toggle('expanded');
    }
  </script>
</body>
</html>
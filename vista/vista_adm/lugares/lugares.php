<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/modelo_adm/lugares/modelo_lugares.php');
require_once(ROOT_PATH . '/modelo/BD.php');

$modelo = new Lugar($conn);
$lugares = $modelo->obtenerLugares();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lugares - RoseSpa</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
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
      overflow-y: auto;
    }
    .sidebar h2{color: var(--primary); margin-bottom: 30px; text-align: center;}
    .sidebar a{display:flex; align-items:center; gap:10px; color: var(--text); text-decoration:none; padding:12px; border-radius:6px; margin-bottom:6px; transition:.3s;}
    .sidebar a:hover{background: var(--primary); color:#fff;}
    .sidebar.hidden { transform: translateX(-100%); }

    .toggle-btn{
      position: fixed;
      top: 20px;
      left: 20px;
      background: rgba(255, 107, 157, 0.6);
      color:#fff;
      border:none;
      padding:10px 14px;
      font-size:1.4rem;
      border-radius:8px;
      cursor:pointer;
      z-index:1100;
      box-shadow: var(--shadow);
    }
    .toggle-btn:hover{ background: rgba(224, 85, 133, 0.8); }

    .content{
      margin-left: 240px;
      flex:1;
      padding:30px;
      transition: margin-left .3s ease;
    }
    .content.expanded{ margin-left: 0; }

    h1{
      font-size:2rem;
      margin-bottom:20px;
      color: var(--primary);
      text-shadow: 2px 2px 6px rgba(0,0,0,0.6);
      margin-left: 60px !important;
    }

    .form-card{
      background: rgba(46,46,68,0.9);
      padding: 20px;
      border-radius: 8px;
      box-shadow: var(--shadow);
      margin-bottom: 25px;
    }
    .form-grid{
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 16px;
      margin-bottom: 16px;
    }
    .form-group{
      display: flex;
      flex-direction: column;
    }
    .form-group label{
      margin-bottom: 6px;
      font-weight: 600;
      color: var(--primary);
    }
    .form-group input, .form-group select{
      padding: 10px;
      border-radius: 6px;
      border: 1px solid #555;
      background: rgba(255,255,255,0.1);
      color: var(--text);
    }
    #map{
      height: 350px;
      border-radius: 8px;
      margin: 16px 0;
      box-shadow: var(--shadow);
    }

    table{
      width:100%;
      border-collapse:collapse;
      background: rgba(46,46,68,0.9);
      border-radius:8px;
      overflow:hidden;
      box-shadow: var(--shadow);
      margin-bottom:25px;
    }
    th,td{padding:14px 16px; text-align:left; font-size:0.95rem;}
    th{background: var(--primary-dark); color:#fff; font-weight:600;}
    tr:nth-child(even){background: rgba(37,37,56,0.9);}
    tr:hover{background: rgba(255,107,157,0.1);}
    td img{border-radius:6px; width: 60px; height: 60px; object-fit: cover;}

    .btn{
      padding:6px 12px;
      border-radius:6px;
      font-size:0.85rem;
      font-weight:600;
      text-decoration:none;
      margin-right:5px;
      display:inline-block;
      transition:.3s;
    }
    .btn-view{background: var(--success);color:#fff;}
    .btn-edit{background: var(--warning);color:#fff;}
    .btn-delete{background: var(--danger);color:#fff;}
    .btn:hover{opacity:.85;}
    .add-btn{
      display:inline-block;
      padding:10px 18px;
      background: var(--primary);
      color:#fff;
      text-decoration:none;
      border-radius:6px;
      font-weight:600;
      transition:.3s;
      box-shadow: var(--shadow);
      margin-bottom: 20px;
    }
    .add-btn:hover{background: var(--primary-dark);}

    .geo-btn{
      background: var(--primary);
      color: #fff;
      border: none;
      padding: 10px 16px;
      border-radius: 6px;
      cursor: pointer;
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 8px;
      margin-top: 8px;
    }
    .geo-btn:hover{background: var(--primary-dark);}

    .success-msg {
      background: rgba(39, 174, 96, 0.9);
      color: white;
      padding: 15px;
      border-radius: 8px;
      margin-bottom: 20px;
      text-align: center;
      font-weight: bold;
    }
  </style>
</head>
<body>

  <button class="toggle-btn" onclick="toggleSidebar()">
    <i class="fas fa-bars"></i>
  </button>

  <div class="sidebar" id="sidebar">
    <h2 class="titulo_menu">RoseSpa</h2>
    <a href="<?= BASE_URL ?>/vista/vista_adm/servicios_combos/vista_inicio_adm.php"><i class="fas fa-spa"></i> Servicios y Combos</a>
    <a href="<?= BASE_URL ?>/vista/vista_adm/inventario/InventarioVista.php"><i class="fas fa-boxes"></i> Productos</a>
    <a href="<?= BASE_URL ?>/vista/vista_adm/venta/vista_medios_pagos.php"><i class="fas fa-money-check-alt"></i> Medios de pago</a>
    <a href="<?= BASE_URL ?>/vista/vista_adm/lugares/lugares.php"><i class="fas fa-map-marker-alt"></i> Lugares</a>
    <a href="<?= BASE_URL ?>/vista/vista_adm/proveedores/vista_proveedores.php"><i class="fas fa-truck"></i> Proveedores</a>
    <a href="<?= BASE_URL ?>/vista/vista_adm/trabajadores/trabajadores_lista.php"><i class="fas fa-user-tie"></i> Trabajadores</a>
    <a href="<?= BASE_URL ?>/vista/vista_adm/clientes/clientes_lista.php"><i class="fas fa-users"></i> Clientes</a>
    <a href="<?= BASE_URL ?>/vista/vista_adm/vista_logouts/vista_logouts_adm.php"><i class="fas fa-history"></i> Logeos y Movimientos</a>
    <a href="<?= BASE_URL ?>/vista/vista_adm/citas/citas.php"><i class="fas fa-calendar-check"></i> Citas</a>
    <a href="<?= BASE_URL ?>/vista/vista_adm/promociones/vista_promociones.php"><i class="fas fa-tag"></i> Promociones</a>
    <a href="<?= BASE_URL ?>/vista/vista_adm/venta/historial_ventas.php"><i class="fas fa-cash-register"></i> Historial de compras</a>
    <a href="<?= BASE_URL ?>/controlador/controladores_adm/controlador_logout/controlador_logout.php?logout=vista_inicio_adm"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a>
  </div>

  <div class="content" id="content">
    <h1>Lugares</h1>

    <?php if (isset($_GET['exito'])): ?>
      <div class="success-msg">¡Lugar agregado con éxito!</div>
    <?php endif; ?>

    <!-- FORMULARIO CORREGIDO -->
    <div class="form-card">
      <form method="POST" action="<?= BASE_URL ?>/controlador/controladores_adm/lugares/controlador_lugares.php" enctype="multipart/form-data">
        <div class="form-grid">
          <div class="form-group">
            <label>Nombre del lugar</label>
            <input type="text" name="nombre" required placeholder="Ej: Sucursal Centro">
          </div>
          <div class="form-group">
            <label>Coordenadas (lat,lng)</label>
            <input type="text" id="cooordenadas" name="cooordenadas" readonly required value="-26.185800,-58.175000">
            <button type="button" class="geo-btn" onclick="usarMiUbicacion()">
              <i class="fas fa-location-arrow"></i> Usar mi ubicación
            </button>
          </div>
        </div>

        <div class="form-group">
          <label>Selecciona en el mapa</label>
          <div id="map"></div>
        </div>

        <div class="form-grid">
          <div class="form-group">
            <label>Imagen del lugar</label>
            <input type="file" name="imagen" accept="image/*" required>
          </div>
          <div class="form-group" style="display:flex; align-items:center; gap:10px;">
            <label style="margin:0;"><input type="checkbox" name="activo" checked> Activo</label>
          </div>
        </div>

        <button type="submit" name="agregar" class="add-btn">+ Agregar Lugar</button>
      </form>
    </div>

    <!-- TABLA CON BOTÓN BORRAR CORREGIDO -->
    <?php if ($lugares && $lugares->num_rows > 0): ?>
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Mapa</th>
            <th>Imagen</th>
            <th>Activo</th>
            <th colspan="3">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $lugares->fetch_assoc()): ?>
            <tr>
              <td>#<?= $row['id_lugar'] ?></td>
              <td><?= htmlspecialchars($row['nombre_lugar']) ?></td>
              <td>
                <?php if (!empty($row['cooordenadas'])): 
                  list($lat, $lng) = explode(',', $row['cooordenadas']);
                ?>
                  <iframe width="180" height="100" style="border:0; border-radius:6px;" loading="lazy"
                          src="https://maps.google.com/maps?q=<?= $lat ?>,<?= $lng ?>&output=embed"></iframe>
                <?php else: ?>
                  <span style="color:#ff6b9d;">Sin coordenadas</span>
                <?php endif; ?>
              </td>
              <td>
                <?php if ($row['imagen_lugar'] && file_exists(ROOT_PATH . '/' . $row['imagen_lugar'])): ?>
                  <img src="<?= BASE_URL ?>/<?= $row['imagen_lugar'] ?>" alt="Lugar">
                <?php else: ?>
                  <div style="width:60px;height:60px;background:#444;border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:0.7rem;">
                    Sin imagen
                  </div>
                <?php endif; ?>
              </td>
              <td><?= $row['activo'] ? 'Activo' : 'Inactivo' ?></td>
              <td><a class="btn btn-edit" href="#">Editar</a></td>
              <td>
                <a class="btn btn-delete" 
                   href="<?= BASE_URL ?>/controlador/controladores_adm/lugares/controlador_lugares.php?eliminar=<?= $row['id_lugar'] ?>"
                   onclick="return confirm('¿Eliminar este lugar?')">
                   Borrar
                </a>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p style="color: var(--text-muted); text-align:center; padding:40px; background:rgba(46,46,68,0.9); border-radius:8px;">
        No hay lugares registrados aún.
      </p>
    <?php endif; ?>
  </div>

  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <script>
    function toggleSidebar() {
      document.getElementById('sidebar').classList.toggle('hidden');
      document.getElementById('content').classList.toggle('expanded');
    }

    const map = L.map('map').setView([-26.1858, -58.1750], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
    const marker = L.marker([-26.1858, -58.1750], { draggable: true }).addTo(map);

    function updateCoords(lat, lng) {
      document.getElementById('cooordenadas').value = `${lat.toFixed(6)},${lng.toFixed(6)}`;
    }

    marker.on('dragend', e => {
      const pos = e.target.getLatLng();
      updateCoords(pos.lat, pos.lng);
    });

    map.on('click', e => {
      marker.setLatLng(e.latlng);
      updateCoords(e.latlng.lat, e.latlng.lng);
    });

    function usarMiUbicacion() {
      if (!navigator.geolocation) return alert('Geolocalización no soportada');
      navigator.geolocation.getCurrentPosition(pos => {
        const { latitude, longitude } = pos.coords;
        map.setView([latitude, longitude], 16);
        marker.setLatLng([latitude, longitude]);
        updateCoords(latitude, longitude);
      }, err => alert('Error: ' + err.message));
    }

    updateCoords(-26.185800, -58.175000);
  </script>
</body>
</html>
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
    <title>Lugares</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
    <style>
        body { font-family: Arial; margin: 30px; background: #fff; color: #000; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #000; text-align: left; vertical-align: top; }
        th { background: #000; color: #fff; }
        input, button { padding: 8px; margin: 5px; font-size: 14px; }
        img { max-width: 100px; border-radius: 8px; }
        #map { height: 300px; width: 100%; margin: 10px 0; border: 1px solid #000; border-radius: 8px; }
        .btn-ubicacion {
            background: #28a745; color: #fff; border: none; border-radius: 25px;
            padding: 10px 18px; cursor: pointer; font-size: 15px; font-weight: bold;
            display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.2); transition: 0.3s;
        }
        .btn-ubicacion:hover { background: #218838; transform: scale(1.05); }
    </style>
</head>
<body>
<h1>Lugares</h1>

<!-- Formulario -->
<form method="POST" action="<?php echo BASE_URL; ?>/controlador/lugares/controlador_lugares.php" enctype="multipart/form-data">
    <input type="text" name="nombre" placeholder="Nombre del lugar" required>
    <input type="text" id="cooordenadas" name="cooordenadas" placeholder="Coordenadas (lat,lng)" readonly required>
    <button type="button" class="btn-ubicacion" onclick="usarMiUbicacion()">📍 Usar mi ubicación actual</button>
    <div id="map"></div>
    <input type="file" name="imagen" accept="image/*" required>
    <label><input type="checkbox" name="activo" checked> Activo</label>
    <button type="submit" name="agregar">Agregar</button>
</form>

<!-- Tabla de lugares -->
<table>
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Mapa</th>
        <th>Imagen</th>
        <th>Activo</th>
        <th>Acción</th>
    </tr>
    <?php while($row = $lugares->fetch_assoc()): ?>
    <tr>
        <td><?php echo $row['id_lugar']; ?></td>
        <td><?php echo $row['nombre_lugar']; ?></td>
        <td>
            <?php if (!empty($row['cooordenadas'])): ?>
                <iframe width="200" height="150" style="border:0;" loading="lazy" allowfullscreen
                        src="https://maps.google.com/maps?q=<?php echo urlencode($row['cooordenadas']); ?>&output=embed"></iframe>
            <?php else: ?>
                <iframe width="200" height="150" style="border:0;" loading="lazy" allowfullscreen
                        src="https://maps.google.com/maps?q=-26.1858,-58.1750&output=embed"></iframe>
            <?php endif; ?>
        </td>
        <td>
            <?php if($row['imagen_lugar']): ?>
                <img src="<?php echo BASE_URL . '/' . $row['imagen_lugar']; ?>" alt="Imagen Lugar">
            <?php endif; ?>
        </td>
        <td><?php echo $row['activo'] ? 'Sí' : 'No'; ?></td>
        <td>
            <a href="<?php echo BASE_URL; ?>/controlador/lugares/controlador_lugares.php?eliminar=<?php echo $row['id_lugar']; ?>">Eliminar</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
    var map = L.map('map').setView([-26.1858, -58.1750], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19, attribution: '© OpenStreetMap' }).addTo(map);

    var marker = L.marker([-26.1858, -58.1750], {draggable:true}).addTo(map);

    marker.on('dragend', function(e){
        var lat = marker.getLatLng().lat.toFixed(6);
        var lng = marker.getLatLng().lng.toFixed(6);
        document.getElementById('cooordenadas').value = lat + "," + lng;
    });

    map.on('click', function(e){
        marker.setLatLng(e.latlng);
        var lat = e.latlng.lat.toFixed(6);
        var lng = e.latlng.lng.toFixed(6);
        document.getElementById('cooordenadas').value = lat + "," + lng;
    });

    document.getElementById('cooordenadas').value = "-26.185800,-58.175000";

    function usarMiUbicacion() {
        if(navigator.geolocation){
            navigator.geolocation.getCurrentPosition(function(position){
                var lat = position.coords.latitude.toFixed(6);
                var lng = position.coords.longitude.toFixed(6);
                map.setView([lat,lng],15);
                marker.setLatLng([lat,lng]);
                document.getElementById('cooordenadas').value = lat + "," + lng;
            }, function(error){
                alert("No se pudo obtener la ubicación: " + error.message);
            });
        } else { alert("Tu navegador no soporta geolocalización."); }
    }
</script>
</body>
</html>



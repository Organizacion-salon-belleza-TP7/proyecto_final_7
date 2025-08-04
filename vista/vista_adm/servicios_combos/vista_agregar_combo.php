<?php
require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');
require_once(ROOT_PATH . '/modelo/modelo_adm/servicios_combos/modelo_inicio_adm.php');

$servicio_modelo = new servicios($conn);
$resultado_traer_servicios = $servicio_modelo->formulario_agregar_combo();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Agregar combo</title>
</head>
<body>
  <h1>Agregar combo</h1>

  <form action="<?= BASE_URL ?>/controlador/controladores_adm/servicios_combos/controlador_inicio_adm.php" method="post" enctype="multipart/form-data">
    <table border="1">
      <tr>
        <td>Nombre</td>
        <td><input type="text" name="nombre_combo"></td>
      </tr>
      <tr>
        <td>Descripcion Combo</td>
        <td><input type="text" name="descripcion_combo"></td>
      </tr>
      <tr>
        <td>Precio</td>
        <td><input type="number" name="precio_combo"></td>
      </tr>
      <tr>
        <td>Imagen</td>
        <td><input type="file" name="imagen_combo"></td>
      </tr>
      <tr>
        <td>Activo</td>
        <td>
          <select name="activo_combo">
            <option value="activo">Activo</option>
            <option value="inactivo">Inactivo</option>
          </select>
        </td>
      </tr>
    </table>

    <h2>Servicios</h2>
    <select id="select-servicios" multiple size="8">
      <?php foreach ($resultado_traer_servicios as $s): ?>
        <option value="<?= $s['id_servicios'] ?>">
          <?= htmlspecialchars($s['nombre']) ?> ($<?= number_format($s['precio'], 2) ?>)
        </option>
      <?php endforeach; ?>
    </select>
    <br>
    <button type="button" onclick="agregarServicios()">Agregar seleccionados</button>

    <h3>Servicios seleccionados:</h3>
    <ul id="servicios-seleccionados"></ul>

    <input type="hidden" name="servicios_combo" id="servicios_combo">

    <br><br>
    <button type="submit" name="enviar_nuevo_combo" value="vista_agregar_combo_adm">Guardar Combo</button>
  </form>

  <script>
    const serviciosDisponibles = <?= json_encode($resultado_traer_servicios) ?>;
    const seleccionados = [];

    function agregarServicios() {
      const select = document.getElementById('select-servicios');
      for (const option of select.selectedOptions) {
        const id = parseInt(option.value);
        if (!seleccionados.find(s => s.id_servicios === id)) {
          const servicio = serviciosDisponibles.find(s => s.id_servicios == id);
          if (servicio) {
            seleccionados.push(servicio);
          }
        }
      }
      actualizarVista();
    }

    function quitarServicio(id) {
      const index = seleccionados.findIndex(s => s.id_servicios === id);
      if (index !== -1) {
        seleccionados.splice(index, 1);
        actualizarVista();
      }
    }

    function actualizarVista() {
      const lista = document.getElementById('servicios-seleccionados');
      lista.innerHTML = '';

      seleccionados.forEach(s => {
        const li = document.createElement('li');
        const texto = document.createTextNode(`${s.nombre} - $${s.precio} `);

        const btnQuitar = document.createElement('button');
        btnQuitar.type = 'button';
        btnQuitar.textContent = 'Quitar';
        btnQuitar.onclick = () => quitarServicio(s.id_servicios);

        li.appendChild(texto);
        li.appendChild(btnQuitar);
        lista.appendChild(li);
      });

      // Actualiza el input oculto
      document.getElementById('servicios_combo').value = seleccionados.map(s => s.id_servicios).join(',');
    }
  </script>
</body>
</html>

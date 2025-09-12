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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Agregar Combo</title>
  <style>
    :root {
        --bg: #1e1e2f;
        --primary: #ff6b9d;
        --primary-dark: #e05585;
        --text: #f1f1f1;
        --card: rgba(46,46,68,0.95);
        --shadow: 0 4px 12px rgba(0,0,0,0.3);
    }
    * {margin:0;padding:0;box-sizing:border-box;}
    body {
        font-family: 'Segoe UI', sans-serif;
        background: url('../../../imagenes/lugares/istockphoto-1856117770-612x612.jpg') no-repeat center center fixed;
        background-size: cover;
        color: var(--text);
        min-height: 100vh;
    }
    body::before {
        content:"";
        position:fixed;
        top:0;left:0;right:0;bottom:0;
        background: rgba(0,0,0,0.6);
        z-index:-1;
    }
    .container {
        max-width: 900px;
        margin: 40px auto;
        background: var(--card);
        padding: 30px;
        border-radius: 12px;
        box-shadow: var(--shadow);
    }
    h1, h2, h3 {
        text-align: center;
        margin-bottom: 20px;
        color: var(--primary);
    }
    table, select, input, button, ul {
        width: 100%;
        margin-bottom: 15px;
    }
    table {
        border-collapse: collapse;
    }
    th, td {
        padding: 10px;
        text-align: center;
        border-bottom: 1px solid #444;
    }
    thead {
        background: var(--primary);
        color: #fff;
    }
    tr:nth-child(even) {
        background: rgba(255,255,255,0.05);
    }
    tr:hover {
        background: rgba(255,255,255,0.1);
    }
    input[type="text"], input[type="number"], select, input[type="file"] {
        padding: 8px;
        border-radius: 6px;
        border: none;
        outline: none;
        background: rgba(255,255,255,0.1);
        color: var(--text);
    }
    button {
        background: var(--primary);
        color: #fff;
        border: none;
        padding: 10px 15px;
        border-radius: 6px;
        cursor: pointer;
        transition: 0.3s;
        font-weight: bold;
        margin-top: 5px;
    }
    button:hover {
        background: var(--primary-dark);
    }
    ul {
        list-style: none;
        padding: 0;
    }
    ul li {
        background: rgba(255,255,255,0.05);
        padding: 8px;
        margin-bottom: 5px;
        border-radius: 6px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    ul li button {
        width: auto;
        padding: 5px 10px;
        font-size: 0.9rem;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Agregar Combo</h1>
    <form action="<?= BASE_URL ?>/controlador/controladores_adm/servicios_combos/controlador_inicio_adm.php" method="post" enctype="multipart/form-data">
      <table>
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
            <?= htmlspecialchars($s['nombre']) ?> ($<?= number_format($s['precio_servicio'], 2) ?>)
          </option>
        <?php endforeach; ?>
      </select>
      <button type="button" onclick="agregarServicios()">Agregar seleccionados</button>

      <h3>Servicios seleccionados:</h3>
      <ul id="servicios-seleccionados"></ul>

      <input type="hidden" name="servicios_combo" id="servicios_combo">

      <button type="submit" name="enviar_nuevo_combo" value="vista_agregar_combo_adm">Guardar Combo</button>
    </form>
  </div>

  <script>
    const serviciosDisponibles = <?= json_encode($resultado_traer_servicios) ?>;
    const seleccionados = [];

    function agregarServicios() {
      const select = document.getElementById('select-servicios');
      for (const option of select.selectedOptions) {
        const id = parseInt(option.value);
        if (!seleccionados.find(s => s.id_servicios === id)) {
          const servicio = serviciosDisponibles.find(s => s.id_servicios == id);
          if (servicio) seleccionados.push(servicio);
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

      document.getElementById('servicios_combo').value = seleccionados.map(s => s.id_servicios).join(',');
    }
  </script>
</body>
</html>

<?php  
require_once "../controladores/ControladorContacto.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {     
    ControladorContacto::guardarContacto();     
    exit;
}

$proveedores = ControladorContacto::mostrarProveedores(); 
$trabajadores = ControladorContacto::mostrarTrabajadores(); 
$contactos = ControladorContacto::mostrarContactos();
?> 

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Contactos - RoseSpa</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
:root {
  --bg: #1e1e2f;
  --primary: #ff6b9d;
  --primary-dark: #e05585;
  --text: #f1f1f1;
  --text-muted: #aaa;
  --card: #2e2e44;
  --danger: #e74c3c;
  --shadow: 0 4px 12px rgba(0,0,0,0.3);
}

* { margin:0; padding:0; box-sizing:border-box; }

body {
  font-family: 'Segoe UI', sans-serif;
  background: url('../../imagenes/lugares/istockphoto-1856117770-612x612.jpg') no-repeat center center fixed;
  background-size: cover;
  color: var(--text);
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 30px 0;
}

body::before {
  content: "";
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.6);
  z-index: -1;
}

h2 {
  color: var(--primary);
  text-align: center;
  margin: 30px 0 20px;
  text-shadow: 2px 2px 6px rgba(0,0,0,0.5);
}

/* FORMULARIO */
form {
  background: rgba(46,46,68,0.95);
  padding: 30px;
  border-radius: 12px;
  box-shadow: var(--shadow);
  width: 90%;
  max-width: 550px;
  display: flex;
  flex-direction: column;
  gap: 15px;
  margin-bottom: 40px;
}

label {
  font-weight: 600;
  color: var(--primary);
}

input[type=text],
input[type=email],
select {
  width: 100%;
  padding: 10px 15px;
  border-radius: 6px;
  border: 1px solid rgba(255, 255, 255, 0.2);
  font-size: 1rem;
  background: rgba(255,255,255,0.1);
  color: var(--text);
  transition: .3s;
}

input:focus, select:focus {
  background: rgba(255,255,255,0.2);
  border: 1px solid var(--primary);
  outline: none;
}

button {
  background: var(--primary);
  color: #fff;
  border: none;
  padding: 10px 18px;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
  box-shadow: var(--shadow);
  transition: .3s;
}

button:hover { background: var(--primary-dark); }

.box {
  padding: 15px;
  border-left: 3px solid var(--primary);
  background: rgba(255,255,255,0.05);
  border-radius: 6px;
  margin-top: 10px;
}

hr {
  width: 80%;
  border: none;
  border-top: 1px solid rgba(255,255,255,0.1);
  margin: 30px 0;
}
</style>
</head>

<body>

<h2><i class="fas fa-id-card-alt"></i> Formulario de Contacto</h2>

<form method="POST">

    <!-- TIPO DE CONTACTO -->
    <label for="tipo_contacto">Tipo de contacto:</label>
    <select name="tipo_contacto" id="tipo_contacto" required>
        <option value="">-- Seleccionar --</option>
        <option value="proveedor">Proveedor</option>
        <option value="trabajador">Trabajador</option>
    </select>

    <!-- SELECT PROVEEDOR -->
    <div id="box_proveedor" class="box" style="display:none;">
        <label for="id_proveedor">Proveedor:</label>
        <select name="id_proveedor" id="id_proveedor">
            <option value="">-- Seleccione un proveedor --</option>
            <?php while ($p = $proveedores->fetch_assoc()): ?>
                <option value="<?= $p['id_proveedor'] ?>">
                    <?= htmlspecialchars($p['nombre_proveedor'] . " " . $p['apellido_proveedor']) ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>

    <!-- SELECT TRABAJADOR -->
    <div id="box_trabajador" class="box" style="display:none;">
        <label for="id_trabajador">Trabajador:</label>
        <select name="id_trabajador" id="id_trabajador">
            <option value="">-- Seleccione un trabajador --</option>
            <?php while ($t = $trabajadores->fetch_assoc()): ?>
                <option value="<?= $t['id_trabajador'] ?>">
                    <?= htmlspecialchars($t['nombre_trabajador'] . " " . $t['apellido_trabajador']) ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>


    <label for="codigo_area">Código de área:</label>
    <input type="text" name="codigo_area" required>

    <label for="numero_telefonico">Número telefónico:</label>
    <input type="text" name="numero_telefonico" required>

    <label for="correo_electronico">Correo electrónico:</label>
    <input type="email" name="correo_electronico" required>

    <button type="submit"><i class="fas fa-save"></i> Guardar Contacto</button>
</form>

<hr>

<script>
document.getElementById("tipo_contacto").addEventListener("change", function() {

    let tipo = this.value;

    document.getElementById("box_proveedor").style.display = 
        (tipo === "proveedor") ? "block" : "none";

    document.getElementById("box_trabajador").style.display = 
        (tipo === "trabajador") ? "block" : "none";
});
</script>

</body>
</html>

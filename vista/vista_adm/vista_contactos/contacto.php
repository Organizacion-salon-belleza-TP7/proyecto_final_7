<?php  
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');
require_once(ROOT_PATH . '/modelo/modelo_adm/modelo_contactos/ModeloContacto.php');

date_default_timezone_set('America/Argentina/Buenos_Aires');

// Obtener pantalla de origen: vista_proveedores o vista_trabajadores
$pantalla = $_GET["vista"] ?? "";

// Obtener datos
$modeloContacto = new ModeloContacto($conn);

// Inicializar variables
$proveedores = null;
$trabajadores = null;
$tipo_contacto = "";

// Determinar qué datos cargar según la pantalla de origen
if ($pantalla === "vista_proveedores") {
    $tipo_contacto = "proveedor";
    $proveedores = $modeloContacto->obtenerProveedores();
} 
elseif ($pantalla === "vista_trabajadores") {
    $tipo_contacto = "trabajador";
    $trabajadores = $modeloContacto->obtenerTrabajadores();
}

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
  background: url('../../../imagenes/lugares/istockphoto-1856117770-612x612.jpg') no-repeat center center fixed;
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

button:hover {
  background: var(--primary-dark);
}

.box {
  padding: 15px;
  border-left: 3px solid var(--primary);
  background: rgba(255,255,255,0.05);
  border-radius: 6px;
  margin-top: 10px;
}

.info-tipo {
  padding: 12px 15px;
  background: rgba(255,107,157,0.1);
  border-radius: 6px;
  border-left: 4px solid var(--primary);
  margin-bottom: 10px;
  font-weight: 500;
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

<h2><i class="fas fa-id-card-alt"></i> Registrar Contacto</h2>

<form method="POST" action="<?= BASE_URL ?>/controlador/controladores_adm/controlador_contactos/ControladorContacto.php">

    <!-- Envía al controlador desde qué pantalla vino -->
    <input type="hidden" name="pantalla_origen" value="<?= $pantalla ?>">
    <input type="hidden" name="guardar_contacto" value="1">
    <input type="hidden" name="tipo_contacto" value="<?= $tipo_contacto ?>">

    <?php  
    // Mostrar información del tipo de contacto
    if ($tipo_contacto === "proveedor") {
        echo '<div class="info-tipo">';
        echo '<i class="fas fa-truck"></i> Agregando contacto para <strong>Proveedor</strong>';
        echo '</div>';
    } elseif ($tipo_contacto === "trabajador") {
        echo '<div class="info-tipo">';
        echo '<i class="fas fa-user-tie"></i> Agregando contacto para <strong>Trabajador</strong>';
        echo '</div>';
    }
    ?>

    <?php if ($tipo_contacto === "proveedor"): ?>
        <!-- Selección de proveedor -->
        <div class="box">
            <label for="id_proveedor">Seleccione Proveedor:</label>
            <select name="id_proveedor" id="id_proveedor" required>
                <option value="">-- Seleccione un proveedor --</option>
                <?php while ($p = $proveedores->fetch_assoc()): ?>
                    <option value="<?= $p['id_proveedor'] ?>">
                        <?= htmlspecialchars($p['nombre_proveedor'] . " " . $p['apellido_proveedor']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
    <?php endif; ?>

    <?php if ($tipo_contacto === "trabajador"): ?>
        <!-- Selección de trabajador -->
        <div class="box">
            <label for="id_trabajador">Seleccione Trabajador:</label>
            <select name="id_trabajador" id="id_trabajador" required>
                <option value="">-- Seleccione un trabajador --</option>
                <?php while ($t = $trabajadores->fetch_assoc()): ?>
                    <option value="<?= $t['id_trabajador'] ?>">
                        <?= htmlspecialchars($t['nombre_trabajador'] . " " . $t['apellido_trabajador']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
    <?php endif; ?>

    <!-- Datos del contacto -->
    <label for="codigo_area">Código de área:</label>
    <input type="text" name="codigo_area" required placeholder="Ej: 11, 351">

    <label for="numero_telefonico">Número telefónico:</label>
    <input type="text" name="numero_telefonico" required placeholder="Ej: 12345678">

    <label for="correo_electronico">Correo electrónico:</label>
    <input type="email" name="correo_electronico" required placeholder="ejemplo@correo.com">

    <button type="submit"><i class="fas fa-save"></i> Guardar Contacto</button>

</form>

<hr>

<script>
// Validación del formulario
document.querySelector('form').addEventListener('submit', function(e) {
    var tipo = "<?= $tipo_contacto ?>";
    var isValid = true;
    
    if (tipo === "proveedor") {
        var proveedor = document.getElementById('id_proveedor').value;
        if (!proveedor) {
            alert('Por favor seleccione un proveedor');
            isValid = false;
        }
    } else if (tipo === "trabajador") {
        var trabajador = document.getElementById('id_trabajador').value;
        if (!trabajador) {
            alert('Por favor seleccione un trabajador');
            isValid = false;
        }
    }
    
    // Validar que el número telefónico solo contenga números
    var telefono = document.querySelector('input[name="numero_telefonico"]').value;
    if (!/^\d+$/.test(telefono)) {
        alert('El número telefónico solo debe contener números');
        isValid = false;
    }
    
    // Validar código de área (solo números)
    var codigo = document.querySelector('input[name="codigo_area"]').value;
    if (!/^\d+$/.test(codigo)) {
        alert('El código de área solo debe contener números');
        isValid = false;
    }
    
    if (!isValid) {
        e.preventDefault();
    }
});
</script>

</body>
</html>
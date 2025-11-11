<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');
require_once(ROOT_PATH . '/controlador/controladores_adm/trabajadores/TrabajadorControlador.php');

// Crear controlador
$controlador = new TrabajadorControlador($conn);

$id = $_GET['id'] ?? null;
$trabajador = $id ? $controlador->ver($id) : null;

// Cargar tipos y niveles
$result_tipos = $conn->query("SELECT id_tipo_trabajador, tipo_trabajador FROM tipo_trabajador");
$tipos = $result_tipos->fetch_all(MYSQLI_ASSOC);

$result_niveles = $conn->query("SELECT id_nivel_profesionalismo, nivel_profesionalismo FROM nivel_profesionalismo");
$niveles = $result_niveles->fetch_all(MYSQLI_ASSOC);

// Guardar datos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datos = [
        'nombre_trabajador' => $_POST['nombre_trabajador'],
        'apellido_trabajador' => $_POST['apellido_trabajador'],
        'dni' => $_POST['dni'],
        'id_tipo_trabajador' => $_POST['id_tipo_trabajador'],
        'id_nivel_profesionalismo' => $_POST['id_nivel_profesionalismo'],
        'activo' => isset($_POST['activo']) ? 1 : 0
    ];
    $controlador->guardar($datos, $id);
    header("Location:" . BASE_URL . "/vista/vista_adm/trabajadores/trabajadores_lista.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $id ? 'Editar' : 'Agregar' ?> Trabajador - RoseSpa</title>
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

    /* Sidebar */
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
    }
    .sidebar h2{
      color: var(--primary);
      margin-bottom: 30px;
      text-align: center;
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

    /* Toggle */
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
      transition:.3s;
      box-shadow: var(--shadow);
    }
    .toggle-btn:hover{ background: rgba(224, 85, 133, 0.8); }

    /* Content */
    .content{
      margin-left: 240px;
      flex:1;
      padding:30px;
      transition: margin-left .3s ease;
      width: 100%;
    }
    .content.expanded{ margin-left: 0; }
    h1{
      font-size:2rem;
      margin-bottom:25px;
      color: var(--primary);
      text-shadow: 2px 2px 6px rgba(0,0,0,0.6);
      text-align: center;
    }

    /* Form Card */
    .form-card{
      background: rgba(46,46,68,0.9);
      padding: 30px;
      border-radius: 12px;
      box-shadow: var(--shadow);
      max-width: 600px;
      margin: 0 auto;
    }
    .form-group{
      margin-bottom: 20px;
    }
    .form-group label{
      display: block;
      margin-bottom: 8px;
      font-weight: 600;
      color: var(--primary);
      font-size: 0.95rem;
    }
    .form-group input[type="text"],
    .form-group input[type="number"],
    .form-group select{
      width: 100%;
      padding: 12px 16px;
      border: 1px solid #555;
      border-radius: 8px;
      background: rgba(255,255,255,0.1);
      color: var(--text);
      font-size: 1rem;
      transition: all 0.3s;
    }
    .form-group input:focus,
    .form-group select:focus{
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(255,107,157,0.2);
    }

    /* Checkbox */
    .checkbox-group{
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 25px;
    }
    .checkbox-group input[type="checkbox"]{
      width: 20px;
      height: 20px;
      accent-color: var(--primary);
    }
    .checkbox-group label{
      margin: 0;
      font-weight: 600;
      color: var(--text);
    }

    /* Botones */
    .btn-group{
      display: flex;
      gap: 15px;
      justify-content: center;
      margin-top: 20px;
    }
    .btn{
      padding: 12px 24px;
      border-radius: 8px;
      font-size: 1rem;
      font-weight: 600;
      text-decoration: none;
      transition: all 0.3s;
      box-shadow: var(--shadow);
      min-width: 140px;
      text-align: center;
    }
    .btn-primary{
      background: var(--primary);
      color: #fff;
    }
    .btn-primary:hover{
      background: var(--primary-dark);
      transform: translateY(-2px);
    }
    .btn-secondary{
      background: #555;
      color: #fff;
    }
    .btn-secondary:hover{
      background: #666;
      transform: translateY(-2px);
    }
  </style>
</head>
<body>

  <!-- Toggle -->
  <button class="toggle-btn" onclick="toggleSidebar()">
    <i class="fas fa-bars"></i>
  </button>

  <!-- Sidebar -->
  <div class="sidebar" id="sidebar">
    <h2 class="titulo_menu">RoseSpa</h2>
    <a href="<?= BASE_URL ?>/vista/vista_adm/servicios_combos/vista_inicio_adm.php"><i class="fas fa-spa"></i> Servicios y Combos</a>
    <a href="<?= BASE_URL ?>/vista/vista_adm/inventario/vista_inventario.php"><i class="fas fa-boxes"></i> Productos</a>
    <a href="#"><i class="fas fa-cash-register"></i> Ventas y Compras</a>
    <a href="<?= BASE_URL ?>/vista/vista_adm/lugares/lugares.php"><i class="fas fa-map-marker-alt"></i> Lugares</a>
    <a href="<?= BASE_URL ?>/vista/vista_adm/proveedores/vista_proveedores.php"><i class="fas fa-truck"></i> Proveedores</a>
    <a href="<?= BASE_URL ?>/vista/vista_adm/trabajadores/trabajadores_lista.php"><i class="fas fa-user-tie"></i> Trabajadores</a>
    <a href="<?= BASE_URL ?>/vista/vista_adm/clientes/clientes_lista.php"><i class="fas fa-users"></i> Clientes</a>
    <a href="<?= BASE_URL ?>/vista/vista_adm/vista_logouts/vista_logouts_adm.php"><i class="fas fa-history"></i> Logeos y Movimientos</a>
    <a href="<?= BASE_URL ?>/vista/vista_adm/citas/citas.php"><i class="fas fa-calendar-check"></i> Citas</a>
    <a href="<?= BASE_URL ?>/controlador/controladores_adm/controlador_logout/controlador_logout.php?logout=trabajador_form"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a>
  </div>

  <!-- Content -->
  <div class="content" id="content">
    <h1><?= $id ? 'Editar Trabajador' : 'Agregar Nuevo Trabajador' ?></h1>

    <div class="form-card">
      <form method="POST">
        <div class="form-group">
          <label>Nombre</label>
          <input type="text" name="nombre_trabajador" value="<?= htmlspecialchars($trabajador['nombre_trabajador'] ?? '') ?>" required placeholder="Ej: Ana">
        </div>

        <div class="form-group">
          <label>Apellido</label>
          <input type="text" name="apellido_trabajador" value="<?= htmlspecialchars($trabajador['apellido_trabajador'] ?? '') ?>" required placeholder="Ej: Gómez">
        </div>

        <div class="form-group">
          <label>DNI</label>
          <input type="number" name="dni" value="<?= htmlspecialchars($trabajador['dni'] ?? '') ?>" required placeholder="Ej: 30123456" min="1000000">
        </div>

        <div class="form-group">
          <label>Tipo de Trabajador</label>
          <select name="id_tipo_trabajador" required>
            <option value="">Seleccione un tipo</option>
            <?php foreach($tipos as $tipo): ?>
              <option value="<?= $tipo['id_tipo_trabajador'] ?>" 
                <?= ($trabajador['id_tipo_trabajador'] ?? '') == $tipo['id_tipo_trabajador'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($tipo['tipo_trabajador']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-group">
          <label>Nivel Profesional</label>
          <select name="id_nivel_profesionalismo" required>
            <option value="">Seleccione un nivel</option>
            <?php foreach($niveles as $nivel): ?>
              <option value="<?= $nivel['id_nivel_profesionalismo'] ?>" 
                <?= ($trabajador['id_nivel_profesionalismo'] ?? '') == $nivel['id_nivel_profesionalismo'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($nivel['nivel_profesionalismo']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="checkbox-group">
          <input type="checkbox" name="activo" id="activo" <?= (!isset($trabajador['activo']) || $trabajador['activo']) ? 'checked' : '' ?>>
          <label for="activo">Trabajador activo</label>
        </div>

        <div class="btn-group">
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> <?= $id ? 'Actualizar' : 'Guardar' ?>
          </button>
          <a href="<?= BASE_URL ?>/vista/vista_adm/trabajadores/trabajadores_lista.php" class:class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
          </a>
        </div>
      </form>
    </div>
  </div>

  <!-- JS -->
  <script src="<?= BASE_URL ?>/modelo/modelo_adm/servicios_combos/menu_desplegable.js"></script>
</body>
</html>
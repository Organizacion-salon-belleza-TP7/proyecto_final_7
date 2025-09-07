<?php
require_once(__DIR__ . '/../../variable_global.php');
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Rose Spa | Crear Usuario</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
  <style>
    /* Reutilizamos las variables y estilos principales del login */
    :root {
      --primary: #ff6b9d;
      --primary-light: #ff8bb3;
      --secondary: #c44569;
      --accent: #f8b500;
      --dark: #2d1b36;
      --light: #fef7f7;
      --white: #ffffff;
      --gradient-rose: linear-gradient(135deg, #ff6b9d 0%, #c44569 100%);
      --shadow-light: 0 8px 32px rgba(0, 0, 0, 0.1);
      --shadow-medium: 0 16px 64px rgba(0, 0, 0, 0.15);
      --shadow-heavy: 0 24px 96px rgba(0, 0, 0, 0.2);
    }
    * { margin:0; padding:0; box-sizing:border-box; }
    body {
      font-family: 'Inter', sans-serif;
      background: var(--light);
      color: var(--dark);
      min-height:100vh;
      display:flex;
      flex-direction:column;
    }
    header {
      background: rgba(255,255,255,0.95);
      backdrop-filter: blur(20px);
      box-shadow: var(--shadow-light);
      position:fixed;
      top:0; width:100%;
      z-index:1000;
    }
    .header-container {
      display:flex; justify-content:space-between; align-items:center;
      padding:20px 0;
      width:min(90%,1400px); margin:auto;
    }
    .logo {
      font-family:'Playfair Display', serif;
      font-size:2rem; font-weight:700;
      color:var(--primary);
      text-decoration:none; position:relative;
    }
    .logo span { color:var(--secondary); }

    main {
      flex:1; display:flex; justify-content:center; align-items:center;
      padding:120px 20px 60px;
      position:relative; overflow:hidden;
    }
    main::before {
      content:''; position:absolute; inset:0;
      background:linear-gradient(45deg, rgba(102,126,234,0.6), rgba(118,75,162,0.6), rgba(240,147,251,0.6), rgba(245,87,108,0.6));
      background-size:400% 400%;
      animation:gradientShift 15s ease infinite;
      z-index:-2;
    }
    @keyframes gradientShift {
      0% {background-position:0% 50%;}
      50% {background-position:100% 50%;}
      100% {background-position:0% 50%;}
    }
    .form-container {
      background:rgba(255,255,255,0.95);
      backdrop-filter:blur(20px);
      border-radius:25px;
      box-shadow:var(--shadow-heavy);
      max-width:600px; width:100%;
      padding:40px;
      animation:fadeInUp 0.8s ease-out;
    }
    .form-header {
      text-align:center; margin-bottom:30px;
    }
    .form-header h1 {
      font-family:'Playfair Display', serif;
      font-size:2.2rem; font-weight:700;
      color:var(--primary);
    }
    .form-header p {
      font-size:1rem; opacity:0.8;
    }
    .form-group {
      margin-bottom:20px;
    }
    .form-group label {
      display:block; margin-bottom:8px;
      font-weight:500; color:var(--dark);
    }
    .form-group input {
      width:100%; padding:14px 16px;
      border:1px solid rgba(255,107,157,0.3);
      border-radius:12px;
      font-size:1rem;
      transition:all .3s ease;
      background:rgba(255,255,255,0.8);
    }
    .form-group input:focus {
      border-color:var(--primary);
      box-shadow:0 0 0 3px rgba(255,107,157,0.2);
      outline:none; background:var(--white);
    }
    .btn-submit {
      width:100%; background:var(--gradient-rose);
      color:white; border:none;
      padding:16px; border-radius:12px;
      font-size:1.1rem; font-weight:600;
      cursor:pointer; transition:all .3s ease;
      box-shadow:var(--shadow-light);
      margin-top:15px;
    }
    .btn-submit:hover {
      transform:translateY(-3px);
      box-shadow:var(--shadow-medium);
    }
    footer {
      background:var(--dark);
      color:white;
      text-align:center;
      padding:25px 0;
      position:relative;
    }
    @keyframes fadeInUp {
      from {opacity:0; transform:translateY(30px);}
      to {opacity:1; transform:translateY(0);}
    }
    .boton {
        display: inline-block;
        padding: 8px 16px;
        background-color: #007bff;
        color: white;
        text-decoration: none;
        border-radius: 5px;
    }
    .boton:hover {
        background-color: #0056b3;
    }

  </style>
</head>
<body>
  <!-- Header -->
  <header>
    <div class="header-container">
      <a href="<?= BASE_URL ?>/pagina_web_landing-(prototipo-2)/prueba_landing.php" class="logo">Rose<span>Spa</span></a>
    </div>
  </header>

  <!-- Main -->
  <main>
    <div class="form-container">
      <div class="form-header">
        <h1>Crear Usuario</h1>
        <p>Completa tus datos para registrarte en Rose Spa</p>
      </div>
      <form action="<?= BASE_URL ?>/controlador/controladores_login/controlador_crear_usuario.php" method="post">
        
        <div class="form-group">
          <label for="nombre">Nombre de Usuario</label>
          <input type="text" id="nombre" name="nombre" required>
        </div>
        
        <div class="form-group">
          <label for="contrasena">Contraseña</label>
          <input type="password" id="contrasena" name="contrasena" required>
        </div>
        
        <h2 style="margin:25px 0 15px; font-size:1.3rem; color:var(--secondary);">Datos Personales</h2>
        
        <div class="form-group">
          <label for="nombre_cliente">Nombre</label>
          <input type="text" id="nombre_cliente" name="nombre_cliente" required>
        </div>
        
        <div class="form-group">
          <label for="apellido_cliente">Apellido</label>
          <input type="text" id="apellido_cliente" name="apellido_cliente" required>
        </div>
        
        <div class="form-group">
          <label for="alergias_cliente">Alergias</label>
          <input type="text" id="alergias_cliente" name="alergias_cliente" placeholder="Rellene sus alergias si corresponde">
        </div>
        
        <div class="form-group">
          <label for="fecha_nacimiento_cliente">Fecha de nacimiento</label>
          <input type="date" id="fecha_nacimiento_cliente" name="fecha_nacimiento_cliente">
        </div>
        
        <div class="form-group">
          <label for="dni_cliente">DNI</label>
          <input type="number" id="dni_cliente" name="dni_cliente">
        </div>
        
        <button type="submit" name="crear_usuario" class="btn-submit">Registrarse</button>

        <br>
        <br>

        <div class="form-group">
            <a href="<?= BASE_URL?>/vista/vista_login/vista_login.php" class="boton">Volver</a>
        </div>


      </form>
    </div>
  </main>

  <!-- Footer -->
  <footer>
    <p>&copy; 2024 Rose Spa. Todos los derechos reservados.</p>
  </footer>
</body>
</html>

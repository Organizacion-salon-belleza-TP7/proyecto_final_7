<?php
require_once(__DIR__ . '/../../variable_global.php');

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rose Spa | Iniciar Sesión</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Variables de colores (consistentes con la landing) */
        :root {
            --primary: #f8c6d0;
            --secondary: #d9a6b3;
            --accent: #a86a7e;
            --dark: #3c2f3d;
            --light: #fff8f8;
        }
        
        /* Reset y estilos generales */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            color: var(--dark);
            background-color: var(--light);
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }
        
        /* Header (mismo estilo que la landing) */
        header {
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            top: 0;
            z-index: 1000;
        }
        
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 0;
        }
        
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: var(--accent);
            text-decoration: none;
        }
        
        .logo span {
            color: var(--primary);
        }
        
        /* Contenedor principal del login */
        .login-main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px 0;
            background: linear-gradient(rgba(255, 255, 255, 0), rgba(255, 255, 255, 0)), 
            url('https://images.unsplash.com/photo-1560066984-138dadb4c035?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTF8fHNhbG9uJTIwZGUlMjBiellaXphfGVufDB8fDB8fHww&auto=format&fit=crop&w=1200&q=80') no-repeat center center/cover;
        }
        
        .login-container {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 450px;
            overflow: hidden;
        }
        
        .login-header {
            background: linear-gradient(to right, var(--primary), var(--accent));
            padding: 30px;
            text-align: center;
            color: white;
        }
        
        .login-header h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .login-header p {
            opacity: 0.9;
        }
        
        .login-form {
            padding: 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark);
        }
        
        .input-with-icon {
            position: relative;
        }
        
        .input-with-icon i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--accent);
        }
        
        .input-with-icon input {
            width: 100%;
            padding: 12px 15px 12px 45px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        
        .input-with-icon input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 2px rgba(168, 106, 126, 0.2);
            outline: none;
        }
        
        .btn-login {
            display: block;
            width: 100%;
            background: linear-gradient(to right, var(--primary), var(--accent));
            color: white;
            border: none;
            padding: 14px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(168, 106, 126, 0.3);
        }
        
        .login-links {
            text-align: center;
            margin-top: 20px;
        }
        
        .login-links a {
            color: var(--accent);
            text-decoration: none;
            transition: color 0.3s;
            display: inline-block;
            margin: 5px 0;
        }
        
        .login-links a:hover {
            color: var(--dark);
            text-decoration: underline;
        }
        
        .separator {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 20px 0;
            color: #888;
        }
        
        .separator::before,
        .separator::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #ddd;
        }
        
        .separator span {
            padding: 0 10px;
        }
        
        /* Footer (mismo estilo que la landing) */
        footer {
            background-color: var(--dark);
            color: white;
            padding: 20px 0;
            text-align: center;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .login-main {
                padding: 40px 0;
            }
            
            .login-container {
                max-width: 90%;
            }
            
            .login-header {
                padding: 20px;
            }
            
            .login-form {
                padding: 20px;
            }
        }
        
        @media (max-width: 480px) {
            .login-header h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <!-- Header (igual al de la landing) -->
    <header>
        <div class="container header-container">
            <a href="<?= BASE_URL ?>" class="logo">Rose<span>Spa</span></a>
        </div>
    </header>

    <!-- Contenido principal -->
    <main class="login-main">
        <div class="login-container">
            <div class="login-header">
                <h1>Iniciar Sesión</h1>
                <p>Accede a tu cuenta de Rose Spa</p>
            </div>
            
            <div class="login-form">
                <form action="<?= BASE_URL ?>/controlador/controladores_login/controlador_login.php" method="post">
                    <div class="form-group">
                        <label for="name_user">Nombre de Usuario</label>
                        <div class="input-with-icon">
                            <i class="fas fa-user"></i>
                            <input type="text" id="name_user" name="name_user" required placeholder="Ingresa tu nombre de usuario">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <div class="input-with-icon">
                            <i class="fas fa-lock"></i>
                            <input type="password" id="password" name="password" required placeholder="Ingresa tu contraseña">
                        </div>
                    </div>
                    
                    <button type="submit" name="send_form" class="btn-login">Iniciar Sesión</button>
                </form>
                
                <div class="separator"><span>o</span></div>
                
                <div class="login-links">
                    <a href="<?= BASE_URL?>/vista/vista_login/vista_crear_usuario.php">¿No tienes cuenta? Regístrate</a><br>
                    <a href="<?= BASE_URL ?>/pagina_web_landing-(prototipo-2)/prueba_landing.php">Volver al inicio</a>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer (igual al de la landing) -->
    <footer>
        <div class="container">
            <p>&copy; 2023 Rose Spa. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>
</html>
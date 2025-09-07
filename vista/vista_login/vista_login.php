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
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        /* Variables de colores (consistentes con la landing) */
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
        
        /* Reset y estilos generales */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            color: var(--dark);
            background: var(--light);
            line-height: 1.7;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .container {
            width: min(90%, 1400px);
            margin: 0 auto;
            padding: 0 20px;
        }
        
        /* Header (mismo estilo que la landing) */
        header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            box-shadow: var(--shadow-light);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            transition: all 0.3s ease;
        }
        
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 0;
        }
        
        .logo {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary);
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
        }

        .logo::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 3px;
            background: var(--gradient-rose);
            transition: width 0.3s ease;
        }

        .logo:hover::after {
            width: 100%;
        }
        
        .logo span {
            color: var(--secondary);
        }
        
        /* Contenedor principal del login */
        .login-main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 120px 0 60px;
            position: relative;
            overflow: hidden;
        }

        .login-main::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(102, 126, 234, 0.6), rgba(118, 75, 162, 0.6), rgba(240, 147, 251, 0.6), rgba(245, 87, 108, 0.6));
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
            z-index: -2;
        }

        .login-main::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('https://images.unsplash.com/photo-1560066984-138dadb4c035?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTF8fHNhbG9uJTIwZGUlMjBiellaXphfGVufDB8fDB8fHww&auto=format&fit=crop&w=1200&q=80') center/cover;
            opacity: 0.2;
            z-index: -1;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        .login-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 25px;
            box-shadow: var(--shadow-heavy);
            width: 100%;
            max-width: 450px;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.2);
            transform: translateY(0);
            transition: transform 0.4s ease, box-shadow 0.4s ease;
        }

        .login-container:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-heavy);
        }
        
        .login-header {
            background: var(--gradient-rose);
            padding: 40px 30px;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .login-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(to right, transparent, rgba(255, 255, 255, 0.2), transparent);
            transform: rotate(45deg);
            animation: shimmer 3s infinite;
        }

        @keyframes shimmer {
            0% { left: -100%; }
            100% { left: 100%; }
        }
        
        .login-header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
            position: relative;
            z-index: 2;
        }
        
        .login-header p {
            opacity: 0.9;
            font-size: 1.1rem;
            position: relative;
            z-index: 2;
        }
        
        .login-form {
            padding: 40px;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 10px;
            font-weight: 500;
            color: var(--dark);
            font-size: 1.1rem;
        }
        
        .input-with-icon {
            position: relative;
        }
        
        .input-with-icon i {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary);
            font-size: 1.2rem;
            z-index: 2;
        }
        
        .input-with-icon input {
            width: 100%;
            padding: 16px 20px 16px 55px;
            border: 1px solid rgba(255, 107, 157, 0.3);
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.8);
            position: relative;
            z-index: 1;
        }
        
        .input-with-icon input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(255, 107, 157, 0.2);
            outline: none;
            background: var(--white);
        }

        .input-with-icon input::placeholder {
            color: rgba(45, 27, 54, 0.5);
        }
        
        .btn-login {
            display: block;
            width: 100%;
            background: var(--gradient-rose);
            color: white;
            border: none;
            padding: 18px;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-light);
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn-login:hover::before {
            left: 100%;
        }
        
        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-medium);
        }
        
        .login-links {
            text-align: center;
            margin-top: 25px;
        }
        
        .login-links a {
            color: var(--primary);
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
            margin: 8px 0;
            font-weight: 500;
            position: relative;
        }

        .login-links a::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--gradient-rose);
            transition: width 0.3s ease;
        }
        
        .login-links a:hover {
            color: var(--secondary);
        }

        .login-links a:hover::after {
            width: 100%;
        }
        
        .separator {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 30px 0;
            color: var(--dark);
            opacity: 0.7;
        }
        
        .separator::before,
        .separator::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid rgba(255, 107, 157, 0.3);
        }
        
        .separator span {
            padding: 0 15px;
            font-size: 0.9rem;
        }
        
        /* Footer (mismo estilo que la landing) */
        footer {
            background: var(--dark);
            color: white;
            padding: 30px 0;
            text-align: center;
            position: relative;
        }

        footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ff6b9d' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='1'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        
        .copyright {
            position: relative;
            z-index: 2;
        }
        
        /* Efectos de animación al cargar */
        .login-container {
            animation: fadeInUp 0.8s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .login-main {
                padding: 100px 0 40px;
            }
            
            .login-container {
                max-width: 90%;
            }
            
            .login-header {
                padding: 30px 20px;
            }
            
            .login-header h1 {
                font-size: 2rem;
            }
            
            .login-form {
                padding: 30px;
            }
        }
        
        @media (max-width: 480px) {
            .login-header h1 {
                font-size: 1.8rem;
            }
            
            .login-form {
                padding: 25px 20px;
            }
            
            .input-with-icon input {
                padding: 14px 15px 14px 45px;
            }
        }

        /* Mensajes de error/éxito (para futuras implementaciones) */
        .alert {
            padding: 12px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 500;
            display: none;
        }
        
        .alert-error {
            background: rgba(244, 67, 54, 0.1);
            border: 1px solid rgba(244, 67, 54, 0.2);
            color: #f44336;
        }
        
        .alert-success {
            background: rgba(76, 175, 80, 0.1);
            border: 1px solid rgba(76, 175, 80, 0.2);
            color: #4CAF50;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container header-container">
            <a href="<?= BASE_URL ?>/pagina_web_landing-(prototipo-2)/prueba_landing.php" class="logo">Rose<span>Spa</span></a>
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
                <!-- Mensajes de alerta (ocultos por defecto) -->
                <div class="alert alert-error" id="error-message">
                    <i class="fas fa-exclamation-circle"></i> Credenciales incorrectas
                </div>
                
                <div class="alert alert-success" id="success-message">
                    <i class="fas fa-check-circle"></i> ¡Inicio de sesión exitoso!
                </div>
                
                <form action="<?= BASE_URL ?>/controlador/controladores_login/controlador_login.php" method="post" id="login-form">
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

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="copyright">
                <p>&copy; 2024 Rose Spa. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <script>
        // Efecto de partículas en el fondo (similar a la landing)
        function createParticles() {
            const loginMain = document.querySelector('.login-main');
            const particleCount = 20;

            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.style.cssText = `
                    position: absolute;
                    width: 4px;
                    height: 4px;
                    background: rgba(255, 255, 255, 0.8);
                    border-radius: 50%;
                    animation: particleFloat ${Math.random() * 10 + 15}s infinite linear;
                    z-index: -1;
                `;
                particle.style.left = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 5 + 's';
                loginMain.appendChild(particle);
            }
        }

        // Validación básica del formulario
        document.getElementById('login-form').addEventListener('submit', function(e) {
            const username = document.getElementById('name_user').value;
            const password = document.getElementById('password').value;
            
            if (!username || !password) {
                e.preventDefault();
                const errorMsg = document.getElementById('error-message');
                errorMsg.textContent = 'Por favor, completa todos los campos';
                errorMsg.style.display = 'block';
                
                // Ocultar mensaje después de 5 segundos
                setTimeout(() => {
                    errorMsg.style.display = 'none';
                }, 5000);
            }
        });

        // Efecto de enfoque en los inputs
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });

        // Inicializar partículas al cargar la página
        window.addEventListener('load', function() {
            createParticles();
        });
    </script>
</body>
</html>
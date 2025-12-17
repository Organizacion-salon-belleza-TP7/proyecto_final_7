<?php
require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');
require_once(ROOT_PATH . '/controlador/controlador_trabajadores/controlador_inicio/TrabajadorController.php');

$controller = new TrabajadorController($conn);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pantalla Trabajador</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #fce4ec, #f8bbd0);
            margin: 0;
            padding: 0;
            color: #4a148c;
        }
        .container {
            width: 80%;
            max-width: 900px;
            margin: 50px auto;
            background: #fff;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        }
        h1 {
            text-align: center;
            color: #ad1457;
        }
        nav {
            text-align: center;
            margin-bottom: 30px;
        }
        nav a {
            text-decoration: none;
            color: #d81b60;
            font-weight: bold;
            margin: 0 10px;
            transition: 0.3s;
        }
        nav a:hover {
            color: #880e4f;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Pantalla del Trabajador</h1>
        
        <nav>
            <a href="<?= BASE_URL ?>/vista/vista_trabajadores/vista_inicio/listaEspera.php">Lista de Espera</a> |
            <a href="<?= BASE_URL ?>/vista/vista_trabajadores/vista_inicio/cerrarSesion.php">Cerrar Sesión</a>
        </nav>

        <p style="text-align:center;font-size:18px;">
            Bienvenido al panel del trabajador. Selecciona una opción del menú.
        </p>
    </div>
</body>
</html>

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../variable_global.php');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cerrar Sesión</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #fce4ec, #f8bbd0);
            margin: 0;
            padding: 0;
            color: #4a148c;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: #fff;
            padding: 40px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        }
        a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: #d81b60;
            color: white;
            text-decoration: none;
            border-radius: 10px;
            transition: 0.3s;
        }
        a:hover {
            background: #880e4f;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Has cerrado sesión correctamente</h1>
        <a href="<?= BASE_URL ?>/vista/vista_login/vista_login.php">Ir al login</a>
    </div>
</body>
</html>

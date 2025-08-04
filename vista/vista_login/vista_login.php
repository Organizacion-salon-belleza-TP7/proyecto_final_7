<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    require_once(__DIR__ . '/../../variable_global.php');
    ?>
<div>
    <h1>Inicie Sesion</h1>
        <form action="<?= BASE_URL ?>/controlador/controladores_login/controlador_login.php" method = "post">
            <div>
                <label for="name">Nombre Usuarios</label>
                <input type="text" name = "name_user" required>
            </div>
            <div>
                <label for="password">Contraseña</label>
                <input type="text" name = "password">
            </div>
            <div>
                <input type="submit" name = "send_form">
            </div>
            <div>
                <a href="crear_cuenta">¿No tienes cuenta? Registrate</a>
            </div>
        </form>
    </div>
    
</body>
</html>
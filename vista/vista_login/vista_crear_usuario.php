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

    <h1>Crear usuario</h1>

    <form action="<?= BASE_URL ?>/controlador/controladores_login/controlador_crear_usuario.php" method="post">
        <table border="1">
        <tr>
            <td>Nombre</td>
            <td><input type="text" name="nombre"></td>
        </tr>
        <tr>
            <td>Contraseña</td>
            <td><input type="password" name="contrasena"></td>
        </tr>

        </table>

        <br>
        
        <h2>Datos de usted</h2>
        
        <table border="1">
            <tr>
                <td>Nombre</td>
                <td><input type="text" name="nombre_cliente"></td>
            </tr>
            <tr>
                <td>Apellido</td>
                <td><input type="text" name="apellido_cliente"></td>
            </tr>
            <tr>
                <td>Alergias</td>
                <td><input type="text" name="alergias_cliente" placeholder="Rellene sus alergias para tenerlo en cuenta"></td>
            </tr>
            <tr>
                <td>Fecha de nacimiento</td>
                <td><input type="date" name="fecha_nacimiento_cliente"></td>
            </tr>
            <tr>
                <td>Dni</td>
                <td><input type="number" name="dni_cliente"></td>
            </tr>

            <tr>
                <td><input type="submit" name="crear_usuario"></td>
            </tr>

        </table>
    </form>
    
</body>
</html>
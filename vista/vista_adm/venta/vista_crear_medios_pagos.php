<?php
require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');
require_once(ROOT_PATH . '/modelo/modelo_adm/modelo_venta/modelo_venta.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Agregar Medios De Pago</h1>

    <form action="<?= BASE_URL ?>/controlador/controladores_adm/controlador_venta/controlador_venta.php" method="post">
        <table border="1">
            <input type="hidden" name="enviar" value="agregar_medios_pagos">
            <tr>
                <td>Nombre Metodo Pago</td>
                <td><input type="text" name="nombre_metodo_pago"></td>
            </tr>
            <tr>
                <td>Importe</td>
                <td>
                    <select name="importe">
                        <option value="1">Incremento</option>
                        <option value="0">Decremento</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Cantidad a importar</td>
                <td><input type="number" name="cantidad_importe"></td>
            </tr>
            <tr>
                <td>Activo</td>
                <td>
                    <select name="activo">
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><input type="submit"></td>
            </tr>

        </table>
    </form>
    
</body>
</html>
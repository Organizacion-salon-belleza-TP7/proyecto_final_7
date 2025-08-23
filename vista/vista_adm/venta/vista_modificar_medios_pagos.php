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
    <h1>Modifique los datos del medio de pago</h1>

    <table border="1">
        <form action="<?= BASE_URL ?>/controlador/controladores_adm/controlador_venta/controlador_venta.php" method="post">
            <?php
            if($funcion_formulario_modificar && $funcion_formulario_modificar->num_rows > 0){
                $traer_medios_pagos = $funcion_formulario_modificar->fetch_assoc();
                ?>
                <input type="hidden" name="vista_modificar_medios_pagos" value="vista_modificar">
                <input type="hidden" name="id_metodo_pago" value="<?= $traer_medios_pagos['id_metodo_pago'];  ?>">
                <tr>
                    <td>Nombre Metodo Pago</td>
                    <td><input type="text" name="nombre_metodo_pago" value="<?= $traer_medios_pagos['metodo_pago']; ?>"></td>
                </tr>
                <tr>
                    <td>Importe</td>
                    <td>
                        <?php
                        if($traer_medios_pagos['incremento'] == null){
                            ?>
                            <select name="importe">
                                <option value="0">Decremento</option>
                                <option value="1">Incremento</option>
                            </select>
                            </tr>

                            <tr>
                                <td>Cantidad Importe</td>
                                <td><input type="number" name="cantidad_importe" value="<?= $traer_medios_pagos['decremento']; ?>"></td>
                            </tr>
                            <?php

                        }elseif($traer_medios_pagos['decremento'] == null){
                            ?>
                            <select name="importe">
                                <option value="1">Incremento</option>
                                <option value="0">Decremento</option>
                            </select>

                            </tr>

                            <tr>
                                <td>Cantidad Importe</td>
                                <td><input type="number" name="cantidad_importe" value="<?= $traer_medios_pagos['incremento']; ?>"></td>
                            </tr>

                            <?php

                        }else{
                            echo "hubo un fallo trayendo los importes";
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td><input type="submit"></td>
                </tr>

                <?php


            }else{
                echo "hubo un fallo trayendo los datos del medio de pago";
            }
            ?>

        </form>
    </table>

    
</body>
</html>
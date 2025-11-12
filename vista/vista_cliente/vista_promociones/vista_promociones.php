<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');
require_once(ROOT_PATH . '/modelo/modelo_cliente/promociones/modelo_promociones.php');

$clase_promociones = new promociones_cli($conn);

$funcion_traer_promos = $clase_promociones->traer_promociones();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Promociones</h1>

    <?php
    if($funcion_traer_promos && $funcion_traer_promos->num_rows > 0){
        echo "<table>
            <thead>
                <tr>
                    <th>ID</th><th>Nombre</th><th>Usuario</th><th>Fecha Cita</th>
                    <th>Estado</th><th>Lugar</th><th>Acción</th>
                </tr>
            </thead><tbody>";

    } 
    ?>
    
</body>
</html>
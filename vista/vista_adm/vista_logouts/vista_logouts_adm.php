<?php
require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');
require_once(ROOT_PATH . '/modelo/modelo_adm/modelo_logout/modelo_logout.php');
$logouts_modelo = new logout($conn);
$traer_logueos = $logouts_modelo->mostrar_logueos();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div>
        <a href="">Servicios Y Combos</a>
        <a href="">Productos</a>
        <a href="">Ventas Y Compras</a>
        <a href="">Proveedores</a>
        <a href="<?= BASE_URL ?>/vista/vista_adm/vista_logots/vista_logouts_adm.php">Logeos y Movimientos</a>
        <a href="<?= BASE_URL ?>/controlador/controladores_adm/controlador_logout/controlador_logout.php?logout=vista_inicio_adm" class="logout">Cerrar sesión</a>
    </div>


    <?php
    if($traer_logueos && $traer_logueos->num_rows> 0){
        echo "<table border = '1'>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Fecha Logueo</th>
                    <th>Fecha Logout</th>
                </tr>
            </thead>";

        while($bucle_logouts = $traer_logueos->fetch_assoc()){
            echo "<tr>
                    <td>{$bucle_logouts['id_historial_logueos']}</td>
                    <td>{$bucle_logouts['nombre_usuario']}</td>
                    <td>{$bucle_logouts['fecha_logueo']}</td>
                    ";
                    if($bucle_logouts['fecha_logout'] === null){
                        echo "<td>Sesion activa</td>";
                    }else{
                        echo "<td>{$bucle_logouts['fecha_logout']}</td>";
                    }
                    

        }
        

    }

    ?>


    
</body>
</html>
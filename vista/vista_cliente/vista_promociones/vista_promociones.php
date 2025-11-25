    <?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once(__DIR__ . '/../../../variable_global.php');
    require_once(ROOT_PATH . '/modelo/BD.php');
    require_once(ROOT_PATH . '/modelo/modelo_cliente/promociones/modelo_promociones.php');

    $clase_promociones = new promociones_cliente($conn);

    $funcion_traer_promos = $clase_promociones->traer_servicios_combos_promocionados();

    session_start();
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
            echo "<table border = '1'>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Días de promoción</th>
                        <th>Descuento</th>
                        <th>Puntos Acumulables</th>
                        <th>Agregar al carrito</th>
                    </tr>
                </thead>
                <tbody>";

            while($row = $funcion_traer_promos->fetch_assoc()){
                if (!empty($row['nombre_combo'])) {
                    $nombre = $row['nombre_combo'];
                    $tipo = "Combo";
                } elseif (!empty($row['nombre_servicio'])) {
                    $nombre = $row['nombre_servicio'];
                    $tipo = "Servicio";
                } else {
                    $nombre = "—";
                    $tipo = "Desconocido";
                }

                echo "<tr>
                    <td>{$nombre}</td>
                    <td>{$tipo}</td>
                    <td>{$row['dias_promocion']}</td>
                    <td>{$row['descuento']}%</td>
                    <td>{$row['puntos']}</td>
                    <td><a class='btn btn-delete' href='".BASE_URL."/controlador/controladores_cliente/controlador_promociones/controlador_promociones.php?id={$row['id_promocion']}&agregar_carrito=vista_promociones'>Agregar al carro</a></td>
                </tr>";

                
            }
            echo "</tbody></table>";
            echo "<a class='btn btn-delete' href='".BASE_URL."/controlador/controladores_cliente/controlador_promociones/controlador_promociones.php?ver_carrito=vista_promociones'>Carrito</a>";
        }else{
            echo "<p>No hay promociones registradas.</p>";
        }
        ?>
        
    </body>
    </html>
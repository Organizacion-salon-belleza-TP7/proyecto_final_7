<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle Servicio</title>
    <style>
        :root {
            --bg: #1e1e2f;
            --primary: #ff6b9d;
            --primary-dark: #e05585;
            --text: #f1f1f1;
            --card: rgba(46,46,68,0.95);
            --shadow: 0 4px 12px rgba(0,0,0,0.3);
        }
        * {margin:0;padding:0;box-sizing:border-box;}
        body {
            font-family: 'Segoe UI', sans-serif;
            background: url('../../../imagenes/lugares/istockphoto-1856117770-612x612.jpg') no-repeat center center fixed;
            background-size: cover;
            color: var(--text);
            min-height: 100vh;
        }
        body::before {
            content:"";
            position:fixed;
            top:0;left:0;right:0;bottom:0;
            background: rgba(0,0,0,0.6);
            z-index:-1;
        }
        .container {
            max-width: 1000px;
            margin: 40px auto;
            background: var(--card);
            padding: 30px;
            border-radius: 12px;
            box-shadow: var(--shadow);
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: var(--primary);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        thead {
            background: var(--primary);
            color: #fff;
        }
        th, td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #444;
        }
        tr:nth-child(even) {
            background: rgba(255,255,255,0.05);
        }
        tr:hover {
            background: rgba(255,255,255,0.1);
        }
        .add-btn {
            display: inline-block;
            text-decoration: none;
            background: var(--primary);
            color: #fff;
            padding: 12px 20px;
            border-radius: 8px;
            font-weight: bold;
            transition: 0.3s;
            box-shadow: var(--shadow);
        }
        .add-btn:hover {
            background: var(--primary-dark);
        }
        .no-data {
            text-align: center;
            color: #ccc;
            font-size: 1.1rem;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Detalle del Servicio</h1>
        <?php
        require_once(__DIR__ . '/../../../variable_global.php');
        require_once(ROOT_PATH . '/modelo/BD.php');
        require_once(ROOT_PATH . '/modelo/modelo_adm/servicios_combos/modelo_inicio_adm.php');
        $servicio_modelo = new servicios($conn);
        $id_servicio = $_GET['id'];
        $resultado_traer_servicios = $servicio_modelo->detalle_servicio($id_servicio);

        if($resultado_traer_servicios && $resultado_traer_servicios->num_rows > 0){
            echo "<table>
                <thead>
                    <tr>
                        <th>ID_detalle</th>
                        <th>Nombre del servicio</th>
                        <th>Nombre del producto</th>
                        <th>Cantidad a usar</th>
                        <th>Tipo de servicio</th>
                        <th>Intereses</th>
                    </tr>
                </thead><tbody>";

            while($row = $resultado_traer_servicios->fetch_assoc()){
                echo "<tr>
                        <td>{$row['id_products_usados']}</td>
                        <td>{$row['nombre']}</td>
                        <td>{$row['nombre_producto']}</td>
                        <td>{$row['cantidad_usada']}</td>
                        <td>{$row['tipo_servicio']}</td>
                        <td>{$row['intereses']}%</td>
                    </tr>";
            }
            echo "</tbody></table>";
            echo "<div style='text-align:center;'>
                    <a href='" . BASE_URL . "/vista/vista_adm/servicios_combos/vista_inicio_adm.php' class='add-btn'>Volver</a>
                  </div>";

        }else{
            echo '<script language="javascript">
            alert("No se han usado nada para el servicio");
            self.location = "' . BASE_URL . '/vista/vista_adm/servicios_combos/vista_inicio_adm.php";
            </script>';
        }
        ?>
    </div>
</body>
</html>

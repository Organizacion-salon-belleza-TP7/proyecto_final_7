<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');
require_once(ROOT_PATH . '/modelo/modelo_cliente/modelo_inicio/modelo_inicio.php');

session_start();

$id_usuario = $_SESSION['user'];

$modelo_inicio = new modelo_inicio($conn);

$funcion_traer_citas = $modelo_inicio->traer_citas_compradas($id_usuario);

?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Citas Compradas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Estilos basados en la plantilla administrativa, adaptados a tu vista */
        :root {
            --bg: #1e1e2f;
            --primary: #ff6b9d;
            --primary-dark: #e05585;
            --text: #f1f1f1;
            --card: #2e2e44;
            --danger: #e74c3c;
            --success: #27ae60;
            --shadow: 0 4px 12px rgba(0,0,0,0.3);
            --info: #3498db; /* Color extra para el botón de ir a compras/ventas */
        }
        *{margin:0;padding:0;box-sizing:border-box;}
        body{
            font-family: 'Segoe UI', sans-serif;
            /* Usar un fondo similar o el mismo para la consistencia */
            background: url('../../../imagenes/lugares/istockphoto-1856117770-612x612.jpg') no-repeat center center fixed;
            background-size: cover;
            color: var(--text);
            min-height: 100vh;
            display: flex;
            flex-direction: column; /* Para centrar el contenido y ordenar */
            align-items: center; /* Centrado horizontal */
            padding: 50px 20px;
            position: relative;
            z-index: 1;
        }

        /* Overlay oscuro para mejorar legibilidad */
        body::before {
            content: "";
            position: fixed;
            top:0;
            left:0;
            right:0;
            bottom:0;
            background: rgba(0,0,0,0.6); /* Un poco más oscuro */
            z-index: -1;
        }

        /* Título Principal */
        h1{
            font-size:2.5rem;
            margin-bottom:30px;
            color: var(--primary);
            text-shadow: 2px 2px 6px rgba(0,0,0,0.8);
            text-align: center;
        }
        
        /* Contenedor principal para la tabla (simulando un "content" centrado) */
        .main-container {
            width: 100%;
            max-width: 1100px; /* Ancho máximo para que no se extienda demasiado */
            margin: 0 auto;
        }

        /* Tables */
        table{
            width:100%;
            border-collapse:collapse;
            background: rgba(46,46,68,0.95); /* Más opaco */
            border-radius:10px;
            overflow:hidden;
            box-shadow: var(--shadow);
            margin-bottom:25px;
        }
        th,td{
            padding:15px 18px;
            text-align:left;
            font-size:1rem;
        }
        th{
            background: var(--primary-dark);
            color:#fff;
            font-weight:700;
            text-transform: uppercase;
        }
        tr:nth-child(even){background: rgba(37,37,56,0.9);}
        tr:hover{background: rgba(255,107,157,0.15);}
        
        /* Estilos para las celdas de Estado */
        .estado-activo {
            color: var(--success);
            font-weight: bold;
        }
        .estado-inactivo {
            color: var(--danger);
            font-weight: bold;
        }


        /* Buttons */
        .btn{
            padding:8px 15px;
            border-radius:6px;
            font-size:0.9rem;
            font-weight:600;
            text-decoration:none;
            display:inline-block;
            transition:.3s;
            border: none;
            cursor: pointer;
        }
        .btn-view{
            background: var(--danger); /* Usar danger para reembolsar/cancelar */
            color:#fff;
            white-space: nowrap; /* Evita que el texto del botón se rompa */
        }
        /* Clase para el botón deshabilitado visualmente */
        .btn-disabled{
            background: #7f8c8d; /* Gris para deshabilitado */
            color:#fff;
            cursor: not-allowed;
            opacity: 0.6;
            white-space: nowrap;
        }
        .btn:hover:not(.btn-disabled){opacity:.85;}
        
        /* Estilos para los enlaces de navegación */
        .nav-links {
            display: flex;
            gap: 20px;
            margin-top: 20px;
        }

        .nav-links a {
            display:inline-block;
            padding:10px 18px;
            background: var(--info); /* Color diferente para navegación */
            color:#fff;
            text-decoration:none;
            border-radius:6px;
            font-weight:600;
            transition:.3s;
            box-shadow: var(--shadow);
        }
        .nav-links a:hover {
            background: #2980b9;
        }
        
        /* Mensaje de no hay citas */
        .no-citas {
            background: rgba(46,46,68,0.95);
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            font-size: 1.1rem;
            color: var(--warning); /* Usar el color warning del otro código */
            box-shadow: var(--shadow);
        }

    </style>
</head>
<body>
    <div class="main-container">
        <h1>Citas Compradas</h1>

        <?php
        if($funcion_traer_citas && $funcion_traer_citas->num_rows > 0){
            echo "<table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Usuario</th>
                        <th>Fecha Cita</th>
                        <th>Estado</th>
                        <th>Lugar</th>
                        <th>Acción</th>
                    </tr>
                </thead><tbody>";

            while($array_citas_compradas = $funcion_traer_citas->fetch_assoc()){
                echo "<tr>
                    <td>{$array_citas_compradas['id_cita']}</td>
                    <td>{$array_citas_compradas['nombre']}</td>
                    <td>{$array_citas_compradas['nombre_usuario']}</td>
                    <td>{$array_citas_compradas['fecha_cita']}</td>
                    ";
                    if($array_citas_compradas['activo'] == 1){
                        echo "<td class='estado-activo'>Activo</td>";

                        echo "<td>{$array_citas_compradas['nombre_lugar']}</td>
                        <td><a class='btn btn-view' href='".BASE_URL."/controlador/controladores_cliente/controladores_inicio/controlador_inicio_cli.php?id_caja={$array_citas_compradas['id_caja']}&id_cita={$array_citas_compradas['id_cita']}&reembolsar_cita=vista_inicio_cli'>Reembolsar Cita</a></td>";


                    }else{
                        echo "<td class='estado-inactivo'>Inactivo</td>";
                        echo "<td>{$array_citas_compradas['nombre_lugar']}</td>
                            <td>
                                <a class='btn btn-disabled' href='#' onclick='alert(\"⚠️ No podés reembolsar una cita que ya está inactiva.\"); return false;'>
                                    Reembolsar Cita
                                </a>
                            </td>";

                    }

                echo "</tr>";

            }
            echo "</tbody></table>";
        } else {
            echo "<p class='no-citas'>No tenés citas compradas actualmente. ¡Es hora de agendar una! 📅✨</p>";
        }

        ?>
        
        <div class="nav-links">
            <a href="<?= BASE_URL ?>/vista/vista_cliente/vista_citas/layout.php">Reservar Cita</a>
            <a href="<?= BASE_URL ?>/vista/vista_cliente/vista_venta_productos/vista_venta.php">Ir a Comprar Productos</a>
        </div>
    </div>
</body>
</html>
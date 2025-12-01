<?php
// Solo inicia sesión si no está iniciada (evita el Notice)

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');
require_once(ROOT_PATH . '/modelo/modelo_cliente/citas/CitaModelo.php');
require_once(ROOT_PATH . '/controlador/controladores_cliente/citas/CitaControlador.php');




$controlador = new CitaControlador($conn);
$modelo_citas = new CitaModelo($conn);

$accion = $_GET['accion'] ?? 'mostrar';
$vista = 'seleccionar_servicios';

switch ($accion) {

    case 'mostrar':
    case 'seleccionar':
        // === CARGAMOS LOS DATOS DE LA BD (CORREGIDO CON LOS NOMBRES REALES DE TUS COLUMNAS) ===
        $detalles = [];

        // SERVICIOS - nombre real de la columna es "nombre" y "precio_servicio"
        $query_servicios = "SELECT 
                                id_servicios AS id, 
                                nombre AS nombre, 
                                precio_servicio, 
                                'servicio' AS tipo 
                            FROM servicios 
                            WHERE activo = 1 
                            ORDER BY nombre";
        
        $result_servicios = mysqli_query($conn, $query_servicios);
        if (!$result_servicios) {
            die("Error en consulta de servicios: " . mysqli_error($conn));
        }
        while ($row = mysqli_fetch_assoc($result_servicios)) {
            $row['precio_servicio'] = $row['precio_servicio']; // lo dejamos igual para el HTML
            $detalles[] = $row;
        }

        // COMBOS - columnas correctas
        $query_combos = "SELECT 
                            id_combos AS id, 
                            nombre AS nombre, 
                            precio, 
                            'combo' AS tipo 
                         FROM combos 
                         WHERE activo = 1 
                         ORDER BY nombre";
        
        $result_combos = mysqli_query($conn, $query_combos);
        if (!$result_combos) {
            die("Error en consulta de combos: " . mysqli_error($conn));
        }
        while ($row = mysqli_fetch_assoc($result_combos)) {
            $detalles[] = $row;
        }

        // LUGARES
        $lugares = [];
        $query_lugares = "SELECT id_lugar, nombre_lugar FROM lugares WHERE activo = 1 ORDER BY nombre_lugar";
        $result_lugares = mysqli_query($conn, $query_lugares);
        if (!$result_lugares) {
            die("Error en consulta de lugares: " . mysqli_error($conn));
        }
        while ($row = mysqli_fetch_assoc($result_lugares)) {
            $lugares[] = $row;
        }

        // Pasamos a variables globales para que la vista las vea
        $GLOBALS['detalles'] = $detalles;
        $GLOBALS['lugares']  = $lugares;

        $vista = 'seleccionar_servicios';
        break;

    case 'guardar':
        $fecha_hora = $_POST['fecha_hora'] ?? '';
        $id_lugar   = $_POST['id_lugar'] ?? 1;

        if (empty($fecha_hora)) {
            $vista = 'seleccionar_servicios';
            break;
        }

        // Verificar si el turno está ocupado
        $sql = "SELECT id_cita FROM citas WHERE fecha_cita = ? AND id_lugar = ? AND activo = 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $fecha_hora, $id_lugar);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // TURNO OCUPADO → lista de espera
            $vista = 'lista_espera_form';
            $GLOBALS['fecha_deseada'] = $fecha_hora;  // Pasamos para la vista
            $GLOBALS['id_lugar_deseado'] = $id_lugar;
        } else {
            $controlador->guardar();
            exit;
        }
        break;

    case 'unirse_lista':
        $id_usuario = $_SESSION['user'];
        $fecha_deseada = $_POST['fecha_hora'] ?? date('Y-m-d H:i:s');  // Adaptado
        $id_lugar = $_POST['id_lugar'] ?? 1;
        $tiempo_estimado = '30-90 minutos';
        $confirmacion = 0;  // 0 = esperando (adaptado a tu columna)
        $id_caja = NULL;  // Null hasta que se confirme

        $sql = "INSERT INTO lista_espera 
                (id_caja, id_usuario_persona, fecha_deseada, id_lugar, tiempo_estimado, confirmacion) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iisisi", $id_caja, $id_usuario, $fecha_deseada, $id_lugar, $tiempo_estimado, $confirmacion);
        $stmt->execute();

        // Vista de éxito (creala abajo)
        $vista = 'exito_lista_espera';
        $GLOBALS['fecha_deseada'] = $fecha_deseada;
        break;

    default:
        $vista = 'seleccionar_servicios';
        break;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RoseSpa - Reservar Cita</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background: linear-gradient(to bottom, #fce4ec, #f8bbd0); font-family: 'Segoe UI', sans-serif; }
        .header { background: linear-gradient(to right, #ff6b9d, #e91e63); padding: 2rem 0; }
    </style>
</head>
<body class="min-h-screen">
    <header class="header text-white shadow-2xl text-center">
        <h1 class="text-5xl font-bold tracking-wider">RoseSpa - Centro de Belleza</h1>
    </header>

    <main class="container mx-auto px-6 py-12">
        <?php
        $ruta = __DIR__ . "/" . $vista . ".php";
        if (file_exists($ruta)) {
            include $ruta;
        } else {
            echo "<div class='text-center py-20 bg-white rounded-3xl shadow-2xl'>
                    <h2 class='text-4xl text-pink-600 font-bold'>Error 404</h2>
                    <p>Vista no encontrada: <strong>$vista.php</strong></p>
                  </div>";
        }
        ?>
    </main>
</body>
</html>
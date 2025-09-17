<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once(__DIR__ . '/../../config/db.php');
require_once(__DIR__ . '/../../modelos/modelo_login/modelo_login.php');


date_default_timezone_set('America/Argentina/Buenos_Aires');

header('Content-Type: application/json; charset=UTF-8');

// Solo permitir POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Leer JSON del body
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['nombre_usuario']) && isset($data['contrasena'])) {
        $nombre_usuario = $data['nombre_usuario'];
        $contrasena = $data['contrasena'];

        $logeo = new iniciar_session($nombre_usuario, $contrasena);
        $resultado = $logeo->buscar_usuario($conn);

        if ($resultado && $resultado->num_rows > 0) {
            $usuario = $resultado->fetch_assoc();
            $fecha_actual = date('Y-m-d H:i:s');

            // insertar historial de logins
            $logeo->insertar_historial_login($conn, $usuario['id_usuario'], $fecha_actual);

            // discriminar según tipo
            if ($usuario['id_tipo_usuario'] == 1) {
                $resultado_adm = $logeo->discriminar_adm($usuario, $conn);
                echo json_encode([
                    'status' => 'success',
                    'tipo'   => 'admin',
                    'usuario'=> $usuario,
                    'relacion' => $resultado_adm['traer_adm']->fetch_assoc()
                ]);
            } elseif ($usuario['id_tipo_usuario'] == 3) {
                $resultado_emp = $logeo->discriminar_empleados($usuario, $conn);
                echo json_encode([
                    'status' => 'success',
                    'tipo'   => 'empleado',
                    'usuario'=> $usuario,
                    'relacion' => $resultado_emp['traer_emp']->fetch_assoc()
                ]);
            } elseif ($usuario['id_tipo_usuario'] == 2) {
                $resultado_cli = $logeo->discriminar_cliente($usuario, $conn);
                echo json_encode([
                    'status' => 'success',
                    'tipo'   => 'cliente',
                    'usuario'=> $usuario,
                    'relacion' => $resultado_cli['traer_cli']->fetch_assoc()
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message'=> 'Tipo de usuario no reconocido'
                ]);
            }

        } else {
            echo json_encode([
                'status' => 'error',
                'message'=> 'Usuario o contraseña incorrectos'
            ]);
        }
    } else {
        echo json_encode([
            'status' => 'error',
            'message'=> 'Faltan parámetros: nombre_usuario o contrasena'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message'=> 'Método no permitido. Use POST.'
    ]);
}

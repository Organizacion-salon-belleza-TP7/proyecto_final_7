<?php
// RUTA CORRECTA DESDE controladores_adm/lugares/
require_once __DIR__ . "/../../../variable_global.php";
require_once ROOT_PATH . '/modelo/modelo_adm/lugares/modelo_lugares.php';
require_once ROOT_PATH . '/modelo/BD.php';

$modelo = new Lugar($conn);

// === AGREGAR LUGAR ===
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['agregar'])) {
    $nombre = trim($_POST['nombre'] ?? '');
    $coordenadas = $_POST['cooordenadas'] ?? '';
    $activo = isset($_POST['activo']) ? 1 : 0;

    $imagen_ruta = "";

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
        $carpeta = ROOT_PATH . "/imagenes/lugares/";
        if (!is_dir($carpeta)) mkdir($carpeta, 0777, true);

        $extension = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
        $nombre_archivo = time() . "_" . rand(1000, 9999) . "." . $extension;
        $ruta_final = $carpeta . $nombre_archivo;

        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_final)) {
            $imagen_ruta = "imagenes/lugares/" . $nombre_archivo;
        }
    }

    if ($nombre && $coordenadas) {
        $modelo->agregarLugar($nombre, $coordenadas, $imagen_ruta, $activo);
        header("Location: ../../../vista/vista_adm/lugares/lugares.php?exito=1");
        exit;
    }
}

// === ELIMINAR LUGAR ===
if (isset($_GET['eliminar'])) {
    $id = (int)$_GET['eliminar'];
    if ($id > 0) {
        $modelo->eliminarLugar($id);
    }
    header("Location: ../../../vista/vista_adm/lugares/lugares.php");
    exit;
}
?>
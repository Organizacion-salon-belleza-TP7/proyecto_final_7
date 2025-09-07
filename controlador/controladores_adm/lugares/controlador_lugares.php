<?php
require_once __DIR__ . "/../../../variable_global.php";
require_once __DIR__ . "/modelo/modelo_adm/lugares/modelo_lugares.php";

require_once(ROOT_PATH . '/modelo/BD.php');

$modelo = new Lugar($conn);

// Agregar lugar
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['agregar'])) {
    $nombre = $_POST['nombre'];
    $coordenadas = $_POST['coordenadas'];
    $activo = isset($_POST['activo']) ? 1 : 0;

    $imagen = "";
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        $carpeta = ROOT_PATH  . "/../../uploads/";
        if (!is_dir($carpeta)) mkdir($carpeta, 0777, true);

        $imagen = basename($_FILES['imagen']['name']);
        move_uploaded_file($_FILES['imagen']['tmp_name'], $carpeta . $imagen);
        $imagen = BASE_URL . "uploads/" . $imagen;
    }

    $modelo->agregarLugar($nombre, $coordenadas, $imagen, $activo);
    header("Location: " . BASE_URL . "/vista/vista_adm/lugares/lugares.php");
    exit;
}

// Eliminar lugar
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $modelo->eliminarLugar($id);
    header("Location: " . BASE_URL . "/vista/vista_adm/lugares/lugares.php");
    exit;
}
?>




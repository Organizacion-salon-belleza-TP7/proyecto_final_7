<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/modelo_adm/proveedores/modelo_proveedor.php');
require_once(ROOT_PATH . '/modelo/BD.php');


// Instanciamos el modelo
$modelo = new ModeloProveedor($conn);

// Verificamos si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre_proveedor'];
    $apellido = $_POST['apellido_proveedor'];
    $dni = $_POST['dni'];

    if ($modelo->agregarProveedor($nombre, $apellido, $dni)) {
        header("Location: " . BASE_URL . "/vista/vista_adm/proveedores/vista_proveedores.php");
        exit();
    } else {
        echo "Error al agregar proveedor.";
    }
}
?>



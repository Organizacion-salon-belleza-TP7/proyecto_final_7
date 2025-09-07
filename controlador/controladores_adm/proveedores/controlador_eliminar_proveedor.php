<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/modelo_adm/proveedores/modelo_proveedor.php');


if (isset($_GET['id'])) {
    $modelo = new ModeloProveedor($conn);
    $id = $_GET['id'];

    if ($modelo->eliminarProveedor($id)) {
        header("Location: " . BASE_URL . "/vista/vista_adm/proveedores/vista_proveedores.php");
        exit();
    } else {
        echo "Error al eliminar proveedor.";
    }
}
?>

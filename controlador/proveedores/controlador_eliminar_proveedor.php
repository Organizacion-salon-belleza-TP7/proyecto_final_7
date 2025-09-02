<?php
require_once(__DIR__ . '/../../variable_global.php');
require_once __DIR__ . '/modelo/proveedores/modelo_proveedor.php';


if (isset($_GET['id'])) {
    $modelo = new ModeloProveedor();
    $id = $_GET['id'];

    if ($modelo->eliminarProveedor($id)) {
        header("Location: ../vista/vista_proveedores.php");
        exit();
    } else {
        echo "Error al eliminar proveedor.";
    }
}
?>

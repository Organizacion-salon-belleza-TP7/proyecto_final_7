<?php
// Incluimos variable global
require_once(__DIR__ . '/../../variable_global.php');

// Incluimos el modelo (ahora sí está dentro de subcarpeta proveedores)
require_once __DIR__ . "modelo/proveedores/modelo_proveedor.php";

// Instanciamos el modelo
$modelo = new ModeloProveedor();

// Verificamos si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre_proveedor'];
    $apellido = $_POST['apellido_proveedor'];
    $dni = $_POST['dni'];

    if ($modelo->agregarProveedor($nombre, $apellido, $dni)) {
        header("Location: " . BASE_URL . "/vista/proveedores/vista_proveedores.php");
        exit();
    } else {
        echo "Error al agregar proveedor.";
    }
}
?>



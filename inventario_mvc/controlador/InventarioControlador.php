<?php
require_once("modelo/InventarioModelo.php");

$modelo = new InventarioModelo();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    $imagen = '';

    if (!empty($_FILES['imagen']['name'])) {
        $nombreImagen = basename($_FILES['imagen']['name']);
        $ruta = "uploads/" . $nombreImagen;
        move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta);
        $imagen = $ruta;
    } else {
        $imagen = $_POST['imagen_existente'] ?? '';
    }

    if ($accion === 'agregar') {
        $modelo->agregar(
            $_POST['nombre'],
            $_POST['stock'],
            $_POST['vencimiento'],
            $_POST['precio_producto'],
            $_POST['precio_venta'],
            $imagen,
            $_POST['proveedor']
        );
    } elseif ($accion === 'modificar') {
        $modelo->modificar(
            $_POST['id'],
            $_POST['nombre'],
            $_POST['stock'],
            $_POST['vencimiento'],
            $_POST['precio_producto'],
            $_POST['precio_venta'],
            $imagen,
            $_POST['proveedor']
        );
    } elseif ($accion === 'eliminar') {
        $modelo->eliminar($_POST['id']);
    }

    // IMPORTANTE: redirigir aquí para que luego cargue todo de nuevo
    header("Location: index.php");
    exit;
}

// LISTAR INVENTARIO (esto es lo que mostrará la vista)
$inventario = $modelo->listar();

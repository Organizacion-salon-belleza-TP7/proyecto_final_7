<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');
require_once(ROOT_PATH . "/modelo/modelo_adm/modelo_inventario/InventarioModelo.php");

date_default_timezone_set('America/Argentina/Buenos_Aires');
session_start();

$inventario_modelo = new Inventario($conn);

// --- ACCIONES POST ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    $imagen = '';

    // Manejo de imagen
    if (!empty($_FILES['imagen']['name'])) {
        $nombreImagen = basename($_FILES['imagen']['name']);
        $ruta = ROOT_PATH . "/inventario_mvc/uploads/" . $nombreImagen;
        move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta);
        $imagen = $nombreImagen;
    } else {
        $imagen = $_POST['imagen_existente'] ?? '';
    }

    if ($accion === 'agregar') {
        $id_insertado = $inventario_modelo->agregar_producto(
            $_POST['nombre'],
            $_POST['stock'],
            $_POST['vencimiento'],
            $_POST['precio_producto'],
            $_POST['precio_venta'],
            $imagen,
            $_POST['proveedor']
        );

        if ($id_insertado) {
            echo '<script>
                alert("Producto agregado correctamente");
                window.location = "' . BASE_URL . '/vista/vista_adm/inventario/InventarioVista.php";
            </script>';
            exit;
        }
    }

 if ($accion === 'modificar') {
    $imagen = '';

    // Manejo de imagen
    if (!empty($_FILES['imagen']['name'])) {
        $nombreImagen = basename($_FILES['imagen']['name']);
        $ruta = ROOT_PATH . "/inventario_mvc/uploads/" . $nombreImagen;
        move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta);
        $imagen = $nombreImagen;
    } else {
        $imagen = $_POST['imagen_existente'] ?? '';
    }

    // AQUÍ ESTABA EL ERROR: FALTABA $_POST['id']
    $modificado = $inventario_modelo->modificar_producto(
        $_POST['id'],                    // CORREGIDO: agregado
        $_POST['nombre'],
        $_POST['stock'],
        $_POST['vencimiento'],
        $_POST['precio_producto'],
        $_POST['precio_venta'],
        $imagen,
        $_POST['proveedor']
    );

    if ($modificado) {
        echo '<script>
            alert("Producto modificado correctamente");
            window.location = "' . BASE_URL . '/vista/vista_adm/inventario/InventarioVista.php";
        </script>';
        exit;
    } else {
        echo '<script>
            alert("Error al modificar el producto");
            window.location = "' . BASE_URL . '/vista/vista_adm/inventario/InventarioVista.php";
        </script>';
        exit;
    }
}
    
}

// --- ACCIONES GET ---
if (isset($_GET['detalle']) && !empty($_GET['id'])) {
    $id = $_GET['id'];
    header("Location: " . BASE_URL . "/vista/vista_adm/inventario/vista_detalleInventario.php?id=$id");
    exit;
}

if (isset($_GET['agregar']) && $_GET['agregar'] === 'vista_inventario') {
    header("Location: " . BASE_URL . "/vista/vista_adm/inventario/agregar_producto.php");
    exit;
}

if (isset($_GET['modificar']) && !empty($_GET['id'])) {
    $id = $_GET['id'];
    header("Location: " . BASE_URL . "/vista/vista_adm/inventario/editar_producto.php?id=$id");
    exit;
}

if (isset($_GET['eliminar']) && !empty($_GET['id'])) {
    $id = $_GET['id'];
    $resultado = $inventario_modelo->eliminar_producto($id);

    if ($resultado) {
        echo '<script>
            alert("Producto eliminado correctamente");
            window.location = "' . BASE_URL . '/vista/vista_adm/inventario/InventarioVista.php";
        </script>';
        exit;
    } else {
        echo '<script>
            alert("Error al eliminar el producto");
            window.location = "' . BASE_URL . '/vista/vista_adm/inventario/InventarioVista.php";
        </script>';
        exit;
    }
}


// --- LISTAR INVENTARIO PARA LA VISTA ---
$inventario = $inventario_modelo->mostrar_inventario();

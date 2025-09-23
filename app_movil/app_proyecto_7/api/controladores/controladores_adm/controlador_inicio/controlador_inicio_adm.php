<?php
header('Content-Type: application/json; charset=UTF-8');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../config/db.php');
require_once(__DIR__ . '/../../../modelos/modelos_adm/modelo_inicio/modelo_inicio_adm.php');

$inventario = new Inventario($conn);

// Get the HTTP method
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            // Product detail: always return an array with one object
            $id = intval($_GET['id']);
            $result = $inventario->detalle_producto($id);
            $data = $result->fetch_assoc();
            echo json_encode($data ? [$data] : []);
        } elseif (isset($_GET['buscar'])) {
            // Search product by name
            $termino = $_GET['buscar'];
            $result = $inventario->buscar_producto($termino);
            $data = $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode($data);
        } else {
            // Show the entire inventory
            $result = $inventario->mostrar_inventario();

            // Corrected logic to ensure $data is always an array
            if ($result && $result->num_rows > 0) {
                $data = $result->fetch_all(MYSQLI_ASSOC);
            } else {
                // If there are no products, initialize $data as an empty array
                $data = [];
            }
            echo json_encode($data);
        }
        break;

    case 'POST':
        // Create product
        $data = json_decode(file_get_contents('php://input'), true);

        $nombre = $data['nombre_producto'];
        $stock = $data['stock'];
        $vencimiento = $data['vencimiento'];
        $precio_compra = $data['precio_producto'];
        $precio_venta = $data['precio_venta'];
        $imagen = $data['imagen_producto'] ?? '';
        $proveedor = $data['id_proveedor'];

        $id_insertado = $inventario->agregar_producto($nombre, $stock, $vencimiento, $precio_compra, $precio_venta, $imagen, $proveedor);

        if ($id_insertado) {
            echo json_encode(['message' => 'Producto agregado correctamente', 'id' => $id_insertado]);
        } else {
            echo json_encode(['error' => 'Error al agregar el producto']);
        }
        break;

    case 'PUT':
        // Modify product
        $data = json_decode(file_get_contents('php://input'), true);

        $id = $data['id_inventario'];
        $nombre = $data['nombre_producto'];
        $stock = $data['stock'];
        $vencimiento = $data['vencimiento'];
        $precio_compra = $data['precio_producto'];
        $precio_venta = $data['precio_venta'];
        $imagen = $data['imagen_producto'] ?? '';
        $proveedor = $data['id_proveedor'];

        $modificado = $inventario->modificar_producto($id, $nombre, $stock, $vencimiento, $precio_compra, $precio_venta, $imagen, $proveedor);

        if ($modificado) {
            echo json_encode(['message' => 'Producto modificado correctamente']);
        } else {
            echo json_encode(['error' => 'Error al modificar el producto']);
        }
        break;

    case 'DELETE':
        // Delete product
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $eliminado = $inventario->eliminar_producto($id);

            if ($eliminado) {
                echo json_encode(['message' => 'Producto eliminado correctamente']);
            } else {
                echo json_encode(['error' => 'Error al eliminar el producto']);
            }
        } else {
            echo json_encode(['error' => 'ID de producto no especificado']);
        }
        break;

    default:
        echo json_encode(['error' => 'Método no permitido']);
        break;
}

$conn->close();
?>
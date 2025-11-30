<?php
// HEADERS COMPLETOS PARA CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS, PATCH");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, Accept, Origin, X-Custom-Header");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");
header("Content-Type: application/json; charset=UTF-8");

// Manejar preflight requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS, PATCH");
    
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    
    http_response_code(200);
    exit();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../config/db.php');
require_once(__DIR__ . '/../../../modelos/modelos_adm/modelo_inicio/modelo_inicio_adm.php');

// FUNCIÓN PARA ENVIAR RESPUESTAS JSON CONSISTENTES
function sendJsonResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    echo json_encode($data);
    exit;
}

// FUNCIÓN PARA MANEJAR ERRORES
function handleError($message, $statusCode = 500) {
    sendJsonResponse(['success' => false, 'error' => $message], $statusCode);
}

// FUNCIÓN PARA OBTENER RUTA CORRECTA DE IMÁGENES
function getUploadDir() {
    $baseDir = '/opt/lampp/htdocs/proyecto_final_7/imagenes/inventario/';
    
    if (!is_dir($baseDir)) {
        if (!mkdir($baseDir, 0755, true)) {
            error_log("No se pudo crear directorio: $baseDir");
            return false;
        }
    }
    
    if (!is_writable($baseDir)) {
        error_log("Directorio no tiene permisos de escritura: $baseDir");
        return false;
    }
    
    return $baseDir;
}

try {
    $inventario = new Inventario($conn);

    // Get the HTTP method
    $method = $_SERVER['REQUEST_METHOD'];

    switch ($method) {
        case 'GET':
            if (isset($_GET['id'])) {
                $id = intval($_GET['id']);
                $result = $inventario->detalle_producto($id);
                $data = $result->fetch_assoc();
                sendJsonResponse($data ? [$data] : []);
            } elseif (isset($_GET['buscar'])) {
                $termino = $_GET['buscar'];
                $result = $inventario->buscar_producto($termino);
                $data = $result->fetch_all(MYSQLI_ASSOC);
                sendJsonResponse($data);
            } else {
                $result = $inventario->mostrar_inventario();
                if ($result && $result->num_rows > 0) {
                    $data = $result->fetch_all(MYSQLI_ASSOC);
                } else {
                    $data = [];
                }
                sendJsonResponse($data);
            }
            break;

        case 'POST':
            // DEBUG EXTENSIVO
            error_log("=== DEBUG POST REQUEST ===");
            error_log("CONTENT_TYPE: " . ($_SERVER['CONTENT_TYPE'] ?? 'NO DEFINIDO'));
            error_log("POST data: " . print_r($_POST, true));
            error_log("FILES data: " . print_r($_FILES, true));
            error_log("=== FIN DEBUG ===");

            // DETERMINAR SI ES AGREGAR O MODIFICAR
            $isMultipart = isset($_SERVER['CONTENT_TYPE']) && 
                          strpos($_SERVER['CONTENT_TYPE'], 'multipart/form-data') !== false;
            
            if ($isMultipart) {
                error_log("Procesando FormData (multipart)");
                
                // ✅✅✅ VERIFICAR SI ES MODIFICACIÓN (tiene id_inventario) O AGREGAR NUEVO ✅✅✅
                if (isset($_POST['id_inventario']) && !empty($_POST['id_inventario'])) {
                    // ES UNA MODIFICACIÓN
                    error_log("🔧 DETECTADA MODIFICACIÓN DE PRODUCTO");
                    
                    $id = intval($_POST['id_inventario']);
                    $nombre = $_POST['nombre_producto'] ?? '';
                    $stock = intval($_POST['stock'] ?? 0);
                    $vencimiento = $_POST['vencimiento'] ?? null;
                    $precio_compra = floatval($_POST['precio_producto'] ?? 0);
                    $precio_venta = floatval($_POST['precio_venta'] ?? 0);
                    $proveedor = intval($_POST['id_proveedor'] ?? 0);
                    
                    // MANEJAR IMAGEN
                    $imagen = '';
                    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                        error_log("Procesando nueva imagen para modificación...");
                        
                        $uploadDir = getUploadDir();
                        if (!$uploadDir) {
                            handleError('Error con el directorio de imágenes.', 500);
                        }

                        $imageFileType = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
                        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                        
                        if (!in_array($imageFileType, $allowedExtensions)) {
                            handleError('Tipo de archivo no permitido.', 400);
                        }

                        $newFileName = uniqid() . '.' . $imageFileType;
                        $uploadFile = $uploadDir . $newFileName;
                        
                        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $uploadFile)) {
                            $imagen = $newFileName;
                            error_log("Nueva imagen guardada: " . $imagen);
                        } else {
                            $error = error_get_last();
                            error_log("Error moviendo archivo: " . ($error['message'] ?? 'Desconocido'));
                            handleError('Error al subir la nueva imagen.', 500);
                        }
                    } else {
                        error_log("No hay nueva imagen, el modelo mantendrá la existente");
                        $imagen = '';
                    }

                    error_log("Actualizando producto ID: $id");
                    $modificado = $inventario->modificar_producto($id, $nombre, $stock, $vencimiento, $precio_compra, $precio_venta, $imagen, $proveedor);

                    if ($modificado) {
                        sendJsonResponse([
                            'success' => true,
                            'message' => 'Producto modificado correctamente',
                            'data' => [
                                'id' => $id,
                                'nombre_producto' => $nombre,
                                'imagen_producto' => $imagen
                            ]
                        ]);
                    } else {
                        handleError('Error al modificar el producto en la base de datos.');
                    }
                    
                } else {
                    // ES UN AGREGADO NUEVO
                    error_log("🆕 DETECTADO AGREGADO DE NUEVO PRODUCTO");
                    
                    // Verificar que se recibió una imagen
                    if (!isset($_FILES['imagen']) || $_FILES['imagen']['error'] !== UPLOAD_ERR_OK) {
                        handleError('No se recibió una imagen válida.', 400);
                    }

                    // Validar campos requeridos
                    $requiredFields = ['nombre_producto', 'stock', 'precio_venta', 'id_proveedor'];
                    foreach ($requiredFields as $field) {
                        if (empty($_POST[$field])) {
                            handleError("El campo $field es requerido.", 400);
                        }
                    }

                    $nombre = $_POST['nombre_producto'];
                    $stock = intval($_POST['stock']);
                    $vencimiento = $_POST['vencimiento'] ?? null;
                    $precio_compra = floatval($_POST['precio_producto'] ?? 0);
                    $precio_venta = floatval($_POST['precio_venta']);
                    $proveedor = intval($_POST['id_proveedor']);
                    
                    // Procesar imagen
                    $uploadDir = getUploadDir();
                    if (!$uploadDir) {
                        handleError('Error con el directorio de imágenes.', 500);
                    }

                    $imageFileType = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
                    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                    
                    if (!in_array($imageFileType, $allowedExtensions)) {
                        handleError('Tipo de archivo no permitido. Use JPG, JPEG, PNG o GIF.', 400);
                    }

                    $newFileName = uniqid() . '.' . $imageFileType;
                    $uploadFile = $uploadDir . $newFileName;
                    
                    error_log("Intentando guardar imagen en: $uploadFile");
                    
                    if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $uploadFile)) {
                        $error = error_get_last();
                        error_log("Error moviendo archivo: " . ($error['message'] ?? 'Desconocido'));
                        handleError('Error al subir la imagen. Verifique permisos del servidor.', 500);
                    }

                    $imagen = $newFileName;

                    $id_insertado = $inventario->agregar_producto($nombre, $stock, $vencimiento, $precio_compra, $precio_venta, $imagen, $proveedor);

                    if ($id_insertado) {
                        sendJsonResponse([
                            'success' => true,
                            'message' => 'Producto agregado correctamente', 
                            'id' => $id_insertado
                        ], 201);
                    } else {
                        handleError('Error al agregar el producto en la base de datos.');
                    }
                }

            } else {
                // Es JSON (para modificación sin imagen)
                error_log("Procesando JSON");
                $input = file_get_contents('php://input');
                $data = json_decode($input, true);
                
                if (json_last_error() !== JSON_ERROR_NONE) {
                    handleError('JSON inválido: ' . json_last_error_msg(), 400);
                }

                if (isset($data['id_inventario'])) {
                    // Modificación con JSON
                    $id = intval($data['id_inventario']);
                    $nombre = $data['nombre_producto'];
                    $stock = intval($data['stock']);
                    $vencimiento = $data['vencimiento'] ?? null;
                    $precio_compra = floatval($data['precio_producto'] ?? 0);
                    $precio_venta = floatval($data['precio_venta']);
                    $imagen = $data['imagen_producto'] ?? '';
                    $proveedor = intval($data['id_proveedor'] ?? 0);

                    error_log("Actualizando producto (JSON) ID: $id");
                    $modificado = $inventario->modificar_producto($id, $nombre, $stock, $vencimiento, $precio_compra, $precio_venta, $imagen, $proveedor);

                    if ($modificado) {
                        sendJsonResponse([
                            'success' => true,
                            'message' => 'Producto modificado correctamente'
                        ]);
                    } else {
                        handleError('Error al modificar el producto en la base de datos.');
                    }
                } else {
                    handleError('Para agregar producto use FormData con imagen.', 400);
                }
            }
            break;

        case 'DELETE':
            if (isset($_GET['id'])) {
                $id = intval($_GET['id']);
                $eliminado = $inventario->eliminar_producto($id);

                if ($eliminado) {
                    sendJsonResponse([
                        'success' => true,
                        'message' => 'Producto eliminado correctamente'
                    ]);
                } else {
                    handleError('Error al eliminar el producto');
                }
            } else {
                handleError('ID de producto no especificado', 400);
            }
            break;

        default:
            handleError('Método no permitido', 405);
            break;
    }

} catch (Exception $e) {
    error_log("Error en API: " . $e->getMessage());
    handleError('Error interno del servidor: ' . $e->getMessage());
}
?>
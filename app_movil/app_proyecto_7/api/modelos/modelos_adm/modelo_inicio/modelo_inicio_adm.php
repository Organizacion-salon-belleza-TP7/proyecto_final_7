<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../config/db.php');

class Inventario {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Mostrar todo el inventario
    public function mostrar_inventario() {
        $sql = "SELECT i.id_inventario, i.nombre_producto, i.stock, i.vencimiento, 
                       i.precio_producto, i.precio_venta, i.imagen_producto, 
                       p.nombre_proveedor, p.id_proveedor
                FROM inventario i
                INNER JOIN proveedores p ON p.id_proveedor = i.id_proveedor
                ORDER BY i.id_inventario DESC";

        $resultado = $this->conn->query($sql);
        if (!$resultado) {
            error_log("Error en mostrar_inventario: " . $this->conn->error);
            return false;
        }
        return $resultado;
    }

    // Detalle de producto
    public function detalle_producto($id_inventario) {
        $stmt = $this->conn->prepare("SELECT i.*, p.nombre_proveedor, p.id_proveedor
                                      FROM inventario i
                                      INNER JOIN proveedores p ON p.id_proveedor = i.id_proveedor
                                      WHERE i.id_inventario = ?");
        if (!$stmt) {
            error_log("Error preparando detalle_producto: " . $this->conn->error);
            return false;
        }
        
        $stmt->bind_param("i", $id_inventario);
        if (!$stmt->execute()) {
            error_log("Error ejecutando detalle_producto: " . $stmt->error);
            return false;
        }
        return $stmt->get_result();
    }

    // Formulario agregar producto
    public function formulario_agregar_producto() {
        $proveedores = $this->conn->query("SELECT id_proveedor, nombre_proveedor FROM proveedores");
        if (!$proveedores) {
            error_log("Error en formulario_agregar_producto: " . $this->conn->error);
            return false;
        }
        return $proveedores;
    }

    // Agregar nuevo producto
    public function agregar_producto($nombre, $stock, $vencimiento, $precio_compra, $precio_venta, $imagen, $proveedor) {
        $stmt = $this->conn->prepare("INSERT INTO inventario(nombre_producto, stock, vencimiento, precio_producto, precio_venta, imagen_producto, id_proveedor) 
                                      VALUES (?, ?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            error_log("Error preparando agregar_producto: " . $this->conn->error);
            return false;
        }
        
        $stmt->bind_param("sissdsi", $nombre, $stock, $vencimiento, $precio_compra, $precio_venta, $imagen, $proveedor);

        if ($stmt->execute()) {
            $id_insertado = $this->conn->insert_id;
            error_log("Producto agregado correctamente. ID: " . $id_insertado);
            return $id_insertado;
        } else {
            error_log("Error ejecutando agregar_producto: " . $stmt->error);
            return false;
        }
    }

    // Formulario modificar producto
    public function formulario_modificar_producto($id_inventario) {
        $stmt = $this->conn->prepare("SELECT * FROM inventario WHERE id_inventario = ?");
        if (!$stmt) {
            error_log("Error preparando formulario_modificar_producto: " . $this->conn->error);
            return false;
        }
        
        $stmt->bind_param("i", $id_inventario);
        if (!$stmt->execute()) {
            error_log("Error ejecutando formulario_modificar_producto: " . $stmt->error);
            return false;
        }
        
        $producto = $stmt->get_result()->fetch_assoc();

        $proveedores = $this->conn->query("SELECT id_proveedor, nombre_proveedor FROM proveedores");
        if (!$proveedores) {
            error_log("Error obteniendo proveedores: " . $this->conn->error);
            return false;
        }

        return [
            'producto' => $producto,
            'proveedores' => $proveedores
        ];
    }

    // Modificar producto - CORREGIDO
    public function modificar_producto($id, $nombre, $stock, $vencimiento, $precio_compra, $precio_venta, $imagen, $proveedor) {
        try {
            error_log("Modificando producto ID: $id");
            error_log("Datos - Nombre: $nombre, Stock: $stock, Imagen: $imagen");
            
            // SI LA IMAGEN ESTÁ VACÍA, MANTENER LA IMAGEN EXISTENTE
            if (empty($imagen)) {
                error_log("No hay nueva imagen, obteniendo imagen actual...");
                $stmt_select = $this->conn->prepare("SELECT imagen_producto FROM inventario WHERE id_inventario = ?");
                $stmt_select->bind_param("i", $id);
                $stmt_select->execute();
                $result = $stmt_select->get_result();
                
                if ($result && $result->num_rows > 0) {
                    $producto_actual = $result->fetch_assoc();
                    $imagen = $producto_actual['imagen_producto'];
                    error_log("Imagen actual mantenida: " . $imagen);
                } else {
                    error_log("No se pudo obtener la imagen actual del producto ID: $id");
                    $imagen = ''; // Imagen vacía si no se encuentra
                }
                $stmt_select->close();
            } else {
                error_log("Usando nueva imagen: " . $imagen);
            }

            $stmt = $this->conn->prepare("UPDATE inventario 
                                          SET nombre_producto=?, stock=?, vencimiento=?, 
                                              precio_producto=?, precio_venta=?, imagen_producto=?, id_proveedor=? 
                                          WHERE id_inventario=?");
            if (!$stmt) {
                error_log("Error preparando modificar_producto: " . $this->conn->error);
                return false;
            }
            
            $stmt->bind_param("sisddsii", $nombre, $stock, $vencimiento, $precio_compra, $precio_venta, $imagen, $proveedor, $id);
            
            $result = $stmt->execute();
            
            if (!$result) {
                error_log("Error ejecutando modificar_producto: " . $stmt->error);
                return false;
            }

            // Verificar si realmente se modificó alguna fila
            if ($stmt->affected_rows > 0) {
                error_log("Producto modificado correctamente. ID: $id, Filas afectadas: " . $stmt->affected_rows);
                return true;
            } else {
                error_log("No se modificó ningún registro. ID: $id, Affected rows: " . $stmt->affected_rows);
                return true; // Puede que los datos sean iguales, no necesariamente es un error
            }
        } catch (Exception $e) {
            error_log("Error en modificar_producto: " . $e->getMessage());
            return false;
        }
    }

    // Eliminar producto físicamente
    public function eliminar_producto($id_inventario) {
        $stmt = $this->conn->prepare("DELETE FROM inventario WHERE id_inventario = ?");
        if (!$stmt) {
            error_log("Error preparando eliminar_producto: " . $this->conn->error);
            return false;
        }
        
        $stmt->bind_param("i", $id_inventario);
        
        if ($stmt->execute()) {
            error_log("Producto eliminado correctamente. ID: " . $id_inventario);
            return true;
        } else {
            error_log("Error ejecutando eliminar_producto: " . $stmt->error);
            return false;
        }
    }

    // Buscar producto por nombre
    public function buscar_producto($termino) {
        $stmt = $this->conn->prepare("SELECT i.*, p.nombre_proveedor 
                                      FROM inventario i
                                      INNER JOIN proveedores p ON p.id_proveedor = i.id_proveedor
                                      WHERE i.nombre_producto LIKE ?");
        if (!$stmt) {
            error_log("Error preparando buscar_producto: " . $this->conn->error);
            return false;
        }
        
        $busqueda = "%$termino%";
        $stmt->bind_param("s", $busqueda);
        
        if (!$stmt->execute()) {
            error_log("Error ejecutando buscar_producto: " . $stmt->error);
            return false;
        }
        
        return $stmt->get_result();
    }
}
?>
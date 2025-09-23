// app/controladores/controladores_inventario/controlador_inventario.js
import Producto from "../../../modelo/modelo_adm/inicio/modelo_inicio";

// ✅ CORREGIDO - usa el mismo nombre en definición y uso
const API_URL = "http://192.168.100.8/proyecto_final_7/app_movil/app_proyecto_7/api/router.php";

// Función para obtener todos los productos del inventario
export async function getInventario() {
  try {
    // ✅ CORREGIDO - usa API_URL en lugar de API_BASE_URL
    const response = await fetch(`${API_URL}?route=inicio`);
    
    if (!response.ok) {
      throw new Error("Error al obtener el inventario: " + response.status);
    }
    
    const data = await response.json();
    
    // Validación adicional para debugging
    console.log("Datos recibidos de la API:", data);
    
    if (!Array.isArray(data)) {
      console.error("La API no devolvió un array:", data);
      throw new Error("Formato de datos inválido del servidor");
    }
    
    // Mapea los datos crudos a instancias de la clase Producto
    return data.map(item => new Producto(
      item.id_inventario,
      item.nombre_producto,
      item.stock,
      item.vencimiento,
      item.precio_producto,
      item.precio_venta,
      item.imagen_producto,
      item.nombre_proveedor
    ));
  } catch (error) {
    console.error("Error al obtener el inventario:", error);
    throw new Error("No se pudo conectar con el servidor: " + error.message);
  }
}

// Función para eliminar producto
export async function deleteProducto(id) {
  try {
    // ✅ CORREGIDO - usa API_URL en lugar de API_BASE_URL
    const response = await fetch(`${API_URL}?route=inicio&id=${id}`, {
      method: 'DELETE'
    });
    
    if (!response.ok) {
      throw new Error("Error al eliminar el producto: " + response.status);
    }
    
    const result = await response.json();
    
    if (result.error) {
      throw new Error(result.error);
    }
    
    return result;
  } catch (error) {
    console.error("Error al eliminar producto:", error);
    throw new Error("No se pudo eliminar el producto: " + error.message);
  }
}
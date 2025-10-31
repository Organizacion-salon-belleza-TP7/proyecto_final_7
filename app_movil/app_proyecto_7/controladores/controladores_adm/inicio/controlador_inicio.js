// app/controladores/controladores_adm/inicio/controlador_inicio.js
import Producto from "../../../modelo/modelo_adm/inicio/modelo_inicio";

const API_URL = "http://192.168.100.8/proyecto_final_7/app_movil/app_proyecto_7/api/router.php";

// OBTENER INVENTARIO (ya funciona)
export async function getInventario() {
  try {
    const response = await fetch(`${API_URL}?route=inicio`);
    
    if (!response.ok) {
      throw new Error("Error al obtener el inventario: " + response.status);
    }
    
    const data = await response.json();
    
    if (!Array.isArray(data)) {
      throw new Error("Formato de datos inválido del servidor");
    }
    
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

// ELIMINAR PRODUCTO (ya funciona)
export async function deleteProducto(id) {
  try {
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

// OBTENER UN PRODUCTO POR ID (para modificar)
export async function getProductoById(id) {
  try {
    const response = await fetch(`${API_URL}?route=inicio&id=${id}`);
    
    if (!response.ok) {
      throw new Error("Error al obtener el producto: " + response.status);
    }
    
    const data = await response.json();
    
    if (!Array.isArray(data) || data.length === 0) {
      throw new Error("Producto no encontrado");
    }
    
    const item = data[0];
    return new Producto(
      item.id_inventario,
      item.nombre_producto,
      item.stock,
      item.vencimiento,
      item.precio_producto,
      item.precio_venta,
      item.imagen_producto,
      item.nombre_proveedor
    );
  } catch (error) {
    console.error("Error al obtener producto:", error);
    throw new Error("No se pudo obtener el producto: " + error.message);
  }
}

// AGREGAR NUEVO PRODUCTO
export async function agregarProducto(productoData) {
  try {
    const response = await fetch(`${API_URL}?route=inicio`, {
      method: 'POST',
      body: productoData // 'productoData' ahora es un objeto FormData
    });
    
    if (!response.ok) {
      throw new Error("Error al agregar el producto: " + response.status);
    }
    
    const result = await response.json();
    
    if (result.error) {
      throw new Error(result.error);
    }
    
    return result;
  } catch (error) {
    console.error("Error al agregar producto:", error);
    throw new Error("No se pudo agregar el producto: " + error.message);
  }
}

// MODIFICAR PRODUCTO EXISTENTE
export async function modificarProducto(id, productoData) {
  try {
    const response = await fetch(`${API_URL}?route=inicio`, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        id_inventario: id,
        ...productoData
      })
    });
    
    if (!response.ok) {
      throw new Error("Error al modificar el producto: " + response.status);
    }
    
    const result = await response.json();
    
    if (result.error) {
      throw new Error(result.error);
    }
    
    return result;
  } catch (error) {
    console.error("Error al modificar producto:", error);
    throw new Error("No se pudo modificar el producto: " + error.message);
  }
}

// ✅ OBTENER PROVEEDORES
// Deja solo esta versión de la función
export async function getProveedores() {
  try {
    const response = await fetch(`${API_URL}?route=proveedores`);
    
    if (!response.ok) {
      throw new Error("Error al obtener proveedores: " + response.status);
    }
    
    const data = await response.json();
    
    if (!Array.isArray(data)) {
      throw new Error("Formato de datos de proveedores inválido del servidor");
    }
    
    return data;
  } catch (error) {
    console.error("Error al obtener proveedores:", error);
    throw new Error("No se pudieron obtener los proveedores: " + error.message);
  }
}
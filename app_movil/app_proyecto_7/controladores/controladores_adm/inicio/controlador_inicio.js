// app/controladores/controladores_adm/inicio/controlador_inicio.js
import Producto from "../../../modelo/modelo_adm/inicio/modelo_inicio";

const API_URL = "http://10.253.89.87/proyecto_final_7/app_movil/app_proyecto_7/api/router.php";

// OBTENER INVENTARIO
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
      item.nombre_proveedor,
      item.id_proveedor
    ));
  } catch (error) {
    console.error("Error al obtener el inventario:", error);
    throw new Error("No se pudo conectar con el servidor: " + error.message);
  }
}

// ELIMINAR PRODUCTO
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

// OBTENER UN PRODUCTO POR ID
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
      item.nombre_proveedor,
      item.id_proveedor
    );
  } catch (error) {
    console.error("Error al obtener producto:", error);
    throw new Error("No se pudo obtener el producto: " + error.message);
  }
}

// AGREGAR NUEVO PRODUCTO
export async function agregarProducto(productoData) {
  try {
    const esFormData = productoData instanceof FormData;
    
    const config = {
      method: 'POST',
    };

    if (esFormData) {
      config.body = productoData;
    } else {
      config.headers = {
        'Content-Type': 'application/json',
      };
      config.body = JSON.stringify(productoData);
    }

    const response = await fetch(`${API_URL}?route=inicio`, config);
    
    if (!response.ok) {
      const errorText = await response.text();
      console.error('Error response:', errorText);
      throw new Error(`Error al agregar el producto: ${response.status}`);
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

// MODIFICAR PRODUCTO EXISTENTE (SOLO JSON)
export async function modificarProducto(id, productoData) {
  try {
    const datosEnvio = {
      id_inventario: parseInt(id),
      nombre_producto: productoData.nombre_producto,
      stock: parseInt(productoData.stock) || 0,
      vencimiento: productoData.vencimiento || '',
      precio_producto: parseFloat(productoData.precio_producto) || 0,
      precio_venta: parseFloat(productoData.precio_venta),
      imagen_producto: productoData.imagen_producto || '',
      id_proveedor: productoData.id_proveedor ? parseInt(productoData.id_proveedor) : 0
    };

    console.log('Enviando modificación (JSON):', datosEnvio);

    const response = await fetch(`${API_URL}?route=inicio`, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(datosEnvio)
    });
    
    if (!response.ok) {
      const errorText = await response.text();
      console.error('Error response:', errorText);
      
      try {
        const errorJson = JSON.parse(errorText);
        throw new Error(errorJson.error || `Error al modificar el producto: ${response.status}`);
      } catch (parseError) {
        throw new Error(`Error al modificar el producto: ${response.status} - ${errorText}`);
      }
    }
    
    const result = await response.json();
    console.log('Resultado modificación:', result);
    
    if (result.error) {
      throw new Error(result.error);
    }
    
    return result;
  } catch (error) {
    console.error("Error al modificar producto:", error);
    throw new Error("No se pudo modificar el producto: " + error.message);
  }
}

// MODIFICAR PRODUCTO CON IMAGEN (FORM DATA) - VERSIÓN CORREGIDA COMPLETA
export async function modificarProductoConImagen(id, productoData, imagen) {
  try {
    console.log('=== 🚀 INICIANDO MODIFICACIÓN CON IMAGEN ===');
    
    const formData = new FormData();
    
    // ✅✅✅ AGREGAR TODOS LOS CAMPOS DE TEXTO ✅✅✅
    console.log('📝 Agregando campos de texto al FormData...');
    
    formData.append('id_inventario', id.toString());
    console.log('✅ Campo agregado: id_inventario =', id.toString());
    
    formData.append('nombre_producto', productoData.nombre_producto.toString());
    console.log('✅ Campo agregado: nombre_producto =', productoData.nombre_producto.toString());
    
    formData.append('stock', productoData.stock.toString());
    console.log('✅ Campo agregado: stock =', productoData.stock.toString());
    
    formData.append('vencimiento', (productoData.vencimiento || '').toString());
    console.log('✅ Campo agregado: vencimiento =', productoData.vencimiento || '');
    
    formData.append('precio_producto', (productoData.precio_producto || '0').toString());
    console.log('✅ Campo agregado: precio_producto =', productoData.precio_producto || '0');
    
    formData.append('precio_venta', productoData.precio_venta.toString());
    console.log('✅ Campo agregado: precio_venta =', productoData.precio_venta.toString());
    
    formData.append('id_proveedor', (productoData.id_proveedor || '').toString());
    console.log('✅ Campo agregado: id_proveedor =', productoData.id_proveedor || '');
    
    // ✅✅✅ AGREGAR IMAGEN SI EXISTE ✅✅✅
    if (imagen) {
      console.log('🖼️ Agregando imagen al FormData...');
      formData.append('imagen', {
        uri: imagen.uri,
        name: imagen.fileName || `producto_${id}_${Date.now()}.jpg`,
        type: 'image/jpeg'
      });
      console.log('✅ Imagen agregada:', imagen.fileName);
    } else {
      console.log('ℹ️ No hay imagen nueva para agregar');
    }

    console.log('=== ✅ FORM DATA COMPLETADO ===');
    console.log(`📊 Total campos: 7 campos de texto ${imagen ? '+ 1 imagen' : '(sin imagen nueva)'}`);
    console.log('🌐 Enviando solicitud al servidor...');
    
    // ✅✅✅ ENVIAR CON FETCH ✅✅✅
    const response = await fetch(`${API_URL}?route=inicio`, {
      method: 'POST',
      body: formData,
      // ❌ NO establecer headers - React Native lo hace automáticamente con boundary
    });

    console.log('📡 Status de respuesta:', response.status);
    console.log('✅ OK:', response.ok);
    
    const responseText = await response.text();
    console.log('📨 Respuesta del servidor:', responseText);
    
    if (!response.ok) {
      console.error('❌ Error del servidor:', responseText);
      
      try {
        const errorData = JSON.parse(responseText);
        throw new Error(errorData.error || `Error ${response.status} del servidor`);
      } catch (e) {
        console.error('❌ Error parseando respuesta de error:', e);
        throw new Error(`Error ${response.status}: ${responseText}`);
      }
    }
    
    const result = JSON.parse(responseText);
    console.log('🎉 Modificación exitosa:', result);
    
    if (result.error) {
      throw new Error(result.error);
    }
    
    return result;
    
  } catch (error) {
    console.error("💥 Error completo en modificarProductoConImagen:", error);
    throw new Error("No se pudo modificar el producto: " + error.message);
  }
}

// AGREGAR PRODUCTO CON IMAGEN
export async function agregarProductoConImagen(productoData, imagen) {
  try {
    const formData = new FormData();
    
    formData.append('nombre_producto', productoData.nombre_producto.toString());
    formData.append('stock', productoData.stock.toString());
    formData.append('vencimiento', (productoData.vencimiento || '').toString());
    formData.append('precio_producto', (productoData.precio_producto || '0').toString());
    formData.append('precio_venta', productoData.precio_venta.toString());
    formData.append('id_proveedor', (productoData.id_proveedor || '').toString());
    
    if (imagen) {
      formData.append('imagen', {
        uri: imagen.uri,
        name: imagen.fileName || `producto_${Date.now()}.jpg`,
        type: 'image/jpeg'
      });
    }

    console.log('Enviando nuevo producto con imagen');

    const response = await fetch(`${API_URL}?route=inicio`, {
      method: 'POST',
      body: formData,
    });
    
    if (!response.ok) {
      const errorText = await response.text();
      console.error('Error response:', errorText);
      throw new Error(`Error al agregar el producto: ${response.status}`);
    }
    
    const result = await response.json();
    console.log('Resultado agregar producto:', result);
    
    if (result.error) {
      throw new Error(result.error);
    }
    
    return result;
  } catch (error) {
    console.error("Error al agregar producto con imagen:", error);
    throw new Error("No se pudo agregar el producto: " + error.message);
  }
}

// OBTENER PROVEEDORES
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

// FUNCIÓN PARA OBTENER LA URL DE LA IMAGEN
export function getUrlImagen(nombreImagen) {
  if (!nombreImagen) return null;
  return `http://10.253.89.87/proyecto_final_7/imagenes/inventario/${nombreImagen}`;
}

// VALIDAR DATOS DEL PRODUCTO
export function validarProducto(productoData) {
  const errores = [];
  
  if (!productoData.nombre_producto || productoData.nombre_producto.trim() === '') {
    errores.push('El nombre del producto es obligatorio');
  }
  
  if (!productoData.stock || isNaN(productoData.stock) || parseInt(productoData.stock) < 0) {
    errores.push('El stock debe ser un número válido');
  }
  
  if (!productoData.precio_venta || isNaN(productoData.precio_venta) || parseFloat(productoData.precio_venta) <= 0) {
    errores.push('El precio de venta debe ser un número mayor a 0');
  }
  
  return errores;
}

// FUNCIÓN PARA PROBAR CONEXIÓN BÁSICA
export async function probarConexion() {
  try {
    console.log('=== 🔍 PROBANDO CONEXIÓN BÁSICA ===');
    
    const response = await fetch(`${API_URL}?route=inicio`, {
      method: 'GET',
    });

    console.log('📡 Conexión básica - Status:', response.status);
    
    if (response.ok) {
      const data = await response.json();
      console.log('✅ Conexión exitosa, datos recibidos:', data.length, 'productos');
      return true;
    } else {
      console.error('❌ Conexión fallida:', response.status);
      return false;
    }
  } catch (error) {
    console.error('💥 Error de conexión:', error.message);
    return false;
  }
}
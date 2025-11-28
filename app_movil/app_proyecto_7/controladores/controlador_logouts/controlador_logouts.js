// controladores/controlador_logouts/controlador_logouts.js
import AsyncStorage from '@react-native-async-storage/async-storage';
import LogoutService from '../../services/logoutService'; // 👈 IMPORTAR EL SERVICIO

/**
 * Cierra la sesión del usuario eliminando los datos locales Y notificando al servidor
 */
export const cerrarSesion = async () => {
  try {
    console.log('🔄 Cerrando sesión...');

    // 1. Obtener datos del usuario ANTES de limpiar
    const usuarioData = await AsyncStorage.getItem("usuarioLogueado");
    let id_usuario = null;
    let nombre_usuario = '';

    if (usuarioData) {
      const usuario = JSON.parse(usuarioData);
      id_usuario = usuario.id_usuario;
      nombre_usuario = usuario.nombre_usuario || '';

      // 2. NOTIFICAR AL SERVIDOR para actualizar la BD
      try {
        console.log('📡 Notificando servidor del logout...');
        const resultadoServidor = await LogoutService.cerrarSesion(id_usuario);
        console.log('✅ Servidor notificado:', resultadoServidor);
      } catch (serverError) {
        console.warn('⚠️ No se pudo notificar al servidor:', serverError);
        // Continuamos para limpiar localmente aunque falle el servidor
      }
    }

    // 3. Limpiar datos locales
    await AsyncStorage.multiRemove([
      'usuarioLogueado',
      'userToken', 
      'sessionData',
      'id_usuario',
      'nombre_usuario'
    ]);
    
    console.log('✅ Sesión cerrada exitosamente');
    return {
      success: true,
      message: 'Sesión cerrada correctamente',
      id_usuario: id_usuario,
      timestamp: new Date().toISOString()
    };
    
  } catch (error) {
    console.error('❌ Error al cerrar sesión:', error);
    
    // Intentar limpiar lo básico aunque falle multiRemove
    try {
      await AsyncStorage.removeItem('usuarioLogueado');
    } catch (fallbackError) {
      console.error('❌ Error crítico al limpiar sesión:', fallbackError);
    }
    
    throw new Error('No se pudo cerrar la sesión');
  }
};

// ... (el resto de tus funciones se mantienen igual)
export const verificarSesion = async () => {
  try {
    const usuarioData = await AsyncStorage.getItem('usuarioLogueado');
    
    if (!usuarioData) {
      return {
        isLoggedIn: false,
        usuario: null,
        message: 'No hay sesión activa'
      };
    }
    
    const usuario = JSON.parse(usuarioData);
    return {
      isLoggedIn: true,
      usuario: usuario,
      message: 'Sesión activa'
    };
    
  } catch (error) {
    console.error('❌ Error al verificar sesión:', error);
    return {
      isLoggedIn: false,
      usuario: null,
      message: 'Error al verificar sesión'
    };
  }
};

export const obtenerUsuarioActual = async () => {
  try {
    const usuarioData = await AsyncStorage.getItem('usuarioLogueado');
    return usuarioData ? JSON.parse(usuarioData) : null;
  } catch (error) {
    console.error('❌ Error al obtener usuario:', error);
    return null;
  }
};

export const limpiarDataCompleta = async () => {
  try {
    const todasLasKeys = await AsyncStorage.getAllKeys();
    
    const keysAEliminar = todasLasKeys.filter(key => 
      key.includes('usuario') ||
      key.includes('user') ||
      key.includes('session') ||
      key.includes('token') ||
      key.includes('cita') ||
      key.includes('config')
    );
    
    if (keysAEliminar.length > 0) {
      await AsyncStorage.multiRemove(keysAEliminar);
      console.log(`🗑️ Eliminadas ${keysAEliminar.length} keys de almacenamiento`);
    }
    
    return {
      success: true,
      message: 'Data limpiada completamente',
      keysEliminadas: keysAEliminar
    };
    
  } catch (error) {
    console.error('❌ Error al limpiar data completa:', error);
    throw new Error('Error al limpiar datos de la aplicación');
  }
};
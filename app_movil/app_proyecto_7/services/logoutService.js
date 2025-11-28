// app/services/logoutService.js
import Logout from '../modelo/modelo_logout/modelo_logout';

const API_URL = "http://192.168.100.8/proyecto_final_7/app_movil/app_proyecto_7/api/router.php";

export default class LogoutService {
  /**
   * Notifica al servidor que el usuario cerró sesión
   */
  static async cerrarSesion(id_usuario) {
    try {
      const response = await fetch(`${API_URL}/index.php?route=logouts`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          id_usuario: id_usuario,
          accion: 'cerrar_sesion'
        })
      });

      const data = await response.json();

      if (!response.ok) {
        throw new Error(data.message || 'Error del servidor');
      }

      // Retornar instancia del modelo Logout
      return new Logout(
        id_usuario,
        data.fecha_logout || new Date().toISOString(),
        data.nombre_usuario || ''
      );

    } catch (error) {
      console.error('❌ Error en LogoutService:', error);
      throw error;
    }
  }

  /**
   * Método alternativo con GET
   */
  static async cerrarSesionGET(id_usuario) {
    try {
      const response = await fetch(
        `${API_URL}/index.php?route=logouts&id_usuario=${id_usuario}&accion=cerrar_sesion`
      );
      
      const data = await response.json();
      
      if (!response.ok) {
        throw new Error(data.message || 'Error del servidor');
      }

      return new Logout(
        id_usuario,
        data.fecha_logout || new Date().toISOString(),
        data.nombre_usuario || ''
      );

    } catch (error) {
      console.error('❌ Error en LogoutService (GET):', error);
      throw error;
    }
  }
}
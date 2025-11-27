// app/services/authService.js
import AsyncStorage from '@react-native-async-storage/async-storage';

const AUTH_KEYS = {
  USER_ID: 'user_id',
  USER_DATA: 'user_data',
  USER_TYPE: 'user_type',
  IS_LOGGED_IN: 'is_logged_in'
};

export const authService = {
  // Guardar datos de sesión
  async login(userData) {
    try {
      await AsyncStorage.multiSet([
        [AUTH_KEYS.USER_ID, userData.id_usuario.toString()],
        [AUTH_KEYS.USER_DATA, JSON.stringify(userData)],
        [AUTH_KEYS.USER_TYPE, userData.tipo],
        [AUTH_KEYS.IS_LOGGED_IN, 'true']
      ]);
      return true;
    } catch (error) {
      console.error('Error guardando sesión:', error);
      return false;
    }
  },

  // Cerrar sesión
  async logout() {
    try {
      await AsyncStorage.multiRemove([
        AUTH_KEYS.USER_ID,
        AUTH_KEYS.USER_DATA,
        AUTH_KEYS.USER_TYPE,
        AUTH_KEYS.IS_LOGGED_IN
      ]);
      return true;
    } catch (error) {
      console.error('Error cerrando sesión:', error);
      return false;
    }
  },

  // Obtener ID del usuario (equivalente a $_SESSION['user'])
  async getUserId() {
    try {
      return await AsyncStorage.getItem(AUTH_KEYS.USER_ID);
    } catch (error) {
      console.error('Error obteniendo user ID:', error);
      return null;
    }
  },

  // Obtener datos completos del usuario
  async getUserData() {
    try {
      const userData = await AsyncStorage.getItem(AUTH_KEYS.USER_DATA);
      return userData ? JSON.parse(userData) : null;
    } catch (error) {
      console.error('Error obteniendo user data:', error);
      return null;
    }
  },

  // Verificar si está logueado
  async isLoggedIn() {
    try {
      const isLoggedIn = await AsyncStorage.getItem(AUTH_KEYS.IS_LOGGED_IN);
      return isLoggedIn === 'true';
    } catch (error) {
      console.error('Error verificando sesión:', error);
      return false;
    }
  },

  // Obtener tipo de usuario
  async getUserType() {
    try {
      return await AsyncStorage.getItem(AUTH_KEYS.USER_TYPE);
    } catch (error) {
      console.error('Error obteniendo user type:', error);
      return null;
    }
  }
};
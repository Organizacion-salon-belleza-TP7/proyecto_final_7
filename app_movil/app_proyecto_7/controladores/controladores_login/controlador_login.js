// app/controladores/controladores_login/controlador_login.js
import User from "../../modelo/modelo_login/modelo_login.js";
import { authService } from "../../modelo/modelo_sesiones/authService.js"; // ✅ NUEVO

const API_URL = "http://10.0.2.206/proyecto_final_7/app_movil/app_proyecto_7/api/router.php?route=login";

export async function login(nombre_usuario, contrasena) {
  const response = await fetch(API_URL, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ nombre_usuario, contrasena }),
  });

  if (!response.ok) {
    throw new Error("Error en la petición: " + response.status);
  }

  const data = await response.json();

  if (data.status === "success") {
    const user = new User(
      data.usuario.id_usuario,
      data.usuario.nombre_usuario,
      data.tipo,
      data.relacion
    );

    // ✅ GUARDAR EN "SESSION"
    const sessionSaved = await authService.login({
      id_usuario: data.usuario.id_usuario,
      nombre_usuario: data.usuario.nombre_usuario,
      tipo: data.tipo,
      relacion: data.relacion
    });

    if (!sessionSaved) {
      throw new Error("Error al guardar la sesión");
    }

    return {
      success: true,
      user,
      tipo: data.tipo,
    };
  } else {
    return {
      success: false,
      message: data.message,
    };
  }
}

// ✅ NUEVA FUNCIÓN: Verificar sesión existente
export async function checkExistingSession() {
  return await authService.isLoggedIn();
}

// ✅ NUEVA FUNCIÓN: Obtener datos de sesión
export async function getSessionData() {
  return await authService.getUserData();
}
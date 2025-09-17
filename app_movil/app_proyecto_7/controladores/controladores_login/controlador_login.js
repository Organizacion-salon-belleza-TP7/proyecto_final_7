// app/controllers/loginController.js
import User from "../../modelo/modelo_login/modelo_login.js";

const API_URL = "http://192.168.100.8/proyecto_final_7/app_movil/app_proyecto_7/api/router.php?route=login";

export async function login(nombre_usuario, contrasena) {
  try {
    const response = await fetch(API_URL, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ nombre_usuario, contrasena }),
    });

    const data = await response.json();

    if (data.status === "success") {
      return new User(
        data.usuario.id_usuario,
        data.usuario.nombre_usuario,
        data.tipo,
        data.relacion
      );
    } else {
      throw new Error(data.message);
    }
  } catch (error) {
    throw new Error("Error en el login: " + error.message);
  }
}

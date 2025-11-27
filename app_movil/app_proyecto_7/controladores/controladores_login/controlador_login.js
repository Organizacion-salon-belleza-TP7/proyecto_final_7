// app/controladores/controladores_login/controlador_login.js
import User from "../../modelo/modelo_login/modelo_login.js";

const API_URL =
  "http://192.168.100.8/proyecto_final_7/app_movil/app_proyecto_7/api/router.php?route=login";

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

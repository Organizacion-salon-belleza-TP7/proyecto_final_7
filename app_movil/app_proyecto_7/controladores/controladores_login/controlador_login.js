import User from "../../modelo/modelo_login/modelo_login.js";

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
    return new User(
      data.usuario.id_usuario,
      data.usuario.nombre_usuario,
      data.tipo,
      data.relacion
    );
  } else {
    throw new Error(data.message);
  }
}

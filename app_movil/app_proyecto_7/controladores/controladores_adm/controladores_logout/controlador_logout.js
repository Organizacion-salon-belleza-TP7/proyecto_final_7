const API_URL = "http://192.168.0.20/proyecto_final_7/app_movil/app_proyecto_7/api/router.php";

export async function cerrarSesion(id_usuario) {
  try {
    const response = await fetch(`${API_URL}?route=logouts`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ id_usuario }),
    });

    return await response.json();
  } catch (error) {
    console.error("Error cerrando sesión:", error);
    return { error: "Error de conexión" };
  }
}

export async function obtenerLogueos() {
  try {
    const response = await fetch(`${API_URL}?route=logouts`);
    return await response.json();
  } catch (error) {
    console.error("Error obteniendo logueos:", error);
    return [];
  }
}

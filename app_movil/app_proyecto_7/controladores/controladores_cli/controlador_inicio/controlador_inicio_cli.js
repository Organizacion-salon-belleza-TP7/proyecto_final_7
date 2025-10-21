// app/controladores/controladores_cli/controlador_inicio/controlador_inicio_cli.js
import Cita from "../../../modelo/modelo_cli/modelo_inicio/modelo_inicio_cli";

export async function traerCitasCompradas(id_usuario) {
  const API_URL = `http://10.0.2.206/proyecto_final_7/app_movil/app_proyecto_7/api/router.php?route=client_interface&accion=ver_citas_compradas&id_usuario=${id_usuario}`;

  try {
    const response = await fetch(API_URL);

    if (!response.ok) {
      throw new Error("Error al conectar con el servidor: " + response.status);
    }

    const data = await response.json();

    if (data.success) {
      return data.citas.map(
        (c) =>
          new Cita(
            c.id_cita,
            c.nombre,
            c.nombre_usuario,
            c.fecha_cita,
            c.activo,
            c.nombre_lugar,
            c.id_caja
          )
      );
    } else {
      throw new Error(data.message || "No se pudieron obtener las citas");
    }
  } catch (error) {
    throw new Error("Error al obtener citas: " + error.message);
  }
}

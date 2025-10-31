// app/controladores/controladores_cli/controlador_inicio/controlador_inicio_cli.js
import Cita from "../../../modelo/modelo_cli/modelo_inicio/modelo_inicio_cli";

// 📌 URL base de tu API
const BASE_URL = "http://192.168.100.8/proyecto_final_7/app_movil/app_proyecto_7/api/router.php";

/**
 * Trae todas las citas compradas de un usuario
 * @param {number} id_usuario
 * @returns {Promise<Cita[]>}
 */
export async function traerCitasCompradas(id_usuario) {
  const API_URL = `${BASE_URL}?route=client_interface&accion=ver_citas_compradas&id_usuario=${id_usuario}`;

  try {
    const response = await fetch(API_URL);
    if (!response.ok) throw new Error("Error al conectar con el servidor: " + response.status);

    const data = await response.json();

    if (data.success) {
      // Transformar los datos en objetos del modelo Cita
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

/**
 * Cancela una cita y procesa el reembolso
 * @param {number} id_caja
 * @param {number} id_cita
 * @param {number} id_usuario
 * @returns {Promise<Object>} Datos del reembolso
 */
export async function cancelarCita(id_caja, id_cita, id_usuario) {
  const API_URL = `${BASE_URL}?route=client_interface&reembolsar_cita=vista_inicio_cli&id_caja=${id_caja}&id_cita=${id_cita}&id_usuario=${id_usuario}`;

  try {
    const response = await fetch(API_URL);
    if (!response.ok) {
      throw new Error("Error al conectar con el servidor: " + response.status);
    }

    const data = await response.json();

    if (data.success) {
      return data.data; // retorna el objeto con reembolso, precio_total, etc.
    } else {
      throw new Error(data.error || "Error al cancelar la cita");
    }
  } catch (error) {
    throw new Error("Error al cancelar cita: " + error.message);
  }
}
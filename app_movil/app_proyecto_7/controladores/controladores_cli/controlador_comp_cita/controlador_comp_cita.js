import Cita from "../../../modelo/modelo_cli/modelo_comp_cita/modelo_comp_cita";

// 📌 URL base de la API
const BASE_URL = "http://10.0.2.206/proyecto_final_7/app_movil/app_proyecto_7/api/router.php";

/**
 * 📅 Obtener todos los servicios activos
 * @returns {Promise<Array>}
 */
export async function traerServicios() {
  const API_URL = `${BASE_URL}?route=client_interface&accion=obtener_servicios`;
  try {
    const response = await fetch(API_URL);
    if (!response.ok) throw new Error("Error al conectar con el servidor: " + response.status);

    const data = await response.json();
    if (data.success) {
      return data.servicios;
    } else {
      throw new Error(data.message || "No se pudieron obtener los servicios");
    }
  } catch (error) {
    throw new Error("Error al obtener servicios: " + error.message);
  }
}

/**
 * 🎁 Obtener todos los combos activos
 * @returns {Promise<Array>}
 */
export async function traerCombos() {
  const API_URL = `${BASE_URL}?route=client_interface&accion=obtener_combos`;
  try {
    const response = await fetch(API_URL);
    if (!response.ok) throw new Error("Error al conectar con el servidor: " + response.status);

    const data = await response.json();
    if (data.success) {
      return data.combos;
    } else {
      throw new Error(data.message || "No se pudieron obtener los combos");
    }
  } catch (error) {
    throw new Error("Error al obtener combos: " + error.message);
  }
}

/**
 * 📍 Obtener los lugares disponibles
 * @returns {Promise<Array>}
 */
export async function traerLugares() {
  const API_URL = `${BASE_URL}?route=client_interface&accion=obtener_lugares`;
  try {
    const response = await fetch(API_URL);
    if (!response.ok) throw new Error("Error al conectar con el servidor: " + response.status);

    const data = await response.json();
    if (data.success) {
      return data.lugares;
    } else {
      throw new Error(data.message || "No se pudieron obtener los lugares");
    }
  } catch (error) {
    throw new Error("Error al obtener lugares: " + error.message);
  }
}

/**
 * 💾 Guardar una nueva cita
 * @param {number} id_cliente
 * @param {string} fecha_cita (YYYY-MM-DD HH:mm:ss)
 * @param {number} id_lugar
 * @param {Array<number>} servicios
 * @param {Array<number>} combos
 * @returns {Promise<Object>} Datos de la cita creada
 */
export async function guardarCita(id_cliente, fecha_cita, id_lugar, servicios = [], combos = []) {
  const API_URL = `${BASE_URL}?route=client_interface&accion=guardar_cita`;

  const body = {
    id_cliente,
    fecha_cita,
    id_lugar,
    servicios,
    combos
  };

  try {
    const response = await fetch(API_URL, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(body)
    });

    if (!response.ok) throw new Error("Error al conectar con el servidor: " + response.status);

    const data = await response.json();

    if (data.success) {
      return new Cita(data.id_cita, fecha_cita, data.nombre_lugar, 1, servicios, combos);
    } else {
      throw new Error(data.message || "Error al guardar la cita");
    }
  } catch (error) {
    throw new Error("Error al guardar cita: " + error.message);
  }
}

/**
 * 🔍 Obtener los detalles de una cita (servicios + combos)
 * @param {number} id_cita
 * @returns {Promise<Cita>}
 */
export async function traerDetallesCita(id_cita) {
  const API_URL = `${BASE_URL}?route=client_interface&accion=obtener_detalle_cita&id_cita=${id_cita}`;

  try {
    const response = await fetch(API_URL);
    if (!response.ok) throw new Error("Error al conectar con el servidor: " + response.status);

    const data = await response.json();

    if (data.success) {
      return new Cita(
        data.cita.id_cita,
        data.cita.fecha_cita,
        data.cita.nombre_lugar,
        data.cita.activo,
        data.detalles.filter((d) => d.tipo === "servicio"),
        data.detalles.filter((d) => d.tipo === "combo")
      );
    } else {
      throw new Error(data.message || "No se pudo obtener el detalle de la cita");
    }
  } catch (error) {
    throw new Error("Error al obtener detalle de cita: " + error.message);
  }
}

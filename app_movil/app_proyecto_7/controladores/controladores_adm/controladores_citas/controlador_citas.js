import Cita from "../../../modelo/modelo_adm/modelo_citas/modelo_citas";

const API_URL = "http://192.168.100.8/proyecto_final_7/app_movil/app_proyecto_7/api/router.php";

// ✅ LISTAR CITAS
export async function getCitas() {
  try {
    const response = await fetch(`${API_URL}?route=citas`);

    if (!response.ok) {
      throw new Error("Error al obtener las citas: " + response.status);
    }

    const data = await response.json();

    if (!Array.isArray(data)) {
      throw new Error("Formato de datos inválido del servidor");
    }

    return data.map(item => new Cita(
      item.id_cita,
      item.nombre_cliente,
      item.fecha_cita,
      item.activo,
      item.servicios,
      item.combos
    ));
  } catch (error) {
    console.error("Error en getCitas:", error);
    throw new Error("No se pudo obtener las citas: " + error.message);
  }
}

// ✅ DETALLE DE UNA CITA
export async function getCitaById(id) {
  try {
    const response = await fetch(`${API_URL}?route=citas&id=${id}`);

    if (!response.ok) {
      throw new Error("Error al obtener la cita: " + response.status);
    }

    const data = await response.json();

    if (!Array.isArray(data) || data.length === 0) {
      throw new Error("Cita no encontrada");
    }

    return data; // devuelve array con detalle completo
  } catch (error) {
    console.error("Error en getCitaById:", error);
    throw new Error("No se pudo obtener la cita: " + error.message);
  }
}

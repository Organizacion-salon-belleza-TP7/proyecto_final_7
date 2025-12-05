import Cita from "../../../modelo/modelo_cli/modelo_comp_cita/modelo_comp_cita";

// 📌 URL base de la API
const BASE_URL = "http://10.0.2.206/proyecto_final_7/app_movil/app_proyecto_7/api/router.php";

/**
 * 📅 Obtener todos los servicios activos
 * @returns {Promise<Array>}
 */
export async function traerServicios() {
  const API_URL = `${BASE_URL}?route=elegir_cita&tipo=servicios`;
  console.log("🔗 [SERVICIOS] URL llamada:", API_URL);
  
  try {
    const response = await fetch(API_URL);
    console.log("📡 [SERVICIOS] Response status:", response.status);
    
    if (!response.ok) {
      throw new Error(`Error HTTP: ${response.status} - ${response.statusText}`);
    }

    const data = await response.json();
    console.log("📦 [SERVICIOS] Data recibida:", data);
    
    if (data.success && Array.isArray(data.servicios)) {
      console.log(`✅ [SERVICIOS] ${data.servicios.length} servicios obtenidos`);
      return data.servicios;
    } else {
      throw new Error(data.message || "No se pudieron obtener los servicios");
    }
  } catch (error) {
    console.error("💥 [SERVICIOS] Error completo:", error);
    throw new Error("Error al obtener servicios: " + error.message);
  }
}

/**
 * 🎁 Obtener todos los combos activos
 * @returns {Promise<Array>}
 */
export async function traerCombos() {
  const API_URL = `${BASE_URL}?route=elegir_cita&tipo=combos`;
  console.log("🔗 [COMBOS] URL llamada:", API_URL);
  
  try {
    const response = await fetch(API_URL);
    console.log("📡 [COMBOS] Response status:", response.status);
    
    if (!response.ok) {
      throw new Error(`Error HTTP: ${response.status} - ${response.statusText}`);
    }

    const data = await response.json();
    console.log("📦 [COMBOS] Data recibida:", data);
    
    if (data.success && Array.isArray(data.combos)) {
      console.log(`✅ [COMBOS] ${data.combos.length} combos obtenidos`);
      return data.combos;
    } else {
      throw new Error(data.message || "No se pudieron obtener los combos");
    }
  } catch (error) {
    console.error("💥 [COMBOS] Error completo:", error);
    throw new Error("Error al obtener combos: " + error.message);
  }
}

/**
 * 📍 Obtener los lugares disponibles
 * @returns {Promise<Array>}
 */
export async function traerLugares() {
  const API_URL = `${BASE_URL}?route=elegir_cita&tipo=lugares`;
  console.log("🔗 [LUGARES] URL llamada:", API_URL);
  
  try {
    const response = await fetch(API_URL);
    console.log("📡 [LUGARES] Response status:", response.status);
    
    if (!response.ok) {
      throw new Error(`Error HTTP: ${response.status} - ${response.statusText}`);
    }

    const data = await response.json();
    console.log("📦 [LUGARES] Data recibida:", data);
    
    if (data.success && Array.isArray(data.lugares)) {
      console.log(`✅ [LUGARES] ${data.lugares.length} lugares obtenidos`);
      return data.lugares;
    } else {
      throw new Error(data.message || "No se pudieron obtener los lugares");
    }
  } catch (error) {
    console.error("💥 [LUGARES] Error completo:", error);
    throw new Error("Error al obtener lugares: " + error.message);
  }
}

/**
 * 💾 Guardar una nueva cita - VERSIÓN CORREGIDA
 * @param {number} id_cliente
 * @param {string} fecha_cita (YYYY-MM-DD HH:mm:ss)
 * @param {number} id_lugar
 * @param {Array<number>} servicios
 * @param {Array<number>} combos
 * @returns {Promise<Object>} Datos de la cita creada
 */
export async function guardarCita(id_cliente, fecha_cita, id_lugar, servicios = [], combos = []) {
  // ✅ CORREGIDO: Usar la ruta correcta 'elegir_cita'
  const API_URL = `${BASE_URL}?route=elegir_cita`;
  
  console.log("💾 [GUARDAR CITA] Preparando datos...");
  console.log("📝 ID Cliente:", id_cliente);
  console.log("📝 Fecha:", fecha_cita);
  console.log("📝 Lugar ID:", id_lugar);
  console.log("📝 Servicios:", servicios);
  console.log("📝 Combos:", combos);

  const body = {
    id_cliente: parseInt(id_cliente),
    fecha_cita,
    id_lugar: parseInt(id_lugar),
    servicios: servicios.map(s => parseInt(s)),
    combos: combos.map(c => parseInt(c))
  };

  console.log("📤 [GUARDAR CITA] Body enviado:", body);
  console.log("🔗 [GUARDAR CITA] URL:", API_URL);

  try {
    const response = await fetch(API_URL, {
      method: "POST",
      headers: { 
        "Content-Type": "application/json",
        "Accept": "application/json"
      },
      body: JSON.stringify(body)
    });

    console.log("📡 [GUARDAR CITA] Response status:", response.status);
    
    // ✅ MEJORADO: Obtener texto primero para mejor debug
    const responseText = await response.text();
    console.log("📦 [GUARDAR CITA] Response text:", responseText);
    
    let data;
    try {
      data = JSON.parse(responseText);
    } catch (parseError) {
      console.error("❌ [GUARDAR CITA] Error parseando JSON:", parseError);
      throw new Error("Respuesta del servidor no es JSON válido: " + responseText);
    }

    console.log("📦 [GUARDAR CITA] Data parseada:", data);

    if (!response.ok) {
      throw new Error(data.message || data.error || `Error HTTP: ${response.status}`);
    }

    if (data.success) {
      console.log("✅ [GUARDAR CITA] Cita guardada exitosamente, ID:", data.id_cita);
      
      // Crear instancia de Cita con los datos necesarios
      return new Cita(
        data.id_cita,
        id_cliente,
        fecha_cita,
        1, // activo
        id_lugar,
        "", // nombre_lugar - se puede obtener después
        servicios,
        combos
      );
    } else {
      // ✅ MEJORADO: Mostrar mensaje de error específico del servidor
      throw new Error(data.message || data.error || "Error al guardar la cita");
    }
  } catch (error) {
    console.error("💥 [GUARDAR CITA] Error completo:", error);
    throw new Error("Error al guardar cita: " + error.message);
  }
}

/**
 * 🔍 Obtener los detalles de una cita (servicios + combos)
 * @param {number} id_cita
 * @returns {Promise<Cita>}
 */
export async function traerDetallesCita(id_cita) {
  const API_URL = `${BASE_URL}?route=elegir_cita&tipo=detalles_cita&id_cita=${id_cita}`;
  console.log("🔗 [DETALLES CITA] URL llamada:", API_URL);

  try {
    const response = await fetch(API_URL);
    console.log("📡 [DETALLES CITA] Response status:", response.status);
    
    if (!response.ok) {
      throw new Error(`Error HTTP: ${response.status} - ${response.statusText}`);
    }

    const data = await response.json();
    console.log("📦 [DETALLES CITA] Data recibida:", data);

    if (data.success) {
      console.log("✅ [DETALLES CITA] Detalles obtenidos exitosamente");
      
      const servicios = data.detalles.filter((d) => d.tipo === "servicio");
      const combos = data.detalles.filter((d) => d.tipo === "combo");
      
      return new Cita(
        data.cita.id_cita,
        data.cita.id_cliente || null,
        data.cita.fecha_cita,
        data.cita.activo,
        data.cita.id_lugar || null,
        data.cita.nombre_lugar || "",
        servicios,
        combos
      );
    } else {
      throw new Error(data.message || "No se pudo obtener el detalle de la cita");
    }
  } catch (error) {
    console.error("💥 [DETALLES CITA] Error completo:", error);
    throw new Error("Error al obtener detalle de cita: " + error.message);
  }
}

/**
 * 🎯 Función utilitaria para normalizar IDs en la vista
 * @param {Object} item - Item del servicio/combo/lugar
 * @returns {number} ID normalizado
 */
export function obtenerId(item) {
  return item.id_servicios ?? item.id_combos ?? item.id_lugar ?? item.id;
}

/**
 * 🎯 Función utilitaria para normalizar nombres en la vista
 * @param {Object} item - Item del servicio/combo/lugar
 * @returns {string} Nombre normalizado
 */
export function obtenerNombre(item) {
  return item.nombre_servicio ?? item.nombre_combo ?? item.nombre_lugar ?? item.nombre ?? "Sin nombre";
}

/**
 * 🎯 Función utilitaria para normalizar precios en la vista
 * @param {Object} item - Item del servicio/combo
 * @returns {number} Precio normalizado como NÚMERO
 */
export function obtenerPrecio(item) {
  const precio = item.precio_servicio ?? item.precio ?? 0;
  return parseFloat(precio) || 0; // ✅ CORREGIDO: Convertir a número
}

/**
 * 🐛 FUNCIÓN DEBUG TEMPORAL - Para testing
 */
export async function guardarCitaDebug(id_cliente, fecha_cita, id_lugar, servicios = [], combos = []) {
  const API_URL = `${BASE_URL}?route=elegir_cita`;
  
  const body = {
    id_cliente: parseInt(id_cliente),
    fecha_cita,
    id_lugar: parseInt(id_lugar),
    servicios: servicios.map(s => parseInt(s)),
    combos: combos.map(c => parseInt(c)),
    accion: 'guardar_cita'
  };

  console.log("🐛 [DEBUG] Enviando a:", API_URL);
  console.log("🐛 [DEBUG] Body:", body);

  try {
    const response = await fetch(API_URL, {
      method: "POST",
      headers: { 
        "Content-Type": "application/json",
        "Accept": "application/json"
      },
      body: JSON.stringify(body)
    });

    const text = await response.text();
    console.log("🐛 [DEBUG] Respuesta cruda:", text);
    
    try {
      return JSON.parse(text);
    } catch (parseError) {
      console.error("🐛 [DEBUG] Error parseando JSON:", parseError);
      return { success: false, message: "Respuesta no es JSON: " + text };
    }
  } catch (error) {
    console.error("🐛 [DEBUG] Error de red:", error);
    return { success: false, message: "Error de red: " + error.message };
  }
}
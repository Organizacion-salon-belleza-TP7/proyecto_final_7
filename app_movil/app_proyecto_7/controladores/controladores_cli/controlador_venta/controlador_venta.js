// 📌 URL base de la API
const BASE_URL = "http://192.168.100.8/proyecto_final_7/app_movil/app_proyecto_7/api/router.php";

/**
 * 💳 Obtener métodos de pago disponibles
 * @returns {Promise<Array>}
 */
export async function traerMetodosPago() {
  const API_URL = `${BASE_URL}?route=ventas&action=metodos_pago`;
  console.log("🔗 [METODOS PAGO] URL llamada:", API_URL);
  
  try {
    const response = await fetch(API_URL);
    console.log("📡 [METODOS PAGO] Response status:", response.status);
    
    if (!response.ok) {
      throw new Error(`Error HTTP: ${response.status} - ${response.statusText}`);
    }

    const data = await response.json();
    console.log("📦 [METODOS PAGO] Data recibida:", data);
    
    if (data.status === 'success' && Array.isArray(data.data)) {
      console.log(`✅ [METODOS PAGO] ${data.data.length} métodos obtenidos`);
      return data.data;
    } else {
      throw new Error(data.message || "No se pudieron obtener los métodos de pago");
    }
  } catch (error) {
    console.error("💥 [METODOS PAGO] Error completo:", error);
    throw new Error("Error al obtener métodos de pago: " + error.message);
  }
}

/**
 * 📋 Obtener datos de la cita para la venta
 * @param {number} id_cita
 * @returns {Promise<Object>}
 */
export async function traerDatosCita(id_cita) {
  const API_URL = `${BASE_URL}?route=ventas&action=datos_cita&id_cita=${id_cita}`;
  console.log("🔗 [DATOS CITA] URL llamada:", API_URL);
  
  try {
    const response = await fetch(API_URL);
    console.log("📡 [DATOS CITA] Response status:", response.status);
    
    if (!response.ok) {
      throw new Error(`Error HTTP: ${response.status} - ${response.statusText}`);
    }

    const data = await response.json();
    console.log("📦 [DATOS CITA] Data recibida:", data);
    
    if (data.status === 'success' && data.data) {
      console.log("✅ [DATOS CITA] Datos obtenidos exitosamente");
      return data.data;
    } else {
      throw new Error(data.message || "No se pudieron obtener los datos de la cita");
    }
  } catch (error) {
    console.error("💥 [DATOS CITA] Error completo:", error);
    throw new Error("Error al obtener datos de cita: " + error.message);
  }
}

/**
 * 💰 Procesar pago de la cita
 * @param {number} id_cita
 * @param {number} id_metodo
 * @param {number} cantidad
 * @param {number} cantidad_calculada
 * @returns {Promise<Object>}
 */
export async function procesarPago(id_cita, id_metodo, cantidad, cantidad_calculada) {
  const API_URL = `${BASE_URL}?route=ventas`;
  
  console.log("💳 [PROCESAR PAGO] Preparando datos...");
  console.log("📝 ID Cita:", id_cita);
  console.log("📝 Método Pago:", id_metodo);
  console.log("📝 Cantidad:", cantidad);
  console.log("📝 Cantidad Calculada:", cantidad_calculada);

  const body = {
    action: 'procesar_pago',
    id_cita: parseInt(id_cita),
    id_metodo: parseInt(id_metodo),
    cantidad: parseFloat(cantidad),
    cantidad_calculada: parseFloat(cantidad_calculada)
  };

  console.log("📤 [PROCESAR PAGO] Body enviado:", body);
  console.log("🔗 [PROCESAR PAGO] URL:", API_URL);

  try {
    const response = await fetch(API_URL, {
      method: "POST",
      headers: { 
        "Content-Type": "application/json",
        "Accept": "application/json"
      },
      body: JSON.stringify(body)
    });

    console.log("📡 [PROCESAR PAGO] Response status:", response.status);
    
    if (!response.ok) {
      const errorText = await response.text();
      console.error("❌ [PROCESAR PAGO] Error response:", errorText);
      throw new Error(`Error HTTP: ${response.status} - ${response.statusText}`);
    }

    const data = await response.json();
    console.log("📦 [PROCESAR PAGO] Data recibida:", data);

    if (data.status === 'success') {
      console.log("✅ [PROCESAR PAGO] Pago procesado exitosamente");
      return data.data;
    } else {
      throw new Error(data.message || "Error al procesar el pago");
    }
  } catch (error) {
    console.error("💥 [PROCESAR PAGO] Error completo:", error);
    throw new Error("Error al procesar pago: " + error.message);
  }
}

/**
 * ✅ Confirmar cita para pago
 * @param {number} id_cita
 * @returns {Promise<Object>}
 */
export async function confirmarCita(id_cita) {
  const API_URL = `${BASE_URL}?route=ventas`;
  
  const body = {
    action: 'confirmar_cita',
    id_cita: parseInt(id_cita)
  };

  console.log("📤 [CONFIRMAR CITA] Body enviado:", body);

  try {
    const response = await fetch(API_URL, {
      method: "POST",
      headers: { 
        "Content-Type": "application/json",
        "Accept": "application/json"
      },
      body: JSON.stringify(body)
    });

    const data = await response.json();
    console.log("📦 [CONFIRMAR CITA] Data recibida:", data);

    if (data.status === 'success') {
      console.log("✅ [CONFIRMAR CITA] Cita confirmada para pago");
      return data.data;
    } else {
      throw new Error(data.message || "Error al confirmar cita");
    }
  } catch (error) {
    console.error("💥 [CONFIRMAR CITA] Error completo:", error);
    throw new Error("Error al confirmar cita: " + error.message);
  }
}

/**
 * 🎯 Función utilitaria para calcular incrementos/decrementos
 * @param {number} monto
 * @param {Object} metodoPago
 * @returns {number}
 */
export function calcularMontoConAjustes(monto, metodoPago) {
  let montoAjustado = monto;
  
  if (metodoPago.incremento && metodoPago.incremento > 0) {
    const incremento = (monto * metodoPago.incremento) / 100;
    montoAjustado += incremento;
    console.log(`📈 Aplicado incremento del ${metodoPago.incremento}%: +$${incremento.toFixed(2)}`);
  }
  
  if (metodoPago.decremento && metodoPago.decremento > 0) {
    const decremento = (monto * metodoPago.decremento) / 100;
    montoAjustado -= decremento;
    console.log(`📉 Aplicado decremento del ${metodoPago.decremento}%: -$${decremento.toFixed(2)}`);
  }
  
  return montoAjustado;
}

/**
 * 🎯 Función para formatear precios
 * @param {number} precio
 * @returns {string}
 */
export function formatearPrecio(precio) {
  return parseFloat(precio).toFixed(2);
}
import React, { useEffect, useState } from "react";
import {
  View,
  Text,
  ScrollView,
  TouchableOpacity,
  ActivityIndicator,
  Alert,
  StyleSheet,
  Platform,
  Modal,
  FlatList,
} from "react-native";
import { useLocalSearchParams, useRouter } from "expo-router";
import DateTimePicker from '@react-native-community/datetimepicker';

// ✅ IMPORT CORREGIDO
import {
  traerServicios,
  traerCombos,
  traerLugares,
  guardarCita,
  obtenerId,
  obtenerNombre,
  obtenerPrecio,
} from "../../../../controladores/controladores_cli/controlador_comp_cita/controlador_comp_cita";

export default function SeleccionarServiciosScreen() {
  const router = useRouter();
  const { id_cliente } = useLocalSearchParams();

  const [servicios, setServicios] = useState([]);
  const [combos, setCombos] = useState([]);
  const [lugares, setLugares] = useState([]);
  const [seleccionados, setSeleccionados] = useState([]);
  const [idLugar, setIdLugar] = useState(null);
  const [cargando, setCargando] = useState(true);
  
  // Estados para fecha y hora
  const [fechaHora, setFechaHora] = useState(new Date());
  const [mostrarDatePicker, setMostrarDatePicker] = useState(false);
  const [modalHorariosVisible, setModalHorariosVisible] = useState(false);

  // Generar horarios disponibles
  const generarHorariosDisponibles = () => {
    const horarios = [];
    const horaInicio = 8; // 8 AM
    const horaFin = 20;   // 8 PM
    
    for (let hora = horaInicio; hora < horaFin; hora++) {
      for (let minuto = 0; minuto < 60; minuto += 30) {
        const horaFormateada = hora.toString().padStart(2, '0');
        const minutoFormateado = minuto.toString().padStart(2, '0');
        horarios.push({
          hora: `${horaFormateada}:${minutoFormateado}`,
          disponible: Math.random() > 0.2 // Simula disponibilidad (80% disponible)
        });
      }
    }
    
    return horarios;
  };

  const [horariosDisponibles, setHorariosDisponibles] = useState(generarHorariosDisponibles());

  useEffect(() => {
    const cargarDatos = async () => {
      try {
        console.log("🔄 Iniciando carga de datos...");
        
        const [serv, comb, lug] = await Promise.all([
          traerServicios(),
          traerCombos(),
          traerLugares(),
        ]);

        console.log("✅ Datos cargados:");
        console.log("Servicios:", serv);
        console.log("Combos:", comb);
        console.log("Lugares:", lug);

        // Debug: Verificar estructura de precios
        if (serv && serv.length > 0) {
          console.log("🔍 Estructura del primer servicio:", serv[0]);
          console.log("💰 Precio del primer servicio:", serv[0].precio_servicio ?? serv[0].precio);
        }
        if (comb && comb.length > 0) {
          console.log("🔍 Estructura del primer combo:", comb[0]);
          console.log("💰 Precio del primer combo:", comb[0].precio_combo ?? comb[0].precio);
        }

        setServicios(serv || []);
        setCombos(comb || []);
        setLugares(lug || []);
      } catch (error) {
        console.error("❌ Error cargando datos:", error);
        Alert.alert("Error", "No se pudieron cargar los datos: " + error.message);
      } finally {
        setCargando(false);
      }
    };

    cargarDatos();
  }, []);

  const toggleSeleccion = (item, tipo) => {
    const itemId = obtenerId(item);
    const existe = seleccionados.find(
      (s) => s.id === itemId && s.tipo === tipo
    );

    if (existe) {
      setSeleccionados(
        seleccionados.filter(
          (s) => !(s.id === itemId && s.tipo === tipo)
        )
      );
    } else {
      // ✅ CORREGIDO: Convertir precio a número inmediatamente
      const precio = parseFloat(obtenerPrecio(item)) || 0;
      console.log(`➕ Agregando ${tipo}:`, { 
        nombre: obtenerNombre(item), 
        precio: precio,
        precioTipo: typeof precio 
      });
      
      setSeleccionados([...seleccionados, { 
        id: itemId, 
        nombre: obtenerNombre(item),
        precio: precio, // ✅ Ahora es número, no string
        tipo 
      }]);
    }

    // Debug: Verificar seleccionados después del cambio
    setTimeout(() => {
      console.log("🛒 Seleccionados actuales:", seleccionados);
      console.log("🧮 Total calculado:", calcularTotal());
    }, 100);
  };

  const estaSeleccionado = (item, tipo) => {
    const itemId = obtenerId(item);
    return seleccionados.some(s => s.id === itemId && s.tipo === tipo);
  };

  // Manejo de fecha
  const onChangeDate = (event, selectedDate) => {
    setMostrarDatePicker(false);
    if (selectedDate) {
      // Mantener la hora actual, solo cambiar la fecha
      const nuevaFecha = new Date(fechaHora);
      nuevaFecha.setFullYear(selectedDate.getFullYear());
      nuevaFecha.setMonth(selectedDate.getMonth());
      nuevaFecha.setDate(selectedDate.getDate());
      setFechaHora(nuevaFecha);
    }
  };

  // Seleccionar hora desde el modal
  const seleccionarHoraModal = (hora) => {
    const [horas, minutos] = hora.split(':');
    const nuevaFecha = new Date(fechaHora);
    nuevaFecha.setHours(parseInt(horas));
    nuevaFecha.setMinutes(parseInt(minutos));
    setFechaHora(nuevaFecha);
    setModalHorariosVisible(false);
  };

  const formatearFecha = (fecha) => {
    return fecha.toLocaleDateString('es-ES', {
      weekday: 'long',
      year: 'numeric',
      month: 'long',
      day: 'numeric'
    });
  };

  const formatearHora = (fecha) => {
    return fecha.toLocaleTimeString('es-ES', {
      hour: '2-digit',
      minute: '2-digit'
    });
  };

  const formatearParaAPI = (fecha) => {
    // Formato: YYYY-MM-DD HH:MM:SS
    const año = fecha.getFullYear();
    const mes = String(fecha.getMonth() + 1).padStart(2, '0');
    const dia = String(fecha.getDate()).padStart(2, '0');
    const horas = String(fecha.getHours()).padStart(2, '0');
    const minutos = String(fecha.getMinutes()).padStart(2, '0');
    const segundos = String(fecha.getSeconds()).padStart(2, '0');
    
    return `${año}-${mes}-${dia} ${horas}:${minutos}:${segundos}`;
  };

  // ✅ CORREGIDO: Función mejorada para calcular el total
  const calcularTotal = () => {
    console.log("🧮 Calculando total...");
    let total = 0;
    
    seleccionados.forEach((item, index) => {
      // ✅ CORREGIDO: Ya es número, pero por seguridad convertimos
      const precio = parseFloat(item.precio) || 0;
      console.log(`   Item ${index}: ${item.nombre} - $${precio} (tipo: ${typeof item.precio})`);
      total += precio;
    });
    
    console.log(`💰 Total calculado: $${total}`);
    return total;
  };

  // Función para formatear precio para mostrar
  const formatearPrecioParaMostrar = (precio) => {
    const precioNum = parseFloat(precio);
    if (isNaN(precioNum)) {
      console.warn("⚠️ Precio no válido:", precio);
      return "0.00";
    }
    return precioNum.toFixed(2);
  };

  const confirmarCita = async () => {
    if (!idLugar) {
      Alert.alert("Atención", "Seleccioná un lugar.");
      return;
    }
    
    if (seleccionados.length === 0) {
      Alert.alert("Atención", "Seleccioná al menos un servicio o combo.");
      return;
    }

    // Validar que la fecha no sea en el pasado
    const ahora = new Date();
    if (fechaHora <= ahora) {
      Alert.alert("Atención", "La fecha y hora deben ser futuras.");
      return;
    }

    try {
      console.log("💾 Confirmando cita...");
      
      // Debug final antes de enviar
      console.log("📊 RESUMEN FINAL:");
      seleccionados.forEach((item, index) => {
        console.log(`   ${index + 1}. ${item.nombre} - $${item.precio}`);
      });
      
      const totalFinal = calcularTotal();
      console.log(`💰 TOTAL FINAL: $${totalFinal}`);
      
      // Formatear fecha para la API
      const fechaISO = formatearParaAPI(fechaHora);
      
      const serviciosIDs = seleccionados
        .filter((s) => s.tipo === "servicio")
        .map((s) => s.id);

      const combosIDs = seleccionados
        .filter((s) => s.tipo === "combo")
        .map((s) => s.id);

      console.log("📤 Enviando:", {
        id_cliente,
        fecha: fechaISO,
        idLugar,
        serviciosIDs,
        combosIDs
      });

      const cita = await guardarCita(
        id_cliente,
        fechaISO,
        idLugar,
        serviciosIDs,
        combosIDs
      );

      console.log("✅ Cita guardada:", cita);

      // Navegar a pantalla de confirmación
      router.push({
        pathname: "/vista/vista_cli/vista_comp_cita/confirmar_cit_comp",
        params: {
          cita: JSON.stringify(cita),
          seleccionados: JSON.stringify(seleccionados),
          lugares: JSON.stringify(lugares),
          idLugar,
          fechaCita: fechaISO,
          fechaFormateada: `${formatearFecha(fechaHora)} a las ${formatearHora(fechaHora)}`,
          total: totalFinal.toString()
        },
      });

    } catch (error) {
      console.error("❌ Error guardando cita:", error);
      Alert.alert("Error", "No se pudo guardar la cita: " + error.message);
    }
  };

  if (cargando) {
    return (
      <View style={styles.center}>
        <ActivityIndicator size="large" color="#0000ff" />
        <Text style={styles.loadingText}>Cargando servicios...</Text>
      </View>
    );
  }

  const total = calcularTotal();

  return (
    <ScrollView 
      style={styles.container}
      contentContainerStyle={styles.scrollContent}
    >
      <Text style={styles.title}>Reservar Cita</Text>

      {/* SELECTOR DE FECHA Y HORA SEPARADOS */}
      <View style={styles.seccion}>
        <Text style={styles.subtitle}>📅 Fecha y Hora de la Cita</Text>
        
        {/* Fecha */}
        <View style={styles.filaSelectores}>
          <View style={styles.selectorContainer}>
            <Text style={styles.selectorLabel}>Fecha</Text>
            <TouchableOpacity 
              style={styles.selectorBoton}
              onPress={() => setMostrarDatePicker(true)}
            >
              <Text style={styles.selectorBotonText}>
                {formatearFecha(fechaHora)}
              </Text>
            </TouchableOpacity>
          </View>

          {/* Hora */}
          <View style={styles.selectorContainer}>
            <Text style={styles.selectorLabel}>Hora</Text>
            <TouchableOpacity 
              style={styles.selectorBoton}
              onPress={() => setModalHorariosVisible(true)}
            >
              <Text style={styles.selectorBotonText}>
                {formatearHora(fechaHora)}
              </Text>
            </TouchableOpacity>
          </View>
        </View>

        {/* Date Picker */}
        {mostrarDatePicker && (
          <DateTimePicker
            value={fechaHora}
            mode="date"
            display="default"
            onChange={onChangeDate}
            minimumDate={new Date()}
            locale="es-ES"
          />
        )}

        {/* Modal de Horarios Disponibles */}
        <Modal
          visible={modalHorariosVisible}
          animationType="slide"
          transparent={true}
          onRequestClose={() => setModalHorariosVisible(false)}
        >
          <View style={styles.modalContainer}>
            <View style={styles.modalContent}>
              <Text style={styles.modalTitle}>Seleccionar Hora</Text>
              <Text style={styles.modalSubtitle}>
                {formatearFecha(fechaHora)}
              </Text>
              
              <FlatList
                data={horariosDisponibles.filter(h => h.disponible)}
                keyExtractor={(item, index) => index.toString()}
                numColumns={3}
                contentContainerStyle={styles.horariosGrid}
                renderItem={({ item }) => (
                  <TouchableOpacity
                    style={[
                      styles.horarioItem,
                      formatearHora(fechaHora) === item.hora && styles.horarioSeleccionado
                    ]}
                    onPress={() => seleccionarHoraModal(item.hora)}
                  >
                    <Text style={[
                      styles.horarioTexto,
                      formatearHora(fechaHora) === item.hora && styles.horarioTextoSeleccionado
                    ]}>
                      {item.hora}
                    </Text>
                  </TouchableOpacity>
                )}
              />
              
              <TouchableOpacity
                style={styles.modalCerrar}
                onPress={() => setModalHorariosVisible(false)}
              >
                <Text style={styles.modalCerrarTexto}>Cerrar</Text>
              </TouchableOpacity>
            </View>
          </View>
        </Modal>
      </View>

      {/* LUGARES */}
      <View style={styles.seccion}>
        <Text style={styles.subtitle}>📍 Lugar de la Cita</Text>
        {lugares.length === 0 ? (
          <Text style={styles.noData}>No hay lugares disponibles</Text>
        ) : (
          lugares.map((lugar, index) => (
            <TouchableOpacity
              key={`lugar-${obtenerId(lugar)}-${index}`}
              style={[
                styles.card,
                idLugar === obtenerId(lugar) && styles.cardSelected,
              ]}
              onPress={() => setIdLugar(obtenerId(lugar))}
            >
              <Text style={[
                styles.text,
                idLugar === obtenerId(lugar) && styles.textSelected
              ]}>
                {obtenerNombre(lugar)}
              </Text>
            </TouchableOpacity>
          ))
        )}
      </View>

      {/* SERVICIOS */}
      <View style={styles.seccion}>
        <Text style={styles.subtitle}>💈 Servicios</Text>
        {servicios.length === 0 ? (
          <Text style={styles.noData}>No hay servicios disponibles</Text>
        ) : (
          servicios.map((serv, index) => (
            <TouchableOpacity
              key={`servicio-${obtenerId(serv)}-${index}`}
              style={[
                styles.card,
                estaSeleccionado(serv, "servicio") && styles.cardSelected,
              ]}
              onPress={() => toggleSeleccion(serv, "servicio")}
            >
              <Text style={[
                styles.text,
                estaSeleccionado(serv, "servicio") && styles.textSelected
              ]}>
                {obtenerNombre(serv)}
              </Text>
              <Text style={[
                styles.precio,
                estaSeleccionado(serv, "servicio") && styles.textSelected
              ]}>
                ${formatearPrecioParaMostrar(obtenerPrecio(serv))}
              </Text>
            </TouchableOpacity>
          ))
        )}
      </View>

      {/* COMBOS */}
      <View style={styles.seccion}>
        <Text style={styles.subtitle}>🎁 Combos</Text>
        {combos.length === 0 ? (
          <Text style={styles.noData}>No hay combos disponibles</Text>
        ) : (
          combos.map((comb, index) => (
            <TouchableOpacity
              key={`combo-${obtenerId(comb)}-${index}`}
              style={[
                styles.card,
                estaSeleccionado(comb, "combo") && styles.cardSelected,
              ]}
              onPress={() => toggleSeleccion(comb, "combo")}
            >
              <Text style={[
                styles.text,
                estaSeleccionado(comb, "combo") && styles.textSelected
              ]}>
                {obtenerNombre(comb)}
              </Text>
              <Text style={[
                styles.precio,
                estaSeleccionado(comb, "combo") && styles.textSelected
              ]}>
                ${formatearPrecioParaMostrar(obtenerPrecio(comb))}
              </Text>
            </TouchableOpacity>
          ))
        )}
      </View>

      {/* RESUMEN */}
      {seleccionados.length > 0 && (
        <View style={styles.resumen}>
          <Text style={styles.subtitle}>📋 Resumen de la Cita</Text>
          
          <View style={styles.resumenItem}>
            <Text style={styles.resumenLabel}>Fecha:</Text>
            <Text style={styles.resumenValor}>
              {formatearFecha(fechaHora)}
            </Text>
          </View>

          <View style={styles.resumenItem}>
            <Text style={styles.resumenLabel}>Hora:</Text>
            <Text style={styles.resumenValor}>
              {formatearHora(fechaHora)}
            </Text>
          </View>
          
          {idLugar && (
            <View style={styles.resumenItem}>
              <Text style={styles.resumenLabel}>Lugar:</Text>
              <Text style={styles.resumenValor}>
                {lugares.find(l => obtenerId(l) === idLugar)?.nombre_lugar || obtenerNombre(lugares.find(l => obtenerId(l) === idLugar))}
              </Text>
            </View>
          )}
          
          <Text style={styles.resumenLabel}>Servicios seleccionados:</Text>
          {seleccionados.map((item, index) => (
            <View key={`resumen-${index}`} style={styles.resumenServicioContainer}>
              <Text style={styles.resumenServicio}>
                • {item.nombre} 
              </Text>
              <Text style={styles.resumenServicioPrecio}>
                ${formatearPrecioParaMostrar(item.precio)}
              </Text>
            </View>
          ))}
          
          <View style={styles.totalContainer}>
            <Text style={styles.totalLabel}>Total:</Text>
            <Text style={styles.totalValor}>
              ${formatearPrecioParaMostrar(total)}
            </Text>
          </View>
        </View>
      )}

      {/* BOTÓN CONFIRMAR */}
      <TouchableOpacity 
        style={[
          styles.btnConfirmar,
          (!idLugar || seleccionados.length === 0) && styles.btnDisabled
        ]} 
        onPress={confirmarCita}
        disabled={!idLugar || seleccionados.length === 0}
      >
        <Text style={styles.btnText}>
          {!idLugar ? "Seleccioná un lugar" : 
           seleccionados.length === 0 ? "Seleccioná servicios" : 
           `Confirmar Cita - $${formatearPrecioParaMostrar(total)}`}
        </Text>
      </TouchableOpacity>

      {/* ✅ ESPACIO ADICIONAL EN LA PARTE INFERIOR */}
      <View style={styles.espacioInferior} />
    </ScrollView>
  );
}

const styles = StyleSheet.create({
  container: { 
    flex: 1, 
    backgroundColor: "#fff" 
  },
  scrollContent: {
    padding: 20,
    paddingBottom: 40,
  },
  center: { 
    flex: 1, 
    justifyContent: "center", 
    alignItems: "center" 
  },
  loadingText: { 
    marginTop: 10, 
    fontSize: 16 
  },
  title: { 
    fontSize: 24, 
    fontWeight: "bold", 
    marginBottom: 20, 
    textAlign: "center",
    color: "#333"
  },
  seccion: {
    marginBottom: 25,
  },
  subtitle: {
    fontSize: 18,
    fontWeight: "bold",
    marginBottom: 15,
    color: "#333"
  },
  noData: {
    textAlign: "center",
    color: "#666",
    fontStyle: "italic",
    marginVertical: 10
  },
  filaSelectores: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    marginBottom: 10,
  },
  selectorContainer: {
    flex: 1,
    marginHorizontal: 5,
  },
  selectorLabel: {
    fontSize: 14,
    fontWeight: 'bold',
    color: '#333',
    marginBottom: 5,
  },
  selectorBoton: {
    backgroundColor: "#f8f9fa",
    padding: 12,
    borderRadius: 8,
    borderWidth: 1,
    borderColor: "#dee2e6",
    alignItems: 'center',
  },
  selectorBotonText: {
    fontSize: 14,
    fontWeight: "500",
    color: "#333",
  },
  modalContainer: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: 'rgba(0,0,0,0.5)',
    padding: 20,
  },
  modalContent: {
    backgroundColor: 'white',
    borderRadius: 15,
    padding: 20,
    width: '100%',
    maxHeight: '80%',
  },
  modalTitle: {
    fontSize: 20,
    fontWeight: 'bold',
    marginBottom: 5,
    textAlign: 'center',
    color: '#333'
  },
  modalSubtitle: {
    fontSize: 14,
    color: '#666',
    marginBottom: 20,
    textAlign: 'center'
  },
  horariosGrid: {
    paddingVertical: 10,
  },
  horarioItem: {
    flex: 1,
    padding: 12,
    margin: 5,
    backgroundColor: '#f8f9fa',
    borderRadius: 8,
    alignItems: 'center',
    borderWidth: 1,
    borderColor: '#dee2e6',
  },
  horarioSeleccionado: {
    backgroundColor: '#007bff',
    borderColor: '#0056b3'
  },
  horarioTexto: {
    fontSize: 14,
    color: '#333',
    fontWeight: '500'
  },
  horarioTextoSeleccionado: {
    color: '#fff'
  },
  modalCerrar: {
    marginTop: 15,
    padding: 12,
    backgroundColor: '#6c757d',
    borderRadius: 8,
    alignItems: 'center'
  },
  modalCerrarTexto: {
    color: '#fff',
    fontWeight: 'bold',
    fontSize: 16
  },
  card: {
    backgroundColor: "#f8f9fa",
    padding: 15,
    borderRadius: 10,
    marginBottom: 10,
    borderWidth: 1,
    borderColor: "#dee2e6"
  },
  cardSelected: {
    backgroundColor: "#007bff",
    borderColor: "#0056b3"
  },
  text: {
    fontSize: 16,
    color: "#333",
    fontWeight: "500"
  },
  textSelected: {
    color: "#fff"
  },
  precio: {
    fontSize: 14,
    color: "#666",
    marginTop: 5
  },
  resumen: {
    backgroundColor: "#e9ecef",
    padding: 15,
    borderRadius: 10,
    marginTop: 20
  },
  resumenItem: {
    marginBottom: 10
  },
  resumenLabel: {
    fontSize: 14,
    fontWeight: "bold",
    color: "#333",
    marginBottom: 5
  },
  resumenValor: {
    fontSize: 14,
    color: "#333"
  },
  resumenServicioContainer: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    marginBottom: 5,
    marginLeft: 10
  },
  resumenServicio: {
    fontSize: 14,
    color: "#333",
    flex: 1
  },
  resumenServicioPrecio: {
    fontSize: 14,
    color: "#333",
    fontWeight: '500'
  },
  totalContainer: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    marginTop: 10,
    paddingTop: 10,
    borderTopWidth: 1,
    borderTopColor: "#ccc"
  },
  totalLabel: {
    fontSize: 16,
    fontWeight: "bold",
    color: "#333"
  },
  totalValor: {
    fontSize: 16,
    fontWeight: "bold",
    color: "#28a745"
  },
  btnConfirmar: {
    marginTop: 30,
    padding: 15,
    backgroundColor: "#28a745",
    borderRadius: 10,
    alignItems: "center"
  },
  btnDisabled: {
    backgroundColor: "#6c757d"
  },
  btnText: { 
    color: "#fff", 
    fontWeight: "bold", 
    fontSize: 18 
  },
  espacioInferior: {
    height: 50,
  },
});
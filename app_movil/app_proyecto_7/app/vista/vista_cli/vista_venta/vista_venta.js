import React, { useEffect, useState } from "react";
import {
  View,
  Text,
  ScrollView,
  TouchableOpacity,
  ActivityIndicator,
  Alert,
  StyleSheet,
  Modal,
  TextInput,
  FlatList,
} from "react-native";
import { useLocalSearchParams, useRouter } from "expo-router";

// Importar controladores
import {
  traerMetodosPago,
  traerDatosCita,
  procesarPago,
  confirmarCita,
  calcularMontoConAjustes,
  formatearPrecio,
} from "../../../../controladores/controladores_cli/controlador_venta/controlador_venta";
import Venta from "../../../../modelo/modelo_cli/modelo_venta/modelo_venta";

export default function VentaScreen() {
  const router = useRouter();
  const { id } = useLocalSearchParams();
  const id_cita = parseInt(id);

  const [cargando, setCargando] = useState(true);
  const [procesandoPago, setProcesandoPago] = useState(false);
  const [metodosPago, setMetodosPago] = useState([]);
  const [metodoPagoSeleccionado, setMetodoPagoSeleccionado] = useState(null);
  const [datosCita, setDatosCita] = useState(null);
  const [modalMetodosVisible, setModalMetodosVisible] = useState(false);
  const [montoIngresado, setMontoIngresado] = useState("");
  const [venta, setVenta] = useState(null);

  useEffect(() => {
    const cargarDatos = async () => {
      try {
        console.log("🔄 Cargando datos para la venta...");
        
        const [metodos, datos] = await Promise.all([
          traerMetodosPago(),
          traerDatosCita(id_cita),
        ]);

        console.log("✅ Datos cargados:");
        console.log("Métodos de pago:", metodos);
        console.log("Datos cita:", datos);

        setMetodosPago(metodos || []);
        setDatosCita(datos || {});

        // Crear instancia de Venta
        const nuevaVenta = new Venta(
          id_cita,
          datos.detalle || [],
          datos.total || 0
        );
        setVenta(nuevaVenta);

      } catch (error) {
        console.error("❌ Error cargando datos:", error);
        Alert.alert("Error", "No se pudieron cargar los datos: " + error.message);
      } finally {
        setCargando(false);
      }
    };

    if (id_cita) {
      cargarDatos();
    }
  }, [id_cita]);

  // Calcular monto con ajustes según método de pago
  const calcularMontoFinal = () => {
    if (!metodoPagoSeleccionado || !datosCita) {
      return datosCita?.total || 0;
    }
    
    return calcularMontoConAjustes(datosCita.total, metodoPagoSeleccionado);
  };

  const montoFinal = calcularMontoFinal();

  const seleccionarMetodoPago = (metodo) => {
    setMetodoPagoSeleccionado(metodo);
    setModalMetodosVisible(false);
    
    // Actualizar venta con el método de pago seleccionado
    if (venta) {
      venta.setMetodoPago(metodo);
      setVenta(venta);
    }
    
    // Establecer monto ingresado como el monto final calculado
    setMontoIngresado(montoFinal.toString());
  };

  const procesarVenta = async () => {
    if (!metodoPagoSeleccionado) {
      Alert.alert("Atención", "Seleccioná un método de pago.");
      return;
    }

    const montoNum = parseFloat(montoIngresado);
    const montoCalculado = parseFloat(montoFinal);

    if (isNaN(montoNum) || montoNum <= 0) {
      Alert.alert("Error", "Ingresá un monto válido.");
      return;
    }

    // Validar que el monto ingresado coincida con el calculado
    if (Math.abs(montoNum - montoCalculado) > 0.01) {
      Alert.alert(
        "Monto incorrecto", 
        `El monto debe ser exactamente $${formatearPrecio(montoCalculado)}`
      );
      return;
    }

    setProcesandoPago(true);

    try {
      console.log("💳 Procesando pago...");
      
      const resultado = await procesarPago(
        id_cita,
        metodoPagoSeleccionado.id_metodo_pago,
        montoNum,
        montoCalculado
      );

      console.log("✅ Pago procesado:", resultado);

      Alert.alert(
        "¡Pago Exitoso!",
        `Tu pago de $${formatearPrecio(montoCalculado)} ha sido procesado correctamente.\nID de Venta: ${resultado.id_venta}`,
        [
          {
            text: "Aceptar",
            onPress: () => {
              // Navegar a pantalla de confirmación o historial
              router.push({
                pathname: "/vista/vista_cli/vista_venta/confirmacion_venta",
                params: {
                  id_venta: resultado.id_venta.toString(),
                  monto_total: montoCalculado.toString(),
                  fecha_venta: resultado.fecha_venta,
                  metodo_pago: metodoPagoSeleccionado.metodo_pago,
                  detalle: JSON.stringify(datosCita.detalle || [])
                }
              });
            }
          }
        ]
      );

    } catch (error) {
      console.error("❌ Error procesando pago:", error);
      Alert.alert("Error", "No se pudo procesar el pago: " + error.message);
    } finally {
      setProcesandoPago(false);
    }
  };

  if (cargando) {
    return (
      <View style={styles.center}>
        <ActivityIndicator size="large" color="#0000ff" />
        <Text style={styles.loadingText}>Cargando datos de la venta...</Text>
      </View>
    );
  }

  if (!datosCita) {
    return (
      <View style={styles.center}>
        <Text style={styles.errorText}>No se encontraron datos para la cita</Text>
        <TouchableOpacity 
          style={styles.btnVolver}
          onPress={() => router.back()}
        >
          <Text style={styles.btnVolverText}>Volver</Text>
        </TouchableOpacity>
      </View>
    );
  }

  return (
    <ScrollView style={styles.container}>
      <Text style={styles.title}>💳 Procesar Pago</Text>

      {/* Información de la Cita */}
      <View style={styles.seccion}>
        <Text style={styles.subtitle}>📋 Detalle de la Cita #{id_cita}</Text>
        
        {datosCita.detalle && datosCita.detalle.length > 0 ? (
          datosCita.detalle.map((item, index) => (
            <View key={index} style={styles.itemDetalle}>
              <Text style={styles.itemNombre}>
                {item.id_servicios ? '💈 ' : '🎁 '}
                {item.nombre_servicio || item.nombre_combo || 'Item'}
              </Text>
              <Text style={styles.itemPrecio}>
                ${formatearPrecio(item.precio_servicio || item.precio_combo || 0)}
              </Text>
            </View>
          ))
        ) : (
          <Text style={styles.noData}>No hay items en esta cita</Text>
        )}

        <View style={styles.totalContainer}>
          <Text style={styles.totalLabel}>Total Original:</Text>
          <Text style={styles.totalValor}>
            ${formatearPrecio(datosCita.total || 0)}
          </Text>
        </View>
      </View>

      {/* Selección de Método de Pago */}
      <View style={styles.seccion}>
        <Text style={styles.subtitle}>💳 Método de Pago</Text>
        
        <TouchableOpacity 
          style={styles.selectorMetodo}
          onPress={() => setModalMetodosVisible(true)}
        >
          <Text style={
            metodoPagoSeleccionado ? 
            styles.selectorMetodoText : 
            styles.selectorMetodoPlaceholder
          }>
            {metodoPagoSeleccionado ? 
              `${metodoPagoSeleccionado.metodo_pago}` : 
              "Seleccioná un método de pago"
            }
          </Text>
          <Text style={styles.selectorMetodoIcon}>▼</Text>
        </TouchableOpacity>

        {metodoPagoSeleccionado && (
          <View style={styles.ajustesContainer}>
            {metodoPagoSeleccionado.incremento > 0 && (
              <Text style={styles.ajusteText}>
                📈 Incremento: +{metodoPagoSeleccionado.incremento}%
              </Text>
            )}
            {metodoPagoSeleccionado.decremento > 0 && (
              <Text style={styles.ajusteText}>
                📉 Decremento: -{metodoPagoSeleccionado.decremento}%
              </Text>
            )}
          </View>
        )}
      </View>

      {/* Monto Final */}
      {metodoPagoSeleccionado && (
        <View style={styles.seccion}>
          <Text style={styles.subtitle}>💰 Monto a Pagar</Text>
          
          <View style={styles.montoContainer}>
            <Text style={styles.montoLabel}>Monto calculado:</Text>
            <Text style={styles.montoFinal}>
              ${formatearPrecio(montoFinal)}
            </Text>
          </View>

          <View style={styles.inputContainer}>
            <Text style={styles.inputLabel}>Ingresá el monto:</Text>
            <TextInput
              style={styles.input}
              value={montoIngresado}
              onChangeText={setMontoIngresado}
              placeholder="0.00"
              keyboardType="numeric"
              placeholderTextColor="#999"
            />
          </View>
        </View>
      )}

      {/* Botón de Procesar Pago */}
      <TouchableOpacity 
        style={[
          styles.btnProcesar,
          (!metodoPagoSeleccionado || procesandoPago) && styles.btnDisabled
        ]} 
        onPress={procesarVenta}
        disabled={!metodoPagoSeleccionado || procesandoPago}
      >
        {procesandoPago ? (
          <ActivityIndicator color="#fff" />
        ) : (
          <Text style={styles.btnProcesarText}>
            {!metodoPagoSeleccionado ? 
              "Seleccioná un método de pago" : 
              `Procesar Pago - $${formatearPrecio(montoFinal)}`
            }
          </Text>
        )}
      </TouchableOpacity>

      {/* Modal de Métodos de Pago */}
      <Modal
        visible={modalMetodosVisible}
        animationType="slide"
        transparent={true}
        onRequestClose={() => setModalMetodosVisible(false)}
      >
        <View style={styles.modalContainer}>
          <View style={styles.modalContent}>
            <Text style={styles.modalTitle}>Seleccionar Método de Pago</Text>
            
            <FlatList
              data={metodosPago}
              keyExtractor={(item) => item.id_metodo_pago.toString()}
              renderItem={({ item }) => (
                <TouchableOpacity
                  style={[
                    styles.metodoItem,
                    metodoPagoSeleccionado?.id_metodo_pago === item.id_metodo_pago && 
                    styles.metodoItemSeleccionado
                  ]}
                  onPress={() => seleccionarMetodoPago(item)}
                >
                  <Text style={styles.metodoNombre}>{item.metodo_pago}</Text>
                  <View style={styles.metodoAjustes}>
                    {item.incremento > 0 && (
                      <Text style={styles.metodoAjuste}>+{item.incremento}%</Text>
                    )}
                    {item.decremento > 0 && (
                      <Text style={styles.metodoAjuste}>-{item.decremento}%</Text>
                    )}
                  </View>
                </TouchableOpacity>
              )}
            />
            
            <TouchableOpacity
              style={styles.modalCerrar}
              onPress={() => setModalMetodosVisible(false)}
            >
              <Text style={styles.modalCerrarTexto}>Cerrar</Text>
            </TouchableOpacity>
          </View>
        </View>
      </Modal>

      {/* Espacio adicional */}
      <View style={styles.espacioInferior} />
    </ScrollView>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: "#fff",
    padding: 20,
  },
  center: {
    flex: 1,
    justifyContent: "center",
    alignItems: "center",
    padding: 20,
  },
  loadingText: {
    marginTop: 10,
    fontSize: 16,
    color: "#666",
  },
  errorText: {
    fontSize: 16,
    color: "#dc3545",
    textAlign: "center",
    marginBottom: 20,
  },
  title: {
    fontSize: 24,
    fontWeight: "bold",
    marginBottom: 20,
    textAlign: "center",
    color: "#333",
  },
  seccion: {
    marginBottom: 25,
  },
  subtitle: {
    fontSize: 18,
    fontWeight: "bold",
    marginBottom: 15,
    color: "#333",
  },
  noData: {
    textAlign: "center",
    color: "#666",
    fontStyle: "italic",
    marginVertical: 10,
  },
  // Estilos para el detalle de items
  itemDetalle: {
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "center",
    backgroundColor: "#f8f9fa",
    padding: 12,
    borderRadius: 8,
    marginBottom: 8,
  },
  itemNombre: {
    fontSize: 14,
    color: "#333",
    flex: 1,
  },
  itemPrecio: {
    fontSize: 14,
    fontWeight: "bold",
    color: "#28a745",
  },
  totalContainer: {
    flexDirection: "row",
    justifyContent: "space-between",
    marginTop: 15,
    paddingTop: 15,
    borderTopWidth: 1,
    borderTopColor: "#dee2e6",
  },
  totalLabel: {
    fontSize: 16,
    fontWeight: "bold",
    color: "#333",
  },
  totalValor: {
    fontSize: 16,
    fontWeight: "bold",
    color: "#28a745",
  },
  // Selector de método de pago
  selectorMetodo: {
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "center",
    backgroundColor: "#f8f9fa",
    padding: 15,
    borderRadius: 8,
    borderWidth: 1,
    borderColor: "#dee2e6",
  },
  selectorMetodoText: {
    fontSize: 16,
    color: "#333",
    fontWeight: "500",
  },
  selectorMetodoPlaceholder: {
    fontSize: 16,
    color: "#999",
  },
  selectorMetodoIcon: {
    fontSize: 14,
    color: "#666",
  },
  ajustesContainer: {
    marginTop: 10,
    padding: 10,
    backgroundColor: "#e7f3ff",
    borderRadius: 6,
  },
  ajusteText: {
    fontSize: 12,
    color: "#0066cc",
    marginBottom: 2,
  },
  // Monto final
  montoContainer: {
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "center",
    marginBottom: 15,
  },
  montoLabel: {
    fontSize: 16,
    color: "#333",
  },
  montoFinal: {
    fontSize: 18,
    fontWeight: "bold",
    color: "#28a745",
  },
  inputContainer: {
    marginTop: 10,
  },
  inputLabel: {
    fontSize: 14,
    color: "#666",
    marginBottom: 5,
  },
  input: {
    backgroundColor: "#f8f9fa",
    borderWidth: 1,
    borderColor: "#dee2e6",
    borderRadius: 8,
    padding: 12,
    fontSize: 16,
    color: "#333",
  },
  // Botón procesar
  btnProcesar: {
    marginTop: 30,
    padding: 15,
    backgroundColor: "#28a745",
    borderRadius: 10,
    alignItems: "center",
  },
  btnDisabled: {
    backgroundColor: "#6c757d",
  },
  btnProcesarText: {
    color: "#fff",
    fontWeight: "bold",
    fontSize: 18,
  },
  btnVolver: {
    padding: 12,
    backgroundColor: "#6c757d",
    borderRadius: 8,
    alignItems: "center",
  },
  btnVolverText: {
    color: "#fff",
    fontWeight: "bold",
    fontSize: 16,
  },
  // Modal métodos de pago
  modalContainer: {
    flex: 1,
    justifyContent: "center",
    alignItems: "center",
    backgroundColor: "rgba(0,0,0,0.5)",
    padding: 20,
  },
  modalContent: {
    backgroundColor: "white",
    borderRadius: 15,
    padding: 20,
    width: "100%",
    maxHeight: "80%",
  },
  modalTitle: {
    fontSize: 20,
    fontWeight: "bold",
    marginBottom: 15,
    textAlign: "center",
    color: "#333",
  },
  metodoItem: {
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "center",
    padding: 15,
    borderBottomWidth: 1,
    borderBottomColor: "#f0f0f0",
  },
  metodoItemSeleccionado: {
    backgroundColor: "#e7f3ff",
    borderRadius: 8,
  },
  metodoNombre: {
    fontSize: 16,
    color: "#333",
    fontWeight: "500",
  },
  metodoAjustes: {
    flexDirection: "row",
  },
  metodoAjuste: {
    fontSize: 12,
    color: "#666",
    marginLeft: 8,
    paddingHorizontal: 6,
    paddingVertical: 2,
    backgroundColor: "#f8f9fa",
    borderRadius: 4,
  },
  modalCerrar: {
    marginTop: 15,
    padding: 12,
    backgroundColor: "#6c757d",
    borderRadius: 8,
    alignItems: "center",
  },
  modalCerrarTexto: {
    color: "#fff",
    fontWeight: "bold",
    fontSize: 16,
  },
  espacioInferior: {
    height: 50,
  },
});
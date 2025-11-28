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
import { Ionicons } from "@expo/vector-icons";

// Importar controladores
import {
  traerMetodosPago,
  traerDatosCita,
  procesarPago,
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
      <View style={styles.centerContainer}>
        <ActivityIndicator size="large" color="#ff6b9d" />
        <Text style={styles.loadingText}>Cargando datos de la venta...</Text>
      </View>
    );
  }

  if (!datosCita) {
    return (
      <View style={styles.centerContainer}>
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
    <View style={styles.container}>
      {/* Header */}
      <View style={styles.header}>
        <Text style={styles.title}>Procesar Pago</Text>
      </View>

      <ScrollView 
        contentContainerStyle={styles.scrollContent}
        showsVerticalScrollIndicator={false}
      >
        {/* Información de la Cita */}
        <View style={styles.card}>
          <Text style={styles.cardTitle}>
            <Ionicons name="list" size={20} color="#ff6b9d" />
            Detalle de la Cita #{id_cita}
          </Text>
          
          {datosCita.detalle && datosCita.detalle.length > 0 ? (
            datosCita.detalle.map((item, index) => (
              <View key={index} style={styles.itemDetalle}>
                <View style={styles.itemInfo}>
                  <Ionicons 
                    name={item.id_servicios ? "cut" : "gift"} 
                    size={16} 
                    color="#ff6b9d" 
                  />
                  <Text style={styles.itemNombre}>
                    {item.nombre_servicio || item.nombre_combo || 'Item'}
                  </Text>
                </View>
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
        <View style={styles.card}>
          <Text style={styles.cardTitle}>
            <Ionicons name="card" size={20} color="#ff6b9d" />
            Método de Pago
          </Text>
          
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
            <Ionicons name="chevron-down" size={20} color="#666" />
          </TouchableOpacity>

          {metodoPagoSeleccionado && (
            <View style={styles.ajustesContainer}>
              {metodoPagoSeleccionado.incremento > 0 && (
                <View style={styles.ajusteRow}>
                  <Ionicons name="trending-up" size={16} color="#27ae60" />
                  <Text style={styles.ajusteText}>
                    Incremento: +{metodoPagoSeleccionado.incremento}%
                  </Text>
                </View>
              )}
              {metodoPagoSeleccionado.decremento > 0 && (
                <View style={styles.ajusteRow}>
                  <Ionicons name="trending-down" size={16} color="#e74c3c" />
                  <Text style={styles.ajusteText}>
                    Decremento: -{metodoPagoSeleccionado.decremento}%
                  </Text>
                </View>
              )}
            </View>
          )}
        </View>

        {/* Monto Final */}
        {metodoPagoSeleccionado && (
          <View style={styles.card}>
            <Text style={styles.cardTitle}>
              <Ionicons name="cash" size={20} color="#ff6b9d" />
              Monto a Pagar
            </Text>
            
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
            <>
              <Ionicons name="card" size={24} color="#fff" />
              <Text style={styles.btnProcesarText}>
                {!metodoPagoSeleccionado ? 
                  "Seleccioná un método de pago" : 
                  `Procesar Pago - $${formatearPrecio(montoFinal)}`
                }
              </Text>
            </>
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
                        <View style={styles.ajusteBadge}>
                          <Ionicons name="trending-up" size={12} color="#27ae60" />
                          <Text style={styles.ajusteBadgeText}>+{item.incremento}%</Text>
                        </View>
                      )}
                      {item.decremento > 0 && (
                        <View style={[styles.ajusteBadge, styles.ajusteBadgeRed]}>
                          <Ionicons name="trending-down" size={12} color="#e74c3c" />
                          <Text style={styles.ajusteBadgeText}>-{item.decremento}%</Text>
                        </View>
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
    </View>
  );
}

const styles = StyleSheet.create({
  container: { 
    flex: 1, 
    backgroundColor: '#fdf0f5' 
  },
  header: {
    padding: 16,
    backgroundColor: '#fff',
    borderBottomWidth: 1,
    borderBottomColor: '#eee',
  },
  title: { 
    fontSize: 28, 
    fontWeight: '700', 
    color: '#000',
    textAlign: 'center',
  },
  scrollContent: {
    padding: 16,
    paddingBottom: 40,
  },
  centerContainer: { 
    flex: 1, 
    justifyContent: 'center', 
    alignItems: 'center', 
    backgroundColor: '#fdf0f5',
    padding: 20,
  },
  loadingText: { 
    marginTop: 10, 
    fontSize: 16, 
    color: '#333' 
  },
  errorText: {
    fontSize: 16,
    color: '#e74c3c',
    textAlign: 'center',
    marginBottom: 20,
  },
  card: {
    backgroundColor: 'white',
    padding: 16,
    borderRadius: 15,
    marginBottom: 16,
    elevation: 3,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1,
    shadowRadius: 4,
  },
  cardTitle: {
    fontSize: 18,
    fontWeight: '700',
    marginBottom: 15,
    color: '#333',
    flexDirection: 'row',
    alignItems: 'center',
  },
  noData: {
    textAlign: 'center',
    color: '#666',
    fontStyle: 'italic',
    marginVertical: 10,
  },
  // Estilos para el detalle de items
  itemDetalle: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    paddingVertical: 10,
    borderBottomWidth: 1,
    borderBottomColor: '#f0f0f0',
  },
  itemInfo: {
    flexDirection: 'row',
    alignItems: 'center',
    flex: 1,
  },
  itemNombre: {
    fontSize: 14,
    color: '#333',
    marginLeft: 8,
    flex: 1,
  },
  itemPrecio: {
    fontSize: 14,
    fontWeight: '700',
    color: '#27ae60',
  },
  totalContainer: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    marginTop: 15,
    paddingTop: 15,
    borderTopWidth: 1,
    borderTopColor: '#eee',
  },
  totalLabel: {
    fontSize: 16,
    fontWeight: 'bold',
    color: '#333',
  },
  totalValor: {
    fontSize: 16,
    fontWeight: 'bold',
    color: '#27ae60',
  },
  // Selector de método de pago
  selectorMetodo: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    backgroundColor: '#f8f9fa',
    padding: 15,
    borderRadius: 10,
    borderWidth: 1,
    borderColor: '#dee2e6',
  },
  selectorMetodoText: {
    fontSize: 16,
    color: '#333',
    fontWeight: '500',
  },
  selectorMetodoPlaceholder: {
    fontSize: 16,
    color: '#999',
  },
  ajustesContainer: {
    marginTop: 10,
    padding: 12,
    backgroundColor: '#f8f9fa',
    borderRadius: 8,
  },
  ajusteRow: {
    flexDirection: 'row',
    alignItems: 'center',
    marginBottom: 5,
  },
  ajusteText: {
    fontSize: 12,
    color: '#666',
    marginLeft: 6,
  },
  // Monto final
  montoContainer: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 15,
  },
  montoLabel: {
    fontSize: 16,
    color: '#333',
  },
  montoFinal: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#27ae60',
  },
  inputContainer: {
    marginTop: 10,
  },
  inputLabel: {
    fontSize: 14,
    color: '#666',
    marginBottom: 8,
    fontWeight: '600',
  },
  input: {
    backgroundColor: '#f8f9fa',
    borderWidth: 1,
    borderColor: '#dee2e6',
    borderRadius: 10,
    padding: 12,
    fontSize: 16,
    color: '#333',
  },
  // Botón procesar
  btnProcesar: {
    marginTop: 25,
    padding: 16,
    backgroundColor: '#ff6b9d',
    borderRadius: 20,
    alignItems: 'center',
    flexDirection: 'row',
    justifyContent: 'center',
    elevation: 5,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 5 },
    shadowOpacity: 0.15,
    shadowRadius: 10,
  },
  btnDisabled: {
    backgroundColor: '#7f8c8d',
  },
  btnProcesarText: {
    color: '#fff',
    fontWeight: '700',
    fontSize: 18,
    marginLeft: 8,
  },
  btnVolver: {
    padding: 12,
    backgroundColor: '#ff6b9d',
    borderRadius: 10,
    alignItems: 'center',
  },
  btnVolverText: {
    color: '#fff',
    fontWeight: 'bold',
    fontSize: 16,
  },
  // Modal métodos de pago
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
    elevation: 5,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 5 },
    shadowOpacity: 0.15,
    shadowRadius: 10,
  },
  modalTitle: {
    fontSize: 20,
    fontWeight: 'bold',
    marginBottom: 15,
    textAlign: 'center',
    color: '#333',
  },
  metodoItem: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    padding: 15,
    borderBottomWidth: 1,
    borderBottomColor: '#f0f0f0',
  },
  metodoItemSeleccionado: {
    backgroundColor: '#fdf0f5',
    borderRadius: 8,
  },
  metodoNombre: {
    fontSize: 16,
    color: '#333',
    fontWeight: '500',
  },
  metodoAjustes: {
    flexDirection: 'row',
  },
  ajusteBadge: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#e8f5e8',
    paddingHorizontal: 8,
    paddingVertical: 4,
    borderRadius: 12,
    marginLeft: 6,
  },
  ajusteBadgeRed: {
    backgroundColor: '#fde8e8',
  },
  ajusteBadgeText: {
    fontSize: 10,
    color: '#333',
    fontWeight: '600',
    marginLeft: 4,
  },
  modalCerrar: {
    marginTop: 15,
    padding: 12,
    backgroundColor: '#ff6b9d',
    borderRadius: 10,
    alignItems: 'center',
  },
  modalCerrarTexto: {
    color: '#fff',
    fontWeight: 'bold',
    fontSize: 16,
  },
  espacioInferior: {
    height: 50,
  },
});
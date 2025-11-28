import React from "react";
import { View, Text, ScrollView, TouchableOpacity, StyleSheet } from "react-native";
import { useLocalSearchParams, useRouter } from "expo-router";
import { Ionicons } from "@expo/vector-icons";

export default function ConfirmacionVentaScreen() {
  const router = useRouter();
  const { 
    id_venta, 
    monto_total, 
    fecha_venta, 
    metodo_pago,
    detalle 
  } = useLocalSearchParams();

  const detalleData = JSON.parse(detalle || "[]");

  // Función para formatear precio
  const formatearPrecio = (precio) => {
    const precioNum = Number(precio);
    if (isNaN(precioNum)) {
      return "0.00";
    }
    return precioNum.toFixed(2);
  };

  return (
    <View style={styles.container}>
      {/* Header */}
      <View style={styles.header}>
        <Text style={styles.title}>Confirmación de Pago</Text>
      </View>

      <ScrollView 
        contentContainerStyle={styles.scrollContent}
        showsVerticalScrollIndicator={false}
      >
        {/* Icono de éxito */}
        <View style={styles.successContainer}>
          <Ionicons name="checkmark-circle" size={80} color="#27ae60" />
          <Text style={styles.successTitle}>¡Pago Exitoso!</Text>
          <Text style={styles.successSubtitle}>Tu pago ha sido procesado correctamente</Text>
        </View>

        {/* Detalles de la venta */}
        <View style={styles.card}>
          <Text style={styles.cardTitle}>Detalles de la Venta</Text>
          
          <View style={styles.detalleItem}>
            <View style={styles.detalleRow}>
              <Ionicons name="receipt" size={20} color="#ff6b9d" />
              <Text style={styles.detalleLabel}>ID de Venta:</Text>
            </View>
            <Text style={styles.detalleValor}>#{id_venta}</Text>
          </View>

          <View style={styles.detalleItem}>
            <View style={styles.detalleRow}>
              <Ionicons name="calendar" size={20} color="#ff6b9d" />
              <Text style={styles.detalleLabel}>Fecha y Hora:</Text>
            </View>
            <Text style={styles.detalleValor}>
              {new Date(fecha_venta).toLocaleString('es-ES')}
            </Text>
          </View>

          <View style={styles.detalleItem}>
            <View style={styles.detalleRow}>
              <Ionicons name="card" size={20} color="#ff6b9d" />
              <Text style={styles.detalleLabel}>Método de Pago:</Text>
            </View>
            <Text style={styles.detalleValor}>{metodo_pago}</Text>
          </View>
        </View>

        {/* Servicios y combos */}
        <View style={styles.card}>
          <Text style={styles.cardTitle}>Servicios y Combos</Text>
          {detalleData.map((item, index) => (
            <View key={index} style={styles.servicioItem}>
              <View style={styles.servicioInfo}>
                <Ionicons 
                  name={item.id_servicios ? "cut" : "gift"} 
                  size={18} 
                  color="#ff6b9d" 
                />
                <Text style={styles.servicioNombre}>
                  {item.nombre_servicio || item.nombre_combo || 'Item'}
                </Text>
              </View>
              <Text style={styles.servicioPrecio}>
                ${formatearPrecio(item.precio_servicio || item.precio_combo || 0)}
              </Text>
            </View>
          ))}
        </View>

        {/* Total pagado */}
        <View style={styles.totalCard}>
          <View style={styles.totalContainer}>
            <Text style={styles.totalLabel}>Total Pagado:</Text>
            <Text style={styles.totalValor}>${formatearPrecio(monto_total)}</Text>
          </View>
        </View>

        {/* Botón de acción principal */}
        <TouchableOpacity 
          style={styles.btnPrincipal}
          onPress={() => router.push("/vista/vista_cli/vista_inicio/vista_inicio_cli")}
        >
          <Ionicons name="list" size={24} color="#fff" />
          <Text style={styles.btnPrincipalText}>Volver al Historial de Citas</Text>
        </TouchableOpacity>

        {/* Espacio inferior */}
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
  successContainer: {
    alignItems: 'center',
    marginBottom: 25,
    padding: 20,
    backgroundColor: 'white',
    borderRadius: 15,
    elevation: 3,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1,
    shadowRadius: 4,
  },
  successTitle: {
    fontSize: 24,
    fontWeight: 'bold',
    color: '#27ae60',
    marginTop: 10,
    marginBottom: 5,
  },
  successSubtitle: {
    fontSize: 16,
    color: '#666',
    textAlign: 'center',
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
    borderBottomWidth: 1,
    borderBottomColor: '#eee',
    paddingBottom: 8,
  },
  detalleItem: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 12,
    paddingBottom: 12,
    borderBottomWidth: 1,
    borderBottomColor: '#f0f0f0',
  },
  detalleRow: {
    flexDirection: 'row',
    alignItems: 'center',
    flex: 1,
  },
  detalleLabel: {
    fontSize: 14,
    color: '#666',
    fontWeight: '600',
    marginLeft: 8,
  },
  detalleValor: {
    fontSize: 14,
    color: '#333',
    fontWeight: '700',
    textAlign: 'right',
  },
  servicioItem: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 10,
    paddingVertical: 8,
  },
  servicioInfo: {
    flexDirection: 'row',
    alignItems: 'center',
    flex: 1,
  },
  servicioNombre: {
    fontSize: 14,
    color: '#333',
    marginLeft: 8,
    flex: 1,
  },
  servicioPrecio: {
    fontSize: 14,
    color: '#333',
    fontWeight: '700',
  },
  totalCard: {
    backgroundColor: 'white',
    padding: 16,
    borderRadius: 15,
    marginBottom: 20,
    elevation: 3,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1,
    shadowRadius: 4,
  },
  totalContainer: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
  },
  totalLabel: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#333',
  },
  totalValor: {
    fontSize: 20,
    fontWeight: 'bold',
    color: '#27ae60',
  },
  btnPrincipal: {
    backgroundColor: '#ff6b9d',
    padding: 16,
    borderRadius: 20,
    alignItems: 'center',
    marginBottom: 12,
    flexDirection: 'row',
    justifyContent: 'center',
    elevation: 5,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 5 },
    shadowOpacity: 0.15,
    shadowRadius: 10,
  },
  btnPrincipalText: {
    color: '#fff',
    fontWeight: '700',
    fontSize: 18,
    marginLeft: 8,
  },
  btnSecundario: {
    backgroundColor: 'white',
    padding: 16,
    borderRadius: 20,
    alignItems: 'center',
    flexDirection: 'row',
    justifyContent: 'center',
    borderWidth: 2,
    borderColor: '#ff6b9d',
    elevation: 3,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1,
    shadowRadius: 4,
  },
  btnSecundarioText: {
    color: '#ff6b9d',
    fontWeight: '700',
    fontSize: 16,
    marginLeft: 8,
  },
  espacioInferior: {
    height: 50,
  },
});
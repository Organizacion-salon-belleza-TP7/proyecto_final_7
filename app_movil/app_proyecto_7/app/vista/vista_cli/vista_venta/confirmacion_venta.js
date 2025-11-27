import React from "react";
import { View, Text, ScrollView, TouchableOpacity, StyleSheet } from "react-native";
import { useLocalSearchParams, useRouter } from "expo-router";

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

  return (
    <ScrollView style={styles.container}>
      <View style={styles.header}>
        <Text style={styles.successIcon}>✅</Text>
        <Text style={styles.title}>¡Pago Exitoso!</Text>
        <Text style={styles.subtitle}>Tu pago ha sido procesado correctamente</Text>
      </View>

      <View style={styles.detallesContainer}>
        <Text style={styles.detallesTitle}>Detalles de la Venta</Text>
        
        <View style={styles.detalleItem}>
          <Text style={styles.detalleLabel}>ID de Venta:</Text>
          <Text style={styles.detalleValor}>#{id_venta}</Text>
        </View>

        <View style={styles.detalleItem}>
          <Text style={styles.detalleLabel}>Fecha y Hora:</Text>
          <Text style={styles.detalleValor}>
            {new Date(fecha_venta).toLocaleString('es-ES')}
          </Text>
        </View>

        <View style={styles.detalleItem}>
          <Text style={styles.detalleLabel}>Método de Pago:</Text>
          <Text style={styles.detalleValor}>{metodo_pago}</Text>
        </View>

        <View style={styles.serviciosSection}>
          <Text style={styles.serviciosTitle}>Servicios y Combos:</Text>
          {detalleData.map((item, index) => (
            <View key={index} style={styles.servicioItem}>
              <Text style={styles.servicioNombre}>
                {item.id_servicios ? '💈 ' : '🎁 '}
                {item.nombre_servicio || item.nombre_combo || 'Item'}
              </Text>
              <Text style={styles.servicioPrecio}>
                ${(item.precio_servicio || item.precio_combo || 0).toFixed(2)}
              </Text>
            </View>
          ))}
        </View>

        <View style={styles.totalSection}>
          <Text style={styles.totalLabel}>Total Pagado:</Text>
          <Text style={styles.totalValor}>${parseFloat(monto_total).toFixed(2)}</Text>
        </View>
      </View>

      <View style={styles.accionesContainer}>
        <TouchableOpacity 
          style={styles.btnPrincipal}
          onPress={() => router.push("/vista/vista_cli/vista_citas/historial_citas")}
        >
          <Text style={styles.btnPrincipalText}>Ver Historial de Citas</Text>
        </TouchableOpacity>
        
        <TouchableOpacity 
          style={styles.btnSecundario}
          onPress={() => router.push("/vista/vista_cli/vista_inicio/inicio_cli")}
        >
          <Text style={styles.btnSecundarioText}>Volver al Inicio</Text>
        </TouchableOpacity>
      </View>
    </ScrollView>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    padding: 20,
    backgroundColor: '#fff'
  },
  header: {
    alignItems: 'center',
    marginBottom: 30,
    paddingVertical: 20
  },
  successIcon: {
    fontSize: 60,
    marginBottom: 10
  },
  title: {
    fontSize: 28,
    fontWeight: 'bold',
    color: '#28a745',
    marginBottom: 5
  },
  subtitle: {
    fontSize: 16,
    color: '#666',
    textAlign: 'center'
  },
  detallesContainer: {
    backgroundColor: '#f8f9fa',
    padding: 20,
    borderRadius: 10,
    marginBottom: 20
  },
  detallesTitle: {
    fontSize: 20,
    fontWeight: 'bold',
    marginBottom: 15,
    color: '#333'
  },
  detalleItem: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    marginBottom: 10,
    paddingBottom: 10,
    borderBottomWidth: 1,
    borderBottomColor: '#dee2e6'
  },
  detalleLabel: {
    fontSize: 16,
    color: '#666',
    fontWeight: '500'
  },
  detalleValor: {
    fontSize: 16,
    color: '#333',
    fontWeight: 'bold'
  },
  serviciosSection: {
    marginTop: 15
  },
  serviciosTitle: {
    fontSize: 18,
    fontWeight: 'bold',
    marginBottom: 10,
    color: '#333'
  },
  servicioItem: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    marginBottom: 8,
    paddingLeft: 10
  },
  servicioNombre: {
    fontSize: 14,
    color: '#333',
    flex: 1
  },
  servicioPrecio: {
    fontSize: 14,
    color: '#333',
    fontWeight: '500'
  },
  totalSection: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    marginTop: 15,
    paddingTop: 15,
    borderTopWidth: 2,
    borderTopColor: '#dee2e6'
  },
  totalLabel: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#333'
  },
  totalValor: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#28a745'
  },
  accionesContainer: {
    marginTop: 20
  },
  btnPrincipal: {
    backgroundColor: '#007bff',
    padding: 15,
    borderRadius: 10,
    alignItems: 'center',
    marginBottom: 10
  },
  btnPrincipalText: {
    color: '#fff',
    fontSize: 18,
    fontWeight: 'bold'
  },
  btnSecundario: {
    backgroundColor: '#6c757d',
    padding: 15,
    borderRadius: 10,
    alignItems: 'center'
  },
  btnSecundarioText: {
    color: '#fff',
    fontSize: 16,
    fontWeight: '500'
  }
});
import React from "react";
import { View, Text, ScrollView, TouchableOpacity, StyleSheet } from "react-native";
import { useLocalSearchParams, useRouter } from "expo-router";

export default function ConfirmarCitaScreen() {
  const router = useRouter();
  const { 
    cita, 
    seleccionados, 
    lugares, 
    idLugar, 
    fechaCita,
    fechaFormateada 
  } = useLocalSearchParams();

  const citaData = JSON.parse(cita);
  const seleccionadosData = JSON.parse(seleccionados);
  const lugaresData = JSON.parse(lugares);
  
  const lugarSeleccionado = lugaresData.find(l => l.id_lugar == idLugar);

  const total = seleccionadosData.reduce((sum, item) => sum + item.precio, 0);

  return (
    <ScrollView style={styles.container}>
      <View style={styles.header}>
        <Text style={styles.successIcon}>✅</Text>
        <Text style={styles.title}>¡Cita Confirmada!</Text>
        <Text style={styles.subtitle}>Tu cita ha sido agendada exitosamente</Text>
      </View>

      <View style={styles.detallesContainer}>
        <Text style={styles.detallesTitle}>Detalles de la Cita</Text>
        
        <View style={styles.detalleItem}>
          <Text style={styles.detalleLabel}>ID de Cita:</Text>
          <Text style={styles.detalleValor}>{citaData.id_cita}</Text>
        </View>

        <View style={styles.detalleItem}>
          <Text style={styles.detalleLabel}>Fecha y Hora:</Text>
          <Text style={styles.detalleValor}>{fechaFormateada}</Text>
        </View>

        <View style={styles.detalleItem}>
          <Text style={styles.detalleLabel}>Lugar:</Text>
          <Text style={styles.detalleValor}>{lugarSeleccionado?.nombre_lugar}</Text>
        </View>

        <View style={styles.serviciosSection}>
          <Text style={styles.serviciosTitle}>Servicios y Combos:</Text>
          {seleccionadosData.map((item, index) => (
            <View key={index} style={styles.servicioItem}>
              <Text style={styles.servicioNombre}>
                {item.tipo === 'servicio' ? '💈 ' : '🎁 '}
                {item.nombre}
              </Text>
              <Text style={styles.servicioPrecio}>${item.precio}</Text>
            </View>
          ))}
        </View>

        <View style={styles.totalSection}>
          <Text style={styles.totalLabel}>Total:</Text>
          <Text style={styles.totalValor}>${total}</Text>
        </View>
      </View>

      <TouchableOpacity 
        style={styles.btnVolver}
        onPress={() => router.back()}
      >
        <Text style={styles.btnVolverText}>Volver al Inicio</Text>
      </TouchableOpacity>
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
  btnVolver: {
    backgroundColor: '#007bff',
    padding: 15,
    borderRadius: 10,
    alignItems: 'center'
  },
  btnVolverText: {
    color: '#fff',
    fontSize: 18,
    fontWeight: 'bold'
  }
});
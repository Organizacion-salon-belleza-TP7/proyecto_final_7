import React from "react";
import { View, Text, ScrollView, TouchableOpacity, StyleSheet } from "react-native";
import { useLocalSearchParams, useRouter } from "expo-router";
import { Ionicons } from "@expo/vector-icons";

export default function ConfirmarCitaScreen() {
  const router = useRouter();
  const { 
    cita, 
    seleccionados, 
    lugares, 
    idLugar, 
    fechaCita,
    fechaFormateada,
    total 
  } = useLocalSearchParams();

  const citaData = JSON.parse(cita);
  const seleccionadosData = JSON.parse(seleccionados);
  const lugaresData = JSON.parse(lugares);
  
  const lugarSeleccionado = lugaresData.find(l => l.id_lugar == idLugar);

  // ✅ CORREGIDO: Debug para ver los datos recibidos
  console.log("🔍 [CONFIRMAR] Datos recibidos:");
  console.log("📦 seleccionadosData:", seleccionadosData);
  console.log("💰 total desde params:", total, "tipo:", typeof total);
  
  seleccionadosData.forEach((item, index) => {
    console.log(`   Item ${index}: ${item.nombre} - $${item.precio} (tipo: ${typeof item.precio})`);
  });

  // ✅ CORREGIDO: Función robusta para calcular total
  const calcularTotal = () => {
    let totalCalculado = 0;
    
    seleccionadosData.forEach((item, index) => {
      // ✅ FORZAR conversión a número
      const precio = Number(item.precio) || 0;
      console.log(`   Item ${index}: ${item.nombre} - $${precio} (convertido de: ${item.precio})`);
      totalCalculado += precio;
    });
    
    console.log(`💰 Total calculado: $${totalCalculado}`);
    return totalCalculado;
  };

  const totalCalculado = calcularTotal();
  
  // ✅ CORREGIDO: Usar el total calculado aquí, no el de params
  const totalFinal = totalCalculado;

  // Función para formatear precio
  const formatearPrecio = (precio) => {
    const precioNum = Number(precio);
    if (isNaN(precioNum)) {
      return "0.00";
    }
    return precioNum.toFixed(2);
  };

  // Navegar a venta
  const irAPagar = () => {
    router.push({
      pathname: "/vista/vista_cli/vista_venta/vista_venta",
      params: {
        id: citaData.id_cita.toString()
      }
    });
  };

  return (
    <View style={styles.container}>
      {/* Header */}
      <View style={styles.header}>
        <Text style={styles.title}>Confirmación de Cita</Text>
      </View>

      <ScrollView 
        contentContainerStyle={styles.scrollContent}
        showsVerticalScrollIndicator={false}
      >
        {/* Icono de éxito */}
        <View style={styles.successContainer}>
          <Ionicons name="checkmark-circle" size={80} color="#27ae60" />
          <Text style={styles.successTitle}>¡Cita Confirmada!</Text>
          <Text style={styles.successSubtitle}>Tu cita ha sido agendada exitosamente</Text>
        </View>

        {/* Detalles de la cita */}
        <View style={styles.card}>
          <Text style={styles.cardTitle}>Detalles de la Cita</Text>
          
          <View style={styles.detalleItem}>
            <View style={styles.detalleRow}>
              <Ionicons name="calendar" size={20} color="#ff6b9d" />
              <Text style={styles.detalleLabel}>ID de Cita:</Text>
            </View>
            <Text style={styles.detalleValor}>{citaData.id_cita}</Text>
          </View>

          <View style={styles.detalleItem}>
            <View style={styles.detalleRow}>
              <Ionicons name="time" size={20} color="#ff6b9d" />
              <Text style={styles.detalleLabel}>Fecha y Hora:</Text>
            </View>
            <Text style={styles.detalleValor}>{fechaFormateada}</Text>
          </View>

          <View style={styles.detalleItem}>
            <View style={styles.detalleRow}>
              <Ionicons name="location" size={20} color="#ff6b9d" />
              <Text style={styles.detalleLabel}>Lugar:</Text>
            </View>
            <Text style={styles.detalleValor}>{lugarSeleccionado?.nombre_lugar}</Text>
          </View>
        </View>

        {/* Servicios y combos seleccionados */}
        <View style={styles.card}>
          <Text style={styles.cardTitle}>Servicios y Combos</Text>
          {seleccionadosData.map((item, index) => (
            <View key={index} style={styles.servicioItem}>
              <View style={styles.servicioInfo}>
                <Ionicons 
                  name={item.tipo === 'servicio' ? "cut" : "gift"} 
                  size={18} 
                  color="#ff6b9d" 
                />
                <Text style={styles.servicioNombre}>{item.nombre}</Text>
              </View>
              <Text style={styles.servicioPrecio}>
                ${formatearPrecio(item.precio)}
              </Text>
            </View>
          ))}
        </View>

        {/* Total */}
        <View style={styles.totalCard}>
          <View style={styles.totalContainer}>
            <Text style={styles.totalLabel}>Total:</Text>
            <Text style={styles.totalValor}>${formatearPrecio(totalFinal)}</Text>
          </View>
        </View>

        {/* Botones de acción */}
        <TouchableOpacity 
          style={styles.btnPagar}
          onPress={irAPagar}
        >
          <Ionicons name="card" size={24} color="#fff" />
          <Text style={styles.btnPagarText}>
            Pagar Cita - ${formatearPrecio(totalFinal)}
          </Text>
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
    flex: 1,
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
  btnPagar: {
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
  btnPagarText: {
    color: '#fff',
    fontWeight: '700',
    fontSize: 18,
    marginLeft: 8,
  },
  btnVolver: {
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
  btnVolverText: {
    color: '#ff6b9d',
    fontWeight: '700',
    fontSize: 16,
    marginLeft: 8,
  },
  espacioInferior: {
    height: 50,
  },
});
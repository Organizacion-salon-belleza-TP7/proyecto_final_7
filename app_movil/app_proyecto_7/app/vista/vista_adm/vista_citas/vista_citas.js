import React, { useState, useCallback } from "react";
import { View, Text, FlatList, ActivityIndicator, Alert, StyleSheet, TouchableOpacity } from "react-native";
import { getCitas } from "../../../../controladores/controladores_adm/controladores_citas/controlador_citas";
import { useRouter } from "expo-router";
import { useFocusEffect } from "@react-navigation/native";

export default function CitasScreen() {
  const [citas, setCitas] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const router = useRouter();

  const fetchCitas = async () => {
    try {
      setLoading(true);
      setError(null);
      const data = await getCitas();
      setCitas(data);
    } catch (err) {
      console.error("Error en fetchCitas:", err);
      setError(err.message);
      Alert.alert("Error", err.message);
    } finally {
      setLoading(false);
    }
  };

  useFocusEffect(
    useCallback(() => {
      fetchCitas();
    }, [])
  );

  const renderItem = ({ item }) => (
    <View style={styles.card}>
      <View style={styles.textContainer}>
        <Text style={styles.cardTitle}>Cliente: {item.nombre_cliente}</Text>
        <Text style={styles.text}>Fecha: {item.fecha_cita}</Text>
        <Text style={styles.text}>Estado: {item.activo === 1 ? "Inactivo" : "Activo"}</Text>
        <Text style={styles.text}>Servicios: {item.servicios || "Ninguno"}</Text>
        <Text style={styles.text}>Combos: {item.combos || "Ninguno"}</Text>
      </View>
      <TouchableOpacity
        style={styles.button}
        onPress={() => router.push(`/vista/vista_adm/vista_citas/vista_detalle_cita?id=${item.id_cita}`)}
      >
        <Text style={styles.buttonText}>Ver Detalle</Text>
      </TouchableOpacity>
    </View>
  );

  if (loading) {
    return (
      <View style={styles.centerContainer}>
        <ActivityIndicator size="large" color="#ff6b9d" />
        <Text style={styles.loadingText}>Cargando citas...</Text>
      </View>
    );
  }

  if (error) {
    return (
      <View style={styles.centerContainer}>
        <Text style={styles.errorText}>Error: {error}</Text>
        <TouchableOpacity style={styles.retryButton} onPress={fetchCitas}>
          <Text style={styles.buttonText}>Reintentar</Text>
        </TouchableOpacity>
      </View>
    );
  }

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Listado de Citas</Text>
      <Text style={styles.subtitle}>Total de citas: {citas.length}</Text>
      <FlatList
        data={citas}
        renderItem={renderItem}
        keyExtractor={(item) => item.id_cita.toString()}
        contentContainerStyle={styles.list}
        showsVerticalScrollIndicator={false}
      />
    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, padding: 16, backgroundColor: '#fdf0f5' },
  title: { fontSize: 28, fontWeight: '700', textAlign: 'center', marginBottom: 10, color: '#000' },
  subtitle: { fontSize: 16, marginBottom: 16, textAlign: 'center', color: '#333' },
  card: { backgroundColor: 'white', padding: 16, borderRadius: 12, marginBottom: 12, elevation: 4, flexDirection: 'row', alignItems: 'center' },
  textContainer: { flex: 1 },
  cardTitle: { fontSize: 18, fontWeight: '700', marginBottom: 4, color: '#000' },
  text: { fontSize: 14, color: '#333' },
  button: { backgroundColor: '#ff6b9d', paddingVertical: 8, paddingHorizontal: 12, borderRadius: 12, alignItems: 'center', justifyContent: 'center' },
  buttonText: { color: '#fff', fontWeight: '700' },
  centerContainer: { flex: 1, justifyContent: 'center', alignItems: 'center', padding: 20 },
  loadingText: { marginTop: 10, fontSize: 16, color: '#333' },
  errorText: { color: 'red', textAlign: 'center', marginBottom: 20, fontSize: 16 },
  retryButton: { backgroundColor: '#ff6b9d', paddingVertical: 10, paddingHorizontal: 20, borderRadius: 12 },
  list: { paddingBottom: 20 }
});

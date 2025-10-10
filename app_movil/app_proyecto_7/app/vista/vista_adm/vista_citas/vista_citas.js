import React, { useState, useCallback } from "react";
import { View, Text, FlatList, ActivityIndicator, Alert, StyleSheet, Button } from "react-native";
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

  // ✅ recarga cuando la pantalla gana foco
  useFocusEffect(
    useCallback(() => {
      fetchCitas();
    }, [])
  );

  const renderItem = ({ item }) => (
    <View style={styles.card}>
      <View style={styles.textContainer}>
        <Text style={styles.cardTitle}>Cliente: {item.nombre_cliente}</Text>
        <Text>Fecha: {item.fecha_cita}</Text>
        <Text>Estado: {item.activo === 1 ? "Inactivo" : "Activo"}</Text>
        <Text>Servicios: {item.servicios || "Ninguno"}</Text>
        <Text>Combos: {item.combos || "Ninguno"}</Text>
      </View>
      <View style={styles.buttonContainer}>
        <Button 
          title="Ver Detalle" 
          onPress={() => router.push(`/vista/vista_adm/vista_citas/vista_detalle_cita?id=${item.id_cita}`)} 
        />
      </View>
    </View>
  );

  if (loading) {
    return (
      <View style={styles.centerContainer}>
        <ActivityIndicator size="large" color="#0000ff" />
        <Text style={styles.loadingText}>Cargando citas...</Text>
      </View>
    );
  }

  if (error) {
    return (
      <View style={styles.centerContainer}>
        <Text style={styles.errorText}>Error: {error}</Text>
        <Button title="Reintentar" onPress={fetchCitas} />
      </View>
    );
  }

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Listado de Citas</Text>
      <Text style={styles.subtitle}>
        Total de citas: {citas.length}
      </Text>
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
  container: { flex: 1, padding: 16, backgroundColor: "#f5f5f5" },
  centerContainer: { flex: 1, justifyContent: "center", alignItems: "center", padding: 20 },
  title: { fontSize: 24, fontWeight: "bold", marginBottom: 16, textAlign: "center", color: "#333" },
  subtitle: { fontSize: 16, marginBottom: 16, textAlign: "center", color: "#666" },
  card: { backgroundColor: "white", padding: 16, borderRadius: 8, marginBottom: 12, elevation: 3, flexDirection: "row" },
  textContainer: { flex: 1 },
  cardTitle: { fontSize: 18, fontWeight: "bold", marginBottom: 4, color: "#333" },
  buttonContainer: { justifyContent: "center" },
  loadingText: { marginTop: 10, fontSize: 16, color: "#666" },
  errorText: { color: "red", textAlign: "center", marginBottom: 20, fontSize: 16 },
  list: { paddingBottom: 20 },
});

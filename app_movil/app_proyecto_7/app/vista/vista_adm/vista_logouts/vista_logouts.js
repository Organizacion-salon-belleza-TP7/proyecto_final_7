import React, { useEffect, useState } from "react";
import { View, Text, FlatList, ActivityIndicator, StyleSheet, Alert } from "react-native";
import { obtenerLogueos } from "../../../../controladores/controladores_adm/controladores_logout/controlador_logout";

export default function VistaLogouts() {
  const [logueos, setLogueos] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    obtenerLogueos().then((data) => {
      if (data.error) {
        Alert.alert("Error", data.error);
      } else {
        setLogueos(data);
      }
      setLoading(false);
    });
  }, []);

  if (loading) return (
    <View style={styles.centerContainer}>
      <ActivityIndicator size="large" color="#ff6b9d" />
      <Text style={styles.loadingText}>Cargando historial de logueos...</Text>
    </View>
  );

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Historial de Logueos</Text>
      <FlatList
        data={logueos}
        keyExtractor={(item) => item.id_historial_logueos.toString()}
        renderItem={({ item }) => (
          <View style={styles.card}>
            <Text style={styles.userName}>{item.nombre_usuario}</Text>
            <Text style={styles.infoText}>Login: {item.fecha_logueo}</Text>
            <Text style={styles.infoText}>Logout: {item.fecha_logout ?? "No cerró sesión"}</Text>
          </View>
        )}
        contentContainerStyle={{ paddingBottom: 20 }}
        showsVerticalScrollIndicator={false}
      />
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    padding: 16,
    backgroundColor: "#fdf0f5"
  },
  centerContainer: {
    flex: 1,
    justifyContent: "center",
    alignItems: "center"
  },
  loadingText: {
    marginTop: 10,
    fontSize: 16,
    color: "#666"
  },
  title: {
    fontSize: 24,
    fontWeight: "700",
    marginBottom: 15,
    textAlign: "center",
    color: "#000"
  },
  card: {
    backgroundColor: "#fff",
    padding: 12,
    borderRadius: 8,
    marginBottom: 10,
    elevation: 3
  },
  userName: {
    fontSize: 16,
    fontWeight: "700",
    marginBottom: 4,
    color: "#333"
  },
  infoText: {
    fontSize: 14,
    color: "#555"
  }
});

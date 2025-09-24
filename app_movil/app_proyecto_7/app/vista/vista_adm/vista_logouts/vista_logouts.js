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

  if (loading) return <ActivityIndicator size="large" />;

  return (
    <View style={styles.container}>
      <Text style={styles.titulo}>Historial de logueos</Text>
      <FlatList
        data={logueos}
        keyExtractor={(item) => item.id_historial_logueos.toString()}
        renderItem={({ item }) => (
          <View style={styles.card}>
            <Text style={styles.nombre}>{item.nombre_usuario}</Text>
            <Text>Login: {item.fecha_logueo}</Text>
            <Text>Logout: {item.fecha_logout ?? "No cerró sesión"}</Text>
          </View>
        )}
      />
    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, padding: 15, backgroundColor: "#fff" },
  titulo: { fontSize: 20, fontWeight: "bold", marginBottom: 10 },
  card: {
    backgroundColor: "#f2f2f2",
    padding: 10,
    marginBottom: 8,
    borderRadius: 8,
  },
  nombre: { fontSize: 16, fontWeight: "bold" },
});

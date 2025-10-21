import React, { useEffect, useState } from "react";
import {
  View,
  Text,
  FlatList,
  ActivityIndicator,
  StyleSheet,
  Alert,
} from "react-native";
import AsyncStorage from "@react-native-async-storage/async-storage";
import { traerCitasCompradas } from "../../../../controladores/controladores_cli/controlador_inicio/controlador_inicio_cli";

export default function VistaInicioCliente() {
  const [citas, setCitas] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    async function cargarCitas() {
      try {
        // 🧠 Obtener usuario logueado desde el almacenamiento
        const userData = await AsyncStorage.getItem("usuarioLogueado");
        if (!userData) throw new Error("No hay usuario logueado");

        const user = JSON.parse(userData);

        // Llamar al backend con el ID del usuario
        const data = await traerCitasCompradas(user.id_usuario);

        console.log("📋 Citas recibidas:", data);

        // Validar que sea un array
        if (!Array.isArray(data)) {
          throw new Error("El servidor devolvió un formato inesperado");
        }

        setCitas(data);
      } catch (error) {
        console.error("Error cargando citas:", error);
        Alert.alert("Error", error.message);
      } finally {
        setLoading(false);
      }
    }

    cargarCitas();
  }, []);

  if (loading) {
    return <ActivityIndicator style={{ flex: 1 }} size="large" color="#000" />;
  }

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Mis Citas Compradas</Text>

      {citas.length === 0 ? (
        <Text style={styles.empty}>No tienes citas compradas</Text>
      ) : (
        <FlatList
          data={citas}
          // ✅ Clave única garantizada
          keyExtractor={(item, index) =>
            `${item.id_cita ?? "sin-id"}-${item.id_historial_compra ?? index}`
          }
          renderItem={({ item }) => (
            <View style={styles.card}>
              <Text style={styles.text}>Lugar: {item.nombre_lugar}</Text>
              <Text style={styles.text}>Fecha: {item.fecha_cita}</Text>
              <Text style={styles.text}>
                Estado: {item.activo ? "Activa" : "Inactiva"}
              </Text>
            </View>
          )}
        />
      )}
    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, padding: 20, backgroundColor: "#fff" },
  title: {
    fontSize: 22,
    fontWeight: "bold",
    marginBottom: 15,
    textAlign: "center",
  },
  empty: { textAlign: "center", marginTop: 30, fontSize: 16 },
  card: {
    backgroundColor: "#f5f5f5",
    padding: 15,
    borderRadius: 10,
    marginBottom: 10,
    shadowColor: "#000",
    shadowOpacity: 0.1,
    shadowOffset: { width: 0, height: 2 },
    shadowRadius: 4,
  },
  text: { fontSize: 16 },
});

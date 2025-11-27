import React, { useEffect, useState } from "react";
import {
  View,
  Text,
  FlatList,
  ActivityIndicator,
  StyleSheet,
  Alert,
  TouchableOpacity,
} from "react-native";
import AsyncStorage from "@react-native-async-storage/async-storage";
import {
  traerCitasCompradas,
  cancelarCita,
} from "../../../../controladores/controladores_cli/controlador_inicio/controlador_inicio_cli";

// 🔹 Expo Router
import { useRouter } from "expo-router";

export default function VistaInicioCliente() {
  const [citas, setCitas] = useState([]);
  const [loading, setLoading] = useState(true);
  const [usuario, setUsuario] = useState(null);

  const router = useRouter(); // ← YA NO USAMOS navigation

  useEffect(() => {
    cargarCitas();
  }, []);

  async function cargarCitas() {
    try {
      const userData = await AsyncStorage.getItem("usuarioLogueado");
      if (!userData) throw new Error("No hay usuario logueado");

      const user = JSON.parse(userData);
      setUsuario(user);

      const data = await traerCitasCompradas(user.id_usuario);
      setCitas(data);
    } catch (error) {
      Alert.alert("Error", error.message);
    } finally {
      setLoading(false);
    }
  }

  async function handleCancelarCita(id_caja, id_cita) {
    try {
      const userData = await AsyncStorage.getItem("usuarioLogueado");
      const user = JSON.parse(userData);

      const result = await cancelarCita(id_caja, id_cita, user.id_usuario);
      Alert.alert("Éxito", `Cita cancelada. Reembolso: $${result.reembolso}`);
      cargarCitas();
    } catch (error) {
      Alert.alert("Error", error.message);
    }
  }

  if (loading) {
    return <ActivityIndicator style={{ flex: 1 }} size="large" color="#000" />;
  }

  return (
    <View style={styles.container}>
      <FlatList
        data={citas}
        ListHeaderComponent={
          <Text style={styles.title}>Mis Citas Compradas</Text>
        }
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

            {item.activo ? (
              <TouchableOpacity
                style={styles.btnCancel}
                onPress={() => handleCancelarCita(item.id_caja, item.id_cita)}
              >
                <Text style={styles.btnText}>Cancelar Cita</Text>
              </TouchableOpacity>
            ) : (
              <TouchableOpacity style={styles.btnDisabled} disabled>
                <Text style={styles.btnText}>Cita Inactiva</Text>
              </TouchableOpacity>
            )}
          </View>
        )}
        ListEmptyComponent={
          <Text style={styles.empty}>No tienes citas compradas</Text>
        }
        ListFooterComponent={
          <TouchableOpacity
            style={styles.btnComprar}
            onPress={() => {
              if (!usuario) {
                Alert.alert("Error", "No se encontró información del usuario");
                return;
              }

              // 🔹 Navegación correcta con Expo Router
              router.push({
                pathname: "vista/vista_cli/vista_comp_cita/vista_comp_cita",
                params: { id_cliente: usuario.id_usuario },
              });
            }}
          >
            <Text style={styles.btnComprarText}>Comprar Nueva Cita</Text>
          </TouchableOpacity>
        }
        contentContainerStyle={{ paddingBottom: 40 }}
      />
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
  },
  text: { fontSize: 16 },
  btnCancel: {
    marginTop: 10,
    backgroundColor: "#e74c3c",
    padding: 10,
    borderRadius: 6,
    alignItems: "center",
  },
  btnDisabled: {
    marginTop: 10,
    backgroundColor: "#7f8c8d",
    padding: 10,
    borderRadius: 6,
    alignItems: "center",
  },
  btnText: { color: "#fff", fontWeight: "bold" },
  btnComprar: {
    marginTop: 25,
    backgroundColor: "#e91e63",
    padding: 14,
    borderRadius: 10,
    alignItems: "center",
  },
  btnComprarText: {
    color: "#fff",
    fontWeight: "bold",
    fontSize: 18,
  },
});

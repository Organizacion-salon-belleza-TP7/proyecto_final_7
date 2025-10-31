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
import { traerCitasCompradas, cancelarCita } from "../../../../controladores/controladores_cli/controlador_inicio/controlador_inicio_cli";

export default function VistaInicioCliente() {
  const [citas, setCitas] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    cargarCitas();
  }, []);

  async function cargarCitas() {
    try {
      const userData = await AsyncStorage.getItem("usuarioLogueado");
      if (!userData) throw new Error("No hay usuario logueado");

      const user = JSON.parse(userData);
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
      cargarCitas(); // refresca lista
    } catch (error) {
      Alert.alert("Error", error.message);
    }
  }

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
});
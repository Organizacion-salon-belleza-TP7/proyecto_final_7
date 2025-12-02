import React, { useEffect, useState, useCallback } from "react";
import {
  View,
  Text,
  FlatList,
  ActivityIndicator,
  StyleSheet,
  Alert,
  TouchableOpacity,
  RefreshControl,
} from "react-native";
import AsyncStorage from "@react-native-async-storage/async-storage";
import {
  traerCitasCompradas,
  cancelarCita,
} from "../../../../controladores/controladores_cli/controlador_inicio/controlador_inicio_cli";
import { useRouter, useFocusEffect } from "expo-router";
import { Ionicons } from "@expo/vector-icons";

export default function VistaInicioCliente() {
  const [citas, setCitas] = useState([]);
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);
  const [usuario, setUsuario] = useState(null);

  const router = useRouter();

  const cargarCitas = useCallback(async () => {
    try {
      const userData = await AsyncStorage.getItem("usuarioLogueado");
      if (!userData) {
        Alert.alert("Error", "No hay usuario logueado");
        router.replace("/");
        return;
      }

      const user = JSON.parse(userData);
      setUsuario(user);

      const data = await traerCitasCompradas(user.id_usuario);
      setCitas(data);
    } catch (error) {
      Alert.alert("Error", error.message);
    } finally {
      setLoading(false);
      setRefreshing(false);
    }
  }, []);

  // Actualizar cuando la pantalla obtiene el foco
  useFocusEffect(
    useCallback(() => {
      cargarCitas();
      
      // Opcional: Actualización automática cada 30 segundos
      // const interval = setInterval(cargarCitas, 30000);
      // return () => clearInterval(interval);
    }, [cargarCitas])
  );

  useEffect(() => {
    cargarCitas();
  }, []);

  const onRefresh = useCallback(() => {
    setRefreshing(true);
    cargarCitas();
  }, [cargarCitas]);

  async function handleCancelarCita(id_caja, id_cita) {
    try {
      const userData = await AsyncStorage.getItem("usuarioLogueado");
      const user = JSON.parse(userData);

      const result = await cancelarCita(id_caja, id_cita, user.id_usuario);
      Alert.alert("Éxito", `Cita cancelada. Reembolso: $${result.reembolso}`);
      cargarCitas(); // Actualizar inmediatamente
    } catch (error) {
      Alert.alert("Error", error.message);
    }
  }

  const handleComprarCita = () => {
    if (!usuario?.id_usuario) {
      Alert.alert("Error", "Usuario no identificado");
      return;
    }
    
    router.push({
      pathname: "vista/vista_cli/vista_comp_cita/vista_comp_cita",
      params: { 
        id_cliente: usuario.id_usuario,
        nombre_cliente: usuario.nombre_usuario 
      },
    });
  };

  if (loading && !refreshing) {
    return (
      <View style={styles.centerContainer}>
        <ActivityIndicator size="large" color="#ff6b9d" />
        <Text style={styles.loadingText}>Cargando tus citas...</Text>
      </View>
    );
  }

  return (
    <View style={styles.container}>
      {/* HEADER SIMPLE — estilo igual al admin */}
      <View style={styles.header}>
        <Text style={styles.title}>Mis Citas</Text>
      </View>

      {/* Información del usuario */}
      <View style={styles.userInfo}>
        <Ionicons name="person-circle" size={40} color="#ff6b9d" />
        <View style={styles.userTextContainer}>
          <Text style={styles.userName}>{usuario?.nombre_usuario || "Usuario"}</Text>
          <Text style={styles.userId}>ID: {usuario?.id_usuario}</Text>
        </View>
        <TouchableOpacity 
          style={styles.refreshIconButton}
          onPress={cargarCitas}
        >
          <Ionicons name="refresh" size={24} color="#ff6b9d" />
        </TouchableOpacity>
      </View>

      {/* Botón comprar cita */}
      <TouchableOpacity
        style={styles.addButton}
        onPress={handleComprarCita}
      >
        <Ionicons name="add-circle" size={20} color="#fff" />
        <Text style={styles.addButtonText}>Comprar Nueva Cita</Text>
      </TouchableOpacity>

      <View style={styles.headerInfo}>
        <Text style={styles.subtitle}>Total de citas: {citas.length}</Text>
        <TouchableOpacity 
          style={styles.refreshButton}
          onPress={cargarCitas}
        >
          <Ionicons name="refresh-outline" size={18} color="#ff6b9d" />
          <Text style={styles.refreshText}>Actualizar</Text>
        </TouchableOpacity>
      </View>

      <FlatList
        data={citas}
        keyExtractor={(item, index) =>
          `${item.id_cita ?? "sin-id"}-${item.id_historial_compra ?? index}`
        }
        showsVerticalScrollIndicator={false}
        contentContainerStyle={styles.list}
        refreshControl={
          <RefreshControl
            refreshing={refreshing}
            onRefresh={onRefresh}
            colors={["#ff6b9d"]}
            tintColor="#ff6b9d"
          />
        }
        ListEmptyComponent={
          <View style={styles.emptyContainer}>
            <Ionicons name="calendar-outline" size={60} color="#ccc" />
            <Text style={styles.emptyText}>No tienes citas compradas</Text>
            <TouchableOpacity
              style={styles.emptyButton}
              onPress={handleComprarCita}
            >
              <Ionicons name="add-circle" size={20} color="#fff" />
              <Text style={styles.emptyButtonText}>Comprar mi primera cita</Text>
            </TouchableOpacity>
          </View>
        }
        renderItem={({ item }) => (
          <View style={styles.card}>
            <View style={styles.textContainer}>
              <Text style={styles.cardTitle}>
                Lugar: {item.nombre_lugar}
              </Text>
              <Text style={styles.cardText}>Fecha: {item.fecha_cita}</Text>
              <Text style={styles.cardText}>ID Cita: {item.id_cita}</Text>
              <Text
                style={[
                  styles.cardText,
                  styles.statusText,
                  item.activo ? styles.activeStatus : styles.inactiveStatus,
                ]}
              >
                Estado: {item.activo ? "Activa ✓" : "Inactiva ✗"}
              </Text>
            </View>

            <View style={styles.buttonContainer}>
              {item.activo ? (
                <TouchableOpacity
                  style={styles.cancelButton}
                  onPress={() => handleCancelarCita(item.id_caja, item.id_cita)}
                >
                  <Ionicons name="close-circle" size={16} color="#fff" />
                  <Text style={styles.buttonText}> Cancelar</Text>
                </TouchableOpacity>
              ) : (
                <TouchableOpacity style={styles.disabledButton} disabled>
                  <Ionicons name="time-outline" size={16} color="#fff" />
                  <Text style={styles.buttonText}> Inactiva</Text>
                </TouchableOpacity>
              )}
            </View>
          </View>
        )}
      />
    </View>
  );
}

// ----------- ESTILOS ADAPTADOS AL ESTILO ROSA DEL ADMIN -------------------

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: "#fdf0f5",
    padding: 16,
  },

  header: {
    backgroundColor: "#ff6b9d",
    paddingVertical: 14,
    borderRadius: 14,
    marginBottom: 18,
    alignItems: "center",
    elevation: 3,
  },
  title: {
    color: "white",
    fontSize: 22,
    fontWeight: "700",
  },

  userInfo: {
    flexDirection: "row",
    alignItems: "center",
    backgroundColor: "white",
    padding: 16,
    borderRadius: 15,
    marginBottom: 16,
    elevation: 3,
    justifyContent: "space-between",
  },

  userTextContainer: {
    flex: 1,
    marginLeft: 12,
  },
  userName: {
    fontSize: 18,
    fontWeight: "700",
    color: "#000",
  },
  userId: {
    fontSize: 14,
    color: "#666",
  },

  refreshIconButton: {
    padding: 8,
    borderRadius: 20,
    backgroundColor: "#f8f8f8",
  },

  addButton: {
    backgroundColor: "#ff6b9d",
    paddingVertical: 12,
    borderRadius: 20,
    marginBottom: 16,
    flexDirection: "row",
    justifyContent: "center",
    alignItems: "center",
    elevation: 3,
  },
  addButtonText: {
    color: "#fff",
    fontSize: 18,
    marginLeft: 8,
    fontWeight: "700",
  },

  headerInfo: {
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "center",
    marginBottom: 14,
  },
  subtitle: {
    fontSize: 16,
    color: "#333",
    fontWeight: "600",
  },
  refreshButton: {
    flexDirection: "row",
    alignItems: "center",
    paddingHorizontal: 12,
    paddingVertical: 6,
    backgroundColor: "#fff",
    borderRadius: 15,
    elevation: 2,
  },
  refreshText: {
    color: "#ff6b9d",
    marginLeft: 4,
    fontSize: 14,
    fontWeight: "600",
  },

  card: {
    backgroundColor: "white",
    padding: 16,
    borderRadius: 15,
    marginBottom: 12,
    flexDirection: "row",
    justifyContent: "space-between",
    elevation: 5,
  },

  cardTitle: {
    fontSize: 18,
    fontWeight: "700",
    marginBottom: 4,
    color: "#333",
  },
  cardText: {
    fontSize: 14,
    color: "#555",
    marginBottom: 2,
  },
  statusText: {
    marginTop: 3,
    fontWeight: "700",
  },
  activeStatus: {
    color: "#27ae60",
  },
  inactiveStatus: {
    color: "#7f8c8d",
  },

  buttonContainer: {
    justifyContent: "center",
  },
  cancelButton: {
    backgroundColor: "#e74c3c",
    paddingVertical: 8,
    paddingHorizontal: 12,
    borderRadius: 8,
    flexDirection: "row",
    alignItems: "center",
    elevation: 2,
  },

  disabledButton: {
    backgroundColor: "#7f8c8d",
    paddingVertical: 8,
    paddingHorizontal: 12,
    borderRadius: 8,
    flexDirection: "row",
    alignItems: "center",
  },

  buttonText: {
    color: "white",
    fontSize: 14,
    fontWeight: "700",
  },

  emptyContainer: {
    marginTop: 40,
    alignItems: "center",
    padding: 20,
  },
  emptyText: {
    marginTop: 15,
    color: "#666",
    fontSize: 16,
    marginBottom: 20,
    textAlign: "center",
  },
  emptyButton: {
    backgroundColor: "#ff6b9d",
    paddingVertical: 12,
    paddingHorizontal: 24,
    borderRadius: 20,
    flexDirection: "row",
    alignItems: "center",
    elevation: 3,
  },
  emptyButtonText: {
    color: "#fff",
    fontSize: 16,
    marginLeft: 8,
    fontWeight: "600",
  },

  centerContainer: {
    flex: 1,
    justifyContent: "center",
    alignItems: "center",
    backgroundColor: "#fdf0f5",
  },
  loadingText: {
    marginTop: 10,
    color: "#333",
    fontSize: 16,
  },

  list: {
    paddingBottom: 20,
  },
});
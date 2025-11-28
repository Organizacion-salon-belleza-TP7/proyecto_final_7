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
import { cerrarSesion } from "../../../../controladores/controlador_logouts/controlador_logouts";
import { useRouter } from "expo-router";
import { Ionicons } from "@expo/vector-icons";

export default function VistaInicioCliente() {
  const [citas, setCitas] = useState([]);
  const [loading, setLoading] = useState(true);
  const [usuario, setUsuario] = useState(null);
  const [menuVisible, setMenuVisible] = useState(false);

  const router = useRouter();

  useEffect(() => {
    cargarCitas();
  }, []);

  async function cargarCitas() {
    try {
      const userData = await AsyncStorage.getItem("usuarioLogueado");
      if (!userData) {
        Alert.alert("Error", "No hay usuario logueado");
        router.replace('/');
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

  async function handleCerrarSesion() {
    Alert.alert(
      "Cerrar Sesión",
      "¿Estás seguro de que quieres cerrar sesión?",
      [
        { text: "Cancelar", style: "cancel" },
        {
          text: "Cerrar Sesión",
          style: "destructive",
          onPress: async () => {
            try {
              setMenuVisible(false);
              const resultado = await cerrarSesion();
              
              if (resultado.success) {
                router.replace('/');
              } else {
                throw new Error('No se pudo completar el logout');
              }
            } catch (error) {
              Alert.alert("Error", "No se pudo cerrar sesión correctamente");
            }
          },
        },
      ]
    );
  }

  if (loading) {
    return (
      <View style={styles.centerContainer}>
        <ActivityIndicator size="large" color="#ff6b9d" />
        <Text style={styles.loadingText}>Cargando tus citas...</Text>
      </View>
    );
  }

  return (
    <View style={styles.container}>
      {/* Header con título y botón de menú */}
      <View style={styles.header}>
        <TouchableOpacity 
          style={styles.menuButton}
          onPress={() => setMenuVisible(!menuVisible)}
        >
          <Ionicons name="menu" size={28} color="#fff" />
        </TouchableOpacity>
        <Text style={styles.title}>Mis Citas</Text>
        <View style={styles.headerSpacer} />
      </View>

      {/* Menú desplegable a la izquierda */}
      {menuVisible && (
        <View style={styles.dropdownMenu}>
          <TouchableOpacity 
            style={styles.menuItem}
            onPress={handleCerrarSesion}
          >
            <Ionicons name="log-out" size={20} color="#e74c3c" />
            <Text style={styles.logoutText}>Cerrar Sesión</Text>
          </TouchableOpacity>
        </View>
      )}

      {/* Información del usuario */}
      <View style={styles.userInfo}>
        <Ionicons name="person-circle" size={40} color="#ff6b9d" />
        <View style={styles.userTextContainer}>
          <Text style={styles.userName}>{usuario?.nombre_usuario || 'Usuario'}</Text>
          <Text style={styles.userId}>ID: {usuario?.id_usuario}</Text>
        </View>
      </View>

      {/* Botón para comprar nueva cita */}
      <TouchableOpacity
        style={styles.addButton}
        onPress={() => {
          if (!usuario) {
            Alert.alert("Error", "No se encontró información del usuario");
            return;
          }
          router.push({
            pathname: "vista/vista_cli/vista_comp_cita/vista_comp_cita",
            params: { id_cliente: usuario.id_usuario },
          });
        }}
      >
        <Ionicons name="add-circle" size={20} color="#fff" />
        <Text style={styles.addButtonText}>Comprar Nueva Cita</Text>
      </TouchableOpacity>

      <Text style={styles.subtitle}>Total de citas: {citas.length}</Text>

      {/* Lista de citas */}
      <FlatList
        data={citas}
        renderItem={({ item }) => (
          <View style={styles.card}>
            <View style={styles.textContainer}>
              <Text style={styles.cardTitle}>Lugar: {item.nombre_lugar}</Text>
              <Text style={styles.cardText}>Fecha: {item.fecha_cita}</Text>
              <Text style={[
                styles.cardText, 
                styles.statusText,
                item.activo ? styles.activeStatus : styles.inactiveStatus
              ]}>
                Estado: {item.activo ? "Activa" : "Inactiva"}
              </Text>
            </View>
            <View style={styles.buttonContainer}>
              {item.activo ? (
                <TouchableOpacity
                  style={styles.cancelButton}
                  onPress={() => handleCancelarCita(item.id_caja, item.id_cita)}
                >
                  <Text style={styles.buttonText}>Cancelar Cita</Text>
                </TouchableOpacity>
              ) : (
                <TouchableOpacity style={styles.disabledButton} disabled>
                  <Text style={styles.buttonText}>Cita Inactiva</Text>
                </TouchableOpacity>
              )}
            </View>
          </View>
        )}
        keyExtractor={(item, index) =>
          `${item.id_cita ?? "sin-id"}-${item.id_historial_compra ?? index}`
        }
        ListEmptyComponent={
          <View style={styles.emptyContainer}>
            <Ionicons name="calendar-outline" size={60} color="#ccc" />
            <Text style={styles.emptyText}>No tienes citas compradas</Text>
          </View>
        }
        contentContainerStyle={styles.list}
        showsVerticalScrollIndicator={false}
      />
    </View>
  );
}

const styles = StyleSheet.create({
  container: { 
    flex: 1, 
    padding: 16, 
    backgroundColor: '#fdf0f5' 
  },
  header: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 16,
  },
  title: { 
    fontSize: 28, 
    fontWeight: '700', 
    color: '#000',
    textAlign: 'center',
    flex: 1,
  },
  headerSpacer: {
    width: 40, // Mismo ancho que el botón para centrar el título
  },
  menuButton: {
    padding: 8,
    backgroundColor: '#ff6b9d',
    borderRadius: 8,
    width: 40,
    alignItems: 'center',
  },
  dropdownMenu: {
    position: 'absolute',
    top: 60,
    left: 16,
    backgroundColor: 'white',
    borderRadius: 12,
    padding: 8,
    elevation: 5,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.25,
    shadowRadius: 4,
    zIndex: 1000,
    minWidth: 160,
  },
  menuItem: {
    flexDirection: 'row',
    alignItems: 'center',
    paddingVertical: 12,
    paddingHorizontal: 16,
  },
  logoutText: {
    marginLeft: 8,
    fontSize: 16,
    color: '#e74c3c',
    fontWeight: '600',
  },
  userInfo: {
    flexDirection: 'row',
    alignItems: 'center',
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
  userTextContainer: {
    marginLeft: 12,
  },
  userName: {
    fontSize: 18,
    fontWeight: '700',
    color: '#000',
    marginBottom: 4,
  },
  userId: {
    fontSize: 14,
    color: '#666',
  },
  subtitle: { 
    fontSize: 16, 
    marginBottom: 16, 
    textAlign: 'center', 
    color: '#333' 
  },
  addButton: { 
    backgroundColor: '#ff6b9d', 
    paddingVertical: 12, 
    borderRadius: 20, 
    marginBottom: 16, 
    alignItems: 'center',
    flexDirection: 'row',
    justifyContent: 'center',
  },
  addButtonText: {
    color: '#fff', 
    fontWeight: '700', 
    fontSize: 18,
    marginLeft: 8,
  },
  card: { 
    backgroundColor: 'white', 
    padding: 16, 
    borderRadius: 15, 
    marginBottom: 12, 
    elevation: 5, 
    flexDirection: 'row', 
    alignItems: 'center',
    justifyContent: 'space-between',
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 5 },
    shadowOpacity: 0.15,
    shadowRadius: 10,
  },
  textContainer: { 
    flex: 1,
    marginRight: 12,
  },
  cardTitle: { 
    fontSize: 18, 
    fontWeight: '700', 
    marginBottom: 6, 
    color: '#000' 
  },
  cardText: { 
    fontSize: 14, 
    color: '#333', 
    marginBottom: 2 
  },
  statusText: {
    fontWeight: '600',
  },
  activeStatus: {
    color: '#27ae60',
  },
  inactiveStatus: {
    color: '#7f8c8d',
  },
  buttonContainer: { 
    flexDirection: 'column',
  },
  cancelButton: { 
    backgroundColor: '#e74c3c', 
    paddingVertical: 8, 
    paddingHorizontal: 12, 
    borderRadius: 8 
  },
  disabledButton: { 
    backgroundColor: '#7f8c8d', 
    paddingVertical: 8, 
    paddingHorizontal: 12, 
    borderRadius: 8 
  },
  buttonText: { 
    color: '#fff', 
    fontWeight: '700', 
    textAlign: 'center',
    fontSize: 14,
  },
  centerContainer: { 
    flex: 1, 
    justifyContent: 'center', 
    alignItems: 'center', 
    padding: 20 
  },
  loadingText: { 
    marginTop: 10, 
    fontSize: 16, 
    color: '#333' 
  },
  emptyContainer: { 
    alignItems: 'center', 
    marginTop: 50, 
    padding: 20 
  },
  emptyText: { 
    textAlign: "center", 
    marginTop: 15, 
    fontSize: 16, 
    color: '#666' 
  },
  list: { 
    paddingBottom: 20 
  },
});
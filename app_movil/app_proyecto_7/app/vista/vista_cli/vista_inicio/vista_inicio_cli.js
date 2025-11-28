import React, { useEffect, useState } from "react";
import {
  View,
  Text,
  FlatList,
  ActivityIndicator,
  StyleSheet,
  Alert,
  TouchableOpacity,
  Modal,
  Animated,
} from "react-native";
import AsyncStorage from "@react-native-async-storage/async-storage";
import {
  traerCitasCompradas,
  cancelarCita,
} from "../../../../controladores/controladores_cli/controlador_inicio/controlador_inicio_cli";
import { cerrarSesion, verificarSesion } from "../../../../controladores/controlador_logouts/controlador_logouts";
import { useRouter } from "expo-router";
import { Ionicons } from "@expo/vector-icons";

export default function VistaInicioCliente() {
  const [citas, setCitas] = useState([]);
  const [loading, setLoading] = useState(true);
  const [usuario, setUsuario] = useState(null);
  const [menuVisible, setMenuVisible] = useState(false);
  const [slideAnim] = useState(new Animated.Value(-300));

  const router = useRouter();

  useEffect(() => {
    cargarCitas();
  }, []);

  // Animación del menú
  useEffect(() => {
    if (menuVisible) {
      Animated.timing(slideAnim, {
        toValue: 0,
        duration: 300,
        useNativeDriver: true,
      }).start();
    } else {
      Animated.timing(slideAnim, {
        toValue: -300,
        duration: 300,
        useNativeDriver: true,
      }).start();
    }
  }, [menuVisible]);

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

  // 👇 FUNCIÓN DE CERRAR SESIÓN
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
              
              console.log('🔴 Iniciando proceso de logout...');
              const resultado = await cerrarSesion();
              
              if (resultado.success) {
                console.log('✅ Logout exitoso, redirigiendo...');
                // Navegar al login
                router.replace('/');
              } else {
                throw new Error('No se pudo completar el logout');
              }
              
            } catch (error) {
              console.error('❌ Error en cerrar sesión:', error);
              Alert.alert(
                "Error", 
                "No se pudo cerrar sesión correctamente. Intenta nuevamente."
              );
            }
          },
        },
      ]
    );
  }

  if (loading) {
    return (
      <View style={styles.loadingContainer}>
        <ActivityIndicator size="large" color="#e91e63" />
        <Text style={styles.loadingText}>Cargando tus citas...</Text>
      </View>
    );
  }

  return (
    <View style={styles.container}>
      {/* Header con botón de menú */}
      <View style={styles.header}>
        <Text style={styles.headerTitle}>Mis Citas</Text>
        <TouchableOpacity 
          style={styles.menuButton}
          onPress={() => setMenuVisible(true)}
        >
          <Ionicons name="menu" size={28} color="#fff" />
        </TouchableOpacity>
      </View>

      {/* Menú desplegable */}
      <Modal
        visible={menuVisible}
        transparent={true}
        animationType="none"
        onRequestClose={() => setMenuVisible(false)}
      >
        <TouchableOpacity 
          style={styles.menuOverlay}
          activeOpacity={1}
          onPress={() => setMenuVisible(false)}
        >
          <Animated.View 
            style={[
              styles.menuContainer,
              { transform: [{ translateX: slideAnim }] }
            ]}
          >
            <View style={styles.menuHeader}>
              <Text style={styles.menuTitle}>Menú</Text>
              <TouchableOpacity 
                onPress={() => setMenuVisible(false)}
                style={styles.closeButton}
              >
                <Ionicons name="close" size={24} color="#fff" />
              </TouchableOpacity>
            </View>

            <View style={styles.userInfo}>
              <Ionicons name="person-circle" size={50} color="#e91e63" />
              <Text style={styles.userName}>
                {usuario?.nombre_usuario || 'Usuario'}
              </Text>
              <Text style={styles.userId}>ID: {usuario?.id_usuario}</Text>
            </View>

            <TouchableOpacity 
              style={styles.menuItem}
              onPress={() => {
                setMenuVisible(false);
                // Navegar a perfil si lo tienes
              }}
            >
              <Ionicons name="person" size={20} color="#333" />
              <Text style={styles.menuText}>Mi Perfil</Text>
            </TouchableOpacity>

            <TouchableOpacity 
              style={styles.menuItem}
              onPress={() => {
                setMenuVisible(false);
                // Navegar a configuración si lo tienes
              }}
            >
              <Ionicons name="settings" size={20} color="#333" />
              <Text style={styles.menuText}>Configuración</Text>
            </TouchableOpacity>

            <TouchableOpacity 
              style={styles.menuItem}
              onPress={() => {
                setMenuVisible(false);
                cargarCitas();
                Alert.alert("Éxito", "Datos actualizados");
              }}
            >
              <Ionicons name="refresh" size={20} color="#333" />
              <Text style={styles.menuText}>Actualizar</Text>
            </TouchableOpacity>

            <TouchableOpacity 
              style={[styles.menuItem, styles.logoutButton]}
              onPress={handleCerrarSesion}
            >
              <Ionicons name="log-out" size={20} color="#e74c3c" />
              <Text style={[styles.menuText, styles.logoutText]}>Cerrar Sesión</Text>
            </TouchableOpacity>
          </Animated.View>
        </TouchableOpacity>
      </Modal>

      {/* Contenido principal */}
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
          <View style={styles.emptyContainer}>
            <Ionicons name="calendar-outline" size={60} color="#ccc" />
            <Text style={styles.emptyText}>No tienes citas compradas</Text>
          </View>
        }
        ListFooterComponent={
          <TouchableOpacity
            style={styles.btnComprar}
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
            <Text style={styles.btnComprarText}>Comprar Nueva Cita</Text>
          </TouchableOpacity>
        }
        contentContainerStyle={{ paddingBottom: 40 }}
      />
    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: "#fff" },
  loadingContainer: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: '#fff'
  },
  loadingText: {
    marginTop: 10,
    fontSize: 16,
    color: '#666'
  },
  // Header styles
  header: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    backgroundColor: '#e91e63',
    paddingHorizontal: 15,
    paddingVertical: 15,
    paddingTop: 50,
  },
  headerTitle: {
    color: '#fff',
    fontSize: 20,
    fontWeight: 'bold',
  },
  menuButton: {
    padding: 5,
  },
  // Menu styles
  menuOverlay: {
    flex: 1,
    backgroundColor: 'rgba(0,0,0,0.5)',
  },
  menuContainer: {
    position: 'absolute',
    left: 0,
    top: 0,
    bottom: 0,
    width: 300,
    backgroundColor: '#fff',
    paddingTop: 60,
  },
  menuHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    paddingHorizontal: 20,
    paddingVertical: 15,
    borderBottomWidth: 1,
    borderBottomColor: '#eee',
  },
  menuTitle: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#333',
  },
  closeButton: {
    padding: 5,
  },
  userInfo: {
    alignItems: 'center',
    padding: 20,
    borderBottomWidth: 1,
    borderBottomColor: '#eee',
  },
  userName: {
    fontSize: 18,
    fontWeight: 'bold',
    marginTop: 10,
    color: '#333'
  },
  userId: {
    fontSize: 14,
    color: '#666',
    marginTop: 5
  },
  menuItem: {
    flexDirection: 'row',
    alignItems: 'center',
    paddingHorizontal: 20,
    paddingVertical: 15,
    borderBottomWidth: 1,
    borderBottomColor: '#f0f0f0',
  },
  menuText: {
    marginLeft: 15,
    fontSize: 16,
    color: '#333',
  },
  logoutButton: {
    marginTop: 20,
    borderTopWidth: 1,
    borderTopColor: '#eee',
  },
  logoutText: {
    color: '#e74c3c',
    fontWeight: 'bold',
  },
  // Content styles
  title: {
    fontSize: 22,
    fontWeight: "bold",
    marginBottom: 15,
    textAlign: "center",
    marginTop: 20,
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
  card: {
    backgroundColor: "#f5f5f5",
    padding: 15,
    borderRadius: 10,
    marginBottom: 10,
    marginHorizontal: 20,
    borderLeftWidth: 4,
    borderLeftColor: '#e91e63'
  },
  text: { 
    fontSize: 16,
    marginBottom: 5
  },
  btnCancel: {
    marginTop: 10,
    backgroundColor: "#e74c3c",
    padding: 12,
    borderRadius: 6,
    alignItems: "center",
  },
  btnDisabled: {
    marginTop: 10,
    backgroundColor: "#7f8c8d",
    padding: 12,
    borderRadius: 6,
    alignItems: "center",
  },
  btnText: { 
    color: "#fff", 
    fontWeight: "bold",
    fontSize: 16
  },
  btnComprar: {
    marginTop: 25,
    backgroundColor: "#e91e63",
    padding: 16,
    borderRadius: 10,
    alignItems: "center",
    marginHorizontal: 20,
    flexDirection: 'row',
    justifyContent: 'center'
  },
  btnComprarText: {
    color: "#fff",
    fontWeight: "bold",
    fontSize: 18,
    marginLeft: 10
  },
});
import React, { useState, useEffect, useCallback } from "react";
import { 
  View, 
  Text, 
  FlatList, 
  ActivityIndicator, 
  Alert, 
  StyleSheet, 
  TouchableOpacity, 
  Image 
} from "react-native";
import AsyncStorage from "@react-native-async-storage/async-storage";
import { getInventario, deleteProducto } from "../../../../controladores/controladores_adm/inicio/controlador_inicio";
import { useFocusEffect } from '@react-navigation/native';
import { useRouter } from "expo-router";
import { Ionicons } from "@expo/vector-icons";

const IMAGE_BASE_URL = "http://10.0.2.206/proyecto_final_7/imagenes/inventario/";

export default function InventarioScreen() {
  const [productos, setProductos] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [usuario, setUsuario] = useState(null);
  const router = useRouter();

  const fetchProductos = async () => {
    try {
      setLoading(true);
      setError(null);
      const data = await getInventario();
      setProductos(data);
    } catch (err) {
      setError(err.message);
      Alert.alert("Error", err.message);
    } finally {
      setLoading(false);
    }
  };

  const cargarUsuario = async () => {
    try {
      const userData = await AsyncStorage.getItem("usuarioLogueado");
      if (userData) {
        const user = JSON.parse(userData);
        setUsuario(user);
      }
    } catch (error) {
      console.error("Error al cargar usuario:", error);
    }
  };

  useFocusEffect(
    useCallback(() => {
      fetchProductos();
      cargarUsuario();
    }, [])
  );

  const handleDelete = async (id) => {
    Alert.alert(
      "Confirmar Eliminación",
      "¿Estás seguro de que quieres eliminar este producto?",
      [
        { text: "Cancelar", style: "cancel" },
        {
          text: "Eliminar",
          style: "destructive",
          onPress: async () => {
            try {
              await deleteProducto(id);
              fetchProductos();
            } catch (err) {
              Alert.alert("Error", err.message);
            }
          },
        },
      ]
    );
  };

  const renderItem = ({ item }) => (
    <View style={styles.card}>
      <Image 
        source={{ uri: `${IMAGE_BASE_URL}${item.imagen_producto}` }}
        style={styles.productImage}
      />
      <View style={styles.textContainer}>
        <Text style={styles.cardTitle}>{item.nombre_producto}</Text>
        <Text style={styles.cardText}>Stock: {item.stock}</Text>
        <Text style={styles.cardText}>Vencimiento: {item.vencimiento}</Text>
        <Text style={styles.cardText}>Precio Compra: ${item.precio_producto}</Text>
        <Text style={styles.cardText}>Precio Venta: ${item.precio_venta}</Text>
        <Text style={styles.cardText}>Proveedor: {item.nombre_proveedor}</Text>
      </View>
      <View style={styles.buttonContainer}>
        <TouchableOpacity 
          style={styles.modifyButton} 
          onPress={() => router.push(`/vista/vista_adm/inicio/vista_modificar_producto?id=${item.id_inventario}`)}
        >
          <Text style={styles.buttonText}>Modificar</Text>
        </TouchableOpacity>
        <TouchableOpacity 
          style={styles.deleteButton} 
          onPress={() => handleDelete(item.id_inventario)}
        >
          <Text style={styles.buttonText}>Eliminar</Text>
        </TouchableOpacity>
      </View>
    </View>
  );

  if (loading) {
    return (
      <View style={styles.centerContainer}>
        <ActivityIndicator size="large" color="#ff6b9d" />
        <Text style={styles.loadingText}>Cargando inventario...</Text>
      </View>
    );
  }

  if (error) {
    return (
      <View style={styles.centerContainer}>
        <Text style={styles.errorText}>Error: {error}</Text>
        <TouchableOpacity style={styles.retryButton} onPress={fetchProductos}>
          <Text style={styles.buttonText}>Reintentar</Text>
        </TouchableOpacity>
      </View>
    );
  }

  return (
    <View style={styles.container}>
      {/* Información del usuario */}
      <View style={styles.userInfo}>
        <Ionicons name="person-circle" size={40} color="#ff6b9d" />
        <View style={styles.userTextContainer}>
          <Text style={styles.userName}>{usuario?.nombre_usuario || 'Administrador'}</Text>
          <Text style={styles.userId}>ID: {usuario?.id_usuario}</Text>
        </View>
      </View>

      <Text style={styles.title}>Inventario de Productos</Text>
      
      {/* Botón para agregar producto */}
      <TouchableOpacity 
        style={styles.addButton} 
        onPress={() => router.push("/vista/vista_adm/inicio/vista_agregar_producto")}
      >
        <Ionicons name="add-circle" size={20} color="#fff" />
        <Text style={styles.addButtonText}>Agregar Producto</Text>
      </TouchableOpacity>

      <Text style={styles.subtitle}>Total de productos: {productos.length}</Text>

      {/* Lista de productos */}
      <FlatList
        data={productos}
        renderItem={renderItem}
        keyExtractor={(item) => item.id_inventario.toString()}
        ListEmptyComponent={
          <View style={styles.emptyContainer}>
            <Ionicons name="cube-outline" size={60} color="#ccc" />
            <Text style={styles.emptyText}>No hay productos en el inventario</Text>
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
  title: { 
    fontSize: 28, 
    fontWeight: '700', 
    marginBottom: 12, 
    textAlign: 'center', 
    color: '#000' 
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
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 5 },
    shadowOpacity: 0.15,
    shadowRadius: 10,
  },
  productImage: { 
    width: 80, 
    height: 80, 
    resizeMode: 'cover', 
    marginRight: 16, 
    borderRadius: 10 
  },
  textContainer: { 
    flex: 1 
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
  buttonContainer: { 
    flexDirection: 'column', 
    justifyContent: 'space-between', 
    marginLeft: 10 
  },
  modifyButton: { 
    backgroundColor: '#ff6b9d', 
    paddingVertical: 8, 
    paddingHorizontal: 12, 
    borderRadius: 8, 
    marginBottom: 6 
  },
  deleteButton: { 
    backgroundColor: '#e74c3c', 
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
  errorText: { 
    color: 'red', 
    textAlign: 'center', 
    marginBottom: 20, 
    fontSize: 16 
  },
  retryButton: { 
    backgroundColor: '#ff6b9d', 
    paddingVertical: 10, 
    paddingHorizontal: 20, 
    borderRadius: 12 
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
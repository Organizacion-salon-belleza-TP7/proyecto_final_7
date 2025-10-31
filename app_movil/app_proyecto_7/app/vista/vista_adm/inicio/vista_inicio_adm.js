import React, { useState, useEffect, useCallback } from "react";
import { 
  View, 
  Text, 
  FlatList, 
  ActivityIndicator, 
  Alert, 
  StyleSheet, 
  Button, 
  Image 
} from "react-native";
import { getInventario, deleteProducto } from "../../../../controladores/controladores_adm/inicio/controlador_inicio";
import { useFocusEffect } from '@react-navigation/native';
import { useRouter } from "expo-router";

const IMAGE_BASE_URL = "http://192.168.100.8/proyecto_final_7/imagenes/inventario/";

export default function InventarioScreen() {
  const [productos, setProductos] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
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

  useFocusEffect(
    useCallback(() => {
      fetchProductos();
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
        <Text>Stock: {item.stock}</Text>
        <Text>Vencimiento: {item.vencimiento}</Text>
        <Text>Precio Compra: ${item.precio_producto}</Text>
        <Text>Precio Venta: ${item.precio_venta}</Text>
        <Text>Proveedor: {item.nombre_proveedor}</Text>
      </View>
      <View style={styles.buttonContainer}>
        <Button 
          title="Modificar" 
          onPress={() => router.push(`/vista/vista_adm/inicio/vista_modificar_producto?id=${item.id_inventario}`)} 
        />
        <Button 
          title="Eliminar" 
          onPress={() => handleDelete(item.id_inventario)} 
          color="red" 
        />
      </View>
    </View>
  );

  if (loading) {
    return (
      <View style={styles.centerContainer}>
        <ActivityIndicator size="large" color="#0000ff" />
        <Text style={styles.loadingText}>Cargando inventario...</Text>
      </View>
    );
  }

  if (error) {
    return (
      <View style={styles.centerContainer}>
        <Text style={styles.errorText}>Error: {error}</Text>
        <Button title="Reintentar" onPress={fetchProductos} />
      </View>
    );
  }

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Inventario de Productos</Text>
      <Button 
        title="Agregar Producto" 
        onPress={() => router.push("/vista/vista_adm/inicio/vista_agregar_producto")} 
      />
      <Text style={styles.subtitle}>Total de productos: {productos.length}</Text>
      <FlatList
        data={productos}
        renderItem={renderItem}
        keyExtractor={(item) => item.id_inventario.toString()}
        contentContainerStyle={styles.list}
        showsVerticalScrollIndicator={false}
      />
    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, padding: 16, backgroundColor: '#f5f5f5' },
  title: { fontSize: 24, fontWeight: 'bold', marginBottom: 16, textAlign: 'center', color: '#333' },
  subtitle: { fontSize: 16, marginBottom: 16, textAlign: 'center', color: '#666' },
  card: { backgroundColor: 'white', padding: 16, borderRadius: 8, marginBottom: 12, elevation: 3, flexDirection: 'row', alignItems: 'center' },
  productImage: { width: 80, height: 80, resizeMode: 'cover', marginRight: 16, borderRadius: 4 },
  textContainer: { flex: 1 },
  cardTitle: { fontSize: 18, fontWeight: 'bold', marginBottom: 4, color: '#333' },
  buttonContainer: { flexDirection: 'column', justifyContent: 'space-around', marginLeft: 10, height: 80 },
  centerContainer: { flex: 1, justifyContent: 'center', alignItems: 'center', padding: 20 },
  loadingText: { marginTop: 10, fontSize: 16, color: '#666' },
  errorText: { color: 'red', textAlign: 'center', marginBottom: 20, fontSize: 16 },
  list: { paddingBottom: 20 }
});

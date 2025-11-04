import React, { useState, useEffect, useCallback } from "react";
import { 
  View, 
  Text, 
  FlatList, 
  ActivityIndicator, 
  Alert,
  TouchableOpacity, 
  Image 
} from "react-native";
import { getInventario, deleteProducto } from "../../../../controladores/controladores_adm/inicio/controlador_inicio";
import { useFocusEffect } from '@react-navigation/native';
import { useRouter } from "expo-router";
// ⬇️ Importación clave para usar el safe area context
import { useSafeAreaInsets } from 'react-native-safe-area-context'; 

import { styles } from "../../css/inventario_styles"; 

const IMAGE_BASE_URL = "http://192.168.0.20/proyecto_final_7/imagenes/inventario/";

export default function InventarioScreen() {
  const [productos, setProductos] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const router = useRouter();
  
  // ⬅️ Obtener los insets (márgenes de la zona segura)
  const insets = useSafeAreaInsets(); 

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
    // ⬅️ El estilo se aplica con los insets
    <View style={[styles.container, { paddingTop: insets.top, paddingBottom: insets.bottom }]}>
      <Text style={styles.title}>Inventario de Productos</Text>
      <TouchableOpacity 
        style={styles.addButton} 
        onPress={() => router.push("/vista/vista_adm/inicio/vista_agregar_producto")}
      >
        <Text style={styles.buttonText}>Agregar Producto</Text>
      </TouchableOpacity>
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
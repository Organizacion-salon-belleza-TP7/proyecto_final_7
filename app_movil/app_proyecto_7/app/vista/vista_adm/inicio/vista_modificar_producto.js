// app/vista/vista_adm/inventario/vista_modificar_producto.js
import React, { useState, useEffect } from "react";
import { 
  View, 
  Text, 
  TextInput, 
  Button, 
  Alert, 
  StyleSheet, 
  ScrollView,
  ActivityIndicator 
} from "react-native";
import { getProductoById, modificarProducto } from "../../../../controladores/controladores_adm/inicio/controlador_inicio";
import { useRouter, useLocalSearchParams } from "expo-router";

export default function ModificarProductoScreen() {
  const { id } = useLocalSearchParams();
  const [formData, setFormData] = useState({
    nombre_producto: '',
    stock: '',
    vencimiento: '',
    precio_producto: '',
    precio_venta: '',
    imagen_producto: '',
    id_proveedor: ''
  });
  const [loading, setLoading] = useState(true);
  const [saving, setSaving] = useState(false);
  const router = useRouter();

  useEffect(() => {
    cargarProducto();
  }, [id]);

  const cargarProducto = async () => {
    try {
      setLoading(true);
      const producto = await getProductoById(id);
      setFormData({
        nombre_producto: producto.nombre_producto,
        stock: producto.stock.toString(),
        vencimiento: producto.vencimiento,
        precio_producto: producto.precio_producto.toString(),
        precio_venta: producto.precio_venta.toString(),
        imagen_producto: producto.imagen_producto,
        id_proveedor: producto.id_proveedor?.toString() || ''
      });
    } catch (error) {
      Alert.alert("Error", error.message);
      router.back();
    } finally {
      setLoading(false);
    }
  };

  const handleChange = (field, value) => {
    setFormData(prev => ({
      ...prev,
      [field]: value
    }));
  };

  const handleSubmit = async () => {
    if (!formData.nombre_producto || !formData.stock || !formData.precio_venta) {
      Alert.alert("Error", "Por favor complete los campos obligatorios");
      return;
    }

    setSaving(true);
    try {
      await modificarProducto(id, formData);
      Alert.alert("Éxito", "Producto modificado correctamente", [
        { text: "OK", onPress: () => router.back() }
      ]);
    } catch (error) {
      Alert.alert("Error", error.message);
    } finally {
      setSaving(false);
    }
  };

  if (loading) {
    return (
      <View style={styles.centerContainer}>
        <ActivityIndicator size="large" color="#0000ff" />
        <Text>Cargando producto...</Text>
      </View>
    );
  }

  if (saving) {
    return (
      <View style={styles.centerContainer}>
        <ActivityIndicator size="large" color="#0000ff" />
        <Text>Guardando cambios...</Text>
      </View>
    );
  }

  return (
    <ScrollView style={styles.container}>
      <Text style={styles.title}>Modificar Producto</Text>
      <Text style={styles.productId}>ID: {id}</Text>
      
      <TextInput
        style={styles.input}
        placeholder="Nombre del producto *"
        value={formData.nombre_producto}
        onChangeText={(text) => handleChange('nombre_producto', text)}
      />
      
      <TextInput
        style={styles.input}
        placeholder="Stock *"
        value={formData.stock}
        onChangeText={(text) => handleChange('stock', text)}
        keyboardType="numeric"
      />
      
      <TextInput
        style={styles.input}
        placeholder="Fecha de vencimiento (YYYY-MM-DD)"
        value={formData.vencimiento}
        onChangeText={(text) => handleChange('vencimiento', text)}
      />
      
      <TextInput
        style={styles.input}
        placeholder="Precio de compra"
        value={formData.precio_producto}
        onChangeText={(text) => handleChange('precio_producto', text)}
        keyboardType="numeric"
      />
      
      <TextInput
        style={styles.input}
        placeholder="Precio de venta *"
        value={formData.precio_venta}
        onChangeText={(text) => handleChange('precio_venta', text)}
        keyboardType="numeric"
      />
      
      <TextInput
        style={styles.input}
        placeholder="Nombre de la imagen"
        value={formData.imagen_producto}
        onChangeText={(text) => handleChange('imagen_producto', text)}
      />
      
      <TextInput
        style={styles.input}
        placeholder="ID del proveedor"
        value={formData.id_proveedor}
        onChangeText={(text) => handleChange('id_proveedor', text)}
        keyboardType="numeric"
      />
      
      <View style={styles.buttonContainer}>
        <Button title="Cancelar" onPress={() => router.back()} color="gray" />
        <Button title="Guardar Cambios" onPress={handleSubmit} />
      </View>
    </ScrollView>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    padding: 16,
    backgroundColor: '#f5f5f5'
  },
  title: {
    fontSize: 24,
    fontWeight: 'bold',
    marginBottom: 10,
    textAlign: 'center'
  },
  productId: {
    textAlign: 'center',
    marginBottom: 20,
    color: '#666'
  },
  input: {
    backgroundColor: 'white',
    padding: 12,
    marginBottom: 12,
    borderRadius: 6,
    borderWidth: 1,
    borderColor: '#ddd'
  },
  buttonContainer: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    marginTop: 20
  },
  centerContainer: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center'
  }
});
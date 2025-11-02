import React, { useState, useEffect } from "react";
import { 
  View, 
  Text, 
  TextInput, 
  ScrollView, 
  TouchableOpacity, 
  ActivityIndicator, 
  StyleSheet, 
  Alert 
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

  if (loading || saving) {
    return (
      <View style={styles.centerContainer}>
        <ActivityIndicator size="large" color="#ff6b9d" />
        <Text style={styles.loadingText}>{loading ? 'Cargando producto...' : 'Guardando cambios...'}</Text>
      </View>
    );
  }

  return (
    <ScrollView style={styles.container} contentContainerStyle={{ paddingBottom: 50 }}>
      <Text style={styles.title}>Modificar Producto</Text>
      <Text style={styles.productId}>ID: {id}</Text>

      <Text style={styles.label}>Nombre del producto *</Text>
      <TextInput
        style={styles.input}
        placeholder="Nombre del producto"
        placeholderTextColor="#999"
        value={formData.nombre_producto}
        onChangeText={(text) => handleChange('nombre_producto', text)}
      />

      <Text style={styles.label}>Stock *</Text>
      <TextInput
        style={styles.input}
        placeholder="Stock"
        placeholderTextColor="#999"
        value={formData.stock}
        onChangeText={(text) => handleChange('stock', text)}
        keyboardType="numeric"
      />

      <Text style={styles.label}>Fecha de vencimiento</Text>
      <TextInput
        style={styles.input}
        placeholder="YYYY-MM-DD"
        placeholderTextColor="#999"
        value={formData.vencimiento}
        onChangeText={(text) => handleChange('vencimiento', text)}
      />

      <Text style={styles.label}>Precio de compra</Text>
      <TextInput
        style={styles.input}
        placeholder="Precio de compra"
        placeholderTextColor="#999"
        value={formData.precio_producto}
        onChangeText={(text) => handleChange('precio_producto', text)}
        keyboardType="numeric"
      />

      <Text style={styles.label}>Precio de venta *</Text>
      <TextInput
        style={styles.input}
        placeholder="Precio de venta"
        placeholderTextColor="#999"
        value={formData.precio_venta}
        onChangeText={(text) => handleChange('precio_venta', text)}
        keyboardType="numeric"
      />

      <Text style={styles.label}>Nombre de la imagen</Text>
      <TextInput
        style={styles.input}
        placeholder="Imagen"
        placeholderTextColor="#999"
        value={formData.imagen_producto}
        onChangeText={(text) => handleChange('imagen_producto', text)}
      />

      <Text style={styles.label}>ID del proveedor</Text>
      <TextInput
        style={styles.input}
        placeholder="ID proveedor"
        placeholderTextColor="#999"
        value={formData.id_proveedor}
        onChangeText={(text) => handleChange('id_proveedor', text)}
        keyboardType="numeric"
      />

      <View style={styles.buttonContainer}>
        <TouchableOpacity style={styles.cancelButton} onPress={() => router.back()}>
          <Text style={styles.buttonText}>Cancelar</Text>
        </TouchableOpacity>
        <TouchableOpacity style={styles.submitButton} onPress={handleSubmit}>
          <Text style={styles.buttonText}>Guardar Cambios</Text>
        </TouchableOpacity>
      </View>
    </ScrollView>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, padding: 16, backgroundColor: '#fdf0f5' },
  title: { fontSize: 28, fontWeight: '700', textAlign: 'center', marginBottom: 10, color: '#000' },
  productId: { textAlign: 'center', marginBottom: 20, color: '#666' },
  label: { fontSize: 16, marginBottom: 5, fontWeight: '700', color: '#000' },
  input: { backgroundColor: 'white', padding: 12, marginBottom: 12, borderRadius: 12, borderWidth: 1, borderColor: '#ff6b9d', color: '#000' },
  buttonContainer: { flexDirection: 'row', justifyContent: 'space-between', marginTop: 20 },
  cancelButton: { flex: 1, backgroundColor: '#999', paddingVertical: 12, borderRadius: 20, alignItems: 'center', marginRight: 8 },
  submitButton: { flex: 1, backgroundColor: '#ff6b9d', paddingVertical: 12, borderRadius: 20, alignItems: 'center', marginLeft: 8 },
  buttonText: { color: '#fff', fontWeight: '700', textAlign: 'center' },
  centerContainer: { flex: 1, justifyContent: 'center', alignItems: 'center' },
  loadingText: { marginTop: 10, fontSize: 16, color: '#333' }
});

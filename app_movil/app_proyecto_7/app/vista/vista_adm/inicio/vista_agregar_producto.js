// app/vista/vista_adm/inventario/vista_agregar_producto.js
import React, { useState, useEffect } from "react";
import { 
  View, 
  Text, 
  TextInput, 
  Button, 
  Alert, 
  StyleSheet, 
  ScrollView,
  ActivityIndicator,
  Platform,
  TouchableOpacity,
  Image
} from "react-native";
import { Picker } from '@react-native-picker/picker';
import DateTimePicker from '@react-native-community/datetimepicker';
import * as ImagePicker from 'expo-image-picker';
import { agregarProducto, getProveedores } from "../../../../controladores/controladores_adm/inicio/controlador_inicio";
import { useRouter } from "expo-router";

// URL base para las imágenes
const IMAGE_BASE_URL = "http://192.168.100.8/proyecto_final_7/imagenes/inventario/";

export default function AgregarProductoScreen() {
  const [formData, setFormData] = useState({
    nombre_producto: '',
    stock: '',
    vencimiento: '',
    precio_producto: '',
    precio_venta: '',
    id_proveedor: ''
  });
  const [proveedores, setProveedores] = useState([]);
  const [loading, setLoading] = useState(false);
  const [proveedoresLoading, setProveedoresLoading] = useState(true);
  const router = useRouter();

  const [date, setDate] = useState(new Date());
  const [showDatePicker, setShowDatePicker] = useState(false);
  const [image, setImage] = useState(null);

  useEffect(() => {
    const cargarProveedores = async () => {
      try {
        setProveedoresLoading(true);
        const proveedoresData = await getProveedores();
        setProveedores(proveedoresData);
        if (proveedoresData.length > 0) {
          setFormData(prev => ({
            ...prev,
            id_proveedor: proveedoresData[0].id_proveedor.toString() 
          }));
        }
      } catch (error) {
        console.error("Error cargando proveedores:", error);
        Alert.alert("Error", "No se pudieron cargar los proveedores.");
      } finally {
        setProveedoresLoading(false);
      }
    };
    cargarProveedores();
  }, []);

  const handleChange = (field, value) => {
    setFormData(prev => ({
      ...prev,
      [field]: value
    }));
  };

  const handleDateChange = (event, selectedDate) => {
    const currentDate = selectedDate || date;
    setShowDatePicker(Platform.OS === 'ios');
    setDate(currentDate);
    const formattedDate = currentDate.toISOString().split('T')[0];
    handleChange('vencimiento', formattedDate);
  };

  const showDatepicker = () => {
    setShowDatePicker(true);
  };
  
  const pickImage = async () => {
    const { status } = await ImagePicker.requestMediaLibraryPermissionsAsync();
    if (status !== 'granted') {
      Alert.alert('Permiso denegado', 'Se necesita permiso para acceder a la galería.');
      return;
    }
    
    let result = await ImagePicker.launchImageLibraryAsync({
      mediaTypes: ImagePicker.MediaTypeOptions.Images,
      allowsEditing: true,
      aspect: [4, 3],
      quality: 1,
    });

    if (!result.canceled) {
      setImage(result.assets[0].uri);
    }
  };

  const handleSubmit = async () => {
    if (!formData.nombre_producto || !formData.stock || !formData.precio_venta || !formData.id_proveedor) {
      Alert.alert("Error", "Por favor complete los campos obligatorios (*)");
      return;
    }

    if (!image) {
      Alert.alert("Error", "Por favor, seleccione una imagen para el producto.");
      return;
    }

    setLoading(true);
    try {
      const data = new FormData();
      data.append('nombre_producto', formData.nombre_producto);
      data.append('stock', parseInt(formData.stock));
      data.append('vencimiento', formData.vencimiento);
      data.append('precio_producto', parseFloat(formData.precio_producto || 0));
      data.append('precio_venta', parseFloat(formData.precio_venta));
      data.append('id_proveedor', parseInt(formData.id_proveedor));
      
      data.append('imagen', {
        uri: image,
        name: `producto_${Date.now()}.jpg`,
        type: 'image/jpeg',
      });
      
      await agregarProducto(data);

      Alert.alert("Éxito", "Producto agregado correctamente", [
        { text: "OK", onPress: () => router.back() }
      ]);
    } catch (error) {
      Alert.alert("Error", error.message);
    } finally {
      setLoading(false);
    }
  };

  if (loading || proveedoresLoading) {
    return (
      <View style={styles.centerContainer}>
        <ActivityIndicator size="large" color="#0000ff" />
        <Text style={styles.loadingText}>{loading ? 'Agregando producto...' : 'Cargando proveedores...'}</Text>
      </View>
    );
  }

  return (
    <ScrollView 
      style={styles.container}
      // ✅ Agrega esta propiedad para crear un espacio al final de la lista
      contentContainerStyle={{ paddingBottom: 100 }}
    >
      <Text style={styles.title}>Agregar Nuevo Producto</Text>
      
      <Text style={styles.label}>Nombre del producto *</Text>
      <TextInput
        style={styles.input}
        placeholder="Ej: Leche Deslactosada"
        value={formData.nombre_producto}
        onChangeText={(text) => handleChange('nombre_producto', text)}
      />
      
      <Text style={styles.label}>Stock *</Text>
      <TextInput
        style={styles.input}
        placeholder="Ej: 50"
        value={formData.stock}
        onChangeText={(text) => handleChange('stock', text)}
        keyboardType="numeric"
      />
      
      <Text style={styles.label}>Fecha de vencimiento</Text>
      <TouchableOpacity onPress={showDatepicker}>
        <TextInput
          style={styles.input}
          placeholder="Seleccionar fecha"
          value={formData.vencimiento}
          editable={false}
        />
      </TouchableOpacity>
      {showDatePicker && (
        <DateTimePicker
          value={date}
          mode="date"
          display="default"
          onChange={handleDateChange}
        />
      )}
      
      <Text style={styles.label}>Precio de compra</Text>
      <TextInput
        style={styles.input}
        placeholder="Ej: 15.50"
        value={formData.precio_producto}
        onChangeText={(text) => handleChange('precio_producto', text)}
        keyboardType="numeric"
      />
      
      <Text style={styles.label}>Precio de venta *</Text>
      <TextInput
        style={styles.input}
        placeholder="Ej: 25.00"
        value={formData.precio_venta}
        onChangeText={(text) => handleChange('precio_venta', text)}
        keyboardType="numeric"
      />
      
      <Text style={styles.label}>Imagen del producto</Text>
      <Button title="Seleccionar Imagen" onPress={pickImage} />
      {image && <Image source={{ uri: image }} style={styles.imagePreview} />}
      
      <Text style={styles.label}>Proveedor *</Text>
      <View style={styles.pickerContainer}>
        <Picker
          selectedValue={formData.id_proveedor}
          onValueChange={(itemValue) => handleChange('id_proveedor', itemValue)}
          style={styles.picker}
        >
          {proveedores.map(proveedor => (
            <Picker.Item 
              key={proveedor.id_proveedor}
              label={proveedor.nombre_proveedor} 
              value={proveedor.id_proveedor.toString()}
            />
          ))}
        </Picker>
      </View>
      
      <View style={styles.buttonContainer}>
        <Button title="Cancelar" onPress={() => router.back()} color="gray" />
        <Button title="Agregar Producto" onPress={handleSubmit} />
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
    marginBottom: 20,
    textAlign: 'center',
    color: '#333'
  },
  label: {
    fontSize: 16,
    marginBottom: 5,
    fontWeight: 'bold',
    color: '#555'
  },
  input: {
    backgroundColor: 'white',
    padding: 12,
    marginBottom: 12,
    borderRadius: 6,
    borderWidth: 1,
    borderColor: '#ddd'
  },
  pickerContainer: {
    backgroundColor: 'white',
    borderRadius: 6,
    borderWidth: 1,
    borderColor: '#ddd',
    marginBottom: 12
  },
  picker: {
    height: 50,
    width: '100%',
  },
  imagePreview: {
    width: 200,
    height: 200,
    resizeMode: 'cover',
    marginTop: 10,
    alignSelf: 'center',
    borderRadius: 10
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
  },
  loadingText: {
    marginTop: 10,
    fontSize: 16,
    color: '#666'
  }
});
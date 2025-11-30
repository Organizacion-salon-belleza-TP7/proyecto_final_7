import React, { useState, useEffect } from "react";
import {
  View,
  Text,
  TextInput,
  ScrollView,
  TouchableOpacity,
  ActivityIndicator,
  StyleSheet,
  Alert,
  Image,
} from "react-native";
import { Picker } from "@react-native-picker/picker";
import * as ImagePicker from 'expo-image-picker';
import {
  getProductoById,
  modificarProducto,
  modificarProductoConImagen,
  getProveedores,
  getUrlImagen,
  validarProducto,
} from "../../../../controladores/controladores_adm/inicio/controlador_inicio";
import { useRouter, useLocalSearchParams } from "expo-router";

export default function ModificarProductoScreen() {
  const { id } = useLocalSearchParams();
  const [formData, setFormData] = useState({
    nombre_producto: "",
    stock: "",
    vencimiento: "",
    precio_producto: "",
    precio_venta: "",
    imagen_producto: "",
    id_proveedor: "",
  });
  const [proveedores, setProveedores] = useState([]);
  const [loading, setLoading] = useState(true);
  const [saving, setSaving] = useState(false);
  const [imagenSeleccionada, setImagenSeleccionada] = useState(null);
  const [imagenUri, setImagenUri] = useState(null);
  const [permisosConcedidos, setPermisosConcedidos] = useState(false);
  const router = useRouter();

  useEffect(() => {
    cargarProducto();
    cargarProveedores();
    solicitarPermisos();
  }, [id]);

  const solicitarPermisos = async () => {
    try {
      console.log('Solicitando permisos...');
      const { status } = await ImagePicker.requestMediaLibraryPermissionsAsync();
      console.log('Permisos galería:', status);
      
      if (status !== 'granted') {
        Alert.alert(
          'Permisos necesarios', 
          'Se necesitan permisos para acceder a la galería.',
          [{ text: 'OK' }]
        );
        return;
      }
      
      const cameraStatus = await ImagePicker.requestCameraPermissionsAsync();
      console.log('Permisos cámara:', cameraStatus.status);
      
      setPermisosConcedidos(true);
    } catch (error) {
      console.error('Error al solicitar permisos:', error);
      Alert.alert('Error', 'No se pudieron solicitar los permisos');
    }
  };

  const cargarProducto = async () => {
    try {
      setLoading(true);
      const producto = await getProductoById(id);
      setFormData({
        nombre_producto: producto.nombre_producto,
        stock: producto.stock.toString(),
        vencimiento: producto.vencimiento || '',
        precio_producto: producto.precio_producto?.toString() || '',
        precio_venta: producto.precio_venta.toString(),
        imagen_producto: producto.imagen_producto || '',
        id_proveedor: producto.id_proveedor?.toString() || "",
      });

      // Si ya tiene una imagen, mostrar preview
      if (producto.imagen_producto) {
        setImagenUri(getUrlImagen(producto.imagen_producto));
      }
    } catch (error) {
      console.error("Error al cargar producto:", error);
      Alert.alert("Error", error.message);
      router.back();
    } finally {
      setLoading(false);
    }
  };

  const cargarProveedores = async () => {
    try {
      const data = await getProveedores();
      setProveedores(data);
    } catch (error) {
      console.error("Error al cargar proveedores:", error);
      Alert.alert("Error", "No se pudieron cargar los proveedores");
    }
  };

  const seleccionarImagen = async () => {
    try {
      if (!permisosConcedidos) {
        Alert.alert('Permisos requeridos', 'Primero necesitas conceder permisos para acceder a la galería.');
        return;
      }

      console.log('Abriendo galería...');
      
      // FORMA CORREGIDA - Usar MediaTypeOptions en lugar de MediaType
      const resultado = await ImagePicker.launchImageLibraryAsync({
        mediaTypes: ImagePicker.MediaTypeOptions.Images, // ← CORREGIDO
        allowsEditing: true,
        aspect: [4, 3],
        quality: 0.8,
      });

      console.log('Resultado de imagen:', resultado);

      if (!resultado.canceled && resultado.assets && resultado.assets.length > 0) {
        const imagen = resultado.assets[0];
        const imagenObjeto = {
          uri: imagen.uri,
          fileName: `producto_${id}_${Date.now()}.jpg`,
          type: 'image/jpeg'
        };
        
        setImagenSeleccionada(imagenObjeto);
        setImagenUri(imagen.uri);
        
        console.log('Imagen seleccionada:', imagenObjeto);
        
        Alert.alert('Éxito', 'Imagen seleccionada correctamente');
      } else {
        console.log('Selección de imagen cancelada');
      }
    } catch (error) {
      console.error("Error al seleccionar imagen:", error);
      Alert.alert("Error", "No se pudo seleccionar la imagen: " + error.message);
    }
  };

  const tomarFoto = async () => {
    try {
      if (!permisosConcedidos) {
        Alert.alert('Permisos requeridos', 'Primero necesitas conceder permisos para usar la cámara.');
        return;
      }

      console.log('Abriendo cámara...');

      const resultado = await ImagePicker.launchCameraAsync({
        allowsEditing: true,
        aspect: [4, 3],
        quality: 0.8,
      });

      console.log('Resultado de cámara:', resultado);

      if (!resultado.canceled && resultado.assets && resultado.assets.length > 0) {
        const imagen = resultado.assets[0];
        const imagenObjeto = {
          uri: imagen.uri,
          fileName: `producto_${id}_${Date.now()}.jpg`,
          type: 'image/jpeg'
        };
        
        setImagenSeleccionada(imagenObjeto);
        setImagenUri(imagen.uri);
        
        console.log('Foto tomada:', imagenObjeto);
        
        Alert.alert('Éxito', 'Foto tomada correctamente');
      } else {
        console.log('Toma de foto cancelada');
      }
    } catch (error) {
      console.error("Error al tomar foto:", error);
      Alert.alert("Error", "No se pudo tomar la foto: " + error.message);
    }
  };

  const handleChange = (field, value) => {
    setFormData((prev) => ({
      ...prev,
      [field]: value,
    }));
  };

  const handleSubmit = async () => {
    // Validar datos antes de enviar
    const errores = validarProducto(formData);
    if (errores.length > 0) {
      Alert.alert("Error", errores.join('\n'));
      return;
    }

    setSaving(true);
    try {
      console.log('Iniciando modificación del producto...');
      
      // Preparar datos numéricos
      const datosParaEnviar = {
        ...formData,
        stock: parseInt(formData.stock) || 0,
        precio_producto: parseFloat(formData.precio_producto) || 0,
        precio_venta: parseFloat(formData.precio_venta) || 0,
      };

      // Si hay una nueva imagen seleccionada, usar FormData
      if (imagenSeleccionada) {
        console.log('Modificando con imagen...');
        await modificarProductoConImagen(id, datosParaEnviar, imagenSeleccionada);
      } else {
        // Si no hay imagen nueva, enviar solo los datos
        console.log('Modificando sin imagen...');
        await modificarProducto(id, datosParaEnviar);
      }
      
      Alert.alert(
        "Éxito", 
        "Producto modificado correctamente", 
        [
          { 
            text: "OK", 
            onPress: () => router.back() 
          }
        ]
      );
    } catch (error) {
      console.error('Error en handleSubmit:', error);
      Alert.alert("Error", error.message);
    } finally {
      setSaving(false);
    }
  };

  const eliminarImagen = () => {
    setImagenSeleccionada(null);
    setImagenUri(null);
    setFormData(prev => ({
      ...prev,
      imagen_producto: ""
    }));
    Alert.alert("Imagen eliminada", "La imagen ha sido removida del producto");
  };

  const confirmarEliminarImagen = () => {
    Alert.alert(
      "Eliminar imagen",
      "¿Estás seguro de que quieres eliminar la imagen?",
      [
        {
          text: "Cancelar",
          style: "cancel"
        },
        {
          text: "Eliminar",
          style: "destructive",
          onPress: eliminarImagen
        }
      ]
    );
  };

  if (loading) {
    return (
      <View style={styles.centerContainer}>
        <ActivityIndicator size="large" color="#ff6b9d" />
        <Text style={styles.loadingText}>Cargando producto...</Text>
      </View>
    );
  }

  if (saving) {
    return (
      <View style={styles.centerContainer}>
        <ActivityIndicator size="large" color="#ff6b9d" />
        <Text style={styles.loadingText}>Guardando cambios...</Text>
        <Text style={styles.savingSubtext}>Esto puede tomar unos segundos</Text>
      </View>
    );
  }

  return (
    <ScrollView style={styles.container} contentContainerStyle={{ paddingBottom: 50 }}>
      <Text style={styles.title}>Modificar Producto</Text>
      <Text style={styles.productId}>ID: {id}</Text>

      {/* Preview de la imagen */}
      <View style={styles.imagenSection}>
        <Text style={styles.label}>Imagen del producto</Text>
        
        {imagenUri ? (
          <View style={styles.previewContainer}>
            <Image 
              source={{ uri: imagenUri }} 
              style={styles.imagenPreview}
              resizeMode="cover"
            />
            <TouchableOpacity 
              style={styles.eliminarImagenBtn}
              onPress={confirmarEliminarImagen}
            >
              <Text style={styles.eliminarImagenText}>✕</Text>
            </TouchableOpacity>
          </View>
        ) : (
          <View style={styles.sinImagenContainer}>
            <Text style={styles.sinImagenText}>No hay imagen</Text>
            <Text style={styles.sinImagenSubtext}>Selecciona una imagen</Text>
          </View>
        )}

        <View style={styles.botonesImagenContainer}>
          <TouchableOpacity 
            style={[
              styles.botonImagen,
              !permisosConcedidos && styles.botonImagenDisabled
            ]}
            onPress={seleccionarImagen}
            disabled={!permisosConcedidos}
          >
            <Text style={styles.botonImagenText}>
              {permisosConcedidos ? '📁 Galería' : '⏳ Esperando permisos...'}
            </Text>
          </TouchableOpacity>
          
          <TouchableOpacity 
            style={[
              styles.botonImagen,
              !permisosConcedidos && styles.botonImagenDisabled
            ]}
            onPress={tomarFoto}
            disabled={!permisosConcedidos}
          >
            <Text style={styles.botonImagenText}>
              {permisosConcedidos ? '📷 Cámara' : '⏳ Esperando permisos...'}
            </Text>
          </TouchableOpacity>
        </View>

        {!permisosConcedidos && (
          <Text style={styles.permisoText}>
            Necesitas conceder permisos para acceder a la galería y cámara
          </Text>
        )}

        <Text style={styles.inputHelper}>
          {imagenSeleccionada ? "Nueva imagen seleccionada" : 
           formData.imagen_producto ? `Imagen actual: ${formData.imagen_producto}` : 
           "No se ha seleccionado ninguna imagen"}
        </Text>
      </View>

      <Text style={styles.label}>Nombre del producto *</Text>
      <TextInput
        style={styles.input}
        placeholder="Nombre del producto"
        placeholderTextColor="#999"
        value={formData.nombre_producto}
        onChangeText={(text) => handleChange("nombre_producto", text)}
      />

      <Text style={styles.label}>Stock *</Text>
      <TextInput
        style={styles.input}
        placeholder="Cantidad en stock"
        placeholderTextColor="#999"
        value={formData.stock}
        onChangeText={(text) => handleChange("stock", text.replace(/[^0-9]/g, ''))}
        keyboardType="numeric"
      />

      <Text style={styles.label}>Fecha de vencimiento</Text>
      <TextInput
        style={styles.input}
        placeholder="YYYY-MM-DD"
        placeholderTextColor="#999"
        value={formData.vencimiento}
        onChangeText={(text) => handleChange("vencimiento", text)}
      />

      <Text style={styles.label}>Precio de compra</Text>
      <TextInput
        style={styles.input}
        placeholder="0.00"
        placeholderTextColor="#999"
        value={formData.precio_producto}
        onChangeText={(text) => handleChange("precio_producto", text.replace(/[^0-9.]/g, ''))}
        keyboardType="decimal-pad"
      />

      <Text style={styles.label}>Precio de venta *</Text>
      <TextInput
        style={styles.input}
        placeholder="0.00"
        placeholderTextColor="#999"
        value={formData.precio_venta}
        onChangeText={(text) => handleChange("precio_venta", text.replace(/[^0-9.]/g, ''))}
        keyboardType="decimal-pad"
      />

      <Text style={styles.label}>Proveedor</Text>
      <View style={styles.pickerContainer}>
        <Picker
          selectedValue={formData.id_proveedor}
          onValueChange={(value) => handleChange("id_proveedor", value)}
        >
          <Picker.Item label="Seleccione un proveedor" value="" />
          {proveedores.map((prov) => (
            <Picker.Item
              key={prov.id_proveedor}
              label={prov.nombre_proveedor}
              value={prov.id_proveedor.toString()}
            />
          ))}
        </Picker>
      </View>

      <View style={styles.buttonContainer}>
        <TouchableOpacity 
          style={styles.cancelButton} 
          onPress={() => router.back()}
          disabled={saving}
        >
          <Text style={styles.buttonText}>Cancelar</Text>
        </TouchableOpacity>
        <TouchableOpacity 
          style={[
            styles.submitButton,
            saving && styles.submitButtonDisabled
          ]} 
          onPress={handleSubmit}
          disabled={saving}
        >
          {saving ? (
            <ActivityIndicator size="small" color="#fff" />
          ) : (
            <Text style={styles.buttonText}>Guardar Cambios</Text>
          )}
        </TouchableOpacity>
      </View>

      <View style={styles.infoContainer}>
        <Text style={styles.infoText}>* Campos obligatorios</Text>
        <Text style={styles.infoText}>Los cambios se aplicarán inmediatamente</Text>
      </View>
    </ScrollView>
  );
}

const styles = StyleSheet.create({
  container: { 
    flex: 1, 
    padding: 16, 
    backgroundColor: "#fdf0f5" 
  },
  title: { 
    fontSize: 28, 
    fontWeight: "700", 
    textAlign: "center", 
    marginBottom: 10, 
    color: "#000" 
  },
  productId: { 
    textAlign: "center", 
    marginBottom: 20, 
    color: "#666",
    fontSize: 14,
  },
  label: { 
    fontSize: 16, 
    marginBottom: 5, 
    fontWeight: "700", 
    color: "#000" 
  },
  input: {
    backgroundColor: "white",
    padding: 12,
    marginBottom: 12,
    borderRadius: 12,
    borderWidth: 1,
    borderColor: "#ff6b9d",
    color: "#000",
    fontSize: 16,
  },
  pickerContainer: {
    backgroundColor: "white",
    borderRadius: 12,
    borderWidth: 1,
    borderColor: "#ff6b9d",
    marginBottom: 12,
  },
  buttonContainer: { 
    flexDirection: "row", 
    justifyContent: "space-between", 
    marginTop: 20 
  },
  cancelButton: {
    flex: 1,
    backgroundColor: "#999",
    paddingVertical: 12,
    borderRadius: 20,
    alignItems: "center",
    marginRight: 8,
  },
  submitButton: {
    flex: 1,
    backgroundColor: "#ff6b9d",
    paddingVertical: 12,
    borderRadius: 20,
    alignItems: "center",
    marginLeft: 8,
  },
  submitButtonDisabled: {
    backgroundColor: "#ccc",
  },
  buttonText: { 
    color: "#fff", 
    fontWeight: "700", 
    textAlign: "center",
    fontSize: 16,
  },
  centerContainer: { 
    flex: 1, 
    justifyContent: "center", 
    alignItems: "center",
    backgroundColor: "#fdf0f5",
  },
  loadingText: { 
    marginTop: 10, 
    fontSize: 16, 
    color: "#333" 
  },
  savingSubtext: {
    marginTop: 5,
    fontSize: 12,
    color: "#666",
    fontStyle: 'italic',
  },
  imagenSection: {
    marginBottom: 20,
  },
  previewContainer: {
    position: 'relative',
    alignItems: 'center',
    marginBottom: 12,
  },
  imagenPreview: {
    width: 200,
    height: 200,
    borderRadius: 12,
    borderWidth: 2,
    borderColor: '#ff6b9d',
  },
  eliminarImagenBtn: {
    position: 'absolute',
    top: -10,
    right: -10,
    backgroundColor: '#e74c3c',
    width: 30,
    height: 30,
    borderRadius: 15,
    justifyContent: 'center',
    alignItems: 'center',
    elevation: 3,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.2,
    shadowRadius: 2,
  },
  eliminarImagenText: {
    color: 'white',
    fontWeight: 'bold',
    fontSize: 16,
  },
  sinImagenContainer: {
    width: 200,
    height: 200,
    backgroundColor: '#f0f0f0',
    borderRadius: 12,
    borderWidth: 2,
    borderColor: '#ddd',
    borderStyle: 'dashed',
    justifyContent: 'center',
    alignItems: 'center',
    marginBottom: 12,
    alignSelf: 'center',
  },
  sinImagenText: {
    color: '#666',
    fontStyle: 'italic',
    fontSize: 16,
  },
  sinImagenSubtext: {
    color: '#999',
    fontSize: 12,
    marginTop: 5,
  },
  botonesImagenContainer: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    marginBottom: 8,
  },
  botonImagen: {
    flex: 1,
    backgroundColor: '#ff6b9d',
    padding: 12,
    borderRadius: 10,
    alignItems: 'center',
    marginHorizontal: 4,
    elevation: 2,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 1 },
    shadowOpacity: 0.1,
    shadowRadius: 2,
  },
  botonImagenDisabled: {
    backgroundColor: '#ccc',
  },
  botonImagenText: {
    color: 'white',
    fontWeight: '600',
    fontSize: 14,
  },
  inputHelper: {
    fontSize: 12,
    color: '#666',
    textAlign: 'center',
    fontStyle: 'italic',
    marginTop: 5,
  },
  permisoText: {
    fontSize: 12,
    color: '#e74c3c',
    textAlign: 'center',
    marginTop: 5,
    fontWeight: '600',
  },
  infoContainer: {
    marginTop: 20,
    padding: 12,
    backgroundColor: '#f8f9fa',
    borderRadius: 8,
    borderLeftWidth: 4,
    borderLeftColor: '#ff6b9d',
  },
  infoText: {
    fontSize: 12,
    color: '#666',
    marginBottom: 2,
  },
});
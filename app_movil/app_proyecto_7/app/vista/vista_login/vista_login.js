// app/vista/vista_login/vista_login.js
import React, { useState, useEffect } from "react";
import { View, Text, TextInput, TouchableOpacity, Alert, StyleSheet, ImageBackground } from "react-native";
import AsyncStorage from "@react-native-async-storage/async-storage";
import { login, checkExistingSession } from "../../../controladores/controladores_login/controlador_login.js"; // ✅ ACTUALIZADO
import { useRouter } from "expo-router";

export default function LoginScreen() {
  const [usuario, setUsuario] = useState("");
  const [contrasena, setContrasena] = useState("");
  const [cargando, setCargando] = useState(true);
  const router = useRouter();

  // ✅ VERIFICAR SI YA HAY SESIÓN AL CARGAR
  useEffect(() => {
    const verificarSesion = async () => {
      try {
        const tieneSesion = await checkExistingSession();
        if (tieneSesion) {
          // Redirigir automáticamente si ya está logueado
          const userData = await AsyncStorage.getItem("usuarioLogueado");
          if (userData) {
            const user = JSON.parse(userData);
            redirigirSegunTipo(user.tipo);
          }
        }
      } catch (error) {
        console.error("Error verificando sesión:", error);
      } finally {
        setCargando(false);
      }
    };

    verificarSesion();
  }, []);

  const redirigirSegunTipo = (tipo) => {
    switch (tipo) {
      case "admin":
        router.replace("/vista/vista_adm/inicio/vista_inicio_adm");
        break;
      case "empleado":
        router.replace("/vista/vista_emp/inicio/vista_inicio_emp");
        break;
      case "cliente":
        router.replace("/vista/vista_cli/vista_inicio/vista_inicio_cli");
        break;
      default:
        Alert.alert("Error", "Tipo de usuario desconocido");
    }
  };

  const handleLogin = async () => {
    try {
      const res = await login(usuario, contrasena);

      if (res.success) {
        const user = res.user;
        const tipo = res.tipo;
        
        // ✅ Ya se guardó en authService, pero mantenemos AsyncStorage por compatibilidad
        await AsyncStorage.setItem("usuarioLogueado", JSON.stringify(user));
        
        redirigirSegunTipo(tipo);
        
      } else {
        Alert.alert("Error", res.message);
      }
    } catch (error) {
      Alert.alert("Error", error.message);
    }
  };

  if (cargando) {
    return (
      <View style={styles.loadingContainer}>
        <Text style={styles.loadingText}>Verificando sesión...</Text>
      </View>
    );
  }

  return (
    <ImageBackground
      source={{ uri: "https://images.unsplash.com/photo-1560066984-138dadb4c035?auto=format&fit=crop&w=1200&q=80" }}
      style={styles.background}
      blurRadius={5}
    >
      <View style={styles.container}>
        <Text style={styles.title}>Bienvenida</Text>
        <Text style={styles.subtitle}>Inicia sesión y disfruta de tu belleza</Text>
        <TextInput
          style={styles.input}
          placeholder="Usuario"
          placeholderTextColor="#555"
          value={usuario}
          onChangeText={setUsuario}
        />
        <TextInput
          style={styles.input}
          placeholder="Contraseña"
          placeholderTextColor="#555"
          secureTextEntry
          value={contrasena}
          onChangeText={setContrasena}
        />
        <TouchableOpacity style={styles.button} onPress={handleLogin}>
          <Text style={styles.buttonText}>Ingresar</Text>
        </TouchableOpacity>
      </View>
    </ImageBackground>
  );
}

const styles = StyleSheet.create({
  loadingContainer: {
    flex: 1,
    justifyContent: "center",
    alignItems: "center",
    backgroundColor: "#fff"
  },
  loadingText: {
    fontSize: 18,
    color: "#666"
  },
  background: {
    flex: 1,
    resizeMode: "cover",
    justifyContent: "center",
    alignItems: "center",
  },
  container: {
    width: "85%",
    backgroundColor: "rgba(255, 255, 255, 0.1)",
    padding: 30,
    borderRadius: 25,
    borderWidth: 1,
    borderColor: "rgba(255, 107, 157, 0.3)",
    shadowColor: "#000",
    shadowOffset: { width: 0, height: 10 },
    shadowOpacity: 0.2,
    shadowRadius: 20,
    backdropFilter: "blur(10px)",
  },
  title: {
    fontSize: 32,
    fontWeight: "700",
    color: "#000",
    textAlign: "center",
    marginBottom: 10,
  },
  subtitle: {
    fontSize: 16,
    color: "#000",
    textAlign: "center",
    marginBottom: 25,
  },
  input: {
    backgroundColor: "rgba(255, 107, 157, 0.2)",
    color: "#000",
    padding: 15,
    marginBottom: 15,
    borderRadius: 15,
    borderWidth: 1,
    borderColor: "rgba(255, 107, 157, 0.4)",
  },
  button: {
    backgroundColor: "#ff6b9d",
    paddingVertical: 15,
    borderRadius: 25,
    alignItems: "center",
    marginTop: 10,
    shadowColor: "#ff6b9d",
    shadowOffset: { width: 0, height: 10 },
    shadowOpacity: 0.3,
    shadowRadius: 20,
  },
  buttonText: {
    color: "#fff",
    fontSize: 18,
    fontWeight: "700",
  },
});
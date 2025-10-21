// app/vista/vista_login/vista_login.js
import React, { useState } from "react";
import { View, Text, TextInput, Button, Alert, StyleSheet } from "react-native";
import AsyncStorage from "@react-native-async-storage/async-storage";
import { login } from "../../../controladores/controladores_login/controlador_login.js";
import { useRouter } from "expo-router";

export default function LoginScreen() {
  const [usuario, setUsuario] = useState("");
  const [contrasena, setContrasena] = useState("");
  const router = useRouter();

  const handleLogin = async () => {
    try {
      const res = await login(usuario, contrasena);

      if (res.success) {
        const user = res.user;
        const tipo = res.tipo;

        // 🧠 Guardar usuario en almacenamiento local
        await AsyncStorage.setItem("usuarioLogueado", JSON.stringify(user));

        Alert.alert("Bienvenido", `${user.nombre_usuario} (${tipo})`);

        // 🚦 Redirigir según tipo
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
      } else {
        Alert.alert("Error", res.message);
      }
    } catch (error) {
      Alert.alert("Error", error.message);
    }
  };

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Iniciar Sesión</Text>
      <TextInput
        style={styles.input}
        placeholder="Usuario"
        value={usuario}
        onChangeText={setUsuario}
      />
      <TextInput
        style={styles.input}
        placeholder="Contraseña"
        secureTextEntry
        value={contrasena}
        onChangeText={setContrasena}
      />
      <Button title="Ingresar" onPress={handleLogin} />
    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, justifyContent: "center", padding: 20 },
  title: { fontSize: 22, marginBottom: 20, textAlign: "center" },
  input: { borderWidth: 1, marginBottom: 10, padding: 8, borderRadius: 5 },
});

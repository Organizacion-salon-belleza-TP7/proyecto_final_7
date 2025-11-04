"use client"

import { useState, useEffect } from "react"
import {
  View,
  Text,
  TextInput,
  TouchableOpacity,
  Alert,
  ImageBackground,
  // 💡 Reintroduce Animated para las animaciones de borde
  Animated, 
  ActivityIndicator,
  // Componentes para la solución del teclado
  Platform,
  KeyboardAvoidingView,
  ScrollView,
} from "react-native"
import { SafeAreaView } from "react-native-safe-area-context"
import AsyncStorage from "@react-native-async-storage/async-storage"
import { login } from "../../../controladores/controladores_login/controlador_login.js"
import { useRouter } from "expo-router"
import {
  createLoginStyles,
  COLORS,
  // 💡 Importa la función de creación de la animación neón
  createNeonBorderAnimation, 
} from "../css/LoginStyles"

export default function LoginScreen() {
  const [usuario, setUsuario] = useState("")
  const [contrasena, setContrasena] = useState("")
  const [isLoading, setIsLoading] = useState(false)
  const [inputFocus, setInputFocus] = useState({ usuario: false, contrasena: false })
  const router = useRouter()

  const styles = createLoginStyles()
  // 💡 Inicializa la animación de borde neón
  const { neonAnim, animateNeonBorder, neonBorderColor, neonShadowColor } = createNeonBorderAnimation()

  // 💡 Inicia la animación de borde cuando el componente se monta
  useEffect(() => {
    animateNeonBorder()
  }, [])

  const handleLogin = async () => {
    if (!usuario.trim() || !contrasena.trim()) {
      Alert.alert("Error", "Por favor completa todos los campos")
      return
    }

    setIsLoading(true)

    try {
      const res = await login(usuario, contrasena)

      if (res.success) {
        const user = res.user
        const tipo = res.tipo
        await AsyncStorage.setItem("usuarioLogueado", JSON.stringify(user))
        Alert.alert("Bienvenido", `${user.nombre_usuario} (${tipo})`)

        switch (tipo) {
          case "admin":
            router.replace("/vista/vista_adm/inicio/vista_inicio_adm")
            break
          case "empleado":
            router.replace("/vista/vista_emp/inicio/vista_inicio_emp")
            break
          case "cliente":
            router.replace("/vista/vista_cli/vista_inicio/vista_inicio_cli")
            break
          default:
            Alert.alert("Error", "Tipo de usuario desconocido")
        }
      } else {
        Alert.alert("Error", res.message)
      }
    } catch (error) {
      Alert.alert("Error", error.message)
    } finally {
      setIsLoading(false)
    }
  }

  return (
    <SafeAreaView style={styles.safeAreaContainer} edges={["top", "bottom"]}>
      <ImageBackground
        source={{
          uri: "https://images.unsplash.com/photo-1560066984-138dadb4c035?auto=format&fit=crop&w=1200&q=80",
        }}
        style={styles.background}
        blurRadius={5}
      >
        <KeyboardAvoidingView 
          style={{ flex: 1, width: '100%' }}
          behavior={Platform.OS === "ios" ? "padding" : "padding"} 
          keyboardVerticalOffset={0} 
        >
            <ScrollView
                contentContainerStyle={{ 
                    flexGrow: 1, 
                    justifyContent: 'center', 
                    alignItems: 'center',
                    paddingHorizontal: 20,
                }} 
                keyboardShouldPersistTaps='handled'
            >
                {/* 💡 APLICA Animated.View para el contenedor principal de la tarjeta */}
                <Animated.View
                    style={[
                        styles.container,
                        // 💡 Estilos animados para el borde neón
                        {
                            borderColor: neonBorderColor,
                            shadowColor: neonShadowColor, // Para que la sombra también cambie con el neón
                            shadowRadius: 25, // Aumenta la sombra para el efecto neón
                            elevation: 15, 
                        }
                    ]}
                >
                    {/* Logo/Decoración */}
                    <View style={styles.decorationContainer} />

                    {/* Título */}
                    <Text style={styles.title}>Bienvenida</Text>
                    <Text style={styles.subtitle}>Inicia sesión y disfruta de tu belleza</Text>

                    {/* Input Usuario */}
                    <View style={styles.inputContainer}>
                        <TextInput
                            style={[styles.input, inputFocus.usuario && { borderColor: COLORS.primary }]}
                            placeholder="Usuario"
                            placeholderTextColor="#9CA3AF"
                            value={usuario}
                            onChangeText={setUsuario}
                            onFocus={() => setInputFocus({ ...inputFocus, usuario: true })}
                            onBlur={() => setInputFocus({ ...inputFocus, usuario: false })}
                            editable={!isLoading}
                        />
                    </View>

                    {/* Input Contraseña */}
                    <View style={styles.inputContainer}>
                        <TextInput
                            style={[styles.input, inputFocus.contrasena && { borderColor: COLORS.primary }]}
                            placeholder="Contraseña"
                            placeholderTextColor="#9CA3AF"
                            secureTextEntry
                            value={contrasena}
                            onChangeText={setContrasena}
                            onFocus={() => setInputFocus({ ...inputFocus, contrasena: true })}
                            onBlur={() => setInputFocus({ ...inputFocus, contrasena: false })}
                            editable={!isLoading}
                        />
                    </View>

                    {/* Botón Ingresar */}
                    <View style={styles.button}>
                        <TouchableOpacity style={styles.button} onPress={handleLogin} disabled={isLoading} activeOpacity={0.8}>
                            {isLoading ? (
                                <ActivityIndicator size="small" color={COLORS.white} />
                            ) : (
                                <Text style={styles.buttonText}>Ingresar</Text>
                            )}
                        </TouchableOpacity>
                    </View>
                {/* 🟢 CORRECCIÓN: Se asegura que el tag de cierre sea </Animated.View> */}
                </Animated.View>
            </ScrollView>
        </KeyboardAvoidingView>
      </ImageBackground>
    </SafeAreaView>
  )
}
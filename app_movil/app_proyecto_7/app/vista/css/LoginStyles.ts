import { StyleSheet, Animated } from "react-native"

// Paleta de colores profesional para belleza
const COLORS = {
  primary: "#E84C89", // Rosa vibrante
  primaryDark: "#D63971", // Rosa oscuro
  secondary: "#8B5CF6", // Púrpura elegante
  accent: "#F59E0B", // Dorado/naranja
  white: "#FFFFFF",
  darkGray: "#1F2937",
  lightGray: "#F3F4F6",
  border: "rgba(255, 107, 157, 0.35)", 
  backdrop: "rgba(31, 41, 55, 0.75)",
  shadow: "#000000",
}

export const createLoginStyles = () => {
  const styles = StyleSheet.create({
    // CONTENEDORES BASE
    background: {
      flex: 1,
      resizeMode: "cover",
      justifyContent: "center",
      alignItems: "center",
      backgroundColor: COLORS.darkGray,
    },

    safeAreaContainer: {
      flex: 1,
      backgroundColor: COLORS.darkGray,
    },

    safeAreaContent: {
      flex: 1,
      justifyContent: "center",
      alignItems: "center",
      paddingHorizontal: 20,
    },

    // CONTENEDOR PRINCIPAL - Card
    container: {
      width: "90%",
      maxWidth: 380,
      backgroundColor: COLORS.backdrop,
      paddingVertical: 40,
      paddingHorizontal: 28,
      borderRadius: 28,
      borderWidth: 3,
      // borderColor y shadowColor/Radius son sobreescritos por la animación
      shadowColor: COLORS.shadow,
      shadowOffset: { width: 0, height: 15 },
      shadowOpacity: 0.3,
      shadowRadius: 25,
      elevation: 15, // Para Android
    },

    // TIPOGRAFÍA - TÍTULO
    title: {
      fontSize: 36,
      fontWeight: "700",
      color: COLORS.white,
      textAlign: "center",
      marginBottom: 12,
      letterSpacing: 0.5,
    },

    // TIPOGRAFÍA - SUBTÍTULO
    subtitle: {
      fontSize: 15,
      color: "#D1D5DB",
      textAlign: "center",
      marginBottom: 32,
      lineHeight: 22,
      fontWeight: "500",
    },

    // CONTENEDOR DE INPUTS
    inputContainer: {
      marginBottom: 18,
    },

    // INPUTS - Usuario y Contraseña
    input: {
      backgroundColor: "rgba(255, 255, 255, 0.12)",
      color: COLORS.white,
      paddingVertical: 16,
      paddingHorizontal: 18,
      borderRadius: 16,
      borderWidth: 1.5,
      borderColor: "rgba(232, 76, 137, 0.5)",
      fontSize: 16,
      fontWeight: "500",
      marginBottom: 16,
    },

    // BOTÓN PRINCIPAL - Ingresar
    button: {
      backgroundColor: COLORS.primary,
      paddingVertical: 16,
      paddingHorizontal: 28,
      borderRadius: 20,
      alignItems: "center",
      marginTop: 8,
      shadowColor: COLORS.primary,
      shadowOffset: { width: 0, height: 12 },
      shadowOpacity: 0.4,
      shadowRadius: 20,
      elevation: 12,
      overflow: "hidden",
    },

    buttonText: {
      color: COLORS.white,
      fontSize: 18,
      fontWeight: "700",
      letterSpacing: 0.8,
    },

    // ENLACE SECUNDARIO
    linkContainer: {
      marginTop: 20,
      alignItems: "center",
    },

    linkText: {
      fontSize: 14,
      color: "#9CA3AF",
      fontWeight: "500",
    },

    linkHighlight: {
      color: COLORS.accent,
      fontWeight: "700",
    },

    // CONTENEDOR DE DECORACIÓN
    decorationContainer: {
      position: "absolute",
      top: -40,
      right: -40,
      width: 140,
      height: 140,
      borderRadius: 70,
      backgroundColor: "rgba(139, 92, 246, 0.1)",
      borderWidth: 1,
      borderColor: "rgba(139, 92, 246, 0.2)",
    },
  })

  return styles
}

export { COLORS }

// FUNCIONES Y VALORES PARA LA ANIMACIÓN NEÓN
export const createNeonBorderAnimation = () => {
  const neonAnim = new Animated.Value(0)

  const animateNeonBorder = () => {
    Animated.loop(
      Animated.timing(neonAnim, {
        toValue: 1,
        duration: 3000, // Duración de la animación (3 segundos)
        useNativeDriver: false, // Los cambios de color requieren useNativeDriver: false
      })
    ).start()
  }

  const neonBorderColor = neonAnim.interpolate({
    inputRange: [0, 0.5, 1],
    outputRange: [COLORS.primary, COLORS.secondary, COLORS.primary], // Colores de transición (Rosa -> Púrpura -> Rosa)
  })

  const neonShadowColor = neonAnim.interpolate({
    inputRange: [0, 0.5, 1],
    outputRange: [COLORS.primary, COLORS.secondary, COLORS.primary],
  })

  return { neonAnim, animateNeonBorder, neonBorderColor, neonShadowColor }
}
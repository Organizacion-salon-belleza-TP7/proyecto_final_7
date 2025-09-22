import React from "react";
import { View, Text, Button, StyleSheet } from "react-native";
import { useRouter, useLocalSearchParams } from "expo-router";

export default function InicioAdmScreen() {
  const router = useRouter();
  const params = useLocalSearchParams(); // ⚠️ reemplazamos useSearchParams

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Bienvenido Administrador</Text>
      
      {params.nombre_usuario && (
        <Text style={styles.subtitle}>
          Usuario: {params.nombre_usuario}
        </Text>
      )}

      <Button
        title="Cerrar sesión"
        onPress={() => router.replace("/vista/vista_login/vista_login")}
      />
    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, justifyContent: "center", alignItems: "center", padding: 20 },
  title: { fontSize: 24, marginBottom: 10, fontWeight: "bold" },
  subtitle: { fontSize: 18, marginBottom: 20 },
});

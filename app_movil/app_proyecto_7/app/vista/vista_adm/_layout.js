import { Drawer } from "expo-router/drawer";
import { StatusBar } from "expo-status-bar";
import AsyncStorage from "@react-native-async-storage/async-storage";
import { View, Text, TouchableOpacity } from "react-native";
import { Ionicons } from "@expo/vector-icons";
import { useRouter } from "expo-router";

import {
  DrawerContentScrollView,
  DrawerItem,
} from "@react-navigation/drawer";

import { cerrarSesion } from "../../../controladores/controlador_logouts/controlador_logouts";

export default function AdmLayout() {
  const router = useRouter();

  const handleCerrarSesion = async () => {
    try {
      const data = await AsyncStorage.getItem("usuarioLogueado");
      const usuario = JSON.parse(data);

      if (usuario?.id_usuario) {
        await cerrarSesion(usuario.id_usuario);
      }

      await AsyncStorage.removeItem("usuarioLogueado");
      router.replace("/");
    } catch (err) {
      console.log("Error al cerrar sesión:", err);
    }
  };

  return (
    <>
      <Drawer
        screenOptions={{
          drawerActiveTintColor: "#ff6b9d",
          drawerInactiveTintColor: "#444",
          drawerLabelStyle: { fontSize: 16, marginLeft: -4 },

          // 🌸 Fondo rosa del drawer
          drawerStyle: {
            backgroundColor: "#ffe6ea",
            width: 280,
            height: "70%", // 🔽 Recorte del menú
            borderTopRightRadius: 25,
            borderBottomRightRadius: 25,
          },

          // Fondo rosa interno
          drawerContentContainerStyle: {
            backgroundColor: "#ffe6ea",
          },
        }}
        drawerContent={(props) => (
          <DrawerContentScrollView
            {...props}
            contentContainerStyle={{
              backgroundColor: "#ffe6ea",
              paddingTop: 40,

              // 🔽 Recortar contenido hasta donde marcaste
              minHeight: "70%",
            }}
          >
            {/* Inicio */}
            <DrawerItem
              label="Inicio"
              icon={({ color, size }) => (
                <Ionicons name="home-outline" size={size} color={color} />
              )}
              onPress={() =>
                props.navigation.navigate("inicio/vista_inicio_adm")
              }
            />

            {/* Citas */}
            <DrawerItem
              label="Citas"
              icon={({ color, size }) => (
                <Ionicons name="time-outline" size={size} color={color} />
              )}
              onPress={() =>
                props.navigation.navigate("vista_citas/vista_citas")
              }
            />

            {/* Logueos */}
            <DrawerItem
              label="Logueos"
              icon={({ color, size }) => (
                <Ionicons name="document-text-outline" size={size} color={color} />
              )}
              onPress={() =>
                props.navigation.navigate("vista_logouts/vista_logouts")
              }
            />

            {/* BOTÓN CERRAR SESIÓN */}
            <TouchableOpacity
              onPress={handleCerrarSesion}
              style={{
                marginTop: 20,
                marginHorizontal: 15,
                backgroundColor: "#ffccd5", // Rosa más fuerte
                padding: 15,
                borderRadius: 12,
                flexDirection: "row",
                alignItems: "center",
              }}
            >
              <Ionicons name="log-out-outline" size={22} color="#e74c3c" />
              <Text
                style={{
                  marginLeft: 10,
                  fontSize: 16,
                  color: "#e74c3c",
                  fontWeight: "600",
                }}
              >
                Cerrar Sesión
              </Text>
            </TouchableOpacity>
          </DrawerContentScrollView>
        )}
      >
        <Drawer.Screen
          name="inicio/vista_inicio_adm"
          options={{ title: "Inicio" }}
        />

        <Drawer.Screen
          name="vista_citas/vista_citas"
          options={{ title: "Citas" }}
        />

        <Drawer.Screen
          name="vista_logouts/vista_logouts"
          options={{ title: "Logouts" }}
        />

        {/* Ocultas */}
        <Drawer.Screen
          name="vista_citas/vista_detalle_cita"
          options={{ 
          drawerItemStyle: { display: "none" },
          headerShown: false   // 👈 ESTO OCULTA EL HEADER
          }}
        />


        <Drawer.Screen
          name="inicio/vista_agregar_producto"
          options={{ 
          drawerItemStyle: { display: "none" },
          headerShown: false   // 👈 ESTO OCULTA EL HEADER
        }}
        />


        <Drawer.Screen
          name="inicio/vista_modificar_producto"
          options={{ 
            drawerItemStyle: { display: "none" },
            headerShown: false   // 👈 ESTO OCULTA EL HEADER
          }}
        />
      </Drawer>

      <StatusBar style="auto" />
    </>
  );
}

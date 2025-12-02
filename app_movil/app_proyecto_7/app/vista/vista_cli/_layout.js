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

export default function CliLayout() {
  const router = useRouter();

  const handleCerrarSesion = async () => {
    try {
      await cerrarSesion(); 
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

          drawerStyle: {
            backgroundColor: "#ffe6ea",
            width: 280,
            height: "70%",
            borderTopRightRadius: 25,
            borderBottomRightRadius: 25,
          },

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
              minHeight: "70%",
            }}
          >
            {/* ✔ MIS CITAS */}
            <DrawerItem
              label="Mis Citas"
              icon={({ color, size }) => (
                <Ionicons name="calendar-outline" size={size} color={color} />
              )}
              onPress={() => router.push("/vista/vista_cli/vista_inicio")}
            />

            {/* ✔ BOTÓN CERRAR SESIÓN */}
            <TouchableOpacity
              onPress={handleCerrarSesion}
              style={{
                marginTop: 20,
                marginHorizontal: 15,
                backgroundColor: "#ffccd5",
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

        {/* ------------------- RUTAS DEL DRAWER ------------------- */}

        <Drawer.Screen
          name="vista_inicio/vista_inicio_cli"
          options={{ 
            title: "Mis Citas",
            // NO agregar unmountOnBlur aquí, para mantener la actualización automática
          }}
        />

        {/* RUTAS OCULTAS */}
        <Drawer.Screen
          name="vista_comp_cita/confirmar_cit_comp"
          options={{
            drawerItemStyle: { display: "none" },
            headerShown: false,
            unmountOnBlur: true, // ← AGREGAR ESTO
          }}
        />

        <Drawer.Screen
          name="vista_comp_cita/vista_comp_cita"
          options={{
            drawerItemStyle: { display: "none" },
            headerShown: false,
            unmountOnBlur: true, // ← AGREGAR ESTO (ESTO ES LO QUE NECESITAS)
          }}
        />

        <Drawer.Screen
          name="vista_venta/vista_venta"
          options={{
            drawerItemStyle: { display: "none" },
            headerShown: false,
            unmountOnBlur: true, // ← AGREGAR ESTO si también tiene problemas similares
          }}
        />

        <Drawer.Screen
          name="vista_venta/confirmacion_venta"
          options={{
            drawerItemStyle: { display: "none" },
            headerShown: false,
            unmountOnBlur: true, // ← AGREGAR ESTO si también tiene problemas similares
          }}
        />

      </Drawer>

      <StatusBar style="auto" />
    </>
  );
}
import { Ionicons } from '@expo/vector-icons'; // ⬅️ Opcional: Para usar un ícono de hamburguesa estándar
import { useNavigation } from "@react-navigation/native"; // ⬅️ Importamos useNavigation
import { Drawer } from "expo-router/drawer";
import { StatusBar } from "expo-status-bar";
import { Platform, TouchableOpacity } from "react-native";

// Componente para el botón de menú personalizado
const CustomDrawerButton = ({ tintColor }) => {
  const navigation = useNavigation();

  return (
    <TouchableOpacity 
      onPress={() => navigation.openDrawer()}
      style={{ marginLeft: Platform.OS === 'ios' ? 10 : 20 }} // Ajuste de margen
    >
      {/* Usamos Ionicons como un ícono de hamburguesa estándar */}
      <Ionicons 
        name="menu-outline" 
        size={30} 
        color={tintColor || '#fff'} 
      />
    </TouchableOpacity>
  );
};

export default function AdmLayout() {
  return (
    <>
      <Drawer
        screenOptions={{
          // Estilos globales del Header (la barra superior)
          headerStyle: {
            backgroundColor: '#ff6b9d', // Fondo rosa fuerte
          },
          headerTintColor: '#fff', // Color del texto y botón de flecha/menú
          headerTitleStyle: {
            fontWeight: 'bold',
          },
          
          // Estilos globales del Drawer (el menú desplegable)
          drawerStyle: {
            backgroundColor: '#fff', 
          },
          drawerActiveTintColor: '#fff', 
          drawerActiveBackgroundColor: '#ff6b9d', 
          drawerInactiveTintColor: '#333', 
          drawerLabelStyle: {
            fontSize: 16,
            marginLeft: -10,
          },
        }}
      >
        {/* PANTALLAS VISIBLES EN EL DRAWER */}
        <Drawer.Screen
          name="inicio/vista_inicio_adm"
          options={{ 
            title: "Inventario", 
            headerTitle: '', // Deja el header visible, pero sin título
            // ⬅️ Usamos el botón personalizado
            headerLeft: ({ tintColor }) => <CustomDrawerButton tintColor={tintColor} />, 
          }}
        />
        <Drawer.Screen
          name="vista_citas/vista_citas"
          options={{ title: "Citas Agendadas" }}
        />
        <Drawer.Screen
          name="vista_logouts/vista_logouts"
          options={{ 
            title: "Cerrar Sesión",
            drawerLabelStyle: { 
                fontSize: 16,
                fontWeight: 'bold',
                color: '#ff4c4c',
                marginLeft: -10,
            }
          }}
        />

        {/* PANTALLAS OCULTAS DEL DRAWER (Mantiene el Header y el botón de retroceso) */}
        <Drawer.Screen
            name="vista_citas/vista_detalle_cita"
            options={{ 
                title: "Detalle Cita",
                drawerItemStyle: { display: "none" } 
            }}
        />
        <Drawer.Screen
            name="inicio/vista_agregar_producto"
            options={{ 
                title: "Agregar Producto",
                drawerItemStyle: { display: "none" } 
            }}
        />
        <Drawer.Screen
            name="inicio/vista_modificar_producto"
            options={{ 
                title: "Modificar Producto",
                drawerItemStyle: { display: "none" } 
            }}
        />
      </Drawer>
      <StatusBar style="light" /> 
    </>
  );
}
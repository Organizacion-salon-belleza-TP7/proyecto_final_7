import { Drawer } from "expo-router/drawer";
import { StatusBar } from "expo-status-bar";

export default function RootLayout() {
  return (
    <>
      <Drawer>
         {/* Pantalla de Inventario */}
        <Drawer.Screen
          name="index"
          options={{ title: "Index" }}
        />

        {/* Pantalla de Inventario */}
        <Drawer.Screen
          name="vista/vista_adm/inicio/vista_inicio_adm"
          options={{ title: "Inicio" }}
        />

        {/* Pantalla de Citas */}
        <Drawer.Screen
          name="vista/vista_adm/vista_citas/vista_citas"
          options={{ title: "Citas" }}
        />
      </Drawer>
      <StatusBar style="auto" />
    </>
  );
}



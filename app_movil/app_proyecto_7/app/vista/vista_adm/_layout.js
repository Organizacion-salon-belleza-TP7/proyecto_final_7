import { Drawer } from "expo-router/drawer";
import { StatusBar } from "expo-status-bar";

export default function AdmLayout() {
  return (
    <>
      <Drawer>
        {/* 👇 Solo las dos que querés */}
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

        {/* 👇 Estas pantallas existen, pero se ocultan del Drawer */}
    <Drawer.Screen
        name="vista_citas/vista_detalle_cita"
        options={{ drawerItemStyle: { display: "none" } }}
    />
    <Drawer.Screen
        name="inicio/vista_agregar_producto"
        options={{ drawerItemStyle: { display: "none" } }}
    />
    <Drawer.Screen
        name="inicio/vista_modificar_producto"
        options={{ drawerItemStyle: { display: "none" } }}
        />
    </Drawer>
      <StatusBar style="auto" />
    </>
  );
}

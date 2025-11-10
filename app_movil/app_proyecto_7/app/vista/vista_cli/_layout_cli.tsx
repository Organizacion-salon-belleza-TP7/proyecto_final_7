import { Stack } from "expo-router";
import { StatusBar } from "expo-status-bar";

export default function LayoutCli() {
  return (
    <>
      <Stack>
        {/* Pantalla principal del cliente */}
        <Stack.Screen
          name="vista_inicio/vista_inicio_cli"
          options={{ headerShown: false }}
        />

        {/* Pantalla para seleccionar servicios y combos */}
        <Stack.Screen
          name="vista_cita/SeleccionarServiciosScreen"
          options={{ headerShown: false }}
        />

        {/* Pantalla de confirmación de cita */}
        <Stack.Screen
          name="vista_cita/ConfirmarCitaScreen"
          options={{ headerShown: false }}
        />

      </Stack>
      <StatusBar style="auto" />
    </>
  );
}

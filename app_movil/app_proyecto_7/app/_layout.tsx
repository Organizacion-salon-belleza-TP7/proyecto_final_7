import { Stack } from "expo-router";
import { StatusBar } from "expo-status-bar";

export default function RootLayout() {
  return (
    <>
      <Stack>
        <Stack.Screen
          name="index"
          options={{ headerShown: false }}
        />
        <Stack.Screen
          name="vista/vista_login/vista_login"
          options={{ headerShown: false }}
        />
        {/* 👇 Todo lo de ADM queda dentro de su propio layout */}
        <Stack.Screen
          name="vista/vista_adm"
          options={{ headerShown: false }}
        />
      </Stack>
      <StatusBar style="auto" />
    </>
  );
}




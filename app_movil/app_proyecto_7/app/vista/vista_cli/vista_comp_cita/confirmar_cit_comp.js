// app/vistas/vistas_cli/vista_citas/ConfirmarCitaScreen.js
import React from "react";
import { View, Text, TouchableOpacity, ScrollView } from "react-native";

export default function ConfirmarCitaScreen({ navigation, route }) {
  const { cita, seleccionados, lugares, idLugar } = route.params;

  const lugarNombre =
    lugares.find((l) => l.id_lugar === idLugar)?.nombre_lugar || "Sin lugar";

  const total = seleccionados.reduce((acc, s) => acc + Number(s.precio), 0);

  return (
    <ScrollView className="flex-1 bg-gray-100 p-6">
      <View className="bg-white rounded-2xl shadow-lg p-6 mt-6">
        <Text className="text-3xl font-bold text-pink-600 mb-6 text-center">
          ¡Cita registrada!
        </Text>

        <Text className="text-lg mb-2 text-gray-800 text-center">
          ID de cita: <Text className="font-semibold">{cita.id_cita}</Text>
        </Text>

        <Text className="text-xl font-semibold mt-4 mb-2 text-gray-800">
          Servicios y Combos seleccionados:
        </Text>
        {seleccionados.map((item, i) => (
          <Text key={i} className="text-gray-700">
            • {item.tipo}: {item.nombre} - ${item.precio}
          </Text>
        ))}

        <Text className="mt-4 text-gray-800">
          Lugar: <Text className="font-semibold">{lugarNombre}</Text>
        </Text>
        <Text className="mb-4 text-gray-800">
          Total a pagar: <Text className="font-semibold">${total}</Text>
        </Text>

        <TouchableOpacity
          onPress={() => navigation.navigate("PagoScreen", { cita })}
          className="bg-green-500 py-4 rounded-lg mt-6"
        >
          <Text className="text-white text-center font-semibold text-lg">
            Ir a Pago
          </Text>
        </TouchableOpacity>
      </View>
    </ScrollView>
  );
}

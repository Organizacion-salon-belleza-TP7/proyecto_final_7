// app/vistas/vistas_cli/vista_citas/SeleccionarServiciosScreen.js
import React, { useEffect, useState } from "react";
import {
  View,
  Text,
  ScrollView,
  TouchableOpacity,
  ActivityIndicator,
  Alert,
} from "react-native";
import { traerServicios, traerCombos, traerLugares, guardarCita } from "../../../../controladores/controladores_cli/controlador_comp_cita/controlador_comp_cita";

export default function SeleccionarServiciosScreen({ navigation, route }) {
  const { id_cliente } = route.params; // ID del usuario logueado

  const [servicios, setServicios] = useState([]);
  const [combos, setCombos] = useState([]);
  const [lugares, setLugares] = useState([]);
  const [seleccionados, setSeleccionados] = useState([]);
  const [idLugar, setIdLugar] = useState(null);
  const [cargando, setCargando] = useState(true);

  useEffect(() => {
    const cargarDatos = async () => {
      try {
        const [serv, comb, lug] = await Promise.all([
          traerServicios(),
          traerCombos(),
          traerLugares(),
        ]);
        setServicios(serv);
        setCombos(comb);
        setLugares(lug);
      } catch (error) {
        Alert.alert("Error", error.message);
      } finally {
        setCargando(false);
      }
    };

    cargarDatos();
  }, []);

  const toggleSeleccion = (tipo, id, nombre, precio) => {
    const existe = seleccionados.find(
      (item) => item.id === id && item.tipo === tipo
    );

    if (existe) {
      setSeleccionados(seleccionados.filter((item) => item.id !== id));
    } else {
      setSeleccionados([...seleccionados, { tipo, id, nombre, precio }]);
    }
  };

  const confirmarCita = async () => {
    if (!idLugar) return Alert.alert("Atención", "Seleccioná un lugar.");
    if (seleccionados.length === 0)
      return Alert.alert("Atención", "Seleccioná al menos un servicio o combo.");

    try {
      const fecha = new Date().toISOString().slice(0, 19).replace("T", " ");
      const serviciosIDs = seleccionados
        .filter((s) => s.tipo === "servicio")
        .map((s) => s.id);
      const combosIDs = seleccionados
        .filter((s) => s.tipo === "combo")
        .map((s) => s.id);

      const cita = await guardarCita(
        id_cliente,
        fecha,
        idLugar,
        serviciosIDs,
        combosIDs
      );

      navigation.navigate("ConfirmarCitaScreen", {
        cita,
        seleccionados,
        lugares,
        idLugar,
      });
    } catch (error) {
      Alert.alert("Error", error.message);
    }
  };

  if (cargando) {
    return (
      <View className="flex-1 justify-center items-center bg-gray-100">
        <ActivityIndicator size="large" color="#ec4899" />
        <Text className="text-gray-700 mt-2">Cargando datos...</Text>
      </View>
    );
  }

  return (
    <ScrollView className="flex-1 bg-gray-100 p-4">
      <Text className="text-2xl font-bold text-pink-600 mb-4 text-center">
        Seleccioná tus servicios
      </Text>

      {/* Servicios */}
      <Text className="text-xl font-semibold mb-2 text-gray-800">Servicios</Text>
      {servicios.map((s) => (
        <TouchableOpacity
          key={`s-${s.id}`}
          onPress={() => toggleSeleccion("servicio", s.id, s.nombre, s.precio_servicio)}
          className={`p-3 rounded-xl mb-2 ${
            seleccionados.some((sel) => sel.id === s.id)
              ? "bg-pink-500"
              : "bg-white"
          } shadow`}
        >
          <Text
            className={`text-lg ${
              seleccionados.some((sel) => sel.id === s.id)
                ? "text-white"
                : "text-gray-800"
            }`}
          >
            {s.nombre} - ${s.precio_servicio}
          </Text>
        </TouchableOpacity>
      ))}

      {/* Combos */}
      <Text className="text-xl font-semibold mb-2 mt-4 text-gray-800">Combos</Text>
      {combos.map((c) => (
        <TouchableOpacity
          key={`c-${c.id}`}
          onPress={() => toggleSeleccion("combo", c.id, c.nombre, c.precio)}
          className={`p-3 rounded-xl mb-2 ${
            seleccionados.some((sel) => sel.id === c.id)
              ? "bg-pink-500"
              : "bg-white"
          } shadow`}
        >
          <Text
            className={`text-lg ${
              seleccionados.some((sel) => sel.id === c.id)
                ? "text-white"
                : "text-gray-800"
            }`}
          >
            {c.nombre} - ${c.precio}
          </Text>
        </TouchableOpacity>
      ))}

      {/* Lugares */}
      <Text className="text-xl font-semibold mb-2 mt-6 text-gray-800">Lugar</Text>
      {lugares.map((l) => (
        <TouchableOpacity
          key={l.id_lugar}
          onPress={() => setIdLugar(l.id_lugar)}
          className={`p-3 rounded-xl mb-2 ${
            idLugar === l.id_lugar ? "bg-green-500" : "bg-white"
          } shadow`}
        >
          <Text
            className={`text-lg ${
              idLugar === l.id_lugar ? "text-white" : "text-gray-800"
            }`}
          >
            {l.nombre_lugar}
          </Text>
        </TouchableOpacity>
      ))}

      {/* Botón confirmar */}
      <TouchableOpacity
        onPress={confirmarCita}
        className="bg-pink-600 p-4 rounded-xl mt-6 shadow"
      >
        <Text className="text-white text-center text-lg font-bold">
          Confirmar Cita
        </Text>
      </TouchableOpacity>
    </ScrollView>
  );
}

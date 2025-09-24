import { useLocalSearchParams } from "expo-router";
import React, { useEffect, useState } from "react";
import { View, Text, ActivityIndicator, StyleSheet, ScrollView } from "react-native";

export default function VistaDetalleCita() {
  const { id } = useLocalSearchParams();
  const [detalle, setDetalle] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    if (!id) return;

    fetch(`http://192.168.100.8/proyecto_final_7/app_movil/app_proyecto_7/api/router.php?route=citas&id=${id}`)
      .then(res => res.json())
      .then(data => {
        setDetalle(data);
        setLoading(false);
      })
      .catch(err => {
        console.error("Error:", err);
        setLoading(false);
      });
  }, [id]);

  if (loading) return <ActivityIndicator size="large" />;

  if (!detalle || detalle.length === 0) {
    return <Text>No se pudo cargar el detalle de la cita</Text>;
  }

  return (
    <ScrollView style={styles.container}>
      <Text style={styles.titulo}>Detalle de la cita</Text>
      <Text>ID: {detalle[0].id_cita}</Text>
      <Text>Cliente: {detalle[0].nombre_cliente}</Text>
      <Text>Fecha: {detalle[0].fecha_cita}</Text>
      <Text>Estado: {detalle[0].activo ? "Activa" : "Inactiva"}</Text>
      <Text>Lugar: {detalle[0].lugar}</Text>

      <Text style={styles.subtitulo}>Servicios</Text>
      {detalle.map((d, i) =>
        d.servicio_nombre ? (
          <Text key={`serv-${i}`}>
            {d.servicio_nombre} - ${d.servicio_precio}
          </Text>
        ) : null
      )}

      <Text style={styles.subtitulo}>Combos</Text>
      {detalle.map((d, i) =>
        d.combo_nombre ? (
          <Text key={`combo-${i}`}>
            {d.combo_nombre} - ${d.combo_precio}
          </Text>
        ) : null
      )}
    </ScrollView>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, padding: 20 },
  titulo: { fontSize: 20, fontWeight: "bold", marginBottom: 10 },
  subtitulo: { fontSize: 16, fontWeight: "bold", marginTop: 15 }
});

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

  if (loading) return (
    <View style={styles.centerContainer}>
      <ActivityIndicator size="large" color="#ff6b9d" />
      <Text style={styles.loadingText}>Cargando detalle de la cita...</Text>
    </View>
  );

  if (!detalle || detalle.length === 0) {
    return (
      <View style={styles.centerContainer}>
        <Text style={styles.errorText}>No se pudo cargar el detalle de la cita</Text>
      </View>
    );
  }

  const cita = detalle[0];

  return (
    <ScrollView style={styles.container}>
      <Text style={styles.title}>Detalle de la cita</Text>
      <View style={styles.infoContainer}>
        <Text style={styles.label}>ID:</Text>
        <Text style={styles.text}>{cita.id_cita}</Text>
      </View>
      <View style={styles.infoContainer}>
        <Text style={styles.label}>Cliente:</Text>
        <Text style={styles.text}>{cita.nombre_cliente}</Text>
      </View>
      <View style={styles.infoContainer}>
        <Text style={styles.label}>Fecha:</Text>
        <Text style={styles.text}>{cita.fecha_cita}</Text>
      </View>
      <View style={styles.infoContainer}>
        <Text style={styles.label}>Estado:</Text>
        <Text style={styles.text}>{cita.activo ? "Activo" : "Inactivo"}</Text>
      </View>
      <View style={styles.infoContainer}>
        <Text style={styles.label}>Lugar:</Text>
        <Text style={styles.text}>{cita.lugar}</Text>
      </View>

      <Text style={styles.subtitle}>Servicios</Text>
      {detalle.map((d, i) =>
        d.servicio_nombre ? (
          <View key={`serv-${i}`} style={styles.itemContainer}>
            <Text style={styles.text}>{d.servicio_nombre} - ${d.servicio_precio}</Text>
          </View>
        ) : null
      )}

      <Text style={styles.subtitle}>Combos</Text>
      {detalle.map((d, i) =>
        d.combo_nombre ? (
          <View key={`combo-${i}`} style={styles.itemContainer}>
            <Text style={styles.text}>{d.combo_nombre} - ${d.combo_precio}</Text>
          </View>
        ) : null
      )}
    </ScrollView>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    padding: 20,
    backgroundColor: "#fdf0f5"
  },
  centerContainer: {
    flex: 1,
    justifyContent: "center",
    alignItems: "center",
    padding: 20
  },
  loadingText: {
    marginTop: 10,
    fontSize: 16,
    color: "#333"
  },
  errorText: {
    fontSize: 16,
    color: "red",
    textAlign: "center"
  },
  title: {
    fontSize: 24,
    fontWeight: "700",
    marginBottom: 15,
    textAlign: "center",
    color: "#000"
  },
  subtitle: {
    fontSize: 18,
    fontWeight: "700",
    marginTop: 20,
    marginBottom: 10,
    color: "#000"
  },
  infoContainer: {
    flexDirection: "row",
    marginBottom: 6
  },
  label: {
    fontWeight: "700",
    marginRight: 5,
    color: "#333"
  },
  text: {
    color: "#333",
    fontSize: 16
  },
  itemContainer: {
    backgroundColor: "#fff",
    padding: 10,
    borderRadius: 8,
    marginBottom: 6,
    elevation: 2
  }
});

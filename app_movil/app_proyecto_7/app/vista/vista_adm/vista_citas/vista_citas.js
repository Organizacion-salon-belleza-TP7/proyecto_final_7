import React, { useState, useCallback } from "react";
import { 
  View, 
  Text, 
  FlatList, 
  ActivityIndicator, 
  Alert, 
  StyleSheet, 
  TouchableOpacity 
} from "react-native";
import { getCitas } from "../../../../controladores/controladores_adm/controladores_citas/controlador_citas";
import { useRouter } from "expo-router";
import { useFocusEffect } from "@react-navigation/native";
import { Ionicons } from "@expo/vector-icons";

export default function CitasScreen() {
  const [citas, setCitas] = useState([]);
  const [displayCount, setDisplayCount] = useState(10); // ← Mostrar 10 al inicio
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const router = useRouter();

  const fetchCitas = async () => {
    try {
      setLoading(true);
      setError(null);
      const data = await getCitas();
      setCitas(data);
      setDisplayCount(10); // ← Reset paginación al actualizar
    } catch (err) {
      console.error("Error en fetchCitas:", err);
      setError(err.message);
      Alert.alert("Error", err.message);
    } finally {
      setLoading(false);
    }
  };

  useFocusEffect(
    useCallback(() => {
      fetchCitas();
    }, [])
  );

  const handleShowMore = () => {
    setDisplayCount(prev => prev + 10);
  };

  const handleShowAll = () => {
    setDisplayCount(citas.length);
  };

  const handleBackToTen = () => {
    setDisplayCount(10);
  };

  const renderItem = ({ item }) => (
    <View style={styles.card}>
      <View style={styles.textContainer}>
        <Text style={styles.cardTitle}>Cliente: {item.nombre_cliente}</Text>
        <Text style={styles.cardText}>Fecha: {item.fecha_cita}</Text>
        <Text style={styles.cardText}>Estado: {item.activo === 1 ? "Inactivo" : "Activo"}</Text>
        <Text style={styles.cardText}>Servicios: {item.servicios || "Ninguno"}</Text>
        <Text style={styles.cardText}>Combos: {item.combos || "Ninguno"}</Text>
      </View>

      <TouchableOpacity
        style={styles.detailButton}
        onPress={() => router.push(`/vista/vista_adm/vista_citas/vista_detalle_cita?id=${item.id_cita}`)}
      >
        <Ionicons name="eye" size={18} color="#fff" />
        <Text style={styles.buttonText}>Ver Detalle</Text>
      </TouchableOpacity>
    </View>
  );

  if (loading) {
    return (
      <View style={styles.centerContainer}>
        <ActivityIndicator size="large" color="#ff6b9d" />
        <Text style={styles.loadingText}>Cargando citas...</Text>
      </View>
    );
  }

  if (error) {
    return (
      <View style={styles.centerContainer}>
        <Text style={styles.errorText}>Error: {error}</Text>
        <TouchableOpacity style={styles.retryButton} onPress={fetchCitas}>
          <Text style={styles.buttonText}>Reintentar</Text>
        </TouchableOpacity>
      </View>
    );
  }

  const displayedCitas = citas.slice(0, displayCount);

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Listado de Citas</Text>
      <Text style={styles.subtitle}>
        Mostrando {displayedCitas.length} de {citas.length} citas
      </Text>

      <FlatList
        data={displayedCitas}
        renderItem={renderItem}
        keyExtractor={(item) => item.id_cita.toString()}
        contentContainerStyle={styles.list}
        showsVerticalScrollIndicator={false}
      />

      {/* BOTÓN: Mostrar 10 más */}
      {displayCount < citas.length && (
        <TouchableOpacity style={styles.moreButton} onPress={handleShowMore}>
          <Text style={styles.moreButtonText}>Mostrar 10 más</Text>
        </TouchableOpacity>
      )}

      {/* BOTÓN: Mostrar todo */}
      {displayCount < citas.length && (
        <TouchableOpacity style={styles.showAllButton} onPress={handleShowAll}>
          <Text style={styles.showAllText}>Mostrar todo</Text>
        </TouchableOpacity>
      )}

      {/* BOTÓN: Volver a 10 → aparece SOLO si ya estás mostrando todo */}
      {displayCount >= citas.length && citas.length > 10 && (
        <TouchableOpacity style={styles.backButton} onPress={handleBackToTen}>
          <Text style={styles.backButtonText}>Volver a mostrar 10</Text>
        </TouchableOpacity>
      )}
    </View>
  );
}

const styles = StyleSheet.create({
  container: { 
    flex: 1, 
    padding: 16, 
    backgroundColor: '#fdf0f5' 
  },

  title: { 
    fontSize: 28, 
    fontWeight: '700', 
    textAlign: 'center',
    marginBottom: 10,
    color: '#000' 
  },

  subtitle: { 
    fontSize: 16, 
    textAlign: 'center', 
    color: '#333', 
    marginBottom: 16 
  },

  card: { 
    backgroundColor: 'white',
    padding: 16,
    borderRadius: 15,
    marginBottom: 12,
    flexDirection: 'row',
    alignItems: 'center',
    elevation: 6,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 5 },
    shadowOpacity: 0.12,
    shadowRadius: 8,
  },

  textContainer: { flex: 1 },

  cardTitle: { 
    fontSize: 18, 
    fontWeight: '700', 
    color: '#000',
    marginBottom: 6 
  },

  cardText: { 
    fontSize: 14, 
    color: '#333', 
    marginBottom: 2 
  },

  detailButton: {
    backgroundColor: '#ff6b9d',
    paddingVertical: 8,
    paddingHorizontal: 12,
    borderRadius: 12,
    flexDirection: "row",
    alignItems: "center",
    gap: 6
  },

  buttonText: { 
    color: '#fff', 
    fontWeight: '700', 
    fontSize: 14 
  },

  centerContainer: { 
    flex: 1, 
    justifyContent: 'center', 
    alignItems: 'center', 
    padding: 20 
  },

  loadingText: { 
    marginTop: 10, 
    fontSize: 16, 
    color: '#333' 
  },

  errorText: { 
    color: 'red', 
    marginBottom: 20, 
    textAlign: 'center',
    fontSize: 16 
  },

  retryButton: { 
    backgroundColor: '#ff6b9d', 
    paddingVertical: 10, 
    paddingHorizontal: 20, 
    borderRadius: 12 
  },

  list: { paddingBottom: 100 },

  moreButton: {
    backgroundColor: "#ff6b9d",
    padding: 12,
    borderRadius: 12,
    marginBottom: 10,
  },

  moreButtonText: {
    color: "#fff",
    textAlign: "center",
    fontSize: 16,
    fontWeight: "700",
  },

  showAllButton: {
    backgroundColor: "#ff3f7d",
    padding: 12,
    borderRadius: 12,
    marginBottom: 20,
  },

  showAllText: {
    color: "#fff",
    textAlign: "center",
    fontSize: 16,
    fontWeight: "700",
  },

  backButton: {
    backgroundColor: "#d6336c",
    padding: 12,
    borderRadius: 12,
    marginBottom: 20,
  },

  backButtonText: {
    color: "#fff",
    textAlign: "center",
    fontSize: 16,
    fontWeight: "700",
  }
});

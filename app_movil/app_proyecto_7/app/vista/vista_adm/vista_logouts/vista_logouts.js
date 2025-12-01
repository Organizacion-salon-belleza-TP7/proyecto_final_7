import React, { useEffect, useState } from "react";
import { 
  View, 
  Text, 
  FlatList, 
  ActivityIndicator, 
  StyleSheet, 
  Alert,
  TouchableOpacity 
} from "react-native";
import { obtenerLogueos } from "../../../../controladores/controladores_adm/controladores_logout/controlador_logout";
import { Ionicons } from "@expo/vector-icons";

export default function VistaLogouts() {
  const [logueos, setLogueos] = useState([]);
  const [displayCount, setDisplayCount] = useState(10); // Paginación: mostrar 10 al inicio
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);
  const [error, setError] = useState(null);

  const cargarLogueos = async () => {
    try {
      setLoading(true);
      setError(null);
      const data = await obtenerLogueos();
      if (data.error) {
        setError(data.error);
        Alert.alert("Error", data.error);
      } else {
        // Ordenar por fecha más reciente primero
        const sortedData = data.sort((a, b) => 
          new Date(b.fecha_logueo) - new Date(a.fecha_logueo)
        );
        setLogueos(sortedData);
        setDisplayCount(10); // Resetear paginación al actualizar
      }
    } catch (error) {
      const errorMsg = "No se pudo cargar el historial";
      setError(errorMsg);
      Alert.alert("Error", errorMsg);
    } finally {
      setLoading(false);
      setRefreshing(false);
    }
  };

  useEffect(() => {
    cargarLogueos();
  }, []);

  const handleRefresh = () => {
    setRefreshing(true);
    cargarLogueos();
  };

  // Funciones de paginación
  const handleShowMore = () => {
    setDisplayCount(prev => prev + 10);
  };

  const handleShowAll = () => {
    setDisplayCount(logueos.length);
  };

  const handleBackToTen = () => {
    setDisplayCount(10);
  };

  const renderItem = ({ item }) => (
    <View style={styles.card}>
      <View style={styles.cardHeader}>
        <View style={styles.userInfo}>
          <Ionicons name="person-circle" size={36} color="#ff6b9d" />
          <View style={styles.userTextContainer}>
            <Text style={styles.userName}>{item.nombre_usuario}</Text>
            <Text style={styles.userId}>ID: {item.id_usuario}</Text>
          </View>
        </View>
        <View style={styles.statusContainer}>
          {item.fecha_logout ? (
            <View style={[styles.statusBadge, styles.logoutBadge]}>
              <Ionicons name="log-out" size={14} color="#fff" />
              <Text style={styles.statusText}>Sesión cerrada</Text>
            </View>
          ) : (
            <View style={[styles.statusBadge, styles.activeBadge]}>
              <Ionicons name="wifi" size={14} color="#fff" />
              <Text style={styles.statusText}>Activo</Text>
            </View>
          )}
        </View>
      </View>

      <View style={styles.timelineContainer}>
        <View style={styles.timelineItem}>
          <View style={styles.iconContainer}>
            <Ionicons name="log-in" size={20} color="#27ae60" />
          </View>
          <View style={styles.timelineContent}>
            <Text style={styles.timelineLabel}>Inicio de sesión</Text>
            <Text style={styles.timelineValue}>{item.fecha_logueo}</Text>
          </View>
        </View>

        <View style={styles.timelineDivider}>
          <View style={styles.verticalLine} />
          <Ionicons name="arrow-down" size={16} color="#ff6b9d" />
          <View style={styles.verticalLine} />
        </View>

        <View style={styles.timelineItem}>
          <View style={styles.iconContainer}>
            <Ionicons 
              name={item.fecha_logout ? "log-out" : "time"} 
              size={20} 
              color={item.fecha_logout ? "#e74c3c" : "#f39c12"} 
            />
          </View>
          <View style={styles.timelineContent}>
            <Text style={styles.timelineLabel}>Cierre de sesión</Text>
            <Text style={styles.timelineValue}>
              {item.fecha_logout || "En curso..."}
            </Text>
            {!item.fecha_logout && (
              <Text style={styles.activeSession}>Sesión activa</Text>
            )}
          </View>
        </View>
      </View>

      <View style={styles.durationContainer}>
        <Ionicons name="time-outline" size={16} color="#666" />
        <Text style={styles.durationText}>
          Duración: {item.fecha_logout ? "Completada" : "En progreso"}
        </Text>
      </View>
    </View>
  );

  if (loading) return (
    <View style={styles.centerContainer}>
      <ActivityIndicator size="large" color="#ff6b9d" />
      <Text style={styles.loadingText}>Cargando historial de logueos...</Text>
    </View>
  );

  if (error) {
    return (
      <View style={styles.centerContainer}>
        <Ionicons name="alert-circle" size={60} color="#e74c3c" />
        <Text style={styles.errorText}>Error: {error}</Text>
        <TouchableOpacity style={styles.retryButton} onPress={cargarLogueos}>
          <Ionicons name="refresh" size={18} color="#fff" />
          <Text style={styles.buttonText}>Reintentar</Text>
        </TouchableOpacity>
      </View>
    );
  }

  const displayedLogueos = logueos.slice(0, displayCount);

  return (
    <View style={styles.container}>
      <View style={styles.header}>
        <View style={styles.titleContainer}>
          <Ionicons name="time" size={32} color="#ff6b9d" />
          <Text style={styles.title}>Historial de Logueos</Text>
        </View>
        <Text style={styles.subtitle}>
          Mostrando {displayedLogueos.length} de {logueos.length} registros
        </Text>
      </View>

      <TouchableOpacity 
        style={styles.refreshButton} 
        onPress={handleRefresh}
        disabled={refreshing}
      >
        <Ionicons 
          name={refreshing ? "refresh" : "refresh-outline"} 
          size={20} 
          color="#fff" 
        />
        <Text style={styles.refreshButtonText}>
          {refreshing ? "Actualizando..." : "Actualizar"}
        </Text>
      </TouchableOpacity>

      <FlatList
        data={displayedLogueos}
        keyExtractor={(item) => item.id_historial_logueos.toString()}
        renderItem={renderItem}
        contentContainerStyle={styles.list}
        showsVerticalScrollIndicator={false}
        refreshing={refreshing}
        onRefresh={handleRefresh}
        ListEmptyComponent={
          <View style={styles.emptyContainer}>
            <Ionicons name="document-text-outline" size={60} color="#ccc" />
            <Text style={styles.emptyTitle}>No hay registros</Text>
            <Text style={styles.emptyText}>
              No se encontraron logueos en el historial
            </Text>
            <TouchableOpacity 
              style={styles.emptyButton} 
              onPress={handleRefresh}
            >
              <Ionicons name="refresh" size={18} color="#fff" />
              <Text style={styles.emptyButtonText}>Intentar de nuevo</Text>
            </TouchableOpacity>
          </View>
        }
      />

      {/* BOTÓN: Mostrar 10 más */}
      {displayCount < logueos.length && (
        <TouchableOpacity style={styles.moreButton} onPress={handleShowMore}>
          <Ionicons name="chevron-down" size={20} color="#fff" />
          <Text style={styles.moreButtonText}>Mostrar 10 más</Text>
        </TouchableOpacity>
      )}

      {/* BOTÓN: Mostrar todo */}
      {displayCount < logueos.length && (
        <TouchableOpacity style={styles.showAllButton} onPress={handleShowAll}>
          <Ionicons name="list" size={20} color="#fff" />
          <Text style={styles.showAllText}>Mostrar todo</Text>
        </TouchableOpacity>
      )}

      {/* BOTÓN: Volver a 10 → aparece SOLO si ya estás mostrando todo */}
      {displayCount >= logueos.length && logueos.length > 10 && (
        <TouchableOpacity style={styles.backButton} onPress={handleBackToTen}>
          <Ionicons name="chevron-up" size={20} color="#fff" />
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
  header: {
    marginBottom: 16,
    backgroundColor: 'white',
    padding: 20,
    borderRadius: 15,
    elevation: 3,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1,
    shadowRadius: 4,
  },
  titleContainer: {
    flexDirection: 'row',
    alignItems: 'center',
    marginBottom: 8,
  },
  title: { 
    fontSize: 28, 
    fontWeight: '700', 
    marginLeft: 12,
    color: '#000' 
  },
  subtitle: { 
    fontSize: 16, 
    color: '#666',
    textAlign: 'center',
  },
  refreshButton: { 
    backgroundColor: '#ff6b9d', 
    paddingVertical: 12, 
    borderRadius: 20, 
    marginBottom: 16, 
    alignItems: 'center',
    flexDirection: 'row',
    justifyContent: 'center',
    elevation: 3,
  },
  refreshButtonText: {
    color: '#fff', 
    fontWeight: '700', 
    fontSize: 16,
    marginLeft: 8,
  },
  card: { 
    backgroundColor: 'white', 
    padding: 20, 
    borderRadius: 15, 
    marginBottom: 16, 
    elevation: 5,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 3 },
    shadowOpacity: 0.1,
    shadowRadius: 8,
  },
  cardHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'flex-start',
    marginBottom: 20,
    paddingBottom: 16,
    borderBottomWidth: 1,
    borderBottomColor: '#f0f0f0',
  },
  userInfo: {
    flexDirection: 'row',
    alignItems: 'center',
    flex: 1,
  },
  userTextContainer: {
    marginLeft: 12,
    flex: 1,
  },
  userName: { 
    fontSize: 18, 
    fontWeight: '700', 
    color: '#000',
    marginBottom: 2,
  },
  userId: { 
    fontSize: 14, 
    color: '#666' 
  },
  statusContainer: {
    marginLeft: 10,
  },
  statusBadge: {
    flexDirection: 'row',
    alignItems: 'center',
    paddingHorizontal: 10,
    paddingVertical: 6,
    borderRadius: 20,
    gap: 4,
  },
  logoutBadge: {
    backgroundColor: '#e74c3c',
  },
  activeBadge: {
    backgroundColor: '#27ae60',
  },
  statusText: {
    color: '#fff',
    fontSize: 12,
    fontWeight: '600',
  },
  timelineContainer: {
    marginBottom: 16,
  },
  timelineItem: {
    flexDirection: 'row',
    alignItems: 'center',
    marginBottom: 8,
  },
  iconContainer: {
    width: 40,
    height: 40,
    borderRadius: 20,
    backgroundColor: '#f8f8f8',
    justifyContent: 'center',
    alignItems: 'center',
    marginRight: 12,
  },
  timelineContent: {
    flex: 1,
  },
  timelineLabel: {
    fontSize: 12,
    color: '#666',
    marginBottom: 2,
    textTransform: 'uppercase',
    letterSpacing: 0.5,
  },
  timelineValue: {
    fontSize: 15,
    color: '#000',
    fontWeight: '500',
  },
  timelineDivider: {
    alignItems: 'center',
    marginVertical: 8,
  },
  verticalLine: {
    width: 2,
    height: 20,
    backgroundColor: '#ff6b9d',
  },
  activeSession: {
    fontSize: 12,
    color: '#f39c12',
    fontStyle: 'italic',
    marginTop: 2,
  },
  durationContainer: {
    flexDirection: 'row',
    alignItems: 'center',
    paddingTop: 12,
    borderTopWidth: 1,
    borderTopColor: '#f0f0f0',
  },
  durationText: {
    fontSize: 14,
    color: '#666',
    marginLeft: 8,
  },
  centerContainer: { 
    flex: 1, 
    justifyContent: 'center', 
    alignItems: 'center', 
    padding: 20,
    backgroundColor: '#fdf0f5',
  },
  loadingText: { 
    marginTop: 12, 
    fontSize: 16, 
    color: '#333',
    fontWeight: '500',
  },
  errorText: { 
    color: '#e74c3c', 
    textAlign: 'center', 
    marginVertical: 20, 
    fontSize: 16,
    fontWeight: '500',
  },
  retryButton: { 
    backgroundColor: '#ff6b9d', 
    paddingVertical: 10, 
    paddingHorizontal: 20, 
    borderRadius: 12,
    flexDirection: 'row',
    alignItems: 'center',
    gap: 8,
  },
  list: { 
    paddingBottom: 100 
  },
  emptyContainer: { 
    alignItems: 'center', 
    marginTop: 40, 
    padding: 20,
  },
  emptyTitle: {
    fontSize: 20,
    fontWeight: '700',
    color: '#666',
    marginTop: 16,
    marginBottom: 8,
  },
  emptyText: { 
    textAlign: "center", 
    fontSize: 15, 
    color: '#999',
    marginBottom: 24,
    lineHeight: 22,
  },
  emptyButton: {
    backgroundColor: '#ff6b9d',
    paddingVertical: 12,
    paddingHorizontal: 24,
    borderRadius: 20,
    flexDirection: 'row',
    alignItems: 'center',
    gap: 8,
  },
  emptyButtonText: {
    color: '#fff',
    fontWeight: '600',
    fontSize: 15,
  },
  // Estilos de paginación
  moreButton: {
    backgroundColor: "#ff6b9d",
    padding: 12,
    borderRadius: 12,
    marginBottom: 10,
    flexDirection: 'row',
    justifyContent: 'center',
    alignItems: 'center',
    gap: 8,
    elevation: 3,
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
    marginBottom: 10,
    flexDirection: 'row',
    justifyContent: 'center',
    alignItems: 'center',
    gap: 8,
    elevation: 3,
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
    flexDirection: 'row',
    justifyContent: 'center',
    alignItems: 'center',
    gap: 8,
    elevation: 3,
  },
  backButtonText: {
    color: "#fff",
    textAlign: "center",
    fontSize: 16,
    fontWeight: "700",
  },
  buttonText: { 
    color: '#fff', 
    fontWeight: '700', 
    fontSize: 14 
  },
});
// ../../css/inventario_styles.js

import { StyleSheet } from "react-native";

export const styles = StyleSheet.create({
  container: { 
    flex: 1, 
    paddingHorizontal: 16, // Padding horizontal para el Safe Area
    backgroundColor: '#fdf0f5' 
  },
  innerContainer: {
    paddingTop: 16, // Mantenemos el padding superior para el contenido interno
    flex: 1
  },
  title: { 
    fontSize: 28, 
    fontWeight: '700', 
    marginBottom: 12, 
    textAlign: 'center', 
    color: '#000' 
  },
  subtitle: { 
    fontSize: 16, 
    marginBottom: 16, 
    textAlign: 'center', 
    color: '#333' 
  },
  card: { 
    backgroundColor: 'white', 
    padding: 16, 
    borderRadius: 15, 
    marginBottom: 12, 
    elevation: 5, 
    flexDirection: 'row', 
    alignItems: 'center', 
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 5 },
    shadowOpacity: 0.15,
    shadowRadius: 10,
  },
  productImage: { 
    width: 80, 
    height: 80, 
    resizeMode: 'cover', 
    marginRight: 16, 
    borderRadius: 10 
  },
  textContainer: { 
    flex: 1 
  },
  cardTitle: { 
    fontSize: 18, 
    fontWeight: '700', 
    marginBottom: 6, 
    color: '#000' 
  },
  cardText: { 
    fontSize: 14, 
    color: '#333', 
    marginBottom: 2 
  },
  buttonContainer: { 
    flexDirection: 'column', 
    justifyContent: 'space-between', 
    marginLeft: 10 
  },
  modifyButton: { 
    backgroundColor: '#ff6b9d', 
    paddingVertical: 6, 
    paddingHorizontal: 10, 
    borderRadius: 8, 
    marginBottom: 6 
  },
  deleteButton: { 
    backgroundColor: '#ff4c4c', 
    paddingVertical: 6, 
    paddingHorizontal: 10, 
    borderRadius: 8 
  },
  buttonText: { 
    color: '#fff', 
    fontWeight: '700', 
    textAlign: 'center' 
  },
  centerContainer: { 
    flex: 1, 
    justifyContent: 'center', 
    alignItems: 'center', 
    padding: 20, 
    backgroundColor: '#fdf0f5' // Se añade el fondo aquí también
  },
  loadingText: { 
    marginTop: 10, 
    fontSize: 16, 
    color: '#333' 
  },
  errorText: { 
    color: 'red', 
    textAlign: 'center', 
    marginBottom: 20, 
    fontSize: 16 
  },
  retryButton: { 
    backgroundColor: '#ff6b9d', 
    paddingVertical: 10, 
    paddingHorizontal: 20, 
    borderRadius: 12 
  },
  addButton: { 
    backgroundColor: '#ff6b9d', 
    paddingVertical: 12, 
    borderRadius: 20, 
    marginBottom: 12, 
    alignItems: 'center' 
  },
  list: { 
    paddingBottom: 20 
  },
});
// app/modelo/modelo_adm/inicio/modelo_inicio.js
export default class Producto {
  constructor(
    id_inventario,
    nombre_producto,
    stock,
    vencimiento,
    precio_producto,
    precio_venta,
    imagen_producto,
    nombre_proveedor,
    id_proveedor
  ) {
    this.id_inventario = id_inventario;
    this.nombre_producto = nombre_producto;
    this.stock = stock;
    this.vencimiento = vencimiento;
    this.precio_producto = precio_producto;
    this.precio_venta = precio_venta;
    this.imagen_producto = imagen_producto;
    this.nombre_proveedor = nombre_proveedor;
    this.id_proveedor = id_proveedor;
  }

  // Métodos útiles si los necesitas
  getPrecioConFormato() {
    return `$${parseFloat(this.precio_venta).toFixed(2)}`;
  }

  getStockColor() {
    if (this.stock <= 0) return '#e74c3c';
    if (this.stock <= 10) return '#f39c12';
    return '#27ae60';
  }

  tieneImagen() {
    return this.imagen_producto && this.imagen_producto.trim() !== '';
  }

  getUrlImagen() {
    if (this.tieneImagen()) {
      return `http://10.253.89.87/proyecto_final_7/imagenes/inventario/${this.imagen_producto}`;
    }
    return null;
  }
}
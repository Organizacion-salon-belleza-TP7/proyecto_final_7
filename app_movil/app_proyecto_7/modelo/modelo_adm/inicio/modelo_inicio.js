// app/modelos/modelo_inventario/Producto.js
export default class Producto {
  constructor(
    id_inventario,
    nombre_producto,
    stock,
    vencimiento,
    precio_producto,
    precio_venta,
    imagen_producto,
    nombre_proveedor
  ) {
    this.id_inventario = id_inventario;
    this.nombre_producto = nombre_producto;
    this.stock = stock;
    this.vencimiento = vencimiento;
    this.precio_producto = precio_producto;
    this.precio_venta = precio_venta;
    this.imagen_producto = imagen_producto;
    this.nombre_proveedor = nombre_proveedor;
  }
}
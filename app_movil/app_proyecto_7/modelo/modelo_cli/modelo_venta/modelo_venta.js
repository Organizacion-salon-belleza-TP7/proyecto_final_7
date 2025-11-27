export default class Venta {
  constructor(id_cita, detalle = [], total = 0, metodoPago = null) {
    this.id_cita = id_cita;
    this.detalle = detalle; // Array de servicios/combos
    this.total = total;
    this.metodoPago = metodoPago;
    this.fecha_venta = new Date();
  }

  // Método para agregar items al detalle
  agregarItem(item) {
    this.detalle.push(item);
    this.calcularTotal();
  }

  // Método para calcular el total
  calcularTotal() {
    this.total = this.detalle.reduce((sum, item) => {
      const precio = item.precio_servicio || item.precio_combo || 0;
      return sum + parseFloat(precio);
    }, 0);
  }

  // Método para establecer método de pago
  setMetodoPago(metodo) {
    this.metodoPago = metodo;
  }

  // Método para obtener descripción de la venta
  obtenerDescripcion() {
    return this.detalle.map(item => {
      if (item.nombre_servicio) {
        return `💈 ${item.nombre_servicio}`;
      } else if (item.nombre_combo) {
        return `🎁 ${item.nombre_combo}`;
      }
      return 'Item';
    }).join(', ');
  }

  // Método para validar si la venta está lista para procesar
  validarVenta() {
    return this.id_cita && 
           this.detalle.length > 0 && 
           this.total > 0 && 
           this.metodoPago;
  }
}
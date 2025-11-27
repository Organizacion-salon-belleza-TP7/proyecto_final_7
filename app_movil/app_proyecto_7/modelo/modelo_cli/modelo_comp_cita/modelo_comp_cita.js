export default class Cita {
  constructor(id_cita, id_cliente, fecha_cita, activo, id_lugar, nombre_lugar, servicios = [], combos = []) {
    this.id_cita = id_cita;
    this.id_cliente = id_cliente;
    this.fecha_cita = fecha_cita;
    this.activo = activo;
    this.id_lugar = id_lugar;
    this.nombre_lugar = nombre_lugar;
    this.servicios = servicios;
    this.combos = combos;
  }

  // Método para obtener el total de la cita
  obtenerTotal() {
    const totalServicios = this.servicios.reduce((sum, serv) => sum + (serv.precio || 0), 0);
    const totalCombos = this.combos.reduce((sum, combo) => sum + (combo.precio || 0), 0);
    return totalServicios + totalCombos;
  }

  // Método para obtener descripción de servicios/combos
  obtenerDescripcion() {
    const serviciosDesc = this.servicios.map(s => s.nombre || 'Servicio').join(', ');
    const combosDesc = this.combos.map(c => c.nombre || 'Combo').join(', ');
    
    if (serviciosDesc && combosDesc) {
      return `${serviciosDesc} + ${combosDesc}`;
    } else if (serviciosDesc) {
      return serviciosDesc;
    } else {
      return combosDesc;
    }
  }
}
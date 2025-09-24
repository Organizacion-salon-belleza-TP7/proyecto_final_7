export default class Cita {
  constructor(id_cita, nombre_cliente, fecha_cita, activo, servicios, combos) {
    this.id_cita = id_cita;
    this.nombre_cliente = nombre_cliente;
    this.fecha_cita = fecha_cita;
    this.activo = activo;
    this.servicios = servicios;
    this.combos = combos;
  }
}

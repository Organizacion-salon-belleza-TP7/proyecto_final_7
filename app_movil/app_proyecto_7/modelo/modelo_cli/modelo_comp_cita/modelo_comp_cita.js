export default class Cita {
  constructor(id_cita, id_cliente, fecha_cita, activo, hash, id_lugar, nombre_lugar, servicios = [], combos = []) {
    this.id_cita = id_cita;
    this.id_cliente = id_cliente;
    this.fecha_cita = fecha_cita;
    this.activo = activo;
    this.hash = hash;
    this.id_lugar = id_lugar;
    this.nombre_lugar = nombre_lugar;
    this.servicios = servicios;
    this.combos = combos;
  }
}

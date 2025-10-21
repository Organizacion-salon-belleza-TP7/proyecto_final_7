export default class Cita {
  constructor(id_cita, nombre, nombre_usuario, fecha_cita, activo, nombre_lugar, id_caja) {
    this.id_cita = id_cita;
    this.nombre = nombre;
    this.nombre_usuario = nombre_usuario;
    this.fecha_cita = fecha_cita;
    this.activo = activo;
    this.nombre_lugar = nombre_lugar;
    this.id_caja = id_caja;
  }
}

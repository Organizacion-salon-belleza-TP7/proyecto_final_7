// app/models/logoutModel.js
export default class Logout {
  constructor(id_usuario, fecha_logout, nombre_usuario = '') {
    this.id_usuario = id_usuario;
    this.fecha_logout = fecha_logout;
    this.nombre_usuario = nombre_usuario;
  }
}
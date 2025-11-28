  // app/models/userModel.js
  export default class User {
    constructor(id_usuario, nombre_usuario, tipo, relacion) {
      this.id_usuario = id_usuario;
      this.nombre_usuario = nombre_usuario;
      this.tipo = tipo;
      this.relacion = relacion;
    }
  }

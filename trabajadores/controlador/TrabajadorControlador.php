<?php
require_once("modelo/TrabajadorModelo.php");

class TrabajadorControlador {
    private $modelo;

    public function __construct($conexion) {
        $this->modelo = new TrabajadorModelo($conexion);
    }

    public function mostrarTrabajadores() {
        $buscar = isset($_GET['buscar']) ? trim($_GET['buscar']) : "";
        $trabajadores = $this->modelo->obtenerTrabajadores($buscar);
        include("vista/trabajadores_vista.php");
    }
}

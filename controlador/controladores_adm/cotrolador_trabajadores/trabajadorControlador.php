<?php
require_once("modelo/trabajadorModelo.php");

class TrabajadorControlador {
    public function mostrarTrabajadores() {
        $trabajadores = TrabajadorModelo::obtenerTrabajadores();
        require("vista/trabajadorVista.php");
    }
}



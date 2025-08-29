<?php
require_once("modelo/CitasModelo.php");

class CitasControlador {
    private $modelo;

    public function __construct() {
        $this->modelo = new CitasModelo();
    }

    public function index() {
        $citas = $this->modelo->listar();
        require("vista/citas.php");
    }
}

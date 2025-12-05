<?php
require_once(ROOT_PATH . '/modelo/modelo_trabajadores/inicio_trabajador/Trabajador.php');


class TrabajadorController {
    private $conn;

    private $listaEspera;

    public function __construct($conn) {
        $this->listaEspera = new ListaEspera($conn);
    }

    public function listaEspera() {
        return $this->listaEspera->obtenerListaEspera();
    }



    public function confirmar($id) {
        return $this->modelo->confirmar($id);
    }

    public function cancelar($id) {
        return $this->modelo->cancelar($id);
    }
}

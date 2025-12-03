<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/modelo_trabajadores/inicio_trabajador/Trabajador.php');
require_once(ROOT_PATH . '/modelo/BD.php');

class TrabajadorController {

    private $trabajadorModel;

    public function __construct($conn) {
        $this->trabajadorModel = new Trabajador($conn);
    }

    // Método real que tu vista necesita
    public function listarTrabajadores() {
        return $this->trabajadorModel->obtenerTodos();
    }

    public function listaEspera() {
        return $this->trabajadorModel->listaEspera();
    }

    public function confirmar($id) {
        $this->trabajadorModel->confirmar($id);
        header("Location: listaEspera.php");
        exit;
    }

    public function cancelar($id) {
        $this->trabajadorModel->cancelar($id);
        header("Location: listaEspera.php");
        exit;
    }
}

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/modelo_trabajadores/inicio_trabajador/Trabajador.php');
require_once(ROOT_PATH . '/modelo/BD.php');

class TrabajadorController {
    private $trabajadorModel;

    // Constructor: recibe la conexión y crea la instancia del modelo
    public function __construct($conn) {
        $this->trabajadorModel = new Trabajador($conn);
    }

    // Pantalla principal del trabajador
    public function pantalla() {
        $trabajadores = $this->trabajadorModel->obtenerTodos();
        include __DIR__ . '/../view/pantallaTrabajador.php';
    }

    // Lista de espera
    public function listaEspera() {
        $lista = $this->trabajadorModel->listaEspera();
        include __DIR__ . '/../view/listaEspera.php';
    }

    // Confirmar registro de lista de espera
    public function confirmar($id) {
        $this->trabajadorModel->confirmar($id);
        header("Location: pantallaTrabajador.php");
        exit;
    }

    // Cancelar registro de lista de espera
    public function cancelar($id) {
        $this->trabajadorModel->cancelar($id);
        header("Location: pantallaTrabajador.php");
        exit;
    }

    // Cerrar sesión
    public function cerrarSesion() {
        session_start();
        session_destroy();
        include __DIR__ . '/../view/cerrarSesion.php';
    }
}

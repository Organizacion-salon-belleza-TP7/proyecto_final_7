<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../variable_global.php');
require_once("modelo/modelo_adm/modelo_citas/CitasModelo.php");
require_once(ROOT_PATH . '/modelo/BD.php');

class CitasControlador {
    private $modelo;

    public function __construct() {
        global $conn; // <<< trae la conexión definida en BD.php
        $this->modelo = new CitasModelo($conn);
    }

    public function index() {
        return $this->modelo->listar();
    }
}

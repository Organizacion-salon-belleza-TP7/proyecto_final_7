<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');
require_once(ROOT_PATH . '/modelo/modelo_adm/modelo_citas/CitasModelo.php');

class CitasControlador {
    private $modelo;

    public function __construct() {
        global $conn;
        $this->modelo = new CitasModelo($conn);
    }

    // Solo redirige al detalle de cita
    public function mostrarDetalle($id_cita) {
        $detalle_cita = $this->modelo->obtenerDetalle($id_cita);
        
        if (empty($detalle_cita)) {
            die("Cita no encontrada");
        }
        
        // Incluir directamente la vista de detalle
        include(ROOT_PATH . '/vista/vista_adm/citas/detalle_cita.php');
        exit;
    }
}

// Ejecutar solo si viene para ver detalle
if (isset($_GET['detalle']) && isset($_GET['id'])) {
    $controlador = new CitasControlador();
    $controlador->mostrarDetalle($_GET['id']);
}
?>
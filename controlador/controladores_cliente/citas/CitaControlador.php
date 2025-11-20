<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');

require_once(ROOT_PATH . '/modelo/modelo_cliente/citas/CitaModelo.php');

session_start();


class CitaControlador {
    private $modelo;

    public function __construct($conn) {
        $this->modelo = new CitaModelo($conn);
    }

    // Mostrar formulario de selección de servicios y combos
    public function seleccionar() {
        $servicios = $this->modelo->obtenerServicios();
        $combos = $this->modelo->obtenerCombos();
        $lugares = $this->modelo->obtenerLugares();

        $detalles = [];
        foreach ($servicios as $s) {
            $s['tipo'] = 'servicio';
            $detalles[] = $s;
        }
        foreach ($combos as $c) {
            $c['tipo'] = 'combo';
            $detalles[] = $c;
        }

        return compact('detalles', 'lugares');
    }

    public function guardar() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: layout.php");
        exit;
    }

    $id_cliente = $_SESSION['id_cliente'] ?? $_SESSION['user_id'] ?? $_SESSION['id'] ?? 1;

    $fecha_cita = $_POST['fecha_hora'] ?? '';
    $id_lugar = $_POST['id_lugar'] ?? '';
    $servicios = $_POST['servicios'] ?? [];
    $combos = $_POST['combos'] ?? [];

    // VALIDACIONES
    if (empty($fecha_cita) || empty($id_lugar)) {
        $error = "Faltan datos obligatorios";
        include __DIR__ . '/../../../vista/vista_cliente/vista_citas/seleccionar_servicios.php';
        return;
    }

    // GUARDAR CITA
    $id_cita = $this->modelo->guardarCitaConLugar($id_cliente, $fecha_cita, $id_lugar, $servicios, $combos);

    if (!$id_cita) {
        $error = "Error al guardar la cita";
        include __DIR__ . '/../../../vista/vista_cliente/vista_citas/seleccionar_servicios.php';
        return;
    }

    // OBTENER DETALLES
    $detalles = [];
    foreach ($servicios as $s) {
        $data = $this->modelo->obtenerServiciosPorId($s);
        if ($data) {
            $detalles[] = [
                'tipo' => 'servicio',
                'nombre' => $data['nombre'],
                'precio' => $data['precio_servicio'] ?? 0
            ];
        }
    }
    foreach ($combos as $c) {
        $data = $this->modelo->obtenerCombosPorId($c);
        if ($data) {
            $detalles[] = [
                'tipo' => 'combo',
                'nombre' => $data['nombre'],
                'precio' => $data['precio'] ?? 0
            ];
        }
    }

    // OBTENER LUGARES PARA EL NOMBRE
    $lugares = $this->modelo->obtenerLugares();

    // ASIGNAR VARIABLES PARA LA VISTA
    $GLOBALS['id_cita'] = $id_cita;
    $GLOBALS['fecha_cita'] = $fecha_cita;
    $GLOBALS['id_lugar'] = $id_lugar;
    $GLOBALS['detalles'] = $detalles;
    $GLOBALS['lugares'] = $lugares;

    // INCLUIR LA VISTA DE CONFIRMACIÓN
    include __DIR__ . '/../../../vista/vista_cliente/vista_citas/confirmar_cita.php';
}
}


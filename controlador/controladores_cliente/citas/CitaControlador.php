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
        $id_cliente = $_POST['id_cliente'] ?? 1;
        $fecha_cita = $_POST['fecha_hora'] ?? '';
        $id_lugar = $_POST['id_lugar'] ?? '';
        $servicios = $_POST['servicios'] ?? [];
        $combos = $_POST['combos'] ?? [];

        $id_cita = $this->modelo->guardarCitaConLugar($id_cliente, $fecha_cita, $id_lugar, $servicios, $combos);

        $detalles = [];
        foreach ($servicios as $s) {
            $data = $this->modelo->obtenerServiciosPorId($s);
            if ($data) $detalles[] = ['tipo'=>'servicio','nombre'=>$data['nombre'],'precio'=>$data['precio_servicio']];
        }
        foreach ($combos as $c) {
            $data = $this->modelo->obtenerCombosPorId($c);
            if ($data) $detalles[] = ['tipo'=>'combo','nombre'=>$data['nombre'],'precio'=>$data['precio']];
        }

        $lugares = $this->modelo->obtenerLugares();

        return compact('id_cita','fecha_cita','id_lugar','detalles','lugares');
    }
}


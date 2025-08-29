<?php
<<<<<<< HEAD
require_once __DIR__ . '/../modelo/CitaModelo.php';

=======
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../modelo/CitaModelo.php';


>>>>>>> unir_sistema
class CitaControlador {

    // Mostrar formulario de selección de servicios y combos
    public function seleccionar() {
        $modelo = new CitaModelo();
        $servicios = $modelo->obtenerServicios();
        $combos = $modelo->obtenerCombos();
        $lugares = $modelo->obtenerLugares();

        // Combinar servicios y combos en un solo array
        $detalles = [];
        if (!empty($servicios)) {
            foreach ($servicios as $s) {
                $s['tipo'] = 'servicio';
                $detalles[] = $s;
            }
        }
        if (!empty($combos)) {
            foreach ($combos as $c) {
                $c['tipo'] = 'combo';
                $detalles[] = $c;
            }
        }

        // ✅ devolvemos las variables al index
        return compact('detalles', 'lugares');
    }

    public function guardar() {
        $modelo = new CitaModelo();

        $id_cliente = $_POST['id_cliente'] ?? 1;
        $fecha_cita = $_POST['fecha_hora'] ?? '';
        $id_lugar = $_POST['id_lugar'] ?? '';
        $servicios = $_POST['servicios'] ?? [];
        $combos = $_POST['combos'] ?? [];

        $id_cita = $modelo->guardarCitaConLugar($id_cliente, $fecha_cita, $id_lugar, $servicios, $combos);

        // Traer detalles para mostrar en la confirmación
        $detalles = [];

        foreach ($servicios as $s) {
            $data = $modelo->obtenerServiciosPorId($s);
            if ($data) {
                $detalles[] = ['tipo'=>'servicio', 'nombre'=>$data['nombre'], 'precio'=>$data['precio']];
            }
        }

        foreach ($combos as $c) {
            $data = $modelo->obtenerCombosPorId($c);
            if ($data) {
                $detalles[] = ['tipo'=>'combo', 'nombre'=>$data['nombre'], 'precio'=>$data['precio']];
            }
        }

        // Traer los lugares para mostrar el nombre
        $lugares = $modelo->obtenerLugares();

        // ✅ devolvemos las variables al index
        return compact('id_cita', 'fecha_cita', 'id_lugar', 'detalles', 'lugares');
    }
}

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');

class productos{
    private $conn;
    private $id_servicios;
    private $descripcion;
    private $duracion;
    private $precio;
    private $id_trabajador;
    private $activo;

    public function __construct($conn,$id_servicios,$descripcion,$duracion,$precio,$id_trabajador,
    $activo){
        $this->conn = $conn;
        $this->id_servicios = $id_servicios;
        $this->descripcion = $descripcion;
        $this->duracion = $duracion;
        $this->precio = $precio;
        $this->id_trabajador = $id_trabajador;
        $this->activo = $activo;


    }

    public function mostrar_servicios(){
        $traer_servicios = "SELECT servicios.id_servicios, servicios.nombre, servicios.descripcion, servicios.duracion, servicios.precio, trabajadores.nombre_trabajador, servicios.activo FROM servicios
        INNER JOIN trabajadores ON trabajadores.id_trabajador = servicios.id_trabajador";
        $resultado_traer_servicios = $conn->query($traer_servicios);

        return $resultado_traer_servicios;
    }

    
}

?>
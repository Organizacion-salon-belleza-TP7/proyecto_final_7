<?php
require_once __DIR__ . '/../modelo/ModeloContacto.php';

class ControladorContacto {

    public static function guardar() {

        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            $tipo = $_POST["tipo_contacto"];

            // Como quitaste el ID, ponemos un valor fijo (1)
            $id_proveedor = ($tipo == "proveedor") ? 1 : 0;
            $id_trabajador = ($tipo == "trabajador") ? 1 : 0;

            $datos = [
                "id_proveedor"      => $id_proveedor,
                "id_trabajador"     => $id_trabajador,
                "codigo_area"       => $_POST["codigo_area"],
                "numero_telefonico" => $_POST["numero_telefonico"],
                "correo_electronico"=> $_POST["correo_electronico"]
            ];

            if (ModeloContacto::guardar($datos)) {
                echo "<script>alert('Contacto guardado correctamente');</script>";
            } else {
                echo "<script>alert('Error al guardar contacto');</script>";
            }
        }
    }
}
?>

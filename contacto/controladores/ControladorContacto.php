<?php
require_once "../modelos/ModeloContacto.php";

class ControladorContacto {

    public static function guardarContacto() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            $tipo = $_POST["tipo_contacto"] ?? "";

            // Preparar valores nulos
            $idProveedor = null;
            $idTrabajador = null;

            if ($tipo === "proveedor") {
                $idProveedor = $_POST["id_proveedor"] ?? null;
            }

            if ($tipo === "trabajador") {
                $idTrabajador = $_POST["id_trabajador"] ?? null;
            }

            $data = [
                "id_proveedor" => $idProveedor,
                "id_trabajador" => $idTrabajador,
                "codigo_area" => $_POST["codigo_area"],
                "numero_telefonico" => $_POST["numero_telefonico"],
                "correo_electronico" => $_POST["correo_electronico"]
            ];

            ModeloContacto::insertarContacto($data);

            header("Location: contacto.php");
            exit;
        }
    }

    public static function mostrarProveedores() {
        return ModeloContacto::obtenerProveedores();
    }

    public static function mostrarTrabajadores() {
        return ModeloContacto::obtenerTrabajadores();
    }

    public static function mostrarContactos() {
        return ModeloContacto::obtenerContactos();
    }
}

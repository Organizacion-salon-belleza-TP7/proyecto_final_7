<?php
require_once "../conexion.php";

class ModeloContacto {

    public static function obtenerContactos() {
        $conn = Conexion::conectar();
        $sql = "SELECT c.id_contacto, 
                       p.nombre_proveedor, p.apellido_proveedor,
                       t.nombre_trabajador, t.apellido_trabajador,
                       c.codigo_area, c.numero_telefonico, c.correo_electronico
                FROM contactos c
                LEFT JOIN proveedores p ON c.id_proveedor = p.id_proveedor
                LEFT JOIN trabajadores t ON c.id_trabajador = t.id_trabajador";

        return $conn->query($sql);
    }

    public static function obtenerProveedores() {
        $conn = Conexion::conectar();
        $sql = "SELECT id_proveedor, nombre_proveedor, apellido_proveedor FROM proveedores";
        return $conn->query($sql);
    }

    public static function obtenerTrabajadores() {
        $conn = Conexion::conectar();
        $sql = "SELECT id_trabajador, nombre_trabajador, apellido_trabajador FROM trabajadores";
        return $conn->query($sql);
    }

    public static function insertarContacto($data) {
        $conn = Conexion::conectar();

        $stmt = $conn->prepare("
            INSERT INTO contactos
            (id_proveedor, id_trabajador, codigo_area, numero_telefonico, correo_electronico)
            VALUES (?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "iisss",
            $data["id_proveedor"],
            $data["id_trabajador"],
            $data["codigo_area"],
            $data["numero_telefonico"],
            $data["correo_electronico"]
        );

        return $stmt->execute();
    }
}

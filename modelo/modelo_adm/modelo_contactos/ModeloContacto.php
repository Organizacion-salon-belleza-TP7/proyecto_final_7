<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');

class ModeloContacto {
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /* ================================
       OBTENER TODOS LOS CONTACTOS
       ================================ */
    public function obtenerContactos() {
        $sql = "SELECT c.id_contacto, 
                       p.nombre_proveedor, p.apellido_proveedor,
                       t.nombre_trabajador, t.apellido_trabajador,
                       c.codigo_area, c.numero_telefonico, c.correo_electronico
                FROM contactos c
                LEFT JOIN proveedores p ON c.id_proveedor = p.id_proveedor
                LEFT JOIN trabajadores t ON c.id_trabajador = t.id_trabajador";

        return $this->conn->query($sql);
    }

    /* ================================
       OBTENER LISTA DE PROVEEDORES
       ================================ */
    public function obtenerProveedores() {
        $sql = "SELECT id_proveedor, nombre_proveedor, apellido_proveedor FROM proveedores";
        return $this->conn->query($sql);
    }

    /* ================================
       OBTENER LISTA DE TRABAJADORES
       ================================ */
    public function obtenerTrabajadores() {
        $sql = "SELECT id_trabajador, nombre_trabajador, apellido_trabajador FROM trabajadores";
        return $this->conn->query($sql);
    }

    /* ================================
       INSERTAR CONTACTO NUEVO
       ================================ */
    public function insertarContacto($data) {
        $stmt = $this->conn->prepare("
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

<?php
require_once __DIR__ . '/../conexion.php';

class ModeloContacto {

    public static function guardar($datos) {
        $conn = Conexion::conectar();

        $sql = "INSERT INTO contactos (id_proveedor, id_trabajador, codigo_area, numero_telefonico, correo_electronico)
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "iisss",
            $datos["id_proveedor"],
            $datos["id_trabajador"],
            $datos["codigo_area"],
            $datos["numero_telefonico"],
            $datos["correo_electronico"]
        );

        return $stmt->execute();
    }
}
?>

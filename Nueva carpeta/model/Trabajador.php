<?php
require_once __DIR__ . '/../db.php';

class Trabajador {
    public static function obtenerTodos() {
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM trabajadores WHERE activo = 1");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

public static function listaEspera() {
    global $pdo;

    $sql = "SELECT le.id_lista_espera,
                   le.tiempo_estimado,
                   le.confirmacion,
                   t.nombre_trabajador, t.apellido_trabajador, t.dni AS dni_trabajador,
                   c.nombre AS nombre_cliente, c.apellido AS apellido_cliente, c.dni AS dni_cliente
            FROM lista_espera le
            INNER JOIN usuarios_personas up ON le.id_usuario_persona = up.id_usuarios_personas
            LEFT JOIN trabajadores t ON up.id_trabajador = t.id_trabajador
            LEFT JOIN clientes c ON up.id_cliente = c.id_cliente";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


    public static function confirmar($id) {
        global $pdo;
        $stmt = $pdo->prepare("UPDATE lista_espera SET confirmacion = 1 WHERE id_lista_espera = ?");
        return $stmt->execute([$id]);
    }

    public static function cancelar($id) {
        global $pdo;
        $stmt = $pdo->prepare("UPDATE lista_espera SET confirmacion = 0 WHERE id_lista_espera = ?");
        return $stmt->execute([$id]);
    }
}

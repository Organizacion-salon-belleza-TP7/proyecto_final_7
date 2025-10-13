<?php
require_once __DIR__ . '/../model/Trabajador.php';

class TrabajadorController {
    public static function pantalla() {
        $trabajadores = Trabajador::obtenerTodos();
        include __DIR__ . '/../view/pantallaTrabajador.php';
    }

    public static function listaEspera() {
        $lista = Trabajador::listaEspera();
        include __DIR__ . '/../view/listaEspera.php';
    }

    public static function confirmar($id) {
        Trabajador::confirmar($id);
        header("Location: pantallaTrabajador.php");
    }

    public static function cancelar($id) {
        Trabajador::cancelar($id);
        header("Location: pantallaTrabajador.php");
    }

    public static function cerrarSesion() {
        session_start();
        session_destroy();
        include __DIR__ . '/../view/cerrarSesion.php';
    }
}

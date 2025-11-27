<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once(__DIR__ . '/../../config/db.php');

class logout{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function cerrar_session($id_usuario){
        $seleccionar_usuario = $this->conn->prepare("SELECT id_usuario, nombre_usuario, contrasena, id_tipo_usuario FROM usuarios WHERE id_usuario = ?");
        $seleccionar_usuario->bind_param("i",$id_usuario);

        if($seleccionar_usuario->execute()){
            $seleccionar_usuario->get_result();
            $fecha_actual = date('Y-m-d H:i:s');
            $insertar_logout = $this->conn->prepare("UPDATE `historial_logeos` SET fecha_logout = ? WHERE id_usuario = ?");
            $insertar_logout->bind_param("si",$fecha_actual,$id_usuario);

            if($insertar_logout->execute()){
                return true;

            }else{
                return false;
            }

        }
    }
}
?>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');

class logout{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function cerrar_session($id_usuario){
        $seleccionar_usuario = $this->conn->prepare("SELECT id_usuario, nombre_usuario, contrasena, dni, id_tipo_usuario FROM usuarios WHERE id_usuario = ?");
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

    public function mostrar_logueos(){
        $sql_traer_logueos ="SELECT historial_logeos.id_historial_logueos, usuarios.nombre_usuario, historial_logeos.fecha_logueo, historial_logeos.fecha_logout FROM historial_logeos
        INNER JOIN usuarios ON usuarios.id_usuario = historial_logeos.id_usuario";

        $traer_logueos = $this->conn->query($sql_traer_logueos);

        return $traer_logueos;


    }

}
?>
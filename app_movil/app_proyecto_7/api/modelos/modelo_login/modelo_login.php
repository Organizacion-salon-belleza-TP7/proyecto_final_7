<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once(__DIR__ . '/../../config/db.php');

class iniciar_session {
    private $nombre;
    private $contrasena;

    public function __construct($nombre,$contrasena){
        $this->nombre = $nombre;
        $this->contrasena = $contrasena;

    }

    public function buscar_usuario($conn){
        $consulta_buscar = $conn->prepare("SELECT id_usuario, nombre_usuario, contrasena, id_tipo_usuario 
        FROM usuarios WHERE nombre_usuario = ? 
        AND contrasena = ?");

        $consulta_buscar->bind_param("ss",$this->nombre, $this->contrasena);

        $consulta_buscar->execute();

        $resultado_consulta = $consulta_buscar->get_result();

        return $resultado_consulta;
    }

    public function discriminar_adm($usuario,$conn){

        if (!is_null($usuario['id_usuario'])){

            $consulta_admin = "SELECT usuarios.id_usuario,usuarios.nombre_usuario,usuarios.contrasena,tipo_usuario.id_tipo_usuario
            FROM usuarios
            INNER JOIN tipo_usuario ON usuarios.id_tipo_usuario = tipo_usuario.id_tipo_usuario
            WHERE tipo_usuario.id_tipo_usuario = 1";


            $resultado_adm = $conn->query($consulta_admin);

            $id_usuario = $usuario['id_usuario'];
            $consulta_relacion = $conn->prepare("SELECT id_usuarios_personas, id_trabajador, id_cliente, id_usuario FROM `usuarios_personas` WHERE id_usuario = ?");
            $consulta_relacion->bind_param('i',$id_usuario);

            if($consulta_relacion->execute()){
                $resultado_relacion = $consulta_relacion->get_result();
                return [
                    'resultado_adm' => $resultado_adm,
                    'traer_adm' => $resultado_relacion
                ];


            }

            
            
        }else{
            echo '<script language = javascript>
            alert("hubo un fallo al traer el dni del adm")
            self.location = "' . BASE_URL . '/vista/vista_login/vista_login.php";
            </script>';
        }
    }

    public function discriminar_empleados($usuario,$conn){

        if(!is_null($usuario['id_usuario'])){
            
            $consulta_trabajador = "SELECT usuarios.id_usuario,usuarios.nombre_usuario,usuarios.contrasena,tipo_usuario.id_tipo_usuario
            FROM usuarios
            INNER JOIN tipo_usuario ON usuarios.id_tipo_usuario = tipo_usuario.id_tipo_usuario
            WHERE tipo_usuario.id_tipo_usuario = 3";

            $resultado_trabajador = $conn->query($consulta_trabajador);


            $id_usuario = $usuario['id_usuario'];
            $consulta_relacion = $conn->prepare("SELECT id_usuarios_personas, id_trabajador, id_cliente, id_usuario FROM `usuarios_personas` WHERE id_usuario = ?");
            $consulta_relacion->bind_param('i',$id_usuario);

             if($consulta_relacion->execute()){
                $resultado_relacion = $consulta_relacion->get_result();
                return [
                    'resultado_emp' => $resultado_trabajador,
                    'traer_emp' => $resultado_relacion
                ];


            }



        }else{
            echo '<script language = javascript>
            alert("hubo un fallo al traer el dni del trabajador")
            self.location = "' . BASE_URL . '/vista/vista_login/vista_login.php"
            </script>';
        }

    }

    public function discriminar_cliente($usuario,$conn){
        
        if(!is_null($usuario['id_usuario'])){
            $consulta_cliente = "SELECT usuarios.id_usuario,usuarios.nombre_usuario,usuarios.contrasena,tipo_usuario.id_tipo_usuario
            FROM usuarios
            INNER JOIN tipo_usuario ON usuarios.id_tipo_usuario = tipo_usuario.id_tipo_usuario
            WHERE tipo_usuario.id_tipo_usuario = 2";

            $resultado_cliente = $conn->query($consulta_cliente);

            $id_usuario = $usuario['id_usuario'];
            $consulta_relacion = $conn->prepare("SELECT id_usuarios_personas, id_trabajador, id_cliente, id_usuario FROM `usuarios_personas` WHERE id_usuario = ?");
            $consulta_relacion->bind_param('i',$id_usuario);

             if($consulta_relacion->execute()){
                $resultado_relacion = $consulta_relacion->get_result();
                return [
                    'resultado_cli' => $resultado_cliente,
                    'traer_cli' => $resultado_relacion
                ];


            }


        }else{
            echo '<script language = javascript>
            alert("hubo un fallo al traer el dni del cliente")
            self.location = "' . BASE_URL . '/vista/vista_login/vista_login.php"
            </script>';
        }

    }

    public function insertar_historial_login($conn,$id_usuario,$fecha_actual){
        $consulta_insert_logueo = $conn->prepare("INSERT INTO `historial_logeos`(id_usuario, fecha_logueo) VALUES (?,?)");
        $consulta_insert_logueo->bind_param("is",$id_usuario,$fecha_actual);

        if($consulta_insert_logueo->execute()){
            return true;

        }else{
            return false;
        }

    }
}
?>
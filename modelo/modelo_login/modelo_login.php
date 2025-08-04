<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once(__DIR__ . '/../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');
class iniciar_session {
    private $nombre;
    private $contrasena;

    public function __construct($nombre,$contrasena){
        $this->nombre = $nombre;
        $this->contrasena = $contrasena;

    }

    public function buscar_usuario($conn){
        $consulta_buscar = $conn->prepare("SELECT nombre_usuario, contrasena, id_tipo_usuario,dni 
        FROM usuarios WHERE nombre_usuario = ? 
        AND contrasena = ?");

        $consulta_buscar->bind_param("ss",$this->nombre, $this->contrasena);

        $consulta_buscar->execute();

        $resultado_consulta = $consulta_buscar->get_result();

        return $resultado_consulta;
    }

    public function discriminar_adm($usuario,$conn){

        if (!is_null($usuario['dni'])){
            $dni = intval($usuario['dni']);

            $consulta_admin = "SELECT usuarios.id_usuario,usuarios.nombre_usuario,usuarios.contrasena,trabajadores.dni,tipo_usuario.id_tipo_usuario
            FROM usuarios
            INNER JOIN tipo_usuario ON usuarios.id_tipo_usuario = tipo_usuario.id_tipo_usuario
            INNER JOIN trabajadores ON usuarios.dni = trabajadores.dni
            WHERE usuarios.dni = $dni AND trabajadores.dni = $dni AND tipo_usuario.tipo_usuario = 'administrador'";

            $resultado_adm = $conn->query($consulta_admin);

            return $resultado_adm;
            
            
        }else{
            echo '<script language = javascript>
            alert("hubo un fallo al traer el dni del adm")
            self.location = "' . BASE_URL . '/vista/vista_login/vista_login.php";
            </script>';
        }
    }

    public function discriminar_empleados($usuario,$conn){

        if(!is_null($usuario['dni'])){
            $dni_trabajador = intval($usuario['dni']);
            
            $consulta_trabajador = "SELECT usuarios.id_usuario,usuarios.nombre_usuario,usuarios.contrasena,trabajadores.dni,tipo_usuario.id_tipo_usuario
            FROM usuarios
            INNER JOIN tipo_usuario ON usuarios.id_tipo_usuario = tipo_usuario.id_tipo_usuario
            INNER JOIN trabajadores ON usuarios.dni = trabajadores.dni
            WHERE usuarios.dni = $dni_trabajador AND trabajadores.dni = $dni_trabajador";

            $resultado_trabajador = $conn->query($consulta_trabajador);

            return $resultado_trabajador;


        }else{
            echo '<script language = javascript>
            alert("hubo un fallo al traer el dni del trabajador")
            self.location = "' . BASE_URL . '/vista/vista_login/vista_login.php"
            </script>';
        }

    }

    public function discriminar_cliente($usuario,$conn){
        
        if(is_null($usuario['dni'])){
            $consulta_cliente = "SELECT usuarios.id_usuario,usuarios.nombre_usuario,usuarios.contrasena,usuarios.dni,tipo_usuario.id_tipo_usuario
            FROM usuarios
            INNER JOIN tipo_usuario ON usuarios.id_tipo_usuario = tipo_usuario.id_tipo_usuario
            WHERE usuarios.dni IS NULL AND tipo_usuario.tipo_usuario = 'cliente'";

            $resultado_cliente = $conn->query($consulta_cliente);

            return $resultado_cliente;

        }else{
            echo '<script language = javascript>
            alert("hubo un fallo al traer el dni del cliente")
            self.location = "' . BASE_URL . '/vista/vista_login/vista_login.php"
            </script>';
        }

    }
}
?>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once(__DIR__ . '/../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');
class crear_usuario{
    private $conn;

    public function __construct($conn){
        $this->conn = $conn;

    }

    public function crear_usuario($nombre_usuario,$contrasena_usuario,$nombre_cliente,$apellido_cliente,$alergias_cliente,$fecha_nacimiento_cliente,$dni_cliente){
        $insertar_usuario = $this->conn->prepare("INSERT INTO `usuarios`(nombre_usuario, contrasena) VALUES (?,?)");
        $insertar_usuario->bind_param('ss',$nombre_usuario,$contrasena_usuario);

        if($insertar_usuario->execute()){
            $ultimo_id_usuario = $this->conn->insert_id;

            $agregar_cliente = $this->conn->prepare("INSERT INTO `clientes`(nombre, apellido, alergias, fecha_nacimiento, dni) VALUES (?,?,?,?,?)");
            $agregar_cliente->bind_param('ssssi',$nombre_cliente,$apellido_cliente,$alergias_cliente,$fecha_nacimiento_cliente,$dni_cliente);

            if($agregar_cliente->execute()){
                $ultimo_cliente_insertado = $this->conn->insert_id;

                $insertar_relacion = $this->conn->prepare("INSERT INTO `usuarios_personas`(id_trabajador, id_cliente, id_usuario) VALUES (null,?,?)");
                $insertar_relacion->bind_param('ii',$ultimo_cliente_insertado,$ultimo_id_usuario);

                if($insertar_relacion->execute()){
                    return true;

                }else{
                    return false;
                }
            }


        }
    }
}
?>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');
require_once(ROOT_PATH . '/vendor/autoload.php');

$dotenv = Dotenv\Dotenv::createImmutable(ROOT_PATH);
$dotenv->load();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


class pagina_landing{
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function traer_servicios(){
        $traer_servicios = "SELECT id_servicios, nombre, descripcion, duracion, id_tiempo_servicio, precio_servicio, activo, id_tipo_servicio, imagen 
        FROM servicios WHERE 1
        LIMIT 6";

        $resultado = $this->conn->query($traer_servicios);

        return $resultado;

    }

    public function traer_inventario(){
        $traer_inventario = "SELECT id_inventario, nombre_producto, imagen_producto FROM inventario WHERE 1
        LIMIT 6";

        $resultado_inventario = $this->conn->query($traer_inventario);

        return $resultado_inventario;
    }

    public function traer_lugares(){
        $traer_lugares = "SELECT id_lugar, nombre_lugar, cooordenadas, imagen_lugar, activo FROM lugares WHERE 1
        LIMIT 2";

        $resultado_traer_lugares = $this->conn->query($traer_lugares);

        return $resultado_traer_lugares;
    }

    public function enviar_mail($nombre,$email,$telefono,$servicios_interes,$mensaje_personal){

        $mail = new PHPMailer(true);

        try {
        // Configuración SMTP (usar variables .env)
            $mail->isSMTP();
            $mail->Host       = $_ENV['EMAIL_HOST'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $_ENV['EMAIL_USER'];
            $mail->Password   = $_ENV['EMAIL_PASS'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = $_ENV['EMAIL_PORT'];

        // Remitente (tu email)
            $mail->setFrom($_ENV['EMAIL_USER'], 'Formulario Web del Salon');

        // A quién te llega el email
            $mail->addAddress('rosespasalonbelleza23@gmail.com'); // tu correo real

            //para responder
            $mail->addReplyTo($email, $nombre);

        // Contenido del mensaje
            $mail->isHTML(true);
            $mail->Subject = 'Nueva Solicitud de Información - Web del Salón';

            $mail->Body = "
                <h2>Nueva Solicitud de Información</h2>
                <p><strong>Nombre:</strong> $nombre</p>
                <p><strong>Email:</strong> $email</p>
                <p><strong>Teléfono:</strong> $telefono</p>
                <p><strong>Servicio de interés:</strong> $servicios_interes</p>
                <p><strong>Mensaje:</strong><br>$mensaje_personal</p>
                <hr>
                <p>Enviado desde la landing page del salón.</p>
            ";

            $mail->send();

            return true;

        } catch (Exception $e) {
            echo "Error al enviar email: {$mail->ErrorInfo}";
        }
    }



}

?>
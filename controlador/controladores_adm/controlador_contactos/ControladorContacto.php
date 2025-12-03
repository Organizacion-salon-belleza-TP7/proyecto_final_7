<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');
require_once(ROOT_PATH . '/modelo/modelo_adm/modelo_contactos/ModeloContacto.php');

session_start();
date_default_timezone_set('America/Argentina/Buenos_Aires');

// Crear instancia del modelo con tu conexión
$modeloContacto = new ModeloContacto($conn);


/* =====================================================
   AGREGAR CONTACTO PARA TRABAJADOR
   ===================================================== */
if (isset($_GET['agregar_contacto_trabajador'])) {
    $vista = $_GET['agregar_contacto_trabajador']; // ej: "vista_trabajadores"
    header("Location: " . BASE_URL . "/vista/vista_adm/vista_contactos/contacto.php?vista=$vista");
    exit;
}


/* =====================================================
   AGREGAR CONTACTO PARA PROVEEDOR
   ===================================================== */
if (isset($_GET['agregar_contacto_proveedor'])) {
    $vista = $_GET['agregar_contacto_proveedor']; // ej: "vista_proveedores"
    header("Location: " . BASE_URL . "/vista/vista_adm/vista_contactos/contacto.php?vista=$vista");
    exit;
}



/* =====================================================
   GUARDAR CONTACTO (POST)
   ===================================================== */
if (isset($_POST['guardar_contacto'])) {

    $pantalla_origen = $_POST['pantalla_origen']; // viene desde el formulario
    $tipo = $_POST['tipo_contacto']; // viene como campo oculto

    // Validar que el tipo sea válido
    if (!in_array($tipo, ['proveedor', 'trabajador'])) {
        echo '<script>
            alert("Error: Tipo de contacto inválido");
            history.back();
        </script>';
        exit;
    }

    // Validar campos según el tipo
    if ($tipo === "proveedor") {
        if (empty($_POST["id_proveedor"])) {
            echo '<script>
                alert("Por favor seleccione un proveedor");
                history.back();
            </script>';
            exit;
        }
        $idProveedor = $_POST["id_proveedor"];
        $idTrabajador = null;
    } 
    elseif ($tipo === "trabajador") {
        if (empty($_POST["id_trabajador"])) {
            echo '<script>
                alert("Por favor seleccione un trabajador");
                history.back();
            </script>';
            exit;
        }
        $idProveedor = null;
        $idTrabajador = $_POST["id_trabajador"];
    }

    // Validar campos requeridos
    if (empty($_POST["codigo_area"]) || empty($_POST["numero_telefonico"]) || empty($_POST["correo_electronico"])) {
        echo '<script>
            alert("Todos los campos son requeridos");
            history.back();
        </script>';
        exit;
    }

    // Validar correo electrónico
    $correo = filter_var($_POST["correo_electronico"], FILTER_SANITIZE_EMAIL);
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        echo '<script>
            alert("Correo electrónico inválido");
            history.back();
        </script>';
        exit;
    }

    // Validar que el código de área y teléfono sean numéricos
    if (!is_numeric($_POST["codigo_area"]) || !is_numeric($_POST["numero_telefonico"])) {
        echo '<script>
            alert("El código de área y número telefónico deben ser numéricos");
            history.back();
        </script>';
        exit;
    }

    $data = [
        "id_proveedor"      => $idProveedor,
        "id_trabajador"     => $idTrabajador,
        "codigo_area"       => $_POST["codigo_area"],
        "numero_telefonico" => $_POST["numero_telefonico"],
        "correo_electronico"=> $correo
    ];

    $insertado = $modeloContacto->insertarContacto($data);

    if ($insertado) {
        // Determinar a dónde redirigir según el tipo
        if ($tipo === "proveedor") {
            // Redirigir a la vista de proveedores
            $redireccion = BASE_URL . '/vista/vista_adm/proveedores/vista_proveedores.php';
        } else {
            // Redirigir a la vista de trabajadores
            $redireccion = BASE_URL . '/vista/vista_adm/trabajadores/trabajadores_lista.php';
        }
        
        echo '<script>
            alert("Contacto agregado correctamente");
            self.location = "' . $redireccion . '";
        </script>';
        exit;
    } else {
        echo '<script>
            alert("Error al agregar contacto");
            history.back();
        </script>';
        exit;
    }
}



/* =====================================================
   MOSTRAR CONTACTOS
   ===================================================== */
if (isset($_GET['listar']) && $_GET['listar'] === 'contactos') {
    $vista = $_GET['vista']; // pantalla desde donde se llamó
    $contactos = $modeloContacto->obtenerContactos();
    require_once(ROOT_PATH . "/vista/vista_adm/contactos/vista_listado_contactos.php");
    exit;
}

?>
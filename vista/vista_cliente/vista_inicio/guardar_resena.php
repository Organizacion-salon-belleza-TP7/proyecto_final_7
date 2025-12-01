<?php
require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');
session_start();
if (!$_SESSION['user']) die();

$id = (int)$_POST['id'];
$est = (int)$_POST['estrellas'];
$com = $conn->real_escape_string($_POST['comentario']);

$conn->query("UPDATE citas SET resena_estrellas = $est, resena_comentario = '$com' WHERE id_cita = $id");
?>
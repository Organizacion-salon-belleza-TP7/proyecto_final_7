<?php
require_once __DIR__ . '/../db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    $stmt = $pdo->prepare("UPDATE lista_espera SET confirmacion = 1 WHERE id_lista_espera = ?");
    $stmt->execute([$id]);
}

header("Location: listaEspera.php");
exit;

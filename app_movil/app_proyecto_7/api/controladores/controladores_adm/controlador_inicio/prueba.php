<?php

$uploadDir = __DIR__ . '/../../../../../../imagenes/inventario/';

// Ver la ruta original
echo "Ruta construida: " . $uploadDir . PHP_EOL;

// Ver la ruta real (resolviendo ../)
$realPath = realpath($uploadDir);
if ($realPath) {
    echo "Ruta real: " . $realPath . PHP_EOL;
} else {
    echo "❌ No se pudo resolver la ruta con realpath()" . PHP_EOL;
}

// Verificar si existe
if (is_dir($uploadDir)) {
    echo "✅ La ruta existe";
} else {
    echo "❌ La ruta NO existe";
}
?>


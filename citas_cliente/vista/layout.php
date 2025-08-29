<<<<<<< HEAD
=======
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
>>>>>>> unir_sistema
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spa - Reservar Cita</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

    <header class="bg-pink-600 text-white py-4 shadow">
        <h1 class="text-3xl text-center font-bold">Centro de Belleza SPA</h1>
    </header>

    <main class="py-10">
        <?php
        // Aquí se incluye la vista según la acción
        if(isset($vista)) {
            include __DIR__ . "/$vista.php";
        }
        ?>
    </main>

    <footer class="bg-gray-200 text-center py-4 mt-10">
    </footer>

</body>
</html>

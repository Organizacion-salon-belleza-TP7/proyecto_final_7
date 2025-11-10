<?php
class Conexion {
    private static $instancia = null;

    public static function conectar() {
        if (self::$instancia === null) {
            try {
                $host = 'localhost';
                $dbname = 'trabajo_final_7';  
                $username = 'root';           
                $password = '';               

                $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                self::$instancia = $pdo;
            } catch (PDOException $e) {
                die("Error de conexión: " . $e->getMessage());
            }
        }
        return self::$instancia;
    }
}
?>
<?php
// Zona horaria del sitio (Colombia, UTC-5, sin horario de verano).
// Con esto PHP (date) y MySQL (CURRENT_TIMESTAMP) usan la misma hora.
date_default_timezone_set('America/Bogota');


$db_host = "localhost"; 
$db_nombre = "restaurante";
$db_usuario = "root";   
$db_password = "";    

try {
    $pdo = new PDO(
        "mysql:host=$db_host;dbname=$db_nombre;charset=utf8mb4",
        $db_usuario,
        $db_password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET time_zone = '-05:00'",
        ]
    );
} catch (PDOException $e) {
    // No mostramos el error real al usuario final por seguridad,
    // pero lo dejamos disponible para depuración en el log del servidor.
    error_log("Error de conexión a la base de datos: " . $e->getMessage());
    die("No fue posible conectar con la base de datos. Intenta más tarde.");
}

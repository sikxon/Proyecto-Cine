<?php
$host = "localhost"; // Servidor (localhost para WAMP)
$dbname = "cine"; // Cambia por el nombre de tu base de datos
$username = "root"; // Usuario por defecto en WAMP
$password = ""; // Contraseña (dejar en blanco si no configuraste una)

try {
    // Crear la conexión PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
    // Configurar el modo de errores para PDO
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    // Manejo de errores
    echo "Error en la conexión: " . $e->getMessage();
}
?>


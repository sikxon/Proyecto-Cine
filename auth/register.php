<?php
require_once '../db_connection.php';
require_once '../config.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    $fechaDeNacimiento = $_POST['fechaDeNacimiento'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Insertar los datos en la tabla Cliente
    $stmt = $conn->prepare("INSERT INTO Cliente (Nombre, Apellido, Email, Telefono, Direccion, FechaDeNacimiento, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $nombre, $apellido, $email, $telefono, $direccion, $fechaDeNacimiento, $password);

    if ($stmt->execute()) {
        echo "Registro exitoso. <a href='login.php'>Inicia sesión aquí</a>.";
    } else {
        echo "Error al registrar el cliente: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
</head>
<body>
    <h1>Registro de Cliente</h1>
    <form method="POST">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required>
        <br>
        <label for="apellido">Apellido:</label>
        <input type="text" id="apellido" name="apellido" required>
        <br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <br>
        <label for="telefono">Teléfono:</label>
        <input type="text" id="telefono" name="telefono" required>
        <br>
        <label for="direccion">Dirección:</label>
        <input type="text" id="direccion" name="direccion" required>
        <br>
        <label for="fechaDeNacimiento">Fecha de Nacimiento:</label>
        <input type="date" id="fechaDeNacimiento" name="fechaDeNacimiento" required>
        <br>
        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <button type="submit">Registrar</button>
    </form>
</body>
</html>

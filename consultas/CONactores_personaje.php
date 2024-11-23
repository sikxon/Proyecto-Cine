<?php
require_once '../db_connection.php';

$query = "SELECT Pelicula.titulo, Actor.nombre, Actor.apellido, Personaje.nombre AS Personaje, Personaje.rol
            FROM Pelicula
            JOIN Personaje ON Pelicula.ID_Pelicula = Personaje.ID_Pelicula
            JOIN Actor ON Personaje.DNI_Actor = Actor.DNI_Actor";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Consulta de actores y personajes que interpretan</title>
</head>
<body>
    <h1>Consulta de actores y personajes que interpretan</h1>
    <table border="1">
        <tr>
            <th>Titulo</th>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Personaje</th>
            <th>Rol</th>
        </tr>
        <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <td><?= $row['Titulo'] ?></td>
                <td><?= $row['Nombre'] ?></td>
                <td><?= $row['Apellido'] ?></td>
                <td><?= $row['Personaje'] ?></td>
                <td><?= $row['Rol'] ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
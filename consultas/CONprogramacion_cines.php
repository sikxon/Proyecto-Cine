<?php
require_once '../db_connection.php';

$query = "SELECT Pelicula.titulo, Funcion.Inicio, Funcion.Fin, Cine.nombre_cine
            FROM Pelicula
            JOIN Funcion ON Pelicula.ID_Pelicula = Funcion.ID_Pelicula
            JOIN Salas ON Funcion.ID_Salas = Salas.ID_Sala
            JOIN Cine ON Salas.ID_Cine = Cine.ID_Cine";

$result = $pdo->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Consulta de funciones de un cine especifico</title>
</head>
<body>
    <h1>Consulta de Entradas</h1>
    <table border="1">
        <tr>
            <th>Título</th>
            <th>Inicio</th>
            <th>Fin</th>
            <th>Nombre de cine</th>
        </tr>
        <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <td><?= $row['titulo'] ?></td>
                <td><?= $row['Inicio'] ?></td>
                <td><?= $row['Fin'] ?></td>
                <td><?= $row['Nombre de cine'] ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
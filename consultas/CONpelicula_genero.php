<?php
require_once '../db_connection.php';

$query = "SELECT Pelicula.titulo, Genero.Nombre AS Genero, Pelicula.Año_de_estreno, Pelicula.Director
          FROM Pelicula
          JOIN Genero_Pelicula ON Pelicula.ID_Pelicula = Genero_Pelicula.ID_Pelicula
          JOIN Genero ON Genero_Pelicula.ID_Genero = Genero.ID_Genero";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Consulta de Películas y Géneros</title>
</head>
<body>
    <h1>Consulta de Películas y Géneros</h1>
    <table border="1">
        <tr>
            <th>Título</th>
            <th>Género</th>
            <th>Año de Estreno</th>
            <th>Director</th>
        </tr>
        <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <td><?= $row['titulo'] ?></td>
                <td><?= $row['Genero'] ?></td>
                <td><?= $row['Año_de_estreno'] ?></td>
                <td><?= $row['Director'] ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
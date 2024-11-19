<?php
require_once '../db_connection.php';

$query = "SELECT Pelicula.titulo, Pelicula.Clasificacion, Pelicula.idioma, Factura_Detalles.PrecioPorEntrada, 
                 Salas.ID_Sala, Salas.Tipo, Butacas.NumeroDeFila, Butacas.NumeroDeAsiento, Funcion.Inicio, Funcion.Fin 
          FROM Pelicula
          JOIN Funcion ON Pelicula.ID_Pelicula = Funcion.ID_Pelicula
          JOIN Salas ON Funcion.ID_Salas = Salas.ID_Sala
          JOIN Butacas ON Salas.ID_Sala = Butacas.ID_Sala
          JOIN Factura_Detalles ON Funcion.ID_Programacion = Factura_Detalles.ID_FacDet";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Consulta de Entradas</title>
</head>
<body>
    <h1>Consulta de Entradas</h1>
    <table border="1">
        <tr>
            <th>Título</th>
            <th>Clasificación</th>
            <th>Idioma</th>
            <th>Precio</th>
            <th>Sala</th>
            <th>Tipo</th>
            <th>Fila</th>
            <th>Asiento</th>
            <th>Inicio</th>
            <th>Fin</th>
        </tr>
        <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <td><?= $row['titulo'] ?></td>
                <td><?= $row['Clasificacion'] ?></td>
                <td><?= $row['idioma'] ?></td>
                <td><?= $row['PrecioPorEntrada'] ?></td>
                <td><?= $row['ID_Sala'] ?></td>
                <td><?= $row['Tipo'] ?></td>
                <td><?= $row['NumeroDeFila'] ?></td>
                <td><?= $row['NumeroDeAsiento'] ?></td>
                <td><?= $row['Inicio'] ?></td>
                <td><?= $row['Fin'] ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
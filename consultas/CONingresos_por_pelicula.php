<?php
require_once '../db_connection.php';

$query = "SELECT Pelicula.titulo, SUM(Factura_Detalles.Subtotal) AS IngresosTotales
            FROM Pelicula
            JOIN Funcion ON Pelicula.ID_Pelicula = Funcion.ID_Pelicula
            JOIN Factura_Detalles ON Funcion.ID_Programacion = Factura_Detalles.ID_FacDet
            GROUP BY Pelicula.titulo";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Consulta de ingresos totales por pelicula</title>
</head>
<body>
    <h1>Consulta de ingresos totales por pelicula</h1>
    <table border="1">
        <tr>
            <th>Titulo</th>
            <th>Ingresos totales</th>
        </tr>
        <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <td><?= $row['Titulo'] ?></td>
                <td><?= $row['Ingresos totales'] ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
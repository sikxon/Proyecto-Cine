<?php
require_once '../db_connection.php';

$query = "SELECT Butacas.NumeroDeFila, Butacas.NumeroDeAsiento, Butacas.Estado
            FROM Funcion
            JOIN Salas ON Funcion.ID_Salas = Salas.ID_Sala
            JOIN Butacas ON Salas.ID_Sala = Butacas.ID_Sala
            WHERE Butacas.Estado = 'Libre'";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Consulta de disponibilidad de butacas</title>
</head>
<body>
    <h1>Consulta de disponibilidad de butacas</h1>
    <table border="1">
        <tr>
            <th>Numero de fila</th>
            <th>Numero de asiento</th>
            <th>Estado</th>
        </tr>
        <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <td><?= $row['Numero de fila'] ?></td>
                <td><?= $row['Numero de asiento'] ?></td>
                <td><?= $row['Estado'] ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
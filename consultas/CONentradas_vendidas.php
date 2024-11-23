<?php
require_once '../db_connection.php';

$query = "SELECT Cliente.Nombre, Cliente.Apellido, Entrada.FechaDeCompra, Factura_Detalles.CantidadDeEntradas, Factura_Detalles.Subtotal
            FROM Cliente
            JOIN Entrada ON Cliente.ID_Cliente = Entrada.ID_Cliente
            JOIN Factura_Detalles ON Entrada.ID_FacDet = Factura_Detalles.ID_FacDet";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Consulta de entradas vendidas y a que cliente</title>
</head>
<body>
    <h1>Consulta de entradas vendidas y a que cliente</h1>
    <table border="1">
        <tr>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>FechaDeCompra</th>
            <th>CantidadDeEntradas</th>
            <th>Subtotal</th>
        </tr>
        <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <td><?= $row['Nombre'] ?></td>
                <td><?= $row['Apellido'] ?></td>
                <td><?= $row['FechaDeCompra'] ?></td>
                <td><?= $row['CantidadDeEntradas'] ?></td>
                <td><?= $row['Subtotal'] ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
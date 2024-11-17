<?php
require_once 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];

    if ($action == "add") {
        $cantidad_entradas = $_POST['cantidad_entradas'];
        $precio_por_entrada = $_POST['precio_por_entrada'];
        $subtotal = $_POST['subtotal'];

        $stmt = $conn->prepare("INSERT INTO Factura_Detalles (CantidadDeEntradas, PrecioPorEntrada, Subtotal) VALUES (?, ?, ?)");
        $stmt->bind_param("idd", $cantidad_entradas, $precio_por_entrada, $subtotal);
        $stmt->execute();
        echo "Factura Detalles añadida exitosamente.";
    } elseif ($action == "edit") {
        $id_facdet = $_POST['id_facdet'];
        $cantidad_entradas = $_POST['cantidad_entradas'];
        $precio_por_entrada = $_POST['precio_por_entrada'];
        $subtotal = $_POST['subtotal'];

        $stmt = $conn->prepare("UPDATE Factura_Detalles SET CantidadDeEntradas = ?, PrecioPorEntrada = ?, Subtotal = ? WHERE ID_FacDet = ?");
        $stmt->bind_param("iddi", $cantidad_entradas, $precio_por_entrada, $subtotal, $id_facdet);
        $stmt->execute();
        echo "Factura Detalles actualizada exitosamente.";
    } elseif ($action == "delete") {
        $id_facdet = $_POST['id_facdet'];

        $stmt = $conn->prepare("DELETE FROM Factura_Detalles WHERE ID_FacDet = ?");
        $stmt->bind_param("i", $id_facdet);
        $stmt->execute();
        echo "Factura Detalles eliminada exitosamente.";
    }
}

$result = $conn->query("SELECT * FROM Factura_Detalles");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD de Factura Detalles</title>
</head>
<body>
    <h1>CRUD de Factura Detalles</h1>

    <form method="POST">
        <input type="hidden" name="action" value="add">
        <input type="number" name="cantidad_entradas" placeholder="Cantidad de Entradas" required>
        <input type="number" step="0.01" name="precio_por_entrada" placeholder="Precio por Entrada" required>
        <input type="number" step="0.01" name="subtotal" placeholder="Subtotal" required>
        <button type="submit">Añadir Factura Detalles</button>
    </form>

    <h2>Lista de Factura Detalles</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Cantidad de Entradas</th>
            <th>Precio por Entrada</th>
            <th>Subtotal</th>
            <th>Acciones</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['ID_FacDet'] ?></td>
                <td><?= $row['CantidadDeEntradas'] ?></td>
                <td><?= $row['PrecioPorEntrada'] ?></td>
                <td><?= $row['Subtotal'] ?></td>
                <td>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id_facdet" value="<?= $row['ID_FacDet'] ?>">
                        <button type="submit">Eliminar</button>
                    </form>
                    <button onclick="editFacturaDetalles(<?= $row['ID_FacDet'] ?>, <?= $row['CantidadDeEntradas'] ?>, <?= $row['PrecioPorEntrada'] ?>, <?= $row['Subtotal'] ?>)">Editar</button>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <script>
        function editFacturaDetalles(id, cantidad, precio, subtotal) {
            const form = document.createElement('form');
            form.method = 'POST';

            form.innerHTML = `
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id_facdet" value="${id}">
                <input type="number" name="cantidad_entradas" value="${cantidad}" required>
                <input type="number" step="0.01" name="precio_por_entrada" value="${precio}" required>
                <input type="number" step="0.01" name="subtotal" value="${subtotal}" required>
                <button type="submit">Actualizar</button>
            `;

            document.body.appendChild(form);
            form.submit();
        }
    </script>
</body>
</html>


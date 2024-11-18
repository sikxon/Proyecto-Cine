<?php
require_once '../db_connection.php';
require_once '../config.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];

    if ($action == "add") {
        $precio = $_POST['precio'];
        $fecha_de_compra = $_POST['fecha_de_compra'];
        $id_facdet = $_POST['id_facdet'];
        $id_cliente = $_POST['id_cliente'];

        $stmt = $conn->prepare("INSERT INTO Entrada (Precio, FechaDeCompra, ID_FacDet, ID_Cliente) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("dsii", $precio, $fecha_de_compra, $id_facdet, $id_cliente);
        $stmt->execute();
        echo "Entrada añadida exitosamente.";
    } elseif ($action == "edit") {
        $id_entrada = $_POST['id_entrada'];
        $precio = $_POST['precio'];
        $fecha_de_compra = $_POST['fecha_de_compra'];
        $id_facdet = $_POST['id_facdet'];
        $id_cliente = $_POST['id_cliente'];

        $stmt = $conn->prepare("UPDATE Entrada SET Precio = ?, FechaDeCompra = ?, ID_FacDet = ?, ID_Cliente = ? WHERE ID_Entrada = ?");
        $stmt->bind_param("dsiii", $precio, $fecha_de_compra, $id_facdet, $id_cliente, $id_entrada);
        $stmt->execute();
        echo "Entrada actualizada exitosamente.";
    } elseif ($action == "delete") {
        $id_entrada = $_POST['id_entrada'];

        $stmt = $conn->prepare("DELETE FROM Entrada WHERE ID_Entrada = ?");
        $stmt->bind_param("i", $id_entrada);
        $stmt->execute();
        echo "Entrada eliminada exitosamente.";
    }
}

$result = $conn->query("SELECT * FROM Entrada");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD de Entrada</title>
</head>
<body>
    <h1>CRUD de Entrada</h1>

    <form method="POST">
        <input type="hidden" name="action" value="add">
        <input type="number" step="0.01" name="precio" placeholder="Precio" required>
        <input type="date" name="fecha_de_compra" required>
        <input type="number" name="id_facdet" placeholder="ID Factura Detalles" required>
        <input type="number" name="id_cliente" placeholder="ID Cliente" required>
        <button type="submit">Añadir Entrada</button>
    </form>

    <h2>Lista de Entradas</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Precio</th>
            <th>Fecha de Compra</th>
            <th>ID Factura Detalles</th>
            <th>ID Cliente</th>
            <th>Acciones</th>
        </tr>
        <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <td><?= $row['ID_Entrada'] ?></td>
                <td><?= $row['Precio'] ?></td>
                <td><?= $row['FechaDeCompra'] ?></td>
                <td><?= $row['ID_FacDet'] ?></td>
                <td><?= $row['ID_Cliente'] ?></td>
                <td>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id_entrada" value="<?= $row['ID_Entrada'] ?>">
                        <button type="submit">Eliminar</button>
                    </form>
                    <button onclick="editEntrada(<?= $row['ID_Entrada'] ?>, <?= $row['Precio'] ?>, '<?= $row['FechaDeCompra'] ?>', <?= $row['ID_FacDet'] ?>, <?= $row['ID_Cliente'] ?>)">Editar</button>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <script>
        function editEntrada(id, precio, fecha, facdet, cliente) {
            const form = document.createElement('form');
            form.method = 'POST';

            form.innerHTML = `
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id_entrada" value="${id}">
                <input type="number" step="0.01" name="precio" value="${precio}" required>
                <input type="date" name="fecha_de_compra" value="${fecha}" required>
                <input type="number" name="id_facdet" value="${facdet}" required>
                <input type="number" name="id_cliente" value="${cliente}" required>
                <button type="submit">Actualizar</button>
            `;

            document.body.appendChild(form);
            form.submit();
        }
    </script>
</body>
</html>
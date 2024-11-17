<?php
require_once 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];

    if ($action == "add") {
        $numero_fila = $_POST['numero_fila'];
        $numero_asiento = $_POST['numero_asiento'];
        $estado = $_POST['estado'];
        $id_sala = $_POST['id_sala'];

        $stmt = $conn->prepare("INSERT INTO Butacas (NumeroDeFila, NumeroDeAsiento, Estado, ID_Sala) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iisi", $numero_fila, $numero_asiento, $estado, $id_sala);
        $stmt->execute();
        echo "Butaca añadida exitosamente.";
    } elseif ($action == "edit") {
        $id_butaca = $_POST['id_butaca'];
        $numero_fila = $_POST['numero_fila'];
        $numero_asiento = $_POST['numero_asiento'];
        $estado = $_POST['estado'];
        $id_sala = $_POST['id_sala'];

        $stmt = $conn->prepare("UPDATE Butacas SET NumeroDeFila = ?, NumeroDeAsiento = ?, Estado = ?, ID_Sala = ? WHERE ID_Butaca = ?");
        $stmt->bind_param("iisii", $numero_fila, $numero_asiento, $estado, $id_sala, $id_butaca);
        $stmt->execute();
        echo "Butaca actualizada exitosamente.";
    } elseif ($action == "delete") {
        $id_butaca = $_POST['id_butaca'];

        $stmt = $conn->prepare("DELETE FROM Butacas WHERE ID_Butaca = ?");
        $stmt->bind_param("i", $id_butaca);
        $stmt->execute();
        echo "Butaca eliminada exitosamente.";
    }
}

$result = $conn->query("SELECT * FROM Butacas");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD de Butacas</title>
</head>
<body>
    <h1>CRUD de Butacas</h1>

    <form method="POST">
        <input type="hidden" name="action" value="add">
        <input type="number" name="numero_fila" placeholder="Número de Fila" required>
        <input type="number" name="numero_asiento" placeholder="Número de Asiento" required>
        <select name="estado" required>
            <option value="Libre">Libre</option>
            <option value="Ocupada">Ocupada</option>
        </select>
        <input type="number" name="id_sala" placeholder="ID Sala" required>
        <button type="submit">Añadir Butaca</button>
    </form>

    <h2>Lista de Butacas</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Número de Fila</th>
            <th>Número de Asiento</th>
            <th>Estado</th>
            <th>ID Sala</th>
            <th>Acciones</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['ID_Butaca'] ?></td>
                <td><?= $row['NumeroDeFila'] ?></td>
                <td><?= $row['NumeroDeAsiento'] ?></td>
                <td><?= $row['Estado'] ?></td>
                <td><?= $row['ID_Sala'] ?></td>
                <td>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id_butaca" value="<?= $row['ID_Butaca'] ?>">
                        <button type="submit">Eliminar</button>
                    </form>
                    <button onclick="editButaca(<?= $row['ID_Butaca'] ?>, <?= $row['NumeroDeFila'] ?>, <?= $row['NumeroDeAsiento'] ?>, '<?= $row['Estado'] ?>', <?= $row['ID_Sala'] ?>)">Editar</button>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <script>
        function editButaca(id, fila, asiento, estado, sala) {
            const form = document.createElement('form');
            form.method = 'POST';

            form.innerHTML = `
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id_butaca" value="${id}">
                <input type="number" name="numero_fila" value="${fila}" required>
                <input type="number" name="numero_asiento" value="${asiento}" required>
                <select name="estado" required>
                    <option value="Libre" ${estado === 'Libre' ? 'selected' : ''}>Libre</option>
                    <option value="Ocupada" ${estado === 'Ocupada' ? 'selected' : ''}>Ocupada</option>
                </select>
                <input type="number" name="id_sala" value="${sala}" required>
                <button type="submit">Actualizar</button>
            `;

            document.body.appendChild(form);
            form.submit();
        }
    </script>
</body>
</html>
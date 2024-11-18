<?php
require_once '../db_connection.php';
require_once '../config.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];

    if ($action == "add") {
        $tipo = $_POST['tipo'];
        $capacidad = $_POST['capacidad'];
        $id_cine = $_POST['id_cine'];

        $stmt = $conn->prepare("INSERT INTO Salas (Tipo, Capacidad, ID_Cine) VALUES (?, ?, ?)");
        $stmt->bind_param("sii", $tipo, $capacidad, $id_cine);
        $stmt->execute();
        echo "Sala añadida exitosamente.";
    } elseif ($action == "edit") {
        $id_sala = $_POST['id_sala'];
        $tipo = $_POST['tipo'];
        $capacidad = $_POST['capacidad'];
        $id_cine = $_POST['id_cine'];

        $stmt = $conn->prepare("UPDATE Salas SET Tipo = ?, Capacidad = ?, ID_Cine = ? WHERE ID_Sala = ?");
        $stmt->bind_param("siii", $tipo, $capacidad, $id_cine, $id_sala);
        $stmt->execute();
        echo "Sala actualizada exitosamente.";
    } elseif ($action == "delete") {
        $id_sala = $_POST['id_sala'];

        $stmt = $conn->prepare("DELETE FROM Salas WHERE ID_Sala = ?");
        $stmt->bind_param("i", $id_sala);
        $stmt->execute();
        echo "Sala eliminada exitosamente.";
    }
}

$result = $conn->query("SELECT * FROM Salas");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD de Salas</title>
</head>
<body>
    <h1>CRUD de Salas</h1>

    <form method="POST">
        <input type="hidden" name="action" value="add">
        <input type="text" name="tipo" placeholder="Tipo de Sala" required>
        <input type="number" name="capacidad" placeholder="Capacidad" required>
        <input type="number" name="id_cine" placeholder="ID del Cine" required>
        <button type="submit">Añadir Sala</button>
    </form>

    <h2>Lista de Salas</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Tipo</th>
            <th>Capacidad</th>
            <th>ID Cine</th>
            <th>Acciones</th>
        </tr>
        <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <td><?= $row['ID_Sala'] ?></td>
                <td><?= $row['Tipo'] ?></td>
                <td><?= $row['Capacidad'] ?></td>
                <td><?= $row['ID_Cine'] ?></td>
                <td>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id_sala" value="<?= $row['ID_Sala'] ?>">
                        <button type="submit">Eliminar</button>
                    </form>
                    <button onclick="editSala(<?= $row['ID_Sala'] ?>, '<?= $row['Tipo'] ?>', <?= $row['Capacidad'] ?>, <?= $row['ID_Cine'] ?>)">Editar</button>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <script>
        function editSala(id, tipo, capacidad, id_cine) {
            const form = document.createElement('form');
            form.method = 'POST';

            form.innerHTML = `
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id_sala" value="${id}">
                <input type="text" name="tipo" value="${tipo}" required>
                <input type="number" name="capacidad" value="${capacidad}" required>
                <input type="number" name="id_cine" value="${id_cine}" required>
                <button type="submit">Actualizar</button>
            `;

            document.body.appendChild(form);
            form.submit();
     

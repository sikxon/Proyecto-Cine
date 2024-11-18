<?php
require_once '../db_connection.php';
require_once '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];

    try {
        if ($action == "add") {
            // Agregar Sala
            $tipo = filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_STRING);
            $capacidad = filter_input(INPUT_POST, 'capacidad', FILTER_VALIDATE_INT);
            $id_cine = filter_input(INPUT_POST, 'id_cine', FILTER_VALIDATE_INT);

            $stmt = $conn->prepare("INSERT INTO Salas (Tipo, Capacidad, ID_Cine) 
                                    VALUES (:tipo, :capacidad, :id_cine)");
            $stmt->execute([
                ':tipo' => $tipo,
                ':capacidad' => $capacidad,
                ':id_cine' => $id_cine
            ]);
            echo "Sala añadida exitosamente.";
        } elseif ($action == "edit") {
            // Editar Sala
            $id_sala = filter_input(INPUT_POST, 'id_sala', FILTER_VALIDATE_INT);
            $tipo = filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_STRING);
            $capacidad = filter_input(INPUT_POST, 'capacidad', FILTER_VALIDATE_INT);
            $id_cine = filter_input(INPUT_POST, 'id_cine', FILTER_VALIDATE_INT);

            $stmt = $conn->prepare("UPDATE Salas 
                                    SET Tipo = :tipo, Capacidad = :capacidad, ID_Cine = :id_cine 
                                    WHERE ID_Sala = :id_sala");
            $stmt->execute([
                ':tipo' => $tipo,
                ':capacidad' => $capacidad,
                ':id_cine' => $id_cine,
                ':id_sala' => $id_sala
            ]);
            echo "Sala actualizada exitosamente.";
        } elseif ($action == "delete") {
            // Eliminar Sala
            $id_sala = filter_input(INPUT_POST, 'id_sala', FILTER_VALIDATE_INT);

            $stmt = $conn->prepare("DELETE FROM Salas WHERE ID_Sala = :id_sala");
            $stmt->execute([':id_sala' => $id_sala]);
            echo "Sala eliminada exitosamente.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Consultar todas las salas
try {
    $stmt = $conn->query("SELECT * FROM Salas");
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error al consultar las salas: " . $e->getMessage();
    exit;
}
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
        <?php foreach ($result as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['ID_Sala']) ?></td>
                <td><?= htmlspecialchars($row['Tipo']) ?></td>
                <td><?= htmlspecialchars($row['Capacidad']) ?></td>
                <td><?= htmlspecialchars($row['ID_Cine']) ?></td>
                <td>
                    <!-- Formulario para eliminar -->
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id_sala" value="<?= htmlspecialchars($row['ID_Sala']) ?>">
                        <button type="submit">Eliminar</button>
                    </form>
                    <!-- Botón para editar -->
                    <button onclick="editSala(<?= htmlspecialchars($row['ID_Sala']) ?>, '<?= htmlspecialchars($row['Tipo']) ?>', 
                        <?= htmlspecialchars($row['Capacidad']) ?>, <?= htmlspecialchars($row['ID_Cine']) ?>)">Editar</button>
                </td>
            </tr>
        <?php endforeach; ?>
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
        }
    </script>
</body>
</html>
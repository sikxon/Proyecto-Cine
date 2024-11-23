<?php
require_once '../db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];

    try {
        if ($action == "add") {
            // Agregar Género
            $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);

            if ($nombre) {
                $stmt = $pdo->prepare("INSERT INTO Genero (Nombre) VALUES (:nombre)");
                $stmt->execute([':nombre' => $nombre]);
                echo "Género añadido exitosamente.";
            } else {
                echo "El nombre del género es inválido.";
            }
        } elseif ($action == "edit") {
            // Editar Género
            $id_genero = filter_input(INPUT_POST, 'id_genero', FILTER_VALIDATE_INT);
            $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);

            if ($id_genero && $nombre) {
                $stmt = $pdo->prepare("UPDATE Genero SET Nombre = :nombre WHERE ID_Genero = :id_genero");
                $stmt->execute([
                    ':nombre' => $nombre,
                    ':id_genero' => $id_genero
                ]);
                echo "Género actualizado exitosamente.";
            } else {
                echo "Datos inválidos.";
            }
        } elseif ($action == "delete") {
            // Eliminar Género
            $id_genero = filter_input(INPUT_POST, 'id_genero', FILTER_VALIDATE_INT);

            if ($id_genero) {
                $stmt = $pdo->prepare("DELETE FROM Genero WHERE ID_Genero = :id_genero");
                $stmt->execute([':id_genero' => $id_genero]);
                echo "Género eliminado exitosamente.";
            } else {
                echo "ID de género inválido.";
            }
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Consultar todos los géneros
try {
    $result = $pdo->query("SELECT * FROM Genero");
} catch (PDOException $e) {
    echo "Error al consultar los géneros: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD de Géneros</title>
</head>
<body>
    <h1>CRUD de Géneros</h1>

    <!-- Formulario para agregar géneros -->
    <form method="POST">
        <input type="hidden" name="action" value="add">
        <input type="text" name="nombre" placeholder="Nombre del Género" required>
        <button type="submit">Añadir Género</button>
    </form>

    <h2>Lista de Géneros</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Acciones</th>
        </tr>
        <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <td><?= htmlspecialchars($row['ID_Genero']) ?></td>
                <td><?= htmlspecialchars($row['Nombre']) ?></td>
                <td>
                    <!-- Botón para eliminar -->
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id_genero" value="<?= htmlspecialchars($row['ID_Genero']) ?>">
                        <button type="submit">Eliminar</button>
                    </form>
                    <!-- Botón para editar -->
                    <button onclick="editGenero(<?= htmlspecialchars($row['ID_Genero']) ?>, '<?= htmlspecialchars($row['Nombre']) ?>')">Editar</button>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <script>
        function editGenero(id, nombre) {
            const form = document.createElement('form');
            form.method = 'POST';

            form.innerHTML = `
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id_genero" value="${id}">
                <input type="text" name="nombre" value="${nombre}" required>
                <button type="submit">Actualizar</button>
            `;

            document.body.appendChild(form);
            form.submit();
        }
    </script>
</body>
</html>
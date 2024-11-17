<?php
require_once 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];

    if ($action == "add") {
        $nombre = $_POST['nombre'];

        $stmt = $conn->prepare("INSERT INTO Genero (Nombre) VALUES (?)");
        $stmt->bind_param("s", $nombre);
        $stmt->execute();
        echo "Género añadido exitosamente.";
    } elseif ($action == "edit") {
        $id_genero = $_POST['id_genero'];
        $nombre = $_POST['nombre'];

        $stmt = $conn->prepare("UPDATE Genero SET Nombre = ? WHERE ID_Genero = ?");
        $stmt->bind_param("si", $nombre, $id_genero);
        $stmt->execute();
        echo "Género actualizado exitosamente.";
    } elseif ($action == "delete") {
        $id_genero = $_POST['id_genero'];

        $stmt = $conn->prepare("DELETE FROM Genero WHERE ID_Genero = ?");
        $stmt->bind_param("i", $id_genero);
        $stmt->execute();
        echo "Género eliminado exitosamente.";
    }
}

$result = $conn->query("SELECT * FROM Genero");
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
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['ID_Genero'] ?></td>
                <td><?= $row['Nombre'] ?></td>
                <td>
                    <!-- Botón para eliminar -->
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id_genero" value="<?= $row['ID_Genero'] ?>">
                        <button type="submit">Eliminar</button>
                    </form>
                    <!-- Botón para editar -->
                    <button onclick="editGenero(<?= $row['ID_Genero'] ?>, '<?= $row['Nombre'] ?>')">Editar</button>
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

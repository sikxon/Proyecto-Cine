<?php
require_once '../db_connection.php';
require_once '../config.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];

    if ($action == "add") {
        $inicio = $_POST['inicio'];
        $fin = $_POST['fin'];
        $id_pelicula = $_POST['id_pelicula'];
        $id_salas = $_POST['id_salas'];

        $stmt = $conn->prepare("INSERT INTO Funcion (Inicio, Fin, ID_Pelicula, ID_Salas) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssii", $inicio, $fin, $id_pelicula, $id_salas);
        $stmt->execute();
        echo "Función añadida exitosamente.";
    } elseif ($action == "edit") {
        $id_programacion = $_POST['id_programacion'];
        $inicio = $_POST['inicio'];
        $fin = $_POST['fin'];
        $id_pelicula = $_POST['id_pelicula'];
        $id_salas = $_POST['id_salas'];

        $stmt = $conn->prepare("UPDATE Funcion SET Inicio = ?, Fin = ?, ID_Pelicula = ?, ID_Salas = ? WHERE ID_Programacion = ?");
        $stmt->bind_param("ssiii", $inicio, $fin, $id_pelicula, $id_salas, $id_programacion);
        $stmt->execute();
        echo "Función actualizada exitosamente.";
    } elseif ($action == "delete") {
        $id_programacion = $_POST['id_programacion'];

        $stmt = $conn->prepare("DELETE FROM Funcion WHERE ID_Programacion = ?");
        $stmt->bind_param("i", $id_programacion);
        $stmt->execute();
        echo "Función eliminada exitosamente.";
    }
}

$result = $conn->query("SELECT * FROM Funcion");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD de Funciones</title>
</head>
<body>
    <h1>CRUD de Funciones</h1>

    <form method="POST">
        <input type="hidden" name="action" value="add">
        <input type="datetime-local" name="inicio" placeholder="Inicio de Función" required>
        <input type="datetime-local" name="fin" placeholder="Fin de Función" required>
        <input type="number" name="id_pelicula" placeholder="ID de Película" required>
        <input type="number" name="id_salas" placeholder="ID de Sala" required>
        <button type="submit">Añadir Función</button>
    </form>

    <h2>Lista de Funciones</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Inicio</th>
            <th>Fin</th>
            <th>ID Película</th>
            <th>ID Sala</th>
            <th>Acciones</th>
        </tr>
        <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <td><?= $row['ID_Programacion'] ?></td>
                <td><?= $row['Inicio'] ?></td>
                <td><?= $row['Fin'] ?></td>
                <td><?= $row['ID_Pelicula'] ?></td>
                <td><?= $row['ID_Salas'] ?></td>
                <td>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id_programacion" value="<?= $row['ID_Programacion'] ?>">
                        <button type="submit">Eliminar</button>
                    </form>
                    <button onclick="editFuncion(<?= $row['ID_Programacion'] ?>, '<?= $row['Inicio'] ?>', '<?= $row['Fin'] ?>', <?= $row['ID_Pelicula'] ?>, <?= $row['ID_Salas'] ?>)">Editar</button>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <script>
        function editFuncion(id, inicio, fin, id_pelicula, id_salas) {
            const form = document.createElement('form');
            form.method = 'POST';

            form.innerHTML = `
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id_programacion" value="${id}">
                <input type="datetime-local" name="inicio" value="${inicio.replace(' ', 'T')}" required>
                <input type="datetime-local" name="fin" value="${fin.replace(' ', 'T')}" required>
                <input type="number" name="id_pelicula" value="${id_pelicula}" required>
                <input type="number" name="id_salas" value="${id_salas}" required>
                <button type="submit">Actualizar</button>
            `;

            document.body.appendChild(form);
            form.submit();
        }
    </script>
</body>
</html>

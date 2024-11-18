<?php
require_once '../db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];

    try {
        if ($action == "add") {
            // Agregar Función
            $inicio = $_POST['inicio'];
            $fin = $_POST['fin'];
            $id_pelicula = filter_input(INPUT_POST, 'id_pelicula', FILTER_VALIDATE_INT);
            $id_salas = filter_input(INPUT_POST, 'id_salas', FILTER_VALIDATE_INT);

            if ($inicio && $fin && $id_pelicula && $id_salas) {
                $stmt = $conn->prepare("INSERT INTO Funcion (Inicio, Fin, ID_Pelicula, ID_Salas) 
                                        VALUES (:inicio, :fin, :id_pelicula, :id_salas)");
                $stmt->execute([
                    ':inicio' => $inicio,
                    ':fin' => $fin,
                    ':id_pelicula' => $id_pelicula,
                    ':id_salas' => $id_salas
                ]);
                echo "Función añadida exitosamente.";
            } else {
                echo "Datos inválidos.";
            }
        } elseif ($action == "edit") {
            // Editar Función
            $id_programacion = filter_input(INPUT_POST, 'id_programacion', FILTER_VALIDATE_INT);
            $inicio = $_POST['inicio'];
            $fin = $_POST['fin'];
            $id_pelicula = filter_input(INPUT_POST, 'id_pelicula', FILTER_VALIDATE_INT);
            $id_salas = filter_input(INPUT_POST, 'id_salas', FILTER_VALIDATE_INT);

            if ($id_programacion && $inicio && $fin && $id_pelicula && $id_salas) {
                $stmt = $conn->prepare("UPDATE Funcion 
                                        SET Inicio = :inicio, Fin = :fin, ID_Pelicula = :id_pelicula, ID_Salas = :id_salas 
                                        WHERE ID_Programacion = :id_programacion");
                $stmt->execute([
                    ':inicio' => $inicio,
                    ':fin' => $fin,
                    ':id_pelicula' => $id_pelicula,
                    ':id_salas' => $id_salas,
                    ':id_programacion' => $id_programacion
                ]);
                echo "Función actualizada exitosamente.";
            } else {
                echo "Datos inválidos.";
            }
        } elseif ($action == "delete") {
            // Eliminar Función
            $id_programacion = filter_input(INPUT_POST, 'id_programacion', FILTER_VALIDATE_INT);

            if ($id_programacion) {
                $stmt = $conn->prepare("DELETE FROM Funcion WHERE ID_Programacion = :id_programacion");
                $stmt->execute([':id_programacion' => $id_programacion]);
                echo "Función eliminada exitosamente.";
            } else {
                echo "ID inválido.";
            }
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Consultar todas las funciones
try {
    $result = $conn->query("SELECT * FROM Funcion");
} catch (PDOException $e) {
    echo "Error al consultar las funciones: " . $e->getMessage();
    exit;
}
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

    <!-- Formulario para agregar función -->
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
                <td><?= htmlspecialchars($row['ID_Programacion']) ?></td>
                <td><?= htmlspecialchars($row['Inicio']) ?></td>
                <td><?= htmlspecialchars($row['Fin']) ?></td>
                <td><?= htmlspecialchars($row['ID_Pelicula']) ?></td>
                <td><?= htmlspecialchars($row['ID_Salas']) ?></td>
                <td>
                    <!-- Botón para eliminar -->
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id_programacion" value="<?= htmlspecialchars($row['ID_Programacion']) ?>">
                        <button type="submit">Eliminar</button>
                    </form>
                    <!-- Botón para editar -->
                    <button onclick="editFuncion(
                        <?= htmlspecialchars($row['ID_Programacion']) ?>, 
                        '<?= htmlspecialchars($row['Inicio']) ?>', 
                        '<?= htmlspecialchars($row['Fin']) ?>', 
                        <?= htmlspecialchars($row['ID_Pelicula']) ?>, 
                        <?= htmlspecialchars($row['ID_Salas']) ?>)">Editar</button>
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
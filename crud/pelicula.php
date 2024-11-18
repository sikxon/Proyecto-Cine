<?php
require_once '../db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];

    try {
        if ($action == "add") {
            // Agregar Película
            $titulo = filter_input(INPUT_POST, 'titulo', FILTER_SANITIZE_STRING);
            $anio = filter_input(INPUT_POST, 'anio', FILTER_SANITIZE_STRING);
            $clasificacion = filter_input(INPUT_POST, 'clasificacion', FILTER_SANITIZE_STRING);
            $director = filter_input(INPUT_POST, 'director', FILTER_SANITIZE_STRING);
            $productor = filter_input(INPUT_POST, 'productor', FILTER_SANITIZE_STRING);
            $idioma = filter_input(INPUT_POST, 'idioma', FILTER_SANITIZE_STRING);
            $calificacion = filter_input(INPUT_POST, 'calificacion', FILTER_SANITIZE_STRING);
            $duracion = filter_input(INPUT_POST, 'duracion', FILTER_SANITIZE_STRING);

            $stmt = $conn->prepare("INSERT INTO Pelicula (titulo, Año_de_estreno, Clasificacion, Director, productor, idioma, Calificacion, Duracion) 
                                    VALUES (:titulo, :anio, :clasificacion, :director, :productor, :idioma, :calificacion, :duracion)");
            $stmt->execute([
                ':titulo' => $titulo,
                ':anio' => $anio,
                ':clasificacion' => $clasificacion,
                ':director' => $director,
                ':productor' => $productor,
                ':idioma' => $idioma,
                ':calificacion' => $calificacion,
                ':duracion' => $duracion
            ]);
            echo "Película añadida exitosamente.";
        } elseif ($action == "edit") {
            // Editar Película
            $id_pelicula = filter_input(INPUT_POST, 'id_pelicula', FILTER_VALIDATE_INT);
            $titulo = filter_input(INPUT_POST, 'titulo', FILTER_SANITIZE_STRING);
            $anio = filter_input(INPUT_POST, 'anio', FILTER_SANITIZE_STRING);
            $clasificacion = filter_input(INPUT_POST, 'clasificacion', FILTER_SANITIZE_STRING);
            $director = filter_input(INPUT_POST, 'director', FILTER_SANITIZE_STRING);
            $productor = filter_input(INPUT_POST, 'productor', FILTER_SANITIZE_STRING);
            $idioma = filter_input(INPUT_POST, 'idioma', FILTER_SANITIZE_STRING);
            $calificacion = filter_input(INPUT_POST, 'calificacion', FILTER_SANITIZE_STRING);
            $duracion = filter_input(INPUT_POST, 'duracion', FILTER_SANITIZE_STRING);

            $stmt = $conn->prepare("UPDATE Pelicula SET titulo = :titulo, Año_de_estreno = :anio, Clasificacion = :clasificacion, 
                                    Director = :director, productor = :productor, idioma = :idioma, Calificacion = :calificacion, 
                                    Duracion = :duracion WHERE ID_Pelicula = :id_pelicula");
            $stmt->execute([
                ':titulo' => $titulo,
                ':anio' => $anio,
                ':clasificacion' => $clasificacion,
                ':director' => $director,
                ':productor' => $productor,
                ':idioma' => $idioma,
                ':calificacion' => $calificacion,
                ':duracion' => $duracion,
                ':id_pelicula' => $id_pelicula
            ]);
            echo "Película actualizada exitosamente.";
        } elseif ($action == "delete") {
            // Eliminar Película
            $id_pelicula = filter_input(INPUT_POST, 'id_pelicula', FILTER_VALIDATE_INT);

            $stmt = $conn->prepare("DELETE FROM Pelicula WHERE ID_Pelicula = :id_pelicula");
            $stmt->execute([':id_pelicula' => $id_pelicula]);
            echo "Película eliminada exitosamente.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Consultar todas las películas
try {
    $result = $conn->query("SELECT * FROM Pelicula");
} catch (PDOException $e) {
    echo "Error al consultar las películas: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD de Películas</title>
</head>
<body>
    <h1>CRUD de Películas</h1>

    <!-- Formulario para agregar películas -->
    <form method="POST">
        <input type="hidden" name="action" value="add">
        <input type="text" name="titulo" placeholder="Título" required>
        <input type="date" name="anio" placeholder="Año de Estreno" required>
        <input type="text" name="clasificacion" placeholder="Clasificación">
        <input type="text" name="director" placeholder="Director" required>
        <input type="text" name="productor" placeholder="Productor" required>
        <input type="text" name="idioma" placeholder="Idioma" required>
        <input type="text" name="calificacion" placeholder="Calificación" required>
        <input type="time" name="duracion" placeholder="Duración" required>
        <button type="submit">Añadir Película</button>
    </form>

    <h2>Lista de Películas</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Título</th>
            <th>Año de Estreno</th>
            <th>Clasificación</th>
            <th>Director</th>
            <th>Productor</th>
            <th>Idioma</th>
            <th>Calificación</th>
            <th>Duración</th>
            <th>Acciones</th>
        </tr>
        <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <td><?= htmlspecialchars($row['ID_Pelicula']) ?></td>
                <td><?= htmlspecialchars($row['titulo']) ?></td>
                <td><?= htmlspecialchars($row['Año_de_estreno']) ?></td>
                <td><?= htmlspecialchars($row['Clasificacion']) ?></td>
                <td><?= htmlspecialchars($row['Director']) ?></td>
                <td><?= htmlspecialchars($row['productor']) ?></td>
                <td><?= htmlspecialchars($row['idioma']) ?></td>
                <td><?= htmlspecialchars($row['Calificacion']) ?></td>
                <td><?= htmlspecialchars($row['Duracion']) ?></td>
                <td>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id_pelicula" value="<?= htmlspecialchars($row['ID_Pelicula']) ?>">
                        <button type="submit">Eliminar</button>
                    </form>
                    <button onclick="editPelicula(<?= htmlspecialchars($row['ID_Pelicula']) ?>, '<?= htmlspecialchars($row['titulo']) ?>', 
                        '<?= htmlspecialchars($row['Año_de_estreno']) ?>', '<?= htmlspecialchars($row['Clasificacion']) ?>', 
                        '<?= htmlspecialchars($row['Director']) ?>', '<?= htmlspecialchars($row['productor']) ?>', 
                        '<?= htmlspecialchars($row['idioma']) ?>', '<?= htmlspecialchars($row['Calificacion']) ?>', 
                        '<?= htmlspecialchars($row['Duracion']) ?>')">Editar</button>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <script>
        function editPelicula(id, titulo, anio, clasificacion, director, productor, idioma, calificacion, duracion) {
            const form = document.createElement('form');
            form.method = 'POST';

            form.innerHTML = `
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id_pelicula" value="${id}">
                <input type="text" name="titulo" value="${titulo}" required>
                <input type="date" name="anio" value="${anio}" required>
                <input type="text" name="clasificacion" value="${clasificacion}">
                <input type="text" name="director" value="${director}" required>
                <input type="text" name="productor" value="${productor}" required>
                <input type="text" name="idioma" value="${idioma}" required>
                <input type="text" name="calificacion" value="${calificacion}" required>
                <input type="time" name="duracion" value="${duracion}" required>
                <button type="submit">Actualizar</button>
            `;

            document.body.appendChild(form);
            form.submit();
        }
    </script>
</body>
</html>
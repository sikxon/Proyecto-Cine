<?php
require_once '../db_connection.php';
require_once '../config.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];

    if ($action == "add") {
        $titulo = $_POST['titulo'];
        $anio = $_POST['anio'];
        $clasificacion = $_POST['clasificacion'];
        $director = $_POST['director'];
        $productor = $_POST['productor'];
        $idioma = $_POST['idioma'];
        $calificacion = $_POST['calificacion'];
        $duracion = $_POST['duracion'];

        $stmt = $conn->prepare("INSERT INTO Pelicula (titulo, Año_de_estreno, Clasificacion, Director, productor, idioma, Calificacion, Duracion) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $titulo, $anio, $clasificacion, $director, $productor, $idioma, $calificacion, $duracion);
        $stmt->execute();
        echo "Película añadida exitosamente.";
    } elseif ($action == "edit") {
        $id_pelicula = $_POST['id_pelicula'];
        $titulo = $_POST['titulo'];
        $anio = $_POST['anio'];
        $clasificacion = $_POST['clasificacion'];
        $director = $_POST['director'];
        $productor = $_POST['productor'];
        $idioma = $_POST['idioma'];
        $calificacion = $_POST['calificacion'];
        $duracion = $_POST['duracion'];

        $stmt = $conn->prepare("UPDATE Pelicula SET titulo = ?, Año_de_estreno = ?, Clasificacion = ?, Director = ?, productor = ?, idioma = ?, Calificacion = ?, Duracion = ? WHERE ID_Pelicula = ?");
        $stmt->bind_param("ssssssssi", $titulo, $anio, $clasificacion, $director, $productor, $idioma, $calificacion, $duracion, $id_pelicula);
        $stmt->execute();
        echo "Película actualizada exitosamente.";
    } elseif ($action == "delete") {
        $id_pelicula = $_POST['id_pelicula'];

        $stmt = $conn->prepare("DELETE FROM Pelicula WHERE ID_Pelicula = ?");
        $stmt->bind_param("i", $id_pelicula);
        $stmt->execute();
        echo "Película eliminada exitosamente.";
    }
}

$result = $conn->query("SELECT * FROM Pelicula");
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
                <td><?= $row['ID_Pelicula'] ?></td>
                <td><?= $row['titulo'] ?></td>
                <td><?= $row['Año_de_estreno'] ?></td>
                <td><?= $row['Clasificacion'] ?></td>
                <td><?= $row['Director'] ?></td>
                <td><?= $row['productor'] ?></td>
                <td><?= $row['idioma'] ?></td>
                <td><?= $row['Calificacion'] ?></td>
                <td><?= $row['Duracion'] ?></td>
                <td>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id_pelicula" value="<?= $row['ID_Pelicula'] ?>">
                        <button type="submit">Eliminar</button>
                    </form>
                    <button onclick="editPelicula(<?= $row['ID_Pelicula'] ?>, '<?= $row['titulo'] ?>', '<?= $row['Año_de_estreno'] ?>', '<?= $row['Clasificacion'] ?>', '<?= $row['Director'] ?>', '<?= $row['productor'] ?>', '<?= $row['idioma'] ?>', '<?= $row['Calificacion'] ?>', '<?= $row['Duracion'] ?>')">Editar</button>
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
                <input type="date" name="anio" value="${ani

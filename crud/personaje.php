<?php
require_once '../db_connection.php';
require_once '../config.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];

    if ($action == "add") {
        $rol = $_POST['rol'];
        $nombre = $_POST['nombre'];
        $dni_actor = $_POST['dni_actor'];
        $id_pelicula = $_POST['id_pelicula'];

        $stmt = $conn->prepare("INSERT INTO Personaje (rol, nombre, DNI_Actor, ID_Pelicula) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssii", $rol, $nombre, $dni_actor, $id_pelicula);
        $stmt->execute();
        echo "Personaje añadido exitosamente.";
    } elseif ($action == "edit") {
        $id_personaje = $_POST['id_personaje'];
        $rol = $_POST['rol'];
        $nombre = $_POST['nombre'];
        $dni_actor = $_POST['dni_actor'];
        $id_pelicula = $_POST['id_pelicula'];

        $stmt = $conn->prepare("UPDATE Personaje SET rol = ?, nombre = ?, DNI_Actor = ?, ID_Pelicula = ? WHERE ID_Personaje = ?");
        $stmt->bind_param("ssiii", $rol, $nombre, $dni_actor, $id_pelicula, $id_personaje);
        $stmt->execute();
        echo "Personaje actualizado exitosamente.";
    } elseif ($action == "delete") {
        $id_personaje = $_POST['id_personaje'];

        $stmt = $conn->prepare("DELETE FROM Personaje WHERE ID_Personaje = ?");
        $stmt->bind_param("i", $id_personaje);
        $stmt->execute();
        echo "Personaje eliminado exitosamente.";
    }
}

$result = $conn->query("SELECT Personaje.ID_Personaje, Personaje.rol, Personaje.nombre, Actor.nombre AS Nombre_Actor, Pelicula.titulo AS Titulo_Pelicula 
FROM Personaje 
JOIN Actor ON Personaje.DNI_Actor = Actor.DNI_Actor 
JOIN Pelicula ON Personaje.ID_Pelicula = Pelicula.ID_Pelicula");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD de Personajes</title>
</head>
<body>
    <h1>CRUD de Personajes</h1>

    <!-- Formulario para agregar personajes -->
    <form method="POST">
        <input type="hidden" name="action" value="add">
        <input type="text" name="rol" placeholder="Rol del Personaje" required>
        <input type="text" name="nombre" placeholder="Nombre del Personaje" required>
        <select name="dni_actor" required>
            <option value="" disabled selected>Selecciona un Actor</option>
            <?php
            $actores = $conn->query("SELECT DNI_Actor, nombre FROM Actor");
            while ($actor = $actores->fetch_assoc()) {
                echo "<option value='{$actor['DNI_Actor']}'>{$actor['nombre']}</option>";
            }
            ?>
        </select>
        <select name="id_pelicula" required>
            <option value="" disabled selected>Selecciona una Película</option>
            <?php
            $peliculas = $conn->query("SELECT ID_Pelicula, titulo FROM Pelicula");
            while ($pelicula = $peliculas->fetch_assoc()) {
                echo "<option value='{$pelicula['ID_Pelicula']}'>{$pelicula['titulo']}</option>";
            }
            ?>
        </select>
        <button type="submit">Añadir Personaje</button>
    </form>

    <h2>Lista de Personajes</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Rol</th>
            <th>Nombre</th>
            <th>Actor</th>
            <th>Película</th>
            <th>Acciones</th>
        </tr>
        <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <td><?= $row['ID_Personaje'] ?></td>
                <td><?= $row['rol'] ?></td>
                <td><?= $row['nombre'] ?></td>
                <td><?= $row['Nombre_Actor'] ?></td>
                <td><?= $row['Titulo_Pelicula'] ?></td>
                <td>
                    <!-- Botón para eliminar -->
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id_personaje" value="<?= $row['ID_Personaje'] ?>">
                        <button type="submit">Eliminar</button>
                    </form>
                    <!-- Botón para editar -->
                    <button onclick="editPersonaje(<?= $row['ID_Personaje'] ?>, '<?= $row['rol'] ?>', '<?= $row['nombre'] ?>', <?= $row['Nombre_Actor'] ?>, <?= $row['Titulo_Pelicula'] ?>)">Editar</button>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <script>
        function editPersonaje(id, rol, nombre, dni_actor, id_pelicula) {
            const form = document.createElement('form');
            form.method = 'POST';

            form.innerHTML = `
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id_personaje" value="${id}">
                <input type="text" name="rol" value="${rol}" required>
                <input type="text" name="nombre" value="${nombre}" required>
                <input type="number" name="dni_actor" value="${dni_actor}" required>
                <input type="number" name="id_pelicula" value="${id_pelicula}" required>
                <button type="submit">Actualizar</button>
            `;

            document.body.appendChild(form);
            form.submit();
        }
    </script>
</body>
</html>

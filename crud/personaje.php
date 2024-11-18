<?php
require_once '../db_connection.php';
require_once '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];

    try {
        if ($action == "add") {
            // Agregar Personaje
            $rol = filter_input(INPUT_POST, 'rol', FILTER_SANITIZE_STRING);
            $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
            $dni_actor = filter_input(INPUT_POST, 'dni_actor', FILTER_VALIDATE_INT);
            $id_pelicula = filter_input(INPUT_POST, 'id_pelicula', FILTER_VALIDATE_INT);

            $stmt = $conn->prepare("INSERT INTO Personaje (rol, nombre, DNI_Actor, ID_Pelicula) 
                                    VALUES (:rol, :nombre, :dni_actor, :id_pelicula)");
            $stmt->execute([
                ':rol' => $rol,
                ':nombre' => $nombre,
                ':dni_actor' => $dni_actor,
                ':id_pelicula' => $id_pelicula
            ]);
            echo "Personaje añadido exitosamente.";
        } elseif ($action == "edit") {
            // Editar Personaje
            $id_personaje = filter_input(INPUT_POST, 'id_personaje', FILTER_VALIDATE_INT);
            $rol = filter_input(INPUT_POST, 'rol', FILTER_SANITIZE_STRING);
            $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
            $dni_actor = filter_input(INPUT_POST, 'dni_actor', FILTER_VALIDATE_INT);
            $id_pelicula = filter_input(INPUT_POST, 'id_pelicula', FILTER_VALIDATE_INT);

            $stmt = $conn->prepare("UPDATE Personaje 
                                    SET rol = :rol, nombre = :nombre, DNI_Actor = :dni_actor, ID_Pelicula = :id_pelicula 
                                    WHERE ID_Personaje = :id_personaje");
            $stmt->execute([
                ':rol' => $rol,
                ':nombre' => $nombre,
                ':dni_actor' => $dni_actor,
                ':id_pelicula' => $id_pelicula,
                ':id_personaje' => $id_personaje
            ]);
            echo "Personaje actualizado exitosamente.";
        } elseif ($action == "delete") {
            // Eliminar Personaje
            $id_personaje = filter_input(INPUT_POST, 'id_personaje', FILTER_VALIDATE_INT);

            $stmt = $conn->prepare("DELETE FROM Personaje WHERE ID_Personaje = :id_personaje");
            $stmt->execute([':id_personaje' => $id_personaje]);
            echo "Personaje eliminado exitosamente.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Consultar todos los personajes
try {
    $stmt = $conn->query("SELECT Personaje.ID_Personaje, Personaje.rol, Personaje.nombre, 
                          Actor.nombre AS Nombre_Actor, Pelicula.titulo AS Titulo_Pelicula 
                          FROM Personaje 
                          JOIN Actor ON Personaje.DNI_Actor = Actor.DNI_Actor 
                          JOIN Pelicula ON Personaje.ID_Pelicula = Pelicula.ID_Pelicula");
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error al consultar los personajes: " . $e->getMessage();
    exit;
}
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
            // Obtener actores disponibles
            $actores = $conn->query("SELECT DNI_Actor, nombre FROM Actor");
            while ($actor = $actores->fetch(PDO::FETCH_ASSOC)) {
                echo "<option value='{$actor['DNI_Actor']}'>{$actor['nombre']}</option>";
            }
            ?>
        </select>
        <select name="id_pelicula" required>
            <option value="" disabled selected>Selecciona una Película</option>
            <?php
            // Obtener películas disponibles
            $peliculas = $conn->query("SELECT ID_Pelicula, titulo FROM Pelicula");
            while ($pelicula = $peliculas->fetch(PDO::FETCH_ASSOC)) {
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
        <?php foreach ($result as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['ID_Personaje']) ?></td>
                <td><?= htmlspecialchars($row['rol']) ?></td>
                <td><?= htmlspecialchars($row['nombre']) ?></td>
                <td><?= htmlspecialchars($row['Nombre_Actor']) ?></td>
                <td><?= htmlspecialchars($row['Titulo_Pelicula']) ?></td>
                <td>
                    <!-- Botón para eliminar -->
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id_personaje" value="<?= htmlspecialchars($row['ID_Personaje']) ?>">
                        <button type="submit">Eliminar</button>
                    </form>
                    <!-- Botón para editar -->
                    <button onclick="editPersonaje(<?= htmlspecialchars($row['ID_Personaje']) ?>, '<?= htmlspecialchars($row['rol']) ?>', 
                        '<?= htmlspecialchars($row['nombre']) ?>', '<?= htmlspecialchars($row['Nombre_Actor']) ?>', 
                        '<?= htmlspecialchars($row['Titulo_Pelicula']) ?>')">Editar</button>
                </td>
            </tr>
        <?php endforeach; ?>
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
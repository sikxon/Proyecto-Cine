<?php
require_once '../db_connection.php'; // Asegúrate de que este archivo esté configurado correctamente.

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];

    try {
        if ($action == "add") {
            // Insertar actor
            $sexo = filter_input(INPUT_POST, 'sexo', FILTER_SANITIZE_STRING);
            $apellido = filter_input(INPUT_POST, 'apellido', FILTER_SANITIZE_STRING);
            $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
            $edad = filter_input(INPUT_POST, 'edad', FILTER_VALIDATE_INT);
            $pais_de_origen = filter_input(INPUT_POST, 'pais_de_origen', FILTER_SANITIZE_STRING);

            $stmt = $pdo->prepare("INSERT INTO actor (sexo, apellido, nombre, edad, Pais_De_Origen) VALUES (:sexo, :apellido, :nombre, :edad, :pais_de_origen)");
            $stmt->bindValue(':sexo', $sexo);
            $stmt->bindValue(':apellido', $apellido);
            $stmt->bindValue(':nombre', $nombre);
            $stmt->bindValue(':edad', $edad, PDO::PARAM_INT);
            $stmt->bindValue(':pais_de_origen', $pais_de_origen);
            $stmt->execute();
            echo "Actor añadido exitosamente.";
        } elseif ($action == "edit") {
            // Actualizar actor
            $dni_actor = filter_input(INPUT_POST, 'dni_actor', FILTER_VALIDATE_INT);
            $sexo = filter_input(INPUT_POST, 'sexo', FILTER_SANITIZE_STRING);
            $apellido = filter_input(INPUT_POST, 'apellido', FILTER_SANITIZE_STRING);
            $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
            $edad = filter_input(INPUT_POST, 'edad', FILTER_VALIDATE_INT);
            $pais_de_origen = filter_input(INPUT_POST, 'pais_de_origen', FILTER_SANITIZE_STRING);

            $stmt = $pdo->prepare("UPDATE actor SET sexo = :sexo, apellido = :apellido, nombre = :nombre, edad = :edad, Pais_De_Origen = :pais WHERE DNI_Actor = :dni");
            $stmt->bindValue(':sexo', $sexo);
            $stmt->bindValue(':apellido', $apellido);
            $stmt->bindValue(':nombre', $nombre);
            $stmt->bindValue(':edad', $edad, PDO::PARAM_INT);
            $stmt->bindValue(':pais', $pais_de_origen);
            $stmt->bindValue(':dni', $dni_actor, PDO::PARAM_INT);
            $stmt->execute();
            echo "Actor actualizado exitosamente.";
        } elseif ($action == "delete") {
            // Eliminar actor
            $dni_actor = filter_input(INPUT_POST, 'dni_actor', FILTER_VALIDATE_INT);

            $stmt = $pdo->prepare("DELETE FROM actor WHERE DNI_Actor = :dni");
            $stmt->bindValue(':dni', $dni_actor, PDO::PARAM_INT);
            $stmt->execute();
            echo "Actor eliminado exitosamente.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Consultar actores para mostrar en la tabla
$result = $pdo->query("SELECT * FROM actor");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/actor.php">
    <title>CRUD de Actores</title>
</head>
<body>
    <h1>CRUD de Actores</h1>

    <!-- Formulario para agregar actores -->
    <form method="POST">
        <input type="hidden" name="action" value="add">
        <input type="text" name="sexo" placeholder="Sexo" required>
        <input type="text" name="apellido" placeholder="Apellido" required>
        <input type="text" name="nombre" placeholder="Nombre" required>
        <input type="number" name="edad" placeholder="Edad" required>
        <input type="text" name="pais_de_origen" placeholder="País de Origen" required>
        <button type="submit">Añadir Actor</button>
    </form>

    <h2>Lista de Actores</h2>
    <table border="1">
        <tr>
            <th>DNI</th>
            <th>Sexo</th>
            <th>Apellido</th>
            <th>Nombre</th>
            <th>Edad</th>
            <th>País de Origen</th>
            <th>Acciones</th>
        </tr>
        <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <td><?= htmlspecialchars($row['DNI_Actor']) ?></td>
                <td><?= htmlspecialchars($row['sexo']) ?></td>
                <td><?= htmlspecialchars($row['apellido']) ?></td>
                <td><?= htmlspecialchars($row['nombre']) ?></td>
                <td><?= htmlspecialchars($row['edad']) ?></td>
                <td><?= htmlspecialchars($row['Pais_De_Origen']) ?></td>
                <td>
                    <!-- Botón para eliminar -->
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="dni_actor" value="<?= htmlspecialchars($row['DNI_Actor']) ?>">
                        <button type="submit">Eliminar</button>
                    </form>
                    <!-- Botón para editar -->
                    <button onclick="editActor(
                        <?= htmlspecialchars($row['DNI_Actor']) ?>,
                        '<?= htmlspecialchars($row['sexo']) ?>',
                        '<?= htmlspecialchars($row['apellido']) ?>',
                        '<?= htmlspecialchars($row['nombre']) ?>',
                        <?= htmlspecialchars($row['edad']) ?>,
                        '<?= htmlspecialchars($row['Pais_De_Origen']) ?>'
                    )">Editar</button>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <script>
        function editActor(dni, sexo, apellido, nombre, edad, pais) {
            const form = document.createElement('form');
            form.method = 'POST';

            form.innerHTML = `
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="dni_actor" value="${dni}">
                <input type="text" name="sexo" value="${sexo}" required>
                <input type="text" name="apellido" value="${apellido}" required>
                <input type="text" name="nombre" value="${nombre}" required>
                <input type="number" name="edad" value="${edad}" required>
                <input type="text" name="pais_de_origen" value="${pais}" required>
                <button type="submit">Actualizar</button>
            `;

            document.body.appendChild(form);
            form.submit();
        }
    </script>
</body>
</html>

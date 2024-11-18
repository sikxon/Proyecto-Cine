<?php
require_once '../db_connection.php';
require_once '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];

    if ($action == "add") {
        $sexo = $_POST['sexo'];
        $apellido = $_POST['apellido'];
        $nombre = $_POST['nombre'];
        $edad = $_POST['edad'];
        $pais_de_origen = $_POST['pais_de_origen'];

        $stmt = $conn->prepare("INSERT INTO Actor (sexo, apellido, nombre, edad, Pais_De_Origen) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssds", $sexo, $apellido, $nombre, $edad, $pais_de_origen);
        $stmt->execute();
        echo "Actor añadido exitosamente.";
    } elseif ($action == "edit") {
        $dni_actor = $_POST['dni_actor'];
        $sexo = $_POST['sexo'];
        $apellido = $_POST['apellido'];
        $nombre = $_POST['nombre'];
        $edad = $_POST['edad'];
        $pais_de_origen = $_POST['pais_de_origen'];

        $stmt = $conn->prepare("UPDATE Actor SET sexo = ?, apellido = ?, nombre = ?, edad = ?, Pais_De_Origen = ? WHERE DNI_Actor = ?");
        $stmt->bind_param("sssdsi", $sexo, $apellido, $nombre, $edad, $pais_de_origen, $dni_actor);
        $stmt->execute();
        echo "Actor actualizado exitosamente.";
    } elseif ($action == "delete") {
        $dni_actor = $_POST['dni_actor'];

        $stmt = $conn->prepare("DELETE FROM Actor WHERE DNI_Actor = ?");
        $stmt->bind_param("i", $dni_actor);
        $stmt->execute();
        echo "Actor eliminado exitosamente.";
    }
}

$result = $conn->query("SELECT * FROM Actor");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                <td><?= $row['DNI_Actor'] ?></td>
                <td><?= $row['sexo'] ?></td>
                <td><?= $row['apellido'] ?></td>
                <td><?= $row['nombre'] ?></td>
                <td><?= $row['edad'] ?></td>
                <td><?= $row['Pais_De_Origen'] ?></td>
                <td>
                    <!-- Botón para eliminar -->
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="dni_actor" value="<?= $row['DNI_Actor'] ?>">
                        <button type="submit">Eliminar</button>
                    </form>
                    <!-- Botón para editar -->
                    <button onclick="editActor(<?= $row['DNI_Actor'] ?>, '<?= $row['sexo'] ?>', '<?= $row['apellido'] ?>', '<?= $row['nombre'] ?>', <?= $row['edad'] ?>, '<?= $row['Pais_De_Origen'] ?>')">Editar</button>
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

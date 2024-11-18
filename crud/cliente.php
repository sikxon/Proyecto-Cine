<?php
require_once '../db_connection.php';
require_once '../config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST['action'];

    if ($action === "add") {
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $email = $_POST['email'];
        $telefono = $_POST['telefono'];
        $direccion = $_POST['direccion'];
        $fechaDeNacimiento = $_POST['fechaDeNacimiento'];

        $stmt = $conn->prepare("INSERT INTO Cliente (Nombre, Apellido, Email, Telefono, Direccion, FechaDeNacimiento) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $nombre, $apellido, $email, $telefono, $direccion, $fechaDeNacimiento);
        $stmt->execute();
        echo "Cliente añadido exitosamente.";
    } elseif ($action === "edit") {
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $email = $_POST['email'];
        $telefono = $_POST['telefono'];
        $direccion = $_POST['direccion'];
        $fechaDeNacimiento = $_POST['fechaDeNacimiento'];

        $stmt = $conn->prepare("UPDATE Cliente SET Nombre = ?, Apellido = ?, Email = ?, Telefono = ?, Direccion = ?, FechaDeNacimiento = ? WHERE ID_Cliente = ?");
        $stmt->bind_param("ssssssi", $nombre, $apellido, $email, $telefono, $direccion, $fechaDeNacimiento, $id);
        $stmt->execute();
        echo "Cliente actualizado exitosamente.";
    } elseif ($action === "delete") {
        $id = $_POST['id'];

        $stmt = $conn->prepare("DELETE FROM Cliente WHERE ID_Cliente = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        echo "Cliente eliminado exitosamente.";
    }
}

$result = $conn->query("SELECT * FROM Cliente");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD de Clientes</title>
</head>
<body>
    <h1>CRUD de Clientes</h1>

    <!-- Formulario para agregar clientes -->
    <form method="POST">
        <input type="hidden" name="action" value="add">
        <input type="text" name="nombre" placeholder="Nombre" required>
        <input type="text" name="apellido" placeholder="Apellido" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="telefono" placeholder="Teléfono" required>
        <input type="text" name="direccion" placeholder="Dirección" required>
        <input type="date" name="fechaDeNacimiento" required>
        <button type="submit">Añadir Cliente</button>
    </form>

    <h2>Lista de Clientes</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Email</th>
            <th>Teléfono</th>
            <th>Dirección</th>
            <th>Fecha de Nacimiento</th>
            <th>Acciones</th>
        </tr>
        <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <td><?= htmlspecialchars($row['ID_Cliente']) ?></td>
                <td><?= htmlspecialchars($row['Nombre']) ?></td>
                <td><?= htmlspecialchars($row['Apellido']) ?></td>
                <td><?= htmlspecialchars($row['Email']) ?></td>
                <td><?= htmlspecialchars($row['Telefono']) ?></td>
                <td><?= htmlspecialchars($row['Direccion']) ?></td>
                <td><?= htmlspecialchars($row['FechaDeNacimiento']) ?></td>
                <td>
                    <!-- Botón para eliminar -->
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?= $row['ID_Cliente'] ?>">
                        <button type="submit">Eliminar</button>
                    </form>
                    <!-- Botón para editar -->
                    <button onclick="editClient(<?= $row['ID_Cliente'] ?>, '<?= $row['Nombre'] ?>', '<?= $row['Apellido'] ?>', '<?= $row['Email'] ?>', '<?= $row['Telefono'] ?>', '<?= $row['Direccion'] ?>', '<?= $row['FechaDeNacimiento'] ?>')">Editar</button>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <script>
        function editClient(id, nombre, apellido, email, telefono, direccion, fecha) {
            const form = document.createElement('form');
            form.method = 'POST';

            form.innerHTML = `
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id" value="${id}">
                <input type="text" name="nombre" value="${nombre}" required>
                <input type="text" name="apellido" value="${apellido}" required>
                <input type="email" name="email" value="${email}" required>
                <input type="text" name="telefono" value="${telefono}" required>
                <input type="text" name="direccion" value="${direccion}" required>
                <input type="date" name="fechaDeNacimiento" value="${fecha}" required>
                <button type="submit">Actualizar</button>
            `;

            document.body.appendChild(form);
            form.submit();
        }
    </script>
</body>
</html>

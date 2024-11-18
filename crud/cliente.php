<?php
require_once '../db_connection.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST['action'];

    try {
        if ($action === "add") {
            // Agregar cliente
            $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
            $apellido = filter_input(INPUT_POST, 'apellido', FILTER_SANITIZE_STRING);
            $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
            $telefono = filter_input(INPUT_POST, 'telefono', FILTER_SANITIZE_STRING);
            $direccion = filter_input(INPUT_POST, 'direccion', FILTER_SANITIZE_STRING);
            $fechaDeNacimiento = filter_input(INPUT_POST, 'fechaDeNacimiento', FILTER_SANITIZE_STRING);

            if ($nombre && $apellido && $email && $telefono && $direccion && $fechaDeNacimiento) {
                $stmt = $conn->prepare("INSERT INTO Cliente (Nombre, Apellido, Email, Telefono, Direccion, FechaDeNacimiento) 
                                        VALUES (:nombre, :apellido, :email, :telefono, :direccion, :fechaDeNacimiento)");
                $stmt->execute([
                    ':nombre' => $nombre,
                    ':apellido' => $apellido,
                    ':email' => $email,
                    ':telefono' => $telefono,
                    ':direccion' => $direccion,
                    ':fechaDeNacimiento' => $fechaDeNacimiento
                ]);
                echo "Cliente añadido exitosamente.";
            } else {
                echo "Datos inválidos.";
            }
        } elseif ($action === "edit") {
            // Editar cliente
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
            $apellido = filter_input(INPUT_POST, 'apellido', FILTER_SANITIZE_STRING);
            $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
            $telefono = filter_input(INPUT_POST, 'telefono', FILTER_SANITIZE_STRING);
            $direccion = filter_input(INPUT_POST, 'direccion', FILTER_SANITIZE_STRING);
            $fechaDeNacimiento = filter_input(INPUT_POST, 'fechaDeNacimiento', FILTER_SANITIZE_STRING);

            if ($id && $nombre && $apellido && $email && $telefono && $direccion && $fechaDeNacimiento) {
                $stmt = $conn->prepare("UPDATE Cliente 
                                        SET Nombre = :nombre, Apellido = :apellido, Email = :email, Telefono = :telefono, Direccion = :direccion, FechaDeNacimiento = :fechaDeNacimiento 
                                        WHERE ID_Cliente = :id");
                $stmt->execute([
                    ':id' => $id,
                    ':nombre' => $nombre,
                    ':apellido' => $apellido,
                    ':email' => $email,
                    ':telefono' => $telefono,
                    ':direccion' => $direccion,
                    ':fechaDeNacimiento' => $fechaDeNacimiento
                ]);
                echo "Cliente actualizado exitosamente.";
            } else {
                echo "Datos inválidos.";
            }
        } elseif ($action === "delete") {
            // Eliminar cliente
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

            if ($id) {
                $stmt = $conn->prepare("DELETE FROM Cliente WHERE ID_Cliente = :id");
                $stmt->execute([':id' => $id]);
                echo "Cliente eliminado exitosamente.";
            } else {
                echo "ID inválido.";
            }
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Consultar todos los clientes
try {
    $result = $conn->query("SELECT * FROM Cliente");
} catch (PDOException $e) {
    echo "Error al consultar los clientes: " . $e->getMessage();
    exit;
}
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
                        <input type="hidden" name="id" value="<?= htmlspecialchars($row['ID_Cliente']) ?>">
                        <button type="submit">Eliminar</button>
                    </form>
                    <!-- Botón para editar -->
                    <button onclick="editClient(
                        <?= htmlspecialchars($row['ID_Cliente']) ?>, 
                        '<?= htmlspecialchars($row['Nombre']) ?>', 
                        '<?= htmlspecialchars($row['Apellido']) ?>', 
                        '<?= htmlspecialchars($row['Email']) ?>', 
                        '<?= htmlspecialchars($row['Telefono']) ?>', 
                        '<?= htmlspecialchars($row['Direccion']) ?>', 
                        '<?= htmlspecialchars($row['FechaDeNacimiento']) ?>'
                    )">Editar</button>
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
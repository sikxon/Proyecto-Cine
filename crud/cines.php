<?php
require_once '../db_connection.php'; // Asegúrate de que este archivo esté configurado correctamente.

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];

    try {
        if ($action == "add") {
            // Insertar cine
            $nombre_cine = filter_input(INPUT_POST, 'nombre_cine', FILTER_SANITIZE_STRING);
            $direccion = filter_input(INPUT_POST, 'direccion', FILTER_SANITIZE_STRING);
            $telefono = filter_input(INPUT_POST, 'telefono', FILTER_SANITIZE_STRING);
            $id_empleados = filter_input(INPUT_POST, 'id_empleados', FILTER_VALIDATE_INT);

            $stmt = $pdo->prepare("INSERT INTO Cine (nombre_cine, Direccion, telefono, ID_Empleados) VALUES (:nombre_cine, :direccion, :telefono, :id_empleados)");
            $stmt->bindValue(':nombre_cine', $nombre_cine);
            $stmt->bindValue(':direccion', $direccion);
            $stmt->bindValue(':telefono', $telefono);
            $stmt->bindValue(':id_empleados', $id_empleados, PDO::PARAM_INT);
            $stmt->execute();
            echo "Cine añadido exitosamente.";
        } elseif ($action == "edit") {
            // Actualizar cine
            $id_cine = filter_input(INPUT_POST, 'id_cine', FILTER_VALIDATE_INT);
            $nombre_cine = filter_input(INPUT_POST, 'nombre_cine', FILTER_SANITIZE_STRING);
            $direccion = filter_input(INPUT_POST, 'direccion', FILTER_SANITIZE_STRING);
            $telefono = filter_input(INPUT_POST, 'telefono', FILTER_SANITIZE_STRING);
            $id_empleados = filter_input(INPUT_POST, 'id_empleados', FILTER_VALIDATE_INT);

            $stmt = $pdo->prepare("UPDATE Cine SET nombre_cine = :nombre_cine, Direccion = :direccion, telefono = :telefono, ID_Empleados = :id_empleados WHERE ID_Cine = :id_cine");
            $stmt->bindValue(':nombre_cine', $nombre_cine);
            $stmt->bindValue(':direccion', $direccion);
            $stmt->bindValue(':telefono', $telefono);
            $stmt->bindValue(':id_empleados', $id_empleados, PDO::PARAM_INT);
            $stmt->bindValue(':id_cine', $id_cine, PDO::PARAM_INT);
            $stmt->execute();
            echo "Cine actualizado exitosamente.";
        } elseif ($action == "delete") {
            // Eliminar cine
            $id_cine = filter_input(INPUT_POST, 'id_cine', FILTER_VALIDATE_INT);

            $stmt = $pdo->prepare("DELETE FROM Cine WHERE ID_Cine = :id_cine");
            $stmt->bindValue(':id_cine', $id_cine, PDO::PARAM_INT);
            $stmt->execute();
            echo "Cine eliminado exitosamente.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Consultar cines para mostrar en la tabla
$result = $pdo->query("SELECT * FROM Cine");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD de Cine</title>
</head>
<body>
    <h1>CRUD de Cine</h1>

    <!-- Formulario para agregar cines -->
    <form method="POST">
        <input type="hidden" name="action" value="add">
        <input type="text" name="nombre_cine" placeholder="Nombre del Cine" required>
        <input type="text" name="direccion" placeholder="Dirección" required>
        <input type="text" name="telefono" placeholder="Teléfono" required>
        <input type="number" name="id_empleados" placeholder="ID del Empleado" required>
        <button type="submit">Añadir Cine</button>
    </form>

    <h2>Lista de Cines</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Dirección</th>
            <th>Teléfono</th>
            <th>ID Empleado</th>
            <th>Acciones</th>
        </tr>
        <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <td><?= htmlspecialchars($row['ID_Cine']) ?></td>
                <td><?= htmlspecialchars($row['nombre_cine']) ?></td>
                <td><?= htmlspecialchars($row['Direccion']) ?></td>
                <td><?= htmlspecialchars($row['telefono']) ?></td>
                <td><?= htmlspecialchars($row['ID_Empleados']) ?></td>
                <td>
                    <!-- Botón para eliminar -->
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id_cine" value="<?= htmlspecialchars($row['ID_Cine']) ?>">
                        <button type="submit">Eliminar</button>
                    </form>
                    <!-- Botón para editar -->
                    <button onclick="editCine(
                        <?= htmlspecialchars($row['ID_Cine']) ?>,
                        '<?= htmlspecialchars($row['nombre_cine']) ?>',
                        '<?= htmlspecialchars($row['Direccion']) ?>',
                        '<?= htmlspecialchars($row['telefono']) ?>',
                        <?= htmlspecialchars($row['ID_Empleados']) ?>
                    )">Editar</button>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <script>
        function editCine(id, nombre, direccion, telefono, empleado) {
            const form = document.createElement('form');
            form.method = 'POST';

            form.innerHTML = `
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id_cine" value="${id}">
                <input type="text" name="nombre_cine" value="${nombre}" required>
                <input type="text" name="direccion" value="${direccion}" required>
                <input type="text" name="telefono" value="${telefono}" required>
                <input type="number" name="id_empleados" value="${empleado}" required>
                <button type="submit">Actualizar</button>
            `;

            document.body.appendChild(form);
            form.submit();
        }
    </script>
</body>
</html>
<?php
require_once '../db_connection.php';
require_once '../config.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];

    if ($action == "add") {
        $nombre_cine = $_POST['nombre_cine'];
        $direccion = $_POST['direccion'];
        $telefono = $_POST['telefono'];
        $id_empleados = $_POST['id_empleados'];

        $stmt = $conn->prepare("INSERT INTO Cine (nombre_cine, Direccion, telefono, ID_Empleados) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $nombre_cine, $direccion, $telefono, $id_empleados);
        $stmt->execute();
        echo "Cine añadido exitosamente.";
    } elseif ($action == "edit") {
        $id_cine = $_POST['id_cine'];
        $nombre_cine = $_POST['nombre_cine'];
        $direccion = $_POST['direccion'];
        $telefono = $_POST['telefono'];
        $id_empleados = $_POST['id_empleados'];

        $stmt = $conn->prepare("UPDATE Cine SET nombre_cine = ?, Direccion = ?, telefono = ?, ID_Empleados = ? WHERE ID_Cine = ?");
        $stmt->bind_param("sssii", $nombre_cine, $direccion, $telefono, $id_empleados, $id_cine);
        $stmt->execute();
        echo "Cine actualizado exitosamente.";
    } elseif ($action == "delete") {
        $id_cine = $_POST['id_cine'];

        $stmt = $conn->prepare("DELETE FROM Cine WHERE ID_Cine = ?");
        $stmt->bind_param("i", $id_cine);
        $stmt->execute();
        echo "Cine eliminado exitosamente.";
    }
}

$result = $conn->query("SELECT * FROM Cine");
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
                <td><?= $row['ID_Cine'] ?></td>
                <td><?= $row['nombre_cine'] ?></td>
                <td><?= $row['Direccion'] ?></td>
                <td><?= $row['telefono'] ?></td>
                <td><?= $row['ID_Empleados'] ?></td>
                <td>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id_cine" value="<?= $row['ID_Cine'] ?>">
                        <button type="submit">Eliminar</button>
                    </form>
                    <button onclick="editCine(<?= $row['ID_Cine'] ?>, '<?= $row['nombre_cine'] ?>', '<?= $row['Direccion'] ?>', '<?= $row['telefono'] ?>', <?= $row['ID_Empleados'] ?>)">Editar</button>
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

<?php
require_once '../db_connection.php'; // Archivo con la conexión a la base de datos.

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];

    try {
        if ($action == "add") {
            // Insertar nueva butaca
            $numero_fila = filter_input(INPUT_POST, 'numero_fila', FILTER_VALIDATE_INT);
            $numero_asiento = filter_input(INPUT_POST, 'numero_asiento', FILTER_VALIDATE_INT);
            $estado = filter_input(INPUT_POST, 'estado', FILTER_SANITIZE_STRING);
            $id_sala = filter_input(INPUT_POST, 'id_sala', FILTER_VALIDATE_INT);

            $stmt = $conn->prepare("INSERT INTO Butacas (NumeroDeFila, NumeroDeAsiento, Estado, ID_Sala) VALUES (:numero_fila, :numero_asiento, :estado, :id_sala)");
            $stmt->bindValue(':numero_fila', $numero_fila, PDO::PARAM_INT);
            $stmt->bindValue(':numero_asiento', $numero_asiento, PDO::PARAM_INT);
            $stmt->bindValue(':estado', $estado);
            $stmt->bindValue(':id_sala', $id_sala, PDO::PARAM_INT);
            $stmt->execute();
            echo "Butaca añadida exitosamente.";
        } elseif ($action == "edit") {
            // Actualizar una butaca existente
            $id_butaca = filter_input(INPUT_POST, 'id_butaca', FILTER_VALIDATE_INT);
            $numero_fila = filter_input(INPUT_POST, 'numero_fila', FILTER_VALIDATE_INT);
            $numero_asiento = filter_input(INPUT_POST, 'numero_asiento', FILTER_VALIDATE_INT);
            $estado = filter_input(INPUT_POST, 'estado', FILTER_SANITIZE_STRING);
            $id_sala = filter_input(INPUT_POST, 'id_sala', FILTER_VALIDATE_INT);

            $stmt = $conn->prepare("UPDATE Butacas SET NumeroDeFila = :numero_fila, NumeroDeAsiento = :numero_asiento, Estado = :estado, ID_Sala = :id_sala WHERE ID_Butaca = :id_butaca");
            $stmt->bindValue(':numero_fila', $numero_fila, PDO::PARAM_INT);
            $stmt->bindValue(':numero_asiento', $numero_asiento, PDO::PARAM_INT);
            $stmt->bindValue(':estado', $estado);
            $stmt->bindValue(':id_sala', $id_sala, PDO::PARAM_INT);
            $stmt->bindValue(':id_butaca', $id_butaca, PDO::PARAM_INT);
            $stmt->execute();
            echo "Butaca actualizada exitosamente.";
        } elseif ($action == "delete") {
            // Eliminar una butaca
            $id_butaca = filter_input(INPUT_POST, 'id_butaca', FILTER_VALIDATE_INT);

            $stmt = $conn->prepare("DELETE FROM Butacas WHERE ID_Butaca = :id_butaca");
            $stmt->bindValue(':id_butaca', $id_butaca, PDO::PARAM_INT);
            $stmt->execute();
            echo "Butaca eliminada exitosamente.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Consultar todas las butacas para mostrarlas en la tabla
$result = $conn->query("SELECT * FROM Butacas");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD de Butacas</title>
</head>
<body>
    <h1>CRUD de Butacas</h1>

    <!-- Formulario para agregar butacas -->
    <form method="POST">
        <input type="hidden" name="action" value="add">
        <input type="number" name="numero_fila" placeholder="Número de Fila" required>
        <input type="number" name="numero_asiento" placeholder="Número de Asiento" required>
        <select name="estado" required>
            <option value="Libre">Libre</option>
            <option value="Ocupada">Ocupada</option>
        </select>
        <input type="number" name="id_sala" placeholder="ID Sala" required>
        <button type="submit">Añadir Butaca</button>
    </form>

    <h2>Lista de Butacas</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Número de Fila</th>
            <th>Número de Asiento</th>
            <th>Estado</th>
            <th>ID Sala</th>
            <th>Acciones</th>
        </tr>
        <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <td><?= htmlspecialchars($row['ID_Butaca']) ?></td>
                <td><?= htmlspecialchars($row['NumeroDeFila']) ?></td>
                <td><?= htmlspecialchars($row['NumeroDeAsiento']) ?></td>
                <td><?= htmlspecialchars($row['Estado']) ?></td>
                <td><?= htmlspecialchars($row['ID_Sala']) ?></td>
                <td>
                    <!-- Botón para eliminar -->
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id_butaca" value="<?= htmlspecialchars($row['ID_Butaca']) ?>">
                        <button type="submit">Eliminar</button>
                    </form>
                    <!-- Botón para editar -->
                    <button onclick="editButaca(
                        <?= htmlspecialchars($row['ID_Butaca']) ?>,
                        <?= htmlspecialchars($row['NumeroDeFila']) ?>,
                        <?= htmlspecialchars($row['NumeroDeAsiento']) ?>,
                        '<?= htmlspecialchars($row['Estado']) ?>',
                        <?= htmlspecialchars($row['ID_Sala']) ?>
                    )">Editar</button>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <script>
        function editButaca(id, fila, asiento, estado, sala) {
            const form = document.createElement('form');
            form.method = 'POST';

            form.innerHTML = `
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id_butaca" value="${id}">
                <input type="number" name="numero_fila" value="${fila}" required>
                <input type="number" name="numero_asiento" value="${asiento}" required>
                <select name="estado" required>
                    <option value="Libre" ${estado === 'Libre' ? 'selected' : ''}>Libre</option>
                    <option value="Ocupada" ${estado === 'Ocupada' ? 'selected' : ''}>Ocupada</option>
                </select>
                <input type="number" name="id_sala" value="${sala}" required>
                <button type="submit">Actualizar</button>
            `;

            document.body.appendChild(form);
            form.submit();
        }
    </script>
</body>
</html>

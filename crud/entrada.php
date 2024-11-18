<?php
require_once '../db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];

    try {
        if ($action == "add") {
            // Agregar entrada
            $precio = filter_input(INPUT_POST, 'precio', FILTER_VALIDATE_FLOAT);
            $fecha_de_compra = filter_input(INPUT_POST, 'fecha_de_compra', FILTER_SANITIZE_STRING);
            $id_facdet = filter_input(INPUT_POST, 'id_facdet', FILTER_VALIDATE_INT);
            $id_cliente = filter_input(INPUT_POST, 'id_cliente', FILTER_VALIDATE_INT);

            if ($precio && $fecha_de_compra && $id_facdet && $id_cliente) {
                $stmt = $conn->prepare("INSERT INTO Entrada (Precio, FechaDeCompra, ID_FacDet, ID_Cliente) 
                                        VALUES (:precio, :fecha_de_compra, :id_facdet, :id_cliente)");
                $stmt->execute([
                    ':precio' => $precio,
                    ':fecha_de_compra' => $fecha_de_compra,
                    ':id_facdet' => $id_facdet,
                    ':id_cliente' => $id_cliente
                ]);
                echo "Entrada añadida exitosamente.";
            } else {
                echo "Datos inválidos.";
            }
        } elseif ($action == "edit") {
            // Editar entrada
            $id_entrada = filter_input(INPUT_POST, 'id_entrada', FILTER_VALIDATE_INT);
            $precio = filter_input(INPUT_POST, 'precio', FILTER_VALIDATE_FLOAT);
            $fecha_de_compra = filter_input(INPUT_POST, 'fecha_de_compra', FILTER_SANITIZE_STRING);
            $id_facdet = filter_input(INPUT_POST, 'id_facdet', FILTER_VALIDATE_INT);
            $id_cliente = filter_input(INPUT_POST, 'id_cliente', FILTER_VALIDATE_INT);

            if ($id_entrada && $precio && $fecha_de_compra && $id_facdet && $id_cliente) {
                $stmt = $conn->prepare("UPDATE Entrada 
                                        SET Precio = :precio, FechaDeCompra = :fecha_de_compra, ID_FacDet = :id_facdet, ID_Cliente = :id_cliente 
                                        WHERE ID_Entrada = :id_entrada");
                $stmt->execute([
                    ':precio' => $precio,
                    ':fecha_de_compra' => $fecha_de_compra,
                    ':id_facdet' => $id_facdet,
                    ':id_cliente' => $id_cliente,
                    ':id_entrada' => $id_entrada
                ]);
                echo "Entrada actualizada exitosamente.";
            } else {
                echo "Datos inválidos.";
            }
        } elseif ($action == "delete") {
            // Eliminar entrada
            $id_entrada = filter_input(INPUT_POST, 'id_entrada', FILTER_VALIDATE_INT);

            if ($id_entrada) {
                $stmt = $conn->prepare("DELETE FROM Entrada WHERE ID_Entrada = :id_entrada");
                $stmt->execute([':id_entrada' => $id_entrada]);
                echo "Entrada eliminada exitosamente.";
            } else {
                echo "ID inválido.";
            }
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Consultar todas las entradas
try {
    $result = $conn->query("SELECT * FROM Entrada");
} catch (PDOException $e) {
    echo "Error al consultar las entradas: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD de Entrada</title>
</head>
<body>
    <h1>CRUD de Entrada</h1>

    <!-- Formulario para agregar entrada -->
    <form method="POST">
        <input type="hidden" name="action" value="add">
        <input type="number" step="0.01" name="precio" placeholder="Precio" required>
        <input type="date" name="fecha_de_compra" required>
        <input type="number" name="id_facdet" placeholder="ID Factura Detalles" required>
        <input type="number" name="id_cliente" placeholder="ID Cliente" required>
        <button type="submit">Añadir Entrada</button>
    </form>

    <h2>Lista de Entradas</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Precio</th>
            <th>Fecha de Compra</th>
            <th>ID Factura Detalles</th>
            <th>ID Cliente</th>
            <th>Acciones</th>
        </tr>
        <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <td><?= htmlspecialchars($row['ID_Entrada']) ?></td>
                <td><?= htmlspecialchars($row['Precio']) ?></td>
                <td><?= htmlspecialchars($row['FechaDeCompra']) ?></td>
                <td><?= htmlspecialchars($row['ID_FacDet']) ?></td>
                <td><?= htmlspecialchars($row['ID_Cliente']) ?></td>
                <td>
                    <!-- Botón para eliminar -->
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id_entrada" value="<?= htmlspecialchars($row['ID_Entrada']) ?>">
                        <button type="submit">Eliminar</button>
                    </form>
                    <!-- Botón para editar -->
                    <button onclick="editEntrada(
                        <?= htmlspecialchars($row['ID_Entrada']) ?>, 
                        <?= htmlspecialchars($row['Precio']) ?>, 
                        '<?= htmlspecialchars($row['FechaDeCompra']) ?>', 
                        <?= htmlspecialchars($row['ID_FacDet']) ?>, 
                        <?= htmlspecialchars($row['ID_Cliente']) ?>)">Editar</button>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <script>
        function editEntrada(id, precio, fecha, facdet, cliente) {
            const form = document.createElement('form');
            form.method = 'POST';

            form.innerHTML = `
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id_entrada" value="${id}">
                <input type="number" step="0.01" name="precio" value="${precio}" required>
                <input type="date" name="fecha_de_compra" value="${fecha}" required>
                <input type="number" name="id_facdet" value="${facdet}" required>
                <input type="number" name="id_cliente" value="${cliente}" required>
                <button type="submit">Actualizar</button>
            `;

            document.body.appendChild(form);
            form.submit();
        }
    </script>
</body>
</html>
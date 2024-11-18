<?php
require_once '../db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];

    try {
        if ($action == "add") {
            // Agregar Factura Detalles
            $cantidad_entradas = filter_input(INPUT_POST, 'cantidad_entradas', FILTER_VALIDATE_INT);
            $precio_por_entrada = filter_input(INPUT_POST, 'precio_por_entrada', FILTER_VALIDATE_FLOAT);
            $subtotal = filter_input(INPUT_POST, 'subtotal', FILTER_VALIDATE_FLOAT);

            if ($cantidad_entradas && $precio_por_entrada && $subtotal) {
                $stmt = $conn->prepare("INSERT INTO Factura_Detalles (CantidadDeEntradas, PrecioPorEntrada, Subtotal) 
                                        VALUES (:cantidad_entradas, :precio_por_entrada, :subtotal)");
                $stmt->execute([
                    ':cantidad_entradas' => $cantidad_entradas,
                    ':precio_por_entrada' => $precio_por_entrada,
                    ':subtotal' => $subtotal
                ]);
                echo "Factura Detalles añadida exitosamente.";
            } else {
                echo "Datos inválidos.";
            }
        } elseif ($action == "edit") {
            // Editar Factura Detalles
            $id_facdet = filter_input(INPUT_POST, 'id_facdet', FILTER_VALIDATE_INT);
            $cantidad_entradas = filter_input(INPUT_POST, 'cantidad_entradas', FILTER_VALIDATE_INT);
            $precio_por_entrada = filter_input(INPUT_POST, 'precio_por_entrada', FILTER_VALIDATE_FLOAT);
            $subtotal = filter_input(INPUT_POST, 'subtotal', FILTER_VALIDATE_FLOAT);

            if ($id_facdet && $cantidad_entradas && $precio_por_entrada && $subtotal) {
                $stmt = $conn->prepare("UPDATE Factura_Detalles 
                                        SET CantidadDeEntradas = :cantidad_entradas, PrecioPorEntrada = :precio_por_entrada, Subtotal = :subtotal 
                                        WHERE ID_FacDet = :id_facdet");
                $stmt->execute([
                    ':cantidad_entradas' => $cantidad_entradas,
                    ':precio_por_entrada' => $precio_por_entrada,
                    ':subtotal' => $subtotal,
                    ':id_facdet' => $id_facdet
                ]);
                echo "Factura Detalles actualizada exitosamente.";
            } else {
                echo "Datos inválidos.";
            }
        } elseif ($action == "delete") {
            // Eliminar Factura Detalles
            $id_facdet = filter_input(INPUT_POST, 'id_facdet', FILTER_VALIDATE_INT);

            if ($id_facdet) {
                $stmt = $conn->prepare("DELETE FROM Factura_Detalles WHERE ID_FacDet = :id_facdet");
                $stmt->execute([':id_facdet' => $id_facdet]);
                echo "Factura Detalles eliminada exitosamente.";
            } else {
                echo "ID inválido.";
            }
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Consultar todas las facturas detalles
try {
    $result = $conn->query("SELECT * FROM Factura_Detalles");
} catch (PDOException $e) {
    echo "Error al consultar las facturas detalles: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD de Factura Detalles</title>
</head>
<body>
    <h1>CRUD de Factura Detalles</h1>

    <!-- Formulario para agregar Factura Detalles -->
    <form method="POST">
        <input type="hidden" name="action" value="add">
        <input type="number" name="cantidad_entradas" placeholder="Cantidad de Entradas" required>
        <input type="number" step="0.01" name="precio_por_entrada" placeholder="Precio por Entrada" required>
        <input type="number" step="0.01" name="subtotal" placeholder="Subtotal" required>
        <button type="submit">Añadir Factura Detalles</button>
    </form>

    <h2>Lista de Factura Detalles</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Cantidad de Entradas</th>
            <th>Precio por Entrada</th>
            <th>Subtotal</th>
            <th>Acciones</th>
        </tr>
        <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <td><?= htmlspecialchars($row['ID_FacDet']) ?></td>
                <td><?= htmlspecialchars($row['CantidadDeEntradas']) ?></td>
                <td><?= htmlspecialchars($row['PrecioPorEntrada']) ?></td>
                <td><?= htmlspecialchars($row['Subtotal']) ?></td>
                <td>
                    <!-- Botón para eliminar -->
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id_facdet" value="<?= htmlspecialchars($row['ID_FacDet']) ?>">
                        <button type="submit">Eliminar</button>
                    </form>
                    <!-- Botón para editar -->
                    <button onclick="editFacturaDetalles(
                        <?= htmlspecialchars($row['ID_FacDet']) ?>, 
                        <?= htmlspecialchars($row['CantidadDeEntradas']) ?>, 
                        <?= htmlspecialchars($row['PrecioPorEntrada']) ?>, 
                        <?= htmlspecialchars($row['Subtotal']) ?>)">Editar</button>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <script>
        function editFacturaDetalles(id, cantidad, precio, subtotal) {
            const form = document.createElement('form');
            form.method = 'POST';

            form.innerHTML = `
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id_facdet" value="${id}">
                <input type="number" name="cantidad_entradas" value="${cantidad}" required>
                <input type="number" step="0.01" name="precio_por_entrada" value="${precio}" required>
                <input type="number" step="0.01" name="subtotal" value="${subtotal}" required>
                <button type="submit">Actualizar</button>
            `;

            document.body.appendChild(form);
            form.submit();
        }
    </script>
</body>
</html>
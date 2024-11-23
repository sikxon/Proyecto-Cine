<?php
require_once '../db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];

    try {
        if ($action == "add") {
            // Agregar Factura Cabecera
            $fecha_emision = filter_input(INPUT_POST, 'fecha_emision', FILTER_SANITIZE_STRING);
            $total = filter_input(INPUT_POST, 'total', FILTER_SANITIZE_STRING);
            $metodo_pago = filter_input(INPUT_POST, 'metodo_pago', FILTER_SANITIZE_STRING);
            $id_facdet = filter_input(INPUT_POST, 'id_facdet', FILTER_VALIDATE_INT);

            if ($fecha_emision && $total && $metodo_pago && $id_facdet) {
                $stmt = $pdo->prepare("INSERT INTO Factura_Cabecera (FechaDeEmision, Total, MetodoDePago, ID_FacDet) 
                                        VALUES (:fecha_emision, :total, :metodo_pago, :id_facdet)");
                $stmt->execute([
                    ':fecha_emision' => $fecha_emision,
                    ':total' => $total,
                    ':metodo_pago' => $metodo_pago,
                    ':id_facdet' => $id_facdet
                ]);
                echo "Factura Cabecera añadida exitosamente.";
            } else {
                echo "Datos inválidos.";
            }
        } elseif ($action == "edit") {
            // Editar Factura Cabecera
            $id_faccab = filter_input(INPUT_POST, 'id_faccab', FILTER_VALIDATE_INT);
            $fecha_emision = filter_input(INPUT_POST, 'fecha_emision', FILTER_SANITIZE_STRING);
            $total = filter_input(INPUT_POST, 'total', FILTER_SANITIZE_STRING);
            $metodo_pago = filter_input(INPUT_POST, 'metodo_pago', FILTER_SANITIZE_STRING);
            $id_facdet = filter_input(INPUT_POST, 'id_facdet', FILTER_VALIDATE_INT);

            if ($id_faccab && $fecha_emision && $total && $metodo_pago && $id_facdet) {
                $stmt = $pdo->prepare("UPDATE Factura_Cabecera 
                                        SET FechaDeEmision = :fecha_emision, Total = :total, MetodoDePago = :metodo_pago, ID_FacDet = :id_facdet 
                                        WHERE ID_FacCab = :id_faccab");
                $stmt->execute([
                    ':fecha_emision' => $fecha_emision,
                    ':total' => $total,
                    ':metodo_pago' => $metodo_pago,
                    ':id_facdet' => $id_facdet,
                    ':id_faccab' => $id_faccab
                ]);
                echo "Factura Cabecera actualizada exitosamente.";
            } else {
                echo "Datos inválidos.";
            }
        } elseif ($action == "delete") {
            // Eliminar Factura Cabecera
            $id_faccab = filter_input(INPUT_POST, 'id_faccab', FILTER_VALIDATE_INT);

            if ($id_faccab) {
                $stmt = $pdo->prepare("DELETE FROM Factura_Cabecera WHERE ID_FacCab = :id_faccab");
                $stmt->execute([':id_faccab' => $id_faccab]);
                echo "Factura Cabecera eliminada exitosamente.";
            } else {
                echo "ID inválido.";
            }
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Consultar todas las facturas cabecera
try {
    $result = $pdo->query("SELECT * FROM Factura_Cabecera");
} catch (PDOException $e) {
    echo "Error al consultar las facturas cabecera: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD de Factura Cabecera</title>
</head>
<body>
    <h1>CRUD de Factura Cabecera</h1>

    <!-- Formulario para agregar Factura Cabecera -->
    <form method="POST">
        <input type="hidden" name="action" value="add">
        <input type="date" name="fecha_emision" placeholder="Fecha de Emisión" required>
        <input type="text" name="total" placeholder="Total" required>
        <input type="text" name="metodo_pago" placeholder="Método de Pago" required>
        <input type="number" name="id_facdet" placeholder="ID Factura Detalles" required>
        <button type="submit">Añadir Factura Cabecera</button>
    </form>

    <h2>Lista de Factura Cabecera</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Fecha de Emisión</th>
            <th>Total</th>
            <th>Método de Pago</th>
            <th>ID Factura Detalles</th>
            <th>Acciones</th>
        </tr>
        <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <td><?= htmlspecialchars($row['ID_FacCab']) ?></td>
                <td><?= htmlspecialchars($row['FechaDeEmision']) ?></td>
                <td><?= htmlspecialchars($row['Total']) ?></td>
                <td><?= htmlspecialchars($row['MetodoDePago']) ?></td>
                <td><?= htmlspecialchars($row['ID_FacDet']) ?></td>
                <td>
                    <!-- Botón para eliminar -->
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id_faccab" value="<?= htmlspecialchars($row['ID_FacCab']) ?>">
                        <button type="submit">Eliminar</button>
                    </form>
                    <!-- Botón para editar -->
                    <button onclick="editFacturaCabecera(
                        <?= htmlspecialchars($row['ID_FacCab']) ?>, 
                        '<?= htmlspecialchars($row['FechaDeEmision']) ?>', 
                        '<?= htmlspecialchars($row['Total']) ?>', 
                        '<?= htmlspecialchars($row['MetodoDePago']) ?>', 
                        <?= htmlspecialchars($row['ID_FacDet']) ?>)">Editar</button>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <script>
        function editFacturaCabecera(id, fecha, total, metodo, idFacDet) {
            const form = document.createElement('form');
            form.method = 'POST';

            form.innerHTML = `
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id_faccab" value="${id}">
                <input type="date" name="fecha_emision" value="${fecha}" required>
                <input type="text" name="total" value="${total}" required>
                <input type="text" name="metodo_pago" value="${metodo}" required>
                <input type="number" name="id_facdet" value="${idFacDet}" required>
                <button type="submit">Actualizar</button>
            `;

            document.body.appendChild(form);
            form.submit();
        }
    </script>
</body>
</html>
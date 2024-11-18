<?php
require_once '../db_connection.php';
require_once '../config.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];

    if ($action == "add") {
        $fecha_emision = $_POST['fecha_emision'];
        $total = $_POST['total'];
        $metodo_pago = $_POST['metodo_pago'];
        $id_facdet = $_POST['id_facdet'];

        $stmt = $conn->prepare("INSERT INTO Factura_Cabecera (FechaDeEmision, Total, MetodoDePago, ID_FacDet) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $fecha_emision, $total, $metodo_pago, $id_facdet);
        $stmt->execute();
        echo "Factura Cabecera añadida exitosamente.";
    } elseif ($action == "edit") {
        $id_faccab = $_POST['id_faccab'];
        $fecha_emision = $_POST['fecha_emision'];
        $total = $_POST['total'];
        $metodo_pago = $_POST['metodo_pago'];
        $id_facdet = $_POST['id_facdet'];

        $stmt = $conn->prepare("UPDATE Factura_Cabecera SET FechaDeEmision = ?, Total = ?, MetodoDePago = ?, ID_FacDet = ? WHERE ID_FacCab = ?");
        $stmt->bind_param("sssii", $fecha_emision, $total, $metodo_pago, $id_facdet, $id_faccab);
        $stmt->execute();
        echo "Factura Cabecera actualizada exitosamente.";
    } elseif ($action == "delete") {
        $id_faccab = $_POST['id_faccab'];

        $stmt = $conn->prepare("DELETE FROM Factura_Cabecera WHERE ID_FacCab = ?");
        $stmt->bind_param("i", $id_faccab);
        $stmt->execute();
        echo "Factura Cabecera eliminada exitosamente.";
    }
}

$result = $conn->query("SELECT * FROM Factura_Cabecera");
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
                <td><?= $row['ID_FacCab'] ?></td>
                <td><?= $row['FechaDeEmision'] ?></td>
                <td><?= $row['Total'] ?></td>
                <td><?= $row['MetodoDePago'] ?></td>
                <td><?= $row['ID_FacDet'] ?></td>
                <td>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id_faccab" value="<?= $row['ID_FacCab'] ?>">
                        <button type="submit">Eliminar</button>
                    </form>
                    <button onclick="editFacturaCabecera(<?= $row['ID_FacCab'] ?>, '<?= $row['FechaDeEmision'] ?>', '<?= $row['Total'] ?>', '<?= $row['MetodoDePago'] ?>', <?= $row['ID_FacDet'] ?>)">Editar</button>
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

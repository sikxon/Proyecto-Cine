<?php
require_once '../db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];

    try {
        if ($action == "add") {
            // Agregar empleado
            $sexo = filter_input(INPUT_POST, 'sexo', FILTER_SANITIZE_STRING);
            $apellido = filter_input(INPUT_POST, 'apellido', FILTER_SANITIZE_STRING);
            $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
            $edad = filter_input(INPUT_POST, 'edad', FILTER_VALIDATE_INT);
            $pais_de_origen = filter_input(INPUT_POST, 'pais_de_origen', FILTER_SANITIZE_STRING);

            if ($sexo && $apellido && $nombre && $edad && $pais_de_origen) {
                $stmt = $pdo->prepare("INSERT INTO Empleados (sexo, apellido, nombre, edad, Pais_De_Origen) 
                                        VALUES (:sexo, :apellido, :nombre, :edad, :pais_de_origen)");
                $stmt->execute([
                    ':sexo' => $sexo,
                    ':apellido' => $apellido,
                    ':nombre' => $nombre,
                    ':edad' => $edad,
                    ':pais_de_origen' => $pais_de_origen
                ]);
                echo "Empleado añadido exitosamente.";
            } else {
                echo "Datos inválidos.";
            }
        } elseif ($action == "edit") {
            // Editar empleado
            $id_empleados = filter_input(INPUT_POST, 'id_empleados', FILTER_VALIDATE_INT);
            $sexo = filter_input(INPUT_POST, 'sexo', FILTER_SANITIZE_STRING);
            $apellido = filter_input(INPUT_POST, 'apellido', FILTER_SANITIZE_STRING);
            $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
            $edad = filter_input(INPUT_POST, 'edad', FILTER_VALIDATE_INT);
            $pais_de_origen = filter_input(INPUT_POST, 'pais_de_origen', FILTER_SANITIZE_STRING);

            if ($id_empleados && $sexo && $apellido && $nombre && $edad && $pais_de_origen) {
                $stmt = $pdo->prepare("UPDATE Empleados 
                                        SET sexo = :sexo, apellido = :apellido, nombre = :nombre, edad = :edad, Pais_De_Origen = :pais_de_origen 
                                        WHERE ID_Empleados = :id_empleados");
                $stmt->execute([
                    ':sexo' => $sexo,
                    ':apellido' => $apellido,
                    ':nombre' => $nombre,
                    ':edad' => $edad,
                    ':pais_de_origen' => $pais_de_origen,
                    ':id_empleados' => $id_empleados
                ]);
                echo "Empleado actualizado exitosamente.";
            } else {
                echo "Datos inválidos.";
            }
        } elseif ($action == "delete") {
            // Eliminar empleado
            $id_empleados = filter_input(INPUT_POST, 'id_empleados', FILTER_VALIDATE_INT);

            if ($id_empleados) {
                $stmt = $pdo->prepare("DELETE FROM Empleados WHERE ID_Empleados = :id_empleados");
                $stmt->execute([':id_empleados' => $id_empleados]);
                echo "Empleado eliminado exitosamente.";
            } else {
                echo "ID inválido.";
            }
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Consultar todos los empleados
try {
    $result = $pdo->query("SELECT * FROM Empleados");
} catch (PDOException $e) {
    echo "Error al consultar los empleados: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD de Empleados</title>
</head>
<body>
    <h1>CRUD de Empleados</h1>

    <!-- Formulario para agregar empleados -->
    <form method="POST">
        <input type="hidden" name="action" value="add">
        <input type="text" name="sexo" placeholder="Sexo" required>
        <input type="text" name="apellido" placeholder="Apellido" required>
        <input type="text" name="nombre" placeholder="Nombre" required>
        <input type="number" name="edad" placeholder="Edad" required>
        <input type="text" name="pais_de_origen" placeholder="País de Origen" required>
        <button type="submit">Añadir Empleado</button>
    </form>

    <h2>Lista de Empleados</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Sexo</th>
            <th>Apellido</th>
            <th>Nombre</th>
            <th>Edad</th>
            <th>País de Origen</th>
            <th>Acciones</th>
        </tr>
        <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <td><?= htmlspecialchars($row['ID_Empleados']) ?></td>
                <td><?= htmlspecialchars($row['sexo']) ?></td>
                <td><?= htmlspecialchars($row['apellido']) ?></td>
                <td><?= htmlspecialchars($row['nombre']) ?></td>
                <td><?= htmlspecialchars($row['edad']) ?></td>
                <td><?= htmlspecialchars($row['Pais_De_Origen']) ?></td>
                <td>
                    <!-- Botón para eliminar -->
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id_empleados" value="<?= htmlspecialchars($row['ID_Empleados']) ?>">
                        <button type="submit">Eliminar</button>
                    </form>
                    <!-- Botón para editar -->
                    <button onclick="editEmpleado(
                        <?= htmlspecialchars($row['ID_Empleados']) ?>, 
                        '<?= htmlspecialchars($row['sexo']) ?>', 
                        '<?= htmlspecialchars($row['apellido']) ?>', 
                        '<?= htmlspecialchars($row['nombre']) ?>', 
                        <?= htmlspecialchars($row['edad']) ?>, 
                        '<?= htmlspecialchars($row['Pais_De_Origen']) ?>'
                    )">Editar</button>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <script>
        function editEmpleado(id, sexo, apellido, nombre, edad, pais) {
            const form = document.createElement('form');
            form.method = 'POST';

            form.innerHTML = `
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id_empleados" value="${id}">
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

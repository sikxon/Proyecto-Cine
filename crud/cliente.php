<?php
include '../includes/header.php';
include '../includes/db_connection.php';

// Insertar o actualizar datos según el formulario enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) { // Agregar registro
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $email = $_POST['email'];
        $telefono = $_POST['telefono'];
        $direccion = $_POST['direccion'];
        $fechaDeNacimiento = $_POST['fechaDeNacimiento'];

        $query = "INSERT INTO Cliente (Nombre, Apellido, Email, Telefono, Direccion, FechaDeNacimiento) 
                  VALUES (:nombre, :apellido, :email, :telefono, :direccion, :fechaDeNacimiento)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido', $apellido);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':direccion', $direccion);
        $stmt->bindParam(':fechaDeNacimiento', $fechaDeNacimiento);

        $message = $stmt->execute() ? "Cliente agregado con éxito." : "Error al agregar el cliente.";
    } elseif (isset($_POST['edit'])) { // Editar registro
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $email = $_POST['email'];
        $telefono = $_POST['telefono'];
        $direccion = $_POST['direccion'];
        $fechaDeNacimiento = $_POST['fechaDeNacimiento'];

        $query = "UPDATE Cliente 
                  SET Nombre = :nombre, Apellido = :apellido, Email = :email, Telefono = :telefono, 
                      Direccion = :direccion, FechaDeNacimiento = :fechaDeNacimiento 
                  WHERE ID_Cliente = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido', $apellido);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':direccion', $direccion);
        $stmt->bindParam(':fechaDeNacimiento', $fechaDeNacimiento);

        $message = $stmt->execute() ? "Cliente actualizado con éxito." : "Error al actualizar el cliente.";
    }
}

// Eliminar registro
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $query = "DELETE FROM Cliente WHERE ID_Cliente = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id);

    $message = $stmt->execute() ? "Cliente eliminado con éxito." : "Error al eliminar el cliente.";
}

// Obtener todos los registros
$query = "SELECT * FROM Cliente";
$stmt = $conn->query($query);
$clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Gestión de Clientes</h2>

<?php if (isset($message)): ?>
    <p><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<!-- Mostrar registros -->
<table>
    <thead>
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
    </thead>
    <tbody>
        <?php foreach ($clientes as $cliente): ?>
        <tr>
            <td><?= htmlspecialchars($cliente['ID_Cliente']) ?></td>
            <td><?= htmlspecialchars($cliente['Nombre']) ?></td>
            <td><?= htmlspecialchars($cliente['Apellido']) ?></td>
            <td><?= htmlspecialchars($cliente['Email']) ?></td>
            <td><?= htmlspecialchars($cliente['Telefono']) ?></td>
            <td><?= htmlspecialchars($cliente['Direccion']) ?></td>
            <td><?= htmlspecialchars($cliente['FechaDeNacimiento']) ?></td>
            <td>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="id" value="<?= $cliente['ID_Cliente'] ?>">
                    <input type="text" name="nombre" value="<?= $cliente['Nombre'] ?>" required>
                    <input type="text" name="apellido" value="<?= $cliente['Apellido'] ?>" required>
                    <input type="email" name="email" value="<?= $cliente['Email'] ?>" required>
                    <input type="text" name="telefono" value="<?= $cliente['Telefono'] ?>" required>
                    <input type="text" name="direccion" value="<?= $cliente['Direccion'] ?>" required>
                    <input type="date" name="fechaDeNacimiento" value="<?= $cliente['FechaDeNacimiento'] ?>" required>
                    <button type="submit" name="edit">Editar</button>
                </form>
                <a href="?delete=<?= $cliente['ID_Cliente'] ?>" onclick="return confirm('¿Estás seguro de eliminar este cliente?')">Eliminar</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Formulario para agregar un registro -->
<form method="POST">
    <h3>Agregar Cliente</h3>
    <input type="text" name="nombre" placeholder="Nombre" required>
    <input type="text" name="apellido" placeholder="Apellido" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="text" name="telefono" placeholder="Teléfono" required>
    <input type="text" name="direccion" placeholder="Dirección" required>
    <input type="date" name="fechaDeNacimiento" required>
    <button type="submit" name="add">Agregar</button>
</form>

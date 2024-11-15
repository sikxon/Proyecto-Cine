<?php
include '../db_connection.php';

$action = $_POST['action'];
$data = json_decode($_POST['data'], true);

try {
    if ($action === 'add') {
        $stmt = $conn->prepare("INSERT INTO Cliente (DNI_Cliente, nombre, apellido, email, telefono, direccion, fechaDeNacimiento) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$data['DNI_Cliente'], $data['nombre'], $data['apellido'], $data['email'], $data['telefono'], $data['direccion'], $data['fechaDeNacimiento']]);
    } elseif ($action === 'update') {
        $stmt = $conn->prepare("UPDATE Cliente SET nombre = ?, apellido = ?, email = ?, telefono = ?, direccion = ?, fechaDeNacimiento = ? WHERE DNI_Cliente = ?");
        $stmt->execute([$data['nombre'], $data['apellido'], $data['email'], $data['telefono'], $data['direccion'], $data['fechaDeNacimiento'], $data['DNI_Cliente']]);
    } elseif ($action === 'delete') {
        $stmt = $conn->prepare("DELETE FROM Cliente WHERE DNI_Cliente = ?");
        $stmt->execute([$data['DNI_Cliente']]);
    }
    echo json_encode(['status' => 'success']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>

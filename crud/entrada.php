<?php
include '../db_connection.php';

$action = $_POST['action'];
$data = json_decode($_POST['data'], true);

try {
    if ($action === 'add') {
        $stmt = $conn->prepare("INSERT INTO Entrada (ID_Entrada, precio, fechaDeCompra, ID_FacDet, ID_Cliente) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$data['ID_Entrada'], $data['precio'], $data['fechaDeCompra'], $data['ID_FacDet'], $data['ID_Cliente']]);
    } elseif ($action === 'update') {
        $stmt = $conn->prepare("UPDATE Entrada SET precio = ?, fechaDeCompra = ?, ID_FacDet = ?, ID_Cliente = ? WHERE ID_Entrada = ?");
        $stmt->execute([$data['precio'], $data['fechaDeCompra'], $data['ID_FacDet'], $data['ID_Cliente'], $data['ID_Entrada']]);
    } elseif ($action === 'delete') {
        $stmt = $conn->prepare("DELETE FROM Entrada WHERE ID_Entrada = ?");
        $stmt->execute([$data['ID_Entrada']]);
    }
    echo json_encode(['status' => 'success']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>

<?php
include '../db_connection.php';

$action = $_POST['action'];
$data = json_decode($_POST['data'], true);

try {
    if ($action === 'add') {
        $stmt = $conn->prepare("INSERT INTO Factura_Detalles (ID_FacDet, cantidadDeEntradas, precioPorEntrada, subtotal) VALUES (?, ?, ?, ?)");
        $stmt->execute([$data['ID_FacDet'], $data['cantidadDeEntradas'], $data['precioPorEntrada'], $data['subtotal']]);
    } elseif ($action === 'update') {
        $stmt = $conn->prepare("UPDATE Factura_Detalles SET cantidadDeEntradas = ?, precioPorEntrada = ?, subtotal = ? WHERE ID_FacDet = ?");
        $stmt->execute([$data['cantidadDeEntradas'], $data['precioPorEntrada'], $data['subtotal'], $data['ID_FacDet']]);
    } elseif ($action === 'delete') {
        $stmt = $conn->prepare("DELETE FROM Factura_Detalles WHERE ID_FacDet = ?");
        $stmt->execute([$data['ID_FacDet']]);
    }
    echo json_encode(['status' => 'success']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>

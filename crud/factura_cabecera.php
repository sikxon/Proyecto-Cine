<?php
include '../db_connection.php';

$action = $_POST['action'];
$data = json_decode($_POST['data'], true);

try {
    if ($action === 'add') {
        $stmt = $conn->prepare("INSERT INTO Factura_Cabecera (ID_FacCab, fechaDeEmision, total, metodoDePago, ID_FacDet) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$data['ID_FacCab'], $data['fechaDeEmision'], $data['total'], $data['metodoDePago'], $data['ID_FacDet']]);
    } elseif ($action === 'update') {
        $stmt = $conn->prepare("UPDATE Factura_Cabecera SET fechaDeEmision = ?, total = ?, metodoDePago = ?, ID_FacDet = ? WHERE ID_FacCab = ?");
        $stmt->execute([$data['fechaDeEmision'], $data['total'], $data['metodoDePago'], $data['ID_FacDet'], $data['ID_FacCab']]);
    } elseif ($action === 'delete') {
        $stmt = $conn->prepare("DELETE FROM Factura_Cabecera WHERE ID_FacCab = ?");
        $stmt->execute([$data['ID_FacCab']]);
    }
    echo json_encode(['status' => 'success']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>

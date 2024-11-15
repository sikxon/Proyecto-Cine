<?php
include '../db_connection.php';

$action = $_POST['action'];
$data = json_decode($_POST['data'], true);

try {
    if ($action === 'add') {
        $stmt = $conn->prepare("INSERT INTO Butacas (ID_Butaca, numeroDeFila, numeroDeAsiento, estado, ID_Salas) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$data['ID_Butaca'], $data['numeroDeFila'], $data['numeroDeAsiento'], $data['estado'], $data['ID_Salas']]);
    } elseif ($action === 'update') {
        $stmt = $conn->prepare("UPDATE Butacas SET numeroDeFila = ?, numeroDeAsiento = ?, estado = ?, ID_Salas = ? WHERE ID_Butaca = ?");
        $stmt->execute([$data['numeroDeFila'], $data['numeroDeAsiento'], $data['estado'], $data['ID_Salas'], $data['ID_Butaca']]);
    } elseif ($action === 'delete') {
        $stmt = $conn->prepare("DELETE FROM Butacas WHERE ID_Butaca = ?");
        $stmt->execute([$data['ID_Butaca']]);
    }
    echo json_encode(['status' => 'success']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>

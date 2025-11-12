<?php
require_once '../db.php';

$data = json_decode(file_get_contents('php://input'), true);

if ($data) {
    $conn->begin_transaction();
    try {
        foreach ($data as $item) {
            $stmt = $conn->prepare("UPDATE dealers SET display_order = ? WHERE id = ?");
            $stmt->bind_param("ii", $item['order'], $item['id']);
            $stmt->execute();
            $stmt->close();
        }
        $conn->commit();
        echo json_encode(['status' => 'success', 'message' => 'Order updated successfully.']);
    } catch (mysqli_sql_exception $exception) {
        $conn->rollback();
        echo json_encode(['status' => 'error', 'message' => $exception->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'No data received.']);
}

$conn->close();
?>

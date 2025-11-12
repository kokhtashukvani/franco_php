<?php
require_once '../db.php';

// Check if ID is provided
if (!isset($_GET['id'])) {
    header("Location: announcement_list.php");
    exit();
}

$id = $_GET['id'];

$stmt = $conn->prepare("DELETE FROM announcements WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: announcement_list.php");
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>

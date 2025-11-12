<?php
require_once '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $is_show = isset($_POST['is_show']) ? 1 : 0;

    $stmt = $conn->prepare("UPDATE news SET title = ?, description = ?, is_show = ? WHERE id = ?");
    $stmt->bind_param("ssii", $title, $description, $is_show, $id);

    if ($stmt->execute()) {
        header("Location: news_list.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

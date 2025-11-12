<?php
require_once '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $release_date = $_POST['release_date'];
    $is_show = isset($_POST['is_show']) ? 1 : 0;

    $stmt = $conn->prepare("UPDATE announcements SET title = ?, description = ?, release_date = ?, is_show = ? WHERE id = ?");
    $stmt->bind_param("sssii", $title, $description, $release_date, $is_show, $id);

    if ($stmt->execute()) {
        header("Location: announcement_list.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

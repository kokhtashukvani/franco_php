<?php
require_once '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $is_show = isset($_POST['is_show']) ? 1 : 0;

    // Get the highest display_order and add 1
    $sql_order = "SELECT MAX(display_order) AS max_order FROM news";
    $result_order = $conn->query($sql_order);
    $row_order = $result_order->fetch_assoc();
    $display_order = $row_order['max_order'] + 1;

    $stmt = $conn->prepare("INSERT INTO news (title, description, is_show, display_order) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssii", $title, $description, $is_show, $display_order);

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

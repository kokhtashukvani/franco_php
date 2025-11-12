<?php
require_once '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $latin_title = $_POST['latin_title'];
    $is_show = isset($_POST['is_show']) ? 1 : 0;

    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["logo_image"]["name"]);
    move_uploaded_file($_FILES["logo_image"]["tmp_name"], $target_file);

    // Get the highest display_order and add 1
    $sql_order = "SELECT MAX(display_order) AS max_order FROM brands";
    $result_order = $conn->query($sql_order);
    $row_order = $result_order->fetch_assoc();
    $display_order = $row_order['max_order'] + 1;

    $stmt = $conn->prepare("INSERT INTO brands (title, latin_title, logo_image, is_show, display_order) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssis", $title, $latin_title, $target_file, $is_show, $display_order);

    if ($stmt->execute()) {
        header("Location: brand_list.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

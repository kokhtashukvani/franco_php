<?php
require_once '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $latin_title = $_POST['latin_title'];
    $is_show = isset($_POST['is_show']) ? 1 : 0;

    $target_file = "";
    if (isset($_FILES["logo_image"]) && $_FILES["logo_image"]["name"]) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["logo_image"]["name"]);
        move_uploaded_file($_FILES["logo_image"]["tmp_name"], $target_file);
    } else {
        $stmt_get_image = $conn->prepare("SELECT logo_image FROM brands WHERE id = ?");
        $stmt_get_image->bind_param("i", $id);
        $stmt_get_image->execute();
        $result = $stmt_get_image->get_result();
        $row = $result->fetch_assoc();
        $target_file = $row['logo_image'];
        $stmt_get_image->close();
    }

    $stmt = $conn->prepare("UPDATE brands SET title = ?, latin_title = ?, logo_image = ?, is_show = ? WHERE id = ?");
    $stmt->bind_param("sssii", $title, $latin_title, $target_file, $is_show, $id);

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

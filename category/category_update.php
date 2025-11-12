<?php
session_start();
include '../db.php';

if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die("CSRF token validation failed.");
}

if (empty($_POST['id']) || empty($_POST['Code']) || empty($_POST['Title'])) {
    die("Error: ID, Code and Title are required.");
}

$id = $_POST['id'];
$code = $_POST['Code'];
$title = $_POST['Title'];
$is_show = isset($_POST['IsShow']) ? 1 : 0;

$sql = "UPDATE product_groups SET code = ?, title = ?, is_show = ? WHERE id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssii", $code, $title, $is_show, $id);

if ($stmt->execute()) {
    header("Location: category_list.php");
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$stmt->close();
$conn->close();
?>
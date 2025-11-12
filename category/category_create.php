<?php
session_start();
include '../db.php';

if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die("CSRF token validation failed.");
}

if (empty($_POST['Code']) || empty($_POST['Title'])) {
    die("Error: Code and Title are required.");
}

$code = $_POST['Code'];
$title = $_POST['Title'];
$is_show = isset($_POST['IsShow']) ? 1 : 0;

$sql = "INSERT INTO product_groups (code, title, is_show) VALUES (?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssi", $code, $title, $is_show);

if ($stmt->execute()) {
    header("Location: category_list.php");
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$stmt->close();
$conn->close();
?>
<?php
session_start();
include '../db.php';

if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die("CSRF token validation failed.");
}

if (empty($_POST['Code']) || empty($_POST['Title']) || empty($_POST['brandId'])) {
    die("Error: Code, Title, and Brand are required.");
}

$code = $_POST['Code'];
$title = $_POST['Title'];
$brand_id = $_POST['brandId'];
$stock_status = $_POST['StockStatus'];
$count_in_bag = $_POST['CountInBag'];
$cach_price = $_POST['CachPrice'];
$no_cach_price = $_POST['NoCachPrice'];
$is_show = isset($_POST['IsShow']) ? 1 : 0;

$sql = "INSERT INTO sub_products (code, title, brand_id, stock_status, count_in_bag, cach_price, no_cach_price, is_show) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssisiidi", $code, $title, $brand_id, $stock_status, $count_in_bag, $cach_price, $no_cach_price, $is_show);

if ($stmt->execute()) {
    header("Location: product_list.php");
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$stmt->close();
$conn->close();
?>
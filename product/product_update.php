<?php
session_start();
include '../db.php';

if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die("CSRF token validation failed.");
}

if (empty($_POST['id']) || empty($_POST['Code']) || empty($_POST['Title']) || empty($_POST['brandId'])) {
    die("Error: ID, Code, Title, and Brand are required.");
}

$id = $_POST['id'];
$code = $_POST['Code'];
$title = $_POST['Title'];
$brand_id = $_POST['brandId'];
$stock_status = $_POST['StockStatus'];
$count_in_bag = $_POST['CountInBag'];
$cach_price = $_POST['CachPrice'];
$no_cach_price = $_POST['NoCachPrice'];
$is_show = isset($_POST['IsShow']) ? 1 : 0;

$sql = "UPDATE sub_products SET code = ?, title = ?, brand_id = ?, stock_status = ?, count_in_bag = ?, cach_price = ?, no_cach_price = ?, is_show = ? WHERE id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssisiidii", $code, $title, $brand_id, $stock_status, $count_in_bag, $cach_price, $no_cach_price, $is_show, $id);

if ($stmt->execute()) {
    header("Location: product_list.php");
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$stmt->close();
$conn->close();
?>
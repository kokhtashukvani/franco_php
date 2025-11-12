<?php
require_once '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $store_name = $_POST['store_name'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $mobile = $_POST['mobile'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $username = $_POST['username'];
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    $target_file = "";
    if (isset($_FILES["profile_image"]) && $_FILES["profile_image"]["name"]) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["profile_image"]["name"]);
        move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file);
    } else {
        $stmt_get_image = $conn->prepare("SELECT profile_image FROM dealers WHERE id = ?");
        $stmt_get_image->bind_param("i", $id);
        $stmt_get_image->execute();
        $result = $stmt_get_image->get_result();
        $row = $result->fetch_assoc();
        $target_file = $row['profile_image'];
        $stmt_get_image->close();
    }

    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE dealers SET store_name = ?, first_name = ?, last_name = ?, profile_image = ?, mobile = ?, phone = ?, email = ?, address = ?, username = ?, password = ?, is_active = ? WHERE id = ?");
        $stmt->bind_param("ssssssssssii", $store_name, $first_name, $last_name, $target_file, $mobile, $phone, $email, $address, $username, $password, $is_active, $id);
    } else {
        $stmt = $conn->prepare("UPDATE dealers SET store_name = ?, first_name = ?, last_name = ?, profile_image = ?, mobile = ?, phone = ?, email = ?, address = ?, username = ?, is_active = ? WHERE id = ?");
        $stmt->bind_param("sssssssssii", $store_name, $first_name, $last_name, $target_file, $mobile, $phone, $email, $address, $username, $is_active, $id);
    }

    if ($stmt->execute()) {
        header("Location: dealer_list.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

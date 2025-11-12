<?php
require_once '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    $stmt = $conn->prepare("UPDATE admins SET first_name = ?, last_name = ?, username = ?, email = ?, phone_number = ?, is_active = ? WHERE id = ?");
    $stmt->bind_param("sssssii", $first_name, $last_name, $username, $email, $phone_number, $is_active, $id);

    if ($stmt->execute()) {
        header("Location: admin_list.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

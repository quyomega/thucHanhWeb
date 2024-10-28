<?php
include '../includes/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $role = $_POST['role'];

    $query = "UPDATE users SET username = ?, email = ?, phone = ?, address = ?, role = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssi", $username, $email,$phone,$address, $role, $id);
    
    if ($stmt->execute()) {
        header("Location: admin_index.php");
        exit();
    } else {
        echo "Có lỗi trong việc cập nhật thông tin.";
    }
}
?>

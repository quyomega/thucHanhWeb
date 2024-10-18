<?php
include '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); 
    $role = $_POST['role'];
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("ssss", $username, $email, $password, $role);
        if ($stmt->execute()) {
            header("Location: admin_index.php");
            exit(); 
        } else {
            echo "Lỗi: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Lỗi trong việc chuẩn bị câu truy vấn: " . $conn->error;
    }
    $conn->close();
}
?>

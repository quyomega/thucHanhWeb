<?php
include '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['id'])) {
        $id = intval($_POST['id']);
        if ($id > 0) {
            $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
            if ($stmt) {
                $stmt->bind_param("i", $id);
                if ($stmt->execute()) {
                    header("Location: admin_index.php?message=success");
                    exit();
                } else {
                    echo "Lỗi thực thi truy vấn: " . $stmt->error;
                }
            } else {
                echo "Lỗi chuẩn bị truy vấn: " . $conn->error;
            }
        } else {
            echo "ID không hợp lệ.";
        }
    } else {
        echo "Không có ID nào được truyền.";
    }
} else {
    echo "Phương thức không hợp lệ.";
}
?>

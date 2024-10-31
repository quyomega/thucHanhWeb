<?php
include '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['id'])) {
        $id = intval($_POST['id']);
        if ($id > 0) {
            // Thay đổi bảng thành 'products' nếu đang xóa sản phẩm
            $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
            if ($stmt) {
                $stmt->bind_param("i", $id);
                if ($stmt->execute()) {
                    // Chuyển hướng thành công với thông báo thành công
                    header("Location: admin_index.php?message=delete_success");
                    exit();
                } else {
                    // In lỗi thực thi truy vấn nếu có
                    echo "Lỗi thực thi truy vấn: " . $stmt->error;
                }
            } else {
                // In lỗi chuẩn bị truy vấn nếu có
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

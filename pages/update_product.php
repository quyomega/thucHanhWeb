<?php
include '../includes/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy thông tin từ form
    $id = $_POST['id'];
    $product_name = $_POST['product_name'];
    $product_code = $_POST['product_code'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $description = $_POST['description'];

    // Câu truy vấn để cập nhật thông tin sản phẩm
    $query = "UPDATE products SET product_name=?, product_code=?, price=?, category=?, description=? WHERE id=?";
    
    // Chuẩn bị câu lệnh
    $stmt = mysqli_prepare($conn, $query);
    if ($stmt) {
        // Liên kết các tham số
        mysqli_stmt_bind_param($stmt, 'ssdsdi', $product_name, $product_code, $price, $category, $description, $id);
        
        // Thực thi câu lệnh
        if (mysqli_stmt_execute($stmt)) {
            echo "Cập nhật sản phẩm thành công!";
        } else {
            echo "Lỗi: " . mysqli_error($conn);
        }

        // Đóng câu lệnh
        mysqli_stmt_close($stmt);
    } else {
        echo "Lỗi chuẩn bị câu lệnh: " . mysqli_error($conn);
    }
}

// Chuyển hướng về trang quản lý
header("Location: admin_index.php");
exit();
?>

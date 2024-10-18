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

    // Khởi tạo biến lưu tên ảnh cũ
    $old_image = ""; // Bạn có thể truy xuất tên ảnh cũ từ cơ sở dữ liệu nếu cần

    // Kiểm tra có file ảnh mới hay không
    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $target = "../assets/images/" . basename($image);
        
        // Di chuyển file ảnh mới vào thư mục
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            // Nếu upload thành công, cập nhật câu truy vấn với ảnh mới
            $query = "UPDATE products SET product_name=?, product_code=?, price=?, category=?, description=?, image=? WHERE id=?";
        } else {
            echo "Lỗi khi tải ảnh lên.";
            exit();
        }
    } else {
        // Nếu không có ảnh mới, cập nhật câu truy vấn mà không thay đổi ảnh
        $query = "UPDATE products SET product_name=?, product_code=?, price=?, category=?, description=? WHERE id=?";
    }
    
    // Chuẩn bị câu lệnh
    $stmt = mysqli_prepare($conn, $query);
    if ($stmt) {
        // Nếu có ảnh mới, cần liên kết thêm tham số cho ảnh
        if (!empty($_FILES['image']['name'])) {
            mysqli_stmt_bind_param($stmt, 'ssdsdsi', $product_name, $product_code, $price, $category, $description, $image, $id);
        } else {
            mysqli_stmt_bind_param($stmt, 'ssdsdi', $product_name, $product_code, $price, $category, $description, $id);
        }
        
        // Thực thi câu lệnh
        if (mysqli_stmt_execute($stmt)) {
            echo "Cập nhật sản phẩm thành công!";
        } else {
            echo "Lỗi: " . mysqli_stmt_error($stmt);
        }

        // Đóng câu lệnh
        mysqli_stmt_close($stmt);
    } else {
        echo "Lỗi chuẩn bị câu lệnh: " . mysqli_error($conn);
    }
}
header("Location: admin_index.php");
exit();
?>

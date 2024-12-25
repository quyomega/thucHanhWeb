<?php
include '../includes/db_connect.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Kiểm tra xem có dữ liệu POST từ hộp thoại xác nhận không
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy thông tin từ form
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    // Truy vấn để lấy giỏ hàng của người dùng
    $query = "SELECT product_id, quantity FROM cart_items WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Cập nhật số lượng sản phẩm trong bảng products
    while ($row = mysqli_fetch_assoc($result)) {
        $product_id = $row['product_id'];
        $quantity = $row['quantity'];

        // Trừ số lượng sản phẩm trong bảng products
        $update_query = "UPDATE products SET quantity = quantity - ? WHERE id = ?";
        $update_stmt = mysqli_prepare($conn, $update_query);
        mysqli_stmt_bind_param($update_stmt, "ii", $quantity, $product_id);
        mysqli_stmt_execute($update_stmt);
    }

    // Xóa giỏ hàng của người dùng
    $delete_query = "DELETE FROM cart_items WHERE user_id = ?";
    $delete_stmt = mysqli_prepare($conn, $delete_query);
    mysqli_stmt_bind_param($delete_stmt, "i", $user_id);
    mysqli_stmt_execute($delete_stmt);

    // Thiết lập thông báo thanh toán thành công
    $payment_success = "Thanh toán thành công!";
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Thông tin thanh toán</h2>

        <!-- Hiển thị thông báo thanh toán thành công nếu có -->
        <?php if (isset($payment_success)): ?>
            <div class="alert alert-success" role="alert">
                <?php echo $payment_success; ?>
            </div>
        <?php endif; ?>

        <form method="POST" id="payment-form">
            <div class="form-group">
                <label for="phone">Số điện thoại</label>
                <input type="text" class="form-control" id="phone" name="phone" required>
            </div>
            <div class="form-group">
                <label for="address">Địa chỉ giao hàng</label>
                <input type="text" class="form-control" id="address" name="address" required>
            </div>
            <button type="submit" class="btn btn-success">Xác nhận thanh toán</button>
        </form>
    </div>
</body>
</html>

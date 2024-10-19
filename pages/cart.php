<?php
include '../includes/db_connect.php';
session_start();
// Kiểm tra xem người dùng đã đăng nhập hay chưa
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Chuyển hướng về trang đăng nhập nếu chưa đăng nhập
    exit();
}

// Lấy user_id từ session
$user_id = $_SESSION['user_id'];

// Lấy dữ liệu giỏ hàng
$query = "SELECT c.quantity, p.id as product_id, p.product_name, p.price, p.image FROM cart_items c 
          JOIN products p ON c.product_id = p.id 
          WHERE c.user_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Tính tổng giá trị giỏ hàng
$total_amount = 0;

// Biến lưu trữ thông báo sản phẩm đã bị xóa
$removed_items = [];

// Kiểm tra và xóa sản phẩm hết hàng
while ($row = mysqli_fetch_assoc($result)) {
    $product_id = $row['product_id'];

    // Kiểm tra số lượng sản phẩm trong kho
    $description_query = "SELECT description FROM products WHERE id = ?"; // Giả sử bạn có một cột 'description' trong bảng 'products'
    $description_stmt = mysqli_prepare($conn, $description_query);
    mysqli_stmt_bind_param($description_stmt, "i", $product_id);
    mysqli_stmt_execute($description_stmt);
    $description_result = mysqli_stmt_get_result($description_stmt);
    $description_row = mysqli_fetch_assoc($description_result);

    // Nếu sản phẩm hết hàng, xóa khỏi giỏ hàng và lưu vào thông báo
    if ($description_row['description'] <= 0) {
        // Lưu tên sản phẩm vào thông báo
        $removed_items[] = $row['product_name'];
        
        $delete_query = "DELETE FROM cart_items WHERE user_id = ? AND product_id = ?";
        $delete_stmt = mysqli_prepare($conn, $delete_query);
        mysqli_stmt_bind_param($delete_stmt, "ii", $user_id, $product_id);
        mysqli_stmt_execute($delete_stmt);
    } else {
        // Nếu sản phẩm còn hàng, tính toán giá trị giỏ hàng
        $subtotal = $row['price'] * $row['quantity'];
        $total_amount += $subtotal;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ hàng</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/cart.css">
    <style>
        h4 {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="marquee">
      <p>Đây là wed bán túi xách của nhóm 13 môn Thực hành Web</p>
    </div>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Nhóm 13</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="user_index.php">Trang chủ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="contact.php">Liên hệ</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container mt-5">
        <h2>Giỏ hàng của bạn</h2>
        <!-- Hiển thị thông báo sản phẩm đã bị xóa -->
        <?php if (!empty($removed_items)): ?>
            <div class="alert alert-warning">
                <?php
                echo "Sản phẩm "  . implode(", ", $removed_items)   . " đã bị xóa khỏi giỏ hàng vì hết hàng: ";
                ?>
            </div>
        <?php endif; ?>
        <?php
        // Lấy lại dữ liệu giỏ hàng sau khi xóa sản phẩm hết hàng
        $query = "SELECT c.quantity, p.product_name, p.price, p.image, p.id as product_id FROM cart_items c 
                  JOIN products p ON c.product_id = p.id 
                  WHERE c.user_id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($result) > 0): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Tên sản phẩm</th>
                        <th>Số lượng</th>
                        <th>Đơn giá</th>
                        <th>Tổng</th>
                        <th>Xác nhận thanh toán</th> <!-- Cột xác nhận thanh toán -->
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                            <td><?php echo number_format($row['price']); ?> VND</td>
                            <td><?php 
                                $subtotal = $row['price'] * $row['quantity']; 
                                echo number_format($subtotal); 
                            ?> VND</td>
                            <td>
                                <input type="checkbox" name="selected_products[]" value="<?php echo $row['product_id']; ?>" data-price="<?php echo $row['price']; ?>" data-quantity="<?php echo $row['quantity']; ?>">
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <h4 id="total-amount">Tổng cộng: <?php echo number_format($total_amount); ?> VND</h4>
        <?php else: ?>
            <p>Giỏ hàng của bạn hiện đang trống.</p>
        <?php endif; ?>
        <div class="d-flex justify-content-between mt-3">
            <a href="user_index.php" class="btn btn-primary">Tiếp tục mua sắm</a>
            <a href="checkout.php" class="btn btn-success">Thanh toán</a> 
        </div>
    </div>
    <footer class="footer">
      <h6> Nguyễn Đức Thắng : 25/01/2003, Cù Khắc Quang : 11/09/2003, Đỗ Vũ Quý : 12/09/2003</h6>
   </footer>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        // Tính toán tổng giá trị cho các sản phẩm đã chọn
        const checkboxes = document.querySelectorAll('input[name="selected_products[]"]');
        const totalAmountElement = document.getElementById('total-amount');
        
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                let total = 0;
                checkboxes.forEach(cb => {
                    if (cb.checked) {
                        const price = parseFloat(cb.dataset.price);
                        const quantity = parseInt(cb.dataset.quantity);
                        total += price * quantity;
                    }
                });
                totalAmountElement.innerText = 'Tổng cộng: ' + total.toLocaleString() + ' VND';
            });
        });
    </script>
</body>
</html>

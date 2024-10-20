<?php
include '../includes/db_connect.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php"); 
    exit();
}

$user_id = $_SESSION['user_id'];
$query = "SELECT c.quantity, p.id as product_id, p.product_name, p.price, p.image FROM cart_items c 
          JOIN products p ON c.product_id = p.id 
          WHERE c.user_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Lưu danh sách sản phẩm trong giỏ hàng vào một mảng
$cart_items = [];
while ($row = mysqli_fetch_assoc($result)) {
    $cart_items[] = $row;
}

// Lấy thông tin người dùng
$user_query = "SELECT phone, address FROM users WHERE id = ?";
$user_stmt = mysqli_prepare($conn, $user_query);
mysqli_stmt_bind_param($user_stmt, "i", $user_id);
mysqli_stmt_execute($user_stmt);
$user_result = mysqli_stmt_get_result($user_stmt);
$user_row = mysqli_fetch_assoc($user_result);
$phone = $user_row['phone'] ?? '';
$address = $user_row['address'] ?? '';

// Xóa mục ra khỏi giỏ hàng
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    $delete_query = "DELETE FROM cart_items WHERE user_id = ? AND product_id = ?";
    $delete_stmt = mysqli_prepare($conn, $delete_query);
    mysqli_stmt_bind_param($delete_stmt, "ii", $user_id, $product_id);
    mysqli_stmt_execute($delete_stmt);
    // Chuyển hướng lại trang giỏ hàng sau khi xóa sản phẩm
    header("Location: cart.php");
    exit();
}

// Xử lý thanh toán
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_payment'])) {
    // Khởi tạo tổng số tiền
    $total_amount = 0;

    // Trừ số lượng trong kho và thêm đơn hàng vào bảng orders
    foreach ($cart_items as $item) {
        $product_id = $item['product_id'];
        $quantity_purchased = $item['quantity'];

        // Cập nhật số lượng sản phẩm trong bảng products
        $update_query = "UPDATE products SET description = description - ? WHERE id = ?";
        $update_stmt = mysqli_prepare($conn, $update_query);
        mysqli_stmt_bind_param($update_stmt, "ii", $quantity_purchased, $product_id);
        mysqli_stmt_execute($update_stmt);

        // Lấy giá sản phẩm từ bảng products
        $product_query = "SELECT price FROM products WHERE id = ?";
        $product_stmt = mysqli_prepare($conn, $product_query);
        mysqli_stmt_bind_param($product_stmt, "i", $product_id);
        mysqli_stmt_execute($product_stmt);
        $product_result = mysqli_stmt_get_result($product_stmt);
        $product_row = mysqli_fetch_assoc($product_result);

        // Tính subtotal cho sản phẩm này
        if ($product_row) {
            $subtotal = $product_row['price'] * $quantity_purchased;
            $total_amount += $subtotal;

            // Cập nhật thông tin vào bảng orders
            $insert_order_query = "INSERT INTO orders (user_id, product_id, quantity, total_amount, created_at) VALUES (?, ?, ?, ?, NOW())";
            $insert_order_stmt = mysqli_prepare($conn, $insert_order_query);
            mysqli_stmt_bind_param($insert_order_stmt, "iiid", $user_id, $product_id, $quantity_purchased, $subtotal);
            if (!mysqli_stmt_execute($insert_order_stmt)) {
                echo "Lỗi thêm đơn hàng: " . mysqli_error($conn);
            }
        }
    }
    // Xóa tất cả các sản phẩm trong giỏ hàng của người dùng
    $clear_cart_query = "DELETE FROM cart_items WHERE user_id = ?";
    $clear_cart_stmt = mysqli_prepare($conn, $clear_cart_query);
    mysqli_stmt_bind_param($clear_cart_stmt, "i", $user_id);
    mysqli_stmt_execute($clear_cart_stmt);
    // Thông báo thanh toán thành công
    echo "<script>alert('Thanh toán thành công!');</script>";
    header("Location: cart.php");
}


// Xóa mục ra khỏi giỏ hàng
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    $delete_query = "DELETE FROM cart_items WHERE user_id = ? AND product_id = ?";
    $delete_stmt = mysqli_prepare($conn, $delete_query); // Sửa ở đây
    mysqli_stmt_bind_param($delete_stmt, "ii", $user_id, $product_id);
    mysqli_stmt_execute($delete_stmt);
    
    // Chuyển hướng lại trang giỏ hàng sau khi xóa sản phẩm
    header("Location: cart.php");
    exit();
}

// Xóa mục ra khỏi giỏ hàng
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    $delete_query = "DELETE FROM cart_items WHERE user_id = ? AND product_id = ?";
    
    // Sửa ở đây: truyền kết nối vào hàm mysqli_prepare
    $delete_stmt = mysqli_prepare($conn, $delete_query); 
    mysqli_stmt_bind_param($delete_stmt, "ii", $user_id, $product_id);
    mysqli_stmt_execute($delete_stmt);
    
    // Chuyển hướng lại trang giỏ hàng sau khi xóa sản phẩm
    header("Location: cart.php");
    exit();
}

// Kiểm tra và xóa sản phẩm hết hàng trong giỏ
$removed_items = [];
foreach ($cart_items as $item) {
    $product_id = $item['product_id'];
    $description_query = "SELECT description FROM products WHERE id = ?"; 
    $description_stmt = mysqli_prepare($conn, $description_query);
    mysqli_stmt_bind_param($description_stmt, "i", $product_id);
    mysqli_stmt_execute($description_stmt);
    $description_result = mysqli_stmt_get_result($description_stmt);
    $description_row = mysqli_fetch_assoc($description_result);
    
    // Kiểm tra số lượng hàng tồn kho
    if ($description_row && $description_row['description'] <= 0) {
        $removed_items[] = $item['product_name'];
        // Xóa sản phẩm hết hàng khỏi giỏ
        $delete_query = "DELETE FROM cart_items WHERE user_id = ? AND product_id = ?";
        $delete_stmt = mysqli_prepare($conn, $delete_query); // Đảm bảo $conn được định nghĩa
        mysqli_stmt_bind_param($delete_stmt, "ii", $user_id, $product_id);
        mysqli_stmt_execute($delete_stmt);
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
        h2 {
            text-align: center;
        }
        h4 {
            text-align: right;
        }
        h6 {
            margin: 0px;
        }
        table {
            text-align: center;
        }
        img {
            max-width: 100px;
            max-height: 100px;
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
        <?php if (!empty($removed_items)): ?>
            <div class="alert alert-warning">
                <?php
                echo "Sản phẩm " . implode(", ", $removed_items) . " đã bị xóa khỏi giỏ hàng vì hết hàng.";
                ?>
            </div>
        <?php endif; ?>
        <?php
        if (count($cart_items) > 0): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Tên sản phẩm</th>
                        <th>Ảnh</th>
                        <th>Số lượng</th>
                        <th>Đơn giá</th>
                        <th>Tổng</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($cart_items as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                        <td>
                        <?php 
                            $imagePath = "../assets/images/" . $row['image']; 
                            if (file_exists($imagePath)): 
                        ?>
                            <img src="<?php echo $imagePath; ?>" class="card-img-top fixed-img" alt="<?php echo $row['product_name']; ?>">
                        <?php else: ?>
                            <img src="assets/images/default.jpg" class="card-img-top fixed-img" alt="Default Image"> 
                        <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                        <td><?php echo number_format($row['price']); ?> VND</td>
                        <td><?php 
                            $subtotal = $row['price'] * $row['quantity']; 
                            echo number_format($subtotal); 
                        ?> VND</td>
                        <td>
                            <button class="btn btn-danger delete-btn" data-product-id="<?php echo $row['product_id']; ?>">Chưa mua</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <h4 id="total-amount">Tổng tiền: 
            <?php
            $total_amount = 0;
            foreach ($cart_items as $item) {
                $total_amount += $item['price'] * $item['quantity'];
            }
            echo number_format($total_amount) . " VND";
            ?>
            </h4>
            <div class="d-flex justify-content-between mt-3">
            <a href="user_index.php" class="btn btn-info">Trở lại mua sắm</a>
                <button class="btn btn-success" data-toggle="modal" data-target="#shippingModal">Thanh toán</button>
            </div>
        <?php else: ?>
            <p>Giỏ hàng của bạn đang trống.</p>
            <a href="user_index.php" class="btn btn-info">Trở lại mua sắm</a>
        <?php endif; ?>
    </div>

    <!-- Modal for shipping information -->
    <div class="modal fade" id="shippingModal" tabindex="-1" role="dialog" aria-labelledby="shippingModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="shippingModalLabel">Thông tin giao hàng</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="cart.php">
                        <div class="form-group">
                            <label for="phone">Số điện thoại:</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="address">Địa chỉ:</label>
                            <textarea class="form-control" id="address" name="address" readonly><?php echo htmlspecialchars($address); ?></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                            <button type="submit" class="btn btn-primary" name="confirm_payment">Xác nhận thanh toán</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $(".delete-btn").click(function() {
                var productId = $(this).data("product-id");
                window.location.href = "cart.php?product_id=" + productId;
            });
        });
    </script>
</body>
</html>

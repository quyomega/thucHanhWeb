<?php
include '../includes/db_connect.php'; 
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); 
    exit();
}
$user_id = $_SESSION['user_id']; 
$query = "SELECT * FROM users WHERE id = $user_id";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);
if (!$user) {
    echo "Không tìm thấy người dùng.";
    exit();
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    // Lấy dữ liệu từ form
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    // Cập nhật thông tin người dùng
    $update_query = "UPDATE users SET username='$username', email='$email', phone='$phone', address='$address' WHERE id=$user_id";
    if (mysqli_query($conn, $update_query)) {
        $message = 'Cập nhật thông tin thành công!';
        // Cập nhật lại thông tin người dùng
        $user['username'] = $username;
        $user['email'] = $email;
        $user['phone'] = $phone;
        $user['address'] = $address;
    } else {
        $message = 'Có lỗi xảy ra trong quá trình cập nhật.';
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin tài khoản</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/user_index.css">
    <style>
        .profile-container {
            margin-top: 50px;
        }
        #userInfo{
            margin-bottom:20px;
        }
        #userInfo, #managementContent {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
            padding: 20px;
            border-radius: 10px;
        }
        .info-row {
            display: flex;
            margin-bottom: 10px;
        }
        .info-row b {
            flex: 0 0 150px;
            text-align: left;
        }
        span {
            margin: 0 10px;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Hiện thông báo nếu có
            <?php if ($message): ?>
                alert("<?php echo $message; ?>");
            <?php endif; ?>
        });
    </script>
</head>
<body>
    <div class="marquee">
        <p>Đây là wed bán túi xách của nhóm 13 môn Thực hành Web</p>
    </div>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Nhóm 13</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="user_index.php">Trang chủ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="contact.php">Liên hệ</a>
                </li>
                <div style="position: absolute; top: 10px; right: 20px;">
                    <a href="logout.php" class="btn btn-danger">Đăng xuất</a>
                </div>
            </ul>
        </div>
    </nav>
    <div class="container profile-container">
        <div id="userInfo">
        <h1 class="text-center">Thông tin tài khoản</h1> 
        <form method="POST" id="updateForm">
            <div class="form-group">
                <label for="username">Tên đăng nhập</label>
                <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="phone">Số điện thoại</label>
                <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>">
            </div>
            <div class="form-group">
                <label for="address">Địa chỉ</label>
                <input type="text" class="form-control" id="address" name="address" value="<?php echo htmlspecialchars($user['address']); ?>">
            </div>
            <button type="submit" class="btn btn-success" name="update">Cập nhật thông tin</button>
        </form> 
        </div>  
        <h1 class="text-center">Lịch sử mua hàng</h1> 
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Mã đơn hàng</th>
                        <th>Mã sản phẩm</th>
                        <th>Số lượng</th>
                        <th>Tổng tiền</th>
                        <th>Ngày đặt</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $order_query = "SELECT * FROM orders WHERE user_id = $user_id ORDER BY created_at DESC";
                    $order_result = mysqli_query($conn, $order_query);
                    while ($order = mysqli_fetch_assoc($order_result)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($order['id']) . "</td>";
                        echo "<td>" . htmlspecialchars($order['product_id']) . "</td>";
                        echo "<td>" . htmlspecialchars($order['quantity']) . "</td>";
                        echo "<td>" . htmlspecialchars(number_format($order['total_amount'], 2)) . " VND</td>";
                        echo "<td>" . htmlspecialchars($order['created_at']) . "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
    </div>
    <footer class="footer">
        <h6>Nguyễn Đức Thắng: 25/01/2003 , Cù Khắc Quang: 11/09/2003 , Đỗ Vũ Quý: 12/09/2003</h6>
    </footer>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

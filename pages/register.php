<?php
include '../includes/db_connect.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    // Kiểm tra mật khẩu và mật khẩu nhập lại có khớp nhau không
    if ($password !== $confirm_password) {
        $error = "Mật khẩu không khớp!";
    } 
    // Kiểm tra tính hợp lệ của mật khẩu (bao gồm các quy tắc về độ dài và ký tự đặc biệt)
    else if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&-_])[A-Za-z\d@$!%*?&-_]{8,}$/', $password)) {
        $error = "Mật khẩu phải có ít nhất 8 ký tự, bao gồm ít nhất một chữ cái viết hoa, một chữ cái viết thường, một số và một ký tự đặc biệt (bao gồm @, $, !, %, *, ?, &, -, _).";
    } 
    // Kiểm tra email đã tồn tại trong cơ sở dữ liệu chưa
    else {
        $checkEmailQuery = "SELECT * FROM users WHERE email = '$email'";
        $checkEmailResult = mysqli_query($conn, $checkEmailQuery);

        if (mysqli_num_rows($checkEmailResult) > 0) {
            $error = "Email đã được đăng ký!";
        } else {
            // Kiểm tra tên người dùng đã tồn tại chưa
            $checkQuery = "SELECT * FROM users WHERE username = '$username'";
            $checkResult = mysqli_query($conn, $checkQuery);

            if (mysqli_num_rows($checkResult) > 0) {
                $error = "Tên người dùng đã tồn tại!";
            } else {
                // Mã hóa mật khẩu trước khi lưu vào cơ sở dữ liệu
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Thực hiện đăng ký người dùng
                $query = "INSERT INTO users (username, password, email, phone, address) VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("sssss", $username, $hashed_password, $email, $phone, $address);

                if ($stmt->execute()) {
                    echo "<script>alert('Đăng ký thành công!'); window.location.href='login.php';</script>";
                } else {
                    $error = "Đã xảy ra lỗi. Vui lòng thử lại.";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/register.css">
</head>
<body>
    <div class="main">
        <div class="login-container">
            <div class="right-panel">

                <?php if ($error): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <form action="" method="POST">
                    <div class="form-group">
                        <label for="username">Tên người dùng:</label>
                        <input type="text" class="form-control" id="username" name="username"  title="Tên người dùng không được chứa dấu cách." required>
                    </div>
                    <div class="form-group">
                        <label for="password">Mật khẩu:</label>
                        <input type="password" class="form-control" id="password" name="password" pattern="^\S+$" title="Mật khẩu không được chứa dấu cách." required>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Nhập lại mật khẩu:</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Số điện thoại:</label>
                        <input type="text" class="form-control" id="phone" name="phone" pattern="^\d{9,10}$" title="Số điện thoại phải có 9 hoặc 10 chữ số." required>
                    </div>
                    <div class="form-group">
                        <label for="address">Địa chỉ:</label>
                        <input type="text" class="form-control" id="address" name="address" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Đăng Ký</button>
                    <div class="text-center">
                        <a href="login.php" class="btn btn-link">Đã có tài khoản? Đăng nhập</a>
                    </div>
                </form>

                <p class="back_index">
                    <a href="index.php">Trở về trang chủ</a>
                </p>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

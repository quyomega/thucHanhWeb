<?php
include 'includes/db_connect.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['username'] = $username;
            header("Location: index.php");
            exit();
        } else {
            $error = "Tên đăng nhập hoặc mật khẩu không đúng!";
        }
    } else {
        $error = "Tên đăng nhập hoặc mật khẩu không đúng!";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            background-color: #f0f2f5; 
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .main{
            display: flex;
        }
        .login-container {
            display: flex; 
            width: 400px; 
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .left-panel {
            padding: 30px; 
            width: 400px; 
            flex: 1; 
        }
        .right-panel {
            padding: 30px; 
            flex: 1; 
        }
        h2 {
            color: #1877f2; 
        }
        p.text-center {
            margin-bottom: 20px; 
        }
        .form-group label {
            font-weight: bold;
        }
        .btn-primary {
            background-color: #1877f2;
            border: none;
            width: 100%;
        }
        .btn-primary:hover {
            background-color: #165eab;
        }
        .text-center {
            margin-top: 15px;
        }
        .footer-text {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #65676b;
        }
    </style>
</head>
<body>
    <div class="main">
        <!-- <div class="left-panel">
            <h2>nhom13_tuisach</h2>
            <p>Facebook giúp bạn kết nối và chia sẻ với mọi người trong cuộc sống của bạn.</p>
        </div> -->
        <div class="login-container">
            <div class="right-panel">
                <?php if ($error): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <form action="" method="POST">
                    <div class="form-group">
                        <label for="username">Tên đăng nhập:</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Mật khẩu:</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Đăng nhập</button>
                    <div class="d-flex justify-content-between">
                        <a href="forgot_password.php" class="btn btn-link">Quên mật khẩu</a>
                        <a href="register.php" class="btn btn-link">Tạo tài khoản mới</a>
                    </div>
                    <div class="text-center">
                        <a href="index.php" class="btn btn-link">Trở về trang chủ</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

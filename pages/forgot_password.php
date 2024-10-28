<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên Mật Khẩu</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Quên Mật Khẩu</h2> 
    <form method="POST" action="send_new_password.php">
        <div class="form-group" >
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <button type="submit" class="btn btn-primary" style="width:190px;right:0px;">Gửi Mật Khẩu Mới</button>
    </form>
    <div class="mt-3"> 
        <a href="login.php" class="btn btn-secondary">Trở về trang đăng nhập</a> 
    </div>
</div>
</body>
</html>

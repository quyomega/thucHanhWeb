<?php
include '../includes/db_connect.php'; 

// Nhúng các tệp PHPMailer
require 'D:\xampp\htdocs\thucHanhWeb\PHPMailer-master\src\Exception.php';
require 'D:\xampp\htdocs\thucHanhWeb\PHPMailer-master\src\PHPMailer.php';
require 'D:\xampp\htdocs\thucHanhWeb\PHPMailer-master\src\SMTP.php';

// Nhập các lớp PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Kiểm tra xem email có được gửi từ form không
if (isset($_POST['email'])) {
    $email = $_POST['email'];
    // Kiểm tra định dạng email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Địa chỉ email không hợp lệ.";
        exit;
    }
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $new_password = bin2hex(random_bytes(4)); // Tạo mật khẩu mới ngẫu nhiên
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT); // Mã hóa mật khẩu mới

        // Cập nhật mật khẩu mới vào cơ sở dữ liệu
        $sql = "UPDATE users SET password = ? WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $hashed_password, $email);
        $stmt->execute();
        $stmt->close();
        
        // Gửi email với mật khẩu mới
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'hanglam1209@gmail.com'; 
            $mail->Password   = 'qlou tusy pfxo adhr'; 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('hanglam1209@gmail.com', 'Team 13');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'New your password';
            $mail->Body    = 'Mật khẩu mới của bạn là: <strong>' . $new_password . '</strong>';
            $mail->AltBody = 'Mật khẩu mới của bạn là: ' . $new_password;
            $mail->send();

            // Thông báo thành công và chuyển hướng về trang đăng nhập
            echo '<script>alert("Mật khẩu mới đã được gửi đến email của bạn."); window.location.href="login.php";</script>';
        } catch (Exception $e) {
            echo "Không thể gửi email. Lỗi: {$mail->ErrorInfo}";
        }
    } else {
        echo "Email không tồn tại.";
    }

    $conn->close();
} else {
    echo "Chưa nhận được địa chỉ email.";
}
?>

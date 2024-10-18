<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
   <title>Nơi Góp Ý</title>
   <style>
       body {
           background-color: #f8f9fa;
       }
       .container {
           margin-top: 50px;
       }
       .form-group label {
           font-weight: bold;
       }
       .footer {
           margin-top: 50px;
           text-align: center;
       }
   </style>
</head>
<body>
   <div class="container">
       <h2 class="text-center">Góp Ý</h2>
       <form method="POST" action="contact.php">
           <div class="form-group">
               <label for="name">Tên của bạn:</label>
               <input type="text" name="name" class="form-control" id="name" placeholder="Nhập tên của bạn" required>
           </div>
           <div class="form-group">
               <label for="email">Email:</label>
               <input type="email" name="email" class="form-control" id="email" placeholder="Nhập email của bạn" required>
           </div>
           <div class="form-group">
               <label for="phone">Số điện thoại:</label>
               <input type="text" name="phone" class="form-control" id="phone" placeholder="Nhập số điện thoại của bạn" required>
           </div>
           <div class="form-group">
               <label for="message">Nội dung:</label>
               <textarea name="message" class="form-control" id="message" rows="4" placeholder="Nhập nội dung góp ý của bạn" required></textarea>
           </div>
           <div class="d-flex justify-content-between mt-4">
               <button type="submit" name="submit" class="btn btn-primary w-40" onclick="location.href='index.php';">Trở về trang chủ</button>
               <button type="submit" name="submit" class="btn btn-primary w-40">Góp ý</button>
           </div>
       </form>
       
       <?php
           include '../includes/db_connect.php'; 

           if (isset($_POST['submit'])) {
               $name = mysqli_real_escape_string($conn, $_POST['name']);
               $email = mysqli_real_escape_string($conn, $_POST['email']);
               $phone = mysqli_real_escape_string($conn, $_POST['phone']);
               $message = mysqli_real_escape_string($conn, $_POST['message']);
               if (!empty($name) && !empty($email) && !empty($message)) {
                   $query = "INSERT INTO contacts (name, email, phone, message) VALUES ('$name', '$email', '$phone', '$message')";
                   if (mysqli_query($conn, $query)) {
                       echo "<div class='alert alert-success mt-3'>Cảm ơn bạn đã góp ý!</div>";
                   } else {
                       echo "<div class='alert alert-danger mt-3'>Lỗi: " . mysqli_error($conn) . "</div>";
                   }
               } else {
                   echo "<div class='alert alert-warning mt-3'>Vui lòng điền đầy đủ thông tin!</div>";
               }
           }
       ?>
   </div>
   <div class="footer">
       <p>&copy; Đây là nơi bạn có thể góp ý cho nhóm 13</p>
   </div>
   <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
   <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

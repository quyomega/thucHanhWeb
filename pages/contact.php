<form method="POST" action="contact.php">
   <input type="text" name="name" placeholder="Tên của bạn">
   <input type="email" name="email" placeholder="Email">
   <input type="text" name="phone" placeholder="Số điện thoại">
   <textarea name="message" placeholder="Nội dung"></textarea>
   <button type="submit" name="submit">Gửi liên hệ</button>
</form>
<?php
    if(isset($_POST['submit'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $message = $_POST['message'];
        
        if(!empty($name) && !empty($email) && !empty($message)) {
           $query = "INSERT INTO contacts (name, email, phone, message) VALUES ('$name', '$email', '$phone', '$message')";
           mysqli_query($conn, $query);
           echo "Cảm ơn bạn đã liên hệ!";
        } else {
           echo "Vui lòng điền đầy đủ thông tin!";
        }
     }
     
?>
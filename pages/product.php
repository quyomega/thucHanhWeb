<?php
include '../includes/db_connect.php';
session_start(); 
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); 
    exit();
}

$id = $_GET['id'];
$query = "SELECT * FROM products WHERE id = $id";
$result = mysqli_query($conn, $query);
$product = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Chi tiết sản phẩm</title>
   <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
   <link rel="stylesheet" href="../assets/css/style.css">
   <style>
      .product-image {
         width: 200px;
         height: 200px;
      }
      .product-details {
         margin-top: 20px;
      }
   </style>
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
               <a class="nav-link" href="<?php echo isset($_SESSION['username']) ? 'user_index.php' : 'index.php'; ?>">Trang chủ</a>
            </li>
            <li class="nav-item">
               <a class="nav-link" href="contact.php">Liên hệ</a>
            </li>
         </ul>
      </div>
   </nav>

   <div class="container product-details">
      <div class="row">
         <div class="col-md-6">
            <img src="../assets/images/<?php echo $product['image']; ?>" alt="<?php echo $product['product_name']; ?>" class="product-image">
         </div>
         <div class="col-md-6">
            <h1>Tên sản phẩm : <?php echo $product['product_name']; ?></h1>
            <p class="lead">Giá: <strong><?php echo number_format($product['price']); ?> VND</strong></p>
            <p>Số lượng còn: <?php echo $product['description']; ?></p>
            <button class="btn btn-primary">Thêm vào giỏ hàng</button>
         </div>
      </div>
   </div>

   <footer class="footer">
      <h6>Nguyễn Đức Thắng : 25/01/2003, Cù Khắc Quang : 11/09/2003, Đỗ Vũ Quý : 12/09/2003</h6>
   </footer>

   <script src="https://code.jquery.com/jquery-3.5.2.slim.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
   <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

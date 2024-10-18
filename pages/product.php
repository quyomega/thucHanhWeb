<?php
include '../includes/db_connect.php';
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
         width: 100%;
         height: auto;
      }
      .product-details {
         margin-top: 20px;
      }
   </style>
</head>
<body>
   <div class="marquee">
      <p>Đây là wed bán túi sách của nhóm 13 môn Thực hành Web</p>
   </div>
   <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <a class="navbar-brand" href="index.php">Nhóm 13</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
         <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
         <ul class="navbar-nav">
            <li class="nav-item">
               <a class="nav-link" href="index.php">Trang chủ</a>
            </li>
            <li class="nav-item">
               <a class="nav-link" href="login.php">Đăng nhập</a>
            </li>
            <li class="nav-item">
               <a class="nav-link" href="register.php">Đăng ký</a>
            </li>
            <li class="nav-item">
               <a class="nav-link" href="contact.php">Liên hệ</a>
            </li>
         </ul>
         <form class="form-inline my-2 my-lg-0 ml-auto" method="POST" action="index.php">
            <input class="form-control mr-2" type="search" name="search_term" placeholder="Tìm kiếm sản phẩm" aria-label="Search">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Tìm kiếm</button>
         </form>
      </div>
   </nav>

   <div class="container product-details">
      <div class="row">
         <div class="col-md-6">
            <img src="../assets/images/<?php echo $product['image']; ?>" alt="<?php echo $product['product_name']; ?>" class="product-image">
         </div>
         <div class="col-md-6">
            <h1><?php echo $product['product_name']; ?></h1>
            <p class="lead">Giá: <strong><?php echo number_format($product['price']); ?> VND</strong></p>
            <p>Mô tả: <?php echo $product['description']; ?></p>
            <button class="btn btn-primary">Thêm vào giỏ hàng</button>
         </div>
      </div>
   </div>

   <footer class="footer">
      <p>Tên nhóm: Nhóm 13</p>
      <h6>Nguyễn Đức Thắng : 25/01/2003, Cù Khắc Quang : 11/09/2003, Đỗ Vũ Quý : 12/09/2003</h6>
   </footer>

   <script src="https://code.jquery.com/jquery-3.5.2.slim.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
   <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

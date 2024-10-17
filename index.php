<?php
include 'includes/db_connect.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Trang chủ</title>
   <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
   <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

   <!-- <header class="mb-4">
      <img src="assets/images/banner.jpg" alt="Banner" class="img-fluid w-100">
   </header> -->

   <nav class="navbar navbar-expand-lg navbar-light">
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
      </div>
   </nav>

   <div class="container mt-5">
      <h2 class="text-center mb-4">Sản phẩm</h2>
      <div class="row">
         <?php
         $query = "SELECT * FROM products";
         $result = mysqli_query($conn, $query);

         while($row = mysqli_fetch_assoc($result)): ?>
            <div class="col-md-4 mb-4">
               <div class="card h-100">
                  <img src="assets/images/<?php echo $row['image']; ?>" class="card-img-top" alt="<?php echo $row['product_name']; ?>">
                  <div class="card-body">
                     <h5 class="card-title"><?php echo $row['product_name']; ?></h5>
                     <p class="card-text"><?php echo number_format($row['price']); ?> VND</p>
                     <a href="product.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">Xem chi tiết</a>
                  </div>
               </div>
            </div>
         <?php endwhile; ?>
      </div>
   </div>

   <footer class=" text-center  mt-4">
      <p>Tên nhóm: Nhóm 13</p>
      <h6>Cù Khắc Quang : 11/09/2003, Đỗ Vũ Quý : 12/09/2003, Nguyễn Đức Thắng : 25/01/2003</h6>
   </footer>

   <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
   <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

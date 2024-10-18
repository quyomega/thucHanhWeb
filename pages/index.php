<?php
include '../includes/db_connect.php';

// Số sản phẩm mỗi trang
$products_per_page = 6;

// Lấy số trang từ URL, nếu không có thì mặc định là 1
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $products_per_page;

// Tính tổng số sản phẩm
$total_query = "SELECT COUNT(*) as total FROM products";
$total_result = mysqli_query($conn, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_products = $total_row['total'];

// Tính tổng số trang
$total_pages = ceil($total_products / $products_per_page);

// Lấy sản phẩm cho trang hiện tại
$query = "SELECT * FROM products LIMIT $offset, $products_per_page";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Trang chủ</title>
   <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
   <link rel="stylesheet" href="../assets/css/style.css">
   <style>
       .navbar {
           position: fixed; 
           width: 100%; 
           z-index: 1000; 
       }
       .footer {
           position: fixed; 
           bottom: 0;
           width: 100%; 
           background-color: #f8f9fa; 
           text-align: center; 
           padding: 10px 0; 
       }
       .fixed-img {
           width: 100%; 
           height: 400px; 
       }
       .card {
           max-width: 300px; 
           margin: auto; 
       }
       .container {
         padding-top: 50px ;
         padding-bottom: 70px ; 
       }
       .card-body {
            display: flex;
            flex-direction: column;
            align-items: center; 
            text-align: center;  
         }
   </style>
</head>
<body>
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
      </div>
   </nav>

   <div class="container mt-5">
      <h2 class="text-center mb-4">Sản phẩm</h2>
      <div class="row">
      <?php while($row = mysqli_fetch_assoc($result)): ?>
   <div class="col-md-4 mb-4">
      <div class="card h-100">
         <?php 
         $imagePath = "../assets/images/" . $row['image']; 
         if (file_exists($imagePath)): 
         ?>
            <img src="<?php echo $imagePath; ?>" class="card-img-top fixed-img" alt="<?php echo $row['product_name']; ?>">
         <?php else: ?>
            <img src="assets/images/default.jpg" class="card-img-top fixed-img" alt="Default Image"> 
         <?php endif; ?>
         <div class="card-body">
            <h5 class="card-title">Tên sản phẩm : <?php echo $row['product_name']; ?></h5>
            <p><h5 class="card-text">Giá : <?php echo number_format($row['price']); ?> VND</h5></p>
            <a href="product.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">Xem chi tiết</a>
         </div>
      </div>
   </div>
<?php endwhile; ?>
      </div>

      <!-- Phân trang -->
      <nav aria-label="Page navigation">
         <ul class="pagination justify-content-center">
            <?php if ($page > 1): ?>
               <li class="page-item">
                  <a class="page-link" href="?page=<?php echo $page - 1; ?>" aria-label="Previous">
                     <span aria-hidden="true">&laquo;</span>
                  </a>
               </li>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
               <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                  <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
               </li>
            <?php endfor; ?>

            <?php if ($page < $total_pages): ?>
               <li class="page-item">
                  <a class="page-link" href="?page=<?php echo $page + 1; ?>" aria-label="Next">
                     <span aria-hidden="true">&raquo;</span>
                  </a>
               </li>
            <?php endif; ?>
         </ul>
      </nav>
   </div>

   <footer class="footer">
      <p>Tên nhóm: Nhóm 13</p>
      <h6> Nguyễn Đức Thắng : 25/01/2003, Cù Khắc Quang : 11/09/2003, Đỗ Vũ Quý : 12/09/2003</h6>
   </footer>

   <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
   <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

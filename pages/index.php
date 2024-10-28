<?php
   include '../includes/db_connect.php';
   $products_per_page = 6;
   $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
   $offset = ($page - 1) * $products_per_page;
   $search_term = isset($_GET['search_term']) ? mysqli_real_escape_string($conn, $_GET['search_term']) : '';
   $total_query = "SELECT COUNT(*) as total FROM products WHERE product_name LIKE '%$search_term%'";
   $total_result = mysqli_query($conn, $total_query);
   $total_row = mysqli_fetch_assoc($total_result);
   $total_products = $total_row['total'];
   $total_pages = ceil($total_products / $products_per_page);
   $query = "SELECT * FROM products WHERE product_name LIKE '%$search_term%' LIMIT $offset, $products_per_page";
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
         <form id="searchForm" class="form-inline my-2 my-lg-0 ml-auto" method="GET" action="index.php" oninput="autoSearch()">
            <input class="form-control mr-2" type="search" name="search_term" placeholder="Tìm kiếm sản phẩm" value="<?php echo htmlspecialchars($search_term); ?>" aria-label="Search">
            <input type="hidden" name="page" value="1"> 
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit" style="display: none;">Tìm kiếm</button>
         </form>
      </div>
   </nav>

   <div class="container mt-5">
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
                  <a class="page-link" href="?page=<?php echo $page - 1; ?>&search_term=<?php echo urlencode($search_term); ?>" aria-label="Previous">
                     <span aria-hidden="true">&laquo;</span>
                  </a>
               </li>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
               <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                  <a class="page-link" href="?page=<?php echo $i; ?>&search_term=<?php echo urlencode($search_term); ?>"><?php echo $i; ?></a>
               </li>
            <?php endfor; ?>

            <?php if ($page < $total_pages): ?>
               <li class="page-item">
                  <a class="page-link" href="?page=<?php echo $page + 1; ?>&search_term=<?php echo urlencode($search_term); ?>" aria-label="Next">
                     <span aria-hidden="true">&raquo;</span>
                  </a>
               </li>
            <?php endif; ?>
         </ul>
      </nav>
   </div>

   <footer class="footer">
      <h6> Nguyễn Đức Thắng: 25/01/2003 , Cù Khắc Quang: 11/09/2003 , Đỗ Vũ Quý: 12/09/2003</h6>
   </footer>

   <script src="https://code.jquery.com/jquery-3.5.2.slim.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
   <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

   <script>
      let searchTimeout;
      function autoSearch() {
          clearTimeout(searchTimeout); 
          searchTimeout = setTimeout(function() {
              document.getElementById('searchForm').submit(); 
          }, 500); 
      }
   </script>
</body>
</html>

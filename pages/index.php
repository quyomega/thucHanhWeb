<?php
   include '../includes/db_connect.php';
   $products_per_page = 6;
   $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
   $offset = ($page - 1) * $products_per_page;
   $search_term = isset($_GET['search_term']) ? mysqli_real_escape_string($conn, $_GET['search_term']) : '';
   $sort_order = isset($_GET['sort']) && $_GET['sort'] === 'DESC' ? 'DESC' : 'ASC'; // Mặc định là tăng dần
   $is_best_selling = isset($_GET['best_selling']) && $_GET['best_selling'] === 'true';
   $is_new_arrivals = isset($_GET['new_arrivals']) && $_GET['new_arrivals'] === 'true';
   // Tính tổng sản phẩm
   if ($is_new_arrivals) {
      $total_query = "SELECT COUNT(*) as total FROM products";
   } else if ($is_best_selling) {
      $total_query = "SELECT COUNT(DISTINCT p.id) as total FROM orders o JOIN products p ON o.product_id = p.id";
   } else {
      $total_query = "SELECT COUNT(*) as total FROM products WHERE product_name LIKE '%$search_term%'";
   }
   $total_result = mysqli_query($conn, $total_query);
   $total_row = mysqli_fetch_assoc($total_result);
   $total_products = $total_row['total'];
   $total_pages = ceil($total_products / $products_per_page);
   // Lấy danh sách sản phẩm
   if ($is_new_arrivals) {
      $query = "SELECT * FROM products ORDER BY created_at DESC LIMIT $offset, $products_per_page";
   } else if ($is_best_selling) {
      $query = "
         SELECT p.* FROM orders o
         JOIN products p ON o.product_id = p.id
         GROUP BY p.id
         ORDER BY p.price $sort_order
         LIMIT $offset, $products_per_page";
   } else {
      $query = "SELECT * FROM products WHERE product_name LIKE '%$search_term%' ORDER BY price $sort_order LIMIT $offset, $products_per_page";
   }
   $result = mysqli_query($conn, $query);
   // Truy vấn tổng số sản phẩm dựa trên tìm kiếm
   $total_query = "SELECT COUNT(*) as total FROM products WHERE product_name LIKE '%$search_term%'";
   $total_result = mysqli_query($conn, $total_query);
   $total_row = mysqli_fetch_assoc($total_result);
   $total_products = $total_row['total'];
   $total_pages = ceil($total_products / $products_per_page);
   // Truy vấn sản phẩm theo tìm kiếm và sắp xếp
   $query = "SELECT * FROM products WHERE product_name LIKE '%$search_term%' ORDER BY price $sort_order LIMIT $offset, $products_per_page";
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
   <link rel="stylesheet" href="../assets/css/index.css">
</head>
<body>
   <div class="container-fluid">
      <div class="row">
         <div class="marquee">
            <p>Đây là web bán túi xách của nhóm 13 môn Thực hành Web</p>
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
               <form id="searchForm" class="form-inline my-2 my-lg-0 ml-auto" method="GET" action="index.php">
                  <input 
                     class="form-control mr-2" 
                     type="search" 
                     name="search_term" 
                     placeholder="Tìm kiếm sản phẩm" 
                     value="<?php echo htmlspecialchars($search_term); ?>" 
                     aria-label="Search" 
                     oninput="autoSearch()">
                  <input type="hidden" name="page" value="1">
               </form>
            </div>
         </nav>
         <!-- Left Menu -->
         <div class="col-md-2 bg-light sidebar" style="margin-top: 100px;">
            <ul class="nav flex-column">
               <li class="nav-item">
                  <a class="nav-link" id="newArrivalsLink" href="?sort=<?php echo $sort_order; ?>&search_term=<?php echo urlencode($search_term); ?>&new_arrivals=true">Hàng mới</a>
               </li>
               <li class="nav-item">
                  <a class="nav-link" id="bestSellingLink" href="?sort=<?php echo $sort_order; ?>&search_term=<?php echo urlencode($search_term); ?>&best_selling=true">Hàng bán chạy</a>
               </li>
               <li class="nav-item">
                  <a class="nav-link">Hàng giảm giá</a>
               </li>
               <li class="nav-item">
                  <a class="nav-link" id="sortPriceToggle" href="javascript:void(0);">Sắp xếp theo giá</a>
                  <ul id="priceSortOptions" style="list-style-type: none; padding-left: 20px; display: none;">
                     <li>
                        <a href="?sort=ASC&search_term=<?php echo urlencode($search_term); ?>&best_selling=<?php echo $is_best_selling ? 'true' : 'false'; ?>&new_arrivals=<?php echo $is_new_arrivals ? 'true' : 'false'; ?>">
                           <span>&#9650;</span> Tăng dần
                        </a>
                     </li>
                     <li>
                        <a href="?sort=DESC&search_term=<?php echo urlencode($search_term); ?>&best_selling=<?php echo $is_best_selling ? 'true' : 'false'; ?>&new_arrivals=<?php echo $is_new_arrivals ? 'true' : 'false'; ?>">
                           <span>&#9660;</span> Giảm dần
                        </a>
                     </li>
                  </ul>
               </li>
            </ul>
         </div>

         <!-- Main Content -->
         <div class="col-md-10" style="margin-left: 16.6667%;">
            <div class="container mt-5" style="padding-bottom: 50px;">
               <div class="row" id="product-list">
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
                           <a class="page-link" href="?page=<?php echo $page - 1; ?>&search_term=<?php echo urlencode($search_term); ?>&sort=<?php echo $sort_order; ?>&best_selling=<?php echo $is_best_selling ? 'true' : 'false'; ?>&new_arrivals=<?php echo $is_new_arrivals ? 'true' : 'false'; ?>" aria-label="Previous">
                              <span aria-hidden="true">&laquo;</span>
                           </a>
                        </li>
                     <?php endif; ?>

                     <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                           <a class="page-link" href="?page=<?php echo $i; ?>&search_term=<?php echo urlencode($search_term); ?>&sort=<?php echo $sort_order; ?>&best_selling=<?php echo $is_best_selling ? 'true' : 'false'; ?>&new_arrivals=<?php echo $is_new_arrivals ? 'true' : 'false'; ?>"><?php echo $i; ?></a>
                        </li>
                     <?php endfor; ?>

                     <?php if ($page < $total_pages): ?>
                        <li class="page-item">
                           <a class="page-link" href="?page=<?php echo $page + 1; ?>&search_term=<?php echo urlencode($search_term); ?>&sort=<?php echo $sort_order; ?>&best_selling=<?php echo $is_best_selling ? 'true' : 'false'; ?>&new_arrivals=<?php echo $is_new_arrivals ? 'true' : 'false'; ?>" aria-label="Next">
                              <span aria-hidden="true">&raquo;</span>
                           </a>
                        </li>
                     <?php endif; ?>
                  </ul>
               </nav>
            </div>
         </div>
      </div>
   </div>
      <footer class="footer">
         <h6>Nguyễn Đức Thắng: 25/01/2003 , Cù Khắc Quang: 11/09/2003 , Đỗ Vũ Quý: 12/09/2003</h6>
      </footer>
   <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
   <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
   <script>
      let debounceTimer;
      function autoSearch() {
         const searchInput = document.querySelector('input[name="search_term"]');
         const searchForm = document.getElementById('searchForm');
         const searchTerm = searchInput.value;

         clearTimeout(debounceTimer); // Xóa timer trước đó

         debounceTimer = setTimeout(() => {
            const searchUrl = new URL(window.location.href);

            if (searchTerm) {
               searchUrl.searchParams.set('search_term', searchTerm);
               searchUrl.searchParams.set('page', '1');
            } else {
               searchUrl.searchParams.delete('search_term');
            }

            window.history.pushState({}, '', searchUrl);
            searchForm.submit();
         }, 1000); // Trì hoãn 500ms
      }
      document.getElementById('sortPriceToggle').addEventListener('click', function () {
         const sortOptions = document.getElementById('priceSortOptions');
         // Kiểm tra trạng thái hiện tại và thay đổi
         if (sortOptions.style.display === 'none' || sortOptions.style.display === '') {
            sortOptions.style.display = 'block'; // Hiển thị menu
         } else {
            sortOptions.style.display = 'none'; // Ẩn menu
         }
      });
   </script>
</body>
</html>

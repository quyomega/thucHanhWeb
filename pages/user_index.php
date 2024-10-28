<?php
   include '../includes/db_connect.php';
   session_start(); 
   if (!isset($_SESSION['username'])) {
      header("Location: login.php"); 
      exit();
   }
   $message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
   unset($_SESSION['message']);
   $products_per_page = 6;
   $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
   $offset = ($page - 1) * $products_per_page;
   $total_query = "SELECT COUNT(*) as total FROM products";
   $total_result = mysqli_query($conn, $total_query);
   $total_row = mysqli_fetch_assoc($total_result);
   $total_products = $total_row['total'];
   $total_pages = ceil($total_products / $products_per_page);
   $search_term = isset($_GET['search_term']) ? mysqli_real_escape_string($conn, $_GET['search_term']) : '';
   $query = "SELECT * FROM products WHERE product_name LIKE '%$search_term%' LIMIT $offset, $products_per_page";
   $result = mysqli_query($conn, $query);
   $user_id = $_SESSION['user_id']; 
   $cart_count_query = "SELECT SUM(quantity) as total_quantity FROM cart_items WHERE user_id = $user_id";
   $cart_count_result = mysqli_query($conn, $cart_count_query);
   $cart_count_row = mysqli_fetch_assoc($cart_count_result);
   $_SESSION['cart_count'] = $cart_count_row['total_quantity'];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Trang chủ</title>
   <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
   <link rel="stylesheet" href="../assets/css/style.css">
   <link rel="stylesheet" href="../assets/css/user_index.css">
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
                <a class="nav-link" href="user_index.php">Trang chủ</a>
            </li>       
            <li class="nav-item">
                <a class="nav-link" href="contact.php">Liên hệ</a>
            </li>
        </ul>
        <form id="searchForm" class="form-inline my-2 my-lg-0 ml-auto" method="GET" action="user_index.php" oninput="autoSearch()">
            <input class="form-control mr-2" type="search" name="search_term" placeholder="Tìm kiếm sản phẩm" value="<?php echo htmlspecialchars($search_term); ?>" aria-label="Search">
            <input type="hidden" name="page" value="1"> 
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit" style="display: none;">Tìm kiếm</button>
        </form>
        <a href="cart.php" class="text-decoration-none ml-3">
            <i class="bi bi-cart" style="font-size: 24px;"></i>
            <span id="cart-count" class="badge badge-pill badge-danger" style="position: absolute; top: 8px; right: 43px;">
               <?php echo isset($_SESSION['cart_count']) ? $_SESSION['cart_count'] : 0; ?>
            </span>
         </a>
        <div class="ml-3 dropdown">
            <a href="#" class="text-decoration-none " id="accountDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
               <i class="bi bi-person" style="font-size: 24px;"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="accountDropdown">
               <a class="dropdown-item text" href="user_info.php">
                  <!-- <?php echo $_SESSION['username']; ?> -->
                  Thông tin tài khoản
               </a>
               <a class="dropdown-item text-danger" href="logout.php">Đăng xuất</a>
            </div>
        </div>
   </div>
   </nav>
<div class="container mt-5">
   <?php if ($message): ?>
       <div class="alert alert-success" role="alert">
           <?php echo $message; ?>
       </div>
   <?php endif; ?>
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
               <h5 class="card-title">Tên sản phẩm: <?php echo $row['product_name']; ?></h5>
               <p class="card-text">Giá: <?php echo number_format($row['price']); ?> VND</p>
               <div class="d-flex align-items-center">
                  <a href="product.php?id=<?php echo $row['id']; ?>" class="btn btn-primary mr-2 "style="white-space: nowrap;">Xem chi tiết</a>
                  <form action="add_to_cart.php" method="POST">
                        <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" class="btn btn-success" style="white-space: nowrap;">Thêm vào giỏ hàng</button>
                  </form>
               </div>
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
   <h6>Nguyễn Đức Thắng: 25/01/2003 , Cù Khắc Quang: 11/09/2003 , Đỗ Vũ Quý: 12/09/2003</h6>
</footer>
<?php if ($message): ?>
   <div class="alert alert-success" role="alert">
       <?php echo $message; ?>
   </div>
<?php endif; ?>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
function autoSearch() {
    document.getElementById('searchForm').submit();
}
</script>
<script>
$(document).ready(function() {
    $('form').on('submit', function(event) {
        event.preventDefault(); 
        var form = $(this);
        $.ajax({
         type: 'POST',
         url: 'add_to_cart.php',
         data: form.serialize(),
         success: function(response) {
            var jsonResponse = JSON.parse(response);  
            console.log("response =", response); 
            console.log("jsonResponse.success =", jsonResponse.success);  
            if (jsonResponse.success === true) {
                  var cartCount = <?php echo isset($_SESSION['cart_count']) ? $_SESSION['cart_count'] : 0; ?>;
                  cartCount++;
                  $('#cart-count').text(cartCount); 
                  alert('Sản phẩm đã được thêm vào giỏ hàng.');
                  location.reload(); 
            } else {
                  alert(jsonResponse.message);  
            }
         },
         error: function() {
            alert('Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng.');
         }
      });
    });
});
</script>
</body>
</html>
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
   <title>Chi tiết sản phẩm</title>
   <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
   <link rel="stylesheet" href="../assets/css/style.css">
   <style>
      .product-image {
         width: 300px;
         height: 360px;
         box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5); 
      }
      .product-details {
         margin-top: 50px;
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
             <div class="dropdown-item">
                 <p style = "margin:0px;"><?php echo $_SESSION['username']; ?></p>
             </div>
             <a class="dropdown-item text-danger" href="logout.php">Đăng xuất</a>
         </div>
     </div>
   </nav>

   <div class="container product-details ">
      <div class="row">
         <div class="col-md-6">
            <div class="bla">
               <img src="../assets/images/<?php echo $product['image']; ?>" alt="<?php echo $product['product_name']; ?>" class="product-image">
            </div>
         </div>
         <div class="col-md-6">
            <h1>Tên sản phẩm : <?php echo $product['product_name']; ?></h1>
            <p class="lead">Giá: <strong><?php echo number_format($product['price']); ?> VND</strong></p>
            <p>Số lượng còn: <?php echo $product['description']; ?></p>
            <form action="add_to_cart.php" method="POST">
               <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
               <button type="submit" class="btn btn-success" style="white-space: nowrap;">Thêm vào giỏ hàng</button>
            </form>
         </div>
      </div>
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

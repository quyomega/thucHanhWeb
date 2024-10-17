<?php
include 'includes/db_connect.php';
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
</head>
<body>
   <h1><?php echo $product['product_name']; ?></h1>
   <img src="assets/images/<?php echo $product['image']; ?>" alt="<?php echo $product['product_name']; ?>">
   <p>Giá: <?php echo number_format($product['price']); ?> VND</p>
   <p>Mô tả: <?php echo $product['description']; ?></p>
</body>
</html>

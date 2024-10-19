<?php
session_start();
include '../includes/db_connect.php';
if (isset($_POST['product_id'])) {
    $product_id = (int)$_POST['product_id'];
    $user_id = $_SESSION['user_id']; 
    $check_query = "SELECT * FROM cart_items WHERE user_id = $user_id AND product_id = $product_id";
    $check_result = mysqli_query($conn, $check_query);
    if (mysqli_num_rows($check_result) > 0) {
        $row = mysqli_fetch_assoc($check_result);
        $new_quantity = $row['quantity'] + 1;
        $description_check_query = "SELECT description FROM products WHERE id = $product_id"; 
        $description_check_result = mysqli_query($conn, $description_check_query);
        $description_row = mysqli_fetch_assoc($description_check_result);
        if ($description_row['description'] > 0) { 
            $update_query = "UPDATE cart_items SET quantity = $new_quantity WHERE user_id = $user_id AND product_id = $product_id";
            mysqli_query($conn, $update_query);
            echo json_encode(['success' => true, 'message' => 'Sản phẩm đã được thêm vào giỏ hàng.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Sản phẩm đã hết hàng.']);
        }
    } else {
        $description_check_query = "SELECT description FROM products WHERE id = $product_id"; 
        $description_check_result = mysqli_query($conn, $description_check_query);
        $description_row = mysqli_fetch_assoc($description_check_result);
        
        if ($description_row['description'] > 0) { 
            $insert_query = "INSERT INTO cart_items (user_id, product_id, quantity) VALUES ($user_id, $product_id, 1)";
            mysqli_query($conn, $insert_query);
            echo json_encode(['success' => true, 'message' => 'Sản phẩm đã được thêm vào giỏ hàng.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Sản phẩm đã hết hàng.']);
        }
    }
    $cart_count_query = "SELECT SUM(quantity) as total_quantity FROM cart_items WHERE user_id = $user_id";
    $cart_count_result = mysqli_query($conn, $cart_count_query);
    $cart_count_row = mysqli_fetch_assoc($cart_count_result);
    $_SESSION['cart_count'] = $cart_count_row['total_quantity'];
} else {
    echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra.']);
}
?>

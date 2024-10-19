<?php
session_start();
include '../includes/db_connect.php';

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    $cart_query = "SELECT product_id, quantity FROM cart_items WHERE user_id = $user_id";
    $cart_result = mysqli_query($conn, $cart_query);

    while ($cart_row = mysqli_fetch_assoc($cart_result)) {
        $product_id = $cart_row['product_id'];
        $quantity = $cart_row['quantity'];

        $stock_query = "SELECT description FROM products WHERE id = $product_id"; 
        $stock_result = mysqli_query($conn, $stock_query);
        $stock_row = mysqli_fetch_assoc($stock_result);

        if ($stock_row['description'] <= 0) {
            $delete_query = "DELETE FROM cart_items WHERE user_id = $user_id AND product_id = $product_id";
            mysqli_query($conn, $delete_query);
        }
    }
}

// Đáp ứng JSON nếu cần thiết
echo json_encode(['success' => true]);
?>

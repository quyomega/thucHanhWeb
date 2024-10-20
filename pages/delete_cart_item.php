<?php
include '../includes/db_connect.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php"); 
    exit();
}

if (isset($_GET['product_id'])) {
    $user_id = $_SESSION['user_id'];
    $product_id = intval($_GET['product_id']);

    // Xóa sản phẩm khỏi giỏ hàng
    $delete_query = "DELETE FROM cart_items WHERE user_id = ? AND product_id = ?";
    $delete_stmt = mysqli_prepare($conn, $delete_query);
    mysqli_stmt_bind_param($delete_stmt, "ii", $user_id, $product_id);
    mysqli_stmt_execute($delete_stmt);

    // Chuyển hướng về giỏ hàng
    header("Location: cart.php");
    exit();
}
?>

<?php
include '../includes/db_connect.php'; 
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_name = $_POST['product_name'];
    $product_code = $_POST['product_code'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $description = $_POST['description'];

    $target_dir = "../assets/images/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check === false) {
        echo "Tệp không phải là ảnh.";
        $uploadOk = 0;
    }

    if (file_exists($target_file)) {
        echo "Xin lỗi, tệp đã tồn tại.";
        $uploadOk = 0;
    }

    if ($_FILES["image"]["size"] > 500000) {
        echo "Xin lỗi, tệp quá lớn.";
        $uploadOk = 0;
    }

    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "Xin lỗi, chỉ cho phép tệp JPG, JPEG, PNG & GIF.";
        $uploadOk = 0;
    }

    if ($uploadOk == 0) {
        echo "Xin lỗi, không thể tải tệp lên.";
    } else {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $query = "INSERT INTO products (product_name, product_code, price, category, image, description) 
                      VALUES ('$product_name', '$product_code', '$price', '$category', '" . basename($_FILES["image"]["name"]) . "', '$description')";
            
            if (mysqli_query($conn, $query)) {
                echo "Sản phẩm đã được thêm thành công.";
                header("Location: admin_index.php");
                exit();
            } else {
                echo "Lỗi: " . mysqli_error($conn);
            }
        } else {
            echo "Xin lỗi, đã xảy ra lỗi khi tải tệp lên.";
        }
    }
}
?>

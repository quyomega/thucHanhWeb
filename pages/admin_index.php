<?php
include '../includes/db_connect.php'; // Kiểm tra đường dẫn

// Lấy thông tin người dùng đã đăng nhập
session_start();
if (!isset($_SESSION['username'])) {
    echo "Chưa đăng nhập.";
    exit;
}

$username = $_SESSION['username'];
$user_query = "SELECT * FROM users WHERE username = '$username'";
$user_result = mysqli_query($conn, $user_query);
$user_info = mysqli_fetch_assoc($user_result);

if (!$user_result) {
    echo "Lỗi truy vấn: " . mysqli_error($conn);
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang quản lý</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .sidebar {
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            background-color: #f8f9fa;
            padding: 15px;
        }
        .content {
            margin-left: 260px;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h3>Nhóm 13</h3>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="#" id="userInfoLink">Thông tin người dùng</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" id="userManagementLink">Quản lý tài khoản</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" id="productManagementLink">Quản lý sản phẩm</a>
            </li>
        </ul>
    </div>

    <div class="content">
        <h2 class="text-center">ADMIN</h2>
        <div id="userInfo">
            <h2 class="text-center">Thông tin tài khoản</h2>
            <p>ID: <?php echo $user_info['id']; ?></p>
            <p>Tên người dùng: <?php echo $user_info['username']; ?></p>
            <p>Email: <?php echo $user_info['email']; ?></p>
            <p>Vai trò: <?php echo $user_info['role']; ?></p>
        </div>

        <div id="managementContent" style="display:none;">
            <div id="userManagement" style="display:none;">
                <h2 class="text-center">Thông tin tài khoản</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên người dùng</th>
                            <th>Email</th>
                            <th>Vai trò</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT * FROM users";
                        $result = mysqli_query($conn, $query);
                        while($user = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?php echo $user['id']; ?></td>
                                <td><?php echo $user['username']; ?></td>
                                <td><?php echo $user['email']; ?></td>
                                <td><?php echo $user['role']; ?></td>
                                <td>
                                    <button class="btn btn-warning" data-toggle="modal" data-target="#editUserModal<?php echo $user['id']; ?>">Chỉnh sửa</button>
                                </td>
                            </tr>

                            <!-- Modal chỉnh sửa người dùng -->
                            <div class="modal fade" id="editUserModal<?php echo $user['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editUserModalLabel">Chỉnh sửa người dùng</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form method="POST" action="update_user.php">
                                            <div class="modal-body">
                                                <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                                                <div class="form-group">
                                                    <label for="username">Tên người dùng:</label>
                                                    <input type="text" class="form-control" name="username" value="<?php echo $user['username']; ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="email">Email:</label>
                                                    <input type="email" class="form-control" name="email" value="<?php echo $user['email']; ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="role">Vai trò:</label>
                                                    <select class="form-control" name="role">
                                                        <option value="user" <?php if ($user['role'] == 'user') echo 'selected'; ?>>User</option>
                                                        <option value="admin" <?php if ($user['role'] == 'admin') echo 'selected'; ?>>Admin</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                                                <button type="submit" class="btn btn-primary">Cập nhật</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <div id="productManagement" style="display:none;">
                <h2 class="text-center">Quản lý sản phẩm</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên sản phẩm</th>
                            <th>Mã sản phẩm</th>
                            <th>Giá</th>
                            <th>Danh mục</th>
                            <th>Ảnh</th>
                            <th>Mô tả</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $product_query = "SELECT * FROM products";
                        $product_result = mysqli_query($conn, $product_query);
                        while($product = mysqli_fetch_assoc($product_result)): ?>
                            <tr>
                                <td><?php echo $product['id']; ?></td>
                                <td><?php echo $product['product_name']; ?></td>
                                <td><?php echo $product['product_code']; ?></td>
                                <td><?php echo number_format($product['price'], 2); ?> VND</td>
                                <td><?php echo $product['category']; ?></td>
                                <td><img src="../assets/images/<?php echo $product['image']; ?>" alt="<?php echo $product['product_name']; ?>" width="50"></td>
                                <td><?php echo $product['description']; ?></td>
                                <td>
                                    <button class="btn btn-warning" data-toggle="modal" data-target="#editProductModal<?php echo $product['id']; ?>">Chỉnh sửa</button>
                                </td>
                            </tr>

                            <!-- Modal chỉnh sửa sản phẩm -->
                            <div class="modal fade" id="editProductModal<?php echo $product['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editProductModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editProductModalLabel">Chỉnh sửa sản phẩm</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form method="POST" action="update_product.php">
                                            <div class="modal-body">
                                                <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                                                <div class="form-group">
                                                    <label for="product_name">Tên sản phẩm:</label>
                                                    <input type="text" class="form-control" name="product_name" value="<?php echo $product['product_name']; ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="product_code">Mã sản phẩm:</label>
                                                    <input type="text" class="form-control" name="product_code" value="<?php echo $product['product_code']; ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="price">Giá:</label>
                                                    <input type="number" class="form-control" name="price" value="<?php echo $product['price']; ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="category">Danh mục:</label>
                                                    <input type="text" class="form-control" name="category" value="<?php echo $product['category']; ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="description">Mô tả:</label>
                                                    <textarea class="form-control" name="description" rows="3" required><?php echo $product['description']; ?></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                                                <button type="submit" class="btn btn-primary">Cập nhật</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#userInfoLink').click(function() {
                $('#managementContent').hide();
                $('#userInfo').show();
            });

            $('#userManagementLink').click(function() {
                $('#userInfo').hide();
                $('#managementContent').show();
                $('#userManagement').show();
                $('#productManagement').hide();
            });

            $('#productManagementLink').click(function() {
                $('#userInfo').hide();
                $('#managementContent').show();
                $('#productManagement').show();
                $('#userManagement').hide();
            });
        });
    </script>
</body>
</html>

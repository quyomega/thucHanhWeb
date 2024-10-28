<?php
include '../includes/db_connect.php'; 
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); 
    exit();
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
    <link rel="stylesheet" href="../assets/css/admin_index.css">
</head>
<body>
    <div class="sidebar">
        <h3>Menu</h3>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="#" id="userInfoLink">Thông tin tài khoản</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" id="userManagementLink">Quản lý tài khoản</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" id="productManagementLink">Quản lý sản phẩm</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" id="testLink">Quản lý hóa đơn</a>
            </li>
        </ul>
    </div>
    <div class="content">
        <h2 class="text-center">Nhóm 13</h2>
        <div style="position: absolute; top: 10px; right: 20px;">
            <a href="index.php" class="btn btn-danger">Đăng xuất</a>
        </div>
        <div id="userInfo">
            <h2 class="text-center">Thông tin tài khoản</h2>
            <div class="info-row">
                <b>ID</b>
                <span id="cham">:</span>
                <span><?php echo $user_info['id']; ?></span>
            </div>
            <div class="info-row">
                <b>Tên đăng nhập</b>
                <span id="cham">:</span>
                <span><?php echo $user_info['username']; ?></span>
            </div>
            <div class="info-row">
                <b>Email</b>
                <span id="cham">:</span>
                <span><?php echo $user_info['email']; ?></span>
            </div>
            <div class="info-row">
                <b>Vai trò</b>
                <span id="cham">:</span>
                <span><?php echo $user_info['role']; ?></span>
            </div>
        </div>
        <div id="managementContent" style="display:none;">
            <div id="userManagement" style="display:none;">
                <h2 class="text-center">Quản lý tài khoản</h2>
                <button class="btn btn-success mb-3" data-toggle="modal" data-target="#addUserModal">Thêm người dùng</button>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th style="text-align: center; white-space: nowrap;">Tài khoản</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th style="white-space: nowrap;">Vai trò</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT * FROM users";
                        $result = mysqli_query($conn, $query);
                        while ($user = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?php echo $user['id']; ?></td>
                                <td style="text-align: center;"><?php echo $user['username']; ?></td>
                                <td><?php echo $user['email']; ?></td>
                                <td><?php echo $user['phone']; ?></td>
                                <td style="white-space: nowrap;"><?php echo $user['address']; ?></td>
                                <td><?php echo $user['role']; ?></td>
                                <td style="white-space: nowrap;">
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
                                                    <label for="username">Tên đăng nhập:</label>
                                                    <input type="text" class="form-control" name="username" value="<?php echo $user['username']; ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="email">Email:</label>
                                                    <input type="email" class="form-control" name="email" value="<?php echo $user['email']; ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="phone">Số điện thoại:</label>
                                                    <input type="text" class="form-control" name="phone" value="<?php echo $user['phone']; ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="address">Địa chỉ:</label>
                                                    <input type="address" class="form-control" name="address" value="<?php echo $user['address']; ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="role">Vai trò:</label>
                                                    <select class="form-control" name="role">
                                                        <option value="user" <?php if ($user['role'] == 'user') echo 'selected'; ?>>User</option>
                                                        <option value="admin" <?php if ($user['role'] == 'admin') echo 'selected'; ?>>Admin</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer d-flex justify-content-between align-items-center">
                                                <button type="button" class="btnXoa" data-toggle="modal" data-target="#deleteUserModal<?php echo $user['id']; ?>">Xóa</button>
                                                <div>
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                                                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal xác nhận xóa người dùng -->
                            <div class="modal fade" id="deleteUserModal<?php echo $user['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteUserModalLabel">Xác nhận xóa</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            Bạn có chắc chắn muốn xóa người dùng <?php echo $user['username']; ?> không?
                                        </div>
                                        <div class="modal-footer">
                                            <form method="POST" action="delete_user.php">
                                                <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                                                <button type="submit" class="btnXoa">Xóa</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Modal chỉnh sửa người dùng -->
                            <div class="modal fade" id="editUserModal<?php echo $user['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true" style="opacity: 0.5;">
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
                                                    <label for="username">Tên đăng nhập:</label>
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
                                            <div class="modal-footer d-flex justify-content-between align-items-center">
                                                <button type="button" class="btnXoa" data-toggle="modal" data-target="#deleteUserModal<?php echo $user['id']; ?>">Xóa</button>
                                                <div>
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                                                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <!-- Modal thêm người dùng -->
                <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addUserModalLabel">Thêm người dùng</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form method="POST" action="add_user.php">
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="username">Tên đăng nhập:</label>
                                        <input type="text" class="form-control" name="username" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Mật khẩu:</label>
                                        <input type="text" class="form-control" name="password" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email:</label>
                                        <input type="email" class="form-control" name="email" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="role">Vai trò:</label>
                                        <select class="form-control" name="role">
                                            <option value="user">User</option>
                                            <option value="admin">Admin</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                                    <button type="submit" class="btn btn-primary">Thêm</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div id="productManagement" style="display:none;">
                <h2 class="text-center">Quản lý sản phẩm</h2>
                <!-- Nút Thêm sản phẩm -->
                <button class="btn btn-success mb-3" data-toggle="modal" data-target="#addProductModal">Thêm sản phẩm</button> 
                <table class="table">
                    <thead>
                        <tr style="white-space: nowrap;">
                            <th>ID</th>
                            <th>Tên sản phẩm</th>
                            <th>Mã sản phẩm</th>
                            <th>Giá</th>
                            <th>Danh mục</th>
                            <th>Ảnh</th>
                            <th>Số lượng</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $product_query = "SELECT * FROM products";
                        $product_result = mysqli_query($conn, $product_query);
                        while($product = mysqli_fetch_assoc($product_result)): ?>
                            <tr  style="white-space: nowrap;">
                                <td><?php echo $product['id']; ?></td>
                                <td style="text-align: center;"><?php echo $product['product_name']; ?></td>
                                <td style="text-align: center;"><?php echo $product['product_code']; ?></td>
                                <td><?php echo number_format($product['price'], 2); ?> VND</td>
                                <td style="text-align: center;"><?php echo $product['category']; ?></td>
                                <td><img src="../assets/images/<?php echo $product['image']; ?>" alt="<?php echo $product['product_name']; ?>" width="50"></td>
                                <td style="text-align: center;"><?php echo $product['description']; ?></td>
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
                                    <form method="POST" action="update_product.php" enctype="multipart/form-data">
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
                                                <label for="description">Số lượng:</label>
                                                <textarea class="form-control" name="description" rows="3" required><?php echo $product['description']; ?></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="image">Ảnh sản phẩm:</label>
                                                <input type="file" class="form-control" name="image">
                                                <small class="form-text text-muted">Để lại trống nếu không muốn thay đổi ảnh.</small>
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

                            <!-- Modal thêm sản phẩm -->
                            <div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="addProductModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="addProductModalLabel">Thêm sản phẩm</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form method="POST" action="add_product.php" enctype="multipart/form-data">
                                            <div class="form-group">
                                                <label for="product_name">Tên sản phẩm:</label>
                                                <input type="text" class="form-control" name="product_name" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="product_code">Mã sản phẩm:</label>
                                                <input type="text" class="form-control" name="product_code">
                                            </div>
                                            <div class="form-group">
                                                <label for="price">Giá:</label>
                                                <input type="number" class="form-control" name="price">
                                            </div>
                                            <div class="form-group">
                                                <label for="category">Danh mục:</label>
                                                <input type="text" class="form-control" name="category">
                                            </div>
                                            <div class="form-group">
                                                <label for="description">Số lượng:</label>
                                                <textarea class="form-control" name="description" rows="3"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="image">Ảnh sản phẩm:</label>
                                                <input type="file" class="form-control" name="image" required>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Thêm sản phẩm</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <div id="test" style="display:none;">
                <div>
                    <p>bla</p>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#userInfoLink').click(function() {
                $('#userInfo').show();
                $('#managementContent').hide();
            });
            $('#userManagementLink').click(function() {
                $('#userInfo').hide();
                $('#managementContent').show();
                $('#userManagement').show();
                $('#productManagement').hide();
                $('#test').hide();
            });
            $('#productManagementLink').click(function() {
                $('#userInfo').hide();
                $('#managementContent').show();
                $('#userManagement').hide();
                $('#productManagement').show();
                $('#test').hide();
            });
            $('#testLink').click(function() {
                $('#userInfo').hide();
                $('#managementContent').show();
                $('#userManagement').hide();
                $('#productManagement').hide();
                $('#test').show();
            });
        });
        $(document).ready(function() {
    $('.nav-item').click(function() {
        $('.nav-item').removeClass('active'); // Xóa lớp active khỏi tất cả các mục
        $(this).addClass('active'); // Thêm lớp active cho mục được nhấp
    });
});
    </script>
</body>
</html>

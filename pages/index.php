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
if (!$total_result) {
    die('Lỗi truy vấn tổng sản phẩm: ' . mysqli_error($conn));
}
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
if (!$result) {
    die('Lỗi truy vấn danh sách sản phẩm: ' . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .sidebar {
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            padding: 20px; /* Thêm padding cho menu */
            border-right: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            background-color: #f8f9fa; /* Màu nền sáng cho menu */
        }

        .sidebar .nav-link {
            font-size: 16px; /* Kích thước font chữ */
            color: #333; /* Màu chữ tối */
            transition: background-color 0.3s; /* Hiệu ứng chuyển màu nền */
        }

        .sidebar .nav-link:hover {
            background-color: #007bff; /* Màu nền khi hover */
            color: white; /* Màu chữ khi hover */
            border-radius: 5px; /* Bo tròn góc */
        }

        .sidebar .nav-link.active {
            font-weight: bold; /* Làm đậm chữ cho mục đang chọn */
            color: white; /* Màu chữ cho mục đang chọn */
            background-color: #007bff; /* Màu nền cho mục đang chọn */
        }

        .sidebar ul {
            list-style-type: none; /* Bỏ dấu chấm */
            padding-left: 0; /* Bỏ lề bên trái */
        }

        .sidebar .nav-item + .nav-item {
            margin-top: 10px; /* Khoảng cách giữa các mục menu */
        }
    </style>
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
                    <form id="searchForm" class="form-inline my-2 my-lg-0 ml-auto" method="GET" action="index.php" oninput="autoSearch()">
                        <input class="form-control mr-2" type="search" name="search_term" placeholder="Tìm kiếm sản phẩm" value="<?php echo htmlspecialchars($search_term); ?>" aria-label="Search">
                        <input type="hidden" name="page" value="1"> 
                        <button class="btn btn-outline-success my-2 my-sm-0" type="submit" style="display: none;">Tìm kiếm</button>
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
                        <a class="nav-link">Sắp xếp theo giá</a>
                        <ul style="list-style-type: none; padding-left: 20px;">
                            <li>
                                <a href="?sort=ASC&search_term=<?php echo urlencode($search_term); ?>&best_selling=<?php echo $is_best_selling ? 'true' : 'false'; ?>&new_arrivals=<?php echo $is_new_arrivals ? 'true' : 'false'; ?>" class="nav-link">
                                    <span>&#9650;</span> Tăng dần
                                </a>
                            </li>
                            <li>
                                <a href="?sort=DESC&search_term=<?php echo urlencode($search_term); ?>&best_selling=<?php echo $is_best_selling ? 'true' : 'false'; ?>&new_arrivals=<?php echo $is_new_arrivals ? 'true' : 'false'; ?>" class="nav-link">
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
                                        <h5 class="card-title"><?php echo $row['product_name']; ?></h5>
                                        <p class="card-text">Giá: <?php echo number_format($row['price'], 0, ',', '.'); ?> VNĐ</p>
                                    </div>
                                    <div class="card-footer">
                                        <a href="login.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">Xem chi tiết</a>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                    <nav aria-label="Page navigation">
                        <ul class="pagination">
                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item <?php if ($i === $page) echo 'active'; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?>&sort=<?php echo $sort_order; ?>&search_term=<?php echo urlencode($search_term); ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // Tự động tìm kiếm
        function autoSearch() {
            const searchInput = document.querySelector('input[name="search_term"]');
            const searchButton = document.querySelector('button[type="submit"]');
            searchButton.style.display = searchInput.value ? 'block' : 'none';
        }
    </script>
</body>
</html>

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th12 25, 2024 lúc 02:58 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `tuixachshop`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cart_items`
--

CREATE TABLE `cart_items` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `cart_items`
--

INSERT INTO `cart_items` (`id`, `user_id`, `product_id`, `quantity`, `created_at`) VALUES
(116, 2, 2, 2, '2024-10-31 07:24:18'),
(118, 2, 3, 1, '2024-11-04 03:56:24'),
(128, 1, 6, 1, '2024-11-23 06:28:08');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `contacts`
--

INSERT INTO `contacts` (`id`, `name`, `email`, `phone`, `message`, `created_at`) VALUES
(1, '1', '1400sad@belgianairways.com', '1', '1', '2024-10-28 02:15:13'),
(2, 'Quý', 'ducthang@gmail.com', '0923427836482', 'không thể tạo tài khoản', '2024-11-04 04:04:37'),
(3, 'Chi cục Thuế Thành Phố Hà Nội 1', 'quyomega@gmail.com', '0912345678', '             ', '2024-11-23 07:50:34'),
(4, 'hh', 'ha@gmail.com', '0912345678', 'zx ', '2024-11-23 08:07:52'),
(5, 'x', 'carey@belgianairways.com', '1234567890', '           ', '2024-11-23 08:11:58'),
(6, 'hh', 'ha@gmail.com', '0912345678', ' ', '2024-11-23 08:12:48');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `product_id`, `quantity`, `total_amount`, `created_at`) VALUES
(36, 1, 2, 1, 2.00, '2024-10-20 01:11:15'),
(37, 1, 4, 1, 4.00, '2024-10-20 01:11:37'),
(38, 1, 5, 2, 10.00, '2024-10-20 01:11:37'),
(53, 1, 3, 1, 3.00, '2024-10-28 04:15:51'),
(54, 2, 3, 1, 3.00, '2024-10-28 04:16:12'),
(55, 1, 2, 2, 4.00, '2024-11-06 05:16:22'),
(56, 1, 1, 1, 1.00, '2024-11-06 05:16:22'),
(57, 1, 5, 1, 5.00, '2024-11-23 06:21:56'),
(58, 1, 3, 2, 6.00, '2024-11-23 06:21:56'),
(59, 1, 6, 1, 6.00, '2024-11-23 06:23:21'),
(60, 1, 14, 1, 14.00, '2024-11-23 06:24:39'),
(61, 1, 4, 1, 4.00, '2024-11-23 06:26:40'),
(62, 1, 3, 1, 3.00, '2024-11-23 06:28:01'),
(63, 47, 30, 1, 30000.00, '2024-11-23 08:10:06'),
(64, 47, 8, 1, 80000.00, '2024-11-23 08:10:06'),
(65, 47, 8, 1, 80000.00, '2024-11-23 08:17:15');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_code` varchar(50) DEFAULT NULL,
  `price` int(10) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`id`, `product_name`, `product_code`, `price`, `category`, `image`, `description`) VALUES
(1, 'Túi xách dior', '1', 1000000, 'Túi sách loại A', '1.jpeg', '1'),
(2, 'Túi MVP', '2', 2000000, 'Túi sách loại A', '2.jpeg', '2'),
(3, 'Túi đeo chéo', '3', 300000, 'Túi sách loại A', '3.jpeg', '0'),
(4, 'Túi đen', '4', 400000, 'Túi sách loại A', '4.jpeg', '3'),
(5, 'Túi vắt vai', '5', 5000000, 'Túi sách loại A', '5.jpeg', '4'),
(6, 'Túi dior', '6', 60000000, 'Túi sách loại B', '6.jpeg', '5'),
(7, 'Túi bla', '7', 700000, 'Túi sách loại B', '7.jpeg', '7'),
(8, 'Túi treo', '8', 80000, 'Túi sách loại B', '8.jpeg', '6'),
(9, 'Túi khóc', '9', 900000, 'Túi sách loại B', '9.jpeg', '9'),
(10, 'Túi giảng viên', '10', 100000, 'Túi sách loại B', '10.jpeg', '10'),
(11, 'Túi thối nát', '11', 1100000, 'Túi sách loại C', '11.jpeg', '11'),
(12, 'Túi màu bạc', '12', 120000, 'Túi sách loại C', '12.jpeg', '12'),
(13, 'Túi màu đen', '13', 1300000, 'Túi sách loại C', '13.jpeg', '13'),
(14, 'Túi màu đỏ', '14', 1400000, 'Túi sách loại C', '14.jpeg', '13'),
(15, 'Túi màu xanh', '15', 1500000, 'Túi sách loại C', '15.jpeg', '15'),
(16, 'Túi không', '16', 160000, 'Túi sách loại D', '16.jpeg', '16'),
(17, 'Túi xách mèo', '17', 170000, 'Túi sách loại D', '17.jpeg', '17'),
(18, 'Túi mù', '18', 188776, 'Túi sách loại D', '18.jpeg', '18'),
(19, 'Túi túi', '19', 190000, 'Túi sách loại D', '19.jpeg', '19'),
(20, 'Túi mà', '20', 200000, 'Túi sách loại D', '20.jpeg', '20'),
(21, 'túi chó', '21', 2111100, 'Túi sách loại E', '21.jpeg', '21'),
(22, 'Túi đẳng cấp', '22', 222340, 'Túi sách loại E', '22.jpeg', '22'),
(23, 'Túi môn xanh', '23', 2311100, 'Túi sách loại E', '23.jpeg', '23'),
(24, 'Túi môn mon', '24', 2444400, 'Túi sách loại E', '24.jpeg', '24'),
(25, 'Túi la', '25', 253333, 'Túi sách loại E', '25.jpeg', '25'),
(26, 'Túi thức', '26', 2632432, 'Túi sách loại F', '26.jpeg', '26'),
(27, 'Túi đẳng cấp', '27', 270000, 'Túi sách loại F', '27.jpeg', '27'),
(28, 'Túi màu xanh', '28', 282342, 'Túi sách loại F', '28.jpeg', '28'),
(29, 'Túi lợi', '29', 294444, 'Túi sách loại F', '29.jpeg', '29'),
(30, 'Túi răng', '30', 30000, 'Túi sách loại F', '30.jpeg', '29'),
(31, 'Túi treo gió', '31', 3199900, 'Túi sách loại F', '31.jpeg', '31'),
(32, 'Túi bà ba', '32', 3223423, 'Túi sách loại F', '32.jpeg', '32'),
(33, 'Túi cặp', '33', 330000, 'Túi sách loại F', '33.jpeg', '33'),
(40, 'Túi không', '40', 100000, 'Xyz', '30.jpeg', '4');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `phone`, `address`, `password`, `role`, `token`) VALUES
(0, 'z', 'quyomegar@gmail.com', '0965017695', 'Thái Bình', '$2y$10$ZVpT1A4QFqowaCyX.6te3.hjqlvVl/XR/QWhk60hDACS0C7x8DIhG', 'admin', '0d47bd43d3e05b5f01f8c3be3055cfcba32909421c9f79b695a6aca38da522e0ec15c6205dfd0cd2a9d36e3ad5b4996b245a'),
(1, 'x', 'carey@belgianairways.com', '1234567890', 'Cửa Lò', '$2y$10$t4Xqk/M2KaSMUtU5IIpVce6NxwBbfRTMab90AfuGslkh6w6ko52Ia', 'user', NULL),
(2, 'c', 'hanglam1209@gmail.com', '999999999', 'Yên Bái', '$2y$10$rwi4.0024.qWzMk2xn7rlOTPDDnyEe13J8iot0nYf7G4I1zd75iqW', 'user', NULL),
(37, 'v', 'quyomega@gmail.com', '0987456321', 'Hà Nội', '$2y$10$zcr6foNX2O65qmIhaKNjauj.tq21XYgmvjc7ZXft1G/hwCEKhMmSq', 'user', 'cdc4ca055b4147355a95572f92411586b1c540058a304076475f9456c8b4f43b0858153693e7bbf5099c6abbc9bb6a8750e5'),
(38, 'test1', 'test1@gmail.com', '0965017699', 'Vĩnh Bảo', '$2y$10$BhG7v.GAyCE/7slPK4cXYuolYknYE9kchHC9hO117zbo7Vi.5SlXu', 'user', NULL),
(47, 'hh', 'ha@gmail.com', '0912345678', 'hh', '$2y$10$ts8Cu/FHfLMNEIeENA3UAO.t.4amiHvEoP/MshJWhrUHZ3vpoWiwi', 'user', NULL);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=133;

--
-- AUTO_INCREMENT cho bảng `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Máy chủ: localhost:3306
-- Thời gian đã tạo: Th2 24, 2026 lúc 10:28 AM
-- Phiên bản máy phục vụ: 10.5.29-MariaDB-log
-- Phiên bản PHP: 8.4.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `qymxlvghhosting_ebroker_db`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category` varchar(255) NOT NULL,
  `parameter_types` varchar(255) NOT NULL,
  `image` text NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0:DeActive 1:Active',
  `sequence` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`id`, `category`, `parameter_types`, `image`, `status`, `sequence`, `created_at`, `updated_at`, `order`) VALUES
(1, 'Đất ở', '10,12,13,14,15,16,17', '1693477169.7339.svg', 1, 0, '2023-08-31 03:19:29', '2024-03-11 01:43:16', 4),
(2, 'Khách sạn', '8,9,10,11,12,13,14,15,16,17,18,19', '1693477203.6729.svg', 1, 0, '2023-08-31 03:20:03', '2024-04-25 07:25:12', 2),
(3, 'Nhà phân quyền', '8,9,10,11,12,13,14,15,16,17,18,19', '1693477224.2269.svg', 1, 0, '2023-08-31 03:20:24', '2024-03-11 01:48:53', 9),
(4, 'Nhà giấy tay', '8,9,10,12,13,14,15,16,17,18,19', '1693477238.3592.svg', 1, 0, '2023-08-31 03:20:38', '2024-03-11 01:48:22', 7),
(5, 'Đất giấy tay', '10,12,13,14,15,16', '1693477256.5512.svg', 1, 0, '2023-08-31 03:20:56', '2024-03-11 01:44:21', 10),
(6, 'Nhà', '8,9,10,12,13,15,16,17,18,19', '1693477275.8085.svg', 1, 0, '2023-08-31 03:21:15', '2024-03-11 01:44:00', 1),
(7, 'Chung cư', '8,9,11,12,13,15,16', '1693477291.2127.svg', 1, 0, '2023-08-31 03:21:31', '2024-03-11 01:43:35', 6),
(8, 'Đất ở phân quyền', '9,10,12,13,14,15,16', '1693477308.8112.svg', 1, 0, '2023-08-31 03:21:48', '2024-03-11 01:39:32', 8),
(9, 'Biệt thự', '4,8,9,10,11,12,13,14,15,16,17,18,19', '1693477323.3042.svg', 1, 0, '2023-08-31 03:22:03', '2024-03-11 01:13:56', 3),
(11, 'Đất nông nghiệp', '10,12,13,14,15,16', '1693928775.0734.svg', 1, 0, '2023-09-05 08:46:15', '2024-03-11 01:12:10', 5);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

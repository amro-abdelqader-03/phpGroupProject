-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 02, 2026 at 06:43 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wedding_store`
--

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `country` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `street` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `building` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `full_name`, `email`, `password_hash`, `created_at`) VALUES
(1, 'Admin', 'admin@weddingstore.com', '$2y$10$GS9NyE3Wm8siGIfhQAlQxeC.dLH8VBekxDjWaPMAiv9N3Ok5mtbyW', '2026-10-30 20:09:03');

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `carts`
--

INSERT INTO `carts` (`id`, `user_id`, `created_at`, `updated_at`, `is_active`) VALUES
(26, 9001, '2026-11-01 07:40:50', '2026-11-01 07:40:50', 1),
(27, 9006, '2026-11-01 19:14:33', '2026-11-01 19:14:33', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` int(11) NOT NULL,
  `cart_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `variant_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart_items`
--

INSERT INTO `cart_items` (`id`, `cart_id`, `product_id`, `variant_id`, `quantity`, `added_at`) VALUES
(8, 26, 9, NULL, 1, '2026-11-01 07:40:50'),
(9, 26, 6, NULL, 1, '2026-11-01 07:41:11');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `status`) VALUES
(1, 'Dresses', 'Bridal, evening, party and formal dresses for the bride and guests.', 'active'),
(2, 'Accessories', 'Bridal accessories including veils, gloves, jewelry, tiaras and crowns.', 'active'),
(3, 'Men\'s Suits', 'Wedding and occasion suits and tuxedos for the groom and guests.', 'active'),
(4, 'Men\'s Shoes', 'Formal shoes to complete the groom and guests\' looks.', 'active'),
(5, 'Women\'s Heels', 'Bridal and occasion heels for the bride and guests.', 'active'),
(6, 'Men\'s Accessories', 'Bow ties, neckties and pocket squares for the groom and guests.', 'active'),
(7, 'Women\'s Shoes', 'Bridal sandals and flats for the bride and guests.', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `estimated_delivery` date DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `order_status` enum('Pending','Confirmed','Shipped','Delivered','Cancelled') DEFAULT 'Pending',
  `full_name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `country` varchar(100) NOT NULL DEFAULT 'Jordan',
  `city` varchar(100) NOT NULL,
  `area` varchar(100) DEFAULT NULL,
  `shipping_address` varchar(255) NOT NULL,
  `building_details` varchar(255) DEFAULT NULL,
  `shipping_cost` decimal(10,2) NOT NULL DEFAULT 0.00,
  `revenue` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `order_date`, `estimated_delivery`, `total_amount`, `order_status`, `full_name`, `phone`, `country`, `city`, `area`, `shipping_address`, `building_details`, `shipping_cost`, `revenue`) VALUES
(9001, 9001, '2026-01-15 07:00:00', NULL, 304.00, 'Delivered', 'Sara Ahmad', '0791111111', 'Jordan', 'Amman', 'Khalda', 'Khalda Street', 'Building 12', 5.00, 299.00),
(9002, 9002, '2026-02-18 08:00:00', NULL, 174.00, 'Delivered', 'Lina Khaled', '0792222222', 'Jordan', 'Irbid', 'University Street', 'University Street', 'Building 8', 5.00, 169.00),
(9003, 9003, '2026-03-22 09:00:00', NULL, 318.00, 'Delivered', 'Omar Ali', '0793333333', 'United States', 'New York', 'Manhattan', 'Broadway Street', 'Apartment 5', 0.00, 318.00),
(9004, 9004, '2026-04-14 10:00:00', NULL, 348.00, 'Delivered', 'Dana Mohammad', '0794444444', 'Germany', 'Berlin', 'Mitte', 'Berlin Street', 'Apartment 10', 0.00, 348.00),
(9005, 9005, '2026-05-20 11:00:00', NULL, 299.00, 'Delivered', 'Rama Hasan', '0795555555', 'United Kingdom', 'London', 'Central', 'Oxford Street', 'Apartment 3', 0.00, 299.00),
(9006, 9001, '2026-06-10 07:30:00', NULL, 207.00, 'Delivered', 'Sara Ahmad', '0791111111', 'Jordan', 'Amman', 'Sweifieh', 'Sweifieh Street', 'Building 7', 0.00, 207.00),
(9007, 9002, '2026-07-12 08:30:00', NULL, 318.00, 'Delivered', 'Lina Khaled', '0792222222', 'France', 'Paris', 'Central', 'Paris Street', 'Apartment 4', 0.00, 318.00),
(9008, 9003, '2026-08-16 09:30:00', NULL, 169.00, 'Delivered', 'Omar Ali', '0793333333', 'Canada', 'Toronto', 'Downtown', 'King Street', 'Apartment 9', 0.00, 169.00),
(9009, 9004, '2026-09-08 10:30:00', NULL, 598.00, 'Delivered', 'Dana Mohammad', '0794444444', 'Jordan', 'Amman', 'Abdoun', 'Abdoun Street', 'Building 15', 0.00, 598.00),
(9010, 9005, '2026-10-05 11:30:00', NULL, 188.00, 'Delivered', 'Rama Hasan', '0795555555', 'Germany', 'Munich', 'Central', 'Munich Street', 'Apartment 6', 0.00, 188.00),
(9011, 9001, '2026-10-12 07:00:00', NULL, 318.00, 'Confirmed', 'Sara Ahmad', '0791111111', 'United States', 'Chicago', 'Central', 'Lake Street', 'Apartment 2', 0.00, 318.00),
(9012, 9002, '2026-10-18 08:00:00', NULL, 299.00, 'Shipped', 'Lina Khaled', '0792222222', 'Jordan', 'Amman', 'Jubeiha', 'Jubeiha Street', 'Building 20', 0.00, 299.00),
(9013, 9003, '2026-10-21 09:00:00', NULL, 49.00, 'Pending', 'Omar Ali', '0793333333', 'United Kingdom', 'London', 'Central', 'Baker Street', 'Apartment 1', 0.00, 49.00),
(9014, 9004, '2026-10-24 10:00:00', NULL, 169.00, 'Cancelled', 'Dana Mohammad', '0794444444', 'France', 'Paris', 'Central', 'Paris Street', 'Apartment 11', 0.00, 0.00),
(9015, 9005, '2026-10-28 11:00:00', NULL, 337.00, 'Delivered', 'Rama Hasan', '0795555555', 'Jordan', 'Zarqa', 'New Zarqa', 'Main Street', 'Building 3', 0.00, 337.00),
(9016, 9006, '2026-11-01 19:22:42', '2026-11-06', 174.00, 'Pending', 'Amro Abdelgader', '42342423424', 'Jordan', 'amman', '42432', 'Street: dadde, Building: 121, Apartment: ewe', '', 5.00, 169.00),
(9017, 9006, '2026-11-01 22:11:21', '2026-11-06', 223.00, 'Pending', 'Amro Abdelgader', '3214243123', 'Jordan', 'amman', 'efsfsfesf', 'Street: dadde, Bldg: 121, Apt: fsfefs', 'fsfefsfe', 5.00, 98.00);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `variant_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `variant_id`, `quantity`, `unit_price`) VALUES
(1, 9001, 1, NULL, 1, 299.00),
(2, 9002, 6, NULL, 1, 169.00),
(3, 9003, 2, NULL, 1, 299.00),
(4, 9003, 12, NULL, 1, 19.00),
(5, 9004, 3, NULL, 1, 299.00),
(6, 9004, 22, NULL, 1, 49.00),
(7, 9005, 4, NULL, 1, 299.00),
(8, 9006, 6, NULL, 1, 169.00),
(9, 9006, 12, NULL, 2, 19.00),
(10, 9007, 2, NULL, 1, 299.00),
(11, 9007, 13, NULL, 1, 19.00),
(12, 9008, 7, NULL, 1, 169.00),
(13, 9009, 1, NULL, 2, 299.00),
(14, 9010, 6, NULL, 1, 169.00),
(15, 9010, 15, NULL, 1, 19.00),
(16, 9011, 2, NULL, 1, 299.00),
(17, 9011, 12, NULL, 1, 19.00),
(18, 9012, 5, NULL, 1, 299.00),
(19, 9013, 22, NULL, 1, 49.00),
(20, 9014, 8, NULL, 1, 169.00),
(21, 9015, 3, NULL, 1, 299.00),
(22, 9015, 14, NULL, 2, 19.00),
(23, 9016, 11, NULL, 1, 169.00),
(24, 9017, 11, 786, 1, 169.00),
(25, 9017, 22, 837, 1, 49.00);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `payment_method` enum('Cash on Delivery','Credit Card') NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_status` enum('Pending','Paid','Failed','Refunded') DEFAULT 'Pending',
  `transaction_reference` varchar(255) DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `order_id`, `payment_method`, `amount`, `payment_status`, `transaction_reference`, `paid_at`, `created_at`) VALUES
(1, 9001, 'Credit Card', 304.00, 'Paid', 'DEMO-PAY-9001', '2026-01-15 07:05:00', '2026-10-30 20:01:21'),
(2, 9002, 'Credit Card', 174.00, 'Paid', 'DEMO-PAY-9002', '2026-02-18 08:05:00', '2026-10-30 20:01:21'),
(3, 9003, 'Credit Card', 318.00, 'Paid', 'DEMO-PAY-9003', '2026-03-22 09:05:00', '2026-10-30 20:01:21'),
(4, 9004, 'Credit Card', 348.00, 'Paid', 'DEMO-PAY-9004', '2026-04-14 10:05:00', '2026-10-30 20:01:21'),
(5, 9005, 'Credit Card', 299.00, 'Paid', 'DEMO-PAY-9005', '2026-05-20 11:05:00', '2026-10-30 20:01:21'),
(6, 9006, 'Cash on Delivery', 207.00, 'Paid', 'DEMO-PAY-9006', '2026-06-10 07:35:00', '2026-10-30 20:01:21'),
(7, 9007, 'Credit Card', 318.00, 'Paid', 'DEMO-PAY-9007', '2026-07-12 08:35:00', '2026-10-30 20:01:21'),
(8, 9008, 'Credit Card', 169.00, 'Paid', 'DEMO-PAY-9008', '2026-08-16 09:35:00', '2026-10-30 20:01:21'),
(9, 9009, 'Credit Card', 598.00, 'Paid', 'DEMO-PAY-9009', '2026-09-08 10:35:00', '2026-10-30 20:01:21'),
(10, 9010, 'Cash on Delivery', 188.00, 'Paid', 'DEMO-PAY-9010', '2026-10-05 11:35:00', '2026-10-30 20:01:21'),
(11, 9011, 'Credit Card', 318.00, 'Pending', 'DEMO-PAY-9011', NULL, '2026-10-30 20:01:21'),
(12, 9012, 'Cash on Delivery', 299.00, 'Pending', 'DEMO-PAY-9012', NULL, '2026-10-30 20:01:21'),
(13, 9013, 'Cash on Delivery', 49.00, 'Pending', 'DEMO-PAY-9013', NULL, '2026-10-30 20:01:21'),
(14, 9014, 'Credit Card', 169.00, 'Failed', 'DEMO-PAY-9014', NULL, '2026-10-30 20:01:21'),
(15, 9015, 'Credit Card', 337.00, 'Paid', 'DEMO-PAY-9015', '2026-10-28 11:05:00', '2026-10-30 20:01:21'),
(16, 9016, 'Credit Card', 174.00, 'Pending', 'cs_test_b1UK1zHiJ78fFMX7AkT86ReFrMEN3IzA5OCcT3RkbdzQKvew0QdVYVvPwJ', NULL, '2026-11-01 19:22:42'),
(17, 9017, 'Cash on Delivery', 223.00, 'Pending', NULL, NULL, '2026-11-01 22:11:21');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `subcategory_id` int(11) NOT NULL,
  `product_name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `cost_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `price` decimal(10,2) NOT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `stock_quantity` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `subcategory_id`, `product_name`, `description`, `cost_price`, `price`, `image_url`, `stock_quantity`, `created_at`) VALUES
(1, 1, 'A-Line Bridal Dress 1', 'A Line bridal gown for weddings and formal occasions.', 180.00, 299.00, '/Data/women/dress/a-line/product1/Front.jpg', 5, '2026-10-29 12:25:42'),
(2, 2, 'Mermaid Bridal Dress 1', 'Mermaid bridal gown for weddings and formal occasions.', 180.00, 299.00, '/Data/women/dress/mermaid/product1/front.jpg', 5, '2026-10-29 12:25:42'),
(3, 3, 'Princess Bridal Dress 1', 'Princess bridal gown for weddings and formal occasions.', 180.00, 299.00, '/Data/women/dress/princessDress/product1/princess.jpg', 5, '2026-10-29 12:25:42'),
(4, 4, 'Royal Bridal Dress 1', 'Royal bridal gown for weddings and formal occasions.', 180.00, 299.00, '/Data/women/dress/royal/product1/front.webp', 5, '2026-10-29 12:25:42'),
(5, 5, 'Sheath Bridal Dress 1', 'Sheath bridal gown for weddings and formal occasions.', 180.00, 299.00, '/Data/women/dress/sheath/product1/front.jpg', 5, '2026-10-29 12:25:42'),
(6, 10, '3 Piece Suit Suit 01', '3 Piece Suit men\'s wedding suit for the groom and special occasions.', 95.00, 169.00, '/Data/men/suits/3%20piece%20suit/01/primary.webp', 8, '2026-10-29 12:25:42'),
(7, 10, '3 Piece Suit Suit 02', '3 Piece Suit men\'s wedding suit for the groom and special occasions.', 95.00, 169.00, '/Data/men/suits/3%20piece%20suit/02/primary.webp', 8, '2026-10-29 12:25:42'),
(8, 11, 'Double Breasted Suit Suit 01', 'Double Breasted Suit men\'s wedding suit for the groom and special occasions.', 95.00, 169.00, '/Data/men/suits/double%20breasted%20suit/01/primary.png', 8, '2026-10-29 12:25:42'),
(9, 12, 'Regular Fit Suit 01', 'Regular Fit men\'s wedding suit for the groom and special occasions.', 95.00, 169.00, '/Data/men/suits/regular%20fit/01/primary.png', 8, '2026-10-29 12:25:42'),
(10, 13, 'Slim Fit Suit 01', 'Slim Fit men\'s wedding suit for the groom and special occasions.', 95.00, 169.00, '/Data/men/suits/slim%20fit/01/primary.png', 8, '2026-10-29 12:25:42'),
(11, 14, 'Tuxedo Suit 01', 'Tuxedo men\'s wedding suit for the groom and special occasions.', 95.00, 169.00, '/Data/men/suits/tuxedo/01/primary.jpg', 8, '2026-10-29 12:25:42'),
(12, 21, 'Bow Ties Black', 'Bow Ties accessory for weddings and special occasions.', 8.00, 19.00, 'assets/images/accessories/suits/bow-ties/classic-black.jpg', 15, '2026-10-29 12:25:42'),
(13, 21, 'Bow Ties Blue', 'Bow Ties accessory for weddings and special occasions.', 8.00, 19.00, 'assets/images/accessories/suits/bow-ties/midnight.jpg', 15, '2026-10-29 12:25:42'),
(14, 21, 'Bow Ties Gray', 'Bow Ties accessory for weddings and special occasions.', 8.00, 19.00, 'assets/images/accessories/suits/bow-ties/ivory-ceremony.jpg', 15, '2026-10-29 12:25:42'),
(15, 21, 'Bow Ties Red', 'Bow Ties accessory for weddings and special occasions.', 8.00, 19.00, 'assets/images/accessories/suits/bow-ties/crimson.jpg', 15, '2026-10-29 12:25:42'),
(16, 22, 'Neck Ties Green Tie', 'Neck Ties accessory for weddings and special occasions.', 8.00, 19.00, 'assets/images/accessories/suits/neck-ties/striped.jpg', 15, '2026-10-29 12:25:42'),
(17, 22, 'Neck Ties 1', 'Neck Ties accessory for weddings and special occasions.', 8.00, 19.00, 'assets/images/accessories/suits/neck-ties/pokadot.jpg', 15, '2026-10-29 12:25:42'),
(18, 22, 'Neck Ties 2', 'Neck Ties accessory for weddings and special occasions.', 8.00, 19.00, 'assets/images/accessories/suits/neck-ties/tie1.jpg', 15, '2026-10-29 12:25:42'),
(19, 23, 'Pocket Squares 1', 'Pocket Squares accessory for weddings and special occasions.', 8.00, 19.00, 'assets/images/accessories/suits/pocket-squares/ivory-silk.jpg', 15, '2026-10-29 12:25:42'),
(20, 23, 'Pocket Squares 2', 'Pocket Squares accessory for weddings and special occasions.', 8.00, 19.00, 'assets/images/accessories/suits/pocket-squares/folded-silk.jpg', 15, '2026-10-29 12:25:42'),
(21, 23, 'Pocket Squares 3', 'Pocket Squares accessory for weddings and special occasions.', 8.00, 19.00, 'assets/images/accessories/suits/pocket-squares/floral-signature.jpg', 15, '2026-10-29 12:25:42'),
(22, 15, 'Derby 1', 'Derby footwear suitable for weddings and formal occasions.', 25.00, 49.00, 'assets/images/accessories/suits/shoes/derby-heritage.jpg', 12, '2026-10-29 12:25:42'),
(23, 15, 'Derby 2', 'Derby footwear suitable for weddings and formal occasions.', 25.00, 49.00, '/Data/men/shoes/derby/Screenshot%202026-10-29%20150144.png', 12, '2026-10-29 12:25:42'),
(24, 15, 'Derby 3', 'Derby footwear suitable for weddings and formal occasions.', 25.00, 49.00, '/Data/men/shoes/derby/Screenshot%202026-10-29%20150445.png', 12, '2026-10-29 12:25:42'),
(25, 16, 'Loafers 1', 'Loarfers footwear suitable for weddings and formal occasions.', 25.00, 49.00, 'assets/images/accessories/suits/shoes/cognac.jpg', 12, '2026-10-29 12:25:42'),
(26, 16, 'Loafers 2', 'Loarfers footwear suitable for weddings and formal occasions.', 25.00, 49.00, '/Data/men/shoes/loarfers/Screenshot%202026-10-29%20150617.png', 12, '2026-10-29 12:25:42'),
(27, 16, 'Loafers 3', 'Loarfers footwear suitable for weddings and formal occasions.', 25.00, 49.00, '/Data/men/shoes/loarfers/Screenshot%202026-10-29%20150623.png', 12, '2026-10-29 12:25:42'),
(28, 17, 'Monk Strap 1', 'Monk Strap footwear suitable for weddings and formal occasions.', 25.00, 49.00, 'assets/images/accessories/suits/shoes/monk3.jpg', 12, '2026-10-29 12:25:42'),
(29, 17, 'Monk Strap 2', 'Monk Strap footwear suitable for weddings and formal occasions.', 25.00, 49.00, '/Data/men/shoes/monk%20strap/Screenshot%202026-10-29%20150925.png', 12, '2026-10-29 12:25:42'),
(30, 17, 'Monk Strap 3', 'Monk Strap footwear suitable for weddings and formal occasions.', 25.00, 49.00, '/Data/men/shoes/monk%20strap/Screenshot%202026-10-29%20150936.png', 12, '2026-10-29 12:25:42'),
(31, 18, 'Oxford 1', 'Oxford footwear suitable for weddings and formal occasions.', 25.00, 49.00, 'assets/images/accessories/suits/shoes/oxford-black.jpg', 12, '2026-10-29 12:25:42'),
(32, 18, 'Oxford 2', 'Oxford footwear suitable for weddings and formal occasions.', 25.00, 49.00, '/Data/men/shoes/oxford/Screenshot%202026-10-29%20151025.png', 12, '2026-10-29 12:25:42'),
(33, 18, 'Oxford 3', 'Oxford footwear suitable for weddings and formal occasions.', 25.00, 49.00, '/Data/men/shoes/oxford/Screenshot%202026-10-29%20151033.png', 12, '2026-10-29 12:25:42'),
(34, 6, 'Gloves 1', 'Gloves accessory for weddings and special occasions.', 8.00, 19.00, 'assets/images/accessories/dresses/gloves/classic-satin.jpg', 15, '2026-10-29 12:25:42'),
(35, 6, 'Gloves 2', 'Gloves accessory for weddings and special occasions.', 8.00, 19.00, 'assets/images/accessories/dresses/gloves/evening-satin.jpg', 15, '2026-10-29 12:25:42'),
(36, 6, 'Gloves 3', 'Gloves accessory for weddings and special occasions.', 8.00, 19.00, 'assets/images/accessories/dresses/gloves/sheer-lace.jpg', 15, '2026-10-29 12:25:42'),
(37, 7, 'Jewelry 1', 'Jewlery accessory for weddings and special occasions.', 8.00, 19.00, 'assets/images/accessories/dresses/jewelry/gold-necklace.jpg', 15, '2026-10-29 12:25:42'),
(38, 7, 'Jewelry 2', 'Jewlery accessory for weddings and special occasions.', 8.00, 19.00, 'assets/images/accessories/dresses/jewelry/pearl-earrings.jpg', 15, '2026-10-29 12:25:42'),
(39, 7, 'Jewelry 3', 'Jewlery accessory for weddings and special occasions.', 8.00, 19.00, 'assets/images/accessories/dresses/jewelry/gold-bangle.jpg', 15, '2026-10-29 12:25:42'),
(40, 8, 'Tiaras And Crowns 1', 'Taras And Crown accessory for weddings and special occasions.', 8.00, 19.00, 'assets/images/accessories/dresses/tiaras-crowns/tiaras1.jpg', 15, '2026-10-29 12:25:42'),
(41, 8, 'Tiaras And Crowns 2', 'Taras And Crown accessory for weddings and special occasions.', 8.00, 19.00, 'assets/images/accessories/dresses/tiaras-crowns/tiaras2.jpg', 15, '2026-10-29 12:25:42'),
(42, 8, 'Tiaras And Crowns 3', 'Taras And Crown accessory for weddings and special occasions.', 8.00, 19.00, 'assets/images/accessories/dresses/tiaras-crowns/gold-tiara.jpg', 15, '2026-10-29 12:25:42'),
(43, 9, 'Veils 1', 'Veils accessory for weddings and special occasions.', 8.00, 19.00, 'assets/images/accessories/dresses/veils/veil.jpg', 15, '2026-10-29 12:25:42'),
(44, 9, 'Veils 2', 'Veils accessory for weddings and special occasions.', 8.00, 19.00, 'assets/images/accessories/dresses/veils/veil2.jpg', 15, '2026-10-29 12:25:42'),
(45, 9, 'Veils 3', 'Veils accessory for weddings and special occasions.', 8.00, 19.00, 'assets/images/accessories/dresses/veils/veil3.webp', 15, '2026-10-29 12:25:42'),
(46, 19, 'Bloch Heels 1', 'Bloch Heels footwear suitable for weddings and formal occasions.', 25.00, 49.00, 'assets/images/accessories/dresses/shoes/block-heel-marble.jpg', 12, '2026-10-29 12:25:42'),
(47, 19, 'Bloch Heels 2', 'Bloch Heels footwear suitable for weddings and formal occasions.', 25.00, 49.00, '/Data/women/shoes/bloch%20heels/Screenshot%202026-10-29%20145406.png', 12, '2026-10-29 12:25:42'),
(48, 19, 'Bloch Heels 3', 'Bloch Heels footwear suitable for weddings and formal occasions.', 25.00, 49.00, '/Data/women/shoes/bloch%20heels/Screenshot%202026-10-29%20145413.png', 12, '2026-10-29 12:25:42'),
(49, 24, 'Bridal Sandal 1', 'Bridal Sandal footwear suitable for weddings and formal occasions.', 25.00, 49.00, 'assets/images/accessories/dresses/shoes/silver-sandal.jpg', 12, '2026-10-29 12:25:42'),
(50, 24, 'Bridal Sandal 2', 'Bridal Sandal footwear suitable for weddings and formal occasions.', 25.00, 49.00, 'assets/images/accessories/dresses/shoes/beaded-mule.jpg', 12, '2026-10-29 12:25:42'),
(51, 24, 'Bridal Sandal 3', 'Bridal Sandal footwear suitable for weddings and formal occasions.', 25.00, 49.00, '/Data/women/shoes/bridal%20sandal/Screenshot%202026-10-29%20145546.png', 12, '2026-10-29 12:25:42'),
(52, 25, 'Flats 1', 'Flats footwear suitable for weddings and formal occasions.', 25.00, 49.00, '/Data/women/shoes/flats/Screenshot%202026-10-29%20145821.png', 12, '2026-10-29 12:25:42'),
(53, 25, 'Flats 2', 'Flats footwear suitable for weddings and formal occasions.', 25.00, 49.00, '/Data/women/shoes/flats/Screenshot%202026-10-29%20145855.png', 12, '2026-10-29 12:25:42'),
(54, 20, 'High Heels 1', 'High Heels footwear suitable for weddings and formal occasions.', 25.00, 49.00, 'assets/images/accessories/dresses/shoes/ribbon-bow-heel.jpg', 12, '2026-10-29 12:25:42'),
(55, 20, 'High Heels 2', 'High Heels footwear suitable for weddings and formal occasions.', 25.00, 49.00, '/Data/women/shoes/high%20heels/Screenshot%202026-10-29%20150010.png', 12, '2026-10-29 12:25:42'),
(57, 1, 'A-Line Bridal Dress 2', 'Elegant A-Line wedding dress.', 180.00, 299.00, '/Data/women/dress/a-line/a-line2/front.jpg', 5, '2026-10-31 19:54:45'),
(58, 1, 'A-Line Bridal Dress 3', 'Elegant A-Line wedding dress.', 180.00, 299.00, '/Data/women/dress/a-line/a-line3/front.jpg', 5, '2026-10-31 19:54:45'),
(59, 1, 'A-Line Bridal Dress 4', 'Elegant A-Line wedding dress.', 180.00, 299.00, '/Data/women/dress/a-line/a-line4/front.webp', 5, '2026-10-31 19:54:45'),
(60, 2, 'Mermaid Bridal Dress 2', 'Elegant Mermaid wedding dress.', 180.00, 299.00, '/Data/women/dress/mermaid/product2/front.jpg', 5, '2026-10-31 19:54:45'),
(61, 2, 'Mermaid Bridal Dress 3', 'Elegant Mermaid wedding dress.', 180.00, 299.00, '/Data/women/dress/mermaid/product3/front.jpg', 5, '2026-10-31 19:54:45'),
(62, 2, 'Mermaid Bridal Dress 4', 'Elegant Mermaid wedding dress.', 180.00, 299.00, '/Data/women/dress/mermaid/product4/front.jpg', 5, '2026-10-31 19:54:45'),
(63, 3, 'Princess Bridal Dress 2', 'Elegant Princess wedding dress.', 180.00, 299.00, '/Data/women/dress/princessDress/product2/princessDress.jpg', 5, '2026-10-31 19:54:45'),
(64, 3, 'Princess Bridal Dress 3', 'Elegant Princess wedding dress.', 180.00, 299.00, '/Data/women/dress/princessDress/product3/princess.jpg', 5, '2026-10-31 19:54:45'),
(65, 3, 'Princess Bridal Dress 4', 'Elegant Princess wedding dress.', 180.00, 299.00, '/Data/women/dress/princessDress/product4/front.jpg', 5, '2026-10-31 19:54:45'),
(66, 4, 'Royal Bridal Dress 2', 'Elegant Royal wedding dress.', 180.00, 299.00, '/Data/women/dress/royal/product2/front.jpg', 5, '2026-10-31 19:54:45'),
(67, 4, 'Royal Bridal Dress 3', 'Elegant Royal wedding dress.', 180.00, 299.00, '/Data/women/dress/royal/product3/front.webp', 5, '2026-10-31 19:54:45'),
(68, 4, 'Royal Bridal Dress 4', 'Elegant Royal wedding dress.', 180.00, 299.00, '/Data/women/dress/royal/product4/front.jpeg', 5, '2026-10-31 19:54:45'),
(69, 5, 'Sheath Bridal Dress 2', 'Elegant Sheath wedding dress.', 180.00, 299.00, '/Data/women/dress/sheath/product2/front.jpg', 5, '2026-10-31 19:54:45'),
(70, 5, 'Sheath Bridal Dress 3', 'Elegant Sheath wedding dress.', 180.00, 299.00, '/Data/women/dress/sheath/product3/front.jpg', 5, '2026-10-31 19:54:45'),
(71, 5, 'Sheath Bridal Dress 4', 'Elegant Sheath wedding dress.', 180.00, 299.00, '/Data/women/dress/sheath/product4/front.jpg', 5, '2026-10-31 19:54:45'),
(84, 12, 'Regular-Fit Suit 02', 'Wedding and formal occasion product.', 95.40, 159.00, '/Data/men/suits/regular%20fit/02/Screenshot%202026-11-01%20205821.png', 0, '2026-11-01 20:22:02'),
(85, 12, 'Regular-Fit Suit 03', 'Wedding and formal occasion product.', 95.40, 159.00, '/Data/men/suits/regular%20fit/03/Screenshot%202026-11-01%20210031.png', 0, '2026-11-01 20:22:02'),
(86, 12, 'Regular-Fit Suit 04', 'Wedding and formal occasion product.', 95.40, 159.00, '/Data/men/suits/regular%20fit/04/Screenshot%202026-11-01%20210448.png', 0, '2026-11-01 20:22:02'),
(87, 12, 'Regular-Fit Suit 05', 'Wedding and formal occasion product.', 95.40, 159.00, '/Data/men/suits/regular%20fit/05/Screenshot%202026-11-01%20211016.png', 0, '2026-11-01 20:22:02'),
(88, 12, 'Regular-Fit Suit 06', 'Wedding and formal occasion product.', 95.40, 159.00, '/Data/men/suits/regular%20fit/06/Screenshot%202026-11-01%20211155.png', 0, '2026-11-01 20:22:02'),
(91, 10, 'Three-Piece Suit 03', 'Wedding and formal occasion product.', 101.40, 169.00, '/Data/men/suits/3%20piece%20suit/03/Screenshot%202026-11-01%20191526.png', 0, '2026-11-01 20:22:02'),
(92, 10, 'Three-Piece Suit 04', 'Wedding and formal occasion product.', 101.40, 169.00, '/Data/men/suits/3%20piece%20suit/04/Screenshot%202026-11-01%20192239.png', 0, '2026-11-01 20:22:02'),
(93, 10, 'Three-Piece Suit 05', 'Wedding and formal occasion product.', 101.40, 169.00, '/Data/men/suits/3%20piece%20suit/05/Screenshot%202026-11-01%20193320.png', 0, '2026-11-01 20:22:02'),
(94, 10, 'Three-Piece Suit 06', 'Wedding and formal occasion product.', 101.40, 169.00, '/Data/men/suits/3%20piece%20suit/06/Screenshot%202026-11-01%20203341.png', 0, '2026-11-01 20:22:02'),
(96, 14, 'Tuxedo 02', 'Wedding and formal occasion product.', 131.40, 219.00, '/Data/men/suits/tuxedo/02/Screenshot%202026-11-01%20212632.png', 0, '2026-11-01 20:22:02'),
(97, 14, 'Tuxedo 03', 'Wedding and formal occasion product.', 131.40, 219.00, '/Data/men/suits/tuxedo/03/Screenshot%202026-11-01%20212825.png', 0, '2026-11-01 20:22:02'),
(98, 14, 'Tuxedo 04', 'Wedding and formal occasion product.', 131.40, 219.00, '/Data/men/suits/tuxedo/04/Screenshot%202026-11-01%20213013.png', 0, '2026-11-01 20:22:02'),
(99, 14, 'Tuxedo 05', 'Wedding and formal occasion product.', 131.40, 219.00, '/Data/men/suits/tuxedo/05/Screenshot%202026-11-01%20213148.png', 0, '2026-11-01 20:22:02'),
(100, 14, 'Tuxedo 06', 'Wedding and formal occasion product.', 131.40, 219.00, '/Data/men/suits/tuxedo/06/Screenshot%202026-11-01%20213627.png', 0, '2026-11-01 20:22:02'),
(102, 11, 'Double-Breasted Suit 02', 'Wedding and formal occasion product.', 113.40, 189.00, '/Data/men/suits/double%20breasted%20suit/02/Screenshot%202026-11-01%20204020.png', 0, '2026-11-01 20:22:02'),
(103, 11, 'Double-Breasted Suit 03', 'Wedding and formal occasion product.', 113.40, 189.00, '/Data/men/suits/double%20breasted%20suit/03/Screenshot%202026-11-01%20204347.png', 0, '2026-11-01 20:22:02'),
(104, 11, 'Double-Breasted Suit 04', 'Wedding and formal occasion product.', 113.40, 189.00, '/Data/men/suits/double%20breasted%20suit/04/Screenshot%202026-11-01%20204735.png', 0, '2026-11-01 20:22:02'),
(105, 11, 'Double-Breasted Suit 05', 'Wedding and formal occasion product.', 113.40, 189.00, '/Data/men/suits/double%20breasted%20suit/05/Screenshot%202026-11-01%20205024.png', 0, '2026-11-01 20:22:02'),
(106, 11, 'Double-Breasted Suit 06', 'Wedding and formal occasion product.', 113.40, 189.00, '/Data/men/suits/double%20breasted%20suit/06/Screenshot%202026-11-01%20205419.png', 0, '2026-11-01 20:22:02'),
(122, 20, 'High-Heel Shoes Screenshot 2026-10-29 150021', 'Wedding and formal occasion product.', 59.40, 99.00, '/Data/women/shoes/high%20heels/Screenshot%202026-10-29%20150021.png', 0, '2026-11-01 20:22:02'),
(124, 13, 'Slim-Fit Suit 02', 'Wedding and formal occasion product.', 95.40, 159.00, '/Data/men/suits/slim%20fit/02/Screenshot%202026-11-01%20211446.png', 0, '2026-11-01 20:22:02'),
(125, 13, 'Slim-Fit Suit 03', 'Wedding and formal occasion product.', 95.40, 159.00, '/Data/men/suits/slim%20fit/03/Screenshot%202026-11-01%20211623.png', 0, '2026-11-01 20:22:02'),
(126, 13, 'Slim-Fit Suit 04', 'Wedding and formal occasion product.', 95.40, 159.00, '/Data/men/suits/slim%20fit/04/Screenshot%202026-11-01%20211756.png', 0, '2026-11-01 20:22:03'),
(127, 13, 'Slim-Fit Suit 05', 'Wedding and formal occasion product.', 95.40, 159.00, '/Data/men/suits/slim%20fit/05/Screenshot%202026-11-01%20211948.png', 0, '2026-11-01 20:22:03'),
(128, 13, 'Slim-Fit Suit 06', 'Wedding and formal occasion product.', 95.40, 159.00, '/Data/men/suits/slim%20fit/06/Screenshot%202026-11-01%20212329.png', 0, '2026-11-01 20:22:03'),
(140, 5, 'Sheath Bridal Dress 5', 'Wedding and formal occasion product.', 179.40, 299.00, '/Data/women/dress/sheath/product5/primary.png', 0, '2026-11-01 20:22:03'),
(148, 3, 'Princess Bridal Dress 2', 'Wedding and formal occasion product.', 179.40, 299.00, '/Data/women/dress/princessDress/product2/princess1.jpg', 0, '2026-11-01 20:22:03'),
(158, 4, 'Royal Bridal Dress 5', 'Wedding and formal occasion product.', 179.40, 299.00, '/Data/women/dress/royal/product5/primary.png', 0, '2026-11-01 20:22:03'),
(169, 2, 'Mermaid Bridal Dress 5', 'Wedding and formal occasion product.', 179.40, 299.00, '/Data/women/dress/mermaid/product5/primary.png', 0, '2026-11-01 20:22:03');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `image_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `image_url` varchar(500) NOT NULL,
  `is_primary` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`image_id`, `product_id`, `image_url`, `is_primary`, `created_at`) VALUES
(16, 84, '/Data/men/suits/regular%20fit/02/Screenshot%202026-11-01%20205821.png', 1, '2026-11-01 20:22:02'),
(17, 84, '/Data/men/suits/regular%20fit/02/Screenshot%202026-11-01%20205850.png', 0, '2026-11-01 20:22:02'),
(18, 84, '/Data/men/suits/regular%20fit/02/Screenshot%202026-11-01%20205918.png', 0, '2026-11-01 20:22:02'),
(19, 84, '/Data/men/suits/regular%20fit/02/Screenshot%202026-11-01%20205945.png', 0, '2026-11-01 20:22:02'),
(20, 85, '/Data/men/suits/regular%20fit/03/Screenshot%202026-11-01%20210031.png', 1, '2026-11-01 20:22:02'),
(21, 85, '/Data/men/suits/regular%20fit/03/Screenshot%202026-11-01%20210100.png', 0, '2026-11-01 20:22:02'),
(22, 85, '/Data/men/suits/regular%20fit/03/Screenshot%202026-11-01%20210157.png', 0, '2026-11-01 20:22:02'),
(23, 85, '/Data/men/suits/regular%20fit/03/Screenshot%202026-11-01%20210226.png', 0, '2026-11-01 20:22:02'),
(24, 85, '/Data/men/suits/regular%20fit/03/Screenshot%202026-11-01%20210307.png', 0, '2026-11-01 20:22:02'),
(25, 86, '/Data/men/suits/regular%20fit/04/Screenshot%202026-11-01%20210448.png', 1, '2026-11-01 20:22:02'),
(26, 86, '/Data/men/suits/regular%20fit/04/Screenshot%202026-11-01%20210512.png', 0, '2026-11-01 20:22:02'),
(27, 86, '/Data/men/suits/regular%20fit/04/Screenshot%202026-11-01%20210532.png', 0, '2026-11-01 20:22:02'),
(28, 86, '/Data/men/suits/regular%20fit/04/Screenshot%202026-11-01%20210543.png', 0, '2026-11-01 20:22:02'),
(29, 86, '/Data/men/suits/regular%20fit/04/Screenshot%202026-11-01%20210606.png', 0, '2026-11-01 20:22:02'),
(30, 87, '/Data/men/suits/regular%20fit/05/Screenshot%202026-11-01%20211016.png', 1, '2026-11-01 20:22:02'),
(31, 87, '/Data/men/suits/regular%20fit/05/Screenshot%202026-11-01%20211027.png', 0, '2026-11-01 20:22:02'),
(32, 87, '/Data/men/suits/regular%20fit/05/Screenshot%202026-11-01%20211039.png', 0, '2026-11-01 20:22:02'),
(33, 87, '/Data/men/suits/regular%20fit/05/Screenshot%202026-11-01%20211053.png', 0, '2026-11-01 20:22:02'),
(34, 87, '/Data/men/suits/regular%20fit/05/Screenshot%202026-11-01%20211104.png', 0, '2026-11-01 20:22:02'),
(35, 88, '/Data/men/suits/regular%20fit/06/Screenshot%202026-11-01%20211155.png', 1, '2026-11-01 20:22:02'),
(36, 88, '/Data/men/suits/regular%20fit/06/Screenshot%202026-11-01%20211206.png', 0, '2026-11-01 20:22:02'),
(37, 88, '/Data/men/suits/regular%20fit/06/Screenshot%202026-11-01%20211223.png', 0, '2026-11-01 20:22:02'),
(38, 88, '/Data/men/suits/regular%20fit/06/Screenshot%202026-11-01%20211237.png', 0, '2026-11-01 20:22:02'),
(39, 88, '/Data/men/suits/regular%20fit/06/Screenshot%202026-11-01%20211256.png', 0, '2026-11-01 20:22:02'),
(52, 91, '/Data/men/suits/3%20piece%20suit/03/Screenshot%202026-11-01%20191526.png', 1, '2026-11-01 20:22:02'),
(53, 91, '/Data/men/suits/3%20piece%20suit/03/Screenshot%202026-11-01%20191702.png', 0, '2026-11-01 20:22:02'),
(54, 91, '/Data/men/suits/3%20piece%20suit/03/Screenshot%202026-11-01%20191850.png', 0, '2026-11-01 20:22:02'),
(55, 91, '/Data/men/suits/3%20piece%20suit/03/Screenshot%202026-11-01%20191923.png', 0, '2026-11-01 20:22:02'),
(56, 91, '/Data/men/suits/3%20piece%20suit/03/Screenshot%202026-11-01%20192001.png', 0, '2026-11-01 20:22:02'),
(57, 92, '/Data/men/suits/3%20piece%20suit/04/Screenshot%202026-11-01%20192239.png', 1, '2026-11-01 20:22:02'),
(58, 92, '/Data/men/suits/3%20piece%20suit/04/Screenshot%202026-11-01%20192319.png', 0, '2026-11-01 20:22:02'),
(59, 92, '/Data/men/suits/3%20piece%20suit/04/Screenshot%202026-11-01%20192458.png', 0, '2026-11-01 20:22:02'),
(60, 92, '/Data/men/suits/3%20piece%20suit/04/Screenshot%202026-11-01%20192541.png', 0, '2026-11-01 20:22:02'),
(61, 92, '/Data/men/suits/3%20piece%20suit/04/Screenshot%202026-11-01%20192703.png', 0, '2026-11-01 20:22:02'),
(62, 93, '/Data/men/suits/3%20piece%20suit/05/Screenshot%202026-11-01%20193320.png', 1, '2026-11-01 20:22:02'),
(63, 93, '/Data/men/suits/3%20piece%20suit/05/Screenshot%202026-11-01%20193407.png', 0, '2026-11-01 20:22:02'),
(64, 93, '/Data/men/suits/3%20piece%20suit/05/Screenshot%202026-11-01%20193449.png', 0, '2026-11-01 20:22:02'),
(65, 93, '/Data/men/suits/3%20piece%20suit/05/Screenshot%202026-11-01%20193548.png', 0, '2026-11-01 20:22:02'),
(66, 93, '/Data/men/suits/3%20piece%20suit/05/Screenshot%202026-11-01%20193623.png', 0, '2026-11-01 20:22:02'),
(67, 94, '/Data/men/suits/3%20piece%20suit/06/Screenshot%202026-11-01%20203341.png', 1, '2026-11-01 20:22:02'),
(68, 94, '/Data/men/suits/3%20piece%20suit/06/Screenshot%202026-11-01%20203442.png', 0, '2026-11-01 20:22:02'),
(69, 94, '/Data/men/suits/3%20piece%20suit/06/Screenshot%202026-11-01%20203516.png', 0, '2026-11-01 20:22:02'),
(70, 94, '/Data/men/suits/3%20piece%20suit/06/Screenshot%202026-11-01%20203546.png', 0, '2026-11-01 20:22:02'),
(71, 94, '/Data/men/suits/3%20piece%20suit/06/Screenshot%202026-11-01%20203630.png', 0, '2026-11-01 20:22:02'),
(76, 96, '/Data/men/suits/tuxedo/02/Screenshot%202026-11-01%20212632.png', 1, '2026-11-01 20:22:02'),
(77, 96, '/Data/men/suits/tuxedo/02/Screenshot%202026-11-01%20212646.png', 0, '2026-11-01 20:22:02'),
(78, 96, '/Data/men/suits/tuxedo/02/Screenshot%202026-11-01%20212702.png', 0, '2026-11-01 20:22:02'),
(79, 96, '/Data/men/suits/tuxedo/02/Screenshot%202026-11-01%20212718.png', 0, '2026-11-01 20:22:02'),
(80, 96, '/Data/men/suits/tuxedo/02/Screenshot%202026-11-01%20212728.png', 0, '2026-11-01 20:22:02'),
(81, 97, '/Data/men/suits/tuxedo/03/Screenshot%202026-11-01%20212825.png', 1, '2026-11-01 20:22:02'),
(82, 97, '/Data/men/suits/tuxedo/03/Screenshot%202026-11-01%20212834.png', 0, '2026-11-01 20:22:02'),
(83, 97, '/Data/men/suits/tuxedo/03/Screenshot%202026-11-01%20212848.png', 0, '2026-11-01 20:22:02'),
(84, 97, '/Data/men/suits/tuxedo/03/Screenshot%202026-11-01%20212856.png', 0, '2026-11-01 20:22:02'),
(85, 97, '/Data/men/suits/tuxedo/03/Screenshot%202026-11-01%20212910.png', 0, '2026-11-01 20:22:02'),
(86, 98, '/Data/men/suits/tuxedo/04/Screenshot%202026-11-01%20213013.png', 1, '2026-11-01 20:22:02'),
(87, 98, '/Data/men/suits/tuxedo/04/Screenshot%202026-11-01%20213032.png', 0, '2026-11-01 20:22:02'),
(88, 98, '/Data/men/suits/tuxedo/04/Screenshot%202026-11-01%20213040.png', 0, '2026-11-01 20:22:02'),
(89, 98, '/Data/men/suits/tuxedo/04/Screenshot%202026-11-01%20213059.png', 0, '2026-11-01 20:22:02'),
(90, 99, '/Data/men/suits/tuxedo/05/Screenshot%202026-11-01%20213148.png', 1, '2026-11-01 20:22:02'),
(91, 99, '/Data/men/suits/tuxedo/05/Screenshot%202026-11-01%20213159.png', 0, '2026-11-01 20:22:02'),
(92, 99, '/Data/men/suits/tuxedo/05/Screenshot%202026-11-01%20213210.png', 0, '2026-11-01 20:22:02'),
(93, 99, '/Data/men/suits/tuxedo/05/Screenshot%202026-11-01%20213222.png', 0, '2026-11-01 20:22:02'),
(94, 100, '/Data/men/suits/tuxedo/06/Screenshot%202026-11-01%20213627.png', 1, '2026-11-01 20:22:02'),
(95, 100, '/Data/men/suits/tuxedo/06/Screenshot%202026-11-01%20213653.png', 0, '2026-11-01 20:22:02'),
(96, 100, '/Data/men/suits/tuxedo/06/Screenshot%202026-11-01%20213709.png', 0, '2026-11-01 20:22:02'),
(97, 100, '/Data/men/suits/tuxedo/06/Screenshot%202026-11-01%20213719.png', 0, '2026-11-01 20:22:02'),
(101, 102, '/Data/men/suits/double%20breasted%20suit/02/Screenshot%202026-11-01%20204020.png', 1, '2026-11-01 20:22:02'),
(102, 102, '/Data/men/suits/double%20breasted%20suit/02/Screenshot%202026-11-01%20204047.png', 0, '2026-11-01 20:22:02'),
(103, 102, '/Data/men/suits/double%20breasted%20suit/02/Screenshot%202026-11-01%20204142.png', 0, '2026-11-01 20:22:02'),
(104, 102, '/Data/men/suits/double%20breasted%20suit/02/Screenshot%202026-11-01%20204214.png', 0, '2026-11-01 20:22:02'),
(105, 103, '/Data/men/suits/double%20breasted%20suit/03/Screenshot%202026-11-01%20204347.png', 1, '2026-11-01 20:22:02'),
(106, 103, '/Data/men/suits/double%20breasted%20suit/03/Screenshot%202026-11-01%20204420.png', 0, '2026-11-01 20:22:02'),
(107, 103, '/Data/men/suits/double%20breasted%20suit/03/Screenshot%202026-11-01%20204510.png', 0, '2026-11-01 20:22:02'),
(108, 103, '/Data/men/suits/double%20breasted%20suit/03/Screenshot%202026-11-01%20204556.png', 0, '2026-11-01 20:22:02'),
(109, 103, '/Data/men/suits/double%20breasted%20suit/03/Screenshot%202026-11-01%20204628.png', 0, '2026-11-01 20:22:02'),
(110, 104, '/Data/men/suits/double%20breasted%20suit/04/Screenshot%202026-11-01%20204735.png', 1, '2026-11-01 20:22:02'),
(111, 104, '/Data/men/suits/double%20breasted%20suit/04/Screenshot%202026-11-01%20204809.png', 0, '2026-11-01 20:22:02'),
(112, 104, '/Data/men/suits/double%20breasted%20suit/04/Screenshot%202026-11-01%20204851.png', 0, '2026-11-01 20:22:02'),
(113, 104, '/Data/men/suits/double%20breasted%20suit/04/Screenshot%202026-11-01%20204937.png', 0, '2026-11-01 20:22:02'),
(114, 105, '/Data/men/suits/double%20breasted%20suit/05/Screenshot%202026-11-01%20205024.png', 1, '2026-11-01 20:22:02'),
(115, 105, '/Data/men/suits/double%20breasted%20suit/05/Screenshot%202026-11-01%20205056.png', 0, '2026-11-01 20:22:02'),
(116, 105, '/Data/men/suits/double%20breasted%20suit/05/Screenshot%202026-11-01%20205213.png', 0, '2026-11-01 20:22:02'),
(117, 105, '/Data/men/suits/double%20breasted%20suit/05/Screenshot%202026-11-01%20205326.png', 0, '2026-11-01 20:22:02'),
(118, 106, '/Data/men/suits/double%20breasted%20suit/06/Screenshot%202026-11-01%20205419.png', 1, '2026-11-01 20:22:02'),
(119, 106, '/Data/men/suits/double%20breasted%20suit/06/Screenshot%202026-11-01%20205445.png', 0, '2026-11-01 20:22:02'),
(120, 106, '/Data/men/suits/double%20breasted%20suit/06/Screenshot%202026-11-01%20205522.png', 0, '2026-11-01 20:22:02'),
(121, 106, '/Data/men/suits/double%20breasted%20suit/06/Screenshot%202026-11-01%20205602.png', 0, '2026-11-01 20:22:02'),
(144, 122, '/Data/women/shoes/high%20heels/Screenshot%202026-10-29%20150021.png', 1, '2026-11-01 20:22:02'),
(148, 124, '/Data/men/suits/slim%20fit/02/Screenshot%202026-11-01%20211446.png', 1, '2026-11-01 20:22:02'),
(149, 124, '/Data/men/suits/slim%20fit/02/Screenshot%202026-11-01%20211457.png', 0, '2026-11-01 20:22:02'),
(150, 124, '/Data/men/suits/slim%20fit/02/Screenshot%202026-11-01%20211511.png', 0, '2026-11-01 20:22:02'),
(151, 124, '/Data/men/suits/slim%20fit/02/Screenshot%202026-11-01%20211523.png', 0, '2026-11-01 20:22:02'),
(152, 124, '/Data/men/suits/slim%20fit/02/Screenshot%202026-11-01%20211536.png', 0, '2026-11-01 20:22:02'),
(153, 125, '/Data/men/suits/slim%20fit/03/Screenshot%202026-11-01%20211623.png', 1, '2026-11-01 20:22:02'),
(154, 125, '/Data/men/suits/slim%20fit/03/Screenshot%202026-11-01%20211633.png', 0, '2026-11-01 20:22:03'),
(155, 125, '/Data/men/suits/slim%20fit/03/Screenshot%202026-11-01%20211647.png', 0, '2026-11-01 20:22:03'),
(156, 125, '/Data/men/suits/slim%20fit/03/Screenshot%202026-11-01%20211656.png', 0, '2026-11-01 20:22:03'),
(157, 125, '/Data/men/suits/slim%20fit/03/Screenshot%202026-11-01%20211707.png', 0, '2026-11-01 20:22:03'),
(158, 126, '/Data/men/suits/slim%20fit/04/Screenshot%202026-11-01%20211756.png', 1, '2026-11-01 20:22:03'),
(159, 126, '/Data/men/suits/slim%20fit/04/Screenshot%202026-11-01%20211808.png', 0, '2026-11-01 20:22:03'),
(160, 126, '/Data/men/suits/slim%20fit/04/Screenshot%202026-11-01%20211822.png', 0, '2026-11-01 20:22:03'),
(161, 126, '/Data/men/suits/slim%20fit/04/Screenshot%202026-11-01%20211835.png', 0, '2026-11-01 20:22:03'),
(162, 126, '/Data/men/suits/slim%20fit/04/Screenshot%202026-11-01%20211858.png', 0, '2026-11-01 20:22:03'),
(163, 127, '/Data/men/suits/slim%20fit/05/Screenshot%202026-11-01%20211948.png', 1, '2026-11-01 20:22:03'),
(164, 127, '/Data/men/suits/slim%20fit/05/Screenshot%202026-11-01%20212005.png', 0, '2026-11-01 20:22:03'),
(165, 127, '/Data/men/suits/slim%20fit/05/Screenshot%202026-11-01%20212019.png', 0, '2026-11-01 20:22:03'),
(166, 127, '/Data/men/suits/slim%20fit/05/Screenshot%202026-11-01%20212039.png', 0, '2026-11-01 20:22:03'),
(167, 127, '/Data/men/suits/slim%20fit/05/Screenshot%202026-11-01%20212213.png', 0, '2026-11-01 20:22:03'),
(168, 128, '/Data/men/suits/slim%20fit/06/Screenshot%202026-11-01%20212329.png', 1, '2026-11-01 20:22:03'),
(169, 128, '/Data/men/suits/slim%20fit/06/Screenshot%202026-11-01%20212355.png', 0, '2026-11-01 20:22:03'),
(170, 128, '/Data/men/suits/slim%20fit/06/Screenshot%202026-11-01%20212408.png', 0, '2026-11-01 20:22:03'),
(171, 128, '/Data/men/suits/slim%20fit/06/Screenshot%202026-11-01%20212419.png', 0, '2026-11-01 20:22:03'),
(192, 140, '/Data/women/dress/sheath/product5/back.png', 0, '2026-11-01 20:22:03'),
(193, 140, '/Data/women/dress/sheath/product5/primary.png', 1, '2026-11-01 20:22:03'),
(194, 140, '/Data/women/dress/sheath/product5/Screenshot%202026-10-29%20143240.png', 0, '2026-11-01 20:22:03'),
(205, 148, '/Data/women/dress/princessDress/product2/princess1.jpg', 1, '2026-11-01 20:22:03'),
(206, 148, '/Data/women/dress/princessDress/product2/princess3.jpg', 0, '2026-11-01 20:22:03'),
(207, 148, '/Data/women/dress/princessDress/product2/princessDress.jpg', 0, '2026-11-01 20:22:03'),
(234, 158, '/Data/women/dress/royal/product5/back.png', 0, '2026-11-01 20:22:03'),
(235, 158, '/Data/women/dress/royal/product5/primary.png', 1, '2026-11-01 20:22:03'),
(236, 158, '/Data/women/dress/royal/product5/Screenshot%202026-10-29%20141739.png', 0, '2026-11-01 20:22:03'),
(258, 169, '/Data/women/dress/mermaid/product5/back.png', 0, '2026-11-01 20:22:03'),
(259, 169, '/Data/women/dress/mermaid/product5/primary.png', 1, '2026-11-01 20:22:03'),
(260, 169, '/Data/women/dress/mermaid/product5/Screenshot%202026-10-29%20140356.png', 0, '2026-11-01 20:22:03'),
(261, 169, '/Data/women/dress/mermaid/product5/Screenshot%202026-10-29%20140431.png', 0, '2026-11-01 20:22:03');

-- --------------------------------------------------------

--
-- Table structure for table `product_variants`
--

CREATE TABLE `product_variants` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `size` varchar(30) DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL,
  `stock_quantity` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_variants`
--

INSERT INTO `product_variants` (`id`, `product_id`, `size`, `color`, `stock_quantity`) VALUES
(243, 1, 'S', 'Black', 10),
(244, 1, 'M', 'Black', 10),
(245, 1, 'L', 'Black', 10),
(246, 1, 'S', 'White', 10),
(247, 1, 'M', 'White', 10),
(248, 1, 'L', 'White', 10),
(249, 1, 'S', 'Navy Blue', 10),
(250, 1, 'M', 'Navy Blue', 10),
(251, 1, 'L', 'Navy Blue', 10),
(252, 57, 'S', 'Black', 10),
(253, 57, 'M', 'Black', 10),
(254, 57, 'L', 'Black', 10),
(255, 57, 'S', 'White', 10),
(256, 57, 'M', 'White', 10),
(257, 57, 'L', 'White', 10),
(258, 57, 'S', 'Navy Blue', 10),
(259, 57, 'M', 'Navy Blue', 10),
(260, 57, 'L', 'Navy Blue', 10),
(261, 58, 'S', 'Black', 10),
(262, 58, 'M', 'Black', 10),
(263, 58, 'L', 'Black', 10),
(264, 58, 'S', 'White', 10),
(265, 58, 'M', 'White', 10),
(266, 58, 'L', 'White', 10),
(267, 58, 'S', 'Navy Blue', 10),
(268, 58, 'M', 'Navy Blue', 10),
(269, 58, 'L', 'Navy Blue', 10),
(270, 59, 'S', 'Black', 10),
(271, 59, 'M', 'Black', 10),
(272, 59, 'L', 'Black', 10),
(273, 59, 'S', 'White', 10),
(274, 59, 'M', 'White', 10),
(275, 59, 'L', 'White', 10),
(276, 59, 'S', 'Navy Blue', 10),
(277, 59, 'M', 'Navy Blue', 10),
(278, 59, 'L', 'Navy Blue', 10),
(279, 2, 'S', 'Black', 10),
(280, 2, 'M', 'Black', 10),
(281, 2, 'L', 'Black', 10),
(282, 2, 'S', 'White', 10),
(283, 2, 'M', 'White', 10),
(284, 2, 'L', 'White', 10),
(285, 2, 'S', 'Navy Blue', 10),
(286, 2, 'M', 'Navy Blue', 10),
(287, 2, 'L', 'Navy Blue', 10),
(288, 60, 'S', 'Black', 10),
(289, 60, 'M', 'Black', 10),
(290, 60, 'L', 'Black', 10),
(291, 60, 'S', 'White', 10),
(292, 60, 'M', 'White', 10),
(293, 60, 'L', 'White', 10),
(294, 60, 'S', 'Navy Blue', 10),
(295, 60, 'M', 'Navy Blue', 10),
(296, 60, 'L', 'Navy Blue', 10),
(297, 61, 'S', 'Black', 10),
(298, 61, 'M', 'Black', 10),
(299, 61, 'L', 'Black', 10),
(300, 61, 'S', 'White', 10),
(301, 61, 'M', 'White', 10),
(302, 61, 'L', 'White', 10),
(303, 61, 'S', 'Navy Blue', 10),
(304, 61, 'M', 'Navy Blue', 10),
(305, 61, 'L', 'Navy Blue', 10),
(306, 62, 'S', 'Black', 10),
(307, 62, 'M', 'Black', 10),
(308, 62, 'L', 'Black', 10),
(309, 62, 'S', 'White', 10),
(310, 62, 'M', 'White', 10),
(311, 62, 'L', 'White', 10),
(312, 62, 'S', 'Navy Blue', 10),
(313, 62, 'M', 'Navy Blue', 10),
(314, 62, 'L', 'Navy Blue', 10),
(315, 169, 'S', 'Black', 10),
(316, 169, 'M', 'Black', 10),
(317, 169, 'L', 'Black', 10),
(318, 169, 'S', 'White', 10),
(319, 169, 'M', 'White', 10),
(320, 169, 'L', 'White', 10),
(321, 169, 'S', 'Navy Blue', 10),
(322, 169, 'M', 'Navy Blue', 10),
(323, 169, 'L', 'Navy Blue', 10),
(324, 3, 'S', 'Black', 10),
(325, 3, 'M', 'Black', 10),
(326, 3, 'L', 'Black', 10),
(327, 3, 'S', 'White', 10),
(328, 3, 'M', 'White', 10),
(329, 3, 'L', 'White', 10),
(330, 3, 'S', 'Navy Blue', 10),
(331, 3, 'M', 'Navy Blue', 10),
(332, 3, 'L', 'Navy Blue', 10),
(333, 63, 'S', 'Black', 10),
(334, 63, 'M', 'Black', 10),
(335, 63, 'L', 'Black', 10),
(336, 63, 'S', 'White', 10),
(337, 63, 'M', 'White', 10),
(338, 63, 'L', 'White', 10),
(339, 63, 'S', 'Navy Blue', 10),
(340, 63, 'M', 'Navy Blue', 10),
(341, 63, 'L', 'Navy Blue', 10),
(342, 64, 'S', 'Black', 10),
(343, 64, 'M', 'Black', 10),
(344, 64, 'L', 'Black', 10),
(345, 64, 'S', 'White', 10),
(346, 64, 'M', 'White', 10),
(347, 64, 'L', 'White', 10),
(348, 64, 'S', 'Navy Blue', 10),
(349, 64, 'M', 'Navy Blue', 10),
(350, 64, 'L', 'Navy Blue', 10),
(351, 65, 'S', 'Black', 10),
(352, 65, 'M', 'Black', 10),
(353, 65, 'L', 'Black', 10),
(354, 65, 'S', 'White', 10),
(355, 65, 'M', 'White', 10),
(356, 65, 'L', 'White', 10),
(357, 65, 'S', 'Navy Blue', 10),
(358, 65, 'M', 'Navy Blue', 10),
(359, 65, 'L', 'Navy Blue', 10),
(360, 148, 'S', 'Black', 10),
(361, 148, 'M', 'Black', 10),
(362, 148, 'L', 'Black', 10),
(363, 148, 'S', 'White', 10),
(364, 148, 'M', 'White', 10),
(365, 148, 'L', 'White', 10),
(366, 148, 'S', 'Navy Blue', 10),
(367, 148, 'M', 'Navy Blue', 10),
(368, 148, 'L', 'Navy Blue', 10),
(369, 4, 'S', 'Black', 10),
(370, 4, 'M', 'Black', 10),
(371, 4, 'L', 'Black', 10),
(372, 4, 'S', 'White', 10),
(373, 4, 'M', 'White', 10),
(374, 4, 'L', 'White', 10),
(375, 4, 'S', 'Navy Blue', 10),
(376, 4, 'M', 'Navy Blue', 10),
(377, 4, 'L', 'Navy Blue', 10),
(378, 66, 'S', 'Black', 10),
(379, 66, 'M', 'Black', 10),
(380, 66, 'L', 'Black', 10),
(381, 66, 'S', 'White', 10),
(382, 66, 'M', 'White', 10),
(383, 66, 'L', 'White', 10),
(384, 66, 'S', 'Navy Blue', 10),
(385, 66, 'M', 'Navy Blue', 10),
(386, 66, 'L', 'Navy Blue', 10),
(387, 67, 'S', 'Black', 10),
(388, 67, 'M', 'Black', 10),
(389, 67, 'L', 'Black', 10),
(390, 67, 'S', 'White', 10),
(391, 67, 'M', 'White', 10),
(392, 67, 'L', 'White', 10),
(393, 67, 'S', 'Navy Blue', 10),
(394, 67, 'M', 'Navy Blue', 10),
(395, 67, 'L', 'Navy Blue', 10),
(396, 68, 'S', 'Black', 10),
(397, 68, 'M', 'Black', 10),
(398, 68, 'L', 'Black', 10),
(399, 68, 'S', 'White', 10),
(400, 68, 'M', 'White', 10),
(401, 68, 'L', 'White', 10),
(402, 68, 'S', 'Navy Blue', 10),
(403, 68, 'M', 'Navy Blue', 10),
(404, 68, 'L', 'Navy Blue', 10),
(405, 158, 'S', 'Black', 10),
(406, 158, 'M', 'Black', 10),
(407, 158, 'L', 'Black', 10),
(408, 158, 'S', 'White', 10),
(409, 158, 'M', 'White', 10),
(410, 158, 'L', 'White', 10),
(411, 158, 'S', 'Navy Blue', 10),
(412, 158, 'M', 'Navy Blue', 10),
(413, 158, 'L', 'Navy Blue', 10),
(414, 5, 'S', 'Black', 10),
(415, 5, 'M', 'Black', 10),
(416, 5, 'L', 'Black', 10),
(417, 5, 'S', 'White', 10),
(418, 5, 'M', 'White', 10),
(419, 5, 'L', 'White', 10),
(420, 5, 'S', 'Navy Blue', 10),
(421, 5, 'M', 'Navy Blue', 10),
(422, 5, 'L', 'Navy Blue', 10),
(423, 69, 'S', 'Black', 10),
(424, 69, 'M', 'Black', 10),
(425, 69, 'L', 'Black', 10),
(426, 69, 'S', 'White', 10),
(427, 69, 'M', 'White', 10),
(428, 69, 'L', 'White', 10),
(429, 69, 'S', 'Navy Blue', 10),
(430, 69, 'M', 'Navy Blue', 10),
(431, 69, 'L', 'Navy Blue', 10),
(432, 70, 'S', 'Black', 10),
(433, 70, 'M', 'Black', 10),
(434, 70, 'L', 'Black', 10),
(435, 70, 'S', 'White', 10),
(436, 70, 'M', 'White', 10),
(437, 70, 'L', 'White', 10),
(438, 70, 'S', 'Navy Blue', 10),
(439, 70, 'M', 'Navy Blue', 10),
(440, 70, 'L', 'Navy Blue', 10),
(441, 71, 'S', 'Black', 10),
(442, 71, 'M', 'Black', 10),
(443, 71, 'L', 'Black', 10),
(444, 71, 'S', 'White', 10),
(445, 71, 'M', 'White', 10),
(446, 71, 'L', 'White', 10),
(447, 71, 'S', 'Navy Blue', 10),
(448, 71, 'M', 'Navy Blue', 10),
(449, 71, 'L', 'Navy Blue', 10),
(450, 140, 'S', 'Black', 10),
(451, 140, 'M', 'Black', 10),
(452, 140, 'L', 'Black', 10),
(453, 140, 'S', 'White', 10),
(454, 140, 'M', 'White', 10),
(455, 140, 'L', 'White', 10),
(456, 140, 'S', 'Navy Blue', 10),
(457, 140, 'M', 'Navy Blue', 10),
(458, 140, 'L', 'Navy Blue', 10),
(459, 34, 'S', 'Black', 10),
(460, 34, 'M', 'Black', 10),
(461, 34, 'L', 'Black', 10),
(462, 34, 'S', 'White', 10),
(463, 34, 'M', 'White', 10),
(464, 34, 'L', 'White', 10),
(465, 34, 'S', 'Navy Blue', 10),
(466, 34, 'M', 'Navy Blue', 10),
(467, 34, 'L', 'Navy Blue', 10),
(468, 35, 'S', 'Black', 10),
(469, 35, 'M', 'Black', 10),
(470, 35, 'L', 'Black', 10),
(471, 35, 'S', 'White', 10),
(472, 35, 'M', 'White', 10),
(473, 35, 'L', 'White', 10),
(474, 35, 'S', 'Navy Blue', 10),
(475, 35, 'M', 'Navy Blue', 10),
(476, 35, 'L', 'Navy Blue', 10),
(477, 36, 'S', 'Black', 10),
(478, 36, 'M', 'Black', 10),
(479, 36, 'L', 'Black', 10),
(480, 36, 'S', 'White', 10),
(481, 36, 'M', 'White', 10),
(482, 36, 'L', 'White', 10),
(483, 36, 'S', 'Navy Blue', 10),
(484, 36, 'M', 'Navy Blue', 10),
(485, 36, 'L', 'Navy Blue', 10),
(486, 37, 'S', 'Black', 10),
(487, 37, 'M', 'Black', 10),
(488, 37, 'L', 'Black', 10),
(489, 37, 'S', 'White', 10),
(490, 37, 'M', 'White', 10),
(491, 37, 'L', 'White', 10),
(492, 37, 'S', 'Navy Blue', 10),
(493, 37, 'M', 'Navy Blue', 10),
(494, 37, 'L', 'Navy Blue', 10),
(495, 38, 'S', 'Black', 10),
(496, 38, 'M', 'Black', 10),
(497, 38, 'L', 'Black', 10),
(498, 38, 'S', 'White', 10),
(499, 38, 'M', 'White', 10),
(500, 38, 'L', 'White', 10),
(501, 38, 'S', 'Navy Blue', 10),
(502, 38, 'M', 'Navy Blue', 10),
(503, 38, 'L', 'Navy Blue', 10),
(504, 39, 'S', 'Black', 10),
(505, 39, 'M', 'Black', 10),
(506, 39, 'L', 'Black', 10),
(507, 39, 'S', 'White', 10),
(508, 39, 'M', 'White', 10),
(509, 39, 'L', 'White', 10),
(510, 39, 'S', 'Navy Blue', 10),
(511, 39, 'M', 'Navy Blue', 10),
(512, 39, 'L', 'Navy Blue', 10),
(513, 40, 'S', 'Black', 10),
(514, 40, 'M', 'Black', 10),
(515, 40, 'L', 'Black', 10),
(516, 40, 'S', 'White', 10),
(517, 40, 'M', 'White', 10),
(518, 40, 'L', 'White', 10),
(519, 40, 'S', 'Navy Blue', 10),
(520, 40, 'M', 'Navy Blue', 10),
(521, 40, 'L', 'Navy Blue', 10),
(522, 41, 'S', 'Black', 10),
(523, 41, 'M', 'Black', 10),
(524, 41, 'L', 'Black', 10),
(525, 41, 'S', 'White', 10),
(526, 41, 'M', 'White', 10),
(527, 41, 'L', 'White', 10),
(528, 41, 'S', 'Navy Blue', 10),
(529, 41, 'M', 'Navy Blue', 10),
(530, 41, 'L', 'Navy Blue', 10),
(531, 42, 'S', 'Black', 10),
(532, 42, 'M', 'Black', 10),
(533, 42, 'L', 'Black', 10),
(534, 42, 'S', 'White', 10),
(535, 42, 'M', 'White', 10),
(536, 42, 'L', 'White', 10),
(537, 42, 'S', 'Navy Blue', 10),
(538, 42, 'M', 'Navy Blue', 10),
(539, 42, 'L', 'Navy Blue', 10),
(540, 43, 'S', 'Black', 10),
(541, 43, 'M', 'Black', 10),
(542, 43, 'L', 'Black', 10),
(543, 43, 'S', 'White', 10),
(544, 43, 'M', 'White', 10),
(545, 43, 'L', 'White', 10),
(546, 43, 'S', 'Navy Blue', 10),
(547, 43, 'M', 'Navy Blue', 10),
(548, 43, 'L', 'Navy Blue', 10),
(549, 44, 'S', 'Black', 10),
(550, 44, 'M', 'Black', 10),
(551, 44, 'L', 'Black', 10),
(552, 44, 'S', 'White', 10),
(553, 44, 'M', 'White', 10),
(554, 44, 'L', 'White', 10),
(555, 44, 'S', 'Navy Blue', 10),
(556, 44, 'M', 'Navy Blue', 10),
(557, 44, 'L', 'Navy Blue', 10),
(558, 45, 'S', 'Black', 10),
(559, 45, 'M', 'Black', 10),
(560, 45, 'L', 'Black', 10),
(561, 45, 'S', 'White', 10),
(562, 45, 'M', 'White', 10),
(563, 45, 'L', 'White', 10),
(564, 45, 'S', 'Navy Blue', 10),
(565, 45, 'M', 'Navy Blue', 10),
(566, 45, 'L', 'Navy Blue', 10),
(567, 6, 'S', 'Black', 10),
(568, 6, 'M', 'Black', 10),
(569, 6, 'L', 'Black', 10),
(570, 6, 'S', 'White', 10),
(571, 6, 'M', 'White', 10),
(572, 6, 'L', 'White', 10),
(573, 6, 'S', 'Navy Blue', 10),
(574, 6, 'M', 'Navy Blue', 10),
(575, 6, 'L', 'Navy Blue', 10),
(576, 7, 'S', 'Black', 10),
(577, 7, 'M', 'Black', 10),
(578, 7, 'L', 'Black', 10),
(579, 7, 'S', 'White', 10),
(580, 7, 'M', 'White', 10),
(581, 7, 'L', 'White', 10),
(582, 7, 'S', 'Navy Blue', 10),
(583, 7, 'M', 'Navy Blue', 10),
(584, 7, 'L', 'Navy Blue', 10),
(585, 91, 'S', 'Black', 10),
(586, 91, 'M', 'Black', 10),
(587, 91, 'L', 'Black', 10),
(588, 91, 'S', 'White', 10),
(589, 91, 'M', 'White', 10),
(590, 91, 'L', 'White', 10),
(591, 91, 'S', 'Navy Blue', 10),
(592, 91, 'M', 'Navy Blue', 10),
(593, 91, 'L', 'Navy Blue', 10),
(594, 92, 'S', 'Black', 10),
(595, 92, 'M', 'Black', 10),
(596, 92, 'L', 'Black', 10),
(597, 92, 'S', 'White', 10),
(598, 92, 'M', 'White', 10),
(599, 92, 'L', 'White', 10),
(600, 92, 'S', 'Navy Blue', 10),
(601, 92, 'M', 'Navy Blue', 10),
(602, 92, 'L', 'Navy Blue', 10),
(603, 93, 'S', 'Black', 10),
(604, 93, 'M', 'Black', 10),
(605, 93, 'L', 'Black', 10),
(606, 93, 'S', 'White', 10),
(607, 93, 'M', 'White', 10),
(608, 93, 'L', 'White', 10),
(609, 93, 'S', 'Navy Blue', 10),
(610, 93, 'M', 'Navy Blue', 10),
(611, 93, 'L', 'Navy Blue', 10),
(612, 94, 'S', 'Black', 10),
(613, 94, 'M', 'Black', 10),
(614, 94, 'L', 'Black', 10),
(615, 94, 'S', 'White', 10),
(616, 94, 'M', 'White', 10),
(617, 94, 'L', 'White', 10),
(618, 94, 'S', 'Navy Blue', 10),
(619, 94, 'M', 'Navy Blue', 10),
(620, 94, 'L', 'Navy Blue', 10),
(621, 8, 'S', 'Black', 10),
(622, 8, 'M', 'Black', 10),
(623, 8, 'L', 'Black', 10),
(624, 8, 'S', 'White', 10),
(625, 8, 'M', 'White', 10),
(626, 8, 'L', 'White', 10),
(627, 8, 'S', 'Navy Blue', 10),
(628, 8, 'M', 'Navy Blue', 10),
(629, 8, 'L', 'Navy Blue', 10),
(630, 102, 'S', 'Black', 10),
(631, 102, 'M', 'Black', 10),
(632, 102, 'L', 'Black', 10),
(633, 102, 'S', 'White', 10),
(634, 102, 'M', 'White', 10),
(635, 102, 'L', 'White', 10),
(636, 102, 'S', 'Navy Blue', 10),
(637, 102, 'M', 'Navy Blue', 10),
(638, 102, 'L', 'Navy Blue', 10),
(639, 103, 'S', 'Black', 10),
(640, 103, 'M', 'Black', 10),
(641, 103, 'L', 'Black', 10),
(642, 103, 'S', 'White', 10),
(643, 103, 'M', 'White', 10),
(644, 103, 'L', 'White', 10),
(645, 103, 'S', 'Navy Blue', 10),
(646, 103, 'M', 'Navy Blue', 10),
(647, 103, 'L', 'Navy Blue', 10),
(648, 104, 'S', 'Black', 10),
(649, 104, 'M', 'Black', 10),
(650, 104, 'L', 'Black', 10),
(651, 104, 'S', 'White', 10),
(652, 104, 'M', 'White', 10),
(653, 104, 'L', 'White', 10),
(654, 104, 'S', 'Navy Blue', 10),
(655, 104, 'M', 'Navy Blue', 10),
(656, 104, 'L', 'Navy Blue', 10),
(657, 105, 'S', 'Black', 10),
(658, 105, 'M', 'Black', 10),
(659, 105, 'L', 'Black', 10),
(660, 105, 'S', 'White', 10),
(661, 105, 'M', 'White', 10),
(662, 105, 'L', 'White', 10),
(663, 105, 'S', 'Navy Blue', 10),
(664, 105, 'M', 'Navy Blue', 10),
(665, 105, 'L', 'Navy Blue', 10),
(666, 106, 'S', 'Black', 10),
(667, 106, 'M', 'Black', 10),
(668, 106, 'L', 'Black', 10),
(669, 106, 'S', 'White', 10),
(670, 106, 'M', 'White', 10),
(671, 106, 'L', 'White', 10),
(672, 106, 'S', 'Navy Blue', 10),
(673, 106, 'M', 'Navy Blue', 10),
(674, 106, 'L', 'Navy Blue', 10),
(675, 9, 'S', 'Black', 10),
(676, 9, 'M', 'Black', 10),
(677, 9, 'L', 'Black', 10),
(678, 9, 'S', 'White', 10),
(679, 9, 'M', 'White', 10),
(680, 9, 'L', 'White', 10),
(681, 9, 'S', 'Navy Blue', 10),
(682, 9, 'M', 'Navy Blue', 10),
(683, 9, 'L', 'Navy Blue', 10),
(684, 84, 'S', 'Black', 10),
(685, 84, 'M', 'Black', 10),
(686, 84, 'L', 'Black', 10),
(687, 84, 'S', 'White', 10),
(688, 84, 'M', 'White', 10),
(689, 84, 'L', 'White', 10),
(690, 84, 'S', 'Navy Blue', 10),
(691, 84, 'M', 'Navy Blue', 10),
(692, 84, 'L', 'Navy Blue', 10),
(693, 85, 'S', 'Black', 10),
(694, 85, 'M', 'Black', 10),
(695, 85, 'L', 'Black', 10),
(696, 85, 'S', 'White', 10),
(697, 85, 'M', 'White', 10),
(698, 85, 'L', 'White', 10),
(699, 85, 'S', 'Navy Blue', 10),
(700, 85, 'M', 'Navy Blue', 10),
(701, 85, 'L', 'Navy Blue', 10),
(702, 86, 'S', 'Black', 10),
(703, 86, 'M', 'Black', 10),
(704, 86, 'L', 'Black', 10),
(705, 86, 'S', 'White', 10),
(706, 86, 'M', 'White', 10),
(707, 86, 'L', 'White', 10),
(708, 86, 'S', 'Navy Blue', 10),
(709, 86, 'M', 'Navy Blue', 10),
(710, 86, 'L', 'Navy Blue', 10),
(711, 87, 'S', 'Black', 10),
(712, 87, 'M', 'Black', 10),
(713, 87, 'L', 'Black', 10),
(714, 87, 'S', 'White', 10),
(715, 87, 'M', 'White', 10),
(716, 87, 'L', 'White', 10),
(717, 87, 'S', 'Navy Blue', 10),
(718, 87, 'M', 'Navy Blue', 10),
(719, 87, 'L', 'Navy Blue', 10),
(720, 88, 'S', 'Black', 10),
(721, 88, 'M', 'Black', 10),
(722, 88, 'L', 'Black', 10),
(723, 88, 'S', 'White', 10),
(724, 88, 'M', 'White', 10),
(725, 88, 'L', 'White', 10),
(726, 88, 'S', 'Navy Blue', 10),
(727, 88, 'M', 'Navy Blue', 10),
(728, 88, 'L', 'Navy Blue', 10),
(729, 10, 'S', 'Black', 10),
(730, 10, 'M', 'Black', 10),
(731, 10, 'L', 'Black', 10),
(732, 10, 'S', 'White', 10),
(733, 10, 'M', 'White', 10),
(734, 10, 'L', 'White', 10),
(735, 10, 'S', 'Navy Blue', 10),
(736, 10, 'M', 'Navy Blue', 10),
(737, 10, 'L', 'Navy Blue', 10),
(738, 124, 'S', 'Black', 10),
(739, 124, 'M', 'Black', 10),
(740, 124, 'L', 'Black', 10),
(741, 124, 'S', 'White', 10),
(742, 124, 'M', 'White', 10),
(743, 124, 'L', 'White', 10),
(744, 124, 'S', 'Navy Blue', 10),
(745, 124, 'M', 'Navy Blue', 10),
(746, 124, 'L', 'Navy Blue', 10),
(747, 125, 'S', 'Black', 10),
(748, 125, 'M', 'Black', 10),
(749, 125, 'L', 'Black', 10),
(750, 125, 'S', 'White', 10),
(751, 125, 'M', 'White', 10),
(752, 125, 'L', 'White', 10),
(753, 125, 'S', 'Navy Blue', 10),
(754, 125, 'M', 'Navy Blue', 10),
(755, 125, 'L', 'Navy Blue', 10),
(756, 126, 'S', 'Black', 10),
(757, 126, 'M', 'Black', 10),
(758, 126, 'L', 'Black', 10),
(759, 126, 'S', 'White', 10),
(760, 126, 'M', 'White', 10),
(761, 126, 'L', 'White', 10),
(762, 126, 'S', 'Navy Blue', 10),
(763, 126, 'M', 'Navy Blue', 10),
(764, 126, 'L', 'Navy Blue', 10),
(765, 127, 'S', 'Black', 10),
(766, 127, 'M', 'Black', 10),
(767, 127, 'L', 'Black', 10),
(768, 127, 'S', 'White', 10),
(769, 127, 'M', 'White', 10),
(770, 127, 'L', 'White', 10),
(771, 127, 'S', 'Navy Blue', 10),
(772, 127, 'M', 'Navy Blue', 10),
(773, 127, 'L', 'Navy Blue', 10),
(774, 128, 'S', 'Black', 10),
(775, 128, 'M', 'Black', 10),
(776, 128, 'L', 'Black', 10),
(777, 128, 'S', 'White', 10),
(778, 128, 'M', 'White', 10),
(779, 128, 'L', 'White', 10),
(780, 128, 'S', 'Navy Blue', 10),
(781, 128, 'M', 'Navy Blue', 10),
(782, 128, 'L', 'Navy Blue', 10),
(783, 11, 'S', 'Black', 10),
(784, 11, 'M', 'Black', 10),
(785, 11, 'L', 'Black', 10),
(786, 11, 'S', 'White', 10),
(787, 11, 'M', 'White', 10),
(788, 11, 'L', 'White', 10),
(789, 11, 'S', 'Navy Blue', 10),
(790, 11, 'M', 'Navy Blue', 10),
(791, 11, 'L', 'Navy Blue', 10),
(792, 96, 'S', 'Black', 10),
(793, 96, 'M', 'Black', 10),
(794, 96, 'L', 'Black', 10),
(795, 96, 'S', 'White', 10),
(796, 96, 'M', 'White', 10),
(797, 96, 'L', 'White', 10),
(798, 96, 'S', 'Navy Blue', 10),
(799, 96, 'M', 'Navy Blue', 10),
(800, 96, 'L', 'Navy Blue', 10),
(801, 97, 'S', 'Black', 10),
(802, 97, 'M', 'Black', 10),
(803, 97, 'L', 'Black', 10),
(804, 97, 'S', 'White', 10),
(805, 97, 'M', 'White', 10),
(806, 97, 'L', 'White', 10),
(807, 97, 'S', 'Navy Blue', 10),
(808, 97, 'M', 'Navy Blue', 10),
(809, 97, 'L', 'Navy Blue', 10),
(810, 98, 'S', 'Black', 10),
(811, 98, 'M', 'Black', 10),
(812, 98, 'L', 'Black', 10),
(813, 98, 'S', 'White', 10),
(814, 98, 'M', 'White', 10),
(815, 98, 'L', 'White', 10),
(816, 98, 'S', 'Navy Blue', 10),
(817, 98, 'M', 'Navy Blue', 10),
(818, 98, 'L', 'Navy Blue', 10),
(819, 99, 'S', 'Black', 10),
(820, 99, 'M', 'Black', 10),
(821, 99, 'L', 'Black', 10),
(822, 99, 'S', 'White', 10),
(823, 99, 'M', 'White', 10),
(824, 99, 'L', 'White', 10),
(825, 99, 'S', 'Navy Blue', 10),
(826, 99, 'M', 'Navy Blue', 10),
(827, 99, 'L', 'Navy Blue', 10),
(828, 100, 'S', 'Black', 10),
(829, 100, 'M', 'Black', 10),
(830, 100, 'L', 'Black', 10),
(831, 100, 'S', 'White', 10),
(832, 100, 'M', 'White', 10),
(833, 100, 'L', 'White', 10),
(834, 100, 'S', 'Navy Blue', 10),
(835, 100, 'M', 'Navy Blue', 10),
(836, 100, 'L', 'Navy Blue', 10),
(837, 22, 'S', 'Black', 10),
(838, 22, 'M', 'Black', 10),
(839, 22, 'L', 'Black', 10),
(840, 22, 'S', 'White', 10),
(841, 22, 'M', 'White', 10),
(842, 22, 'L', 'White', 10),
(843, 22, 'S', 'Navy Blue', 10),
(844, 22, 'M', 'Navy Blue', 10),
(845, 22, 'L', 'Navy Blue', 10),
(846, 23, 'S', 'Black', 10),
(847, 23, 'M', 'Black', 10),
(848, 23, 'L', 'Black', 10),
(849, 23, 'S', 'White', 10),
(850, 23, 'M', 'White', 10),
(851, 23, 'L', 'White', 10),
(852, 23, 'S', 'Navy Blue', 10),
(853, 23, 'M', 'Navy Blue', 10),
(854, 23, 'L', 'Navy Blue', 10),
(855, 24, 'S', 'Black', 10),
(856, 24, 'M', 'Black', 10),
(857, 24, 'L', 'Black', 10),
(858, 24, 'S', 'White', 10),
(859, 24, 'M', 'White', 10),
(860, 24, 'L', 'White', 10),
(861, 24, 'S', 'Navy Blue', 10),
(862, 24, 'M', 'Navy Blue', 10),
(863, 24, 'L', 'Navy Blue', 10),
(864, 25, 'S', 'Black', 10),
(865, 25, 'M', 'Black', 10),
(866, 25, 'L', 'Black', 10),
(867, 25, 'S', 'White', 10),
(868, 25, 'M', 'White', 10),
(869, 25, 'L', 'White', 10),
(870, 25, 'S', 'Navy Blue', 10),
(871, 25, 'M', 'Navy Blue', 10),
(872, 25, 'L', 'Navy Blue', 10),
(873, 26, 'S', 'Black', 10),
(874, 26, 'M', 'Black', 10),
(875, 26, 'L', 'Black', 10),
(876, 26, 'S', 'White', 10),
(877, 26, 'M', 'White', 10),
(878, 26, 'L', 'White', 10),
(879, 26, 'S', 'Navy Blue', 10),
(880, 26, 'M', 'Navy Blue', 10),
(881, 26, 'L', 'Navy Blue', 10),
(882, 27, 'S', 'Black', 10),
(883, 27, 'M', 'Black', 10),
(884, 27, 'L', 'Black', 10),
(885, 27, 'S', 'White', 10),
(886, 27, 'M', 'White', 10),
(887, 27, 'L', 'White', 10),
(888, 27, 'S', 'Navy Blue', 10),
(889, 27, 'M', 'Navy Blue', 10),
(890, 27, 'L', 'Navy Blue', 10),
(891, 28, 'S', 'Black', 10),
(892, 28, 'M', 'Black', 10),
(893, 28, 'L', 'Black', 10),
(894, 28, 'S', 'White', 10),
(895, 28, 'M', 'White', 10),
(896, 28, 'L', 'White', 10),
(897, 28, 'S', 'Navy Blue', 10),
(898, 28, 'M', 'Navy Blue', 10),
(899, 28, 'L', 'Navy Blue', 10),
(900, 29, 'S', 'Black', 10),
(901, 29, 'M', 'Black', 10),
(902, 29, 'L', 'Black', 10),
(903, 29, 'S', 'White', 10),
(904, 29, 'M', 'White', 10),
(905, 29, 'L', 'White', 10),
(906, 29, 'S', 'Navy Blue', 10),
(907, 29, 'M', 'Navy Blue', 10),
(908, 29, 'L', 'Navy Blue', 10),
(909, 30, 'S', 'Black', 10),
(910, 30, 'M', 'Black', 10),
(911, 30, 'L', 'Black', 10),
(912, 30, 'S', 'White', 10),
(913, 30, 'M', 'White', 10),
(914, 30, 'L', 'White', 10),
(915, 30, 'S', 'Navy Blue', 10),
(916, 30, 'M', 'Navy Blue', 10),
(917, 30, 'L', 'Navy Blue', 10),
(918, 31, 'S', 'Black', 10),
(919, 31, 'M', 'Black', 10),
(920, 31, 'L', 'Black', 10),
(921, 31, 'S', 'White', 10),
(922, 31, 'M', 'White', 10),
(923, 31, 'L', 'White', 10),
(924, 31, 'S', 'Navy Blue', 10),
(925, 31, 'M', 'Navy Blue', 10),
(926, 31, 'L', 'Navy Blue', 10),
(927, 32, 'S', 'Black', 10),
(928, 32, 'M', 'Black', 10),
(929, 32, 'L', 'Black', 10),
(930, 32, 'S', 'White', 10),
(931, 32, 'M', 'White', 10),
(932, 32, 'L', 'White', 10),
(933, 32, 'S', 'Navy Blue', 10),
(934, 32, 'M', 'Navy Blue', 10),
(935, 32, 'L', 'Navy Blue', 10),
(936, 33, 'S', 'Black', 10),
(937, 33, 'M', 'Black', 10),
(938, 33, 'L', 'Black', 10),
(939, 33, 'S', 'White', 10),
(940, 33, 'M', 'White', 10),
(941, 33, 'L', 'White', 10),
(942, 33, 'S', 'Navy Blue', 10),
(943, 33, 'M', 'Navy Blue', 10),
(944, 33, 'L', 'Navy Blue', 10),
(945, 46, 'S', 'Black', 10),
(946, 46, 'M', 'Black', 10),
(947, 46, 'L', 'Black', 10),
(948, 46, 'S', 'White', 10),
(949, 46, 'M', 'White', 10),
(950, 46, 'L', 'White', 10),
(951, 46, 'S', 'Navy Blue', 10),
(952, 46, 'M', 'Navy Blue', 10),
(953, 46, 'L', 'Navy Blue', 10),
(954, 47, 'S', 'Black', 10),
(955, 47, 'M', 'Black', 10),
(956, 47, 'L', 'Black', 10),
(957, 47, 'S', 'White', 10),
(958, 47, 'M', 'White', 10),
(959, 47, 'L', 'White', 10),
(960, 47, 'S', 'Navy Blue', 10),
(961, 47, 'M', 'Navy Blue', 10),
(962, 47, 'L', 'Navy Blue', 10),
(963, 48, 'S', 'Black', 10),
(964, 48, 'M', 'Black', 10),
(965, 48, 'L', 'Black', 10),
(966, 48, 'S', 'White', 10),
(967, 48, 'M', 'White', 10),
(968, 48, 'L', 'White', 10),
(969, 48, 'S', 'Navy Blue', 10),
(970, 48, 'M', 'Navy Blue', 10),
(971, 48, 'L', 'Navy Blue', 10),
(972, 54, 'S', 'Black', 10),
(973, 54, 'M', 'Black', 10),
(974, 54, 'L', 'Black', 10),
(975, 54, 'S', 'White', 10),
(976, 54, 'M', 'White', 10),
(977, 54, 'L', 'White', 10),
(978, 54, 'S', 'Navy Blue', 10),
(979, 54, 'M', 'Navy Blue', 10),
(980, 54, 'L', 'Navy Blue', 10),
(981, 55, 'S', 'Black', 10),
(982, 55, 'M', 'Black', 10),
(983, 55, 'L', 'Black', 10),
(984, 55, 'S', 'White', 10),
(985, 55, 'M', 'White', 10),
(986, 55, 'L', 'White', 10),
(987, 55, 'S', 'Navy Blue', 10),
(988, 55, 'M', 'Navy Blue', 10),
(989, 55, 'L', 'Navy Blue', 10),
(990, 122, 'S', 'Black', 10),
(991, 122, 'M', 'Black', 10),
(992, 122, 'L', 'Black', 10),
(993, 122, 'S', 'White', 10),
(994, 122, 'M', 'White', 10),
(995, 122, 'L', 'White', 10),
(996, 122, 'S', 'Navy Blue', 10),
(997, 122, 'M', 'Navy Blue', 10),
(998, 122, 'L', 'Navy Blue', 10),
(999, 12, 'S', 'Black', 10),
(1000, 12, 'M', 'Black', 10),
(1001, 12, 'L', 'Black', 10),
(1002, 12, 'S', 'White', 10),
(1003, 12, 'M', 'White', 10),
(1004, 12, 'L', 'White', 10),
(1005, 12, 'S', 'Navy Blue', 10),
(1006, 12, 'M', 'Navy Blue', 10),
(1007, 12, 'L', 'Navy Blue', 10),
(1008, 13, 'S', 'Black', 10),
(1009, 13, 'M', 'Black', 10),
(1010, 13, 'L', 'Black', 10),
(1011, 13, 'S', 'White', 10),
(1012, 13, 'M', 'White', 10),
(1013, 13, 'L', 'White', 10),
(1014, 13, 'S', 'Navy Blue', 10),
(1015, 13, 'M', 'Navy Blue', 10),
(1016, 13, 'L', 'Navy Blue', 10),
(1017, 14, 'S', 'Black', 10),
(1018, 14, 'M', 'Black', 10),
(1019, 14, 'L', 'Black', 10),
(1020, 14, 'S', 'White', 10),
(1021, 14, 'M', 'White', 10),
(1022, 14, 'L', 'White', 10),
(1023, 14, 'S', 'Navy Blue', 10),
(1024, 14, 'M', 'Navy Blue', 10),
(1025, 14, 'L', 'Navy Blue', 10),
(1026, 15, 'S', 'Black', 10),
(1027, 15, 'M', 'Black', 10),
(1028, 15, 'L', 'Black', 10),
(1029, 15, 'S', 'White', 10),
(1030, 15, 'M', 'White', 10),
(1031, 15, 'L', 'White', 10),
(1032, 15, 'S', 'Navy Blue', 10),
(1033, 15, 'M', 'Navy Blue', 10),
(1034, 15, 'L', 'Navy Blue', 10),
(1035, 16, 'S', 'Black', 10),
(1036, 16, 'M', 'Black', 10),
(1037, 16, 'L', 'Black', 10),
(1038, 16, 'S', 'White', 10),
(1039, 16, 'M', 'White', 10),
(1040, 16, 'L', 'White', 10),
(1041, 16, 'S', 'Navy Blue', 10),
(1042, 16, 'M', 'Navy Blue', 10),
(1043, 16, 'L', 'Navy Blue', 10),
(1044, 17, 'S', 'Black', 10),
(1045, 17, 'M', 'Black', 10),
(1046, 17, 'L', 'Black', 10),
(1047, 17, 'S', 'White', 10),
(1048, 17, 'M', 'White', 10),
(1049, 17, 'L', 'White', 10),
(1050, 17, 'S', 'Navy Blue', 10),
(1051, 17, 'M', 'Navy Blue', 10),
(1052, 17, 'L', 'Navy Blue', 10),
(1053, 18, 'S', 'Black', 10),
(1054, 18, 'M', 'Black', 10),
(1055, 18, 'L', 'Black', 10),
(1056, 18, 'S', 'White', 10),
(1057, 18, 'M', 'White', 10),
(1058, 18, 'L', 'White', 10),
(1059, 18, 'S', 'Navy Blue', 10),
(1060, 18, 'M', 'Navy Blue', 10),
(1061, 18, 'L', 'Navy Blue', 10),
(1062, 19, 'S', 'Black', 10),
(1063, 19, 'M', 'Black', 10),
(1064, 19, 'L', 'Black', 10),
(1065, 19, 'S', 'White', 10),
(1066, 19, 'M', 'White', 10),
(1067, 19, 'L', 'White', 10),
(1068, 19, 'S', 'Navy Blue', 10),
(1069, 19, 'M', 'Navy Blue', 10),
(1070, 19, 'L', 'Navy Blue', 10),
(1071, 20, 'S', 'Black', 10),
(1072, 20, 'M', 'Black', 10),
(1073, 20, 'L', 'Black', 10),
(1074, 20, 'S', 'White', 10),
(1075, 20, 'M', 'White', 10),
(1076, 20, 'L', 'White', 10),
(1077, 20, 'S', 'Navy Blue', 10),
(1078, 20, 'M', 'Navy Blue', 10),
(1079, 20, 'L', 'Navy Blue', 10),
(1080, 21, 'S', 'Black', 10),
(1081, 21, 'M', 'Black', 10),
(1082, 21, 'L', 'Black', 10),
(1083, 21, 'S', 'White', 10),
(1084, 21, 'M', 'White', 10),
(1085, 21, 'L', 'White', 10),
(1086, 21, 'S', 'Navy Blue', 10),
(1087, 21, 'M', 'Navy Blue', 10),
(1088, 21, 'L', 'Navy Blue', 10),
(1089, 49, 'S', 'Black', 10),
(1090, 49, 'M', 'Black', 10),
(1091, 49, 'L', 'Black', 10),
(1092, 49, 'S', 'White', 10),
(1093, 49, 'M', 'White', 10),
(1094, 49, 'L', 'White', 10),
(1095, 49, 'S', 'Navy Blue', 10),
(1096, 49, 'M', 'Navy Blue', 10),
(1097, 49, 'L', 'Navy Blue', 10),
(1098, 50, 'S', 'Black', 10),
(1099, 50, 'M', 'Black', 10),
(1100, 50, 'L', 'Black', 10),
(1101, 50, 'S', 'White', 10),
(1102, 50, 'M', 'White', 10),
(1103, 50, 'L', 'White', 10),
(1104, 50, 'S', 'Navy Blue', 10),
(1105, 50, 'M', 'Navy Blue', 10),
(1106, 50, 'L', 'Navy Blue', 10),
(1107, 51, 'S', 'Black', 10),
(1108, 51, 'M', 'Black', 10),
(1109, 51, 'L', 'Black', 10),
(1110, 51, 'S', 'White', 10),
(1111, 51, 'M', 'White', 10),
(1112, 51, 'L', 'White', 10),
(1113, 51, 'S', 'Navy Blue', 10),
(1114, 51, 'M', 'Navy Blue', 10),
(1115, 51, 'L', 'Navy Blue', 10),
(1116, 52, 'S', 'Black', 10),
(1117, 52, 'M', 'Black', 10),
(1118, 52, 'L', 'Black', 10),
(1119, 52, 'S', 'White', 10),
(1120, 52, 'M', 'White', 10),
(1121, 52, 'L', 'White', 10),
(1122, 52, 'S', 'Navy Blue', 10),
(1123, 52, 'M', 'Navy Blue', 10),
(1124, 52, 'L', 'Navy Blue', 10),
(1125, 53, 'S', 'Black', 10),
(1126, 53, 'M', 'Black', 10),
(1127, 53, 'L', 'Black', 10),
(1128, 53, 'S', 'White', 10),
(1129, 53, 'M', 'White', 10),
(1130, 53, 'L', 'White', 10),
(1131, 53, 'S', 'Navy Blue', 10),
(1132, 53, 'M', 'Navy Blue', 10),
(1133, 53, 'L', 'Navy Blue', 10);

-- --------------------------------------------------------

--
-- Table structure for table `subcategories`
--

CREATE TABLE `subcategories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `category_id` int(11) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subcategories`
--

INSERT INTO `subcategories` (`id`, `name`, `category_id`, `description`, `image_url`) VALUES
(1, 'A Line', 1, 'A Line products for weddings and special occasions.', NULL),
(2, 'Mermaid', 1, 'Mermaid products for weddings and special occasions.', NULL),
(3, 'Princess', 1, 'Princess products for weddings and special occasions.', NULL),
(4, 'Royal', 1, 'Royal products for weddings and special occasions.', NULL),
(5, 'Sheath', 1, 'Sheath products for weddings and special occasions.', NULL),
(6, 'Gloves', 2, 'Gloves products for weddings and special occasions.', NULL),
(7, 'Jewelry', 2, 'Jewelry products for weddings and special occasions.', NULL),
(8, 'Tiaras And Crowns', 2, 'Tiaras And Crowns products for weddings and special occasions.', NULL),
(9, 'Veils', 2, 'Veils products for weddings and special occasions.', NULL),
(10, '3 Piece Suit', 3, '3 Piece Suit products for weddings and special occasions.', NULL),
(11, 'Double Breasted Suit', 3, 'Double Breasted Suit products for weddings and special occasions.', NULL),
(12, 'Regular Fit', 3, 'Regular Fit products for weddings and special occasions.', NULL),
(13, 'Slim Fit', 3, 'Slim Fit products for weddings and special occasions.', NULL),
(14, 'Tuxedo', 3, 'Tuxedo products for weddings and special occasions.', NULL),
(15, 'Derby', 4, 'Derby products for weddings and special occasions.', NULL),
(16, 'Loafers', 4, 'Loafers products for weddings and special occasions.', NULL),
(17, 'Monk Strap', 4, 'Monk Strap products for weddings and special occasions.', NULL),
(18, 'Oxford', 4, 'Oxford products for weddings and special occasions.', NULL),
(19, 'Bloch Heels', 5, 'Bloch Heels products for weddings and special occasions.', NULL),
(20, 'High Heels', 5, 'High Heels products for weddings and special occasions.', NULL),
(21, 'Bow Ties', 6, 'Bow Ties products for weddings and special occasions.', NULL),
(22, 'Neck Ties', 6, 'Neck Ties products for weddings and special occasions.', NULL),
(23, 'Pocket Squares', 6, 'Pocket Squares products for weddings and special occasions.', NULL),
(24, 'Bridal Sandal', 7, 'Bridal Sandal products for weddings and special occasions.', NULL),
(25, 'Flats', 7, 'Flats products for weddings and special occasions.', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `password_hash`, `phone`, `created_at`, `is_active`) VALUES
(9001, 'Sara Ahmad', 'dashboard.sara@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0791111111', '2026-01-10 07:00:00', 1),
(9002, 'Lina Khaled', 'dashboard.lina@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0792222222', '2026-02-15 08:00:00', 1),
(9003, 'Omar Ali', 'dashboard.omar@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0793333333', '2026-03-20 09:00:00', 1),
(9004, 'Dana Mohammad', 'dashboard.dana@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0794444444', '2026-04-12 10:00:00', 1),
(9005, 'Rama Hasan', 'dashboard.rama@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0795555555', '2026-05-18 11:00:00', 1),
(9006, 'Amro Abdelgader', 'amroabd2003@gmail.com', '$2y$10$39L7hDrRc8Pv0vuJ.jgmfeArUFeCT1aS7Hyz5QBMhXc.KIEq0SXJ.', NULL, '2026-11-01 19:13:55', 1);

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cart_id` (`cart_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `variant_id` (`variant_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `variant_id` (`variant_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subcategory_id` (`subcategory_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `subcategories`
--
ALTER TABLE `subcategories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9018;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=170;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=262;

--
-- AUTO_INCREMENT for table `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1525;

--
-- AUTO_INCREMENT for table `subcategories`
--
ALTER TABLE `subcategories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9007;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `address`
--
ALTER TABLE `address`
  ADD CONSTRAINT `address_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_ibfk_3` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `order_items_ibfk_3` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`subcategory_id`) REFERENCES `subcategories` (`id`);

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD CONSTRAINT `product_variants_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subcategories`
--
ALTER TABLE `subcategories`
  ADD CONSTRAINT `subcategories_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

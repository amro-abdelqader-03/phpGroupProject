-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 28, 2026 at 11:28 PM
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
-- Table structure for table `addresses`
--

CREATE TABLE `addresses` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `city` varchar(100) NOT NULL,
  `area` varchar(100) NOT NULL,
  `street` varchar(150) NOT NULL,
  `building_no` varchar(20) DEFAULT NULL,
  `address_details` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `full_name`, `email`, `password_hash`, `created_at`) VALUES
(1, 'Admin', 'admin@example.com', '$2y$10$/uQXKAsaEhacW7MEyximYObCYoVhLLNnqBwPXHtHgMIShdrb1Y6wC', '2026-10-28 22:15:00');

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ;

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` int(11) NOT NULL,
  `cart_id` int(11) NOT NULL,
  `variant_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1
) ;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `status`) VALUES
(1, 'Casual Suit', 'Category for Casual Suit', 'active'),
(2, 'Derby', 'Category for Derby', 'active'),
(3, 'Loafers', 'Category for Loafers', 'active'),
(4, 'Monk Strap', 'Category for Monk Strap', 'active'),
(5, 'Oxford', 'Category for Oxford', 'active'),
(6, 'Slim fit', 'Category for Slim fit', 'active'),
(7, 'Summer Suit', 'Category for Summer Suit', 'active'),
(8, 'accessories', 'Category for accessories', 'active'),
(9, 'classic suits', 'Category for classic suits', 'active'),
(10, 'dress', 'Category for dress', 'active'),
(11, 'tuxido', 'Category for tuxido', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `address_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `order_status` enum('confirmed','shipped','delivered','cancelled') NOT NULL DEFAULT 'confirmed',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `delivery_date` date DEFAULT NULL
) ;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `variant_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL
) ;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `payment_method` enum('credit_card','cash_on_delivery') NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `paid_at` datetime DEFAULT NULL
) ;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `subcategory_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `subcategory_id`, `name`, `description`, `price`, `status`, `created_at`) VALUES
(1, 1, '01 Addison Satin Dress White 0004', 'High quality Satin Dress from our dress collection.', 217.00, 'active', '2026-10-28 22:24:38'),
(4, 2, '12Orellia Dress Ivory 0020', 'High quality Formal Dress from our dress collection.', 236.00, 'active', '2026-10-28 22:24:38'),
(5, 3, '19 Myla Maxi Dress White 0001', 'High quality Maxi Dress from our dress collection.', 278.00, 'active', '2026-10-28 22:24:38'),
(11, 4, '20230511 Meshki Ecomm Deesee7077', 'High quality Party Dress from our dress collection.', 185.00, 'active', '2026-10-28 22:24:38'),
(17, 5, '250128 Meshki Somethingblue4 02 064', 'High quality Evening Dress from our dress collection.', 235.00, 'active', '2026-10-28 22:24:38'),
(18, 5, '250128 Meshki Somethingblue4 05 185', 'High quality Evening Dress from our dress collection.', 262.00, 'active', '2026-10-28 22:24:38'),
(21, 5, '250128 Meshki Somethingblue4 26 1085', 'High quality Evening Dress from our dress collection.', 293.00, 'active', '2026-10-28 22:24:38'),
(25, 5, '250128 Meshki Somethingblue4 29 1231', 'High quality Evening Dress from our dress collection.', 224.00, 'active', '2026-10-28 22:24:38'),
(29, 5, '250128 Meshki Somethingblue4 44 1834', 'High quality Evening Dress from our dress collection.', 305.00, 'active', '2026-10-28 22:24:38'),
(33, 4, '250324 Meshki Hertiagefinal 09 392', 'High quality Party Dress from our dress collection.', 180.00, 'active', '2026-10-28 22:24:38'),
(37, 6, '250708 Meshki Bridal 9605X E1C4094F 7Ecd 48B8 85C6 A95A0Deb3937', 'High quality Bridal Dress from our dress collection.', 433.00, 'active', '2026-10-28 22:24:38'),
(38, 6, '250708 Meshki Bridal 9697 2 X', 'High quality Bridal Dress from our dress collection.', 365.00, 'active', '2026-10-28 22:24:38'),
(39, 6, '250716 Meshki Bridal1 04 210', 'High quality Bridal Dress from our dress collection.', 427.00, 'active', '2026-10-28 22:24:38'),
(44, 6, '250716 Meshki Bridal1 05 269', 'High quality Bridal Dress from our dress collection.', 310.00, 'active', '2026-10-28 22:24:38'),
(50, 6, '250716 Meshki Bridal1 20 1040', 'High quality Bridal Dress from our dress collection.', 429.00, 'active', '2026-10-28 22:24:38'),
(54, 6, '250716 Meshki Bridal1 21 1071 4E95Aa4B 24Eb 4232 968F E7F29De9Bdd9 800X800', 'High quality Bridal Dress from our dress collection.', 401.00, 'active', '2026-10-28 22:24:38'),
(55, 6, '250716 Meshki Bridal1 21 1081 800X800', 'High quality Bridal Dress from our dress collection.', 444.00, 'active', '2026-10-28 22:24:38'),
(56, 6, '250716 Meshki Bridal1 21 1087 4E0Dabd9 Da60 4B41 A0A2 B2995Fcac61E 800X800', 'High quality Bridal Dress from our dress collection.', 375.00, 'active', '2026-10-28 22:24:38'),
(58, 6, '250721 Meshki Bridal2 01 024', 'High quality Bridal Dress from our dress collection.', 325.00, 'active', '2026-10-28 22:24:38'),
(62, 4, '250910 Meshki Womeninpower5 23 1002', 'High quality Party Dress from our dress collection.', 178.00, 'active', '2026-10-28 22:24:38'),
(67, 5, '260113 Meshki Reshoots Somethingblue1 28 687', 'High quality Evening Dress from our dress collection.', 287.00, 'active', '2026-10-28 22:24:38'),
(73, 5, '260113 Meshki Reshoots Somethingblue1 31 848', 'High quality Evening Dress from our dress collection.', 242.00, 'active', '2026-10-28 22:24:38'),
(77, 5, '260114 Meshki Somethingblue1 01 003 1', 'High quality Evening Dress from our dress collection.', 307.00, 'active', '2026-10-28 22:24:38'),
(78, 5, '260114 Meshki Somethingblue1 01 015', 'High quality Evening Dress from our dress collection.', 289.00, 'active', '2026-10-28 22:24:38'),
(82, 5, '260114 Meshki Somethingblue1 06 257', 'High quality Evening Dress from our dress collection.', 223.00, 'active', '2026-10-28 22:24:38'),
(85, 5, '260114 Meshki Somethingblue1 07 319', 'High quality Evening Dress from our dress collection.', 252.00, 'active', '2026-10-28 22:24:38'),
(90, 5, '260114 Meshki Somethingblue1 12 550', 'High quality Evening Dress from our dress collection.', 311.00, 'active', '2026-10-28 22:24:38'),
(94, 5, '260114 Meshki Somethingblue1 25 1099', 'High quality Evening Dress from our dress collection.', 279.00, 'active', '2026-10-28 22:24:38'),
(98, 5, '260127 Meshki Somethingblue3 21 699', 'High quality Evening Dress from our dress collection.', 310.00, 'active', '2026-10-28 22:24:38'),
(103, 4, '260219 Meshki Reshoots 35 Dr10926Wd3 Ivy 1123', 'High quality Party Dress from our dress collection.', 167.00, 'active', '2026-10-28 22:24:38'),
(106, 4, '260325 Meshki Reshoots Hardflash 40 860', 'High quality Party Dress from our dress collection.', 199.00, 'active', '2026-10-28 22:24:38'),
(114, 4, '260325 Meshki Reshoots Hardflash 41 904', 'High quality Party Dress from our dress collection.', 234.00, 'active', '2026-10-28 22:24:38'),
(118, 7, 'Atelier Photo 36Cc2F85A7', 'High quality General Accessory from our accessories collection.', 50.00, 'active', '2026-10-28 22:24:38'),
(119, 7, 'Couture Photo 896Cd9Eb00', 'High quality General Accessory from our accessories collection.', 52.00, 'active', '2026-10-28 22:24:38'),
(120, 7, 'Lorenso Photo 1 020A458630', 'High quality General Accessory from our accessories collection.', 89.00, 'active', '2026-10-28 22:24:38'),
(121, 7, 'Medium 0 B3 A5513 7F02A33D0C', 'High quality General Accessory from our accessories collection.', 52.00, 'active', '2026-10-28 22:24:38'),
(133, 8, 'Medium 10209 Elisora Sleeves 5894Ed7643', 'High quality Sleeves from our accessories collection.', 78.00, 'active', '2026-10-28 22:24:38'),
(134, 9, 'Medium 10231 Gleam Bolero 9Ac46E405F', 'High quality Bolero from our accessories collection.', 125.00, 'active', '2026-10-28 22:24:38'),
(135, 10, 'Medium 12911 Ayelora Cover Dress Eb1892F99D', 'High quality Cover Dress from our accessories collection.', 161.00, 'active', '2026-10-28 22:24:38'),
(136, 11, 'Medium 12926 Botticella Train 7118133A83', 'High quality Train from our accessories collection.', 182.00, 'active', '2026-10-28 22:24:38'),
(137, 12, 'Medium 12935 Brave Cape 4C92539Efd', 'High quality Cape from our accessories collection.', 148.00, 'active', '2026-10-28 22:24:38'),
(138, 11, 'Medium 12960 Caravellia Train 3A5A5Af008', 'High quality Train from our accessories collection.', 189.00, 'active', '2026-10-28 22:24:38'),
(139, 13, 'Medium 13006 Darielle Gloves 1Fc7E77280', 'High quality Gloves from our accessories collection.', 44.00, 'active', '2026-10-28 22:24:38'),
(140, 14, 'Medium 13128 Ellarosea Scarf C16Db63001', 'High quality Scarf from our accessories collection.', 90.00, 'active', '2026-10-28 22:24:38'),
(141, 14, 'Medium 13181 Olivara Scarf 299D64Cf3F', 'High quality Scarf from our accessories collection.', 75.00, 'active', '2026-10-28 22:24:38'),
(142, 12, 'Medium 13190 Ossyn Cape 805608A10E', 'High quality Cape from our accessories collection.', 131.00, 'active', '2026-10-28 22:24:38'),
(143, 15, 'Medium 13218 Ossyn Garter 2115Bad564', 'High quality Garter from our accessories collection.', 44.00, 'active', '2026-10-28 22:24:38'),
(144, 10, 'Medium 13227 Priss Cover Dress A7392Fa09B', 'High quality Cover Dress from our accessories collection.', 190.00, 'active', '2026-10-28 22:24:38'),
(145, 9, 'Medium 13274 Sylvielle Bolero 1D216Deb0E', 'High quality Bolero from our accessories collection.', 143.00, 'active', '2026-10-28 22:24:38'),
(146, 9, 'Medium 13297 Thessara Bolero Ce0F0F14Fa', 'High quality Bolero from our accessories collection.', 117.00, 'active', '2026-10-28 22:24:38'),
(147, 16, 'Medium 13961 Lyrielle Veil 9378Ccde8E', 'High quality Veil from our accessories collection.', 102.00, 'active', '2026-10-28 22:24:38'),
(148, 8, 'Medium 13978 Arvenna Sleeves 5Ac3A02797', 'High quality Sleeves from our accessories collection.', 64.00, 'active', '2026-10-28 22:24:38'),
(149, 16, 'Medium 13990 Annamaria Veil 474C06121A', 'High quality Veil from our accessories collection.', 129.00, 'active', '2026-10-28 22:24:38'),
(150, 8, 'Medium 14006 Calivora Sleeves C2Bd22Ec9C', 'High quality Sleeves from our accessories collection.', 81.00, 'active', '2026-10-28 22:24:38'),
(151, 16, 'Medium 14008 Calivora Veil 3B4D50F1D4', 'High quality Veil from our accessories collection.', 104.00, 'active', '2026-10-28 22:24:38'),
(152, 16, 'Medium 14036 Charelle Veil 6D664Faa5E', 'High quality Veil from our accessories collection.', 108.00, 'active', '2026-10-28 22:24:38'),
(153, 17, 'Medium 14045 Elarinne Choker 8Ee9B459A0', 'High quality Choker from our accessories collection.', 63.00, 'active', '2026-10-28 22:24:38'),
(154, 10, 'Medium 14052 Elthera Cover Dress B3Ee81E623', 'High quality Cover Dress from our accessories collection.', 203.00, 'active', '2026-10-28 22:24:38'),
(155, 14, 'Medium 14063 Elyssar Scarf 64Bfdfa7F5', 'High quality Scarf from our accessories collection.', 73.00, 'active', '2026-10-28 22:24:38'),
(156, 14, 'Medium 14093 Caladriel Scarf 05Bdaaaedb', 'High quality Scarf from our accessories collection.', 94.00, 'active', '2026-10-28 22:24:38'),
(157, 9, 'Medium 14112 Liania Bolero E60Aaa2B9A', 'High quality Bolero from our accessories collection.', 130.00, 'active', '2026-10-28 22:24:38'),
(158, 7, 'Medium 14135 Norienne E1Bc52140C', 'High quality General Accessory from our accessories collection.', 79.00, 'active', '2026-10-28 22:24:38'),
(159, 10, 'Medium 14170 Cosima Cover Dress 788302Ea83', 'High quality Cover Dress from our accessories collection.', 141.00, 'active', '2026-10-28 22:24:38'),
(160, 9, 'Medium 14182 Olvaria Bolero 87C6Cbc957', 'High quality Bolero from our accessories collection.', 130.00, 'active', '2026-10-28 22:24:38'),
(161, 13, 'Medium 14186 Olvaria Gloves 115D5Ca035', 'High quality Gloves from our accessories collection.', 54.00, 'active', '2026-10-28 22:24:38'),
(162, 16, 'Medium 14211 Oriane Veil B6Ac1C57Ab', 'High quality Veil from our accessories collection.', 139.00, 'active', '2026-10-28 22:24:38'),
(163, 9, 'Medium 14227 Teyra Bolero 38E49974Af', 'High quality Bolero from our accessories collection.', 123.00, 'active', '2026-10-28 22:24:38'),
(164, 13, 'Medium 14233 Virelina Gloves 883E6B9217', 'High quality Gloves from our accessories collection.', 42.00, 'active', '2026-10-28 22:24:38'),
(165, 14, 'Medium 14244 Charelle Scarf 20C5036Cf6', 'High quality Scarf from our accessories collection.', 50.00, 'active', '2026-10-28 22:24:38'),
(166, 13, 'Medium 14310 Odyssia Gloves Cd70Af5Fa2', 'High quality Gloves from our accessories collection.', 45.00, 'active', '2026-10-28 22:24:38'),
(167, 9, 'Medium 14332 Opera Bolero Bd3E41C966', 'High quality Bolero from our accessories collection.', 91.00, 'active', '2026-10-28 22:24:38'),
(168, 17, 'Medium 14339 Ossyn Choker F81Fd2Eacb', 'High quality Choker from our accessories collection.', 56.00, 'active', '2026-10-28 22:24:38'),
(169, 9, 'Medium 14423 Vittoria Bolero 350Dc25D77', 'High quality Bolero from our accessories collection.', 125.00, 'active', '2026-10-28 22:24:38'),
(170, 11, 'Medium 14463 Olvaria Train 5D774E96Cf', 'High quality Train from our accessories collection.', 146.00, 'active', '2026-10-28 22:24:38'),
(171, 11, 'Medium 16369 Lyxora Train 7C1D179813', 'High quality Train from our accessories collection.', 181.00, 'active', '2026-10-28 22:24:38'),
(172, 13, 'Medium 16393 Vega Gloves 026C5A5B73', 'High quality Gloves from our accessories collection.', 66.00, 'active', '2026-10-28 22:24:38'),
(173, 13, 'Medium 16475 Stelly Gloves A2C9B31Bfe', 'High quality Gloves from our accessories collection.', 68.00, 'active', '2026-10-28 22:24:38'),
(174, 12, 'Medium 16480 Lyra Cape C0E6052000', 'High quality Cape from our accessories collection.', 155.00, 'active', '2026-10-28 22:24:38'),
(175, 7, 'Medium 2 N9 A2486 3Bf6B3Ef79', 'High quality General Accessory from our accessories collection.', 70.00, 'active', '2026-10-28 22:24:38'),
(184, 15, 'Medium 8701 Veluna Garter 23Aa374A5A', 'High quality Garter from our accessories collection.', 44.00, 'active', '2026-10-28 22:24:38'),
(185, 7, 'Medium 8722 Averra 652Fc4F455', 'High quality General Accessory from our accessories collection.', 50.00, 'active', '2026-10-28 22:24:38'),
(186, 18, 'Medium 8742 Valeris Hairband 99C0Bc26Dd', 'High quality Hairband from our accessories collection.', 53.00, 'active', '2026-10-28 22:24:38'),
(187, 7, 'Medium 8753 Bravelle 74343A0E41', 'High quality General Accessory from our accessories collection.', 93.00, 'active', '2026-10-28 22:24:38'),
(188, 7, 'Medium 8809 Evolis 7B6F223913', 'High quality General Accessory from our accessories collection.', 68.00, 'active', '2026-10-28 22:24:38'),
(190, 7, 'Medium 8833 Glacerin 8330Df47Cb', 'High quality General Accessory from our accessories collection.', 64.00, 'active', '2026-10-28 22:24:38'),
(191, 11, 'Medium 8865 Rowelle Train Aeb6B116C9', 'High quality Train from our accessories collection.', 169.00, 'active', '2026-10-28 22:24:38'),
(192, 8, 'Medium 8873 Qirelle Sleeves C081Ea4569', 'High quality Sleeves from our accessories collection.', 79.00, 'active', '2026-10-28 22:24:38'),
(193, 7, 'Medium 9058 Zeira Cuffs Be06F39724', 'High quality General Accessory from our accessories collection.', 96.00, 'active', '2026-10-28 22:24:38'),
(194, 12, 'Medium 9529 Josephine Cape C8Faf2Da6B', 'High quality Cape from our accessories collection.', 128.00, 'active', '2026-10-28 22:24:38'),
(195, 8, 'Medium 9680 Audrey Sleeves 330938Ec5E', 'High quality Sleeves from our accessories collection.', 54.00, 'active', '2026-10-28 22:24:38'),
(196, 14, 'Medium 9697 Lady Di Scarf 9893511682', 'High quality Scarf from our accessories collection.', 78.00, 'active', '2026-10-28 22:24:38'),
(197, 12, 'Medium 9702 Isabella Cape 5Eb615695C', 'High quality Cape from our accessories collection.', 127.00, 'active', '2026-10-28 22:24:38'),
(198, 10, 'Medium 9720 Maria Cover Dress Dfb4D2C837', 'High quality Cover Dress from our accessories collection.', 201.00, 'active', '2026-10-28 22:24:38'),
(199, 7, 'Medium Img 2937 1E89C4B1F6', 'High quality General Accessory from our accessories collection.', 86.00, 'active', '2026-10-28 22:24:38'),
(200, 7, 'Medium Milla Nova 25 26 10069 A0106317B4', 'High quality General Accessory from our accessories collection.', 76.00, 'active', '2026-10-28 22:24:38'),
(210, 7, 'Milla Nova 25F03Fe995', 'High quality General Accessory from our accessories collection.', 60.00, 'active', '2026-10-28 22:24:38'),
(211, 7, 'Wl 1 291694D073', 'High quality General Accessory from our accessories collection.', 56.00, 'active', '2026-10-28 22:24:38'),
(212, 19, 'Black 01', 'High quality 01 Antique Rogue Texture Slim from our Casual Suit collection.', 292.00, 'active', '2026-10-28 22:24:38'),
(213, 19, 'Navy 01', 'High quality 01 Antique Rogue Texture Slim from our Casual Suit collection.', 232.00, 'active', '2026-10-28 22:24:38'),
(214, 20, 'Blue 01', 'High quality 02 Limehaus Stretch Slim from our Casual Suit collection.', 292.00, 'active', '2026-10-28 22:24:38'),
(215, 20, 'Charcoal 01', 'High quality 02 Limehaus Stretch Slim from our Casual Suit collection.', 275.00, 'active', '2026-10-28 22:24:38'),
(216, 21, 'Black 01', 'High quality 03 Antique Rogue Texture Regular from our Casual Suit collection.', 200.00, 'active', '2026-10-28 22:24:38'),
(217, 21, 'Navy 01', 'High quality 03 Antique Rogue Texture Regular from our Casual Suit collection.', 217.00, 'active', '2026-10-28 22:24:38'),
(218, 22, 'Blue 01', 'High quality 04 Antique Rogue Maddoc Check from our Casual Suit collection.', 280.00, 'active', '2026-10-28 22:24:38'),
(219, 22, 'Charcoal 01', 'High quality 04 Antique Rogue Maddoc Check from our Casual Suit collection.', 225.00, 'active', '2026-10-28 22:24:38'),
(220, 23, 'Black 01', 'High quality 05 Limehaus Stretch Tailored from our Casual Suit collection.', 306.00, 'active', '2026-10-28 22:24:38'),
(221, 23, 'Navy 01', 'High quality 05 Limehaus Stretch Tailored from our Casual Suit collection.', 239.00, 'active', '2026-10-28 22:24:38'),
(222, 24, 'Grey 01', 'High quality 06 Ted Baker Hopsack Check from our Casual Suit collection.', 355.00, 'active', '2026-10-28 22:24:38'),
(223, 24, 'Navy 01', 'High quality 06 Ted Baker Hopsack Check from our Casual Suit collection.', 390.00, 'active', '2026-10-28 22:24:38'),
(224, 25, 'Black 01', 'High quality 07 Limehaus Stretch Regular from our Casual Suit collection.', 191.00, 'active', '2026-10-28 22:24:38'),
(225, 25, 'Navy 01', 'High quality 07 Limehaus Stretch Regular from our Casual Suit collection.', 309.00, 'active', '2026-10-28 22:24:38'),
(226, 26, 'Blue 01', 'High quality 08 Limehaus Stretch Skinny from our Casual Suit collection.', 278.00, 'active', '2026-10-28 22:24:38'),
(227, 26, 'Charcoal 01', 'High quality 08 Limehaus Stretch Skinny from our Casual Suit collection.', 213.00, 'active', '2026-10-28 22:24:38'),
(228, 27, '240224 Black Suit 5668', 'High quality 01 Sharkskin Back from our classic suits collection.', 285.00, 'active', '2026-10-28 22:24:38'),
(229, 27, '230727 Light Blue Sharkskin Back 6447', 'High quality 01 Sharkskin Back from our classic suits collection.', 223.00, 'active', '2026-10-28 22:24:38'),
(230, 28, '240225 Brown Tweed 0874', 'High quality 02 Tan Flan from our classic suits collection.', 194.00, 'active', '2026-10-28 22:24:38'),
(231, 28, '240224 Tan Flannel 5837 33D70B0F F8B1 4856 Acf1 72B10Feea08B', 'High quality 02 Tan Flan from our classic suits collection.', 191.00, 'active', '2026-10-28 22:24:38'),
(232, 29, 'Bone White 2 Button Suit 01', 'High quality 03 Charcoal from our classic suits collection.', 230.00, 'active', '2026-10-28 22:24:38'),
(233, 29, '240225 Grey Shark Skin 0380', 'High quality 03 Charcoal from our classic suits collection.', 243.00, 'active', '2026-10-28 22:24:38'),
(234, 29, '240225 Charcoal Grey Tweed 5138', 'High quality 03 Charcoal from our classic suits collection.', 192.00, 'active', '2026-10-28 22:24:38'),
(235, 29, 'Light Green Tweed 3Pc Suit 01', 'High quality 03 Charcoal from our classic suits collection.', 214.00, 'active', '2026-10-28 22:24:38'),
(236, 29, '240225 Navy Tweed 8553 3B7424Ec C17A 49Ff 9F1E 0Bcc57Dab509', 'High quality 03 Charcoal from our classic suits collection.', 274.00, 'active', '2026-10-28 22:24:38'),
(237, 30, 'Mensuits Suit Npin 1', 'High quality 04 Mensuits from our classic suits collection.', 278.00, 'active', '2026-10-28 22:24:38'),
(238, 30, 'Mensuits Suit Bw 4 1E583132 4697 4046 8E22 6130Efc6355E', 'High quality 04 Mensuits from our classic suits collection.', 302.00, 'active', '2026-10-28 22:24:38'),
(239, 31, 'Black 01', 'High quality 02 Racing Green Darbey Formal Lace Up from our Derby collection.', 102.00, 'active', '2026-10-28 22:24:38'),
(240, 31, 'Brown 01', 'High quality 02 Racing Green Darbey Formal Lace Up from our Derby collection.', 100.00, 'active', '2026-10-28 22:24:38'),
(241, 32, 'Black 01', 'High quality 03 CitiFlex Farringdon Formal Derby from our Derby collection.', 99.00, 'active', '2026-10-28 22:24:38'),
(242, 32, 'Brown 01', 'High quality 03 CitiFlex Farringdon Formal Derby from our Derby collection.', 134.00, 'active', '2026-10-28 22:24:38'),
(243, 33, 'Black 01', 'High quality 04 Antique Rogue Tommy Derby from our Derby collection.', 86.00, 'active', '2026-10-28 22:24:38'),
(244, 33, 'Brown 01', 'High quality 04 Antique Rogue Tommy Derby from our Derby collection.', 126.00, 'active', '2026-10-28 22:24:38'),
(245, 34, 'Black 01', 'High quality 05 Racing Green Horner Derby from our Derby collection.', 137.00, 'active', '2026-10-28 22:24:38'),
(246, 34, 'Brown 01', 'High quality 05 Racing Green Horner Derby from our Derby collection.', 91.00, 'active', '2026-10-28 22:24:38'),
(247, 35, 'Black 01', 'High quality 06 Antique Rogue Finn Derby from our Derby collection.', 102.00, 'active', '2026-10-28 22:24:38'),
(248, 35, 'Burgundy 01', 'High quality 06 Antique Rogue Finn Derby from our Derby collection.', 89.00, 'active', '2026-10-28 22:24:38'),
(249, 36, 'Black 01', 'High quality 07 CitiFlex Cannon Brogue Derby from our Derby collection.', 117.00, 'active', '2026-10-28 22:24:38'),
(250, 36, 'Tan 01', 'High quality 07 CitiFlex Cannon Brogue Derby from our Derby collection.', 117.00, 'active', '2026-10-28 22:24:38'),
(251, 37, 'Stone 01', 'High quality 08 Antique Rogue Winchester Suede Derby from our Derby collection.', 188.00, 'active', '2026-10-28 22:24:38'),
(252, 37, 'Tan 01', 'High quality 08 Antique Rogue Winchester Suede Derby from our Derby collection.', 182.00, 'active', '2026-10-28 22:24:38'),
(253, 38, 'Brown 01', 'High quality 09 Antique Rogue Jack Derby from our Derby collection.', 133.00, 'active', '2026-10-28 22:24:38'),
(254, 38, 'Tan 01', 'High quality 09 Antique Rogue Jack Derby from our Derby collection.', 121.00, 'active', '2026-10-28 22:24:38'),
(255, 39, 'Brown 01', 'High quality 01 Racing Green Elliot Smart Loafer from our Loafers collection.', 128.00, 'active', '2026-10-28 22:24:38'),
(256, 39, 'Tan 01', 'High quality 01 Racing Green Elliot Smart Loafer from our Loafers collection.', 106.00, 'active', '2026-10-28 22:24:38'),
(257, 40, 'Black Leather 01', 'High quality 02 Racing Green Elliot Black Loafer from our Loafers collection.', 123.00, 'active', '2026-10-28 22:24:38'),
(258, 40, 'Black Patent 01', 'High quality 02 Racing Green Elliot Black Loafer from our Loafers collection.', 112.00, 'active', '2026-10-28 22:24:38'),
(259, 41, 'Stone 01', 'High quality 03 Antique Rogue Chester Tassel Loafer from our Loafers collection.', 128.00, 'active', '2026-10-28 22:24:38'),
(260, 41, 'Tan 01', 'High quality 03 Antique Rogue Chester Tassel Loafer from our Loafers collection.', 108.00, 'active', '2026-10-28 22:24:38'),
(261, 42, 'Black 01', 'High quality 04 Antique Rogue Chester Tassel Loafer from our Loafers collection.', 88.00, 'active', '2026-10-28 22:24:38'),
(262, 42, 'Brown 01', 'High quality 04 Antique Rogue Chester Tassel Loafer from our Loafers collection.', 135.00, 'active', '2026-10-28 22:24:38'),
(263, 43, 'Brown 01', 'High quality 05 Antique Rogue Ruben Weave Loafer from our Loafers collection.', 120.00, 'active', '2026-10-28 22:24:38'),
(264, 43, 'Stone 01', 'High quality 05 Antique Rogue Ruben Weave Loafer from our Loafers collection.', 134.00, 'active', '2026-10-28 22:24:38'),
(265, 44, 'Navy 01', 'High quality 06 Antique Rogue Churchill Suede Loafer from our Loafers collection.', 172.00, 'active', '2026-10-28 22:24:38'),
(266, 44, 'Stone 01', 'High quality 06 Antique Rogue Churchill Suede Loafer from our Loafers collection.', 193.00, 'active', '2026-10-28 22:24:38'),
(267, 45, 'Olive 01', 'High quality 07 Antique Rogue Chester Tassel Loafer from our Loafers collection.', 86.00, 'active', '2026-10-28 22:24:38'),
(268, 46, 'Black 01', 'High quality 08 Antique Rogue Henry Velvet Loafer from our Loafers collection.', 154.00, 'active', '2026-10-28 22:24:38'),
(269, 47, 'Brown 01', 'High quality 01 Racing Green Montoy Monk from our Monk Strap collection.', 110.00, 'active', '2026-10-28 22:24:38'),
(270, 48, 'Black 01', 'High quality 02 Limehaus Monkton Patent from our Monk Strap collection.', 157.00, 'active', '2026-10-28 22:24:38'),
(271, 49, 'Black 01', 'High quality 01 Limehaus Oxley Formal from our Oxford collection.', 88.00, 'active', '2026-10-28 22:24:38'),
(272, 49, 'Burgundy 01', 'High quality 01 Limehaus Oxley Formal from our Oxford collection.', 140.00, 'active', '2026-10-28 22:24:38'),
(273, 50, 'Black 01', 'High quality 02 Racing Green Darbey Formal Lace Up from our Oxford collection.', 103.00, 'active', '2026-10-28 22:24:38'),
(274, 50, 'Brown 01', 'High quality 02 Racing Green Darbey Formal Lace Up from our Oxford collection.', 126.00, 'active', '2026-10-28 22:24:38'),
(275, 51, 'Black 01', 'High quality 03 CitiFlex Farringdon Formal Derby from our Oxford collection.', 139.00, 'active', '2026-10-28 22:24:38'),
(276, 51, 'Brown 01', 'High quality 03 CitiFlex Farringdon Formal Derby from our Oxford collection.', 95.00, 'active', '2026-10-28 22:24:38'),
(277, 52, 'Black 01', 'High quality 04 Antique Rogue Tommy Derby from our Oxford collection.', 132.00, 'active', '2026-10-28 22:24:38'),
(278, 52, 'Brown 01', 'High quality 04 Antique Rogue Tommy Derby from our Oxford collection.', 142.00, 'active', '2026-10-28 22:24:38'),
(279, 53, 'Black 01', 'High quality 05 Racing Green Horner Derby from our Oxford collection.', 109.00, 'active', '2026-10-28 22:24:38'),
(280, 53, 'Brown 01', 'High quality 05 Racing Green Horner Derby from our Oxford collection.', 108.00, 'active', '2026-10-28 22:24:38'),
(281, 54, 'Black 01', 'High quality 06 Antique Rogue Finn Derby from our Oxford collection.', 136.00, 'active', '2026-10-28 22:24:38'),
(282, 54, 'Burgundy 01', 'High quality 06 Antique Rogue Finn Derby from our Oxford collection.', 128.00, 'active', '2026-10-28 22:24:38'),
(283, 55, 'Black 01', 'High quality 07 CitiFlex Cannon Brogue Derby from our Oxford collection.', 149.00, 'active', '2026-10-28 22:24:38'),
(284, 55, 'Tan 01', 'High quality 07 CitiFlex Cannon Brogue Derby from our Oxford collection.', 154.00, 'active', '2026-10-28 22:24:38'),
(285, 56, 'Stone 01', 'High quality 08 Antique Rogue Winchester Suede Derby from our Oxford collection.', 203.00, 'active', '2026-10-28 22:24:38'),
(286, 56, 'Tan 01', 'High quality 08 Antique Rogue Winchester Suede Derby from our Oxford collection.', 162.00, 'active', '2026-10-28 22:24:38'),
(287, 57, '01', 'High quality 01 Harley Flannel from our Slim fit collection.', 261.00, 'active', '2026-10-28 22:24:38'),
(288, 57, '01', 'High quality 01 Harley Flannel from our Slim fit collection.', 298.00, 'active', '2026-10-28 22:24:38'),
(289, 58, '01', 'High quality 02 Blake Texture from our Slim fit collection.', 296.00, 'active', '2026-10-28 22:24:38'),
(290, 58, '01', 'High quality 02 Blake Texture from our Slim fit collection.', 264.00, 'active', '2026-10-28 22:24:38'),
(291, 59, '01', 'High quality 03 Grey Texture Stretch from our Slim fit collection.', 206.00, 'active', '2026-10-28 22:24:38'),
(292, 59, '01', 'High quality 03 Grey Texture Stretch from our Slim fit collection.', 292.00, 'active', '2026-10-28 22:24:38'),
(293, 60, '01', 'High quality 04 Limehaus Stretch from our Slim fit collection.', 248.00, 'active', '2026-10-28 22:24:38'),
(294, 60, '01', 'High quality 04 Limehaus Stretch from our Slim fit collection.', 200.00, 'active', '2026-10-28 22:24:38'),
(295, 61, '01', 'High quality 05 Ted Baker Panama from our Slim fit collection.', 402.00, 'active', '2026-10-28 22:24:38'),
(296, 61, '01', 'High quality 05 Ted Baker Panama from our Slim fit collection.', 403.00, 'active', '2026-10-28 22:24:38'),
(297, 62, '01', 'High quality 06 Black Texture Stretch from our Slim fit collection.', 253.00, 'active', '2026-10-28 22:24:38'),
(298, 62, '01', 'High quality 06 Black Texture Stretch from our Slim fit collection.', 217.00, 'active', '2026-10-28 22:24:38'),
(299, 63, '01', 'High quality 07 Baxter Herringbone from our Slim fit collection.', 265.00, 'active', '2026-10-28 22:24:38'),
(300, 63, '01', 'High quality 07 Baxter Herringbone from our Slim fit collection.', 337.00, 'active', '2026-10-28 22:24:38'),
(301, 64, '01', 'High quality 08 Ted Baker Hopsack from our Slim fit collection.', 413.00, 'active', '2026-10-28 22:24:38'),
(302, 64, '01', 'High quality 08 Ted Baker Hopsack from our Slim fit collection.', 400.00, 'active', '2026-10-28 22:24:38'),
(303, 65, 'Bluetexture 01', 'High quality 01 Antique Rogue Texture Stretch from our Summer Suit collection.', 231.00, 'active', '2026-10-28 22:24:38'),
(304, 65, 'Greytexture 01', 'High quality 01 Antique Rogue Texture Stretch from our Summer Suit collection.', 246.00, 'active', '2026-10-28 22:24:38'),
(305, 66, 'Greybromley 01', 'High quality 02 Marc Darcy Bromley Check from our Summer Suit collection.', 429.00, 'active', '2026-10-28 22:24:38'),
(306, 66, 'Skyblue 01', 'High quality 02 Marc Darcy Bromley Check from our Summer Suit collection.', 400.00, 'active', '2026-10-28 22:24:38'),
(307, 67, 'Navylinen 01', 'High quality 03 Ted Baker Linen Cotton Tailored from our Summer Suit collection.', 347.00, 'active', '2026-10-28 22:24:38'),
(308, 67, 'Sagelinen 01', 'High quality 03 Ted Baker Linen Cotton Tailored from our Summer Suit collection.', 419.00, 'active', '2026-10-28 22:24:38'),
(309, 68, 'Sandsuit 01', 'High quality 04 Summer Suits Stone and Sand from our Summer Suit collection.', 251.00, 'active', '2026-10-28 22:24:38'),
(310, 68, 'Stonehm5 01', 'High quality 04 Summer Suits Stone and Sand from our Summer Suit collection.', 260.00, 'active', '2026-10-28 22:24:38'),
(311, 69, 'Ecrublake 01', 'High quality 05 Antique Rogue Blake from our Summer Suit collection.', 225.00, 'active', '2026-10-28 22:24:38'),
(312, 69, 'Greyblake 01', 'High quality 05 Antique Rogue Blake from our Summer Suit collection.', 236.00, 'active', '2026-10-28 22:24:38'),
(313, 70, 'Blueherringbone 01', 'High quality 06 Ted Baker Linen Herringbone and Cotton from our Summer Suit collection.', 430.00, 'active', '2026-10-28 22:24:38'),
(314, 70, 'Stonelinen 01', 'High quality 06 Ted Baker Linen Herringbone and Cotton from our Summer Suit collection.', 392.00, 'active', '2026-10-28 22:24:38'),
(315, 71, 'Sagelinen 01', 'High quality 07 Ted Baker Slim Linen from our Summer Suit collection.', 436.00, 'active', '2026-10-28 22:24:38'),
(316, 71, 'Stonecheck 01', 'High quality 07 Ted Baker Slim Linen from our Summer Suit collection.', 403.00, 'active', '2026-10-28 22:24:38'),
(317, 72, 'Airforcelinen 01', 'High quality 08 Summer Linen and Sand from our Summer Suit collection.', 283.00, 'active', '2026-10-28 22:24:38'),
(318, 72, 'Blue Blake 01', 'High quality 08 Summer Linen and Sand from our Summer Suit collection.', 303.00, 'active', '2026-10-28 22:24:38'),
(319, 73, 'Blackhuxley 01', 'High quality Antique Rogue Huxley Tuxedo from our tuxido collection.', 383.00, 'active', '2026-10-28 22:24:38'),
(320, 73, 'Navyvelvet 01', 'High quality Antique Rogue Huxley Tuxedo from our tuxido collection.', 388.00, 'active', '2026-10-28 22:24:38'),
(321, 74, 'Black 01', 'High quality Antique Rogue Slim Fit Velvet Blazer from our tuxido collection.', 475.00, 'active', '2026-10-28 22:24:38'),
(322, 74, 'Merlot 01', 'High quality Antique Rogue Slim Fit Velvet Blazer from our tuxido collection.', 422.00, 'active', '2026-10-28 22:24:38'),
(323, 75, 'Blacktuxedo 01', 'High quality Limehaus Regular Fit Tuxedo from our tuxido collection.', 400.00, 'active', '2026-10-28 22:24:38'),
(324, 75, 'Navystretch 01', 'High quality Limehaus Regular Fit Tuxedo from our tuxido collection.', 395.00, 'active', '2026-10-28 22:24:38'),
(325, 76, 'Blacktuxedo 01', 'High quality Limehaus Slim Fit Tuxedo from our tuxido collection.', 324.00, 'active', '2026-10-28 22:24:38'),
(326, 76, 'Navystretch 01', 'High quality Limehaus Slim Fit Tuxedo from our tuxido collection.', 337.00, 'active', '2026-10-28 22:24:38'),
(327, 77, 'Blacktuxedo 01', 'High quality Limehaus Tailored Fit Tuxedo from our tuxido collection.', 322.00, 'active', '2026-10-28 22:24:38'),
(328, 77, 'Navystretch 01', 'High quality Limehaus Tailored Fit Tuxedo from our tuxido collection.', 393.00, 'active', '2026-10-28 22:24:38'),
(329, 78, 'Ecrutexture 01', 'High quality Ted Baker Pearce Tuxedo from our tuxido collection.', 508.00, 'active', '2026-10-28 22:24:38'),
(330, 78, 'Pearceblack 01', 'High quality Ted Baker Pearce Tuxedo from our tuxido collection.', 482.00, 'active', '2026-10-28 22:24:38'),
(331, 79, 'Mulberry 01', 'High quality Ted Baker Slim Fit Velvet Blazer from our tuxido collection.', 485.00, 'active', '2026-10-28 22:24:38'),
(332, 79, 'Smoketeal 01', 'High quality Ted Baker Slim Fit Velvet Blazer from our tuxido collection.', 493.00, 'active', '2026-10-28 22:24:38'),
(333, 80, 'Galennavy 01', 'High quality Ted Baker Tuxedo from our tuxido collection.', 512.00, 'active', '2026-10-28 22:24:38'),
(334, 80, 'Superblack 01', 'High quality Ted Baker Tuxedo from our tuxido collection.', 507.00, 'active', '2026-10-28 22:24:38');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `image_url`, `is_primary`) VALUES
(1, 1, 'data/dress/01_ADDISON_SATIN_DRESS_WHITE_0004.jpg', 1),
(2, 4, 'data/dress/12Orellia_Dress_Ivory_0020.jpg', 1),
(3, 5, 'data/dress/19_Myla_Maxi_Dress_White_0001.jpg', 1),
(4, 11, 'data/dress/20230511_meshki_ecomm-deesee7077.jpg', 1),
(5, 17, 'data/dress/250128_MESHKI_SomethingBlue4_02_064.jpg', 1),
(6, 18, 'data/dress/250128_MESHKI_SomethingBlue4_05_185.jpg', 1),
(7, 21, 'data/dress/250128_MESHKI_SomethingBlue4_26_1085.jpg', 1),
(8, 25, 'data/dress/250128_MESHKI_SomethingBlue4_29_1231.jpg', 1),
(9, 29, 'data/dress/250128_MESHKI_SomethingBlue4_44_1834.jpg', 1),
(10, 33, 'data/dress/250324_MESHKI_HertiageFinal_09_392.jpg', 1),
(11, 37, 'data/dress/250708_MESHKI_BRIDAL_9605x_e1c4094f-7ecd-48b8-85c6-a95a0deb3937.jpg', 1),
(12, 38, 'data/dress/250708_MESHKI_BRIDAL_9697__2_x.jpg', 1),
(13, 39, 'data/dress/250716_MESHKI_Bridal1_04_210.jpg', 1),
(14, 44, 'data/dress/250716_MESHKI_Bridal1_05_269.jpg', 1),
(15, 50, 'data/dress/250716_MESHKI_Bridal1_20_1040.jpg', 1),
(16, 54, 'data/dress/250716_MESHKI_Bridal1_21_1071_4e95aa4b-24eb-4232-968f-e7f29de9bdd9_800x800.jpg', 1),
(17, 55, 'data/dress/250716_MESHKI_Bridal1_21_1081_800x800.jpg', 1),
(18, 56, 'data/dress/250716_MESHKI_Bridal1_21_1087_4e0dabd9-da60-4b41-a0a2-b2995fcac61e_800x800.jpg', 1),
(19, 58, 'data/dress/250721_MESHKI_Bridal2_01_024.jpg', 1),
(20, 62, 'data/dress/250910_MESHKI_WomenInPower5_23_1002.jpg', 1),
(21, 67, 'data/dress/260113_MESHKI_Reshoots_SomethingBlue1_28_687.jpg', 1),
(22, 73, 'data/dress/260113_MESHKI_Reshoots_SomethingBlue1_31_848.jpg', 1),
(23, 77, 'data/dress/260114_MESHKI_SomethingBlue1_01_003_1.jpg', 1),
(24, 78, 'data/dress/260114_MESHKI_SomethingBlue1_01_015.jpg', 1),
(25, 82, 'data/dress/260114_MESHKI_SomethingBlue1_06_257.jpg', 1),
(26, 85, 'data/dress/260114_MESHKI_SomethingBlue1_07_319.jpg', 1),
(27, 90, 'data/dress/260114_MESHKI_SomethingBlue1_12_550.jpg', 1),
(28, 94, 'data/dress/260114_MESHKI_SomethingBlue1_25_1099.jpg', 1),
(29, 98, 'data/dress/260127_MESHKI_SomethingBlue3_21_699.jpg', 1),
(30, 103, 'data/dress/260219_MESHKI_Reshoots_35_DR10926WD3-IVY_1123.jpg', 1),
(31, 106, 'data/dress/260325_MESHKI_Reshoots_hardflash_40_860.jpg', 1),
(32, 114, 'data/dress/260325_MESHKI_Reshoots_hardflash_41_904.jpg', 1),
(33, 118, 'data/accessories/Atelier_photo_36cc2f85a7.jpg', 1),
(34, 119, 'data/accessories/Couture_photo_896cd9eb00.jpg', 1),
(35, 120, 'data/accessories/lorenso_photo_1_020a458630.jpg', 1),
(36, 121, 'data/accessories/medium_0_B3_A5513_7f02a33d0c.webp', 1),
(37, 133, 'data/accessories/medium_10209_Elisora_sleeves_5894ed7643.jpg', 1),
(38, 134, 'data/accessories/medium_10231_Gleam_bolero_9ac46e405f.jpg', 1),
(39, 135, 'data/accessories/medium_12911_Ayelora_cover_dress_eb1892f99d.jpg', 1),
(40, 136, 'data/accessories/medium_12926_Botticella_train_7118133a83.jpg', 1),
(41, 137, 'data/accessories/medium_12935_Brave_cape_4c92539efd.jpg', 1),
(42, 138, 'data/accessories/medium_12960_Caravellia_train_3a5a5af008.jpg', 1),
(43, 139, 'data/accessories/medium_13006_Darielle_gloves_1fc7e77280.jpg', 1),
(44, 140, 'data/accessories/medium_13128_Ellarosea_scarf_c16db63001.webp', 1),
(45, 141, 'data/accessories/medium_13181_Olivara_scarf_299d64cf3f.webp', 1),
(46, 142, 'data/accessories/medium_13190_Ossyn_cape_805608a10e.webp', 1),
(47, 143, 'data/accessories/medium_13218_Ossyn_garter_2115bad564.webp', 1),
(48, 144, 'data/accessories/medium_13227_Priss_cover_dress_a7392fa09b.webp', 1),
(49, 145, 'data/accessories/medium_13274_Sylvielle_bolero_1d216deb0e.webp', 1),
(50, 146, 'data/accessories/medium_13297_Thessara_bolero_ce0f0f14fa.webp', 1),
(51, 147, 'data/accessories/medium_13961_Lyrielle_veil_9378ccde8e.webp', 1),
(52, 148, 'data/accessories/medium_13978_Arvenna_sleeves_5ac3a02797.jpg', 1),
(53, 149, 'data/accessories/medium_13990_Annamaria_veil_474c06121a.webp', 1),
(54, 150, 'data/accessories/medium_14006_Calivora_sleeves_c2bd22ec9c.webp', 1),
(55, 151, 'data/accessories/medium_14008_Calivora_veil_3b4d50f1d4.webp', 1),
(56, 152, 'data/accessories/medium_14036_Charelle_veil_6d664faa5e.webp', 1),
(57, 153, 'data/accessories/medium_14045_Elarinne_choker_8ee9b459a0.webp', 1),
(58, 154, 'data/accessories/medium_14052_Elthera_cover_dress_b3ee81e623.webp', 1),
(59, 155, 'data/accessories/medium_14063_Elyssar_scarf_64bfdfa7f5.webp', 1),
(60, 156, 'data/accessories/medium_14093_Caladriel_scarf_05bdaaaedb.jpg', 1),
(61, 157, 'data/accessories/medium_14112_Liania_bolero_e60aaa2b9a.webp', 1),
(62, 158, 'data/accessories/medium_14135_Norienne_e1bc52140c.webp', 1),
(63, 159, 'data/accessories/medium_14170_Cosima_cover_dress_788302ea83.jpg', 1),
(64, 160, 'data/accessories/medium_14182_Olvaria_bolero_87c6cbc957.webp', 1),
(65, 161, 'data/accessories/medium_14186_Olvaria_gloves_115d5ca035.webp', 1),
(66, 162, 'data/accessories/medium_14211_Oriane_veil_b6ac1c57ab.webp', 1),
(67, 163, 'data/accessories/medium_14227_Teyra_bolero_38e49974af.webp', 1),
(68, 164, 'data/accessories/medium_14233_Virelina_gloves_883e6b9217.webp', 1),
(69, 165, 'data/accessories/medium_14244_Charelle_scarf_20c5036cf6.webp', 1),
(70, 166, 'data/accessories/medium_14310_Odyssia_gloves_cd70af5fa2.webp', 1),
(71, 167, 'data/accessories/medium_14332_Opera_bolero_bd3e41c966.webp', 1),
(72, 168, 'data/accessories/medium_14339_Ossyn_choker_f81fd2eacb.webp', 1),
(73, 169, 'data/accessories/medium_14423_Vittoria_bolero_350dc25d77.webp', 1),
(74, 170, 'data/accessories/medium_14463_Olvaria_train_5d774e96cf.webp', 1),
(75, 171, 'data/accessories/medium_16369_Lyxora_train_7c1d179813.webp', 1),
(76, 172, 'data/accessories/medium_16393_Vega_gloves_026c5a5b73.webp', 1),
(77, 173, 'data/accessories/medium_16475_Stelly_gloves_a2c9b31bfe.webp', 1),
(78, 174, 'data/accessories/medium_16480_Lyra_cape_c0e6052000.webp', 1),
(79, 175, 'data/accessories/medium_2_N9_A2486_3bf6b3ef79.jpg', 1),
(80, 184, 'data/accessories/medium_8701_Veluna_garter_23aa374a5a.jpg', 1),
(81, 185, 'data/accessories/medium_8722_Averra_652fc4f455.jpg', 1),
(82, 186, 'data/accessories/medium_8742_Valeris_hairband_99c0bc26dd.jpg', 1),
(83, 187, 'data/accessories/medium_8753_Bravelle_74343a0e41.jpg', 1),
(84, 188, 'data/accessories/medium_8809_Evolis_7b6f223913.jpg', 1),
(85, 190, 'data/accessories/medium_8833_Glacerin_8330df47cb.jpg', 1),
(86, 191, 'data/accessories/medium_8865_Rowelle_train_aeb6b116c9.jpg', 1),
(87, 192, 'data/accessories/medium_8873_Qirelle_sleeves_c081ea4569.jpg', 1),
(88, 193, 'data/accessories/medium_9058_Zeira_cuffs_be06f39724.jpg', 1),
(89, 194, 'data/accessories/medium_9529_Josephine_cape_c8faf2da6b.jpg', 1),
(90, 195, 'data/accessories/medium_9680_Audrey_sleeves_330938ec5e.jpg', 1),
(91, 196, 'data/accessories/medium_9697_Lady_Di_scarf_9893511682.jpg', 1),
(92, 197, 'data/accessories/medium_9702_Isabella_cape_5eb615695c.jpg', 1),
(93, 198, 'data/accessories/medium_9720_Maria_cover_dress_dfb4d2c837.jpg', 1),
(94, 199, 'data/accessories/medium_IMG_2937_1e89c4b1f6.webp', 1),
(95, 200, 'data/accessories/medium_Milla_Nova_25_26_10069_a0106317b4.jpg', 1),
(96, 210, 'data/accessories/Milla_Nova_25f03fe995.jpg', 1),
(97, 211, 'data/accessories/WL_1_291694d073.jpg', 1),
(98, 212, 'data/Casual Suit/01 Antique Rogue Texture Slim/Black/Black_01.jpg', 1),
(99, 213, 'data/Casual Suit/01 Antique Rogue Texture Slim/Navy/Navy_01.jpg', 1),
(100, 214, 'data/Casual Suit/02 Limehaus Stretch Slim/Blue/Blue_01.jpg', 1),
(101, 215, 'data/Casual Suit/02 Limehaus Stretch Slim/Charcoal/Charcoal_01.jpg', 1),
(102, 216, 'data/Casual Suit/03 Antique Rogue Texture Regular/Black/Black_01.jpg', 1),
(103, 217, 'data/Casual Suit/03 Antique Rogue Texture Regular/Navy/Navy_01.jpg', 1),
(104, 218, 'data/Casual Suit/04 Antique Rogue Maddoc Check/Blue/Blue_01.jpg', 1),
(105, 219, 'data/Casual Suit/04 Antique Rogue Maddoc Check/Charcoal/Charcoal_01.jpg', 1),
(106, 220, 'data/Casual Suit/05 Limehaus Stretch Tailored/Black/Black_01.jpg', 1),
(107, 221, 'data/Casual Suit/05 Limehaus Stretch Tailored/Navy/Navy_01.jpg', 1),
(108, 222, 'data/Casual Suit/06 Ted Baker Hopsack Check/Grey/Grey_01.jpg', 1),
(109, 223, 'data/Casual Suit/06 Ted Baker Hopsack Check/Navy/Navy_01.jpg', 1),
(110, 224, 'data/Casual Suit/07 Limehaus Stretch Regular/Black/Black_01.jpg', 1),
(111, 225, 'data/Casual Suit/07 Limehaus Stretch Regular/Navy/Navy_01.jpg', 1),
(112, 226, 'data/Casual Suit/08 Limehaus Stretch Skinny/Blue/Blue_01.jpg', 1),
(113, 227, 'data/Casual Suit/08 Limehaus Stretch Skinny/Charcoal/Charcoal_01.jpg', 1),
(114, 228, 'data/classic suits/01 Sharkskin Back/Black/240224_Black_Suit_5668.jpg', 1),
(115, 229, 'data/classic suits/01 Sharkskin Back/Light Blue/230727_Light_Blue_Sharkskin_Back_6447.jpg', 1),
(116, 230, 'data/classic suits/02 Tan Flan/Brown/240225_Brown_Tweed_0874.jpg', 1),
(117, 231, 'data/classic suits/02 Tan Flan/White/240224_Tan_Flannel_5837_33d70b0f-f8b1-4856-acf1-72b10feea08b.jpg', 1),
(118, 232, 'data/classic suits/03 Charcoal/Bone white/bone-white-2-button-suit-01.jpg', 1),
(119, 233, 'data/classic suits/03 Charcoal/Grey Shark/240225_Grey_Shark_Skin_0380.jpg', 1),
(120, 234, 'data/classic suits/03 Charcoal/Grey Tweed/240225_Charcoal_Grey_Tweed_5138.jpg', 1),
(121, 235, 'data/classic suits/03 Charcoal/light green/light-green-tweed-3pc-suit-01.jpg', 1),
(122, 236, 'data/classic suits/03 Charcoal/Navy Tweed/240225_Navy_Tweed_8553_3b7424ec-c17a-49ff-9f1e-0bcc57dab509.jpg', 1),
(123, 237, 'data/classic suits/04 Mensuits/Blue/mensuits_suit_npin_1.jpg', 1),
(124, 238, 'data/classic suits/04 Mensuits/Gray/mensuits_suit_bw_4_1e583132-4697-4046-8e22-6130efc6355e.jpg', 1),
(125, 239, 'data/Derby/02 Racing Green Darbey Formal Lace Up/Black/Black_01.jpg', 1),
(126, 240, 'data/Derby/02 Racing Green Darbey Formal Lace Up/Brown/Brown_01.jpg', 1),
(127, 241, 'data/Derby/03 CitiFlex Farringdon Formal Derby/Black/Black_01.jpg', 1),
(128, 242, 'data/Derby/03 CitiFlex Farringdon Formal Derby/Brown/Brown_01.jpg', 1),
(129, 243, 'data/Derby/04 Antique Rogue Tommy Derby/Black/Black_01.jpg', 1),
(130, 244, 'data/Derby/04 Antique Rogue Tommy Derby/Brown/Brown_01.jpg', 1),
(131, 245, 'data/Derby/05 Racing Green Horner Derby/Black/Black_01.jpg', 1),
(132, 246, 'data/Derby/05 Racing Green Horner Derby/Brown/Brown_01.jpg', 1),
(133, 247, 'data/Derby/06 Antique Rogue Finn Derby/Black/Black_01.jpg', 1),
(134, 248, 'data/Derby/06 Antique Rogue Finn Derby/Burgundy/Burgundy_01.jpg', 1),
(135, 249, 'data/Derby/07 CitiFlex Cannon Brogue Derby/Black/Black_01.jpg', 1),
(136, 250, 'data/Derby/07 CitiFlex Cannon Brogue Derby/Tan/Tan_01.jpg', 1),
(137, 251, 'data/Derby/08 Antique Rogue Winchester Suede Derby/Stone/Stone_01.jpg', 1),
(138, 252, 'data/Derby/08 Antique Rogue Winchester Suede Derby/Tan/Tan_01.jpg', 1),
(139, 253, 'data/Derby/09 Antique Rogue Jack Derby/Brown/Brown_01.jpg', 1),
(140, 254, 'data/Derby/09 Antique Rogue Jack Derby/Tan/Tan_01.jpg', 1),
(141, 255, 'data/Loafers/01 Racing Green Elliot Smart Loafer/Brown/Brown_01.jpg', 1),
(142, 256, 'data/Loafers/01 Racing Green Elliot Smart Loafer/Tan/Tan_01.jpg', 1),
(143, 257, 'data/Loafers/02 Racing Green Elliot Black Loafer/Black Leather/Black_Leather_01.jpg', 1),
(144, 258, 'data/Loafers/02 Racing Green Elliot Black Loafer/Black Patent/Black_Patent_01.jpg', 1),
(145, 259, 'data/Loafers/03 Antique Rogue Chester Tassel Loafer/Stone/Stone_01.jpg', 1),
(146, 260, 'data/Loafers/03 Antique Rogue Chester Tassel Loafer/Tan/Tan_01.jpg', 1),
(147, 261, 'data/Loafers/04 Antique Rogue Chester Tassel Loafer/Black/Black_01.jpg', 1),
(148, 262, 'data/Loafers/04 Antique Rogue Chester Tassel Loafer/Brown/Brown_01.jpg', 1),
(149, 263, 'data/Loafers/05 Antique Rogue Ruben Weave Loafer/Brown/Brown_01.jpg', 1),
(150, 264, 'data/Loafers/05 Antique Rogue Ruben Weave Loafer/Stone/Stone_01.jpg', 1),
(151, 265, 'data/Loafers/06 Antique Rogue Churchill Suede Loafer/Navy/Navy_01.jpg', 1),
(152, 266, 'data/Loafers/06 Antique Rogue Churchill Suede Loafer/Stone/Stone_01.jpg', 1),
(153, 267, 'data/Loafers/07 Antique Rogue Chester Tassel Loafer/Olive/Olive_01.jpg', 1),
(154, 268, 'data/Loafers/08 Antique Rogue Henry Velvet Loafer/Black/Black_01.jpg', 1),
(155, 269, 'data/Monk Strap/01 Racing Green Montoy Monk/Brown/Brown_01.jpg', 1),
(156, 270, 'data/Monk Strap/02 Limehaus Monkton Patent/Black/Black_01.jpg', 1),
(157, 271, 'data/Oxford/01 Limehaus Oxley Formal/Black/Black_01.jpg', 1),
(158, 272, 'data/Oxford/01 Limehaus Oxley Formal/Burgundy/Burgundy_01.jpg', 1),
(159, 273, 'data/Oxford/02 Racing Green Darbey Formal Lace Up/Black/Black_01.jpg', 1),
(160, 274, 'data/Oxford/02 Racing Green Darbey Formal Lace Up/Brown/Brown_01.jpg', 1),
(161, 275, 'data/Oxford/03 CitiFlex Farringdon Formal Derby/Black/Black_01.jpg', 1),
(162, 276, 'data/Oxford/03 CitiFlex Farringdon Formal Derby/Brown/Brown_01.jpg', 1),
(163, 277, 'data/Oxford/04 Antique Rogue Tommy Derby/Black/Black_01.jpg', 1),
(164, 278, 'data/Oxford/04 Antique Rogue Tommy Derby/Brown/Brown_01.jpg', 1),
(165, 279, 'data/Oxford/05 Racing Green Horner Derby/Black/Black_01.jpg', 1),
(166, 280, 'data/Oxford/05 Racing Green Horner Derby/Brown/Brown_01.jpg', 1),
(167, 281, 'data/Oxford/06 Antique Rogue Finn Derby/Black/Black_01.jpg', 1),
(168, 282, 'data/Oxford/06 Antique Rogue Finn Derby/Burgundy/Burgundy_01.jpg', 1),
(169, 283, 'data/Oxford/07 CitiFlex Cannon Brogue Derby/Black/Black_01.jpg', 1),
(170, 284, 'data/Oxford/07 CitiFlex Cannon Brogue Derby/Tan/Tan_01.jpg', 1),
(171, 285, 'data/Oxford/08 Antique Rogue Winchester Suede Derby/Stone/Stone_01.jpg', 1),
(172, 286, 'data/Oxford/08 Antique Rogue Winchester Suede Derby/Tan/Tan_01.jpg', 1),
(173, 287, 'data/Slim fit/01 Harley Flannel/Khaki/01.jpg', 1),
(174, 288, 'data/Slim fit/01 Harley Flannel/Mulberry/01.jpg', 1),
(175, 289, 'data/Slim fit/02 Blake Texture/Ecru/01.jpg', 1),
(176, 290, 'data/Slim fit/02 Blake Texture/Green/01.jpg', 1),
(177, 291, 'data/Slim fit/03 Grey Texture Stretch/Blue/01.jpg', 1),
(178, 292, 'data/Slim fit/03 Grey Texture Stretch/Grey/01.jpg', 1),
(179, 293, 'data/Slim fit/04 Limehaus Stretch/Black/01.jpg', 1),
(180, 294, 'data/Slim fit/04 Limehaus Stretch/Navy/01.jpg', 1),
(181, 295, 'data/Slim fit/05 Ted Baker Panama/Black/01.jpg', 1),
(182, 296, 'data/Slim fit/05 Ted Baker Panama/Blue/01.jpg', 1),
(183, 297, 'data/Slim fit/06 Black Texture Stretch/Black/01.jpg', 1),
(184, 298, 'data/Slim fit/06 Black Texture Stretch/Navy/01.jpg', 1),
(185, 299, 'data/Slim fit/07 Baxter Herringbone/Pale Grey/01.jpg', 1),
(186, 300, 'data/Slim fit/07 Baxter Herringbone/Sage/01.jpg', 1),
(187, 301, 'data/Slim fit/08 Ted Baker Hopsack/Grey/01.jpg', 1),
(188, 302, 'data/Slim fit/08 Ted Baker Hopsack/Navy/01.jpg', 1),
(189, 303, 'data/Summer Suit/01 Antique Rogue Texture Stretch/Blue/BlueTexture_01.jpg', 1),
(190, 304, 'data/Summer Suit/01 Antique Rogue Texture Stretch/Grey/GreyTexture_01.jpg', 1),
(191, 305, 'data/Summer Suit/02 Marc Darcy Bromley Check/Grey/GreyBromley_01.jpg', 1),
(192, 306, 'data/Summer Suit/02 Marc Darcy Bromley Check/Sky Blue/SkyBlue_01.jpg', 1),
(193, 307, 'data/Summer Suit/03 Ted Baker Linen Cotton Tailored/Navy/NavyLinen_01.jpg', 1),
(194, 308, 'data/Summer Suit/03 Ted Baker Linen Cotton Tailored/Sage/SageLinen_01.jpg', 1),
(195, 309, 'data/Summer Suit/04 Summer Suits Stone and Sand/Sand - Antique Rogue/SandSuit_01.jpg', 1),
(196, 310, 'data/Summer Suit/04 Summer Suits Stone and Sand/Stone - HM5/StoneHM5_01.jpg', 1),
(197, 311, 'data/Summer Suit/05 Antique Rogue Blake/Ecru/EcruBlake_01.jpg', 1),
(198, 312, 'data/Summer Suit/05 Antique Rogue Blake/Grey/GreyBlake_01.jpg', 1),
(199, 313, 'data/Summer Suit/06 Ted Baker Linen Herringbone and Cotton/Blue Herringbone/BlueHerringbone_01.jpg', 1),
(200, 314, 'data/Summer Suit/06 Ted Baker Linen Herringbone and Cotton/Stone Linen Cotton/StoneLinen_01.jpg', 1),
(201, 315, 'data/Summer Suit/07 Ted Baker Slim Linen/Sage Linen/SageLinen_01.jpg', 1),
(202, 316, 'data/Summer Suit/07 Ted Baker Slim Linen/Stone Check/StoneCheck_01.jpg', 1),
(203, 317, 'data/Summer Suit/08 Summer Linen and Sand/Airforce Linen/AirforceLinen_01.jpg', 1),
(204, 318, 'data/Summer Suit/08 Summer Linen and Sand/Blue Blake/Blue_Blake_01.jpg', 1),
(205, 319, 'data/tuxido/Antique Rogue Huxley Tuxedo/Black Huxley/BlackHuxley_01.jpg', 1),
(206, 320, 'data/tuxido/Antique Rogue Huxley Tuxedo/Navy Velvet/NavyVelvet_01.jpg', 1),
(207, 321, 'data/tuxido/Antique Rogue Slim Fit Velvet Blazer/Black/Black_01.jpg', 1),
(208, 322, 'data/tuxido/Antique Rogue Slim Fit Velvet Blazer/Merlot/Merlot_01.jpg', 1),
(209, 323, 'data/tuxido/Limehaus Regular Fit Tuxedo/Black Tuxedo/BlackTuxedo_01.jpg', 1),
(210, 324, 'data/tuxido/Limehaus Regular Fit Tuxedo/Navy Stretch/NavyStretch_01.jpg', 1),
(211, 325, 'data/tuxido/Limehaus Slim Fit Tuxedo/Black Tuxedo/BlackTuxedo_01.jpg', 1),
(212, 326, 'data/tuxido/Limehaus Slim Fit Tuxedo/Navy Stretch/NavyStretch_01.jpg', 1),
(213, 327, 'data/tuxido/Limehaus Tailored Fit Tuxedo/Black Tuxedo/BlackTuxedo_01.jpg', 1),
(214, 328, 'data/tuxido/Limehaus Tailored Fit Tuxedo/Navy Stretch/NavyStretch_01.jpg', 1),
(215, 329, 'data/tuxido/Ted Baker Pearce Tuxedo/Ecru Texture/EcruTexture_01.jpg', 1),
(216, 330, 'data/tuxido/Ted Baker Pearce Tuxedo/Pearce Black/PearceBlack_01.jpg', 1),
(217, 331, 'data/tuxido/Ted Baker Slim Fit Velvet Blazer/Mulberry/Mulberry_01.jpg', 1),
(218, 332, 'data/tuxido/Ted Baker Slim Fit Velvet Blazer/Smoke Teal/SmokeTeal_01.jpg', 1),
(219, 333, 'data/tuxido/Ted Baker Tuxedo/Galen Navy/GalenNavy_01.jpg', 1),
(220, 334, 'data/tuxido/Ted Baker Tuxedo/Super Black/SuperBlack_01.jpg', 1),
(221, 1, 'data/dress/01_ADDISON_SATIN_DRESS_WHITE_0009.jpg', 0),
(222, 1, 'data/dress/01_ADDISON_SATIN_DRESS_WHITE_0011.jpg', 0),
(223, 5, 'data/dress/19_Myla_Maxi_Dress_White_0010.jpg', 0),
(224, 5, 'data/dress/19_Myla_Maxi_Dress_White_0013.jpg', 0),
(225, 5, 'data/dress/19_Myla_Maxi_Dress_White_0015.jpg', 0),
(226, 5, 'data/dress/19_Myla_Maxi_Dress_White_0020.jpg', 0),
(227, 5, 'data/dress/19_Myla_Maxi_Dress_White_0023.jpg', 0),
(228, 11, 'data/dress/20230511_meshki_ecomm-deesee7094.jpg', 0),
(229, 11, 'data/dress/20230511_meshki_ecomm-deesee7098.jpg', 0),
(230, 11, 'data/dress/20230511_meshki_ecomm-deesee7102.jpg', 0),
(231, 11, 'data/dress/20230511_meshki_ecomm-deesee7114.jpg', 0),
(232, 11, 'data/dress/20230511_meshki_ecomm-deesee7115.jpg', 0),
(233, 18, 'data/dress/250128_MESHKI_SomethingBlue4_05_195.jpg', 0),
(234, 18, 'data/dress/250128_MESHKI_SomethingBlue4_05_264.jpg', 0),
(235, 21, 'data/dress/250128_MESHKI_SomethingBlue4_26_1100.jpg', 0),
(236, 21, 'data/dress/250128_MESHKI_SomethingBlue4_26_1113.jpg', 0),
(237, 21, 'data/dress/250128_MESHKI_SomethingBlue4_26_1131.jpg', 0),
(238, 25, 'data/dress/250128_MESHKI_SomethingBlue4_29_1237.jpg', 0),
(239, 25, 'data/dress/250128_MESHKI_SomethingBlue4_29_1248.jpg', 0),
(240, 25, 'data/dress/250128_MESHKI_SomethingBlue4_29_1251.jpg', 0),
(241, 29, 'data/dress/250128_MESHKI_SomethingBlue4_44_1844.jpg', 0),
(242, 29, 'data/dress/250128_MESHKI_SomethingBlue4_44_1852.jpg', 0),
(243, 29, 'data/dress/250128_MESHKI_SomethingBlue4_44_1861.jpg', 0),
(244, 33, 'data/dress/250324_MESHKI_HertiageFinal_09_399.jpg', 0),
(245, 33, 'data/dress/250324_MESHKI_HertiageFinal_09_403.jpg', 0),
(246, 33, 'data/dress/250324_MESHKI_HertiageFinal_09_412.jpg', 0),
(247, 39, 'data/dress/250716_MESHKI_Bridal1_04_214.jpg', 0),
(248, 39, 'data/dress/250716_MESHKI_Bridal1_04_225.jpg', 0),
(249, 39, 'data/dress/250716_MESHKI_Bridal1_04_248.jpg', 0),
(250, 39, 'data/dress/250716_MESHKI_Bridal1_04_260.jpg', 0),
(251, 44, 'data/dress/250716_MESHKI_Bridal1_05_278.jpg', 0),
(252, 44, 'data/dress/250716_MESHKI_Bridal1_05_284.jpg', 0),
(253, 44, 'data/dress/250716_MESHKI_Bridal1_05_288.jpg', 0),
(254, 44, 'data/dress/250716_MESHKI_Bridal1_05_311.jpg', 0),
(255, 44, 'data/dress/250716_MESHKI_Bridal1_05_313.jpg', 0),
(256, 50, 'data/dress/250716_MESHKI_Bridal1_20_1054.jpg', 0),
(257, 50, 'data/dress/250716_MESHKI_Bridal1_20_1058.jpg', 0),
(258, 50, 'data/dress/250716_MESHKI_Bridal1_20_1062.jpg', 0),
(259, 55, 'data/dress/250716_MESHKI_Bridal1_21_1111_800x800.jpg', 0),
(260, 58, 'data/dress/250721_MESHKI_Bridal2_01_033.jpg', 0),
(261, 58, 'data/dress/250721_MESHKI_Bridal2_01_053.jpg', 0),
(262, 58, 'data/dress/250721_MESHKI_Bridal2_01_076.jpg', 0),
(263, 62, 'data/dress/250910_MESHKI_WomenInPower5_23_10051.jpg', 0),
(264, 62, 'data/dress/250910_MESHKI_WomenInPower5_23_1011.jpg', 0),
(265, 62, 'data/dress/250910_MESHKI_WomenInPower5_23_9711.jpg', 0),
(266, 62, 'data/dress/250910_MESHKI_WomenInPower5_23_9831.jpg', 0),
(267, 67, 'data/dress/260113_MESHKI_Reshoots_SomethingBlue1_28_703.jpg', 0),
(268, 67, 'data/dress/260113_MESHKI_Reshoots_SomethingBlue1_28_705.jpg', 0),
(269, 67, 'data/dress/260113_MESHKI_Reshoots_SomethingBlue1_28_712.jpg', 0),
(270, 67, 'data/dress/260113_MESHKI_Reshoots_SomethingBlue1_28_722.jpg', 0),
(271, 67, 'data/dress/260113_MESHKI_Reshoots_SomethingBlue1_28_7281.jpg', 0),
(272, 73, 'data/dress/260113_MESHKI_Reshoots_SomethingBlue1_31_854.jpg', 0),
(273, 73, 'data/dress/260113_MESHKI_Reshoots_SomethingBlue1_31_856.jpg', 0),
(274, 73, 'data/dress/260113_MESHKI_Reshoots_SomethingBlue1_31_860.jpg', 0),
(275, 78, 'data/dress/260114_MESHKI_SomethingBlue1_01_018.jpg', 0),
(276, 78, 'data/dress/260114_MESHKI_SomethingBlue1_01_020.jpg', 0),
(277, 78, 'data/dress/260114_MESHKI_SomethingBlue1_01_044.jpg', 0),
(278, 82, 'data/dress/260114_MESHKI_SomethingBlue1_06_269.jpg', 0),
(279, 82, 'data/dress/260114_MESHKI_SomethingBlue1_06_292.jpg', 0),
(280, 85, 'data/dress/260114_MESHKI_SomethingBlue1_07_328_800x800.jpg', 0),
(281, 85, 'data/dress/260114_MESHKI_SomethingBlue1_07_333_800x800.jpg', 0),
(282, 85, 'data/dress/260114_MESHKI_SomethingBlue1_07_345.jpg', 0),
(283, 85, 'data/dress/260114_MESHKI_SomethingBlue1_07_361.jpg', 0),
(284, 90, 'data/dress/260114_MESHKI_SomethingBlue1_12_563.jpg', 0),
(285, 90, 'data/dress/260114_MESHKI_SomethingBlue1_12_568.jpg', 0),
(286, 90, 'data/dress/260114_MESHKI_SomethingBlue1_12_576.jpg', 0),
(287, 94, 'data/dress/260114_MESHKI_SomethingBlue1_25_1113.jpg', 0),
(288, 94, 'data/dress/260114_MESHKI_SomethingBlue1_25_1118.jpg', 0),
(289, 94, 'data/dress/260114_MESHKI_SomethingBlue1_25_1139.jpg', 0),
(290, 98, 'data/dress/260127_MESHKI_SomethingBlue3_21_715.jpg', 0),
(291, 98, 'data/dress/260127_MESHKI_SomethingBlue3_21_722.jpg', 0),
(292, 98, 'data/dress/260127_MESHKI_SomethingBlue3_21_736.jpg', 0),
(293, 98, 'data/dress/260127_MESHKI_SomethingBlue3_21_744.jpg', 0),
(294, 103, 'data/dress/260219_MESHKI_Reshoots_35_DR10926WD3-IVY_1129.jpg', 0),
(295, 103, 'data/dress/260219_MESHKI_Reshoots_35_DR10926WD3-IVY_1132.jpg', 0),
(296, 106, 'data/dress/260325_MESHKI_Reshoots_hardflash_40_868.jpg', 0),
(297, 106, 'data/dress/260325_MESHKI_Reshoots_hardflash_40_872.jpg', 0),
(298, 106, 'data/dress/260325_MESHKI_Reshoots_hardflash_40_881.jpg', 0),
(299, 106, 'data/dress/260325_MESHKI_Reshoots_hardflash_40_887.jpg', 0),
(300, 106, 'data/dress/260325_MESHKI_Reshoots_hardflash_40_890.jpg', 0),
(301, 106, 'data/dress/260325_MESHKI_Reshoots_hardflash_40_892.jpg', 0),
(302, 106, 'data/dress/260325_MESHKI_Reshoots_hardflash_40_897.jpg', 0),
(303, 114, 'data/dress/260325_MESHKI_Reshoots_hardflash_41_908.jpg', 0),
(304, 114, 'data/dress/260325_MESHKI_Reshoots_hardflash_41_913.jpg', 0),
(305, 114, 'data/dress/260325_MESHKI_Reshoots_hardflash_41_916.jpg', 0),
(306, 121, 'data/accessories/medium_0_B3_A5938_193633a551.webp', 0),
(307, 121, 'data/accessories/medium_0_B3_A6381_1dd8f5014e.webp', 0),
(308, 121, 'data/accessories/medium_0_B3_A6412_f803509f5e.webp', 0),
(309, 121, 'data/accessories/medium_0_B3_A6615_c330dcf9c4.webp', 0),
(310, 121, 'data/accessories/medium_0_B3_A6775_e776290428.webp', 0),
(311, 121, 'data/accessories/medium_0_B3_A7039_6813f74fde.webp', 0),
(312, 121, 'data/accessories/medium_0_B3_A7489_f9f4855842.webp', 0),
(313, 121, 'data/accessories/medium_0_B3_A7514_53d05feefe.webp', 0),
(314, 121, 'data/accessories/medium_0_B3_A8225_527c5d2502.webp', 0),
(315, 121, 'data/accessories/medium_0_B3_A9373_84f8c7348a.webp', 0),
(316, 121, 'data/accessories/medium_0_B3_A9580_859429214f.webp', 0),
(317, 175, 'data/accessories/medium_2_N9_A3201_d01e9e3fb5.jpg', 0),
(318, 175, 'data/accessories/medium_2_N9_A3306_69444c3caa.jpg', 0),
(319, 175, 'data/accessories/medium_2_N9_A3665_994ae5d036.jpg', 0),
(320, 175, 'data/accessories/medium_2_N9_A5016_9a3d350e0b.jpg', 0),
(321, 175, 'data/accessories/medium_2_N9_A5273_a66868fb15.jpg', 0),
(322, 175, 'data/accessories/medium_2_N9_A5485_9f97f92cee.jpg', 0),
(323, 175, 'data/accessories/medium_2_N9_A9609_2_879f2b227c.jpg', 0),
(324, 175, 'data/accessories/medium_2_N9_A9746_70c1af7f2d.jpg', 0),
(325, 188, 'data/accessories/medium_8811_Evolis_3862329296.jpg', 0),
(326, 200, 'data/accessories/medium_Milla_Nova_25_26_10294_0abe5e3d14.jpg', 0),
(327, 200, 'data/accessories/medium_Milla_Nova_25_26_10704_0622f3d176.jpg', 0),
(328, 200, 'data/accessories/medium_Milla_Nova_25_26_20308_986843366a.jpg', 0),
(329, 200, 'data/accessories/medium_Milla_Nova_25_26_20591_aaf075ca6e.jpg', 0),
(330, 200, 'data/accessories/medium_Milla_Nova_25_26_20688_52f5cf90a4.jpg', 0),
(331, 200, 'data/accessories/medium_Milla_Nova_25_26_21075_30a14257e3.jpg', 0),
(332, 200, 'data/accessories/medium_Milla_Nova_25_26_21260_0387b3d925.jpg', 0),
(333, 200, 'data/accessories/medium_Milla_Nova_25_26_21505_e6279bf3be.jpg', 0),
(334, 200, 'data/accessories/medium_Milla_Nova_25_26_Press_Look_40_2c118fdebf.jpg', 0),
(335, 212, 'data/Casual Suit/01 Antique Rogue Texture Slim/Black/Black_02.jpg', 0),
(336, 212, 'data/Casual Suit/01 Antique Rogue Texture Slim/Black/Black_03.jpg', 0),
(337, 212, 'data/Casual Suit/01 Antique Rogue Texture Slim/Black/Black_04.jpg', 0),
(338, 213, 'data/Casual Suit/01 Antique Rogue Texture Slim/Navy/Navy_02.jpg', 0),
(339, 213, 'data/Casual Suit/01 Antique Rogue Texture Slim/Navy/Navy_03.jpg', 0),
(340, 213, 'data/Casual Suit/01 Antique Rogue Texture Slim/Navy/Navy_04.jpg', 0),
(341, 214, 'data/Casual Suit/02 Limehaus Stretch Slim/Blue/Blue_02.jpg', 0),
(342, 214, 'data/Casual Suit/02 Limehaus Stretch Slim/Blue/Blue_03.jpg', 0),
(343, 214, 'data/Casual Suit/02 Limehaus Stretch Slim/Blue/Blue_04.jpg', 0),
(344, 215, 'data/Casual Suit/02 Limehaus Stretch Slim/Charcoal/Charcoal_02.jpg', 0),
(345, 215, 'data/Casual Suit/02 Limehaus Stretch Slim/Charcoal/Charcoal_03.jpg', 0),
(346, 215, 'data/Casual Suit/02 Limehaus Stretch Slim/Charcoal/Charcoal_04.jpg', 0),
(347, 216, 'data/Casual Suit/03 Antique Rogue Texture Regular/Black/Black_02.jpg', 0),
(348, 216, 'data/Casual Suit/03 Antique Rogue Texture Regular/Black/Black_03.jpg', 0),
(349, 216, 'data/Casual Suit/03 Antique Rogue Texture Regular/Black/Black_04.jpg', 0),
(350, 217, 'data/Casual Suit/03 Antique Rogue Texture Regular/Navy/Navy_02.jpg', 0),
(351, 217, 'data/Casual Suit/03 Antique Rogue Texture Regular/Navy/Navy_03.jpg', 0),
(352, 217, 'data/Casual Suit/03 Antique Rogue Texture Regular/Navy/Navy_04.jpg', 0),
(353, 218, 'data/Casual Suit/04 Antique Rogue Maddoc Check/Blue/Blue_02.jpg', 0),
(354, 218, 'data/Casual Suit/04 Antique Rogue Maddoc Check/Blue/Blue_03.jpg', 0),
(355, 218, 'data/Casual Suit/04 Antique Rogue Maddoc Check/Blue/Blue_04.jpg', 0),
(356, 219, 'data/Casual Suit/04 Antique Rogue Maddoc Check/Charcoal/Charcoal_02.jpg', 0),
(357, 219, 'data/Casual Suit/04 Antique Rogue Maddoc Check/Charcoal/Charcoal_03.jpg', 0),
(358, 219, 'data/Casual Suit/04 Antique Rogue Maddoc Check/Charcoal/Charcoal_04.jpg', 0),
(359, 220, 'data/Casual Suit/05 Limehaus Stretch Tailored/Black/Black_02.jpg', 0),
(360, 220, 'data/Casual Suit/05 Limehaus Stretch Tailored/Black/Black_03.jpg', 0),
(361, 220, 'data/Casual Suit/05 Limehaus Stretch Tailored/Black/Black_04.jpg', 0),
(362, 221, 'data/Casual Suit/05 Limehaus Stretch Tailored/Navy/Navy_02.jpg', 0),
(363, 221, 'data/Casual Suit/05 Limehaus Stretch Tailored/Navy/Navy_03.jpg', 0),
(364, 221, 'data/Casual Suit/05 Limehaus Stretch Tailored/Navy/Navy_04.jpg', 0),
(365, 222, 'data/Casual Suit/06 Ted Baker Hopsack Check/Grey/Grey_02.jpg', 0),
(366, 222, 'data/Casual Suit/06 Ted Baker Hopsack Check/Grey/Grey_03.jpg', 0),
(367, 222, 'data/Casual Suit/06 Ted Baker Hopsack Check/Grey/Grey_04.jpg', 0),
(368, 223, 'data/Casual Suit/06 Ted Baker Hopsack Check/Navy/Navy_02.jpg', 0),
(369, 223, 'data/Casual Suit/06 Ted Baker Hopsack Check/Navy/Navy_03.jpg', 0),
(370, 223, 'data/Casual Suit/06 Ted Baker Hopsack Check/Navy/Navy_04.jpg', 0),
(371, 224, 'data/Casual Suit/07 Limehaus Stretch Regular/Black/Black_02.jpg', 0),
(372, 224, 'data/Casual Suit/07 Limehaus Stretch Regular/Black/Black_03.jpg', 0),
(373, 224, 'data/Casual Suit/07 Limehaus Stretch Regular/Black/Black_04.jpg', 0),
(374, 225, 'data/Casual Suit/07 Limehaus Stretch Regular/Navy/Navy_02.jpg', 0),
(375, 225, 'data/Casual Suit/07 Limehaus Stretch Regular/Navy/Navy_03.jpg', 0),
(376, 225, 'data/Casual Suit/07 Limehaus Stretch Regular/Navy/Navy_04.jpg', 0),
(377, 226, 'data/Casual Suit/08 Limehaus Stretch Skinny/Blue/Blue_02.jpg', 0),
(378, 226, 'data/Casual Suit/08 Limehaus Stretch Skinny/Blue/Blue_03.jpg', 0),
(379, 226, 'data/Casual Suit/08 Limehaus Stretch Skinny/Blue/Blue_04.jpg', 0),
(380, 227, 'data/Casual Suit/08 Limehaus Stretch Skinny/Charcoal/Charcoal_02.jpg', 0),
(381, 227, 'data/Casual Suit/08 Limehaus Stretch Skinny/Charcoal/Charcoal_03.jpg', 0),
(382, 227, 'data/Casual Suit/08 Limehaus Stretch Skinny/Charcoal/Charcoal_04.jpg', 0),
(383, 228, 'data/classic suits/01 Sharkskin Back/Black/240224_Black_Suit_5672_2ad06e49-3d80-4bef-b7dd-b0b79d8845f5.jpg', 0),
(384, 229, 'data/classic suits/01 Sharkskin Back/Light Blue/230727_Light_Blue_Sharkskin_Collar_2055.jpg', 0),
(385, 229, 'data/classic suits/01 Sharkskin Back/Light Blue/230727_Light_Blue_Sharkskin_Cuff_0594.jpg', 0),
(386, 229, 'data/classic suits/01 Sharkskin Back/Light Blue/230727_Light_Blue_Sharkskin_Front_6415.jpg', 0),
(387, 230, 'data/classic suits/02 Tan Flan/Brown/240225_Brown_Tweed_0922.jpg', 0),
(388, 230, 'data/classic suits/02 Tan Flan/Brown/240225_Brown_Tweed_0940.jpg', 0),
(389, 230, 'data/classic suits/02 Tan Flan/Brown/240225_Brown_Tweed_0958.jpg', 0),
(390, 231, 'data/classic suits/02 Tan Flan/White/240224_Tan_Flannel_5904.jpg', 0),
(391, 231, 'data/classic suits/02 Tan Flan/White/240224_Tan_Flannel_5959_874e67ea-6537-4ab6-8dde-b6746bfa4d77.jpg', 0),
(392, 232, 'data/classic suits/03 Charcoal/Bone white/bone-white-2-button-suit-06.jpg', 0),
(393, 233, 'data/classic suits/03 Charcoal/Grey Shark/240225_Grey_Shark_Skin_0437.jpg', 0),
(394, 233, 'data/classic suits/03 Charcoal/Grey Shark/240225_Grey_Shark_Skin_0481.jpg', 0),
(395, 233, 'data/classic suits/03 Charcoal/Grey Shark/240225_Grey_Shark_Skin_0498.jpg', 0),
(396, 234, 'data/classic suits/03 Charcoal/Grey Tweed/240225_Charcoal_Grey_Tweed_5156.jpg', 0),
(397, 234, 'data/classic suits/03 Charcoal/Grey Tweed/240225_Charcoal_Grey_Tweed_5165.jpg', 0),
(398, 234, 'data/classic suits/03 Charcoal/Grey Tweed/240225_Charcoal_Grey_Tweed_5236.jpg', 0),
(399, 235, 'data/classic suits/03 Charcoal/light green/light-green-tweed-3pc-suit-06.jpg', 0),
(400, 235, 'data/classic suits/03 Charcoal/light green/light-green-tweed-3pc-suit-07.jpg', 0),
(401, 235, 'data/classic suits/03 Charcoal/light green/light-green-tweed-3pc-suit-08.jpg', 0),
(402, 236, 'data/classic suits/03 Charcoal/Navy Tweed/240225_Navy_Tweed_8592_7349aad4-9b02-4012-908c-1146fcea9c59.jpg', 0),
(403, 236, 'data/classic suits/03 Charcoal/Navy Tweed/240225_Navy_Tweed_8616_8d10460f-e5f0-4df6-8775-b4e0962dd763.jpg', 0),
(404, 236, 'data/classic suits/03 Charcoal/Navy Tweed/240225_Navy_Tweed_8669.jpg', 0),
(405, 237, 'data/classic suits/04 Mensuits/Blue/mensuits_suit_npin_2.png', 0),
(406, 238, 'data/classic suits/04 Mensuits/Gray/mensuits_suit_bw_8_03f6cf1f-da4e-4c38-bac3-3a1547df2150.jpg', 0),
(407, 239, 'data/Derby/02 Racing Green Darbey Formal Lace Up/Black/Black_02.jpg', 0),
(408, 239, 'data/Derby/02 Racing Green Darbey Formal Lace Up/Black/Black_03.jpg', 0),
(409, 239, 'data/Derby/02 Racing Green Darbey Formal Lace Up/Black/Black_04.jpg', 0),
(410, 240, 'data/Derby/02 Racing Green Darbey Formal Lace Up/Brown/Brown_02.jpg', 0),
(411, 240, 'data/Derby/02 Racing Green Darbey Formal Lace Up/Brown/Brown_03.jpg', 0),
(412, 240, 'data/Derby/02 Racing Green Darbey Formal Lace Up/Brown/Brown_04.jpg', 0),
(413, 241, 'data/Derby/03 CitiFlex Farringdon Formal Derby/Black/Black_02.jpg', 0),
(414, 241, 'data/Derby/03 CitiFlex Farringdon Formal Derby/Black/Black_03.jpg', 0),
(415, 241, 'data/Derby/03 CitiFlex Farringdon Formal Derby/Black/Black_04.jpg', 0),
(416, 242, 'data/Derby/03 CitiFlex Farringdon Formal Derby/Brown/Brown_02.jpg', 0),
(417, 242, 'data/Derby/03 CitiFlex Farringdon Formal Derby/Brown/Brown_03.jpg', 0),
(418, 242, 'data/Derby/03 CitiFlex Farringdon Formal Derby/Brown/Brown_04.jpg', 0),
(419, 243, 'data/Derby/04 Antique Rogue Tommy Derby/Black/Black_02.jpg', 0),
(420, 243, 'data/Derby/04 Antique Rogue Tommy Derby/Black/Black_03.jpg', 0),
(421, 243, 'data/Derby/04 Antique Rogue Tommy Derby/Black/Black_04.jpg', 0),
(422, 244, 'data/Derby/04 Antique Rogue Tommy Derby/Brown/Brown_02.jpg', 0),
(423, 244, 'data/Derby/04 Antique Rogue Tommy Derby/Brown/Brown_03.jpg', 0),
(424, 244, 'data/Derby/04 Antique Rogue Tommy Derby/Brown/Brown_04.jpg', 0),
(425, 245, 'data/Derby/05 Racing Green Horner Derby/Black/Black_02.jpg', 0),
(426, 245, 'data/Derby/05 Racing Green Horner Derby/Black/Black_03.jpg', 0),
(427, 245, 'data/Derby/05 Racing Green Horner Derby/Black/Black_04.jpg', 0),
(428, 246, 'data/Derby/05 Racing Green Horner Derby/Brown/Brown_02.jpg', 0),
(429, 246, 'data/Derby/05 Racing Green Horner Derby/Brown/Brown_03.jpg', 0),
(430, 246, 'data/Derby/05 Racing Green Horner Derby/Brown/Brown_04.jpg', 0),
(431, 247, 'data/Derby/06 Antique Rogue Finn Derby/Black/Black_02.jpg', 0),
(432, 247, 'data/Derby/06 Antique Rogue Finn Derby/Black/Black_03.jpg', 0),
(433, 247, 'data/Derby/06 Antique Rogue Finn Derby/Black/Black_04.jpg', 0),
(434, 248, 'data/Derby/06 Antique Rogue Finn Derby/Burgundy/Burgundy_02.jpg', 0),
(435, 248, 'data/Derby/06 Antique Rogue Finn Derby/Burgundy/Burgundy_03.jpg', 0),
(436, 248, 'data/Derby/06 Antique Rogue Finn Derby/Burgundy/Burgundy_04.jpg', 0),
(437, 249, 'data/Derby/07 CitiFlex Cannon Brogue Derby/Black/Black_02.jpg', 0),
(438, 249, 'data/Derby/07 CitiFlex Cannon Brogue Derby/Black/Black_03.jpg', 0),
(439, 249, 'data/Derby/07 CitiFlex Cannon Brogue Derby/Black/Black_04.jpg', 0),
(440, 250, 'data/Derby/07 CitiFlex Cannon Brogue Derby/Tan/Tan_02.jpg', 0),
(441, 250, 'data/Derby/07 CitiFlex Cannon Brogue Derby/Tan/Tan_03.jpg', 0),
(442, 250, 'data/Derby/07 CitiFlex Cannon Brogue Derby/Tan/Tan_04.jpg', 0),
(443, 251, 'data/Derby/08 Antique Rogue Winchester Suede Derby/Stone/Stone_02.jpg', 0),
(444, 251, 'data/Derby/08 Antique Rogue Winchester Suede Derby/Stone/Stone_03.jpg', 0),
(445, 251, 'data/Derby/08 Antique Rogue Winchester Suede Derby/Stone/Stone_04.jpg', 0),
(446, 252, 'data/Derby/08 Antique Rogue Winchester Suede Derby/Tan/Tan_02.jpg', 0),
(447, 252, 'data/Derby/08 Antique Rogue Winchester Suede Derby/Tan/Tan_03.jpg', 0),
(448, 252, 'data/Derby/08 Antique Rogue Winchester Suede Derby/Tan/Tan_04.jpg', 0),
(449, 253, 'data/Derby/09 Antique Rogue Jack Derby/Brown/Brown_02.jpg', 0),
(450, 253, 'data/Derby/09 Antique Rogue Jack Derby/Brown/Brown_03.jpg', 0),
(451, 253, 'data/Derby/09 Antique Rogue Jack Derby/Brown/Brown_04.jpg', 0),
(452, 254, 'data/Derby/09 Antique Rogue Jack Derby/Tan/Tan_02.jpg', 0),
(453, 254, 'data/Derby/09 Antique Rogue Jack Derby/Tan/Tan_03.jpg', 0),
(454, 254, 'data/Derby/09 Antique Rogue Jack Derby/Tan/Tan_04.jpg', 0),
(455, 255, 'data/Loafers/01 Racing Green Elliot Smart Loafer/Brown/Brown_02.jpg', 0),
(456, 255, 'data/Loafers/01 Racing Green Elliot Smart Loafer/Brown/Brown_03.jpg', 0),
(457, 255, 'data/Loafers/01 Racing Green Elliot Smart Loafer/Brown/Brown_04.jpg', 0),
(458, 256, 'data/Loafers/01 Racing Green Elliot Smart Loafer/Tan/Tan_02.jpg', 0),
(459, 256, 'data/Loafers/01 Racing Green Elliot Smart Loafer/Tan/Tan_03.jpg', 0),
(460, 256, 'data/Loafers/01 Racing Green Elliot Smart Loafer/Tan/Tan_04.jpg', 0),
(461, 257, 'data/Loafers/02 Racing Green Elliot Black Loafer/Black Leather/Black_Leather_02.jpg', 0),
(462, 257, 'data/Loafers/02 Racing Green Elliot Black Loafer/Black Leather/Black_Leather_03.jpg', 0),
(463, 257, 'data/Loafers/02 Racing Green Elliot Black Loafer/Black Leather/Black_Leather_04.jpg', 0),
(464, 258, 'data/Loafers/02 Racing Green Elliot Black Loafer/Black Patent/Black_Patent_02.jpg', 0),
(465, 258, 'data/Loafers/02 Racing Green Elliot Black Loafer/Black Patent/Black_Patent_03.jpg', 0),
(466, 258, 'data/Loafers/02 Racing Green Elliot Black Loafer/Black Patent/Black_Patent_04.jpg', 0),
(467, 259, 'data/Loafers/03 Antique Rogue Chester Tassel Loafer/Stone/Stone_02.jpg', 0),
(468, 259, 'data/Loafers/03 Antique Rogue Chester Tassel Loafer/Stone/Stone_03.jpg', 0),
(469, 259, 'data/Loafers/03 Antique Rogue Chester Tassel Loafer/Stone/Stone_04.jpg', 0),
(470, 260, 'data/Loafers/03 Antique Rogue Chester Tassel Loafer/Tan/Tan_02.jpg', 0),
(471, 260, 'data/Loafers/03 Antique Rogue Chester Tassel Loafer/Tan/Tan_03.jpg', 0),
(472, 260, 'data/Loafers/03 Antique Rogue Chester Tassel Loafer/Tan/Tan_04.jpg', 0),
(473, 261, 'data/Loafers/04 Antique Rogue Chester Tassel Loafer/Black/Black_02.jpg', 0),
(474, 261, 'data/Loafers/04 Antique Rogue Chester Tassel Loafer/Black/Black_03.jpg', 0),
(475, 261, 'data/Loafers/04 Antique Rogue Chester Tassel Loafer/Black/Black_04.jpg', 0),
(476, 262, 'data/Loafers/04 Antique Rogue Chester Tassel Loafer/Brown/Brown_02.jpg', 0),
(477, 262, 'data/Loafers/04 Antique Rogue Chester Tassel Loafer/Brown/Brown_03.jpg', 0),
(478, 262, 'data/Loafers/04 Antique Rogue Chester Tassel Loafer/Brown/Brown_04.jpg', 0),
(479, 263, 'data/Loafers/05 Antique Rogue Ruben Weave Loafer/Brown/Brown_02.jpg', 0),
(480, 263, 'data/Loafers/05 Antique Rogue Ruben Weave Loafer/Brown/Brown_03.jpg', 0),
(481, 263, 'data/Loafers/05 Antique Rogue Ruben Weave Loafer/Brown/Brown_04.jpg', 0),
(482, 264, 'data/Loafers/05 Antique Rogue Ruben Weave Loafer/Stone/Stone_02.jpg', 0),
(483, 264, 'data/Loafers/05 Antique Rogue Ruben Weave Loafer/Stone/Stone_03.jpg', 0),
(484, 264, 'data/Loafers/05 Antique Rogue Ruben Weave Loafer/Stone/Stone_04.jpg', 0),
(485, 265, 'data/Loafers/06 Antique Rogue Churchill Suede Loafer/Navy/Navy_02.jpg', 0),
(486, 265, 'data/Loafers/06 Antique Rogue Churchill Suede Loafer/Navy/Navy_03.jpg', 0),
(487, 265, 'data/Loafers/06 Antique Rogue Churchill Suede Loafer/Navy/Navy_04.jpg', 0),
(488, 266, 'data/Loafers/06 Antique Rogue Churchill Suede Loafer/Stone/Stone_02.jpg', 0),
(489, 266, 'data/Loafers/06 Antique Rogue Churchill Suede Loafer/Stone/Stone_03.jpg', 0),
(490, 266, 'data/Loafers/06 Antique Rogue Churchill Suede Loafer/Stone/Stone_04.jpg', 0),
(491, 267, 'data/Loafers/07 Antique Rogue Chester Tassel Loafer/Olive/Olive_02.jpg', 0),
(492, 267, 'data/Loafers/07 Antique Rogue Chester Tassel Loafer/Olive/Olive_03.jpg', 0),
(493, 267, 'data/Loafers/07 Antique Rogue Chester Tassel Loafer/Olive/Olive_04.jpg', 0),
(494, 268, 'data/Loafers/08 Antique Rogue Henry Velvet Loafer/Black/Black_02.jpg', 0),
(495, 268, 'data/Loafers/08 Antique Rogue Henry Velvet Loafer/Black/Black_03.jpg', 0),
(496, 268, 'data/Loafers/08 Antique Rogue Henry Velvet Loafer/Black/Black_04.jpg', 0),
(497, 269, 'data/Monk Strap/01 Racing Green Montoy Monk/Brown/Brown_02.jpg', 0),
(498, 269, 'data/Monk Strap/01 Racing Green Montoy Monk/Brown/Brown_03.jpg', 0),
(499, 269, 'data/Monk Strap/01 Racing Green Montoy Monk/Brown/Brown_04.jpg', 0),
(500, 270, 'data/Monk Strap/02 Limehaus Monkton Patent/Black/Black_02.jpg', 0),
(501, 270, 'data/Monk Strap/02 Limehaus Monkton Patent/Black/Black_03.jpg', 0),
(502, 270, 'data/Monk Strap/02 Limehaus Monkton Patent/Black/Black_04.jpg', 0),
(503, 271, 'data/Oxford/01 Limehaus Oxley Formal/Black/Black_02.jpg', 0),
(504, 271, 'data/Oxford/01 Limehaus Oxley Formal/Black/Black_03.jpg', 0),
(505, 271, 'data/Oxford/01 Limehaus Oxley Formal/Black/Black_04.jpg', 0),
(506, 272, 'data/Oxford/01 Limehaus Oxley Formal/Burgundy/Burgundy_02.jpg', 0),
(507, 272, 'data/Oxford/01 Limehaus Oxley Formal/Burgundy/Burgundy_03.jpg', 0),
(508, 272, 'data/Oxford/01 Limehaus Oxley Formal/Burgundy/Burgundy_04.jpg', 0),
(509, 273, 'data/Oxford/02 Racing Green Darbey Formal Lace Up/Black/Black_02.jpg', 0),
(510, 273, 'data/Oxford/02 Racing Green Darbey Formal Lace Up/Black/Black_03.jpg', 0),
(511, 273, 'data/Oxford/02 Racing Green Darbey Formal Lace Up/Black/Black_04.jpg', 0),
(512, 274, 'data/Oxford/02 Racing Green Darbey Formal Lace Up/Brown/Brown_02.jpg', 0),
(513, 274, 'data/Oxford/02 Racing Green Darbey Formal Lace Up/Brown/Brown_03.jpg', 0),
(514, 274, 'data/Oxford/02 Racing Green Darbey Formal Lace Up/Brown/Brown_04.jpg', 0),
(515, 275, 'data/Oxford/03 CitiFlex Farringdon Formal Derby/Black/Black_02.jpg', 0),
(516, 275, 'data/Oxford/03 CitiFlex Farringdon Formal Derby/Black/Black_03.jpg', 0),
(517, 275, 'data/Oxford/03 CitiFlex Farringdon Formal Derby/Black/Black_04.jpg', 0),
(518, 276, 'data/Oxford/03 CitiFlex Farringdon Formal Derby/Brown/Brown_02.jpg', 0),
(519, 276, 'data/Oxford/03 CitiFlex Farringdon Formal Derby/Brown/Brown_03.jpg', 0),
(520, 276, 'data/Oxford/03 CitiFlex Farringdon Formal Derby/Brown/Brown_04.jpg', 0),
(521, 277, 'data/Oxford/04 Antique Rogue Tommy Derby/Black/Black_02.jpg', 0),
(522, 277, 'data/Oxford/04 Antique Rogue Tommy Derby/Black/Black_03.jpg', 0),
(523, 277, 'data/Oxford/04 Antique Rogue Tommy Derby/Black/Black_04.jpg', 0),
(524, 278, 'data/Oxford/04 Antique Rogue Tommy Derby/Brown/Brown_02.jpg', 0),
(525, 278, 'data/Oxford/04 Antique Rogue Tommy Derby/Brown/Brown_03.jpg', 0),
(526, 278, 'data/Oxford/04 Antique Rogue Tommy Derby/Brown/Brown_04.jpg', 0),
(527, 279, 'data/Oxford/05 Racing Green Horner Derby/Black/Black_02.jpg', 0),
(528, 279, 'data/Oxford/05 Racing Green Horner Derby/Black/Black_03.jpg', 0),
(529, 279, 'data/Oxford/05 Racing Green Horner Derby/Black/Black_04.jpg', 0),
(530, 280, 'data/Oxford/05 Racing Green Horner Derby/Brown/Brown_02.jpg', 0),
(531, 280, 'data/Oxford/05 Racing Green Horner Derby/Brown/Brown_03.jpg', 0),
(532, 280, 'data/Oxford/05 Racing Green Horner Derby/Brown/Brown_04.jpg', 0),
(533, 281, 'data/Oxford/06 Antique Rogue Finn Derby/Black/Black_02.jpg', 0),
(534, 281, 'data/Oxford/06 Antique Rogue Finn Derby/Black/Black_03.jpg', 0),
(535, 281, 'data/Oxford/06 Antique Rogue Finn Derby/Black/Black_04.jpg', 0),
(536, 282, 'data/Oxford/06 Antique Rogue Finn Derby/Burgundy/Burgundy_02.jpg', 0),
(537, 282, 'data/Oxford/06 Antique Rogue Finn Derby/Burgundy/Burgundy_03.jpg', 0),
(538, 282, 'data/Oxford/06 Antique Rogue Finn Derby/Burgundy/Burgundy_04.jpg', 0),
(539, 283, 'data/Oxford/07 CitiFlex Cannon Brogue Derby/Black/Black_02.jpg', 0),
(540, 283, 'data/Oxford/07 CitiFlex Cannon Brogue Derby/Black/Black_03.jpg', 0),
(541, 283, 'data/Oxford/07 CitiFlex Cannon Brogue Derby/Black/Black_04.jpg', 0),
(542, 284, 'data/Oxford/07 CitiFlex Cannon Brogue Derby/Tan/Tan_02.jpg', 0),
(543, 284, 'data/Oxford/07 CitiFlex Cannon Brogue Derby/Tan/Tan_03.jpg', 0),
(544, 284, 'data/Oxford/07 CitiFlex Cannon Brogue Derby/Tan/Tan_04.jpg', 0),
(545, 285, 'data/Oxford/08 Antique Rogue Winchester Suede Derby/Stone/Stone_02.jpg', 0),
(546, 285, 'data/Oxford/08 Antique Rogue Winchester Suede Derby/Stone/Stone_03.jpg', 0),
(547, 285, 'data/Oxford/08 Antique Rogue Winchester Suede Derby/Stone/Stone_04.jpg', 0),
(548, 286, 'data/Oxford/08 Antique Rogue Winchester Suede Derby/Tan/Tan_02.jpg', 0),
(549, 286, 'data/Oxford/08 Antique Rogue Winchester Suede Derby/Tan/Tan_03.jpg', 0),
(550, 286, 'data/Oxford/08 Antique Rogue Winchester Suede Derby/Tan/Tan_04.jpg', 0),
(551, 287, 'data/Slim fit/01 Harley Flannel/Khaki/02.jpg', 0),
(552, 287, 'data/Slim fit/01 Harley Flannel/Khaki/03.jpg', 0),
(553, 287, 'data/Slim fit/01 Harley Flannel/Khaki/04.jpg', 0),
(554, 288, 'data/Slim fit/01 Harley Flannel/Mulberry/02.jpg', 0),
(555, 288, 'data/Slim fit/01 Harley Flannel/Mulberry/03.jpg', 0),
(556, 288, 'data/Slim fit/01 Harley Flannel/Mulberry/04.jpg', 0),
(557, 289, 'data/Slim fit/02 Blake Texture/Ecru/02.jpg', 0),
(558, 289, 'data/Slim fit/02 Blake Texture/Ecru/03.jpg', 0),
(559, 289, 'data/Slim fit/02 Blake Texture/Ecru/04.jpg', 0),
(560, 290, 'data/Slim fit/02 Blake Texture/Green/02.jpg', 0),
(561, 290, 'data/Slim fit/02 Blake Texture/Green/03.jpg', 0),
(562, 290, 'data/Slim fit/02 Blake Texture/Green/04.jpg', 0),
(563, 291, 'data/Slim fit/03 Grey Texture Stretch/Blue/02.jpg', 0),
(564, 291, 'data/Slim fit/03 Grey Texture Stretch/Blue/03.jpg', 0),
(565, 291, 'data/Slim fit/03 Grey Texture Stretch/Blue/04.jpg', 0),
(566, 292, 'data/Slim fit/03 Grey Texture Stretch/Grey/02.jpg', 0),
(567, 292, 'data/Slim fit/03 Grey Texture Stretch/Grey/03.jpg', 0),
(568, 292, 'data/Slim fit/03 Grey Texture Stretch/Grey/04.jpg', 0),
(569, 293, 'data/Slim fit/04 Limehaus Stretch/Black/02.jpg', 0),
(570, 293, 'data/Slim fit/04 Limehaus Stretch/Black/03.jpg', 0),
(571, 293, 'data/Slim fit/04 Limehaus Stretch/Black/04.jpg', 0),
(572, 294, 'data/Slim fit/04 Limehaus Stretch/Navy/02.jpg', 0),
(573, 294, 'data/Slim fit/04 Limehaus Stretch/Navy/03.jpg', 0),
(574, 294, 'data/Slim fit/04 Limehaus Stretch/Navy/04.jpg', 0),
(575, 295, 'data/Slim fit/05 Ted Baker Panama/Black/02.jpg', 0),
(576, 295, 'data/Slim fit/05 Ted Baker Panama/Black/03.jpg', 0),
(577, 295, 'data/Slim fit/05 Ted Baker Panama/Black/04.jpg', 0),
(578, 296, 'data/Slim fit/05 Ted Baker Panama/Blue/02.jpg', 0),
(579, 296, 'data/Slim fit/05 Ted Baker Panama/Blue/03.jpg', 0),
(580, 296, 'data/Slim fit/05 Ted Baker Panama/Blue/04.jpg', 0),
(581, 297, 'data/Slim fit/06 Black Texture Stretch/Black/02.jpg', 0),
(582, 297, 'data/Slim fit/06 Black Texture Stretch/Black/03.jpg', 0),
(583, 297, 'data/Slim fit/06 Black Texture Stretch/Black/04.jpg', 0),
(584, 298, 'data/Slim fit/06 Black Texture Stretch/Navy/02.jpg', 0),
(585, 298, 'data/Slim fit/06 Black Texture Stretch/Navy/03.jpg', 0),
(586, 298, 'data/Slim fit/06 Black Texture Stretch/Navy/04.jpg', 0),
(587, 299, 'data/Slim fit/07 Baxter Herringbone/Pale Grey/02.jpg', 0),
(588, 299, 'data/Slim fit/07 Baxter Herringbone/Pale Grey/03.jpg', 0),
(589, 299, 'data/Slim fit/07 Baxter Herringbone/Pale Grey/04.jpg', 0),
(590, 300, 'data/Slim fit/07 Baxter Herringbone/Sage/02.jpg', 0),
(591, 300, 'data/Slim fit/07 Baxter Herringbone/Sage/03.jpg', 0),
(592, 300, 'data/Slim fit/07 Baxter Herringbone/Sage/04.jpg', 0),
(593, 301, 'data/Slim fit/08 Ted Baker Hopsack/Grey/02.jpg', 0),
(594, 301, 'data/Slim fit/08 Ted Baker Hopsack/Grey/03.jpg', 0),
(595, 301, 'data/Slim fit/08 Ted Baker Hopsack/Grey/04.jpg', 0),
(596, 302, 'data/Slim fit/08 Ted Baker Hopsack/Navy/02.jpg', 0),
(597, 302, 'data/Slim fit/08 Ted Baker Hopsack/Navy/03.jpg', 0),
(598, 302, 'data/Slim fit/08 Ted Baker Hopsack/Navy/04.jpg', 0),
(599, 303, 'data/Summer Suit/01 Antique Rogue Texture Stretch/Blue/BlueTexture_02.jpg', 0),
(600, 303, 'data/Summer Suit/01 Antique Rogue Texture Stretch/Blue/BlueTexture_03.jpg', 0),
(601, 303, 'data/Summer Suit/01 Antique Rogue Texture Stretch/Blue/BlueTexture_04.jpg', 0),
(602, 304, 'data/Summer Suit/01 Antique Rogue Texture Stretch/Grey/GreyTexture_02.jpg', 0),
(603, 304, 'data/Summer Suit/01 Antique Rogue Texture Stretch/Grey/GreyTexture_03.jpg', 0),
(604, 304, 'data/Summer Suit/01 Antique Rogue Texture Stretch/Grey/GreyTexture_04.jpg', 0),
(605, 305, 'data/Summer Suit/02 Marc Darcy Bromley Check/Grey/GreyBromley_02.jpg', 0),
(606, 305, 'data/Summer Suit/02 Marc Darcy Bromley Check/Grey/GreyBromley_03.jpg', 0),
(607, 305, 'data/Summer Suit/02 Marc Darcy Bromley Check/Grey/GreyBromley_04.jpg', 0),
(608, 306, 'data/Summer Suit/02 Marc Darcy Bromley Check/Sky Blue/SkyBlue_02.jpg', 0),
(609, 306, 'data/Summer Suit/02 Marc Darcy Bromley Check/Sky Blue/SkyBlue_03.jpg', 0),
(610, 306, 'data/Summer Suit/02 Marc Darcy Bromley Check/Sky Blue/SkyBlue_04.jpg', 0),
(611, 307, 'data/Summer Suit/03 Ted Baker Linen Cotton Tailored/Navy/NavyLinen_02.jpg', 0),
(612, 307, 'data/Summer Suit/03 Ted Baker Linen Cotton Tailored/Navy/NavyLinen_03.jpg', 0),
(613, 307, 'data/Summer Suit/03 Ted Baker Linen Cotton Tailored/Navy/NavyLinen_04.jpg', 0),
(614, 308, 'data/Summer Suit/03 Ted Baker Linen Cotton Tailored/Sage/SageLinen_02.jpg', 0),
(615, 308, 'data/Summer Suit/03 Ted Baker Linen Cotton Tailored/Sage/SageLinen_03.jpg', 0),
(616, 308, 'data/Summer Suit/03 Ted Baker Linen Cotton Tailored/Sage/SageLinen_04.jpg', 0),
(617, 309, 'data/Summer Suit/04 Summer Suits Stone and Sand/Sand - Antique Rogue/SandSuit_02.jpg', 0),
(618, 309, 'data/Summer Suit/04 Summer Suits Stone and Sand/Sand - Antique Rogue/SandSuit_03.jpg', 0),
(619, 309, 'data/Summer Suit/04 Summer Suits Stone and Sand/Sand - Antique Rogue/SandSuit_04.jpg', 0),
(620, 310, 'data/Summer Suit/04 Summer Suits Stone and Sand/Stone - HM5/StoneHM5_02.jpg', 0),
(621, 310, 'data/Summer Suit/04 Summer Suits Stone and Sand/Stone - HM5/StoneHM5_03.jpg', 0),
(622, 310, 'data/Summer Suit/04 Summer Suits Stone and Sand/Stone - HM5/StoneHM5_04.jpg', 0),
(623, 311, 'data/Summer Suit/05 Antique Rogue Blake/Ecru/EcruBlake_02.jpg', 0),
(624, 311, 'data/Summer Suit/05 Antique Rogue Blake/Ecru/EcruBlake_03.jpg', 0),
(625, 311, 'data/Summer Suit/05 Antique Rogue Blake/Ecru/EcruBlake_04.jpg', 0),
(626, 312, 'data/Summer Suit/05 Antique Rogue Blake/Grey/GreyBlake_02.jpg', 0),
(627, 312, 'data/Summer Suit/05 Antique Rogue Blake/Grey/GreyBlake_03.jpg', 0),
(628, 312, 'data/Summer Suit/05 Antique Rogue Blake/Grey/GreyBlake_04.jpg', 0),
(629, 313, 'data/Summer Suit/06 Ted Baker Linen Herringbone and Cotton/Blue Herringbone/BlueHerringbone_02.jpg', 0),
(630, 313, 'data/Summer Suit/06 Ted Baker Linen Herringbone and Cotton/Blue Herringbone/BlueHerringbone_03.jpg', 0),
(631, 313, 'data/Summer Suit/06 Ted Baker Linen Herringbone and Cotton/Blue Herringbone/BlueHerringbone_04.jpg', 0),
(632, 314, 'data/Summer Suit/06 Ted Baker Linen Herringbone and Cotton/Stone Linen Cotton/StoneLinen_02.jpg', 0),
(633, 314, 'data/Summer Suit/06 Ted Baker Linen Herringbone and Cotton/Stone Linen Cotton/StoneLinen_03.jpg', 0),
(634, 314, 'data/Summer Suit/06 Ted Baker Linen Herringbone and Cotton/Stone Linen Cotton/StoneLinen_04.jpg', 0),
(635, 315, 'data/Summer Suit/07 Ted Baker Slim Linen/Sage Linen/SageLinen_02.jpg', 0),
(636, 315, 'data/Summer Suit/07 Ted Baker Slim Linen/Sage Linen/SageLinen_03.jpg', 0),
(637, 315, 'data/Summer Suit/07 Ted Baker Slim Linen/Sage Linen/SageLinen_04.jpg', 0),
(638, 316, 'data/Summer Suit/07 Ted Baker Slim Linen/Stone Check/StoneCheck_02.jpg', 0),
(639, 316, 'data/Summer Suit/07 Ted Baker Slim Linen/Stone Check/StoneCheck_03.jpg', 0),
(640, 316, 'data/Summer Suit/07 Ted Baker Slim Linen/Stone Check/StoneCheck_04.jpg', 0),
(641, 317, 'data/Summer Suit/08 Summer Linen and Sand/Airforce Linen/AirforceLinen_02.jpg', 0),
(642, 317, 'data/Summer Suit/08 Summer Linen and Sand/Airforce Linen/AirforceLinen_03.jpg', 0),
(643, 317, 'data/Summer Suit/08 Summer Linen and Sand/Airforce Linen/AirforceLinen_04.jpg', 0),
(644, 318, 'data/Summer Suit/08 Summer Linen and Sand/Blue Blake/Blue_Blake_02.jpg', 0),
(645, 318, 'data/Summer Suit/08 Summer Linen and Sand/Blue Blake/Blue_Blake_03.jpg', 0);
INSERT INTO `product_images` (`id`, `product_id`, `image_url`, `is_primary`) VALUES
(646, 318, 'data/Summer Suit/08 Summer Linen and Sand/Blue Blake/Blue_Blake_04.jpg', 0),
(647, 319, 'data/tuxido/Antique Rogue Huxley Tuxedo/Black Huxley/BlackHuxley_02.jpg', 0),
(648, 319, 'data/tuxido/Antique Rogue Huxley Tuxedo/Black Huxley/BlackHuxley_03.jpg', 0),
(649, 319, 'data/tuxido/Antique Rogue Huxley Tuxedo/Black Huxley/BlackHuxley_04.jpg', 0),
(650, 320, 'data/tuxido/Antique Rogue Huxley Tuxedo/Navy Velvet/NavyVelvet_02.jpg', 0),
(651, 320, 'data/tuxido/Antique Rogue Huxley Tuxedo/Navy Velvet/NavyVelvet_03.jpg', 0),
(652, 320, 'data/tuxido/Antique Rogue Huxley Tuxedo/Navy Velvet/NavyVelvet_04.jpg', 0),
(653, 321, 'data/tuxido/Antique Rogue Slim Fit Velvet Blazer/Black/Black_02.jpg', 0),
(654, 321, 'data/tuxido/Antique Rogue Slim Fit Velvet Blazer/Black/Black_03.jpg', 0),
(655, 321, 'data/tuxido/Antique Rogue Slim Fit Velvet Blazer/Black/Black_04.jpg', 0),
(656, 322, 'data/tuxido/Antique Rogue Slim Fit Velvet Blazer/Merlot/Merlot_02.jpg', 0),
(657, 322, 'data/tuxido/Antique Rogue Slim Fit Velvet Blazer/Merlot/Merlot_03.jpg', 0),
(658, 322, 'data/tuxido/Antique Rogue Slim Fit Velvet Blazer/Merlot/Merlot_04.jpg', 0),
(659, 323, 'data/tuxido/Limehaus Regular Fit Tuxedo/Black Tuxedo/BlackTuxedo_02.jpg', 0),
(660, 323, 'data/tuxido/Limehaus Regular Fit Tuxedo/Black Tuxedo/BlackTuxedo_03.jpg', 0),
(661, 323, 'data/tuxido/Limehaus Regular Fit Tuxedo/Black Tuxedo/BlackTuxedo_04.jpg', 0),
(662, 324, 'data/tuxido/Limehaus Regular Fit Tuxedo/Navy Stretch/NavyStretch_02.jpg', 0),
(663, 324, 'data/tuxido/Limehaus Regular Fit Tuxedo/Navy Stretch/NavyStretch_03.jpg', 0),
(664, 324, 'data/tuxido/Limehaus Regular Fit Tuxedo/Navy Stretch/NavyStretch_04.jpg', 0),
(665, 325, 'data/tuxido/Limehaus Slim Fit Tuxedo/Black Tuxedo/BlackTuxedo_02.jpg', 0),
(666, 325, 'data/tuxido/Limehaus Slim Fit Tuxedo/Black Tuxedo/BlackTuxedo_03.jpg', 0),
(667, 325, 'data/tuxido/Limehaus Slim Fit Tuxedo/Black Tuxedo/BlackTuxedo_04.jpg', 0),
(668, 326, 'data/tuxido/Limehaus Slim Fit Tuxedo/Navy Stretch/NavyStretch_02.jpg', 0),
(669, 326, 'data/tuxido/Limehaus Slim Fit Tuxedo/Navy Stretch/NavyStretch_03.jpg', 0),
(670, 326, 'data/tuxido/Limehaus Slim Fit Tuxedo/Navy Stretch/NavyStretch_04.jpg', 0),
(671, 327, 'data/tuxido/Limehaus Tailored Fit Tuxedo/Black Tuxedo/BlackTuxedo_02.jpg', 0),
(672, 327, 'data/tuxido/Limehaus Tailored Fit Tuxedo/Black Tuxedo/BlackTuxedo_03.jpg', 0),
(673, 327, 'data/tuxido/Limehaus Tailored Fit Tuxedo/Black Tuxedo/BlackTuxedo_04.jpg', 0),
(674, 328, 'data/tuxido/Limehaus Tailored Fit Tuxedo/Navy Stretch/NavyStretch_02.jpg', 0),
(675, 328, 'data/tuxido/Limehaus Tailored Fit Tuxedo/Navy Stretch/NavyStretch_03.jpg', 0),
(676, 328, 'data/tuxido/Limehaus Tailored Fit Tuxedo/Navy Stretch/NavyStretch_04.jpg', 0),
(677, 329, 'data/tuxido/Ted Baker Pearce Tuxedo/Ecru Texture/EcruTexture_02.jpg', 0),
(678, 329, 'data/tuxido/Ted Baker Pearce Tuxedo/Ecru Texture/EcruTexture_03.jpg', 0),
(679, 329, 'data/tuxido/Ted Baker Pearce Tuxedo/Ecru Texture/EcruTexture_04.jpg', 0),
(680, 330, 'data/tuxido/Ted Baker Pearce Tuxedo/Pearce Black/PearceBlack_02.jpg', 0),
(681, 330, 'data/tuxido/Ted Baker Pearce Tuxedo/Pearce Black/PearceBlack_03.jpg', 0),
(682, 330, 'data/tuxido/Ted Baker Pearce Tuxedo/Pearce Black/PearceBlack_04.jpg', 0),
(683, 331, 'data/tuxido/Ted Baker Slim Fit Velvet Blazer/Mulberry/Mulberry_02.jpg', 0),
(684, 331, 'data/tuxido/Ted Baker Slim Fit Velvet Blazer/Mulberry/Mulberry_03.jpg', 0),
(685, 331, 'data/tuxido/Ted Baker Slim Fit Velvet Blazer/Mulberry/Mulberry_04.jpg', 0),
(686, 332, 'data/tuxido/Ted Baker Slim Fit Velvet Blazer/Smoke Teal/SmokeTeal_02.jpg', 0),
(687, 332, 'data/tuxido/Ted Baker Slim Fit Velvet Blazer/Smoke Teal/SmokeTeal_03.jpg', 0),
(688, 332, 'data/tuxido/Ted Baker Slim Fit Velvet Blazer/Smoke Teal/SmokeTeal_04.jpg', 0),
(689, 333, 'data/tuxido/Ted Baker Tuxedo/Galen Navy/GalenNavy_02.jpg', 0),
(690, 333, 'data/tuxido/Ted Baker Tuxedo/Galen Navy/GalenNavy_03.jpg', 0),
(691, 333, 'data/tuxido/Ted Baker Tuxedo/Galen Navy/GalenNavy_04.jpg', 0),
(692, 334, 'data/tuxido/Ted Baker Tuxedo/Super Black/SuperBlack_02.jpg', 0),
(693, 334, 'data/tuxido/Ted Baker Tuxedo/Super Black/SuperBlack_03.jpg', 0),
(694, 334, 'data/tuxido/Ted Baker Tuxedo/Super Black/SuperBlack_04.jpg', 0);

-- --------------------------------------------------------

--
-- Table structure for table `product_variants`
--

CREATE TABLE `product_variants` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `size` varchar(20) NOT NULL DEFAULT 'One Size',
  `color` varchar(50) NOT NULL,
  `stock_quantity` int(11) NOT NULL DEFAULT 0
) ;

-- --------------------------------------------------------

--
-- Table structure for table `subcategories`
--

CREATE TABLE `subcategories` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subcategories`
--

INSERT INTO `subcategories` (`id`, `category_id`, `name`, `description`) VALUES
(1, 10, 'Satin Dress', 'Subcategory Satin Dress'),
(2, 10, 'Formal Dress', 'Subcategory Formal Dress'),
(3, 10, 'Maxi Dress', 'Subcategory Maxi Dress'),
(4, 10, 'Party Dress', 'Subcategory Party Dress'),
(5, 10, 'Evening Dress', 'Subcategory Evening Dress'),
(6, 10, 'Bridal Dress', 'Subcategory Bridal Dress'),
(7, 8, 'General Accessory', 'Subcategory General Accessory'),
(8, 8, 'Sleeves', 'Subcategory Sleeves'),
(9, 8, 'Bolero', 'Subcategory Bolero'),
(10, 8, 'Cover Dress', 'Subcategory Cover Dress'),
(11, 8, 'Train', 'Subcategory Train'),
(12, 8, 'Cape', 'Subcategory Cape'),
(13, 8, 'Gloves', 'Subcategory Gloves'),
(14, 8, 'Scarf', 'Subcategory Scarf'),
(15, 8, 'Garter', 'Subcategory Garter'),
(16, 8, 'Veil', 'Subcategory Veil'),
(17, 8, 'Choker', 'Subcategory Choker'),
(18, 8, 'Hairband', 'Subcategory Hairband'),
(19, 1, '01 Antique Rogue Texture Slim', 'Subcategory 01 Antique Rogue Texture Slim'),
(20, 1, '02 Limehaus Stretch Slim', 'Subcategory 02 Limehaus Stretch Slim'),
(21, 1, '03 Antique Rogue Texture Regular', 'Subcategory 03 Antique Rogue Texture Regular'),
(22, 1, '04 Antique Rogue Maddoc Check', 'Subcategory 04 Antique Rogue Maddoc Check'),
(23, 1, '05 Limehaus Stretch Tailored', 'Subcategory 05 Limehaus Stretch Tailored'),
(24, 1, '06 Ted Baker Hopsack Check', 'Subcategory 06 Ted Baker Hopsack Check'),
(25, 1, '07 Limehaus Stretch Regular', 'Subcategory 07 Limehaus Stretch Regular'),
(26, 1, '08 Limehaus Stretch Skinny', 'Subcategory 08 Limehaus Stretch Skinny'),
(27, 9, '01 Sharkskin Back', 'Subcategory 01 Sharkskin Back'),
(28, 9, '02 Tan Flan', 'Subcategory 02 Tan Flan'),
(29, 9, '03 Charcoal', 'Subcategory 03 Charcoal'),
(30, 9, '04 Mensuits', 'Subcategory 04 Mensuits'),
(31, 2, '02 Racing Green Darbey Formal Lace Up', 'Subcategory 02 Racing Green Darbey Formal Lace Up'),
(32, 2, '03 CitiFlex Farringdon Formal Derby', 'Subcategory 03 CitiFlex Farringdon Formal Derby'),
(33, 2, '04 Antique Rogue Tommy Derby', 'Subcategory 04 Antique Rogue Tommy Derby'),
(34, 2, '05 Racing Green Horner Derby', 'Subcategory 05 Racing Green Horner Derby'),
(35, 2, '06 Antique Rogue Finn Derby', 'Subcategory 06 Antique Rogue Finn Derby'),
(36, 2, '07 CitiFlex Cannon Brogue Derby', 'Subcategory 07 CitiFlex Cannon Brogue Derby'),
(37, 2, '08 Antique Rogue Winchester Suede Derby', 'Subcategory 08 Antique Rogue Winchester Suede Derby'),
(38, 2, '09 Antique Rogue Jack Derby', 'Subcategory 09 Antique Rogue Jack Derby'),
(39, 3, '01 Racing Green Elliot Smart Loafer', 'Subcategory 01 Racing Green Elliot Smart Loafer'),
(40, 3, '02 Racing Green Elliot Black Loafer', 'Subcategory 02 Racing Green Elliot Black Loafer'),
(41, 3, '03 Antique Rogue Chester Tassel Loafer', 'Subcategory 03 Antique Rogue Chester Tassel Loafer'),
(42, 3, '04 Antique Rogue Chester Tassel Loafer', 'Subcategory 04 Antique Rogue Chester Tassel Loafer'),
(43, 3, '05 Antique Rogue Ruben Weave Loafer', 'Subcategory 05 Antique Rogue Ruben Weave Loafer'),
(44, 3, '06 Antique Rogue Churchill Suede Loafer', 'Subcategory 06 Antique Rogue Churchill Suede Loafer'),
(45, 3, '07 Antique Rogue Chester Tassel Loafer', 'Subcategory 07 Antique Rogue Chester Tassel Loafer'),
(46, 3, '08 Antique Rogue Henry Velvet Loafer', 'Subcategory 08 Antique Rogue Henry Velvet Loafer'),
(47, 4, '01 Racing Green Montoy Monk', 'Subcategory 01 Racing Green Montoy Monk'),
(48, 4, '02 Limehaus Monkton Patent', 'Subcategory 02 Limehaus Monkton Patent'),
(49, 5, '01 Limehaus Oxley Formal', 'Subcategory 01 Limehaus Oxley Formal'),
(50, 5, '02 Racing Green Darbey Formal Lace Up', 'Subcategory 02 Racing Green Darbey Formal Lace Up'),
(51, 5, '03 CitiFlex Farringdon Formal Derby', 'Subcategory 03 CitiFlex Farringdon Formal Derby'),
(52, 5, '04 Antique Rogue Tommy Derby', 'Subcategory 04 Antique Rogue Tommy Derby'),
(53, 5, '05 Racing Green Horner Derby', 'Subcategory 05 Racing Green Horner Derby'),
(54, 5, '06 Antique Rogue Finn Derby', 'Subcategory 06 Antique Rogue Finn Derby'),
(55, 5, '07 CitiFlex Cannon Brogue Derby', 'Subcategory 07 CitiFlex Cannon Brogue Derby'),
(56, 5, '08 Antique Rogue Winchester Suede Derby', 'Subcategory 08 Antique Rogue Winchester Suede Derby'),
(57, 6, '01 Harley Flannel', 'Subcategory 01 Harley Flannel'),
(58, 6, '02 Blake Texture', 'Subcategory 02 Blake Texture'),
(59, 6, '03 Grey Texture Stretch', 'Subcategory 03 Grey Texture Stretch'),
(60, 6, '04 Limehaus Stretch', 'Subcategory 04 Limehaus Stretch'),
(61, 6, '05 Ted Baker Panama', 'Subcategory 05 Ted Baker Panama'),
(62, 6, '06 Black Texture Stretch', 'Subcategory 06 Black Texture Stretch'),
(63, 6, '07 Baxter Herringbone', 'Subcategory 07 Baxter Herringbone'),
(64, 6, '08 Ted Baker Hopsack', 'Subcategory 08 Ted Baker Hopsack'),
(65, 7, '01 Antique Rogue Texture Stretch', 'Subcategory 01 Antique Rogue Texture Stretch'),
(66, 7, '02 Marc Darcy Bromley Check', 'Subcategory 02 Marc Darcy Bromley Check'),
(67, 7, '03 Ted Baker Linen Cotton Tailored', 'Subcategory 03 Ted Baker Linen Cotton Tailored'),
(68, 7, '04 Summer Suits Stone and Sand', 'Subcategory 04 Summer Suits Stone and Sand'),
(69, 7, '05 Antique Rogue Blake', 'Subcategory 05 Antique Rogue Blake'),
(70, 7, '06 Ted Baker Linen Herringbone and Cotton', 'Subcategory 06 Ted Baker Linen Herringbone and Cotton'),
(71, 7, '07 Ted Baker Slim Linen', 'Subcategory 07 Ted Baker Slim Linen'),
(72, 7, '08 Summer Linen and Sand', 'Subcategory 08 Summer Linen and Sand'),
(73, 11, 'Antique Rogue Huxley Tuxedo', 'Subcategory Antique Rogue Huxley Tuxedo'),
(74, 11, 'Antique Rogue Slim Fit Velvet Blazer', 'Subcategory Antique Rogue Slim Fit Velvet Blazer'),
(75, 11, 'Limehaus Regular Fit Tuxedo', 'Subcategory Limehaus Regular Fit Tuxedo'),
(76, 11, 'Limehaus Slim Fit Tuxedo', 'Subcategory Limehaus Slim Fit Tuxedo'),
(77, 11, 'Limehaus Tailored Fit Tuxedo', 'Subcategory Limehaus Tailored Fit Tuxedo'),
(78, 11, 'Ted Baker Pearce Tuxedo', 'Subcategory Ted Baker Pearce Tuxedo'),
(79, 11, 'Ted Baker Slim Fit Velvet Blazer', 'Subcategory Ted Baker Slim Fit Velvet Blazer'),
(80, 11, 'Ted Baker Tuxedo', 'Subcategory Ted Baker Tuxedo');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
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
  ADD UNIQUE KEY `cart_id` (`cart_id`,`variant_id`),
  ADD KEY `fk_cart_items_variant` (`variant_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`product_id`),
  ADD KEY `fk_favorites_product` (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_orders_user` (`user_id`),
  ADD KEY `fk_orders_address` (`address_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_order_items_order` (`order_id`),
  ADD KEY `fk_order_items_variant` (`variant_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_payments_order` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_products_subcategory` (`subcategory_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_product_images_product` (`product_id`);

--
-- Indexes for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_id` (`product_id`,`size`,`color`);

--
-- Indexes for table `subcategories`
--
ALTER TABLE `subcategories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_subcategories_category` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=335;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=695;

--
-- AUTO_INCREMENT for table `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subcategories`
--
ALTER TABLE `subcategories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `addresses`
--
ALTER TABLE `addresses`
  ADD CONSTRAINT `fk_addresses_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `fk_carts_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `fk_cart_items_cart` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`),
  ADD CONSTRAINT `fk_cart_items_variant` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`);

--
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `fk_favorites_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `fk_favorites_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_address` FOREIGN KEY (`address_id`) REFERENCES `addresses` (`id`),
  ADD CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_order_items_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `fk_order_items_variant` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `fk_payments_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_products_subcategory` FOREIGN KEY (`subcategory_id`) REFERENCES `subcategories` (`id`);

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `fk_product_images_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD CONSTRAINT `fk_product_variants_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `subcategories`
--
ALTER TABLE `subcategories`
  ADD CONSTRAINT `fk_subcategories_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

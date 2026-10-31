-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 29, 2026 at 01:33 PM
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

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(1, 1, 'A Line Bridal Wedding Dress', 'A Line bridal gown for weddings and formal occasions.', 180.00, 299.00, '/Data/women/dress/a_line/primary.png', 5, '2026-10-29 12:25:42'),
(2, 2, 'Mermaid Bridal Wedding Dress', 'Mermaid bridal gown for weddings and formal occasions.', 180.00, 299.00, '/Data/women/dress/mermaid/primary.png', 5, '2026-10-29 12:25:42'),
(3, 3, 'Princess Bridal Wedding Dress', 'Princess bridal gown for weddings and formal occasions.', 180.00, 299.00, '/Data/women/dress/princess/primary.png', 5, '2026-10-29 12:25:42'),
(4, 4, 'Royal Bridal Wedding Dress', 'Royal bridal gown for weddings and formal occasions.', 180.00, 299.00, '/Data/women/dress/royal/primary.png', 5, '2026-10-29 12:25:42'),
(5, 5, 'Sheath Bridal Wedding Dress', 'Sheath bridal gown for weddings and formal occasions.', 180.00, 299.00, '/Data/women/dress/sheath/primary.png', 5, '2026-10-29 12:25:42'),
(6, 10, '3 Piece Suit Suit 01', '3 Piece Suit men\'s wedding suit for the groom and special occasions.', 95.00, 169.00, '/Data/men/suits/3%20piece%20suit/01/primary.webp', 8, '2026-10-29 12:25:42'),
(7, 10, '3 Piece Suit Suit 02', '3 Piece Suit men\'s wedding suit for the groom and special occasions.', 95.00, 169.00, '/Data/men/suits/3%20piece%20suit/02/primary.webp', 8, '2026-10-29 12:25:42'),
(8, 11, 'Double Breasted Suit Suit 01', 'Double Breasted Suit men\'s wedding suit for the groom and special occasions.', 95.00, 169.00, '/Data/men/suits/double%20breasted%20suit/01/primary.png', 8, '2026-10-29 12:25:42'),
(9, 12, 'Regular Fit Suit 01', 'Regular Fit men\'s wedding suit for the groom and special occasions.', 95.00, 169.00, '/Data/men/suits/regular%20fit/01/primary.png', 8, '2026-10-29 12:25:42'),
(10, 13, 'Slim Fit Suit 01', 'Slim Fit men\'s wedding suit for the groom and special occasions.', 95.00, 169.00, '/Data/men/suits/slim%20fit/01/primary.png', 8, '2026-10-29 12:25:42'),
(11, 14, 'Tuxedo Suit 01', 'Tuxedo men\'s wedding suit for the groom and special occasions.', 95.00, 169.00, '/Data/men/suits/tuxedo/01/primary.jpg', 8, '2026-10-29 12:25:42'),
(12, 21, 'The Classic Black Bow Tie', 'A timeless black-tie finish with a crisp, symmetrical knot.', 8.00, 19.00, 'assets/images/accessories/suits/bow-ties/classic-black.jpg', 15, '2026-10-29 12:25:42'),
(13, 21, 'The Midnight Bow Tie', 'Deep midnight tone, tied by hand for a considered finish.', 8.00, 20.00, 'assets/images/accessories/suits/bow-ties/midnight.jpg', 15, '2026-10-29 12:25:42'),
(14, 21, 'The Ivory Ceremony Bow Tie', 'A soft ivory bow tie for daytime ceremonies and garden celebrations.', 8.00, 18.00, 'assets/images/accessories/suits/bow-ties/ivory-ceremony.jpg', 15, '2026-10-29 12:25:42'),
(15, 21, 'The Crimson Bow Tie', 'A rich red accent for grooms who prefer a little colour.', 8.00, 25.00, 'assets/images/accessories/suits/bow-ties/crimson.jpg', 15, '2026-10-29 12:25:42'),
(16, 22, 'The Striped Silk Tie', 'Fine diagonal stripes for a quietly traditional wedding look.', 8.00, 59.00, 'assets/images/accessories/suits/neck-ties/striped.jpg', 15, '2026-10-29 12:25:42'),
(17, 22, 'The Polka Dot Silk Tie', 'A classic spotted pattern that adds character without noise.', 8.00, 45.00, 'assets/images/accessories/suits/neck-ties/pokadot.jpg', 15, '2026-10-29 12:25:42'),
(18, 22, 'The Crimson Silk Tie', 'A confident crimson tie with a clean, modern drape.', 8.00, 55.00, 'assets/images/accessories/suits/neck-ties/silk.jpg', 15, '2026-10-29 12:25:42'),
(19, 23, 'The Ivory Silk Square', 'A clean ivory fold that lets the tailoring lead.', 8.00, 59.00, 'assets/images/accessories/suits/pocket-squares/ivory-silk.jpg', 15, '2026-10-29 12:25:42'),
(20, 23, 'The Folded Silk Square', 'A soft neutral square, folded for an understated finish.', 8.00, 65.00, 'assets/images/accessories/suits/pocket-squares/folded-silk.jpg', 15, '2026-10-29 12:25:42'),
(21, 23, 'The Floral Signature Square', 'A printed floral square for celebrations with a softer edge.', 8.00, 19.00, 'assets/images/accessories/suits/pocket-squares/floral-signature.jpg', 15, '2026-10-29 12:25:42'),
(22, 15, 'The Heritage Derby', 'A burnished leather Derby with open lacing and a softly rounded toe.', 25.00, 110.00, 'assets/images/accessories/suits/shoes/derby-heritage.jpg', 12, '2026-10-29 12:25:42'),
(23, 15, 'Derby 2', 'Derby footwear suitable for weddings and formal occasions.', 25.00, 120.00, 'assets/images/accessories/suits/shoes/derbyyy.jpg', 12, '2026-10-29 12:25:42'),
(24, 15, 'Derby 3', 'Derby footwear suitable for weddings and formal occasions.', 25.00, 99.00, 'assets/images/accessories/suits/shoes/derby3.jpg', 12, '2026-10-29 12:25:42'),
(25, 16, 'The Cognac Loafer', 'A clean, laceless profile in warm cognac leather.', 25.00, 95.00, 'assets/images/accessories/suits/shoes/cognac.jpg', 12, '2026-10-29 12:25:42'),
(26, 16, 'Loafers 2', 'Loarfers footwear suitable for weddings and formal occasions.', 25.00, 130.00, 'assets/images/accessories/suits/shoes/loafers2.jpg', 12, '2026-10-29 12:25:42'),
(27, 16, 'Loafers 3', 'Loarfers footwear suitable for weddings and formal occasions.', 25.00, 139.00, 'assets/images/accessories/suits/shoes/loafers3.jpg', 12, '2026-10-29 12:25:42'),
(28, 17, 'The Signature Monk Strap', 'A single-buckle monk strap with a hand-finished patina.', 25.00, 95.00, 'assets/images/accessories/suits/shoes/monk4.jpg', 12, '2026-10-29 12:25:42'),
(29, 17, 'Monk Strap 2', 'Monk Strap footwear suitable for weddings and formal occasions.', 25.00, 145.00, 'assets/images/accessories/suits/shoes/monk3.jpg', 12, '2026-10-29 12:25:42'),
(30, 17, 'Monk Strap 3', 'Monk Strap footwear suitable for weddings and formal occasions.', 25.00, 95.00, 'assets/images/accessories/suits/shoes/monk5.jpg', 12, '2026-10-29 12:25:42'),
(31, 18, 'The Black Oxford', 'The formal standard: closed lacing, a sleek toe, and a mirror polish.', 25.00, 85.00, 'assets/images/accessories/suits/shoes/oxford-black.jpg', 12, '2026-10-29 12:25:42'),
(32, 18, 'Oxford 2', 'Oxford footwear suitable for weddings and formal occasions.', 25.00, 99.00, 'assets/images/accessories/suits/shoes/oxford2.jpg', 12, '2026-10-29 12:25:42'),
(33, 18, 'Oxford 3', 'Oxford footwear suitable for weddings and formal occasions.', 25.00, 49.00, 'assets/images/accessories/suits/shoes/oxford3.jpg', 12, '2026-10-29 12:25:42'),
(34, 6, 'The Classic Satin Glove', 'A short satin glove with a smooth, ceremonial finish.', 8.00, 35.00, 'assets/images/accessories/dresses/gloves/gloves.jpg', 15, '2026-10-29 12:25:42'),
(35, 6, 'The Evening Satin Glove', 'An evening-length glove for a formal, romantic gesture.', 8.00, 19.00, 'assets/images/accessories/dresses/gloves/gloves3.webp', 15, '2026-10-29 12:25:42'),
(36, 6, 'The Sheer Lace Glove', 'Sheer lace for a bride who loves vintage detail.', 8.00, 19.00, 'assets/images/accessories/dresses/gloves/gloves4.webp', 15, '2026-10-29 12:25:42'),
(37, 7, 'The Fine Gold Necklace', 'A delicate gold chain made for a low, elegant neckline.', 8.00, 45.00, 'assets/images/accessories/dresses/jewelry/gold-necklace.jpg', 15, '2026-10-29 12:25:42'),
(38, 7, 'The Pearl Drop Earrings', 'Pearl drops with a soft lustre and a timeless line.', 8.00, 50.00, 'assets/images/accessories/dresses/jewelry/pearl-earrings.jpg', 15, '2026-10-29 12:25:42'),
(39, 7, 'The Gold Bangle', 'A polished bangle to layer with heirloom pieces.', 8.00, 60.00, 'assets/images/accessories/dresses/jewelry/gold-bangle.jpg', 15, '2026-10-29 12:25:42'),
(40, 8, 'The Crystal Tiara', 'A fine crystal tiara with a light, soaring profile.', 8.00, 19.00, 'assets/images/accessories/dresses/tiaras-crowns/tiaras1.jpg', 15, '2026-10-29 12:25:42'),
(41, 8, 'The Silver Pearl Tiara', 'Silver filigree finished with pearls for a classical touch.', 8.00, 19.00, 'assets/images/accessories/dresses/tiaras-crowns/tiaras2.jpg', 15, '2026-10-29 12:25:42'),
(42, 8, 'The Gold Heirloom Tiara', 'A warm gold headpiece with an antique, treasured feel.', 8.00, 19.00, 'assets/images/accessories/dresses/tiaras-crowns/gold-tiara.jpg', 15, '2026-10-29 12:25:42'),
(43, 9, 'The Fingertip Veil', 'A light fingertip veil that frames the face without hiding it.', 8.00, 69.00, 'assets/images/accessories/dresses/veils/veil3.webp', 15, '2026-10-29 12:25:42'),
(44, 9, 'The Chapel-Length Veil', 'A graceful chapel length made for a measured walk down the aisle.', 8.00, 79.00, 'assets/images/accessories/dresses/veils/veil2.jpg', 15, '2026-10-29 12:25:42'),
(45, 9, 'The Cathedral Veil', 'A dramatic sweep of tulle for the grandest entrances.', 8.00, 55.00, 'assets/images/accessories/dresses/veils/veil.jpg', 15, '2026-10-29 12:25:42'),
(46, 19, 'The Marble Block Heel', 'A white block heel with a stable stride and a sculptural finish.', 25.00, 59.00, 'assets/images/accessories/dresses/shoes/block-heel-marble.jpg', 12, '2026-10-29 12:25:42'),
(47, 19, 'Block Heels 2', 'Block Heels footwear suitable for weddings and formal occasions.', 25.00, 39.00, 'assets/images/accessories/dresses/shoes/blockHeels2.jpg', 12, '2026-10-29 12:25:42'),
(48, 19, 'Block Heels 3', 'Block Heels footwear suitable for weddings and formal occasions.', 25.00, 45.00, 'assets/images/accessories/dresses/shoes/blockHeels3.jpg', 12, '2026-10-29 12:25:42'),
(49, 24, 'The Silver Bridal Sandal', 'Fine silver straps that catch the light with every step.', 25.00, 70.00, 'assets/images/accessories/dresses/shoes/silver-sandal.jpg', 12, '2026-10-29 12:25:42'),
(50, 24, 'The Beaded Bridal Mule', 'A beaded mule with an easy slip-on shape for the reception.', 25.00, 55.00, 'assets/images/accessories/dresses/shoes/beaded-mule.jpg', 12, '2026-10-29 12:25:42'),
(51, 24, 'Bridal Sandal 3', 'Bridal Sandal footwear suitable for weddings and formal occasions.', 25.00, 49.00, 'assets/images/accessories/dresses/shoes/sandal.jpg', 12, '2026-10-29 12:25:42'),
(52, 25, 'Flats 1', 'Flats footwear suitable for weddings and formal occasions.', 25.00, 49.00, 'assets/images/accessories/dresses/shoes/flat1.jpg', 12, '2026-10-29 12:25:42'),
(53, 25, 'Flats 2', 'Flats footwear suitable for weddings and formal occasions.', 25.00, 49.00, 'assets/images/accessories/dresses/shoes/flat2.jpg', 12, '2026-10-29 12:25:42'),
(54, 20, 'The Ribbon Bow Heel', 'Romantic ribbon ties and a refined bridal heel.', 25.00, 49.00, 'assets/images/accessories/dresses/shoes/ribbon-bow-heel.jpg', 12, '2026-10-29 12:25:42'),
(55, 20, 'High Heels 2', 'High Heels footwear suitable for weddings and formal occasions.', 25.00, 69.00, 'assets/images/accessories/dresses/shoes/highHeels2.jpg', 12, '2026-10-29 12:25:42'),
(56, 20, 'High Heels 3', 'High Heels footwear suitable for weddings and formal occasions.', 25.00, 45.00, 'assets/images/accessories/dresses/shoes/highHeels3.jpg', 12, '2026-10-29 12:25:42'),
(57, 26, 'The Classic Silver Cuff Links', 'Polished silver cuff links for a timeless finish.', 0.00, 25.00, 'assets/images/accessories/suits/cuff-links/silver.jpg', 15, '2026-10-31 06:48:01'),
(58, 26, 'The Classic gold and black Cuff Links', 'Polished gold and black cuff links for a classic finish.', 0.00, 25.00, 'assets/images/accessories/suits/cuff-links/gold.jpg', 15, '2026-10-31 06:50:11'),
(59, 26, 'THE initials cuff links', 'Extraordinary initials cuff links.', 0.00, 29.00, 'assets/images/accessories/suits/cuff-links/initials.jpg', 15, '2026-10-31 06:51:55'),
(60, 26, 'The Classic round gold Cuff Links', 'Polished classic gold cuff links for a classic finish.', 0.00, 20.00, 'assets/images/accessories/suits/cuff-links/classicCuffs.jpg', 15, '2026-10-31 06:53:51');

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
(1, 1, '/Data/women/dress/a_line/primary.png', 1, '2026-10-29 12:27:56'),
(2, 1, '/Data/women/dress/a_line/back.png', 0, '2026-10-29 12:27:56'),
(3, 1, '/Data/women/dress/a_line/Screenshot%202026-10-29%20141002.png', 0, '2026-10-29 12:27:56'),
(4, 2, '/Data/women/dress/mermaid/primary.png', 1, '2026-10-29 12:27:56'),
(5, 2, '/Data/women/dress/mermaid/back.png', 0, '2026-10-29 12:27:56'),
(6, 2, '/Data/women/dress/mermaid/Screenshot%202026-10-29%20140356.png', 0, '2026-10-29 12:27:56'),
(7, 2, '/Data/women/dress/mermaid/Screenshot%202026-10-29%20140431.png', 0, '2026-10-29 12:27:56'),
(8, 3, '/Data/women/dress/princess/primary.png', 1, '2026-10-29 12:27:56'),
(9, 3, '/Data/women/dress/princess/back.png', 0, '2026-10-29 12:27:56'),
(10, 3, '/Data/women/dress/princess/Screenshot%202026-10-29%20141305.png', 0, '2026-10-29 12:27:56'),
(11, 4, '/Data/women/dress/royal/primary.png', 1, '2026-10-29 12:27:56'),
(12, 4, '/Data/women/dress/royal/back.png', 0, '2026-10-29 12:27:56'),
(13, 4, '/Data/women/dress/royal/Screenshot%202026-10-29%20141739.png', 0, '2026-10-29 12:27:56'),
(14, 5, '/Data/women/dress/sheath/primary.png', 1, '2026-10-29 12:27:56'),
(15, 5, '/Data/women/dress/sheath/back.png', 0, '2026-10-29 12:27:56'),
(16, 5, '/Data/women/dress/sheath/Screenshot%202026-10-29%20143240.png', 0, '2026-10-29 12:27:56'),
(17, 6, '/Data/men/suits/3%20piece%20suit/01/primary.webp', 1, '2026-10-29 12:27:56'),
(18, 6, '/Data/men/suits/3%20piece%20suit/01/1743952556ca4259e3f397d5ea0b08174b29aaecf9.webp', 0, '2026-10-29 12:27:56'),
(19, 6, '/Data/men/suits/3%20piece%20suit/01/17439525677b323a43735eea9a1c594e491ca88fcc.webp', 0, '2026-10-29 12:27:56'),
(20, 6, '/Data/men/suits/3%20piece%20suit/01/174395263826d019e46fbfc76f6e14ee7fdd806bd7.webp', 0, '2026-10-29 12:27:56'),
(21, 6, '/Data/men/suits/3%20piece%20suit/01/174395263878fbdb3d4f322a00de65dced3f301f74.webp', 0, '2026-10-29 12:27:56'),
(22, 6, '/Data/men/suits/3%20piece%20suit/01/1768628123ca2142cc1de2ff1d3de4a657d2a7b6c4.webp', 0, '2026-10-29 12:27:56'),
(23, 6, '/Data/men/suits/3%20piece%20suit/01/1770113604e126bad9e3120a677bb05558bcc01fef.webp', 0, '2026-10-29 12:27:56'),
(24, 7, '/Data/men/suits/3%20piece%20suit/02/primary.webp', 1, '2026-10-29 12:27:56'),
(25, 7, '/Data/men/suits/3%20piece%20suit/02/175462633452503285247aee1ff8a8a6a1ac90ddd7.webp', 0, '2026-10-29 12:27:56'),
(26, 7, '/Data/men/suits/3%20piece%20suit/02/1754626349fef25442cc4e2ae9816dc2bfe39c3847.webp', 0, '2026-10-29 12:27:56'),
(27, 7, '/Data/men/suits/3%20piece%20suit/02/1754628274f05f4af7a2866e14e67edeb31705cc44.webp', 0, '2026-10-29 12:27:56'),
(28, 7, '/Data/men/suits/3%20piece%20suit/02/1754628282ba1a866c4c7050b907b915b5e969aa23.webp', 0, '2026-10-29 12:27:56'),
(29, 8, '/Data/men/suits/double%20breasted%20suit/01/primary.png', 1, '2026-10-29 12:27:56'),
(30, 8, '/Data/men/suits/double%20breasted%20suit/01/Screenshot%202026-10-29%20132714.png', 0, '2026-10-29 12:27:56'),
(31, 8, '/Data/men/suits/double%20breasted%20suit/01/Screenshot%202026-10-29%20132737.png', 0, '2026-10-29 12:27:56'),
(32, 9, '/Data/men/suits/regular%20fit/01/primary.png', 1, '2026-10-29 12:27:56'),
(33, 9, '/Data/men/suits/regular%20fit/01/Screenshot%202026-10-29%20133013.png', 0, '2026-10-29 12:27:56'),
(34, 9, '/Data/men/suits/regular%20fit/01/Screenshot%202026-10-29%20133028.png', 0, '2026-10-29 12:27:56'),
(35, 9, '/Data/men/suits/regular%20fit/01/Screenshot%202026-10-29%20133039.png', 0, '2026-10-29 12:27:56'),
(36, 10, '/Data/men/suits/slim%20fit/01/primary.png', 1, '2026-10-29 12:27:56'),
(37, 10, '/Data/men/suits/slim%20fit/01/Screenshot%202026-10-29%20132405.png', 0, '2026-10-29 12:27:56'),
(38, 10, '/Data/men/suits/slim%20fit/01/Screenshot%202026-10-29%20132437.png', 0, '2026-10-29 12:27:56'),
(39, 11, '/Data/men/suits/tuxedo/01/primary.jpg', 1, '2026-10-29 12:27:56'),
(40, 11, '/Data/men/suits/tuxedo/01/417145s.jpg', 0, '2026-10-29 12:27:56'),
(41, 11, '/Data/men/suits/tuxedo/01/954111s.jpg', 0, '2026-10-29 12:27:56'),
(42, 11, '/Data/men/suits/tuxedo/01/W16216s3.jpg', 0, '2026-10-29 12:27:56'),
(43, 12, 'assets/images/accessories/suits/bow-ties/classic-black.jpg', 1, '2026-10-29 12:27:56'),
(44, 13, 'assets/images/accessories/suits/bow-ties/midnight.jpg', 1, '2026-10-29 12:27:56'),
(45, 14, 'assets/images/accessories/suits/bow-ties/ivory-ceremony.jpg', 1, '2026-10-29 12:27:56'),
(46, 15, 'assets/images/accessories/suits/bow-ties/crimson.jpg', 1, '2026-10-29 12:27:56'),
(47, 16, 'assets/images/accessories/suits/neck-ties/striped-silk.jpg', 1, '2026-10-29 12:27:56'),
(48, 17, 'assets/images/accessories/suits/neck-ties/polka-dot.jpg', 1, '2026-10-29 12:27:56'),
(49, 18, 'assets/images/accessories/suits/neck-ties/crimson.jpg', 1, '2026-10-29 12:27:56'),
(50, 19, 'assets/images/accessories/suits/pocket-squares/ivory-silk.jpg', 1, '2026-10-29 12:27:56'),
(51, 20, 'assets/images/accessories/suits/pocket-squares/folded-silk.jpg', 1, '2026-10-29 12:27:56'),
(52, 21, 'assets/images/accessories/suits/pocket-squares/floral-signature.jpg', 1, '2026-10-29 12:27:56'),
(53, 22, 'assets/images/accessories/suits/shoes/derby-heritage.jpg', 1, '2026-10-29 12:27:56'),
(54, 23, '/Data/men/shoes/derby/Screenshot%202026-10-29%20150144.png', 1, '2026-10-29 12:27:56'),
(55, 24, '/Data/men/shoes/derby/Screenshot%202026-10-29%20150445.png', 1, '2026-10-29 12:27:56'),
(56, 25, 'assets/images/accessories/suits/shoes/loafer-cognac.jpg', 1, '2026-10-29 12:27:56'),
(57, 26, '/Data/men/shoes/loarfers/Screenshot%202026-10-29%20150617.png', 1, '2026-10-29 12:27:56'),
(58, 27, '/Data/men/shoes/loarfers/Screenshot%202026-10-29%20150623.png', 1, '2026-10-29 12:27:56'),
(59, 28, 'assets/images/accessories/suits/shoes/monk-strap-signature.jpg', 1, '2026-10-29 12:27:56'),
(60, 29, '/Data/men/shoes/monk%20strap/Screenshot%202026-10-29%20150925.png', 1, '2026-10-29 12:27:56'),
(61, 30, '/Data/men/shoes/monk%20strap/Screenshot%202026-10-29%20150936.png', 1, '2026-10-29 12:27:56'),
(62, 31, 'assets/images/accessories/suits/shoes/oxford-black.jpg', 1, '2026-10-29 12:29:03'),
(63, 32, '/Data/men/shoes/oxford/Screenshot%202026-10-29%20151025.png', 1, '2026-10-29 12:29:03'),
(64, 33, '/Data/men/shoes/oxford/Screenshot%202026-10-29%20151033.png', 1, '2026-10-29 12:29:03'),
(65, 34, 'assets/images/accessories/dresses/gloves/classic-satin.jpg', 1, '2026-10-29 12:29:03'),
(66, 35, 'assets/images/accessories/dresses/gloves/evening-satin.jpg', 1, '2026-10-29 12:29:03'),
(67, 36, 'assets/images/accessories/dresses/gloves/sheer-lace.jpg', 1, '2026-10-29 12:29:03'),
(68, 37, 'assets/images/accessories/dresses/jewelry/gold-necklace.jpg', 1, '2026-10-29 12:29:03'),
(69, 38, 'assets/images/accessories/dresses/jewelry/pearl-earrings.jpg', 1, '2026-10-29 12:29:03'),
(70, 39, 'assets/images/accessories/dresses/jewelry/gold-bangle.jpg', 1, '2026-10-29 12:29:03'),
(71, 40, 'assets/images/accessories/dresses/tiaras-crowns/crystal-tiara.jpg', 1, '2026-10-29 12:29:03'),
(72, 41, 'assets/images/accessories/dresses/tiaras-crowns/silver-pearl-tiara.jpg', 1, '2026-10-29 12:29:03'),
(73, 42, 'assets/images/accessories/dresses/tiaras-crowns/gold-tiara.jpg', 1, '2026-10-29 12:29:03'),
(74, 43, 'assets/images/accessories/dresses/veils/fingertip-veil.jpg', 1, '2026-10-29 12:29:03'),
(75, 44, 'assets/images/accessories/dresses/veils/chapel-veil.jpg', 1, '2026-10-29 12:29:03'),
(76, 45, 'assets/images/accessories/dresses/veils/cathedral-veil.jpg', 1, '2026-10-29 12:29:03'),
(77, 46, 'assets/images/accessories/dresses/shoes/block-heel-marble.jpg', 1, '2026-10-29 12:29:03'),
(78, 47, '/Data/women/shoes/bloch%20heels/Screenshot%202026-10-29%20145406.png', 1, '2026-10-29 12:29:03'),
(79, 48, '/Data/women/shoes/bloch%20heels/Screenshot%202026-10-29%20145413.png', 1, '2026-10-29 12:29:03'),
(80, 49, 'assets/images/accessories/dresses/shoes/silver-sandal.jpg', 1, '2026-10-29 12:29:03'),
(81, 50, 'assets/images/accessories/dresses/shoes/beaded-mule.jpg', 1, '2026-10-29 12:29:03'),
(82, 51, '/Data/women/shoes/bridal%20sandal/Screenshot%202026-10-29%20145546.png', 1, '2026-10-29 12:29:03'),
(83, 52, '/Data/women/shoes/flats/Screenshot%202026-10-29%20145821.png', 1, '2026-10-29 12:29:03'),
(84, 53, '/Data/women/shoes/flats/Screenshot%202026-10-29%20145855.png', 1, '2026-10-29 12:29:03'),
(85, 54, 'assets/images/accessories/dresses/shoes/ribbon-bow-heel.jpg', 1, '2026-10-29 12:29:03'),
(86, 55, '/Data/women/shoes/high%20heels/Screenshot%202026-10-29%20150010.png', 1, '2026-10-29 12:29:03'),
(87, 56, '/Data/women/shoes/high%20heels/Screenshot%202026-10-29%20150021.png', 1, '2026-10-29 12:29:03');

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
(1, 1, 'S', NULL, 2),
(2, 1, 'M', NULL, 2),
(3, 1, 'L', NULL, 2),
(4, 1, 'XL', NULL, 2),
(5, 2, 'S', NULL, 2),
(6, 2, 'M', NULL, 2),
(7, 2, 'L', NULL, 2),
(8, 2, 'XL', NULL, 2),
(9, 3, 'S', NULL, 2),
(10, 3, 'M', NULL, 2),
(11, 3, 'L', NULL, 2),
(12, 3, 'XL', NULL, 2),
(13, 4, 'S', NULL, 2),
(14, 4, 'M', NULL, 2),
(15, 4, 'L', NULL, 2),
(16, 4, 'XL', NULL, 2),
(17, 5, 'S', NULL, 2),
(18, 5, 'M', NULL, 2),
(19, 5, 'L', NULL, 2),
(20, 5, 'XL', NULL, 2),
(21, 6, 'S', NULL, 2),
(22, 6, 'M', NULL, 2),
(23, 6, 'L', NULL, 2),
(24, 6, 'XL', NULL, 2),
(25, 7, 'S', NULL, 2),
(26, 7, 'M', NULL, 2),
(27, 7, 'L', NULL, 2),
(28, 7, 'XL', NULL, 2),
(29, 8, 'S', NULL, 2),
(30, 8, 'M', NULL, 2),
(31, 8, 'L', NULL, 2),
(32, 8, 'XL', NULL, 2),
(33, 9, 'S', NULL, 2),
(34, 9, 'M', NULL, 2),
(35, 9, 'L', NULL, 2),
(36, 9, 'XL', NULL, 2),
(37, 10, 'S', NULL, 2),
(38, 10, 'M', NULL, 2),
(39, 10, 'L', NULL, 2),
(40, 10, 'XL', NULL, 2),
(41, 11, 'S', NULL, 2),
(42, 11, 'M', NULL, 2),
(43, 11, 'L', NULL, 2),
(44, 11, 'XL', NULL, 2),
(45, 22, '38', NULL, 2),
(46, 22, '39', NULL, 2),
(47, 22, '40', NULL, 2),
(48, 22, '41', NULL, 2),
(49, 22, '42', NULL, 2),
(50, 22, '43', NULL, 2),
(51, 23, '38', NULL, 2),
(52, 23, '39', NULL, 2),
(53, 23, '40', NULL, 2),
(54, 23, '41', NULL, 2),
(55, 23, '42', NULL, 2),
(56, 23, '43', NULL, 2),
(57, 24, '38', NULL, 2),
(58, 24, '39', NULL, 2),
(59, 24, '40', NULL, 2),
(60, 24, '41', NULL, 2),
(61, 24, '42', NULL, 2),
(62, 24, '43', NULL, 2),
(63, 25, '38', NULL, 2),
(64, 25, '39', NULL, 2),
(65, 25, '40', NULL, 2),
(66, 25, '41', NULL, 2),
(67, 25, '42', NULL, 2),
(68, 25, '43', NULL, 2),
(69, 26, '38', NULL, 2),
(70, 26, '39', NULL, 2),
(71, 26, '40', NULL, 2),
(72, 26, '41', NULL, 2),
(73, 26, '42', NULL, 2),
(74, 26, '43', NULL, 2),
(75, 27, '38', NULL, 2),
(76, 27, '39', NULL, 2),
(77, 27, '40', NULL, 2),
(78, 27, '41', NULL, 2),
(79, 27, '42', NULL, 2),
(80, 27, '43', NULL, 2),
(81, 28, '38', NULL, 2),
(82, 28, '39', NULL, 2),
(83, 28, '40', NULL, 2),
(84, 28, '41', NULL, 2),
(85, 28, '42', NULL, 2),
(86, 28, '43', NULL, 2),
(87, 29, '38', NULL, 2),
(88, 29, '39', NULL, 2),
(89, 29, '40', NULL, 2),
(90, 29, '41', NULL, 2),
(91, 29, '42', NULL, 2),
(92, 29, '43', NULL, 2),
(93, 30, '38', NULL, 2),
(94, 30, '39', NULL, 2),
(95, 30, '40', NULL, 2),
(96, 30, '41', NULL, 2),
(97, 30, '42', NULL, 2),
(98, 30, '43', NULL, 2),
(99, 31, '38', NULL, 2),
(100, 31, '39', NULL, 2),
(101, 31, '40', NULL, 2),
(102, 31, '41', NULL, 2),
(103, 31, '42', NULL, 2),
(104, 31, '43', NULL, 2),
(105, 32, '38', NULL, 2),
(106, 32, '39', NULL, 2),
(107, 32, '40', NULL, 2),
(108, 32, '41', NULL, 2),
(109, 32, '42', NULL, 2),
(110, 32, '43', NULL, 2),
(111, 33, '38', NULL, 2),
(112, 33, '39', NULL, 2),
(113, 33, '40', NULL, 2),
(114, 33, '41', NULL, 2),
(115, 33, '42', NULL, 2),
(116, 33, '43', NULL, 2),
(117, 46, '38', NULL, 2),
(118, 46, '39', NULL, 2),
(119, 46, '40', NULL, 2),
(120, 46, '41', NULL, 2),
(121, 46, '42', NULL, 2),
(122, 46, '43', NULL, 2),
(123, 47, '38', NULL, 2),
(124, 47, '39', NULL, 2),
(125, 47, '40', NULL, 2),
(126, 47, '41', NULL, 2),
(127, 47, '42', NULL, 2),
(128, 47, '43', NULL, 2),
(129, 48, '38', NULL, 2),
(130, 48, '39', NULL, 2),
(131, 48, '40', NULL, 2),
(132, 48, '41', NULL, 2),
(133, 48, '42', NULL, 2),
(134, 48, '43', NULL, 2),
(135, 49, '38', NULL, 2),
(136, 49, '39', NULL, 2),
(137, 49, '40', NULL, 2),
(138, 49, '41', NULL, 2),
(139, 49, '42', NULL, 2),
(140, 49, '43', NULL, 2),
(141, 50, '38', NULL, 2),
(142, 50, '39', NULL, 2),
(143, 50, '40', NULL, 2),
(144, 50, '41', NULL, 2),
(145, 50, '42', NULL, 2),
(146, 50, '43', NULL, 2),
(147, 51, '38', NULL, 2),
(148, 51, '39', NULL, 2),
(149, 51, '40', NULL, 2),
(150, 51, '41', NULL, 2),
(151, 51, '42', NULL, 2),
(152, 51, '43', NULL, 2),
(153, 52, '38', NULL, 2),
(154, 52, '39', NULL, 2),
(155, 52, '40', NULL, 2),
(156, 52, '41', NULL, 2),
(157, 52, '42', NULL, 2),
(158, 52, '43', NULL, 2),
(159, 53, '38', NULL, 2),
(160, 53, '39', NULL, 2),
(161, 53, '40', NULL, 2),
(162, 53, '41', NULL, 2),
(163, 53, '42', NULL, 2),
(164, 53, '43', NULL, 2),
(165, 54, '38', NULL, 2),
(166, 54, '39', NULL, 2),
(167, 54, '40', NULL, 2),
(168, 54, '41', NULL, 2),
(169, 54, '42', NULL, 2),
(170, 54, '43', NULL, 2),
(171, 55, '38', NULL, 2),
(172, 55, '39', NULL, 2),
(173, 55, '40', NULL, 2),
(174, 55, '41', NULL, 2),
(175, 55, '42', NULL, 2),
(176, 55, '43', NULL, 2),
(177, 56, '38', NULL, 2),
(178, 56, '39', NULL, 2),
(179, 56, '40', NULL, 2),
(180, 56, '41', NULL, 2),
(181, 56, '42', NULL, 2),
(182, 56, '43', NULL, 2);

-- --------------------------------------------------------

--
-- Table structure for table `subcategories`
--

CREATE TABLE `subcategories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `category_id` int(11) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `image_url` varchar(500) DEFAULT NULL
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
(15, 'Derby', 4, 'Open lacing and a quietly rounded silhouette.', 'assets/images/accessories/suits/shoes/derby-category.jpg'),
(16, 'Loafers', 4, 'Sleek, laceless ease for long celebrations.', 'assets/images/accessories/suits/shoes/loafers.jpg'),
(17, 'Monk Strap', 4, 'A buckled profile with understated authority.', 'assets/images/accessories/suits/shoes/monk2.jpg'),
(18, 'Oxford', 4, 'The formal benchmark, closed-laced and clean.', 'assets/images/accessories/suits/shoes/oxford-category.jpg'),
(19, 'Block Heels', 5, 'Grounded elegance from ceremony to last dance.', 'assets/images/accessories/dresses/shoes/block-heel-category.jpg'),
(20, 'High Heels', 5, 'A poised heel with a romantic finish.', 'assets/images/accessories/dresses/shoes/highHeels.jpg'),
(21, 'Bow Ties', 6, 'Bow Ties products for weddings and special occasions.', NULL),
(22, 'Neck Ties', 6, 'Neck Ties products for weddings and special occasions.', NULL),
(23, 'Pocket Squares', 6, 'Pocket Squares products for weddings and special occasions.', NULL),
(24, 'Bridal Sandal', 7, 'Delicate straps with a celebratory shimmer.', 'assets/images/accessories/dresses/shoes/bridal-sandal-category.jpg'),
(25, 'Flats', 7, 'Soft, graceful comfort for every step.', 'assets/images/accessories/dresses/shoes/flat-category.jpg'),
(26, 'Cuff Links', 6, 'Cuff links to finish the groom\'s shirt with a polished detail.', NULL);

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
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT for table `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=183;

--
-- AUTO_INCREMENT for table `subcategories`
--
ALTER TABLE `subcategories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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




-- DANA DUMMY DATA --
-- Compatible with wedding_store database

USE wedding_store;

-- =====================================================
-- DASHBOARD DUMMY DATA
-- For testing charts and statistics only
-- Run after creating all tables and foreign keys
-- =====================================================

START TRANSACTION;

-- =====================================================
-- 1. TEST CUSTOMERS
-- Removed is_active because it does not exist in users
-- =====================================================

INSERT INTO users
(id, full_name, email, password_hash, phone, created_at)
VALUES
(9001, 'Sara Ahmad', 'dashboard.sara@example.com',
 '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
 '0791111111', '2026-01-10 10:00:00'),

(9002, 'Lina Khaled', 'dashboard.lina@example.com',
 '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
 '0792222222', '2026-02-15 11:00:00'),

(9003, 'Omar Ali', 'dashboard.omar@example.com',
 '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
 '0793333333', '2026-03-20 12:00:00'),

(9004, 'Dana Mohammad', 'dashboard.dana@example.com',
 '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
 '0794444444', '2026-04-12 13:00:00'),

(9005, 'Rama Hasan', 'dashboard.rama@example.com',
 '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
 '0795555555', '2026-05-18 14:00:00');


-- =====================================================
-- 2. TEST ORDERS
-- =====================================================

INSERT INTO orders
(id, user_id, order_date, total_amount, order_status,
 full_name, phone, country, city, area,
 shipping_address, building_details, shipping_cost, revenue)
VALUES

(9001, 9001, '2026-01-15 10:00:00', 304.00, 'Delivered',
 'Sara Ahmad', '0791111111', 'Jordan', 'Amman', 'Khalda',
 'Khalda Street', 'Building 12', 5.00, 299.00),

(9002, 9002, '2026-02-18 11:00:00', 174.00, 'Delivered',
 'Lina Khaled', '0792222222', 'Jordan', 'Irbid', 'University Street',
 'University Street', 'Building 8', 5.00, 169.00),

(9003, 9003, '2026-03-22 12:00:00', 318.00, 'Delivered',
 'Omar Ali', '0793333333', 'United States', 'New York', 'Manhattan',
 'Broadway Street', 'Apartment 5', 0.00, 318.00),

(9004, 9004, '2026-04-14 13:00:00', 348.00, 'Delivered',
 'Dana Mohammad', '0794444444', 'Germany', 'Berlin', 'Mitte',
 'Berlin Street', 'Apartment 10', 0.00, 348.00),

(9005, 9005, '2026-05-20 14:00:00', 299.00, 'Delivered',
 'Rama Hasan', '0795555555', 'United Kingdom', 'London', 'Central',
 'Oxford Street', 'Apartment 3', 0.00, 299.00),

(9006, 9001, '2026-06-10 10:30:00', 207.00, 'Delivered',
 'Sara Ahmad', '0791111111', 'Jordan', 'Amman', 'Sweifieh',
 'Sweifieh Street', 'Building 7', 0.00, 207.00),

(9007, 9002, '2026-07-12 11:30:00', 318.00, 'Delivered',
 'Lina Khaled', '0792222222', 'France', 'Paris', 'Central',
 'Paris Street', 'Apartment 4', 0.00, 318.00),

(9008, 9003, '2026-08-16 12:30:00', 169.00, 'Delivered',
 'Omar Ali', '0793333333', 'Canada', 'Toronto', 'Downtown',
 'King Street', 'Apartment 9', 0.00, 169.00),

(9009, 9004, '2026-09-08 13:30:00', 598.00, 'Delivered',
 'Dana Mohammad', '0794444444', 'Jordan', 'Amman', 'Abdoun',
 'Abdoun Street', 'Building 15', 0.00, 598.00),

(9010, 9005, '2026-10-05 14:30:00', 188.00, 'Delivered',
 'Rama Hasan', '0795555555', 'Germany', 'Munich', 'Central',
 'Munich Street', 'Apartment 6', 0.00, 188.00),

(9011, 9001, '2026-10-12 10:00:00', 318.00, 'Confirmed',
 'Sara Ahmad', '0791111111', 'United States', 'Chicago', 'Central',
 'Lake Street', 'Apartment 2', 0.00, 318.00),

(9012, 9002, '2026-10-18 11:00:00', 299.00, 'Shipped',
 'Lina Khaled', '0792222222', 'Jordan', 'Amman', 'Jubeiha',
 'Jubeiha Street', 'Building 20', 0.00, 299.00),

(9013, 9003, '2026-10-21 12:00:00', 49.00, 'Pending',
 'Omar Ali', '0793333333', 'United Kingdom', 'London', 'Central',
 'Baker Street', 'Apartment 1', 0.00, 49.00),

(9014, 9004, '2026-10-24 13:00:00', 169.00, 'Cancelled',
 'Dana Mohammad', '0794444444', 'France', 'Paris', 'Central',
 'Paris Street', 'Apartment 11', 0.00, 0.00),

(9015, 9005, '2026-10-28 14:00:00', 337.00, 'Delivered',
 'Rama Hasan', '0795555555', 'Jordan', 'Zarqa', 'New Zarqa',
 'Main Street', 'Building 3', 0.00, 337.00);


-- =====================================================
-- 3. ORDER ITEMS
-- Product IDs refer to existing products (1 to 56)
-- =====================================================

INSERT INTO order_items
(order_id, product_id, variant_id, quantity, unit_price)
VALUES

(9001, 1, NULL, 1, 299.00),

(9002, 6, NULL, 1, 169.00),

(9003, 2, NULL, 1, 299.00),
(9003, 12, NULL, 1, 19.00),

(9004, 3, NULL, 1, 299.00),
(9004, 22, NULL, 1, 49.00),

(9005, 4, NULL, 1, 299.00),

(9006, 6, NULL, 1, 169.00),
(9006, 12, NULL, 2, 19.00),

(9007, 2, NULL, 1, 299.00),
(9007, 13, NULL, 1, 19.00),

(9008, 7, NULL, 1, 169.00),

(9009, 1, NULL, 2, 299.00),

(9010, 6, NULL, 1, 169.00),
(9010, 15, NULL, 1, 19.00),

(9011, 2, NULL, 1, 299.00),
(9011, 12, NULL, 1, 19.00),

(9012, 5, NULL, 1, 299.00),

(9013, 22, NULL, 1, 49.00),

(9014, 8, NULL, 1, 169.00),

(9015, 3, NULL, 1, 299.00),
(9015, 14, NULL, 2, 19.00);


-- =====================================================
-- 4. TEST PAYMENTS
-- =====================================================

INSERT INTO payments
(order_id, payment_method, amount, payment_status,
 transaction_reference, paid_at)
VALUES

(9001, 'Credit Card', 304.00, 'Paid', 'DEMO-PAY-9001', '2026-01-15 10:05:00'),
(9002, 'Credit Card', 174.00, 'Paid', 'DEMO-PAY-9002', '2026-02-18 11:05:00'),
(9003, 'Credit Card', 318.00, 'Paid', 'DEMO-PAY-9003', '2026-03-22 12:05:00'),
(9004, 'Credit Card', 348.00, 'Paid', 'DEMO-PAY-9004', '2026-04-14 13:05:00'),
(9005, 'Credit Card', 299.00, 'Paid', 'DEMO-PAY-9005', '2026-05-20 14:05:00'),
(9006, 'Cash on Delivery', 207.00, 'Paid', 'DEMO-PAY-9006', '2026-06-10 10:35:00'),
(9007, 'Credit Card', 318.00, 'Paid', 'DEMO-PAY-9007', '2026-07-12 11:35:00'),
(9008, 'Credit Card', 169.00, 'Paid', 'DEMO-PAY-9008', '2026-08-16 12:35:00'),
(9009, 'Credit Card', 598.00, 'Paid', 'DEMO-PAY-9009', '2026-09-08 13:35:00'),
(9010, 'Cash on Delivery', 188.00, 'Paid', 'DEMO-PAY-9010', '2026-10-05 14:35:00'),
(9011, 'Credit Card', 318.00, 'Pending', 'DEMO-PAY-9011', NULL),
(9012, 'Cash on Delivery', 299.00, 'Pending', 'DEMO-PAY-9012', NULL),
(9013, 'Cash on Delivery', 49.00, 'Pending', 'DEMO-PAY-9013', NULL),
(9014, 'Credit Card', 169.00, 'Failed', 'DEMO-PAY-9014', NULL),
(9015, 'Credit Card', 337.00, 'Paid', 'DEMO-PAY-9015', '2026-10-28 14:05:00');

COMMIT;

-- =====================================================
-- END OF DASHBOARD DUMMY DATA
-- =====================================================

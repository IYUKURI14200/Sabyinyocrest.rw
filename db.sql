-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 19, 2026 at 10:20 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sabyinyo_crest`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(60) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password_hash`, `created_at`) VALUES
(1, 'admin', '$2y$10$UYq8uFncW87c8JnPROMrh.eolsbUaGGPF3WAhvlh3Dr/AnQhkbTHi', '2026-08-28 22:34:13');

-- --------------------------------------------------------

--
-- Table structure for table `amenities`
--

CREATE TABLE `amenities` (
  `id` int(11) NOT NULL,
  `label` varchar(120) NOT NULL,
  `sort_order` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `amenities`
--

INSERT INTO `amenities` (`id`, `label`, `sort_order`) VALUES
(1, '24/7 Security', 1),
(2, 'Free Parking', 2),
(3, 'Free Wi-Fi', 3),
(4, 'Transport Facility', 4),
(5, 'Conference & Meeting Rooms', 5);

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(40) NOT NULL,
  `room_id` int(11) DEFAULT NULL,
  `room_name` varchar(120) DEFAULT NULL,
  `check_in` date NOT NULL,
  `check_out` date NOT NULL,
  `message` text DEFAULT NULL,
  `status` enum('new','confirmed','cancelled') DEFAULT 'new',
  `email_sent` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `name`, `email`, `phone`, `room_id`, `room_name`, `check_in`, `check_out`, `message`, `status`, `email_sent`, `created_at`) VALUES
(1, 'sir-lex\'s the king', 'iyukurialexandre@gmail.com', '+250780733996', 2, 'Deluxe Room', '2026-08-29', '2026-08-31', 'one person', 'confirmed', 0, '2026-08-28 23:08:18'),
(2, 'Mucyo', 'salexawasale@gmail.com', '+250786635101', 2, 'Deluxe Room', '2026-08-30', '2026-08-31', 'one personal', 'confirmed', 1, '2026-08-28 23:41:52'),
(3, 'aliane', 'iyukurialexandre@gmail.com', '+2507833996', 2, 'Deluxe Room', '2026-08-30', '2026-08-31', 'one personel', 'confirmed', 1, '2026-08-29 13:21:58'),
(4, 'aliane', 'iyukurialexandre@gmail.com', '+2507833996', 2, 'Deluxe Room', '2026-08-30', '2026-08-31', 'one personel', 'confirmed', 1, '2026-08-29 13:22:04'),
(5, 'juventus', 'iyukurialexandre@gmail.com', '+250786635101', 1, 'Double Room', '2026-08-31', '2026-09-03', 'hello i wanted to stay over there for four days in case the you are room are available pls thanks.', 'confirmed', 1, '2026-08-29 15:10:53'),
(6, 'sir-lex\'s the king', 'iyukurialexandre@gmail.com', '+250780733996', 5, 'Queen (Master bedroom)', '2026-09-01', '2026-10-04', '2', 'confirmed', 1, '2026-09-01 18:26:34'),
(7, 'hammad', 'iyukurialexandre@gmail.com', '+17547483887', 2, 'Deluxe Room', '2026-09-05', '2026-09-15', 'am alone', 'confirmed', 1, '2026-09-02 12:14:59'),
(8, 'mucyo braise', 'iyukurialexandre@gmail.com', '+2507854399', 5, 'Queen (Master bedroom)', '2026-09-27', '2026-09-30', 'only me', 'confirmed', 0, '2026-09-08 23:49:59');

-- --------------------------------------------------------

--
-- Table structure for table `dining_slides`
--

CREATE TABLE `dining_slides` (
  `id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `sort_order` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dining_slides`
--

INSERT INTO `dining_slides` (`id`, `image`, `sort_order`) VALUES
(1, 'assets/img/dining_formal_set.jpg', 1),
(2, 'assets/img/dining_small_ensuite.jpg', 2),
(3, 'assets/img/kitchen.jpg', 3),
(4, 'uploads/site/cb9e4f29c7e6a6fd.jpg', 4),
(5, 'uploads/site/2d2aa5d3c8288860.jpg', 5);

-- --------------------------------------------------------

--
-- Table structure for table `facilities`
--

CREATE TABLE `facilities` (
  `id` int(11) NOT NULL,
  `label` varchar(120) NOT NULL,
  `sort_order` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `facilities`
--

INSERT INTO `facilities` (`id`, `label`, `sort_order`) VALUES
(1, 'Accommodation', 1),
(2, 'Bar & Restaurant', 2),
(3, 'Meeting & Workshop Facilities', 3),
(4, 'Transportation Facility', 4);

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

CREATE TABLE `gallery` (
  `id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `caption` varchar(150) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gallery`
--

INSERT INTO `gallery` (`id`, `image`, `caption`, `sort_order`) VALUES
(1, 'assets/img/exterior_gate_teal.jpg', 'Main entrance', 1),
(2, 'assets/img/exterior_balcony_day.jpg', 'Hotel exterior', 2),
(3, 'assets/img/porch_plants.jpg', 'Garden walkway', 3),
(4, 'assets/img/lounge_grey_sofa.jpg', 'Guest lounge', 4),
(5, 'assets/img/lounge_brickwall_tv.jpg', 'Sitting room', 5),
(6, 'assets/img/dining_formal_set.jpg', 'Dining room', 6),
(7, 'assets/img/kitchen.jpg', 'Kitchenette', 7),
(8, 'assets/img/bathroom_shower.jpg', 'Private bathroom', 8),
(9, 'assets/img/room_wardrobe_hall.jpg', 'Room & ensuite', 9),
(10, 'assets/img/room_desk_chair.jpg', 'Family room', 10),
(11, 'uploads/gallery/922c58e5907bef29.jpg', 'Private bathroom', 11),
(12, 'uploads/gallery/48e7161af9926d95.jpg', 'Guest lounge', 12),
(13, 'uploads/gallery/df5616377a198679.jpg', 'GUEST LIBRALLY', 13),
(14, 'uploads/gallery/49eb74caa77dada2.jpg', 'drawing luxury', 14),
(15, 'uploads/gallery/e1a44dbd7beae4ba.jpg', 'Guest lounge', 15),
(16, 'uploads/gallery/0eb8c67922eff6ea.jpg', 'Guest lounge', 16),
(17, 'uploads/gallery/399c3ba839a91ff2.jpg', 'Guest lounge', 17),
(18, 'uploads/gallery/bd4c4c03ac6cce22.jpg', 'queen bedroom', 18);

-- --------------------------------------------------------

--
-- Table structure for table `hero_slides`
--

CREATE TABLE `hero_slides` (
  `id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `sort_order` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hero_slides`
--

INSERT INTO `hero_slides` (`id`, `image`, `sort_order`) VALUES
(6, 'uploads/site/b61778fedc43dbea.jpg', 5),
(7, 'uploads/site/f9300abe3ffdda8f.jpg', 6),
(8, 'uploads/site/2f3a8726be3bd48f.jpg', 7),
(9, 'uploads/site/da2873d1920cc942.jpg', 8),
(11, 'uploads/site/26d0819a5fa5c6c7.jpg', 9);

-- --------------------------------------------------------

--
-- Table structure for table `menu_categories`
--

CREATE TABLE `menu_categories` (
  `id` int(11) NOT NULL,
  `menu_type` enum('breakfast','lunch_dinner') NOT NULL,
  `name` varchar(120) NOT NULL,
  `icon` varchar(10) DEFAULT '',
  `sort_order` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu_categories`
--

INSERT INTO `menu_categories` (`id`, `menu_type`, `name`, `icon`, `sort_order`) VALUES
(1, 'breakfast', 'Breakfast Menu', '☀️', 1),
(2, 'lunch_dinner', 'Starters & Soups', '🥗', 1),
(3, 'lunch_dinner', 'Main Dishes', '🥩', 2),
(4, 'lunch_dinner', 'Side Dishes', '🥦', 3),
(5, 'lunch_dinner', 'Desserts & Refreshments', '🍹', 4);

-- --------------------------------------------------------

--
-- Table structure for table `menu_items`
--

CREATE TABLE `menu_items` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(8,2) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu_items`
--

INSERT INTO `menu_items` (`id`, `category_id`, `name`, `description`, `price`, `sort_order`) VALUES
(1, 1, 'Fruit Platter', 'Freshly sliced pineapple, sweet mango, banana, passion fruit, tree tomato, and juicy watermelon.', NULL, 1),
(2, 1, 'Bread', '', NULL, 2),
(3, 1, 'Egg Dishes (choice of 2)', 'Boiled egg, scrambled egg, Spanish omelette.', NULL, 3),
(4, 1, 'Vegetables', 'Peas & carrot, dodo.', NULL, 4),
(5, 1, 'Coffee or Tea', 'Black tea, African tea, milk coffee.', NULL, 5),
(6, 2, 'Coleslaw Salad', 'Shredded cabbage, grated carrots, mayonnaise, vinegar, sugar, salt & pepper.', NULL, 1),
(7, 2, 'Niçoise Salad', 'Lettuce, canned tuna, boiled eggs, green beans, cucumber, onions, black olives, lemon, olive oil.', NULL, 2),
(8, 2, 'Sabyinyo Crest Salad', 'Canned tuna, boiled eggs, crisp green beans, tender potatoes, black olives, fresh lemon squeeze.', NULL, 3),
(9, 2, 'Rwandan Isombe', 'Finely blended cassava leaves, ground nuts, garlic, oil, seasoned to taste.', NULL, 4),
(10, 2, 'Peanut Soup', 'Roasted ground nuts, tomatoes, onions, garlic, rich tomato paste, salt & pepper.', NULL, 5),
(11, 2, 'Minestrone Soup', 'Clear chicken stock, garden peas, broken spaghetti, white pepper, light seasoning.', NULL, 6),
(12, 2, 'Creamy Carrot Soup', 'Pureed carrots, onions, aromatic green pepper, garlic, salt & white pepper.', NULL, 7),
(13, 2, 'Pumpkin Soup', 'Velvety pureed pumpkin, thickened with potatoes, onions, water, salt & pepper.', NULL, 8),
(14, 3, 'Fish Brochette', 'Skewered fish fillet, smoky barbecue sauce glaze, local seasonings.', NULL, 1),
(15, 3, 'Chicken Lollipop', 'Crispy chicken drumsticks or wings, flour, seasoned golden breadcrumbs, egg wash.', NULL, 2),
(16, 3, 'Roasted Chicken', 'Juicy chicken leg or breast, savory red sauce reduction, aromatic seasoning.', NULL, 3),
(17, 3, 'Traditional Stew', 'Soft potatoes, green peas, rich red sauce, simmered with onions, green pepper, garlic & spices.', NULL, 4),
(18, 3, 'Local Banana Stew', 'Traditional Kinigi green bananas, onions, garlic, green pepper, savory red sauce.', NULL, 5),
(19, 3, 'Fried Local Beans', 'Pan-fried fresh local beans, onions, celery, garlic, green pepper, fresh tomatoes, tomato paste.', NULL, 6),
(20, 3, 'Beef Africana Stew', 'Boneless beef chunks marinated in aromatic beef masala, rich red sauce, onions, green pepper, garlic.', NULL, 7),
(21, 3, 'Fish Fillet in Bread Crumb', 'Spiced masala fish fillet, crisp golden breadcrumb coating, fried in vegetable oil.', NULL, 8),
(22, 3, 'Rice with Chicken Breast', 'Steamed white rice, tender pan-seared chicken breast, sautéed onion, green pepper, oil.', NULL, 9),
(23, 3, 'Spaghetti Bolognese', 'Spaghetti, savory minced beef, rich tomato paste, onions, green pepper, garlic, spices.', NULL, 10),
(24, 4, 'Buttered Mixed Vegetables', 'Tender green beans, cauliflower florets, sweet peas, sliced carrots, tossed in melted butter.', NULL, 1),
(25, 4, 'Vegetable Ratatouille', 'Slow-simmered tomatoes, cauliflower, eggplant, green beans, peas, herbs & seasoning.', NULL, 2),
(26, 4, 'Mixed Peas with Carrots', 'Fresh green peas, poached carrots, onions, green pepper, garlic, black pepper.', NULL, 3),
(27, 4, 'Fried Potatoes (Chips)', 'Freshly cut local potatoes, crispy golden frying oil, sea salt.', NULL, 4),
(28, 4, 'Crecy Rice', 'Long grain rice, carrots, seasoning.', NULL, 5),
(29, 4, 'Curry Rice', 'Long grain rice, curry powder, olive oil, seasoning.', NULL, 6),
(30, 4, 'Brown Rice', 'Long grain rice, soy sauce, red sauce, seasoning.', NULL, 7),
(31, 5, 'Fruit Platter', 'Freshly sliced pineapple, sweet mango, banana, passion fruit, tree tomato, watermelon, orange.', NULL, 1),
(32, 5, 'Sabyinyo Mocktail', 'A refreshing, layered blend of fresh watermelon, pineapple, and tropical passion fruit.', NULL, 2),
(33, 5, 'Fresh Juice', 'Freshly squeezed pineapple juice or tart tree tomato juice.', NULL, 3),
(34, 5, 'Pancake with Honey', 'Flour, baking powder, baking soda, sugar, honey.', NULL, 4);

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `name` varchar(120) NOT NULL,
  `price` decimal(8,2) NOT NULL DEFAULT 0.00,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `name`, `price`, `description`, `image`, `sort_order`, `created_at`) VALUES
(1, 'Double Room', 30.00, 'One large comfortable bed, private bathroom, free Wi-Fi and daily cleaning — ideal for couples or solo travellers.', 'assets/img/room_gold_bedspread.jpg', 1, '2026-08-28 22:34:13'),
(2, 'Deluxe Room', 45.00, 'A brighter, more spacious room with a plush headboard, wardrobe and garden-facing curtains.', 'assets/img/room_bluepillow_bed.jpg', 2, '2026-08-28 22:34:13'),
(3, 'Family Room', 60.00, 'Extra space with a work desk, perfect for longer stays or travelling with family.', 'uploads/rooms/d341e3cef2d0605e.png', 3, '2026-08-28 22:34:13'),
(5, 'Queen (Master bedroom)', 60.00, 'Spacious master suite bedroom with  fully furnished bathroom ,office , access to the barcony, garden and free private parking space', 'uploads/rooms/5b63a37ee5b8877b.jpg', 4, '2026-09-01 17:43:23');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `icon` varchar(30) DEFAULT 'bed',
  `title` varchar(120) NOT NULL,
  `description` text DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `icon`, `title`, `description`, `sort_order`) VALUES
(1, 'bed', 'Accommodation', 'Clean, comfortable and quiet rooms for guests visiting Musanze.', 1),
(2, 'glass', 'Bar & Restaurant', 'Fresh meals and drinks served in a warm, friendly setting.', 2),
(3, 'users', 'Apartment', 'a comfortable place where a family can stay longer for  fovorate envirnment and adventure place.', 3),
(4, 'car', 'Transportation', 'Airport and in-town transport arranged on request but we add its\'s fee.', 4);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL DEFAULT 1,
  `brand_name` varchar(120) NOT NULL DEFAULT 'Sabyinyo Crest',
  `tagline` varchar(160) NOT NULL DEFAULT 'Cyuve, Musanze · Rwanda',
  `hero_title` varchar(255) NOT NULL DEFAULT 'Adventure begins just <em>outside your room</em>',
  `hero_subtitle` text DEFAULT NULL,
  `hero_image` varchar(255) DEFAULT 'assets/img/exterior_balcony_day.jpg',
  `welcome_body1` text DEFAULT NULL,
  `welcome_body2` text DEFAULT NULL,
  `testimonial_quote` varchar(500) DEFAULT NULL,
  `testimonial_image` varchar(255) DEFAULT 'assets/img/lounge_brickwall_tv.jpg',
  `footer_blurb` varchar(400) DEFAULT NULL,
  `reception_phone` varchar(40) DEFAULT '+250 791 330 698',
  `manager_phone` varchar(40) DEFAULT '+250 795 360 707',
  `whatsapp` varchar(20) DEFAULT '250791330698',
  `email` varchar(150) DEFAULT 'comforthotel10@gmail.com',
  `address` varchar(200) DEFAULT 'Cyuve, Musanze, Rwanda',
  `map_query` varchar(200) DEFAULT 'Sabyinyo Crest, Cyuve, Musanze, Rwanda',
  `facebook` varchar(255) DEFAULT '#',
  `twitter` varchar(255) DEFAULT '#',
  `instagram` varchar(255) DEFAULT '#',
  `reviews_count` int(11) DEFAULT 0,
  `reviews_url` varchar(255) DEFAULT '#',
  `video_enabled` tinyint(1) DEFAULT 0,
  `video_source` enum('upload','youtube') DEFAULT 'upload',
  `video_file` varchar(255) DEFAULT NULL,
  `video_youtube_id` varchar(50) DEFAULT NULL,
  `video_title` varchar(150) DEFAULT 'A Look Inside',
  `video_caption` varchar(300) DEFAULT 'Our guests, our team, and a taste of the Sabyinyo Crest experience.',
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `dining_hero_image` varchar(255) DEFAULT 'assets/img/dining_formal_set.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `brand_name`, `tagline`, `hero_title`, `hero_subtitle`, `hero_image`, `welcome_body1`, `welcome_body2`, `testimonial_quote`, `testimonial_image`, `footer_blurb`, `reception_phone`, `manager_phone`, `whatsapp`, `email`, `address`, `map_query`, `facebook`, `twitter`, `instagram`, `reviews_count`, `reviews_url`, `video_enabled`, `video_source`, `video_file`, `video_youtube_id`, `video_title`, `video_caption`, `updated_at`, `dining_hero_image`) VALUES
(1, 'Sabyinyo Crest', 'Cyuve, Musanze · Rwanda', 'Adventure begins just <em>outside your room</em>', 'Just near by the road going up to Volcanoes National Park in Cyuve, Musanze — a calm, comfortable basecamp for gorilla trekkers, business travellers, and everyone in between.', 'assets/img/exterior_balcony_day.jpg', 'Sabyinyo Crest offers clean, modern, and peaceful ,accommodation,breakfast for both business and leisure travelers. Set in Cyuve, only 20 minutes from Volcanoes National Park, each room is designed for a calm stay — comfortable beds, private bathrooms, strong Wi-Fi, and daily cleaning.', 'Musanze is a popular tourist gateway close to Volcanoes National Park. Sabyinyo Crest is the perfect place to rest before, or recover after, your adventures — with a quiet environment, friendly staff, and breakfast, free strong wi-fi, pravite parking and all  included in every stay.', '\"Where luxury meets comfort, and every stay becomes a cherished memory.\"', 'assets/img/lounge_brickwall_tv.jpg', 'A quiet basecamp in Cyuve, Musanze for travellers headed to the Volcanoes — and everyone who just needs a good night\'s sleep.', '+250 788983860', '+250 735367549', '250788983860', 'iyukurialexandre7@gmail.com', 'Cyuve, Musanze, Rwanda', '-1.48947,29.635212(SabyinyoCrest)', '#', '#', '#', 0, '#', 1, 'upload', 'uploads/site/466329ee405d85c5.mp4', NULL, 'A Look Inside', 'Our guests, our team, and a taste of the Sabyinyo Crest experience.', '2026-09-18 18:48:08', 'uploads/site/3a54efd0975d4ba9.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `amenities`
--
ALTER TABLE `amenities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dining_slides`
--
ALTER TABLE `dining_slides`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `facilities`
--
ALTER TABLE `facilities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gallery`
--
ALTER TABLE `gallery`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hero_slides`
--
ALTER TABLE `hero_slides`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu_categories`
--
ALTER TABLE `menu_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `amenities`
--
ALTER TABLE `amenities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `dining_slides`
--
ALTER TABLE `dining_slides`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `facilities`
--
ALTER TABLE `facilities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `gallery`
--
ALTER TABLE `gallery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `hero_slides`
--
ALTER TABLE `hero_slides`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `menu_categories`
--
ALTER TABLE `menu_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD CONSTRAINT `menu_items_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `menu_categories` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

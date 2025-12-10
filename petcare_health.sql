-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 10, 2025 at 03:30 PM
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
-- Database: `petcare_health`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `email`, `phone`, `created_at`) VALUES
(1, 'admin', '$2y$10$OffYUjoAhzveWQfAwQ6Bgex2Lp3E4SD3cMmyN5cr0707HKbv8QI.e', 'admin@petcare.com', '081234567890', '2025-11-23 13:39:24');

-- --------------------------------------------------------

--
-- Table structure for table `blog_posts`
--

CREATE TABLE `blog_posts` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `content` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL,
  `published_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blog_posts`
--

INSERT INTO `blog_posts` (`id`, `title`, `slug`, `content`, `image`, `category`, `author`, `published_date`, `created_at`) VALUES
(1, 'Tips Merawat Kesehatan Hewan Peliharaan', 'tips-merawat-kesehatan-hewan', 'Merawat hewan peliharaan membutuhkan perhatian khusus. Berikut beberapa tips penting untuk menjaga kesehatan hewan kesayangan Anda...', NULL, 'Kesehatan', 'Dr. Hewan', '2024-01-15', '2025-11-23 13:39:24'),
(2, 'Pentingnya Vaksinasi Rutin', 'pentingnya-vaksinasi-rutin', 'Vaksinasi adalah cara terbaik melindungi hewan peliharaan dari berbagai penyakit menular yang berbahaya...', NULL, 'Vaksinasi', 'Dr. Hewan', '2024-01-20', '2025-11-23 13:39:24'),
(3, 'Panduan Nutrisi Hewan Peliharaan', 'panduan-nutrisi-hewan', 'Nutrisi yang tepat sangat penting untuk kesehatan hewan peliharaan Anda. Pelajari kebutuhan nutrisi berdasarkan jenis dan usia...', NULL, 'Nutrisi', 'Dr. Hewan', '2024-01-25', '2025-11-23 13:39:24');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `owner_name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `pet_name` varchar(100) NOT NULL,
  `pet_type` varchar(50) NOT NULL,
  `service_type` varchar(100) NOT NULL,
  `booking_date` date NOT NULL,
  `booking_time` time NOT NULL,
  `notes` text DEFAULT NULL,
  `status` enum('pending','confirmed','completed','cancelled') DEFAULT 'pending',
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `owner_name`, `phone`, `email`, `pet_name`, `pet_type`, `service_type`, `booking_date`, `booking_time`, `notes`, `status`, `is_read`, `created_at`) VALUES
(1, 'Johan Libert', '0812238248746', 'devanderlebanon@gmail.com', 'Amsterdam', 'Burung', 'Vaksin Rabies', '2025-11-29', '15:00:00', 'sakit mata', 'pending', 0, '2025-11-23 14:01:49'),
(2, 'Natalia', '0847374625', 'natalia@gmail.com', 'Nana', 'Hamster', 'Pembersihan Kutu Hewan', '2025-12-31', '11:00:00', '', 'pending', 0, '2025-12-09 09:13:12');

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `subject` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `category` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `stock` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `category`, `description`, `price`, `image`, `stock`, `created_at`) VALUES
(1, 'Vaksin Rabies', 'Vaksinasi', 'Vaksinasi rabies untuk anjing dan kucing, melindungi dari virus rabies', 250000.00, NULL, 50, '2025-11-23 13:39:24'),
(2, 'Medical Check-Up Basic', 'Pemeriksaan', 'Pemeriksaan kesehatan dasar meliputi suhu, detak jantung, dan kondisi umum', 150000.00, NULL, 100, '2025-11-23 13:39:24'),
(3, 'Grooming Lengkap', 'Perawatan', 'Paket grooming lengkap: mandi, potong kuku, sikat gigi, dan styling', 200000.00, NULL, 30, '2025-11-23 13:39:24'),
(4, 'Sterilisasi', 'Operasi', 'Operasi sterilisasi untuk kucing dan anjing kecil', 500000.00, NULL, 20, '2025-11-23 13:39:24'),
(5, 'Perawatan Gigi', 'Perawatan', 'Pembersihan karang gigi dan perawatan kesehatan gigi', 300000.00, NULL, 40, '2025-11-23 13:39:24'),
(6, 'Vaksin FVRCP (Vaccine)', 'Vaksinasi', 'Vaksinasi lengkap untuk kucing meliputi feline viral rhinotracheitis, calicivirus, dan panleukopenia', 300000.00, NULL, 45, '2025-12-08 14:10:18'),
(7, 'Vaksin Ketahanan Tubuh', 'Vaksinasi', 'Meningkatkan sistem imun dan ketahanan tubuh hewan terhadap penyakit', 200000.00, NULL, 60, '2025-12-08 14:10:18'),
(8, 'Pemeriksaan Gigi', 'Pemeriksaan', 'Pemeriksaan kesehatan gigi dan mulut hewan secara menyeluruh', 175000.00, NULL, 80, '2025-12-08 14:10:18'),
(9, 'Pemeriksaan Organ Dalam', 'Pemeriksaan', 'Pemeriksaan organ dalam menggunakan USG dan rontgen untuk deteksi dini penyakit', 400000.00, NULL, 40, '2025-12-08 14:10:18'),
(10, 'Pembersihan Kutu Hewan', 'Perawatan', 'Perawatan khusus untuk membersihkan dan mencegah kutu pada hewan peliharaan', 150000.00, NULL, 50, '2025-12-08 14:10:18'),
(11, 'Operasi Tulang Patah', 'Operasi', 'Tindakan operasi untuk penanganan patah tulang pada hewan dengan peralatan modern', 1500000.00, NULL, 15, '2025-12-08 14:10:18'),
(12, 'Penanganan Luka Dalam', 'Operasi', 'Operasi untuk penanganan luka dalam dan trauma pada hewan peliharaan', 800000.00, NULL, 25, '2025-12-08 14:10:18');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `blog_posts`
--
ALTER TABLE `blog_posts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `blog_posts`
--
ALTER TABLE `blog_posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

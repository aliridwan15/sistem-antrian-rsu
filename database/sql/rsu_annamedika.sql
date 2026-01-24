-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 24, 2026 at 01:21 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rsu_annamedika`
--

-- --------------------------------------------------------

--
-- Table structure for table `antrians`
--

CREATE TABLE `antrians` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `no_antrian` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nik` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_pasien` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nomor_hp` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `poli` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dokter` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_kontrol` date NOT NULL,
  `status` enum('Menunggu','Dipanggil','Selesai','Batal') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Menunggu',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `antrians`
--

INSERT INTO `antrians` (`id`, `user_id`, `no_antrian`, `nik`, `nama_pasien`, `tanggal_lahir`, `jenis_kelamin`, `nomor_hp`, `alamat`, `poli`, `dokter`, `tanggal_kontrol`, `status`, `created_at`, `updated_at`) VALUES
(1, NULL, 'U-001', '1234567890123456', 'Ali Ridwan Nurhasan', '2026-01-08', 'Laki-laki', '082334644850', 'JL. Merdeka', 'Poli Penyakit Dalam', 'dr. Donny Valiandra, Sp.PD', '2026-01-22', 'Menunggu', '2026-01-22 08:52:37', '2026-01-22 08:52:37'),
(2, NULL, 'U-002', '1234567890123456', 'Ali Ridwan Nurhasan', '2026-01-01', 'Laki-laki', '082334644850', 'JL. Merdeka', 'Poli Subspesialis Ginjal & Hipertensi', 'dr. Rosida Fajariya, Sp.PD', '2026-01-23', 'Menunggu', '2026-01-22 08:52:54', '2026-01-22 08:52:54'),
(3, 4, 'P-001', '1234567890123456', 'Ali Ridwan Nurhasan', '2026-01-01', 'Laki-laki', '082334644850', 'JL. Merdeka', 'Poli Paru', 'dr. Lia Priscilia Purnama Putri, Sp.P', '2026-01-22', 'Menunggu', '2026-01-22 08:57:13', '2026-01-22 08:57:13'),
(4, 4, 'U-003', '1234567890123456', 'Ali Ridwan Nurhasan', '2026-01-01', 'Laki-laki', '082334644850', 'JL. Merdeka', 'Poli Tumbuh Kembang', 'dr. Siti Wahyu Windarti, Sp.A, M.Ked.klin', '2026-01-22', 'Menunggu', '2026-01-22 08:57:42', '2026-01-22 08:57:42'),
(5, 4, 'U-001', '1234567890123456', 'Ali Ridwan Nurhasan', '2026-01-02', 'Laki-laki', '082334644850', 'JL. Merdeka', 'Poli Tumbuh Kembang', 'dr. Siti Wahyu Windarti, Sp.A, M.Ked.klin', '2026-01-23', 'Menunggu', '2026-01-22 18:04:49', '2026-01-22 18:04:49'),
(6, 4, 'A-001', '1234567890123456', 'Ali Ridwan Nurhasan', '2026-01-01', 'Laki-laki', '082334644850', 'JL. Merdeka', 'Poli Anak', 'dr. Siti Wahyu Windarti, Sp.A, M.Ked.klin', '2026-01-23', 'Menunggu', '2026-01-22 19:44:59', '2026-01-22 19:44:59'),
(7, 4, 'L-001', '1234567890123456', 'Ali Ridwan Nurhasan', '2026-01-01', 'Laki-laki', '082334644850', 'JL. Merdeka', 'Poli Kulit & Kelamin', 'dr. Farhat SuryaNingrat, Sp.KK', '2026-01-23', 'Menunggu', '2026-01-22 20:35:30', '2026-01-22 20:35:30'),
(8, 4, 'U-002', '1234567890123456', 'Ali Ridwan Nurhasan', '2026-01-08', 'Laki-laki', '082334644850', 'JL. Merdeka', 'Poli Tumbuh Kembang', 'dr. Siti Wahyu Windarti, Sp.A, M.Ked.klin', '2026-01-23', 'Menunggu', '2026-01-22 20:36:30', '2026-01-22 20:36:30'),
(9, 4, 'M-001', '1234567890123456', 'Ali Ridwan Nurhasan', '2026-01-02', 'Laki-laki', '082334644850', 'JL. Merdeka', 'Poli Mata', 'dr. Mohammad Haikal Bakry, Sp.M', '2026-01-23', 'Menunggu', '2026-01-22 20:41:19', '2026-01-22 20:41:19'),
(10, 4, 'T-001', '1234567890123456', 'Ali Ridwan Nurhasan', '2026-01-02', 'Laki-laki', '082334644850', 'JL. Merdeka', 'Poli THT', 'dr. Indah Yuliarini, Sp.T.H.T.KL', '2026-01-24', 'Selesai', '2026-01-24 02:15:45', '2026-01-24 02:16:43'),
(11, 4, 'J-001', '1234567890123456', 'Ali Ridwan Nurhasan', '2026-01-01', 'Laki-laki', '082334644850', 'JL. Merdeka', 'Poli Jantung', 'dr. Arief Rahman Hakim, Sp.JP', '2026-01-24', 'Selesai', '2026-01-24 02:27:34', '2026-01-24 02:37:23'),
(12, 4, 'M-001', '1234567890123456', 'Ali Ridwan Nurhasan', '2026-01-01', 'Laki-laki', '082334644850', 'JL. Merdeka', 'Poli Mata', 'dr. Mohammad Haikal Bakry, Sp.M', '2026-01-24', 'Menunggu', '2026-01-24 03:05:49', '2026-01-24 03:05:49'),
(13, 4, 'KK-001', '1234567890123456', 'Ali Ridwan Nurhasan', '2026-01-01', 'Laki-laki', '082334644850', 'JL. Merdeka', 'Poli Kulit & Kelamin', 'dr. Farhat SuryaNingrat, Sp.KK', '2026-01-28', 'Menunggu', '2026-01-24 04:22:10', '2026-01-24 04:22:10');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'dr. Siti Wahyu Windarti, Sp.A, M.Ked.klin', '2026-01-14 02:11:00', '2026-01-14 02:11:00'),
(2, 'dr. Fatimah Arief, Sp.A, M.Ked.klin', '2026-01-14 02:11:00', '2026-01-14 02:11:00'),
(3, 'dr. Widjaja Indrachan, Sp.OG', '2026-01-14 02:11:00', '2026-01-14 02:11:00'),
(4, 'dr. Desak Ketut Ayu Aryani, Sp.OG', '2026-01-14 02:11:00', '2026-01-14 02:11:00'),
(5, 'dr. Nurul Hidayat, Sp.B', '2026-01-14 02:11:00', '2026-01-14 02:11:00'),
(6, 'dr. Khoirotul Ummah, Sp.PD', '2026-01-14 02:11:00', '2026-01-14 02:11:00'),
(7, 'dr. Farhat SuryaNingrat, Sp.KK', '2026-01-14 02:11:00', '2026-01-14 02:11:00'),
(8, 'dr. Indah Yuliarini, Sp.T.H.T.KL', '2026-01-14 02:11:00', '2026-01-14 02:11:00'),
(9, 'dr. Fery Setiabudy, Sp.S', '2026-01-14 02:11:00', '2026-01-14 02:11:00'),
(10, 'dr. Ida Bagus Adhi P, Sp.OT', '2026-01-14 02:11:00', '2026-01-14 02:11:00'),
(11, 'dr. Rosida Fajariya, Sp.PD', '2026-01-14 02:11:00', '2026-01-14 02:11:00'),
(15, 'dr. Nur Waqiah, Sp.OG, M.Kes', '2026-01-16 22:26:55', '2026-01-16 22:26:55'),
(16, 'dr. Rony Richardo, Sp.B', '2026-01-17 00:48:32', '2026-01-17 00:48:32'),
(17, 'dr. Donny Valiandra, Sp.PD', '2026-01-17 00:52:57', '2026-01-17 00:52:57'),
(18, 'dr. Yasmita Rahajeng, Sp.PD', '2026-01-17 00:53:55', '2026-01-17 00:53:55'),
(19, 'dr. Rosida Fajariya, Sp.PD', '2026-01-17 00:56:29', '2026-01-17 00:56:29'),
(20, 'dr. Mohammad Edwin Prasetyo, Sp.PD', '2026-01-17 00:58:30', '2026-01-17 00:58:30'),
(21, 'dr. Primita Ayu Damayanti, Sp.N', '2026-01-17 01:02:31', '2026-01-17 01:02:31'),
(22, 'dr. Ima Wiryani Sofyan, Sp.T.H.T.KL', '2026-01-17 01:04:56', '2026-01-17 01:04:56'),
(23, 'dr. Andri Eko P, Sp.P', '2026-01-17 01:07:00', '2026-01-17 01:07:00'),
(24, 'dr. Lia Priscilia Purnama Putri, Sp.P', '2026-01-17 01:08:29', '2026-01-17 01:08:29'),
(25, 'dr. Arief Rahman Hakim, Sp.JP', '2026-01-17 01:09:51', '2026-01-17 01:09:51'),
(27, 'dr. Ari Alauddin Mawdudi, Sp.U.M.Ked.klin', '2026-01-17 01:15:00', '2026-01-17 01:15:00'),
(28, 'dr. Mohammad Haikal Bakry, Sp.M', '2026-01-17 01:16:45', '2026-01-17 01:16:45'),
(29, 'dr. Indah Sulistyani, Sp.A', '2026-01-17 01:37:36', '2026-01-17 01:37:36');

-- --------------------------------------------------------

--
-- Table structure for table `doctor_poli`
--

CREATE TABLE `doctor_poli` (
  `id` bigint UNSIGNED NOT NULL,
  `doctor_id` bigint UNSIGNED NOT NULL,
  `poli_id` bigint UNSIGNED NOT NULL,
  `day` varchar(255) NOT NULL,
  `time` varchar(100) NOT NULL,
  `note` varchar(255) DEFAULT NULL,
  `status` enum('Aktif','OFF') NOT NULL DEFAULT 'Aktif',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `doctor_poli`
--

INSERT INTO `doctor_poli` (`id`, `doctor_id`, `poli_id`, `day`, `time`, `note`, `status`, `created_at`, `updated_at`) VALUES
(2, 2, 1, 'Senin, Selasa, Kamis', '15.30 - Selesai', NULL, 'Aktif', '2026-01-15 07:52:20', '2026-01-16 22:22:00'),
(3, 3, 2, 'Senin, Selasa, Rabu, Kamis, Jumat', '15.00 - Selesai', NULL, 'Aktif', '2026-01-15 07:52:20', '2026-01-16 22:28:22'),
(4, 5, 3, 'Senin, Selasa, Rabu, Kamis, Jumat', '06.00 - Selesai', NULL, 'Aktif', '2026-01-15 07:52:20', '2026-01-17 00:46:52'),
(5, 4, 2, 'Selasa, Rabu', '06.30 - Selesai', NULL, 'Aktif', '2026-01-15 07:52:20', '2026-01-16 22:28:59'),
(6, 6, 4, 'Senin, Selasa, Kamis, Sabtu', '14.00 - Selesai', NULL, 'Aktif', '2026-01-15 07:52:20', '2026-01-17 00:51:54'),
(8, 7, 9, 'Rabu', '16.00 - Selesai', NULL, 'Aktif', '2026-01-15 07:52:20', '2026-01-17 01:22:21'),
(9, 8, 8, 'Senin, Selasa, Rabu, Kamis', '14.30 - Selesai', NULL, 'Aktif', '2026-01-15 07:52:20', '2026-01-21 01:14:51'),
(10, 9, 7, 'Senin, Selasa, Rabu, Kamis, Jumat, Sabtu', '09.00 - Selesai', NULL, 'Aktif', '2026-01-15 07:52:20', '2026-01-21 01:14:22'),
(11, 10, 10, 'Sabtu', '14.00 - Selesai', NULL, 'Aktif', '2026-01-15 07:52:20', '2026-01-19 00:22:06'),
(12, 11, 15, 'Sabtu', '09.00 - 12.00', 'Membuat Janji Terlebih Dahulu', 'Aktif', '2026-01-15 07:52:20', '2026-01-17 01:37:03'),
(51, 1, 1, 'Senin, Jumat', '12.30 - Selesai', NULL, 'Aktif', '2026-01-16 22:14:29', '2026-01-21 01:06:40'),
(52, 1, 14, 'Senin, Jumat', '12.30 - Selesai', NULL, 'Aktif', '2026-01-16 22:14:29', '2026-01-21 01:06:40'),
(53, 1, 1, 'Rabu, Kamis', '12.00 - Selesai', NULL, 'Aktif', '2026-01-16 22:14:29', '2026-01-21 01:06:40'),
(54, 1, 14, 'Rabu, Kamis', '12.00 - Selesai', NULL, 'Aktif', '2026-01-16 22:14:29', '2026-01-21 01:06:40'),
(55, 1, 1, 'Sabtu', '08.30 - Selesai', NULL, 'Aktif', '2026-01-16 22:14:29', '2026-01-21 01:06:40'),
(56, 1, 14, 'Sabtu', '08.30 - Selesai', NULL, 'Aktif', '2026-01-16 22:14:29', '2026-01-21 01:06:40'),
(57, 2, 1, 'Sabtu', '14.30 - Selesai', NULL, 'Aktif', '2026-01-16 22:22:00', '2026-01-16 22:22:00'),
(59, 15, 2, 'Senin, Rabu, Kamis', '12.30 - Selesai', NULL, 'Aktif', '2026-01-16 22:26:55', '2026-01-16 22:26:55'),
(60, 16, 3, 'Senin, Rabu, Jumat', '17.00-Selesai', NULL, 'Aktif', '2026-01-17 00:48:32', '2026-01-21 01:08:45'),
(61, 17, 4, 'Senin, Kamis', '08.00-Selesai', NULL, 'Aktif', '2026-01-17 00:52:57', '2026-01-17 00:52:57'),
(62, 18, 4, 'Senin, Selasa, Rabu, Kamis, Jumat', '15.00 - Selesai', NULL, 'Aktif', '2026-01-17 00:53:55', '2026-01-17 00:54:10'),
(63, 19, 4, 'Senin, Rabu, Jumat', '20.00-Selesai', NULL, 'Aktif', '2026-01-17 00:56:29', '2026-01-17 00:56:29'),
(64, 19, 4, 'Selasa, Kamis', '15.00-Selesai', NULL, 'Aktif', '2026-01-17 00:56:29', '2026-01-17 00:56:29'),
(65, 20, 4, 'Senin, Kamis', '10.00-Selesai', NULL, 'Aktif', '2026-01-17 00:58:30', '2026-01-17 00:58:30'),
(66, 20, 4, 'Selasa, Rabu, Jumat', '08.00-Selesai', NULL, 'Aktif', '2026-01-17 00:58:30', '2026-01-17 00:58:30'),
(67, 21, 7, 'Senin, Selasa, Rabu, Kamis', '17.00-Selesai', NULL, 'Aktif', '2026-01-17 01:02:31', '2026-01-17 01:02:31'),
(68, 22, 8, 'Senin, Selasa, Kamis, Jumat', '09.00-Selesai', NULL, 'Aktif', '2026-01-17 01:04:56', '2026-01-17 01:05:28'),
(69, 23, 5, 'Senin, Selasa, Rabu, Kamis, Jumat', '14.05-Selesai', NULL, 'Aktif', '2026-01-17 01:07:00', '2026-01-17 01:07:00'),
(70, 24, 5, 'Selasa, Kamis', '16.00-Selesai', NULL, 'Aktif', '2026-01-17 01:08:29', '2026-01-17 01:08:29'),
(71, 24, 5, 'Sabtu', '09.00-Selesai', NULL, 'Aktif', '2026-01-17 01:08:29', '2026-01-17 01:08:29'),
(72, 25, 6, 'Senin, Rabu, Kamis', '15.00 - Selesai', NULL, 'Aktif', '2026-01-17 01:09:51', '2026-01-17 01:09:51'),
(74, 27, 11, 'Senin', '08.00-Selesai', NULL, 'Aktif', '2026-01-17 01:15:00', '2026-01-17 01:15:00'),
(75, 27, 11, 'Selasa', '14.00 - Selesai', NULL, 'Aktif', '2026-01-17 01:15:00', '2026-01-17 01:15:00'),
(76, 27, 11, 'Rabu', '09.00-Selesai', NULL, 'Aktif', '2026-01-17 01:15:00', '2026-01-17 01:15:00'),
(77, 27, 11, 'Kamis', '13.00-Selesai', NULL, 'Aktif', '2026-01-17 01:15:00', '2026-01-17 01:15:00'),
(78, 28, 13, 'Senin, Selasa, Sabtu', '08.00-Selesai', NULL, 'Aktif', '2026-01-17 01:16:45', '2026-01-17 01:16:45'),
(79, 29, 1, 'OFF', 'OFF', NULL, 'OFF', '2026-01-17 01:37:36', '2026-01-17 01:37:36'),
(80, 29, 14, 'OFF', 'OFF', NULL, 'OFF', '2026-01-17 01:37:36', '2026-01-17 01:37:36'),
(81, 9, 7, 'Senin, Selasa, Rabu, Kamis, Jumat', '14.00 - Selesai', NULL, 'Aktif', '2026-01-21 01:13:52', '2026-01-21 01:14:22');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_01_19_132939_create_master_data_tables', 2),
(5, '2026_01_19_133010_create_doctor_poli_table', 3),
(8, '2026_01_22_135715_create_antrians_table', 4),
(9, '2026_01_23_033150_add_kode_to_polis_table', 5);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `polis`
--

CREATE TABLE `polis` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `kode` varchar(10) DEFAULT NULL COMMENT 'Kode huruf untuk antrian (A, B, G, dll)',
  `icon` varchar(50) DEFAULT 'bi-hospital',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `polis`
--

INSERT INTO `polis` (`id`, `name`, `kode`, `icon`, `created_at`, `updated_at`) VALUES
(1, 'Poli Anak', 'A', 'bi-emoji-smile', '2026-01-14 01:49:54', '2026-01-23 03:32:49'),
(2, 'Poli Kandungan', 'K', 'bi-gender-female', '2026-01-14 01:49:54', '2026-01-23 03:32:49'),
(3, 'Poli Bedah', 'B', 'bi-scissors', '2026-01-14 01:49:54', '2026-01-23 03:32:49'),
(4, 'Poli Penyakit Dalam', 'PD', 'bi-clipboard-heart', '2026-01-14 01:49:54', '2026-01-22 20:48:43'),
(5, 'Poli Paru', 'P', 'bi-lungs', '2026-01-14 01:49:54', '2026-01-23 03:32:49'),
(6, 'Poli Jantung', 'J', 'bi-heart-pulse', '2026-01-14 01:49:54', '2026-01-23 03:32:49'),
(7, 'Poli Syaraf', 'S', 'bi-diagram-3', '2026-01-14 01:49:54', '2026-01-23 03:32:49'),
(8, 'Poli THT', 'T', 'bi-ear', '2026-01-14 01:49:54', '2026-01-23 03:32:49'),
(9, 'Poli Kulit & Kelamin', 'KK', 'bi-droplet', '2026-01-14 01:49:54', '2026-01-23 07:00:47'),
(10, 'Poli Orthopedi', 'O', 'bi-person-wheelchair', '2026-01-14 01:49:54', '2026-01-23 03:32:49'),
(11, 'Poli Urologi', 'U', 'bi-gender-male', '2026-01-14 01:49:54', '2026-01-23 03:32:49'),
(12, 'Poli Gigi', 'G', 'bi-emoji-grin', '2026-01-14 01:49:54', '2026-01-23 03:32:49'),
(13, 'Poli Mata', 'M', 'bi-eye', '2026-01-14 01:49:54', '2026-01-23 03:32:49'),
(14, 'Poli Tumbuh Kembang', 'TK', 'bi-graph-up', '2026-01-14 01:49:54', '2026-01-22 20:48:08'),
(15, 'Poli Subspesialis Ginjal & Hipertensi', 'GH', 'bi-droplet-half', '2026-01-14 01:49:54', '2026-01-22 20:48:58');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('NCuwyICQdpgJjmwgnZoAaJqzdezevaJqXimHrXpi', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQ1pwUlRqQ1RXSXpFM1RUR0V1TUE1QXdhN2hlemlKWDFwcW5oZjdFaiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NjA6Imh0dHBzOi8vc2lzdGVtLWFudHJpYW4udGVzdC9qYWR3YWwtZG9rdGVyP3BvbGk9UG9saSUyMFN5YXJhZiI7czo1OiJyb3V0ZSI7czoxMzoiamFkd2FsLmRva3RlciI7fX0=', 1769257720);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pasien',
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'admin@rs.com', NULL, '$2y$12$kIwu/bgndoyT2eKkbVtWNOeOf61ia2DVF7bkdiYL77sjXbBtsMzZu', 'admin', NULL, '2026-01-11 20:48:16', '2026-01-11 20:48:16'),
(2, 'Pasien Budi', 'budi@gmail.com', NULL, '$2y$12$jBYGYggLzMB0cLGx5BCWIur04129Hu8db79xk5OoHdFOQqu62eghK', 'pasien', NULL, '2026-01-11 20:48:16', '2026-01-11 20:48:16'),
(3, 'Admin', 'admin@gmail.com', NULL, '$2y$12$zHLPbi.miQ7k32CjyK/VZu9XnKQ6RsIPh9wu1vJgtDmXAg5yXp2bO', 'admin', NULL, '2026-01-11 21:00:43', '2026-01-11 21:00:43'),
(4, 'Kurniawan', 'kur@gmail.com', NULL, '$2y$12$55s1dtd6JwLvVDE2uDuQ4Ox4Qmmtjqe4qzeE1XrX6.IMZG02R3Jnm', 'pasien', NULL, '2026-01-12 10:11:53', '2026-01-12 10:11:53'),
(5, 'Sarung Tangan Gym', 'sar@gmail.com', NULL, '$2y$12$hqtZ94A.vAn/J8chyRId4OuXVNMqI3aoyUYvv127yNydgM.9m67JC', 'pasien', NULL, '2026-01-23 07:08:00', '2026-01-23 07:08:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `antrians`
--
ALTER TABLE `antrians`
  ADD PRIMARY KEY (`id`),
  ADD KEY `antrians_user_id_foreign` (`user_id`),
  ADD KEY `antrians_no_antrian_created_at_index` (`no_antrian`,`created_at`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctor_poli`
--
ALTER TABLE `doctor_poli`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doctor_poli_doctor_id_foreign` (`doctor_id`),
  ADD KEY `doctor_poli_poli_id_foreign` (`poli_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `polis`
--
ALTER TABLE `polis`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `antrians`
--
ALTER TABLE `antrians`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `doctor_poli`
--
ALTER TABLE `doctor_poli`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `polis`
--
ALTER TABLE `polis`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `antrians`
--
ALTER TABLE `antrians`
  ADD CONSTRAINT `antrians_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `doctor_poli`
--
ALTER TABLE `doctor_poli`
  ADD CONSTRAINT `doctor_poli_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `doctor_poli_poli_id_foreign` FOREIGN KEY (`poli_id`) REFERENCES `polis` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

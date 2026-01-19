-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 19, 2026 at 07:53 AM
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
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
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
(9, 8, 8, 'Senin, Selasa, Rabu, Kamis', '14.30 - Selesai', NULL, 'Aktif', '2026-01-15 07:52:20', '2026-01-17 01:03:18'),
(10, 9, 7, 'Jumat', '14.00 - Selesai (Sore)', NULL, 'Aktif', '2026-01-15 07:52:20', '2026-01-19 00:23:25'),
(11, 10, 10, 'Sabtu', '14.00 - Selesai', NULL, 'Aktif', '2026-01-15 07:52:20', '2026-01-19 00:22:06'),
(12, 11, 15, 'Sabtu', '09.00 - 12.00', 'Membuat Janji Terlebih Dahulu', 'Aktif', '2026-01-15 07:52:20', '2026-01-17 01:37:03'),
(51, 1, 1, 'Senin, Jumat', '12.30 - Selesai', NULL, 'Aktif', '2026-01-16 22:14:29', '2026-01-16 22:20:06'),
(52, 1, 14, 'Senin, Jumat', '12.30 - Selesai', NULL, 'Aktif', '2026-01-16 22:14:29', '2026-01-16 22:20:06'),
(53, 1, 1, 'Rabu, Kamis', '12.00 - Selesai', NULL, 'Aktif', '2026-01-16 22:14:29', '2026-01-16 22:20:06'),
(54, 1, 14, 'Rabu, Kamis', '12.00 - Selesai', NULL, 'Aktif', '2026-01-16 22:14:29', '2026-01-16 22:20:06'),
(55, 1, 1, 'Sabtu', '08.00 - Selesai', NULL, 'Aktif', '2026-01-16 22:14:29', '2026-01-16 22:20:06'),
(56, 1, 14, 'Sabtu', '08.00 - Selesai', NULL, 'Aktif', '2026-01-16 22:14:29', '2026-01-16 22:20:06'),
(57, 2, 1, 'Sabtu', '14.30 - Selesai', NULL, 'Aktif', '2026-01-16 22:22:00', '2026-01-16 22:22:00'),
(59, 15, 2, 'Senin, Rabu, Kamis', '12.30 - Selesai', NULL, 'Aktif', '2026-01-16 22:26:55', '2026-01-16 22:26:55'),
(60, 16, 3, 'Senin, Selasa, Jumat', '17.00-Selesai', NULL, 'Aktif', '2026-01-17 00:48:32', '2026-01-17 00:48:49'),
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
(80, 29, 14, 'OFF', 'OFF', NULL, 'OFF', '2026-01-17 01:37:36', '2026-01-17 01:37:36');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
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
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `polis`
--

CREATE TABLE `polis` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `icon` varchar(50) DEFAULT 'bi-hospital',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `polis`
--

INSERT INTO `polis` (`id`, `name`, `icon`, `created_at`, `updated_at`) VALUES
(1, 'Poli Anak', 'bi-emoji-smile', '2026-01-14 01:49:54', '2026-01-14 01:49:54'),
(2, 'Poli Kandungan', 'bi-gender-female', '2026-01-14 01:49:54', '2026-01-14 01:49:54'),
(3, 'Poli Bedah', 'bi-scissors', '2026-01-14 01:49:54', '2026-01-14 01:49:54'),
(4, 'Poli Penyakit Dalam', 'bi-clipboard-heart', '2026-01-14 01:49:54', '2026-01-14 01:49:54'),
(5, 'Poli Paru', 'bi-lungs', '2026-01-14 01:49:54', '2026-01-14 01:49:54'),
(6, 'Poli Jantung', 'bi-heart-pulse', '2026-01-14 01:49:54', '2026-01-14 01:49:54'),
(7, 'Poli Syaraf', 'bi-diagram-3', '2026-01-14 01:49:54', '2026-01-14 01:49:54'),
(8, 'Poli THT', 'bi-ear', '2026-01-14 01:49:54', '2026-01-14 01:49:54'),
(9, 'Poli Kulit & Kelamin', 'bi-droplet', '2026-01-14 01:49:54', '2026-01-14 01:49:54'),
(10, 'Poli Orthopedi', 'bi-person-wheelchair', '2026-01-14 01:49:54', '2026-01-14 01:49:54'),
(11, 'Poli Urologi', 'bi-gender-male', '2026-01-14 01:49:54', '2026-01-14 01:49:54'),
(12, 'Poli Gigi', 'bi-emoji-grin', '2026-01-14 01:49:54', '2026-01-14 01:49:54'),
(13, 'Poli Mata', 'bi-eye', '2026-01-14 01:49:54', '2026-01-14 01:49:54'),
(14, 'Poli Tumbuh Kembang', 'bi-graph-up', '2026-01-14 01:49:54', '2026-01-14 01:49:54'),
(15, 'Subspesialis Ginjal', 'bi-droplet-half', '2026-01-14 01:49:54', '2026-01-14 01:49:54');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('G6HxRS3qxWB0lJ2nQS1Ol7WKr6wcGm30lNd0kabM', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.24.0 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRVRla1NhUnZWUUU1OXFxeGV1VkpidWZTWEdhT1BsUGxzcTVQUXVOViI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDA6Imh0dHA6Ly9zaXN0ZW0tYW50cmlhbi50ZXN0Lz9oZXJkPXByZXZpZXciO3M6NToicm91dGUiO3M6NDoiaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1768807049),
('gB2Cj1KeZRdeXWFx8erS6vTiu142rfaQh83zDlor', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.24.0 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiczcyblQ5Y0h1YU82YTRSb1R3dWlZSERxblpnc0FCbXF0dVhqSUROOSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDA6Imh0dHA6Ly9zaXN0ZW0tYW50cmlhbi50ZXN0Lz9oZXJkPXByZXZpZXciO3M6NToicm91dGUiO3M6NDoiaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1768807052),
('IjOWsryBYY6IK0A8NEi4mn44UncyPukuYUMrDloj', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.24.0 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoieG5nUVl0SUF1T0ZnbkhUQ1hYQXY3Wklqb3oyUFRWRWY0NTNzMFBRYSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDA6Imh0dHA6Ly9zaXN0ZW0tYW50cmlhbi50ZXN0Lz9oZXJkPXByZXZpZXciO3M6NToicm91dGUiO3M6NDoiaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1768806824),
('JDAB8EUMNSpUoaCqZbQxx11Xx1TR85aPF88S9iiP', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRWw2dVlWVmNhVkNXcFFTWTI5ME9kMnFpVWVBMFF6ZlRXTTI2UGxXVyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjY6Imh0dHA6Ly9zaXN0ZW0tYW50cmlhbi50ZXN0IjtzOjU6InJvdXRlIjtzOjQ6ImhvbWUiO319', 1768807451);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pasien',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
(4, 'Kurniawan', 'kur@gmail.com', NULL, '$2y$12$55s1dtd6JwLvVDE2uDuQ4Ox4Qmmtjqe4qzeE1XrX6.IMZG02R3Jnm', 'pasien', NULL, '2026-01-12 10:11:53', '2026-01-12 10:11:53');

--
-- Indexes for dumped tables
--

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
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `doctor_poli`
--
ALTER TABLE `doctor_poli`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

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
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `polis`
--
ALTER TABLE `polis`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

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

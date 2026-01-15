-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 15, 2026 at 01:17 AM
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
(11, 'dr. Rosida Fajariya, Sp.PD', '2026-01-14 02:11:00', '2026-01-14 02:11:00');

-- --------------------------------------------------------

--
-- Table structure for table `doctor_poli`
--

CREATE TABLE `doctor_poli` (
  `id` bigint UNSIGNED NOT NULL,
  `doctor_id` bigint UNSIGNED NOT NULL,
  `poli_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `doctor_poli`
--

INSERT INTO `doctor_poli` (`id`, `doctor_id`, `poli_id`) VALUES
(1, 1, 1),
(2, 2, 1),
(3, 3, 2),
(4, 4, 2),
(5, 5, 3),
(6, 6, 4),
(7, 11, 4),
(8, 9, 7),
(9, 8, 8),
(10, 7, 9),
(11, 10, 10),
(16, 1, 14);

-- --------------------------------------------------------

--
-- Table structure for table `doctor_schedules`
--

CREATE TABLE `doctor_schedules` (
  `id` bigint UNSIGNED NOT NULL,
  `doctor_id` bigint UNSIGNED NOT NULL,
  `poli_id` bigint UNSIGNED NOT NULL,
  `day` enum('senin','selasa','rabu','kamis','jumat','sabtu','minggu') NOT NULL,
  `time` varchar(100) NOT NULL,
  `note` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `doctor_schedules`
--

INSERT INTO `doctor_schedules` (`id`, `doctor_id`, `poli_id`, `day`, `time`, `note`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'senin', '12.30 - Selesai', NULL, '2026-01-14 02:11:00', '2026-01-14 02:11:00'),
(2, 2, 1, 'senin', '15.30 - Selesai', NULL, '2026-01-14 02:11:00', '2026-01-14 02:11:00'),
(3, 3, 2, 'senin', '15.00 - Selesai', NULL, '2026-01-14 02:11:00', '2026-01-14 02:11:00'),
(4, 5, 3, 'senin', '06.00 - Selesai', NULL, '2026-01-14 02:11:00', '2026-01-14 02:11:00'),
(5, 4, 2, 'selasa', '06.30 - Selesai', NULL, '2026-01-14 02:11:00', '2026-01-14 02:11:00'),
(6, 6, 4, 'selasa', '14.00 - Selesai', NULL, '2026-01-14 02:11:00', '2026-01-14 02:11:00'),
(7, 1, 1, 'rabu', '12.00 - Selesai', NULL, '2026-01-14 02:11:00', '2026-01-14 02:11:00'),
(8, 7, 9, 'rabu', '16.00 - Selesai', NULL, '2026-01-14 02:11:00', '2026-01-14 02:11:00'),
(9, 8, 8, 'kamis', '14.30 - Selesai', NULL, '2026-01-14 02:11:00', '2026-01-14 02:11:00'),
(10, 9, 7, 'jumat', '14.00 - Selesai (Sore)', NULL, '2026-01-14 02:11:00', '2026-01-14 02:11:00'),
(11, 10, 10, 'sabtu', '14.00 - Selesai', NULL, '2026-01-14 02:11:00', '2026-01-14 02:11:00'),
(12, 11, 15, 'sabtu', '09.00 - 12.00', 'Membuat Janji Terlebih Dahulu', '2026-01-14 02:11:00', '2026-01-14 02:11:00');

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
('io8q4kGBSDwshacxUZnlVHxMU9EAfpTnOXEFOKSM', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUWRlamxLdWh4aWQzUk5yZkNBUTVPaFZuQUxlVkRzM2MyWllFdjFUeiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDQ6Imh0dHA6Ly9zaXN0ZW0tYW50cmlhbi50ZXN0L2FkbWluL2RhdGEtZG9rdGVyIjtzOjU6InJvdXRlIjtzOjE4OiJhZG1pbi5kb2t0ZXIuaW5kZXgiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTozO30=', 1768362587),
('wmLducrsZxkh3b043TzyLozGQVKx9VRwNR1iRbI2', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.24.0 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiYmZqa0tvMDNWVkxUNGpLbjJKbWtSNVhpZkl0Vk9oSkw2Q1NDUlJPdCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDA6Imh0dHA6Ly9zaXN0ZW0tYW50cmlhbi50ZXN0Lz9oZXJkPXByZXZpZXciO3M6NToicm91dGUiO3M6NDoiaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1768353442);

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
-- Indexes for table `doctor_schedules`
--
ALTER TABLE `doctor_schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doctor_id` (`doctor_id`),
  ADD KEY `poli_id` (`poli_id`);

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `doctor_poli`
--
ALTER TABLE `doctor_poli`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `doctor_schedules`
--
ALTER TABLE `doctor_schedules`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

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

--
-- Constraints for table `doctor_schedules`
--
ALTER TABLE `doctor_schedules`
  ADD CONSTRAINT `doctor_schedules_ibfk_1` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `doctor_schedules_ibfk_2` FOREIGN KEY (`poli_id`) REFERENCES `polis` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

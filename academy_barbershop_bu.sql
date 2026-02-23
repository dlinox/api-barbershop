-- --------------------------------------------------------
-- Host:                         34.176.227.41
-- Versión del servidor:         8.0.45-0ubuntu0.24.04.1 - (Ubuntu)
-- SO del servidor:              Linux
-- HeidiSQL Versión:             12.12.0.7122
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Volcando estructura para tabla db_barbershop.academy_attendances
CREATE TABLE IF NOT EXISTS `academy_attendances` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `enrollment_id` bigint unsigned NOT NULL,
  `attendance_deadline_id` bigint unsigned NOT NULL,
  `check_in` time DEFAULT NULL,
  `check_out` time DEFAULT NULL,
  `status` enum('present','absent','late','absent_justified','late_justified') COLLATE utf8mb4_unicode_ci NOT NULL,
  `observation` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `academy_attendances_enrollment_id_attendance_deadline_id_unique` (`enrollment_id`,`attendance_deadline_id`),
  KEY `academy_attendances_enrollment_id_index` (`enrollment_id`),
  KEY `academy_attendances_attendance_deadline_id_index` (`attendance_deadline_id`),
  KEY `academy_attendances_status_index` (`status`),
  CONSTRAINT `academy_attendances_attendance_deadline_id_foreign` FOREIGN KEY (`attendance_deadline_id`) REFERENCES `academy_attendance_deadlines` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `academy_attendances_enrollment_id_foreign` FOREIGN KEY (`enrollment_id`) REFERENCES `academy_enrollments` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla db_barbershop.academy_attendances: ~0 rows (aproximadamente)

-- Volcando estructura para tabla db_barbershop.academy_attendance_deadlines
CREATE TABLE IF NOT EXISTS `academy_attendance_deadlines` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `group_id` bigint unsigned NOT NULL,
  `date` date NOT NULL,
  `check_in_deadline` datetime DEFAULT NULL,
  `check_out_deadline` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `academy_attendance_deadlines_group_id_date_unique` (`group_id`,`date`),
  KEY `academy_attendance_deadlines_date_index` (`date`),
  KEY `academy_attendance_deadlines_group_id_index` (`group_id`),
  CONSTRAINT `academy_attendance_deadlines_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `academy_groups` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla db_barbershop.academy_attendance_deadlines: ~0 rows (aproximadamente)

-- Volcando estructura para tabla db_barbershop.academy_branches
CREATE TABLE IF NOT EXISTS `academy_branches` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ubication` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `academy_branches_name_unique` (`name`),
  KEY `academy_branches_is_active_index` (`is_active`),
  KEY `academy_branches_name_index` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla db_barbershop.academy_branches: ~1 rows (aproximadamente)
INSERT INTO `academy_branches` (`id`, `name`, `address`, `ubication`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 'Escuela Bárbaros Juliaca', 'Jr. Unión N° 209', 'Centro', 1, '2026-02-16 11:40:08', '2026-02-17 00:16:20');

-- Volcando estructura para tabla db_barbershop.academy_enrollments
CREATE TABLE IF NOT EXISTS `academy_enrollments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `profile_student_id` bigint unsigned NOT NULL,
  `group_id` bigint unsigned NOT NULL,
  `status` enum('active','cancelled','completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `academy_enrollments_profile_student_id_group_id_unique` (`profile_student_id`,`group_id`),
  KEY `academy_enrollments_group_id_foreign` (`group_id`),
  CONSTRAINT `academy_enrollments_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `academy_groups` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `academy_enrollments_profile_student_id_foreign` FOREIGN KEY (`profile_student_id`) REFERENCES `profile_students` (`core_person_id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla db_barbershop.academy_enrollments: ~32 rows (aproximadamente)
INSERT INTO `academy_enrollments` (`id`, `profile_student_id`, `group_id`, `status`, `created_at`, `updated_at`) VALUES
	(6, 6, 3, 'active', '2026-02-19 09:52:11', '2026-02-19 09:52:11'),
	(7, 7, 3, 'active', '2026-02-19 09:52:50', '2026-02-19 09:52:50'),
	(8, 8, 3, 'active', '2026-02-19 09:53:05', '2026-02-19 09:53:05'),
	(9, 9, 3, 'active', '2026-02-19 09:54:52', '2026-02-19 09:54:52'),
	(10, 10, 3, 'active', '2026-02-19 09:55:11', '2026-02-19 09:55:11'),
	(11, 11, 3, 'active', '2026-02-19 09:55:24', '2026-02-19 09:55:24'),
	(12, 12, 3, 'active', '2026-02-19 09:56:17', '2026-02-19 09:56:17'),
	(13, 13, 3, 'active', '2026-02-19 09:56:41', '2026-02-19 09:56:41'),
	(14, 14, 3, 'active', '2026-02-19 09:57:29', '2026-02-19 09:57:29'),
	(15, 15, 3, 'active', '2026-02-19 09:57:56', '2026-02-19 09:57:56'),
	(16, 16, 3, 'active', '2026-02-19 09:58:48', '2026-02-19 09:58:48'),
	(17, 17, 3, 'active', '2026-02-19 09:59:07', '2026-02-19 09:59:07'),
	(18, 18, 3, 'active', '2026-02-19 09:59:38', '2026-02-19 09:59:38'),
	(19, 20, 3, 'active', '2026-02-19 10:04:54', '2026-02-19 10:04:54'),
	(20, 21, 3, 'active', '2026-02-19 10:05:08', '2026-02-19 10:05:08'),
	(21, 22, 10, 'active', '2026-02-20 14:41:27', '2026-02-20 14:41:27'),
	(22, 23, 10, 'active', '2026-02-20 14:41:44', '2026-02-20 14:41:44'),
	(23, 24, 10, 'active', '2026-02-20 14:43:56', '2026-02-20 14:43:56'),
	(24, 25, 10, 'active', '2026-02-20 14:47:03', '2026-02-20 14:47:03'),
	(25, 26, 10, 'active', '2026-02-20 14:48:38', '2026-02-20 14:48:38'),
	(26, 27, 10, 'active', '2026-02-20 14:51:17', '2026-02-20 14:51:17'),
	(27, 28, 10, 'active', '2026-02-20 14:55:30', '2026-02-20 14:55:30'),
	(28, 29, 10, 'active', '2026-02-20 14:58:30', '2026-02-20 14:58:30'),
	(29, 30, 10, 'active', '2026-02-20 14:59:33', '2026-02-20 14:59:33'),
	(30, 31, 10, 'active', '2026-02-20 15:04:40', '2026-02-20 15:04:40'),
	(31, 32, 10, 'active', '2026-02-20 15:06:02', '2026-02-20 15:06:02'),
	(32, 33, 10, 'active', '2026-02-20 15:10:23', '2026-02-20 15:10:23'),
	(33, 34, 10, 'active', '2026-02-20 15:12:54', '2026-02-20 15:12:54'),
	(34, 35, 10, 'active', '2026-02-20 15:14:09', '2026-02-20 15:14:09'),
	(35, 36, 12, 'active', '2026-02-20 18:36:02', '2026-02-20 18:36:02'),
	(36, 37, 12, 'active', '2026-02-20 18:38:04', '2026-02-20 18:38:04'),
	(37, 38, 12, 'active', '2026-02-20 18:39:20', '2026-02-20 18:39:20');

-- Volcando estructura para tabla db_barbershop.academy_enrollment_payments
CREATE TABLE IF NOT EXISTS `academy_enrollment_payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `enrollment_id` bigint unsigned NOT NULL,
  `group_payment_plan_id` bigint unsigned NOT NULL,
  `type` enum('enrollment','monthly') COLLATE utf8mb4_unicode_ci NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `academy_enrollment_payments_enrollment_id_foreign` (`enrollment_id`),
  KEY `academy_enrollment_payments_group_payment_plan_id_foreign` (`group_payment_plan_id`),
  CONSTRAINT `academy_enrollment_payments_enrollment_id_foreign` FOREIGN KEY (`enrollment_id`) REFERENCES `academy_enrollments` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `academy_enrollment_payments_group_payment_plan_id_foreign` FOREIGN KEY (`group_payment_plan_id`) REFERENCES `academy_group_payment_plans` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=82 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla db_barbershop.academy_enrollment_payments: ~72 rows (aproximadamente)
INSERT INTO `academy_enrollment_payments` (`id`, `enrollment_id`, `group_payment_plan_id`, `type`, `subtotal`, `discount`, `total`, `created_at`, `updated_at`) VALUES
	(10, 6, 21, 'enrollment', 250.00, 0.00, 250.00, '2026-02-19 09:52:11', '2026-02-19 09:52:11'),
	(11, 6, 22, 'monthly', 650.00, 0.00, 650.00, '2026-02-19 09:52:11', '2026-02-19 09:52:11'),
	(12, 6, 23, 'monthly', 650.00, 0.00, 650.00, '2026-02-19 09:52:11', '2026-02-19 09:52:11'),
	(13, 7, 21, 'enrollment', 250.00, 0.00, 250.00, '2026-02-19 09:52:50', '2026-02-19 09:52:50'),
	(14, 7, 22, 'monthly', 650.00, 0.00, 650.00, '2026-02-19 09:52:50', '2026-02-19 09:52:50'),
	(15, 7, 23, 'monthly', 650.00, 0.00, 650.00, '2026-02-19 09:52:50', '2026-02-19 09:52:50'),
	(16, 8, 21, 'enrollment', 250.00, 0.00, 250.00, '2026-02-19 09:53:05', '2026-02-19 09:53:05'),
	(17, 8, 22, 'monthly', 650.00, 0.00, 650.00, '2026-02-19 09:53:05', '2026-02-19 09:53:05'),
	(18, 8, 23, 'monthly', 650.00, 0.00, 650.00, '2026-02-19 09:53:05', '2026-02-19 09:53:05'),
	(19, 9, 21, 'enrollment', 250.00, 0.00, 250.00, '2026-02-19 09:54:52', '2026-02-19 09:54:52'),
	(20, 9, 22, 'monthly', 650.00, 0.00, 650.00, '2026-02-19 09:54:52', '2026-02-19 09:54:52'),
	(21, 9, 23, 'monthly', 650.00, 0.00, 650.00, '2026-02-19 09:54:52', '2026-02-19 09:54:52'),
	(22, 10, 21, 'enrollment', 250.00, 0.00, 250.00, '2026-02-19 09:55:11', '2026-02-19 09:55:11'),
	(23, 10, 22, 'monthly', 650.00, 0.00, 650.00, '2026-02-19 09:55:11', '2026-02-19 09:55:11'),
	(24, 10, 23, 'monthly', 650.00, 0.00, 650.00, '2026-02-19 09:55:11', '2026-02-19 09:55:11'),
	(25, 11, 21, 'enrollment', 250.00, 0.00, 250.00, '2026-02-19 09:55:24', '2026-02-19 09:55:24'),
	(26, 11, 22, 'monthly', 650.00, 0.00, 650.00, '2026-02-19 09:55:24', '2026-02-19 09:55:24'),
	(27, 11, 23, 'monthly', 650.00, 0.00, 650.00, '2026-02-19 09:55:24', '2026-02-19 09:55:24'),
	(28, 12, 21, 'enrollment', 250.00, 0.00, 250.00, '2026-02-19 09:56:17', '2026-02-19 09:56:17'),
	(29, 13, 21, 'enrollment', 250.00, 0.00, 250.00, '2026-02-19 09:56:41', '2026-02-19 09:56:41'),
	(30, 13, 22, 'monthly', 650.00, 0.00, 650.00, '2026-02-19 09:56:41', '2026-02-19 09:56:41'),
	(31, 13, 23, 'monthly', 650.00, 0.00, 650.00, '2026-02-19 09:56:41', '2026-02-19 09:56:41'),
	(32, 14, 21, 'enrollment', 250.00, 250.00, 0.00, '2026-02-19 09:57:29', '2026-02-19 09:57:29'),
	(33, 14, 22, 'monthly', 650.00, 0.00, 650.00, '2026-02-19 09:57:29', '2026-02-19 09:57:29'),
	(34, 14, 23, 'monthly', 650.00, 0.00, 650.00, '2026-02-19 09:57:29', '2026-02-19 09:57:29'),
	(35, 15, 21, 'enrollment', 250.00, 0.00, 250.00, '2026-02-19 09:57:56', '2026-02-19 09:57:56'),
	(36, 15, 22, 'monthly', 650.00, 0.00, 650.00, '2026-02-19 09:57:56', '2026-02-19 09:57:56'),
	(37, 15, 23, 'monthly', 650.00, 0.00, 650.00, '2026-02-19 09:57:56', '2026-02-19 09:57:56'),
	(38, 16, 21, 'enrollment', 250.00, 0.00, 250.00, '2026-02-19 09:58:48', '2026-02-19 09:58:48'),
	(39, 16, 22, 'monthly', 650.00, 0.00, 650.00, '2026-02-19 09:58:48', '2026-02-19 09:58:48'),
	(40, 16, 23, 'monthly', 650.00, 0.00, 650.00, '2026-02-19 09:58:48', '2026-02-19 09:58:48'),
	(41, 17, 21, 'enrollment', 250.00, 0.00, 250.00, '2026-02-19 09:59:07', '2026-02-19 09:59:07'),
	(42, 17, 22, 'monthly', 650.00, 0.00, 650.00, '2026-02-19 09:59:07', '2026-02-19 09:59:07'),
	(43, 17, 23, 'monthly', 650.00, 0.00, 650.00, '2026-02-19 09:59:07', '2026-02-19 09:59:07'),
	(44, 18, 21, 'enrollment', 250.00, 0.00, 250.00, '2026-02-19 09:59:38', '2026-02-19 09:59:38'),
	(45, 18, 23, 'monthly', 650.00, 0.00, 650.00, '2026-02-19 09:59:38', '2026-02-19 09:59:38'),
	(46, 19, 21, 'enrollment', 250.00, 0.00, 250.00, '2026-02-19 10:04:54', '2026-02-19 10:04:54'),
	(47, 20, 21, 'enrollment', 250.00, 0.00, 250.00, '2026-02-19 10:05:08', '2026-02-19 10:05:08'),
	(48, 20, 22, 'monthly', 650.00, 0.00, 650.00, '2026-02-19 10:05:08', '2026-02-19 10:05:08'),
	(49, 20, 23, 'monthly', 650.00, 0.00, 650.00, '2026-02-19 10:05:08', '2026-02-19 10:05:08'),
	(50, 21, 27, 'enrollment', 250.00, 0.00, 250.00, '2026-02-20 14:41:27', '2026-02-20 14:41:27'),
	(51, 21, 28, 'monthly', 650.00, 0.00, 650.00, '2026-02-20 14:41:27', '2026-02-20 14:41:27'),
	(52, 22, 27, 'enrollment', 250.00, 0.00, 250.00, '2026-02-20 14:41:44', '2026-02-20 14:41:44'),
	(53, 22, 28, 'monthly', 650.00, 0.00, 650.00, '2026-02-20 14:41:44', '2026-02-20 14:41:44'),
	(54, 23, 27, 'enrollment', 250.00, 0.00, 250.00, '2026-02-20 14:43:56', '2026-02-20 14:43:56'),
	(55, 23, 28, 'monthly', 650.00, 0.00, 650.00, '2026-02-20 14:43:56', '2026-02-20 14:43:56'),
	(56, 24, 27, 'enrollment', 250.00, 0.00, 250.00, '2026-02-20 14:47:03', '2026-02-20 14:47:03'),
	(57, 24, 28, 'monthly', 650.00, 0.00, 650.00, '2026-02-20 14:47:03', '2026-02-20 14:47:03'),
	(58, 25, 27, 'enrollment', 250.00, 0.00, 250.00, '2026-02-20 14:48:38', '2026-02-20 14:48:38'),
	(59, 25, 28, 'monthly', 650.00, 0.00, 650.00, '2026-02-20 14:48:38', '2026-02-20 14:48:38'),
	(60, 26, 28, 'monthly', 650.00, 0.00, 650.00, '2026-02-20 14:51:17', '2026-02-20 14:51:17'),
	(61, 26, 27, 'enrollment', 250.00, 250.00, 0.00, '2026-02-20 14:51:17', '2026-02-20 14:51:17'),
	(62, 27, 27, 'enrollment', 250.00, 0.00, 250.00, '2026-02-20 14:55:30', '2026-02-20 14:55:30'),
	(63, 27, 28, 'monthly', 650.00, 0.00, 650.00, '2026-02-20 14:55:30', '2026-02-20 14:55:30'),
	(64, 28, 27, 'enrollment', 250.00, 0.00, 250.00, '2026-02-20 14:58:30', '2026-02-20 14:58:30'),
	(65, 28, 28, 'monthly', 650.00, 0.00, 650.00, '2026-02-20 14:58:30', '2026-02-20 14:58:30'),
	(66, 29, 27, 'enrollment', 250.00, 0.00, 250.00, '2026-02-20 14:59:33', '2026-02-20 14:59:33'),
	(67, 29, 28, 'monthly', 650.00, 0.00, 650.00, '2026-02-20 14:59:33', '2026-02-20 14:59:33'),
	(68, 30, 27, 'enrollment', 250.00, 0.00, 250.00, '2026-02-20 15:04:40', '2026-02-20 15:04:40'),
	(69, 30, 28, 'monthly', 650.00, 0.00, 650.00, '2026-02-20 15:04:40', '2026-02-20 15:04:40'),
	(70, 31, 27, 'enrollment', 250.00, 0.00, 250.00, '2026-02-20 15:06:02', '2026-02-20 15:06:02'),
	(71, 31, 28, 'monthly', 650.00, 0.00, 650.00, '2026-02-20 15:06:02', '2026-02-20 15:06:02'),
	(72, 32, 27, 'enrollment', 250.00, 0.00, 250.00, '2026-02-20 15:10:23', '2026-02-20 15:10:23'),
	(73, 33, 27, 'enrollment', 250.00, 0.00, 250.00, '2026-02-20 15:12:54', '2026-02-20 15:12:54'),
	(74, 33, 28, 'monthly', 650.00, 0.00, 650.00, '2026-02-20 15:12:54', '2026-02-20 15:12:54'),
	(75, 34, 27, 'enrollment', 250.00, 0.00, 250.00, '2026-02-20 15:14:09', '2026-02-20 15:14:09'),
	(76, 34, 28, 'monthly', 650.00, 0.00, 650.00, '2026-02-20 15:14:09', '2026-02-20 15:14:09'),
	(77, 35, 44, 'enrollment', 250.00, 0.00, 250.00, '2026-02-20 18:36:02', '2026-02-20 18:36:02'),
	(78, 36, 44, 'enrollment', 250.00, 0.00, 250.00, '2026-02-20 18:38:04', '2026-02-20 18:38:04'),
	(79, 36, 45, 'monthly', 450.00, 0.00, 450.00, '2026-02-20 18:38:04', '2026-02-20 18:38:04'),
	(80, 37, 44, 'enrollment', 250.00, 0.00, 250.00, '2026-02-20 18:39:20', '2026-02-20 18:39:20'),
	(81, 37, 45, 'monthly', 450.00, 0.00, 450.00, '2026-02-20 18:39:20', '2026-02-20 18:39:20');

-- Volcando estructura para tabla db_barbershop.academy_groups
CREATE TABLE IF NOT EXISTS `academy_groups` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `branch_id` bigint unsigned NOT NULL,
  `level_id` bigint unsigned NOT NULL,
  `schedule_id` bigint unsigned NOT NULL,
  `room_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `enrollment_price` decimal(10,2) NOT NULL,
  `monthly_price` decimal(10,2) NOT NULL,
  `days_of_week` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `attendance_tolerance_minutes` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `academy_groups_branch_id_foreign` (`branch_id`),
  KEY `academy_groups_level_id_foreign` (`level_id`),
  KEY `academy_groups_schedule_id_foreign` (`schedule_id`),
  KEY `academy_groups_room_id_foreign` (`room_id`),
  KEY `academy_groups_is_active_index` (`is_active`),
  KEY `academy_groups_name_index` (`name`),
  CONSTRAINT `academy_groups_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `academy_branches` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `academy_groups_level_id_foreign` FOREIGN KEY (`level_id`) REFERENCES `academy_levels` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `academy_groups_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `academy_rooms` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `academy_groups_schedule_id_foreign` FOREIGN KEY (`schedule_id`) REFERENCES `academy_schedules` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla db_barbershop.academy_groups: ~5 rows (aproximadamente)
INSERT INTO `academy_groups` (`id`, `branch_id`, `level_id`, `schedule_id`, `room_id`, `name`, `start_date`, `end_date`, `enrollment_price`, `monthly_price`, `days_of_week`, `attendance_tolerance_minutes`, `is_active`, `created_at`, `updated_at`) VALUES
	(3, 1, 4, 1, 1, '2 MESES (9-12:30) - JESUS SALAS', '2026-01-08', '2026-03-08', 250.00, 650.00, '1,2,3,4,5', 20, 1, '2026-02-17 14:19:56', '2026-02-19 08:54:06'),
	(9, 1, 4, 1, 2, '2 MESES (9-12:30) - RONALDINO CALCINA', '2026-01-08', '2026-03-08', 250.00, 650.00, '1,2,3,4,5', 20, 1, '2026-02-19 08:53:45', '2026-02-19 08:53:58'),
	(10, 1, 4, 1, 4, '2 MESES (9-12:30) - MIDWAR SANDOVAL', '2026-02-09', '2026-04-09', 250.00, 650.00, '1,2,3,4,5', 20, 1, '2026-02-19 09:02:23', '2026-02-19 09:02:42'),
	(11, 1, 4, 1, 3, '2 MESES (9-12:30) - ALEX SANTOS', '2026-01-16', '2026-03-16', 250.00, 650.00, '1,3,2,4,5', 20, 1, '2026-02-19 09:11:13', '2026-02-19 09:11:31'),
	(12, 1, 1, 2, 2, '3 MESES (2-4) - RONALDINO CALCINA', '2026-02-09', '2026-05-09', 250.00, 450.00, '1,2,3,5,4', 20, 1, '2026-02-20 14:38:43', '2026-02-20 18:21:56');

-- Volcando estructura para tabla db_barbershop.academy_group_payment_plans
CREATE TABLE IF NOT EXISTS `academy_group_payment_plans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `group_id` bigint unsigned NOT NULL,
  `type` enum('enrollment','monthly') COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `group_payment_plan_unique` (`type`,`group_id`,`start_date`,`end_date`),
  KEY `academy_group_payment_plans_group_id_foreign` (`group_id`),
  CONSTRAINT `academy_group_payment_plans_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `academy_groups` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla db_barbershop.academy_group_payment_plans: ~16 rows (aproximadamente)
INSERT INTO `academy_group_payment_plans` (`id`, `group_id`, `type`, `start_date`, `end_date`, `amount`, `created_at`, `updated_at`) VALUES
	(18, 9, 'enrollment', '2026-01-08', '2026-03-08', 250.00, '2026-02-19 08:53:58', '2026-02-19 08:53:58'),
	(19, 9, 'monthly', '2026-01-08', '2026-02-08', 650.00, '2026-02-19 08:53:58', '2026-02-19 08:53:58'),
	(20, 9, 'monthly', '2026-02-08', '2026-03-08', 650.00, '2026-02-19 08:53:58', '2026-02-19 08:53:58'),
	(21, 3, 'enrollment', '2026-01-08', '2026-03-08', 250.00, '2026-02-19 08:54:06', '2026-02-19 08:54:06'),
	(22, 3, 'monthly', '2026-01-08', '2026-02-08', 650.00, '2026-02-19 08:54:06', '2026-02-19 08:54:06'),
	(23, 3, 'monthly', '2026-02-08', '2026-03-08', 650.00, '2026-02-19 08:54:06', '2026-02-19 08:54:06'),
	(27, 10, 'enrollment', '2026-02-09', '2026-04-09', 250.00, '2026-02-19 09:02:42', '2026-02-19 09:02:42'),
	(28, 10, 'monthly', '2026-02-09', '2026-03-09', 650.00, '2026-02-19 09:02:42', '2026-02-19 09:02:42'),
	(29, 10, 'monthly', '2026-03-09', '2026-04-09', 650.00, '2026-02-19 09:02:42', '2026-02-19 09:02:42'),
	(33, 11, 'enrollment', '2026-01-16', '2026-03-16', 250.00, '2026-02-19 09:11:31', '2026-02-19 09:11:31'),
	(34, 11, 'monthly', '2026-01-16', '2026-02-16', 650.00, '2026-02-19 09:11:31', '2026-02-19 09:11:31'),
	(35, 11, 'monthly', '2026-02-16', '2026-03-16', 650.00, '2026-02-19 09:11:31', '2026-02-19 09:11:31'),
	(44, 12, 'enrollment', '2026-02-09', '2026-05-09', 250.00, '2026-02-20 18:21:56', '2026-02-20 18:21:56'),
	(45, 12, 'monthly', '2026-02-09', '2026-03-09', 450.00, '2026-02-20 18:21:56', '2026-02-20 18:21:56'),
	(46, 12, 'monthly', '2026-03-09', '2026-04-09', 450.00, '2026-02-20 18:21:56', '2026-02-20 18:21:56'),
	(47, 12, 'monthly', '2026-04-09', '2026-05-09', 450.00, '2026-02-20 18:21:56', '2026-02-20 18:21:56');

-- Volcando estructura para tabla db_barbershop.academy_levels
CREATE TABLE IF NOT EXISTS `academy_levels` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `duration_months` int unsigned NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `academy_levels_name_unique` (`name`),
  KEY `academy_levels_is_active_index` (`is_active`),
  KEY `academy_levels_name_index` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla db_barbershop.academy_levels: ~3 rows (aproximadamente)
INSERT INTO `academy_levels` (`id`, `order`, `name`, `description`, `duration_months`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 2, 'Intermedio', '2h', 3, 1, '2026-02-16 11:40:08', '2026-02-19 08:42:16'),
	(2, 3, 'Avanzado', '2h', 3, 1, '2026-02-17 00:27:48', '2026-02-19 08:42:06'),
	(4, 1, 'Básico', '3h 30', 2, 1, '2026-02-17 02:50:24', '2026-02-19 08:46:28');

-- Volcando estructura para tabla db_barbershop.academy_rooms
CREATE TABLE IF NOT EXISTS `academy_rooms` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `branch_id` bigint unsigned NOT NULL,
  `number` int NOT NULL,
  `capacity` int NOT NULL,
  `floor` int NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `academy_rooms_branch_id_number_floor_unique` (`branch_id`,`number`,`floor`),
  KEY `academy_rooms_is_active_index` (`is_active`),
  KEY `academy_rooms_number_index` (`number`),
  KEY `academy_rooms_floor_index` (`floor`),
  CONSTRAINT `academy_rooms_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `academy_branches` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla db_barbershop.academy_rooms: ~4 rows (aproximadamente)
INSERT INTO `academy_rooms` (`id`, `branch_id`, `number`, `capacity`, `floor`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 1, 101, 20, 1, 1, '2026-02-16 11:40:08', '2026-02-16 11:40:08'),
	(2, 1, 102, 20, 1, 1, '2026-02-17 02:53:07', '2026-02-17 02:53:07'),
	(3, 1, 103, 20, 1, 1, '2026-02-17 14:12:31', '2026-02-17 14:12:31'),
	(4, 1, 104, 20, 1, 1, '2026-02-17 14:12:59', '2026-02-17 14:12:59');

-- Volcando estructura para tabla db_barbershop.academy_schedules
CREATE TABLE IF NOT EXISTS `academy_schedules` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `shift` enum('morning','afternoon','night') COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `academy_schedules_shift_start_time_end_time_unique` (`shift`,`start_time`,`end_time`),
  KEY `academy_schedules_is_active_index` (`is_active`),
  KEY `academy_schedules_shift_index` (`shift`),
  KEY `academy_schedules_start_time_index` (`start_time`),
  KEY `academy_schedules_end_time_index` (`end_time`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla db_barbershop.academy_schedules: ~6 rows (aproximadamente)
INSERT INTO `academy_schedules` (`id`, `shift`, `start_time`, `end_time`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 'morning', '09:00:00', '12:30:00', 1, '2026-02-16 11:40:08', '2026-02-17 00:17:26'),
	(2, 'afternoon', '14:00:00', '16:00:00', 1, '2026-02-17 00:17:46', '2026-02-20 18:00:37'),
	(3, 'afternoon', '16:00:00', '18:00:00', 1, '2026-02-17 00:17:58', '2026-02-20 18:00:55'),
	(4, 'morning', '18:00:00', '20:00:00', 1, '2026-02-17 00:18:10', '2026-02-20 18:03:00'),
	(5, 'morning', '09:00:00', '19:00:00', 1, '2026-02-17 02:46:56', '2026-02-17 02:46:56'),
	(7, 'afternoon', '14:00:00', '17:30:00', 1, '2026-02-20 18:00:26', '2026-02-20 18:00:26');

-- Volcando estructura para tabla db_barbershop.auth_password_resets
CREATE TABLE IF NOT EXISTS `auth_password_resets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `auth_user_id` bigint unsigned NOT NULL,
  `reset_token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expires_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `auth_password_resets_auth_user_id_index` (`auth_user_id`),
  KEY `auth_password_resets_expires_at_index` (`expires_at`),
  CONSTRAINT `auth_password_resets_auth_user_id_foreign` FOREIGN KEY (`auth_user_id`) REFERENCES `auth_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla db_barbershop.auth_password_resets: ~0 rows (aproximadamente)

-- Volcando estructura para tabla db_barbershop.auth_sessions
CREATE TABLE IF NOT EXISTS `auth_sessions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `auth_user_id` bigint unsigned NOT NULL,
  `behavior_profile_id` bigint unsigned DEFAULT NULL,
  `session_token` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `auth_sessions_auth_user_id_index` (`auth_user_id`),
  KEY `auth_sessions_behavior_profile_id_index` (`behavior_profile_id`),
  KEY `auth_sessions_expires_at_index` (`expires_at`),
  CONSTRAINT `auth_sessions_auth_user_id_foreign` FOREIGN KEY (`auth_user_id`) REFERENCES `auth_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla db_barbershop.auth_sessions: ~13 rows (aproximadamente)
INSERT INTO `auth_sessions` (`id`, `auth_user_id`, `behavior_profile_id`, `session_token`, `ip_address`, `user_agent`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
	(10, 2, 2, 'hrOAIORbBQ4qyL5V4qHThP0Lxmgap161qfUtm2DoK9PO6kzx7kcB16T7tCwTKiwY', '190.239.93.194', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '2026-02-17 13:53:05', '2026-02-24 13:53:05', '2026-02-17 13:53:05', '2026-02-17 13:53:05'),
	(11, 2, 2, 'M2N9I8cYERm3mJo8Tpmzc7gq4DqcD4Bjj8hknIOq0cHMDsFchN1dGALjTZG7bNGY', '38.226.246.154', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-17 13:58:51', '2026-02-24 13:58:51', '2026-02-17 13:58:51', '2026-02-17 13:58:51'),
	(12, 2, 2, 'pZKaNCIEqgVBs38MUPCX5pTPvz7TVtj0p0ZgTArVNO1AHHGA6eQ0hwZZEl49OkwE', '38.226.246.154', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-17 23:56:09', '2026-02-24 23:56:09', '2026-02-17 23:56:09', '2026-02-17 23:56:09'),
	(13, 2, 2, 'uZBFlPWpba5nsUDdub89a4GkmHKUFdWHtlJRo2Y5ckFZzVJnPm7xh62ekOHRmZw2', '38.250.157.46', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-18 02:45:39', '2026-02-25 02:45:39', '2026-02-18 02:45:39', '2026-02-18 02:45:39'),
	(14, 2, 2, '9qA9YYbqhEimD0wXghARQugMlH8CDBD71CWaTsslZnjxfRLrA7Uciu49oWs2BiqJ', '45.191.99.251', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-18 02:54:47', '2026-02-25 02:54:47', '2026-02-18 02:54:47', '2026-02-18 02:54:47'),
	(15, 2, 2, 'lvunCflE3libTFv6ihNYiVfKhkW28VvIzmDxl34duJ0UIuY3GNrjzCz8u5ZPFKxk', '38.226.246.223', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Mobile Safari/537.36', '2026-02-18 02:34:11', '2026-02-25 02:34:11', '2026-02-18 02:34:11', '2026-02-18 02:34:11'),
	(16, 2, 2, 'PGjtTBGJYgmHaUQsJFupawArsFuiFzZnjUTfbD0TnIEbWco9nlbRnN1FvGu5gBNH', '45.191.99.251', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-18 23:03:02', '2026-02-25 23:03:02', '2026-02-18 23:03:02', '2026-02-18 23:03:02'),
	(17, 1, 1, 'p61evfWfFC6oMvmEl76uvaiajB7ZmlRRtvz3KNiFkV1JoLoYgnnw715k3ZONv46f', '45.191.99.251', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Mobile Safari/537.36', '2026-02-18 23:06:25', '2026-02-25 23:06:25', '2026-02-18 23:06:25', '2026-02-18 23:06:25'),
	(18, 2, 2, 'z6pV1L6WpPljG6jzwDEn9StfcxqTdDRIBEalsJCjmDsbX4eGnaHuvPC0lP4KhUU3', '45.191.99.192', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-19 08:41:05', '2026-02-26 08:41:05', '2026-02-19 08:41:05', '2026-02-19 08:41:05'),
	(19, 2, 2, '3Osz3ojI7NOz671YsGRh9iE9XjfcYEDI5wvzMdr3r0cMEJSV37n46kirDkNHFIiB', '190.239.93.101', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Mobile/15E148 Safari/604.1', '2026-02-19 10:11:57', '2026-02-26 10:11:57', '2026-02-19 10:11:57', '2026-02-19 10:11:57'),
	(20, 2, 2, 'GCLk1O5HQWrfl3ryQaoKuTSiptXkrdNCZfEOrpjJl0o1yexeHFfPsGcgW0ytTDHX', '38.226.246.154', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-20 14:23:40', '2026-02-27 14:23:40', '2026-02-20 14:23:40', '2026-02-20 14:23:40'),
	(21, 2, 2, '2czkLRHIAEYqlZrX3QAibepaN7GvQgd0fiGhltWdRsnWrUaBrZD6ZKDZUjBlxbiq', '45.191.99.251', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-21 19:58:52', '2026-02-28 19:58:52', '2026-02-21 19:58:52', '2026-02-21 19:58:52'),
	(22, 2, 2, '7C9rXQKYB7dhdAkpwBHSPKPYtw851DwU0tv3CE6tGfbcvW3RMFf21FO88dTCHvrp', '38.250.157.38', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-22 23:03:28', '2026-03-01 23:03:28', '2026-02-22 23:03:28', '2026-02-22 23:03:28'),
	(23, 2, 2, 'st8ND7mYGwMLGvmE9hJUEjSQUrS0fxfWfv2DTylqhvWvgIZKHwjUP6qYVYqCJ6uR', '45.191.99.251', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-23 01:18:23', '2026-03-02 01:18:23', '2026-02-23 01:18:23', '2026-02-23 01:18:23');

-- Volcando estructura para tabla db_barbershop.auth_users
CREATE TABLE IF NOT EXISTS `auth_users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `last_sign_in_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `auth_users_username_unique` (`username`),
  UNIQUE KEY `auth_users_email_unique` (`email`),
  KEY `auth_users_username_index` (`username`),
  KEY `auth_users_email_index` (`email`),
  KEY `auth_users_is_active_index` (`is_active`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla db_barbershop.auth_users: ~39 rows (aproximadamente)
INSERT INTO `auth_users` (`id`, `username`, `email`, `password`, `is_active`, `email_verified_at`, `last_sign_in_at`, `created_at`, `updated_at`) VALUES
	(1, 'linox', 'super@admin.com', '$2y$12$eIgLsPURLrE9XbYLsP3KZ.2.7sGLXE17ZRVM04/VTT4gDZTFK2XuK', 1, '2026-02-16 11:40:08', '2026-02-18 23:06:25', '2026-02-16 11:40:08', '2026-02-18 23:06:25'),
	(2, '00000001', 'admin@test.com', '$2y$12$vCODGEASNMuJI.GlnRkpfeBmdTl3DH3qCAbpqLCxYuT8O.vRXelDi', 1, NULL, '2026-02-23 01:18:23', '2026-02-16 11:41:53', '2026-02-23 01:18:23'),
	(3, '76063570', 'carlos@gmail.com', '$2y$12$Cbqxd.jW6qd3rOEfYwB4R.lEafeP1NplOKt08/6nu.ZAZx1.U2nHa', 0, NULL, NULL, '2026-02-17 03:11:41', '2026-02-17 03:14:38'),
	(4, '70063570', 'STIP@GMAIL.COM', '$2y$12$I0a2epusoMCU5tNBDLipCOyTGcsil9efPYgn1emXxGEzAiwa50hZq', 0, NULL, NULL, '2026-02-18 00:01:21', '2026-02-18 00:01:21'),
	(5, '12312323', 'linox@gmail.com', '$2y$12$r.1AkaWTyvhZmFBlo0ye5O0uQFmfY/AXyu8lXp2CoUJ22dJ4IGZ8S', 0, NULL, NULL, '2026-02-18 02:55:33', '2026-02-18 02:55:33'),
	(6, '76832299', 'C@GMAIL.COM', '$2y$12$O7vtgFa/FERbAnN.UQJjn.3GbjDH6r98L/f/r5G/KrZspbK.Qb.cK', 0, NULL, NULL, '2026-02-19 09:20:41', '2026-02-19 09:20:41'),
	(7, '61893646', 'C1@GMAIL.COM', '$2y$12$2v32e.Lvyk8/HFdBdZtzTeomN1Xohgx2nI5CY.iIap2Wj1EfznQdS', 0, NULL, NULL, '2026-02-19 09:23:35', '2026-02-19 09:23:35'),
	(8, '62805432', 'C2@GMAIL.COM', '$2y$12$.CIypFjolrQrOKxHvTBkLezZBa.ZSoQ8Maky.Nps5Mkk2lYQ/7D9G', 0, NULL, NULL, '2026-02-19 09:24:45', '2026-02-19 09:24:45'),
	(9, '73820089', 'C3@GMAIL.COM', '$2y$12$PTBMqaIL7GF4lqUQIt4mpewuMBrlY4jcf5dNyCki8DxZIdxGUDxQW', 0, NULL, NULL, '2026-02-19 09:25:53', '2026-02-19 09:25:53'),
	(10, '61939362', 'C4@GMAIL.COM', '$2y$12$mDsN4.zKdDnJwdnVrtrE8O174zK62WY0HM0.lQZSfDP4qjTmxyInO', 0, NULL, NULL, '2026-02-19 09:26:58', '2026-02-19 09:26:58'),
	(11, '61783700', 'C5@GMAIL.COM', '$2y$12$IK12WY7QrAD4LI7R50btA.kBcKI/4jbGXrA38myYWWYvtnabJsIE.', 0, NULL, NULL, '2026-02-19 09:27:53', '2026-02-19 09:27:53'),
	(12, '63017506', 'C6@GMAIL.COM', '$2y$12$2qeAa.EYewO/3Q1hXinGseLlucni.Z5FS9hdcaQ2TdLZvKlUmFUyi', 0, NULL, NULL, '2026-02-19 09:28:58', '2026-02-19 09:28:58'),
	(13, '48022417', 'C7@GMAIL.COM', '$2y$12$zrdfWdgPfrPkcah8/F5yTOmVze/rPh7tDwFU4MPpNDfAIl2lzoNTi', 0, NULL, NULL, '2026-02-19 09:30:01', '2026-02-19 09:30:01'),
	(14, '61892341', 'C8@GMAIL.COM', '$2y$12$SELnPVunL3yDfbkd/onxruuPtt5m4gHfrlZJ0BUIam81mdyW5IPyW', 0, NULL, NULL, '2026-02-19 09:31:13', '2026-02-19 09:31:13'),
	(15, '61321205', 'C9@GMAIL.COM', '$2y$12$A4y8VYuvjcFmA4MUOdHC5eyBPVoJu/.yZZVNCweBYxIwmLQg3L2M.', 0, NULL, NULL, '2026-02-19 09:32:07', '2026-02-19 09:32:07'),
	(16, '62528271', '10@GMAIL.COM', '$2y$12$Ts7pMU5CWRrc.kyFgdDuhexJ6LIamvlPvFk.c0t8iVKglbVyO4d2C', 0, NULL, NULL, '2026-02-19 09:33:23', '2026-02-19 09:33:23'),
	(17, '62480235', '11@GMAIL.COM', '$2y$12$t/U5jez0qq38OqADNYHkZORJWjSxhnI/4g.gnnTvVBM7974jUy21a', 0, NULL, NULL, '2026-02-19 09:34:20', '2026-02-19 09:34:20'),
	(18, '45910327', '12@GMAIL.COM', '$2y$12$hnmq2EGKWmjg2Y8GMDNRgezqhRZGvnjOhpOkRtRI3YFFXDZkR7ODK', 0, NULL, NULL, '2026-02-19 09:41:41', '2026-02-19 09:41:41'),
	(19, '12312301', '13@GMAIL.COM', '$2y$12$RMNPL6Ud0Iesqw2vNgqxiOjCrvnxxI40kO/WD1LAgitQoilW4jkAS', 0, NULL, NULL, '2026-02-19 09:48:29', '2026-02-19 09:48:29'),
	(20, '12312302', '14@GMAIL.COM', '$2y$12$LObMuDJrHfyVTA4XVUqlxu59Mnj9.wOPCv.IfN8c9nT20s8bTOT0e', 0, NULL, NULL, '2026-02-19 09:49:28', '2026-02-19 09:51:17'),
	(21, '12312303', '16@GMAIL.COM', '$2y$12$R/DPpa3tU8H8eu8xPMPrdea/QVlu6nN7bSrPlWjCgiEyZ/rZey/2W', 0, NULL, NULL, '2026-02-19 10:04:24', '2026-02-19 10:04:24'),
	(22, '72233424', 'M1@GMAIL.COM', '$2y$12$vvsklgEa6DpTHCJd6yCamepJ9G25RAcu7jh/VW9CCORpmUEDA/CE6', 0, NULL, NULL, '2026-02-20 14:31:14', '2026-02-20 14:31:14'),
	(23, '70838415', 'M2@GMAIL.COM', '$2y$12$axVeDLho2Vt8aSXCFOb1a.NETRdh9sAUuBg/cLM7ognkuG.sWpi/K', 0, NULL, NULL, '2026-02-20 14:32:19', '2026-02-20 14:32:19'),
	(24, '74310292', 'M3@GMAIL.COM', '$2y$12$aJnBehBnv6L4Rq1NP43wTeqUe0yehMH3v1.jQhU0ePL0hPp2DSqt.', 0, NULL, NULL, '2026-02-20 14:43:15', '2026-02-20 14:43:29'),
	(25, '61091109', 'M4@GMAIL.COM', '$2y$12$ZsAZfvUUMeR13AKdnbSdGOgnwrNYOnCHjYZhoV50RQch0.NHquIBG', 0, NULL, NULL, '2026-02-20 14:46:50', '2026-02-20 14:46:50'),
	(26, '73651532', 'M5@GMAIL.COM', '$2y$12$7kjOTfjkbmogUZtxB.nAZ.TmKkcc1vQAvhgMDSP0FGRx3gijkuNZW', 0, NULL, NULL, '2026-02-20 14:48:24', '2026-02-20 14:52:50'),
	(27, '73541260', 'M6@GMAIL.COM', '$2y$12$.l4qTeAHkaMp07GP5K6CnumJRxbUsWpQckm5el6E89L41eZKd3nEG', 0, NULL, NULL, '2026-02-20 14:50:23', '2026-02-20 14:50:23'),
	(28, '61000410', 'M7@GMIL.COM', '$2y$12$BqzIiqh/JEQIHVDLB6yYjuzpDT3mwAaN66dryyaEQAs5JEbAKeUPC', 0, NULL, NULL, '2026-02-20 14:55:08', '2026-02-20 14:55:08'),
	(29, '71654064', 'M8@GMAI.COM', '$2y$12$Gho/IAz5Kmxv24Z0uqwE8OAV4otQjNVVBGW7Wwh4z/rqbYxB/ozwu', 0, NULL, NULL, '2026-02-20 14:58:16', '2026-02-20 14:58:16'),
	(30, '60665191', 'M9@GMAIL.COM', '$2y$12$sdx6oSSM02db8WPeXqARZ.MZB8zG0f9I5/5nrxgg2LsIC1qjPbI9a', 0, NULL, NULL, '2026-02-20 14:59:12', '2026-02-20 14:59:12'),
	(31, '73171929', 'M10@GMAIL.COM', '$2y$12$EXxMQkC2gXslFLTwYGAL5OnOpzFWX52Le0/LXfz/XVm7xZl6vpLsG', 0, NULL, NULL, '2026-02-20 15:03:25', '2026-02-20 15:03:25'),
	(32, '62824124', 'M11@GMAIL.COM', '$2y$12$Z/bOuixTXvCh/0EGGd/XZeB3kcsDYDcRKE0C1gDI8bYPXMJpcKb9u', 0, NULL, NULL, '2026-02-20 15:05:54', '2026-02-20 15:05:54'),
	(33, '60417095', 'M12@GMAIL.COM', '$2y$12$/aQhs8j5KchiBW9deTo8ZuZtKf1GzYpthNBdyem1MTZazo/AS70D2', 0, NULL, NULL, '2026-02-20 15:10:05', '2026-02-20 15:10:05'),
	(34, '60597092', 'M13@GMAIL.COM', '$2y$12$fZ1EBD1pNYnMAbPYv9Dv9OXsiRV5a9lH8fkdXEC4nFg03NYpt.j1W', 0, NULL, NULL, '2026-02-20 15:11:30', '2026-02-20 15:11:30'),
	(35, '75899252', 'M14@GMAIL.COM', '$2y$12$fVmdT2yHdvCn6iOF9HgTZ.dAh4Q.O3yfyc1E4otFrmShNJDEgIyl.', 0, NULL, NULL, '2026-02-20 15:14:01', '2026-02-20 15:14:01'),
	(36, '62486607', 'ALFIL.CONTA5@GMAIL.COM', '$2y$12$bpjwoCBlf.3rVa3nFVRpb.E9TuHG4TLjy0L8DreM1SHPGBZL1khwW', 0, NULL, NULL, '2026-02-20 18:35:01', '2026-02-20 18:35:01'),
	(37, '60908831', 'R1@GAMIL.COM', '$2y$12$D/dKIfLwgXnqV0ZcsG1WceBbi5wEZimpkU7A5GFwtSxvzvx3Toqcm', 0, NULL, NULL, '2026-02-20 18:37:51', '2026-02-20 18:37:51'),
	(38, '61906283', 'R2@GMAIL.COM', '$2y$12$snx.x2t7CCwmWUbsZf6Pvupz6PUdktVNXbGCnGqu7pXpLI1hZiyIG', 0, NULL, NULL, '2026-02-20 18:38:59', '2026-02-20 18:38:59'),
	(39, '76507044', 'R3@GMAIL.COM', '$2y$12$0zo/rbwgxQTPNck15lqyVOGORlczDgKDBAv6DxcYqh.2RLGI8H8Pq', 0, NULL, NULL, '2026-02-20 18:40:25', '2026-02-20 18:40:25');

-- Volcando estructura para tabla db_barbershop.behavior_permissions
CREATE TABLE IF NOT EXISTS `behavior_permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('module','menu','view','action','feature') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'action',
  `parent_id` bigint unsigned DEFAULT NULL,
  `level` enum('0','1','2','3') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `behavior_permissions_name_unique` (`name`),
  KEY `behavior_permissions_name_index` (`name`),
  KEY `behavior_permissions_type_index` (`type`),
  KEY `behavior_permissions_parent_id_index` (`parent_id`),
  KEY `behavior_permissions_level_index` (`level`),
  CONSTRAINT `behavior_permissions_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `behavior_permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla db_barbershop.behavior_permissions: ~0 rows (aproximadamente)

-- Volcando estructura para tabla db_barbershop.behavior_profiles
CREATE TABLE IF NOT EXISTS `behavior_profiles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `auth_user_id` bigint unsigned NOT NULL,
  `profileable_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `profileable_id` bigint unsigned NOT NULL,
  `behavior_role_id` bigint unsigned NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `profile_morph_unique` (`profileable_type`,`profileable_id`),
  KEY `behavior_profiles_auth_user_id_index` (`auth_user_id`),
  KEY `behavior_profiles_profileable_type_profileable_id_index` (`profileable_type`,`profileable_id`),
  KEY `behavior_profiles_behavior_role_id_index` (`behavior_role_id`),
  KEY `behavior_profiles_is_active_index` (`is_active`),
  CONSTRAINT `behavior_profiles_auth_user_id_foreign` FOREIGN KEY (`auth_user_id`) REFERENCES `auth_users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `behavior_profiles_behavior_role_id_foreign` FOREIGN KEY (`behavior_role_id`) REFERENCES `behavior_roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla db_barbershop.behavior_profiles: ~36 rows (aproximadamente)
INSERT INTO `behavior_profiles` (`id`, `auth_user_id`, `profileable_type`, `profileable_id`, `behavior_role_id`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 1, 'profile_admins', 1, 1, 1, '2026-02-16 11:40:08', '2026-02-16 11:40:08'),
	(2, 2, 'profile_admins', 2, 3, 1, '2026-02-16 11:41:53', '2026-02-16 11:41:53'),
	(6, 6, 'profile_students', 6, 2, 1, '2026-02-19 09:20:41', '2026-02-19 09:20:41'),
	(7, 7, 'profile_students', 7, 2, 1, '2026-02-19 09:23:35', '2026-02-19 09:23:35'),
	(8, 8, 'profile_students', 8, 2, 1, '2026-02-19 09:24:45', '2026-02-19 09:24:45'),
	(9, 9, 'profile_students', 9, 2, 1, '2026-02-19 09:25:53', '2026-02-19 09:25:53'),
	(10, 10, 'profile_students', 10, 2, 1, '2026-02-19 09:26:58', '2026-02-19 09:26:58'),
	(11, 11, 'profile_students', 11, 2, 1, '2026-02-19 09:27:53', '2026-02-19 09:27:53'),
	(12, 12, 'profile_students', 12, 2, 1, '2026-02-19 09:28:58', '2026-02-19 09:28:58'),
	(13, 13, 'profile_students', 13, 2, 1, '2026-02-19 09:30:01', '2026-02-19 09:30:01'),
	(14, 14, 'profile_students', 14, 2, 1, '2026-02-19 09:31:13', '2026-02-19 09:31:13'),
	(15, 15, 'profile_students', 15, 2, 1, '2026-02-19 09:32:07', '2026-02-19 09:32:07'),
	(16, 16, 'profile_students', 16, 2, 1, '2026-02-19 09:33:23', '2026-02-19 09:33:23'),
	(17, 17, 'profile_students', 17, 2, 1, '2026-02-19 09:34:20', '2026-02-19 09:34:20'),
	(18, 18, 'profile_students', 18, 2, 1, '2026-02-19 09:41:41', '2026-02-19 09:41:41'),
	(19, 19, 'profile_students', 19, 2, 1, '2026-02-19 09:48:29', '2026-02-19 09:48:29'),
	(20, 20, 'profile_students', 20, 2, 1, '2026-02-19 09:49:28', '2026-02-19 09:49:28'),
	(21, 21, 'profile_students', 21, 2, 1, '2026-02-19 10:04:24', '2026-02-19 10:04:24'),
	(22, 22, 'profile_students', 22, 2, 1, '2026-02-20 14:31:14', '2026-02-20 14:31:14'),
	(23, 23, 'profile_students', 23, 2, 1, '2026-02-20 14:32:19', '2026-02-20 14:32:19'),
	(24, 24, 'profile_students', 24, 2, 1, '2026-02-20 14:43:15', '2026-02-20 14:43:15'),
	(25, 25, 'profile_students', 25, 2, 1, '2026-02-20 14:46:50', '2026-02-20 14:46:50'),
	(26, 26, 'profile_students', 26, 2, 1, '2026-02-20 14:48:24', '2026-02-20 14:48:24'),
	(27, 27, 'profile_students', 27, 2, 1, '2026-02-20 14:50:23', '2026-02-20 14:50:23'),
	(28, 28, 'profile_students', 28, 2, 1, '2026-02-20 14:55:08', '2026-02-20 14:55:08'),
	(29, 29, 'profile_students', 29, 2, 1, '2026-02-20 14:58:16', '2026-02-20 14:58:16'),
	(30, 30, 'profile_students', 30, 2, 1, '2026-02-20 14:59:12', '2026-02-20 14:59:12'),
	(31, 31, 'profile_students', 31, 2, 1, '2026-02-20 15:03:25', '2026-02-20 15:03:25'),
	(32, 32, 'profile_students', 32, 2, 1, '2026-02-20 15:05:54', '2026-02-20 15:05:54'),
	(33, 33, 'profile_students', 33, 2, 1, '2026-02-20 15:10:05', '2026-02-20 15:10:05'),
	(34, 34, 'profile_students', 34, 2, 1, '2026-02-20 15:11:30', '2026-02-20 15:11:30'),
	(35, 35, 'profile_students', 35, 2, 1, '2026-02-20 15:14:01', '2026-02-20 15:14:01'),
	(36, 36, 'profile_students', 36, 2, 1, '2026-02-20 18:35:01', '2026-02-20 18:35:01'),
	(37, 37, 'profile_students', 37, 2, 1, '2026-02-20 18:37:51', '2026-02-20 18:37:51'),
	(38, 38, 'profile_students', 38, 2, 1, '2026-02-20 18:38:59', '2026-02-20 18:38:59'),
	(39, 39, 'profile_students', 39, 2, 1, '2026-02-20 18:40:25', '2026-02-20 18:40:25');

-- Volcando estructura para tabla db_barbershop.behavior_roles
CREATE TABLE IF NOT EXISTS `behavior_roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `redirect_to` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `level` enum('0','1','2','3') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `behavior_roles_name_unique` (`name`),
  UNIQUE KEY `behavior_roles_display_name_unique` (`display_name`),
  KEY `behavior_roles_name_index` (`name`),
  KEY `behavior_roles_level_index` (`level`),
  KEY `behavior_roles_is_active_index` (`is_active`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla db_barbershop.behavior_roles: ~3 rows (aproximadamente)
INSERT INTO `behavior_roles` (`id`, `name`, `display_name`, `redirect_to`, `level`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 'super_admin', 'Super Admin', '/admin', '0', 1, '2026-02-16 11:40:07', '2026-02-16 11:40:07'),
	(2, 'estudiante', 'Estudiante', '/admin', '3', 1, '2026-02-16 11:40:08', '2026-02-16 11:40:08'),
	(3, 'administrador', 'Administrador', '/admin', '1', 1, '2026-02-16 11:41:10', '2026-02-16 11:41:10');

-- Volcando estructura para tabla db_barbershop.behavior_role_permissions
CREATE TABLE IF NOT EXISTS `behavior_role_permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `behavior_role_id` bigint unsigned NOT NULL,
  `behavior_permission_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `role_perm_unique` (`behavior_role_id`,`behavior_permission_id`),
  KEY `behavior_role_permissions_behavior_role_id_index` (`behavior_role_id`),
  KEY `behavior_role_permissions_behavior_permission_id_index` (`behavior_permission_id`),
  CONSTRAINT `behavior_role_permissions_behavior_permission_id_foreign` FOREIGN KEY (`behavior_permission_id`) REFERENCES `behavior_permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `behavior_role_permissions_behavior_role_id_foreign` FOREIGN KEY (`behavior_role_id`) REFERENCES `behavior_roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla db_barbershop.behavior_role_permissions: ~0 rows (aproximadamente)

-- Volcando estructura para tabla db_barbershop.core_cities
CREATE TABLE IF NOT EXISTS `core_cities` (
  `code` char(6) COLLATE utf8mb4_unicode_ci NOT NULL,
  `department` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `province` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `district` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` char(2) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PE',
  PRIMARY KEY (`code`),
  KEY `core_cities_department_index` (`department`),
  KEY `core_cities_province_index` (`province`),
  KEY `core_cities_district_index` (`district`),
  KEY `core_cities_country_index` (`country`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla db_barbershop.core_cities: ~0 rows (aproximadamente)

-- Volcando estructura para tabla db_barbershop.core_countries
CREATE TABLE IF NOT EXISTS `core_countries` (
  `code` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`code`),
  UNIQUE KEY `core_countries_code_unique` (`code`),
  UNIQUE KEY `core_countries_name_unique` (`name`),
  KEY `core_countries_name_index` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla db_barbershop.core_countries: ~1 rows (aproximadamente)
INSERT INTO `core_countries` (`code`, `name`) VALUES
	('PE', 'Perú');

-- Volcando estructura para tabla db_barbershop.core_document_types
CREATE TABLE IF NOT EXISTS `core_document_types` (
  `code` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`code`),
  UNIQUE KEY `core_document_types_code_unique` (`code`),
  UNIQUE KEY `core_document_types_name_unique` (`name`),
  KEY `core_document_types_name_index` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla db_barbershop.core_document_types: ~5 rows (aproximadamente)
INSERT INTO `core_document_types` (`code`, `name`) VALUES
	('0', 'Otros'),
	('1', 'DNI'),
	('4', 'Carnet de Extranjería'),
	('6', 'RUC'),
	('7', 'Pasaporte');

-- Volcando estructura para tabla db_barbershop.core_genders
CREATE TABLE IF NOT EXISTS `core_genders` (
  `code` char(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`code`),
  UNIQUE KEY `core_genders_code_unique` (`code`),
  UNIQUE KEY `core_genders_name_unique` (`name`),
  KEY `core_genders_name_index` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla db_barbershop.core_genders: ~4 rows (aproximadamente)
INSERT INTO `core_genders` (`code`, `name`) VALUES
	('0', 'No conocido'),
	('1', 'Masculino'),
	('2', 'Femenino'),
	('9', 'No aplicable');

-- Volcando estructura para tabla db_barbershop.core_persons
CREATE TABLE IF NOT EXISTS `core_persons` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `document_type` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `document_number` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `paternal_surname` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `maternal_surname` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_birth` date DEFAULT NULL,
  `phone` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` char(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` char(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `core_persons_document_type_document_number_unique` (`document_type`,`document_number`),
  UNIQUE KEY `core_persons_phone_unique` (`phone`),
  UNIQUE KEY `core_persons_email_unique` (`email`),
  KEY `core_persons_country_foreign` (`country`),
  KEY `core_persons_city_foreign` (`city`),
  KEY `core_persons_name_index` (`name`),
  KEY `core_persons_paternal_surname_index` (`paternal_surname`),
  KEY `core_persons_maternal_surname_index` (`maternal_surname`),
  KEY `core_persons_gender_index` (`gender`),
  KEY `core_persons_phone_index` (`phone`),
  KEY `core_persons_email_index` (`email`),
  KEY `core_persons_name_paternal_surname_maternal_surname_index` (`name`,`paternal_surname`,`maternal_surname`),
  CONSTRAINT `core_persons_city_foreign` FOREIGN KEY (`city`) REFERENCES `core_cities` (`code`) ON DELETE SET NULL,
  CONSTRAINT `core_persons_country_foreign` FOREIGN KEY (`country`) REFERENCES `core_countries` (`code`) ON DELETE SET NULL,
  CONSTRAINT `core_persons_document_type_foreign` FOREIGN KEY (`document_type`) REFERENCES `core_document_types` (`code`) ON DELETE RESTRICT,
  CONSTRAINT `core_persons_gender_foreign` FOREIGN KEY (`gender`) REFERENCES `core_genders` (`code`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla db_barbershop.core_persons: ~36 rows (aproximadamente)
INSERT INTO `core_persons` (`id`, `document_type`, `document_number`, `name`, `paternal_surname`, `maternal_surname`, `date_birth`, `phone`, `email`, `gender`, `address`, `city`, `country`, `created_at`, `updated_at`) VALUES
	(1, '1', '00000000', 'Lino', 'Puma', NULL, NULL, NULL, 'super@admin.com', NULL, NULL, NULL, 'PE', '2026-02-16 11:40:07', '2026-02-16 11:40:07'),
	(2, '1', '00000001', 'Admin', 'Admin', 'Test', NULL, '900000001', 'admin@test.com', NULL, NULL, NULL, NULL, '2026-02-16 11:41:52', '2026-02-16 11:41:52'),
	(6, '1', '76832299', 'BRITNEY LISBET', 'CANSAYA', 'GEMIO', NULL, '961258362', 'C@GMAIL.COM', NULL, NULL, NULL, NULL, '2026-02-19 09:20:41', '2026-02-19 09:20:41'),
	(7, '1', '61893646', 'RENZO PAUL', 'GOMEZ', 'TAIPE', NULL, '961264940', 'C1@GMAIL.COM', NULL, NULL, NULL, NULL, '2026-02-19 09:23:34', '2026-02-19 09:23:34'),
	(8, '1', '62805432', 'CHRISTIAN GUILARDINO', 'MAMANI', 'CONDORI', NULL, '922127589', 'C2@GMAIL.COM', NULL, NULL, NULL, NULL, '2026-02-19 09:24:45', '2026-02-19 09:24:45'),
	(9, '1', '73820089', 'FERMINA FLORA', 'CALCINA', 'MAMANI', NULL, '957772458', 'C3@GMAIL.COM', NULL, NULL, NULL, NULL, '2026-02-19 09:25:53', '2026-02-19 09:25:53'),
	(10, '1', '61939362', 'ANDREW ALEXANDER', 'PAREDES', 'POMA', NULL, '61939362', 'C4@GMAIL.COM', NULL, NULL, NULL, NULL, '2026-02-19 09:26:58', '2026-02-19 09:26:58'),
	(11, '1', '61783700', 'YIMER RUSSEL', 'AHUMADA', 'QUISPE', NULL, '965194393', 'C5@GMAIL.COM', NULL, NULL, NULL, NULL, '2026-02-19 09:27:52', '2026-02-19 09:27:52'),
	(12, '1', '63017506', 'KEVIN JUNIOR', 'LUQUE', 'CHAIÑA', NULL, '900466355', 'C6@GMAIL.COM', NULL, NULL, NULL, NULL, '2026-02-19 09:28:58', '2026-02-19 09:28:58'),
	(13, '1', '48022417', 'ROXANA', 'CUNO', 'HUANCA', NULL, '998424104', 'C7@GMAIL.COM', NULL, NULL, NULL, NULL, '2026-02-19 09:30:01', '2026-02-19 09:30:01'),
	(14, '1', '61892341', 'PRINCE ARTURO GABRIEL', 'GOMEZ', 'FLORES', NULL, '921666999', 'C8@GMAIL.COM', NULL, NULL, NULL, NULL, '2026-02-19 09:31:13', '2026-02-19 09:31:13'),
	(15, '1', '61321205', 'MARCO RUBIÑO', 'QUISPE', 'SUCAPUCA', NULL, '941735090', 'C9@GMAIL.COM', NULL, NULL, NULL, NULL, '2026-02-19 09:32:07', '2026-02-19 09:32:07'),
	(16, '1', '62528271', 'DIEGO LEONARDO', 'COYA', 'COAQUIRA', NULL, '973128544', '10@GMAIL.COM', NULL, NULL, NULL, NULL, '2026-02-19 09:33:23', '2026-02-19 09:33:23'),
	(17, '1', '62480235', 'JEAN FRANK RONALDHO', 'CASTILLO', 'ROJAS', NULL, '993138481', '11@GMAIL.COM', NULL, NULL, NULL, NULL, '2026-02-19 09:34:19', '2026-02-19 09:34:19'),
	(18, '1', '45910327', 'BERTHA', 'CALSINA', 'CCASO', NULL, '929120254', '12@GMAIL.COM', NULL, NULL, NULL, NULL, '2026-02-19 09:41:41', '2026-02-19 09:41:41'),
	(19, '1', '12312301', 'ARISON HAIR', 'HUAHUAMULLO', 'SANCHEZ', NULL, '925818582', '13@GMAIL.COM', NULL, NULL, NULL, NULL, '2026-02-19 09:48:28', '2026-02-19 09:48:28'),
	(20, '1', '12312302', 'MARIA DEL VALLE', 'MONTILLA', 'ALBORNOZ', NULL, '915075991', '14@GMAIL.COM', NULL, NULL, NULL, NULL, '2026-02-19 09:49:28', '2026-02-19 09:51:16'),
	(21, '1', '12312303', 'GUSTAVO MAXIMO', 'APAZA', 'DELGADO', NULL, '910523360', '16@GMAIL.COM', NULL, NULL, NULL, NULL, '2026-02-19 10:04:23', '2026-02-19 10:04:23'),
	(22, '1', '72233424', 'ROBERTO CARLOS', 'ROCHA', 'BUTRON', NULL, '980511864', 'M1@GMAIL.COM', NULL, NULL, NULL, NULL, '2026-02-20 14:31:13', '2026-02-20 14:31:13'),
	(23, '1', '70838415', 'SUSY EDITH', 'CANTUTA', 'QUISPE', NULL, '929507195', 'M2@GMAIL.COM', NULL, NULL, NULL, NULL, '2026-02-20 14:32:19', '2026-02-20 14:32:19'),
	(24, '1', '74310292', 'JHON BRAYAN', 'CRUZ', 'QUISPE', NULL, '947736178', 'M3@GMAIL.COM', NULL, NULL, NULL, NULL, '2026-02-20 14:43:15', '2026-02-20 14:43:28'),
	(25, '1', '61091109', 'JENNIFER ANAHIS', 'CHOQUEMAQUE', 'CALAPUJA', NULL, '969567586', 'M4@GMAIL.COM', NULL, NULL, NULL, NULL, '2026-02-20 14:46:50', '2026-02-20 14:46:50'),
	(26, '1', '73651532', 'ROY ALVARO', 'PALOMINO', 'QUISPE', NULL, '935269221', 'M5@GMAIL.COM', NULL, NULL, NULL, NULL, '2026-02-20 14:48:24', '2026-02-20 14:52:50'),
	(27, '1', '73541260', 'DELMA MERY', 'CCANCAPA', 'PIZARRO', NULL, '921614240', 'M6@GMAIL.COM', NULL, NULL, NULL, NULL, '2026-02-20 14:50:22', '2026-02-20 14:50:22'),
	(28, '1', '61000410', 'FERNANDO SERGIO', 'MAMANI', 'CONDORI', NULL, '953886034', 'M7@GMIL.COM', NULL, NULL, NULL, NULL, '2026-02-20 14:55:07', '2026-02-20 14:55:07'),
	(29, '1', '71654064', 'MARLETH MILAGROS', 'CHAMBI', 'HUAMAN', NULL, '910333619', 'M8@GMAI.COM', NULL, NULL, NULL, NULL, '2026-02-20 14:58:16', '2026-02-20 14:58:16'),
	(30, '1', '60665191', 'KEVIN', 'UCHIRI', 'RAMOS', NULL, '933420822', 'M9@GMAIL.COM', NULL, NULL, NULL, NULL, '2026-02-20 14:59:12', '2026-02-20 14:59:12'),
	(31, '1', '73171929', 'FRANK ANTHONY', 'TICONA', 'APAZA', NULL, '991733788', 'M10@GMAIL.COM', NULL, NULL, NULL, NULL, '2026-02-20 15:03:25', '2026-02-20 15:03:25'),
	(32, '1', '62824124', 'KENYI LEONEL', 'TICONA', 'APAZA', NULL, '991292613', 'M11@GMAIL.COM', NULL, NULL, NULL, NULL, '2026-02-20 15:05:53', '2026-02-20 15:05:53'),
	(33, '1', '60417095', 'NOLBERTO RICHARD', 'RAMOS', 'MOROCCO', NULL, '917111765', 'M12@GMAIL.COM', NULL, NULL, NULL, NULL, '2026-02-20 15:10:05', '2026-02-20 15:10:05'),
	(34, '1', '60597092', 'RAUL EDUARDO', 'HUAMAN', 'SURCO', NULL, '942361760', 'M13@GMAIL.COM', NULL, NULL, NULL, NULL, '2026-02-20 15:11:30', '2026-02-20 15:11:30'),
	(35, '1', '75899252', 'YONATAN HEBER', 'QUISPE', 'MAMANI', NULL, '921660409', 'M14@GMAIL.COM', NULL, NULL, NULL, NULL, '2026-02-20 15:14:01', '2026-02-20 15:14:01'),
	(36, '1', '62486607', 'GARCIA YANA LUIS ALBERTO', 'GARCIA', 'YANA', NULL, '989976297', 'ALFIL.CONTA5@GMAIL.COM', NULL, NULL, NULL, NULL, '2026-02-20 18:35:01', '2026-02-20 18:35:01'),
	(37, '1', '60908831', 'ROGER FRANCES', 'ADCO', 'MACEDO', NULL, '957107771', 'R1@GAMIL.COM', NULL, NULL, NULL, NULL, '2026-02-20 18:37:51', '2026-02-20 18:37:51'),
	(38, '1', '61906283', 'ALVARO GEFFEN', 'CALATAYUD', 'MONRROY', NULL, '989689563', 'R2@GMAIL.COM', NULL, NULL, NULL, NULL, '2026-02-20 18:38:58', '2026-02-20 18:38:58'),
	(39, '1', '76507044', 'LUIS YAMPIER', 'CONDORI', 'FLORES', NULL, '917519654', 'R3@GMAIL.COM', NULL, NULL, NULL, NULL, '2026-02-20 18:40:25', '2026-02-20 18:40:25');

-- Volcando estructura para tabla db_barbershop.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla db_barbershop.migrations: ~5 rows (aproximadamente)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2026_01_24_233406_create_core_tables', 1),
	(2, '2026_01_25_022447_create_security_tables', 1),
	(3, '2026_01_25_022457_create_profile_tables', 1),
	(4, '2026_01_31_203836_create_personal_access_tokens_table', 1),
	(5, '2026_02_11_204823_create_academy_tables', 1);

-- Volcando estructura para tabla db_barbershop.personal_access_tokens
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  KEY `personal_access_tokens_expires_at_index` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla db_barbershop.personal_access_tokens: ~0 rows (aproximadamente)

-- Volcando estructura para tabla db_barbershop.profile_admins
CREATE TABLE IF NOT EXISTS `profile_admins` (
  `core_person_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`core_person_id`),
  CONSTRAINT `profile_admins_core_person_id_foreign` FOREIGN KEY (`core_person_id`) REFERENCES `core_persons` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla db_barbershop.profile_admins: ~2 rows (aproximadamente)
INSERT INTO `profile_admins` (`core_person_id`, `created_at`, `updated_at`) VALUES
	(1, '2026-02-16 11:40:08', '2026-02-16 11:40:08'),
	(2, '2026-02-16 11:41:53', '2026-02-16 11:41:53');

-- Volcando estructura para tabla db_barbershop.profile_students
CREATE TABLE IF NOT EXISTS `profile_students` (
  `core_person_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`core_person_id`),
  CONSTRAINT `profile_students_core_person_id_foreign` FOREIGN KEY (`core_person_id`) REFERENCES `core_persons` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla db_barbershop.profile_students: ~34 rows (aproximadamente)
INSERT INTO `profile_students` (`core_person_id`, `created_at`, `updated_at`) VALUES
	(6, '2026-02-19 09:20:41', '2026-02-19 09:20:41'),
	(7, '2026-02-19 09:23:35', '2026-02-19 09:23:35'),
	(8, '2026-02-19 09:24:45', '2026-02-19 09:24:45'),
	(9, '2026-02-19 09:25:53', '2026-02-19 09:25:53'),
	(10, '2026-02-19 09:26:58', '2026-02-19 09:26:58'),
	(11, '2026-02-19 09:27:53', '2026-02-19 09:27:53'),
	(12, '2026-02-19 09:28:58', '2026-02-19 09:28:58'),
	(13, '2026-02-19 09:30:01', '2026-02-19 09:30:01'),
	(14, '2026-02-19 09:31:13', '2026-02-19 09:31:13'),
	(15, '2026-02-19 09:32:07', '2026-02-19 09:32:07'),
	(16, '2026-02-19 09:33:23', '2026-02-19 09:33:23'),
	(17, '2026-02-19 09:34:20', '2026-02-19 09:34:20'),
	(18, '2026-02-19 09:41:41', '2026-02-19 09:41:41'),
	(19, '2026-02-19 09:48:29', '2026-02-19 09:48:29'),
	(20, '2026-02-19 09:49:28', '2026-02-19 09:49:28'),
	(21, '2026-02-19 10:04:24', '2026-02-19 10:04:24'),
	(22, '2026-02-20 14:31:14', '2026-02-20 14:31:14'),
	(23, '2026-02-20 14:32:19', '2026-02-20 14:32:19'),
	(24, '2026-02-20 14:43:15', '2026-02-20 14:43:15'),
	(25, '2026-02-20 14:46:50', '2026-02-20 14:46:50'),
	(26, '2026-02-20 14:48:24', '2026-02-20 14:48:24'),
	(27, '2026-02-20 14:50:23', '2026-02-20 14:50:23'),
	(28, '2026-02-20 14:55:08', '2026-02-20 14:55:08'),
	(29, '2026-02-20 14:58:16', '2026-02-20 14:58:16'),
	(30, '2026-02-20 14:59:12', '2026-02-20 14:59:12'),
	(31, '2026-02-20 15:03:25', '2026-02-20 15:03:25'),
	(32, '2026-02-20 15:05:54', '2026-02-20 15:05:54'),
	(33, '2026-02-20 15:10:05', '2026-02-20 15:10:05'),
	(34, '2026-02-20 15:11:30', '2026-02-20 15:11:30'),
	(35, '2026-02-20 15:14:01', '2026-02-20 15:14:01'),
	(36, '2026-02-20 18:35:01', '2026-02-20 18:35:01'),
	(37, '2026-02-20 18:37:51', '2026-02-20 18:37:51'),
	(38, '2026-02-20 18:38:59', '2026-02-20 18:38:59'),
	(39, '2026-02-20 18:40:25', '2026-02-20 18:40:25');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;

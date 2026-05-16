-- MySQL dump 10.13  Distrib 9.6.0, for macos26.2 (arm64)
--
-- Host: 127.0.0.1    Database: db_app_gruposamanez
-- ------------------------------------------------------
-- Server version	8.0.45-0ubuntu0.24.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `academy_attendance_deadlines`
--

DROP TABLE IF EXISTS `academy_attendance_deadlines`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `academy_attendance_deadlines` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `academy_attendance_deadlines`
--

LOCK TABLES `academy_attendance_deadlines` WRITE;
/*!40000 ALTER TABLE `academy_attendance_deadlines` DISABLE KEYS */;
/*!40000 ALTER TABLE `academy_attendance_deadlines` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `academy_attendances`
--

DROP TABLE IF EXISTS `academy_attendances`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `academy_attendances` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `academy_attendances`
--

LOCK TABLES `academy_attendances` WRITE;
/*!40000 ALTER TABLE `academy_attendances` DISABLE KEYS */;
/*!40000 ALTER TABLE `academy_attendances` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `academy_branches`
--

DROP TABLE IF EXISTS `academy_branches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `academy_branches` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ubication` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `academy_branches_name_unique` (`name`),
  KEY `academy_branches_is_active_index` (`is_active`),
  KEY `academy_branches_name_index` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `academy_branches`
--

LOCK TABLES `academy_branches` WRITE;
/*!40000 ALTER TABLE `academy_branches` DISABLE KEYS */;
INSERT INTO `academy_branches` VALUES (1,'Escuela Bárbaros Juliaca','Jr. Unión N° 209',NULL,'Centro',1,'2026-02-17 02:40:08','2026-02-17 15:16:20');
/*!40000 ALTER TABLE `academy_branches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `academy_enrollment_group_changes`
--

DROP TABLE IF EXISTS `academy_enrollment_group_changes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `academy_enrollment_group_changes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `origin_enrollment_id` bigint unsigned NOT NULL,
  `destination_enrollment_id` bigint unsigned NOT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci,
  `changed_by_user_id` bigint unsigned NOT NULL,
  `changed_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `aegc_origin_enrollment_fk` (`origin_enrollment_id`),
  KEY `aegc_destination_enrollment_fk` (`destination_enrollment_id`),
  KEY `aegc_changed_by_user_fk` (`changed_by_user_id`),
  CONSTRAINT `aegc_changed_by_user_fk` FOREIGN KEY (`changed_by_user_id`) REFERENCES `auth_users` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `aegc_destination_enrollment_fk` FOREIGN KEY (`destination_enrollment_id`) REFERENCES `academy_enrollments` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `aegc_origin_enrollment_fk` FOREIGN KEY (`origin_enrollment_id`) REFERENCES `academy_enrollments` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `academy_enrollment_group_changes`
--

LOCK TABLES `academy_enrollment_group_changes` WRITE;
/*!40000 ALTER TABLE `academy_enrollment_group_changes` DISABLE KEYS */;
/*!40000 ALTER TABLE `academy_enrollment_group_changes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `academy_enrollment_materials`
--

DROP TABLE IF EXISTS `academy_enrollment_materials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `academy_enrollment_materials` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `enrollment_id` bigint unsigned NOT NULL,
  `material_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `academy_enrollment_materials_enrollment_id_material_id_unique` (`enrollment_id`,`material_id`),
  KEY `academy_enrollment_materials_enrollment_id_index` (`enrollment_id`),
  KEY `academy_enrollment_materials_material_id_index` (`material_id`),
  CONSTRAINT `academy_enrollment_materials_enrollment_id_foreign` FOREIGN KEY (`enrollment_id`) REFERENCES `academy_enrollments` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `academy_enrollment_materials_material_id_foreign` FOREIGN KEY (`material_id`) REFERENCES `academy_materials` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `academy_enrollment_materials`
--

LOCK TABLES `academy_enrollment_materials` WRITE;
/*!40000 ALTER TABLE `academy_enrollment_materials` DISABLE KEYS */;
/*!40000 ALTER TABLE `academy_enrollment_materials` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `academy_enrollment_payment_advances`
--

DROP TABLE IF EXISTS `academy_enrollment_payment_advances`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `academy_enrollment_payment_advances` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint unsigned NOT NULL,
  `enrollment_payment_id` bigint unsigned DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `observation` text COLLATE utf8mb4_unicode_ci,
  `payment_date` date NOT NULL,
  `used_at` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `academy_enrollment_payment_advances_student_id_foreign` (`student_id`),
  KEY `adv_enrollment_payment_id_foreign` (`enrollment_payment_id`),
  CONSTRAINT `academy_enrollment_payment_advances_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `profile_students` (`core_person_id`) ON DELETE RESTRICT,
  CONSTRAINT `adv_enrollment_payment_id_foreign` FOREIGN KEY (`enrollment_payment_id`) REFERENCES `academy_enrollment_payments` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `academy_enrollment_payment_advances`
--

LOCK TABLES `academy_enrollment_payment_advances` WRITE;
/*!40000 ALTER TABLE `academy_enrollment_payment_advances` DISABLE KEYS */;
INSERT INTO `academy_enrollment_payment_advances` VALUES (1,13,NULL,100.00,'ADELANTO','2026-05-14',NULL,'2026-05-14 16:06:04','2026-05-14 16:06:04');
/*!40000 ALTER TABLE `academy_enrollment_payment_advances` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `academy_enrollment_payment_details`
--

DROP TABLE IF EXISTS `academy_enrollment_payment_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `academy_enrollment_payment_details` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `enrollment_payment_id` bigint unsigned NOT NULL,
  `group_payment_plan_id` bigint unsigned NOT NULL,
  `type` enum('enrollment','monthly') COLLATE utf8mb4_unicode_ci NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `academy_enrollment_payment_details_group_payment_plan_id_foreign` (`group_payment_plan_id`),
  KEY `academy_enrollment_payment_details_enrollment_payment_id_index` (`enrollment_payment_id`),
  KEY `academy_enrollment_payment_details_type_index` (`type`),
  CONSTRAINT `academy_enrollment_payment_details_enrollment_payment_id_foreign` FOREIGN KEY (`enrollment_payment_id`) REFERENCES `academy_enrollment_payments` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `academy_enrollment_payment_details_group_payment_plan_id_foreign` FOREIGN KEY (`group_payment_plan_id`) REFERENCES `academy_group_payment_plans` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `academy_enrollment_payment_details`
--

LOCK TABLES `academy_enrollment_payment_details` WRITE;
/*!40000 ALTER TABLE `academy_enrollment_payment_details` DISABLE KEYS */;
INSERT INTO `academy_enrollment_payment_details` VALUES (1,1,50,'enrollment',250.00,250.00,0.00,'2026-03-03 22:16:59','2026-03-03 22:16:59'),(2,1,51,'monthly',500.00,100.00,400.00,'2026-03-03 22:16:59','2026-03-03 22:16:59'),(3,2,50,'enrollment',250.00,250.00,0.00,'2026-03-03 22:17:24','2026-03-03 22:17:24'),(4,2,51,'monthly',500.00,100.00,400.00,'2026-03-03 22:17:24','2026-03-03 22:17:24'),(5,3,50,'enrollment',250.00,250.00,0.00,'2026-03-03 22:18:50','2026-03-03 22:18:50'),(6,3,51,'monthly',500.00,100.00,400.00,'2026-03-03 22:18:50','2026-03-03 22:18:50'),(7,4,50,'enrollment',250.00,0.00,250.00,'2026-03-03 22:19:19','2026-03-03 22:19:19'),(8,4,51,'monthly',500.00,100.00,400.00,'2026-03-03 22:19:19','2026-03-03 22:19:19'),(9,5,50,'enrollment',250.00,250.00,0.00,'2026-03-03 22:19:47','2026-03-03 22:19:47'),(10,6,50,'enrollment',250.00,250.00,0.00,'2026-03-03 22:20:07','2026-03-03 22:20:07'),(11,6,51,'monthly',500.00,150.00,350.00,'2026-03-03 22:20:07','2026-03-03 22:20:07'),(12,7,50,'enrollment',250.00,250.00,0.00,'2026-03-03 22:20:29','2026-03-03 22:20:29'),(13,7,51,'monthly',500.00,100.00,400.00,'2026-03-03 22:20:29','2026-03-03 22:20:29'),(14,8,50,'enrollment',250.00,250.00,0.00,'2026-03-03 22:20:51','2026-03-03 22:20:51'),(15,8,51,'monthly',500.00,100.00,400.00,'2026-03-03 22:20:51','2026-03-03 22:20:51'),(16,9,50,'enrollment',250.00,250.00,0.00,'2026-03-03 22:21:18','2026-03-03 22:21:18'),(17,10,50,'enrollment',250.00,250.00,0.00,'2026-03-03 22:21:40','2026-03-03 22:21:40'),(18,10,51,'monthly',500.00,100.00,400.00,'2026-03-03 22:21:40','2026-03-03 22:21:40'),(19,11,50,'enrollment',250.00,250.00,0.00,'2026-03-03 22:23:34','2026-03-03 22:23:34'),(20,11,51,'monthly',500.00,100.00,400.00,'2026-03-03 22:23:34','2026-03-03 22:23:34'),(21,12,50,'enrollment',250.00,250.00,0.00,'2026-03-03 22:24:00','2026-03-03 22:24:00'),(22,12,51,'monthly',500.00,100.00,400.00,'2026-03-03 22:24:00','2026-03-03 22:24:00'),(23,13,50,'enrollment',250.00,250.00,0.00,'2026-03-03 22:24:41','2026-03-03 22:24:41'),(24,14,50,'enrollment',250.00,250.00,0.00,'2026-03-03 22:25:06','2026-03-03 22:25:06'),(25,14,51,'monthly',500.00,100.00,400.00,'2026-03-03 22:25:06','2026-03-03 22:25:06'),(26,15,52,'enrollment',250.00,0.00,250.00,'2026-03-07 22:11:50','2026-03-07 22:11:50'),(27,16,52,'enrollment',250.00,0.00,250.00,'2026-03-07 22:12:41','2026-03-07 22:12:41'),(28,17,52,'enrollment',250.00,0.00,250.00,'2026-03-07 22:13:16','2026-03-07 22:13:16'),(29,18,52,'enrollment',250.00,0.00,250.00,'2026-03-07 22:13:39','2026-03-07 22:13:39'),(30,19,52,'enrollment',250.00,0.00,250.00,'2026-03-07 22:13:56','2026-03-07 22:13:56'),(31,20,53,'monthly',650.00,50.00,600.00,'2026-03-07 22:18:12','2026-03-07 22:18:12'),(32,20,54,'monthly',650.00,50.00,600.00,'2026-03-07 22:18:12','2026-03-07 22:18:12'),(33,21,52,'enrollment',250.00,0.00,250.00,'2026-03-09 17:06:34','2026-03-09 17:06:34'),(34,22,52,'enrollment',250.00,50.00,200.00,'2026-03-09 17:07:03','2026-03-09 17:07:03'),(35,23,55,'enrollment',250.00,0.00,250.00,'2026-03-09 19:11:33','2026-03-09 19:11:33'),(36,24,55,'enrollment',250.00,0.00,250.00,'2026-03-09 19:13:05','2026-03-09 19:13:05'),(37,25,55,'enrollment',250.00,0.00,250.00,'2026-03-09 19:13:24','2026-03-09 19:13:24'),(38,26,52,'enrollment',250.00,0.00,250.00,'2026-03-09 23:53:16','2026-03-09 23:53:16'),(39,27,50,'enrollment',250.00,0.00,250.00,'2026-05-12 19:26:11','2026-05-12 19:26:11'),(40,28,65,'enrollment',250.00,0.00,250.00,'2026-05-13 15:28:54','2026-05-13 15:28:54'),(41,29,65,'enrollment',250.00,125.00,125.00,'2026-05-13 15:30:10','2026-05-13 15:30:10'),(42,30,65,'enrollment',250.00,0.00,250.00,'2026-05-13 15:31:16','2026-05-13 15:31:16'),(43,31,65,'enrollment',250.00,250.00,0.00,'2026-05-13 15:32:56','2026-05-13 15:32:56'),(44,32,65,'enrollment',250.00,0.00,250.00,'2026-05-13 15:33:26','2026-05-13 15:33:26'),(45,33,65,'enrollment',250.00,0.00,250.00,'2026-05-13 15:34:10','2026-05-13 15:34:10'),(46,34,65,'enrollment',250.00,0.00,250.00,'2026-05-13 15:34:45','2026-05-13 15:34:45'),(47,35,65,'enrollment',250.00,0.00,250.00,'2026-05-13 15:35:13','2026-05-13 15:35:13'),(48,36,65,'enrollment',250.00,0.00,250.00,'2026-05-13 15:35:38','2026-05-13 15:35:38'),(49,37,68,'enrollment',250.00,0.00,250.00,'2026-05-13 15:35:59','2026-05-13 15:35:59'),(50,38,68,'enrollment',250.00,0.00,250.00,'2026-05-13 15:36:15','2026-05-13 15:36:15'),(51,39,68,'enrollment',250.00,0.00,250.00,'2026-05-13 15:36:33','2026-05-13 15:36:33'),(52,40,68,'enrollment',0.00,0.00,0.00,'2026-05-13 15:36:58','2026-05-13 15:36:58'),(53,41,90,'enrollment',250.00,0.00,250.00,'2026-05-13 17:24:20','2026-05-13 17:24:20'),(54,42,65,'enrollment',250.00,0.00,250.00,'2026-05-14 15:48:12','2026-05-14 15:48:12'),(55,43,68,'enrollment',250.00,0.00,250.00,'2026-05-14 15:49:20','2026-05-14 15:49:20'),(56,44,65,'enrollment',250.00,125.00,125.00,'2026-05-14 15:50:39','2026-05-14 15:50:39'),(57,45,94,'enrollment',0.00,0.00,0.00,'2026-05-14 16:06:12','2026-05-14 16:06:12'),(58,46,94,'enrollment',0.00,0.00,0.00,'2026-05-14 16:07:06','2026-05-14 16:07:06'),(59,46,95,'monthly',500.00,100.00,400.00,'2026-05-14 16:07:06','2026-05-14 16:07:06');
/*!40000 ALTER TABLE `academy_enrollment_payment_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `academy_enrollment_payments`
--

DROP TABLE IF EXISTS `academy_enrollment_payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `academy_enrollment_payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `enrollment_id` bigint unsigned NOT NULL,
  `status` enum('active','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `academy_enrollment_payments_enrollment_id_foreign` (`enrollment_id`),
  CONSTRAINT `academy_enrollment_payments_enrollment_id_foreign` FOREIGN KEY (`enrollment_id`) REFERENCES `academy_enrollments` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `academy_enrollment_payments`
--

LOCK TABLES `academy_enrollment_payments` WRITE;
/*!40000 ALTER TABLE `academy_enrollment_payments` DISABLE KEYS */;
INSERT INTO `academy_enrollment_payments` VALUES (1,38,'active','2026-03-03 22:16:59','2026-03-03 22:16:59'),(2,39,'active','2026-03-03 22:17:24','2026-03-03 22:17:24'),(3,40,'active','2026-03-03 22:18:50','2026-03-03 22:18:50'),(4,41,'active','2026-03-03 22:19:19','2026-03-03 22:19:19'),(5,42,'active','2026-03-03 22:19:47','2026-03-03 22:19:47'),(6,43,'active','2026-03-03 22:20:07','2026-03-03 22:20:07'),(7,44,'active','2026-03-03 22:20:29','2026-03-03 22:20:29'),(8,45,'active','2026-03-03 22:20:51','2026-03-03 22:20:51'),(9,46,'active','2026-03-03 22:21:18','2026-03-03 22:21:18'),(10,47,'active','2026-03-03 22:21:40','2026-03-03 22:21:40'),(11,49,'active','2026-03-03 22:23:34','2026-03-03 22:23:34'),(12,50,'active','2026-03-03 22:24:00','2026-03-03 22:24:00'),(13,51,'active','2026-03-03 22:24:41','2026-03-03 22:24:41'),(14,52,'active','2026-03-03 22:25:06','2026-03-03 22:25:06'),(15,53,'active','2026-03-07 22:11:50','2026-03-07 22:11:50'),(16,54,'active','2026-03-07 22:12:41','2026-03-07 22:12:41'),(17,55,'active','2026-03-07 22:13:16','2026-03-07 22:13:16'),(18,56,'active','2026-03-07 22:13:39','2026-03-07 22:13:39'),(19,57,'active','2026-03-07 22:13:56','2026-03-07 22:13:56'),(20,56,'active','2026-03-07 22:18:12','2026-03-07 22:18:12'),(21,58,'active','2026-03-09 17:06:34','2026-03-09 17:06:34'),(22,59,'active','2026-03-09 17:07:03','2026-03-09 17:07:03'),(23,60,'active','2026-03-09 19:11:33','2026-03-09 19:11:33'),(24,61,'active','2026-03-09 19:13:05','2026-03-09 19:13:05'),(25,62,'active','2026-03-09 19:13:24','2026-03-09 19:13:24'),(26,63,'active','2026-03-09 23:53:16','2026-03-09 23:53:16'),(27,64,'active','2026-05-12 19:26:11','2026-05-12 19:26:11'),(28,65,'active','2026-05-13 15:28:54','2026-05-13 15:28:54'),(29,66,'active','2026-05-13 15:30:10','2026-05-13 15:30:10'),(30,67,'active','2026-05-13 15:31:16','2026-05-13 15:31:16'),(31,68,'active','2026-05-13 15:32:56','2026-05-13 15:32:56'),(32,69,'active','2026-05-13 15:33:26','2026-05-13 15:33:26'),(33,70,'active','2026-05-13 15:34:10','2026-05-13 15:34:10'),(34,71,'active','2026-05-13 15:34:45','2026-05-13 15:34:45'),(35,72,'active','2026-05-13 15:35:13','2026-05-13 15:35:13'),(36,73,'active','2026-05-13 15:35:38','2026-05-13 15:35:38'),(37,74,'active','2026-05-13 15:35:59','2026-05-13 15:35:59'),(38,75,'active','2026-05-13 15:36:15','2026-05-13 15:36:15'),(39,76,'active','2026-05-13 15:36:33','2026-05-13 15:36:33'),(40,77,'active','2026-05-13 15:36:58','2026-05-13 15:36:58'),(41,78,'active','2026-05-13 17:24:20','2026-05-13 17:24:20'),(42,79,'active','2026-05-14 15:48:12','2026-05-14 15:48:12'),(43,80,'active','2026-05-14 15:49:20','2026-05-14 15:49:20'),(44,81,'active','2026-05-14 15:50:39','2026-05-14 15:50:39'),(45,82,'active','2026-05-14 16:06:12','2026-05-14 16:06:12'),(46,83,'active','2026-05-14 16:07:06','2026-05-14 16:07:06');
/*!40000 ALTER TABLE `academy_enrollment_payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `academy_enrollments`
--

DROP TABLE IF EXISTS `academy_enrollments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `academy_enrollments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `profile_student_id` bigint unsigned NOT NULL,
  `group_id` bigint unsigned NOT NULL,
  `date` date NOT NULL,
  `status` enum('active','cancelled','completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `enrollments_student_id_index` (`profile_student_id`),
  KEY `enrollments_group_id_index` (`group_id`),
  CONSTRAINT `academy_enrollments_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `academy_groups` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `academy_enrollments_profile_student_id_foreign` FOREIGN KEY (`profile_student_id`) REFERENCES `profile_students` (`core_person_id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=84 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `academy_enrollments`
--

LOCK TABLES `academy_enrollments` WRITE;
/*!40000 ALTER TABLE `academy_enrollments` DISABLE KEYS */;
INSERT INTO `academy_enrollments` VALUES (6,6,3,'2026-02-19','active','2026-02-20 00:52:11','2026-02-20 00:52:11'),(7,7,3,'2026-02-19','active','2026-02-20 00:52:50','2026-02-20 00:52:50'),(8,8,3,'2026-02-19','active','2026-02-20 00:53:05','2026-02-20 00:53:05'),(9,9,3,'2026-02-19','active','2026-02-20 00:54:52','2026-02-20 00:54:52'),(10,10,3,'2026-02-19','active','2026-02-20 00:55:11','2026-02-20 00:55:11'),(11,11,3,'2026-02-19','active','2026-02-20 00:55:24','2026-02-20 00:55:24'),(12,12,3,'2026-02-19','active','2026-02-20 00:56:17','2026-02-20 00:56:17'),(13,13,3,'2026-02-19','active','2026-02-20 00:56:41','2026-02-20 00:56:41'),(14,14,3,'2026-02-19','active','2026-02-20 00:57:29','2026-02-20 00:57:29'),(15,15,3,'2026-02-19','active','2026-02-20 00:57:56','2026-02-20 00:57:56'),(16,16,3,'2026-02-19','active','2026-02-20 00:58:48','2026-02-20 00:58:48'),(17,17,3,'2026-02-19','active','2026-02-20 00:59:07','2026-02-20 00:59:07'),(18,18,3,'2026-02-19','active','2026-02-20 00:59:38','2026-02-20 00:59:38'),(19,20,3,'2026-02-19','active','2026-02-20 01:04:54','2026-02-20 01:04:54'),(20,21,3,'2026-02-19','active','2026-02-20 01:05:08','2026-02-20 01:05:08'),(21,22,10,'2026-02-20','active','2026-02-21 05:41:27','2026-02-21 05:41:27'),(22,23,10,'2026-02-20','active','2026-02-21 05:41:44','2026-02-21 05:41:44'),(23,24,10,'2026-02-20','active','2026-02-21 05:43:56','2026-02-21 05:43:56'),(24,25,10,'2026-02-20','active','2026-02-21 05:47:03','2026-02-21 05:47:03'),(25,26,10,'2026-02-20','active','2026-02-21 05:48:38','2026-02-21 05:48:38'),(26,27,10,'2026-02-20','active','2026-02-21 05:51:17','2026-02-21 05:51:17'),(27,28,10,'2026-02-20','active','2026-02-21 05:55:30','2026-02-21 05:55:30'),(28,29,10,'2026-02-20','active','2026-02-21 05:58:30','2026-02-21 05:58:30'),(29,30,10,'2026-02-20','active','2026-02-21 05:59:33','2026-02-21 05:59:33'),(30,31,10,'2026-02-20','active','2026-02-21 06:04:40','2026-02-21 06:04:40'),(31,32,10,'2026-02-20','active','2026-02-21 06:06:02','2026-02-21 06:06:02'),(32,33,10,'2026-02-20','active','2026-02-21 06:10:23','2026-02-21 06:10:23'),(33,34,10,'2026-02-20','active','2026-02-21 06:12:54','2026-02-21 06:12:54'),(34,35,10,'2026-02-20','active','2026-02-21 06:14:09','2026-02-21 06:14:09'),(35,36,12,'2026-02-20','active','2026-02-21 09:36:02','2026-02-21 09:36:02'),(36,37,12,'2026-02-20','active','2026-02-21 09:38:04','2026-02-21 09:38:04'),(37,38,12,'2026-02-20','active','2026-02-21 09:39:20','2026-02-21 09:39:20'),(38,44,14,'2026-02-23','active','2026-03-03 22:16:59','2026-03-03 22:16:59'),(39,45,14,'2026-02-23','active','2026-03-03 22:17:24','2026-03-03 22:17:24'),(40,46,14,'2026-02-23','active','2026-03-03 22:18:50','2026-03-03 22:18:50'),(41,47,14,'2026-02-23','active','2026-03-03 22:19:19','2026-03-03 22:19:19'),(42,48,14,'2026-02-23','active','2026-03-03 22:19:47','2026-03-03 22:19:47'),(43,49,14,'2026-02-23','active','2026-03-03 22:20:07','2026-03-03 22:20:07'),(44,50,14,'2026-02-23','active','2026-03-03 22:20:29','2026-03-03 22:20:29'),(45,51,14,'2026-02-23','active','2026-03-03 22:20:51','2026-03-03 22:20:51'),(46,52,14,'2026-03-03','active','2026-03-03 22:21:18','2026-03-03 22:21:18'),(47,53,14,'2026-03-03','active','2026-03-03 22:21:40','2026-03-03 22:21:40'),(49,54,14,'2026-03-03','active','2026-03-03 22:23:34','2026-03-03 22:23:34'),(50,55,14,'2026-02-23','active','2026-03-03 22:24:00','2026-03-03 22:24:00'),(51,56,14,'2026-02-23','active','2026-03-03 22:24:41','2026-03-03 22:24:41'),(52,57,14,'2026-02-23','active','2026-03-03 22:25:06','2026-03-03 22:25:06'),(53,88,15,'2026-03-07','active','2026-03-07 22:11:50','2026-03-07 22:17:49'),(54,87,15,'2026-03-06','active','2026-03-07 22:12:41','2026-03-07 22:17:39'),(55,86,15,'2026-03-04','active','2026-03-07 22:13:16','2026-03-07 22:17:29'),(56,64,15,'2026-03-03','active','2026-03-07 22:13:39','2026-03-07 22:17:16'),(57,61,15,'2026-03-02','active','2026-03-07 22:13:56','2026-03-07 22:17:01'),(58,91,15,'2026-03-09','active','2026-03-09 17:06:34','2026-03-09 17:06:34'),(59,90,15,'2026-03-09','active','2026-03-09 17:07:03','2026-03-09 17:07:03'),(60,89,16,'2026-03-07','active','2026-03-09 19:11:33','2026-03-09 19:11:33'),(61,85,16,'2026-03-06','active','2026-03-09 19:13:05','2026-03-09 19:13:05'),(62,63,16,'2026-02-27','active','2026-03-09 19:13:24','2026-03-09 19:13:24'),(63,94,15,'2026-03-09','active','2026-03-09 23:53:16','2026-03-09 23:53:16'),(64,111,14,'2026-05-13','active','2026-05-12 19:26:11','2026-05-12 19:26:11'),(65,113,19,'2026-04-13','active','2026-05-13 15:28:54','2026-05-13 15:28:54'),(66,114,19,'2026-05-12','active','2026-05-13 15:30:10','2026-05-13 15:30:10'),(67,115,19,'2026-05-06','active','2026-05-13 15:31:16','2026-05-13 15:31:16'),(68,116,19,'2026-05-13','active','2026-05-13 15:32:56','2026-05-13 15:32:56'),(69,117,19,'2026-04-21','active','2026-05-13 15:33:26','2026-05-13 15:33:26'),(70,118,19,'2026-05-22','active','2026-05-13 15:34:10','2026-05-13 15:34:10'),(71,111,19,'2026-05-12','active','2026-05-13 15:34:45','2026-05-13 15:34:45'),(72,119,19,'2026-05-13','active','2026-05-13 15:35:13','2026-05-13 15:35:13'),(73,112,19,'2026-05-13','active','2026-05-13 15:35:38','2026-05-13 15:35:38'),(74,120,20,'2026-05-04','active','2026-05-13 15:35:59','2026-05-13 15:35:59'),(75,121,20,'2026-05-11','active','2026-05-13 15:36:15','2026-05-13 15:36:15'),(76,122,20,'2026-05-11','active','2026-05-13 15:36:33','2026-05-13 15:36:33'),(77,89,20,'2026-05-13','active','2026-05-13 15:36:58','2026-05-13 15:36:58'),(78,125,27,'2026-05-13','active','2026-05-13 17:24:20','2026-05-13 17:24:20'),(79,126,19,'2026-05-14','active','2026-05-14 15:48:12','2026-05-14 15:48:12'),(80,127,20,'2026-05-14','active','2026-05-14 15:49:20','2026-05-14 15:49:20'),(81,128,19,'2026-05-14','active','2026-05-14 15:50:39','2026-05-14 15:50:39'),(82,13,28,'2026-05-14','active','2026-05-14 16:06:12','2026-05-14 16:06:12'),(83,25,28,'2026-05-14','active','2026-05-14 16:07:06','2026-05-14 16:07:06');
/*!40000 ALTER TABLE `academy_enrollments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `academy_group_payment_plans`
--

DROP TABLE IF EXISTS `academy_group_payment_plans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `academy_group_payment_plans` (
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
) ENGINE=InnoDB AUTO_INCREMENT=96 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `academy_group_payment_plans`
--

LOCK TABLES `academy_group_payment_plans` WRITE;
/*!40000 ALTER TABLE `academy_group_payment_plans` DISABLE KEYS */;
INSERT INTO `academy_group_payment_plans` VALUES (18,9,'enrollment','2026-01-08','2026-03-08',250.00,'2026-02-19 23:53:58','2026-02-19 23:53:58'),(19,9,'monthly','2026-01-08','2026-02-08',650.00,'2026-02-19 23:53:58','2026-02-19 23:53:58'),(20,9,'monthly','2026-02-08','2026-03-08',650.00,'2026-02-19 23:53:58','2026-02-19 23:53:58'),(21,3,'enrollment','2026-01-08','2026-03-08',250.00,'2026-02-19 23:54:06','2026-02-19 23:54:06'),(22,3,'monthly','2026-01-08','2026-02-08',650.00,'2026-02-19 23:54:06','2026-02-19 23:54:06'),(23,3,'monthly','2026-02-08','2026-03-08',650.00,'2026-02-19 23:54:06','2026-02-19 23:54:06'),(27,10,'enrollment','2026-02-09','2026-04-09',250.00,'2026-02-20 00:02:42','2026-02-20 00:02:42'),(28,10,'monthly','2026-02-09','2026-03-09',650.00,'2026-02-20 00:02:42','2026-02-20 00:02:42'),(29,10,'monthly','2026-03-09','2026-04-09',650.00,'2026-02-20 00:02:42','2026-02-20 00:02:42'),(33,11,'enrollment','2026-01-16','2026-03-16',250.00,'2026-02-20 00:11:31','2026-02-20 00:11:31'),(34,11,'monthly','2026-01-16','2026-02-16',650.00,'2026-02-20 00:11:31','2026-02-20 00:11:31'),(35,11,'monthly','2026-02-16','2026-03-16',650.00,'2026-02-20 00:11:31','2026-02-20 00:11:31'),(44,12,'enrollment','2026-02-09','2026-05-09',250.00,'2026-02-21 09:21:56','2026-02-21 09:21:56'),(45,12,'monthly','2026-02-09','2026-03-09',450.00,'2026-02-21 09:21:56','2026-02-21 09:21:56'),(46,12,'monthly','2026-03-09','2026-04-09',450.00,'2026-02-21 09:21:56','2026-02-21 09:21:56'),(47,12,'monthly','2026-04-09','2026-05-09',450.00,'2026-02-21 09:21:56','2026-02-21 09:21:56'),(48,13,'enrollment','2026-02-18','2026-03-18',250.00,'2026-03-03 22:11:30','2026-03-03 22:11:30'),(49,13,'monthly','2026-02-18','2026-03-18',500.00,'2026-03-03 22:11:30','2026-03-03 22:11:30'),(50,14,'enrollment','2026-02-23','2026-03-23',250.00,'2026-03-03 22:12:23','2026-03-03 22:12:23'),(51,14,'monthly','2026-02-23','2026-03-23',500.00,'2026-03-03 22:12:23','2026-03-03 22:12:23'),(52,15,'enrollment','2026-03-12','2026-05-12',250.00,'2026-03-07 21:49:47','2026-03-07 21:49:47'),(53,15,'monthly','2026-03-12','2026-04-12',650.00,'2026-03-07 21:49:47','2026-03-07 21:49:47'),(54,15,'monthly','2026-04-12','2026-05-12',650.00,'2026-03-07 21:49:47','2026-03-07 21:49:47'),(55,16,'enrollment','2026-03-12','2026-06-12',250.00,'2026-03-09 17:24:57','2026-03-09 17:24:57'),(56,16,'monthly','2026-03-12','2026-04-12',450.00,'2026-03-09 17:24:57','2026-03-09 17:24:57'),(57,16,'monthly','2026-04-13','2026-05-12',450.00,'2026-03-09 17:24:57','2026-03-09 17:24:57'),(58,16,'monthly','2026-05-12','2026-06-12',450.00,'2026-03-09 17:24:57','2026-03-09 17:24:57'),(59,17,'enrollment','2026-03-25','2026-04-27',250.00,'2026-03-09 23:49:14','2026-03-09 23:49:14'),(60,17,'monthly','2026-03-25','2026-04-27',500.00,'2026-03-09 23:49:14','2026-03-09 23:49:14'),(61,18,'enrollment','2026-03-16','2026-06-16',250.00,'2026-03-14 21:11:06','2026-05-13 11:50:59'),(62,18,'monthly','2026-03-16','2026-04-16',450.00,'2026-03-14 21:11:06','2026-03-14 21:11:06'),(63,18,'monthly','2026-04-16','2026-05-16',450.00,'2026-03-14 21:11:06','2026-03-14 21:11:06'),(64,18,'monthly','2026-05-16','2026-06-16',450.00,'2026-03-14 21:11:06','2026-05-13 11:50:59'),(65,19,'enrollment','2026-05-18','2026-07-18',250.00,'2026-05-13 11:49:47','2026-05-13 11:49:47'),(66,19,'monthly','2026-05-18','2026-06-18',650.00,'2026-05-13 11:49:47','2026-05-13 11:49:47'),(67,19,'monthly','2026-06-18','2026-07-18',650.00,'2026-05-13 11:49:47','2026-05-13 11:49:47'),(68,20,'enrollment','2026-05-18','2026-08-18',250.00,'2026-05-13 11:53:37','2026-05-13 11:53:37'),(69,20,'monthly','2026-05-18','2026-06-18',450.00,'2026-05-13 11:53:37','2026-05-13 11:53:37'),(70,20,'monthly','2026-06-18','2026-07-18',450.00,'2026-05-13 11:53:37','2026-05-13 11:53:37'),(71,20,'monthly','2026-07-18','2026-08-18',450.00,'2026-05-13 11:53:37','2026-05-13 11:53:37'),(72,21,'enrollment','2026-04-13','2026-06-13',250.00,'2026-05-13 14:26:40','2026-05-13 14:26:40'),(73,21,'monthly','2026-04-13','2026-05-13',650.00,'2026-05-13 14:26:40','2026-05-13 14:26:40'),(74,21,'monthly','2026-05-13','2026-06-13',650.00,'2026-05-13 14:26:40','2026-05-13 14:26:40'),(75,22,'monthly','2026-04-13','2026-05-13',450.00,'2026-05-13 14:42:31','2026-05-13 14:42:31'),(76,22,'monthly','2026-05-13','2026-06-13',450.00,'2026-05-13 14:42:31','2026-05-13 14:42:31'),(77,22,'monthly','2026-06-13','2026-07-13',450.00,'2026-05-13 14:42:31','2026-05-13 14:42:31'),(78,23,'enrollment','2026-04-13','2026-07-13',250.00,'2026-05-13 14:44:58','2026-05-13 14:44:58'),(79,23,'monthly','2026-04-13','2026-05-13',450.00,'2026-05-13 14:44:58','2026-05-13 14:44:58'),(80,23,'monthly','2026-05-13','2026-06-13',450.00,'2026-05-13 14:44:58','2026-05-13 14:44:58'),(81,23,'monthly','2026-06-13','2026-07-13',450.00,'2026-05-13 14:44:58','2026-05-13 14:44:58'),(82,24,'enrollment','2026-04-18','2026-07-18',250.00,'2026-05-13 14:47:13','2026-05-13 14:47:13'),(83,24,'monthly','2026-04-18','2026-05-18',450.00,'2026-05-13 14:47:13','2026-05-13 14:47:13'),(84,24,'monthly','2026-05-18','2026-06-18',450.00,'2026-05-13 14:47:13','2026-05-13 14:47:13'),(85,24,'monthly','2026-06-18','2026-07-18',450.00,'2026-05-13 14:47:13','2026-05-13 14:47:13'),(86,25,'enrollment','2026-05-02','2026-05-30',250.00,'2026-05-13 14:53:49','2026-05-13 14:53:49'),(87,25,'monthly','2026-05-02','2026-05-30',500.00,'2026-05-13 14:53:49','2026-05-13 14:53:49'),(88,26,'enrollment','2026-04-27','2026-05-28',250.00,'2026-05-13 14:59:42','2026-05-13 14:59:42'),(89,26,'monthly','2026-04-27','2026-05-28',500.00,'2026-05-13 14:59:42','2026-05-13 14:59:42'),(90,27,'enrollment','2026-06-06','2026-09-05',250.00,'2026-05-13 17:21:48','2026-05-13 17:21:48'),(91,27,'monthly','2026-06-06','2026-07-04',450.00,'2026-05-13 17:21:48','2026-05-13 17:21:48'),(92,27,'monthly','2026-07-04','2026-08-01',450.00,'2026-05-13 17:21:48','2026-05-13 17:21:48'),(93,27,'monthly','2026-08-01','2026-09-05',450.00,'2026-05-13 17:21:48','2026-05-13 17:21:48'),(94,28,'enrollment','2026-06-01','2026-07-01',250.00,'2026-05-14 16:05:06','2026-05-14 16:05:06'),(95,28,'monthly','2026-06-01','2026-07-01',500.00,'2026-05-14 16:05:06','2026-05-14 16:05:06');
/*!40000 ALTER TABLE `academy_group_payment_plans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `academy_group_teachers`
--

DROP TABLE IF EXISTS `academy_group_teachers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `academy_group_teachers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `group_id` bigint unsigned NOT NULL,
  `teacher_id` bigint unsigned NOT NULL,
  `hourly_rate` decimal(10,2) NOT NULL,
  `holiday_hourly_rate` decimal(10,2) NOT NULL,
  `status` enum('active','withdrawn','replaced') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `observation` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `academy_group_teachers_group_id_index` (`group_id`),
  KEY `academy_group_teachers_teacher_id_index` (`teacher_id`),
  KEY `academy_group_teachers_status_index` (`status`),
  CONSTRAINT `academy_group_teachers_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `academy_groups` (`id`) ON DELETE CASCADE,
  CONSTRAINT `academy_group_teachers_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `profile_teachers` (`core_person_id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `academy_group_teachers`
--

LOCK TABLES `academy_group_teachers` WRITE;
/*!40000 ALTER TABLE `academy_group_teachers` DISABLE KEYS */;
/*!40000 ALTER TABLE `academy_group_teachers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `academy_groups`
--

DROP TABLE IF EXISTS `academy_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `academy_groups` (
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
  `status` enum('active','coming','cancelled','finished') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `academy_groups_branch_id_foreign` (`branch_id`),
  KEY `academy_groups_level_id_foreign` (`level_id`),
  KEY `academy_groups_schedule_id_foreign` (`schedule_id`),
  KEY `academy_groups_room_id_foreign` (`room_id`),
  KEY `academy_groups_is_active_index` (`is_active`),
  KEY `academy_groups_status_index` (`status`),
  KEY `academy_groups_name_index` (`name`),
  CONSTRAINT `academy_groups_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `academy_branches` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `academy_groups_level_id_foreign` FOREIGN KEY (`level_id`) REFERENCES `academy_levels` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `academy_groups_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `academy_rooms` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `academy_groups_schedule_id_foreign` FOREIGN KEY (`schedule_id`) REFERENCES `academy_schedules` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `academy_groups`
--

LOCK TABLES `academy_groups` WRITE;
/*!40000 ALTER TABLE `academy_groups` DISABLE KEYS */;
INSERT INTO `academy_groups` VALUES (3,1,4,1,1,'2 MESES (9-12:30) - JESUS SALAS','2026-01-08','2026-03-08',250.00,650.00,'1,2,3,4,5',20,'active',1,'2026-02-18 05:19:56','2026-03-04 01:15:24'),(9,1,4,1,2,'2 MESES (9-12:30) - RONALDINO CALCINA','2026-01-08','2026-03-08',250.00,650.00,'1,2,3,4,5',20,'cancelled',0,'2026-02-19 23:53:45','2026-05-13 14:47:28'),(10,1,4,1,4,'2 MESES (9-12:30) - MIDWAR SANDOVAL','2026-02-09','2026-04-09',250.00,650.00,'1,2,3,4,5',20,'active',1,'2026-02-20 00:02:23','2026-03-04 01:18:24'),(11,1,4,1,3,'2 MESES (9-12:30) - ALEX SANTOS','2026-01-16','2026-03-16',250.00,650.00,'1,3,2,4,5',20,'active',1,'2026-02-20 00:11:13','2026-03-04 01:18:18'),(12,1,1,2,2,'3 MESES (2-4) - RONALDINO CALCINA','2026-02-09','2026-05-09',250.00,450.00,'1,2,3,5,4',20,'active',1,'2026-02-21 05:38:43','2026-03-04 01:15:41'),(13,1,2,4,4,'1 MES (6:00-8:00) - MIDWAR','2026-02-18','2026-03-18',250.00,500.00,'1,2,3,4',20,'active',1,'2026-03-03 22:11:30','2026-03-04 01:18:29'),(14,1,2,4,2,'1 MES (6:00-8:00) - RONALDINO','2026-02-23','2026-03-23',250.00,500.00,'1,2,3,4,5',20,'active',1,'2026-03-03 22:12:23','2026-03-04 01:16:24'),(15,1,4,1,1,'2 MESES (9-12:30)16 MARZO - JESUS SALAS','2026-03-16','2026-05-16',250.00,650.00,'1,2,3,4,5',20,'active',1,'2026-03-07 21:49:47','2026-03-14 21:26:33'),(16,1,1,2,2,'3 MESES (2-4) - 16 MARZO X','2026-03-16','2026-06-16',250.00,450.00,'1,2,3,4,5',20,'active',1,'2026-03-09 17:24:57','2026-03-14 21:32:41'),(17,1,2,4,4,'1 MES (6:00-8:00) - RONALDINO','2026-03-25','2026-04-27',250.00,500.00,'1,2,3,4,5',20,'active',1,'2026-03-09 23:49:14','2026-05-13 11:09:53'),(18,1,1,4,2,'3 MESES (6-8)16 MARZO - X','2026-03-16','2026-06-16',250.00,450.00,'1,2,3,4,5',20,'active',1,'2026-03-14 21:11:05','2026-05-13 11:50:59'),(19,1,4,1,1,'2 MESES (9-12:30)18 MAYO','2026-05-18','2026-07-18',250.00,650.00,'1,2,3,4,5',20,'active',1,'2026-05-13 11:49:47','2026-05-13 11:49:47'),(20,1,1,2,2,'3 MESES (2-4)18 MAYO','2026-05-18','2026-08-18',250.00,450.00,'1,2,3,5,4',20,'active',1,'2026-05-13 11:53:37','2026-05-13 11:53:37'),(21,1,4,1,2,'2 MESES (9-12:30)13 ABRIL','2026-04-13','2026-06-13',250.00,650.00,'1,2,3,4,5',20,'active',1,'2026-05-13 14:26:40','2026-05-13 14:26:40'),(22,1,1,3,2,'3 MESES (4-6)13 ABRIL','2026-04-13','2026-07-13',0.00,0.00,'1,2,3,4,5',20,'active',1,'2026-05-13 14:42:31','2026-05-13 14:42:31'),(23,1,1,4,1,'3 MESES (6-8)13 ABRIL','2026-04-13','2026-07-13',250.00,450.00,'1,2,3,4,5',20,'active',1,'2026-05-13 14:44:58','2026-05-13 14:44:58'),(24,1,5,5,1,'3 MESES (SÁBADOS) 18 ABRIL','2026-04-18','2026-07-18',250.00,450.00,'1,2,3,4,5',40,'active',1,'2026-05-13 14:47:13','2026-05-13 14:47:13'),(25,1,2,5,2,'1 MESES (SÁBADOS AVANZADO)18 MAYO','2026-05-02','2026-05-30',250.00,500.00,'6',40,'active',1,'2026-05-13 14:53:49','2026-05-13 14:53:49'),(26,1,2,4,2,'1 MESES (6-8)AVANZADO 27 ABRIL','2026-04-27','2026-05-28',250.00,500.00,'1,3,2,4,5',20,'active',1,'2026-05-13 14:59:42','2026-05-14 15:56:31'),(27,1,5,5,2,'3 MESES (SÁBADOS) 6 JUNIO','2026-06-06','2026-09-05',250.00,450.00,'6',40,'active',1,'2026-05-13 17:21:48','2026-05-13 17:21:48'),(28,1,2,4,2,'1 MESES (6-8)AVANZADO 01 JUNIO','2026-06-01','2026-07-01',250.00,500.00,'1,2,3,4,5',20,'active',1,'2026-05-14 16:05:06','2026-05-14 16:05:06');
/*!40000 ALTER TABLE `academy_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `academy_levels`
--

DROP TABLE IF EXISTS `academy_levels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `academy_levels` (
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `academy_levels`
--

LOCK TABLES `academy_levels` WRITE;
/*!40000 ALTER TABLE `academy_levels` DISABLE KEYS */;
INSERT INTO `academy_levels` VALUES (1,2,'SEMI INTENSIVO','2h',3,1,'2026-02-17 02:40:08','2026-03-11 20:40:56'),(2,3,'AVANZADO','1h',1,1,'2026-02-17 15:27:48','2026-03-11 20:40:35'),(4,1,'INTENSIVO','3h 30',2,1,'2026-02-17 17:50:24','2026-03-11 20:40:09'),(5,4,'FINES DE SEMANA','9:00AM a 7:00 PM',3,1,'2026-03-11 20:41:46','2026-03-11 20:41:46');
/*!40000 ALTER TABLE `academy_levels` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `academy_materials`
--

DROP TABLE IF EXISTS `academy_materials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `academy_materials` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `presentation_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `branch_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `academy_materials_presentation_id_index` (`presentation_id`),
  KEY `academy_materials_is_active_index` (`is_active`),
  KEY `academy_materials_branch_id_index` (`branch_id`),
  CONSTRAINT `academy_materials_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `academy_branches` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `academy_materials_presentation_id_foreign` FOREIGN KEY (`presentation_id`) REFERENCES `inventory_product_presentations` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `academy_materials`
--

LOCK TABLES `academy_materials` WRITE;
/*!40000 ALTER TABLE `academy_materials` DISABLE KEYS */;
/*!40000 ALTER TABLE `academy_materials` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `academy_rooms`
--

DROP TABLE IF EXISTS `academy_rooms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `academy_rooms` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `branch_id` bigint unsigned NOT NULL,
  `number` int NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `capacity` int NOT NULL,
  `floor` int NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `academy_rooms_branch_id_foreign` (`branch_id`),
  KEY `academy_rooms_is_active_index` (`is_active`),
  KEY `academy_rooms_number_index` (`number`),
  KEY `academy_rooms_floor_index` (`floor`),
  CONSTRAINT `academy_rooms_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `academy_branches` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `academy_rooms`
--

LOCK TABLES `academy_rooms` WRITE;
/*!40000 ALTER TABLE `academy_rooms` DISABLE KEYS */;
INSERT INTO `academy_rooms` VALUES (1,1,101,NULL,20,1,1,'2026-02-17 02:40:08','2026-02-17 02:40:08'),(2,1,102,NULL,20,1,1,'2026-02-17 17:53:07','2026-02-17 17:53:07'),(3,1,103,NULL,20,1,1,'2026-02-18 05:12:31','2026-02-18 05:12:31'),(4,1,104,NULL,20,1,1,'2026-02-18 05:12:59','2026-02-18 05:12:59');
/*!40000 ALTER TABLE `academy_rooms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `academy_schedules`
--

DROP TABLE IF EXISTS `academy_schedules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `academy_schedules` (
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `academy_schedules`
--

LOCK TABLES `academy_schedules` WRITE;
/*!40000 ALTER TABLE `academy_schedules` DISABLE KEYS */;
INSERT INTO `academy_schedules` VALUES (1,'morning','09:00:00','12:30:00',1,'2026-02-17 02:40:08','2026-02-17 15:17:26'),(2,'afternoon','14:00:00','16:00:00',1,'2026-02-17 15:17:46','2026-02-21 09:00:37'),(3,'afternoon','16:00:00','18:00:00',1,'2026-02-17 15:17:58','2026-02-21 09:00:55'),(4,'night','18:00:00','20:00:00',1,'2026-02-17 15:18:10','2026-03-03 22:13:00'),(5,'morning','09:00:00','19:00:00',1,'2026-02-17 17:46:56','2026-02-17 17:46:56'),(7,'afternoon','14:00:00','17:30:00',1,'2026-02-21 09:00:26','2026-02-21 09:00:26');
/*!40000 ALTER TABLE `academy_schedules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `academy_student_guardians`
--

DROP TABLE IF EXISTS `academy_student_guardians`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `academy_student_guardians` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint unsigned NOT NULL,
  `full_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kinship` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `academy_student_guardians_student_id_index` (`student_id`),
  CONSTRAINT `academy_student_guardians_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `profile_students` (`core_person_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `academy_student_guardians`
--

LOCK TABLES `academy_student_guardians` WRITE;
/*!40000 ALTER TABLE `academy_student_guardians` DISABLE KEYS */;
INSERT INTO `academy_student_guardians` VALUES (1,126,'EDILSON','PAPA','900135930','2026-05-14 15:46:08','2026-05-14 15:46:08'),(2,127,'PEDRO','PAPA','941275490','2026-05-14 15:47:30','2026-05-14 15:47:30'),(3,129,'APODERADO','APODERADO','932988078','2026-05-14 16:47:32','2026-05-14 16:47:32'),(4,130,'JUAN','TIO','942945252','2026-05-14 17:04:51','2026-05-14 17:04:51');
/*!40000 ALTER TABLE `academy_student_guardians` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `academy_teacher_attendances`
--

DROP TABLE IF EXISTS `academy_teacher_attendances`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `academy_teacher_attendances` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `teacher_id` bigint unsigned NOT NULL,
  `group_id` bigint unsigned DEFAULT NULL,
  `date` date NOT NULL,
  `check_in` time DEFAULT NULL,
  `check_out` time DEFAULT NULL,
  `check_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `check_type` enum('manual','qr') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'manual',
  `status` enum('present','absent','late','absent_justified','late_justified') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'present',
  `observation` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `academy_teacher_attendances_teacher_id_group_id_date_unique` (`teacher_id`,`group_id`,`date`),
  KEY `academy_teacher_attendances_teacher_id_date_index` (`teacher_id`,`date`),
  KEY `academy_teacher_attendances_teacher_id_index` (`teacher_id`),
  KEY `academy_teacher_attendances_group_id_index` (`group_id`),
  KEY `academy_teacher_attendances_date_index` (`date`),
  KEY `academy_teacher_attendances_check_type_index` (`check_type`),
  KEY `academy_teacher_attendances_check_token_index` (`check_token`),
  KEY `academy_teacher_attendances_status_index` (`status`),
  CONSTRAINT `academy_teacher_attendances_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `academy_groups` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `academy_teacher_attendances_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `profile_teachers` (`core_person_id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `academy_teacher_attendances`
--

LOCK TABLES `academy_teacher_attendances` WRITE;
/*!40000 ALTER TABLE `academy_teacher_attendances` DISABLE KEYS */;
/*!40000 ALTER TABLE `academy_teacher_attendances` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auth_password_resets`
--

DROP TABLE IF EXISTS `auth_password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `auth_password_resets` (
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_password_resets`
--

LOCK TABLES `auth_password_resets` WRITE;
/*!40000 ALTER TABLE `auth_password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `auth_password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auth_sessions`
--

DROP TABLE IF EXISTS `auth_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `auth_sessions` (
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
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_sessions`
--

LOCK TABLES `auth_sessions` WRITE;
/*!40000 ALTER TABLE `auth_sessions` DISABLE KEYS */;
INSERT INTO `auth_sessions` VALUES (37,91,92,'VzMMtVWa0lLemKury47UROIEDUlb6Uut6v7CcGUicf6X6wCnguiGx5mnsH4syyLZ','45.191.99.99','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-12 19:28:41','2026-05-19 19:28:41','2026-05-12 19:28:41','2026-05-12 19:28:41'),(67,101,102,'AjyXqcviaLVcVyqUkQlOEPGXLcZ0tcWzkkAsdi4FT0StNGrPo9GpimVswNMT6POZ','45.191.99.99','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-15 18:42:50','2026-05-22 18:42:50','2026-05-15 18:42:50','2026-05-15 18:42:50'),(71,42,91,'x0SaqdSq32dVjGyCjpHP82jg6129VIWe4ay62r5y0XaIcI4F9KAz9zPpdkxFdjWY','45.191.99.99','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-15 19:20:14','2026-05-22 19:20:14','2026-05-15 19:20:14','2026-05-15 22:17:50'),(72,89,89,'q8HRCE6lDZ7jDGz0310ADcDBeQSSxcNHn9StzTimkT9FBCz0way8LNhkhSnktTOq','38.250.157.59','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-15 21:56:17','2026-05-22 21:56:17','2026-05-15 21:56:17','2026-05-15 22:08:22'),(73,102,103,'H9fH7lAbdxHZEexIy1LQA1sXlUWlBlzdZDSYacnbsfWAs62fEKl2gzkFla4sBBM5','38.250.157.59','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-15 21:56:55','2026-05-22 21:56:55','2026-05-15 21:56:55','2026-05-15 21:56:55'),(75,42,91,'NugqW3xweNCC6wyZlkAZ7B6fo90PSfxJZWOfaeHfx4OqfE7417Amfq51hyvjVDrn','45.191.99.70','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','2026-05-15 22:17:42','2026-05-22 22:17:42','2026-05-15 22:17:42','2026-05-15 22:17:50');
/*!40000 ALTER TABLE `auth_sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auth_users`
--

DROP TABLE IF EXISTS `auth_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `auth_users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=124 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_users`
--

LOCK TABLES `auth_users` WRITE;
/*!40000 ALTER TABLE `auth_users` DISABLE KEYS */;
INSERT INTO `auth_users` VALUES (1,'linox','super@admin.com','$2y$12$eIgLsPURLrE9XbYLsP3KZ.2.7sGLXE17ZRVM04/VTT4gDZTFK2XuK',1,'2026-02-17 02:40:08','2026-05-15 22:17:26','2026-02-17 02:40:08','2026-05-15 22:17:26'),(2,'00000001','admin@test.com','$2y$12$vCODGEASNMuJI.GlnRkpfeBmdTl3DH3qCAbpqLCxYuT8O.vRXelDi',1,NULL,'2026-05-15 19:17:39','2026-02-17 02:41:53','2026-05-15 19:17:39'),(6,'76832299','C@GMAIL.COM','$2y$12$O7vtgFa/FERbAnN.UQJjn.3GbjDH6r98L/f/r5G/KrZspbK.Qb.cK',0,NULL,NULL,'2026-02-20 00:20:41','2026-02-20 00:20:41'),(7,'61893646','C1@GMAIL.COM','$2y$12$2v32e.Lvyk8/HFdBdZtzTeomN1Xohgx2nI5CY.iIap2Wj1EfznQdS',0,NULL,NULL,'2026-02-20 00:23:35','2026-02-20 00:23:35'),(8,'62805432','C2@GMAIL.COM','$2y$12$.CIypFjolrQrOKxHvTBkLezZBa.ZSoQ8Maky.Nps5Mkk2lYQ/7D9G',0,NULL,NULL,'2026-02-20 00:24:45','2026-02-20 00:24:45'),(9,'73820089','C3@GMAIL.COM','$2y$12$PTBMqaIL7GF4lqUQIt4mpewuMBrlY4jcf5dNyCki8DxZIdxGUDxQW',0,NULL,NULL,'2026-02-20 00:25:53','2026-02-20 00:25:53'),(10,'61939362','C4@GMAIL.COM','$2y$12$mDsN4.zKdDnJwdnVrtrE8O174zK62WY0HM0.lQZSfDP4qjTmxyInO',0,NULL,NULL,'2026-02-20 00:26:58','2026-02-20 00:26:58'),(11,'61783700','C5@GMAIL.COM','$2y$12$IK12WY7QrAD4LI7R50btA.kBcKI/4jbGXrA38myYWWYvtnabJsIE.',0,NULL,NULL,'2026-02-20 00:27:53','2026-02-20 00:27:53'),(12,'63017506','C6@GMAIL.COM','$2y$12$2qeAa.EYewO/3Q1hXinGseLlucni.Z5FS9hdcaQ2TdLZvKlUmFUyi',0,NULL,NULL,'2026-02-20 00:28:58','2026-02-20 00:28:58'),(13,'48022417','C7@GMAIL.COM','$2y$12$zrdfWdgPfrPkcah8/F5yTOmVze/rPh7tDwFU4MPpNDfAIl2lzoNTi',0,NULL,NULL,'2026-02-20 00:30:01','2026-02-20 00:30:01'),(14,'61892341','C8@GMAIL.COM','$2y$12$SELnPVunL3yDfbkd/onxruuPtt5m4gHfrlZJ0BUIam81mdyW5IPyW',0,NULL,NULL,'2026-02-20 00:31:13','2026-02-20 00:31:13'),(15,'61321205','C9@GMAIL.COM','$2y$12$A4y8VYuvjcFmA4MUOdHC5eyBPVoJu/.yZZVNCweBYxIwmLQg3L2M.',0,NULL,NULL,'2026-02-20 00:32:07','2026-02-20 00:32:07'),(16,'62528271','10@GMAIL.COM','$2y$12$Ts7pMU5CWRrc.kyFgdDuhexJ6LIamvlPvFk.c0t8iVKglbVyO4d2C',0,NULL,NULL,'2026-02-20 00:33:23','2026-02-20 00:33:23'),(17,'62480235','11@GMAIL.COM','$2y$12$t/U5jez0qq38OqADNYHkZORJWjSxhnI/4g.gnnTvVBM7974jUy21a',0,NULL,NULL,'2026-02-20 00:34:20','2026-02-20 00:34:20'),(18,'45910327','12@GMAIL.COM','$2y$12$hnmq2EGKWmjg2Y8GMDNRgezqhRZGvnjOhpOkRtRI3YFFXDZkR7ODK',0,NULL,NULL,'2026-02-20 00:41:41','2026-02-20 00:41:41'),(19,'12312301','13@GMAIL.COM','$2y$12$RMNPL6Ud0Iesqw2vNgqxiOjCrvnxxI40kO/WD1LAgitQoilW4jkAS',0,NULL,NULL,'2026-02-20 00:48:29','2026-02-20 00:48:29'),(20,'12312302','14@GMAIL.COM','$2y$12$LObMuDJrHfyVTA4XVUqlxu59Mnj9.wOPCv.IfN8c9nT20s8bTOT0e',0,NULL,NULL,'2026-02-20 00:49:28','2026-02-20 00:51:17'),(21,'12312303','16@GMAIL.COM','$2y$12$R/DPpa3tU8H8eu8xPMPrdea/QVlu6nN7bSrPlWjCgiEyZ/rZey/2W',0,NULL,NULL,'2026-02-20 01:04:24','2026-02-20 01:04:24'),(22,'72233424','M1@GMAIL.COM','$2y$12$vvsklgEa6DpTHCJd6yCamepJ9G25RAcu7jh/VW9CCORpmUEDA/CE6',0,NULL,NULL,'2026-02-21 05:31:14','2026-02-21 05:31:14'),(23,'70838415','M2@GMAIL.COM','$2y$12$axVeDLho2Vt8aSXCFOb1a.NETRdh9sAUuBg/cLM7ognkuG.sWpi/K',0,NULL,NULL,'2026-02-21 05:32:19','2026-02-21 05:32:19'),(24,'74310292','M3@GMAIL.COM','$2y$12$aJnBehBnv6L4Rq1NP43wTeqUe0yehMH3v1.jQhU0ePL0hPp2DSqt.',0,NULL,NULL,'2026-02-21 05:43:15','2026-02-21 05:43:29'),(25,'61091109','M4@GMAIL.COM','$2y$12$ZsAZfvUUMeR13AKdnbSdGOgnwrNYOnCHjYZhoV50RQch0.NHquIBG',0,NULL,NULL,'2026-02-21 05:46:50','2026-02-21 05:46:50'),(26,'73651532','M5@GMAIL.COM','$2y$12$7kjOTfjkbmogUZtxB.nAZ.TmKkcc1vQAvhgMDSP0FGRx3gijkuNZW',0,NULL,NULL,'2026-02-21 05:48:24','2026-02-21 05:52:50'),(27,'73541260','M6@GMAIL.COM','$2y$12$.l4qTeAHkaMp07GP5K6CnumJRxbUsWpQckm5el6E89L41eZKd3nEG',0,NULL,NULL,'2026-02-21 05:50:23','2026-02-21 05:50:23'),(28,'61000410','M7@GMIL.COM','$2y$12$BqzIiqh/JEQIHVDLB6yYjuzpDT3mwAaN66dryyaEQAs5JEbAKeUPC',0,NULL,NULL,'2026-02-21 05:55:08','2026-02-21 05:55:08'),(29,'71654064','M8@GMAI.COM','$2y$12$Gho/IAz5Kmxv24Z0uqwE8OAV4otQjNVVBGW7Wwh4z/rqbYxB/ozwu',0,NULL,NULL,'2026-02-21 05:58:16','2026-02-21 05:58:16'),(30,'60665191','M9@GMAIL.COM','$2y$12$sdx6oSSM02db8WPeXqARZ.MZB8zG0f9I5/5nrxgg2LsIC1qjPbI9a',0,NULL,NULL,'2026-02-21 05:59:12','2026-02-21 05:59:12'),(31,'73171929','M10@GMAIL.COM','$2y$12$EXxMQkC2gXslFLTwYGAL5OnOpzFWX52Le0/LXfz/XVm7xZl6vpLsG',0,NULL,NULL,'2026-02-21 06:03:25','2026-02-21 06:03:25'),(32,'62824124','M11@GMAIL.COM','$2y$12$Z/bOuixTXvCh/0EGGd/XZeB3kcsDYDcRKE0C1gDI8bYPXMJpcKb9u',0,NULL,NULL,'2026-02-21 06:05:54','2026-02-21 06:05:54'),(33,'60417095','M12@GMAIL.COM','$2y$12$/aQhs8j5KchiBW9deTo8ZuZtKf1GzYpthNBdyem1MTZazo/AS70D2',0,NULL,NULL,'2026-02-21 06:10:05','2026-02-21 06:10:05'),(34,'60597092','M13@GMAIL.COM','$2y$12$fZ1EBD1pNYnMAbPYv9Dv9OXsiRV5a9lH8fkdXEC4nFg03NYpt.j1W',0,NULL,NULL,'2026-02-21 06:11:30','2026-02-21 06:11:30'),(35,'75899252','M14@GMAIL.COM','$2y$12$fVmdT2yHdvCn6iOF9HgTZ.dAh4Q.O3yfyc1E4otFrmShNJDEgIyl.',0,NULL,NULL,'2026-02-21 06:14:01','2026-02-21 06:14:01'),(36,'62486607','ALFIL.CONTA5@GMAIL.COM','$2y$12$bpjwoCBlf.3rVa3nFVRpb.E9TuHG4TLjy0L8DreM1SHPGBZL1khwW',0,NULL,NULL,'2026-02-21 09:35:01','2026-02-21 09:35:01'),(37,'60908831','R1@GAMIL.COM','$2y$12$D/dKIfLwgXnqV0ZcsG1WceBbi5wEZimpkU7A5GFwtSxvzvx3Toqcm',0,NULL,NULL,'2026-02-21 09:37:51','2026-02-21 09:37:51'),(38,'61906283','R2@GMAIL.COM','$2y$12$snx.x2t7CCwmWUbsZf6Pvupz6PUdktVNXbGCnGqu7pXpLI1hZiyIG',0,NULL,NULL,'2026-02-21 09:38:59','2026-02-21 09:38:59'),(39,'76507044','R3@GMAIL.COM','$2y$12$0zo/rbwgxQTPNck15lqyVOGORlczDgKDBAv6DxcYqh.2RLGI8H8Pq',0,NULL,NULL,'2026-02-21 09:40:25','2026-02-21 09:40:25'),(40,'73384290',NULL,'$2y$12$ZeQ5LtINy.YLx2VJf6mVVeSmSKTENpk6XzF8oGZiSVgbrK1dlAg5m',0,NULL,NULL,'2026-02-23 20:38:29','2026-02-23 22:25:24'),(41,'45650909',NULL,'$2y$12$gAj5DpswZwqsGytcd08oo.hcdXfZ8jzKdxNjY5n6rWj8R9Gs/4qFq',0,NULL,NULL,'2026-02-23 22:28:05','2026-02-23 22:28:05'),(42,'70498731',NULL,'$2y$12$aGIgoDdK9rXp0brdZnm.9eb6bWk53WcoJPA5tYvZZUD.HYwNmM8mq',1,NULL,'2026-05-15 22:17:42','2026-02-23 22:29:33','2026-05-15 22:17:42'),(43,'74037105',NULL,'$2y$12$zYx9zdTXo4bgMDBdvHyw0uXkasIpOkMMvtx3ztPt1gX25BLaljjDC',0,NULL,NULL,'2026-02-23 22:30:32','2026-02-23 22:30:32'),(44,'60744187',NULL,'$2y$12$jli02ZaWK.aht3UXjxHarOwtltCT071LCGgatUZffvcQvDGqZTCj2',0,NULL,NULL,'2026-03-03 04:23:41','2026-03-03 04:23:41'),(45,'74241153',NULL,'$2y$12$Veghb/awgj/qDPz0a7ULhOc8KYTAJWB78Jyn5n.mlmNVbSHQfaKoi',0,NULL,NULL,'2026-03-03 05:10:36','2026-03-03 05:10:36'),(46,'72486595',NULL,'$2y$12$/6eQpNhGnwKUv9tjgrVX8eVl53u.SX7c8Zo7UAEczPIjBFMSepxdm',0,NULL,NULL,'2026-03-03 05:11:32','2026-03-03 05:11:32'),(47,'76959738',NULL,'$2y$12$H7vTSdEVe4q1cK9xPLhNP.9HxcN.eAC6PAE2L9G4uuygG3aBfDmJy',0,NULL,NULL,'2026-03-03 05:12:09','2026-03-03 05:12:09'),(48,'42867198',NULL,'$2y$12$Zlvqs.7Kk6bNnn/8YC/z4u51HVBCoK5AH0mXuaQqB/F.4SYNRHJzC',0,NULL,NULL,'2026-03-03 05:12:57','2026-03-03 05:12:57'),(49,'60523192',NULL,'$2y$12$z59ms77U6RSQiDty.GwztuM0nRjN4/EhPkLXOs1C5DeZc2uaEUtUq',0,NULL,NULL,'2026-03-03 05:14:51','2026-03-03 05:14:51'),(50,'70652863',NULL,'$2y$12$PPDPBucSkWmhfJdFGYad1uZqTIpBrgqZASOqJmk36085mpEh5NBxa',0,NULL,NULL,'2026-03-03 05:22:29','2026-03-03 05:22:29'),(51,'47087692',NULL,'$2y$12$3GQBowgdsUzdITbEKrpqduBwEK7IWEnBLu2Nf4DWPyN4jhiYUsOmG',0,NULL,NULL,'2026-03-03 05:23:03','2026-03-03 05:23:03'),(52,'71706577',NULL,'$2y$12$dxBzgT.hwO00NkYLvSXAJemcT0jHi9/WjnMfL/r9WlPvXlww.Ty9O',0,NULL,NULL,'2026-03-03 05:23:31','2026-03-03 05:23:31'),(53,'77803334',NULL,'$2y$12$pPBVGLGv.NyvXUumTPxDYugSLYpuCWasoH2pbCZTs18q7KTZGKGOq',0,NULL,NULL,'2026-03-03 05:24:23','2026-03-03 05:24:23'),(54,'71987363',NULL,'$2y$12$k4IBFh0HF0PcN2xChBGbJuVRUC0mjzUkeVmQ.nubrH/1upJPwW8v6',0,NULL,NULL,'2026-03-03 05:25:38','2026-03-03 05:25:38'),(55,'61158623',NULL,'$2y$12$s679Ut7Ch.9sS3fOmo65s.zuhIZRsk1fAZMDJH.2/GwyPOoTwvdsa',0,NULL,NULL,'2026-03-03 05:26:18','2026-03-03 05:26:18'),(56,'62417911',NULL,'$2y$12$UDgFqcgCeQ7GcUENOUbuBOiplnbmmdR.GV.mB1F75cb7MyGO1.W3C',0,NULL,NULL,'2026-03-03 05:26:54','2026-03-03 05:26:54'),(57,'72046705',NULL,'$2y$12$v.VzKkcydEutJ1Y8PQXRiO94FhfZ/ZQFDZ1dDFpk3zhTLvj.h1G6u',0,NULL,NULL,'2026-03-03 05:27:56','2026-03-03 05:27:56'),(58,'62562577',NULL,'$2y$12$cX8u0JhkOvuNKXm0IUFz2eBWL9TuNH0CC/KPtEXkhgpGXLRpWE.L.',0,NULL,NULL,'2026-03-03 19:30:47','2026-03-03 19:30:47'),(59,'70898405',NULL,'$2y$12$2aEdWRM0.KRIJefoxGHiuO.u2/AuC/QtTy0Bot8BxpuPCuZ98Cpb2',0,NULL,NULL,'2026-03-03 19:32:40','2026-03-03 19:32:40'),(60,'60069429',NULL,'$2y$12$.j.EDKHsW4ie4TlG4BFxX.SvFjgQHx6GmccXuF61CUMM0JFRJ/Nf.',0,NULL,NULL,'2026-03-03 19:33:57','2026-03-03 19:33:57'),(61,'60376745',NULL,'$2y$12$GnPX31EXa0ItnUxmoM66h.uNRLDUfVsRGzPAi2uDJQr.KbwbKeK26',0,NULL,NULL,'2026-03-04 00:59:38','2026-03-04 00:59:38'),(62,'75841036',NULL,'$2y$12$OlCl/aHoLZQjpNJr9dM04u5CTVxVxEJV./PvPChAVVYrOWLvOt1X6',0,NULL,NULL,'2026-03-04 01:00:27','2026-03-04 01:00:27'),(63,'61651506',NULL,'$2y$12$QBm/UqxLBmrxsrnc09uZWe1Ib4BXqQoeDgEhiVoXogT409iR8cYwi',0,NULL,NULL,'2026-03-04 01:01:32','2026-03-04 01:01:32'),(64,'73637823',NULL,'$2y$12$FzZGvmdYFUZcKAYN8rOMx.Esqm933hlFhbsnO209NjIrBwSIss93O',0,NULL,NULL,'2026-03-04 16:57:04','2026-03-04 16:57:04'),(65,'75736643',NULL,'$2y$12$0lLW.MIAQdaJl/sCHT2/jejsYt6aKg5ttuj6gARtHPztcd7O.Ik8i',0,NULL,NULL,'2026-03-06 20:02:43','2026-03-06 20:02:43'),(66,'75283468',NULL,'$2y$12$RiDlexPS/0g0RX2CB62ZU.PNZ03pv3vgZTmEixFXT3FkEgvuslJ8G',0,NULL,NULL,'2026-03-06 22:44:21','2026-03-06 22:44:21'),(67,'60836645',NULL,'$2y$12$sWjeTY4.sgSbVoKieemZj.Gc26ozjun1W31s3wgWsVJMlbxZFBOCa',0,NULL,NULL,'2026-03-07 21:27:01','2026-03-07 21:27:01'),(68,'73744974',NULL,'$2y$12$wT6Ax/x0U21rUQftBW40AOwIC7RpD8WGELqY9xoz3c5WeehNslt5u',0,NULL,NULL,'2026-03-07 21:27:51','2026-03-07 21:27:51'),(69,'60468990',NULL,'$2y$12$I0NGP4qXrf4tCvHRMY3PaOaVH4mfxZVh7aDdjPoFDK89U4z.bRfMO',0,NULL,NULL,'2026-03-07 22:21:04','2026-03-07 22:21:04'),(70,'74057625',NULL,'$2y$12$kmSO5tcn46wdGJRzuBRHl.2/Y1A85DszmEmgyt9YLEEiZtUxltaDq',0,NULL,NULL,'2026-03-09 17:03:01','2026-03-09 17:03:01'),(71,'75889154',NULL,'$2y$12$owLGwVC53s1DjSEch.tsTugajvf96DEcdFdjqa7vOdQTArlj7rYxq',0,NULL,NULL,'2026-03-09 17:04:12','2026-03-09 17:04:12'),(72,'60212709',NULL,'$2y$12$jXk3G6O1mPwRfFtJJlL59eNFbAN1rNdIZNwzmd6mpkdxXf.vyx3uu',0,NULL,NULL,'2026-03-09 20:50:55','2026-03-09 20:50:55'),(73,'76398347',NULL,'$2y$12$9dKYjiI/RyhvbP/sTdcKXu.WVhJ8Loq6bgWvsZu5/iqcbqo2GHq9C',0,NULL,NULL,'2026-03-09 20:51:34','2026-03-09 20:51:34'),(74,'71552620',NULL,'$2y$12$08yrdgL0anmsf3RT074c7ed5YIsIRKWDe0rJ/sDCKUrqnKnbutkoG',0,NULL,NULL,'2026-03-09 23:50:34','2026-03-09 23:50:34'),(75,'73600475',NULL,'$2y$12$fz4fHGiALk4oKrzSOHiwOO1aPXbiLx4L7QaH8Isf93zL/FARCJkdy',0,NULL,NULL,'2026-03-14 19:55:25','2026-03-14 19:55:25'),(76,'70000000',NULL,'$2y$12$nDw4tJiywSY0UBhYIgEr3O01.LtvLqnY1bQpJ7ajIoMESdrFWEoRK',0,NULL,NULL,'2026-03-14 20:14:54','2026-03-14 20:14:54'),(77,'75540251',NULL,'$2y$12$i7g5nMU/XHtBBoiCOABEbOF1891VDjgNy1aPP63zQt.uMY8TjqSnO',0,NULL,NULL,'2026-03-14 20:15:58','2026-03-14 20:15:58'),(78,'77709611',NULL,'$2y$12$4Lzycx7eoTE8FZZEabXOgOUAyeKSEOeEz1T/Ke3z9wGOas4HgwPFm',0,NULL,NULL,'2026-03-14 20:17:52','2026-03-14 20:17:52'),(79,'73355120',NULL,'$2y$12$ozDIcSWUQHt1j6vEiMqwTe4LxHFzymIiF.KH1s/jYRHjLjvGNd6ny',0,NULL,NULL,'2026-03-14 20:18:46','2026-03-14 20:18:46'),(80,'79463049',NULL,'$2y$12$.EPNY9wB4xBg5xosDxNRt.F4mkaBzSI/cLukWFnFyguJnbW1s7wrm',0,NULL,NULL,'2026-03-14 20:19:37','2026-03-14 20:19:37'),(81,'60279006',NULL,'$2y$12$Y3yf15wrzpxPE5mzfCIkle84F2lIFTSgWcKJeMDlsHf3VSIRl00HK',0,NULL,NULL,'2026-03-14 20:20:57','2026-03-14 20:20:57'),(82,'61156311',NULL,'$2y$12$cZe4XquuVF1X9wK2IpqMyurrnhoFkN6oq9zzz3yYzTQQDd.SZT2.W',0,NULL,NULL,'2026-03-14 20:54:46','2026-03-14 20:54:46'),(83,'60177878',NULL,'$2y$12$hwkdA5MigdOpU9rE214TEeflRpJwY/OngRo5et/8niTaOZxQHGawO',0,NULL,NULL,'2026-03-14 20:56:13','2026-03-14 20:56:13'),(84,'61974539',NULL,'$2y$12$6/9Ahk4ugYxH9aGSoaIUuOxeuok1/anrYynNzNMusSxDQWC0Cytdm',0,NULL,NULL,'2026-03-14 20:58:11','2026-03-14 20:58:11'),(85,'61938479',NULL,'$2y$12$TfBsESZJHQGHElqV8VJ2BuC7PWe82mDAoWSAI9FYVig6BNN5vde1e',0,NULL,NULL,'2026-03-14 20:59:16','2026-03-14 20:59:16'),(86,'62528407',NULL,'$2y$12$y/DjshBJVXp8NCRaBpP5SuTDjZkTr4ar1J69Evex19oVwr9fL5Qia',0,NULL,NULL,'2026-03-14 21:04:04','2026-03-14 21:04:04'),(87,'70000001',NULL,'$2y$12$gTmCi/dJ9zVlkTQkB4oIUeeayLeM..9g8/nv.fkwnJrHxEm0dhmNC',0,NULL,NULL,'2026-03-14 21:06:17','2026-03-14 21:06:17'),(88,'60305344',NULL,'$2y$12$Bwg6Zk0OP5yfmwjEhodaT.Uedm0Sqt798/vhzZTmz/rzS1U4iOlPu',0,NULL,NULL,'2026-03-14 21:07:59','2026-03-14 21:07:59'),(89,'70063570','c@test.com','$2y$12$VtnmGnHBfOr9wwDpwdZ7peMSInqPbMmJXJLBDZHfTJtKqyXjXk9D.',1,NULL,'2026-05-15 21:56:17','2026-05-11 17:15:58','2026-05-15 21:56:17'),(90,'72127899','WC@TEST.COM','$2y$12$1oOwRHFjcZ2DyX/Bg.0CI.y8ATYwT04xEx9YzDTRI5y4jhtHKGx7e',1,NULL,NULL,'2026-05-11 17:17:30','2026-05-11 17:17:30'),(91,'73931954',NULL,'$2y$12$Pqq7JRpG0O/C79qWqiX9KODJDreSSblDj5nvYKm6XspPDkL12vOca',1,NULL,'2026-05-12 19:28:41','2026-05-11 17:22:26','2026-05-12 19:28:41'),(92,'77161559',NULL,'$2y$12$qQslHyrPyz080klL2npU.eoIbtAz/velkPEp4aAcmcq/mLeW04.m.',1,NULL,NULL,'2026-05-11 17:22:51','2026-05-11 17:22:51'),(93,'76634956',NULL,'$2y$12$9iriQP48jfIOkUDeAmr/SuTbJIkhqJkC4nNDKdVYEWWakeC.nYEBq',1,NULL,NULL,'2026-05-11 17:23:16','2026-05-11 17:23:16'),(94,'60837000',NULL,'$2y$12$k64VeXjc3llqRj0bG4o6z.Ezbmg7Bem/s9yzKzkbxws1i0sJXEoCG',1,NULL,NULL,'2026-05-11 17:23:44','2026-05-11 17:23:44'),(95,'74886430',NULL,'$2y$12$TfYsII0kTABojn.4pCVB8Ofehc1Lgh7C5WImWATMfuLdxoYy09.zi',1,NULL,NULL,'2026-05-11 17:24:12','2026-05-11 17:24:12'),(96,'76046262',NULL,'$2y$12$W.N30wWiZbWHYcKXT/qtd.5nmdVujuhzTyRyflMVcse4K0OqoaeVO',1,NULL,NULL,'2026-05-12 17:43:27','2026-05-12 17:43:27'),(97,'61159334',NULL,'$2y$12$/uH8Odqh/AASLSLmd1FDTuMEYAA2qTct7mvwWPIcm.M0/LNdY28Ie',1,NULL,NULL,'2026-05-13 11:21:34','2026-05-13 11:21:34'),(98,'60460460',NULL,'$2y$12$FEGVT6iWt4bwDvAiYws8iOLlVRQ9WbXSfxF/Bc0AFow9vll/LSqea',1,NULL,NULL,'2026-05-13 11:22:21','2026-05-13 11:22:21'),(99,'73312751',NULL,'$2y$12$iV5xDFsPxhO1V4iW4/9LeeuHpet661nN8Dnd8W0bNH.0IpYLavMA2',1,NULL,NULL,'2026-05-13 11:23:27','2026-05-13 11:23:27'),(100,'74880171',NULL,'$2y$12$KgXVS1PtSVPdqXFFA2AifeKRRy.9zBAipb.Q1seTtObeM7cFs7Atu',1,NULL,NULL,'2026-05-13 11:24:43','2026-05-13 11:24:43'),(101,'73350098',NULL,'$2y$12$G5Em1eAUjT34qbxzBhN8sO0/4N0trRSeT4ZKfUL4Nb4gpK4Vrm3lW',1,NULL,'2026-05-15 18:42:50','2026-05-13 11:26:04','2026-05-15 18:42:50'),(102,'61320584',NULL,'$2y$12$t/.6ZbIMK243lVfmG.eYxObg5pknMkVqGPBhW74P93xkhzHL24F2S',1,NULL,'2026-05-15 21:56:55','2026-05-13 11:27:05','2026-05-15 21:56:55'),(103,'60837157',NULL,'$2y$12$ZSegwLFW.q19u3KvR4BJn.f56EGGKEndHhokLLVIlVGzsKoYjTFtC',1,NULL,NULL,'2026-05-13 11:28:24','2026-05-13 11:28:24'),(104,'73712265',NULL,'$2y$12$BDjx.ETSred/AW6Y2s5.VOBq7SWXEk6WOya8ZMTgm2AWEwShTTi6m',1,NULL,NULL,'2026-05-13 11:30:07','2026-05-13 11:30:07'),(105,'60681265',NULL,'$2y$12$Ua9Ni2JeQ30IY/J3j0GNWuLLK2VxOVmG.lpvrLOa1WUsR24LPnURS',1,NULL,NULL,'2026-05-13 15:09:19','2026-05-13 15:09:19'),(106,'74600936',NULL,'$2y$12$LoTGc8yxHFghVNi1a5sapOHmc99myTc6Y8QBC9hFIdY2b4m2B/NfW',1,NULL,NULL,'2026-05-13 15:11:32','2026-05-13 15:11:32'),(107,'76935744',NULL,'$2y$12$Hcn6oV/48u5vt1XdhFBAqOEbYbt.glrlElVbFftxJewpKkaNBZxby',1,NULL,NULL,'2026-05-13 15:12:40','2026-05-13 15:12:40'),(108,'60567804',NULL,'$2y$12$vLhEC5u2zNHCNwrU3oXe4.z6ldxSGgkBmigDl6fj37dQ/cXFLvyYO',1,NULL,NULL,'2026-05-13 15:13:28','2026-05-13 15:13:28'),(109,'74457010',NULL,'$2y$12$LmoTACDjMPdnOKJs9Zn5AetJgg9U2DoVtBkGYo0wZP5uGMeA6bx8e',1,NULL,NULL,'2026-05-13 15:14:47','2026-05-13 15:14:47'),(110,'61466197',NULL,'$2y$12$oy8G8jNI5H0XwnGOMrw39uE7lYVhfa8rtNSsvVSDqhcbpZNsM2eMS',1,NULL,NULL,'2026-05-13 15:15:39','2026-05-13 15:15:39'),(111,'60403758',NULL,'$2y$12$vDQeyRcP0odIgrU8cJ7iWe7XY8iMCu4wSYdGEzRKAP9PJqboMKxPO',1,NULL,NULL,'2026-05-13 15:16:55','2026-05-13 15:16:55'),(112,'60069273',NULL,'$2y$12$yBZXQaYRO/NpA6BR24QfLOs4V5.7G.zmqDTkwyHafAW241Uzs2Vci',1,NULL,NULL,'2026-05-13 15:18:11','2026-05-13 15:18:11'),(113,'75403310',NULL,'$2y$12$lnILpDJEg4gSU2muoIecc.Z/hd6Cxt3mi3rz28STnkpRSveUJ7Cda',1,NULL,NULL,'2026-05-13 15:21:25','2026-05-13 15:21:25'),(114,'60066232',NULL,'$2y$12$GXQfGi.Bew9GixHTU.KaY.hJA5nqo5cOIj9KZoKAyOGaoBa35/6RW',1,NULL,NULL,'2026-05-13 15:22:29','2026-05-13 15:22:29'),(115,'60853472',NULL,'$2y$12$UNSgL1QfZHD7r85ot0bYi.PLxg2LbDcR1CumwbZl29AJjAlB9wOqy',1,NULL,NULL,'2026-05-13 15:24:31','2026-05-13 15:24:31'),(116,'62623990',NULL,'$2y$12$1Gs9Qfzd.wIBxiJq8vulIeeH1yl3g3XpmbCB24YubmVWw8bRrPPBG',1,NULL,NULL,'2026-05-13 15:45:10','2026-05-13 15:45:10'),(117,'60658879',NULL,'$2y$12$yZBrLFSrEVcAp.BDKVGTwO1h94/dQk3rbPcoRoQhy1vLA/DP34PTW',1,NULL,NULL,'2026-05-13 17:23:35','2026-05-13 17:23:35'),(118,'61537226',NULL,'$2y$12$Zh0vEIxO/dpGgvrNvzQNNebr9rq.RBFxHB7hVT5NWisIJMtFnkfJC',1,NULL,NULL,'2026-05-14 15:46:08','2026-05-14 15:46:08'),(119,'61881330',NULL,'$2y$12$nZVblwbt.YGKMjdBmPMEMufw.yK6wKxUaC4Pi2oFlUFVWN80VnZu6',1,NULL,NULL,'2026-05-14 15:47:04','2026-05-14 15:47:04'),(120,'60180601',NULL,'$2y$12$WErTPZQLIhnuYBkmZKtJgODAmN5baLMrdVambfDytVic3RvWsQSmS',1,NULL,NULL,'2026-05-14 15:50:15','2026-05-14 15:50:15'),(121,'44912726',NULL,'$2y$12$SstpsQFuXjHDnY33IkO9SuM3uTkF/2/fyv8TSG4Pf15VYc.iOrhce',1,NULL,NULL,'2026-05-14 16:47:32','2026-05-14 16:47:32'),(122,'77238836',NULL,'$2y$12$ejPjMru0u3K/BKWbsBxmYecQZHGct7v0iRbDrusTWSAytpLAbICyG',1,NULL,NULL,'2026-05-14 17:04:51','2026-05-14 17:04:51'),(123,'61046952',NULL,'$2y$12$y9RxbykTVls4.msEU/odr.19QGJ7dsLy59M2VqilCQbM0I3f4afla',1,NULL,NULL,'2026-05-15 17:45:24','2026-05-15 17:45:24');
/*!40000 ALTER TABLE `auth_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `barber_attendances`
--

DROP TABLE IF EXISTS `barber_attendances`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `barber_attendances` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `branch_id` bigint unsigned NOT NULL,
  `barber_id` bigint unsigned NOT NULL,
  `date` date NOT NULL,
  `check_in` time DEFAULT NULL,
  `check_out` time DEFAULT NULL,
  `check_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `check_type` enum('manual','qr') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'manual',
  `status` enum('present','absent','late','absent_justified','late_justified') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'present',
  `observation` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `barber_attendances_barber_id_date_unique` (`barber_id`,`date`),
  KEY `barber_attendances_barber_id_date_index` (`barber_id`,`date`),
  KEY `barber_attendances_branch_id_index` (`branch_id`),
  KEY `barber_attendances_barber_id_index` (`barber_id`),
  KEY `barber_attendances_date_index` (`date`),
  KEY `barber_attendances_check_type_index` (`check_type`),
  KEY `barber_attendances_check_token_index` (`check_token`),
  KEY `barber_attendances_status_index` (`status`),
  CONSTRAINT `barber_attendances_barber_id_foreign` FOREIGN KEY (`barber_id`) REFERENCES `profile_barbers` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `barber_attendances_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `barbershop_branches` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `barber_attendances`
--

LOCK TABLES `barber_attendances` WRITE;
/*!40000 ALTER TABLE `barber_attendances` DISABLE KEYS */;
INSERT INTO `barber_attendances` VALUES (1,1,69,'2026-05-16','19:31:16','22:02:37',NULL,'manual','late',NULL,'2026-05-15 19:31:16','2026-05-15 22:02:37');
/*!40000 ALTER TABLE `barber_attendances` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `barbershop_branches`
--

DROP TABLE IF EXISTS `barbershop_branches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `barbershop_branches` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ubication` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_lat` decimal(10,8) DEFAULT NULL,
  `location_lng` decimal(11,8) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `barbershop_branches_name_index` (`name`),
  KEY `barbershop_branches_is_active_index` (`is_active`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `barbershop_branches`
--

LOCK TABLES `barbershop_branches` WRITE;
/*!40000 ALTER TABLE `barbershop_branches` DISABLE KEYS */;
INSERT INTO `barbershop_branches` VALUES (1,'BÁRBAROS CLUB','JR. UNIÓN #209',NULL,NULL,NULL,NULL,NULL,1,'2026-03-05 23:58:20','2026-03-05 23:59:20'),(2,'OLIMPO','JR. BOLIVAR #113',NULL,NULL,NULL,NULL,NULL,1,'2026-03-05 23:59:10','2026-03-05 23:59:10'),(3,'MONALISA','JR. UNIÓN',NULL,NULL,NULL,NULL,NULL,1,'2026-03-06 00:00:52','2026-03-06 00:00:52'),(4,'JEQUE','JR. BOLIVAR #306',NULL,NULL,NULL,NULL,NULL,1,'2026-03-06 00:01:48','2026-03-06 00:01:48');
/*!40000 ALTER TABLE `barbershop_branches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `barbershop_categories`
--

DROP TABLE IF EXISTS `barbershop_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `barbershop_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `barbershop_categories_name_index` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `barbershop_categories`
--

LOCK TABLES `barbershop_categories` WRITE;
/*!40000 ALTER TABLE `barbershop_categories` DISABLE KEYS */;
INSERT INTO `barbershop_categories` VALUES (1,'CABELLO',NULL,1,'2026-03-06 00:05:57','2026-03-06 00:05:57'),(2,'CUIDADO DE LA PIEL',NULL,1,'2026-03-06 00:08:57','2026-03-06 00:08:57'),(3,'ESPECIALIDADES',NULL,1,'2026-03-06 00:09:41','2026-03-06 00:09:41'),(4,'ESMALTE EN GEL',NULL,1,'2026-03-06 00:11:30','2026-03-06 00:11:30'),(5,'ACRÍLICAS',NULL,1,'2026-03-06 00:11:50','2026-03-06 00:11:50'),(6,'POLIGEL',NULL,1,'2026-03-06 00:11:59','2026-03-06 00:11:59');
/*!40000 ALTER TABLE `barbershop_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `barbershop_reservations`
--

DROP TABLE IF EXISTS `barbershop_reservations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `barbershop_reservations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `branch_id` bigint unsigned NOT NULL,
  `profile_client_id` bigint unsigned NOT NULL,
  `service_id` bigint unsigned DEFAULT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `status` enum('pending','confirmed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `barbershop_reservations_service_id_foreign` (`service_id`),
  KEY `barbershop_reservations_profile_client_id_foreign` (`profile_client_id`),
  KEY `barbershop_reservations_branch_id_foreign` (`branch_id`),
  CONSTRAINT `barbershop_reservations_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `barbershop_branches` (`id`) ON DELETE CASCADE,
  CONSTRAINT `barbershop_reservations_profile_client_id_foreign` FOREIGN KEY (`profile_client_id`) REFERENCES `profile_clients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `barbershop_reservations_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `barbershop_services` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `barbershop_reservations`
--

LOCK TABLES `barbershop_reservations` WRITE;
/*!40000 ALTER TABLE `barbershop_reservations` DISABLE KEYS */;
/*!40000 ALTER TABLE `barbershop_reservations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `barbershop_services`
--

DROP TABLE IF EXISTS `barbershop_services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `barbershop_services` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` bigint unsigned NOT NULL,
  `branch_id` bigint unsigned NOT NULL,
  `price` int NOT NULL DEFAULT '0',
  `duration` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `barbershop_services_category_id_foreign` (`category_id`),
  KEY `barbershop_services_name_index` (`name`),
  KEY `barbershop_services_branch_id_index` (`branch_id`),
  CONSTRAINT `barbershop_services_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `barbershop_branches` (`id`) ON DELETE CASCADE,
  CONSTRAINT `barbershop_services_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `barbershop_categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `barbershop_services`
--

LOCK TABLES `barbershop_services` WRITE;
/*!40000 ALTER TABLE `barbershop_services` DISABLE KEYS */;
INSERT INTO `barbershop_services` VALUES (1,'CORTE DEGRADADO',NULL,1,1,40,45,1,'2026-05-12 19:35:49','2026-05-12 19:35:49'),(2,'CORTE CLASICO',NULL,1,1,40,45,1,'2026-05-13 15:48:03','2026-05-13 15:48:03'),(3,'CORTE MÁS AÑADIDO',NULL,1,1,15,20,1,'2026-05-13 15:48:46','2026-05-13 15:50:09'),(4,'ONDULACIÓN',NULL,1,1,90,60,1,'2026-05-13 15:57:32','2026-05-13 15:57:32');
/*!40000 ALTER TABLE `barbershop_services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `barbershop_ticket_services`
--

DROP TABLE IF EXISTS `barbershop_ticket_services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `barbershop_ticket_services` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ticket_id` bigint unsigned NOT NULL,
  `service_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `discount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `barbershop_ticket_services_ticket_id_foreign` (`ticket_id`),
  KEY `barbershop_ticket_services_service_id_foreign` (`service_id`),
  CONSTRAINT `barbershop_ticket_services_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `barbershop_services` (`id`) ON DELETE CASCADE,
  CONSTRAINT `barbershop_ticket_services_ticket_id_foreign` FOREIGN KEY (`ticket_id`) REFERENCES `barbershop_tickets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `barbershop_ticket_services`
--

LOCK TABLES `barbershop_ticket_services` WRITE;
/*!40000 ALTER TABLE `barbershop_ticket_services` DISABLE KEYS */;
INSERT INTO `barbershop_ticket_services` VALUES (1,1,1,1,40.00,0.00,'2026-05-12 19:36:18','2026-05-12 19:36:18'),(3,2,1,1,40.00,0.00,'2026-05-15 19:10:18','2026-05-15 19:10:18'),(5,4,2,1,40.00,0.00,'2026-05-15 19:12:09','2026-05-15 19:12:09'),(6,5,1,1,40.00,0.00,'2026-05-15 19:12:36','2026-05-15 19:12:36'),(7,6,1,1,40.00,0.00,'2026-05-15 19:13:13','2026-05-15 19:13:13'),(8,7,2,1,40.00,0.00,'2026-05-15 19:13:35','2026-05-15 19:13:35'),(9,8,1,1,40.00,0.00,'2026-05-15 22:06:58','2026-05-15 22:06:58');
/*!40000 ALTER TABLE `barbershop_ticket_services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `barbershop_tickets`
--

DROP TABLE IF EXISTS `barbershop_tickets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `barbershop_tickets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `branch_id` bigint unsigned NOT NULL,
  `reservation_id` bigint unsigned DEFAULT NULL,
  `cash_session_id` bigint unsigned DEFAULT NULL,
  `profile_barber_id` bigint unsigned DEFAULT NULL,
  `profile_client_id` bigint unsigned DEFAULT NULL,
  `auth_user_id` bigint unsigned DEFAULT NULL,
  `ticket_number` int unsigned DEFAULT NULL,
  `amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `discount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total` decimal(12,2) NOT NULL DEFAULT '0.00',
  `ticket_date` timestamp NOT NULL DEFAULT '2026-05-11 14:54:07',
  `status` enum('pending','confirmed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `barbershop_tickets_cash_session_id_ticket_number_unique` (`cash_session_id`,`ticket_number`),
  KEY `barbershop_tickets_reservation_id_foreign` (`reservation_id`),
  KEY `barbershop_tickets_branch_id_foreign` (`branch_id`),
  KEY `barbershop_tickets_auth_user_id_foreign` (`auth_user_id`),
  KEY `barbershop_tickets_cash_session_id_index` (`cash_session_id`),
  KEY `barbershop_tickets_profile_barber_id_index` (`profile_barber_id`),
  KEY `barbershop_tickets_profile_client_id_index` (`profile_client_id`),
  KEY `barbershop_tickets_status_index` (`status`),
  CONSTRAINT `barbershop_tickets_auth_user_id_foreign` FOREIGN KEY (`auth_user_id`) REFERENCES `auth_users` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `barbershop_tickets_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `barbershop_branches` (`id`) ON DELETE CASCADE,
  CONSTRAINT `barbershop_tickets_cash_session_id_foreign` FOREIGN KEY (`cash_session_id`) REFERENCES `treasury_cash_sessions` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `barbershop_tickets_profile_barber_id_foreign` FOREIGN KEY (`profile_barber_id`) REFERENCES `profile_barbers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `barbershop_tickets_profile_client_id_foreign` FOREIGN KEY (`profile_client_id`) REFERENCES `profile_clients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `barbershop_tickets_reservation_id_foreign` FOREIGN KEY (`reservation_id`) REFERENCES `barbershop_reservations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `barbershop_tickets`
--

LOCK TABLES `barbershop_tickets` WRITE;
/*!40000 ALTER TABLE `barbershop_tickets` DISABLE KEYS */;
INSERT INTO `barbershop_tickets` VALUES (1,1,NULL,1,69,NULL,91,1,40.00,0.00,40.00,'2026-05-12 19:36:18','pending','2026-05-12 19:36:18','2026-05-12 19:36:18'),(2,1,NULL,2,77,NULL,101,1,40.00,0.00,40.00,'2026-05-15 19:10:18','confirmed','2026-05-15 18:44:48','2026-05-15 19:10:18'),(4,1,NULL,2,43,NULL,89,2,40.00,0.00,40.00,'2026-05-15 19:12:09','confirmed','2026-05-15 19:12:09','2026-05-15 19:12:09'),(5,1,NULL,2,68,NULL,89,3,40.00,0.00,40.00,'2026-05-15 19:12:36','confirmed','2026-05-15 19:12:36','2026-05-15 19:12:36'),(6,1,NULL,2,69,NULL,89,4,40.00,0.00,40.00,'2026-05-15 19:13:13','confirmed','2026-05-15 19:13:13','2026-05-15 19:13:13'),(7,1,NULL,2,74,NULL,89,5,40.00,0.00,40.00,'2026-05-15 19:13:35','confirmed','2026-05-15 19:13:35','2026-05-15 19:13:35'),(8,1,NULL,2,74,NULL,102,6,40.00,0.00,40.00,'2026-05-15 22:06:58','confirmed','2026-05-15 22:06:58','2026-05-15 22:06:58');
/*!40000 ALTER TABLE `barbershop_tickets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `behavior_permissions`
--

DROP TABLE IF EXISTS `behavior_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `behavior_permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('module','menu','view','action','feature') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'feature',
  `parent_id` bigint unsigned DEFAULT NULL,
  `level` enum('0','1','2','3','4') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `behavior_permissions_name_unique` (`name`),
  KEY `behavior_permissions_name_index` (`name`),
  KEY `behavior_permissions_type_index` (`type`),
  KEY `behavior_permissions_parent_id_index` (`parent_id`),
  KEY `behavior_permissions_level_index` (`level`),
  CONSTRAINT `behavior_permissions_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `behavior_permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=251 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `behavior_permissions`
--

LOCK TABLES `behavior_permissions` WRITE;
/*!40000 ALTER TABLE `behavior_permissions` DISABLE KEYS */;
INSERT INTO `behavior_permissions` VALUES (180,'academy_panel','Panel Academia','feature',NULL,'1','2026-05-11 14:54:26','2026-05-11 14:54:26'),(181,'academy_panel.dashboard','Dashboard','module',180,'1','2026-05-11 14:54:26','2026-05-11 14:54:26'),(182,'academy_panel.dashboard.general','Dashboard Academia','view',181,'1','2026-05-11 14:54:26','2026-05-11 14:54:26'),(183,'academy_panel.dashboard.finance','Dashboard Financiero','view',181,'1','2026-05-11 14:54:26','2026-05-11 14:54:26'),(184,'academy_panel.group','Grupos','module',180,'1','2026-05-11 14:54:26','2026-05-11 14:54:26'),(185,'academy_panel.group.view','Grupos','view',184,'1','2026-05-11 14:54:26','2026-05-11 14:54:26'),(186,'academy_panel.students','Estudiantes','module',180,'1','2026-05-11 14:54:26','2026-05-11 14:54:26'),(187,'academy_panel.students.db','DB Estudiantes','view',186,'1','2026-05-11 14:54:26','2026-05-11 14:54:26'),(188,'academy_panel.students.enrollment','Inscripciones','view',186,'1','2026-05-11 14:54:26','2026-05-11 14:54:26'),(189,'academy_panel.students.enrollment.change_group','Cambio de Grupo','action',188,'1','2026-05-11 14:54:26','2026-05-11 14:54:26'),(190,'academy_panel.students.attendance','Asistencia Estudiantes','view',186,'1','2026-05-11 14:54:26','2026-05-11 14:54:26'),(191,'academy_panel.teachers','Docentes','module',180,'1','2026-05-11 14:54:26','2026-05-11 14:54:26'),(192,'academy_panel.teachers.db','DB Docentes','view',191,'1','2026-05-11 14:54:26','2026-05-11 14:54:26'),(193,'academy_panel.teachers.attendance','Asistencia Docentes','view',191,'1','2026-05-11 14:54:26','2026-05-11 14:54:26'),(194,'academy_panel.workers','Trabajadores','module',180,'1','2026-05-11 14:54:26','2026-05-11 14:54:26'),(195,'academy_panel.workers.db','DB Trabajadores','view',194,'1','2026-05-11 14:54:26','2026-05-11 14:54:26'),(196,'academy_panel.workers.attendance','Asistencia Trabajadores','view',194,'1','2026-05-11 14:54:26','2026-05-11 14:54:26'),(197,'academy_panel.reception','Recepción','module',180,'1','2026-05-11 14:54:26','2026-05-11 14:54:26'),(198,'academy_panel.reception.cash_session','Apertura de Caja','view',197,'1','2026-05-11 14:54:26','2026-05-11 14:54:26'),(199,'academy_panel.reception.pos','Punto de Venta','view',197,'1','2026-05-11 14:54:26','2026-05-11 14:54:26'),(200,'academy_panel.inventory','Inventario','module',180,'1','2026-05-11 14:54:26','2026-05-11 14:54:26'),(201,'academy_panel.inventory.stock','Stock','view',200,'1','2026-05-11 14:54:26','2026-05-11 14:54:26'),(202,'academy_panel.inventory.purchase_order','Órdenes de Compra','view',200,'1','2026-05-11 14:54:26','2026-05-11 14:54:26'),(203,'academy_panel.finance','Finanzas','module',180,'1','2026-05-11 14:54:26','2026-05-11 14:54:26'),(204,'academy_panel.finance.income','Ingresos','view',203,'1','2026-05-11 14:54:26','2026-05-11 14:54:26'),(205,'academy_panel.finance.general_expense','Gastos Generales','view',203,'1','2026-05-11 14:54:26','2026-05-11 14:54:26'),(206,'academy_panel.finance.teacher_payment','Pagos Docentes','view',203,'1','2026-05-11 14:54:26','2026-05-11 14:54:26'),(207,'academy_panel.finance.teacher_advance','Adelantos Docentes','view',203,'1','2026-05-11 14:54:26','2026-05-11 14:54:26'),(208,'academy_panel.finance.worker_payment','Pagos Trabajadores','view',203,'1','2026-05-11 14:54:26','2026-05-11 14:54:26'),(209,'academy_panel.finance.worker_advance','Adelantos Trabajadores','view',203,'1','2026-05-11 14:54:26','2026-05-11 14:54:26'),(210,'academy_panel.report','Reportes','module',180,'1','2026-05-11 14:54:26','2026-05-11 14:54:26'),(211,'academy_panel.report.view','Reportes Academia','view',210,'1','2026-05-11 14:54:26','2026-05-11 14:54:26'),(212,'academy_panel.config','Configuración','module',180,'1','2026-05-11 14:54:26','2026-05-11 14:54:26'),(213,'academy_panel.config.room','Aulas','view',212,'1','2026-05-11 14:54:26','2026-05-11 14:54:26'),(214,'academy_panel.config.schedule','Horarios','view',212,'1','2026-05-11 14:54:26','2026-05-11 14:54:26'),(215,'academy_panel.config.level','Niveles','view',212,'1','2026-05-11 14:54:26','2026-05-11 14:54:26'),(216,'academy_panel.config.material','Materiales','view',212,'1','2026-05-11 14:54:26','2026-05-11 14:54:26'),(217,'academy_panel.config.supplier','Proveedores','view',212,'1','2026-05-11 14:54:26','2026-05-11 14:54:26'),(218,'academy_panel.config.product','Productos','view',212,'1','2026-05-11 14:54:26','2026-05-11 14:54:26'),(219,'barbershop_panel','Panel Barbería','feature',NULL,'1','2026-05-11 14:54:26','2026-05-11 14:54:26'),(220,'barbershop_panel.dashboard','Dashboard','module',219,'1','2026-05-11 14:54:26','2026-05-11 14:54:26'),(221,'barbershop_panel.dashboard.general','Dashboard Barbería','view',220,'1','2026-05-11 14:54:26','2026-05-11 14:54:26'),(222,'barbershop_panel.dashboard.finance','Dashboard Financiero','view',220,'1','2026-05-11 14:54:26','2026-05-11 14:54:26'),(223,'barbershop_panel.reception','Recepción','module',219,'1','2026-05-11 14:54:26','2026-05-11 14:54:26'),(224,'barbershop_panel.reception.cash_session','Apertura de Caja','view',223,'1','2026-05-11 14:54:26','2026-05-11 14:54:26'),(225,'barbershop_panel.reception.pos','Punto de Venta','view',223,'1','2026-05-11 14:54:26','2026-05-11 14:54:26'),(226,'barbershop_panel.reception.ticket','Tickets','view',223,'1','2026-05-11 14:54:26','2026-05-11 14:54:26'),(227,'barbershop_panel.persons','Personas','module',219,'1','2026-05-11 14:54:26','2026-05-11 14:54:26'),(228,'barbershop_panel.persons.barber','Barberos','view',227,'1','2026-05-11 14:54:26','2026-05-11 14:54:26'),(229,'barbershop_panel.persons.worker','Trabajadores','view',227,'1','2026-05-11 14:54:26','2026-05-11 14:54:26'),(230,'barbershop_panel.persons.client','Clientes','view',227,'1','2026-05-11 14:54:26','2026-05-11 14:54:26'),(231,'barbershop_panel.attendance','Asistencias','module',219,'1','2026-05-11 14:54:26','2026-05-11 14:54:26'),(232,'barbershop_panel.attendance.barber','Asistencia Barberos','view',231,'1','2026-05-11 14:54:26','2026-05-11 14:54:26'),(233,'barbershop_panel.attendance.worker','Asistencia Trabajadores','view',231,'1','2026-05-11 14:54:26','2026-05-11 14:54:26'),(234,'barbershop_panel.config','Configuración','module',219,'1','2026-05-11 14:54:26','2026-05-11 14:54:26'),(235,'barbershop_panel.config.category','Categorías','view',234,'1','2026-05-11 14:54:26','2026-05-11 14:54:26'),(236,'barbershop_panel.config.service','Servicios','view',234,'1','2026-05-11 14:54:26','2026-05-11 14:54:26'),(237,'barbershop_panel.inventory','Inventario','module',219,'1','2026-05-11 14:54:26','2026-05-11 14:54:26'),(238,'barbershop_panel.inventory.supplier','Proveedores','view',237,'1','2026-05-11 14:54:26','2026-05-11 14:54:26'),(239,'barbershop_panel.inventory.product','Productos','view',237,'1','2026-05-11 14:54:26','2026-05-11 14:54:26'),(240,'barbershop_panel.inventory.stock','Stock','view',237,'1','2026-05-11 14:54:26','2026-05-11 14:54:26'),(241,'barbershop_panel.inventory.purchase_order','Órdenes de Compra','view',237,'1','2026-05-11 14:54:26','2026-05-11 14:54:26'),(242,'barbershop_panel.report','Reportes','module',219,'1','2026-05-11 14:54:26','2026-05-11 14:54:26'),(243,'barbershop_panel.report.view','Ver Reportes','view',242,'1','2026-05-11 14:54:26','2026-05-11 14:54:26'),(244,'barbershop_panel.finance','Finanzas','module',219,'1','2026-05-11 14:54:26','2026-05-11 14:54:26'),(245,'barbershop_panel.finance.income','Ingresos','view',244,'1','2026-05-11 14:54:26','2026-05-11 14:54:26'),(246,'barbershop_panel.finance.general_expense','Gastos Generales','view',244,'1','2026-05-11 14:54:26','2026-05-11 14:54:26'),(247,'barbershop_panel.finance.barber_advance','Adelantos Barberos','view',244,'1','2026-05-11 14:54:26','2026-05-11 14:54:26'),(248,'barbershop_panel.finance.barber_payment','Pagos Barberos','view',244,'1','2026-05-11 14:54:26','2026-05-11 14:54:26'),(249,'barbershop_panel.finance.worker_advance','Adelantos Trabajadores','view',244,'1','2026-05-11 14:54:26','2026-05-11 14:54:26'),(250,'barbershop_panel.finance.worker_payment','Pagos Trabajadores','view',244,'1','2026-05-11 14:54:26','2026-05-11 14:54:26');
/*!40000 ALTER TABLE `behavior_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `behavior_profiles`
--

DROP TABLE IF EXISTS `behavior_profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `behavior_profiles` (
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
) ENGINE=InnoDB AUTO_INCREMENT=127 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `behavior_profiles`
--

LOCK TABLES `behavior_profiles` WRITE;
/*!40000 ALTER TABLE `behavior_profiles` DISABLE KEYS */;
INSERT INTO `behavior_profiles` VALUES (1,1,'profile_admins',1,1,1,'2026-02-17 02:40:08','2026-02-17 02:40:08'),(2,2,'profile_admins',2,3,1,'2026-02-17 02:41:53','2026-02-17 02:41:53'),(6,6,'profile_students',6,2,1,'2026-02-20 00:20:41','2026-02-20 00:20:41'),(7,7,'profile_students',7,2,1,'2026-02-20 00:23:35','2026-02-20 00:23:35'),(8,8,'profile_students',8,2,1,'2026-02-20 00:24:45','2026-02-20 00:24:45'),(9,9,'profile_students',9,2,1,'2026-02-20 00:25:53','2026-02-20 00:25:53'),(10,10,'profile_students',10,2,1,'2026-02-20 00:26:58','2026-02-20 00:26:58'),(11,11,'profile_students',11,2,1,'2026-02-20 00:27:53','2026-02-20 00:27:53'),(12,12,'profile_students',12,2,1,'2026-02-20 00:28:58','2026-02-20 00:28:58'),(13,13,'profile_students',13,2,1,'2026-02-20 00:30:01','2026-02-20 00:30:01'),(14,14,'profile_students',14,2,1,'2026-02-20 00:31:13','2026-02-20 00:31:13'),(15,15,'profile_students',15,2,1,'2026-02-20 00:32:07','2026-02-20 00:32:07'),(16,16,'profile_students',16,2,1,'2026-02-20 00:33:23','2026-02-20 00:33:23'),(17,17,'profile_students',17,2,1,'2026-02-20 00:34:20','2026-02-20 00:34:20'),(18,18,'profile_students',18,2,1,'2026-02-20 00:41:41','2026-02-20 00:41:41'),(19,19,'profile_students',19,2,1,'2026-02-20 00:48:29','2026-02-20 00:48:29'),(20,20,'profile_students',20,2,1,'2026-02-20 00:49:28','2026-02-20 00:49:28'),(21,21,'profile_students',21,2,1,'2026-02-20 01:04:24','2026-02-20 01:04:24'),(22,22,'profile_students',22,2,1,'2026-02-21 05:31:14','2026-02-21 05:31:14'),(23,23,'profile_students',23,2,1,'2026-02-21 05:32:19','2026-02-21 05:32:19'),(24,24,'profile_students',24,2,1,'2026-02-21 05:43:15','2026-02-21 05:43:15'),(25,25,'profile_students',25,2,1,'2026-02-21 05:46:50','2026-02-21 05:46:50'),(26,26,'profile_students',26,2,1,'2026-02-21 05:48:24','2026-02-21 05:48:24'),(27,27,'profile_students',27,2,1,'2026-02-21 05:50:23','2026-02-21 05:50:23'),(28,28,'profile_students',28,2,1,'2026-02-21 05:55:08','2026-02-21 05:55:08'),(29,29,'profile_students',29,2,1,'2026-02-21 05:58:16','2026-02-21 05:58:16'),(30,30,'profile_students',30,2,1,'2026-02-21 05:59:12','2026-02-21 05:59:12'),(31,31,'profile_students',31,2,1,'2026-02-21 06:03:25','2026-02-21 06:03:25'),(32,32,'profile_students',32,2,1,'2026-02-21 06:05:54','2026-02-21 06:05:54'),(33,33,'profile_students',33,2,1,'2026-02-21 06:10:05','2026-02-21 06:10:05'),(34,34,'profile_students',34,2,1,'2026-02-21 06:11:30','2026-02-21 06:11:30'),(35,35,'profile_students',35,2,1,'2026-02-21 06:14:01','2026-02-21 06:14:01'),(36,36,'profile_students',36,2,1,'2026-02-21 09:35:01','2026-02-21 09:35:01'),(37,37,'profile_students',37,2,1,'2026-02-21 09:37:51','2026-02-21 09:37:51'),(38,38,'profile_students',38,2,1,'2026-02-21 09:38:59','2026-02-21 09:38:59'),(39,39,'profile_students',39,2,1,'2026-02-21 09:40:25','2026-02-21 09:40:25'),(40,40,'profile_teachers',40,4,1,'2026-02-23 20:38:29','2026-02-23 20:38:29'),(41,41,'profile_teachers',41,4,1,'2026-02-23 22:28:05','2026-02-23 22:28:05'),(43,43,'profile_teachers',43,4,1,'2026-02-23 22:30:32','2026-02-23 22:30:32'),(44,44,'profile_students',44,2,1,'2026-03-03 04:23:41','2026-03-03 04:23:41'),(45,45,'profile_students',45,2,1,'2026-03-03 05:10:36','2026-03-03 05:10:36'),(46,46,'profile_students',46,2,1,'2026-03-03 05:11:32','2026-03-03 05:11:32'),(47,47,'profile_students',47,2,1,'2026-03-03 05:12:09','2026-03-03 05:12:09'),(48,48,'profile_students',48,2,1,'2026-03-03 05:12:57','2026-03-03 05:12:57'),(49,49,'profile_students',49,2,1,'2026-03-03 05:14:51','2026-03-03 05:14:51'),(50,50,'profile_students',50,2,1,'2026-03-03 05:22:29','2026-03-03 05:22:29'),(51,51,'profile_students',51,2,1,'2026-03-03 05:23:03','2026-03-03 05:23:03'),(52,52,'profile_students',52,2,1,'2026-03-03 05:23:31','2026-03-03 05:23:31'),(53,53,'profile_students',53,2,1,'2026-03-03 05:24:23','2026-03-03 05:24:23'),(54,54,'profile_students',54,2,1,'2026-03-03 05:25:38','2026-03-03 05:25:38'),(55,55,'profile_students',55,2,1,'2026-03-03 05:26:18','2026-03-03 05:26:18'),(56,56,'profile_students',56,2,1,'2026-03-03 05:26:54','2026-03-03 05:26:54'),(57,57,'profile_students',57,2,1,'2026-03-03 05:27:56','2026-03-03 05:27:56'),(58,58,'profile_students',58,2,1,'2026-03-03 19:30:47','2026-03-03 19:30:47'),(59,59,'profile_students',59,2,1,'2026-03-03 19:32:40','2026-03-03 19:32:40'),(60,60,'profile_students',60,2,1,'2026-03-03 19:33:57','2026-03-03 19:33:57'),(61,61,'profile_students',61,2,1,'2026-03-04 00:59:38','2026-03-04 00:59:38'),(62,62,'profile_students',62,2,1,'2026-03-04 01:00:27','2026-03-04 01:00:27'),(63,63,'profile_students',63,2,1,'2026-03-04 01:01:32','2026-03-04 01:01:32'),(64,64,'profile_students',64,2,1,'2026-03-04 16:57:04','2026-03-04 16:57:04'),(65,65,'profile_students',85,2,1,'2026-03-06 20:02:43','2026-03-06 20:02:43'),(66,66,'profile_students',86,2,1,'2026-03-06 22:44:21','2026-03-06 22:44:21'),(67,67,'profile_students',87,2,1,'2026-03-07 21:27:01','2026-03-07 21:27:01'),(68,68,'profile_students',88,2,1,'2026-03-07 21:27:51','2026-03-07 21:27:51'),(69,69,'profile_students',89,2,1,'2026-03-07 22:21:04','2026-03-07 22:21:04'),(70,70,'profile_students',90,2,1,'2026-03-09 17:03:01','2026-03-09 17:03:01'),(71,71,'profile_students',91,2,1,'2026-03-09 17:04:12','2026-03-09 17:04:12'),(72,72,'profile_students',92,2,1,'2026-03-09 20:50:55','2026-03-09 20:50:55'),(73,73,'profile_students',93,2,1,'2026-03-09 20:51:34','2026-03-09 20:51:34'),(74,74,'profile_students',94,2,1,'2026-03-09 23:50:34','2026-03-09 23:50:34'),(75,75,'profile_students',95,2,1,'2026-03-14 19:55:25','2026-03-14 19:55:25'),(76,76,'profile_students',96,2,1,'2026-03-14 20:14:54','2026-03-14 20:14:54'),(77,77,'profile_students',97,2,1,'2026-03-14 20:15:58','2026-03-14 20:15:58'),(78,78,'profile_students',98,2,1,'2026-03-14 20:17:52','2026-03-14 20:17:52'),(79,79,'profile_students',99,2,1,'2026-03-14 20:18:46','2026-03-14 20:18:46'),(80,80,'profile_students',100,2,1,'2026-03-14 20:19:37','2026-03-14 20:19:37'),(81,81,'profile_students',101,2,1,'2026-03-14 20:20:57','2026-03-14 20:20:57'),(82,82,'profile_students',102,2,1,'2026-03-14 20:54:46','2026-03-14 20:54:46'),(83,83,'profile_students',103,2,1,'2026-03-14 20:56:13','2026-03-14 20:56:13'),(84,84,'profile_students',104,2,1,'2026-03-14 20:58:11','2026-03-14 20:58:11'),(85,85,'profile_students',105,2,1,'2026-03-14 20:59:16','2026-03-14 20:59:16'),(86,86,'profile_students',106,2,1,'2026-03-14 21:04:04','2026-03-14 21:04:04'),(87,87,'profile_students',107,2,1,'2026-03-14 21:06:17','2026-03-14 21:06:17'),(88,88,'profile_students',108,2,1,'2026-03-14 21:07:59','2026-03-14 21:07:59'),(89,89,'profile_admins',84,7,1,'2026-05-11 17:15:58','2026-05-11 17:15:58'),(90,90,'profile_admins',109,6,1,'2026-05-11 17:17:30','2026-05-11 17:17:30'),(91,42,'profile_admins',110,3,1,'2026-05-11 17:18:51','2026-05-11 17:18:51'),(92,91,'profile_barbers',69,5,1,'2026-05-11 17:22:26','2026-05-11 17:22:26'),(93,92,'profile_barbers',66,5,1,'2026-05-11 17:22:51','2026-05-11 17:22:51'),(94,93,'profile_barbers',67,5,1,'2026-05-11 17:23:16','2026-05-11 17:23:16'),(95,94,'profile_barbers',71,5,1,'2026-05-11 17:23:44','2026-05-11 17:23:44'),(96,95,'profile_barbers',72,5,1,'2026-05-11 17:24:12','2026-05-11 17:24:12'),(97,96,'profile_students',111,2,1,'2026-05-12 17:43:27','2026-05-12 17:43:27'),(98,97,'profile_barbers',82,5,1,'2026-05-13 11:21:34','2026-05-13 11:21:34'),(99,98,'profile_barbers',80,5,1,'2026-05-13 11:22:21','2026-05-13 11:22:21'),(100,99,'profile_barbers',79,5,1,'2026-05-13 11:23:27','2026-05-13 11:23:27'),(101,100,'profile_barbers',78,5,1,'2026-05-13 11:24:43','2026-05-13 11:24:43'),(102,101,'profile_barbers',77,5,1,'2026-05-13 11:26:04','2026-05-13 11:26:04'),(103,102,'profile_barbers',74,5,1,'2026-05-13 11:27:05','2026-05-13 11:27:05'),(104,103,'profile_barbers',73,5,1,'2026-05-13 11:28:24','2026-05-13 11:28:24'),(105,104,'profile_barbers',68,5,1,'2026-05-13 11:30:07','2026-05-13 11:30:07'),(106,104,'profile_teachers',68,4,1,'2026-05-13 11:31:27','2026-05-13 11:31:27'),(107,105,'profile_students',112,2,1,'2026-05-13 15:09:19','2026-05-13 15:09:19'),(108,106,'profile_students',113,2,1,'2026-05-13 15:11:32','2026-05-13 15:11:32'),(109,107,'profile_students',114,2,1,'2026-05-13 15:12:40','2026-05-13 15:12:40'),(110,108,'profile_students',115,2,1,'2026-05-13 15:13:28','2026-05-13 15:13:28'),(111,109,'profile_students',116,2,1,'2026-05-13 15:14:47','2026-05-13 15:14:47'),(112,110,'profile_students',117,2,1,'2026-05-13 15:15:39','2026-05-13 15:15:39'),(113,111,'profile_students',118,2,1,'2026-05-13 15:16:55','2026-05-13 15:16:55'),(114,112,'profile_students',119,2,1,'2026-05-13 15:18:11','2026-05-13 15:18:11'),(115,113,'profile_students',120,2,1,'2026-05-13 15:21:25','2026-05-13 15:21:25'),(116,114,'profile_students',121,2,1,'2026-05-13 15:22:29','2026-05-13 15:22:29'),(117,115,'profile_students',122,2,1,'2026-05-13 15:24:31','2026-05-13 15:24:31'),(118,116,'profile_barbers',124,5,1,'2026-05-13 15:45:10','2026-05-13 15:45:10'),(119,43,'profile_barbers',43,5,1,'2026-05-13 15:45:57','2026-05-13 15:45:57'),(120,117,'profile_students',125,2,1,'2026-05-13 17:23:35','2026-05-13 17:23:35'),(121,118,'profile_students',126,2,1,'2026-05-14 15:46:08','2026-05-14 15:46:08'),(122,119,'profile_students',127,2,1,'2026-05-14 15:47:04','2026-05-14 15:47:04'),(123,120,'profile_students',128,2,1,'2026-05-14 15:50:15','2026-05-14 15:50:15'),(124,121,'profile_students',129,2,1,'2026-05-14 16:47:32','2026-05-14 16:47:32'),(125,122,'profile_students',130,2,1,'2026-05-14 17:04:51','2026-05-14 17:04:51'),(126,123,'profile_students',131,2,1,'2026-05-15 17:45:24','2026-05-15 17:45:24');
/*!40000 ALTER TABLE `behavior_profiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `behavior_role_permissions`
--

DROP TABLE IF EXISTS `behavior_role_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `behavior_role_permissions` (
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
) ENGINE=InnoDB AUTO_INCREMENT=701 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `behavior_role_permissions`
--

LOCK TABLES `behavior_role_permissions` WRITE;
/*!40000 ALTER TABLE `behavior_role_permissions` DISABLE KEYS */;
INSERT INTO `behavior_role_permissions` VALUES (288,6,221,'2026-05-12 19:40:57','2026-05-12 19:40:57'),(289,6,220,'2026-05-12 19:40:57','2026-05-12 19:40:57'),(290,6,219,'2026-05-12 19:40:57','2026-05-12 19:40:57'),(291,6,224,'2026-05-12 19:40:57','2026-05-12 19:40:57'),(292,6,223,'2026-05-12 19:40:57','2026-05-12 19:40:57'),(293,6,225,'2026-05-12 19:40:57','2026-05-12 19:40:57'),(294,6,226,'2026-05-12 19:40:57','2026-05-12 19:40:57'),(295,6,228,'2026-05-12 19:40:57','2026-05-12 19:40:57'),(296,6,227,'2026-05-12 19:40:57','2026-05-12 19:40:57'),(297,6,229,'2026-05-12 19:40:57','2026-05-12 19:40:57'),(298,6,230,'2026-05-12 19:40:57','2026-05-12 19:40:57'),(299,6,239,'2026-05-12 19:40:57','2026-05-12 19:40:57'),(300,6,237,'2026-05-12 19:40:57','2026-05-12 19:40:57'),(301,6,240,'2026-05-12 19:40:57','2026-05-12 19:40:57'),(302,6,241,'2026-05-12 19:40:57','2026-05-12 19:40:57'),(303,6,243,'2026-05-12 19:40:57','2026-05-12 19:40:57'),(304,6,242,'2026-05-12 19:40:57','2026-05-12 19:40:57'),(361,8,182,'2026-05-13 11:03:31','2026-05-13 11:03:31'),(362,8,181,'2026-05-13 11:03:31','2026-05-13 11:03:31'),(363,8,180,'2026-05-13 11:03:31','2026-05-13 11:03:31'),(364,8,183,'2026-05-13 11:03:31','2026-05-13 11:03:31'),(365,8,185,'2026-05-13 11:03:31','2026-05-13 11:03:31'),(366,8,184,'2026-05-13 11:03:31','2026-05-13 11:03:31'),(367,8,187,'2026-05-13 11:03:31','2026-05-13 11:03:31'),(368,8,186,'2026-05-13 11:03:31','2026-05-13 11:03:31'),(369,8,189,'2026-05-13 11:03:31','2026-05-13 11:03:31'),(370,8,188,'2026-05-13 11:03:31','2026-05-13 11:03:31'),(371,8,190,'2026-05-13 11:03:31','2026-05-13 11:03:31'),(372,8,192,'2026-05-13 11:03:31','2026-05-13 11:03:31'),(373,8,191,'2026-05-13 11:03:31','2026-05-13 11:03:31'),(374,8,193,'2026-05-13 11:03:31','2026-05-13 11:03:31'),(375,8,195,'2026-05-13 11:03:31','2026-05-13 11:03:31'),(376,8,194,'2026-05-13 11:03:31','2026-05-13 11:03:31'),(377,8,196,'2026-05-13 11:03:31','2026-05-13 11:03:31'),(378,8,198,'2026-05-13 11:03:31','2026-05-13 11:03:31'),(379,8,197,'2026-05-13 11:03:31','2026-05-13 11:03:31'),(380,8,199,'2026-05-13 11:03:31','2026-05-13 11:03:31'),(381,8,201,'2026-05-13 11:03:31','2026-05-13 11:03:31'),(382,8,200,'2026-05-13 11:03:31','2026-05-13 11:03:31'),(383,8,202,'2026-05-13 11:03:31','2026-05-13 11:03:31'),(384,8,204,'2026-05-13 11:03:31','2026-05-13 11:03:31'),(385,8,203,'2026-05-13 11:03:31','2026-05-13 11:03:31'),(386,8,205,'2026-05-13 11:03:31','2026-05-13 11:03:31'),(387,8,206,'2026-05-13 11:03:31','2026-05-13 11:03:31'),(388,8,207,'2026-05-13 11:03:31','2026-05-13 11:03:31'),(389,8,208,'2026-05-13 11:03:31','2026-05-13 11:03:31'),(390,8,209,'2026-05-13 11:03:31','2026-05-13 11:03:31'),(391,8,211,'2026-05-13 11:03:31','2026-05-13 11:03:31'),(392,8,210,'2026-05-13 11:03:31','2026-05-13 11:03:31'),(393,8,213,'2026-05-13 11:03:31','2026-05-13 11:03:31'),(394,8,212,'2026-05-13 11:03:31','2026-05-13 11:03:31'),(395,8,214,'2026-05-13 11:03:31','2026-05-13 11:03:31'),(396,8,215,'2026-05-13 11:03:31','2026-05-13 11:03:31'),(397,8,216,'2026-05-13 11:03:31','2026-05-13 11:03:31'),(398,8,217,'2026-05-13 11:03:31','2026-05-13 11:03:31'),(399,8,218,'2026-05-13 11:03:31','2026-05-13 11:03:31'),(400,8,221,'2026-05-13 11:03:31','2026-05-13 11:03:31'),(401,8,220,'2026-05-13 11:03:31','2026-05-13 11:03:31'),(402,8,219,'2026-05-13 11:03:31','2026-05-13 11:03:31'),(403,8,222,'2026-05-13 11:03:31','2026-05-13 11:03:31'),(404,8,224,'2026-05-13 11:03:31','2026-05-13 11:03:31'),(405,8,223,'2026-05-13 11:03:31','2026-05-13 11:03:31'),(406,8,225,'2026-05-13 11:03:31','2026-05-13 11:03:31'),(407,8,226,'2026-05-13 11:03:31','2026-05-13 11:03:31'),(408,8,228,'2026-05-13 11:03:31','2026-05-13 11:03:31'),(409,8,227,'2026-05-13 11:03:31','2026-05-13 11:03:31'),(410,8,229,'2026-05-13 11:03:31','2026-05-13 11:03:31'),(411,8,230,'2026-05-13 11:03:31','2026-05-13 11:03:31'),(412,8,232,'2026-05-13 11:03:31','2026-05-13 11:03:31'),(413,8,231,'2026-05-13 11:03:31','2026-05-13 11:03:31'),(414,8,233,'2026-05-13 11:03:31','2026-05-13 11:03:31'),(415,8,235,'2026-05-13 11:03:31','2026-05-13 11:03:31'),(416,8,234,'2026-05-13 11:03:31','2026-05-13 11:03:31'),(417,8,236,'2026-05-13 11:03:31','2026-05-13 11:03:31'),(418,8,238,'2026-05-13 11:03:31','2026-05-13 11:03:31'),(419,8,237,'2026-05-13 11:03:31','2026-05-13 11:03:31'),(420,8,239,'2026-05-13 11:03:31','2026-05-13 11:03:31'),(421,8,240,'2026-05-13 11:03:31','2026-05-13 11:03:31'),(422,8,241,'2026-05-13 11:03:31','2026-05-13 11:03:31'),(423,8,243,'2026-05-13 11:03:31','2026-05-13 11:03:31'),(424,8,242,'2026-05-13 11:03:31','2026-05-13 11:03:31'),(425,8,245,'2026-05-13 11:03:31','2026-05-13 11:03:31'),(426,8,244,'2026-05-13 11:03:31','2026-05-13 11:03:31'),(427,8,246,'2026-05-13 11:03:31','2026-05-13 11:03:31'),(428,8,247,'2026-05-13 11:03:31','2026-05-13 11:03:31'),(429,8,248,'2026-05-13 11:03:31','2026-05-13 11:03:31'),(430,8,249,'2026-05-13 11:03:31','2026-05-13 11:03:31'),(431,8,250,'2026-05-13 11:03:31','2026-05-13 11:03:31'),(567,7,182,'2026-05-13 16:07:00','2026-05-13 16:07:00'),(568,7,181,'2026-05-13 16:07:00','2026-05-13 16:07:00'),(569,7,180,'2026-05-13 16:07:00','2026-05-13 16:07:00'),(570,7,185,'2026-05-13 16:07:00','2026-05-13 16:07:00'),(571,7,184,'2026-05-13 16:07:00','2026-05-13 16:07:00'),(572,7,187,'2026-05-13 16:07:00','2026-05-13 16:07:00'),(573,7,186,'2026-05-13 16:07:00','2026-05-13 16:07:00'),(574,7,189,'2026-05-13 16:07:00','2026-05-13 16:07:00'),(575,7,188,'2026-05-13 16:07:00','2026-05-13 16:07:00'),(576,7,190,'2026-05-13 16:07:00','2026-05-13 16:07:00'),(577,7,192,'2026-05-13 16:07:00','2026-05-13 16:07:00'),(578,7,191,'2026-05-13 16:07:00','2026-05-13 16:07:00'),(579,7,193,'2026-05-13 16:07:00','2026-05-13 16:07:00'),(580,7,195,'2026-05-13 16:07:00','2026-05-13 16:07:00'),(581,7,194,'2026-05-13 16:07:00','2026-05-13 16:07:00'),(582,7,196,'2026-05-13 16:07:00','2026-05-13 16:07:00'),(583,7,198,'2026-05-13 16:07:00','2026-05-13 16:07:00'),(584,7,197,'2026-05-13 16:07:00','2026-05-13 16:07:00'),(585,7,199,'2026-05-13 16:07:00','2026-05-13 16:07:00'),(586,7,201,'2026-05-13 16:07:00','2026-05-13 16:07:00'),(587,7,200,'2026-05-13 16:07:00','2026-05-13 16:07:00'),(588,7,202,'2026-05-13 16:07:00','2026-05-13 16:07:00'),(589,7,204,'2026-05-13 16:07:00','2026-05-13 16:07:00'),(590,7,203,'2026-05-13 16:07:00','2026-05-13 16:07:00'),(591,7,205,'2026-05-13 16:07:00','2026-05-13 16:07:00'),(592,7,206,'2026-05-13 16:07:00','2026-05-13 16:07:00'),(593,7,207,'2026-05-13 16:07:00','2026-05-13 16:07:00'),(594,7,208,'2026-05-13 16:07:00','2026-05-13 16:07:00'),(595,7,209,'2026-05-13 16:07:00','2026-05-13 16:07:00'),(596,7,211,'2026-05-13 16:07:00','2026-05-13 16:07:00'),(597,7,210,'2026-05-13 16:07:00','2026-05-13 16:07:00'),(598,7,213,'2026-05-13 16:07:00','2026-05-13 16:07:00'),(599,7,212,'2026-05-13 16:07:00','2026-05-13 16:07:00'),(600,7,214,'2026-05-13 16:07:00','2026-05-13 16:07:00'),(601,7,215,'2026-05-13 16:07:00','2026-05-13 16:07:00'),(602,7,216,'2026-05-13 16:07:00','2026-05-13 16:07:00'),(603,7,217,'2026-05-13 16:07:00','2026-05-13 16:07:00'),(604,7,218,'2026-05-13 16:07:00','2026-05-13 16:07:00'),(605,7,221,'2026-05-13 16:07:00','2026-05-13 16:07:00'),(606,7,220,'2026-05-13 16:07:00','2026-05-13 16:07:00'),(607,7,219,'2026-05-13 16:07:00','2026-05-13 16:07:00'),(608,7,222,'2026-05-13 16:07:00','2026-05-13 16:07:00'),(609,7,224,'2026-05-13 16:07:00','2026-05-13 16:07:00'),(610,7,223,'2026-05-13 16:07:00','2026-05-13 16:07:00'),(611,7,225,'2026-05-13 16:07:00','2026-05-13 16:07:00'),(612,7,226,'2026-05-13 16:07:00','2026-05-13 16:07:00'),(613,7,228,'2026-05-13 16:07:00','2026-05-13 16:07:00'),(614,7,227,'2026-05-13 16:07:00','2026-05-13 16:07:00'),(615,7,229,'2026-05-13 16:07:00','2026-05-13 16:07:00'),(616,7,230,'2026-05-13 16:07:00','2026-05-13 16:07:00'),(617,7,232,'2026-05-13 16:07:00','2026-05-13 16:07:00'),(618,7,231,'2026-05-13 16:07:00','2026-05-13 16:07:00'),(619,7,233,'2026-05-13 16:07:00','2026-05-13 16:07:00'),(620,7,235,'2026-05-13 16:07:00','2026-05-13 16:07:00'),(621,7,234,'2026-05-13 16:07:00','2026-05-13 16:07:00'),(622,7,236,'2026-05-13 16:07:00','2026-05-13 16:07:00'),(623,7,238,'2026-05-13 16:07:00','2026-05-13 16:07:00'),(624,7,237,'2026-05-13 16:07:00','2026-05-13 16:07:00'),(625,7,239,'2026-05-13 16:07:00','2026-05-13 16:07:00'),(626,7,240,'2026-05-13 16:07:00','2026-05-13 16:07:00'),(627,7,241,'2026-05-13 16:07:00','2026-05-13 16:07:00'),(628,7,243,'2026-05-13 16:07:00','2026-05-13 16:07:00'),(629,7,242,'2026-05-13 16:07:00','2026-05-13 16:07:00'),(630,7,245,'2026-05-13 16:07:00','2026-05-13 16:07:00'),(631,7,244,'2026-05-13 16:07:00','2026-05-13 16:07:00'),(632,7,246,'2026-05-13 16:07:00','2026-05-13 16:07:00'),(633,7,247,'2026-05-13 16:07:00','2026-05-13 16:07:00'),(634,7,248,'2026-05-13 16:07:00','2026-05-13 16:07:00'),(635,7,249,'2026-05-13 16:07:00','2026-05-13 16:07:00'),(636,7,250,'2026-05-13 16:07:00','2026-05-13 16:07:00'),(637,3,182,'2026-05-13 16:07:23','2026-05-13 16:07:23'),(638,3,181,'2026-05-13 16:07:23','2026-05-13 16:07:23'),(639,3,180,'2026-05-13 16:07:23','2026-05-13 16:07:23'),(640,3,183,'2026-05-13 16:07:23','2026-05-13 16:07:23'),(641,3,185,'2026-05-13 16:07:23','2026-05-13 16:07:23'),(642,3,184,'2026-05-13 16:07:23','2026-05-13 16:07:23'),(643,3,187,'2026-05-13 16:07:23','2026-05-13 16:07:23'),(644,3,186,'2026-05-13 16:07:23','2026-05-13 16:07:23'),(645,3,189,'2026-05-13 16:07:23','2026-05-13 16:07:23'),(646,3,188,'2026-05-13 16:07:23','2026-05-13 16:07:23'),(647,3,190,'2026-05-13 16:07:23','2026-05-13 16:07:23'),(648,3,192,'2026-05-13 16:07:23','2026-05-13 16:07:23'),(649,3,191,'2026-05-13 16:07:23','2026-05-13 16:07:23'),(650,3,193,'2026-05-13 16:07:23','2026-05-13 16:07:23'),(651,3,195,'2026-05-13 16:07:23','2026-05-13 16:07:23'),(652,3,194,'2026-05-13 16:07:23','2026-05-13 16:07:23'),(653,3,196,'2026-05-13 16:07:23','2026-05-13 16:07:23'),(654,3,198,'2026-05-13 16:07:23','2026-05-13 16:07:23'),(655,3,197,'2026-05-13 16:07:23','2026-05-13 16:07:23'),(656,3,199,'2026-05-13 16:07:23','2026-05-13 16:07:23'),(657,3,201,'2026-05-13 16:07:23','2026-05-13 16:07:23'),(658,3,200,'2026-05-13 16:07:23','2026-05-13 16:07:23'),(659,3,202,'2026-05-13 16:07:23','2026-05-13 16:07:23'),(660,3,204,'2026-05-13 16:07:23','2026-05-13 16:07:23'),(661,3,203,'2026-05-13 16:07:23','2026-05-13 16:07:23'),(662,3,205,'2026-05-13 16:07:23','2026-05-13 16:07:23'),(663,3,206,'2026-05-13 16:07:23','2026-05-13 16:07:23'),(664,3,207,'2026-05-13 16:07:23','2026-05-13 16:07:23'),(665,3,208,'2026-05-13 16:07:23','2026-05-13 16:07:23'),(666,3,209,'2026-05-13 16:07:23','2026-05-13 16:07:23'),(667,3,211,'2026-05-13 16:07:23','2026-05-13 16:07:23'),(668,3,210,'2026-05-13 16:07:23','2026-05-13 16:07:23'),(669,3,221,'2026-05-13 16:07:23','2026-05-13 16:07:23'),(670,3,220,'2026-05-13 16:07:23','2026-05-13 16:07:23'),(671,3,219,'2026-05-13 16:07:23','2026-05-13 16:07:23'),(672,3,222,'2026-05-13 16:07:23','2026-05-13 16:07:23'),(673,3,224,'2026-05-13 16:07:23','2026-05-13 16:07:23'),(674,3,223,'2026-05-13 16:07:23','2026-05-13 16:07:23'),(675,3,225,'2026-05-13 16:07:23','2026-05-13 16:07:23'),(676,3,226,'2026-05-13 16:07:23','2026-05-13 16:07:23'),(677,3,228,'2026-05-13 16:07:23','2026-05-13 16:07:23'),(678,3,227,'2026-05-13 16:07:23','2026-05-13 16:07:23'),(679,3,229,'2026-05-13 16:07:23','2026-05-13 16:07:23'),(680,3,230,'2026-05-13 16:07:23','2026-05-13 16:07:23'),(681,3,232,'2026-05-13 16:07:23','2026-05-13 16:07:23'),(682,3,231,'2026-05-13 16:07:23','2026-05-13 16:07:23'),(683,3,233,'2026-05-13 16:07:23','2026-05-13 16:07:23'),(684,3,235,'2026-05-13 16:07:23','2026-05-13 16:07:23'),(685,3,234,'2026-05-13 16:07:23','2026-05-13 16:07:23'),(686,3,236,'2026-05-13 16:07:23','2026-05-13 16:07:23'),(687,3,238,'2026-05-13 16:07:23','2026-05-13 16:07:23'),(688,3,237,'2026-05-13 16:07:23','2026-05-13 16:07:23'),(689,3,239,'2026-05-13 16:07:23','2026-05-13 16:07:23'),(690,3,240,'2026-05-13 16:07:23','2026-05-13 16:07:23'),(691,3,241,'2026-05-13 16:07:23','2026-05-13 16:07:23'),(692,3,243,'2026-05-13 16:07:23','2026-05-13 16:07:23'),(693,3,242,'2026-05-13 16:07:23','2026-05-13 16:07:23'),(694,3,245,'2026-05-13 16:07:23','2026-05-13 16:07:23'),(695,3,244,'2026-05-13 16:07:23','2026-05-13 16:07:23'),(696,3,246,'2026-05-13 16:07:23','2026-05-13 16:07:23'),(697,3,247,'2026-05-13 16:07:23','2026-05-13 16:07:23'),(698,3,248,'2026-05-13 16:07:23','2026-05-13 16:07:23'),(699,3,249,'2026-05-13 16:07:23','2026-05-13 16:07:23'),(700,3,250,'2026-05-13 16:07:23','2026-05-13 16:07:23');
/*!40000 ALTER TABLE `behavior_role_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `behavior_roles`
--

DROP TABLE IF EXISTS `behavior_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `behavior_roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `redirect_to` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `level` enum('0','1','2','3','4') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `behavior_roles_name_unique` (`name`),
  UNIQUE KEY `behavior_roles_display_name_unique` (`display_name`),
  KEY `behavior_roles_name_index` (`name`),
  KEY `behavior_roles_level_index` (`level`),
  KEY `behavior_roles_is_active_index` (`is_active`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `behavior_roles`
--

LOCK TABLES `behavior_roles` WRITE;
/*!40000 ALTER TABLE `behavior_roles` DISABLE KEYS */;
INSERT INTO `behavior_roles` VALUES (1,'super_admin','Super Admin','/admin','0',1,'2026-02-17 02:40:07','2026-02-17 02:40:07'),(2,'estudiante','Estudiante','/st','3',1,'2026-02-17 02:40:08','2026-02-17 02:40:08'),(3,'administrador','Administrador','/admin','1',1,'2026-02-17 02:41:10','2026-02-17 02:41:10'),(4,'docentes','Docentes','/te','2',1,'2026-02-17 02:40:08','2026-02-17 02:40:08'),(5,'barbero','Barbero','/ba','4',1,'2026-03-23 03:27:53','2026-03-23 03:27:54'),(6,'asistente-junior','Asistente Junior','/admin','1',1,'2026-05-11 15:38:59','2026-05-12 14:30:13'),(7,'asistente-senior','Asistente Senior','/admin','1',1,'2026-05-11 15:39:09','2026-05-11 15:39:09'),(8,'gerente','Gerente','/admin','1',1,'2026-05-13 11:03:19','2026-05-13 11:03:19');
/*!40000 ALTER TABLE `behavior_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `core_calendar_holidays`
--

DROP TABLE IF EXISTS `core_calendar_holidays`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `core_calendar_holidays` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `core_calendar_holidays_date_unique` (`date`),
  KEY `core_calendar_holidays_date_index` (`date`),
  KEY `core_calendar_holidays_is_active_index` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `core_calendar_holidays`
--

LOCK TABLES `core_calendar_holidays` WRITE;
/*!40000 ALTER TABLE `core_calendar_holidays` DISABLE KEYS */;
/*!40000 ALTER TABLE `core_calendar_holidays` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `core_cities`
--

DROP TABLE IF EXISTS `core_cities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `core_cities` (
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `core_cities`
--

LOCK TABLES `core_cities` WRITE;
/*!40000 ALTER TABLE `core_cities` DISABLE KEYS */;
/*!40000 ALTER TABLE `core_cities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `core_companies`
--

DROP TABLE IF EXISTS `core_companies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `core_companies` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trade_name` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ruc` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `core_companies_ruc_unique` (`ruc`),
  KEY `core_companies_is_active_index` (`is_active`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `core_companies`
--

LOCK TABLES `core_companies` WRITE;
/*!40000 ALTER TABLE `core_companies` DISABLE KEYS */;
INSERT INTO `core_companies` VALUES (1,'BARBAROS CLUB E.I.R.L.','ESCUELA BÁRBAROS','20605462589','JR. UNIÓN N°212','930153296','https://api.gruposamanez.com.pe/storage/company_logos/logo-20260513172627.jpg',1,'2026-05-13 17:26:27','2026-05-13 17:27:20');
/*!40000 ALTER TABLE `core_companies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `core_countries`
--

DROP TABLE IF EXISTS `core_countries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `core_countries` (
  `code` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`code`),
  UNIQUE KEY `core_countries_code_unique` (`code`),
  UNIQUE KEY `core_countries_name_unique` (`name`),
  KEY `core_countries_name_index` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `core_countries`
--

LOCK TABLES `core_countries` WRITE;
/*!40000 ALTER TABLE `core_countries` DISABLE KEYS */;
INSERT INTO `core_countries` VALUES ('PE','Perú');
/*!40000 ALTER TABLE `core_countries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `core_document_types`
--

DROP TABLE IF EXISTS `core_document_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `core_document_types` (
  `code` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`code`),
  UNIQUE KEY `core_document_types_code_unique` (`code`),
  UNIQUE KEY `core_document_types_name_unique` (`name`),
  KEY `core_document_types_name_index` (`name`),
  KEY `core_document_types_is_active_index` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `core_document_types`
--

LOCK TABLES `core_document_types` WRITE;
/*!40000 ALTER TABLE `core_document_types` DISABLE KEYS */;
INSERT INTO `core_document_types` VALUES ('0','Otros',1),('1','DNI',1),('4','Carnet de Extranjería',1),('6','RUC',1),('7','Pasaporte',1);
/*!40000 ALTER TABLE `core_document_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `core_files`
--

DROP TABLE IF EXISTS `core_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `core_files` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `fileable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fileable_id` bigint unsigned NOT NULL,
  `type` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `path` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `disk` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'public',
  `mime_type` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `core_files_fileable_index` (`fileable_type`,`fileable_id`),
  KEY `core_files_type_index` (`type`),
  KEY `core_files_disk_index` (`disk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `core_files`
--

LOCK TABLES `core_files` WRITE;
/*!40000 ALTER TABLE `core_files` DISABLE KEYS */;
/*!40000 ALTER TABLE `core_files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `core_genders`
--

DROP TABLE IF EXISTS `core_genders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `core_genders` (
  `code` char(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`code`),
  UNIQUE KEY `core_genders_code_unique` (`code`),
  UNIQUE KEY `core_genders_name_unique` (`name`),
  KEY `core_genders_name_index` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `core_genders`
--

LOCK TABLES `core_genders` WRITE;
/*!40000 ALTER TABLE `core_genders` DISABLE KEYS */;
INSERT INTO `core_genders` VALUES ('2','Femenino'),('1','Masculino'),('3','No binario'),('9','Prefiero no decirlo');
/*!40000 ALTER TABLE `core_genders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `core_infrastructures`
--

DROP TABLE IF EXISTS `core_infrastructures`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `core_infrastructures` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `infrastructurable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `infrastructurable_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `core_infra_type_id_unique` (`infrastructurable_type`,`infrastructurable_id`),
  KEY `core_infrastructures_infrastructurable_type_index` (`infrastructurable_type`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `core_infrastructures`
--

LOCK TABLES `core_infrastructures` WRITE;
/*!40000 ALTER TABLE `core_infrastructures` DISABLE KEYS */;
INSERT INTO `core_infrastructures` VALUES (1,'academy_branches',1,'2026-02-24 03:29:18','2026-02-24 03:29:19'),(2,'barbershop_branches',1,'2026-03-05 23:58:20','2026-03-05 23:58:20'),(3,'barbershop_branches',2,'2026-03-05 23:59:10','2026-03-05 23:59:10'),(4,'barbershop_branches',3,'2026-03-06 00:00:52','2026-03-06 00:00:52'),(5,'barbershop_branches',4,'2026-03-06 00:01:48','2026-03-06 00:01:48');
/*!40000 ALTER TABLE `core_infrastructures` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `core_payment_methods`
--

DROP TABLE IF EXISTS `core_payment_methods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `core_payment_methods` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('cash','bank') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cash',
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `core_payment_methods_is_active_index` (`is_active`),
  KEY `core_payment_methods_name_index` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `core_payment_methods`
--

LOCK TABLES `core_payment_methods` WRITE;
/*!40000 ALTER TABLE `core_payment_methods` DISABLE KEYS */;
INSERT INTO `core_payment_methods` VALUES (1,'Efectivo','cash',1,1,'2026-02-28 07:45:41','2026-02-28 07:45:41'),(2,'Bancarizado','bank',0,1,'2026-02-28 07:45:41','2026-02-28 07:45:41');
/*!40000 ALTER TABLE `core_payment_methods` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `core_persons`
--

DROP TABLE IF EXISTS `core_persons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `core_persons` (
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
) ENGINE=InnoDB AUTO_INCREMENT=132 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `core_persons`
--

LOCK TABLES `core_persons` WRITE;
/*!40000 ALTER TABLE `core_persons` DISABLE KEYS */;
INSERT INTO `core_persons` VALUES (1,'1','00000000','Lino','Puma',NULL,NULL,NULL,'super@admin.com',NULL,NULL,NULL,'PE','2026-02-17 02:40:07','2026-02-17 02:40:07'),(2,'1','00000001','Admin','Admin','Test',NULL,'900000001','admin@test.com',NULL,NULL,NULL,NULL,'2026-02-17 02:41:52','2026-02-17 02:41:52'),(6,'1','76832299','BRITNEY LISBET','CANSAYA','GEMIO',NULL,'961258362','C@GMAIL.COM',NULL,NULL,NULL,NULL,'2026-02-20 00:20:41','2026-02-20 00:20:41'),(7,'1','61893646','RENZO PAUL','GOMEZ','TAIPE',NULL,'961264940','C1@GMAIL.COM',NULL,NULL,NULL,NULL,'2026-02-20 00:23:34','2026-02-20 00:23:34'),(8,'1','62805432','CHRISTIAN GUILARDINO','MAMANI','CONDORI',NULL,'922127589','C2@GMAIL.COM',NULL,NULL,NULL,NULL,'2026-02-20 00:24:45','2026-02-20 00:24:45'),(9,'1','73820089','FERMINA FLORA','CALCINA','MAMANI',NULL,'957772458','C3@GMAIL.COM',NULL,NULL,NULL,NULL,'2026-02-20 00:25:53','2026-02-20 00:25:53'),(10,'1','61939362','ANDREW ALEXANDER','PAREDES','POMA',NULL,'61939362','C4@GMAIL.COM',NULL,NULL,NULL,NULL,'2026-02-20 00:26:58','2026-02-20 00:26:58'),(11,'1','61783700','YIMER RUSSEL','AHUMADA','QUISPE',NULL,'965194393','C5@GMAIL.COM',NULL,NULL,NULL,NULL,'2026-02-20 00:27:52','2026-02-20 00:27:52'),(12,'1','63017506','KEVIN JUNIOR','LUQUE','CHAIÑA',NULL,'900466355','C6@GMAIL.COM',NULL,NULL,NULL,NULL,'2026-02-20 00:28:58','2026-02-20 00:28:58'),(13,'1','48022417','ROXANA','CUNO','HUANCA',NULL,'998424104','C7@GMAIL.COM',NULL,NULL,NULL,NULL,'2026-02-20 00:30:01','2026-02-20 00:30:01'),(14,'1','61892341','PRINCE ARTURO GABRIEL','GOMEZ','FLORES',NULL,'921666999','C8@GMAIL.COM',NULL,NULL,NULL,NULL,'2026-02-20 00:31:13','2026-02-20 00:31:13'),(15,'1','61321205','MARCO RUBIÑO','QUISPE','SUCAPUCA',NULL,'941735090','C9@GMAIL.COM',NULL,NULL,NULL,NULL,'2026-02-20 00:32:07','2026-02-20 00:32:07'),(16,'1','62528271','DIEGO LEONARDO','COYA','COAQUIRA',NULL,'973128544','10@GMAIL.COM',NULL,NULL,NULL,NULL,'2026-02-20 00:33:23','2026-02-20 00:33:23'),(17,'1','62480235','JEAN FRANK RONALDHO','CASTILLO','ROJAS',NULL,'993138481','11@GMAIL.COM',NULL,NULL,NULL,NULL,'2026-02-20 00:34:19','2026-02-20 00:34:19'),(18,'1','45910327','BERTHA','CALSINA','CCASO',NULL,'929120254','12@GMAIL.COM',NULL,NULL,NULL,NULL,'2026-02-20 00:41:41','2026-02-20 00:41:41'),(19,'1','12312301','ARISON HAIR','HUAHUAMULLO','SANCHEZ',NULL,'925818582','13@GMAIL.COM',NULL,NULL,NULL,NULL,'2026-02-20 00:48:28','2026-02-20 00:48:28'),(20,'1','12312302','MARIA DEL VALLE','MONTILLA','ALBORNOZ',NULL,'915075991','14@GMAIL.COM',NULL,NULL,NULL,NULL,'2026-02-20 00:49:28','2026-02-20 00:51:16'),(21,'1','12312303','GUSTAVO MAXIMO','APAZA','DELGADO',NULL,'910523360','16@GMAIL.COM',NULL,NULL,NULL,NULL,'2026-02-20 01:04:23','2026-02-20 01:04:23'),(22,'1','72233424','ROBERTO CARLOS','ROCHA','BUTRON',NULL,'980511864','M1@GMAIL.COM',NULL,NULL,NULL,NULL,'2026-02-21 05:31:13','2026-02-21 05:31:13'),(23,'1','70838415','SUSY EDITH','CANTUTA','QUISPE',NULL,'929507195','M2@GMAIL.COM',NULL,NULL,NULL,NULL,'2026-02-21 05:32:19','2026-02-21 05:32:19'),(24,'1','74310292','JHON BRAYAN','CRUZ','QUISPE',NULL,'947736178','M3@GMAIL.COM',NULL,NULL,NULL,NULL,'2026-02-21 05:43:15','2026-02-21 05:43:28'),(25,'1','61091109','JENNIFER ANAHIS','CHOQUEMAQUE','CALAPUJA',NULL,'969567586','M4@GMAIL.COM',NULL,NULL,NULL,NULL,'2026-02-21 05:46:50','2026-02-21 05:46:50'),(26,'1','73651532','ROY ALVARO','PALOMINO','QUISPE',NULL,'935269221','M5@GMAIL.COM',NULL,NULL,NULL,NULL,'2026-02-21 05:48:24','2026-02-21 05:52:50'),(27,'1','73541260','DELMA MERY','CCANCAPA','PIZARRO',NULL,'921614240','M6@GMAIL.COM',NULL,NULL,NULL,NULL,'2026-02-21 05:50:22','2026-02-21 05:50:22'),(28,'1','61000410','FERNANDO SERGIO','MAMANI','CONDORI',NULL,'953886034','M7@GMIL.COM',NULL,NULL,NULL,NULL,'2026-02-21 05:55:07','2026-02-21 05:55:07'),(29,'1','71654064','MARLETH MILAGROS','CHAMBI','HUAMAN',NULL,'910333619','M8@GMAI.COM',NULL,NULL,NULL,NULL,'2026-02-21 05:58:16','2026-02-21 05:58:16'),(30,'1','60665191','KEVIN','UCHIRI','RAMOS',NULL,'933420822','M9@GMAIL.COM',NULL,NULL,NULL,NULL,'2026-02-21 05:59:12','2026-02-21 05:59:12'),(31,'1','73171929','FRANK ANTHONY','TICONA','APAZA',NULL,'991733788','M10@GMAIL.COM',NULL,NULL,NULL,NULL,'2026-02-21 06:03:25','2026-02-21 06:03:25'),(32,'1','62824124','KENYI LEONEL','TICONA','APAZA',NULL,'991292613','M11@GMAIL.COM',NULL,NULL,NULL,NULL,'2026-02-21 06:05:53','2026-02-21 06:05:53'),(33,'1','60417095','NOLBERTO RICHARD','RAMOS','MOROCCO',NULL,'917111765','M12@GMAIL.COM',NULL,NULL,NULL,NULL,'2026-02-21 06:10:05','2026-02-21 06:10:05'),(34,'1','60597092','RAUL EDUARDO','HUAMAN','SURCO',NULL,'942361760','M13@GMAIL.COM',NULL,NULL,NULL,NULL,'2026-02-21 06:11:30','2026-02-21 06:11:30'),(35,'1','75899252','YONATAN HEBER','QUISPE','MAMANI',NULL,'921660409','M14@GMAIL.COM',NULL,NULL,NULL,NULL,'2026-02-21 06:14:01','2026-02-21 06:14:01'),(36,'1','62486607','GARCIA YANA LUIS ALBERTO','GARCIA','YANA',NULL,'989976297','ALFIL.CONTA5@GMAIL.COM',NULL,NULL,NULL,NULL,'2026-02-21 09:35:01','2026-02-21 09:35:01'),(37,'1','60908831','ROGER FRANCES','ADCO','MACEDO',NULL,'957107771','R1@GAMIL.COM',NULL,NULL,NULL,NULL,'2026-02-21 09:37:51','2026-02-21 09:37:51'),(38,'1','61906283','ALVARO GEFFEN','CALATAYUD','MONRROY',NULL,'989689563','R2@GMAIL.COM',NULL,NULL,NULL,NULL,'2026-02-21 09:38:58','2026-02-21 09:38:58'),(39,'1','76507044','LUIS YAMPIER','CONDORI','FLORES',NULL,'917519654','R3@GMAIL.COM',NULL,NULL,NULL,NULL,'2026-02-21 09:40:25','2026-02-21 09:40:25'),(40,'1','73384290','MIDWAR','CCAMA','SANDOVAL',NULL,'927515425',NULL,NULL,NULL,NULL,NULL,'2026-02-23 20:38:29','2026-02-23 22:25:23'),(41,'1','45650909','GEORGE JESUS','SALAS','VARGAS',NULL,'908614100',NULL,NULL,NULL,NULL,NULL,'2026-02-23 22:28:04','2026-02-23 22:28:04'),(43,'1','74037105','OMAR ALEX','SANTOS','SANTOS',NULL,'932388210',NULL,'1',NULL,NULL,NULL,'2026-02-23 22:30:32','2026-05-13 15:45:57'),(44,'1','60744187','JOSE YAMPIER','CCAMO','CARRIZALES',NULL,'979851503',NULL,NULL,NULL,NULL,NULL,'2026-03-03 04:23:41','2026-03-03 04:23:41'),(45,'1','74241153','GLORIA','ALARCON','HANCCO',NULL,'965221673',NULL,NULL,NULL,NULL,NULL,'2026-03-03 05:10:36','2026-03-03 05:10:36'),(46,'1','72486595','KELY','CHECMAPUCO','FLORES',NULL,'957063131',NULL,NULL,NULL,NULL,NULL,'2026-03-03 05:11:32','2026-03-03 05:11:32'),(47,'1','76959738','MILUSKA','MERMA','QUISPE',NULL,'925343997',NULL,NULL,NULL,NULL,NULL,'2026-03-03 05:12:09','2026-03-03 05:12:09'),(48,'1','42867198','AMANDA','YANA','YANA',NULL,'975799966',NULL,NULL,NULL,NULL,NULL,'2026-03-03 05:12:57','2026-03-03 05:12:57'),(49,'1','60523192','BELINDA MAITE','CALCINA','INOFUENTE',NULL,'929340382',NULL,NULL,NULL,NULL,NULL,'2026-03-03 05:14:50','2026-03-03 05:14:50'),(50,'1','70652863','JORGE LUIS','INCACOÑA','MAMANI',NULL,'994845581',NULL,NULL,NULL,NULL,NULL,'2026-03-03 05:22:28','2026-03-03 05:22:28'),(51,'1','47087692','RODRIGO','BARRETO','SAMANEZ',NULL,'922696443',NULL,NULL,NULL,NULL,NULL,'2026-03-03 05:23:02','2026-03-03 05:23:02'),(52,'1','71706577','JAVIER','AGUIRRE','QUISPE',NULL,'930270783',NULL,NULL,NULL,NULL,NULL,'2026-03-03 05:23:31','2026-03-03 05:23:31'),(53,'1','77803334','NILVER GULIAN','AQUINO','MAMANI',NULL,'928693487',NULL,NULL,NULL,NULL,NULL,'2026-03-03 05:24:23','2026-03-03 05:24:23'),(54,'1','71987363','EDGARDO','CACERES','POLLOYQUERI',NULL,'997979487',NULL,NULL,NULL,NULL,NULL,'2026-03-03 05:25:38','2026-03-03 05:25:38'),(55,'1','61158623','JOEL ANDERSON','QUEA','MAMANI',NULL,'914157382',NULL,NULL,NULL,NULL,NULL,'2026-03-03 05:26:18','2026-03-03 05:26:18'),(56,'1','62417911','ANDY FRABIZIO','TICONA','CANTUTA',NULL,'997534400',NULL,NULL,NULL,NULL,NULL,'2026-03-03 05:26:53','2026-03-03 05:26:53'),(57,'1','72046705','BENJAMIN','ZEA','N',NULL,'918925899',NULL,NULL,NULL,NULL,NULL,'2026-03-03 05:27:56','2026-03-03 05:27:56'),(58,'1','62562577','BRUCE JEAN PIERRE','CONDES','SONCCO',NULL,'950859066',NULL,NULL,NULL,NULL,NULL,'2026-03-03 19:30:47','2026-03-03 19:30:47'),(59,'1','70898405','EMERSON','PALAZUELOS','MACHACA',NULL,'925700987',NULL,NULL,NULL,NULL,NULL,'2026-03-03 19:32:40','2026-03-03 19:32:40'),(60,'1','60069429','RUTH YANELY','YANAPA','PAYE',NULL,'916606795',NULL,NULL,NULL,NULL,NULL,'2026-03-03 19:33:57','2026-03-03 19:33:57'),(61,'1','60376745','ELISA','TURPO','QUISPE',NULL,'932101205',NULL,NULL,NULL,NULL,NULL,'2026-03-04 00:59:37','2026-03-04 00:59:37'),(62,'1','75841036','ANA MARIBEL','TINTA','MAMANI',NULL,'992063481',NULL,NULL,NULL,NULL,NULL,'2026-03-04 01:00:27','2026-03-04 01:00:27'),(63,'1','61651506','ALEX CESAR','ACOSTA','MAMANI',NULL,'916489004',NULL,NULL,NULL,NULL,NULL,'2026-03-04 01:01:32','2026-03-04 01:01:32'),(64,'1','73637823','YESICA SULEMA','QUISPE','MAMANI',NULL,'963291071',NULL,NULL,NULL,NULL,NULL,'2026-03-04 16:57:04','2026-03-04 16:57:04'),(65,'1','10000000','CLIENTE 1','QUISPE','M',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-03-06 16:36:33','2026-03-06 16:37:28'),(66,'1','77161559','NELSON GILMER','QUISPE','LAZARINOS',NULL,'973165636',NULL,NULL,NULL,NULL,NULL,'2026-03-06 16:38:23','2026-03-06 16:38:23'),(67,'1','76634956','MARY LUZ','SACACHIPANA','MAMANI',NULL,'980646459',NULL,NULL,NULL,NULL,NULL,'2026-03-06 16:39:42','2026-03-06 16:39:42'),(68,'1','73712265','RONALDINO','CALCINA','INOFUENTE',NULL,'916343830',NULL,'1',NULL,NULL,NULL,'2026-03-06 16:41:37','2026-05-13 11:30:06'),(69,'1','73931954','ALVARO LUIS','QUISCA','MACHACA',NULL,'929801080',NULL,NULL,NULL,NULL,NULL,'2026-03-06 16:42:29','2026-03-06 16:42:29'),(70,'1','28773440','YULEIDIS MARIA','LOZANO','MONTILLA',NULL,'935208107',NULL,NULL,NULL,NULL,NULL,'2026-03-06 16:44:34','2026-03-06 16:44:34'),(71,'1','60837000','JHON HENRY','ESTRADA','MAMANI',NULL,'956260956',NULL,NULL,NULL,NULL,NULL,'2026-03-06 16:45:29','2026-03-06 16:45:29'),(72,'1','74886430','DANIEL LINCOLN','YAÑEZ','CALLA',NULL,'944045704',NULL,NULL,NULL,NULL,NULL,'2026-03-06 16:46:12','2026-03-06 16:46:12'),(73,'1','60837157','JUAN ELIAS','CALLA','LAROTA',NULL,'930878357',NULL,'1',NULL,NULL,NULL,'2026-03-06 16:46:58','2026-05-13 11:28:23'),(74,'1','61320584','JESUS ADRIAN EUSEBIO','PINTO','ZELA',NULL,'948593253',NULL,'1',NULL,NULL,NULL,'2026-03-06 16:47:54','2026-05-13 11:27:04'),(75,'1','71459712','VIVIAN FIORELLA','CCAMA','OTAZU',NULL,'916944692',NULL,NULL,NULL,NULL,NULL,'2026-03-06 16:50:09','2026-03-06 16:50:09'),(76,'1','74355331','SANTUSA','SUCASACA','ROQUE',NULL,'953875500',NULL,NULL,NULL,NULL,NULL,'2026-03-06 16:50:53','2026-03-06 16:50:53'),(77,'1','73350098','MARY AYDE','QUISPE','PACHECO',NULL,'955729289',NULL,'2',NULL,NULL,NULL,'2026-03-06 16:51:44','2026-05-13 11:26:04'),(78,'1','74880171','NILDA OLGA','QUISPE','PACHECO',NULL,'973334615',NULL,'2',NULL,NULL,NULL,'2026-03-06 16:52:36','2026-05-13 11:24:42'),(79,'1','73312751','MARIBEL ROCIO','ARI','ARI',NULL,'914308445',NULL,'2',NULL,NULL,NULL,'2026-03-06 16:53:34','2026-05-13 11:23:27'),(80,'1','60460460','YAKELIN SONIA','HUAMAN','APAZA',NULL,'926490719',NULL,'2',NULL,NULL,NULL,'2026-03-06 16:56:04','2026-05-13 11:22:20'),(81,'1','60204890','SANDER','MONROY','QUISPE',NULL,'946858414',NULL,NULL,NULL,NULL,NULL,'2026-03-06 16:56:59','2026-03-06 16:56:59'),(82,'1','61159334','DANNY PERCY','PANDIA','ZELA',NULL,'925095054',NULL,'1',NULL,NULL,NULL,'2026-03-06 16:57:46','2026-05-13 11:21:33'),(83,'4','006439245','YENNIFER CAROLINA','SALAZAR','TERAN',NULL,'930153296',NULL,'2',NULL,NULL,NULL,'2026-03-06 16:58:56','2026-05-13 15:41:59'),(84,'1','70063570','CARLOS MARTIN','MAMANI','CAYO',NULL,'998707650',NULL,NULL,NULL,NULL,NULL,'2026-03-06 17:02:04','2026-03-06 17:02:04'),(85,'1','75736643','ROXANA SHEYLA','LAQUISE','TICONA',NULL,'961335452',NULL,NULL,NULL,NULL,NULL,'2026-03-06 20:02:43','2026-03-06 20:02:43'),(86,'1','75283468','CATERIN','CONDORI','QUISPE',NULL,'951415050',NULL,NULL,NULL,NULL,NULL,'2026-03-06 22:44:21','2026-03-06 22:44:21'),(87,'1','60836645','ANTONY GUILLERMO','BENAVENTE','PARICAHUA',NULL,'932345198',NULL,NULL,NULL,NULL,NULL,'2026-03-07 21:27:01','2026-03-07 21:27:01'),(88,'1','73744974','ROSALIA','PARI','CHOQUEHUANCA',NULL,'929892781',NULL,NULL,NULL,NULL,NULL,'2026-03-07 21:27:51','2026-03-07 21:27:51'),(89,'1','60468990','YULISSA','QUISPE','PUMA',NULL,'974207118',NULL,NULL,NULL,NULL,NULL,'2026-03-07 22:21:03','2026-03-07 22:21:03'),(90,'1','74057625','HILDA MARISOL','ARAPA','ARAPA',NULL,'905918929',NULL,NULL,NULL,NULL,NULL,'2026-03-09 17:03:00','2026-03-09 17:03:00'),(91,'1','75889154','EDSON AMERICO','LUQUE','CCALLO',NULL,'957112298',NULL,NULL,NULL,NULL,NULL,'2026-03-09 17:04:12','2026-03-09 17:04:12'),(92,'1','60212709','LUZ LEIDY','CARCAUSTO','TIPO',NULL,'951780883',NULL,NULL,NULL,NULL,NULL,'2026-03-09 20:50:55','2026-03-09 20:50:55'),(93,'1','76398347','JHONATAN SALVADOR','CAHUAPAZA','CALSIN',NULL,'994047014',NULL,NULL,NULL,NULL,NULL,'2026-03-09 20:51:33','2026-03-09 20:51:33'),(94,'1','71552620','EDITH DINA','COLLANQUI','ZELA',NULL,'919043352',NULL,NULL,NULL,NULL,NULL,'2026-03-09 23:50:34','2026-03-09 23:50:34'),(95,'1','73600475','EDILSON','GEMIO','MAMANI',NULL,'999999999',NULL,NULL,NULL,NULL,NULL,'2026-03-14 19:55:25','2026-03-14 19:55:25'),(96,'1','70000000','IRMA LUZ','ITO','LIPA',NULL,'901808957',NULL,NULL,NULL,NULL,NULL,'2026-03-14 20:14:53','2026-03-14 20:14:53'),(97,'1','75540251','LEINY DIANA','HUANCA','GUTIEEREZ',NULL,'900391036',NULL,NULL,NULL,NULL,NULL,'2026-03-14 20:15:58','2026-03-14 20:15:58'),(98,'1','77709611','YHAK YEISON','LIMAHUAYA','MAMANI',NULL,'915016425',NULL,NULL,NULL,NULL,NULL,'2026-03-14 20:17:52','2026-03-14 20:17:52'),(99,'1','73355120','MELY','CHECMAPUCO','FLORES',NULL,'993762251',NULL,NULL,NULL,NULL,NULL,'2026-03-14 20:18:46','2026-03-14 20:18:46'),(100,'1','79463049','MACIEL MARTIN','PARIAPAZA','QUISPE',NULL,'991814172',NULL,NULL,NULL,NULL,NULL,'2026-03-14 20:19:37','2026-03-14 20:19:37'),(101,'1','60279006','NEDAY KATERIN','ARIVILCA','FLORES',NULL,'950388892',NULL,NULL,NULL,NULL,NULL,'2026-03-14 20:20:57','2026-03-14 20:20:57'),(102,'1','61156311','GABRIEL MESI','TIPO','SAHUALAURA',NULL,'946337243',NULL,NULL,NULL,NULL,NULL,'2026-03-14 20:54:46','2026-03-14 20:54:46'),(103,'1','60177878','EMERSON GIOVANNI','QUINA','CHAMBI',NULL,'939492338',NULL,NULL,NULL,NULL,NULL,'2026-03-14 20:56:13','2026-03-14 20:56:13'),(104,'1','61974539','DAYRON DIEGO','CAÑAZACA','CONDORI',NULL,'914566572',NULL,NULL,NULL,NULL,NULL,'2026-03-14 20:58:11','2026-03-14 20:58:11'),(105,'1','61938479','JHOEL ANGEL','QUISPE','CASTELLANOS',NULL,'992390833',NULL,NULL,NULL,NULL,NULL,'2026-03-14 20:59:16','2026-03-14 20:59:16'),(106,'1','62528407','DAYAN MAYUMI','SONCCO','MAMANI',NULL,'958066095',NULL,NULL,NULL,NULL,NULL,'2026-03-14 21:04:03','2026-03-14 21:04:03'),(107,'1','70000001','ESTEBAN FERNANDO','ITO','CHIARA',NULL,'973690183',NULL,NULL,NULL,NULL,NULL,'2026-03-14 21:06:17','2026-03-14 21:06:17'),(108,'1','60305344','BENY MIGUEL DIAZ','LAURA','AQUINO',NULL,'910239745',NULL,NULL,NULL,NULL,NULL,'2026-03-14 21:07:59','2026-03-14 21:07:59'),(109,'1','72127899','WOLKER','CARBAJAL','OCHOCHOQUE',NULL,'900019367','WC@TEST.COM','1',NULL,NULL,NULL,'2026-05-11 17:17:29','2026-05-11 17:17:29'),(110,'1','70498731','KATHERINE','SAMANEZ','TORRES',NULL,'944258342','SAM@TEST.COM',NULL,NULL,NULL,NULL,'2026-05-11 17:18:51','2026-05-11 17:18:51'),(111,'1','76046262','MARIZOL','MANCHA','CUTIPA','1999-01-12','913132245',NULL,'2','AV. MAITA CAPAC #721',NULL,NULL,'2026-05-12 17:43:27','2026-05-12 17:45:39'),(112,'1','60681265','CARMEN ZENAYDA','JAILA','PARI',NULL,'997140274',NULL,'2',NULL,NULL,NULL,'2026-05-13 15:09:19','2026-05-13 15:09:19'),(113,'1','74600936','ANALY','PILCO','CALCINA',NULL,'935833002',NULL,'2','C. CULLCO BELEN',NULL,NULL,'2026-05-13 15:11:32','2026-05-13 15:11:32'),(114,'1','76935744','WILLIAM ANEL','BARRANTES','TAPIA',NULL,'926299983',NULL,'1','HOSPITAL NUEVO',NULL,NULL,'2026-05-13 15:12:40','2026-05-13 15:12:40'),(115,'1','60567804','JOSE FERNANDO','ARI','ZAPANA',NULL,'951249532',NULL,'1','URB. ANEXO ALEXANDER',NULL,NULL,'2026-05-13 15:13:28','2026-05-13 15:13:28'),(116,'1','74457010','RODYNE','ESTRADA','HUANCA',NULL,'908700213',NULL,NULL,'PRADERAS LOS INCAS',NULL,NULL,'2026-05-13 15:14:47','2026-05-13 15:14:47'),(117,'1','61466197','ROMARIO','RAFAELE','ESPINOZA',NULL,'951533566',NULL,'1','URB. SANTA ADRIANA',NULL,NULL,'2026-05-13 15:15:39','2026-05-13 15:15:39'),(118,'1','60403758','ALEX ABAD','MACHACA','CONDORI',NULL,'942635943',NULL,'1',NULL,NULL,NULL,'2026-05-13 15:16:55','2026-05-13 15:16:55'),(119,'1','60069273','ROSMERI','CANAZA','CHUQUIMAMANI',NULL,'931915923',NULL,'2','AV HUANCANE',NULL,NULL,'2026-05-13 15:18:11','2026-05-13 15:18:11'),(120,'1','75403310','CINTHIA LIZETH','SUCASACA','QUISPE',NULL,'951489720',NULL,'2','AV PERU CON AV TACNA',NULL,NULL,'2026-05-13 15:21:24','2026-05-13 15:21:24'),(121,'1','60066232','JIMENA LISBETH','QUISPE','MUÑOZ',NULL,'967303271',NULL,'2','AV AVIACION',NULL,NULL,'2026-05-13 15:22:29','2026-05-13 15:22:29'),(122,'1','60853472','JOSE DANIEL','OSORIO','MAMANI',NULL,'929663283',NULL,'1',NULL,NULL,NULL,'2026-05-13 15:24:30','2026-05-13 15:24:30'),(123,'1','74179053','DIANA MICHEL','CHIARA','PACHECO',NULL,'942297776',NULL,'2',NULL,NULL,NULL,'2026-05-13 15:40:37','2026-05-13 15:40:37'),(124,'1','62623990','DIEGO FORLAN','ARESTIGUE','CONDORI',NULL,'974502042',NULL,'1',NULL,NULL,NULL,'2026-05-13 15:45:09','2026-05-13 15:45:09'),(125,'1','60658879','AARON EMANUEL','FLORES','CAHUANACON',NULL,'933888978',NULL,'1','JR CUSCO',NULL,NULL,'2026-05-13 17:23:35','2026-05-13 17:23:35'),(126,'1','61537226','WILLIAM','AÑAMURO','CHOQUEHUANCA',NULL,'994405520',NULL,NULL,NULL,NULL,NULL,'2026-05-14 15:46:07','2026-05-14 15:46:07'),(127,'1','61881330','JOSE FERNANDO','MAMANI','MAMANI','2010-01-14','987877559',NULL,'1',NULL,NULL,NULL,'2026-05-14 15:47:03','2026-05-14 15:47:03'),(128,'1','60180601','HENRY RAUL','CARITA','CHOQUEMAMANI',NULL,'903011684',NULL,'1',NULL,NULL,NULL,'2026-05-14 15:50:14','2026-05-14 15:50:14'),(129,'1','44912726','CLEMENCIA','CCAPAYQUE','CCOARITE',NULL,'928349737',NULL,'2','AV TINTAYA',NULL,NULL,'2026-05-14 16:47:31','2026-05-14 16:47:31'),(130,'1','77238836','OMAR ALEXIS','RAMIREZ','QUISPE',NULL,'982429666',NULL,'1','AV TRIUNFO',NULL,NULL,'2026-05-14 17:04:50','2026-05-14 17:04:50'),(131,'1','61046952','HECTOR RAUL','COILA','NEYRA','2008-01-15','933555530',NULL,NULL,NULL,NULL,NULL,'2026-05-15 17:45:24','2026-05-15 17:45:24');
/*!40000 ALTER TABLE `core_persons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventory_brands`
--

DROP TABLE IF EXISTS `inventory_brands`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventory_brands` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `inventory_brands_name_unique` (`name`),
  KEY `inventory_brands_is_active_index` (`is_active`),
  KEY `inventory_brands_name_index` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory_brands`
--

LOCK TABLES `inventory_brands` WRITE;
/*!40000 ALTER TABLE `inventory_brands` DISABLE KEYS */;
INSERT INTO `inventory_brands` VALUES (1,'GENÉRICO',NULL,1,'2026-02-23 20:24:28','2026-02-23 20:24:28'),(2,'KEMEI',NULL,1,'2026-02-23 20:24:41','2026-02-23 20:24:41'),(3,'VGR',NULL,1,'2026-02-23 20:24:51','2026-02-23 20:24:51'),(4,'ANDIS',NULL,1,'2026-02-23 21:05:37','2026-02-23 21:05:37'),(5,'IMMORTAL',NULL,1,'2026-02-23 21:06:06','2026-02-23 21:06:06'),(6,'DORCO',NULL,1,'2026-02-24 21:49:35','2026-02-24 21:49:35'),(7,'LUXOR',NULL,1,'2026-02-24 23:52:37','2026-02-24 23:52:37');
/*!40000 ALTER TABLE `inventory_brands` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventory_categories`
--

DROP TABLE IF EXISTS `inventory_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventory_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('product') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'product',
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `inventory_categories_name_type_parent_id_unique` (`name`,`type`,`parent_id`),
  KEY `inventory_categories_parent_id_index` (`parent_id`),
  KEY `inventory_categories_type_index` (`type`),
  KEY `inventory_categories_is_active_index` (`is_active`),
  CONSTRAINT `inventory_categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `inventory_categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory_categories`
--

LOCK TABLES `inventory_categories` WRITE;
/*!40000 ALTER TABLE `inventory_categories` DISABLE KEYS */;
INSERT INTO `inventory_categories` VALUES (1,NULL,'MÁQUINAS / EQUIPOS ELECTRICOS','product',NULL,1,'2026-02-23 20:24:06','2026-02-23 21:03:55'),(2,NULL,'PEINES Y CEPILLOS','product',NULL,1,'2026-02-23 20:55:49','2026-02-23 20:55:49'),(3,NULL,'TIJERAS Y NAVAJAS','product',NULL,1,'2026-02-23 20:57:06','2026-02-23 20:57:06'),(4,NULL,'INSUMOS Y DESECHABLES','product',NULL,1,'2026-02-23 20:57:14','2026-02-23 21:03:06'),(5,NULL,'PRODUCTOS CABELLO','product',NULL,1,'2026-02-23 21:02:07','2026-02-23 21:02:07'),(6,NULL,'PRODUCTO HIGIENE Y DESINFECCION','product',NULL,1,'2026-02-23 21:02:46','2026-02-23 21:02:46'),(7,NULL,'UNIFORME','product',NULL,1,'2026-02-23 21:20:45','2026-02-23 21:20:45'),(8,NULL,'MATERIAL DE TRABAJO','product',NULL,1,'2026-02-23 21:47:04','2026-02-23 21:47:04');
/*!40000 ALTER TABLE `inventory_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventory_kardex`
--

DROP TABLE IF EXISTS `inventory_kardex`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventory_kardex` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `presentation_id` bigint unsigned NOT NULL,
  `infrastructure_id` bigint unsigned NOT NULL,
  `movement_type` enum('in','out','adjustment') COLLATE utf8mb4_unicode_ci NOT NULL,
  `reason` enum('purchase','sale','service_use','enrollment','waste','return','transfer','initial','adjustment') COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int NOT NULL,
  `unit_cost` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total_cost` decimal(10,2) NOT NULL DEFAULT '0.00',
  `balance_quantity` int NOT NULL DEFAULT '0',
  `balance_unit_cost` decimal(10,2) NOT NULL DEFAULT '0.00',
  `balance_total_cost` decimal(10,2) NOT NULL DEFAULT '0.00',
  `reference_id` bigint unsigned DEFAULT NULL,
  `reference_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `inventory_kardex_product_id_index` (`product_id`),
  KEY `inventory_kardex_presentation_id_index` (`presentation_id`),
  KEY `inventory_kardex_infrastructure_id_index` (`infrastructure_id`),
  KEY `inventory_kardex_movement_type_index` (`movement_type`),
  KEY `inventory_kardex_reason_index` (`reason`),
  KEY `inventory_kardex_created_by_index` (`created_by`),
  KEY `inventory_kardex_created_at_index` (`created_at`),
  KEY `inventory_kardex_presentation_id_infrastructure_id_index` (`presentation_id`,`infrastructure_id`),
  KEY `inventory_kardex_reference_id_reference_type_index` (`reference_id`,`reference_type`),
  CONSTRAINT `inventory_kardex_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `auth_users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `inventory_kardex_infrastructure_id_foreign` FOREIGN KEY (`infrastructure_id`) REFERENCES `core_infrastructures` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `inventory_kardex_presentation_id_foreign` FOREIGN KEY (`presentation_id`) REFERENCES `inventory_product_presentations` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `inventory_kardex_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `inventory_products` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory_kardex`
--

LOCK TABLES `inventory_kardex` WRITE;
/*!40000 ALTER TABLE `inventory_kardex` DISABLE KEYS */;
INSERT INTO `inventory_kardex` VALUES (1,2,8,1,'in','initial',16,170.00,2720.00,16,170.00,2720.00,NULL,NULL,'PRODUCTO DE REGALO POR MATRICULA',2,'2026-03-03 04:53:23','2026-03-03 04:53:23'),(2,3,9,1,'in','initial',25,10.00,250.00,25,10.00,250.00,NULL,NULL,NULL,2,'2026-03-03 04:58:56','2026-03-03 04:58:56'),(3,5,6,1,'in','initial',2,75.00,150.00,2,75.00,150.00,NULL,NULL,NULL,2,'2026-03-03 05:06:28','2026-03-03 05:06:28'),(4,6,7,1,'in','initial',39,0.00,0.00,39,0.00,0.00,NULL,NULL,NULL,2,'2026-03-03 05:07:09','2026-03-03 05:07:09'),(5,4,11,1,'in','initial',2,10.00,20.00,2,10.00,20.00,NULL,NULL,NULL,2,'2026-03-03 05:09:41','2026-03-03 05:09:41'),(6,7,10,1,'in','initial',16,60.00,960.00,16,60.00,960.00,NULL,NULL,NULL,2,'2026-03-04 01:13:13','2026-03-04 01:13:13'),(7,40,12,1,'in','initial',116,30.00,3480.00,116,30.00,3480.00,NULL,NULL,NULL,2,'2026-03-04 03:08:11','2026-03-04 03:08:11'),(8,40,12,1,'adjustment','adjustment',-1,0.00,0.00,115,0.00,0.00,NULL,NULL,NULL,2,'2026-03-04 17:05:42','2026-03-04 17:05:42'),(9,40,12,1,'adjustment','adjustment',-1,0.00,0.00,114,0.00,0.00,NULL,NULL,NULL,2,'2026-03-04 21:34:16','2026-03-04 21:34:16'),(10,40,12,1,'adjustment','adjustment',-1,0.00,0.00,113,0.00,0.00,NULL,NULL,NULL,2,'2026-03-04 21:34:53','2026-03-04 21:34:53'),(11,40,12,1,'adjustment','adjustment',7,0.00,0.00,120,0.00,0.00,NULL,NULL,NULL,2,'2026-03-05 19:43:25','2026-03-05 19:43:25'),(12,40,12,1,'adjustment','adjustment',-8,0.00,0.00,112,0.00,0.00,NULL,NULL,NULL,2,'2026-03-05 19:43:34','2026-03-05 19:43:34'),(13,40,12,1,'adjustment','adjustment',-1,0.00,0.00,111,0.00,0.00,NULL,NULL,NULL,2,'2026-03-06 13:52:47','2026-03-06 13:52:47');
/*!40000 ALTER TABLE `inventory_kardex` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventory_product_presentations`
--

DROP TABLE IF EXISTS `inventory_product_presentations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventory_product_presentations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `sku` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit_type` enum('unit','box','pack','bottle','tube','blister','bag','display','dozen') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unit',
  `quantity` int NOT NULL DEFAULT '1',
  `barcode` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `min_stock` int NOT NULL DEFAULT '0',
  `max_stock` int NOT NULL DEFAULT '0',
  `cost_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `sale_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `inventory_product_presentations_product_id_name_unique` (`product_id`,`name`),
  UNIQUE KEY `inventory_product_presentations_sku_unique` (`sku`),
  UNIQUE KEY `inventory_product_presentations_barcode_unique` (`barcode`),
  KEY `inventory_product_presentations_product_id_index` (`product_id`),
  KEY `inventory_product_presentations_sku_index` (`sku`),
  KEY `inventory_product_presentations_unit_type_index` (`unit_type`),
  KEY `inventory_product_presentations_is_default_index` (`is_default`),
  KEY `inventory_product_presentations_is_active_index` (`is_active`),
  CONSTRAINT `inventory_product_presentations_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `inventory_products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory_product_presentations`
--

LOCK TABLES `inventory_product_presentations` WRITE;
/*!40000 ALTER TABLE `inventory_product_presentations` DISABLE KEYS */;
INSERT INTO `inventory_product_presentations` VALUES (2,5,'SKU-000002','CASACA TALLA M','unit',1,NULL,6,20,75.00,75.00,0,1,'2026-03-03 04:39:11','2026-03-03 04:39:11'),(3,5,'SKU-000003','CASACA TALLA S','unit',1,NULL,6,20,75.00,75.00,0,1,'2026-03-03 04:41:28','2026-03-03 04:41:28'),(4,5,'SKU-000004','CASACA TALLA L','unit',1,NULL,6,20,75.00,75.00,1,1,'2026-03-03 04:42:53','2026-03-03 04:42:53'),(5,5,'SKU-000005','CASACA TALLA XL','unit',1,NULL,6,20,75.00,75.00,0,1,'2026-03-03 04:43:15','2026-03-03 04:43:15'),(6,5,'SKU-000006','CASACA TALLA XXL','unit',1,NULL,6,20,75.00,75.00,0,1,'2026-03-03 04:43:36','2026-03-03 04:43:36'),(7,6,'SKU-000007','GORRO DE SOL','unit',1,NULL,12,200,20.00,20.00,1,1,'2026-03-03 04:45:35','2026-03-03 04:45:35'),(8,2,'SKU-000008','MAQUINA CLIPPER','unit',1,NULL,6,100,170.00,170.00,1,1,'2026-03-03 04:50:27','2026-03-03 04:50:27'),(9,3,'SKU-000009','REVISTA GUIA DE BARBERO','unit',1,NULL,12,200,10.00,10.00,1,1,'2026-03-03 04:58:32','2026-03-03 04:58:32'),(10,7,'SKU-000010','MOCHILA NEGRA','unit',1,NULL,6,200,75.00,75.00,1,1,'2026-03-03 05:06:06','2026-03-03 05:06:06'),(11,4,'SKU-000011','CARPETA DE TRABAJO ANILLADO','unit',1,NULL,6,200,10.00,10.00,1,1,'2026-03-03 05:09:27','2026-03-03 05:09:27'),(12,40,'SKU-000012','AGUA CIELO 625 ML','pack',120,NULL,0,0,10.00,30.00,1,1,'2026-03-04 01:29:39','2026-03-04 01:29:39');
/*!40000 ALTER TABLE `inventory_product_presentations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventory_products`
--

DROP TABLE IF EXISTS `inventory_products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventory_products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `category_id` bigint unsigned NOT NULL,
  `brand_id` bigint unsigned DEFAULT NULL,
  `is_for_sale` tinyint(1) NOT NULL DEFAULT '0',
  `is_for_internal` tinyint(1) NOT NULL DEFAULT '1',
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `inventory_products_name_index` (`name`),
  KEY `inventory_products_category_id_index` (`category_id`),
  KEY `inventory_products_brand_id_index` (`brand_id`),
  KEY `inventory_products_is_for_sale_index` (`is_for_sale`),
  KEY `inventory_products_is_for_internal_index` (`is_for_internal`),
  KEY `inventory_products_is_active_index` (`is_active`),
  CONSTRAINT `inventory_products_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `inventory_brands` (`id`) ON DELETE SET NULL,
  CONSTRAINT `inventory_products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `inventory_categories` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory_products`
--

LOCK TABLES `inventory_products` WRITE;
/*!40000 ALTER TABLE `inventory_products` DISABLE KEYS */;
INSERT INTO `inventory_products` VALUES (1,'MAQUINA TRIMMER',NULL,1,2,1,1,NULL,1,'2026-02-23 20:27:17','2026-02-23 20:27:17'),(2,'MAQUINA INALAMBRICA',NULL,1,2,1,1,NULL,1,'2026-02-23 20:33:36','2026-02-23 20:33:36'),(3,'REVISTA GUIA',NULL,8,1,1,1,NULL,1,'2026-02-23 21:57:10','2026-02-23 21:57:10'),(4,'CARPETA DE TRABAJO',NULL,8,1,1,0,NULL,1,'2026-02-23 21:58:49','2026-02-23 21:58:49'),(5,'CASACAS DE ESCUELA',NULL,7,1,1,1,NULL,1,'2026-02-23 21:59:41','2026-02-23 21:59:41'),(6,'GORRO DE SOL',NULL,7,1,1,1,NULL,1,'2026-02-23 22:00:12','2026-02-23 22:00:12'),(7,'MOCHILA',NULL,7,1,1,1,NULL,1,'2026-02-23 22:02:10','2026-02-23 22:02:10'),(8,'ACEITE DE MAQUINA',NULL,4,1,1,1,NULL,1,'2026-02-23 22:04:38','2026-02-23 22:04:38'),(9,'AFTER SHAVE',NULL,6,1,1,1,NULL,1,'2026-02-23 22:05:47','2026-02-23 22:05:47'),(10,'BOTA PELO (MADERA)',NULL,6,1,1,1,NULL,1,'2026-02-24 21:35:56','2026-02-24 21:35:56'),(11,'BRUSH',NULL,6,1,1,1,NULL,1,'2026-02-24 21:37:04','2026-02-24 21:37:04'),(12,'CAPA DE ESCUELA',NULL,6,1,1,1,NULL,1,'2026-02-24 21:38:02','2026-02-24 21:38:02'),(13,'CERA',NULL,5,5,1,1,NULL,1,'2026-02-24 21:39:25','2026-02-24 21:39:25'),(14,'COOL CARE DESINFECTANTE',NULL,6,4,1,1,NULL,1,'2026-02-24 21:39:58','2026-02-24 21:39:58'),(15,'FIGARO',NULL,2,1,1,0,NULL,1,'2026-02-24 21:40:28','2026-02-24 21:40:28'),(16,'GANCHOS DE METAL',NULL,5,1,1,0,NULL,1,'2026-02-24 21:41:08','2026-02-24 21:41:08'),(17,'GEL SIN ALCOHOL',NULL,5,1,1,1,NULL,1,'2026-02-24 21:41:45','2026-02-24 21:41:45'),(18,'GUANTES',NULL,4,1,1,1,NULL,1,'2026-02-24 21:49:06','2026-02-24 21:49:06'),(19,'HOJILLAS',NULL,4,6,1,1,NULL,1,'2026-02-24 21:49:56','2026-02-24 21:49:56'),(20,'JUEGO DE PEINETAS',NULL,2,1,1,1,NULL,1,'2026-02-24 21:50:27','2026-02-24 21:50:27'),(21,'KISS EXPRESS',NULL,5,1,1,0,NULL,1,'2026-02-24 21:50:53','2026-02-24 21:50:53'),(22,'LACA',NULL,5,5,1,1,NULL,1,'2026-02-24 21:51:40','2026-02-24 21:51:40'),(23,'MAQUINA SHAVER',NULL,1,2,1,1,NULL,1,'2026-02-24 21:52:21','2026-02-24 21:52:21'),(24,'NAVAJERA',NULL,3,1,1,1,NULL,1,'2026-02-24 21:54:35','2026-02-24 21:54:35'),(25,'PALETA DE SILICONA',NULL,4,1,1,1,NULL,1,'2026-02-24 22:00:13','2026-02-24 22:00:13'),(26,'PAPEL DE CUELLO',NULL,4,1,1,1,NULL,1,'2026-02-24 22:00:46','2026-02-24 22:00:46'),(27,'PEINE DE MAQUINA',NULL,2,1,1,1,NULL,1,'2026-02-24 22:01:09','2026-02-24 22:01:09'),(28,'PEINE DELGADO',NULL,2,1,1,1,NULL,1,'2026-02-24 22:01:34','2026-02-24 22:01:34'),(29,'PEINE MILIMETRICO',NULL,2,1,1,1,NULL,1,'2026-02-24 22:02:09','2026-02-24 22:02:09'),(30,'PEINE FLAT TOP',NULL,2,1,1,1,NULL,1,'2026-02-24 22:03:51','2026-02-24 22:03:51'),(31,'PEINE TEXTURIZADOR',NULL,2,1,1,1,NULL,1,'2026-02-24 22:04:09','2026-02-24 22:04:09'),(32,'PINCEL DE PIGMENTACION',NULL,4,1,1,0,NULL,1,'2026-02-24 22:05:00','2026-02-24 22:05:00'),(33,'POLVO CICATRIZANTE',NULL,6,1,1,0,NULL,1,'2026-02-24 22:05:24','2026-02-24 22:05:24'),(34,'POLVO TEXTURIZADOR',NULL,5,1,1,1,NULL,1,'2026-02-24 22:05:56','2026-02-24 22:05:56'),(35,'ROCIADOR',NULL,5,1,1,1,NULL,1,'2026-02-24 22:06:31','2026-02-24 22:06:31'),(36,'SECADORA',NULL,1,3,1,0,NULL,1,'2026-02-24 23:33:45','2026-02-24 23:33:45'),(37,'SHAVING GEL',NULL,6,1,1,1,NULL,1,'2026-02-24 23:34:12','2026-02-24 23:34:12'),(38,'TALCO',NULL,6,1,1,1,NULL,1,'2026-02-24 23:35:39','2026-02-24 23:35:39'),(39,'TIJERAS',NULL,3,7,1,1,NULL,1,'2026-02-24 23:53:00','2026-02-24 23:53:00'),(40,'AGUA CORTESIA',NULL,4,1,1,1,NULL,1,'2026-02-25 00:09:18','2026-02-25 00:09:18'),(41,'PAÑOS HUMEDOS',NULL,4,1,1,1,NULL,1,'2026-02-25 00:09:47','2026-02-25 00:09:47'),(42,'TOALLA DE LAVAR - NEGRO',NULL,6,1,1,0,NULL,1,'2026-02-25 19:05:10','2026-02-25 19:05:10'),(43,'TOALLA PAPEL DE MANO',NULL,6,1,1,1,NULL,1,'2026-02-25 19:06:08','2026-02-25 19:06:08'),(44,'TRU BARBER',NULL,5,1,1,0,NULL,1,'2026-02-25 19:34:37','2026-02-25 19:34:37'),(45,'SKALA',NULL,5,1,1,1,NULL,1,'2026-02-25 20:05:07','2026-02-25 20:05:07');
/*!40000 ALTER TABLE `inventory_products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventory_purchase_order_items`
--

DROP TABLE IF EXISTS `inventory_purchase_order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventory_purchase_order_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `purchase_order_id` bigint unsigned NOT NULL,
  `presentation_id` bigint unsigned NOT NULL,
  `quantity_ordered` int NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `inventory_purchase_order_items_purchase_order_id_index` (`purchase_order_id`),
  KEY `inventory_purchase_order_items_presentation_id_index` (`presentation_id`),
  CONSTRAINT `inventory_purchase_order_items_presentation_id_foreign` FOREIGN KEY (`presentation_id`) REFERENCES `inventory_product_presentations` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `inventory_purchase_order_items_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `inventory_purchase_orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory_purchase_order_items`
--

LOCK TABLES `inventory_purchase_order_items` WRITE;
/*!40000 ALTER TABLE `inventory_purchase_order_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `inventory_purchase_order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventory_purchase_orders`
--

DROP TABLE IF EXISTS `inventory_purchase_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventory_purchase_orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `supplier_id` bigint unsigned NOT NULL,
  `infrastructure_id` bigint unsigned NOT NULL,
  `order_number` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `receipt_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `receipt_serie` char(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `receipt_number` int unsigned DEFAULT NULL,
  `status` enum('pending','received','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `order_date` date NOT NULL,
  `expected_date` date DEFAULT NULL,
  `received_date` date DEFAULT NULL,
  `total_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `inventory_purchase_orders_order_number_unique` (`order_number`),
  KEY `inventory_purchase_orders_supplier_id_index` (`supplier_id`),
  KEY `inventory_purchase_orders_infrastructure_id_index` (`infrastructure_id`),
  KEY `inventory_purchase_orders_status_index` (`status`),
  KEY `inventory_purchase_orders_order_date_index` (`order_date`),
  KEY `inventory_purchase_orders_created_by_index` (`created_by`),
  CONSTRAINT `inventory_purchase_orders_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `auth_users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `inventory_purchase_orders_infrastructure_id_foreign` FOREIGN KEY (`infrastructure_id`) REFERENCES `core_infrastructures` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `inventory_purchase_orders_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `inventory_suppliers` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory_purchase_orders`
--

LOCK TABLES `inventory_purchase_orders` WRITE;
/*!40000 ALTER TABLE `inventory_purchase_orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `inventory_purchase_orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventory_sale_items`
--

DROP TABLE IF EXISTS `inventory_sale_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventory_sale_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sale_id` bigint unsigned NOT NULL,
  `presentation_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `unit_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `discount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `inventory_sale_items_sale_id_index` (`sale_id`),
  KEY `inventory_sale_items_presentation_id_index` (`presentation_id`),
  CONSTRAINT `inventory_sale_items_presentation_id_foreign` FOREIGN KEY (`presentation_id`) REFERENCES `inventory_product_presentations` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `inventory_sale_items_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `inventory_sales` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory_sale_items`
--

LOCK TABLES `inventory_sale_items` WRITE;
/*!40000 ALTER TABLE `inventory_sale_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `inventory_sale_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventory_sales`
--

DROP TABLE IF EXISTS `inventory_sales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventory_sales` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `infrastructure_id` bigint unsigned NOT NULL,
  `cash_session_id` bigint unsigned DEFAULT NULL,
  `person_id` bigint unsigned DEFAULT NULL,
  `barbershop_ticket_id` bigint unsigned DEFAULT NULL,
  `context` enum('barbershop','academy','other') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'other',
  `subtotal` decimal(12,2) NOT NULL DEFAULT '0.00',
  `discount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total` decimal(12,2) NOT NULL DEFAULT '0.00',
  `status` enum('pending','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'completed',
  `user_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `inventory_sales_barbershop_ticket_id_foreign` (`barbershop_ticket_id`),
  KEY `inventory_sales_infrastructure_id_index` (`infrastructure_id`),
  KEY `inventory_sales_cash_session_id_index` (`cash_session_id`),
  KEY `inventory_sales_person_id_index` (`person_id`),
  KEY `inventory_sales_context_index` (`context`),
  KEY `inventory_sales_status_index` (`status`),
  KEY `inventory_sales_user_id_index` (`user_id`),
  CONSTRAINT `inventory_sales_barbershop_ticket_id_foreign` FOREIGN KEY (`barbershop_ticket_id`) REFERENCES `barbershop_tickets` (`id`) ON DELETE SET NULL,
  CONSTRAINT `inventory_sales_cash_session_id_foreign` FOREIGN KEY (`cash_session_id`) REFERENCES `treasury_cash_sessions` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `inventory_sales_infrastructure_id_foreign` FOREIGN KEY (`infrastructure_id`) REFERENCES `core_infrastructures` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `inventory_sales_person_id_foreign` FOREIGN KEY (`person_id`) REFERENCES `core_persons` (`id`) ON DELETE SET NULL,
  CONSTRAINT `inventory_sales_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `auth_users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory_sales`
--

LOCK TABLES `inventory_sales` WRITE;
/*!40000 ALTER TABLE `inventory_sales` DISABLE KEYS */;
/*!40000 ALTER TABLE `inventory_sales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventory_stocks`
--

DROP TABLE IF EXISTS `inventory_stocks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventory_stocks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `presentation_id` bigint unsigned NOT NULL,
  `infrastructure_id` bigint unsigned NOT NULL,
  `current_stock` int NOT NULL DEFAULT '0',
  `last_movement_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `inventory_stocks_presentation_id_infrastructure_id_unique` (`presentation_id`,`infrastructure_id`),
  KEY `inventory_stocks_product_id_index` (`product_id`),
  KEY `inventory_stocks_presentation_id_index` (`presentation_id`),
  KEY `inventory_stocks_infrastructure_id_index` (`infrastructure_id`),
  KEY `inventory_stocks_current_stock_index` (`current_stock`),
  CONSTRAINT `inventory_stocks_infrastructure_id_foreign` FOREIGN KEY (`infrastructure_id`) REFERENCES `core_infrastructures` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `inventory_stocks_presentation_id_foreign` FOREIGN KEY (`presentation_id`) REFERENCES `inventory_product_presentations` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `inventory_stocks_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `inventory_products` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory_stocks`
--

LOCK TABLES `inventory_stocks` WRITE;
/*!40000 ALTER TABLE `inventory_stocks` DISABLE KEYS */;
INSERT INTO `inventory_stocks` VALUES (1,2,8,1,15,'2026-03-07 21:55:39','2026-03-03 04:53:23','2026-03-07 21:55:39'),(2,3,9,1,24,'2026-03-07 21:55:39','2026-03-03 04:58:56','2026-03-07 21:55:39'),(3,5,6,1,1,'2026-03-07 21:55:39','2026-03-03 05:06:28','2026-03-07 21:55:39'),(4,6,7,1,38,'2026-03-07 21:55:39','2026-03-03 05:07:09','2026-03-07 21:55:39'),(5,4,11,1,1,'2026-03-07 21:55:39','2026-03-03 05:09:41','2026-03-07 21:55:39'),(6,7,10,1,15,'2026-03-07 21:55:39','2026-03-04 01:13:13','2026-03-07 21:55:39'),(7,40,12,1,111,'2026-03-06 13:52:47','2026-03-04 03:08:11','2026-03-06 13:52:47');
/*!40000 ALTER TABLE `inventory_stocks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventory_suppliers`
--

DROP TABLE IF EXISTS `inventory_suppliers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventory_suppliers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `inventory_suppliers_is_active_index` (`is_active`),
  KEY `inventory_suppliers_name_index` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory_suppliers`
--

LOCK TABLES `inventory_suppliers` WRITE;
/*!40000 ALTER TABLE `inventory_suppliers` DISABLE KEYS */;
/*!40000 ALTER TABLE `inventory_suppliers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2026_01_24_233406_create_core_tables',1),(2,'2026_01_25_022447_create_security_tables',1),(3,'2026_01_25_022457_create_profile_tables',1),(4,'2026_02_25_000000_create_treasury_tables',1),(5,'2026_02_25_154900_create_barbershop_tables',1),(6,'2026_02_26_204823_create_inventory_tables',1),(7,'2026_02_27_204823_create_academy_tables',1),(8,'2026_03_14_221455_create_profile_admin_infrastructures_table',1),(9,'2026_03_17_000000_create_academy_teacher_attendances_table',1),(10,'2026_03_17_010000_create_treasury_employee_payments_tables',1),(11,'2026_03_19_000000_create_worker_attendances_table',1),(12,'2026_03_21_000000_create_academy_student_guardians_table',1),(13,'2026_03_21_100000_create_core_companies_table',1),(14,'2026_03_21_110000_create_core_files_table',1),(15,'2026_03_22_000000_create_treasury_expense_tables',1),(16,'2026_03_24_000000_create_barber_attendances_table',1),(17,'2026_03_24_132014_create_reports_table',1),(18,'2026_04_02_000000_create_treasury_employee_schedules_table',1),(19,'2026_04_06_000000_create_calendar_holidays_table',1),(20,'2026_05_01_000000_create_academy_enrollment_group_changes_table',1),(21,'2026_05_02_000001_create_treasury_income_audits_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `profile_admin_infrastructures`
--

DROP TABLE IF EXISTS `profile_admin_infrastructures`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `profile_admin_infrastructures` (
  `profile_admin_id` bigint unsigned NOT NULL,
  `core_infrastructure_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`profile_admin_id`,`core_infrastructure_id`),
  KEY `profile_admin_infrastructures_core_infrastructure_id_foreign` (`core_infrastructure_id`),
  CONSTRAINT `profile_admin_infrastructures_core_infrastructure_id_foreign` FOREIGN KEY (`core_infrastructure_id`) REFERENCES `core_infrastructures` (`id`) ON DELETE CASCADE,
  CONSTRAINT `profile_admin_infrastructures_profile_admin_id_foreign` FOREIGN KEY (`profile_admin_id`) REFERENCES `profile_admins` (`core_person_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `profile_admin_infrastructures`
--

LOCK TABLES `profile_admin_infrastructures` WRITE;
/*!40000 ALTER TABLE `profile_admin_infrastructures` DISABLE KEYS */;
INSERT INTO `profile_admin_infrastructures` VALUES (2,1),(84,1),(110,1),(2,2),(84,2),(109,2),(110,2),(2,3),(84,3),(109,3),(110,3),(2,4),(84,4),(110,4),(2,5),(84,5),(110,5);
/*!40000 ALTER TABLE `profile_admin_infrastructures` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `profile_admins`
--

DROP TABLE IF EXISTS `profile_admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `profile_admins` (
  `core_person_id` bigint unsigned NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`core_person_id`),
  KEY `profile_admins_is_active_index` (`is_active`),
  CONSTRAINT `profile_admins_core_person_id_foreign` FOREIGN KEY (`core_person_id`) REFERENCES `core_persons` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `profile_admins`
--

LOCK TABLES `profile_admins` WRITE;
/*!40000 ALTER TABLE `profile_admins` DISABLE KEYS */;
INSERT INTO `profile_admins` VALUES (1,1,'2026-02-17 02:40:08','2026-02-17 02:40:08'),(2,1,'2026-02-17 02:41:53','2026-02-17 02:41:53'),(84,1,'2026-05-11 17:15:58','2026-05-11 17:15:58'),(109,1,'2026-05-11 17:17:30','2026-05-11 17:17:30'),(110,1,'2026-05-11 17:18:51','2026-05-11 17:18:51');
/*!40000 ALTER TABLE `profile_admins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `profile_barbers`
--

DROP TABLE IF EXISTS `profile_barbers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `profile_barbers` (
  `id` bigint unsigned NOT NULL,
  `branch_id` bigint unsigned NOT NULL,
  `commission_percentage` decimal(5,2) NOT NULL DEFAULT '0.00',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `profile_barbers_branch_id_index` (`branch_id`),
  KEY `profile_barbers_is_active_index` (`is_active`),
  CONSTRAINT `profile_barbers_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `barbershop_branches` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `profile_barbers_id_foreign` FOREIGN KEY (`id`) REFERENCES `core_persons` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `profile_barbers`
--

LOCK TABLES `profile_barbers` WRITE;
/*!40000 ALTER TABLE `profile_barbers` DISABLE KEYS */;
INSERT INTO `profile_barbers` VALUES (43,1,45.00,1,'2026-05-13 15:45:57','2026-05-13 15:45:57'),(66,2,45.00,1,'2026-05-11 17:22:51','2026-05-11 17:22:51'),(67,3,45.00,1,'2026-05-11 17:23:16','2026-05-11 17:23:16'),(68,1,45.00,1,'2026-05-13 11:30:07','2026-05-13 11:30:07'),(69,1,45.00,1,'2026-05-11 17:22:26','2026-05-11 17:22:26'),(71,2,40.00,1,'2026-05-11 17:23:44','2026-05-11 17:23:44'),(72,2,45.00,1,'2026-05-11 17:24:12','2026-05-11 17:24:12'),(73,2,40.00,1,'2026-05-13 11:28:24','2026-05-13 11:28:24'),(74,1,45.00,1,'2026-05-13 11:27:05','2026-05-13 11:27:05'),(77,1,45.00,1,'2026-05-13 11:26:04','2026-05-13 11:26:04'),(78,3,45.00,1,'2026-05-13 11:24:43','2026-05-13 11:24:43'),(79,3,45.00,1,'2026-05-13 11:23:27','2026-05-13 11:23:27'),(80,4,45.00,1,'2026-05-13 11:22:21','2026-05-13 11:22:21'),(82,4,40.00,1,'2026-05-13 11:21:34','2026-05-13 11:21:34'),(124,4,40.00,1,'2026-05-13 15:45:10','2026-05-13 15:45:10');
/*!40000 ALTER TABLE `profile_barbers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `profile_clients`
--

DROP TABLE IF EXISTS `profile_clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `profile_clients` (
  `id` bigint unsigned NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `profile_clients_is_active_index` (`is_active`),
  CONSTRAINT `profile_clients_id_foreign` FOREIGN KEY (`id`) REFERENCES `core_persons` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `profile_clients`
--

LOCK TABLES `profile_clients` WRITE;
/*!40000 ALTER TABLE `profile_clients` DISABLE KEYS */;
INSERT INTO `profile_clients` VALUES (65,1,'2026-03-06 16:36:33','2026-03-06 16:36:33');
/*!40000 ALTER TABLE `profile_clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `profile_students`
--

DROP TABLE IF EXISTS `profile_students`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `profile_students` (
  `core_person_id` bigint unsigned NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`core_person_id`),
  KEY `profile_students_is_active_index` (`is_active`),
  CONSTRAINT `profile_students_core_person_id_foreign` FOREIGN KEY (`core_person_id`) REFERENCES `core_persons` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `profile_students`
--

LOCK TABLES `profile_students` WRITE;
/*!40000 ALTER TABLE `profile_students` DISABLE KEYS */;
INSERT INTO `profile_students` VALUES (6,1,'2026-02-20 00:20:41','2026-02-20 00:20:41'),(7,1,'2026-02-20 00:23:35','2026-02-20 00:23:35'),(8,1,'2026-02-20 00:24:45','2026-02-20 00:24:45'),(9,1,'2026-02-20 00:25:53','2026-02-20 00:25:53'),(10,1,'2026-02-20 00:26:58','2026-02-20 00:26:58'),(11,1,'2026-02-20 00:27:53','2026-02-20 00:27:53'),(12,1,'2026-02-20 00:28:58','2026-02-20 00:28:58'),(13,1,'2026-02-20 00:30:01','2026-02-20 00:30:01'),(14,1,'2026-02-20 00:31:13','2026-02-20 00:31:13'),(15,1,'2026-02-20 00:32:07','2026-02-20 00:32:07'),(16,1,'2026-02-20 00:33:23','2026-02-20 00:33:23'),(17,1,'2026-02-20 00:34:20','2026-02-20 00:34:20'),(18,1,'2026-02-20 00:41:41','2026-02-20 00:41:41'),(19,1,'2026-02-20 00:48:29','2026-02-20 00:48:29'),(20,1,'2026-02-20 00:49:28','2026-02-20 00:49:28'),(21,1,'2026-02-20 01:04:24','2026-02-20 01:04:24'),(22,1,'2026-02-21 05:31:14','2026-02-21 05:31:14'),(23,1,'2026-02-21 05:32:19','2026-02-21 05:32:19'),(24,1,'2026-02-21 05:43:15','2026-02-21 05:43:15'),(25,1,'2026-02-21 05:46:50','2026-02-21 05:46:50'),(26,1,'2026-02-21 05:48:24','2026-02-21 05:48:24'),(27,1,'2026-02-21 05:50:23','2026-02-21 05:50:23'),(28,1,'2026-02-21 05:55:08','2026-02-21 05:55:08'),(29,1,'2026-02-21 05:58:16','2026-02-21 05:58:16'),(30,1,'2026-02-21 05:59:12','2026-02-21 05:59:12'),(31,1,'2026-02-21 06:03:25','2026-02-21 06:03:25'),(32,1,'2026-02-21 06:05:54','2026-02-21 06:05:54'),(33,1,'2026-02-21 06:10:05','2026-02-21 06:10:05'),(34,1,'2026-02-21 06:11:30','2026-02-21 06:11:30'),(35,1,'2026-02-21 06:14:01','2026-02-21 06:14:01'),(36,1,'2026-02-21 09:35:01','2026-02-21 09:35:01'),(37,1,'2026-02-21 09:37:51','2026-02-21 09:37:51'),(38,1,'2026-02-21 09:38:59','2026-02-21 09:38:59'),(39,1,'2026-02-21 09:40:25','2026-02-21 09:40:25'),(44,1,'2026-03-03 04:23:41','2026-03-03 04:23:41'),(45,1,'2026-03-03 05:10:36','2026-03-03 05:10:36'),(46,1,'2026-03-03 05:11:32','2026-03-03 05:11:32'),(47,1,'2026-03-03 05:12:09','2026-03-03 05:12:09'),(48,1,'2026-03-03 05:12:57','2026-03-03 05:12:57'),(49,1,'2026-03-03 05:14:51','2026-03-03 05:14:51'),(50,1,'2026-03-03 05:22:29','2026-03-03 05:22:29'),(51,1,'2026-03-03 05:23:03','2026-03-03 05:23:03'),(52,1,'2026-03-03 05:23:31','2026-03-03 05:23:31'),(53,1,'2026-03-03 05:24:23','2026-03-03 05:24:23'),(54,1,'2026-03-03 05:25:38','2026-03-03 05:25:38'),(55,1,'2026-03-03 05:26:18','2026-03-03 05:26:18'),(56,1,'2026-03-03 05:26:54','2026-03-03 05:26:54'),(57,1,'2026-03-03 05:27:56','2026-03-03 05:27:56'),(58,1,'2026-03-03 19:30:47','2026-03-03 19:30:47'),(59,1,'2026-03-03 19:32:40','2026-03-03 19:32:40'),(60,1,'2026-03-03 19:33:57','2026-03-03 19:33:57'),(61,1,'2026-03-04 00:59:38','2026-03-04 00:59:38'),(62,1,'2026-03-04 01:00:27','2026-03-04 01:00:27'),(63,1,'2026-03-04 01:01:32','2026-03-04 01:01:32'),(64,1,'2026-03-04 16:57:04','2026-03-04 16:57:04'),(85,1,'2026-03-06 20:02:43','2026-03-06 20:02:43'),(86,1,'2026-03-06 22:44:21','2026-03-06 22:44:21'),(87,1,'2026-03-07 21:27:01','2026-03-07 21:27:01'),(88,1,'2026-03-07 21:27:51','2026-03-07 21:27:51'),(89,1,'2026-03-07 22:21:04','2026-03-07 22:21:04'),(90,1,'2026-03-09 17:03:01','2026-03-09 17:03:01'),(91,1,'2026-03-09 17:04:12','2026-03-09 17:04:12'),(92,1,'2026-03-09 20:50:55','2026-03-09 20:50:55'),(93,1,'2026-03-09 20:51:34','2026-03-09 20:51:34'),(94,1,'2026-03-09 23:50:34','2026-03-09 23:50:34'),(95,1,'2026-03-14 19:55:25','2026-03-14 19:55:25'),(96,1,'2026-03-14 20:14:54','2026-03-14 20:14:54'),(97,1,'2026-03-14 20:15:58','2026-03-14 20:15:58'),(98,1,'2026-03-14 20:17:52','2026-03-14 20:17:52'),(99,1,'2026-03-14 20:18:46','2026-03-14 20:18:46'),(100,1,'2026-03-14 20:19:37','2026-03-14 20:19:37'),(101,1,'2026-03-14 20:20:57','2026-03-14 20:20:57'),(102,1,'2026-03-14 20:54:46','2026-03-14 20:54:46'),(103,1,'2026-03-14 20:56:13','2026-03-14 20:56:13'),(104,1,'2026-03-14 20:58:11','2026-03-14 20:58:11'),(105,1,'2026-03-14 20:59:16','2026-03-14 20:59:16'),(106,1,'2026-03-14 21:04:04','2026-03-14 21:04:04'),(107,1,'2026-03-14 21:06:17','2026-03-14 21:06:17'),(108,1,'2026-03-14 21:07:59','2026-03-14 21:07:59'),(111,1,'2026-05-12 17:43:27','2026-05-12 17:43:27'),(112,1,'2026-05-13 15:09:19','2026-05-13 15:09:19'),(113,1,'2026-05-13 15:11:32','2026-05-13 15:11:32'),(114,1,'2026-05-13 15:12:40','2026-05-13 15:12:40'),(115,1,'2026-05-13 15:13:28','2026-05-13 15:13:28'),(116,1,'2026-05-13 15:14:47','2026-05-13 15:14:47'),(117,1,'2026-05-13 15:15:39','2026-05-13 15:15:39'),(118,1,'2026-05-13 15:16:55','2026-05-13 15:16:55'),(119,1,'2026-05-13 15:18:11','2026-05-13 15:18:11'),(120,1,'2026-05-13 15:21:25','2026-05-13 15:21:25'),(121,1,'2026-05-13 15:22:29','2026-05-13 15:22:29'),(122,1,'2026-05-13 15:24:31','2026-05-13 15:24:31'),(125,1,'2026-05-13 17:23:35','2026-05-13 17:23:35'),(126,1,'2026-05-14 15:46:08','2026-05-14 15:46:08'),(127,1,'2026-05-14 15:47:04','2026-05-14 15:47:04'),(128,1,'2026-05-14 15:50:15','2026-05-14 15:50:15'),(129,1,'2026-05-14 16:47:32','2026-05-14 16:47:32'),(130,1,'2026-05-14 17:04:51','2026-05-14 17:04:51'),(131,1,'2026-05-15 17:45:24','2026-05-15 17:45:24');
/*!40000 ALTER TABLE `profile_students` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `profile_teachers`
--

DROP TABLE IF EXISTS `profile_teachers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `profile_teachers` (
  `core_person_id` bigint unsigned NOT NULL,
  `branch_id` bigint unsigned DEFAULT NULL,
  `payment_type` enum('hourly','monthly') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `monthly_salary` decimal(10,2) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`core_person_id`),
  KEY `profile_teachers_branch_id_foreign` (`branch_id`),
  KEY `profile_teachers_payment_type_index` (`payment_type`),
  KEY `profile_teachers_is_active_index` (`is_active`),
  CONSTRAINT `profile_teachers_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `academy_branches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `profile_teachers_core_person_id_foreign` FOREIGN KEY (`core_person_id`) REFERENCES `core_persons` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `profile_teachers`
--

LOCK TABLES `profile_teachers` WRITE;
/*!40000 ALTER TABLE `profile_teachers` DISABLE KEYS */;
INSERT INTO `profile_teachers` VALUES (40,NULL,NULL,NULL,1,'2026-02-23 20:38:29','2026-02-23 20:38:29'),(41,NULL,NULL,NULL,1,'2026-02-23 22:28:05','2026-02-23 22:28:05'),(43,NULL,NULL,NULL,1,'2026-02-23 22:30:32','2026-02-23 22:30:32'),(68,1,'hourly',NULL,1,'2026-05-13 11:31:27','2026-05-13 11:31:27');
/*!40000 ALTER TABLE `profile_teachers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `profile_workers`
--

DROP TABLE IF EXISTS `profile_workers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `profile_workers` (
  `id` bigint unsigned NOT NULL,
  `infrastructure_id` bigint unsigned NOT NULL DEFAULT '1',
  `position` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `monthly_salary` decimal(10,2) DEFAULT NULL,
  `payment_frequency` enum('monthly','biweekly') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `profile_workers_is_active_index` (`is_active`),
  KEY `profile_workers_infrastructure_id_index` (`infrastructure_id`),
  CONSTRAINT `profile_workers_id_foreign` FOREIGN KEY (`id`) REFERENCES `core_persons` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `profile_workers`
--

LOCK TABLES `profile_workers` WRITE;
/*!40000 ALTER TABLE `profile_workers` DISABLE KEYS */;
INSERT INTO `profile_workers` VALUES (66,1,'barber',NULL,NULL,1,'2026-03-06 16:38:23','2026-03-06 16:38:23'),(67,1,'barber',NULL,NULL,1,'2026-03-06 16:39:42','2026-03-06 16:39:42'),(69,1,'barber',NULL,NULL,1,'2026-03-06 16:42:29','2026-03-06 16:42:29'),(70,1,'cashier',NULL,NULL,1,'2026-03-06 16:44:34','2026-03-06 16:44:34'),(71,1,'barber',NULL,NULL,1,'2026-03-06 16:45:29','2026-03-06 16:45:29'),(72,1,'barber',NULL,NULL,1,'2026-03-06 16:46:12','2026-03-06 16:46:12'),(73,1,'barber',NULL,NULL,1,'2026-03-06 16:46:58','2026-03-06 16:46:58'),(74,1,'barber',NULL,NULL,1,'2026-03-06 16:47:54','2026-03-06 16:47:54'),(75,1,'cashier',1200.00,NULL,0,'2026-03-06 16:50:09','2026-05-13 15:39:02'),(76,1,'barber',100.00,NULL,0,'2026-03-06 16:50:53','2026-05-13 15:39:33'),(77,1,'barber',NULL,NULL,1,'2026-03-06 16:51:44','2026-03-06 16:51:44'),(78,1,'barber',NULL,NULL,1,'2026-03-06 16:52:36','2026-03-06 16:52:36'),(79,1,'barber',NULL,NULL,1,'2026-03-06 16:53:34','2026-03-06 16:53:34'),(80,1,'barber',NULL,NULL,1,'2026-03-06 16:56:04','2026-03-06 16:56:04'),(81,1,'barber',NULL,NULL,1,'2026-03-06 16:56:59','2026-03-06 16:56:59'),(82,1,'barber',NULL,NULL,1,'2026-03-06 16:57:46','2026-03-06 16:57:46'),(83,1,'administrative',1500.00,NULL,1,'2026-03-06 16:58:56','2026-05-13 15:41:59'),(84,1,'administrative',NULL,NULL,1,'2026-03-06 17:02:04','2026-03-06 17:02:04'),(109,1,'asistente',1200.00,NULL,1,'2026-05-13 15:41:23','2026-05-13 15:41:23'),(123,1,'asistente',1200.00,NULL,1,'2026-05-13 15:40:37','2026-05-13 15:40:37');
/*!40000 ALTER TABLE `profile_workers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reports`
--

DROP TABLE IF EXISTS `reports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reports` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `version` int unsigned NOT NULL DEFAULT '1',
  `type` enum('academy_income_per_day','academy_attendance_by_group','academy_student_list_by_group','barbershop_income_per_day','barbershop_barber_commissions','barbershop_cash_session_summary','treasury_income_per_day','treasury_expense_per_day','treasury_cash_session','treasury_income_vs_expense','treasury_pending_expenses','inventory_kardex','inventory_stock_by_product','inventory_stock_by_infrastructure','inventory_sales_per_day','inventory_low_stock') COLLATE utf8mb4_unicode_ci NOT NULL,
  `data` json NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `generated_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `reports_reference_version_unique` (`reference`,`version`),
  KEY `reports_reference_index` (`reference`),
  KEY `reports_type_index` (`type`),
  KEY `reports_generated_by_index` (`generated_by`),
  CONSTRAINT `reports_generated_by_foreign` FOREIGN KEY (`generated_by`) REFERENCES `auth_users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reports`
--

LOCK TABLES `reports` WRITE;
/*!40000 ALTER TABLE `reports` DISABLE KEYS */;
INSERT INTO `reports` VALUES (1,'Ingreso Diario Barbería - BÁRBAROS CLUB - 2026-05-16','barbershop_income_per_day,1,2026-05-16',1,'barbershop_income_per_day','{\"date\": \"2026-05-16\", \"branch_id\": 1, \"total_day\": 0, \"rows_count\": 0, \"total_bank\": 0, \"total_cash\": 0, \"branch_name\": \"BÁRBAROS CLUB\"}','barbershop/ingreso-diario-barberia-2026-05-16-20260515191610.pdf',89,'2026-05-15 19:16:10','2026-05-15 19:16:10'),(2,'Ingreso Diario Barbería - BÁRBAROS CLUB - 2026-05-14','barbershop_income_per_day,1,2026-05-14',1,'barbershop_income_per_day','{\"date\": \"2026-05-14\", \"branch_id\": 1, \"total_day\": 0, \"rows_count\": 0, \"total_bank\": 0, \"total_cash\": 0, \"branch_name\": \"BÁRBAROS CLUB\"}','barbershop/ingreso-diario-barberia-2026-05-14-20260515220156.pdf',89,'2026-05-15 22:01:56','2026-05-15 22:01:56');
/*!40000 ALTER TABLE `reports` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `treasury_cash_registers`
--

DROP TABLE IF EXISTS `treasury_cash_registers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `treasury_cash_registers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `infrastructure_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `treasury_cash_registers_infrastructure_id_index` (`infrastructure_id`),
  KEY `treasury_cash_registers_is_active_index` (`is_active`),
  CONSTRAINT `treasury_cash_registers_infrastructure_id_foreign` FOREIGN KEY (`infrastructure_id`) REFERENCES `core_infrastructures` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `treasury_cash_registers`
--

LOCK TABLES `treasury_cash_registers` WRITE;
/*!40000 ALTER TABLE `treasury_cash_registers` DISABLE KEYS */;
INSERT INTO `treasury_cash_registers` VALUES (1,2,'CAJA OFICIAL BARBAROS',1,'2026-05-12 19:33:44','2026-05-13 11:40:16'),(2,1,'CAJA OFICIAL ESCUELA',1,'2026-05-13 11:40:02','2026-05-13 11:40:02'),(3,3,'CAJA OFICIAL OLIMPO',1,'2026-05-13 11:40:35','2026-05-13 11:40:35'),(4,4,'CAJA OFICIAL MONALISA',1,'2026-05-13 11:41:08','2026-05-13 11:41:08'),(5,5,'CAJA OFICIAL JEQUE',1,'2026-05-13 11:41:24','2026-05-13 11:41:24');
/*!40000 ALTER TABLE `treasury_cash_registers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `treasury_cash_sessions`
--

DROP TABLE IF EXISTS `treasury_cash_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `treasury_cash_sessions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cash_register_id` bigint unsigned NOT NULL,
  `opened_by` bigint unsigned NOT NULL,
  `closed_by` bigint unsigned DEFAULT NULL,
  `opening_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `expected_closing_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `actual_closing_amount` decimal(12,2) DEFAULT NULL,
  `difference` decimal(12,2) DEFAULT NULL,
  `status` enum('open','closed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `opened_at` timestamp NOT NULL,
  `closed_at` timestamp NULL DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `treasury_cash_sessions_cash_register_id_index` (`cash_register_id`),
  KEY `treasury_cash_sessions_opened_by_index` (`opened_by`),
  KEY `treasury_cash_sessions_closed_by_index` (`closed_by`),
  KEY `treasury_cash_sessions_status_index` (`status`),
  KEY `treasury_cash_sessions_opened_at_index` (`opened_at`),
  KEY `treasury_cash_sessions_closed_at_index` (`closed_at`),
  CONSTRAINT `treasury_cash_sessions_cash_register_id_foreign` FOREIGN KEY (`cash_register_id`) REFERENCES `treasury_cash_registers` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `treasury_cash_sessions_closed_by_foreign` FOREIGN KEY (`closed_by`) REFERENCES `auth_users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `treasury_cash_sessions_opened_by_foreign` FOREIGN KEY (`opened_by`) REFERENCES `auth_users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `treasury_cash_sessions`
--

LOCK TABLES `treasury_cash_sessions` WRITE;
/*!40000 ALTER TABLE `treasury_cash_sessions` DISABLE KEYS */;
INSERT INTO `treasury_cash_sessions` VALUES (1,1,1,1,100.00,100.00,0.00,-100.00,'closed','2026-05-12 19:34:47','2026-05-13 11:38:03',NULL,'2026-05-12 19:34:47','2026-05-13 11:38:03'),(2,1,89,89,100.00,340.00,320.00,-20.00,'closed','2026-05-13 11:55:11','2026-05-15 22:10:16','falta dinero','2026-05-13 11:55:11','2026-05-15 22:10:16');
/*!40000 ALTER TABLE `treasury_cash_sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `treasury_employee_advances`
--

DROP TABLE IF EXISTS `treasury_employee_advances`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `treasury_employee_advances` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `employee_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `employee_id` bigint unsigned NOT NULL,
  `infrastructure_id` bigint unsigned DEFAULT NULL,
  `cash_session_id` bigint unsigned DEFAULT NULL,
  `payment_method_id` bigint unsigned NOT NULL,
  `authorized_by` bigint unsigned NOT NULL,
  `paid_by` bigint unsigned NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `advance_date` date NOT NULL,
  `payment_reference` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discounted_in_payment_id` bigint unsigned DEFAULT NULL,
  `status` enum('pending','discounted') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `reason` text COLLATE utf8mb4_unicode_ci,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `treasury_employee_advances_infrastructure_id_foreign` (`infrastructure_id`),
  KEY `treasury_employee_advances_cash_session_id_foreign` (`cash_session_id`),
  KEY `treasury_employee_advances_payment_method_id_foreign` (`payment_method_id`),
  KEY `treasury_employee_advances_authorized_by_foreign` (`authorized_by`),
  KEY `treasury_employee_advances_paid_by_foreign` (`paid_by`),
  KEY `treasury_employee_advances_discounted_in_payment_id_foreign` (`discounted_in_payment_id`),
  KEY `employee_index` (`employee_type`,`employee_id`),
  KEY `treasury_employee_advances_advance_date_index` (`advance_date`),
  KEY `treasury_employee_advances_status_index` (`status`),
  CONSTRAINT `treasury_employee_advances_authorized_by_foreign` FOREIGN KEY (`authorized_by`) REFERENCES `auth_users` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `treasury_employee_advances_cash_session_id_foreign` FOREIGN KEY (`cash_session_id`) REFERENCES `treasury_cash_sessions` (`id`) ON DELETE SET NULL,
  CONSTRAINT `treasury_employee_advances_discounted_in_payment_id_foreign` FOREIGN KEY (`discounted_in_payment_id`) REFERENCES `treasury_employee_payments` (`id`) ON DELETE SET NULL,
  CONSTRAINT `treasury_employee_advances_infrastructure_id_foreign` FOREIGN KEY (`infrastructure_id`) REFERENCES `core_infrastructures` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `treasury_employee_advances_paid_by_foreign` FOREIGN KEY (`paid_by`) REFERENCES `auth_users` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `treasury_employee_advances_payment_method_id_foreign` FOREIGN KEY (`payment_method_id`) REFERENCES `core_payment_methods` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `treasury_employee_advances`
--

LOCK TABLES `treasury_employee_advances` WRITE;
/*!40000 ALTER TABLE `treasury_employee_advances` DISABLE KEYS */;
/*!40000 ALTER TABLE `treasury_employee_advances` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `treasury_employee_payments`
--

DROP TABLE IF EXISTS `treasury_employee_payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `treasury_employee_payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `employee_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `employee_id` bigint unsigned NOT NULL,
  `infrastructure_id` bigint unsigned DEFAULT NULL,
  `cash_session_id` bigint unsigned DEFAULT NULL,
  `payment_method_id` bigint unsigned NOT NULL,
  `paid_by` bigint unsigned NOT NULL,
  `period` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `period_start` date NOT NULL,
  `period_end` date NOT NULL,
  `base_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `bonus` decimal(10,2) NOT NULL DEFAULT '0.00',
  `deductions` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total_amount` decimal(10,2) NOT NULL,
  `calculation_details` json DEFAULT NULL,
  `payment_date` date NOT NULL,
  `payment_reference` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('paid','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'paid',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_employee_payment` (`employee_type`,`employee_id`,`period_start`,`period_end`),
  KEY `treasury_employee_payments_cash_session_id_foreign` (`cash_session_id`),
  KEY `treasury_employee_payments_payment_method_id_foreign` (`payment_method_id`),
  KEY `treasury_employee_payments_paid_by_foreign` (`paid_by`),
  KEY `employee_index` (`employee_type`,`employee_id`),
  KEY `treasury_employee_payments_infrastructure_id_index` (`infrastructure_id`),
  KEY `treasury_employee_payments_period_start_index` (`period_start`),
  KEY `treasury_employee_payments_period_end_index` (`period_end`),
  KEY `treasury_employee_payments_payment_date_index` (`payment_date`),
  KEY `treasury_employee_payments_status_index` (`status`),
  CONSTRAINT `treasury_employee_payments_cash_session_id_foreign` FOREIGN KEY (`cash_session_id`) REFERENCES `treasury_cash_sessions` (`id`) ON DELETE SET NULL,
  CONSTRAINT `treasury_employee_payments_infrastructure_id_foreign` FOREIGN KEY (`infrastructure_id`) REFERENCES `core_infrastructures` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `treasury_employee_payments_paid_by_foreign` FOREIGN KEY (`paid_by`) REFERENCES `auth_users` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `treasury_employee_payments_payment_method_id_foreign` FOREIGN KEY (`payment_method_id`) REFERENCES `core_payment_methods` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `treasury_employee_payments`
--

LOCK TABLES `treasury_employee_payments` WRITE;
/*!40000 ALTER TABLE `treasury_employee_payments` DISABLE KEYS */;
/*!40000 ALTER TABLE `treasury_employee_payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `treasury_employee_schedules`
--

DROP TABLE IF EXISTS `treasury_employee_schedules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `treasury_employee_schedules` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `type` enum('barber','worker') COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `treasury_employee_schedules_type_index` (`type`),
  KEY `treasury_employee_schedules_is_active_index` (`is_active`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `treasury_employee_schedules`
--

LOCK TABLES `treasury_employee_schedules` WRITE;
/*!40000 ALTER TABLE `treasury_employee_schedules` DISABLE KEYS */;
INSERT INTO `treasury_employee_schedules` VALUES (1,'barber','09:00:00','21:00:00',1,'2026-05-13 17:28:07','2026-05-13 17:28:07'),(2,'worker','08:30:00','20:30:00',1,'2026-05-13 17:28:07','2026-05-13 17:28:07');
/*!40000 ALTER TABLE `treasury_employee_schedules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `treasury_expense_types`
--

DROP TABLE IF EXISTS `treasury_expense_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `treasury_expense_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `treasury_expense_types_name_index` (`name`),
  KEY `treasury_expense_types_is_active_index` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `treasury_expense_types`
--

LOCK TABLES `treasury_expense_types` WRITE;
/*!40000 ALTER TABLE `treasury_expense_types` DISABLE KEYS */;
/*!40000 ALTER TABLE `treasury_expense_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `treasury_expenses`
--

DROP TABLE IF EXISTS `treasury_expenses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `treasury_expenses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `expense_type_id` bigint unsigned NOT NULL,
  `infrastructure_id` bigint unsigned DEFAULT NULL,
  `cash_session_id` bigint unsigned DEFAULT NULL,
  `payment_method_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_date` date NOT NULL,
  `voucher_date` date DEFAULT NULL,
  `voucher_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `voucher_image_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','approved','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'approved',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `treasury_expenses_expense_type_id_index` (`expense_type_id`),
  KEY `treasury_expenses_infrastructure_id_index` (`infrastructure_id`),
  KEY `treasury_expenses_cash_session_id_index` (`cash_session_id`),
  KEY `treasury_expenses_payment_method_id_index` (`payment_method_id`),
  KEY `treasury_expenses_user_id_index` (`user_id`),
  KEY `treasury_expenses_transaction_date_index` (`transaction_date`),
  KEY `treasury_expenses_status_index` (`status`),
  CONSTRAINT `treasury_expenses_cash_session_id_foreign` FOREIGN KEY (`cash_session_id`) REFERENCES `treasury_cash_sessions` (`id`) ON DELETE SET NULL,
  CONSTRAINT `treasury_expenses_expense_type_id_foreign` FOREIGN KEY (`expense_type_id`) REFERENCES `treasury_expense_types` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `treasury_expenses_infrastructure_id_foreign` FOREIGN KEY (`infrastructure_id`) REFERENCES `core_infrastructures` (`id`) ON DELETE SET NULL,
  CONSTRAINT `treasury_expenses_payment_method_id_foreign` FOREIGN KEY (`payment_method_id`) REFERENCES `core_payment_methods` (`id`) ON DELETE SET NULL,
  CONSTRAINT `treasury_expenses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `auth_users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `treasury_expenses`
--

LOCK TABLES `treasury_expenses` WRITE;
/*!40000 ALTER TABLE `treasury_expenses` DISABLE KEYS */;
/*!40000 ALTER TABLE `treasury_expenses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `treasury_income_audits`
--

DROP TABLE IF EXISTS `treasury_income_audits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `treasury_income_audits` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `income_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `edit_description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subtotal_before` decimal(12,2) NOT NULL,
  `discount_before` decimal(12,2) NOT NULL,
  `total_before` decimal(12,2) NOT NULL,
  `details_snapshot` json NOT NULL,
  `payment_methods_snapshot` json NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `treasury_income_audits_user_id_foreign` (`user_id`),
  KEY `treasury_income_audits_income_id_index` (`income_id`),
  CONSTRAINT `treasury_income_audits_income_id_foreign` FOREIGN KEY (`income_id`) REFERENCES `treasury_incomes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `treasury_income_audits_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `auth_users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `treasury_income_audits`
--

LOCK TABLES `treasury_income_audits` WRITE;
/*!40000 ALTER TABLE `treasury_income_audits` DISABLE KEYS */;
/*!40000 ALTER TABLE `treasury_income_audits` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `treasury_income_details`
--

DROP TABLE IF EXISTS `treasury_income_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `treasury_income_details` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `income_id` bigint unsigned NOT NULL,
  `itemable_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `itemable_id` bigint unsigned DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `unit_price` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `subtotal` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `treasury_income_details_income_id_index` (`income_id`),
  KEY `treasury_income_details_itemable_type_itemable_id_index` (`itemable_type`,`itemable_id`),
  CONSTRAINT `treasury_income_details_income_id_foreign` FOREIGN KEY (`income_id`) REFERENCES `treasury_incomes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `treasury_income_details`
--

LOCK TABLES `treasury_income_details` WRITE;
/*!40000 ALTER TABLE `treasury_income_details` DISABLE KEYS */;
INSERT INTO `treasury_income_details` VALUES (1,1,NULL,NULL,'Matricula',1,250.00,250.00,0.00,'2026-03-03 22:16:59','2026-03-03 22:16:59'),(2,1,NULL,NULL,'Pension',1,500.00,100.00,400.00,'2026-03-03 22:16:59','2026-03-03 22:16:59'),(3,2,NULL,NULL,'Matricula',1,250.00,250.00,0.00,'2026-03-03 22:17:24','2026-03-03 22:17:24'),(4,2,NULL,NULL,'Pension',1,500.00,100.00,400.00,'2026-03-03 22:17:24','2026-03-03 22:17:24'),(5,3,NULL,NULL,'Matricula',1,250.00,250.00,0.00,'2026-03-03 22:18:50','2026-03-03 22:18:50'),(6,3,NULL,NULL,'Pension',1,500.00,100.00,400.00,'2026-03-03 22:18:50','2026-03-03 22:18:50'),(7,4,NULL,NULL,'Matricula',1,250.00,0.00,250.00,'2026-03-03 22:19:19','2026-03-03 22:19:19'),(8,4,NULL,NULL,'Pension',1,500.00,100.00,400.00,'2026-03-03 22:19:19','2026-03-03 22:19:19'),(9,5,NULL,NULL,'Matricula',1,250.00,250.00,0.00,'2026-03-03 22:19:47','2026-03-03 22:19:47'),(10,6,NULL,NULL,'Matricula',1,250.00,250.00,0.00,'2026-03-03 22:20:07','2026-03-03 22:20:07'),(11,6,NULL,NULL,'Pension',1,500.00,150.00,350.00,'2026-03-03 22:20:07','2026-03-03 22:20:07'),(12,7,NULL,NULL,'Matricula',1,250.00,250.00,0.00,'2026-03-03 22:20:29','2026-03-03 22:20:29'),(13,7,NULL,NULL,'Pension',1,500.00,100.00,400.00,'2026-03-03 22:20:29','2026-03-03 22:20:29'),(14,8,NULL,NULL,'Matricula',1,250.00,250.00,0.00,'2026-03-03 22:20:51','2026-03-03 22:20:51'),(15,8,NULL,NULL,'Pension',1,500.00,100.00,400.00,'2026-03-03 22:20:51','2026-03-03 22:20:51'),(16,9,NULL,NULL,'Matricula',1,250.00,250.00,0.00,'2026-03-03 22:21:18','2026-03-03 22:21:18'),(17,10,NULL,NULL,'Matricula',1,250.00,250.00,0.00,'2026-03-03 22:21:40','2026-03-03 22:21:40'),(18,10,NULL,NULL,'Pension',1,500.00,100.00,400.00,'2026-03-03 22:21:40','2026-03-03 22:21:40'),(19,11,NULL,NULL,'Matricula',1,250.00,250.00,0.00,'2026-03-03 22:23:34','2026-03-03 22:23:34'),(20,11,NULL,NULL,'Pension',1,500.00,100.00,400.00,'2026-03-03 22:23:34','2026-03-03 22:23:34'),(21,12,NULL,NULL,'Matricula',1,250.00,250.00,0.00,'2026-03-03 22:24:00','2026-03-03 22:24:00'),(22,12,NULL,NULL,'Pension',1,500.00,100.00,400.00,'2026-03-03 22:24:00','2026-03-03 22:24:00'),(23,13,NULL,NULL,'Matricula',1,250.00,250.00,0.00,'2026-03-03 22:24:41','2026-03-03 22:24:41'),(24,14,NULL,NULL,'Matricula',1,250.00,250.00,0.00,'2026-03-03 22:25:06','2026-03-03 22:25:06'),(25,14,NULL,NULL,'Pension',1,500.00,100.00,400.00,'2026-03-03 22:25:06','2026-03-03 22:25:06'),(26,15,NULL,NULL,'Matricula',1,250.00,0.00,250.00,'2026-03-07 22:11:50','2026-03-07 22:11:50'),(27,16,NULL,NULL,'Matricula',1,250.00,0.00,250.00,'2026-03-07 22:12:41','2026-03-07 22:12:41'),(28,17,NULL,NULL,'Matricula',1,250.00,0.00,250.00,'2026-03-07 22:13:16','2026-03-07 22:13:16'),(29,18,NULL,NULL,'Matricula',1,250.00,0.00,250.00,'2026-03-07 22:13:39','2026-03-07 22:13:39'),(30,19,NULL,NULL,'Matricula',1,250.00,0.00,250.00,'2026-03-07 22:13:56','2026-03-07 22:13:56'),(31,20,NULL,NULL,'Pension',1,650.00,50.00,600.00,'2026-03-07 22:18:12','2026-03-07 22:18:12'),(32,20,NULL,NULL,'Pension',1,650.00,50.00,600.00,'2026-03-07 22:18:12','2026-03-07 22:18:12'),(33,21,NULL,NULL,'Matricula',1,250.00,0.00,250.00,'2026-03-09 17:06:34','2026-03-09 17:06:34'),(34,22,NULL,NULL,'Matricula',1,250.00,50.00,200.00,'2026-03-09 17:07:03','2026-03-09 17:07:03'),(35,23,NULL,NULL,'Matricula',1,250.00,0.00,250.00,'2026-03-09 19:11:33','2026-03-09 19:11:33'),(36,24,NULL,NULL,'Matricula',1,250.00,0.00,250.00,'2026-03-09 19:13:05','2026-03-09 19:13:05'),(37,25,NULL,NULL,'Matricula',1,250.00,0.00,250.00,'2026-03-09 19:13:24','2026-03-09 19:13:24'),(38,26,NULL,NULL,'Matricula',1,250.00,0.00,250.00,'2026-03-09 23:53:16','2026-03-09 23:53:16'),(39,27,NULL,NULL,'Matricula',1,250.00,0.00,250.00,'2026-05-12 19:26:11','2026-05-12 19:26:11'),(40,28,NULL,NULL,'Matricula',1,250.00,0.00,250.00,'2026-05-13 15:28:54','2026-05-13 15:28:54'),(41,29,NULL,NULL,'Matricula',1,250.00,125.00,125.00,'2026-05-13 15:30:10','2026-05-13 15:30:10'),(42,30,NULL,NULL,'Matricula',1,250.00,0.00,250.00,'2026-05-13 15:31:16','2026-05-13 15:31:16'),(43,31,NULL,NULL,'Matricula',1,250.00,250.00,0.00,'2026-05-13 15:32:56','2026-05-13 15:32:56'),(44,32,NULL,NULL,'Matricula',1,250.00,0.00,250.00,'2026-05-13 15:33:26','2026-05-13 15:33:26'),(45,33,NULL,NULL,'Matricula',1,250.00,0.00,250.00,'2026-05-13 15:34:10','2026-05-13 15:34:10'),(46,34,NULL,NULL,'Matricula',1,250.00,0.00,250.00,'2026-05-13 15:34:45','2026-05-13 15:34:45'),(47,35,NULL,NULL,'Matricula',1,250.00,0.00,250.00,'2026-05-13 15:35:13','2026-05-13 15:35:13'),(48,36,NULL,NULL,'Matricula',1,250.00,0.00,250.00,'2026-05-13 15:35:38','2026-05-13 15:35:38'),(49,37,NULL,NULL,'Matricula',1,250.00,0.00,250.00,'2026-05-13 15:35:59','2026-05-13 15:35:59'),(50,38,NULL,NULL,'Matricula',1,250.00,0.00,250.00,'2026-05-13 15:36:15','2026-05-13 15:36:15'),(51,39,NULL,NULL,'Matricula',1,250.00,0.00,250.00,'2026-05-13 15:36:33','2026-05-13 15:36:33'),(52,40,NULL,NULL,'Matricula',1,0.00,0.00,0.00,'2026-05-13 15:36:58','2026-05-13 15:36:58'),(53,41,NULL,NULL,'Matricula',1,250.00,0.00,250.00,'2026-05-13 17:24:20','2026-05-13 17:24:20'),(54,42,NULL,NULL,'Matricula',1,250.00,0.00,250.00,'2026-05-14 15:48:12','2026-05-14 15:48:12'),(55,43,NULL,NULL,'Matricula',1,250.00,0.00,250.00,'2026-05-14 15:49:20','2026-05-14 15:49:20'),(56,44,NULL,NULL,'Matricula',1,250.00,125.00,125.00,'2026-05-14 15:50:39','2026-05-14 15:50:39'),(57,45,NULL,NULL,'Matricula',1,0.00,0.00,0.00,'2026-05-14 16:06:12','2026-05-14 16:06:12'),(58,46,NULL,NULL,'Matricula',1,0.00,0.00,0.00,'2026-05-14 16:07:06','2026-05-14 16:07:06'),(59,46,NULL,NULL,'Cuota',1,500.00,100.00,400.00,'2026-05-14 16:07:06','2026-05-14 16:07:06'),(60,47,NULL,NULL,'CORTE DEGRADADO',1,40.00,0.00,40.00,'2026-05-15 19:10:18','2026-05-15 19:10:18'),(61,48,NULL,NULL,'CORTE CLASICO',1,40.00,0.00,40.00,'2026-05-15 19:12:09','2026-05-15 19:12:09'),(62,49,NULL,NULL,'CORTE DEGRADADO',1,40.00,0.00,40.00,'2026-05-15 19:12:36','2026-05-15 19:12:36'),(63,50,NULL,NULL,'CORTE DEGRADADO',1,40.00,0.00,40.00,'2026-05-15 19:13:13','2026-05-15 19:13:13'),(64,51,NULL,NULL,'CORTE CLASICO',1,40.00,0.00,40.00,'2026-05-15 19:13:35','2026-05-15 19:13:35'),(65,52,NULL,NULL,'CORTE DEGRADADO',1,40.00,0.00,40.00,'2026-05-15 22:06:58','2026-05-15 22:06:58');
/*!40000 ALTER TABLE `treasury_income_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `treasury_income_payment_methods`
--

DROP TABLE IF EXISTS `treasury_income_payment_methods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `treasury_income_payment_methods` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `income_id` bigint unsigned NOT NULL,
  `payment_method_id` bigint unsigned NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `payment_reference` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `treasury_income_payment_methods_income_id_index` (`income_id`),
  KEY `treasury_income_payment_methods_payment_method_id_index` (`payment_method_id`),
  CONSTRAINT `treasury_income_payment_methods_income_id_foreign` FOREIGN KEY (`income_id`) REFERENCES `treasury_incomes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `treasury_income_payment_methods_payment_method_id_foreign` FOREIGN KEY (`payment_method_id`) REFERENCES `core_payment_methods` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `treasury_income_payment_methods`
--

LOCK TABLES `treasury_income_payment_methods` WRITE;
/*!40000 ALTER TABLE `treasury_income_payment_methods` DISABLE KEYS */;
INSERT INTO `treasury_income_payment_methods` VALUES (1,1,1,400.00,NULL,'2026-03-03 22:16:59','2026-03-03 22:16:59'),(2,2,1,400.00,NULL,'2026-03-03 22:17:24','2026-03-03 22:17:24'),(3,3,1,400.00,NULL,'2026-03-03 22:18:50','2026-03-03 22:18:50'),(4,4,1,650.00,NULL,'2026-03-03 22:19:19','2026-03-03 22:19:19'),(5,6,1,350.00,NULL,'2026-03-03 22:20:07','2026-03-03 22:20:07'),(6,7,1,400.00,NULL,'2026-03-03 22:20:29','2026-03-03 22:20:29'),(7,8,1,400.00,NULL,'2026-03-03 22:20:51','2026-03-03 22:20:51'),(8,10,1,400.00,NULL,'2026-03-03 22:21:40','2026-03-03 22:21:40'),(9,11,1,400.00,NULL,'2026-03-03 22:23:34','2026-03-03 22:23:34'),(10,12,1,400.00,NULL,'2026-03-03 22:24:00','2026-03-03 22:24:00'),(11,14,1,400.00,NULL,'2026-03-03 22:25:06','2026-03-03 22:25:06'),(12,15,1,250.00,NULL,'2026-03-07 22:11:50','2026-03-07 22:11:50'),(13,16,1,250.00,NULL,'2026-03-07 22:12:41','2026-03-07 22:12:41'),(14,17,1,250.00,NULL,'2026-03-07 22:13:16','2026-03-07 22:13:16'),(15,18,1,250.00,NULL,'2026-03-07 22:13:39','2026-03-07 22:13:39'),(16,19,1,250.00,NULL,'2026-03-07 22:13:56','2026-03-07 22:13:56'),(17,20,1,1200.00,NULL,'2026-03-07 22:18:12','2026-03-07 22:18:12'),(18,21,1,250.00,NULL,'2026-03-09 17:06:34','2026-03-09 17:06:34'),(19,22,1,200.00,NULL,'2026-03-09 17:07:03','2026-03-09 17:07:03'),(20,23,1,250.00,NULL,'2026-03-09 19:11:33','2026-03-09 19:11:33'),(21,24,1,250.00,NULL,'2026-03-09 19:13:05','2026-03-09 19:13:05'),(22,25,1,250.00,NULL,'2026-03-09 19:13:24','2026-03-09 19:13:24'),(23,26,1,250.00,NULL,'2026-03-09 23:53:16','2026-03-09 23:53:16'),(24,27,1,250.00,NULL,'2026-05-12 19:26:11','2026-05-12 19:26:11'),(25,28,1,250.00,NULL,'2026-05-13 15:28:54','2026-05-13 15:28:54'),(26,29,2,125.00,'YAPE','2026-05-13 15:30:10','2026-05-13 15:30:10'),(27,30,1,250.00,NULL,'2026-05-13 15:31:16','2026-05-13 15:31:16'),(28,32,1,250.00,NULL,'2026-05-13 15:33:26','2026-05-13 15:33:26'),(29,33,1,250.00,NULL,'2026-05-13 15:34:10','2026-05-13 15:34:10'),(30,34,1,250.00,NULL,'2026-05-13 15:34:45','2026-05-13 15:34:45'),(31,35,1,250.00,NULL,'2026-05-13 15:35:13','2026-05-13 15:35:13'),(32,36,1,250.00,NULL,'2026-05-13 15:35:38','2026-05-13 15:35:38'),(33,37,1,250.00,NULL,'2026-05-13 15:35:59','2026-05-13 15:35:59'),(34,38,1,250.00,NULL,'2026-05-13 15:36:15','2026-05-13 15:36:15'),(35,39,1,250.00,NULL,'2026-05-13 15:36:33','2026-05-13 15:36:33'),(36,41,1,250.00,NULL,'2026-05-13 17:24:20','2026-05-13 17:24:20'),(37,42,1,250.00,NULL,'2026-05-14 15:48:12','2026-05-14 15:48:12'),(38,43,1,250.00,NULL,'2026-05-14 15:49:20','2026-05-14 15:49:20'),(39,44,1,125.00,NULL,'2026-05-14 15:50:39','2026-05-14 15:50:39'),(40,46,1,400.00,NULL,'2026-05-14 16:07:06','2026-05-14 16:07:06'),(41,47,1,40.00,NULL,'2026-05-15 19:10:18','2026-05-15 19:10:18'),(42,48,1,20.00,NULL,'2026-05-15 19:12:09','2026-05-15 19:12:09'),(43,48,2,20.00,'411','2026-05-15 19:12:09','2026-05-15 19:12:09'),(44,49,2,40.00,'444','2026-05-15 19:12:36','2026-05-15 19:12:36'),(45,50,1,40.00,NULL,'2026-05-15 19:13:13','2026-05-15 19:13:13'),(46,51,1,30.00,NULL,'2026-05-15 19:13:35','2026-05-15 19:13:35'),(47,51,2,10.00,'777','2026-05-15 19:13:35','2026-05-15 19:13:35'),(48,52,1,40.00,NULL,'2026-05-15 22:06:58','2026-05-15 22:06:58');
/*!40000 ALTER TABLE `treasury_income_payment_methods` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `treasury_incomes`
--

DROP TABLE IF EXISTS `treasury_incomes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `treasury_incomes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cash_session_id` bigint unsigned DEFAULT NULL,
  `infrastructure_id` bigint unsigned NOT NULL,
  `receipt_type` enum('00') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '00',
  `receipt_serie` char(4) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'R001',
  `receipt_number` int NOT NULL,
  `person_id` bigint unsigned DEFAULT NULL,
  `transactionable_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transactionable_id` bigint unsigned DEFAULT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `discount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `tax` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total` decimal(12,2) NOT NULL,
  `observations` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'completed',
  `is_edited` tinyint(1) NOT NULL DEFAULT '0',
  `transaction_date` date NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `treasury_incomes_receipt_serie_receipt_number_unique` (`receipt_serie`,`receipt_number`),
  KEY `treasury_incomes_cash_session_id_index` (`cash_session_id`),
  KEY `treasury_incomes_infrastructure_id_index` (`infrastructure_id`),
  KEY `treasury_incomes_person_id_index` (`person_id`),
  KEY `treasury_incomes_status_index` (`status`),
  KEY `treasury_incomes_transaction_date_index` (`transaction_date`),
  KEY `treasury_incomes_user_id_index` (`user_id`),
  KEY `treasury_incomes_transactionable_type_transactionable_id_index` (`transactionable_type`,`transactionable_id`),
  CONSTRAINT `treasury_incomes_cash_session_id_foreign` FOREIGN KEY (`cash_session_id`) REFERENCES `treasury_cash_sessions` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `treasury_incomes_infrastructure_id_foreign` FOREIGN KEY (`infrastructure_id`) REFERENCES `core_infrastructures` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `treasury_incomes_person_id_foreign` FOREIGN KEY (`person_id`) REFERENCES `core_persons` (`id`) ON DELETE SET NULL,
  CONSTRAINT `treasury_incomes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `auth_users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `treasury_incomes`
--

LOCK TABLES `treasury_incomes` WRITE;
/*!40000 ALTER TABLE `treasury_incomes` DISABLE KEYS */;
INSERT INTO `treasury_incomes` VALUES (1,NULL,1,'00','R001',1,44,'academy_enrollment_payments',1,400.00,350.00,0.00,400.00,NULL,'completed',0,'2026-03-03',2,'2026-03-03 22:16:59','2026-03-03 22:16:59'),(2,NULL,1,'00','R001',2,45,'academy_enrollment_payments',2,400.00,350.00,0.00,400.00,NULL,'completed',0,'2026-03-03',2,'2026-03-03 22:17:24','2026-03-03 22:17:24'),(3,NULL,1,'00','R001',3,46,'academy_enrollment_payments',3,400.00,350.00,0.00,400.00,NULL,'completed',0,'2026-03-03',2,'2026-03-03 22:18:50','2026-03-03 22:18:50'),(4,NULL,1,'00','R001',4,47,'academy_enrollment_payments',4,650.00,100.00,0.00,650.00,NULL,'completed',0,'2026-03-03',2,'2026-03-03 22:19:19','2026-03-03 22:19:19'),(5,NULL,1,'00','R001',5,48,'academy_enrollment_payments',5,0.00,250.00,0.00,0.00,NULL,'completed',0,'2026-03-03',2,'2026-03-03 22:19:47','2026-03-03 22:19:47'),(6,NULL,1,'00','R001',6,49,'academy_enrollment_payments',6,350.00,400.00,0.00,350.00,NULL,'completed',0,'2026-03-03',2,'2026-03-03 22:20:07','2026-03-03 22:20:07'),(7,NULL,1,'00','R001',7,50,'academy_enrollment_payments',7,400.00,350.00,0.00,400.00,NULL,'completed',0,'2026-03-03',2,'2026-03-03 22:20:29','2026-03-03 22:20:29'),(8,NULL,1,'00','R001',8,51,'academy_enrollment_payments',8,400.00,350.00,0.00,400.00,NULL,'completed',0,'2026-03-03',2,'2026-03-03 22:20:51','2026-03-03 22:20:51'),(9,NULL,1,'00','R001',9,52,'academy_enrollment_payments',9,0.00,250.00,0.00,0.00,NULL,'completed',0,'2026-03-03',2,'2026-03-03 22:21:18','2026-03-03 22:21:18'),(10,NULL,1,'00','R001',10,53,'academy_enrollment_payments',10,400.00,350.00,0.00,400.00,NULL,'completed',0,'2026-03-03',2,'2026-03-03 22:21:40','2026-03-03 22:21:40'),(11,NULL,1,'00','R001',11,54,'academy_enrollment_payments',11,400.00,350.00,0.00,400.00,NULL,'completed',0,'2026-03-03',2,'2026-03-03 22:23:34','2026-03-03 22:23:34'),(12,NULL,1,'00','R001',12,55,'academy_enrollment_payments',12,400.00,350.00,0.00,400.00,NULL,'completed',0,'2026-03-03',2,'2026-03-03 22:24:00','2026-03-03 22:24:00'),(13,NULL,1,'00','R001',13,56,'academy_enrollment_payments',13,0.00,250.00,0.00,0.00,NULL,'completed',0,'2026-03-03',2,'2026-03-03 22:24:41','2026-03-03 22:24:41'),(14,NULL,1,'00','R001',14,57,'academy_enrollment_payments',14,400.00,350.00,0.00,400.00,NULL,'completed',0,'2026-03-03',2,'2026-03-03 22:25:06','2026-03-03 22:25:06'),(15,NULL,1,'00','R001',15,88,'academy_enrollment_payments',15,250.00,0.00,0.00,250.00,NULL,'completed',0,'2026-03-07',2,'2026-03-07 22:11:50','2026-03-07 22:11:50'),(16,NULL,1,'00','R001',16,87,'academy_enrollment_payments',16,250.00,0.00,0.00,250.00,NULL,'completed',0,'2026-03-07',2,'2026-03-07 22:12:41','2026-03-07 22:12:41'),(17,NULL,1,'00','R001',17,86,'academy_enrollment_payments',17,250.00,0.00,0.00,250.00,NULL,'completed',0,'2026-03-07',2,'2026-03-07 22:13:16','2026-03-07 22:13:16'),(18,NULL,1,'00','R001',18,64,'academy_enrollment_payments',18,250.00,0.00,0.00,250.00,NULL,'completed',0,'2026-03-07',2,'2026-03-07 22:13:39','2026-03-07 22:13:39'),(19,NULL,1,'00','R001',19,61,'academy_enrollment_payments',19,250.00,0.00,0.00,250.00,NULL,'completed',0,'2026-03-07',2,'2026-03-07 22:13:56','2026-03-07 22:13:56'),(20,NULL,1,'00','R001',20,64,'academy_enrollment_payments',20,1200.00,100.00,0.00,1200.00,NULL,'completed',0,'2026-03-07',2,'2026-03-07 22:18:12','2026-03-07 22:18:12'),(21,NULL,1,'00','R001',21,91,'academy_enrollment_payments',21,250.00,0.00,0.00,250.00,NULL,'completed',0,'2026-03-09',2,'2026-03-09 17:06:34','2026-03-09 17:06:34'),(22,NULL,1,'00','R001',22,90,'academy_enrollment_payments',22,200.00,50.00,0.00,200.00,NULL,'completed',0,'2026-03-09',2,'2026-03-09 17:07:03','2026-03-09 17:07:03'),(23,NULL,1,'00','R001',23,89,'academy_enrollment_payments',23,250.00,0.00,0.00,250.00,NULL,'completed',0,'2026-03-09',2,'2026-03-09 19:11:33','2026-03-09 19:11:33'),(24,NULL,1,'00','R001',24,85,'academy_enrollment_payments',24,250.00,0.00,0.00,250.00,NULL,'completed',0,'2026-03-09',2,'2026-03-09 19:13:05','2026-03-09 19:13:05'),(25,NULL,1,'00','R001',25,63,'academy_enrollment_payments',25,250.00,0.00,0.00,250.00,NULL,'completed',0,'2026-03-09',2,'2026-03-09 19:13:24','2026-03-09 19:13:24'),(26,NULL,1,'00','R001',26,94,'academy_enrollment_payments',26,250.00,0.00,0.00,250.00,NULL,'completed',0,'2026-03-09',2,'2026-03-09 23:53:16','2026-03-09 23:53:16'),(27,NULL,1,'00','R001',27,111,'academy_enrollment_payments',27,250.00,0.00,0.00,250.00,NULL,'completed',0,'2026-05-12',42,'2026-05-12 19:26:11','2026-05-12 19:26:11'),(28,NULL,1,'00','R001',28,113,'academy_enrollment_payments',28,250.00,0.00,0.00,250.00,NULL,'completed',0,'2026-05-13',89,'2026-05-13 15:28:54','2026-05-13 15:28:54'),(29,NULL,1,'00','R001',29,114,'academy_enrollment_payments',29,125.00,125.00,0.00,125.00,NULL,'completed',0,'2026-05-13',89,'2026-05-13 15:30:10','2026-05-13 15:30:10'),(30,NULL,1,'00','R001',30,115,'academy_enrollment_payments',30,250.00,0.00,0.00,250.00,NULL,'completed',0,'2026-05-13',89,'2026-05-13 15:31:16','2026-05-13 15:31:16'),(31,NULL,1,'00','R001',31,116,'academy_enrollment_payments',31,0.00,250.00,0.00,0.00,NULL,'completed',0,'2026-05-13',89,'2026-05-13 15:32:56','2026-05-13 15:32:56'),(32,NULL,1,'00','R001',32,117,'academy_enrollment_payments',32,250.00,0.00,0.00,250.00,NULL,'completed',0,'2026-05-13',89,'2026-05-13 15:33:26','2026-05-13 15:33:26'),(33,NULL,1,'00','R001',33,118,'academy_enrollment_payments',33,250.00,0.00,0.00,250.00,NULL,'completed',0,'2026-05-13',89,'2026-05-13 15:34:10','2026-05-13 15:34:10'),(34,NULL,1,'00','R001',34,111,'academy_enrollment_payments',34,250.00,0.00,0.00,250.00,NULL,'completed',0,'2026-05-13',89,'2026-05-13 15:34:45','2026-05-13 15:34:45'),(35,NULL,1,'00','R001',35,119,'academy_enrollment_payments',35,250.00,0.00,0.00,250.00,NULL,'completed',0,'2026-05-13',89,'2026-05-13 15:35:13','2026-05-13 15:35:13'),(36,NULL,1,'00','R001',36,112,'academy_enrollment_payments',36,250.00,0.00,0.00,250.00,NULL,'completed',0,'2026-05-13',89,'2026-05-13 15:35:38','2026-05-13 15:35:38'),(37,NULL,1,'00','R001',37,120,'academy_enrollment_payments',37,250.00,0.00,0.00,250.00,NULL,'completed',0,'2026-05-13',89,'2026-05-13 15:35:59','2026-05-13 15:35:59'),(38,NULL,1,'00','R001',38,121,'academy_enrollment_payments',38,250.00,0.00,0.00,250.00,NULL,'completed',0,'2026-05-13',89,'2026-05-13 15:36:15','2026-05-13 15:36:15'),(39,NULL,1,'00','R001',39,122,'academy_enrollment_payments',39,250.00,0.00,0.00,250.00,NULL,'completed',0,'2026-05-13',89,'2026-05-13 15:36:33','2026-05-13 15:36:33'),(40,NULL,1,'00','R001',40,89,'academy_enrollment_payments',40,0.00,0.00,0.00,0.00,NULL,'completed',0,'2026-05-13',89,'2026-05-13 15:36:58','2026-05-13 15:36:58'),(41,NULL,1,'00','R001',41,125,'academy_enrollment_payments',41,250.00,0.00,0.00,250.00,NULL,'completed',0,'2026-05-13',89,'2026-05-13 17:24:20','2026-05-13 17:24:20'),(42,NULL,1,'00','R001',42,126,'academy_enrollment_payments',42,250.00,0.00,0.00,250.00,NULL,'completed',0,'2026-05-14',89,'2026-05-14 15:48:12','2026-05-14 15:48:12'),(43,NULL,1,'00','R001',43,127,'academy_enrollment_payments',43,250.00,0.00,0.00,250.00,NULL,'completed',0,'2026-05-14',89,'2026-05-14 15:49:20','2026-05-14 15:49:20'),(44,NULL,1,'00','R001',44,128,'academy_enrollment_payments',44,125.00,125.00,0.00,125.00,NULL,'completed',0,'2026-05-14',89,'2026-05-14 15:50:39','2026-05-14 15:50:39'),(45,NULL,1,'00','R001',45,13,'academy_enrollment_payments',45,0.00,0.00,0.00,0.00,NULL,'completed',0,'2026-05-14',89,'2026-05-14 16:06:12','2026-05-14 16:06:12'),(46,NULL,1,'00','R001',46,25,'academy_enrollment_payments',46,400.00,100.00,0.00,400.00,NULL,'completed',0,'2026-05-14',89,'2026-05-14 16:07:06','2026-05-14 16:07:06'),(47,2,2,'00','R001',47,NULL,'barbershop_tickets',2,40.00,0.00,0.00,40.00,NULL,'completed',0,'2026-05-15',89,'2026-05-15 19:10:18','2026-05-15 19:10:18'),(48,2,2,'00','R001',48,NULL,'barbershop_tickets',4,40.00,0.00,0.00,40.00,NULL,'completed',0,'2026-05-15',89,'2026-05-15 19:12:09','2026-05-15 19:12:09'),(49,2,2,'00','R001',49,NULL,'barbershop_tickets',5,40.00,0.00,0.00,40.00,NULL,'completed',0,'2026-05-15',89,'2026-05-15 19:12:36','2026-05-15 19:12:36'),(50,2,2,'00','R001',50,NULL,'barbershop_tickets',6,40.00,0.00,0.00,40.00,NULL,'completed',0,'2026-05-15',89,'2026-05-15 19:13:13','2026-05-15 19:13:13'),(51,2,2,'00','R001',51,NULL,'barbershop_tickets',7,40.00,0.00,0.00,40.00,NULL,'completed',0,'2026-05-15',89,'2026-05-15 19:13:35','2026-05-15 19:13:35'),(52,2,2,'00','R001',52,NULL,'barbershop_tickets',8,40.00,0.00,0.00,40.00,NULL,'completed',0,'2026-05-15',102,'2026-05-15 22:06:58','2026-05-15 22:06:58');
/*!40000 ALTER TABLE `treasury_incomes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `worker_attendances`
--

DROP TABLE IF EXISTS `worker_attendances`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `worker_attendances` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `worker_id` bigint unsigned NOT NULL,
  `date` date NOT NULL,
  `check_in` time DEFAULT NULL,
  `check_out` time DEFAULT NULL,
  `check_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `check_type` enum('manual','qr') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'manual',
  `status` enum('present','absent','late','absent_justified','late_justified') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'present',
  `observation` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `worker_attendances_worker_id_date_unique` (`worker_id`,`date`),
  KEY `worker_attendances_worker_id_date_index` (`worker_id`,`date`),
  KEY `worker_attendances_worker_id_index` (`worker_id`),
  KEY `worker_attendances_date_index` (`date`),
  KEY `worker_attendances_check_type_index` (`check_type`),
  KEY `worker_attendances_check_token_index` (`check_token`),
  KEY `worker_attendances_status_index` (`status`),
  CONSTRAINT `worker_attendances_worker_id_foreign` FOREIGN KEY (`worker_id`) REFERENCES `profile_workers` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `worker_attendances`
--

LOCK TABLES `worker_attendances` WRITE;
/*!40000 ALTER TABLE `worker_attendances` DISABLE KEYS */;
/*!40000 ALTER TABLE `worker_attendances` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-05-15 22:28:34

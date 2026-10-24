-- MySQL dump 10.13  Distrib 8.4.7, for Win64 (x86_64)
--
-- Host: localhost    Database: akcourse
-- ------------------------------------------------------
-- Server version	8.4.7

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
-- Table structure for table `activities`
--

DROP TABLE IF EXISTS `activities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `activities` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL DEFAULT '0',
  `reviewer_id` int NOT NULL DEFAULT '0',
  `product_id` int unsigned NOT NULL DEFAULT '0',
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activities`
--

LOCK TABLES `activities` WRITE;
/*!40000 ALTER TABLE `activities` DISABLE KEYS */;
/*!40000 ALTER TABLE `activities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_notifications`
--

DROP TABLE IF EXISTS `admin_notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_notifications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `click_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_admin_notifications_read` (`is_read`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_notifications`
--

LOCK TABLES `admin_notifications` WRITE;
/*!40000 ALTER TABLE `admin_notifications` DISABLE KEYS */;
INSERT INTO `admin_notifications` VALUES (1,9,'Payment successful via ',0,'/admin/payment/successful','2026-10-21 10:36:14','2026-10-21 10:36:14'),(2,11,'New member registered',0,'/admin/users/detail/11','2026-10-21 13:14:24','2026-10-21 13:14:24'),(3,12,'New member registered',0,'/admin/users/detail/12','2026-10-21 15:36:41','2026-10-21 15:36:41');
/*!40000 ALTER TABLE `admin_notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_password_resets`
--

DROP TABLE IF EXISTS `admin_password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_password_resets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `token` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_password_resets`
--

LOCK TABLES `admin_password_resets` WRITE;
/*!40000 ALTER TABLE `admin_password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `admin_password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admins` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`,`username`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admins`
--

LOCK TABLES `admins` WRITE;
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;
INSERT INTO `admins` VALUES (1,'Super Admin','info@wolinet.com','admin',NULL,'668a414d16c2f1720336717.png','$2y$12$4HYnlY.T8Q6wykTJ8STTvu4nuyW83KXuoG6tMpGx0kYUo.RgXuVpm','mSqGgvqBvPEcVNnLvL4Yp73cVUqvAFIDcbhVRM5D6Koxmp9NxW6v97tmJJXK',NULL,'2026-10-22 17:20:28');
/*!40000 ALTER TABLE `admins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `advertisements`
--

DROP TABLE IF EXISTS `advertisements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `advertisements` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` int NOT NULL DEFAULT '1',
  `impression` int NOT NULL DEFAULT '0',
  `click` int NOT NULL DEFAULT '0',
  `redirect_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `advertisements`
--

LOCK TABLES `advertisements` WRITE;
/*!40000 ALTER TABLE `advertisements` DISABLE KEYS */;
/*!40000 ALTER TABLE `advertisements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `api_keys`
--

DROP TABLE IF EXISTS `api_keys`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `api_keys` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL DEFAULT '0',
  `key` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 = active, 2 = inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `api_keys`
--

LOCK TABLES `api_keys` WRITE;
/*!40000 ALTER TABLE `api_keys` DISABLE KEYS */;
/*!40000 ALTER TABLE `api_keys` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `campaign_products`
--

DROP TABLE IF EXISTS `campaign_products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `campaign_products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `campaign_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `discount_percentage` decimal(5,2) NOT NULL DEFAULT '0.00',
  `status` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `campaign_products`
--

LOCK TABLES `campaign_products` WRITE;
/*!40000 ALTER TABLE `campaign_products` DISABLE KEYS */;
/*!40000 ALTER TABLE `campaign_products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `campaigns`
--

DROP TABLE IF EXISTS `campaigns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `campaigns` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount_min` decimal(5,2) NOT NULL DEFAULT '0.00',
  `discount_max` decimal(5,2) NOT NULL DEFAULT '0.00',
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `campaign_end` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `campaigns`
--

LOCK TABLES `campaigns` WRITE;
/*!40000 ALTER TABLE `campaigns` DISABLE KEYS */;
/*!40000 ALTER TABLE `campaigns` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `carts`
--

DROP TABLE IF EXISTS `carts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `carts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int unsigned NOT NULL DEFAULT '0',
  `user_id` int unsigned DEFAULT '0',
  `session_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` int unsigned NOT NULL DEFAULT '0',
  `category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `license` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_extended` tinyint(1) NOT NULL DEFAULT '0',
  `extended_amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `price` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `seller_fee` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `buyer_fee` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `quantity` int NOT NULL DEFAULT '1',
  `coupon_id` int NOT NULL DEFAULT '0',
  `discount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `carts_product_id_foreign` (`product_id`),
  KEY `carts_category_id_foreign` (`category_id`),
  KEY `carts_user_id_index` (`user_id`),
  KEY `carts_session_id_index` (`session_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carts`
--

LOCK TABLES `carts` WRITE;
/*!40000 ALTER TABLE `carts` DISABLE KEYS */;
INSERT INTO `carts` VALUES (3,33,8,'XX7nz7oXmwvUHKuAGOJ87rzA0VYXH1PIeLDLbizE','Cubase - Beginner',2,'Audio Editing Software Series','1',0,0.00000000,190000.00000000,0.00000000,0.00000000,1,0,0.00000000,'2026-10-21 08:48:38','2026-10-21 08:48:38'),(5,30,10,'6YpIVO1UGH5JbZX7tpkn9WUTrAaaRLoiVXiXDTra','Waves Ultimate 17 - Advanced Chain Building',1,'Vocal Mixing Series','1',1,0.00000000,250000.00000000,0.00000000,0.00000000,1,0,0.00000000,'2026-10-21 13:01:19','2026-10-21 13:01:40'),(6,29,10,'6YpIVO1UGH5JbZX7tpkn9WUTrAaaRLoiVXiXDTra','iZotope Nectar 4 - Intermediate',1,'Vocal Mixing Series','1',1,0.00000000,210000.00000000,0.00000000,0.00000000,1,0,0.00000000,'2026-10-21 13:01:23','2026-10-21 13:01:38'),(7,31,NULL,'r0NHh6vRjlpWCXroBgrhElIbqZgXM3FcWE3b0CG1','Complete Vocal Mixing Series',1,'Vocal Mixing Series','1',1,0.00000000,480000.00000000,0.00000000,0.00000000,1,0,0.00000000,'2026-10-21 13:09:07','2026-10-21 13:11:48'),(8,31,11,'XP8GLJn9Qe5UEkyIOHi0kxm1MkcPFw1RiB8Hfh47','Complete Vocal Mixing Series',1,'Vocal Mixing Series','1',0,0.00000000,480000.00000000,0.00000000,0.00000000,1,2,0.00000000,'2026-10-21 13:15:52','2026-10-21 13:22:31'),(9,27,NULL,'CCxDthttUUYPSTc7bBYN7GX7Wb9UIUG2SrAlsYIQ','Cymatics Voxity - Beginner',1,'Vocal Mixing Series','1',1,0.00000000,140000.00000000,0.00000000,0.00000000,1,0,0.00000000,'2026-10-23 20:53:40','2026-10-23 20:53:54');
/*!40000 ALTER TABLE `carts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_type` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_size` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `preview_file_types` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `personal_buyer_fee` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `commercial_buyer_fee` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `twelve_month_extended_fee` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `top_menu` tinyint(1) NOT NULL DEFAULT '0',
  `featured` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `seo_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Vocal Mixing Series','','audio','500','[\"mp3\",\"mp4\"]',0.00000000,0.00000000,0.00000000,0,1,1,NULL,'2026-10-20 04:33:48','2026-10-20 04:34:03'),(2,'Audio Editing Software Series','','audio','500','[\"mp3\",\"mp4\"]',0.00000000,0.00000000,0.00000000,0,1,1,NULL,'2026-10-20 04:33:48','2026-10-20 04:34:03'),(3,'Beat Making Series','','audio','500','[\"mp3\",\"mp4\"]',0.00000000,0.00000000,0.00000000,0,1,1,NULL,'2026-10-20 04:33:48','2026-10-20 04:34:03');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `changelogs`
--

DROP TABLE IF EXISTS `changelogs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `changelogs` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int unsigned NOT NULL,
  `heading` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `changelogs`
--

LOCK TABLES `changelogs` WRITE;
/*!40000 ALTER TABLE `changelogs` DISABLE KEYS */;
/*!40000 ALTER TABLE `changelogs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `comments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL DEFAULT '0',
  `review_id` int NOT NULL DEFAULT '0',
  `product_id` int unsigned NOT NULL DEFAULT '0',
  `parent_id` int unsigned NOT NULL DEFAULT '0',
  `text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_reported` tinyint(1) DEFAULT '0',
  `report_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `author_reply` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
INSERT INTO `comments` VALUES (1,1,0,26,0,'Does this course and template include the full DAW project file for FL Studio and Pro Tools?',0,NULL,0,'2026-10-09 15:01:06','2026-10-20 15:01:06'),(2,1,0,26,1,'Yes! The download package includes ready-to-run template sessions for FL Studio 21/24, Logic Pro 11, and Pro Tools Studio, along with raw and processed WAV stems.',0,NULL,1,'2026-10-09 18:01:06','2026-10-20 15:01:06'),(3,2,0,27,0,'Where do I find the license key for the bundled software tools after purchase?',0,NULL,0,'2026-10-12 15:01:06','2026-10-20 15:01:06'),(4,1,0,27,3,'Your license key and installer instructions are automatically dispatched to your student dashboard under the Software Licenses tab immediately upon payment confirmation.',0,NULL,1,'2026-10-12 18:01:06','2026-10-20 15:01:06'),(5,3,0,28,0,'Are the practice vocal stems royalty-free to use in our own commercial mixing portfolio?',0,NULL,0,'2026-10-03 15:01:06','2026-10-20 15:01:06'),(6,1,0,28,5,'Absolutely! All stems provided in this academy series are 100% royalty-free for your personal portfolio and mix demonstration reels.',0,NULL,1,'2026-10-03 18:01:06','2026-10-20 15:01:06'),(7,4,0,29,0,'I am on an Apple Silicon M2/M3 Mac. Are the plugins and presets fully compatible with native ARM?',0,NULL,0,'2026-10-18 15:01:06','2026-10-20 15:01:06'),(8,1,0,29,7,'Yes, 100% native ARM Apple Silicon and Windows 10/11 64-bit VST3/AU/AAX compatible.',0,NULL,1,'2026-10-18 18:01:06','2026-10-20 15:01:06'),(9,5,0,30,0,'Does the course cover pitch correction with both Antares Auto-Tune and Celemony Melodyne?',0,NULL,0,'2026-10-07 15:01:06','2026-10-20 15:01:06'),(10,1,0,30,9,'Yes, Module 3 covers surgical pitch drift correction with Melodyne followed by real-time stylistic tuning and vibrato control with Auto-Tune Pro.',0,NULL,1,'2026-10-07 18:01:06','2026-10-20 15:01:06');
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `coupons`
--

DROP TABLE IF EXISTS `coupons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `coupons` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount_type` tinyint(1) NOT NULL COMMENT '1=fixed, 2=percent',
  `amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `description` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `minimum_spend` decimal(28,8) DEFAULT '0.00000000',
  `usage_limit_per_coupon` int DEFAULT NULL,
  `usage_limit_per_user` int DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'enable-> 1, disable-> 0, expired-> 2',
  `start_date` timestamp NULL DEFAULT NULL,
  `end_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coupons`
--

LOCK TABLES `coupons` WRITE;
/*!40000 ALTER TABLE `coupons` DISABLE KEYS */;
INSERT INTO `coupons` VALUES (1,'Studio Launch Special','AK23LAUNCH',1,20000.00000000,'TZS 20,000 instant discount on course enrollment',50000.00000000,NULL,NULL,1,'2026-10-16 13:20:08','2031-10-21 13:20:08','2026-10-21 12:55:27','2026-10-21 12:55:27'),(2,'VIP 20% Off','STUDIO20',2,20.00000000,'20% off masterclass and software bundle',50000.00000000,NULL,NULL,1,'2026-10-16 13:20:08','2031-10-21 13:20:08','2026-10-21 12:55:27','2026-10-21 12:55:27');
/*!40000 ALTER TABLE `coupons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cron_job_logs`
--

DROP TABLE IF EXISTS `cron_job_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cron_job_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cron_job_id` int NOT NULL DEFAULT '0',
  `start_at` datetime DEFAULT NULL,
  `end_at` datetime DEFAULT NULL,
  `duration` int NOT NULL DEFAULT '0',
  `error` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cron_job_logs`
--

LOCK TABLES `cron_job_logs` WRITE;
/*!40000 ALTER TABLE `cron_job_logs` DISABLE KEYS */;
INSERT INTO `cron_job_logs` VALUES (2,1,'2026-10-19 16:06:21','2026-10-19 16:06:21',0,NULL,'2026-10-19 13:06:21','2026-10-19 13:06:21'),(3,1,'2026-10-20 17:34:38','2026-10-20 17:34:38',0,NULL,'2026-10-20 14:34:38','2026-10-20 14:34:38'),(4,1,'2026-10-20 22:18:32','2026-10-20 22:18:32',0,NULL,'2026-10-20 19:18:32','2026-10-20 19:18:32'),(5,1,'2026-10-22 20:20:43','2026-10-22 20:20:43',0,NULL,'2026-10-22 17:20:43','2026-10-22 17:20:43'),(6,1,'2026-10-23 22:56:20','2026-10-23 22:56:21',0,NULL,'2026-10-23 19:56:21','2026-10-23 19:56:21');
/*!40000 ALTER TABLE `cron_job_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cron_jobs`
--

DROP TABLE IF EXISTS `cron_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cron_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alias` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `action` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cron_schedule_id` int NOT NULL DEFAULT '0',
  `next_run` datetime DEFAULT NULL,
  `last_run` datetime DEFAULT NULL,
  `is_running` tinyint(1) NOT NULL DEFAULT '1',
  `is_default` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cron_jobs`
--

LOCK TABLES `cron_jobs` WRITE;
/*!40000 ALTER TABLE `cron_jobs` DISABLE KEYS */;
INSERT INTO `cron_jobs` VALUES (1,'Campaign Expired Status Update','campaign_expired_status_update','[\"App\\\\Http\\\\Controllers\\\\CronController\", \"campaignExpired\"]',NULL,2,'2026-10-24 22:56:21','2026-10-23 22:56:21',1,1,'2024-07-16 00:45:10','2026-10-23 19:56:21');
/*!40000 ALTER TABLE `cron_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cron_schedules`
--

DROP TABLE IF EXISTS `cron_schedules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cron_schedules` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `interval` int NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cron_schedules`
--

LOCK TABLES `cron_schedules` WRITE;
/*!40000 ALTER TABLE `cron_schedules` DISABLE KEYS */;
INSERT INTO `cron_schedules` VALUES (1,'Hourly',3600,1,'2025-02-05 01:04:48','2025-02-05 01:04:48'),(2,'Daily',86400,1,'2025-02-10 17:56:51','2025-02-10 17:56:51');
/*!40000 ALTER TABLE `cron_schedules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `deposits`
--

DROP TABLE IF EXISTS `deposits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `deposits` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL DEFAULT '0',
  `order_id` int DEFAULT NULL,
  `method_code` int unsigned NOT NULL DEFAULT '0',
  `from_account_balance` tinyint NOT NULL DEFAULT '0',
  `amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `method_currency` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `charge` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `rate` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `final_amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `detail` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `btc_amount` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `btc_wallet` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trx` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_try` int NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1=>success, 2=>pending, 3=>cancel',
  `from_api` tinyint(1) NOT NULL DEFAULT '0',
  `is_web` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'This will be 1 if the request is from NextJs application',
  `admin_feedback` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `success_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `failed_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_cron` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `deposits`
--

LOCK TABLES `deposits` WRITE;
/*!40000 ALTER TABLE `deposits` DISABLE KEYS */;
INSERT INTO `deposits` VALUES (1,1,1,801,0,150000.00000000,'TSH',0.00000000,1.00000000,150000.00000000,'{\"channel\":\"M-Pesa Selcom\",\"phone\":\"255754123456\",\"selcom_order_id\":\"SLCM-421719\",\"customer_name\":\"Amina Mushi\"}',NULL,NULL,'SELCOM-MP-839210',0,1,0,0,NULL,NULL,NULL,0,'2026-10-20 02:34:04','2026-10-20 02:34:04'),(2,2,2,801,0,590000.00000000,'TSH',0.00000000,1.00000000,590000.00000000,'{\"channel\":\"Tigo Pesa Selcom\",\"phone\":\"255713987654\",\"selcom_order_id\":\"SLCM-599723\",\"customer_name\":\"John Kweka\"}',NULL,NULL,'SELCOM-TG-592811',0,1,0,0,NULL,NULL,NULL,0,'2026-10-19 22:34:04','2026-10-19 22:34:04'),(3,3,3,801,0,250000.00000000,'TSH',0.00000000,1.00000000,250000.00000000,'{\"channel\":\"Airtel Money Selcom\",\"phone\":\"255784556677\",\"selcom_order_id\":\"SLCM-119763\",\"customer_name\":\"Rohema Ngowi\"}',NULL,NULL,'SELCOM-AIR-441029',0,1,0,0,NULL,NULL,NULL,0,'2026-10-19 14:34:04','2026-10-19 14:34:04'),(4,4,4,801,0,165000.00000000,'TSH',0.00000000,1.00000000,165000.00000000,'{\"channel\":\"M-Pesa Selcom\",\"phone\":\"255762334455\",\"selcom_order_id\":\"SLCM-562312\",\"customer_name\":\"David Kimaro\"}',NULL,NULL,'SELCOM-MP-993814',0,1,0,0,NULL,NULL,NULL,0,'2026-10-19 04:34:04','2026-10-19 04:34:04'),(5,5,5,801,0,480000.00000000,'TSH',0.00000000,1.00000000,480000.00000000,'{\"channel\":\"Halopesa Selcom\",\"phone\":\"255773112233\",\"selcom_order_id\":\"SLCM-934615\",\"customer_name\":\"Fatma Mohamed\"}',NULL,NULL,'SELCOM-HALO-123490',0,1,0,0,NULL,NULL,NULL,0,'2026-10-18 04:34:04','2026-10-18 04:34:04'),(6,6,6,801,0,120000.00000000,'TSH',0.00000000,1.00000000,120000.00000000,'{\"channel\":\"M-Pesa Selcom\",\"phone\":\"255755998877\",\"selcom_order_id\":\"SLCM-474060\",\"customer_name\":\"Emmanuel Mtei\"}',NULL,NULL,'SELCOM-MP-772108',0,1,0,0,NULL,NULL,NULL,0,'2026-10-17 04:34:04','2026-10-17 04:34:04'),(7,1,7,801,0,210000.00000000,'TSH',0.00000000,1.00000000,210000.00000000,'{\"channel\":\"M-Pesa Selcom\",\"phone\":\"255754123456\",\"selcom_order_id\":\"SLCM-972585\",\"customer_name\":\"Amina Mushi\"}',NULL,NULL,'SELCOM-MP-662309',0,1,0,0,NULL,NULL,NULL,0,'2026-10-16 04:34:04','2026-10-16 04:34:04'),(8,2,8,801,0,240000.00000000,'TSH',0.00000000,1.00000000,240000.00000000,'{\"channel\":\"Tigo Pesa Selcom\",\"phone\":\"255713987654\",\"selcom_order_id\":\"SLCM-971797\",\"customer_name\":\"John Kweka\"}',NULL,NULL,'SELCOM-TG-448201',0,1,0,0,NULL,NULL,NULL,0,'2026-10-15 04:34:04','2026-10-15 04:34:04'),(9,3,9,801,0,520000.00000000,'TSH',0.00000000,1.00000000,520000.00000000,'{\"channel\":\"Airtel Money Selcom\",\"phone\":\"255784556677\",\"selcom_order_id\":\"SLCM-328484\",\"customer_name\":\"Rohema Ngowi\"}',NULL,NULL,'SELCOM-AIR-882910',0,1,0,0,NULL,NULL,NULL,0,'2026-10-14 04:34:04','2026-10-14 04:34:04'),(10,8,10,801,0,140000.00000000,'TZS',0.00000000,1.00000000,140000.00000000,'{\"phone\":\"255754123456\",\"operator\":\"MPESA\",\"sku\":\"AK23-COURSE-CYMATICS_VOXITY\"}','0','','AK-IWQZLBTS',0,1,0,0,NULL,'http://localhost/user/download','http://localhost/checkout/AK23-COURSE-CYMATICS_VOXITY',0,'2026-10-21 08:46:41','2026-10-21 08:46:44'),(11,8,11,801,0,140000.00000000,'TZS',0.00000000,1.00000000,140000.00000000,'{\"phone\":\"255754123456\",\"operator\":\"MPESA\",\"sku\":\"AK23-COURSE-CYMATICS_VOXITY\"}','0','','AK-OZZRH1QE',0,1,0,0,NULL,'http://localhost/user/download','http://localhost/checkout/AK23-COURSE-CYMATICS_VOXITY',0,'2026-10-21 08:48:22','2026-10-21 08:48:22'),(12,9,12,801,0,140000.00000000,'TZS',0.00000000,1.00000000,140000.00000000,'{\"phone\":\"255754123456\",\"operator\":\"MPESA\",\"sku\":\"AK23-COURSE-CYMATICS_VOXITY\"}','0','','AK-X0VNJFBB',0,0,0,0,NULL,'http://localhost/akcourse/user/download','http://localhost/akcourse/checkout/AK23-COURSE-CYMATICS_VOXITY',0,'2026-10-21 08:50:54','2026-10-21 08:50:54'),(13,9,13,801,0,140000.00000000,'TZS',0.00000000,1.00000000,140000.00000000,'{\"phone\":\"255754123456\",\"operator\":\"MPESA\",\"sku\":\"AK23-COURSE-CYMATICS_VOXITY\"}','0','','AK-APEEQCDP',0,0,0,0,NULL,'http://localhost/akcourse/user/download','http://localhost/akcourse/checkout/AK23-COURSE-CYMATICS_VOXITY',0,'2026-10-21 09:02:26','2026-10-21 09:02:26'),(14,9,14,801,0,140000.00000000,'TZS',0.00000000,1.00000000,140000.00000000,'{\"phone\":\"255754123456\",\"operator\":\"MPESA\",\"sku\":\"AK23-COURSE-CYMATICS_VOXITY\"}','0','','AK-JYGECHD4',0,0,0,0,NULL,'http://localhost/akcourse/user/download','http://localhost/akcourse/checkout/AK23-COURSE-CYMATICS_VOXITY',0,'2026-10-21 09:04:41','2026-10-21 09:04:41'),(15,9,15,801,0,140000.00000000,'TZS',0.00000000,1.00000000,140000.00000000,'{\"phone\":\"255754123456\",\"operator\":\"MPESA\",\"sku\":\"AK23-COURSE-CYMATICS_VOXITY\"}','0','','AK-V6SQNIMK',0,1,0,0,NULL,'http://localhost/akcourse/user/download','http://localhost/akcourse/checkout/AK23-COURSE-CYMATICS_VOXITY',0,'2026-10-21 09:04:55','2026-10-21 09:05:08'),(16,10,16,801,0,140000.00000000,'TZS',0.00000000,1.00000000,140000.00000000,'{\"phone\":\"255747003357\",\"operator\":\"MPESA\",\"sku\":\"AK23-COURSE-CYMATICS_VOXITY\"}','0','','AK-NHVSEJRC',0,0,0,0,NULL,'http://localhost/akcourse/user/download','http://localhost/akcourse/checkout/AK23-COURSE-CYMATICS_VOXITY',0,'2026-10-21 09:19:56','2026-10-21 09:19:56'),(17,10,17,801,0,250000.00000000,'TZS',0.00000000,1.00000000,250000.00000000,'{\"payment_method\":\"mobile_money\",\"phone\":\"255747003357\",\"operator\":\"MPESA\",\"sku\":\"AK23-COURSE-WAVES_ULTIMATE_17\"}','0','','AK-KGVTCIDU',0,0,0,0,NULL,'http://localhost/akcourse/user/download','http://localhost/akcourse/checkout/AK23-COURSE-WAVES_ULTIMATE_17',0,'2026-10-21 10:06:51','2026-10-21 10:06:51'),(18,9,18,801,0,120000.00000000,'TZS',0.00000000,1.00000000,120000.00000000,'{\"payment_method\":\"mobile_money\",\"phone\":\"255754123456\",\"operator\":\"MPESA\",\"sku\":\"AK23-COURSE-TUNED_PLUGINS\",\"selcom_error\":{\"success\":false,\"data\":{\"reference\":\"S20776479272\",\"resultcode\":\"403\",\"result\":\"FAIL\",\"message\":\"Authentication failed. Source IP not whitelisted (4032)\",\"data\":[]},\"status\":403,\"message\":\"Authentication failed. Source IP not whitelisted (4032)\"},\"api_message\":\"Authentication failed. Source IP not whitelisted (4032)\"}','0','','AK-VSV7HVKD',0,0,0,0,NULL,'http://localhost/akcourse/user/download','http://localhost/akcourse/checkout/AK23-COURSE-TUNED_PLUGINS',0,'2026-10-21 10:19:06','2026-10-21 10:19:07'),(19,9,19,802,0,120000.00000000,'TZS',0.00000000,1.00000000,120000.00000000,'{\"payment_method\":\"card\",\"card_name\":\"Alex Kaseja\",\"card_brand\":\"Visa\",\"card_last4\":\"4242\",\"sku\":\"AK23-COURSE-TUNED_PLUGINS\"}','0','','AK-YRFU9LQE',0,1,0,0,NULL,'http://localhost/akcourse/user/download','http://localhost/akcourse/checkout/AK23-COURSE-TUNED_PLUGINS',0,'2026-10-21 10:19:34','2026-10-21 10:19:34'),(20,9,20,802,0,120000.00000000,'TZS',0.00000000,1.00000000,120000.00000000,'{\"payment_method\":\"card\",\"card_name\":\"Alex Kaseja\",\"card_brand\":\"Visa\",\"card_last4\":\"4242\",\"sku\":\"AK23-COURSE-TUNED_PLUGINS\"}','0','','AK-C1EWR85E',0,1,0,0,NULL,'http://localhost/akcourse/user/download','http://localhost/akcourse/checkout/AK23-COURSE-TUNED_PLUGINS',0,'2026-10-21 10:20:31','2026-10-21 10:20:31'),(21,9,21,801,0,120000.00000000,'TZS',0.00000000,1.00000000,120000.00000000,'{\"payment_method\":\"mobile_money\",\"phone\":\"255747003357\",\"operator\":\"MPESA\",\"sku\":\"AK23-COURSE-TUNED_PLUGINS\",\"selcom_error\":{\"success\":false,\"data\":{\"reference\":\"S20776494942\",\"resultcode\":\"403\",\"result\":\"FAIL\",\"message\":\"Authentication failed. Source IP not whitelisted (4032)\",\"data\":[]},\"status\":403,\"message\":\"Authentication failed. Source IP not whitelisted (4032)\"},\"api_message\":\"Authentication failed. Source IP not whitelisted (4032)\"}','0','','AK-9WIZNXDM',0,0,0,0,NULL,'http://localhost/akcourse/user/download','http://localhost/akcourse/checkout/AK23-COURSE-TUNED_PLUGINS',0,'2026-10-21 10:34:22','2026-10-21 10:34:22'),(22,9,22,802,0,120000.00000000,'TZS',0.00000000,1.00000000,120000.00000000,'{\"payment_method\":\"card\",\"card_name\":\"Alex M. Kaseja\",\"card_brand\":\"Visa\",\"card_last4\":\"4444\",\"sku\":\"AK23-COURSE-TUNED_PLUGINS\"}','0','','AK-HSDXVYVO',0,1,0,0,NULL,'http://localhost/akcourse/user/download','http://localhost/akcourse/checkout/AK23-COURSE-TUNED_PLUGINS',0,'2026-10-21 10:36:13','2026-10-21 10:36:13');
/*!40000 ALTER TABLE `deposits` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `device_tokens`
--

DROP TABLE IF EXISTS `device_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `device_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL DEFAULT '0',
  `is_app` tinyint(1) NOT NULL DEFAULT '0',
  `token` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `device_tokens`
--

LOCK TABLES `device_tokens` WRITE;
/*!40000 ALTER TABLE `device_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `device_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `downloads`
--

DROP TABLE IF EXISTS `downloads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `downloads` (
  `id` int NOT NULL AUTO_INCREMENT,
  `product_id` int DEFAULT '0',
  `user_id` int DEFAULT '0',
  `download_count` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `downloads`
--

LOCK TABLES `downloads` WRITE;
/*!40000 ALTER TABLE `downloads` DISABLE KEYS */;
/*!40000 ALTER TABLE `downloads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `extensions`
--

DROP TABLE IF EXISTS `extensions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `extensions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `act` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `script` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `shortcode` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'object',
  `support` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'help section',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=>enable, 2=>disable',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `extensions`
--

LOCK TABLES `extensions` WRITE;
/*!40000 ALTER TABLE `extensions` DISABLE KEYS */;
INSERT INTO `extensions` VALUES (1,'whatsapp-chat','WhatsApp Chat','Live WhatsApp messaging widget allowing learners to instantly chat with AK23 Academy support and instructors via WhatsApp.','whatsapp.png','<!-- AK23 Live WhatsApp Floating Widget -->\n<div id=\"ak23-whatsapp-widget\" class=\"ak23-wa-wrapper\">\n    <!-- Popover Chat Box -->\n    <div id=\"ak23-wa-popover\" class=\"ak23-wa-popover\">\n        <div class=\"ak23-wa-header\">\n            <div class=\"ak23-wa-avatar-box\">\n                <div class=\"ak23-wa-avatar\">AK</div>\n                <span class=\"ak23-wa-status-dot\"></span>\n            </div>\n            <div class=\"ak23-wa-meta\">\n                <div class=\"ak23-wa-title\">{{title}} <i class=\"las la-check-circle\" style=\"color: #25D366;\" title=\"Verified\"></i></div>\n                <div class=\"ak23-wa-sub\">{{subtitle}}</div>\n            </div>\n            <button type=\"button\" class=\"ak23-wa-close\" id=\"ak23-wa-close-btn\" aria-label=\"Close\">&times;</button>\n        </div>\n        <div class=\"ak23-wa-body\">\n            <div class=\"ak23-wa-msg-bubble\">\n                <span class=\"ak23-wa-sender\">AK23 Academy</span>\n                <p class=\"ak23-wa-text\">Habari! 👋 Welcome to AK23 Academy. How can we help you with your vocal mixing courses, licenses, or bundle downloads?</p>\n                <span class=\"ak23-wa-time\">Just now</span>\n            </div>\n            <div class=\"ak23-wa-chips\">\n                <button type=\"button\" class=\"ak23-wa-chip\" data-msg=\"Hello AK23 Academy, I would like to learn about the Vocal Mixing Series courses\">🎵 Vocal Mixing Series</button>\n                <button type=\"button\" class=\"ak23-wa-chip\" data-msg=\"Hello AK23 Academy, I need assistance with my course ZIP package download\">📦 Bundle Download Help</button>\n                <button type=\"button\" class=\"ak23-wa-chip\" data-msg=\"Hello AK23 Academy, I have a question regarding matching plugin licenses\">🔑 Plugin Licenses</button>\n            </div>\n        </div>\n        <div class=\"ak23-wa-footer\">\n            <a href=\"https://wa.me/{{phone_number}}?text={{default_message}}\" target=\"_blank\" rel=\"noopener noreferrer\" id=\"ak23-wa-cta\" class=\"ak23-wa-btn\">\n                <i class=\"lab la-whatsapp\"></i>\n                <span>Start WhatsApp Chat</span>\n            </a>\n            <div class=\"ak23-wa-note\">Direct Instant Chat &bull; AK23 Studio Support</div>\n        </div>\n    </div>\n\n    <!-- Floating Trigger Button -->\n    <button type=\"button\" id=\"ak23-wa-toggle\" class=\"ak23-wa-trigger\" aria-label=\"Chat on WhatsApp\">\n        <i class=\"lab la-whatsapp\"></i>\n        <span class=\"ak23-wa-badge\">1</span>\n        <span class=\"ak23-wa-tooltip\">Chat with us on WhatsApp</span>\n    </button>\n</div>\n\n<style>\n.ak23-wa-wrapper {\n    position: fixed;\n    bottom: 24px;\n    right: 24px;\n    z-index: 99999;\n    font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, sans-serif;\n}\n.ak23-wa-trigger {\n    width: 58px;\n    height: 58px;\n    border-radius: 50%;\n    background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);\n    color: #FFFFFF !important;\n    border: none;\n    outline: none;\n    cursor: pointer;\n    box-shadow: 0 8px 24px rgba(37, 211, 102, 0.4), 0 2px 6px rgba(0,0,0,0.3);\n    display: flex;\n    align-items: center;\n    justify-content: center;\n    font-size: 32px;\n    position: relative;\n    transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.25s ease;\n}\n.ak23-wa-trigger:hover {\n    transform: scale(1.08);\n    box-shadow: 0 10px 28px rgba(37, 211, 102, 0.55);\n}\n.ak23-wa-badge {\n    position: absolute;\n    top: -2px;\n    right: -2px;\n    width: 20px;\n    height: 20px;\n    background: #FF3B30;\n    color: #FFF;\n    font-size: 11px;\n    font-weight: 800;\n    border-radius: 50%;\n    display: flex;\n    align-items: center;\n    justify-content: center;\n    border: 2px solid #121316;\n}\n.ak23-wa-tooltip {\n    position: absolute;\n    right: 70px;\n    background: #181820;\n    color: #FBAF43;\n    padding: 6px 12px;\n    border-radius: 6px;\n    font-size: 12px;\n    font-weight: 700;\n    white-space: nowrap;\n    border: 1px solid rgba(251, 175, 67, 0.3);\n    box-shadow: 0 4px 14px rgba(0,0,0,0.4);\n    opacity: 0;\n    pointer-events: none;\n    transform: translateX(8px);\n    transition: opacity 0.2s ease, transform 0.2s ease;\n}\n.ak23-wa-trigger:hover .ak23-wa-tooltip {\n    opacity: 1;\n    transform: translateX(0);\n}\n.ak23-wa-popover {\n    display: none;\n    position: absolute;\n    bottom: 74px;\n    right: 0;\n    width: 340px;\n    max-width: calc(100vw - 32px);\n    background: #14141A;\n    border: 1px solid rgba(251, 175, 67, 0.25);\n    border-radius: 14px;\n    overflow: hidden;\n    box-shadow: 0 16px 40px rgba(0, 0, 0, 0.65), 0 0 20px rgba(251, 175, 67, 0.1);\n    animation: ak23WaSlideUp 0.25s cubic-bezier(0.16, 1, 0.3, 1) forwards;\n}\n@keyframes ak23WaSlideUp {\n    from { opacity: 0; transform: translateY(12px) scale(0.96); }\n    to { opacity: 1; transform: translateY(0) scale(1); }\n}\n.ak23-wa-popover.open {\n    display: block;\n}\n.ak23-wa-header {\n    background: #0D0E12;\n    padding: 14px 16px;\n    display: flex;\n    align-items: center;\n    gap: 12px;\n    border-bottom: 1px solid rgba(255, 255, 255, 0.08);\n}\n.ak23-wa-avatar-box {\n    position: relative;\n    width: 38px;\n    height: 38px;\n    flex-shrink: 0;\n}\n.ak23-wa-avatar {\n    width: 100%;\n    height: 100%;\n    border-radius: 50%;\n    background: linear-gradient(135deg, #FBAF43 0%, #E29828 100%);\n    color: #121316;\n    font-weight: 900;\n    font-size: 14px;\n    display: flex;\n    align-items: center;\n    justify-content: center;\n}\n.ak23-wa-status-dot {\n    position: absolute;\n    bottom: 0;\n    right: 0;\n    width: 10px;\n    height: 10px;\n    border-radius: 50%;\n    background: #25D366;\n    border: 2px solid #0D0E12;\n}\n.ak23-wa-meta {\n    flex-grow: 1;\n}\n.ak23-wa-title {\n    color: #FFFFFF;\n    font-size: 13.5px;\n    font-weight: 700;\n    display: flex;\n    align-items: center;\n    gap: 4px;\n}\n.ak23-wa-sub {\n    color: #94A3B8;\n    font-size: 11px;\n}\n.ak23-wa-close {\n    background: transparent;\n    border: none;\n    color: #94A3B8;\n    font-size: 22px;\n    cursor: pointer;\n    line-height: 1;\n    padding: 0 4px;\n    transition: color 0.15s ease;\n}\n.ak23-wa-close:hover {\n    color: #FFFFFF;\n}\n.ak23-wa-body {\n    padding: 16px;\n    background: #14141A;\n    background-image: radial-gradient(rgba(255, 255, 255, 0.03) 1px, transparent 1px);\n    background-size: 12px 12px;\n}\n.ak23-wa-msg-bubble {\n    background: #1C1D24;\n    border: 1px solid rgba(255, 255, 255, 0.07);\n    border-radius: 12px 12px 12px 2px;\n    padding: 12px 14px;\n    margin-bottom: 14px;\n    position: relative;\n}\n.ak23-wa-sender {\n    display: block;\n    font-size: 10px;\n    font-weight: 800;\n    color: #FBAF43;\n    text-transform: uppercase;\n    letter-spacing: 0.05em;\n    margin-bottom: 4px;\n}\n.ak23-wa-text {\n    font-size: 12.5px;\n    line-height: 1.5;\n    color: #E2E8F0;\n    margin: 0 0 6px 0;\n}\n.ak23-wa-time {\n    display: block;\n    text-align: right;\n    font-size: 10px;\n    color: #64748B;\n}\n.ak23-wa-chips {\n    display: flex;\n    flex-direction: column;\n    gap: 6px;\n}\n.ak23-wa-chip {\n    background: rgba(251, 175, 67, 0.06);\n    border: 1px solid rgba(251, 175, 67, 0.2);\n    border-radius: 8px;\n    color: #CBD5E1;\n    font-size: 11.5px;\n    padding: 7px 10px;\n    text-align: left;\n    cursor: pointer;\n    transition: all 0.2s ease;\n}\n.ak23-wa-chip:hover {\n    background: rgba(251, 175, 67, 0.15);\n    border-color: #FBAF43;\n    color: #FBAF43;\n    transform: translateX(3px);\n}\n.ak23-wa-footer {\n    padding: 12px 16px 14px;\n    background: #0E0F13;\n    border-top: 1px solid rgba(255, 255, 255, 0.06);\n}\n.ak23-wa-btn {\n    display: flex;\n    align-items: center;\n    justify-content: center;\n    gap: 8px;\n    background: #25D366;\n    color: #0A0A0E !important;\n    font-weight: 700;\n    font-size: 13.5px;\n    padding: 11px 16px;\n    border-radius: 8px;\n    text-decoration: none !important;\n    transition: background 0.2s ease, transform 0.15s ease;\n    box-shadow: 0 4px 14px rgba(37, 211, 102, 0.35);\n}\n.ak23-wa-btn:hover {\n    background: #20BA5A;\n    color: #0A0A0E !important;\n    transform: translateY(-1px);\n}\n.ak23-wa-btn i {\n    font-size: 18px;\n}\n.ak23-wa-note {\n    text-align: center;\n    font-size: 10px;\n    color: #64748B;\n    margin-top: 8px;\n}\n</style>\n\n<script>\n(function() {\n    function initAk23Wa() {\n        var toggle = document.getElementById(\'ak23-wa-toggle\');\n        var popover = document.getElementById(\'ak23-wa-popover\');\n        var closeBtn = document.getElementById(\'ak23-wa-close-btn\');\n        var cta = document.getElementById(\'ak23-wa-cta\');\n        if (!toggle || !popover) return;\n\n        var phone = \"{{phone_number}}\";\n        var baseMsg = \"{{default_message}}\";\n\n        toggle.addEventListener(\'click\', function(e) {\n            e.stopPropagation();\n            popover.classList.toggle(\'open\');\n        });\n\n        if (closeBtn) {\n            closeBtn.addEventListener(\'click\', function(e) {\n                e.stopPropagation();\n                popover.classList.remove(\'open\');\n            });\n        }\n\n        document.addEventListener(\'click\', function(e) {\n            if (!popover.contains(e.target) && e.target !== toggle && !toggle.contains(e.target)) {\n                popover.classList.remove(\'open\');\n            }\n        });\n\n        var chips = popover.querySelectorAll(\'.ak23-wa-chip\');\n        chips.forEach(function(chip) {\n            chip.addEventListener(\'click\', function() {\n                var customMsg = this.getAttribute(\'data-msg\') || baseMsg;\n                var url = \"https://wa.me/\" + phone + \"?text=\" + encodeURIComponent(customMsg);\n                window.open(url, \'_blank\', \'noopener,noreferrer\');\n                popover.classList.remove(\'open\');\n            });\n        });\n    }\n\n    if (document.readyState === \'loading\') {\n        document.addEventListener(\'DOMContentLoaded\', initAk23Wa);\n    } else {\n        initAk23Wa();\n    }\n})();\n</script>','{\"phone_number\":{\"title\":\"WhatsApp Phone Number (with Country Code e.g. 255755000000)\",\"value\":\"255755000000\"},\"title\":{\"title\":\"Widget Header Title\",\"value\":\"AK23 Academy Live Support\"},\"subtitle\":{\"title\":\"Status Text \\/ Hours\",\"value\":\"Online \\u2022 Replies in 15 min\"},\"default_message\":{\"title\":\"Default Chat Message\",\"value\":\"Hello AK23 Academy! I have an inquiry about the vocal mixing courses and bundle download.\"}}','na',1,'2019-10-18 23:16:05','2026-10-21 04:01:05'),(2,'google-recaptcha2','Google Recaptcha 2','Key location is shown bellow','recaptcha3.png','\n<script src=\"https://www.google.com/recaptcha/api.js\"></script>\n<div class=\"g-recaptcha\" data-sitekey=\"{{site_key}}\" data-callback=\"verifyCaptcha\"></div>\n<div id=\"g-recaptcha-error\"></div>','{\"site_key\":{\"title\":\"Site Key\",\"value\":\"---------------------------\"},\"secret_key\":{\"title\":\"Secret Key\",\"value\":\"--------------------------\"}}','recaptcha.png',0,'2019-10-18 23:16:05','2024-02-05 05:48:49'),(3,'custom-captcha','Custom Captcha','Just put any random string','customcaptcha.png',NULL,'{\"random_key\":{\"title\":\"Random String\",\"value\":\"SecureString\"}}','na',0,'2019-10-18 23:16:05','2023-12-24 10:46:43'),(4,'google-analytics','Google Analytics','Key location is shown bellow','google_analytics.png','<script async src=\"https://www.googletagmanager.com/gtag/js?id={{measurement_id}}\"></script>\n                <script>\n                  window.dataLayer = window.dataLayer || [];\n                  function gtag(){dataLayer.push(arguments);}\n                  gtag(\"js\", new Date());\n                \n                  gtag(\"config\", \"{{measurement_id}}\");\n                </script>','{\"measurement_id\":{\"title\":\"Measurement ID\",\"value\":\"------\"}}','ganalytics.png',0,NULL,'2021-05-04 10:19:12'),(5,'fb-comment','Facebook Comment ','Key location is shown bellow','Facebook.png','<div id=\"fb-root\"></div><script async defer crossorigin=\"anonymous\" src=\"https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v4.0&appId={{app_key}}&autoLogAppEvents=1\"></script>','{\"app_key\":{\"title\":\"App Key\",\"value\":\"----\"}}','fb_com.png',0,NULL,'2022-03-22 05:18:36');
/*!40000 ALTER TABLE `extensions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `forms`
--

DROP TABLE IF EXISTS `forms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `forms` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `act` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `form_data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `forms`
--

LOCK TABLES `forms` WRITE;
/*!40000 ALTER TABLE `forms` DISABLE KEYS */;
/*!40000 ALTER TABLE `forms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `frontends`
--

DROP TABLE IF EXISTS `frontends`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `frontends` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `data_keys` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `seo_content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `tempname` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `frontends_data_keys_index` (`data_keys`),
  KEY `frontends_slug_index` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=94 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `frontends`
--

LOCK TABLES `frontends` WRITE;
/*!40000 ALTER TABLE `frontends` DISABLE KEYS */;
INSERT INTO `frontends` VALUES (1,'seo.data','{\"seo_image\":\"1\",\"keywords\":[\"Maarifa23\",\"Maarifa 23\",\"Audio Academy\",\"Vocal Mixing Courses\",\"Studio Software\",\"Music Production\",\"DAW Workflows\",\"Sound Engineering Tanzania\",\"Lifetime Software License\"],\"description\":\"Maarifa23 is East Africa\'s premier audio production academy. Master vocal mixing, DAW workflows, multitrack stems, and receive one-time lifetime software licenses with official certificates.\",\"social_title\":\"Maarifa23 - Audio Production & Studio Software Academy\",\"social_description\":\"Maarifa23 is East Africa\'s premier audio production academy. Master vocal mixing, DAW workflows, multitrack stems, and receive one-time lifetime software licenses with official certificates.\",\"image\":\"680e19c1a204e1745754561.png\"}',NULL,NULL,'','2020-07-04 23:42:52','2026-10-23 21:52:11'),(24,'about.content','{\"has_image\":\"1\",\"heading\":\"Latest News\",\"sub_heading\":\"Register New Account\",\"description\":\"fdg sdfgsdf g ggg\",\"about_icon\":\"<i class=\\\"las la-address-card\\\"><\\/i>\",\"background_image\":\"60951a84abd141620384388.png\",\"about_image\":\"5f9914e907ace1603867881.jpg\"}',NULL,NULL,NULL,'2020-10-28 00:51:20','2021-05-07 10:16:28'),(25,'blog.content','{\"heading\":\"Latest News\",\"subheading\":\"------\"}',NULL,NULL,NULL,'2020-10-28 00:51:34','2022-03-19 04:41:13'),(26,'blog.element','{\"has_image\":[\"1\",\"1\"],\"title\":\"this is a test blog 2\",\"description\":\"aewf asdf\",\"description_nic\":\"asdf asdf\",\"blog_icon\":\"<i class=\\\"lab la-hornbill\\\"><\\/i>\",\"blog_image_1\":\"5f99164f1baec1603868239.jpg\",\"blog_image_2\":\"5ff2e146346d21609752902.jpg\"}',NULL,NULL,'this-is-a-test-blog-2','2020-10-28 00:57:19','2021-01-04 03:35:02'),(27,'contact_us.content','{\"heading_one\":\"Contact & Academic Support\",\"subheading_one\":\"Have questions about the AK23 vocal mixing course series, direct zip package downloads, or plugin licenses? Reach out directly.\",\"heading_two\":\"Send Us a Direct Message\",\"subheading_two\":\"Submit an inquiry ticket below and our instructors will get back to you promptly.\",\"working_hours\":\"Monday - Saturday: 9:00 AM - 8:00 PM (EAT)\",\"response_time\":\"Typical response time: within 1 to 2 hours\",\"has_image\":\"0\",\"image\":\"\"}',NULL,'basic',NULL,'2020-10-28 00:59:19','2026-10-21 04:04:29'),(28,'counter.content','{\"heading\":\"Latest News\",\"sub_heading\":\"Register New Account\"}',NULL,NULL,NULL,'2020-10-28 01:04:02','2020-10-28 01:04:02'),(31,'social_icon.element','{\"title\":\"Facebook\",\"social_icon\":\"<i class=\\\"fab fa-facebook-f\\\"><\\/i>\",\"url\":\"https:\\/\\/www.facebook.com\\/\"}',NULL,'basic',NULL,'2020-11-12 04:07:30','2023-12-20 14:10:08'),(33,'feature.content','{\"heading\":\"asdf\",\"sub_heading\":\"asdf\"}',NULL,NULL,NULL,'2021-01-03 23:40:54','2021-01-03 23:40:55'),(34,'feature.element','{\"title\":\"asdf\",\"description\":\"asdf\",\"feature_icon\":\"asdf\"}',NULL,NULL,NULL,'2021-01-03 23:41:02','2021-01-03 23:41:02'),(35,'service.element','{\"trx_type\":\"withdraw\",\"service_icon\":\"<i class=\\\"las la-highlighter\\\"><\\/i>\",\"title\":\"asdfasdf\",\"description\":\"asdfasdfasdfasdf\"}',NULL,NULL,NULL,'2021-03-06 01:12:10','2021-03-06 01:12:10'),(36,'service.content','{\"trx_type\":\"deposit\",\"heading\":\"asdf fffff\",\"subheading\":\"555\"}',NULL,NULL,NULL,'2021-03-06 01:27:34','2022-03-30 08:07:06'),(39,'banner.content','{\"title\":\"Download 23,000+ Digital Resources\",\"subtitle\":\"Maarifa23 offers a wide range of digital products, including PHP scripts, WordPress themes & plugins, website templates, graphics, and more.\",\"counter_title\":\"More Than\",\"counter_subtitle\":\"2000+\",\"has_image\":\"1\",\"image\":\"65b9e8bd058c51706682557.png\",\"counter_image\":\"656f05a55b8801701774757.png\"}',NULL,'basic',NULL,'2021-05-02 06:09:30','2026-10-23 21:52:11'),(41,'cookie.data','{\"short_desc\":\"We may use cookies or any other tracking technologies when you visit our website, including any other media form, mobile website, or mobile application related or connected to help customize the Site and improve your experience.\",\"description\":\"<div><h6 class=\\\"mb-1\\\">What information do we collect?<\\/h6><p>We gather data from you when you register on our site, submit a request, buy any services, react to an overview, or round out a structure. At the point when requesting any assistance or enrolling on our site, as suitable, you might be approached to enter your: name, email address, or telephone number. You may, nonetheless, visit our site anonymously.<\\/p><p><br><\\/p><\\/div><div><h6 class=\\\"mb-1\\\">How do we protect your information?<\\/h6><p>All provided delicate\\/credit data is sent through Stripe.<br>After an exchange, your private data (credit cards, social security numbers, financials, and so on) won\'t be put away on our workers.<\\/p><p><br><\\/p><\\/div><div><h6 class=\\\"mb-1\\\">Do we disclose any information to outside parties?<\\/h6><p>We don\'t sell, exchange, or in any case move to outside gatherings by and by recognizable data. This does exclude confided in outsiders who help us in working our site, leading our business, or adjusting you, since those gatherings consent to keep this data private. We may likewise deliver your data when we accept discharge is suitable to follow the law, implement our site strategies, or ensure our own or others\' rights, property, or wellbeing.<\\/p><p><br><\\/p><\\/div><div><h6 class=\\\"mb-1\\\">Children\'s Online Privacy Protection Act Compliance<\\/h6><p>We are consistent with the prerequisites of COPPA (Children\'s Online Privacy Protection Act), we don\'t gather any data from anybody under 13 years old. Our site, items, and administrations are completely coordinated to individuals who are in any event 13 years of age or more established.<\\/p><p><br><\\/p><\\/div><div><h6 class=\\\"mb-1\\\">Changes to our Privacy Policy<\\/h6><p>If we decide to change our privacy policy, we will post those changes on this page.<\\/p><p><br><\\/p><\\/div><div><h6 class=\\\"mb-1\\\">How long we retain your information?<\\/h6><p>At the point when you register for our site, we cycle and keep your information we have about you however long you don\'t erase the record or withdraw yourself (subject to laws and guidelines).<\\/p><p><br><\\/p><\\/div><div><h6 class=\\\"mb-1\\\">What we don\\u2019t do with your data<\\/h6><p>We don\'t and will never share, unveil, sell, or in any case give your information to different organizations for the promoting of their items or administrations.<\\/p><\\/div>\",\"status\":0}',NULL,NULL,NULL,'2020-07-04 23:42:52','2026-10-20 07:32:44'),(42,'policy_pages.element','{\"title\":\"Privacy Policy\",\"details\":\"<div class=\\\"m23-policy-section mb-4\\\">\\n    <div class=\\\"d-flex align-items-center gap-2 mb-2\\\">\\n        <span class=\\\"m23-policy-num\\\">01<\\/span>\\n        <h5 class=\\\"text-white fw-bold mb-0\\\">Overview & Scope<\\/h5>\\n    <\\/div>\\n    <p class=\\\"text-secondary fs-14 leading-relaxed mb-3\\\">\\n        This Privacy Policy outlines how <strong>Maarifa23 Academy<\\/strong> (\\\"Maarifa23\\\", \\\"we\\\", \\\"our\\\", or \\\"us\\\") collects, secures, manages, and protects the personal information and digital transactions of students, creators, and buyers across our platform at <a href=\\\"\\/\\\" class=\\\"text-warning text-decoration-none\\\">maarifa23.com<\\/a>.\\n    <\\/p>\\n    <div class=\\\"p-3 rounded-2\\\" style=\\\"background: rgba(245, 158, 11, 0.08); border-left: 3px solid #F59E0B;\\\">\\n        <p class=\\\"mb-0 text-white fs-13\\\">\\n            <i class=\\\"las la-info-circle me-1 text-warning\\\"><\\/i>\\n            By enrolling in courses, downloading multitrack stems or DAW sample packs, or obtaining perpetual software licenses from Maarifa23, you acknowledge and agree to the privacy and security practices described herein.\\n        <\\/p>\\n    <\\/div>\\n<\\/div>\\n\\n<div class=\\\"m23-policy-section mb-4\\\">\\n    <div class=\\\"d-flex align-items-center gap-2 mb-2\\\">\\n        <span class=\\\"m23-policy-num\\\">02<\\/span>\\n        <h5 class=\\\"text-white fw-bold mb-0\\\">Information We Collect<\\/h5>\\n    <\\/div>\\n    <p class=\\\"text-secondary fs-14 leading-relaxed mb-2\\\">\\n        We only gather the minimum personal information necessary to deliver authentic course materials, maintain lifetime software licenses, and facilitate authorized student downloads:\\n    <\\/p>\\n    <ul class=\\\"text-secondary fs-14 ps-3 mb-0\\\" style=\\\"line-height: 1.8;\\\">\\n        <li><strong class=\\\"text-white\\\">Account Identification:<\\/strong> Your full name, email address, username, and mobile telephone number provided during student registration.<\\/li>\\n        <li><strong class=\\\"text-white\\\">Order & License Credentials:<\\/strong> Cryptographic purchase codes, generated lifetime license keys (<code>M23-LIC-...<\\/code>), official purchase certificate serials (<code>M23-CERT-...<\\/code>), and transaction references.<\\/li>\\n        <li><strong class=\\\"text-white\\\">Download & Access Logs:<\\/strong> Timestamped records of digital course pack and studio file retrievals to protect student accounts from unauthorized access.<\\/li>\\n        <li><strong class=\\\"text-white\\\">Support Communications:<\\/strong> Inquiries, technical help tickets, and WhatsApp assistance records submitted to our academic support staff.<\\/li>\\n    <\\/ul>\\n<\\/div>\\n\\n<div class=\\\"m23-policy-section mb-4\\\">\\n    <div class=\\\"d-flex align-items-center gap-2 mb-2\\\">\\n        <span class=\\\"m23-policy-num\\\">03<\\/span>\\n        <h5 class=\\\"text-white fw-bold mb-0\\\">Payment Security & Zero Card Storage<\\/h5>\\n    <\\/div>\\n    <p class=\\\"text-secondary fs-14 leading-relaxed mb-3\\\">\\n        All financial payments (including Mobile Money, Tigo Pesa, M-Pesa, Airtel Money, and card transactions) are processed through certified, bank-grade payment gateway partners using end-to-end 256-bit SSL encryption.\\n    <\\/p>\\n    <div class=\\\"p-3 rounded-2\\\" style=\\\"background: rgba(16, 185, 129, 0.08); border-left: 3px solid #10B981;\\\">\\n        <p class=\\\"mb-0 text-white fs-13\\\">\\n            <i class=\\\"las la-shield-alt me-1 text-success\\\"><\\/i>\\n            <strong>Zero Financial Storage:<\\/strong> Maarifa23 servers <u>never<\\/u> store, handle, or retain credit card numbers, CVVs, or mobile money PINs. All billing handshakes occur directly within isolated, certified gateway environments.\\n        <\\/p>\\n    <\\/div>\\n<\\/div>\\n\\n<div class=\\\"m23-policy-section mb-4\\\">\\n    <div class=\\\"d-flex align-items-center gap-2 mb-2\\\">\\n        <span class=\\\"m23-policy-num\\\">04<\\/span>\\n        <h5 class=\\\"text-white fw-bold mb-0\\\">License Registry & Anti-Tamper Verification<\\/h5>\\n    <\\/div>\\n    <p class=\\\"text-secondary fs-14 leading-relaxed mb-2\\\">\\n        Every completed course purchase automatically generates a verifiable <strong>Certificate of Course Purchase<\\/strong> and perpetual software license key stored in our active registry:\\n    <\\/p>\\n    <ul class=\\\"text-secondary fs-14 ps-3 mb-0\\\" style=\\\"line-height: 1.8;\\\">\\n        <li>Public verification lookups (via <code>\\/verify-license<\\/code>) authenticate only the validity of the license, student name, and associated course.<\\/li>\\n        <li>Private billing information, payment methods, personal email addresses, and phone numbers are <u>strictly masked<\\/u> and never exposed via verification queries.<\\/li>\\n        <li>License keys remain permanently assigned to the purchasing student account with zero recurring renewal fees or expirations.<\\/li>\\n    <\\/ul>\\n<\\/div>\\n\\n<div class=\\\"m23-policy-section mb-4\\\">\\n    <div class=\\\"d-flex align-items-center gap-2 mb-2\\\">\\n        <span class=\\\"m23-policy-num\\\">05<\\/span>\\n        <h5 class=\\\"text-white fw-bold mb-0\\\">Strict Zero Third-Party Selling Policy<\\/h5>\\n    <\\/div>\\n    <p class=\\\"text-secondary fs-14 leading-relaxed mb-2\\\">\\n        We respect your creative focus and privacy. We maintain an absolute policy regarding student data:\\n    <\\/p>\\n    <ul class=\\\"text-secondary fs-14 ps-3 mb-0\\\" style=\\\"line-height: 1.8;\\\">\\n        <li>We <strong>do not sell, rent, monetize, or trade<\\/strong> student contact information, email addresses, or usage patterns to third-party advertisers or commercial data brokers.<\\/li>\\n        <li>Information is shared only with trusted operational providers (such as transactional email delivery and gateway settlement) operating under strict non-disclosure obligations.<\\/li>\\n        <li>We will never send unsolicited promotional spam to your phone or inbox.<\\/li>\\n    <\\/ul>\\n<\\/div>\\n\\n<div class=\\\"m23-policy-section mb-4\\\">\\n    <div class=\\\"d-flex align-items-center gap-2 mb-2\\\">\\n        <span class=\\\"m23-policy-num\\\">06<\\/span>\\n        <h5 class=\\\"text-white fw-bold mb-0\\\">Data Retention & Student Rights<\\/h5>\\n    <\\/div>\\n    <p class=\\\"text-secondary fs-14 leading-relaxed mb-2\\\">\\n        Under applicable data protection laws and Maarifa23 standards, you hold full authority over your personal information:\\n    <\\/p>\\n    <ul class=\\\"text-secondary fs-14 ps-3 mb-0\\\" style=\\\"line-height: 1.8;\\\">\\n        <li><strong class=\\\"text-white\\\">Lifetime Access:<\\/strong> Your account profile, purchased courses, downloadable stem packages, and license records remain accessible indefinitely in your Student Portal.<\\/li>\\n        <li><strong class=\\\"text-white\\\">Profile Rectification:<\\/strong> You can update your password, name, and profile details anytime from your dashboard account settings.<\\/li>\\n        <li><strong class=\\\"text-white\\\">Account Deletion & Data Erasure:<\\/strong> You may request complete account deactivation and removal of non-essential records by contacting our support desk. Essential transaction logs are retained strictly as required by commercial tax and licensing audit laws.<\\/li>\\n    <\\/ul>\\n<\\/div>\\n\\n<div class=\\\"m23-policy-section mb-0\\\">\\n    <div class=\\\"d-flex align-items-center gap-2 mb-2\\\">\\n        <span class=\\\"m23-policy-num\\\">07<\\/span>\\n        <h5 class=\\\"text-white fw-bold mb-0\\\">Contact the Data Protection Desk<\\/h5>\\n    <\\/div>\\n    <p class=\\\"text-secondary fs-14 leading-relaxed mb-3\\\">\\n        If you have questions regarding this Privacy Policy, wish to exercise your data rights, or require assistance with course licensing verification, reach out to our administration team:\\n    <\\/p>\\n    <div class=\\\"p-3 rounded-2\\\" style=\\\"background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.08);\\\">\\n        <div class=\\\"row g-2 align-items-center\\\">\\n            <div class=\\\"col-sm-6\\\">\\n                <span class=\\\"text-muted fs-12 d-block\\\">DIRECT PRIVACY INQUIRIES<\\/span>\\n                <a href=\\\"mailto:support@maarifa23.com\\\" class=\\\"text-warning fw-semibold fs-14 text-decoration-none\\\">\\n                    <i class=\\\"las la-envelope me-1\\\"><\\/i> support@maarifa23.com\\n                <\\/a>\\n            <\\/div>\\n            <div class=\\\"col-sm-6\\\">\\n                <span class=\\\"text-muted fs-12 d-block\\\">ACADEMY HEADQUARTERS<\\/span>\\n                <span class=\\\"text-white fs-13\\\">Maarifa23 Studio Academy &middot; Dar es Salaam, Tanzania<\\/span>\\n            <\\/div>\\n        <\\/div>\\n    <\\/div>\\n<\\/div>\"}',NULL,'basic','privacy-policy','2021-06-09 08:50:42','2026-10-23 22:14:35'),(43,'policy_pages.element','{\"title\":\"Terms of Service\",\"details\":\"<div><h6 class=\\\"mb-1\\\">Terms & Conditions for Users<\\/h6><p>Before getting to this site, you are consenting to be limited by these site Terms and Conditions of Use, every single appropriate law, and guidelines, and concur that you are answerable for consistency with any material neighborhood laws. If you disagree with any of these terms, you are restricted from utilizing or getting to this site.<\\/p><p><br \\/><\\/p><\\/div><div><h6 class=\\\"mb-1\\\">Support<\\/h6><p>Whenever you have downloaded our item, you may get in touch with us for help through email and we will give a valiant effort to determine your issue. We will attempt to answer using the Email for more modest bug fixes, after which we will refresh the center bundle. Content help is offered to confirmed clients by Tickets as it were. Backing demands made by email and Livechat.<\\/p><p>On the off chance that your help requires extra adjustment of the System, at that point, you have two alternatives:<\\/p><ul><li>Hang tight for additional update discharge.<\\/li><li>Or on the other hand, enlist a specialist (We offer customization for extra charges).<\\/li><li><br \\/><\\/li><\\/ul><\\/div><div><h6 class=\\\"mb-1\\\">Ownership<\\/h6><p>You may not guarantee scholarly or selective possession of any of our items, altered or unmodified. All items are property, we created them. Our items are given \\\"with no guarantees\\\" without guarantee of any sort, either communicated or suggested. On no occasion will our juridical individual be subject to any harms including, however not restricted to, immediate, roundabout, extraordinary, accidental, or significant harms or different misfortunes emerging out of the utilization of or powerlessness to utilize our items.<\\/p><p><br \\/><\\/p><\\/div><div><h6 class=\\\"mb-1\\\">Warranty<\\/h6><p>We don\'t offer any guarantee or assurance of these Services in any way. When our Services have been modified we can\'t ensure they will work with all outsider plugins, modules, or internet browsers. Program similarity ought to be tried against the show formats on the demo worker. If you don\'t mind guarantee that the programs you use will work with the component, as we can not ensure that our systems will work with all program mixes.<\\/p><p><br \\/><\\/p><\\/div><div><h6 class=\\\"mb-1\\\">Unauthorized\\/Illegal Usage<\\/h6><p>You may not utilize our things for any illicit or unapproved reason or may you, in the utilization of the stage, disregard any laws in your locale (counting yet not restricted to copyright laws) just as the laws of your nation and International law. Specifically, it is disallowed to utilize the things on our foundation for pages that advance: brutality, illegal intimidation, hard sexual entertainment, bigotry, obscenity content or warez programming joins.<\\/p><p><br \\/><\\/p><\\/div><div><h6 class=\\\"mb-1\\\">Payment\\/Refund Policy<\\/h6><p>No refund or cash back will be made. After a deposit has been finished, it is extremely unlikely to invert it. You should utilize your equilibrium on requests our administrations, Hosting, SEO campaign. You concur that once you complete a deposit, you won\'t document a debate or a chargeback against us in any way, shape, or form.<br \\/><br \\/>If you document a debate or chargeback against us after a deposit, we claim all authority to end every single future request, prohibit you from our site. False action, for example, utilizing unapproved or taken charge cards will prompt the end of your record. There are no special cases.<\\/p><p><br \\/><\\/p><\\/div><div><h6 class=\\\"mb-1\\\">Free Balance \\/ Coupon Policy<\\/h6><p>We offer numerous approaches to get FREE Balance, Coupons and Deposit offers yet we generally reserve the privilege to audit it and deduct it from your record offset with any explanation we may it is a sort of misuse. If we choose to deduct a few or all of free Balance from your record balance, and your record balance becomes negative, at that point the record will naturally be suspended. If your record is suspended because of a negative Balance you can request to make a custom payment to settle your equilibrium to actuate your record.<\\/p><\\/div>\"}',NULL,'basic','terms-of-service','2021-06-09 08:51:18','2024-02-26 16:44:04'),(44,'maintenance.data','{\"description\":\"<div class=\\\"mb-5\\\" style=\\\"color: rgb(111, 111, 111); font-family: Nunito, sans-serif; margin-bottom: 3rem !important;\\\"><h3 class=\\\"mb-3\\\" style=\\\"text-align: center; font-weight: 600; line-height: 1.3; font-size: 24px; font-family: Exo, sans-serif; color: rgb(54, 54, 54);\\\">What information do we collect?<\\/h3><p class=\\\"font-18\\\" style=\\\"text-align: center; margin-right: 0px; margin-left: 0px; font-size: 18px !important;\\\">We gather data from you when you register on our site, submit a request, buy any services, react to an overview, or round out a structure. At the point when requesting any assistance or enrolling on our site, as suitable, you might be approached to enter your: name, email address, or telephone number. You may, nonetheless, visit our site anonymously.<\\/p><\\/div>\",\"image\":\"668a41fac8f7d1720336890.png\"}',NULL,NULL,NULL,'2020-07-04 23:42:52','2024-07-07 01:21:44'),(45,'banner.element','{\"has_image\":\"1\",\"tech_image\":\"6540cb3e696811698745150.png\"}',NULL,'basic',NULL,'2023-10-31 07:09:10','2023-10-31 07:09:10'),(46,'banner.element','{\"has_image\":\"1\",\"tech_image\":\"6540cb4958c281698745161.png\"}',NULL,'basic',NULL,'2023-10-31 07:09:21','2023-10-31 07:09:21'),(47,'banner.element','{\"has_image\":\"1\",\"tech_image\":\"6540cb5370e4c1698745171.png\"}',NULL,'basic',NULL,'2023-10-31 07:09:31','2023-10-31 07:09:31'),(48,'banner.element','{\"has_image\":\"1\",\"tech_image\":\"6540cb5a7a24b1698745178.png\"}',NULL,'basic',NULL,'2023-10-31 07:09:38','2023-10-31 07:09:38'),(49,'banner.element','{\"has_image\":\"1\",\"tech_image\":\"6540cb6317b8b1698745187.png\"}',NULL,'basic',NULL,'2023-10-31 07:09:47','2023-10-31 07:09:47'),(50,'banner.element','{\"has_image\":\"1\",\"tech_image\":\"6540cb6ca1f621698745196.png\"}',NULL,'basic',NULL,'2023-10-31 07:09:56','2023-10-31 07:09:56'),(51,'banner.element','{\"has_image\":\"1\",\"tech_image\":\"6540cb7d6aec01698745213.png\"}',NULL,'basic',NULL,'2023-10-31 07:10:13','2023-10-31 07:10:13'),(52,'banner.element','{\"has_image\":\"1\",\"tech_image\":\"6540cb86351101698745222.png\"}',NULL,'basic',NULL,'2023-10-31 07:10:22','2023-10-31 07:10:22'),(53,'featured_product.content','{\"title\":\"Featured Items\",\"subtitle\":\"Consistently, our staff actually hand-pick probably the best new site topics from our assortment.\",\"has_image\":\"1\",\"top_image\":\"6540d4044808d1698747396.png\",\"bottom_image\":\"6540d404500d11698747396.png\"}',NULL,'basic',NULL,'2023-10-31 07:46:36','2023-12-05 11:26:47'),(54,'best_selling_product.content','{\"title\":\"The world-leading marketplace for code, scripts and plugins\",\"subtitle\":\"Home of the most popular scripts and plugins of all time\"}',NULL,'basic',NULL,'2023-10-31 08:35:15','2023-12-05 11:55:27'),(55,'additional_benefit.content','{\"rotate_text\":\"Best Degital MarketPlace\",\"title\":\"Additional Benefits\",\"subtitle\":\"In addition to the purchase item, all members get exclusive discounts from top digital companies on the market.\",\"has_image\":\"1\",\"image\":\"6540dff2934d51698750450.png\",\"icon_image\":\"6540dff2ca5421698750450.png\"}',NULL,'basic',NULL,'2023-10-31 08:37:30','2024-02-01 11:14:40'),(56,'additional_benefit.element','{\"icon\":\"<i class=\\\"icon-Dimond\\\"><\\/i>\",\"title\":\"Maarifa23 Exclusive\",\"subtitle\":\"Innovate with Maarifa23 Exclusive: Tailored solutions for cutting-edge digital experiences and breakthrough results.\"}',NULL,'basic',NULL,'2023-10-31 08:41:56','2026-10-23 21:52:11'),(57,'additional_benefit.element','{\"icon\":\"<i class=\\\"icon-Support2\\\"><\\/i>\",\"title\":\"Custom Service\",\"subtitle\":\"Experience satisfaction with our Custom Service, prioritizing your requirements for an exceeding seamless experience\"}',NULL,'basic',NULL,'2023-10-31 08:43:09','2024-02-01 09:18:32'),(58,'cta.content','{\"has_image\":\"1\",\"title\":\"Start Earning with Maarifa23\",\"subtitle\":\"Sell your code  & reach millions of buyers or promote other artists on Creative Market tio earn cash!\",\"image\":\"6540e5e2001d71698751970.png\"}',NULL,'basic',NULL,'2023-10-31 09:02:49','2026-10-23 21:52:11'),(60,'latest_product.content','{\"title\":\"Check Out Latest Item\",\"subtitle\":\"We carefully review new entries form our community on by one to make sure they meet hight-quality design and functionality standards.\"}',NULL,'basic',NULL,'2023-10-31 09:04:54','2024-02-01 09:08:56'),(61,'footer.content','{\"short_description\":\"Discover a vibrant online hub, offering a diverse collection of premium scripts & plugins for seamless digital products.\"}',NULL,'basic',NULL,'2023-10-31 09:34:36','2024-02-01 09:23:54'),(62,'social_icon.element','{\"title\":\"Twitter\",\"social_icon\":\"<i class=\\\"fab fa-twitter\\\"><\\/i>\",\"url\":\"https:\\/\\/www.twitter.com\"}',NULL,'basic',NULL,'2023-10-31 09:40:32','2023-12-09 08:55:24'),(63,'social_icon.element','{\"title\":\"Linkedin\",\"social_icon\":\"<i class=\\\"fab fa-linkedin-in\\\"><\\/i>\",\"url\":\"https:\\/\\/www.linkined.com\\/\"}',NULL,'basic',NULL,'2023-10-31 09:41:10','2023-12-20 14:09:52'),(64,'login.content','{\"has_image\":\"1\",\"heading\":\"Welcome back to the world leading marketplace for Digital Item\",\"title\":\"Sign In to Maarifa23\",\"subtitle\":\"Sign in to Maarifa23 and explore our platform\'s digital resources\",\"icon_title\":\"5000+\",\"icon_subtitle\":\"TEMPLATE\",\"image\":\"65c0b70365dff1707128579.png\",\"icon_image\":\"65410aa9ded331698761385.png\"}',NULL,'basic',NULL,'2023-10-31 11:39:45','2026-10-23 21:52:11'),(65,'social_login.content','{\"google_text\":\"Sign In Using Google\",\"google_icon\":\"<i class=\\\"icon-google-1\\\"><\\/i>\",\"facebook_text\":\"Sign In With Facebook asdfasdfasdfasdf\",\"facebook_icon\":\"<i class=\\\"icon-Fackbook\\\"><\\/i>\",\"linkedin_text\":\"Sign In With Linkined\",\"linkedin_icon\":\"<i class=\\\"icon-LinkedIn\\\"><\\/i>\"}',NULL,'basic',NULL,'2023-11-01 07:11:38','2023-12-11 10:13:48'),(67,'weekly_selling_product.content','{\"title\":\"Weekly Best Selling Item\"}',NULL,'basic',NULL,'2023-12-05 12:41:22','2024-02-01 09:11:06'),(68,'register.content','{\"has_image\":\"1\",\"heading\":\"Get started with world leading marketplace for digital item\",\"title\":\"Sign Up to Maarifa23\",\"subtitle\":\"Sign up Maarifa23 & unlock a world of opportunities!\",\"icon_title\":\"10,000+\",\"icon_subtitle\":\"CODE\",\"image\":\"65c0b6e1c69d91707128545.png\",\"icon_image\":\"657426afe96841702110895.png\"}',NULL,'basic',NULL,'2023-12-09 08:34:12','2026-10-23 21:52:11'),(71,'marketplace.content','{\"title\":\"The World-Leading Marketplace\"}',NULL,'basic',NULL,'2023-12-16 10:50:11','2024-02-01 09:10:18'),(72,'become_author.content','{\"heading\":\"Do you want to sale items?\",\"details\":\"If you have unique products or services to offer, join our community of sellers and showcase your offerings to a wide audience. Enjoy the benefits of reaching potential customers and growing your business with us.\",\"button_text\":\"Become an author\"}',NULL,'basic',NULL,'2023-12-18 11:45:30','2024-01-23 06:48:26'),(73,'upload_term.content','{\"details\":\"Any picture, sounds, video, code, blaze, or resources that are not my own work, have been properly authorized for use in the record see or primary download. Other than these things, this work is completely my own and I have full right to sell it on Maarifa23.\"}',NULL,'basic',NULL,'2023-12-19 05:54:04','2026-10-23 21:52:11'),(74,'collection_definition.content','{\"heading\":\"What is a Collection?\",\"details\":\"Collections are groups of items compiled by different users on a theme.  They can be set to Private for personal use, or Public so that they appear on this page and on a user\'s homepage.\"}',NULL,'basic',NULL,'2023-12-23 11:15:34','2024-02-01 13:21:17'),(75,'banned.content','{\"has_image\":\"1\",\"heading\":\"Your account is currently suspended.\",\"image\":\"65ae66cb5aa2f1705928395.png\"}',NULL,'basic',NULL,'2024-01-10 06:01:51','2024-01-22 12:59:55'),(76,'faq.content','{\"heading\":\"Commonly Asked Question\",\"subheading\":\"Find answers to commonly asked questions right here for quick solutions\",\"has_image\":\"1\",\"image\":\"faq_theme.svg\"}',NULL,'basic',NULL,'2024-01-30 11:44:32','2026-10-21 04:25:48'),(77,'faq.element','{\"question\":\"How do I purchase a digital product on your marketplace?\",\"answer\":\"Simply browse our marketplace, select the desired product, and click on the \\\"Buy Now\\\" or \\\"Add to Cart\\\" button. Follow the checkout process to complete your purchase.\"}',NULL,'basic',NULL,'2024-01-30 11:46:44','2024-01-30 11:46:44'),(78,'faq.element','{\"question\":\"What payment methods are accepted?\",\"answer\":\"We accept various payment methods, including credit\\/debit cards, PayPal, and other secure payment gateways. Choose the option that suits you best during checkout.\"}',NULL,'basic',NULL,'2024-01-30 11:47:19','2024-02-01 15:15:23'),(79,'faq.element','{\"question\":\"How do I access and download my purchased digital products?\",\"answer\":\"After completing your purchase, you\'ll receive an email with instructions on how to access and download your digital product. Additionally, you can find a download link in your account under the \\\"Purchases Item\\\" menu on your dashboard.\"}',NULL,'basic',NULL,'2024-01-30 11:48:05','2024-01-30 11:48:05'),(80,'faq.element','{\"question\":\"Can I get a refund for a digital product?\",\"answer\":\"Due to the nature of digital products, refunds are generally not provided. However, if you encounter any issues or have concerns, please contact our support team for assistance.\"}',NULL,'basic',NULL,'2024-01-30 11:48:28','2024-01-30 11:48:28'),(81,'faq.element','{\"question\":\"Are the digital products on your marketplace safe and virus-free?\",\"answer\":\"Yes, we prioritize the safety and security of our users. All products undergo thorough checks, and sellers are vetted. We recommend using reputable antivirus software for an added layer of protection.\"}',NULL,'basic',NULL,'2024-01-30 11:48:52','2024-01-30 11:48:52'),(82,'policy_pages.element','{\"title\":\"Refund Policy\",\"details\":\"<div>At <b>Maarifa23<\\/b>, we strive to provide our customers with high-quality products and exceptional customer service. We understand that sometimes a purchase may not meet your expectations, and we want to ensure your satisfaction. Please review our refund policy below:<\\/div><div><br \\/><\\/div><h6>30-Day Money Back Guarantee:<\\/h6><div>We offer a 30-day money-back guarantee on all purchases made through <b>Maarifa23<\\/b>. If you are not satisfied with your purchase for any reason, you may request a refund within 30 days of the purchase date. No questions asked.<\\/div><div><br \\/><\\/div><h6>Eligibility for Refund:<\\/h6><div>To be eligible for a refund, the following conditions must be met:<\\/div><div><br \\/><\\/div><div>1.\\u00a0 Your request for a refund is made within 30 days of the purchase date.<\\/div><div>2.\\u00a0 The product is not used, damaged, or altered in any way.<\\/div><div>3.\\u00a0 You have a valid reason for requesting a refund, such as technical issues with the product that cannot be resolved by our support team.<\\/div><div><br \\/><\\/div><h6>How to Request a Refund:<\\/h6><div>To request a refund, please contact our customer support team at support@Maarifa23.com with your order details and the reason for your refund request. Our team will review your request and process your refund within 10 business days.<\\/div><div><br \\/><\\/div><h6>Refund Process:<\\/h6><div>Once your refund request is approved, the refund will be issued to the original method of payment used for the purchase. Please note that it may take 10 days for the refund to appear in your account, depending on your payment provider.<\\/div><div><br \\/><\\/div><h6>Exceptions:<\\/h6><div>Please note that certain products may not be eligible for refunds, such as:<\\/div><div><br \\/><\\/div><div>1. Products purchased with promotional discounts or during special sale events.<\\/div><div>2. Products that have been customized or personalized according to your specifications.<\\/div><div>3. Services provided by third-party vendors through <b>Maarifa23<\\/b>.<\\/div><div><br \\/><\\/div><h6>Contact Us:<\\/h6><div>If you have any questions or concerns about our refund policy, please feel free to contact us at support@Maarifa23.com. Our customer support team is available to assist you with any inquiries you may have.<\\/div><div><br \\/><\\/div><h6>Changes to Refund Policy:<\\/h6><div>We reserve the right to update or change our refund policy at any time without prior notice. Any changes to the policy will be effective immediately upon posting on this page.<\\/div><div><br \\/><\\/div>\"}',NULL,'basic','refund-policy','2024-01-30 15:30:59','2026-10-23 21:52:11'),(83,'ban.content','{\"title\":\"Oops !\",\"heading\":\"Your account is currently suspended.\",\"subheading\":\"Your account is temporarily suspended. If you have any concerns, please reach out to our support team for prompt assistance & resolution.\",\"has_image\":\"1\",\"image\":\"65bb5c3239f501706777650.png\"}',NULL,'basic',NULL,'2024-02-01 10:48:44','2024-02-01 10:54:10'),(85,'social_icon.element','{\"title\":\"instagram\",\"social_icon\":\"<i class=\\\"fab fa-instagram\\\"><\\/i>\",\"url\":\"https:\\/\\/www.instagram.com\\/\"}',NULL,'basic',NULL,'2024-02-26 16:40:39','2024-02-26 16:42:46'),(86,'register_disable.content','{\"has_image\":\"1\",\"heading\":\"Registration Currently Disabled\",\"subheading\":\"Registration unavailable: Please check back later for updates. Thank you for your patience.\",\"button_name\":\"Go Home\",\"button_url\":\"\\/\",\"image\":\"668295a5ebc4a1719834021.png\"}',NULL,'basic','','2024-06-30 23:40:21','2024-06-30 23:40:22'),(87,'kyc.content','{\"required\":\"Please submit the required KYC information to verify yourself. Otherwise, you couldn\'t make any withdrawal requests to the system.\",\"pending\":\"Your submitted KYC information is pending for admin approval. Please wait till that.\",\"reject\":\"Your KYC document has been rejected. Please resubmit the document for further review.\"}',NULL,'basic','','2024-07-02 21:06:13','2024-07-02 21:06:13'),(88,'featured_author.content','{\"has_image\":\"1\",\"top_image\":\"679f3addc07941738488541.png\",\"bottom_image\":\"679f3addcee211738488541.png\"}',NULL,'basic','','2023-10-31 03:04:21','2025-02-01 21:29:01'),(89,'free_product.content','{\"title\":\"Download free items\"}',NULL,'basic','','2025-02-11 02:52:27','2025-02-11 02:52:27'),(90,'contact_us.element','{\"heading\":\"WhatsApp Live Chat\",\"subheading\":\"Instant chat for courses & downloads\",\"contact_address\":\"+255 755 000 000\",\"action_url\":\"https:\\/\\/wa.me\\/255755000000?text=Hello%20AK23%20Academy%20Support\",\"icon\":\"<i class=\\\"lab la-whatsapp\\\"><\\/i>\"}',NULL,'basic',NULL,'2026-10-21 04:04:29','2026-10-21 04:12:58'),(91,'contact_us.element','{\"heading\":\"Support Email\",\"subheading\":\"Inquiries, licenses & order help\",\"contact_address\":\"support@ak23academy.com\",\"action_url\":\"mailto:support@ak23academy.com\",\"icon\":\"<i class=\\\"las la-envelope\\\"><\\/i>\"}',NULL,'basic',NULL,'2026-10-21 04:04:29','2026-10-21 04:12:58'),(92,'contact_us.element','{\"heading\":\"Studio Phone\",\"subheading\":\"Mon - Sat, 9:00 AM - 8:00 PM EAT\",\"contact_address\":\"+255 755 000 000\",\"action_url\":\"tel:+255755000000\",\"icon\":\"<i class=\\\"las la-phone\\\"><\\/i>\"}',NULL,'basic',NULL,'2026-10-21 04:04:29','2026-10-21 04:12:58'),(93,'contact_us.element','{\"heading\":\"Academy Studio Location\",\"subheading\":\"AK23 Media & Vocal Production Lab\",\"contact_address\":\"Dar es Salaam, Tanzania\",\"action_url\":\"#\",\"icon\":\"<i class=\\\"las la-map-marker-alt\\\"><\\/i>\"}',NULL,'basic',NULL,'2026-10-21 04:04:29','2026-10-21 04:12:58');
/*!40000 ALTER TABLE `frontends` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gateway_currencies`
--

DROP TABLE IF EXISTS `gateway_currencies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gateway_currencies` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `symbol` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `method_code` int DEFAULT NULL,
  `gateway_alias` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `min_amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `max_amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `percent_charge` decimal(5,2) NOT NULL DEFAULT '0.00',
  `fixed_charge` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `rate` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `gateway_parameter` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gateway_currencies`
--

LOCK TABLES `gateway_currencies` WRITE;
/*!40000 ALTER TABLE `gateway_currencies` DISABLE KEYS */;
INSERT INTO `gateway_currencies` VALUES (3,'Selcom Mobile Money','TZS','TZS',801,'selcom',100.00000000,50000000.00000000,0.00,0.00000000,1.00000000,'{\"vendor\":\"SW00170027\",\"api_key\":\"TILL61237100-62ceeea68ed6426a83e640c5950d117d\",\"api_secret\":\"469b1f-f26748-4f6ca1-18b224-b4bde4-1e\",\"base_url\":\"https:\\/\\/apigw.selcommobile.com\\/v1\",\"webhook_url\":\"https:\\/\\/www.m23.ak23studiokits.com\\/webhook_selcom.php\"}','2026-10-21 08:28:59','2026-10-21 08:28:59');
/*!40000 ALTER TABLE `gateway_currencies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gateways`
--

DROP TABLE IF EXISTS `gateways`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gateways` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `form_id` int unsigned NOT NULL DEFAULT '0',
  `code` int DEFAULT NULL,
  `name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alias` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'NULL',
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=>enable, 2=>disable',
  `gateway_parameters` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `supported_currencies` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `crypto` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: fiat currency, 1: crypto currency',
  `extra` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gateways`
--

LOCK TABLES `gateways` WRITE;
/*!40000 ALTER TABLE `gateways` DISABLE KEYS */;
INSERT INTO `gateways` VALUES (11,0,111,'Stripe (Storefront Elements)','StripeJs','663a3995417171715091861.png',1,'{\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"sk_test_SAMPLE_SECRET_KEY_PLACEHOLDER\"},\"publishable_key\":{\"title\":\"PUBLISHABLE KEY\",\"global\":true,\"value\":\"pk_test_SAMPLE_PUB_KEY_PLACEHOLDER\"}}','{\"USD\":\"USD\",\"AUD\":\"AUD\",\"BRL\":\"BRL\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"HKD\":\"HKD\",\"INR\":\"INR\",\"JPY\":\"JPY\",\"MXN\":\"MXN\",\"MYR\":\"MYR\",\"NOK\":\"NOK\",\"NZD\":\"NZD\",\"PLN\":\"PLN\",\"SEK\":\"SEK\",\"SGD\":\"SGD\"}',0,NULL,NULL,'2019-09-14 13:14:22','2026-10-20 10:38:44'),(15,0,503,'CoinPayments','Coinpayments','663a36a8d8e1d1715091112.png',0,'{\"public_key\":{\"title\":\"Public Key\",\"global\":true,\"value\":\"---------------------\"},\"private_key\":{\"title\":\"Private Key\",\"global\":true,\"value\":\"---------------------\"},\"merchant_id\":{\"title\":\"Merchant ID\",\"global\":true,\"value\":\"---------------------\"}}','{\"BTC\":\"Bitcoin\",\"BTC.LN\":\"Bitcoin (Lightning Network)\",\"LTC\":\"Litecoin\",\"CPS\":\"CPS Coin\",\"VLX\":\"Velas\",\"APL\":\"Apollo\",\"AYA\":\"Aryacoin\",\"BAD\":\"Badcoin\",\"BCD\":\"Bitcoin Diamond\",\"BCH\":\"Bitcoin Cash\",\"BCN\":\"Bytecoin\",\"BEAM\":\"BEAM\",\"BITB\":\"Bean Cash\",\"BLK\":\"BlackCoin\",\"BSV\":\"Bitcoin SV\",\"BTAD\":\"Bitcoin Adult\",\"BTG\":\"Bitcoin Gold\",\"BTT\":\"BitTorrent\",\"CLOAK\":\"CloakCoin\",\"CLUB\":\"ClubCoin\",\"CRW\":\"Crown\",\"CRYP\":\"CrypticCoin\",\"CRYT\":\"CryTrExCoin\",\"CURE\":\"CureCoin\",\"DASH\":\"DASH\",\"DCR\":\"Decred\",\"DEV\":\"DeviantCoin\",\"DGB\":\"DigiByte\",\"DOGE\":\"Dogecoin\",\"EBST\":\"eBoost\",\"EOS\":\"EOS\",\"ETC\":\"Ether Classic\",\"ETH\":\"Ethereum\",\"ETN\":\"Electroneum\",\"EUNO\":\"EUNO\",\"EXP\":\"EXP\",\"Expanse\":\"Expanse\",\"FLASH\":\"FLASH\",\"GAME\":\"GameCredits\",\"GLC\":\"Goldcoin\",\"GRS\":\"Groestlcoin\",\"KMD\":\"Komodo\",\"LOKI\":\"LOKI\",\"LSK\":\"LSK\",\"MAID\":\"MaidSafeCoin\",\"MUE\":\"MonetaryUnit\",\"NAV\":\"NAV Coin\",\"NEO\":\"NEO\",\"NMC\":\"Namecoin\",\"NVST\":\"NVO Token\",\"NXT\":\"NXT\",\"OMNI\":\"OMNI\",\"PINK\":\"PinkCoin\",\"PIVX\":\"PIVX\",\"POT\":\"PotCoin\",\"PPC\":\"Peercoin\",\"PROC\":\"ProCurrency\",\"PURA\":\"PURA\",\"QTUM\":\"QTUM\",\"RES\":\"Resistance\",\"RVN\":\"Ravencoin\",\"RVR\":\"RevolutionVR\",\"SBD\":\"Steem Dollars\",\"SMART\":\"SmartCash\",\"SOXAX\":\"SOXAX\",\"STEEM\":\"STEEM\",\"STRAT\":\"STRAT\",\"SYS\":\"Syscoin\",\"TPAY\":\"TokenPay\",\"TRIGGERS\":\"Triggers\",\"TRX\":\" TRON\",\"UBQ\":\"Ubiq\",\"UNIT\":\"UniversalCurrency\",\"USDT\":\"Tether USD (Omni Layer)\",\"USDT.BEP20\":\"Tether USD (BSC Chain)\",\"USDT.ERC20\":\"Tether USD (ERC20)\",\"USDT.TRC20\":\"Tether USD (Tron/TRC20)\",\"VTC\":\"Vertcoin\",\"WAVES\":\"Waves\",\"XCP\":\"Counterparty\",\"XEM\":\"NEM\",\"XMR\":\"Monero\",\"XSN\":\"Stakenet\",\"XSR\":\"SucreCoin\",\"XVG\":\"VERGE\",\"XZC\":\"ZCoin\",\"ZEC\":\"ZCash\",\"ZEN\":\"Horizen\"}',1,NULL,NULL,'2019-09-14 13:14:22','2026-10-20 10:38:59'),(24,0,113,'PayPal','PaypalSdk','663a38ed101a61715091693.png',0,'{\"clientId\":{\"title\":\"Paypal Client ID\",\"global\":true,\"value\":\"PAYPAL_SAMPLE_CLIENT_ID_PLACEHOLDER\"},\"clientSecret\":{\"title\":\"Client Secret\",\"global\":true,\"value\":\"PAYPAL_SAMPLE_SECRET_PLACEHOLDER\"}}','{\"AUD\":\"AUD\",\"BRL\":\"BRL\",\"CAD\":\"CAD\",\"CZK\":\"CZK\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"HKD\":\"HKD\",\"HUF\":\"HUF\",\"INR\":\"INR\",\"ILS\":\"ILS\",\"JPY\":\"JPY\",\"MYR\":\"MYR\",\"MXN\":\"MXN\",\"TWD\":\"TWD\",\"NZD\":\"NZD\",\"NOK\":\"NOK\",\"PHP\":\"PHP\",\"PLN\":\"PLN\",\"GBP\":\"GBP\",\"RUB\":\"RUB\",\"SGD\":\"SGD\",\"SEK\":\"SEK\",\"CHF\":\"CHF\",\"THB\":\"THB\",\"USD\":\"$\"}',0,NULL,NULL,'2019-09-14 13:14:22','2026-10-20 10:39:04'),(25,0,114,'Stripe','StripeV3','663a39afb519f1715091887.png',1,'{\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"sk_test_SAMPLE_SECRET_KEY_PLACEHOLDER\"},\"publishable_key\":{\"title\":\"PUBLISHABLE KEY\",\"global\":true,\"value\":\"pk_test_SAMPLE_PUB_KEY_PLACEHOLDER\"},\"end_point\":{\"title\":\"End Point Secret\",\"global\":true,\"value\":\"whsec_SAMPLE_SECRET_KEY_PLACEHOLDER\"}}','{\"USD\":\"USD\",\"AUD\":\"AUD\",\"BRL\":\"BRL\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"HKD\":\"HKD\",\"INR\":\"INR\",\"JPY\":\"JPY\",\"MXN\":\"MXN\",\"MYR\":\"MYR\",\"NOK\":\"NOK\",\"NZD\":\"NZD\",\"PLN\":\"PLN\",\"SEK\":\"SEK\",\"SGD\":\"SGD\"}',0,'{\"webhook\":{\"title\": \"Webhook Endpoint\",\"value\":\"ipn.StripeV3\"}}',NULL,'2019-09-14 13:14:22','2026-10-20 10:35:58'),(63,0,801,'Selcom Mobile Money','selcom',NULL,1,'{\"vendor\":{\"title\":\"Vendor ID \\/ Till\",\"global\":true,\"value\":\"SW00170027\"},\"api_key\":{\"title\":\"API Key\",\"global\":true,\"value\":\"TILL61237100-62ceeea68ed6426a83e640c5950d117d\"},\"api_secret\":{\"title\":\"API Secret\",\"global\":true,\"value\":\"469b1f-f26748-4f6ca1-18b224-b4bde4-1e\"},\"base_url\":{\"title\":\"Base URL\",\"global\":true,\"value\":\"https:\\/\\/apigw.selcommobile.com\\/v1\"},\"webhook_url\":{\"title\":\"Webhook URL\",\"global\":true,\"value\":\"https:\\/\\/www.m23.ak23studiokits.com\\/webhook_selcom.php\"}}','\"{\\\"TZS\\\":\\\"TZS\\\",\\\"USD\\\":\\\"USD\\\"}\"',0,NULL,'Selcom Mobile Money Checkout & USSD Push (M-Pesa, Mixx, Airtel Money, HaloPesa)','2026-10-20 04:33:48','2026-10-21 08:28:59');
/*!40000 ALTER TABLE `gateways` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `general_settings`
--

DROP TABLE IF EXISTS `general_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `general_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `site_name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cur_text` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'currency text',
  `cur_sym` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'currency symbol',
  `email_from` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_from_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_template` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `sms_template` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sms_from` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `push_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `push_template` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `base_color` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `secondary_color` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail_config` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'email configuration',
  `sms_config` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `firebase_config` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `global_shortcodes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `socialite_credentials` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `kv` tinyint(1) NOT NULL DEFAULT '0',
  `ev` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'email verification, 0 - dont check, 1 - check',
  `en` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'email notification, 0 - dont send, 1 - send',
  `sv` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'mobile verication, 0 - dont check, 1 - check',
  `sn` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'sms notification, 0 - dont send, 1 - send',
  `pn` tinyint(1) NOT NULL DEFAULT '1',
  `force_ssl` tinyint(1) NOT NULL DEFAULT '0',
  `maintenance_mode` tinyint(1) NOT NULL DEFAULT '0',
  `secure_password` tinyint(1) NOT NULL DEFAULT '0',
  `agree` tinyint(1) NOT NULL DEFAULT '0',
  `multi_language` tinyint(1) NOT NULL DEFAULT '1',
  `registration` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: Off	, 1: On',
  `active_template` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `system_version` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '12.0',
  `last_migration_at` timestamp NULL DEFAULT NULL,
  `migration_batch` int NOT NULL DEFAULT '13',
  `api_rate_limit` int NOT NULL DEFAULT '60',
  `rpc_endpoint_enabled` tinyint NOT NULL DEFAULT '1',
  `available_version` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_cron` datetime DEFAULT NULL,
  `system_customized` tinyint(1) NOT NULL DEFAULT '0',
  `paginate_number` int NOT NULL DEFAULT '0',
  `currency_format` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1=>Both\r\n2=>Text Only\r\n3=>Symbol Only',
  `config_progress` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `personal_license_features` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `commercial_license_features` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `file_server` tinyint(1) DEFAULT '1' COMMENT '1 = current, 2 = ftp, 3 = wasabi, 4 = digital_ocean',
  `ftp` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `wasabi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `digital_ocean` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `backblaze` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `referral_fixed` decimal(28,8) unsigned NOT NULL DEFAULT '0.00000000',
  `referral_percentage` decimal(5,2) unsigned NOT NULL DEFAULT '0.00',
  `referral` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1=enable,0=disable',
  `changelog` tinyint(1) DEFAULT '0',
  `free_item` tinyint(1) DEFAULT '0',
  `min_reviews` int DEFAULT '0',
  `trending_count` int DEFAULT '0',
  `advertisement` tinyint(1) NOT NULL DEFAULT '0',
  `comment_disable` tinyint(1) DEFAULT '0',
  `preview_video_size` int NOT NULL DEFAULT '100',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `general_settings`
--

LOCK TABLES `general_settings` WRITE;
/*!40000 ALTER TABLE `general_settings` DISABLE KEYS */;
INSERT INTO `general_settings` VALUES (1,'Maarifa23','TSH','TSH ','support@maarifa23.com','Maarifa23 Academy','<html>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\r\n<meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">\r\n<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\r\n<title>\r\n</title>\r\n<style type=\"text/css\">\r\n	.ReadMsgBody {\r\n		width: 100%;\r\n		background-color: #ffffff;\r\n	}\r\n	.ExternalClass {\r\n		width: 100%;\r\n		background-color: #ffffff;\r\n	}\r\n	.ExternalClass,\r\n	.ExternalClass p,\r\n	.ExternalClass span,\r\n	.ExternalClass font,\r\n	.ExternalClass td,\r\n	.ExternalClass div {\r\n		line-height: 100%;\r\n	}\r\n	html {\r\n		width: 100%;\r\n	}\r\n	body {\r\n		-webkit-text-size-adjust: none;\r\n		-ms-text-size-adjust: none;\r\n		margin: 0;\r\n		padding: 0;\r\n	}\r\n	table {\r\n		border-spacing: 0;\r\n		table-layout: fixed;\r\n		margin: 0 auto;\r\n		border-collapse: collapse;\r\n	}\r\n	table table table {\r\n		table-layout: auto;\r\n	}\r\n	.yshortcuts a {\r\n		border-bottom: none !important;\r\n	}\r\n	img:hover {\r\n		opacity: 0.9 !important;\r\n	}\r\n	a {\r\n		color: #0087ff;\r\n		text-decoration: none;\r\n	}\r\n	.textbutton a {\r\n		font-family: \"open sans\", arial, sans-serif !important;\r\n	}\r\n	.btn-link a {\r\n		color: #ffffff !important;\r\n	}\r\n	@media only screen and (max-width: 480px) {\r\n		body {\r\n			width: auto !important;\r\n		}\r\n		*[class=\"table-inner\"] {\r\n			width: 90% !important;\r\n			text-align: center !important;\r\n		}\r\n		*[class=\"table-full\"] {\r\n			width: 100% !important;\r\n			text-align: center !important;\r\n		} /* image */\r\n		img[class=\"img1\"] {\r\n			width: 100% !important;\r\n			height: auto !important;\r\n		}\r\n	}\r\n\r\n</style>\r\n<table bgcolor=\"#030442\" width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n	<tbody>\r\n		<tr>\r\n			<td height=\"50\">\r\n			</td>\r\n		</tr>\r\n		<tr>\r\n			<td align=\"center\" style=\"text-align:center;vertical-align:top;font-size:0;\">\r\n				<table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n					<tbody>\r\n						<tr>\r\n							<td align=\"center\" width=\"600\">\r\n								<table class=\"table-inner\" width=\"95%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n									<tbody>\r\n										<tr>\r\n											<td bgcolor=\"#0087ff\" style=\"border-top-left-radius:6px; border-top-right-radius:6px;text-align:center;vertical-align:top;font-size:0;\" align=\"center\">\r\n												<table width=\"90%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n													<tbody>\r\n														<tr>\r\n															<td height=\"20\">\r\n															</td>\r\n														</tr>\r\n														<tr>\r\n															<td align=\"center\" style=\"font-family: Open sans, Arial, sans-serif; color:#FFFFFF; font-size:16px; font-weight: bold;\">\r\n															This is a System Generated Email</td>\r\n														</tr>\r\n														<tr>\r\n															<td height=\"20\">\r\n															</td>\r\n														</tr>\r\n													</tbody>\r\n												</table>\r\n											</td>\r\n										</tr>\r\n									</tbody>\r\n								</table>\r\n								<table class=\"table-inner\" width=\"95%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n									<tbody>\r\n										<tr>\r\n											<td bgcolor=\"#FFFFFF\" align=\"center\" style=\"text-align:center;vertical-align:top;font-size:0;\">\r\n												<table align=\"center\" width=\"90%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n													<tbody>\r\n														<tr>\r\n															<td height=\"35\">\r\n															</td>\r\n														</tr>\r\n														<tr>\r\n															<td align=\"center\" style=\"vertical-align:top;font-size:0;\">\r\n																<a href=\"#\">\r\n																	<img style=\"display:block; line-height:0px; font-size:0px; border:0px; width: 240px;\" width=\"240px\" src=\"http://localhost/akcourse/assets/images/logo_icon/logo_dark.png\" alt=\"img\">\r\n																</a>\r\n															</td>\r\n														</tr>\r\n														<tr>\r\n															<td height=\"40\"></td>\r\n														</tr>\r\n														<tr>\r\n															<td align=\"center\" style=\"font-family: Open Sans, Arial, sans-serif; font-size: 22px;color:#414a51;font-weight: bold;\">\r\n															Hello {{fullname}} ({{username}}) </td>\r\n														</tr>\r\n														<tr>\r\n															<td align=\"center\" style=\"text-align:center;vertical-align:top;font-size:0;\">\r\n																<table width=\"40\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n																	<tbody>\r\n																		<tr>\r\n																			<td height=\"20\" style=\" border-bottom:3px solid #0087ff;\">\r\n																			</td>\r\n																		</tr>\r\n																	</tbody>\r\n																</table>\r\n															</td>\r\n														</tr>\r\n														<tr>\r\n															<td height=\"30\"></td>\r\n														</tr>\r\n														<tr>\r\n															<td align=\"left\" style=\"font-family: Open sans, Arial, sans-serif; color:#7f8c8d; font-size:16px; line-height: 28px;\">\r\n															{{message}}</td>\r\n														</tr>\r\n														<tr>\r\n															<td height=\"60\"></td>\r\n														</tr>\r\n													</tbody>\r\n												</table>\r\n											</td>\r\n										</tr>\r\n										<tr>\r\n											<td height=\"45\" align=\"center\" bgcolor=\"#f4f4f4\" style=\"border-bottom-left-radius:6px;border-bottom-right-radius:6px;\">\r\n												<table align=\"center\" width=\"90%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n													<tbody>\r\n														<tr>\r\n															<td height=\"10\"></td>\r\n														</tr>\r\n														<tr>\r\n															<td class=\"preference-link\" align=\"center\" style=\"font-family: Open sans, Arial, sans-serif; color:#95a5a6; font-size:14px;\">\r\n																© 2026 <a href=\"#\">{{site_name}}</a> &nbsp;. All Rights Reserved. </td>\r\n														</tr>\r\n														<tr>\r\n															<td height=\"10\"></td>\r\n														</tr>\r\n													</tbody>\r\n												</table>\r\n											</td>\r\n										</tr>\r\n									</tbody>\r\n								</table>\r\n							</td>\r\n						</tr>\r\n					</tbody>\r\n				</table>\r\n			</td>\r\n		</tr>\r\n		<tr>\r\n			<td height=\"60\"></td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n</html>','hi {{fullname}} ({{username}}), {{message}}','Maarifa23',NULL,NULL,'fbaf43','3a3a3c','{\"name\":\"php\"}','{\"name\":\"nexmo\",\"clickatell\":{\"api_key\":\"----------------\"},\"infobip\":{\"username\":\"------------8888888\",\"password\":\"-----------------\"},\"message_bird\":{\"api_key\":\"-------------------\"},\"nexmo\":{\"api_key\":\"----------------------\",\"api_secret\":\"----------------------\"},\"sms_broadcast\":{\"username\":\"----------------------\",\"password\":\"-----------------------------\"},\"twilio\":{\"account_sid\":\"-----------------------\",\"auth_token\":\"---------------------------\",\"from\":\"----------------------\"},\"text_magic\":{\"username\":\"-----------------------\",\"apiv2_key\":\"-------------------------------\"},\"custom\":{\"method\":\"get\",\"url\":\"https:\\/\\/hostname\\/demo-api-v1\",\"headers\":{\"name\":[\"api_key\"],\"value\":[\"test_api 555\"]},\"body\":{\"name\":[\"from_number\"],\"value\":[\"5657545757\"]}}}','{\"apiKey\":\"----------------\",\"authDomain\":\"------------------\",\"projectId\":\"-------------------\",\"storageBucket\":\"-----------------\",\"messagingSenderId\":\"------------------\",\"appId\":\"------------------\",\"measurementId\":\"---------------\"}','{\n    \"site_name\":\"Name of your site\",\n    \"site_currency\":\"Currency of your site\",\n    \"currency_symbol\":\"Symbol of currency\"\n}','{\"google\":{\"client_id\":\"-----------------------\",\"client_secret\":\"--------------------\",\"status\":1},\"facebook\":{\"client_id\":\"-------------------------\",\"client_secret\":\"-----------------------\",\"status\":0},\"linkedin\":{\"client_id\":\"----------------------\",\"client_secret\":\"----------------------\",\"status\":0}}',0,0,1,0,0,0,0,0,0,1,1,1,'basic','13.0','2026-10-21 18:02:06',13,60,1,'13.0','2026-10-23 23:29:34',0,20,1,'[\"general_setting\",\"logo_favicon\",\"notification_template\",\"deposit_method\",\"seo\",\"policy_content\",\"withdrawal_method\"]','[\"One-Time Lifetime License\",\"Full Course Access\",\"Digital Assets & Project Files\",\"Lifetime Updates\"]','[\"One-Time Lifetime License\",\"Full Course Access\",\"Digital Assets & Project Files\",\"Lifetime Updates\"]',1,'{\"domain\":\"-----------------\",\"host\":\"----------------------\",\"username\":\"---------------------\",\"password\":\"------------------\",\"port\":\"----------------------\",\"root\":\"--------------------\"}','{\"driver\":\"---------------------\",\"key\":\"------------------------\",\"secret\":\"---------------------\",\"region\":\"---------------------\",\"bucket\":\"----------------------\",\"endpoint\":\"----------------------\"}','{\"driver\":\"---------------------\",\"key\":\"---------------------\",\"secret\":\"---------------------\",\"region\":\"---------------------\",\"bucket\":\"---------------------\",\"endpoint\":\"---------------------\"}','{\"driver\":\"---------------------\",\"key\":\"---------------------\",\"secret\":\"---------------------\",\"region\":\"---------------------\",\"bucket\":\"---------------------\",\"endpoint\":\"---------------------\"}',2.00000000,3.00,0,0,0,1,100,0,0,100,NULL,'2026-10-23 22:06:46');
/*!40000 ALTER TABLE `general_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `languages`
--

DROP TABLE IF EXISTS `languages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `languages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: not default language, 1: default language',
  `image` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `languages`
--

LOCK TABLES `languages` WRITE;
/*!40000 ALTER TABLE `languages` DISABLE KEYS */;
INSERT INTO `languages` VALUES (1,'English','en',1,'67aafe281263e1739259432.png','2020-07-06 03:47:55','2025-02-11 01:37:12');
/*!40000 ALTER TABLE `languages` ENABLE KEYS */;
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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2026_10_24_030000_add_license_key_to_order_items',1),(2,'2026_10_24_043000_add_performance_optimization_indexes',2);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notification_logs`
--

DROP TABLE IF EXISTS `notification_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notification_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL DEFAULT '0',
  `sender` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sent_from` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sent_to` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `notification_type` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_read` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notification_logs`
--

LOCK TABLES `notification_logs` WRITE;
/*!40000 ALTER TABLE `notification_logs` DISABLE KEYS */;
INSERT INTO `notification_logs` VALUES (1,9,'php','support@ak23academy.com','test@example.com','Deposit Completed Successfully','<html>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\r\n<meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">\r\n<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\r\n<title>\r\n</title>\r\n<style type=\"text/css\">\r\n	.ReadMsgBody {\r\n		width: 100%;\r\n		background-color: #ffffff;\r\n	}\r\n	.ExternalClass {\r\n		width: 100%;\r\n		background-color: #ffffff;\r\n	}\r\n	.ExternalClass,\r\n	.ExternalClass p,\r\n	.ExternalClass span,\r\n	.ExternalClass font,\r\n	.ExternalClass td,\r\n	.ExternalClass div {\r\n		line-height: 100%;\r\n	}\r\n	html {\r\n		width: 100%;\r\n	}\r\n	body {\r\n		-webkit-text-size-adjust: none;\r\n		-ms-text-size-adjust: none;\r\n		margin: 0;\r\n		padding: 0;\r\n	}\r\n	table {\r\n		border-spacing: 0;\r\n		table-layout: fixed;\r\n		margin: 0 auto;\r\n		border-collapse: collapse;\r\n	}\r\n	table table table {\r\n		table-layout: auto;\r\n	}\r\n	.yshortcuts a {\r\n		border-bottom: none !important;\r\n	}\r\n	img:hover {\r\n		opacity: 0.9 !important;\r\n	}\r\n	a {\r\n		color: #0087ff;\r\n		text-decoration: none;\r\n	}\r\n	.textbutton a {\r\n		font-family: \"open sans\", arial, sans-serif !important;\r\n	}\r\n	.btn-link a {\r\n		color: #ffffff !important;\r\n	}\r\n	@media only screen and (max-width: 480px) {\r\n		body {\r\n			width: auto !important;\r\n		}\r\n		*[class=\"table-inner\"] {\r\n			width: 90% !important;\r\n			text-align: center !important;\r\n		}\r\n		*[class=\"table-full\"] {\r\n			width: 100% !important;\r\n			text-align: center !important;\r\n		} /* image */\r\n		img[class=\"img1\"] {\r\n			width: 100% !important;\r\n			height: auto !important;\r\n		}\r\n	}\r\n\r\n</style>\r\n<table bgcolor=\"#030442\" width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n	<tbody>\r\n		<tr>\r\n			<td height=\"50\">\r\n			</td>\r\n		</tr>\r\n		<tr>\r\n			<td align=\"center\" style=\"text-align:center;vertical-align:top;font-size:0;\">\r\n				<table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n					<tbody>\r\n						<tr>\r\n							<td align=\"center\" width=\"600\">\r\n								<table class=\"table-inner\" width=\"95%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n									<tbody>\r\n										<tr>\r\n											<td bgcolor=\"#0087ff\" style=\"border-top-left-radius:6px; border-top-right-radius:6px;text-align:center;vertical-align:top;font-size:0;\" align=\"center\">\r\n												<table width=\"90%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n													<tbody>\r\n														<tr>\r\n															<td height=\"20\">\r\n															</td>\r\n														</tr>\r\n														<tr>\r\n															<td align=\"center\" style=\"font-family: Open sans, Arial, sans-serif; color:#FFFFFF; font-size:16px; font-weight: bold;\">\r\n															This is a System Generated Email</td>\r\n														</tr>\r\n														<tr>\r\n															<td height=\"20\">\r\n															</td>\r\n														</tr>\r\n													</tbody>\r\n												</table>\r\n											</td>\r\n										</tr>\r\n									</tbody>\r\n								</table>\r\n								<table class=\"table-inner\" width=\"95%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n									<tbody>\r\n										<tr>\r\n											<td bgcolor=\"#FFFFFF\" align=\"center\" style=\"text-align:center;vertical-align:top;font-size:0;\">\r\n												<table align=\"center\" width=\"90%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n													<tbody>\r\n														<tr>\r\n															<td height=\"35\">\r\n															</td>\r\n														</tr>\r\n														<tr>\r\n															<td align=\"center\" style=\"vertical-align:top;font-size:0;\">\r\n																<a href=\"#\">\r\n																	<img style=\"display:block; line-height:0px; font-size:0px; border:0px; width: 240px;\" width=\"240px\" src=\"https://viserlab.com/assets/images/logoIcon/logo-dark.png\" alt=\"img\">\r\n																</a>\r\n															</td>\r\n														</tr>\r\n														<tr>\r\n															<td height=\"40\"></td>\r\n														</tr>\r\n														<tr>\r\n															<td align=\"center\" style=\"font-family: Open Sans, Arial, sans-serif; font-size: 22px;color:#414a51;font-weight: bold;\">\r\n															Hello Student User (test748) </td>\r\n														</tr>\r\n														<tr>\r\n															<td align=\"center\" style=\"text-align:center;vertical-align:top;font-size:0;\">\r\n																<table width=\"40\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n																	<tbody>\r\n																		<tr>\r\n																			<td height=\"20\" style=\" border-bottom:3px solid #0087ff;\">\r\n																			</td>\r\n																		</tr>\r\n																	</tbody>\r\n																</table>\r\n															</td>\r\n														</tr>\r\n														<tr>\r\n															<td height=\"30\"></td>\r\n														</tr>\r\n														<tr>\r\n															<td align=\"left\" style=\"font-family: Open sans, Arial, sans-serif; color:#7f8c8d; font-size:16px; line-height: 28px;\">\r\n															<div>Your deposit of&nbsp;<span style=\"font-weight: bolder;\">120,000.00 TSH</span>&nbsp;is via&nbsp;&nbsp;<span style=\"font-weight: bolder;\">&nbsp;</span>has been completed Successfully.<span style=\"font-weight: bolder;\"><br></span></div><div><span style=\"font-weight: bolder;\"><br></span></div><div><span style=\"font-weight: bolder;\">Details of your Deposit :<br></span></div><div><br></div><div>Amount : 120,000.00 TSH</div><div>Charge:&nbsp;<font color=\"#000000\">0.00 TSH</font></div><div><br></div><div>Conversion Rate : 1 TSH = 1.00 TZS</div><div>Received : 120,000.00 TZS<br></div><div>Paid via :&nbsp; </div><div><br></div><div>Transaction Number : AK-HSDXVYVO</div><div><font size=\"5\"><span style=\"font-weight: bolder;\"><br></span></font></div><div><font size=\"5\">Your current Balance is&nbsp;<span style=\"font-weight: bolder;\">TSH 0.00 TSH TSH</span></font></div><div><br style=\"font-family: Montserrat, sans-serif;\"></div></td>\r\n														</tr>\r\n														<tr>\r\n															<td height=\"60\"></td>\r\n														</tr>\r\n													</tbody>\r\n												</table>\r\n											</td>\r\n										</tr>\r\n										<tr>\r\n											<td height=\"45\" align=\"center\" bgcolor=\"#f4f4f4\" style=\"border-bottom-left-radius:6px;border-bottom-right-radius:6px;\">\r\n												<table align=\"center\" width=\"90%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n													<tbody>\r\n														<tr>\r\n															<td height=\"10\"></td>\r\n														</tr>\r\n														<tr>\r\n															<td class=\"preference-link\" align=\"center\" style=\"font-family: Open sans, Arial, sans-serif; color:#95a5a6; font-size:14px;\">\r\n																© 2024 <a href=\"#\">Maarifa23</a> &nbsp;. All Rights Reserved. </td>\r\n														</tr>\r\n														<tr>\r\n															<td height=\"10\"></td>\r\n														</tr>\r\n													</tbody>\r\n												</table>\r\n											</td>\r\n										</tr>\r\n									</tbody>\r\n								</table>\r\n							</td>\r\n						</tr>\r\n					</tbody>\r\n				</table>\r\n			</td>\r\n		</tr>\r\n		<tr>\r\n			<td height=\"60\"></td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n</html>','email',NULL,0,'2026-10-21 10:36:17','2026-10-21 10:36:17');
/*!40000 ALTER TABLE `notification_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notification_templates`
--

DROP TABLE IF EXISTS `notification_templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notification_templates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `act` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `push_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_body` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `sms_body` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `push_body` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `shortcodes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `email_status` tinyint(1) NOT NULL DEFAULT '1',
  `email_sent_from_name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_sent_from_address` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sms_status` tinyint(1) NOT NULL DEFAULT '1',
  `sms_sent_from` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `push_status` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notification_templates`
--

LOCK TABLES `notification_templates` WRITE;
/*!40000 ALTER TABLE `notification_templates` DISABLE KEYS */;
INSERT INTO `notification_templates` VALUES (1,'BAL_ADD','Balance - Added','Your Account has been Credited',NULL,'<div><div style=\"font-family: Montserrat, sans-serif;\">{{amount}} {{site_currency}} has been added to your account .</div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">Transaction Number : {{trx}}</div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><span style=\"color: rgb(33, 37, 41); font-family: Montserrat, sans-serif;\">Your Current Balance is :&nbsp;</span><font style=\"font-family: Montserrat, sans-serif;\"><span style=\"font-weight: bolder;\">{{post_balance}}&nbsp; {{site_currency}}&nbsp;</span></font><br></div><div><font style=\"font-family: Montserrat, sans-serif;\"><span style=\"font-weight: bolder;\"><br></span></font></div><div>Admin note:&nbsp;<span style=\"color: rgb(33, 37, 41); font-size: 12px; font-weight: 600; white-space: nowrap; text-align: var(--bs-body-text-align);\">{{remark}}</span></div>','{{amount}} {{site_currency}} credited in your account. Your Current Balance {{post_balance}} {{site_currency}} . Transaction: #{{trx}}. Admin note is \"{{remark}}\"',NULL,'{\"trx\":\"Transaction number for the action\",\"amount\":\"Amount inserted by the admin\",\"remark\":\"Remark inserted by the admin\",\"post_balance\":\"Balance of the user after this transaction\"}',1,NULL,NULL,0,NULL,0,'2021-11-03 12:00:00','2022-04-03 02:18:28'),(2,'BAL_SUB','Balance - Subtracted','Your Account has been Debited',NULL,'<div style=\"font-family: Montserrat, sans-serif;\">{{amount}} {{site_currency}} has been subtracted from your account .</div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">Transaction Number : {{trx}}</div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><span style=\"color: rgb(33, 37, 41); font-family: Montserrat, sans-serif;\">Your Current Balance is :&nbsp;</span><font style=\"font-family: Montserrat, sans-serif;\"><span style=\"font-weight: bolder;\">{{post_balance}}&nbsp; {{site_currency}}</span></font><br><div><font style=\"font-family: Montserrat, sans-serif;\"><span style=\"font-weight: bolder;\"><br></span></font></div><div>Admin Note: {{remark}}</div>','{{amount}} {{site_currency}} debited from your account. Your Current Balance {{post_balance}} {{site_currency}} . Transaction: #{{trx}}. Admin Note is {{remark}}',NULL,'{\"trx\":\"Transaction number for the action\",\"amount\":\"Amount inserted by the admin\",\"remark\":\"Remark inserted by the admin\",\"post_balance\":\"Balance of the user after this transaction\"}',1,NULL,NULL,1,NULL,0,'2021-11-03 12:00:00','2022-04-03 02:24:11'),(3,'DEPOSIT_COMPLETE','Deposit - Automated - Successful','Deposit Completed Successfully',NULL,'<div>Your deposit of&nbsp;<span style=\"font-weight: bolder;\">{{amount}} {{site_currency}}</span>&nbsp;is via&nbsp;&nbsp;<span style=\"font-weight: bolder;\">{{method_name}}&nbsp;</span>has been completed Successfully.<span style=\"font-weight: bolder;\"><br></span></div><div><span style=\"font-weight: bolder;\"><br></span></div><div><span style=\"font-weight: bolder;\">Details of your Deposit :<br></span></div><div><br></div><div>Amount : {{amount}} {{site_currency}}</div><div>Charge:&nbsp;<font color=\"#000000\">{{charge}} {{site_currency}}</font></div><div><br></div><div>Conversion Rate : 1 {{site_currency}} = {{rate}} {{method_currency}}</div><div>Received : {{method_amount}} {{method_currency}}<br></div><div>Paid via :&nbsp; {{method_name}}</div><div><br></div><div>Transaction Number : {{trx}}</div><div><font size=\"5\"><span style=\"font-weight: bolder;\"><br></span></font></div><div><font size=\"5\">Your current Balance is&nbsp;<span style=\"font-weight: bolder;\">{{post_balance}} {{site_currency}}</span></font></div><div><br style=\"font-family: Montserrat, sans-serif;\"></div>','{{amount}} {{site_currency}} Deposit successfully by {{method_name}}',NULL,'{\"trx\":\"Transaction number for the deposit\",\"amount\":\"Amount inserted by the user\",\"charge\":\"Gateway charge set by the admin\",\"rate\":\"Conversion rate between base currency and method currency\",\"method_name\":\"Name of the deposit method\",\"method_currency\":\"Currency of the deposit method\",\"method_amount\":\"Amount after conversion between base currency and method currency\",\"post_balance\":\"Balance of the user after this transaction\"}',1,NULL,NULL,1,NULL,0,'2021-11-03 12:00:00','2022-04-03 02:25:43'),(4,'DEPOSIT_APPROVE','Deposit - Manual - Approved','Your Deposit is Approved',NULL,'<div style=\"font-family: Montserrat, sans-serif;\">Your deposit request of&nbsp;<span style=\"font-weight: bolder;\">{{amount}} {{site_currency}}</span>&nbsp;is via&nbsp;&nbsp;<span style=\"font-weight: bolder;\">{{method_name}}&nbsp;</span>is Approved .<span style=\"font-weight: bolder;\"><br></span></div><div style=\"font-family: Montserrat, sans-serif;\"><span style=\"font-weight: bolder;\"><br></span></div><div style=\"font-family: Montserrat, sans-serif;\"><span style=\"font-weight: bolder;\">Details of your Deposit :<br></span></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">Amount : {{amount}} {{site_currency}}</div><div style=\"font-family: Montserrat, sans-serif;\">Charge:&nbsp;<font color=\"#FF0000\">{{charge}} {{site_currency}}</font></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">Conversion Rate : 1 {{site_currency}} = {{rate}} {{method_currency}}</div><div style=\"font-family: Montserrat, sans-serif;\">Received : {{method_amount}} {{method_currency}}<br></div><div style=\"font-family: Montserrat, sans-serif;\">Paid via :&nbsp; {{method_name}}</div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">Transaction Number : {{trx}}</div><div style=\"font-family: Montserrat, sans-serif;\"><font size=\"5\"><span style=\"font-weight: bolder;\"><br></span></font></div><div style=\"font-family: Montserrat, sans-serif;\"><font size=\"5\">Your current Balance is&nbsp;<span style=\"font-weight: bolder;\">{{post_balance}} {{site_currency}}</span></font></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div>','Admin Approve Your {{amount}} {{site_currency}} payment request by {{method_name}} transaction : {{trx}}',NULL,'{\"trx\":\"Transaction number for the deposit\",\"amount\":\"Amount inserted by the user\",\"charge\":\"Gateway charge set by the admin\",\"rate\":\"Conversion rate between base currency and method currency\",\"method_name\":\"Name of the deposit method\",\"method_currency\":\"Currency of the deposit method\",\"method_amount\":\"Amount after conversion between base currency and method currency\",\"post_balance\":\"Balance of the user after this transaction\"}',1,NULL,NULL,1,NULL,0,'2021-11-03 12:00:00','2022-04-03 02:26:07'),(5,'DEPOSIT_REJECT','Deposit - Manual - Rejected','Your Deposit Request is Rejected',NULL,'<div style=\"font-family: Montserrat, sans-serif;\">Your deposit request of&nbsp;<span style=\"font-weight: bolder;\">{{amount}} {{site_currency}}</span>&nbsp;is via&nbsp;&nbsp;<span style=\"font-weight: bolder;\">{{method_name}} has been rejected</span>.<span style=\"font-weight: bolder;\"><br></span></div><div><br></div><div><br></div><div style=\"font-family: Montserrat, sans-serif;\">Conversion Rate : 1 {{site_currency}} = {{rate}} {{method_currency}}</div><div style=\"font-family: Montserrat, sans-serif;\">Received : {{method_amount}} {{method_currency}}<br></div><div style=\"font-family: Montserrat, sans-serif;\">Paid via :&nbsp; {{method_name}}</div><div style=\"font-family: Montserrat, sans-serif;\">Charge: {{charge}}</div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">Transaction Number was : {{trx}}</div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">if you have any queries, feel free to contact us.<br></div><br style=\"font-family: Montserrat, sans-serif;\"><div style=\"font-family: Montserrat, sans-serif;\"><br><br></div><span style=\"color: rgb(33, 37, 41); font-family: Montserrat, sans-serif;\">{{rejection_message}}</span><br>','Admin Rejected Your {{amount}} {{site_currency}} payment request by {{method_name}}\r\n\r\n{{rejection_message}}',NULL,'{\"trx\":\"Transaction number for the deposit\",\"amount\":\"Amount inserted by the user\",\"charge\":\"Gateway charge set by the admin\",\"rate\":\"Conversion rate between base currency and method currency\",\"method_name\":\"Name of the deposit method\",\"method_currency\":\"Currency of the deposit method\",\"method_amount\":\"Amount after conversion between base currency and method currency\",\"rejection_message\":\"Rejection message by the admin\"}',1,NULL,NULL,1,NULL,0,'2021-11-03 12:00:00','2022-04-05 03:45:27'),(6,'DEPOSIT_REQUEST','Deposit - Manual - Requested','Deposit Request Submitted Successfully',NULL,'<div>Your deposit request of&nbsp;<span style=\"font-weight: bolder;\">{{amount}} {{site_currency}}</span>&nbsp;is via&nbsp;&nbsp;<span style=\"font-weight: bolder;\">{{method_name}}&nbsp;</span>submitted successfully<span style=\"font-weight: bolder;\">&nbsp;.<br></span></div><div><span style=\"font-weight: bolder;\"><br></span></div><div><span style=\"font-weight: bolder;\">Details of your Deposit :<br></span></div><div><br></div><div>Amount : {{amount}} {{site_currency}}</div><div>Charge:&nbsp;<font color=\"#FF0000\">{{charge}} {{site_currency}}</font></div><div><br></div><div>Conversion Rate : 1 {{site_currency}} = {{rate}} {{method_currency}}</div><div>Payable : {{method_amount}} {{method_currency}}<br></div><div>Pay via :&nbsp; {{method_name}}</div><div><br></div><div>Transaction Number : {{trx}}</div><div><br></div><div><br style=\"font-family: Montserrat, sans-serif;\"></div>','{{amount}} {{site_currency}} Deposit requested by {{method_name}}. Charge: {{charge}} . Trx: {{trx}}',NULL,'{\"trx\":\"Transaction number for the deposit\",\"amount\":\"Amount inserted by the user\",\"charge\":\"Gateway charge set by the admin\",\"rate\":\"Conversion rate between base currency and method currency\",\"method_name\":\"Name of the deposit method\",\"method_currency\":\"Currency of the deposit method\",\"method_amount\":\"Amount after conversion between base currency and method currency\"}',1,NULL,NULL,1,NULL,0,'2021-11-03 12:00:00','2022-04-03 02:29:19'),(7,'PASS_RESET_CODE','Password - Reset - Code','Password Reset',NULL,'<div style=\"font-family: Montserrat, sans-serif;\">We have received a request to reset the password for your account on&nbsp;<span style=\"font-weight: bolder;\">{{time}} .<br></span></div><div style=\"font-family: Montserrat, sans-serif;\">Requested From IP:&nbsp;<span style=\"font-weight: bolder;\">{{ip}}</span>&nbsp;using&nbsp;<span style=\"font-weight: bolder;\">{{browser}}</span>&nbsp;on&nbsp;<span style=\"font-weight: bolder;\">{{operating_system}}&nbsp;</span>.</div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><br style=\"font-family: Montserrat, sans-serif;\"><div style=\"font-family: Montserrat, sans-serif;\"><div>Your account recovery code is:&nbsp;&nbsp;&nbsp;<font size=\"6\"><span style=\"font-weight: bolder;\">{{code}}</span></font></div><div><br></div></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\"><font size=\"4\" color=\"#CC0000\">If you do not wish to reset your password, please disregard this message.&nbsp;</font><br></div><div><font size=\"4\" color=\"#CC0000\"><br></font></div>','Your account recovery code is: {{code}}',NULL,'{\"code\":\"Verification code for password reset\",\"ip\":\"IP address of the user\",\"browser\":\"Browser of the user\",\"operating_system\":\"Operating system of the user\",\"time\":\"Time of the request\"}',1,NULL,NULL,0,NULL,0,'2021-11-03 12:00:00','2022-03-20 20:47:05'),(8,'PASS_RESET_DONE','Password - Reset - Confirmation','You have reset your password',NULL,'<p style=\"font-family: Montserrat, sans-serif;\">You have successfully reset your password.</p><p style=\"font-family: Montserrat, sans-serif;\">You changed from&nbsp; IP:&nbsp;<span style=\"font-weight: bolder;\">{{ip}}</span>&nbsp;using&nbsp;<span style=\"font-weight: bolder;\">{{browser}}</span>&nbsp;on&nbsp;<span style=\"font-weight: bolder;\">{{operating_system}}&nbsp;</span>&nbsp;on&nbsp;<span style=\"font-weight: bolder;\">{{time}}</span></p><p style=\"font-family: Montserrat, sans-serif;\"><span style=\"font-weight: bolder;\"><br></span></p><p style=\"font-family: Montserrat, sans-serif;\"><span style=\"font-weight: bolder;\"><font color=\"#ff0000\">If you did not change that, please contact us as soon as possible.</font></span></p>','Your password has been changed successfully',NULL,'{\"ip\":\"IP address of the user\",\"browser\":\"Browser of the user\",\"operating_system\":\"Operating system of the user\",\"time\":\"Time of the request\"}',1,NULL,NULL,1,NULL,0,'2021-11-03 12:00:00','2022-04-05 03:46:35'),(9,'ADMIN_SUPPORT_REPLY','Support - Reply','Reply Support Ticket',NULL,'<div><p><span data-mce-style=\"font-size: 11pt;\" style=\"font-size: 11pt;\"><span style=\"font-weight: bolder;\">A member from our support team has replied to the following ticket:</span></span></p><p><span style=\"font-weight: bolder;\"><span data-mce-style=\"font-size: 11pt;\" style=\"font-size: 11pt;\"><span style=\"font-weight: bolder;\"><br></span></span></span></p><p><span style=\"font-weight: bolder;\">[Ticket#{{ticket_id}}] {{ticket_subject}}<br><br>Click here to reply:&nbsp; {{link}}</span></p><p>----------------------------------------------</p><p>Here is the reply :<br></p><p>{{reply}}<br></p></div><div><br style=\"font-family: Montserrat, sans-serif;\"></div>','Your Ticket#{{ticket_id}} :  {{ticket_subject}} has been replied.',NULL,'{\"ticket_id\":\"ID of the support ticket\",\"ticket_subject\":\"Subject  of the support ticket\",\"reply\":\"Reply made by the admin\",\"link\":\"URL to view the support ticket\"}',1,NULL,NULL,1,NULL,0,'2021-11-03 12:00:00','2022-03-20 20:47:51'),(10,'EVER_CODE','Verification - Email','Please verify your email address',NULL,'<br><div><div style=\"font-family: Montserrat, sans-serif;\">Thanks For joining us.<br></div><div style=\"font-family: Montserrat, sans-serif;\">Please use the below code to verify your email address.<br></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">Your email verification code is:<font size=\"6\"><span style=\"font-weight: bolder;\">&nbsp;{{code}}</span></font></div></div>','---',NULL,'{\"code\":\"Email verification code\"}',1,NULL,NULL,0,NULL,0,'2021-11-03 12:00:00','2022-04-03 02:32:07'),(11,'SVER_CODE','Verification - SMS','Verify Your Mobile Number',NULL,'---','Your phone verification code is: {{code}}',NULL,'{\"code\":\"SMS Verification Code\"}',0,NULL,NULL,1,NULL,0,'2021-11-03 12:00:00','2022-03-20 19:24:37'),(12,'WITHDRAW_APPROVE','Withdraw - Approved','Withdraw Request has been Processed and your money is sent',NULL,'<div style=\"font-family: Montserrat, sans-serif;\">Your withdraw request of&nbsp;<span style=\"font-weight: bolder;\">{{amount}} {{site_currency}}</span>&nbsp; via&nbsp;&nbsp;<span style=\"font-weight: bolder;\">{{method_name}}&nbsp;</span>has been Processed Successfully.<span style=\"font-weight: bolder;\"><br></span></div><div style=\"font-family: Montserrat, sans-serif;\"><span style=\"font-weight: bolder;\"><br></span></div><div style=\"font-family: Montserrat, sans-serif;\"><span style=\"font-weight: bolder;\">Details of your withdraw:<br></span></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">Amount : {{amount}} {{site_currency}}</div><div style=\"font-family: Montserrat, sans-serif;\">Charge:&nbsp;<font color=\"#FF0000\">{{charge}} {{site_currency}}</font></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">Conversion Rate : 1 {{site_currency}} = {{rate}} {{method_currency}}</div><div style=\"font-family: Montserrat, sans-serif;\">You will get: {{method_amount}} {{method_currency}}<br></div><div style=\"font-family: Montserrat, sans-serif;\">Via :&nbsp; {{method_name}}</div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">Transaction Number : {{trx}}</div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">-----</div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\"><font size=\"4\">Details of Processed Payment :</font></div><div style=\"font-family: Montserrat, sans-serif;\"><font size=\"4\"><span style=\"font-weight: bolder;\">{{admin_details}}</span></font></div>','Admin Approve Your {{amount}} {{site_currency}} withdraw request by {{method_name}}. Transaction {{trx}}',NULL,'{\"trx\":\"Transaction number for the withdraw\",\"amount\":\"Amount requested by the user\",\"charge\":\"Gateway charge set by the admin\",\"rate\":\"Conversion rate between base currency and method currency\",\"method_name\":\"Name of the withdraw method\",\"method_currency\":\"Currency of the withdraw method\",\"method_amount\":\"Amount after conversion between base currency and method currency\",\"admin_details\":\"Details provided by the admin\"}',1,NULL,NULL,1,NULL,0,'2021-11-03 12:00:00','2022-03-20 20:50:16'),(13,'WITHDRAW_REJECT','Withdraw - Rejected','Withdraw Request has been Rejected and your money is refunded to your account',NULL,'<div style=\"font-family: Montserrat, sans-serif;\">Your withdraw request of&nbsp;<span style=\"font-weight: bolder;\">{{amount}} {{site_currency}}</span>&nbsp; via&nbsp;&nbsp;<span style=\"font-weight: bolder;\">{{method_name}}&nbsp;</span>has been Rejected.<span style=\"font-weight: bolder;\"><br></span></div><div style=\"font-family: Montserrat, sans-serif;\"><span style=\"font-weight: bolder;\"><br></span></div><div style=\"font-family: Montserrat, sans-serif;\"><span style=\"font-weight: bolder;\">Details of your withdraw:<br></span></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">Amount : {{amount}} {{site_currency}}</div><div style=\"font-family: Montserrat, sans-serif;\">Charge:&nbsp;<font color=\"#FF0000\">{{charge}} {{site_currency}}</font></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">Conversion Rate : 1 {{site_currency}} = {{rate}} {{method_currency}}</div><div style=\"font-family: Montserrat, sans-serif;\">You should get: {{method_amount}} {{method_currency}}<br></div><div style=\"font-family: Montserrat, sans-serif;\">Via :&nbsp; {{method_name}}</div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">Transaction Number : {{trx}}</div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">----</div><div style=\"font-family: Montserrat, sans-serif;\"><font size=\"3\"><br></font></div><div style=\"font-family: Montserrat, sans-serif;\"><font size=\"3\">{{amount}} {{currency}} has been&nbsp;<span style=\"font-weight: bolder;\">refunded&nbsp;</span>to your account and your current Balance is&nbsp;<span style=\"font-weight: bolder;\">{{post_balance}}</span><span style=\"font-weight: bolder;\">&nbsp;{{site_currency}}</span></font></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">-----</div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\"><font size=\"4\">Details of Rejection :</font></div><div style=\"font-family: Montserrat, sans-serif;\"><font size=\"4\"><span style=\"font-weight: bolder;\">{{admin_details}}</span></font></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\"><br><br><br><br><br></div><div></div><div></div>','Admin Rejected Your {{amount}} {{site_currency}} withdraw request. Your Main Balance {{post_balance}}  {{method_name}} , Transaction {{trx}}',NULL,'{\"trx\":\"Transaction number for the withdraw\",\"amount\":\"Amount requested by the user\",\"charge\":\"Gateway charge set by the admin\",\"rate\":\"Conversion rate between base currency and method currency\",\"method_name\":\"Name of the withdraw method\",\"method_currency\":\"Currency of the withdraw method\",\"method_amount\":\"Amount after conversion between base currency and method currency\",\"post_balance\":\"Balance of the user after fter this action\",\"admin_details\":\"Rejection message by the admin\"}',1,NULL,NULL,1,NULL,0,'2021-11-03 12:00:00','2022-03-20 20:57:46'),(14,'WITHDRAW_REQUEST','Withdraw - Requested','Withdraw Request Submitted Successfully',NULL,'<div style=\"font-family: Montserrat, sans-serif;\">Your withdraw request of&nbsp;<span style=\"font-weight: bolder;\">{{amount}} {{site_currency}}</span>&nbsp; via&nbsp;&nbsp;<span style=\"font-weight: bolder;\">{{method_name}}&nbsp;</span>has been submitted Successfully.<span style=\"font-weight: bolder;\"><br></span></div><div style=\"font-family: Montserrat, sans-serif;\"><span style=\"font-weight: bolder;\"><br></span></div><div style=\"font-family: Montserrat, sans-serif;\"><span style=\"font-weight: bolder;\">Details of your withdraw:<br></span></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">Amount : {{amount}} {{site_currency}}</div><div style=\"font-family: Montserrat, sans-serif;\">Charge:&nbsp;<font color=\"#FF0000\">{{charge}} {{site_currency}}</font></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">Conversion Rate : 1 {{site_currency}} = {{rate}} {{method_currency}}</div><div style=\"font-family: Montserrat, sans-serif;\">You will get: {{method_amount}} {{method_currency}}<br></div><div style=\"font-family: Montserrat, sans-serif;\">Via :&nbsp; {{method_name}}</div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">Transaction Number : {{trx}}</div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\"><font size=\"5\">Your current Balance is&nbsp;<span style=\"font-weight: bolder;\">{{post_balance}} {{site_currency}}</span></font></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\"><br><br><br></div>','{{amount}} {{site_currency}} withdraw requested by {{method_name}}. You will get {{method_amount}} {{method_currency}} Trx: {{trx}}',NULL,'{\"trx\":\"Transaction number for the withdraw\",\"amount\":\"Amount requested by the user\",\"charge\":\"Gateway charge set by the admin\",\"rate\":\"Conversion rate between base currency and method currency\",\"method_name\":\"Name of the withdraw method\",\"method_currency\":\"Currency of the withdraw method\",\"method_amount\":\"Amount after conversion between base currency and method currency\",\"post_balance\":\"Balance of the user after fter this transaction\"}',1,NULL,NULL,1,NULL,0,'2021-11-03 12:00:00','2022-03-21 04:39:03'),(15,'DEFAULT','Default Template','Subject',NULL,'{{message}}','{{message}}',NULL,'{\"subject\":\"Subject\",\"message\":\"Message\"}',1,NULL,NULL,1,NULL,0,'2019-09-14 13:14:22','2023-12-13 12:24:20'),(16,'KYC_APPROVE','KYC Approved','KYC has been approved',NULL,NULL,NULL,NULL,'[]',1,NULL,NULL,1,NULL,0,NULL,NULL),(17,'KYC_REJECT','KYC Rejected','KYC has been rejected',NULL,NULL,NULL,NULL,'{\"reason\":\"Rejection Reason\"}',1,NULL,NULL,1,NULL,0,NULL,NULL),(18,'MAIL_TO_AUTHOR','Send Email to Author','New email from user',NULL,'<div>Username:&nbsp;{{username}}</div><div><span style=\"color: var(--bs-body-color); font-size: 1rem; text-align: var(--bs-body-text-align);\">Email:&nbsp;{{email}}</span><br></div><div>Message: {{message}}</div>','New sms from: {{username}}',NULL,'{\"username\":\"Username\",\"email\":\"Email\", \"message\": \"Message from user\"}',1,NULL,NULL,0,NULL,0,NULL,'2023-12-11 05:40:06'),(19,'REFUND_REJECTED','Refund Request - Rejected','Refund Request Rejected',NULL,'Your refund request is rejected for the purchase code of&nbsp;{{purchase_code}}','{{amount}} {{site_currency}} credited in your account. Your Current Balance {{post_balance}} {{site_currency}} . Transaction: #{{trx}}. Admin note is \"{{remark}}\"',NULL,'{\"purchase_code\":\"Purchase Code of the item\", \"message\": \"Message for the author\", \"buyer\": \"Buyer\'s username\", \"author\":\"Author\'s username\"}',1,NULL,NULL,0,NULL,0,'2021-11-03 12:00:00','2023-12-04 07:01:45'),(20,'COMMENTED','Commented','New Comment on Your Product',NULL,'Hey&nbsp;{{author}},&nbsp;<div>{{username}} commented on your item&nbsp;<b>{{product_name}} .</b></div><div><b>Comment content</b>:&nbsp;{{comment}}</div><div><b>Reply URL:&nbsp;</b><span style=\"text-align: var(--bs-body-text-align);\"><font color=\"#212529\">{{url}}</font></span></div>','Hey {{author}}, \r\n{{username}} commented on your item {{product_name}} .\r\nComment content: {{comment}}\r\nReply URL: {{url}}',NULL,'{\"comment\":\"Comment body\", \"author\": \"Author\'s username\", \"comment_user_mail\": \"Comment user email\", \"product_name\": \"Product\'s name\", \"username\": \"Comment username\", \"url\": \"URL to view the comment\"}',1,NULL,NULL,1,NULL,0,'2021-11-03 12:00:00','2024-01-10 12:37:56'),(21,'REFUND_APPROVED','Refund Request - Approved','Refund Request Approved',NULL,'Your Refund Request Approved.<div>Purchase Code:&nbsp;{{purchase_code}}</div><div>Amount:&nbsp;{{amount}}</div><div>Amount added to your balance.</div>','{{amount}} {{site_currency}} credited in your account. Your Current Balance {{post_balance}} {{site_currency}} . Transaction: #{{trx}}. Admin note is \"{{remark}}\"',NULL,'{\"purchase_code\":\"Purchase Code of the item\", \"amount\" :\"Amount added to balance\", \"message\": \"Message for the author\", \"buyer\": \"Buyer\'s username\", \"author\": \"Author\'s username\"}',1,NULL,NULL,0,NULL,0,'2021-11-03 12:00:00','2023-12-17 07:02:54'),(22,'PAYMENT_VIA_ACCOUNT','Payment via Account Balance - Subtracted','Your Account has been Debited',NULL,'<div style=\"font-family: Montserrat, sans-serif;\">{{amount}} {{site_currency}} has been subtracted from your account .</div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">Transaction Number : {{trx}}</div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><span style=\"color: rgb(33, 37, 41); font-family: Montserrat, sans-serif;\">Your Current Balance is :&nbsp;</span><font style=\"font-family: Montserrat, sans-serif;\"><span style=\"font-weight: bolder;\">{{post_balance}}&nbsp; {{site_currency}}</span></font><br><div><font style=\"font-family: Montserrat, sans-serif;\"><span style=\"font-weight: bolder;\"><br></span></font></div><div>Admin Note: {{remark}}</div>','{{amount}} {{site_currency}} debited from your account. Your Current Balance {{post_balance}} {{site_currency}} . Transaction: #{{trx}}. Admin Note is {{remark}}',NULL,'{\"trx\":\"Transaction number for the action\",\"amount\":\"Amount inserted by the admin\",\"remark\":\"Remark inserted by the admin\",\"post_balance\":\"Balance of the user after this transaction\"}',1,NULL,NULL,1,NULL,0,'2021-11-03 12:00:00','2022-04-03 02:24:11'),(23,'REVIEWER_BANNED','Reviewer Banned','You are banned!',NULL,'<div style=\"text-align: center;\"><b style=\"text-align: var(--bs-body-text-align);\"><font size=\"6\" style=\"\" color=\"#cc3399\">You are banned:</font></b></div><div><br></div><div>Reason:&nbsp;</div><div>{{reason}}<br></div>','Reason: \r\n{{reason}}',NULL,'{\"reason\":\"The reason for ban\"}\r\n',1,NULL,NULL,0,NULL,0,'2021-11-03 12:00:00','2023-12-19 08:59:49'),(24,'REVIEW_NOTIFICATION','Item Review Notification','Your Item Reviewed',NULL,'The item you submitted to the&nbsp;{{site_name}} is reviewed by our reviewer.<div>Item Status:&nbsp;{{status}}</div><div>Review Message:&nbsp;{{message}}</div><div><br></div>','asdfasdf',NULL,'{\"status\":\"Status of the item\",\"message\":\"Review message by the reviewer\"}',1,NULL,NULL,1,NULL,0,'2021-11-03 12:00:00','2023-12-19 09:24:01'),(25,'BUYER_REVIEW','Buyer Review','Your item reviewed by buyer',NULL,'Your item got a new review in the category of :&nbsp;{{review_category}}<div>View the details here:&nbsp;{{link}}</div>','asdfasdf',NULL,'{\"buyer\" : \"Buyer username\", \"purchase_code\":\"Purchase code of the item\",\"review\":\"Review message by the buyer\",\"rating\":\"Rating of the review\",\"review_category\":\"In which category the rating submitted\", \"link\" : \"Link of the review\"}',1,NULL,NULL,0,NULL,0,'2021-11-03 12:00:00','2023-12-19 09:36:18'),(27,'NEW_REFUND_REQUEST','Refund Request - New','New Refund Request',NULL,NULL,NULL,NULL,'{\"buyer\": \"Buyer username\", \"purchase_code\":\"Purchase code of the item\",\"reason\":\"Reason Fore Refund\", \"refundable_amount\": \"Refundable amount\"}',1,NULL,NULL,0,NULL,0,'2021-11-03 12:00:00','2023-12-19 09:36:18'),(28,'PRODUCT_APPROVED','Product Approved','Your product has been approved',NULL,'<span style=\"color: rgb(33, 37, 41);\">Hello&nbsp;{{author}},</span><div>Your<span style=\"font-weight: bolder;\"> {{product_name}}&nbsp;</span>, in&nbsp;{{product_category}} is approved at {{approved_time}}.&nbsp;<br></div><div>Reviewer message:&nbsp;{{message}}</div>','Hello {{author}},\r\nYour {{product_name}} , in {{product_category}} is approved at {{approved_time}}. \r\nReviewer message: {{message}}',NULL,'{\"author\": \"Author username\", \"product_name\":\"Name of the product\",\"approved_time\":\"Time when approved\",\"url\":\"URL of approved product\", \"message\": \"Message from reviewer\"}',1,NULL,NULL,0,NULL,0,'2021-11-03 12:00:00','2024-01-10 12:40:17'),(29,'PRODUCT_SOFT_REJECTED','Product Soft Reject','Product soft rejected',NULL,'<span style=\"color: rgb(33, 37, 41);\">Hello&nbsp;{{author}},</span><div>Your<span style=\"font-weight: bolder;\">&nbsp;</span><b>{{product_name}}&nbsp;</b>, in&nbsp;<b>{{product_category}} </b>is soft rejected at {{review_time}}.&nbsp; You can edit the item from the following link:&nbsp;{{edit_url}}<br></div><div>Reviewer message:&nbsp;{{message}}</div>','Hello {{author}},\r\nYour {{product_name}} , in {{product_category}} is soft rejected at {{review_time}}.  You can edit the item from the following link: {{edit_url}}\r\nReviewer message: {{message}}',NULL,'{\"author\": \"Author username\", \"product_name\":\"Name of the product\", \"product_category\": \"Product Category\",\"review_time\":\"Time when reviewd\",\"edit_url\":\"URL for re-submission\", \"message\": \"Message from reviewer\"}',1,NULL,NULL,0,NULL,0,'2021-11-03 12:00:00','2024-01-10 11:56:03'),(30,'PRODUCT_HARD_REJECTED','Product Hard Reject','Product hard rejected',NULL,'<span style=\"color: rgb(33, 37, 41);\">Hello&nbsp;{{author}},</span><div>Your<span style=\"font-weight: bolder;\">&nbsp;</span><span style=\"font-weight: bolder;\">{{product_name}}&nbsp;</span>, in&nbsp;<span style=\"font-weight: bolder;\">{{product_category}}&nbsp;</span>is hard rejected at {{review_time}}.&nbsp;&nbsp;<br></div><div>Reviewer message:&nbsp;{{message}}</div>','Hello {{author}},\r\nYour {{product_name}} , in {{product_category}} is hard rejected at {{review_time}}.  \r\nReviewer message: {{message}}',NULL,'{\"author\": \"Author username\", \"product_name\":\"Name of the product\", \"product_category\": \"Product Category\",\"review_time\":\"Time when reviewd\", \"message\": \"Message from reviewer\"}',1,NULL,NULL,0,NULL,0,'2021-11-03 12:00:00','2024-01-10 11:56:59'),(31,'PRODUCT_DOWN','Product Down','Your Product is temporarily down',NULL,'<span style=\"color: rgb(33, 37, 41);\">Hello&nbsp;{{author}},</span><div>Your<span style=\"font-weight: bolder;\">&nbsp;{{product_name}}&nbsp;</span>, in&nbsp;{{product_category}} is down at {{review_time}}.&nbsp; You can edit&nbsp; the product from here: {{edit_url}} .&nbsp;<br></div><div>Reviewer message:&nbsp;{{message}}</div>','Hello {{author}},\r\nYour {{product_name}} , in {{product_category}} is down at {{review_time}}. \r\nReviewer message: {{message}}',NULL,'{\"author\": \"Author username\", \"product_name\":\"Name of the product\", \"product_category\": \"Product Category\",\"review_time\":\"Time when reviewd\", \"edit_url\": \"Edit URL\", \"message\": \"Message from reviewer\"}',1,NULL,NULL,0,NULL,0,'2021-11-03 12:00:00','2024-01-10 11:30:42'),(32,'PRODUCT_PERMANENT_DOWN','Product Permanent Down','Your Product is permanently down',NULL,'','',NULL,'{\"author\": \"Author username\", \"product_name\":\"Name of the product\", \"product_category\": \"Product Category\",\"review_time\":\"Time when reviewd\", \"message\": \"Message from reviewer\"}',1,NULL,NULL,0,NULL,0,'2021-11-03 12:00:00','2022-04-03 02:18:28'),(33,'PRODUCT_UPDATE_REJECTED','Product update rejected','Your update rejected',NULL,'<span style=\"color: rgb(33, 37, 41);\">Hello&nbsp;{{author}},</span><div>Your<span style=\"font-weight: bolder;\">&nbsp;</span><span style=\"font-weight: bolder;\">{{product_name}}&nbsp;</span>, in&nbsp;<span style=\"font-weight: bolder;\">{{product_category}}</span>\'s update is rejected at {{review_time}}.&nbsp; You can edit the item from this link:&nbsp;{{edit_url}}&nbsp;<br></div><div>Reviewer message:&nbsp;{{message}}</div>','Hello {{author}},\r\nYour {{product_name}} , in {{product_category}}\'s update is rejected at {{review_time}}.  You can edit the item from this link: {{edit_url}} \r\nReviewer message: {{message}}',NULL,'{\"author\": \"Author username\", \"product_name\":\"Name of the product\", \"product_category\": \"Product Category\",\"review_time\":\"Time when reviewd\", \"edit_url\": \"Edit URL\", \"message\": \"Message from reviewer\"}',1,NULL,NULL,0,NULL,0,'2021-11-03 12:00:00','2024-01-10 11:58:01'),(34,'PRODUCT_UPDATE_APPROVED','Product update approved','Your update approved',NULL,'Hello&nbsp;{{author}},<div><b>{{product_name}} </b>, in&nbsp;{{product_category}} update is approved at&nbsp;{{review_time}}.&nbsp;<br></div><div>Reviewer message:&nbsp;{{message}}</div>','adfasdf',NULL,'{\"author\": \"Author username\", \"product_name\":\"Name of the product\", \"product_category\": \"Product Category\",\"review_time\":\"Time when reviewd\", \"url\": \"URL\", \"message\": \"Message from reviewer\"}',1,NULL,NULL,1,NULL,0,'2021-11-03 12:00:00','2024-01-10 08:04:35');
/*!40000 ALTER TABLE `notification_templates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned DEFAULT NULL,
  `purchase_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `license_key` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `certificate_number` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int NOT NULL DEFAULT '0',
  `product_id` bigint unsigned DEFAULT NULL,
  `is_extended` tinyint NOT NULL DEFAULT '0',
  `is_refunded` tinyint NOT NULL DEFAULT '0',
  `extended_amount` decimal(28,8) DEFAULT '0.00000000',
  `product_price` decimal(28,8) DEFAULT '0.00000000',
  `seller_fee` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `buyer_fee` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `quantity` int NOT NULL DEFAULT '1',
  `license` tinyint NOT NULL DEFAULT '1' COMMENT '1=personal\r\n2=commercial\r\n',
  `downloads` tinyint(1) NOT NULL DEFAULT '0',
  `discount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `seller_earning` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  PRIMARY KEY (`id`),
  KEY `purchase_items_purchase_id_foreign` (`order_id`),
  KEY `purchase_items_product_id_foreign` (`product_id`),
  KEY `idx_order_items_product_refund` (`product_id`,`is_refunded`),
  KEY `order_items_license_key_index` (`license_key`),
  KEY `order_items_certificate_number_index` (`certificate_number`),
  KEY `order_items_purchase_code_index` (`purchase_code`),
  KEY `order_items_user_id_index` (`user_id`),
  CONSTRAINT `purchase_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL,
  CONSTRAINT `purchase_items_purchase_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES (1,1,'AK23-H6XQILYAM1','AK23-LIC-44B5-1337-5467','AK23-CERT-2026-8313-8625',1,27,0,0,0.00000000,150000.00000000,0.00000000,0.00000000,1,1,4,0.00000000,'2026-10-20 02:34:04','2026-10-20 02:34:04',0.00000000),(2,2,'AK23-CQP5RZJEEK','AK23-LIC-C206-78BF-FC69','AK23-CERT-2026-5AB2-6B90',2,38,0,0,0.00000000,590000.00000000,0.00000000,0.00000000,1,1,5,0.00000000,'2026-10-19 22:34:04','2026-10-19 22:34:04',0.00000000),(3,3,'AK23-J42CEIHHYH','AK23-LIC-7C53-79F5-1823','AK23-CERT-2026-983D-FC6F',3,30,0,0,0.00000000,250000.00000000,0.00000000,0.00000000,1,1,1,0.00000000,'2026-10-19 14:34:04','2026-10-19 14:34:04',0.00000000),(4,4,'AK23-UK4DJLDAWF','AK23-LIC-ADD3-0F3D-A27F','AK23-CERT-2026-007F-6162',4,34,0,0,0.00000000,165000.00000000,0.00000000,0.00000000,1,1,2,0.00000000,'2026-10-19 04:34:04','2026-10-19 04:34:04',0.00000000),(5,5,'AK23-VEQFGVGXD4','AK23-LIC-E0E7-414E-E243','AK23-CERT-2026-40D3-2859',5,31,0,0,0.00000000,480000.00000000,0.00000000,0.00000000,1,1,8,0.00000000,'2026-10-18 04:34:04','2026-10-18 04:34:04',0.00000000),(6,6,'AK23-O1KQPNRAGE','AK23-LIC-3374-BABB-48AB','AK23-CERT-2026-EC2C-CDC1',6,14,0,1,0.00000000,120000.00000000,0.00000000,0.00000000,1,1,5,0.00000000,'2026-10-17 04:34:04','2026-10-17 04:34:04',0.00000000),(7,7,'AK23-1MEJGV1MAE','AK23-LIC-7A48-BC61-DF77','AK23-CERT-2026-FEAD-2F83',1,29,0,0,0.00000000,210000.00000000,0.00000000,0.00000000,1,1,6,0.00000000,'2026-10-16 04:34:04','2026-10-16 04:34:04',0.00000000),(8,8,'AK23-UVAXKO2CSB','AK23-LIC-F7C9-F171-A6FD','AK23-CERT-2026-81F7-35B8',2,37,0,0,0.00000000,240000.00000000,0.00000000,0.00000000,1,1,8,0.00000000,'2026-10-15 04:34:04','2026-10-15 04:34:04',0.00000000),(9,9,'AK23-E4MQ6I5XU7','AK23-LIC-F276-608A-AC56','AK23-CERT-2026-A12C-6A22',3,44,0,0,0.00000000,520000.00000000,0.00000000,0.00000000,1,1,7,0.00000000,'2026-10-14 04:34:04','2026-10-14 04:34:04',0.00000000),(10,10,'369333-75-e36zgv-542316','AK23-LIC-4175-E339-67E6','AK23-CERT-2026-AA01-FCAB',8,27,0,0,0.00000000,140000.00000000,0.00000000,0.00000000,1,1,0,0.00000000,'2026-10-21 08:46:41','2026-10-21 08:46:41',0.00000000),(11,11,'442479-67-hpsby7-224203','AK23-LIC-9D7D-4A21-9C16','AK23-CERT-2026-31E0-9F9C',8,27,0,0,0.00000000,140000.00000000,0.00000000,0.00000000,1,1,0,0.00000000,'2026-10-21 08:48:22','2026-10-21 08:48:22',0.00000000),(12,12,'810302-35-ql37wo-102765','AK23-LIC-7A9B-48BE-52CE','AK23-CERT-2026-5033-8609',9,27,0,0,0.00000000,140000.00000000,0.00000000,0.00000000,1,1,0,0.00000000,'2026-10-21 08:50:54','2026-10-21 08:50:54',0.00000000),(13,13,'520865-96-bemv50-459245','AK23-LIC-C1CE-0AD0-0629','AK23-CERT-2026-F6B8-89C6',9,27,0,0,0.00000000,140000.00000000,0.00000000,0.00000000,1,1,0,0.00000000,'2026-10-21 09:02:26','2026-10-21 09:02:26',0.00000000),(14,14,'697392-94-wx2mgj-671099','AK23-LIC-341D-D8DE-CF70','AK23-CERT-2026-3F5F-C418',9,27,0,0,0.00000000,140000.00000000,0.00000000,0.00000000,1,1,0,0.00000000,'2026-10-21 09:04:41','2026-10-21 09:04:41',0.00000000),(15,15,'472460-93-7wyfis-432863','AK23-LIC-DA72-3C69-8F96','AK23-CERT-2026-1D6D-8908',9,27,0,0,0.00000000,140000.00000000,0.00000000,0.00000000,1,1,0,0.00000000,'2026-10-21 09:04:55','2026-10-21 09:04:55',0.00000000),(16,16,'927261-40-onqkh9-580687','AK23-LIC-A086-B875-EBFB','AK23-CERT-2026-2748-D3BB',10,27,0,0,0.00000000,140000.00000000,0.00000000,0.00000000,1,1,0,0.00000000,'2026-10-21 09:19:56','2026-10-21 09:19:56',0.00000000),(17,17,'440180-86-nv5kte-630596','AK23-LIC-ECAC-C879-2040','AK23-CERT-2026-8DCA-3CCE',10,30,0,0,0.00000000,250000.00000000,0.00000000,0.00000000,1,1,0,0.00000000,'2026-10-21 10:06:51','2026-10-21 10:06:51',0.00000000),(18,18,'579911-24-54fngd-506410','AK23-LIC-105C-B14C-B9D1','AK23-CERT-2026-6290-364A',9,26,0,0,0.00000000,120000.00000000,0.00000000,0.00000000,1,1,0,0.00000000,'2026-10-21 10:19:06','2026-10-21 10:19:06',0.00000000),(19,19,'222704-30-raz0yt-954088','AK23-LIC-2306-32CA-0D6F','AK23-CERT-2026-1C57-231B',9,26,0,0,0.00000000,120000.00000000,0.00000000,0.00000000,1,1,0,0.00000000,'2026-10-21 10:19:34','2026-10-21 10:19:34',0.00000000),(20,20,'741173-22-jxz07e-427294','AK23-LIC-0C83-91E9-3ABD','AK23-CERT-2026-0E99-161D',9,26,0,0,0.00000000,120000.00000000,0.00000000,0.00000000,1,1,0,0.00000000,'2026-10-21 10:20:31','2026-10-21 10:20:31',0.00000000),(21,21,'424127-40-bd2uey-998506','AK23-LIC-E5B7-841E-1E0F','AK23-CERT-2026-D9AE-2969',9,26,0,0,0.00000000,120000.00000000,0.00000000,0.00000000,1,1,0,0.00000000,'2026-10-21 10:34:22','2026-10-21 10:34:22',0.00000000),(22,22,'990849-51-7lda0o-716895','AK23-LIC-F82E-B123-8837','AK23-CERT-2026-6F4D-06CF',9,26,0,0,0.00000000,120000.00000000,0.00000000,0.00000000,1,1,0,0.00000000,'2026-10-21 10:36:13','2026-10-21 10:36:13',0.00000000);
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `discount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `payment_status` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `trx` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `coupon_id` int unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `purchases_customer_id_foreign` (`user_id`),
  KEY `idx_orders_user_status` (`user_id`,`payment_status`),
  KEY `orders_trx_index` (`trx`),
  CONSTRAINT `purchases_customer_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,1,150000.00000000,0.00000000,1,'2026-10-20 02:34:04','2026-10-20 02:34:04','SELCOM-MP-839210',0),(2,2,590000.00000000,0.00000000,1,'2026-10-19 22:34:04','2026-10-19 22:34:04','SELCOM-TG-592811',0),(3,3,250000.00000000,0.00000000,1,'2026-10-19 14:34:04','2026-10-19 14:34:04','SELCOM-AIR-441029',0),(4,4,165000.00000000,0.00000000,1,'2026-10-19 04:34:04','2026-10-19 04:34:04','SELCOM-MP-993814',0),(5,5,480000.00000000,0.00000000,1,'2026-10-18 04:34:04','2026-10-18 04:34:04','SELCOM-HALO-123490',0),(6,6,120000.00000000,0.00000000,1,'2026-10-17 04:34:04','2026-10-17 04:34:04','SELCOM-MP-772108',0),(7,1,210000.00000000,0.00000000,1,'2026-10-16 04:34:04','2026-10-16 04:34:04','SELCOM-MP-662309',0),(8,2,240000.00000000,0.00000000,1,'2026-10-15 04:34:04','2026-10-15 04:34:04','SELCOM-TG-448201',0),(9,3,520000.00000000,0.00000000,1,'2026-10-14 04:34:04','2026-10-14 04:34:04','SELCOM-AIR-882910',0),(10,8,140000.00000000,0.00000000,1,'2026-10-21 08:46:41','2026-10-21 08:46:44','AK-IWQZLBTS',0),(11,8,140000.00000000,0.00000000,1,'2026-10-21 08:48:21','2026-10-21 08:48:22','AK-OZZRH1QE',0),(12,9,140000.00000000,0.00000000,0,'2026-10-21 08:50:54','2026-10-21 08:50:54','AK-X0VNJFBB',0),(13,9,140000.00000000,0.00000000,0,'2026-10-21 09:02:26','2026-10-21 09:02:26','AK-APEEQCDP',0),(14,9,140000.00000000,0.00000000,0,'2026-10-21 09:04:41','2026-10-21 09:04:41','AK-JYGECHD4',0),(15,9,140000.00000000,0.00000000,1,'2026-10-21 09:04:55','2026-10-21 09:05:10','AK-V6SQNIMK',0),(16,10,140000.00000000,0.00000000,0,'2026-10-21 09:19:56','2026-10-21 09:19:56','AK-NHVSEJRC',0),(17,10,250000.00000000,0.00000000,0,'2026-10-21 10:06:51','2026-10-21 10:06:51','AK-KGVTCIDU',0),(18,9,120000.00000000,0.00000000,0,'2026-10-21 10:19:06','2026-10-21 10:19:06','AK-VSV7HVKD',0),(19,9,120000.00000000,0.00000000,1,'2026-10-21 10:19:34','2026-10-21 10:19:34','AK-YRFU9LQE',0),(20,9,120000.00000000,0.00000000,1,'2026-10-21 10:20:30','2026-10-21 10:20:31','AK-C1EWR85E',0),(21,9,120000.00000000,0.00000000,0,'2026-10-21 10:34:22','2026-10-21 10:34:22','AK-9WIZNXDM',0),(22,9,120000.00000000,0.00000000,1,'2026-10-21 10:36:13','2026-10-21 10:36:13','AK-HSDXVYVO',0);
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tempname` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'template name',
  `secs` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `seo_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pages`
--

LOCK TABLES `pages` WRITE;
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;
INSERT INTO `pages` VALUES (1,'HOME','/','templates.basic.','[\"featured_category\",\"featured_product\",\"latest_product\",\"marketplace\",\"weekly_selling_product\",\"cta\",\"additional_benefit\"]',NULL,1,'2020-07-11 06:23:58','2024-01-30 12:20:31'),(5,'Contact','contact','templates.basic.','[\"faq\"]',NULL,1,'2020-10-22 01:14:53','2024-01-30 11:42:39'),(20,'FAQ','faq','templates.basic.','[\"faq\"]',NULL,1,'2026-10-21 04:52:12','2026-10-21 04:58:18');
/*!40000 ALTER TABLE `pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `email` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `token` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_rating`
--

DROP TABLE IF EXISTS `product_rating`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_rating` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `rating_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_rating_product_id_foreign` (`product_id`),
  KEY `product_rating_rating_id_foreign` (`rating_id`),
  CONSTRAINT `product_rating_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_rating_rating_id_foreign` FOREIGN KEY (`rating_id`) REFERENCES `ratings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_rating`
--

LOCK TABLES `product_rating` WRITE;
/*!40000 ALTER TABLE `product_rating` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_rating` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_user`
--

DROP TABLE IF EXISTS `product_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_user` (
  `product_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  KEY `product_user_product_id_foreign` (`product_id`),
  KEY `product_user_user_id_foreign` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_user`
--

LOCK TABLES `product_user` WRITE;
/*!40000 ALTER TABLE `product_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_views`
--

DROP TABLE IF EXISTS `product_views`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_views` (
  `id` int NOT NULL AUTO_INCREMENT,
  `product_id` int unsigned NOT NULL DEFAULT '0',
  `views` int DEFAULT '0',
  `views_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_views`
--

LOCK TABLES `product_views` WRITE;
/*!40000 ALTER TABLE `product_views` DISABLE KEYS */;
INSERT INTO `product_views` VALUES (1,1,7,'2026-10-19 21:00:00','2026-10-20 20:33:09','2026-10-20 20:45:43'),(2,26,1,'2026-10-20 21:00:00','2026-10-21 10:00:09','2026-10-21 10:00:09'),(3,27,1,'2026-10-20 21:00:00','2026-10-21 10:04:57','2026-10-21 10:04:57'),(4,26,1,'2026-10-21 21:00:00','2026-10-22 17:23:33','2026-10-22 17:23:33'),(5,41,1,'2026-10-22 21:00:00','2026-10-23 20:27:49','2026-10-23 20:27:49'),(6,44,2,'2026-10-22 21:00:00','2026-10-23 20:28:12','2026-10-23 20:30:24'),(7,28,1,'2026-10-22 21:00:00','2026-10-23 20:52:27','2026-10-23 20:52:27'),(8,27,1,'2026-10-22 21:00:00','2026-10-23 20:53:35','2026-10-23 20:53:35'),(9,43,1,'2026-10-23 21:00:00','2026-10-23 21:30:24','2026-10-23 21:30:24'),(10,40,1,'2026-10-23 21:00:00','2026-10-24 03:08:37','2026-10-24 03:08:37');
/*!40000 ALTER TABLE `product_views` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL DEFAULT '0',
  `assigned_to` int unsigned NOT NULL DEFAULT '0',
  `category_id` int unsigned NOT NULL DEFAULT '0',
  `sub_category_id` int unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(28,8) DEFAULT '0.00000000',
  `price_cl` decimal(28,8) DEFAULT '0.00000000',
  `thumbnail` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approved_by` int unsigned DEFAULT NULL,
  `demo_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `preview_video` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attribute_info` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `preview_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `inline_preview_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_sold` int NOT NULL DEFAULT '0',
  `total_review` int NOT NULL DEFAULT '0',
  `avg_rating` decimal(5,2) NOT NULL DEFAULT '0.00',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `changelog` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_free` tinyint(1) DEFAULT '0',
  `comment_disable` tinyint(1) DEFAULT '0',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `tags` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `temp_file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `audio_temp_file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_updated` tinyint(1) NOT NULL DEFAULT '0',
  `published_at` timestamp NULL DEFAULT NULL,
  `last_updated` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `sku` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'course',
  `learning_outcome` text COLLATE utf8mb4_unicode_ci,
  `software_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `software_edition` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lesson_count` int NOT NULL DEFAULT '0',
  `video_duration_seconds` int NOT NULL DEFAULT '0',
  `sequence_no` int NOT NULL DEFAULT '1',
  `stage_label` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `accent_color` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_products_status_category` (`status`,`category_id`),
  KEY `products_slug_index` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (14,1,0,3,5,'Beat Making Foundations - Getting Started','beat-making-foundations',120000.00000000,180000.00000000,NULL,NULL,NULL,NULL,'[{\"name\":\"Skill Stage\",\"value\":\"Level 1: Foundations\"},{\"name\":\"Software License Included\",\"value\":\"Yes (Confirmed)\"},{\"name\":\"Format\",\"value\":\"HD Video + Practice Resources + License Key\"},{\"name\":\"Language\",\"value\":\"English & Swahili\"}]',NULL,NULL,58,52,4.80,'Set up a simple beat session and understand the core building blocks.',NULL,0,0,1,'[\"Beat Making\",\"Rhythm\",\"Drums\",\"Foundations\"]',1,NULL,NULL,NULL,0,NULL,NULL,'2026-10-20 04:33:48','2026-10-23 20:09:03','AK23-COURSE-BEAT_MAKING_FOUNDATIONS','course','Set up a simple beat session and understand the core building blocks.','AK23 Drum Engine & Sampler','Essential Beat Production License',8,7200,1,'Getting Started','#F59E0B'),(26,1,0,1,0,'Tuned Plugins - Getting Started','tuned-plugins',120000.00000000,180000.00000000,NULL,NULL,NULL,NULL,'',NULL,NULL,3,0,0.00,'Build your first simple vocal-processing workflow.',NULL,0,0,1,NULL,1,NULL,NULL,NULL,0,NULL,NULL,'2026-10-21 08:29:56','2026-10-23 20:09:03','AK23-COURSE-TUNED_PLUGINS','course','Build your first simple vocal-processing workflow.','Tuned Pitch Suite','Perpetual Vocal Pitch License',8,7200,1,'Getting Started','#D9A742'),(27,1,0,1,0,'Cymatics Voxity - Beginner','cymatics-voxity',140000.00000000,210000.00000000,NULL,NULL,NULL,NULL,'',NULL,NULL,3,0,0.00,'Shape vocal tone, dynamics, and space with confidence.',NULL,0,0,1,NULL,1,NULL,NULL,NULL,0,NULL,NULL,'2026-10-21 08:29:56','2026-10-23 20:09:03','AK23-COURSE-CYMATICS_VOXITY','course','Shape vocal tone, dynamics, and space with confidence.','Cymatics Voxity','Multi-FX Studio License',8,7200,2,'Beginner','#DE784D'),(28,1,0,1,0,'Nuro Audio Xvox Pro - Developing Your Skills','nuro-audio-xvox-pro',175000.00000000,262500.00000000,NULL,NULL,NULL,NULL,'',NULL,NULL,0,0,0.00,'Balance compression, de-essing, tone, and effects.',NULL,0,0,1,NULL,1,NULL,NULL,NULL,0,NULL,NULL,'2026-10-21 08:29:56','2026-10-23 20:09:03','AK23-COURSE-NURO_AUDIO_XVOX_PRO','course','Balance compression, de-essing, tone, and effects.','Nuro Audio Xvox Pro','Commercial Vocal Strip License',8,7200,3,'Developing Your Skills','#9B77D2'),(29,1,0,1,0,'iZotope Nectar 4 - Intermediate','izotope-nectar-4',210000.00000000,315000.00000000,NULL,NULL,NULL,NULL,'',NULL,NULL,0,0,0.00,'Develop a more detailed vocal-processing workflow.',NULL,0,0,1,NULL,1,NULL,NULL,NULL,0,NULL,NULL,'2026-10-21 08:29:56','2026-10-23 20:09:03','AK23-COURSE-IZOTOPE_NECTAR_4','course','Develop a more detailed vocal-processing workflow.','iZotope Nectar 4 Pro','Vocal Assistant Edition',8,7200,4,'Intermediate','#ED7C66'),(30,1,0,1,0,'Waves Ultimate 17 - Advanced Chain Building','waves-ultimate-17',250000.00000000,375000.00000000,NULL,NULL,NULL,NULL,'',NULL,NULL,0,0,0.00,'Assemble a vocal chain using selected individual processors.',NULL,0,0,1,NULL,1,NULL,NULL,NULL,0,NULL,NULL,'2026-10-21 08:29:56','2026-10-23 20:09:03','AK23-COURSE-WAVES_ULTIMATE_17','course','Assemble a vocal chain using selected individual processors.','Waves 17 Studio Rack','Full Ultimate Plugin Collection',8,7200,5,'Advanced Chain Building','#4FA6C9'),(31,1,0,1,0,'Complete Vocal Mixing Series','complete-vocal-mixing-series',480000.00000000,720000.00000000,NULL,NULL,NULL,NULL,'',NULL,NULL,0,0,0.00,'All five courses and five matching software packages.',NULL,0,0,1,NULL,1,NULL,NULL,NULL,0,NULL,NULL,'2026-10-21 08:29:56','2026-10-23 20:09:03','AK23-BUNDLE-VOCAL','bundle','Master all stages in the Vocal Mixing Series','Complete Vocal Suite Bundle','All 5 Software Licenses & Presets Included',50,36000,99,'Complete Series','#FBAF43'),(32,1,0,2,0,'Fender Studio 8 - Getting Started','fender-studio-8',150000.00000000,225000.00000000,NULL,NULL,NULL,NULL,'',NULL,NULL,0,0,0.00,'Set up your first project, record audio, and complete a simple edit.',NULL,0,0,1,NULL,1,NULL,NULL,NULL,0,NULL,NULL,'2026-10-21 08:29:57','2026-10-23 20:09:03','AK23-COURSE-FENDER_STUDIO_8','course','Set up your first project, record audio, and complete a simple edit.','Fender Studio One 8','Standard Studio DAW License',8,7200,1,'Getting Started','#F0A62F'),(33,1,0,2,0,'Cubase - Beginner','cubase',190000.00000000,285000.00000000,NULL,NULL,NULL,NULL,'',NULL,NULL,0,0,0.00,'Record, arrange, edit, and export a clean audio project.',NULL,0,0,1,NULL,1,NULL,NULL,NULL,0,NULL,NULL,'2026-10-21 08:29:57','2026-10-23 20:09:03','AK23-COURSE-CUBASE','course','Record, arrange, edit, and export a clean audio project.','Steinberg Cubase 13 Pro','Pro Audio Editing License',8,7200,2,'Beginner','#3B82F6'),(34,1,0,2,0,'FL Studio - Beginner','fl-studio',165000.00000000,247500.00000000,NULL,NULL,NULL,NULL,'',NULL,NULL,0,0,0.00,'Navigate the workflow, arrange audio, and finish your first session.',NULL,0,0,1,NULL,1,NULL,NULL,NULL,0,NULL,NULL,'2026-10-21 08:29:57','2026-10-23 20:09:03','AK23-COURSE-FL_STUDIO','course','Navigate the workflow, arrange audio, and finish your first session.','Image-Line FL Studio 21','Signature Producer Edition',8,7200,3,'Beginner','#F97316'),(35,1,0,2,0,'Ableton Live - Developing Your Skills','ableton-live',220000.00000000,330000.00000000,NULL,NULL,NULL,NULL,'',NULL,NULL,0,0,0.00,'Work with Session and Arrangement views to build an audio project.',NULL,0,0,1,NULL,1,NULL,NULL,NULL,0,NULL,NULL,'2026-10-21 08:29:57','2026-10-23 20:09:03','AK23-COURSE-ABLETON_LIVE','course','Work with Session and Arrangement views to build an audio project.','Ableton Live 12','Suite Studio Edition',8,7200,4,'Developing Your Skills','#10B981'),(36,1,0,2,0,'Logic Pro - Developing Your Skills','logic-pro',180000.00000000,270000.00000000,NULL,NULL,NULL,NULL,'',NULL,NULL,0,0,0.00,'Record, edit, organize, and export audio in a clear Mac workflow.',NULL,0,0,1,NULL,1,NULL,NULL,NULL,0,NULL,NULL,'2026-10-21 08:29:57','2026-10-23 20:09:03','AK23-COURSE-LOGIC_PRO','course','Record, edit, organize, and export audio in a clear Mac workflow.','Apple Logic Pro','Full Studio Workstation License',8,7200,5,'Developing Your Skills','#8B5CF6'),(37,1,0,2,0,'Pro Tools - Studio Workflow','pro-tools',260000.00000000,390000.00000000,NULL,NULL,NULL,NULL,'',NULL,NULL,0,0,0.00,'Build a structured recording and editing workflow from start to export.',NULL,0,0,1,NULL,1,NULL,NULL,NULL,0,NULL,NULL,'2026-10-21 08:29:57','2026-10-23 20:09:03','AK23-COURSE-PRO_TOOLS','course','Build a structured recording and editing workflow from start to export.','Avid Pro Tools Studio','Industry Standard Perpetual License',8,7200,6,'Studio Workflow','#06B6D4'),(38,1,0,2,0,'Complete Audio Software Series','complete-audio-software-series',540000.00000000,810000.00000000,NULL,NULL,NULL,NULL,'',NULL,NULL,0,0,0.00,'All six courses and six matching software packages.',NULL,0,0,1,NULL,1,NULL,NULL,NULL,0,NULL,NULL,'2026-10-21 08:29:57','2026-10-23 20:09:03','AK23-BUNDLE-DAW','bundle','Master all stages in the Audio Editing Software Series','Complete Audio DAW Suite Bundle','All 6 Professional Workstations Included',50,36000,99,'Complete Series','#FBAF43'),(39,1,0,3,0,'Drum Programming - Beginner','drum-programming',135000.00000000,202500.00000000,NULL,NULL,NULL,NULL,'',NULL,NULL,0,0,0.00,'Build clear, energetic drum patterns with groove and variation.',NULL,0,0,1,NULL,1,NULL,NULL,NULL,0,NULL,NULL,'2026-10-21 08:29:57','2026-10-23 20:09:03','AK23-COURSE-DRUM_PROGRAMMING','course','Build clear, energetic drum patterns with groove and variation.','MPC Beat Groove Machine','Drum Synthesis & Sequencing Suite',8,7200,2,'Beginner','#EF4444'),(40,1,0,3,0,'Melodies & Chords - Building Musical Ideas','melodies-and-chords',145000.00000000,217500.00000000,NULL,NULL,NULL,NULL,'',NULL,NULL,0,0,0.00,'Create simple chord progressions and memorable melodic parts.',NULL,0,0,1,NULL,1,NULL,NULL,NULL,0,NULL,NULL,'2026-10-21 08:29:57','2026-10-23 20:09:03','AK23-COURSE-MELODIES_AND_CHORDS','course','Create simple chord progressions and memorable melodic parts.','PolySynth & Chord Engine','Harmonic Melody Generator License',8,7200,3,'Building Musical Ideas','#10B981'),(41,1,0,3,0,'Bass & 808s - Developing Your Sound','bass-and-808s',140000.00000000,210000.00000000,NULL,NULL,NULL,NULL,'',NULL,NULL,0,0,0.00,'Write bass parts that support the drums, melody, and energy of the beat.',NULL,0,0,1,NULL,1,NULL,NULL,NULL,0,NULL,NULL,'2026-10-21 08:29:57','2026-10-23 20:09:03','AK23-COURSE-BASS_AND_808S','course','Write bass parts that support the drums, melody, and energy of the beat.','SubLab 808 Bass Designer','Low-End Sound Synthesis License',8,7200,4,'Developing Your Sound','#6366F1'),(42,1,0,3,0,'Sampling & Sound Design - Creative Production','sampling-and-sound-design',160000.00000000,240000.00000000,NULL,NULL,NULL,NULL,'',NULL,NULL,0,0,0.00,'Shape samples and sounds into original parts that fit your production.',NULL,0,0,1,NULL,1,NULL,NULL,NULL,0,NULL,NULL,'2026-10-21 08:29:57','2026-10-23 20:09:03','AK23-COURSE-SAMPLING_AND_SOUND_DESIGN','course','Shape samples and sounds into original parts that fit your production.','Serum Creative Synth & Sampler','Wavetable Sound Design License',8,7200,5,'Creative Production','#8B5CF6'),(43,1,0,3,0,'Arrangement & Beat Mix - Complete Your Beat','arrangement-and-beat-mix',170000.00000000,255000.00000000,NULL,NULL,NULL,NULL,'',NULL,NULL,0,0,0.00,'Turn a loop into a complete arrangement and prepare a clear final mix.',NULL,0,0,1,NULL,1,NULL,NULL,NULL,0,NULL,NULL,'2026-10-21 08:29:57','2026-10-23 20:09:03','AK23-COURSE-ARRANGEMENT_AND_BEAT_MIX','course','Turn a loop into a complete arrangement and prepare a clear final mix.','AK23 Master Strip & Maximizer','Final Beat Mix & Master License',8,7200,6,'Complete Your Beat','#F97316'),(44,1,0,3,0,'Complete Beat Making Series','complete-beat-making-series',520000.00000000,780000.00000000,'6adbe64c4734a1792796236.png',NULL,NULL,NULL,'','6adbe65102bec1792796241.png',NULL,0,0,0.00,'All six courses and six matching software packages.',NULL,1,0,1,'[]',1,'6adbe6515de701792796241.rar',NULL,NULL,0,NULL,NULL,'2026-10-21 08:29:57','2026-10-23 20:09:03','AK23-BUNDLE-BEATS','bundle','Master all stages in the Beat Making Series','Complete Beat Making Suite','All Production Tools & Drum Kits Included',50,36000,99,'Complete Series','#F0A62F');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ratings`
--

DROP TABLE IF EXISTS `ratings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ratings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` int NOT NULL,
  `product_count` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ratings`
--

LOCK TABLES `ratings` WRITE;
/*!40000 ALTER TABLE `ratings` DISABLE KEYS */;
/*!40000 ALTER TABLE `ratings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `refund_activities`
--

DROP TABLE IF EXISTS `refund_activities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `refund_activities` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `refund_request_id` int NOT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `seller_id` int DEFAULT NULL,
  `buyer_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `refund_activities`
--

LOCK TABLES `refund_activities` WRITE;
/*!40000 ALTER TABLE `refund_activities` DISABLE KEYS */;
/*!40000 ALTER TABLE `refund_activities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `refund_requests`
--

DROP TABLE IF EXISTS `refund_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `refund_requests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_item_id` int unsigned NOT NULL,
  `reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` tinyint NOT NULL DEFAULT '0',
  `user_id` int NOT NULL DEFAULT '0',
  `buyer_id` int NOT NULL DEFAULT '0',
  `amount` double(28,8) NOT NULL DEFAULT '0.00000000',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `refund_requests`
--

LOCK TABLES `refund_requests` WRITE;
/*!40000 ALTER TABLE `refund_requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `refund_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reported_reviews`
--

DROP TABLE IF EXISTS `reported_reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reported_reviews` (
  `id` int NOT NULL AUTO_INCREMENT,
  `review_id` int unsigned NOT NULL DEFAULT '0',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reported_reviews`
--

LOCK TABLES `reported_reviews` WRITE;
/*!40000 ALTER TABLE `reported_reviews` DISABLE KEYS */;
/*!40000 ALTER TABLE `reported_reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reported_reviews_attachments`
--

DROP TABLE IF EXISTS `reported_reviews_attachments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reported_reviews_attachments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `reported_reviews_id` int unsigned NOT NULL DEFAULT '0',
  `attachment` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reported_reviews_attachments`
--

LOCK TABLES `reported_reviews_attachments` WRITE;
/*!40000 ALTER TABLE `reported_reviews_attachments` DISABLE KEYS */;
/*!40000 ALTER TABLE `reported_reviews_attachments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `review_categories`
--

DROP TABLE IF EXISTS `review_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `review_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `review_categories`
--

LOCK TABLES `review_categories` WRITE;
/*!40000 ALTER TABLE `review_categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `review_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reviews` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL DEFAULT '0',
  `author_id` int unsigned NOT NULL DEFAULT '0',
  `product_id` int unsigned NOT NULL DEFAULT '0',
  `review_category_id` int unsigned NOT NULL DEFAULT '0',
  `rating` int NOT NULL DEFAULT '0',
  `review` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_reported` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reviews`
--

LOCK TABLES `reviews` WRITE;
/*!40000 ALTER TABLE `reviews` DISABLE KEYS */;
INSERT INTO `reviews` VALUES (1,1,1,26,0,5,'This vocal mixing masterclass completely leveled up my mixing workflow. The dynamic EQ balance and parallel compression setup on lead vocals gave my tracks the commercial radio presence I was missing!',0,'2026-09-19 15:01:06','2026-09-19 15:01:06'),(2,2,1,27,0,5,'Outstanding course and preset bundle! The vocal stems included in the downloads allowed me to follow along step-by-step. The de-essing and Auto-Tune formant tips are absolute gold.',0,'2026-09-07 15:01:06','2026-09-07 15:01:06'),(3,3,1,28,0,5,'Best Afrobeats and modern rap vocal mixing tutorial on the internet. AK23 breaks down spatial acoustics, stereo delay throws, and reverb ducking so clearly. 10/10.',0,'2026-09-12 15:01:06','2026-09-12 15:01:06'),(4,4,1,29,0,4,'Very comprehensive walkthrough of professional vocal chains. My vocals now sit right on top of heavy 808s and kick drums without harsh sibilance or low-mid mud.',0,'2026-10-09 15:01:06','2026-10-09 15:01:06'),(5,5,1,30,0,5,'The DAW templates and plugin presets alone are worth double the price. Opened the project in FL Studio and had a finished commercial vocal mix in under 30 minutes.',0,'2026-10-12 15:01:06','2026-10-12 15:01:06'),(6,6,1,31,0,5,'Clear, concise, and straight to the point. No fluff, just practical professional vocal production techniques from a real studio veteran.',0,'2026-10-08 15:01:06','2026-10-08 15:01:06'),(7,7,1,32,0,4,'Incredible insight on vocal saturation and harmonics. The license key for the bundled plugin suite arrived in my student dashboard immediately after checkout.',0,'2026-10-03 15:01:06','2026-10-03 15:01:06'),(8,1,1,33,0,5,'My clients have already noticed a dramatic improvement in clarity and warmth on their hook vocals. Essential investment for any serious recording and mixing engineer.',0,'2026-10-04 15:01:06','2026-10-04 15:01:06');
/*!40000 ALTER TABLE `reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sub_categories`
--

DROP TABLE IF EXISTS `sub_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sub_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` int NOT NULL DEFAULT '0',
  `form_id` int NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sub_categories`
--

LOCK TABLES `sub_categories` WRITE;
/*!40000 ALTER TABLE `sub_categories` DISABLE KEYS */;
INSERT INTO `sub_categories` VALUES (1,'Vocal Mixing Courses',1,0,1,'2026-10-20 04:33:48','2026-10-20 04:33:48'),(2,'Vocal Production Bundles',1,0,1,'2026-10-20 04:33:48','2026-10-20 04:33:48'),(3,'Audio Software Courses',2,0,1,'2026-10-20 04:33:48','2026-10-20 04:33:48'),(4,'Audio Software Bundles',2,0,1,'2026-10-20 04:33:48','2026-10-20 04:33:48'),(5,'Beat Making Courses',3,0,1,'2026-10-20 04:33:48','2026-10-20 04:33:48'),(6,'Beat Making Bundles',3,0,1,'2026-10-20 04:33:48','2026-10-20 04:33:48');
/*!40000 ALTER TABLE `sub_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subscribers`
--

DROP TABLE IF EXISTS `subscribers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `subscribers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subscribers`
--

LOCK TABLES `subscribers` WRITE;
/*!40000 ALTER TABLE `subscribers` DISABLE KEYS */;
/*!40000 ALTER TABLE `subscribers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `support_attachments`
--

DROP TABLE IF EXISTS `support_attachments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `support_attachments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `support_message_id` int unsigned NOT NULL DEFAULT '0',
  `attachment` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `support_attachments`
--

LOCK TABLES `support_attachments` WRITE;
/*!40000 ALTER TABLE `support_attachments` DISABLE KEYS */;
/*!40000 ALTER TABLE `support_attachments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `support_messages`
--

DROP TABLE IF EXISTS `support_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `support_messages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `support_ticket_id` int unsigned NOT NULL DEFAULT '0',
  `admin_id` int unsigned NOT NULL DEFAULT '0',
  `message` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `support_messages`
--

LOCK TABLES `support_messages` WRITE;
/*!40000 ALTER TABLE `support_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `support_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `support_tickets`
--

DROP TABLE IF EXISTS `support_tickets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `support_tickets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT '0',
  `name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ticket` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: Open, 1: Answered, 2: Replied, 3: Closed',
  `priority` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 = Low, 2 = medium, 3 = heigh',
  `last_reply` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `support_tickets`
--

LOCK TABLES `support_tickets` WRITE;
/*!40000 ALTER TABLE `support_tickets` DISABLE KEYS */;
/*!40000 ALTER TABLE `support_tickets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_migrations`
--

DROP TABLE IF EXISTS `system_migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `system_migrations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  `executed_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `system_migrations_migration_unique` (`migration`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_migrations`
--

LOCK TABLES `system_migrations` WRITE;
/*!40000 ALTER TABLE `system_migrations` DISABLE KEYS */;
INSERT INTO `system_migrations` VALUES (1,'2026_10_21_000001_create_system_migrations_registry',13,'2026-10-21 17:53:09'),(2,'2026_10_21_000002_add_v13_general_settings_attributes',13,'2026-10-21 17:53:10'),(3,'2026_10_21_000003_add_v13_high_concurrency_indexes',13,'2026-10-21 17:53:10');
/*!40000 ALTER TABLE `system_migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL DEFAULT '0',
  `amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `charge` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `post_balance` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `trx_type` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trx` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `details` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remark` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_transactions_user_type` (`user_id`,`trx_type`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transactions`
--

LOCK TABLES `transactions` WRITE;
/*!40000 ALTER TABLE `transactions` DISABLE KEYS */;
INSERT INTO `transactions` VALUES (1,8,140000.00000000,0.00000000,140000.00000000,'+','AK-IWQZLBTS','Payment for Purchase Via Selcom Mobile Money','payment','2026-10-21 08:46:44','2026-10-21 08:46:44'),(2,8,140000.00000000,0.00000000,0.00000000,'-','AK-IWQZLBTS','Payment for Purchase Item','purchase','2026-10-21 08:46:44','2026-10-21 08:46:44'),(3,8,140000.00000000,0.00000000,140000.00000000,'+','AK-OZZRH1QE','Payment for Purchase Via Selcom Mobile Money','payment','2026-10-21 08:48:22','2026-10-21 08:48:22'),(4,8,140000.00000000,0.00000000,0.00000000,'-','AK-OZZRH1QE','Payment for Purchase Item','purchase','2026-10-21 08:48:22','2026-10-21 08:48:22'),(5,9,140000.00000000,0.00000000,140000.00000000,'+','AK-V6SQNIMK','Payment for Purchase Via Selcom Mobile Money','payment','2026-10-21 09:05:10','2026-10-21 09:05:10'),(6,9,140000.00000000,0.00000000,0.00000000,'-','AK-V6SQNIMK','Payment for Purchase Item','purchase','2026-10-21 09:05:10','2026-10-21 09:05:10'),(7,9,120000.00000000,0.00000000,120000.00000000,'+','AK-YRFU9LQE','Payment for Purchase Via ','payment','2026-10-21 10:19:34','2026-10-21 10:19:34'),(8,9,120000.00000000,0.00000000,0.00000000,'-','AK-YRFU9LQE','Payment for Purchase Item','purchase','2026-10-21 10:19:34','2026-10-21 10:19:34'),(9,9,120000.00000000,0.00000000,120000.00000000,'+','AK-C1EWR85E','Payment for Purchase Via ','payment','2026-10-21 10:20:31','2026-10-21 10:20:31'),(10,9,120000.00000000,0.00000000,0.00000000,'-','AK-C1EWR85E','Payment for Purchase Item','purchase','2026-10-21 10:20:31','2026-10-21 10:20:31'),(11,9,120000.00000000,0.00000000,120000.00000000,'+','AK-HSDXVYVO','Payment for Purchase Via ','payment','2026-10-21 10:36:13','2026-10-21 10:36:13'),(12,9,120000.00000000,0.00000000,0.00000000,'-','AK-HSDXVYVO','Payment for Purchase Item','purchase','2026-10-21 10:36:13','2026-10-21 10:36:13');
/*!40000 ALTER TABLE `transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `update_logs`
--

DROP TABLE IF EXISTS `update_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `update_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `version` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `update_log` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `update_logs`
--

LOCK TABLES `update_logs` WRITE;
/*!40000 ALTER TABLE `update_logs` DISABLE KEYS */;
INSERT INTO `update_logs` VALUES (1,'13.0.0','[\"Upgraded Laravel 12 core architecture to v13 Enterprise Modernization Suite.\",\"Created dedicated `system_migrations` registry table for declarative schema evolution tracking.\",\"Added high-concurrency composite indexes across `orders`, `order_items`, `products`, `transactions`, and `admin_notifications`.\",\"Configured REST & RPC API enterprise rate limiting and Webhook verification infrastructure.\",\"Synchronized unified AK23 dark warm-gold (#FBAF43) administrative interface.\",\"Recompiled cache, views, and route trees for sub-millisecond execution.\"]','2026-10-21 17:53:17','2026-10-21 17:53:17'),(2,'13.0.0','[\"Upgraded Laravel 12 core architecture to v13 Enterprise Modernization Suite.\",\"Created dedicated `system_migrations` registry table for declarative schema evolution tracking.\",\"Added high-concurrency composite indexes across `orders`, `order_items`, `products`, `transactions`, and `admin_notifications`.\",\"Configured REST & RPC API enterprise rate limiting and Webhook verification infrastructure.\",\"Synchronized unified AK23 dark warm-gold (#FBAF43) administrative interface.\",\"Recompiled cache, views, and route trees for sub-millisecond execution.\"]','2026-10-21 17:55:01','2026-10-21 17:55:01'),(3,'13.0.0','[\"Upgraded Laravel 12 core architecture to v13 Enterprise Modernization Suite.\",\"Created dedicated `system_migrations` registry table for declarative schema evolution tracking.\",\"Added high-concurrency composite indexes across `orders`, `order_items`, `products`, `transactions`, and `admin_notifications`.\",\"Configured REST & RPC API enterprise rate limiting and Webhook verification infrastructure.\",\"Synchronized unified AK23 dark warm-gold (#FBAF43) administrative interface.\",\"Recompiled cache, views, and route trees for sub-millisecond execution.\"]','2026-10-21 18:02:06','2026-10-21 18:02:06');
/*!40000 ALTER TABLE `update_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_follows`
--

DROP TABLE IF EXISTS `user_follows`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_follows` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `follower_id` int unsigned NOT NULL DEFAULT '0',
  `following_id` int unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_follows`
--

LOCK TABLES `user_follows` WRITE;
/*!40000 ALTER TABLE `user_follows` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_follows` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_logins`
--

DROP TABLE IF EXISTS `user_logins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_logins` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL DEFAULT '0',
  `user_ip` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_code` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `longitude` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `browser` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `os` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_logins`
--

LOCK TABLES `user_logins` WRITE;
/*!40000 ALTER TABLE `user_logins` DISABLE KEYS */;
INSERT INTO `user_logins` VALUES (1,1,'127.0.0.1','','','','','','Chrome','Windows 10','2026-10-19 15:08:07','2026-10-19 15:08:07'),(2,1,'127.0.0.1','','','','','','Chrome','Windows 10','2026-10-20 00:22:07','2026-10-20 00:22:07'),(3,10,'127.0.0.1','','','','','','Chrome','Windows 10','2026-10-21 11:12:22','2026-10-21 11:12:22'),(4,11,'127.0.0.1','','','','','','Chrome','Windows 10','2026-10-21 13:14:24','2026-10-21 13:14:24'),(5,12,'127.0.0.1','','','','','','Chrome','Windows 10','2026-10-21 15:36:41','2026-10-21 15:36:41'),(6,12,'127.0.0.1','','','','','','Chrome','Windows 10','2026-10-22 17:23:04','2026-10-22 17:23:04');
/*!40000 ALTER TABLE `user_logins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `firstname` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lastname` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `dial_code` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_code` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ref_by` int unsigned NOT NULL DEFAULT '0',
  `balance` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `total_review` int NOT NULL DEFAULT '0',
  `avg_rating` decimal(5,2) NOT NULL DEFAULT '0.00',
  `total_follower` int NOT NULL DEFAULT '0',
  `total_following` int NOT NULL DEFAULT '0',
  `total_sold` int NOT NULL DEFAULT '0',
  `total_sold_amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0: banned, 1: active',
  `kyc_data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `kyc_rejection_reason` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kv` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: KYC Unverified, 2: KYC pending, 1: KYC verified',
  `ev` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: email unverified, 1: email verified',
  `sv` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: mobile unverified, 1: mobile verified',
  `profile_complete` tinyint(1) NOT NULL DEFAULT '0',
  `ver_code` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'stores verification code',
  `ver_code_send_at` datetime DEFAULT NULL COMMENT 'verification send time',
  `ts` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: 2fa off, 1: 2fa on',
  `tv` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0: 2fa unverified, 1: 2fa verified',
  `tsc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ban_reason` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `thumbnail` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_author` tinyint NOT NULL DEFAULT '0',
  `is_author_featured` tinyint(1) NOT NULL DEFAULT '0',
  `author_info` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cover_img` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bio` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `show_country` tinyint(1) NOT NULL DEFAULT '0',
  `login_by` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `email_settings` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `social_media_settings` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`,`email`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Amina','Mushi','amina','amina@example.com','255','TZ','255754123456',0,0.00000000,'$2y$12$Vbm2BBxrX17ZgGqh4F/BmOOopRTABKoIUBhB8Dg19gK4kTDRCkGMu','Tanzania','Dar es Salaam',NULL,NULL,NULL,0,0.00,0,0,0,0.00000000,1,NULL,NULL,0,1,1,1,NULL,NULL,0,1,NULL,NULL,NULL,NULL,NULL,'2026-08-19 14:47:57','2026-10-20 04:33:48',NULL,0,0,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL),(2,'John','Kweka','johnkweka','john.kweka@example.com','255','TZ','255713987654',0,0.00000000,'$2y$12$pTKsOqLKZnG94hm7pvm3Re5.Er1qRDMRUkwHy3X5jw34mM6NeZVX.','Tanzania','Arusha',NULL,NULL,NULL,0,0.00,0,0,0,0.00000000,1,NULL,NULL,0,1,1,1,NULL,NULL,0,1,NULL,NULL,NULL,NULL,NULL,'2026-08-19 14:47:57','2026-10-20 04:33:48',NULL,0,0,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL),(3,'Rohema','Ngowi','rohema','rohema.ngowi@example.com','255','TZ','255784556677',0,0.00000000,'$2y$12$IhdxVNIeA0hvOOA7eRSZiuoxr8JzdPpk7vcAsx2c9Z8Xe/g/yWB2e','Tanzania','Moshi',NULL,NULL,NULL,0,0.00,0,0,0,0.00000000,1,NULL,NULL,0,1,1,1,NULL,NULL,0,1,NULL,NULL,NULL,NULL,NULL,'2026-08-19 14:47:58','2026-10-20 04:33:48',NULL,0,0,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL),(4,'David','Kimaro','davidkimaro','david.kimaro@example.com','255','TZ','255762334455',0,0.00000000,'$2y$12$aVdY4Z294//X6NVzj3449u4YJHPzmzDRWkqIelRQKSmbXuEk588Su','Tanzania','Dar es Salaam',NULL,NULL,NULL,0,0.00,0,0,0,0.00000000,1,NULL,NULL,0,1,1,1,NULL,NULL,0,1,NULL,NULL,NULL,NULL,NULL,'2026-08-19 14:47:58','2026-10-20 04:33:48',NULL,0,0,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL),(5,'Fatma','Mohamed','fatma','fatma.mohamed@example.com','255','TZ','255773112233',0,0.00000000,'$2y$12$YNLJMn0oZmPuF0MDdzAU3eBkJKOqhkUm65qx4tlpEKD8AGx9m1vsi','Tanzania','Zanzibar',NULL,NULL,NULL,0,0.00,0,0,0,0.00000000,1,NULL,NULL,0,1,1,1,NULL,NULL,0,1,NULL,NULL,NULL,NULL,NULL,'2026-08-19 14:47:59','2026-10-20 04:33:48',NULL,0,0,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL),(6,'Emmanuel','Mtei','emmanuel','emmanuel.mtei@example.com','255','TZ','255755998877',0,0.00000000,'$2y$12$61kF5aAgRRfdAnCB4pjAw.TrbrEbLq5I410jxgtdTUrbfdpT5uXb.','Tanzania','Mwanza',NULL,NULL,NULL,0,0.00,0,0,0,0.00000000,1,NULL,NULL,0,1,1,1,NULL,NULL,0,1,NULL,NULL,NULL,NULL,NULL,'2026-08-19 14:47:59','2026-10-20 04:33:48',NULL,0,0,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL),(7,'Student','User','company752','company@example.com',NULL,NULL,NULL,0,0.00000000,'$2y$12$HnDvaIVuuJKF.0Js6MZch.gH7YAWElclo7MTbjP1NpLDHC86oQwg.',NULL,NULL,NULL,NULL,NULL,0,0.00,0,0,0,0.00000000,1,NULL,NULL,0,1,1,0,NULL,NULL,0,1,NULL,NULL,NULL,NULL,NULL,'2026-10-20 09:21:53','2026-10-20 09:21:53',NULL,0,0,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL),(8,'Student','User','teststudent595','teststudent@example.com',NULL,NULL,'255754123456',0,0.00000000,'$2y$12$iLegx2kKTymVd8UMRKzvGOHU7mRQ.Qf.7/pYQUcsvvl/3B.7GZ0Rq',NULL,NULL,NULL,NULL,NULL,0,0.00,0,0,0,0.00000000,1,NULL,NULL,0,1,1,0,NULL,NULL,0,1,NULL,NULL,NULL,NULL,NULL,'2026-10-21 08:44:42','2026-10-21 08:48:22',NULL,0,0,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL),(9,'Student','User','alexkaseja','test@example.com','93','AF','255747003357',0,0.00000000,'$2y$12$3Bih1wGq4qsSFl8ucPcDGup3H2qKu5/aGcoHg1YXwsnGDx5kqtXD.','Afghanistan','Dar es Salaam',NULL,NULL,'Dar es Salaam',0,0.00,0,0,0,0.00000000,1,NULL,NULL,0,1,1,1,NULL,NULL,0,1,NULL,NULL,NULL,NULL,NULL,'2026-10-21 08:50:53','2026-10-21 10:37:04',NULL,0,0,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL),(10,'Alex','M. Kaseja','info326','info@wolinet.com',NULL,NULL,'255747003357',0,0.00000000,'$2y$12$lnru7DUU7xr4ffl/UL3MVud2TNCcsOoLeCQJgxhF87pF9VaDbs1HS','Tanzania','Dar es Salaam',NULL,'14111','Ali Hassan Mwinyi Rd, Kijitonyama',0,0.00,0,0,0,0.00000000,1,NULL,NULL,0,1,1,1,NULL,NULL,0,1,NULL,NULL,NULL,NULL,NULL,'2026-10-21 09:19:56','2026-10-21 11:08:39',NULL,0,0,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL),(11,'John','Doe','testuser123','johndoe156@example.com','93','AF','123456789',0,0.00000000,'$2y$12$NBVxzEYSUXaHWKFEqZEqOew0WY86xY4.gmtoL0WIw0fZLWLM1jn/6','Afghanistan','Dar es Salaam','Dar es Salaam','00100','123 Studio St',0,0.00,0,0,0,0.00000000,1,NULL,NULL,1,1,1,1,NULL,NULL,0,1,NULL,NULL,NULL,NULL,NULL,'2026-10-21 13:14:24','2026-10-21 13:14:53',NULL,0,0,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL),(12,'Renatus','Bernardo','halima','bernadorenatus32@gmail.com','1','US','967899337',0,0.00000000,'$2y$12$Sm8q1ciAIVdl6ABzegkEh.virDEJP8WmZGAxW9Nb5lr76AnWGM0hG','United States','Vernon','Connecticut','06066','Dart Road 12',0,0.00,0,0,0,0.00000000,1,NULL,NULL,1,1,1,1,NULL,NULL,0,1,NULL,NULL,NULL,NULL,NULL,'2026-10-21 15:36:41','2026-10-23 19:46:24',NULL,0,0,NULL,NULL,NULL,'Music Producer & Sound Engineer.',0,NULL,NULL,NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `withdrawals`
--

DROP TABLE IF EXISTS `withdrawals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `withdrawals` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `method_id` int unsigned NOT NULL DEFAULT '0',
  `user_id` bigint unsigned NOT NULL DEFAULT '0',
  `amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `currency` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rate` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `charge` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `trx` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `final_amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `after_charge` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `withdraw_information` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `admin_feedback` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `withdrawals_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `withdrawals`
--

LOCK TABLES `withdrawals` WRITE;
/*!40000 ALTER TABLE `withdrawals` DISABLE KEYS */;
/*!40000 ALTER TABLE `withdrawals` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-10-24 11:58:33

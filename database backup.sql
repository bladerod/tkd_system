/*
SQLyog Ultimate v10.00 Beta1
MySQL - 5.5.5-10.4.32-MariaDB : Database - db-tkd
*********************************************************************
*/


/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`db-tkd` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

USE `db-tkd`;

/*Table structure for table `announcements` */

DROP TABLE IF EXISTS `announcements`;

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_by_user_id` int(11) NOT NULL,
  `target_type` enum('all','class','belt','branch') NOT NULL,
  `class_id` int(11) DEFAULT NULL,
  `belt_level` varchar(150) DEFAULT NULL,
  `branch_id` int(10) DEFAULT NULL,
  `channel` varchar(150) DEFAULT NULL,
  `title` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `publish_date` date NOT NULL,
  `expire_date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `created_by_user_id` (`created_by_user_id`),
  KEY `class_id` (`class_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `announcements` */

insert  into `announcements`(`id`,`created_by_user_id`,`target_type`,`class_id`,`belt_level`,`branch_id`,`channel`,`title`,`message`,`publish_date`,`expire_date`) values (1,7,'class',1,'',0,'','testing','this message is a testing','2026-03-10','2026-03-11'),(6,7,'all',NULL,NULL,NULL,'SMS','testing','testiing','2026-03-19','2026-03-20'),(7,7,'all',NULL,NULL,NULL,'SMS','testing','testiing','2026-03-19','2026-03-20'),(8,7,'belt',NULL,'Blue Belt',NULL,'App,SMS,Email','tesingdawdwadwa','tokidawida','2026-03-19','2026-03-20');

/*Table structure for table `attendance_logs` */

DROP TABLE IF EXISTS `attendance_logs`;

CREATE TABLE `attendance_logs` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `student_id` int(10) NOT NULL,
  `class_session_id` int(10) NOT NULL,
  `checkin_time` datetime NOT NULL,
  `checkout_time` datetime NOT NULL,
  `method` enum('face','qr','manual') DEFAULT NULL,
  `confidence_score` varchar(50) NOT NULL,
  `recorded_by_user_id` int(10) NOT NULL,
  `device_id` int(10) NOT NULL,
  `status` int(1) NOT NULL DEFAULT 1,
  `attendance_status` enum('present','late','absent','excused') NOT NULL DEFAULT 'present',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `attendance_logs` */

insert  into `attendance_logs`(`id`,`student_id`,`class_session_id`,`checkin_time`,`checkout_time`,`method`,`confidence_score`,`recorded_by_user_id`,`device_id`,`status`,`attendance_status`) values (1,1,201,'2026-03-10 08:00:15','2026-03-10 09:30:20','face','98.7',2,1,1,'present'),(2,2,201,'2026-03-10 08:02:40','2026-03-10 09:28:55','qr','',2,1,1,'present'),(3,3,201,'2026-03-10 08:10:00','2026-03-10 09:35:10','manual','',1,0,1,'present'),(4,4,202,'2026-03-11 10:00:05','2026-03-11 11:30:00','face','95.4',3,2,1,'present'),(5,5,202,'2026-03-11 10:01:30','2026-03-11 11:29:45','qr','',3,2,1,'present'),(6,6,202,'2026-03-11 10:12:20','0000-00-00 00:00:00','manual','',1,0,1,'present'),(7,7,203,'2026-03-12 13:00:10','2026-03-12 14:30:00','face','99.0',2,1,1,'present'),(8,8,203,'2026-03-12 13:03:25','2026-03-12 14:28:50','qr','',2,1,1,'present'),(9,9,203,'2026-03-12 13:15:00','2026-03-12 14:35:00','manual','',1,0,1,'present'),(10,10,204,'2026-03-13 15:00:00','2026-03-13 16:30:30','face','94.2',3,2,1,'present'),(11,11,204,'2026-03-13 15:05:45','2026-03-13 16:28:15','qr','',3,2,1,'present'),(12,12,204,'2026-03-13 15:20:00','0000-00-00 00:00:00','manual','',1,0,1,'present'),(13,1,1,'2026-03-28 07:50:06','2026-03-28 07:50:06','manual','100',24,0,1,'present'),(14,2,2,'2026-03-30 03:12:30','2026-03-30 03:12:30','manual','100',24,0,1,'late'),(15,5,27,'2026-03-28 09:46:39','2026-03-28 09:46:39','manual','100',24,0,1,'late'),(16,2,27,'2026-03-28 09:46:39','2026-03-28 09:46:39','manual','100',24,0,1,'late'),(17,1,27,'2026-03-28 09:46:39','2026-03-28 09:46:39','manual','100',24,0,1,'late'),(18,6,27,'2026-03-28 09:46:39','2026-03-28 09:46:39','manual','100',24,0,1,'absent'),(21,1,30,'2026-03-30 05:50:22','2026-03-30 05:50:22','manual','100',24,0,1,'present'),(22,2,30,'2026-03-30 05:50:22','2026-03-30 05:50:22','manual','100',24,0,1,'present'),(23,5,30,'2026-03-30 05:50:22','2026-03-30 05:50:22','manual','100',24,0,1,'present'),(24,2,31,'2026-03-30 05:50:03','2026-03-30 05:50:03','manual','100',24,0,1,'present'),(25,6,31,'2026-03-30 05:50:03','2026-03-30 05:50:03','manual','100',24,0,1,'present'),(26,5,31,'2026-03-30 05:50:03','2026-03-30 05:50:03','manual','100',24,0,1,'present'),(28,7,32,'2026-03-30 15:01:54','2026-03-30 15:01:54','manual','100',24,0,1,'present'),(30,7,33,'2026-03-31 14:38:47','2026-03-31 14:38:47','manual','100',24,0,1,'present');

/*Table structure for table `audit_logs` */

DROP TABLE IF EXISTS `audit_logs`;

CREATE TABLE `audit_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `action` varchar(100) NOT NULL,
  `entity` varchar(100) NOT NULL,
  `entity_id` int(11) NOT NULL,
  `old_value` text DEFAULT NULL,
  `new_value` text DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `entity_id` (`entity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `audit_logs` */

/*Table structure for table `belt_exam_results` */

DROP TABLE IF EXISTS `belt_exam_results`;

CREATE TABLE `belt_exam_results` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `exam_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `score` decimal(5,2) NOT NULL,
  `result` enum('pass','fail','conditional') NOT NULL,
  `remarks` text DEFAULT NULL,
  `approved_by` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `exam_id` (`exam_id`),
  KEY `student_id` (`student_id`),
  KEY `approved_by` (`approved_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `belt_exam_results` */

/*Table structure for table `belt_exams` */

DROP TABLE IF EXISTS `belt_exams`;

CREATE TABLE `belt_exams` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `exam_date` date NOT NULL,
  `belt_level` varchar(50) NOT NULL,
  `class_id` int(11) NOT NULL,
  `chief_instructor_id` int(11) NOT NULL,
  `status` enum('scheduled','ongoing','completed','cancelled') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `class_id` (`class_id`),
  KEY `chief_instructor_id` (`chief_instructor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `belt_exams` */

/*Table structure for table `belt_levels` */

DROP TABLE IF EXISTS `belt_levels`;

CREATE TABLE `belt_levels` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `rank_order` int(11) NOT NULL,
  `color_code` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `belt_levels` */

insert  into `belt_levels`(`id`,`name`,`rank_order`,`color_code`) values (1,'White',1,'#FFFFFF'),(2,'White-Yellow',2,'#FFFACD'),(3,'Yellow',3,'#FFFF00'),(4,'Yellow-Green',4,'#ADFF2F'),(5,'Green',5,'#008000'),(6,'Green-Blue',6,'#00CED1'),(7,'Blue',7,'#0000FF'),(8,'Blue-Red',8,'#8A2BE2'),(9,'Red',9,'#FF0000'),(10,'Red-Black',10,'#8B0000'),(11,'Black',11,'#000000');

/*Table structure for table `billing_rules` */

DROP TABLE IF EXISTS `billing_rules`;

CREATE TABLE `billing_rules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `monthly_fee` decimal(10,2) NOT NULL DEFAULT 0.00,
  `enrollment_fees` decimal(10,2) DEFAULT 0.00,
  `uniform_fees` decimal(10,2) DEFAULT 0.00,
  `belt_promotion_fees` decimal(10,2) DEFAULT 0.00,
  `competition_fees` decimal(10,2) DEFAULT 0.00,
  `billing_cycle` enum('Monthly','Quarterly') DEFAULT 'Monthly',
  `due_date_rule` varchar(50) DEFAULT '1st of Month',
  `grace_period` int(11) DEFAULT 0,
  `late_fees_type` enum('Fixed','Percentage') DEFAULT 'Fixed',
  `late_fee_amount` varchar(20) DEFAULT NULL,
  `allow_partial_payment` tinyint(1) DEFAULT 1,
  `auto_mark_overdue` tinyint(1) DEFAULT 1,
  `auto_generate_monthly_invoice` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `billing_rules` */

insert  into `billing_rules`(`id`,`monthly_fee`,`enrollment_fees`,`uniform_fees`,`belt_promotion_fees`,`competition_fees`,`billing_cycle`,`due_date_rule`,`grace_period`,`late_fees_type`,`late_fee_amount`,`allow_partial_payment`,`auto_mark_overdue`,`auto_generate_monthly_invoice`,`created_at`,`updated_at`) values (1,'123.00','123.00','123.00','123.00','123.00','Quarterly','5th of Month',3,'Percentage','30',0,0,0,'2026-03-23 14:58:54','2026-03-23 16:08:54');

/*Table structure for table `branches` */

DROP TABLE IF EXISTS `branches`;

CREATE TABLE `branches` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `province` varchar(255) DEFAULT NULL,
  `mobile` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `branches_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `branches` */

insert  into `branches`(`id`,`name`,`code`,`address`,`city`,`province`,`mobile`,`email`,`status`,`created_at`,`updated_at`) values (1,'Quezon City Branch','BR001','123 Katipunan Ave','Quezon City','Metro Manila','09171234567','maria.santos@email.com','active','2026-03-01 08:00:00','2026-03-01 08:00:00'),(2,'Manila Branch','BR002','45 Mabini St','Manila','Metro Manila','09181234568','juan.delacruz@email.com','active','2026-03-02 09:15:00','2026-03-02 09:15:00'),(3,'Cebu Branch','BR003','678 Rizal Blvd','Cebu City','Cebu','09221234569','liza.reyes@email.com','active','2026-03-03 10:30:00','2026-03-05 14:20:00'),(4,'Davao Branch','BR004','89 Bonifacio St','Davao City','Davao del Sur','09331234570','roberto.tan@email.com','active','2026-03-04 11:45:00','2026-03-04 11:45:00'),(5,'Iloilo Branch','BR005','12 Lopez Jaena St','Iloilo City','Iloilo','09441234571','angela.mendoza@email.com','active','2026-03-05 13:00:00','2026-03-05 13:00:00'),(7,'Muntinlupa Branch','BR006','Blk 1 lot 2','muntinlupa city','Metro manila','0938292821','testing@gmail.com','active','2026-03-23 14:18:39','2026-03-23 14:18:39');

/*Table structure for table `brandings` */

DROP TABLE IF EXISTS `brandings`;

CREATE TABLE `brandings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `logo_path` varchar(255) DEFAULT NULL,
  `logo_filename` varchar(255) DEFAULT NULL,
  `certificate_header_text` varchar(255) DEFAULT NULL,
  `certificate_signature_name` varchar(255) DEFAULT NULL,
  `signature_position` varchar(255) DEFAULT NULL,
  `official_seal_path` varchar(255) DEFAULT NULL,
  `official_seal_filename` varchar(255) DEFAULT NULL,
  `primary_color` varchar(255) DEFAULT '#1C1C1D',
  `secondary_color` varchar(255) DEFAULT NULL,
  `accent_color` varchar(255) DEFAULT NULL,
  `favicon_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `brandings` */

insert  into `brandings`(`id`,`logo_path`,`logo_filename`,`certificate_header_text`,`certificate_signature_name`,`signature_position`,`official_seal_path`,`official_seal_filename`,`primary_color`,`secondary_color`,`accent_color`,`favicon_path`,`created_at`,`updated_at`) values (1,'branding/logos/PiEbUfDE9Ir6EYOtVyWvU6fwv5s799fSbHSHCcNB.png','5da5af5a-bb51-4141-bbf5-1484fd27fa69.png','TKD','TKD Testing','Chief Instructor','branding/seals/zfrRACaLyYhErVByUZS7g8MJfytnoLalePCAmzYa.png','649126567_1299888535319912_527797277755080973_n.png','#1C1C1D',NULL,NULL,NULL,'2026-03-19 08:52:49','2026-03-23 01:28:41'),(2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'#1C1C1D',NULL,NULL,NULL,'2026-03-23 14:56:01','2026-03-23 14:56:01');

/*Table structure for table `cache` */

DROP TABLE IF EXISTS `cache`;

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `cache` */

/*Table structure for table `cache_locks` */

DROP TABLE IF EXISTS `cache_locks`;

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `cache_locks` */

/*Table structure for table `certificates` */

DROP TABLE IF EXISTS `certificates`;

CREATE TABLE `certificates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `certificate_type` varchar(100) NOT NULL,
  `title` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `issued_date` date NOT NULL,
  `issued_by_user_id` int(11) NOT NULL,
  `qr_code_value` varchar(255) NOT NULL,
  `verification_url` varchar(255) NOT NULL,
  `pdf_path` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  KEY `issued_by_user_id` (`issued_by_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `certificates` */

/*Table structure for table `chat_messages` */

DROP TABLE IF EXISTS `chat_messages`;

CREATE TABLE `chat_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `thread_id` int(11) NOT NULL,
  `sender_user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `attachment_url` varchar(255) DEFAULT NULL,
  `sent_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `thread_id` (`thread_id`),
  KEY `sender_user_id` (`sender_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `chat_messages` */

/*Table structure for table `chat_participants` */

DROP TABLE IF EXISTS `chat_participants`;

CREATE TABLE `chat_participants` (
  `thread_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`thread_id`,`user_id`),
  KEY `thread_id` (`thread_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `chat_participants` */

/*Table structure for table `chat_threads` */

DROP TABLE IF EXISTS `chat_threads`;

CREATE TABLE `chat_threads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` enum('private','class','parent-staff') NOT NULL,
  `class_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `chat_threads` */

/*Table structure for table `class_schedules` */

DROP TABLE IF EXISTS `class_schedules`;

CREATE TABLE `class_schedules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class_id` int(11) NOT NULL,
  `day_of_week` enum('monday','tuesday','wednesday','thursday','friday','saturday','sunday') NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `class_schedules_class_id_foreign` (`class_id`),
  CONSTRAINT `class_schedules_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `class_schedules` */

insert  into `class_schedules`(`id`,`class_id`,`day_of_week`,`start_time`,`end_time`,`created_at`,`updated_at`) values (7,2,'monday','10:00:00','12:00:00','2026-03-25 16:36:51','2026-03-25 16:36:51'),(8,1,'tuesday','13:00:00','16:00:00','2026-03-25 16:36:57','2026-03-25 16:36:57'),(10,5,'wednesday','12:00:00','15:30:00','2026-03-26 12:12:26','2026-03-26 12:12:26'),(11,3,'friday','08:30:00','11:00:00','2026-03-26 12:35:11','2026-03-26 12:35:11'),(12,6,'monday','12:15:00','14:15:00','2026-03-26 14:15:53','2026-03-26 14:15:53'),(15,9,'wednesday','11:20:00','12:25:00','2026-03-27 02:22:16','2026-03-27 02:22:16'),(19,12,'saturday','17:43:00','18:30:00','2026-03-28 09:42:43','2026-03-28 09:42:43'),(22,14,'monday','22:58:00','23:35:00','2026-03-31 14:37:40','2026-03-31 14:37:40'),(23,14,'tuesday','22:38:00','23:25:00','2026-03-31 14:37:40','2026-03-31 14:37:40'),(26,7,'monday','00:12:00','14:12:00','2026-04-06 16:23:25','2026-04-06 16:23:25'),(28,13,'monday','12:42:00','13:30:00','2026-04-06 16:54:07','2026-04-06 16:54:07');

/*Table structure for table `class_sessions` */

DROP TABLE IF EXISTS `class_sessions`;

CREATE TABLE `class_sessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class_id` int(11) NOT NULL,
  `session_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `instructor_id` bigint(20) unsigned NOT NULL,
  `session_status` enum('scheduled','ongoing','completed','cancelled') NOT NULL DEFAULT 'scheduled',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `class_sessions_class_id_foreign` (`class_id`),
  KEY `class_sessions_instructor_id_foreign` (`instructor_id`),
  CONSTRAINT `class_sessions_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `class_sessions` */

insert  into `class_sessions`(`id`,`class_id`,`session_date`,`start_time`,`end_time`,`instructor_id`,`session_status`,`notes`,`created_at`,`updated_at`) values (25,7,'2026-03-28','07:26:51','08:26:51',6,'ongoing',NULL,'2026-03-28 07:26:51','2026-03-28 07:26:51'),(26,6,'2026-03-28','07:28:25','08:28:25',6,'ongoing',NULL,'2026-03-28 07:28:25','2026-03-28 07:28:25'),(27,12,'2026-03-28','07:55:09','08:55:09',6,'ongoing',NULL,'2026-03-28 07:55:09','2026-03-28 07:55:09'),(30,6,'2026-03-30','03:35:20','04:35:20',6,'ongoing',NULL,'2026-03-30 03:35:20','2026-03-30 03:35:20'),(31,13,'2026-03-30','04:42:10','05:42:10',6,'ongoing',NULL,'2026-03-30 04:42:10','2026-03-30 04:42:10'),(32,14,'2026-03-30','15:00:28','16:00:28',6,'ongoing',NULL,'2026-03-30 15:00:28','2026-03-30 15:00:28'),(33,14,'2026-03-31','14:38:19','15:38:19',6,'ongoing',NULL,'2026-03-31 14:38:19','2026-03-31 14:38:19');

/*Table structure for table `class_students` */

DROP TABLE IF EXISTS `class_students`;

CREATE TABLE `class_students` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `status` enum('active','completed','dropped') DEFAULT 'active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `class_students` */

insert  into `class_students`(`id`,`class_id`,`student_id`,`start_date`,`end_date`,`status`) values (3,2,1,'2026-03-27',NULL,'active'),(4,2,2,'2026-03-26',NULL,'active'),(7,7,1,'2026-03-27',NULL,'active'),(8,7,2,'2026-03-27',NULL,'active'),(9,6,1,'2026-03-27',NULL,'active'),(10,6,2,'2026-03-27',NULL,'active'),(11,9,1,'2026-03-27',NULL,'active'),(12,6,5,'2026-03-27',NULL,'active'),(15,12,5,'2026-03-28',NULL,'active'),(16,12,2,'2026-03-28',NULL,'active'),(17,12,1,'2026-03-28',NULL,'active'),(18,12,6,'2026-03-28',NULL,'active'),(19,13,2,'2026-03-30',NULL,'active'),(20,13,6,'2026-03-30',NULL,'active'),(21,13,5,'2026-03-30',NULL,'active'),(22,13,13,'2026-03-30',NULL,'active'),(24,14,7,'2026-03-30',NULL,'active'),(25,13,15,'2026-03-31',NULL,'active'),(26,14,19,'2026-04-06',NULL,'active');

/*Table structure for table `classes` */

DROP TABLE IF EXISTS `classes`;

CREATE TABLE `classes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `branch_id` int(11) NOT NULL,
  `class_name` varchar(150) NOT NULL,
  `age_group` varchar(50) DEFAULT NULL,
  `level` varchar(50) DEFAULT NULL,
  `max_students` int(11) DEFAULT 0,
  `primary_instructor_id` int(11) DEFAULT NULL,
  `assistant_instructor_id` int(11) DEFAULT NULL,
  `status` enum('active','inactive','cancelled') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `classes` */

insert  into `classes`(`id`,`branch_id`,`class_name`,`age_group`,`level`,`max_students`,`primary_instructor_id`,`assistant_instructor_id`,`status`,`created_at`,`updated_at`) values (1,2,'Taekwondo Beginner Basics','24-35','White',20,3,2,'active','2026-03-05 00:00:00','2026-03-25 16:36:57'),(2,1,'White Belt Fundamentals Class','8-14','White',20,5,NULL,'active','2026-03-25 02:32:41','2026-03-25 16:36:51'),(3,1,'Taekwondo 101: White Belt Level','10-15','White',20,4,NULL,'inactive','2026-03-25 02:34:48','2026-03-26 12:35:11'),(5,4,'White-Yellow Belt Skills Development','17-20','White-Yellow',20,4,3,'active','2026-03-26 12:12:26','2026-03-26 12:12:26'),(6,1,'Veterans training','10','Blue-Red',10,6,5,'active','2026-03-26 14:15:53','2026-03-26 14:15:53'),(7,1,'Hard training','10-15','Blue-Red',5,8,7,'active','2026-03-26 15:12:59','2026-04-06 16:23:25'),(9,1,'Intermediate Training','10-15','Red-Black',10,6,NULL,'active','2026-03-27 02:22:16','2026-03-27 02:22:16'),(12,1,'Master Training','18-22','Red-Black',10,6,9,'active','2026-03-28 07:54:23','2026-03-28 07:54:23'),(13,1,'Master Training','18-22','Black',10,6,8,'active','2026-03-30 04:41:01','2026-04-06 16:54:07'),(14,2,'Fundamental Training','7-10','Blue-Red',10,6,8,'active','2026-03-30 14:56:55','2026-03-30 14:56:55');

/*Table structure for table `club_profiles` */

DROP TABLE IF EXISTS `club_profiles`;

CREATE TABLE `club_profiles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `club_name` varchar(255) NOT NULL,
  `club_acronym` varchar(255) DEFAULT NULL,
  `founded_year` varchar(4) DEFAULT NULL,
  `club_description` text DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `contact_number` varchar(255) DEFAULT NULL,
  `club_address` text DEFAULT NULL,
  `logo_url` varchar(255) DEFAULT NULL,
  `website_url` varchar(255) DEFAULT NULL,
  `facebook_url` varchar(255) DEFAULT NULL,
  `instagram_url` varchar(255) DEFAULT NULL,
  `tax_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `club_profiles` */

insert  into `club_profiles`(`id`,`club_name`,`club_acronym`,`founded_year`,`club_description`,`email`,`contact_number`,`club_address`,`logo_url`,`website_url`,`facebook_url`,`instagram_url`,`tax_id`,`created_at`,`updated_at`) values (1,'Taekwondo','TKD','2026','Premier Taekwondo training facility dedicated to excellence in martial arts education and character development.','info@tkdchampions.com','+63 (2) 1234-5678','123 Martial Arts Avenue, Barangay Sports Complex, Manila, Philippines',NULL,'https://tkdchampions.com','https://facebook.com/tkdchampions','https://instagram.com/tkdchampions','123-456-789-000','2026-03-19 06:11:24','2026-03-19 08:14:40');

/*Table structure for table `competition_entries` */

DROP TABLE IF EXISTS `competition_entries`;

CREATE TABLE `competition_entries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `competition_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `instructor_id` int(11) NOT NULL,
  `category` varchar(100) NOT NULL,
  `division` varchar(100) NOT NULL,
  `result` enum('win','loss','draw','pending') NOT NULL,
  `medal` enum('gold','silver','bronze','none') DEFAULT 'none',
  `remarks` text DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `competition_id` (`competition_id`),
  KEY `student_id` (`student_id`),
  KEY `instructor_id` (`instructor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `competition_entries` */

/*Table structure for table `competitions` */

DROP TABLE IF EXISTS `competitions`;

CREATE TABLE `competitions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `location` varchar(150) NOT NULL,
  `date` date NOT NULL,
  `organizer` varchar(150) DEFAULT NULL,
  `level` enum('local','regional','national','international') NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  `status` enum('active','inactive') DEFAULT 'active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `competitions` */

insert  into `competitions`(`id`,`name`,`location`,`date`,`organizer`,`level`,`created_at`,`updated_at`,`status`) values (1,'Metro Manila Taekwondo Open','Quezon City Sports Complex, Quezon City','2026-06-16','Philippine Taekwondo Association','regional','2026-04-01 01:08:22','2026-04-01 01:36:28','active');

/*Table structure for table `devices` */

DROP TABLE IF EXISTS `devices`;

CREATE TABLE `devices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `branch_id` int(11) NOT NULL,
  `device_name` varchar(100) NOT NULL,
  `device_type` enum('kiosk','tablet','desktop') NOT NULL,
  `api_key` varchar(255) NOT NULL,
  `last_seen` datetime DEFAULT NULL,
  `status` enum('active','inactive','maintenance') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `branch_id` (`branch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `devices` */

/*Table structure for table `discounts` */

DROP TABLE IF EXISTS `discounts`;

CREATE TABLE `discounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `type` enum('percent','fixed') NOT NULL,
  `value` decimal(10,2) NOT NULL,
  `applicable_to` enum('all','monthly fee','enrollment') DEFAULT NULL,
  `valid_from` date NOT NULL,
  `valid_to` date NOT NULL,
  `status` int(5) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `discounts` */

insert  into `discounts`(`id`,`name`,`type`,`value`,`applicable_to`,`valid_from`,`valid_to`,`status`,`created_at`,`updated_at`) values (1,'White Belt Special','percent','10.00','all','2026-03-24','2026-03-26',0,'2026-03-24 13:07:42','2026-03-24 13:07:42'),(2,'Intro Starter Bundle','fixed','69.00','enrollment','2026-03-25','2026-03-26',0,'2026-03-24 13:09:26','2026-03-24 13:09:26'),(3,'New Student Uniform Credit','percent','15.00','enrollment','2026-04-01','2026-04-10',1,'2026-03-24 13:22:31','2026-03-24 13:22:31'),(4,'Black Belt Path Enrollment','fixed','50.00','enrollment','2026-04-02','2026-04-30',1,'2026-03-24 13:22:31','2026-03-24 13:22:31'),(5,'Sibling Legacy Discount','percent','10.00','monthly fee','2026-04-05','2026-05-05',1,'2026-03-24 13:22:31','2026-03-24 13:22:31'),(6,'early birdParent Child Duo Promo','fixed','30.00','all','2026-04-01','2026-04-07',1,'2026-03-24 13:22:31','2026-03-24 13:48:49'),(7,'Family Unity Rate','percent','20.00','all','2026-12-20','2026-12-31',1,'2026-03-24 13:22:31','2026-03-24 13:22:31'),(8,'Refer A Sparring Partner','fixed','25.00','monthly fee','2026-04-03','2026-04-20',1,'2026-03-24 13:22:31','2026-03-24 13:22:31'),(9,'Summer Shatter Sale','percent','5.00','all','2026-04-06','2026-04-06',0,'2026-03-24 13:22:31','2026-03-24 13:22:31'),(10,'Back To School Focus Promo','percent','12.00','monthly fee','2026-04-01','2026-06-01',1,'2026-03-24 13:22:31','2026-03-24 13:22:31'),(11,'New Year New Goal Discount','fixed','40.00','enrollment','2026-04-01','2026-04-15',1,'2026-03-24 13:22:31','2026-03-24 13:22:31'),(13,'Spring Graduation Special','percent','40.00','enrollment','2026-03-23','2026-03-27',0,'2026-03-24 16:05:10','2026-03-24 16:05:10');

/*Table structure for table `face_profiles` */

DROP TABLE IF EXISTS `face_profiles`;

CREATE TABLE `face_profiles` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `student_id` int(10) NOT NULL,
  `face_embedding_vector` blob NOT NULL,
  `model_version` varchar(50) NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `face_profiles` */

/*Table structure for table `instructors` */

DROP TABLE IF EXISTS `instructors`;

CREATE TABLE `instructors` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) DEFAULT NULL,
  `fname` varchar(100) NOT NULL,
  `lname` varchar(100) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `contact` varchar(50) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rank_belt` varchar(255) DEFAULT NULL,
  `certification_level` varchar(255) DEFAULT NULL,
  `specialization` varchar(255) DEFAULT NULL,
  `hire_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `bio` text DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `active_flag` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `instructors` */

insert  into `instructors`(`id`,`user_id`,`fname`,`lname`,`photo`,`email`,`contact`,`username`,`password`,`rank_belt`,`certification_level`,`specialization`,`hire_date`,`bio`,`status`,`active_flag`,`created_at`,`updated_at`) values (2,0,'Minho','Park','instructors/6gp9JMND0sAfY6y7fc59ST7yVPUSVCRBO3Fs1IEA.jpg','ogiechanco0@gmail.com','09171234567','ogiechanco','$2y$12$De449fdZdrp9cPRV0JpSAOl0yc0P0.vvksjXLaqwtBoTOP4ztpI7O','Black Belt','Head Instructor','deawewadaw','2026-03-25 01:50:33','dawdawdwadasdawdas','active',1,'2026-03-18 04:08:33','2026-03-19 02:02:11'),(3,0,'Jisoo','Kim','instructors/bF9UegHz4yt4se35xe6ZjYe8dtvr5TPqYBZTRyN4.jpg','resshin240@gmail.com','0938292812','1234admin','$2y$12$pO9ee169WnJpr0MQJY4P1udaTs0cYJbe8O9sLwoYzqjNqCKdE4HQC','Black Belt','Head Instructor','ewan','2026-03-25 01:50:43','awdwadasd','active',1,'2026-03-18 04:34:24','2026-03-18 04:34:24'),(4,0,'John','Doe','instructors/Ievw56S3lHOTmOubm8G4jidqP154ljrmplrp4N9X.jpg','interimpink@talemarketing.com','0938292321','1_IQ','$2y$12$Go1NSoyNDez0vRsDYZDnRu1soZD0wvzTg/GBdWl5L47Jt/OJ/SuO2','Black Belt','Head Instructor','dsad','2026-03-18 00:00:00','2wdawdawdawd','active',1,'2026-03-18 04:44:53','2026-03-18 04:44:53'),(5,0,'Taeyang','Choi',NULL,'porras@porrs','09171234567','dwadwadawd','$2y$12$34ZbxYWrK327SfZ7VTtlTuZqtV1E./na3AQnsxIvzffWpeh.rlZYa','Red Belt','Assistant Instructor','kick boxing','2026-03-25 01:50:50','loremipsum','active',1,'2026-03-24 17:11:38','2026-03-24 17:11:38'),(6,24,'Eduard','Estareja','instructors/iBUw9NVsFVLPObp40hA8Jo7T81tHddnNuCY9euXc.png','eduardestarejajr@gmail.com','09826717854','testing@gmail.com','$2y$12$gLhLdEEjfhL1i1a7i0RFb.xEBEL.einOX3jXlVHcsEsZwP5JBZgOm','Black Belt','Head Instructor','flying kick','2026-03-26 22:13:31','trust the process','active',1,'2026-03-26 13:40:09','2026-03-26 13:40:09'),(7,25,'john','alvior','instructors/ekceucBRTf40SSg9awHumCiMRsOA2hwUyeXLZpbF.png','john@gmail.com','09874783672','janjan','$2y$12$12Ph/MScgQTzwM0.JeQGYO1xK1En4dB1fwVTxp6kPmXjAmbzClBz6','Red Belt','Head Instructor','flying kick','2026-03-26 22:40:36','hard working','active',1,'2026-03-26 14:34:39','2026-03-26 14:34:39'),(8,26,'triever','ruga','instructors/LQt3BlhWasQ5jZlSoaLXOYGHmtfwWhtAQvFgrfZJ.png','triever@gmail.com','0987462713','everr','$2y$12$oji7lQPPuc.nErUsrW/N8O7WMyQPzyMVNLzZ76SGk4MEvDUJmAcaO','Red Belt','Head Instructor','flying kick','2026-03-26 23:10:13','intellegence','active',1,'2026-03-26 15:10:13','2026-03-26 15:10:13'),(9,27,'Mica','Barrientos','instructors/8NFkFD653sqjw7p0Ihn4A8zgpze13w5md0MPgDq6.png','mica@gmail.com','0987462713','micah','$2y$12$dOH4T5KvpX2JlYTqC85TGeOyhl7EgQq0Jba0ZstQkcgRL9NjhQTMm','Black Belt','Head Instructor','flying kick','2026-03-27 23:28:30','hard working','active',1,'2026-03-27 15:28:30','2026-03-27 15:28:30');

/*Table structure for table `invoices` */

DROP TABLE IF EXISTS `invoices`;

CREATE TABLE `invoices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `subscription_id` int(11) NOT NULL,
  `invoice_no` varchar(50) NOT NULL,
  `billing_period_start` date NOT NULL,
  `billing_period_end` date NOT NULL,
  `amount` decimal(10,2) DEFAULT 0.00,
  `discount` decimal(10,2) DEFAULT 0.00,
  `penalty` decimal(10,2) DEFAULT 0.00,
  `total_due` decimal(10,2) DEFAULT 0.00,
  `due_date` date NOT NULL,
  `status` enum('pending','paid','overdue','void') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `invoice_no` (`invoice_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `invoices` */

/*Table structure for table `jobs` */

DROP TABLE IF EXISTS `jobs`;

CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `jobs` */

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `migrations` */

insert  into `migrations`(`id`,`migration`,`batch`) values (1,'2026_03_13_021327_create_personal_access_tokens_table',1),(2,'2026_03_13_063529_create_cache_table',2),(3,'2026_03_19_055841_create_club_profiles_table',3),(4,'2026_03_19_060249_create_club_profiles_table',4);

/*Table structure for table `notifications` */

DROP TABLE IF EXISTS `notifications`;

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `type` varchar(50) NOT NULL,
  `read_flag` tinyint(1) DEFAULT 0,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `notifications` */

/*Table structure for table `parent_students` */

DROP TABLE IF EXISTS `parent_students`;

CREATE TABLE `parent_students` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `relationship` enum('mother','father','guardian') NOT NULL,
  `is_primary` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `parent_students` */

insert  into `parent_students`(`id`,`parent_id`,`student_id`,`relationship`,`is_primary`,`created_at`,`updated_at`) values (1,12,1,'father',0,'2026-03-26 13:38:56',NULL);

/*Table structure for table `parents` */

DROP TABLE IF EXISTS `parents`;

CREATE TABLE `parents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `emergency_contact` varchar(255) DEFAULT NULL,
  `relationship_note` text DEFAULT NULL,
  `address` text DEFAULT NULL,
  `id_verified_flag` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `parents_user_id_foreign` (`user_id`),
  CONSTRAINT `parents_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `parents` */

insert  into `parents`(`id`,`user_id`,`emergency_contact`,`relationship_note`,`address`,`id_verified_flag`,`created_at`,`updated_at`) values (6,16,'Maria Santos','Mother','Quezon City',1,'2026-03-17 12:06:47','2026-03-17 12:06:47'),(7,17,'Jose Cruz','Father','Makati City',1,'2026-03-17 12:06:47','2026-03-17 12:06:47'),(8,18,'Anna Tan','Mother','Pasig City',1,'2026-03-17 12:06:47','2026-03-17 12:06:47'),(9,19,'Carlos Garcia','Father','Caloocan City',1,'2026-03-17 12:06:47','2026-03-17 12:06:47'),(10,20,'Grace Lee','Mother','Manila City',1,'2026-03-17 12:06:47','2026-03-17 12:06:47'),(11,22,'09876521465','Father','1177 Quirino Highway, Brgy kaligayahan, Novaliches',1,'2026-03-26 13:29:58',NULL),(12,23,'09876521465','Father','1177 Quirino Highway, Brgy kaligayahan, Novaliches',1,'2026-03-26 13:38:56',NULL);

/*Table structure for table `payments` */

DROP TABLE IF EXISTS `payments`;

CREATE TABLE `payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_id` int(11) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `payment_method` enum('cash','gcash','card','bank') NOT NULL,
  `reference_no` varchar(100) DEFAULT NULL,
  `paid_at` datetime NOT NULL,
  `received_by_user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `invoice_id` (`invoice_id`),
  KEY `received_by_user_id` (`received_by_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `payments` */

/*Table structure for table `personal_access_tokens` */

DROP TABLE IF EXISTS `personal_access_tokens`;

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  KEY `personal_access_tokens_expires_at_index` (`expires_at`)
) ENGINE=InnoDB AUTO_INCREMENT=109 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `personal_access_tokens` */

insert  into `personal_access_tokens`(`id`,`tokenable_type`,`tokenable_id`,`name`,`token`,`abilities`,`last_used_at`,`expires_at`,`created_at`,`updated_at`) values (1,'App\\Models\\User',7,'flutter-mobile-app','4c0b0a6e7d284b137bda9502f50c048a12bb061f75af4b1f385f6e5dca05204d','[\"*\"]',NULL,NULL,'2026-03-13 02:20:32','2026-03-13 02:20:32'),(2,'App\\Models\\User',7,'flutter-mobile-app','8898e355447851ee9eb95c8597365758abe94e06d6baabae4421a6a7d021c455','[\"*\"]',NULL,NULL,'2026-03-13 03:36:02','2026-03-13 03:36:02'),(3,'App\\Models\\User',7,'flutter-mobile-app','4b4c5c47651cfd40692f715c1f94b038a59cc02b387c1a59e5a73994879a5c6f','[\"*\"]',NULL,NULL,'2026-03-13 03:38:36','2026-03-13 03:38:36'),(4,'App\\Models\\User',7,'flutter-mobile-app','73dcd67e1710ce1ff4b8494c1a39ecb909bf86485a11daa76dd45e2485f88075','[\"*\"]',NULL,NULL,'2026-03-13 06:02:52','2026-03-13 06:02:52'),(5,'App\\Models\\User',7,'flutter-mobile-app','f99378ab688cde79ec5e72cb6ad84aaaa9779710cabac36800a789604a08bbbe','[\"*\"]',NULL,NULL,'2026-03-13 06:13:56','2026-03-13 06:13:56'),(6,'App\\Models\\User',7,'flutter-mobile-app','f16bd7bacad7b16a2a84e1aca7bca70e76761d8a104ad433c357df91c61fa1dc','[\"*\"]',NULL,NULL,'2026-03-13 06:26:31','2026-03-13 06:26:31'),(7,'App\\Models\\User',7,'flutter-mobile-app','92e8df958fa48c046f5caafdab68bb9d4495be1ff5354642bad0c576a1d69e21','[\"*\"]',NULL,NULL,'2026-03-13 07:06:21','2026-03-13 07:06:21'),(8,'App\\Models\\User',7,'flutter-mobile-app','fe4e87d65e93fea1a8e47270febd434f8017233e2c59a134099a5148ed181ab9','[\"*\"]','2026-03-13 09:14:08',NULL,'2026-03-13 07:58:49','2026-03-13 09:14:08'),(9,'App\\Models\\User',7,'flutter-mobile-app','67806086a3651c1670922edc3d25affa8d1624213ad94e05b1f4cf99a388f09f','[\"*\"]','2026-03-16 03:37:28',NULL,'2026-03-13 09:14:13','2026-03-16 03:37:28'),(10,'App\\Models\\User',7,'flutter-mobile-app','995ea63c191272dff538b60019c77187bcb090ab8e585ede6e9e91d2165c1b14','[\"*\"]',NULL,NULL,'2026-03-16 03:37:03','2026-03-16 03:37:03'),(11,'App\\Models\\User',7,'flutter-mobile-app','844c3b35b2cf8f97053e8244879953b5c6246fab5fa86f09cb48080d5cdf4ea1','[\"*\"]',NULL,NULL,'2026-03-16 08:26:00','2026-03-16 08:26:00'),(12,'App\\Models\\User',7,'flutter-mobile-app','ca18ac77bf0b8fffb1e1ed582d4c7d30646a6b9e7c88a7464ca060d258d9bd5e','[\"*\"]','2026-03-16 08:28:36',NULL,'2026-03-16 08:26:35','2026-03-16 08:28:36'),(13,'App\\Models\\User',24,'flutter-mobile-app','d0fd7851cce12366e6a3cefb644014b108fe0b466d1fee53df9eaace037c3a00','[\"*\"]',NULL,NULL,'2026-03-26 13:41:31','2026-03-26 13:41:31'),(14,'App\\Models\\User',24,'flutter-mobile-app','ec018d4736aab0f021519db883ffd7a3d71dc6c227e0068c0ebbe63079f22870','[\"*\"]',NULL,NULL,'2026-03-26 14:02:49','2026-03-26 14:02:49'),(15,'App\\Models\\User',24,'flutter-mobile-app','3baf7823cebbecccc0f78d2169dffede7ab4ce2563825d43e111a664465646ac','[\"*\"]',NULL,NULL,'2026-03-26 14:03:31','2026-03-26 14:03:31'),(16,'App\\Models\\User',24,'flutter-mobile-app','82e001760672c6335113656613c3a2ed93682a7ded3a57e2501d6c7fbabb2da6','[\"*\"]',NULL,NULL,'2026-03-26 14:08:13','2026-03-26 14:08:13'),(17,'App\\Models\\User',24,'flutter-mobile-app','57c844d98646358e1d19b7a070ce88bad2ceaab54fed26dfd43fed1234eae501','[\"*\"]','2026-03-26 14:39:53',NULL,'2026-03-26 14:09:37','2026-03-26 14:39:53'),(18,'App\\Models\\User',25,'flutter-mobile-app','94a3264e4534429b3d2f398218d3911cb3914009ac744bd2ea247bc0a9166054','[\"*\"]','2026-03-26 14:40:52',NULL,'2026-03-26 14:38:28','2026-03-26 14:40:52'),(19,'App\\Models\\User',24,'flutter-mobile-app','47bb8932035c4c18ac32139a325a1559930e5e5192359cf7289bd3e42df01266','[\"*\"]','2026-03-26 14:56:23',NULL,'2026-03-26 14:56:04','2026-03-26 14:56:23'),(20,'App\\Models\\User',26,'flutter-mobile-app','84360fae0a371e9269df771084a5054756a3aa67b8be9851118580ed9468c3df','[\"*\"]','2026-03-27 00:55:17',NULL,'2026-03-26 15:11:24','2026-03-27 00:55:17'),(21,'App\\Models\\User',26,'flutter-mobile-app','52b31e8d381a48dbce11aa5cde75c67e08b408faecf21d168dfbc326da02b3a5','[\"*\"]','2026-03-27 01:04:31',NULL,'2026-03-27 00:55:45','2026-03-27 01:04:31'),(22,'App\\Models\\User',24,'flutter-mobile-app','60fe7202dca4a78c0efdaf69a47517c700fce23923cd74d60bd2f77bea51ad12','[\"*\"]','2026-03-27 01:23:32',NULL,'2026-03-27 01:23:31','2026-03-27 01:23:32'),(23,'App\\Models\\User',24,'flutter-mobile-app','b8e538f79bce51494c448e5d9d0babb142a2da97bbb58a1a259eee367533fdd6','[\"*\"]','2026-03-27 01:33:37',NULL,'2026-03-27 01:33:35','2026-03-27 01:33:37'),(24,'App\\Models\\User',24,'flutter-mobile-app','d5d561596fa3fcf629cab14b908f4a12ac0bcc5458621794d756c868de896993','[\"*\"]','2026-03-27 01:37:02',NULL,'2026-03-27 01:37:00','2026-03-27 01:37:02'),(25,'App\\Models\\User',24,'flutter-mobile-app','5dd414e5ae4227798f33343af91f316731a47e93d67b8b837c1772197f8ed5da','[\"*\"]','2026-03-27 01:38:40',NULL,'2026-03-27 01:38:38','2026-03-27 01:38:40'),(26,'App\\Models\\User',24,'flutter-mobile-app','fafc7e94d2e03cf542d5a656779d57c52c40aa83030182db61ea9d5a1a7cdbc8','[\"*\"]','2026-03-27 01:47:46',NULL,'2026-03-27 01:47:43','2026-03-27 01:47:46'),(27,'App\\Models\\User',24,'flutter-mobile-app','3fee1fe1629a9f0920ad055b0f6833c1324fafa792a1ee0d5ab5784f00d1e65e','[\"*\"]','2026-03-27 01:51:53',NULL,'2026-03-27 01:51:27','2026-03-27 01:51:53'),(28,'App\\Models\\User',24,'flutter-mobile-app','33ce817ef4fb556556fbe3346e5a2fbc7694058bf48409f9b5d8a8290288eb86','[\"*\"]',NULL,NULL,'2026-03-27 01:57:05','2026-03-27 01:57:05'),(29,'App\\Models\\User',24,'flutter-mobile-app','5a35e6a01d60e9f9829069ff27e7b9f4aaf821abaafd9014afc1c23ac7b23393','[\"*\"]','2026-03-27 02:07:22',NULL,'2026-03-27 02:07:20','2026-03-27 02:07:22'),(30,'App\\Models\\User',24,'flutter-mobile-app','d07057309f1b8ac01b1d8e945f9775803329096afcc2e35cd77e384e9e414cfe','[\"*\"]','2026-03-27 02:23:48',NULL,'2026-03-27 02:23:47','2026-03-27 02:23:48'),(31,'App\\Models\\User',24,'flutter-mobile-app','893cef178f0d179483f6e774b4ca33dcca9cac22a1024578812246f5cd9d4a19','[\"*\"]','2026-03-27 02:37:26',NULL,'2026-03-27 02:37:25','2026-03-27 02:37:26'),(32,'App\\Models\\User',24,'flutter-mobile-app','68184cb869b6035eb73e4d51e79566eed035d1fc20f8f702d2fdb0d9cfcaf9d0','[\"*\"]','2026-03-27 02:39:10',NULL,'2026-03-27 02:39:08','2026-03-27 02:39:10'),(33,'App\\Models\\User',24,'flutter-mobile-app','a07e06e5f91a05e2ae8e57f3f83f9e046582b839a7d5698cecab9f8949d91845','[\"*\"]','2026-03-27 03:19:38',NULL,'2026-03-27 03:08:33','2026-03-27 03:19:38'),(34,'App\\Models\\User',24,'flutter-mobile-app','ad2b9b2c04dea0a6c3edd45e547a9438e9be6d7a2c40196099e49c4ae0bc96b7','[\"*\"]','2026-03-27 03:25:39',NULL,'2026-03-27 03:19:54','2026-03-27 03:25:39'),(35,'App\\Models\\User',24,'flutter-mobile-app','a0d76461a6c202510b71de1121c3bb28c7b3166587ac1f3f1776543eb74606b5','[\"*\"]','2026-03-27 03:26:56',NULL,'2026-03-27 03:26:42','2026-03-27 03:26:56'),(36,'App\\Models\\User',24,'flutter-mobile-app','ff2e64f5ea1d1d53bcf04855468a8bc5e0fe8b1cfb9028eae39dfcf61553cd9a','[\"*\"]','2026-03-27 03:45:53',NULL,'2026-03-27 03:43:36','2026-03-27 03:45:53'),(37,'App\\Models\\User',24,'flutter-mobile-app','6557474ecea8bd03270c07594d9a5058ef13dc450eba8aaaac98c4e4558a2567','[\"*\"]','2026-03-27 03:54:43',NULL,'2026-03-27 03:54:36','2026-03-27 03:54:43'),(38,'App\\Models\\User',24,'flutter-mobile-app','c2dc50f9defcd6cf2f64842ffbd6f4f54078c070c93b01530aa19dd53aa7e05c','[\"*\"]','2026-03-27 03:59:51',NULL,'2026-03-27 03:58:17','2026-03-27 03:59:51'),(39,'App\\Models\\User',24,'flutter-mobile-app','4d9843b08750c03e30f9810414457d44fedb7d7347c321c9f41e254cee1d823a','[\"*\"]','2026-03-27 04:13:31',NULL,'2026-03-27 04:09:31','2026-03-27 04:13:31'),(40,'App\\Models\\User',24,'flutter-mobile-app','acb4153b3780052ebde8dac3ee834200bce34597eb24b8f1d61665d7bc4d8820','[\"*\"]','2026-03-27 04:30:40',NULL,'2026-03-27 04:29:00','2026-03-27 04:30:40'),(41,'App\\Models\\User',24,'flutter-mobile-app','8201a3c67faf6efbe0a8538ac638037b3176899570bc1dda297bf62944c0b06a','[\"*\"]','2026-03-27 15:17:21',NULL,'2026-03-27 15:03:46','2026-03-27 15:17:21'),(42,'App\\Models\\User',27,'flutter-mobile-app','f80c42677ed4a56d367d7d44d6b44b6fc738ea84ebc6c198e1dd8544e1764bef','[\"*\"]','2026-03-27 15:29:06',NULL,'2026-03-27 15:29:04','2026-03-27 15:29:06'),(43,'App\\Models\\User',27,'flutter-mobile-app','e96ab928a6ffa118962b05157c6c705b1734d1980e640e40456de056f1968fc7','[\"*\"]','2026-03-27 15:54:04',NULL,'2026-03-27 15:31:41','2026-03-27 15:54:04'),(44,'App\\Models\\User',27,'flutter-mobile-app','9016efedc313e486f18e195a134ea94fb3df64d12dbe6847bac078ce7383c3d1','[\"*\"]','2026-03-28 06:29:58',NULL,'2026-03-28 06:29:39','2026-03-28 06:29:58'),(45,'App\\Models\\User',24,'flutter-mobile-app','4b7125fb56c84ac303f57f69d17830c4a03a6e715ca0a8edbeb7eb23a2c9cdf3','[\"*\"]','2026-03-28 06:41:54',NULL,'2026-03-28 06:30:24','2026-03-28 06:41:54'),(46,'App\\Models\\User',24,'flutter-mobile-app','d54f1142d0c3734c8aafce409349c7bfaeed3e8a5c94a3bf355026d05b98f481','[\"*\"]','2026-03-28 07:50:43',NULL,'2026-03-28 07:25:07','2026-03-28 07:50:43'),(47,'App\\Models\\User',24,'flutter-mobile-app','970fcc7cf89bd033e8658c53b814b26b1200c7b281c87ef7b53a4192a0398e92','[\"*\"]','2026-03-28 07:28:25',NULL,'2026-03-28 07:28:17','2026-03-28 07:28:25'),(48,'App\\Models\\User',24,'flutter-mobile-app','773e15637e72e46ba8d539eb65c4fdff120c4ebfd06a367430b5a37a57fffad2','[\"*\"]','2026-03-28 07:49:32',NULL,'2026-03-28 07:49:26','2026-03-28 07:49:32'),(49,'App\\Models\\User',24,'flutter-mobile-app','1d81fcfedffbc440f8e6d701cb487459e2187f6ea80e2d425e3f99e0fc96c357','[\"*\"]','2026-03-28 07:51:18',NULL,'2026-03-28 07:51:13','2026-03-28 07:51:18'),(50,'App\\Models\\User',24,'flutter-mobile-app','a673ce7667bc3f1a8b1e0ecb718f11421282651fd53376ea738d47389e193959','[\"*\"]','2026-03-28 07:56:10',NULL,'2026-03-28 07:54:57','2026-03-28 07:56:10'),(51,'App\\Models\\User',24,'flutter-mobile-app','e583a6027edbfa83a49610625e36f0d378b74795d5e19e63b7386065225d7f7d','[\"*\"]','2026-03-28 08:00:34',NULL,'2026-03-28 08:00:19','2026-03-28 08:00:34'),(52,'App\\Models\\User',24,'flutter-mobile-app','669331ca85b906f759df310a853bff7ee582119bed58bceb8026ad516fc4fd0e','[\"*\"]','2026-03-28 09:37:06',NULL,'2026-03-28 09:30:51','2026-03-28 09:37:06'),(53,'App\\Models\\User',24,'flutter-mobile-app','4aa5e8ba9324d5bc20d83ae88e4bb081cc801b3472fdc3524df11fb8132e0891','[\"*\"]','2026-03-28 09:38:50',NULL,'2026-03-28 09:38:22','2026-03-28 09:38:50'),(54,'App\\Models\\User',27,'flutter-mobile-app','e185b364a0ce91a3233f74cf17e1c86f10d2b6f2e450465891f79173eba92c45','[\"*\"]','2026-03-28 09:42:01',NULL,'2026-03-28 09:41:31','2026-03-28 09:42:01'),(55,'App\\Models\\User',24,'flutter-mobile-app','7e9723b578c246d76e4e0a43246d0f95ec0fd2a32192c8471ddd72253010d954','[\"*\"]','2026-03-28 09:47:55',NULL,'2026-03-28 09:43:10','2026-03-28 09:47:55'),(56,'App\\Models\\User',24,'flutter-mobile-app','b2272609a0a40b51b9756da2bbeef14988d940ad1dbc4da9908f6fb4b8a89c4e','[\"*\"]','2026-03-30 02:59:49',NULL,'2026-03-30 02:44:57','2026-03-30 02:59:49'),(57,'App\\Models\\User',24,'flutter-mobile-app','c821b1b934511c198e67ac541ed82d0f997e61204118c12cf0e8909be0dff64f','[\"*\"]','2026-03-30 03:04:37',NULL,'2026-03-30 03:04:35','2026-03-30 03:04:37'),(58,'App\\Models\\User',24,'flutter-mobile-app','2dc547da16c537f4de95333b69e410c831acf63f2b905e8f4fbabbee8b684531','[\"*\"]','2026-03-30 03:05:55',NULL,'2026-03-30 03:05:53','2026-03-30 03:05:55'),(59,'App\\Models\\User',24,'flutter-mobile-app','f4091be58a9d0f6bd341d8b8d5645d497327bf9edbca997f135aa3cea39fa228','[\"*\"]','2026-03-30 03:12:42',NULL,'2026-03-30 03:11:28','2026-03-30 03:12:42'),(60,'App\\Models\\User',24,'flutter-mobile-app','d9047ad65aa8bca8389b8fc94209c065645a1c7973ad00094daebe3d2370da95','[\"*\"]','2026-03-30 03:20:19',NULL,'2026-03-30 03:20:17','2026-03-30 03:20:19'),(61,'App\\Models\\User',24,'flutter-mobile-app','a332eaa789cbfa8e3f8f1d710d9a72a40b7ead730fc52585d12b849d8910f756','[\"*\"]','2026-03-30 03:43:12',NULL,'2026-03-30 03:30:51','2026-03-30 03:43:12'),(62,'App\\Models\\User',24,'flutter-mobile-app','0cfee484abed79ff3b05412e387d7b52f62d0a066ed14013bf701ea6489bfd55','[\"*\"]','2026-03-30 04:01:03',NULL,'2026-03-30 04:00:45','2026-03-30 04:01:03'),(63,'App\\Models\\User',24,'flutter-mobile-app','8663ac3f84a4c7e8ebdeb2ad8725aa16833253281f66e2198456dddda8a5a403','[\"*\"]','2026-03-30 04:39:32',NULL,'2026-03-30 04:02:23','2026-03-30 04:39:32'),(64,'App\\Models\\User',24,'flutter-mobile-app','11d60719095615c08f57ec17e7ab80fa7bc1d13b56f68fe9c18e8cc5e8633d60','[\"*\"]','2026-03-30 04:44:18',NULL,'2026-03-30 04:41:59','2026-03-30 04:44:18'),(65,'App\\Models\\User',24,'flutter-mobile-app','c761f31bcb2138a06173dcc751fb78c65e416ef7d26420706ea1d1b5b3537038','[\"*\"]','2026-03-30 04:51:16',NULL,'2026-03-30 04:50:31','2026-03-30 04:51:16'),(66,'App\\Models\\User',24,'flutter-mobile-app','83bad2accfb6725683d481c17564f5eb8331705a2a5873a4df4fc764a8582ac5','[\"*\"]','2026-03-30 04:55:23',NULL,'2026-03-30 04:55:05','2026-03-30 04:55:23'),(67,'App\\Models\\User',24,'flutter-mobile-app','eef13ad563450a9956ddb8aed06e6b55e27fae8aec195ce71626ebdfd9956e76','[\"*\"]','2026-03-30 05:11:17',NULL,'2026-03-30 05:00:20','2026-03-30 05:11:17'),(68,'App\\Models\\User',24,'flutter-mobile-app','a74bdcec2cc24180ddc7a46497edb8a4001a6d507514a2a855c6c227fd64ed42','[\"*\"]','2026-03-30 05:22:53',NULL,'2026-03-30 05:20:58','2026-03-30 05:22:53'),(69,'App\\Models\\User',24,'flutter-mobile-app','7343028617bcc5907529184246be2b341d27451b8ee806ec223c1541e6e90d1c','[\"*\"]','2026-03-30 05:23:50',NULL,'2026-03-30 05:23:16','2026-03-30 05:23:50'),(70,'App\\Models\\User',24,'flutter-mobile-app','a8c90d9e57d05f8a81ed5d33e4389d3bdc63534ffbfe34d38a378fb52abddec0','[\"*\"]','2026-03-30 05:24:52',NULL,'2026-03-30 05:24:38','2026-03-30 05:24:52'),(71,'App\\Models\\User',24,'flutter-mobile-app','b92666554ec5498f2fea2601b2ded42408c60b0ec789d23fcff2ca364baf1711','[\"*\"]','2026-03-30 05:25:37',NULL,'2026-03-30 05:25:15','2026-03-30 05:25:37'),(72,'App\\Models\\User',24,'flutter-mobile-app','d6fd20b7ae9c63218f1bb5e558c7dd92227625f68f92e8e0460959a9a91744b2','[\"*\"]','2026-03-30 05:34:19',NULL,'2026-03-30 05:25:52','2026-03-30 05:34:19'),(73,'App\\Models\\User',24,'flutter-mobile-app','8d8089a4accacfd98c2931c1c1a314c346a9d85ddbe27fb1b9c114263d11671f','[\"*\"]','2026-03-30 05:40:24',NULL,'2026-03-30 05:39:15','2026-03-30 05:40:24'),(74,'App\\Models\\User',24,'flutter-mobile-app','d4b51e21f4c6f7c87fc4a67d889d49a00b38f365b57f2edaa1b1348b238846be','[\"*\"]','2026-03-30 05:47:18',NULL,'2026-03-30 05:41:57','2026-03-30 05:47:18'),(75,'App\\Models\\User',24,'flutter-mobile-app','76516cc965c15d5bc616a291c7e61739f9f9eaa3a5acc3098006c729c0bb70d5','[\"*\"]','2026-03-30 05:50:31',NULL,'2026-03-30 05:47:51','2026-03-30 05:50:31'),(76,'App\\Models\\User',24,'flutter-mobile-app','d7b74ad2992d14a7542ba3f17d8790798eb897aeabcecd2aa846c1b39863ec25','[\"*\"]','2026-03-30 12:06:50',NULL,'2026-03-30 12:06:40','2026-03-30 12:06:50'),(77,'App\\Models\\User',32,'flutter-mobile-app','9f5346af29fc815577e84711eafcf9512ee430188a3ca3cf253ab5cd811bde91','[\"*\"]','2026-03-30 14:46:46',NULL,'2026-03-30 14:44:13','2026-03-30 14:46:46'),(78,'App\\Models\\User',32,'flutter-mobile-app','fa599c256fd812889e806759fb08bdff24be2d3819a60561eee7625a9ec4a729','[\"*\"]','2026-03-30 14:51:53',NULL,'2026-03-30 14:51:50','2026-03-30 14:51:53'),(79,'App\\Models\\User',32,'flutter-mobile-app','7292da335a5eaf43cfecd78714dcece2eb52093a841f7eb99983e5e80b216512','[\"*\"]','2026-03-30 14:57:54',NULL,'2026-03-30 14:57:54','2026-03-30 14:57:54'),(80,'App\\Models\\User',24,'flutter-mobile-app','9b276e63d1e55cf8d9f217c923a43bb23ff51e85ead638ad1c948c6b67dfc6de','[\"*\"]','2026-03-30 15:01:15',NULL,'2026-03-30 14:59:56','2026-03-30 15:01:15'),(81,'App\\Models\\User',24,'flutter-mobile-app','a2090c276dbec72cf7bb5191040fddf15f66763480aa3e8899788a2353f87e25','[\"*\"]','2026-03-30 15:04:47',NULL,'2026-03-30 15:01:33','2026-03-30 15:04:47'),(82,'App\\Models\\User',32,'flutter-mobile-app','f4761fc2311f0786435aa659f9c5f09a9924619eac0b0160873fc1575c03c513','[\"*\"]','2026-03-30 15:05:51',NULL,'2026-03-30 15:05:50','2026-03-30 15:05:51'),(83,'App\\Models\\User',24,'flutter-mobile-app','8a2822c937b085b5fca60359ddade7ff85595a67d460e6fbee266a87e5fc3536','[\"*\"]','2026-03-30 15:30:29',NULL,'2026-03-30 15:26:19','2026-03-30 15:30:29'),(84,'App\\Models\\User',32,'flutter-mobile-app','de7aa69805a08e12060c6866b41ef295a983b816939ab992ea2ed4053d55f7de','[\"*\"]','2026-03-30 15:31:45',NULL,'2026-03-30 15:31:44','2026-03-30 15:31:45'),(85,'App\\Models\\User',32,'flutter-mobile-app','8ae30bb91b58ea120ee30ea95ff1fdb96529bd3df5f86afe8629ce8d922a1c51','[\"*\"]','2026-03-31 04:20:01',NULL,'2026-03-31 04:19:58','2026-03-31 04:20:01'),(86,'App\\Models\\User',32,'flutter-mobile-app','5ecb2b7c2f6645e2cc0a3f3dc09d2314ae4f4a23ac46868f1df5fe7b724787f8','[\"*\"]','2026-03-31 04:30:51',NULL,'2026-03-31 04:30:49','2026-03-31 04:30:51'),(87,'App\\Models\\User',32,'flutter-mobile-app','a8b403ca32bc652e73c6d64194eb68a33fc07a340e225dd298d181585a3e080e','[\"*\"]','2026-03-31 05:20:39',NULL,'2026-03-31 05:20:37','2026-03-31 05:20:39'),(88,'App\\Models\\User',32,'flutter-mobile-app','5da5e62bd231b18d62a393bb633f6aeb220f4d61b4f81a5cec8b4b304969e847','[\"*\"]','2026-03-31 05:36:52',NULL,'2026-03-31 05:36:23','2026-03-31 05:36:52'),(89,'App\\Models\\User',32,'face-login','bd3b5b34568134924718911d3b1b188eb393478e7da902c2a451f4396a7562f3','[\"*\"]','2026-03-31 05:37:57',NULL,'2026-03-31 05:37:20','2026-03-31 05:37:57'),(90,'App\\Models\\User',32,'face-login','969af8943a1a02e5532cf9f8341056df74a1d0e13d1ab405060554e6bca7eb84','[\"*\"]','2026-03-31 05:39:00',NULL,'2026-03-31 05:38:51','2026-03-31 05:39:00'),(91,'App\\Models\\User',32,'face-login','b6a6f5a2f6badfc598575ca183c413188af0a3a1dd256147c56ec3b670339026','[\"*\"]','2026-03-31 05:39:48',NULL,'2026-03-31 05:39:47','2026-03-31 05:39:48'),(92,'App\\Models\\User',32,'flutter-mobile-app','35282e08e49f9c90a94f602bbc54f1ba9372f85226e08bacca76abce7cc6f44d','[\"*\"]','2026-03-31 05:41:30',NULL,'2026-03-31 05:41:23','2026-03-31 05:41:30'),(93,'App\\Models\\User',32,'face-login','95d95f9306c5c89d30177d57b82a7d6d809a890d001666dadc10e499666853db','[\"*\"]','2026-03-31 05:42:04',NULL,'2026-03-31 05:42:02','2026-03-31 05:42:04'),(94,'App\\Models\\User',32,'face-login','e0b83c0ecdaac272751f57c0436ed399f9177768479cb146df0f75865be947bb','[\"*\"]','2026-03-31 13:33:44',NULL,'2026-03-31 13:33:37','2026-03-31 13:33:44'),(95,'App\\Models\\User',32,'face-login','5211c4fe71219a8b9b1a19f2f4ca4e96ee8e9b51b0709284264ef26eadedfe85','[\"*\"]','2026-03-31 13:34:04',NULL,'2026-03-31 13:34:00','2026-03-31 13:34:04'),(96,'App\\Models\\User',32,'face-login','2a85f99499a656a9f42f19282f4b1b6e068cec2200680f47c37daad7b30e59a4','[\"*\"]','2026-03-31 13:34:32',NULL,'2026-03-31 13:34:29','2026-03-31 13:34:32'),(97,'App\\Models\\User',24,'flutter-mobile-app','73bf912910af2d2f53129fecdbf52467c6dca75f8554646d48dc9c3467dfa128','[\"*\"]','2026-03-31 13:36:02',NULL,'2026-03-31 13:36:00','2026-03-31 13:36:02'),(98,'App\\Models\\User',32,'face-login','c355a11c98f293b885ec803e045bf10923485b6c48b5058bca11811f662b04e4','[\"*\"]','2026-03-31 13:51:37',NULL,'2026-03-31 13:51:35','2026-03-31 13:51:37'),(99,'App\\Models\\User',32,'face-login','56fa2dddbe3786f7f46240ecb6332a231406b2b06c683cdfd8f74359bbf1fda9','[\"*\"]','2026-03-31 14:36:06',NULL,'2026-03-31 14:13:25','2026-03-31 14:36:06'),(100,'App\\Models\\User',24,'flutter-mobile-app','223599cdf95eaafd074494a3d7293a4e70cb54aa94a2ce1b012af988636782e8','[\"*\"]','2026-03-31 14:38:54',NULL,'2026-03-31 14:38:03','2026-03-31 14:38:54'),(101,'App\\Models\\User',32,'face-login','7a6d04251ff6991b3d64686853d0fc372d616b125d0cfd1d3f217f73be959202','[\"*\"]','2026-03-31 14:39:14',NULL,'2026-03-31 14:39:13','2026-03-31 14:39:14'),(102,'App\\Models\\User',33,'flutter-mobile-app','7caf69be7a847aaaa7ea0addddaca780a576796b1f931a7d44b5adf2e224cd78','[\"*\"]','2026-03-31 14:40:40',NULL,'2026-03-31 14:40:27','2026-03-31 14:40:40'),(103,'App\\Models\\User',33,'flutter-mobile-app','7e558f87d676310b8e9c925b6dd166118643f7dab767cae756d7881286010703','[\"*\"]','2026-03-31 14:43:02',NULL,'2026-03-31 14:43:01','2026-03-31 14:43:02'),(104,'App\\Models\\User',33,'flutter-mobile-app','4102636e314d94fe8362f2ffab17460744be6b9c70eae882853954bfda69c6be','[\"*\"]','2026-03-31 14:56:02',NULL,'2026-03-31 14:55:38','2026-03-31 14:56:02'),(105,'App\\Models\\User',33,'face-login','0912ab69b69928830727688849b84cdcdb13c72dff436c37f5cd2b791543865a','[\"*\"]','2026-03-31 14:57:06',NULL,'2026-03-31 14:57:06','2026-03-31 14:57:06'),(106,'App\\Models\\User',33,'face-login','1ea05449f8c7984fe0fa2cc338c741f5ce6cc4b99f1cab8b9f2c4c8fdb69320b','[\"*\"]','2026-03-31 14:57:53',NULL,'2026-03-31 14:57:52','2026-03-31 14:57:53'),(107,'App\\Models\\User',33,'face-login','d9b4ab5082897e906d716ff784fd8fd1e90e3d12986b38ef2ad723cc557e8c1d','[\"*\"]','2026-03-31 15:10:09',NULL,'2026-03-31 15:09:26','2026-03-31 15:10:09'),(108,'App\\Models\\User',33,'face-login','a4d816ff76d9d2cd8a716d066a2e020e4ae6c02be1f76661840e89b5791ea879','[\"*\"]','2026-03-31 15:18:14',NULL,'2026-03-31 15:17:58','2026-03-31 15:18:14');

/*Table structure for table `plans` */

DROP TABLE IF EXISTS `plans`;

CREATE TABLE `plans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plan_name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `sessions_per_week` int(11) DEFAULT 0,
  `monthly_price` decimal(10,2) DEFAULT 0.00,
  `unlimited_flag` tinyint(1) DEFAULT 0,
  `billing_cycle` enum('monthly','quarterly','yearly') DEFAULT 'monthly',
  `active_flag` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `plans` */

/*Table structure for table `role_permissions` */

DROP TABLE IF EXISTS `role_permissions`;

CREATE TABLE `role_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role` enum('admin','instructor','staff','parent') NOT NULL,
  `module` varchar(100) NOT NULL,
  `can_view` tinyint(1) DEFAULT 0,
  `can_create` tinyint(1) DEFAULT 0,
  `can_edit` tinyint(1) DEFAULT 0,
  `can_delete` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_role_module` (`role`,`module`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `role_permissions` */

insert  into `role_permissions`(`id`,`role`,`module`,`can_view`,`can_create`,`can_edit`,`can_delete`) values (2,'staff','dashboard',1,1,0,0),(3,'staff','students',1,0,0,0),(4,'staff','parents',1,0,0,0),(5,'staff','instructors',1,0,0,0),(6,'staff','classes',1,0,0,0),(7,'staff','branches',1,0,0,0),(8,'staff','attendance',1,0,0,0),(9,'staff','billing',1,0,0,0),(10,'staff','certificates',1,0,0,0),(11,'staff','competitions',1,0,0,0),(12,'staff','announcements',1,0,0,0),(13,'staff','chat',1,0,0,0),(14,'staff','reports',1,0,0,0),(15,'staff','users',0,1,0,0),(16,'staff','settings',0,1,1,0),(17,'admin','dashboard',1,1,1,1),(18,'admin','students',1,1,1,1),(19,'admin','parents',1,1,1,1),(20,'admin','instructors',1,1,1,1),(21,'admin','classes',1,1,1,1),(22,'admin','branches',1,1,1,1),(23,'admin','attendance',1,1,1,1),(24,'admin','billing',1,1,1,1),(25,'admin','certificates',1,1,1,1),(26,'admin','competitions',1,1,1,1),(27,'admin','announcements',1,1,1,1),(28,'admin','chat',1,1,1,1),(29,'admin','reports',1,1,1,1),(30,'admin','users',1,1,1,1),(31,'admin','settings',1,1,1,1);

/*Table structure for table `sessions` */

DROP TABLE IF EXISTS `sessions`;

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `sessions` */

insert  into `sessions`(`id`,`user_id`,`ip_address`,`user_agent`,`payload`,`last_activity`) values ('4UTTKsmxNEpHikr4Np4RdAGeeJ4mDyn86ThkkIiv',8,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiYzBITWxWN05WNEZtTGhhZXNmNGlGandaMFljYmVIZWliaEU0a1dMQSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9jbGFzc2VzIjtzOjU6InJvdXRlIjtzOjEzOiJjbGFzc2VzLmluZGV4Ijt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6ODt9',1775551508),('L3B1Ppjk7oSEOs0RJjj2wLtExMoEQ61XTtUAkbka',7,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiQVlFRFJpSTVETGdtOTlEMmVpd24wbW1XOWlQdzhjNDhQcHlwSW5nYSI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjUyOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvc2V0dGluZ3Mvcm9sZXMtYW5kLXBlcm1pc3Npb25zIjtzOjU6InJvdXRlIjtzOjMyOiJzZXR0aW5ncy5yb2xlcy1wZXJtaXNzaW9ucy5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjc7fQ==',1775551527);

/*Table structure for table `skill_checklist` */

DROP TABLE IF EXISTS `skill_checklist`;

CREATE TABLE `skill_checklist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `belt_level` varchar(50) NOT NULL,
  `skill_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `skill_checklist` */

/*Table structure for table `student_evaluations` */

DROP TABLE IF EXISTS `student_evaluations`;

CREATE TABLE `student_evaluations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `instructor_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `evaluation_date` date NOT NULL,
  `technique_score` int(11) DEFAULT NULL CHECK (`technique_score` between 0 and 100),
  `discipline_score` int(11) DEFAULT NULL CHECK (`discipline_score` between 0 and 100),
  `fitness_score` int(11) DEFAULT NULL CHECK (`fitness_score` between 0 and 100),
  `sparring_score` int(11) DEFAULT NULL CHECK (`sparring_score` between 0 and 100),
  `notes` text DEFAULT NULL,
  `belt_ready_flag` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  KEY `instructor_id` (`instructor_id`),
  KEY `class_id` (`class_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `student_evaluations` */

/*Table structure for table `student_skill_progress` */

DROP TABLE IF EXISTS `student_skill_progress`;

CREATE TABLE `student_skill_progress` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `skill_id` int(11) NOT NULL,
  `instructor_id` int(11) NOT NULL,
  `status` enum('pending','in_progress','completed','failed') NOT NULL,
  `checked_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  KEY `skill_id` (`skill_id`),
  KEY `instructor_id` (`instructor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `student_skill_progress` */

/*Table structure for table `student_subscriptions` */

DROP TABLE IF EXISTS `student_subscriptions`;

CREATE TABLE `student_subscriptions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `status` enum('active','expired','cancelled','suspended') DEFAULT 'active',
  `auto_renew_flag` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `student_subscriptions` */

/*Table structure for table `students` */

DROP TABLE IF EXISTS `students`;

CREATE TABLE `students` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `branch_id` int(11) NOT NULL,
  `student_code` varchar(50) DEFAULT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `birthdate` date NOT NULL,
  `gender` enum('male','female','other') NOT NULL,
  `photo_url` varchar(255) DEFAULT NULL,
  `face_photo` varchar(255) DEFAULT NULL,
  `current_belt` varchar(50) DEFAULT NULL,
  `join_date` date NOT NULL,
  `status` enum('active','inactive','suspended') DEFAULT 'active',
  `medical_notes` text DEFAULT NULL,
  `allergies` text DEFAULT NULL,
  `emergency_contact_name` varchar(150) DEFAULT NULL,
  `emergency_contact_mobile` varchar(20) DEFAULT NULL,
  `primary_parent_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `student_code` (`student_code`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `students` */

insert  into `students`(`id`,`user_id`,`branch_id`,`student_code`,`first_name`,`last_name`,`middle_name`,`birthdate`,`gender`,`photo_url`,`face_photo`,`current_belt`,`join_date`,`status`,`medical_notes`,`allergies`,`emergency_contact_name`,`emergency_contact_mobile`,`primary_parent_id`,`created_at`) values (1,NULL,1,'tkd-00001','Mechelle','Stoneman','Malakas','2026-03-13','male',NULL,NULL,'1','2026-03-13','active',NULL,NULL,'Tyesha Freitag','0915-123-4567',10,'2026-03-13 10:42:11'),(2,NULL,1,'tkd-00002','Hyunwoo','Lee',NULL,'2026-04-07','male',NULL,NULL,'4','2026-03-24','active','testing','testing','dwajhduwyhaddfiuopghwascliugjkbhvdawbkljvdfwa','09499374690',10,'2026-03-24 15:49:41'),(3,NULL,4,'tkd-00003','Juan','Dela Cruz',NULL,'2008-06-11','male',NULL,NULL,'11','2026-03-24','active','testing','allergic to peanuts','Mr. Asimo','0999999999',20,'2026-03-24 16:03:29'),(4,NULL,4,'tkd-00004','Nicky','Minaj',NULL,'2005-03-24','female',NULL,NULL,'10','2026-03-24','active','Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.','blha blah blha blha','eddu manzano','09207020903',17,'2026-03-24 16:25:33'),(5,NULL,1,NULL,'Juna','reyes',NULL,'2003-06-19','female',NULL,NULL,'3','2026-03-27','active','fracture','N/A','Roberto Reyes','09287364831',22,'2026-03-27 12:01:12'),(6,NULL,1,NULL,'joselito','Reyes',NULL,'2002-03-12','male',NULL,NULL,'3','2026-03-28','active','N/A','N/A','Menard Cruz','09287364831',19,'2026-03-28 17:30:03'),(7,NULL,2,NULL,'Harith','Requestas',NULL,'2018-07-13','male',NULL,NULL,'3','2026-03-30','active','N/A','N/A','Menard Cruz','09287364831',10,'2026-03-30 20:42:57'),(8,NULL,1,NULL,'Melai','Alvior',NULL,'2015-02-03','female',NULL,NULL,'3','2026-03-30','active','N/A','N/A','Menard Cruz','09287364831',19,'2026-03-30 21:14:36'),(9,NULL,1,NULL,'Lenard','Cruz',NULL,'2015-06-24','male',NULL,NULL,'5','2026-03-30','active','N/A','N/A','Menard Cruz','09287364831',20,'2026-03-30 21:21:29'),(10,NULL,1,NULL,'Berto','Cruz',NULL,'2014-02-06','male',NULL,NULL,'7','2026-03-30','active','N/A','N/A','Menard Cruz','09287364831',10,'2026-03-30 21:25:32'),(11,29,1,'TKD-0CWXF','benedick1','Caber',NULL,'2014-02-20','male',NULL,NULL,'4','2026-03-30','active','N/A','N/A','Roberto Reyes','09287364831',16,'2026-03-30 13:48:47'),(12,30,1,'TKD-KDDI5','Cyril','maldives',NULL,'2012-03-30','female',NULL,NULL,'2','2026-03-30','active','N/A','N/A','Dwayne wade','09218734930',10,'2026-03-30 14:11:24'),(13,31,1,'TKD-RZDVA','Lester','Reyes',NULL,'2017-05-17','male',NULL,NULL,'7','2026-03-30','active','N/A','N/A','kelson dee','09283743892',16,'2026-03-30 14:21:45'),(15,33,1,'TKD-DPMSM','Triever','Estareja',NULL,'2015-11-27','male',NULL,'face-photos/691sVFvLazx3Lp74hLgzJGEeeQEDkJU9huBHDXEx.jpg','9','2026-03-31','active','N/A','N/A','Emmalyn Ruga','09283948172',16,'2026-03-31 14:35:10'),(17,35,5,'TKD-I9FFF','Dennis','Cruz',NULL,'2026-04-01','male',NULL,NULL,'1','2026-04-01','active',NULL,NULL,'Mr. Asimo','09399012345',20,'2026-04-01 01:07:01'),(18,36,1,'26-00001','Princess Dianne','Garay',NULL,'2003-08-24','female',NULL,NULL,'11','2026-04-01','active',NULL,NULL,'Mr. Asimo','0999999999',16,'2026-04-01 01:47:23'),(19,37,2,'26-00002','Nathaniel','Custodio',NULL,'2003-08-03','male',NULL,NULL,'11','2026-04-01','active',NULL,NULL,'Mr. Asimo','0999999999',17,'2026-04-01 01:51:02');

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `branch_id` int(11) NOT NULL,
  `role` enum('admin','instructor','parent','staff','student') NOT NULL,
  `username` varchar(160) NOT NULL,
  `fname` varchar(150) NOT NULL,
  `lname` varchar(150) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `photo_url` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `fk_branch` (`branch_id`),
  CONSTRAINT `fk_branch` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `users` */

insert  into `users`(`id`,`branch_id`,`role`,`username`,`fname`,`lname`,`email`,`mobile`,`password`,`photo_url`,`status`,`last_login_at`,`created_at`,`updated_at`) values (7,2,'admin','1234admin','Nataniel','Herras','testing@gmail.com','09499333000','$2y$12$93a4KdYWPG1OSuQe0DtOyOzD7dW5HLaVLEMslAQGuV8Hq.knHl9Dq','profile-photos/1774326137_h9uoElzpCo.png',1,'2026-04-07 16:44:25','2026-03-03 06:58:17','2026-04-07 16:44:25'),(8,2,'staff','admin','Princess','Valdez','Valdez@gmail.com','09444444444','$2y$12$TX1QJw1.7///Sj0I93nSgOpr2rCLkDGo6Am.sPbOxtIYW3SSmxPTm','profile-photos/1775534251_LJ7GSmkN6T.jpg',1,'2026-04-07 16:43:37','2026-03-03 07:02:26','2026-04-07 16:43:37'),(10,1,'parent','parent1234','Cletus','Christopher','magulang123@gmail.com','09329329939','$2y$12$I8ofrlo50mdWeSWCOxac8.7T4AKyJILkquPZJS5KqxrR7i4AoLP5q',NULL,1,'2026-03-11 03:58:31','2026-03-10 08:48:46','2026-03-25 02:19:21'),(11,1,'instructor','jdoe','John','Doe','jdoe@example.com','09171234567','hashed_pw1','',0,NULL,'2026-03-17 11:55:06','2026-03-18 16:57:47'),(12,1,'instructor','asmith','Alice','Smith','asmith@example.com','09181234567','hashed_pw2','',0,NULL,'2026-03-17 11:55:06','2026-03-18 16:57:46'),(13,2,'instructor','bchan','Brian','Chan','bchan@example.com','09191234567','hashed_pw3','',0,NULL,'2026-03-17 11:55:06','2026-03-18 16:57:46'),(14,2,'instructor','cmendoza','Carla','Mendoza','cmendoza@example.com','09201234567','hashed_pw4','',0,NULL,'2026-03-17 11:55:06','2026-03-18 16:57:45'),(15,3,'instructor','dlee','David','Lee','dlee@example.com','09211234567','hashed_pw5','',0,NULL,'2026-03-17 11:55:06','2026-03-18 16:57:43'),(16,1,'parent','msantos','Maria','Santos','msantos@example.com','09170000046','hashed_pw46','',0,NULL,'2026-03-17 12:05:55','2026-03-18 16:57:50'),(17,1,'parent','jcruz','Jose','Cruz','jcruz@example.com','09170000047','hashed_pw47','',0,NULL,'2026-03-17 12:05:55','2026-03-18 16:57:42'),(18,2,'parent','atan','Anna','Tan','atan@example.com','09170000048','hashed_pw48','',0,NULL,'2026-03-17 12:05:55','2026-03-18 16:57:43'),(19,2,'parent','cgarcia','Carlos','Garcia','cgarcia@example.com','09170000049','hashed_pw49','',0,NULL,'2026-03-17 12:05:55','2026-03-18 16:57:41'),(20,3,'parent','glee','Grace','Lee','glee@example.com','09170000050','hashed_pw50','',0,NULL,'2026-03-17 12:05:55','2026-03-18 16:57:41'),(21,5,'instructor','dwadwadawd','Lady Joy','Porrs','porras@porrs','09171234567','$2y$12$dvFe7NxBRoycJBI22wY0eenI/EWHoeYRqpGnls1u9tTvOOEZcq5YO',NULL,1,NULL,'2026-03-24 17:11:38','2026-03-25 03:09:32'),(22,1,'parent','','Roberto','Magdangal','roberto@gmail.com','09876521465','$2y$12$aFdvkK8AAqqxbzujur.N2umCcB6gdPBYm7wRrSBBw7EtBkZZCNAlW',NULL,1,NULL,'2026-03-26 13:29:58','2026-03-26 13:29:58'),(23,1,'parent','','Julious','Magdangal','magdangal@gmail.com','09876521465','$2y$12$QOXNjpOolI3hwi5b7HMyRu3Fm51dRzSSvySi4skxWUWEHZwQH2jxS',NULL,1,NULL,'2026-03-26 13:38:56','2026-04-06 10:14:35'),(24,1,'instructor','Draken','Eduard','Estareja','eduardestarejajr@gmail.com','09826717854','$2y$12$cmVhWkunC.vtJXlnp9s7aem1.OuqCGbF5nmSL.nUnYK2VO84Nb9i.',NULL,1,NULL,'2026-03-26 13:40:09','2026-03-26 22:06:58'),(25,2,'instructor','janjan','john','alvior','john@gmail.com','09874783672','$2y$12$ZFbpX2RZJy2dRBGBp1r0AeJ.cZY8A0TDSr0dYJKNONBnoM03/sgVm',NULL,1,NULL,'2026-03-26 14:34:39','2026-03-26 14:34:39'),(26,3,'instructor','everr','triever','ruga','triever@gmail.com','0987462713','$2y$12$Q1iuc99JEMX7.4f4aofFM.D8b3OS7BtCkHnt5z2auYJalZyadhMcG',NULL,1,NULL,'2026-03-26 15:10:13','2026-03-26 15:10:13'),(27,1,'instructor','micah','Mica','Barrientos','mica@gmail.com','0987462713','$2y$12$UOzLdBD3Sw1j0hb6ADHCyuCPIyETKlGivemR0mX47SOXlOqkAz4Ue',NULL,1,NULL,'2026-03-27 15:28:29','2026-03-27 15:28:29'),(28,1,'','benedick@gmail.com','benedick','Caber','benedick@gmail.com',NULL,'$2y$12$bAIdXMSW1Rf2njYkCk6Heunh.4Hb8935tQvAvijlSKaE0zii1zRKW',NULL,0,NULL,'2026-03-30 13:34:47','2026-03-30 13:34:47'),(29,1,'','ben@gmail.com','benedick1','Caber','ben@gmail.com',NULL,'$2y$12$ARrJNOobw2B0k2vMT0LxDumeBtYQo3hcmjHWT82tQ7WRQkGU3rLw2',NULL,0,NULL,'2026-03-30 13:48:47','2026-03-30 13:48:47'),(30,1,'','cyril@gmail.com','Cyril','maldives','cyril@gmail.com',NULL,'$2y$12$.eP1IrTKEj/ZM9/bcKxED.dnybf4KT163gFT42sb/XtmJEEwvFUjC',NULL,0,NULL,'2026-03-30 14:11:24','2026-03-30 14:11:24'),(31,1,'student','lester@gmail.com','Lester','Reyes','lester@gmail.com',NULL,'$2y$12$jLU9r/ASwj.R.SAZe5TG3ewjdgNydYSDBdxH5KzJVQue6.DfMIHdO',NULL,0,NULL,'2026-03-30 14:21:45','2026-03-30 14:21:45'),(33,1,'student','triever.estareja356','Triever','Estareja','triever2015@gmail.com',NULL,'$2y$12$ppjllEgwXbFYNJZO0FpvEO.Z0kV8Tkrwh56xIuDWKJuI1QxsWouWu',NULL,0,NULL,'2026-03-31 14:35:10','2026-03-31 14:35:10'),(34,7,'student','testing.testing968','testing','testing','testing123@gmail.com','0999999999','$2y$12$2UMyyNk/DrQhPdM/XKnFKeaqj42Yz/baLysGC8oobAU01ye2ttp82',NULL,1,NULL,'2026-04-01 01:00:00','2026-04-01 01:00:00'),(35,5,'student','dennis.cruz939','Dennis','Cruz','dennis.cruz85@domain.ph','09399012345','$2y$12$rX5CpaKJDqJagH123SJKKeOeapbA6J5PqzQI8ddXgXbmM0xbPdSG6',NULL,1,NULL,'2026-04-01 01:07:01','2026-04-01 01:07:01'),(36,1,'student','princess dianne.garay161','Princess Dianne','Garay','DiannePrincess@gmail.com','0999999999','$2y$12$v3zed601e19jfrowK.ZCg.jTpJ5fPoG00lnrXFZtmrt5w6J813RtC',NULL,1,NULL,'2026-04-01 01:47:22','2026-04-01 01:47:22'),(37,2,'student','nathaniel.custodio815','Nathaniel','Custodio','Nathaniel@gmail.com','0999999999','$2y$12$7O.mtCLHVbwOjMTcdkTVPuBPfcFyyTgxm43Y7qCw/.I5f//Lf/xe.',NULL,1,NULL,'2026-04-01 01:51:02','2026-04-01 01:51:02'),(38,5,'admin','','Garay','Princess','Princess@gmail.com','09473258232','$2y$12$9P5OQRWVYIpV8RRrZq3/QeDthaJLTTqhh20aVnl56DWf4pZ/4AjS2','profile-photos/1775030025_MHmDaGY8El.jpg',1,NULL,'2026-04-01 15:53:45','2026-04-01 15:53:45');

/*Table structure for table `parentviews` */

DROP TABLE IF EXISTS `parentviews`;

/*!50001 DROP VIEW IF EXISTS `parentviews` */;
/*!50001 DROP TABLE IF EXISTS `parentviews` */;

/*!50001 CREATE TABLE  `parentviews`(
 `id` int(11) ,
 `name` varchar(301) ,
 `mobile` varchar(20) ,
 `status` tinyint(1) 
)*/;

/*Table structure for table `student_overview` */

DROP TABLE IF EXISTS `student_overview`;

/*!50001 DROP VIEW IF EXISTS `student_overview` */;
/*!50001 DROP TABLE IF EXISTS `student_overview` */;

/*!50001 CREATE TABLE  `student_overview`(
 `id` int(11) ,
 `student_code` varchar(50) ,
 `student_name` varchar(201) ,
 `branch_id` int(11) ,
 `belt_id` bigint(20) unsigned ,
 `current_belt` varchar(255) ,
 `status` enum('active','inactive','suspended') ,
 `parent_name` varchar(301) ,
 `balance` decimal(32,2) ,
 `attendance` decimal(24,0) 
)*/;

/*Table structure for table `vwattendancelog` */

DROP TABLE IF EXISTS `vwattendancelog`;

/*!50001 DROP VIEW IF EXISTS `vwattendancelog` */;
/*!50001 DROP TABLE IF EXISTS `vwattendancelog` */;

/*!50001 CREATE TABLE  `vwattendancelog`(
 `id` int(100) ,
 `branch` varchar(255) ,
 `student_code` varchar(50) ,
 `student_name` varchar(201) ,
 `class_name` varchar(150) ,
 `checkin_time` datetime ,
 `checkout_time` datetime ,
 `method` enum('face','qr','manual') ,
 `confidence_score` varchar(50) ,
 `device_id` int(10) ,
 `instructor_name` varchar(201) ,
 `status` int(1) 
)*/;

/*Table structure for table `vw_attendance_reports` */

DROP TABLE IF EXISTS `vw_attendance_reports`;

/*!50001 DROP VIEW IF EXISTS `vw_attendance_reports` */;
/*!50001 DROP TABLE IF EXISTS `vw_attendance_reports` */;

/*!50001 CREATE TABLE  `vw_attendance_reports`(
 `id` int(100) ,
 `student` varchar(201) ,
 `date` date ,
 `time_in` time ,
 `time_out` time ,
 `status` varchar(7) ,
 `instructor` varchar(201) ,
 `class` varchar(150) 
)*/;

/*Table structure for table `vw_billing_summary` */

DROP TABLE IF EXISTS `vw_billing_summary`;

/*!50001 DROP VIEW IF EXISTS `vw_billing_summary` */;
/*!50001 DROP TABLE IF EXISTS `vw_billing_summary` */;

/*!50001 CREATE TABLE  `vw_billing_summary`(
 `id` int(11) ,
 `student` varchar(201) ,
 `parent` varchar(301) ,
 `total_bill` decimal(32,2) ,
 `total_paid` decimal(33,2) ,
 `remaining_balance` decimal(32,2) ,
 `due_date` date 
)*/;

/*Table structure for table `vw_competition_entries` */

DROP TABLE IF EXISTS `vw_competition_entries`;

/*!50001 DROP VIEW IF EXISTS `vw_competition_entries` */;
/*!50001 DROP TABLE IF EXISTS `vw_competition_entries` */;

/*!50001 CREATE TABLE  `vw_competition_entries`(
 `id` int(11) ,
 `competition_id` int(11) ,
 `student_name` varchar(201) ,
 `instructor_name` varchar(201) ,
 `category` varchar(100) ,
 `division` varchar(100) ,
 `result` enum('win','loss','draw','pending') ,
 `medal` enum('gold','silver','bronze','none') ,
 `remarks` text 
)*/;

/*Table structure for table `vw_instructor_load` */

DROP TABLE IF EXISTS `vw_instructor_load`;

/*!50001 DROP VIEW IF EXISTS `vw_instructor_load` */;
/*!50001 DROP TABLE IF EXISTS `vw_instructor_load` */;

/*!50001 CREATE TABLE  `vw_instructor_load`(
 `id` bigint(20) unsigned ,
 `instructor` varchar(201) ,
 `date` date ,
 `class` varchar(150) ,
 `total_students` bigint(21) ,
 `time` varchar(23) ,
 `hours` bigint(21) 
)*/;

/*Table structure for table `vw_revenue_reports` */

DROP TABLE IF EXISTS `vw_revenue_reports`;

/*!50001 DROP VIEW IF EXISTS `vw_revenue_reports` */;
/*!50001 DROP TABLE IF EXISTS `vw_revenue_reports` */;

/*!50001 CREATE TABLE  `vw_revenue_reports`(
 `id` int(11) ,
 `date` datetime ,
 `student` varchar(201) ,
 `payment_type` varchar(7) ,
 `method` enum('cash','gcash','card','bank') ,
 `amount_paid` decimal(12,2) ,
 `receipt_no` varchar(100) 
)*/;

/*View structure for view parentviews */

/*!50001 DROP TABLE IF EXISTS `parentviews` */;
/*!50001 DROP VIEW IF EXISTS `parentviews` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `parentviews` AS select `p`.`id` AS `id`,concat(`u`.`fname`,' ',`u`.`lname`) AS `name`,`u`.`mobile` AS `mobile`,`u`.`status` AS `status` from (`parents` `p` join `users` `u` on(`p`.`user_id` = `u`.`id`)) */;

/*View structure for view student_overview */

/*!50001 DROP TABLE IF EXISTS `student_overview` */;
/*!50001 DROP VIEW IF EXISTS `student_overview` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `student_overview` AS select `s`.`id` AS `id`,`s`.`student_code` AS `student_code`,concat(`s`.`first_name`,' ',`s`.`last_name`) AS `student_name`,`s`.`branch_id` AS `branch_id`,`b`.`id` AS `belt_id`,`b`.`name` AS `current_belt`,`s`.`status` AS `status`,concat(`u`.`fname`,' ',`u`.`lname`) AS `parent_name`,ifnull((select sum(`i`.`total_due`) from `invoices` `i` where `i`.`student_id` = `s`.`id` and `i`.`status` <> 'paid'),0) AS `balance`,ifnull(round((select count(`al`.`id`) from `attendance_logs` `al` where `al`.`student_id` = `s`.`id`) / nullif((select count(`cs`.`id`) from (`class_sessions` `cs` join `class_students` `cls` on(`cs`.`class_id` = `cls`.`class_id`)) where `cls`.`student_id` = `s`.`id`),0) * 100,0),0) AS `attendance` from ((`students` `s` left join `belt_levels` `b` on(`s`.`current_belt` = `b`.`id`)) left join `users` `u` on(`s`.`primary_parent_id` = `u`.`id`)) */;

/*View structure for view vwattendancelog */

/*!50001 DROP TABLE IF EXISTS `vwattendancelog` */;
/*!50001 DROP VIEW IF EXISTS `vwattendancelog` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vwattendancelog` AS select `a`.`id` AS `id`,`b`.`name` AS `branch`,`s`.`student_code` AS `student_code`,concat(`s`.`first_name`,' ',`s`.`last_name`) AS `student_name`,`c`.`class_name` AS `class_name`,`a`.`checkin_time` AS `checkin_time`,`a`.`checkout_time` AS `checkout_time`,`a`.`method` AS `method`,`a`.`confidence_score` AS `confidence_score`,`a`.`device_id` AS `device_id`,concat(`i`.`fname`,' ',`i`.`lname`) AS `instructor_name`,`a`.`status` AS `status` from (((((`attendance_logs` `a` join `students` `s` on(`s`.`id` = `a`.`student_id`)) join `branches` `b` on(`s`.`branch_id` = `b`.`id`)) join `class_sessions` `cs` on(`cs`.`id` = `a`.`class_session_id`)) join `classes` `c` on(`c`.`id` = `cs`.`class_id`)) join `instructors` `i` on(`c`.`primary_instructor_id` = `i`.`id`)) where `s`.`status` = 'active' order by `a`.`id` desc */;

/*View structure for view vw_attendance_reports */

/*!50001 DROP TABLE IF EXISTS `vw_attendance_reports` */;
/*!50001 DROP VIEW IF EXISTS `vw_attendance_reports` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_attendance_reports` AS select `al`.`id` AS `id`,concat(`s`.`first_name`,' ',`s`.`last_name`) AS `student`,`cs`.`session_date` AS `date`,cast(`al`.`checkin_time` as time) AS `time_in`,cast(`al`.`checkout_time` as time) AS `time_out`,case when cast(`al`.`checkin_time` as time) > `cs`.`start_time` then 'Late' else 'Present' end AS `status`,concat(`i`.`fname`,' ',`i`.`lname`) AS `instructor`,`c`.`class_name` AS `class` from ((((`attendance_logs` `al` join `students` `s` on(`al`.`student_id` = `s`.`id`)) join `class_sessions` `cs` on(`al`.`class_session_id` = `cs`.`id`)) join `classes` `c` on(`cs`.`class_id` = `c`.`id`)) left join `instructors` `i` on(`cs`.`instructor_id` = `i`.`id`)) */;

/*View structure for view vw_billing_summary */

/*!50001 DROP TABLE IF EXISTS `vw_billing_summary` */;
/*!50001 DROP VIEW IF EXISTS `vw_billing_summary` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_billing_summary` AS select `s`.`id` AS `id`,concat(`s`.`first_name`,' ',`s`.`last_name`) AS `student`,concat(`u`.`fname`,' ',`u`.`lname`) AS `parent`,sum(`i`.`amount`) AS `total_bill`,sum(`i`.`amount` - `i`.`total_due`) AS `total_paid`,sum(`i`.`total_due`) AS `remaining_balance`,max(`i`.`due_date`) AS `due_date` from ((`students` `s` left join `users` `u` on(`s`.`primary_parent_id` = `u`.`id`)) left join `invoices` `i` on(`s`.`id` = `i`.`student_id`)) group by `s`.`id` */;

/*View structure for view vw_competition_entries */

/*!50001 DROP TABLE IF EXISTS `vw_competition_entries` */;
/*!50001 DROP VIEW IF EXISTS `vw_competition_entries` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_competition_entries` AS select `c`.`id` AS `id`,`c`.`competition_id` AS `competition_id`,concat(`s`.`first_name`,' ',`s`.`last_name`) AS `student_name`,concat(`i`.`fname`,' ',`i`.`lname`) AS `instructor_name`,`c`.`category` AS `category`,`c`.`division` AS `division`,`c`.`result` AS `result`,`c`.`medal` AS `medal`,`c`.`remarks` AS `remarks` from ((`competition_entries` `c` join `instructors` `i` on(`c`.`instructor_id` = `i`.`id`)) join `students` `s` on(`c`.`student_id` = `s`.`id`)) */;

/*View structure for view vw_instructor_load */

/*!50001 DROP TABLE IF EXISTS `vw_instructor_load` */;
/*!50001 DROP VIEW IF EXISTS `vw_instructor_load` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_instructor_load` AS select `i`.`id` AS `id`,concat(`i`.`fname`,' ',`i`.`lname`) AS `instructor`,`cs`.`session_date` AS `date`,`c`.`class_name` AS `class`,(select count(0) from `class_students` `cls` where `cls`.`class_id` = `c`.`id`) AS `total_students`,concat(`cs`.`start_time`,' - ',`cs`.`end_time`) AS `time`,timestampdiff(HOUR,`cs`.`start_time`,`cs`.`end_time`) AS `hours` from ((`class_sessions` `cs` join `classes` `c` on(`cs`.`class_id` = `c`.`id`)) join `instructors` `i` on(`cs`.`instructor_id` = `i`.`id`)) */;

/*View structure for view vw_revenue_reports */

/*!50001 DROP TABLE IF EXISTS `vw_revenue_reports` */;
/*!50001 DROP VIEW IF EXISTS `vw_revenue_reports` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_revenue_reports` AS select `p`.`id` AS `id`,`p`.`paid_at` AS `date`,concat(`s`.`first_name`,' ',`s`.`last_name`) AS `student`,'Tuition' AS `payment_type`,`p`.`payment_method` AS `method`,`p`.`amount` AS `amount_paid`,`p`.`reference_no` AS `receipt_no` from ((`payments` `p` join `invoices` `i` on(`p`.`invoice_id` = `i`.`id`)) join `students` `s` on(`i`.`student_id` = `s`.`id`)) */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

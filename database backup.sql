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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `attendance_logs` */

insert  into `attendance_logs`(`id`,`student_id`,`class_session_id`,`checkin_time`,`checkout_time`,`method`,`confidence_score`,`recorded_by_user_id`,`device_id`,`status`) values (1,1,201,'2026-03-10 08:00:15','2026-03-10 09:30:20','face','98.7',2,1,1),(2,2,201,'2026-03-10 08:02:40','2026-03-10 09:28:55','qr','',2,1,1),(3,3,201,'2026-03-10 08:10:00','2026-03-10 09:35:10','manual','',1,0,1),(4,4,202,'2026-03-11 10:00:05','2026-03-11 11:30:00','face','95.4',3,2,1),(5,5,202,'2026-03-11 10:01:30','2026-03-11 11:29:45','qr','',3,2,1),(6,6,202,'2026-03-11 10:12:20','0000-00-00 00:00:00','manual','',1,0,1),(7,7,203,'2026-03-12 13:00:10','2026-03-12 14:30:00','face','99.0',2,1,1),(8,8,203,'2026-03-12 13:03:25','2026-03-12 14:28:50','qr','',2,1,1),(9,9,203,'2026-03-12 13:15:00','2026-03-12 14:35:00','manual','',1,0,1),(10,10,204,'2026-03-13 15:00:00','2026-03-13 16:30:30','face','94.2',3,2,1),(11,11,204,'2026-03-13 15:05:45','2026-03-13 16:28:15','qr','',3,2,1),(12,12,204,'2026-03-13 15:20:00','0000-00-00 00:00:00','manual','',1,0,1);

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

insert  into `branches`(`id`,`name`,`code`,`address`,`city`,`province`,`mobile`,`email`,`status`,`created_at`,`updated_at`) values (1,'Quezon City Branch','BR001','123 Katipunan Ave','Quezon City','Metro Manila','09171234567','maria.santos@email.com','active','2026-03-01 08:00:00','2026-03-01 08:00:00'),(2,'Manila Branch','BR002','45 Mabini St','Manila','Metro Manila','09181234568','juan.delacruz@email.com','active','2026-03-02 09:15:00','2026-03-02 09:15:00'),(3,'Cebu Branch','BR003','678 Rizal Blvd','Cebu City','Cebu','09221234569','liza.reyes@email.com','inactive','2026-03-03 10:30:00','2026-03-05 14:20:00'),(4,'Davao Branch','BR004','89 Bonifacio St','Davao City','Davao del Sur','09331234570','roberto.tan@email.com','active','2026-03-04 11:45:00','2026-03-04 11:45:00'),(5,'Iloilo Branch','BR005','12 Lopez Jaena St','Iloilo City','Iloilo','09441234571','angela.mendoza@email.com','active','2026-03-05 13:00:00','2026-03-05 13:00:00'),(7,'Muntinlupa Branch','BR006','Blk 1 lot 2','muntinlupa city','Metro manila','0938292821','testing@gmail.com','active','2026-03-23 14:18:39','2026-03-23 14:18:39');

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
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `class_schedules` */

insert  into `class_schedules`(`id`,`class_id`,`day_of_week`,`start_time`,`end_time`,`created_at`,`updated_at`) values (7,2,'monday','10:00:00','12:00:00','2026-03-25 16:36:51','2026-03-25 16:36:51'),(8,1,'tuesday','13:00:00','16:00:00','2026-03-25 16:36:57','2026-03-25 16:36:57'),(10,5,'wednesday','12:00:00','15:30:00','2026-03-26 12:12:26','2026-03-26 12:12:26'),(12,3,'friday','08:30:00','11:00:00','2026-03-26 17:13:08','2026-03-26 17:13:08');

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
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `class_sessions` */

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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `class_students` */

insert  into `class_students`(`id`,`class_id`,`student_id`,`start_date`,`end_date`,`status`) values (3,2,1,'2026-03-27',NULL,'active'),(4,2,2,'2026-03-26',NULL,'active'),(7,3,1,'2026-03-26',NULL,'active');

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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `classes` */

insert  into `classes`(`id`,`branch_id`,`class_name`,`age_group`,`level`,`max_students`,`primary_instructor_id`,`assistant_instructor_id`,`status`,`created_at`,`updated_at`) values (1,2,'Taekwondo Beginner Basics','24-35','White',20,3,2,'active','2026-03-05 00:00:00','2026-03-25 16:36:57'),(2,1,'White Belt Fundamentals Class','8-14','White',20,5,NULL,'active','2026-03-25 02:32:41','2026-03-25 16:36:51'),(3,1,'Taekwondo 101: White Belt Level','10-15','White',10,4,NULL,'inactive','2026-03-25 02:34:48','2026-03-26 17:13:08'),(5,4,'White-Yellow Belt Skills Development','17-20','White-Yellow',20,4,3,'active','2026-03-26 12:12:26','2026-03-26 12:12:26');

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
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `competition_id` (`competition_id`),
  KEY `student_id` (`student_id`),
  KEY `instructor_id` (`instructor_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `competition_entries` */

insert  into `competition_entries`(`id`,`competition_id`,`student_id`,`instructor_id`,`category`,`division`,`result`,`medal`,`remarks`,`created_at`,`updated_at`) values (1,1,4,2,'Sparring','20','pending','bronze','Strong performance, won by points','2026-03-27 18:03:50','2026-03-27 18:03:50');

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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `competitions` */

insert  into `competitions`(`id`,`name`,`location`,`date`,`organizer`,`level`,`created_at`,`updated_at`) values (1,'Metro Manila Taekwondo Open','Quezon City Sports Complex, Quezon City','2026-03-29','Philippine Taekwondo Association','regional','2026-03-27 11:46:41','2026-03-27 16:31:33'),(3,'Cebu Inter-School Taekwondo Championship','Cebu Coliseum, Cebu City','2026-04-10','Cebu Taekwondo Council','local','2026-03-27 11:59:22','2026-03-27 11:59:22');

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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `instructors` */

insert  into `instructors`(`id`,`user_id`,`fname`,`lname`,`photo`,`email`,`contact`,`username`,`password`,`rank_belt`,`certification_level`,`specialization`,`hire_date`,`bio`,`status`,`active_flag`,`created_at`,`updated_at`) values (2,0,'Minho','Park','instructors/6gp9JMND0sAfY6y7fc59ST7yVPUSVCRBO3Fs1IEA.jpg','ogiechanco0@gmail.com','09171234567','ogiechanco','$2y$12$De449fdZdrp9cPRV0JpSAOl0yc0P0.vvksjXLaqwtBoTOP4ztpI7O','Black Belt','Head Instructor','deawewadaw','2026-03-25 01:50:33','dawdawdwadasdawdas','active',1,'2026-03-18 04:08:33','2026-03-19 02:02:11'),(3,0,'Jisoo','Kim','instructors/bF9UegHz4yt4se35xe6ZjYe8dtvr5TPqYBZTRyN4.jpg','resshin240@gmail.com','0938292812','1234admin','$2y$12$pO9ee169WnJpr0MQJY4P1udaTs0cYJbe8O9sLwoYzqjNqCKdE4HQC','Black Belt','Head Instructor','ewan','2026-03-25 01:50:43','awdwadasd','active',1,'2026-03-18 04:34:24','2026-03-18 04:34:24'),(4,0,'John','Doe','instructors/Ievw56S3lHOTmOubm8G4jidqP154ljrmplrp4N9X.jpg','interimpink@talemarketing.com','0938292321','1_IQ','$2y$12$Go1NSoyNDez0vRsDYZDnRu1soZD0wvzTg/GBdWl5L47Jt/OJ/SuO2','Black Belt','Head Instructor','dsad','2026-03-18 00:00:00','2wdawdawdawd','active',1,'2026-03-18 04:44:53','2026-03-18 04:44:53'),(5,0,'Taeyang','Choi',NULL,'porras@porrs','09171234567','dwadwadawd','$2y$12$34ZbxYWrK327SfZ7VTtlTuZqtV1E./na3AQnsxIvzffWpeh.rlZYa','Red Belt','Assistant Instructor','kick boxing','2026-03-25 01:50:50','loremipsum','active',1,'2026-03-24 17:11:38','2026-03-24 17:11:38');

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
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `parent_students` */

insert  into `parent_students`(`id`,`parent_id`,`student_id`,`relationship`,`is_primary`,`created_at`,`updated_at`) values (1,18,1,'father',0,'2026-03-26 17:50:16','0000-00-00 00:00:00'),(2,19,4,'mother',0,'2026-03-26 21:43:05','0000-00-00 00:00:00');

/*Table structure for table `parents` */

DROP TABLE IF EXISTS `parents`;

CREATE TABLE `parents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `emergency_contact` varchar(255) DEFAULT NULL,
  `relationship_note` text DEFAULT NULL,
  `address` text DEFAULT NULL,
  `id_verified_flag` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `parents_user_id_foreign` (`user_id`),
  CONSTRAINT `parents_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `parents` */

insert  into `parents`(`id`,`user_id`,`emergency_contact`,`relationship_note`,`address`,`id_verified_flag`,`created_at`,`updated_at`) values (6,16,'Maria Santos','Mother','Quezon City','1','2026-03-17 12:06:47','2026-03-17 12:06:47'),(7,17,'Jose Cruz','Father','Makati City','1','2026-03-17 12:06:47','2026-03-17 12:06:47'),(8,18,'Anna Tan','Mother','Pasig City','1','2026-03-17 12:06:47','2026-03-17 12:06:47'),(9,19,'Carlos Garcia','Father','Caloocan City','1','2026-03-17 12:06:47','2026-03-17 12:06:47'),(10,20,'Grace Lee','Mother','Manila City','1','2026-03-17 12:06:47','2026-03-17 12:06:47'),(11,22,'09171234567','Mother','45 Rizal Avenue, Barangay Malate, Manila City, Metro Manila','0','2026-03-26 16:55:53',NULL),(12,23,'09182345678','Father','102 Mabini Street, Barangay Poblacion, Makati City, Metro Manila','0','2026-03-26 17:05:12',NULL),(13,24,'09351234567','Father','12 Aguinaldo Street, Barangay Buhangin, Davao City, Davao del Sur','0','2026-03-26 17:06:21',NULL),(14,25,'09561234567','Father','89 Katipunan Avenue, Barangay Loyola Heights, Quezon City, Metro Manila','0','2026-03-26 17:10:34',NULL),(15,26,'09471234567','Guardian','33 Lopez Jaena Street, Barangay Jaro, Iloilo City, Iloilo','0','2026-03-26 17:27:28',NULL),(16,27,'09291234567','Mother','78 Bonifacio Street, Barangay Lahug, Cebu City, Cebu','0','2026-03-26 17:28:46',NULL),(17,28,'09184567891','Mother','21 Sampaguita Street, Barangay Commonwealth, Quezon City, Metro Manila','0','2026-03-26 17:47:53',NULL),(18,29,'09275678912','Father','55 Luna Street, Barangay San Isidro, Parañaque City, Metro Manila','1','2026-03-26 17:50:16',NULL),(19,30,'09384567891','Mother','33 Lopez Jaena Street, Barangay Jaro, Iloilo City, Iloilo','0','2026-03-26 21:43:05',NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `personal_access_tokens` */

insert  into `personal_access_tokens`(`id`,`tokenable_type`,`tokenable_id`,`name`,`token`,`abilities`,`last_used_at`,`expires_at`,`created_at`,`updated_at`) values (1,'App\\Models\\User',7,'flutter-mobile-app','4c0b0a6e7d284b137bda9502f50c048a12bb061f75af4b1f385f6e5dca05204d','[\"*\"]',NULL,NULL,'2026-03-13 02:20:32','2026-03-13 02:20:32'),(2,'App\\Models\\User',7,'flutter-mobile-app','8898e355447851ee9eb95c8597365758abe94e06d6baabae4421a6a7d021c455','[\"*\"]',NULL,NULL,'2026-03-13 03:36:02','2026-03-13 03:36:02'),(3,'App\\Models\\User',7,'flutter-mobile-app','4b4c5c47651cfd40692f715c1f94b038a59cc02b387c1a59e5a73994879a5c6f','[\"*\"]',NULL,NULL,'2026-03-13 03:38:36','2026-03-13 03:38:36'),(4,'App\\Models\\User',7,'flutter-mobile-app','73dcd67e1710ce1ff4b8494c1a39ecb909bf86485a11daa76dd45e2485f88075','[\"*\"]',NULL,NULL,'2026-03-13 06:02:52','2026-03-13 06:02:52'),(5,'App\\Models\\User',7,'flutter-mobile-app','f99378ab688cde79ec5e72cb6ad84aaaa9779710cabac36800a789604a08bbbe','[\"*\"]',NULL,NULL,'2026-03-13 06:13:56','2026-03-13 06:13:56'),(6,'App\\Models\\User',7,'flutter-mobile-app','f16bd7bacad7b16a2a84e1aca7bca70e76761d8a104ad433c357df91c61fa1dc','[\"*\"]',NULL,NULL,'2026-03-13 06:26:31','2026-03-13 06:26:31'),(7,'App\\Models\\User',7,'flutter-mobile-app','92e8df958fa48c046f5caafdab68bb9d4495be1ff5354642bad0c576a1d69e21','[\"*\"]',NULL,NULL,'2026-03-13 07:06:21','2026-03-13 07:06:21'),(8,'App\\Models\\User',7,'flutter-mobile-app','fe4e87d65e93fea1a8e47270febd434f8017233e2c59a134099a5148ed181ab9','[\"*\"]','2026-03-13 09:14:08',NULL,'2026-03-13 07:58:49','2026-03-13 09:14:08'),(9,'App\\Models\\User',7,'flutter-mobile-app','67806086a3651c1670922edc3d25affa8d1624213ad94e05b1f4cf99a388f09f','[\"*\"]','2026-03-16 03:37:28',NULL,'2026-03-13 09:14:13','2026-03-16 03:37:28'),(10,'App\\Models\\User',7,'flutter-mobile-app','995ea63c191272dff538b60019c77187bcb090ab8e585ede6e9e91d2165c1b14','[\"*\"]',NULL,NULL,'2026-03-16 03:37:03','2026-03-16 03:37:03'),(11,'App\\Models\\User',7,'flutter-mobile-app','844c3b35b2cf8f97053e8244879953b5c6246fab5fa86f09cb48080d5cdf4ea1','[\"*\"]',NULL,NULL,'2026-03-16 08:26:00','2026-03-16 08:26:00'),(12,'App\\Models\\User',7,'flutter-mobile-app','ca18ac77bf0b8fffb1e1ed582d4c7d30646a6b9e7c88a7464ca060d258d9bd5e','[\"*\"]','2026-03-16 08:28:36',NULL,'2026-03-16 08:26:35','2026-03-16 08:28:36');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `role_permissions` */

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

insert  into `sessions`(`id`,`user_id`,`ip_address`,`user_agent`,`payload`,`last_activity`) values ('1sANdoWrL4GWMQIHERKX5R2OUxHthZZZsRfw2I4o',7,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiRXpYaGxFSFFXRUtiM0hTZ0NIM0laMnhsOHZWUFh2UDZrVHBwY0JJVyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9kYXNoYm9hcmQiO3M6NToicm91dGUiO3M6MTU6ImRhc2hib2FyZC5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjc7fQ==',1774842687),('2M1hOlxmMSNtZTSG5rTwzsJaZJHS7mFloLTLA7jJ',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiRUFmZkI5eUJTVlZMazhRQnJmMnF0WVRTUDBGeGRQOWp3MWNJdHFubSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMCI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fX0=',1774606893),('z4T8FIYnKVlxqhtrYMpyvaWnqxfAMsljTX9EDkpV',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTGZub3k0YzJla1U0NWZzWGNncTlTWDVzdWxxU1NBdWRMR3pTamdleSI7czo3OiJzdWNjZXNzIjtzOjM4OiJZb3UgaGF2ZSBiZWVuIGxvZ2dlZCBvdXQgc3VjY2Vzc2Z1bGx5LiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjE6e2k6MDtzOjc6InN1Y2Nlc3MiO319fQ==',1774603144);

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
  `branch_id` int(11) NOT NULL,
  `student_code` varchar(50) DEFAULT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `birthdate` date NOT NULL,
  `gender` enum('male','female','other') NOT NULL,
  `photo_url` varchar(255) DEFAULT NULL,
  `current_belt` varchar(50) DEFAULT NULL,
  `join_date` date NOT NULL,
  `status` enum('active','inactive','suspended') DEFAULT 'active',
  `password` varchar(255) NOT NULL,
  `medical_notes` text DEFAULT NULL,
  `allergies` text DEFAULT NULL,
  `emergency_contact_name` varchar(150) DEFAULT NULL,
  `emergency_contact_mobile` varchar(20) DEFAULT NULL,
  `primary_parent_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `student_code` (`student_code`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `students` */

insert  into `students`(`id`,`branch_id`,`student_code`,`first_name`,`last_name`,`middle_name`,`birthdate`,`gender`,`photo_url`,`current_belt`,`join_date`,`status`,`password`,`medical_notes`,`allergies`,`emergency_contact_name`,`emergency_contact_mobile`,`primary_parent_id`,`created_at`) values (1,1,'tkd-00001','Mechelle','Stoneman','Malakas','2026-03-13','male',NULL,'1','2026-03-13','active','',NULL,NULL,'Tyesha Freitag','0915-123-4567',10,'2026-03-13 10:42:11'),(2,1,'tkd-00002','Hyunwoo','Lee',NULL,'2026-04-07','male',NULL,'4','2026-03-24','active','','testing','testing','dwajhduwyhaddfiuopghwascliugjkbhvdawbkljvdfwa','09499374690',10,'2026-03-24 15:49:41'),(3,4,'tkd-00003','Juan','Dela Cruz',NULL,'2008-06-11','male',NULL,'11','2026-03-24','active','','testing','allergic to peanuts','Mr. Asimo','0999999999',20,'2026-03-24 16:03:29'),(4,4,'tkd-00004','Nicky','Minaj',NULL,'2005-03-24','female',NULL,'10','2026-03-24','active','','Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.','blha blah blha blha','eddu manzano','09207020903',17,'2026-03-24 16:25:33'),(5,7,NULL,'Joshua','Dela Cruz',NULL,'2026-03-26','male',NULL,'1','2026-03-26','active','',NULL,NULL,'Ashanti Dela Cruz','09182345679',29,'2026-03-26 21:41:23'),(6,5,NULL,'Princess','Lopez',NULL,'2003-08-25','female',NULL,'1','2026-03-30','active','',NULL,NULL,'Paolo Villanueva','09499937649',30,'2026-03-30 11:51:26');

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `branch_id` int(11) NOT NULL,
  `role` enum('admin','instructor','staff','parent') NOT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `users` */

insert  into `users`(`id`,`branch_id`,`role`,`username`,`fname`,`lname`,`email`,`mobile`,`password`,`photo_url`,`status`,`last_login_at`,`created_at`,`updated_at`) values (7,1,'admin','1234admin','Nataniel','Herras','testing@gmail.com','09499333000','$2y$12$93a4KdYWPG1OSuQe0DtOyOzD7dW5HLaVLEMslAQGuV8Hq.knHl9Dq','profile-photos/1774326137_h9uoElzpCo.png',1,'2026-03-30 11:38:36','2026-03-03 06:58:17','2026-03-30 11:38:36'),(8,2,'staff','admin','Princes','Valdez','Jeyde@gmail.com','09444444444','$2y$12$.YTxMvTVmKVtYdEvF8hy..c92hVUDjMGUM7qWKaBbHjvczP0ZdZW6','profile-photos/2HlKZIUgnzfjIzUFdPXrvdYfXUdy81YXaoAGj6Yw.jpg',1,'2026-03-03 15:02:26','2026-03-03 07:02:26','2026-03-25 03:09:42'),(10,1,'parent','parent1234','Cletus','Christopher','magulang123@gmail.com','09329329939','$2y$12$I8ofrlo50mdWeSWCOxac8.7T4AKyJILkquPZJS5KqxrR7i4AoLP5q',NULL,1,'2026-03-11 03:58:31','2026-03-10 08:48:46','2026-03-25 02:19:21'),(11,1,'instructor','jdoe','John','Doe','jdoe@example.com','09171234567','hashed_pw1','',0,NULL,'2026-03-17 11:55:06','2026-03-18 16:57:47'),(12,1,'instructor','asmith','Alice','Smith','asmith@example.com','09181234567','hashed_pw2','',0,NULL,'2026-03-17 11:55:06','2026-03-18 16:57:46'),(13,2,'instructor','bchan','Brian','Chan','bchan@example.com','09191234567','hashed_pw3','',0,NULL,'2026-03-17 11:55:06','2026-03-18 16:57:46'),(14,2,'instructor','cmendoza','Carla','Mendoza','cmendoza@example.com','09201234567','hashed_pw4','',0,NULL,'2026-03-17 11:55:06','2026-03-18 16:57:45'),(15,3,'instructor','dlee','David','Lee','dlee@example.com','09211234567','hashed_pw5','',0,NULL,'2026-03-17 11:55:06','2026-03-18 16:57:43'),(16,1,'parent','msantos','Maria','Santos','msantos@example.com','09170000046','hashed_pw46','',0,NULL,'2026-03-17 12:05:55','2026-03-18 16:57:50'),(17,1,'parent','jcruz','Jose','Cruz','jcruz@example.com','09170000047','hashed_pw47','',0,NULL,'2026-03-17 12:05:55','2026-03-18 16:57:42'),(18,2,'parent','atan','Anna','Tan','atan@example.com','09170000048','hashed_pw48','',0,NULL,'2026-03-17 12:05:55','2026-03-18 16:57:43'),(19,2,'parent','cgarcia','Carlos','Garcia','cgarcia@example.com','09170000049','hashed_pw49','',0,NULL,'2026-03-17 12:05:55','2026-03-18 16:57:41'),(20,3,'parent','glee','Grace','Lee','glee@example.com','09170000050','hashed_pw50','',0,NULL,'2026-03-17 12:05:55','2026-03-18 16:57:41'),(21,5,'instructor','dwadwadawd','Lady Joy','Porrs','porras@porrs','09171234567','$2y$12$dvFe7NxBRoycJBI22wY0eenI/EWHoeYRqpGnls1u9tTvOOEZcq5YO',NULL,1,NULL,'2026-03-24 17:11:38','2026-03-25 03:09:32'),(22,1,'parent','','Maria','Santos','maria.santos@gmail.com','09171234567','$2y$12$WWjGmQB7YyWvbTaaDGC19ufnejfabt1p.KguzrAWsYQ8q/yP9f7tC',NULL,1,NULL,'2026-03-26 16:55:53','2026-03-26 16:55:53'),(23,1,'parent','','Jose','Dela Cruz','jose.delacruz@yahoo.com','09182345678','$2y$12$zXWVb5MQd.D51Qoi7qHd..Ma.iMkZHmGHqStCnqgrvRKnTfFeiMJK',NULL,1,NULL,'2026-03-26 17:05:12','2026-03-26 17:05:12'),(24,1,'parent','','Roberto','Garcia','roberto.garcia@yahoo.com','09351234567','$2y$12$4pQW.KPdZnpUnhnxC2Jn1.Rd7KNVfCh5tEq/HBhWtFGrQMNxdQ9VS',NULL,1,NULL,'2026-03-26 17:06:21','2026-03-26 17:06:21'),(25,1,'parent','','Carlos','Bautista','carlos.bautista@yahoo.com','09561234567','$2y$12$fo.PWI1/2kZu3kj3nZLdWOxMFvOemY1TBSlbk.04n9SoVF9ZfLplO',NULL,1,NULL,'2026-03-26 17:10:34','2026-03-26 17:10:34'),(26,1,'parent','emendoza','Elena','Mendoza','elena.mendoza@gmail.com','09471234567','$2y$12$COCaEXMXAyvmQ8b9QvZ0zuhK4uGwAj2IGrN7JRIRF4wVlTFVNaKBK',NULL,1,NULL,'2026-03-26 17:27:28','2026-03-26 17:27:28'),(27,1,'parent','','Ana','Reyes','ana.reyes@gmail.com','09291234567','$2y$12$POwOKx7oiWQLBod3n68G3OVYY2ClM/2xpvFvJUbuRBXhcG6uLCNb.',NULL,0,NULL,'2026-03-26 17:28:46','2026-03-26 17:28:46'),(28,1,'parent','','Liza','Ramos','liza.ramos@gmail.com','09184567891','$2y$12$YAyPricnrJoKilW5c./5DeYgGYSSPCuvB8toH0QiYRyv6PGRC9P9K',NULL,1,NULL,'2026-03-26 17:47:53','2026-03-26 17:47:53'),(29,1,'parent','','Manuel','Torres','manuel.torres@yahoo.com','09275678912','$2y$12$8RxLSeZ9.UREjr/7oL2fAuPhZA/fZX3WedQL.ihCOFnAJBTlgH6bO',NULL,1,NULL,'2026-03-26 17:50:16','2026-03-26 17:50:16'),(30,1,'parent','','Kimberly','Garcia','Kim@gmail.com','09384567891','$2y$12$9QgzxuHHNz.paOIEVBUfZubeWqOI.HKfKpM.6JF2PQtFc/dC9BLh2',NULL,1,NULL,'2026-03-26 21:43:05','2026-03-26 21:43:05');

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
 `student_code` varchar(50) ,
 `student_name` varchar(201) ,
 `class_session_id` int(10) ,
 `checkin_time` datetime ,
 `checkout_time` datetime ,
 `method` enum('face','qr','manual') ,
 `confidence_score` varchar(50) ,
 `device_id` int(10) ,
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

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vwattendancelog` AS select `a`.`id` AS `id`,`s`.`student_code` AS `student_code`,concat(`s`.`first_name`,' ',`s`.`last_name`) AS `student_name`,`a`.`class_session_id` AS `class_session_id`,`a`.`checkin_time` AS `checkin_time`,`a`.`checkout_time` AS `checkout_time`,`a`.`method` AS `method`,`a`.`confidence_score` AS `confidence_score`,`a`.`device_id` AS `device_id`,`a`.`status` AS `status` from (`attendance_logs` `a` join `students` `s` on(`s`.`id` = `a`.`student_id`)) where `s`.`status` = 'active' */;

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

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_instructor_load` AS select `i`.`id` AS `id`,concat(`i`.`fname`,' ',`i`.`lname`) AS `instructor`,`cs`.`session_date` AS `date`,`c`.`class_name` AS `class`,(select count(0) from `tkd_db`.`class_students` `cls` where `cls`.`class_id` = `c`.`id`) AS `total_students`,concat(`cs`.`start_time`,' - ',`cs`.`end_time`) AS `time`,timestampdiff(HOUR,`cs`.`start_time`,`cs`.`end_time`) AS `hours` from ((`tkd_db`.`class_sessions` `cs` join `tkd_db`.`classes` `c` on(`cs`.`class_id` = `c`.`id`)) join `tkd_db`.`instructors` `i` on(`cs`.`instructor_id` = `i`.`id`)) */;

/*View structure for view vw_revenue_reports */

/*!50001 DROP TABLE IF EXISTS `vw_revenue_reports` */;
/*!50001 DROP VIEW IF EXISTS `vw_revenue_reports` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_revenue_reports` AS select `p`.`id` AS `id`,`p`.`paid_at` AS `date`,concat(`s`.`first_name`,' ',`s`.`last_name`) AS `student`,'Tuition' AS `payment_type`,`p`.`payment_method` AS `method`,`p`.`amount` AS `amount_paid`,`p`.`reference_no` AS `receipt_no` from ((`tkd_db`.`payments` `p` join `tkd_db`.`invoices` `i` on(`p`.`invoice_id` = `i`.`id`)) join `tkd_db`.`students` `s` on(`i`.`student_id` = `s`.`id`)) */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

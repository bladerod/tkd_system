/*
SQLyog Community v13.1.7 (64 bit)
MySQL - 10.4.32-MariaDB : Database - db-tkd
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

/*Table structure for table `active_logins` */

DROP TABLE IF EXISTS `active_logins`;

CREATE TABLE `active_logins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `login_type` enum('face_scan','manual') NOT NULL DEFAULT 'manual',
  `logged_in_at` datetime NOT NULL,
  `expires_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_student` (`student_id`),
  CONSTRAINT `fk_active_logins_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `active_logins` */

insert  into `active_logins`(`id`,`student_id`,`login_type`,`logged_in_at`,`expires_at`) values 
(6,20,'manual','2026-05-04 17:45:00','2026-05-04 23:59:59'),
(7,15,'manual','2026-05-06 23:54:44','2026-05-06 23:59:59'),
(8,22,'face_scan','2026-04-13 22:15:22','2026-04-13 23:59:59'),
(9,23,'manual','2026-04-29 09:45:01','2026-04-29 23:59:59'),
(10,24,'manual','2026-04-20 11:55:23','2026-04-20 23:59:59'),
(11,25,'manual','2026-04-21 23:03:18','2026-04-21 23:59:59'),
(12,21,'manual','2026-04-30 23:27:50','2026-04-30 23:59:59'),
(13,26,'manual','2026-04-29 23:00:16','2026-04-29 23:59:59'),
(14,28,'manual','2026-04-30 23:13:38','2026-04-30 23:59:59'),
(15,27,'manual','2026-05-07 00:29:50','2026-05-07 23:59:59'),
(16,32,'manual','2026-05-06 23:41:44','2026-05-06 23:59:59'),
(17,33,'manual','2026-05-07 00:24:13','2026-05-07 23:59:59');

/*Table structure for table `announcement_reads` */

DROP TABLE IF EXISTS `announcement_reads`;

CREATE TABLE `announcement_reads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `announcement_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `read_at` datetime NOT NULL,
  `is_dismissed` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_read` (`announcement_id`,`user_id`),
  KEY `fk_ann_reads_user` (`user_id`),
  CONSTRAINT `fk_ann_reads_announcement` FOREIGN KEY (`announcement_id`) REFERENCES `announcements` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_ann_reads_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=385 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `announcement_reads` */

insert  into `announcement_reads`(`id`,`announcement_id`,`user_id`,`read_at`,`is_dismissed`) values 
(1,9,33,'2026-04-20 17:12:04',1),
(2,10,33,'2026-04-20 17:12:10',1),
(3,11,33,'2026-04-20 17:12:13',1),
(4,12,33,'2026-04-20 17:12:17',1),
(5,13,33,'2026-04-20 17:12:18',1),
(6,24,33,'2026-04-20 17:12:02',1),
(7,25,33,'2026-04-20 14:21:00',0),
(8,26,33,'2026-04-20 14:21:00',0),
(9,27,33,'2026-04-20 14:21:00',0),
(10,28,33,'2026-04-20 14:21:00',0),
(11,29,33,'2026-04-20 14:21:00',0),
(12,30,33,'2026-04-20 14:21:00',0),
(13,31,33,'2026-04-20 14:21:00',0),
(14,32,33,'2026-04-20 14:21:00',0),
(15,33,33,'2026-04-20 14:21:00',0),
(16,34,33,'2026-04-20 17:11:59',1),
(17,35,33,'2026-04-20 17:11:55',1),
(18,36,33,'2026-04-20 17:11:49',1),
(19,9,44,'2026-04-20 14:25:15',0),
(20,10,44,'2026-04-20 14:25:15',0),
(21,11,44,'2026-04-20 14:25:15',0),
(22,12,44,'2026-04-20 14:25:15',0),
(23,13,44,'2026-04-20 14:25:15',0),
(24,24,44,'2026-04-20 14:25:15',0),
(25,25,44,'2026-04-20 14:25:15',0),
(26,26,44,'2026-04-20 14:25:15',0),
(27,27,44,'2026-04-20 14:25:15',0),
(28,28,44,'2026-04-20 14:25:15',0),
(29,29,44,'2026-04-20 14:25:15',0),
(30,30,44,'2026-04-20 14:25:15',0),
(31,31,44,'2026-04-20 14:25:15',0),
(32,32,44,'2026-04-20 14:25:15',0),
(33,33,44,'2026-04-20 14:25:15',0),
(34,34,44,'2026-04-20 14:25:15',0),
(35,35,44,'2026-04-20 14:25:15',0),
(36,36,44,'2026-04-20 14:25:15',0),
(37,37,33,'2026-04-20 17:11:46',1),
(38,38,33,'2026-04-20 17:11:41',1),
(39,39,33,'2026-04-20 17:11:36',1),
(47,37,44,'2026-04-20 16:40:26',0),
(48,38,44,'2026-04-20 16:40:26',0),
(49,39,44,'2026-04-20 16:40:26',0),
(50,40,44,'2026-04-20 16:40:26',0),
(52,40,33,'2026-04-20 16:44:14',0),
(53,41,33,'2026-04-20 17:14:45',0),
(55,9,40,'2026-04-20 17:16:27',1),
(56,10,40,'2026-04-20 17:16:29',1),
(57,11,40,'2026-04-20 17:16:31',1),
(58,12,40,'2026-04-20 17:15:28',0),
(59,13,40,'2026-04-20 17:15:28',0),
(60,24,40,'2026-04-20 17:15:28',0),
(61,25,40,'2026-04-20 17:15:28',0),
(62,26,40,'2026-04-20 17:15:28',0),
(63,27,40,'2026-04-20 17:15:28',0),
(64,28,40,'2026-04-20 17:15:28',0),
(65,29,40,'2026-04-20 17:15:28',0),
(66,30,40,'2026-04-20 17:15:28',0),
(67,31,40,'2026-04-20 17:15:28',0),
(68,32,40,'2026-04-20 17:15:28',0),
(69,33,40,'2026-04-20 17:15:28',0),
(70,34,40,'2026-04-20 17:15:28',0),
(71,35,40,'2026-04-20 17:15:28',0),
(72,36,40,'2026-04-20 17:15:28',0),
(73,37,40,'2026-04-20 17:15:28',0),
(74,38,40,'2026-04-20 17:15:28',0),
(75,39,40,'2026-04-20 17:15:28',0),
(76,40,40,'2026-04-20 17:15:28',0),
(77,41,40,'2026-04-20 17:15:28',0),
(85,42,33,'2026-04-20 17:40:00',1),
(87,43,33,'2026-04-21 17:03:33',0),
(88,44,33,'2026-04-21 17:03:33',0),
(91,9,48,'2026-04-21 22:30:09',1),
(92,10,48,'2026-04-21 22:21:51',0),
(93,11,48,'2026-04-21 22:21:51',0),
(94,12,48,'2026-04-21 22:21:51',0),
(95,13,48,'2026-04-21 22:21:51',0),
(96,24,48,'2026-04-21 22:21:51',0),
(97,25,48,'2026-04-21 22:21:51',0),
(98,26,48,'2026-04-21 22:21:51',0),
(99,27,48,'2026-04-21 22:21:51',0),
(100,28,48,'2026-04-21 22:21:51',0),
(101,29,48,'2026-04-21 22:21:51',0),
(102,30,48,'2026-04-21 22:21:51',0),
(103,31,48,'2026-04-21 22:21:51',0),
(104,32,48,'2026-04-21 22:21:51',0),
(105,33,48,'2026-04-21 22:21:51',0),
(106,34,48,'2026-04-21 22:21:51',0),
(107,35,48,'2026-04-21 22:30:07',1),
(108,36,48,'2026-04-21 22:30:05',1),
(109,37,48,'2026-04-21 22:21:51',0),
(110,38,48,'2026-04-21 22:21:51',0),
(111,39,48,'2026-04-21 22:21:51',0),
(112,40,48,'2026-04-21 22:21:51',0),
(113,41,48,'2026-04-21 22:21:51',0),
(114,42,48,'2026-04-21 22:21:51',0),
(115,43,48,'2026-04-21 22:21:51',0),
(116,44,48,'2026-04-21 22:30:03',1),
(117,9,49,'2026-04-23 21:35:06',0),
(118,10,49,'2026-04-23 21:35:06',0),
(119,11,49,'2026-04-23 21:35:06',0),
(120,12,49,'2026-04-23 21:35:06',0),
(121,13,49,'2026-04-23 21:35:06',0),
(122,24,49,'2026-04-23 21:35:06',0),
(123,25,49,'2026-04-23 21:35:06',0),
(124,26,49,'2026-04-23 21:35:06',0),
(125,27,49,'2026-04-23 21:35:06',0),
(126,28,49,'2026-04-23 21:35:06',0),
(127,29,49,'2026-04-23 21:35:06',0),
(128,30,49,'2026-04-23 21:35:06',0),
(129,31,49,'2026-04-23 21:35:06',0),
(130,32,49,'2026-04-23 21:35:06',0),
(131,33,49,'2026-04-23 21:35:06',0),
(132,34,49,'2026-04-23 21:35:06',0),
(133,35,49,'2026-04-23 21:35:06',0),
(134,36,49,'2026-04-23 21:35:06',0),
(135,37,49,'2026-04-23 21:35:06',0),
(136,38,49,'2026-04-23 21:35:06',0),
(137,39,49,'2026-04-23 21:35:06',0),
(138,40,49,'2026-04-23 21:35:06',0),
(139,41,49,'2026-04-23 21:35:06',0),
(140,42,49,'2026-04-23 21:35:06',0),
(141,43,49,'2026-04-23 21:35:06',0),
(142,44,49,'2026-04-23 21:35:06',0),
(143,45,33,'2026-04-27 22:54:55',0),
(144,42,40,'2026-04-28 22:32:08',0),
(145,43,40,'2026-04-28 22:32:08',0),
(146,44,40,'2026-04-28 22:32:08',0),
(147,45,40,'2026-04-28 22:32:08',0),
(149,9,43,'2026-04-28 22:55:10',0),
(150,10,43,'2026-04-28 22:55:10',0),
(151,11,43,'2026-04-28 22:55:10',0),
(152,12,43,'2026-04-28 22:55:10',0),
(153,13,43,'2026-04-28 22:55:10',0),
(154,24,43,'2026-04-28 22:55:10',0),
(155,25,43,'2026-04-28 22:55:10',0),
(156,26,43,'2026-04-28 22:55:10',0),
(157,27,43,'2026-04-28 22:55:10',0),
(158,28,43,'2026-04-28 22:55:10',0),
(159,29,43,'2026-04-28 22:55:10',0),
(160,30,43,'2026-04-28 22:55:10',0),
(161,31,43,'2026-04-28 22:55:10',0),
(162,32,43,'2026-04-28 22:55:10',0),
(163,33,43,'2026-04-28 22:55:10',0),
(164,34,43,'2026-04-28 22:55:10',0),
(165,35,43,'2026-04-28 22:55:10',0),
(166,36,43,'2026-04-28 22:55:10',0),
(167,37,43,'2026-04-28 22:55:10',0),
(168,38,43,'2026-04-28 22:55:10',0),
(169,39,43,'2026-04-28 22:55:10',0),
(170,40,43,'2026-04-28 22:55:10',0),
(171,41,43,'2026-04-28 22:55:10',0),
(172,42,43,'2026-04-28 22:55:10',0),
(173,43,43,'2026-04-28 22:55:10',0),
(174,44,43,'2026-04-28 22:55:10',0),
(175,45,43,'2026-04-28 22:55:10',0),
(185,9,50,'2026-04-29 09:13:47',0),
(186,10,50,'2026-04-29 09:13:47',0),
(187,11,50,'2026-04-29 09:13:47',0),
(188,12,50,'2026-04-29 09:13:47',0),
(189,13,50,'2026-04-29 09:13:47',0),
(190,24,50,'2026-04-29 09:13:47',0),
(191,25,50,'2026-04-29 09:13:47',0),
(192,26,50,'2026-04-29 09:13:47',0),
(193,27,50,'2026-04-29 09:13:47',0),
(194,28,50,'2026-04-29 09:13:47',0),
(195,29,50,'2026-04-29 09:13:47',0),
(196,30,50,'2026-04-29 09:13:47',0),
(197,31,50,'2026-04-29 09:13:47',0),
(198,32,50,'2026-04-29 09:13:47',0),
(199,33,50,'2026-04-29 09:13:47',0),
(200,34,50,'2026-04-29 09:13:47',0),
(201,35,50,'2026-04-29 09:13:47',0),
(202,36,50,'2026-04-29 09:13:47',0),
(203,37,50,'2026-04-29 09:13:47',0),
(204,38,50,'2026-04-29 09:13:47',0),
(205,39,50,'2026-04-29 09:13:47',0),
(206,40,50,'2026-04-29 09:13:47',0),
(207,41,50,'2026-04-29 09:13:47',0),
(208,42,50,'2026-04-29 09:13:47',0),
(209,43,50,'2026-04-29 09:13:47',0),
(210,44,50,'2026-04-29 09:13:47',0),
(211,45,50,'2026-04-29 09:13:47',0),
(220,9,53,'2026-04-29 09:38:09',0),
(221,10,53,'2026-04-29 09:38:09',0),
(222,11,53,'2026-04-29 09:38:09',0),
(223,12,53,'2026-04-29 09:38:09',0),
(224,13,53,'2026-04-29 09:38:09',0),
(225,24,53,'2026-04-29 09:38:09',0),
(226,25,53,'2026-04-29 09:38:09',0),
(227,26,53,'2026-04-29 09:38:09',0),
(228,27,53,'2026-04-29 09:38:09',0),
(229,28,53,'2026-04-29 09:38:09',0),
(230,29,53,'2026-04-29 09:38:09',0),
(231,30,53,'2026-04-29 09:38:09',0),
(232,31,53,'2026-04-29 09:38:09',0),
(233,32,53,'2026-04-29 09:38:09',0),
(234,33,53,'2026-04-29 09:38:09',0),
(235,34,53,'2026-04-29 09:38:09',0),
(236,35,53,'2026-04-29 09:38:09',0),
(237,36,53,'2026-04-29 09:38:09',0),
(238,37,53,'2026-04-29 09:38:09',0),
(239,38,53,'2026-04-29 09:38:09',0),
(240,39,53,'2026-04-29 09:38:09',0),
(241,40,53,'2026-04-29 09:38:09',0),
(242,41,53,'2026-04-29 09:38:09',0),
(243,42,53,'2026-04-29 09:38:09',0),
(244,43,53,'2026-04-29 09:38:09',0),
(245,44,53,'2026-04-29 09:38:09',0),
(246,45,53,'2026-04-29 09:38:09',0),
(282,9,54,'2026-05-04 15:51:05',0),
(283,10,54,'2026-05-04 15:51:05',0),
(284,11,54,'2026-05-04 15:51:05',0),
(285,12,54,'2026-05-04 15:51:05',0),
(286,13,54,'2026-05-04 15:51:05',0),
(287,24,54,'2026-05-04 15:51:05',0),
(288,25,54,'2026-05-04 15:51:05',0),
(289,26,54,'2026-05-04 15:51:05',0),
(290,27,54,'2026-05-04 15:51:05',0),
(291,28,54,'2026-05-04 15:51:05',0),
(292,29,54,'2026-05-04 15:51:05',0),
(293,30,54,'2026-05-04 15:51:05',0),
(294,31,54,'2026-05-04 15:51:05',0),
(295,32,54,'2026-05-04 15:51:05',0),
(296,33,54,'2026-05-04 15:51:05',0),
(297,34,54,'2026-05-04 15:51:05',0),
(298,35,54,'2026-05-04 15:51:05',0),
(299,36,54,'2026-05-04 15:51:05',0),
(300,37,54,'2026-05-04 15:51:05',0),
(301,38,54,'2026-05-04 15:51:05',0),
(302,39,54,'2026-05-04 15:51:05',0),
(303,40,54,'2026-05-04 15:51:05',0),
(304,41,54,'2026-05-04 15:51:05',0),
(305,42,54,'2026-05-04 15:51:05',0),
(306,43,54,'2026-05-04 15:51:05',0),
(307,44,54,'2026-05-04 15:51:05',0),
(308,45,54,'2026-05-04 15:51:05',0),
(317,9,52,'2026-05-04 16:40:39',0),
(318,10,52,'2026-05-04 16:40:39',0),
(319,11,52,'2026-05-04 16:40:39',0),
(320,12,52,'2026-05-04 16:40:39',0),
(321,13,52,'2026-05-04 16:40:39',0),
(322,24,52,'2026-05-04 16:40:39',0),
(323,25,52,'2026-05-04 16:40:39',0),
(324,26,52,'2026-05-04 16:40:39',0),
(325,27,52,'2026-05-04 16:40:39',0),
(326,28,52,'2026-05-04 16:40:39',0),
(327,29,52,'2026-05-04 16:40:39',0),
(328,30,52,'2026-05-04 16:40:39',0),
(329,31,52,'2026-05-04 16:40:39',0),
(330,32,52,'2026-05-04 16:40:39',0),
(331,33,52,'2026-05-04 16:40:39',0),
(332,34,52,'2026-05-04 16:40:39',0),
(333,35,52,'2026-05-04 16:40:39',0),
(334,36,52,'2026-05-04 16:40:39',0),
(335,37,52,'2026-05-04 16:40:39',0),
(336,38,52,'2026-05-04 16:40:39',0),
(337,39,52,'2026-05-04 16:40:39',0),
(338,40,52,'2026-05-04 16:40:39',0),
(339,41,52,'2026-05-04 16:40:39',0),
(340,42,52,'2026-05-04 16:40:39',0),
(341,43,52,'2026-05-04 16:40:39',0),
(342,44,52,'2026-05-04 16:40:39',0),
(343,45,52,'2026-05-04 16:40:39',0),
(352,41,44,'2026-05-06 00:34:54',0),
(353,42,44,'2026-05-06 00:34:54',0),
(354,43,44,'2026-05-06 00:34:54',0),
(355,44,44,'2026-05-06 00:34:54',0),
(356,45,44,'2026-05-06 00:34:54',0),
(358,9,59,'2026-05-06 23:52:52',0),
(359,10,59,'2026-05-06 23:52:52',0),
(360,11,59,'2026-05-06 23:52:52',0),
(361,12,59,'2026-05-06 23:52:52',0),
(362,13,59,'2026-05-06 23:52:52',0),
(363,24,59,'2026-05-06 23:52:52',0),
(364,25,59,'2026-05-06 23:52:52',0),
(365,26,59,'2026-05-06 23:52:52',0),
(366,27,59,'2026-05-06 23:52:52',0),
(367,28,59,'2026-05-06 23:52:52',0),
(368,29,59,'2026-05-06 23:52:52',0),
(369,30,59,'2026-05-06 23:52:52',0),
(370,31,59,'2026-05-06 23:52:52',0),
(371,32,59,'2026-05-06 23:52:52',0),
(372,33,59,'2026-05-06 23:52:52',0),
(373,34,59,'2026-05-06 23:52:52',0),
(374,35,59,'2026-05-06 23:52:52',0),
(375,36,59,'2026-05-06 23:52:52',0),
(376,37,59,'2026-05-06 23:52:52',0),
(377,38,59,'2026-05-06 23:52:52',0),
(378,39,59,'2026-05-06 23:52:52',0),
(379,40,59,'2026-05-06 23:52:52',0),
(380,41,59,'2026-05-06 23:52:52',0),
(381,42,59,'2026-05-06 23:52:52',0),
(382,43,59,'2026-05-06 23:52:52',0),
(383,44,59,'2026-05-06 23:52:52',0),
(384,45,59,'2026-05-06 23:52:52',0);

/*Table structure for table `announcement_recipients` */

DROP TABLE IF EXISTS `announcement_recipients`;

CREATE TABLE `announcement_recipients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `announcement_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_ann_recipients_announcement` (`announcement_id`),
  KEY `fk_ann_recipients_user` (`user_id`),
  CONSTRAINT `fk_ann_recipients_announcement` FOREIGN KEY (`announcement_id`) REFERENCES `announcements` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_ann_recipients_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `announcement_recipients` */

insert  into `announcement_recipients`(`id`,`announcement_id`,`user_id`) values 
(6,24,33),
(7,25,43),
(8,29,46),
(9,30,46),
(10,31,46),
(11,32,13),
(12,33,44),
(13,34,33),
(14,37,33),
(15,38,33),
(16,39,33),
(17,40,44),
(18,41,33),
(19,42,33),
(20,43,33);

/*Table structure for table `announcements` */

DROP TABLE IF EXISTS `announcements`;

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_by_user_id` int(11) NOT NULL,
  `target_type` enum('all','belt','branch','class','parents','students','specific_students','specific_parents') DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `belt_level` varchar(150) DEFAULT NULL,
  `branch_id` int(10) DEFAULT NULL,
  `channel` varchar(150) DEFAULT NULL,
  `title` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `event_date` varchar(50) DEFAULT NULL,
  `event_time` varchar(50) DEFAULT NULL,
  `fee` varchar(50) DEFAULT NULL,
  `publish_date` datetime DEFAULT NULL,
  `expire_date` date DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `created_by_user_id` (`created_by_user_id`),
  KEY `class_id` (`class_id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `announcements` */

insert  into `announcements`(`id`,`created_by_user_id`,`target_type`,`class_id`,`belt_level`,`branch_id`,`channel`,`title`,`message`,`location`,`event_date`,`event_time`,`fee`,`publish_date`,`expire_date`,`status`,`created_by`) values 
(9,0,'all',NULL,NULL,NULL,'App','Belt Exam Scheduled','Green to Blue Belt examination scheduled for September 20, 2025.',NULL,NULL,NULL,NULL,'2026-04-14 00:00:00','2026-05-14','active',NULL),
(10,0,'all',NULL,NULL,NULL,'App','No Classes - Holiday','The TKD main will be closed on September 16-17 in observance of the national holiday.',NULL,NULL,NULL,NULL,'2026-04-14 00:00:00','2026-05-14','active',NULL),
(11,0,'all',NULL,NULL,NULL,'App','Competition Open','Registration is now open for the Regional Championship in October.',NULL,NULL,NULL,NULL,'2026-04-14 00:00:00','2026-05-14','active',NULL),
(12,0,'all',NULL,NULL,NULL,'App','Test Announcement','This is a test message from instructor.',NULL,NULL,NULL,NULL,'2026-04-14 00:00:00','2026-05-14','active',24),
(13,0,'all',NULL,NULL,NULL,'App','Announcement','Don\'t late tommorow',NULL,NULL,NULL,NULL,'2026-04-14 00:00:00','2026-05-14','active',24),
(24,0,'specific_students',NULL,NULL,NULL,'App','Mobility','10km run tommorow',NULL,NULL,NULL,NULL,'2026-04-17 00:00:00','2026-05-17','active',24),
(25,0,'specific_students',NULL,NULL,NULL,'App','Belt Exam','Goodluck!','Caloocan Sport Complex',NULL,'9:00am to 12:00pm','200','2026-04-17 00:00:00','2026-05-17','active',24),
(26,0,'class',NULL,NULL,NULL,'App','Conditioning','Drink more water',NULL,NULL,NULL,NULL,'2026-04-20 00:00:00','2026-05-20','active',24),
(27,0,'class',NULL,NULL,NULL,'App','Conditioning','Drink more water',NULL,NULL,NULL,NULL,'2026-04-20 00:00:00','2026-05-20','active',24),
(28,0,'class',NULL,NULL,NULL,'App','Conditioning','Drink more water',NULL,NULL,NULL,NULL,'2026-04-20 00:00:00','2026-05-20','active',24),
(29,0,'class',NULL,NULL,NULL,'App','Conditioning','Drink more water',NULL,NULL,NULL,NULL,'2026-04-20 00:00:00','2026-05-20','active',24),
(30,0,'class',NULL,NULL,NULL,'App','Belt Exam','Prepare your self!',NULL,NULL,NULL,NULL,'2026-04-20 00:00:00','2026-05-20','active',24),
(31,0,'class',NULL,NULL,NULL,'App','Competition','Prepare yourself!','Sm Fairview',NULL,NULL,NULL,'2026-04-20 11:54:54','2026-05-20','active',24),
(32,0,'specific_parents',NULL,NULL,NULL,'App','Meeting','Good day, Parents!\n\nWe would like to invite you to a Parents’ Meeting for our Taekwondo program. The purpose of this meeting is to discuss your child’s progress, upcoming activities, training schedules, and important guidelines to support their development in Taekwondo.','Sm Fairview',NULL,NULL,NULL,'2026-04-20 12:18:00','2026-05-20','active',24),
(33,0,'specific_parents',NULL,NULL,NULL,'App','Meetings','Good day, Parents!\n\nWe would like to invite you to a Parents’ Meeting for our Taekwondo program. The purpose of this meeting is to discuss your child’s progress, upcoming activities, training schedules, and important guidelines to support their development in Taekwondo.','Caloocan Complex',NULL,NULL,NULL,'2026-04-20 12:29:54','2026-05-20','active',24),
(34,0,'specific_students',NULL,NULL,NULL,'App','Palarong pambansa','Goodluck triever to upcoming fights. Be prepared','China Town',NULL,'9:00am to 10:00am','500','2026-04-20 12:58:25','2026-05-20','active',24),
(35,0,'all',NULL,NULL,NULL,'App','Summer Competetion','Goodluck Students! Be prepared','Sm Fairview','Apr 23, 2026','2:30 AM to 6:30 PM','500','2026-04-20 13:32:53','2026-05-20','active',24),
(36,0,'all',NULL,NULL,NULL,'App','Promotion Events','Good day, Parents and Students!\n\nWe are pleased to announce our upcoming Taekwondo Promotion Event, where students will have the opportunity to demonstrate their skills and advance to the next belt level. This event is an important milestone that reflects their hard work, discipline, and dedication in training','Sm North','Apr 30, 2026','8:00 AM to 11:00 PM',NULL,'2026-04-20 14:20:30','2026-05-20','active',24),
(37,0,'specific_students',NULL,NULL,NULL,'App','Stamina Training','Jogging 10km','Forest Park','Apr 21, 2026',NULL,NULL,'2026-04-20 14:31:32','2026-05-20','active',24),
(38,0,'specific_students',NULL,NULL,NULL,'App','Belt Exam','Prepare yourself',NULL,NULL,NULL,NULL,'2026-04-20 14:47:10','2026-05-20','active',24),
(39,0,'specific_students',NULL,NULL,NULL,'App','Supencion','You are suspend in 3 weeks',NULL,'Apr 21, 2026',NULL,NULL,'2026-04-20 16:28:04','2026-05-20','active',24),
(40,0,'specific_parents',NULL,NULL,NULL,'App','Subscription','Good day sir! Your daughter subscription is need to renew','Quezon City','Apr 28, 2026',NULL,NULL,'2026-04-20 16:39:43','2026-05-20','active',24),
(41,0,'specific_students',NULL,NULL,NULL,'App','Greetings','Welcome! Triever?',NULL,NULL,NULL,NULL,'2026-04-20 17:14:18','2026-05-20','active',24),
(42,0,'specific_students',NULL,NULL,NULL,'App','International Taekwondo Competition','Good day, Triever\n\nWe are proud to announce that you are been officially selected to represent our Taekwondo team in an upcoming International Taekwondo Competition. This is a remarkable achievement that reflects dedication, discipline, and performance in training.','Thailand','Apr 25, 2026','8:30 AM to 12:30 PM','Free','2026-04-20 17:32:30','2026-05-20','active',24),
(43,0,'specific_students',NULL,NULL,NULL,'App','Belt Promotion','Please bring your gear tomorrow!','Sm Fairview','Apr 22, 2026','9:00 AM to 12:00 PM',NULL,'2026-04-21 17:02:12','2026-05-21','active',24),
(44,0,'all',NULL,NULL,NULL,'App','Suspended Class','No session tomorrow due to severe storm',NULL,NULL,NULL,NULL,'2026-04-21 17:03:06','2026-05-21','active',24),
(45,0,'specific_students',NULL,NULL,NULL,'App','Test','Test message',NULL,NULL,NULL,NULL,'2026-04-27 21:28:02','2026-05-27','active',24);

/*Table structure for table `attendance_logs` */

DROP TABLE IF EXISTS `attendance_logs`;

CREATE TABLE `attendance_logs` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `student_id` int(10) NOT NULL,
  `class_session_id` int(10) NOT NULL,
  `checkin_time` datetime NOT NULL,
  `checkout_time` datetime NOT NULL,
  `method` enum('face','qr','manual','face_scan','app','device') DEFAULT NULL,
  `confidence_score` varchar(50) NOT NULL,
  `recorded_by_user_id` int(10) NOT NULL,
  `device_id` int(10) NOT NULL,
  `status` int(1) NOT NULL DEFAULT 1,
  `attendance_status` enum('present','late','absent','excused') NOT NULL DEFAULT 'present',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=82 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `attendance_logs` */

insert  into `attendance_logs`(`id`,`student_id`,`class_session_id`,`checkin_time`,`checkout_time`,`method`,`confidence_score`,`recorded_by_user_id`,`device_id`,`status`,`attendance_status`) values 
(1,1,201,'2026-03-10 08:00:15','2026-03-10 09:30:20','face','98.7',2,1,1,'present'),
(2,2,201,'2026-03-10 08:02:40','2026-03-10 09:28:55','qr','',2,1,1,'present'),
(3,3,201,'2026-03-10 08:10:00','2026-03-10 09:35:10','manual','',1,0,1,'present'),
(4,4,202,'2026-03-11 10:00:05','2026-03-11 11:30:00','face','95.4',3,2,1,'present'),
(5,5,202,'2026-03-11 10:01:30','2026-03-11 11:29:45','qr','',3,2,1,'present'),
(6,6,202,'2026-03-11 10:12:20','0000-00-00 00:00:00','manual','',1,0,1,'present'),
(7,7,203,'2026-03-12 13:00:10','2026-03-12 14:30:00','face','99.0',2,1,1,'present'),
(8,8,203,'2026-03-12 13:03:25','2026-03-12 14:28:50','qr','',2,1,1,'present'),
(9,9,203,'2026-03-12 13:15:00','2026-03-12 14:35:00','manual','',1,0,1,'present'),
(10,10,204,'2026-03-13 15:00:00','2026-03-13 16:30:30','face','94.2',3,2,1,'present'),
(11,11,204,'2026-03-13 15:05:45','2026-03-13 16:28:15','qr','',3,2,1,'present'),
(12,12,204,'2026-03-13 15:20:00','0000-00-00 00:00:00','manual','',1,0,1,'present'),
(13,1,1,'2026-03-28 07:50:06','2026-03-28 07:50:06','manual','100',24,0,1,'present'),
(14,2,2,'2026-03-30 03:12:30','2026-03-30 03:12:30','manual','100',24,0,1,'late'),
(15,5,27,'2026-03-28 09:46:39','2026-03-28 09:46:39','manual','100',24,0,1,'late'),
(16,2,27,'2026-03-28 09:46:39','2026-03-28 09:46:39','manual','100',24,0,1,'late'),
(17,1,27,'2026-03-28 09:46:39','2026-03-28 09:46:39','manual','100',24,0,1,'late'),
(18,6,27,'2026-03-28 09:46:39','2026-03-28 09:46:39','manual','100',24,0,1,'absent'),
(21,1,30,'2026-03-30 05:50:22','2026-03-30 05:50:22','manual','100',24,0,1,'present'),
(22,2,30,'2026-03-30 05:50:22','2026-03-30 05:50:22','manual','100',24,0,1,'present'),
(23,5,30,'2026-03-30 05:50:22','2026-03-30 05:50:22','manual','100',24,0,1,'present'),
(24,2,31,'2026-03-30 05:50:03','2026-03-30 05:50:03','manual','100',24,0,1,'present'),
(25,6,31,'2026-03-30 05:50:03','2026-03-30 05:50:03','manual','100',24,0,1,'present'),
(26,5,31,'2026-03-30 05:50:03','2026-03-30 05:50:03','manual','100',24,0,1,'present'),
(28,7,32,'2026-03-30 15:01:54','2026-03-30 15:01:54','manual','100',24,0,1,'present'),
(30,7,33,'2026-03-31 14:38:47','2026-03-31 14:38:47','manual','100',24,0,1,'present'),
(31,2,34,'2026-04-10 16:02:54','2026-04-10 16:02:54','manual','100',24,0,1,'present'),
(32,6,34,'2026-04-10 16:02:54','2026-04-10 16:02:54','manual','100',24,0,1,'present'),
(33,5,34,'2026-04-10 16:02:54','2026-04-10 16:02:54','manual','100',24,0,1,'present'),
(34,21,34,'2026-04-10 16:03:48','2026-04-10 16:03:48','manual','100',24,0,1,'late'),
(35,20,34,'2026-04-10 16:03:49','2026-04-10 16:03:49','manual','100',24,0,1,'late'),
(36,9,34,'2026-04-10 16:03:49','2026-04-10 16:03:49','manual','100',24,0,1,'late'),
(37,2,35,'2026-04-12 15:31:21','2026-04-12 15:31:21','manual','100',24,0,1,'present'),
(38,6,35,'2026-04-12 15:31:21','2026-04-12 15:31:21','manual','100',24,0,1,'present'),
(39,5,35,'2026-04-12 15:31:21','2026-04-12 15:31:21','manual','100',24,0,1,'present'),
(40,15,35,'2026-04-12 15:31:21','2026-04-12 15:31:21','manual','100',24,0,1,'present'),
(41,21,35,'2026-04-12 15:36:37','2026-04-12 15:36:37','manual','100',24,0,1,'late'),
(42,20,35,'2026-04-12 15:36:37','2026-04-12 15:36:37','manual','100',24,0,1,'late'),
(43,2,37,'2026-04-13 02:20:56','2026-04-13 02:20:56','manual','100',24,0,1,'absent'),
(44,5,37,'2026-04-13 02:26:54','2026-04-13 02:26:54','manual','100',24,0,1,'present'),
(45,13,37,'2026-04-13 02:26:54','2026-04-13 02:26:54','manual','100',24,0,1,'present'),
(48,21,37,'2026-04-13 04:37:01','2026-04-13 04:37:01','manual','100',24,0,1,'absent'),
(60,20,36,'2026-04-13 13:28:39','2026-04-13 13:28:39','face_scan','100',24,0,1,'late'),
(61,15,36,'2026-04-13 13:31:15','2026-04-13 13:31:15','face_scan','100',24,0,1,'present'),
(62,22,36,'2026-04-13 22:18:00','2026-04-13 22:18:00','face_scan','100',24,0,1,'present'),
(63,15,38,'2026-04-14 21:29:41','2026-04-14 21:29:41','manual','100',24,0,1,'late'),
(64,15,43,'2026-04-28 14:03:45','2026-04-28 14:03:45','manual','100',24,0,1,'present'),
(65,26,44,'2026-04-29 10:02:38','2026-04-29 10:02:38','face_scan','100',24,0,1,'present'),
(66,15,45,'2026-05-04 11:09:52','2026-05-04 11:09:52','face_scan','100',24,0,1,'present'),
(67,6,45,'2026-05-04 11:10:30','2026-05-04 11:10:30','manual','100',24,0,1,'absent'),
(68,5,45,'2026-05-04 11:10:30','2026-05-04 11:10:30','manual','100',24,0,1,'absent'),
(70,20,46,'2026-05-04 12:59:44','2026-05-04 12:59:44','manual','100',24,0,1,'present'),
(72,27,46,'2026-05-04 16:43:35','2026-05-04 16:43:35','manual','100',24,0,1,'present'),
(73,28,46,'2026-05-04 16:43:35','2026-05-04 16:43:35','manual','100',24,0,1,'present'),
(74,26,47,'2026-05-05 15:00:01','2026-05-05 15:00:01','manual','100',24,0,1,'present'),
(75,27,47,'2026-05-05 15:00:01','2026-05-05 15:00:01','manual','100',24,0,1,'present'),
(76,21,47,'2026-05-05 15:03:55','2026-05-05 15:03:55','manual','100',24,0,1,'late'),
(77,20,47,'2026-05-05 15:03:55','2026-05-05 15:03:55','manual','100',24,0,1,'late'),
(78,28,47,'2026-05-05 15:04:15','2026-05-05 15:04:15','manual','100',24,0,1,'absent'),
(79,9,47,'2026-05-05 15:44:53','2026-05-05 15:44:53','manual','100',24,0,1,'absent'),
(80,15,49,'2026-05-06 23:19:53','2026-05-06 23:19:53','face_scan','100',24,0,1,'present'),
(81,33,50,'2026-05-07 00:25:26','2026-05-07 00:25:26','manual','100',60,0,1,'present');

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

insert  into `belt_levels`(`id`,`name`,`rank_order`,`color_code`) values 
(1,'White',1,'#FFFFFF'),
(2,'White-Yellow',2,'#FFFACD'),
(3,'Yellow',3,'#FFFF00'),
(4,'Yellow-Green',4,'#ADFF2F'),
(5,'Green',5,'#008000'),
(6,'Green-Blue',6,'#00CED1'),
(7,'Blue',7,'#0000FF'),
(8,'Blue-Red',8,'#8A2BE2'),
(9,'Red',9,'#FF0000'),
(10,'Red-Black',10,'#8B0000'),
(11,'Black',11,'#000000');

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

insert  into `billing_rules`(`id`,`monthly_fee`,`enrollment_fees`,`uniform_fees`,`belt_promotion_fees`,`competition_fees`,`billing_cycle`,`due_date_rule`,`grace_period`,`late_fees_type`,`late_fee_amount`,`allow_partial_payment`,`auto_mark_overdue`,`auto_generate_monthly_invoice`,`created_at`,`updated_at`) values 
(1,123.00,123.00,123.00,123.00,123.00,'Quarterly','5th of Month',3,'Percentage','30',1,1,1,'2026-03-23 14:58:54','2026-04-21 05:24:51');

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

insert  into `branches`(`id`,`name`,`code`,`address`,`city`,`province`,`mobile`,`email`,`status`,`created_at`,`updated_at`) values 
(1,'Quezon City Branch','BR001','123 Katipunan Ave','Quezon City','Metro Manila','09171234567','maria.santos@email.com','active','2026-03-01 08:00:00','2026-03-01 08:00:00'),
(2,'Manila Branch','BR002','45 Mabini St','Manila','Metro Manila','09181234568','juan.delacruz@email.com','active','2026-03-02 09:15:00','2026-03-02 09:15:00'),
(3,'Cebu Branch','BR003','678 Rizal Blvd','Cebu City','Cebu','09221234569','liza.reyes@email.com','active','2026-03-03 10:30:00','2026-03-05 14:20:00'),
(4,'Davao Branch','BR004','89 Bonifacio St','Davao City','Davao del Sur','09331234570','roberto.tan@email.com','active','2026-03-04 11:45:00','2026-03-04 11:45:00'),
(5,'Iloilo Branch','BR005','12 Lopez Jaena St','Iloilo City','Iloilo','09441234571','angela.mendoza@email.com','active','2026-03-05 13:00:00','2026-03-05 13:00:00'),
(7,'Muntinlupa Branch','BR006','Blk 1 lot 2','muntinlupa city','Metro manila','0938292821','testing@gmail.com','active','2026-03-23 14:18:39','2026-03-23 14:18:39');

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

insert  into `brandings`(`id`,`logo_path`,`logo_filename`,`certificate_header_text`,`certificate_signature_name`,`signature_position`,`official_seal_path`,`official_seal_filename`,`primary_color`,`secondary_color`,`accent_color`,`favicon_path`,`created_at`,`updated_at`) values 
(1,'branding/logos/0LoA2dbso4EUaKLmssg3xpF7UaM9GycfDmGe4orx.png','672347259_2367834793698310_2583631110720143572_n.png','TKD','TKD Testing','Chief Instructor','branding/seals/zfrRACaLyYhErVByUZS7g8MJfytnoLalePCAmzYa.png','649126567_1299888535319912_527797277755080973_n.png','#1C1C1D',NULL,NULL,NULL,'2026-03-19 08:52:49','2026-04-21 01:59:25'),
(2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'#1C1C1D',NULL,NULL,NULL,'2026-03-23 14:56:01','2026-03-23 14:56:01');

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

/*Table structure for table `certificate_templates` */

DROP TABLE IF EXISTS `certificate_templates`;

CREATE TABLE `certificate_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `type` enum('promotion','dan','competition') DEFAULT NULL,
  `layout` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`layout`)),
  `is_active` tinyint(1) DEFAULT 1,
  `background` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `certificate_templates` */

insert  into `certificate_templates`(`id`,`name`,`type`,`layout`,`is_active`,`background`,`created_at`,`updated_at`) values 
(1,'Belt Promotion Template','promotion','\"{\\\"objects\\\":[{\\\"type\\\":\\\"text\\\",\\\"key\\\":\\\"date_issue\\\",\\\"x\\\":520,\\\"y\\\":100,\\\"fontSize\\\":20,\\\"fill\\\":\\\"rgb(0,0,0)\\\"},{\\\"type\\\":\\\"text\\\",\\\"key\\\":\\\"student_name\\\",\\\"x\\\":100,\\\"y\\\":100,\\\"fontSize\\\":20,\\\"fill\\\":\\\"rgb(0,0,0)\\\"},{\\\"type\\\":\\\"text\\\",\\\"key\\\":\\\"belt_level\\\",\\\"x\\\":320,\\\"y\\\":100,\\\"fontSize\\\":20,\\\"fill\\\":\\\"rgb(0,0,0)\\\"}]}\"',1,NULL,NULL,'2026-04-12 16:09:31'),
(2,'building','competition','\"{\\\"student_name\\\":{\\\"x\\\":300,\\\"y\\\":250},\\\"belt_level\\\":{\\\"x\\\":300,\\\"y\\\":320}}\"',1,NULL,'2026-03-31 07:52:00','2026-03-31 07:52:00');

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
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
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
  `is_seen` varchar(100) DEFAULT NULL,
  `sender_user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `attachment_url` varchar(255) DEFAULT NULL,
  `sent_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `thread_id` (`thread_id`),
  KEY `sender_user_id` (`sender_user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `chat_messages` */

insert  into `chat_messages`(`id`,`thread_id`,`is_seen`,`sender_user_id`,`message`,`attachment_url`,`sent_at`) values 
(1,1,'0',7,'dwadawd',NULL,'2026-04-21 06:26:27'),
(2,2,'0',7,'this is testing',NULL,'2026-04-21 06:26:43');

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

insert  into `chat_participants`(`thread_id`,`user_id`) values 
(1,7),
(1,8),
(2,7),
(2,38);

/*Table structure for table `chat_threads` */

DROP TABLE IF EXISTS `chat_threads`;

CREATE TABLE `chat_threads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` enum('private','class','parent-staff') NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `is_seen` varchar(100) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `chat_threads` */

insert  into `chat_threads`(`id`,`type`,`name`,`is_seen`,`class_id`,`created_at`) values 
(1,'private',NULL,NULL,NULL,'0000-00-00 00:00:00'),
(2,'private',NULL,NULL,NULL,'0000-00-00 00:00:00');

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
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `class_schedules` */

insert  into `class_schedules`(`id`,`class_id`,`day_of_week`,`start_time`,`end_time`,`created_at`,`updated_at`) values 
(7,2,'monday','10:00:00','12:00:00','2026-03-25 16:36:51','2026-03-25 16:36:51'),
(8,1,'tuesday','13:00:00','16:00:00','2026-03-25 16:36:57','2026-03-25 16:36:57'),
(10,5,'wednesday','12:00:00','15:30:00','2026-03-26 12:12:26','2026-03-26 12:12:26'),
(11,3,'friday','08:30:00','11:00:00','2026-03-26 12:35:11','2026-03-26 12:35:11'),
(19,12,'saturday','17:43:00','18:30:00','2026-03-28 09:42:43','2026-03-28 09:42:43'),
(22,14,'monday','22:58:00','23:35:00','2026-03-31 14:37:40','2026-03-31 14:37:40'),
(23,14,'tuesday','22:38:00','23:25:00','2026-03-31 14:37:40','2026-03-31 14:37:40'),
(26,7,'monday','00:12:00','14:12:00','2026-04-06 16:23:25','2026-04-06 16:23:25'),
(35,6,'monday','22:10:00','23:30:00','2026-04-13 22:13:51','2026-04-13 22:13:51'),
(39,9,'monday','11:59:00','12:25:00','2026-04-20 11:02:54','2026-04-20 11:02:54'),
(40,13,'monday','10:15:00','11:50:00','2026-04-29 09:20:23','2026-04-29 09:20:23'),
(41,13,'tuesday','21:28:00','23:30:00','2026-04-29 09:20:23','2026-04-29 09:20:23'),
(42,13,'wednesday','09:15:00','11:30:00','2026-04-29 09:20:23','2026-04-29 09:20:23'),
(43,15,'monday','07:15:00','09:15:00','2026-05-07 00:16:35','2026-05-07 00:16:35'),
(44,15,'tuesday','07:15:00','09:15:00','2026-05-07 00:16:35','2026-05-07 00:16:35'),
(45,15,'wednesday','15:15:00','22:15:00','2026-05-07 00:16:35','2026-05-07 00:16:35'),
(46,15,'thursday','19:15:00','22:15:00','2026-05-07 00:16:35','2026-05-07 00:16:35');

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
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `class_sessions` */

insert  into `class_sessions`(`id`,`class_id`,`session_date`,`start_time`,`end_time`,`instructor_id`,`session_status`,`notes`,`created_at`,`updated_at`) values 
(25,7,'2026-03-28','07:26:51','08:26:51',6,'ongoing',NULL,'2026-03-28 07:26:51','2026-03-28 07:26:51'),
(26,6,'2026-03-28','07:28:25','08:28:25',6,'ongoing',NULL,'2026-03-28 07:28:25','2026-03-28 07:28:25'),
(27,12,'2026-03-28','07:55:09','08:55:09',6,'ongoing',NULL,'2026-03-28 07:55:09','2026-03-28 07:55:09'),
(30,6,'2026-03-30','03:35:20','04:35:20',6,'ongoing',NULL,'2026-03-30 03:35:20','2026-03-30 03:35:20'),
(31,13,'2026-03-30','04:42:10','05:42:10',6,'ongoing',NULL,'2026-03-30 04:42:10','2026-03-30 04:42:10'),
(32,14,'2026-03-30','15:00:28','16:00:28',6,'ongoing',NULL,'2026-03-30 15:00:28','2026-03-30 15:00:28'),
(33,14,'2026-03-31','14:38:19','15:38:19',6,'ongoing',NULL,'2026-03-31 14:38:19','2026-03-31 14:38:19'),
(34,13,'2026-04-10','15:24:56','16:24:56',6,'ongoing',NULL,'2026-04-10 15:24:56','2026-04-10 15:24:56'),
(35,13,'2026-04-12','15:31:09','16:31:09',6,'ongoing',NULL,'2026-04-12 15:31:09','2026-04-12 15:31:09'),
(36,6,'2026-04-13','02:20:41','03:20:41',6,'ongoing',NULL,'2026-04-13 02:20:41','2026-04-13 02:20:41'),
(37,13,'2026-04-13','02:20:46','03:20:46',6,'ongoing',NULL,'2026-04-13 02:20:46','2026-04-13 02:20:46'),
(38,13,'2026-04-14','21:29:29','22:29:29',6,'ongoing',NULL,'2026-04-14 21:29:29','2026-04-14 21:29:29'),
(39,9,'2026-04-15','22:55:33','23:55:33',6,'ongoing',NULL,'2026-04-15 22:55:33','2026-04-15 22:55:33'),
(40,13,'2026-04-20','10:37:03','11:37:03',6,'ongoing',NULL,'2026-04-20 10:37:03','2026-04-20 10:37:03'),
(41,14,'2026-04-27','17:18:45','18:18:45',6,'ongoing',NULL,'2026-04-27 17:18:45','2026-04-27 17:18:45'),
(42,6,'2026-04-27','17:18:53','18:18:53',6,'ongoing',NULL,'2026-04-27 17:18:53','2026-04-27 17:18:53'),
(43,13,'2026-04-28','12:54:17','13:54:17',6,'ongoing',NULL,'2026-04-28 12:54:17','2026-04-28 12:54:17'),
(44,13,'2026-04-29','10:02:11','11:02:11',6,'ongoing',NULL,'2026-04-29 10:02:11','2026-04-29 10:02:11'),
(45,13,'2026-05-04','11:09:37','12:09:37',6,'ongoing',NULL,'2026-05-04 11:09:37','2026-05-04 11:09:37'),
(46,6,'2026-05-04','12:59:37','13:59:37',6,'ongoing',NULL,'2026-05-04 12:59:37','2026-05-04 12:59:37'),
(47,13,'2026-05-05','14:46:25','15:46:25',6,'ongoing',NULL,'2026-05-05 14:46:25','2026-05-05 14:46:25'),
(48,14,'2026-05-05','22:21:51','23:21:51',6,'ongoing',NULL,'2026-05-05 22:21:51','2026-05-05 22:21:51'),
(49,13,'2026-05-06','00:40:21','01:40:21',6,'ongoing',NULL,'2026-05-06 00:40:21','2026-05-06 00:40:21'),
(50,15,'2026-05-07','00:25:20','01:25:20',10,'ongoing',NULL,'2026-05-07 00:25:20','2026-05-07 00:25:20');

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
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `class_students` */

insert  into `class_students`(`id`,`class_id`,`student_id`,`start_date`,`end_date`,`status`) values 
(3,2,1,'2026-03-27',NULL,'active'),
(4,2,2,'2026-03-26',NULL,'active'),
(7,7,1,'2026-03-27',NULL,'active'),
(8,7,2,'2026-03-27',NULL,'active'),
(9,6,1,'2026-03-27',NULL,'active'),
(10,6,2,'2026-03-27',NULL,'active'),
(11,9,1,'2026-03-27',NULL,'active'),
(12,6,5,'2026-03-27',NULL,'active'),
(15,12,5,'2026-03-28',NULL,'active'),
(16,12,2,'2026-03-28',NULL,'active'),
(17,12,1,'2026-03-28',NULL,'active'),
(18,12,6,'2026-03-28',NULL,'active'),
(20,13,6,'2026-03-30',NULL,'active'),
(22,13,13,'2026-03-30',NULL,'active'),
(24,14,7,'2026-03-30',NULL,'active'),
(25,13,15,'2026-03-31',NULL,'active'),
(26,14,19,'2026-04-06',NULL,'active'),
(27,13,21,'2026-04-10',NULL,'active'),
(28,13,20,'2026-04-10',NULL,'active'),
(29,13,9,'2026-04-10',NULL,'active'),
(30,6,15,'2026-04-13',NULL,'active'),
(31,6,20,'2026-04-13',NULL,'active'),
(32,6,22,'2026-04-13',NULL,'active'),
(33,6,23,'2026-04-13',NULL,'active'),
(34,9,24,'2026-04-20',NULL,'active'),
(35,13,26,'2026-04-29',NULL,'active'),
(36,13,27,'2026-04-29',NULL,'active'),
(37,13,28,'2026-04-29',NULL,'active'),
(38,6,27,'2026-05-04',NULL,'active'),
(39,6,28,'2026-05-04',NULL,'active'),
(40,13,32,'2026-05-06',NULL,'active'),
(41,15,33,'2026-05-06',NULL,'active');

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
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `classes` */

insert  into `classes`(`id`,`branch_id`,`class_name`,`age_group`,`level`,`max_students`,`primary_instructor_id`,`assistant_instructor_id`,`status`,`created_at`,`updated_at`) values 
(1,2,'Taekwondo Beginner Basics','24-35','White',20,3,2,'active','2026-03-05 00:00:00','2026-03-25 16:36:57'),
(2,1,'White Belt Fundamentals Class','8-14','White',20,5,NULL,'active','2026-03-25 02:32:41','2026-03-25 16:36:51'),
(3,1,'Taekwondo 101: White Belt Level','10-15','White',20,4,NULL,'inactive','2026-03-25 02:34:48','2026-03-26 12:35:11'),
(5,4,'White-Yellow Belt Skills Development','17-20','White-Yellow',20,4,3,'active','2026-03-26 12:12:26','2026-03-26 12:12:26'),
(6,1,'Veterans training','10','Blue-Red',10,6,NULL,'active','2026-03-26 14:15:53','2026-04-13 22:13:51'),
(7,1,'Hard training','10-15','Blue-Red',5,8,7,'active','2026-03-26 15:12:59','2026-04-06 16:23:25'),
(9,1,'Intermediate Training','10-15','White-Yellow',10,6,9,'active','2026-03-27 02:22:16','2026-04-20 11:02:54'),
(12,1,'Master Training','18-22','Red-Black',10,6,9,'active','2026-03-28 07:54:23','2026-03-28 07:54:23'),
(13,1,'Master Training','18-22','Black',10,6,8,'active','2026-03-30 04:41:01','2026-04-06 16:54:07'),
(14,2,'Fundamental Training','7-10','Blue-Red',10,6,8,'active','2026-03-30 14:56:55','2026-03-30 14:56:55'),
(15,2,'Kids Training','10','Yellow',10,10,7,'active','2026-05-07 00:16:35','2026-05-07 00:16:35');

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

insert  into `club_profiles`(`id`,`club_name`,`club_acronym`,`founded_year`,`club_description`,`email`,`contact_number`,`club_address`,`logo_url`,`website_url`,`facebook_url`,`instagram_url`,`tax_id`,`created_at`,`updated_at`) values 
(1,'Taekwondo','TKD','2026','Premier Taekwondo training facility dedicated to excellence in martial arts education and character development.','info@tkdchampions.com','+63 (2) 1234-5678','123 Martial Arts Avenue, Barangay Sports Complex, Manila, Philippines',NULL,'https://tkdchampions.com','https://facebook.com/tkdchampions','https://instagram.com/tkdchampions','123-456-789-000','2026-03-19 06:11:24','2026-03-19 08:14:40');

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
  `created_at` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `competition_id` (`competition_id`),
  KEY `student_id` (`student_id`),
  KEY `instructor_id` (`instructor_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `competition_entries` */

insert  into `competition_entries`(`id`,`competition_id`,`student_id`,`instructor_id`,`category`,`division`,`result`,`medal`,`remarks`,`updated_at`,`created_at`) values 
(1,1,20,2,'Sparring','20','draw','gold','testing','2026-04-17 16:13:41','2026-04-17'),
(2,1,27,6,'Sparring','20','pending','none','test','2026-05-04 11:35:22','2026-05-04'),
(3,2,20,6,'Sparring','20','win','silver','Good Work!','2026-05-04 12:55:59','2026-05-04'),
(4,2,20,6,'Sparring','20','loss','bronze','Nice Try!','2026-05-04 12:57:08','2026-05-04'),
(5,2,20,6,'Sparring','20','win','gold','Good','2026-05-04 12:57:50','2026-05-04'),
(6,2,20,6,'Sparring','20','win','gold','Verry Good','2026-05-04 12:58:25','2026-05-04');

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `competitions` */

insert  into `competitions`(`id`,`name`,`location`,`date`,`organizer`,`level`,`created_at`,`updated_at`,`status`) values 
(1,'Metro Manila Taekwondo Open','Quezon City Sports Complex, Quezon City','2026-06-16','Philippine Taekwondo Association','regional','2026-04-01 01:08:22','2026-04-01 01:36:28','active'),
(2,'Thailand Taekwondo Open','Bangkok Thailand','2026-05-03','International Taekwondo Association','international','2026-05-04 12:53:39','2026-05-04 12:53:39','active');

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

insert  into `discounts`(`id`,`name`,`type`,`value`,`applicable_to`,`valid_from`,`valid_to`,`status`,`created_at`,`updated_at`) values 
(1,'White Belt Special','percent',10.00,'all','2026-03-24','2026-03-26',0,'2026-03-24 13:07:42','2026-03-24 13:07:42'),
(2,'Intro Starter Bundle','fixed',69.00,'enrollment','2026-03-25','2026-03-26',0,'2026-03-24 13:09:26','2026-03-24 13:09:26'),
(3,'New Student Uniform Credit','percent',15.00,'enrollment','2026-04-01','2026-04-10',1,'2026-03-24 13:22:31','2026-03-24 13:22:31'),
(4,'Black Belt Path Enrollment','fixed',50.00,'enrollment','2026-04-02','2026-04-30',1,'2026-03-24 13:22:31','2026-03-24 13:22:31'),
(5,'Sibling Legacy Discount','percent',10.00,'monthly fee','2026-04-05','2026-05-05',1,'2026-03-24 13:22:31','2026-03-24 13:22:31'),
(6,'early birdParent Child Duo Promo','fixed',30.00,'all','2026-04-01','2026-04-07',1,'2026-03-24 13:22:31','2026-03-24 13:48:49'),
(7,'Family Unity Rate','percent',20.00,'all','2026-12-20','2026-12-31',1,'2026-03-24 13:22:31','2026-03-24 13:22:31'),
(8,'Refer A Sparring Partner','fixed',25.00,'monthly fee','2026-04-03','2026-04-20',1,'2026-03-24 13:22:31','2026-03-24 13:22:31'),
(9,'Summer Shatter Sale','percent',5.00,'all','2026-04-06','2026-04-06',0,'2026-03-24 13:22:31','2026-03-24 13:22:31'),
(10,'Back To School Focus Promo','percent',12.00,'monthly fee','2026-04-01','2026-06-01',1,'2026-03-24 13:22:31','2026-03-24 13:22:31'),
(11,'New Year New Goal Discount','fixed',40.00,'enrollment','2026-04-01','2026-04-15',1,'2026-03-24 13:22:31','2026-03-24 13:22:31'),
(13,'Spring Graduation Special','percent',40.00,'enrollment','2026-03-23','2026-03-27',0,'2026-03-24 16:05:10','2026-03-24 16:05:10');

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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `instructors` */

insert  into `instructors`(`id`,`user_id`,`fname`,`lname`,`photo`,`email`,`contact`,`username`,`password`,`rank_belt`,`certification_level`,`specialization`,`hire_date`,`bio`,`status`,`active_flag`,`created_at`,`updated_at`) values 
(2,0,'Minho','Park','instructors/6gp9JMND0sAfY6y7fc59ST7yVPUSVCRBO3Fs1IEA.jpg','ogiechanco0@gmail.com','09171234567','ogiechanco','$2y$12$De449fdZdrp9cPRV0JpSAOl0yc0P0.vvksjXLaqwtBoTOP4ztpI7O','Black Belt','Head Instructor','deawewadaw','2026-03-25 01:50:33','dawdawdwadasdawdas','active',1,'2026-03-18 04:08:33','2026-03-19 02:02:11'),
(3,0,'Jisoo','Kim','instructors/bF9UegHz4yt4se35xe6ZjYe8dtvr5TPqYBZTRyN4.jpg','resshin240@gmail.com','0938292812','1234admin','$2y$12$pO9ee169WnJpr0MQJY4P1udaTs0cYJbe8O9sLwoYzqjNqCKdE4HQC','Black Belt','Head Instructor','ewan','2026-03-25 01:50:43','awdwadasd','active',1,'2026-03-18 04:34:24','2026-03-18 04:34:24'),
(4,0,'John','Doe','instructors/Ievw56S3lHOTmOubm8G4jidqP154ljrmplrp4N9X.jpg','interimpink@talemarketing.com','0938292321','1_IQ','$2y$12$Go1NSoyNDez0vRsDYZDnRu1soZD0wvzTg/GBdWl5L47Jt/OJ/SuO2','Black Belt','Head Instructor','dsad','2026-03-18 00:00:00','2wdawdawdawd','active',1,'2026-03-18 04:44:53','2026-03-18 04:44:53'),
(5,0,'Taeyang','Choi',NULL,'porras@porrs','09171234567','dwadwadawd','$2y$12$34ZbxYWrK327SfZ7VTtlTuZqtV1E./na3AQnsxIvzffWpeh.rlZYa','Red Belt','Assistant Instructor','kick boxing','2026-03-25 01:50:50','loremipsum','active',1,'2026-03-24 17:11:38','2026-03-24 17:11:38'),
(6,24,'Eduard','Estareja','/storage/instructor_photos/eFHVHpiUhXgepXezoIHD6JAeoLceKRq0P27mIVYK.png','eduardestarejajr@gmail.com','09826717854','testing@gmail.com','$2y$12$gLhLdEEjfhL1i1a7i0RFb.xEBEL.einOX3jXlVHcsEsZwP5JBZgOm','Black Belt','Head Instructor','flying kick','2026-05-05 23:04:36','trust the process','active',1,'2026-03-26 13:40:09','2026-03-26 13:40:09'),
(7,25,'john','alvior','instructors/ekceucBRTf40SSg9awHumCiMRsOA2hwUyeXLZpbF.png','john@gmail.com','09874783672','janjan','$2y$12$12Ph/MScgQTzwM0.JeQGYO1xK1En4dB1fwVTxp6kPmXjAmbzClBz6','Red Belt','Head Instructor','flying kick','2026-03-26 22:40:36','hard working','active',1,'2026-03-26 14:34:39','2026-03-26 14:34:39'),
(8,26,'triever','ruga','instructors/LQt3BlhWasQ5jZlSoaLXOYGHmtfwWhtAQvFgrfZJ.png','triever@gmail.com','0987462713','everr','$2y$12$oji7lQPPuc.nErUsrW/N8O7WMyQPzyMVNLzZ76SGk4MEvDUJmAcaO','Red Belt','Head Instructor','flying kick','2026-03-26 23:10:13','intellegence','active',1,'2026-03-26 15:10:13','2026-03-26 15:10:13'),
(9,27,'Mica','Barrientos','instructors/8NFkFD653sqjw7p0Ihn4A8zgpze13w5md0MPgDq6.png','mica@gmail.com','0987462713','micah','$2y$12$dOH4T5KvpX2JlYTqC85TGeOyhl7EgQq0Jba0ZstQkcgRL9NjhQTMm','Black Belt','Head Instructor','flying kick','2026-03-27 23:28:30','hard working','active',1,'2026-03-27 15:28:30','2026-03-27 15:28:30'),
(10,60,'Lee','Sin','/storage/instructor_photos/Mq0iF8YuY7XU43yGKLL12SEbNpvNXTFpFVd3zxPi.png','lee@gmail.com','09860283764','Lee','$2y$12$Nq5PbYRyPxRQ9ZiexE6TaOW5nak2vyuxJda.BWtwl2.0OXUZBFNzC','Black Belt','Head Instructor','flying kick','2026-05-07 00:00:41','Good trainor','active',1,'2026-05-07 00:00:10','2026-05-07 00:00:10');

/*Table structure for table `invoices` */

DROP TABLE IF EXISTS `invoices`;

CREATE TABLE `invoices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `subscription_id` int(11) NOT NULL,
  `invoice_no` varchar(50) NOT NULL,
  `billing_period_start` date NOT NULL,
  `billing_period_end` date NOT NULL,
  `amount` decimal(10,2) DEFAULT 0.00,
  `discount` decimal(10,2) DEFAULT 0.00,
  `penalty` decimal(10,2) DEFAULT 0.00,
  `total_due` decimal(10,2) DEFAULT 0.00,
  `due_date` date NOT NULL,
  `status` enum('pending','paid','overdue','void','partial','pending_verification') DEFAULT 'pending',
  `payment_proof` varchar(255) DEFAULT NULL,
  `proof_uploaded_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `invoice_no` (`invoice_no`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `invoices` */

insert  into `invoices`(`id`,`student_id`,`parent_id`,`subscription_id`,`invoice_no`,`billing_period_start`,`billing_period_end`,`amount`,`discount`,`penalty`,`total_due`,`due_date`,`status`,`payment_proof`,`proof_uploaded_at`,`created_at`,`updated_at`) values 
(1,19,17,2,'INV-202604-0001','2026-04-01','2026-04-30',1000.00,50.00,0.00,950.00,'2026-04-05','paid',NULL,NULL,'2026-04-21 05:13:20','2026-04-21 05:27:49'),
(2,18,16,3,'INV-202604-0002','2026-04-01','2026-04-30',1000.00,0.00,0.00,1000.00,'2026-04-05','paid',NULL,NULL,'2026-04-21 05:33:29','2026-04-21 05:34:20'),
(3,25,47,4,'INV-202604-0003','2026-04-01','2026-04-30',500.00,0.00,0.00,500.00,'2026-04-05','paid',NULL,NULL,'2026-04-21 17:30:29','2026-04-21 17:30:58'),
(10,15,16,5,'INV-202604-0004','2026-04-01','2026-04-30',1000.00,0.00,0.00,1000.00,'2026-04-05','paid','payment-proofs/6fy7Hovb3duziTTV3k54sNCwGjiTDf7VuB2CBr6g.jpg','2026-04-23 21:24:08','2026-04-23 21:22:21','2026-04-23 21:26:04'),
(33,23,18,6,'INV-202604-0005','2026-04-01','2026-04-30',1499.97,0.00,0.00,1499.97,'2026-04-05','paid','payment-proofs/wIQRQh3yAjlJtfgtgJyrybhnNInxIA0ALVTOj33b.jpg','2026-04-23 23:19:29','2026-04-23 23:19:17','2026-04-23 23:22:19'),
(39,15,16,5,'INV-202605-0006','2026-05-01','2026-05-31',1000.00,0.00,0.00,1000.00,'2026-05-05','paid','payment-proofs/gd19s73bGkw6cstrg9B0Dkf1Cz7OfOg6v2CwbpMr.jpg','2026-04-28 13:35:24','2026-05-01 00:00:00','2026-04-28 13:36:50'),
(41,18,16,3,'INV-202605-0007','2026-05-01','2026-05-31',1000.00,0.00,0.00,1000.00,'2026-05-05','pending',NULL,NULL,'2026-05-01 00:00:00','2026-05-01 00:00:00'),
(42,19,17,2,'INV-202605-0008','2026-05-01','2026-05-31',1000.00,0.00,0.00,1000.00,'2026-05-05','pending',NULL,NULL,'2026-05-01 00:00:00','2026-05-01 00:00:00'),
(43,23,18,6,'INV-202605-0009','2026-05-01','2026-05-31',1499.97,0.00,0.00,1499.97,'2026-05-05','pending',NULL,NULL,'2026-05-01 00:00:00','2026-05-01 00:00:00'),
(44,25,47,4,'INV-202605-0010','2026-05-01','2026-05-31',500.00,0.00,0.00,500.00,'2026-05-05','pending',NULL,NULL,'2026-05-01 00:00:00','2026-05-01 00:00:00'),
(49,28,51,7,'INV-202604-0011','2026-04-01','2026-04-30',1000.00,0.00,0.00,1000.00,'2026-04-05','paid','payment-proofs/a3c6nB5rrDADL7V0cfoGvk10osgnYCfbqlIAzE2s.jpg','2026-04-29 09:58:44','2026-04-29 09:40:51','2026-04-29 10:13:21'),
(50,27,51,8,'INV-202604-0012','2026-04-01','2026-04-30',1000.00,0.00,0.00,1000.00,'2026-04-05','paid','payment-proofs/iZ2EKDq8K44HB32MJBMdh1IC8GLPo0z7GQ6tGImA.jpg','2026-05-04 15:53:34','2026-04-29 09:41:09','2026-05-04 15:53:56'),
(51,27,51,8,'INV-202605-0013','2026-05-01','2026-05-31',1000.00,0.00,0.00,1000.00,'2026-05-05','paid','payment-proofs/ER0PGsoyVnt0ZAeEc2gUe04eZD2WRHU1gHlnotDI.jpg','2026-05-04 15:54:07','2026-05-01 00:00:00','2026-05-04 15:54:22'),
(53,28,51,7,'INV-202605-0014','2026-05-01','2026-05-31',1000.00,0.00,0.00,1000.00,'2026-05-05','paid','payment-proofs/RQeNNaHLh1eef1uHWQCh0L2QuLTTCnL5tTTp26FG.jpg','2026-04-29 20:38:01','2026-05-01 00:00:00','2026-04-29 20:38:28'),
(55,32,18,9,'INV-202605-0015','2026-05-01','2026-05-31',1000.00,0.00,0.00,1000.00,'2026-05-05','paid','payment-proofs/RHxuHS5oDpw5v0VH5t3vEYkwqLhNHqDFZx7eKNIE.jpg','2026-05-06 23:00:35','2026-05-06 22:57:30','2026-05-06 23:00:52'),
(56,33,20,10,'INV-202605-0016','2026-05-01','2026-05-31',500.00,0.00,0.00,500.00,'2026-06-05','paid','payment-proofs/kEVd243Z3US24ZFzWBhYeUG7VCjuhVg6Hejacvny.jpg','2026-05-07 00:19:11','2026-05-07 00:18:45','2026-05-07 00:19:21');

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

insert  into `migrations`(`id`,`migration`,`batch`) values 
(1,'2026_03_13_021327_create_personal_access_tokens_table',1),
(2,'2026_03_13_063529_create_cache_table',2),
(3,'2026_03_19_055841_create_club_profiles_table',3),
(4,'2026_03_19_060249_create_club_profiles_table',4);

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
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `parent_students` */

insert  into `parent_students`(`id`,`parent_id`,`student_id`,`relationship`,`is_primary`,`created_at`,`updated_at`) values 
(1,12,1,'father',0,'2026-03-26 13:38:56',NULL),
(2,13,22,'guardian',0,'2026-04-15 22:13:21',NULL),
(3,13,23,'guardian',0,'2026-04-15 22:13:21',NULL),
(4,16,23,'father',0,'2026-04-23 21:34:40',NULL),
(5,18,27,'guardian',0,'2026-04-29 20:35:16',NULL),
(6,18,28,'guardian',0,'2026-04-29 20:35:16',NULL),
(7,19,29,'father',0,'2026-05-06 21:08:31',NULL),
(8,19,30,'father',0,'2026-05-06 21:08:31',NULL),
(9,54,31,'mother',0,'2026-05-06 21:16:04',NULL),
(10,10,2,'mother',0,'2026-05-06 21:18:26',NULL),
(11,17,4,'mother',0,'2026-05-06 21:18:26',NULL),
(12,22,5,'mother',0,'2026-05-06 21:18:26',NULL),
(13,19,6,'mother',0,'2026-05-06 21:18:26',NULL),
(14,10,7,'mother',0,'2026-05-06 21:18:26',NULL),
(15,19,8,'mother',0,'2026-05-06 21:18:26',NULL),
(16,20,9,'mother',0,'2026-05-06 21:18:26',NULL),
(17,10,10,'mother',0,'2026-05-06 21:18:26',NULL),
(18,16,11,'mother',0,'2026-05-06 21:18:26',NULL),
(19,10,12,'mother',0,'2026-05-06 21:18:26',NULL),
(20,16,13,'mother',0,'2026-05-06 21:18:26',NULL),
(21,16,15,'mother',0,'2026-05-06 21:18:26',NULL),
(22,20,17,'mother',0,'2026-05-06 21:18:26',NULL),
(23,16,18,'mother',0,'2026-05-06 21:18:26',NULL),
(24,17,19,'mother',0,'2026-05-06 21:18:26',NULL),
(25,16,20,'mother',0,'2026-05-06 21:18:26',NULL),
(26,10,21,'mother',0,'2026-05-06 21:18:26',NULL),
(27,45,24,'mother',0,'2026-05-06 21:18:26',NULL),
(28,47,25,'mother',0,'2026-05-06 21:18:26',NULL),
(29,44,26,'mother',0,'2026-05-06 21:18:26',NULL),
(41,18,32,'mother',0,'2026-05-06 22:53:39',NULL),
(42,20,33,'mother',0,'2026-05-07 00:09:51',NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `parents` */

insert  into `parents`(`id`,`user_id`,`emergency_contact`,`relationship_note`,`address`,`id_verified_flag`,`created_at`,`updated_at`) values 
(6,16,'Maria Santos','Mother','Quezon City',1,'2026-03-17 12:06:47','2026-03-17 12:06:47'),
(7,17,'Jose Cruz','Father','Makati City',1,'2026-03-17 12:06:47','2026-03-17 12:06:47'),
(8,18,'Anna Tan','Mother','Pasig City',1,'2026-03-17 12:06:47','2026-03-17 12:06:47'),
(9,19,'Carlos Garcia','Father','Caloocan City',1,'2026-03-17 12:06:47','2026-03-17 12:06:47'),
(10,20,'Grace Lee','Mother','Manila City',1,'2026-03-17 12:06:47','2026-03-17 12:06:47'),
(11,22,'09876521465','Father','1177 Quirino Highway, Brgy kaligayahan, Novaliches',1,'2026-03-26 13:29:58',NULL),
(12,23,'09876521465','Father','1177 Quirino Highway, Brgy kaligayahan, Novaliches',1,'2026-03-26 13:38:56',NULL),
(13,44,'09699623018','Guardian','camarin caloocan city',1,'2026-04-15 22:13:21',NULL),
(14,45,'091010982037','Legal Guardian','Alcoy Cebu',1,'2026-04-20 10:59:17',NULL),
(15,47,'09109853927','Mother','Camarin',1,'2026-04-21 17:13:48',NULL),
(16,49,'09698372017','Father','Lupang arienda sta ana taytay rizal',1,'2026-04-23 21:34:40',NULL),
(18,54,'09123456789','Guardian','camarin caloocan city',1,'2026-04-29 20:35:16',NULL),
(19,57,'09283921843','Father','bagong silang caloocan city',1,'2026-05-06 21:08:31',NULL),
(20,61,'09387834785','Mother','bagong silang caloocan city',1,'2026-05-07 00:05:29',NULL);

/*Table structure for table `payments` */

DROP TABLE IF EXISTS `payments`;

CREATE TABLE `payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_id` int(11) NOT NULL,
  `transaction_reference` varchar(255) DEFAULT NULL,
  `amount` decimal(12,2) NOT NULL,
  `payment_method` enum('cash','gcash','card','bank') NOT NULL,
  `reference_no` varchar(100) DEFAULT NULL,
  `paid_at` datetime NOT NULL,
  `received_by_user_id` int(11) NOT NULL,
  `status` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `invoice_id` (`invoice_id`),
  KEY `received_by_user_id` (`received_by_user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `payments` */

insert  into `payments`(`id`,`invoice_id`,`transaction_reference`,`amount`,`payment_method`,`reference_no`,`paid_at`,`received_by_user_id`,`status`,`created_at`,`updated_at`) values 
(1,1,NULL,950.00,'cash',NULL,'2026-04-21 05:31:25',0,'completed',NULL,NULL),
(2,2,NULL,1000.00,'cash',NULL,'2026-04-21 05:34:20',0,'completed','2026-04-21 05:34:20','2026-04-21 05:34:20'),
(3,3,NULL,500.00,'cash',NULL,'2026-04-21 17:30:58',0,'completed','2026-04-21 17:30:58','2026-04-21 17:30:58'),
(4,8,'payment-proofs/TPS5R4kKL88WJXV1EY6a06mRzQ82QQuDMvtNeFs0.jpg',1000.00,'',NULL,'2026-04-23 21:12:19',0,'completed','2026-04-23 21:12:19','2026-04-23 21:12:19'),
(5,10,'payment-proofs/6fy7Hovb3duziTTV3k54sNCwGjiTDf7VuB2CBr6g.jpg',1000.00,'',NULL,'2026-04-23 21:26:03',0,'completed','2026-04-23 21:26:04','2026-04-23 21:26:04'),
(6,11,'payment-proofs/yylv4Wt1hayDKrBdzC2r6llIMjpIpRUDyKl3TItZ.jpg',1499.97,'',NULL,'2026-04-23 22:33:59',0,'completed','2026-04-23 22:33:59','2026-04-23 22:33:59'),
(7,30,'payment-proofs/Otd0Csk1SwlQeTu0ehkgK96ADzVwXynwfWjEsOs9.jpg',1499.97,'',NULL,'2026-04-23 23:12:06',0,'completed','2026-04-23 23:12:06','2026-04-23 23:12:06'),
(8,33,'payment-proofs/wIQRQh3yAjlJtfgtgJyrybhnNInxIA0ALVTOj33b.jpg',1499.97,'',NULL,'2026-04-23 23:22:19',0,'completed','2026-04-23 23:22:19','2026-04-23 23:22:19'),
(9,39,'payment-proofs/gd19s73bGkw6cstrg9B0Dkf1Cz7OfOg6v2CwbpMr.jpg',1000.00,'',NULL,'2026-04-28 13:36:50',0,'completed','2026-04-28 13:36:50','2026-04-28 13:36:50'),
(10,49,'payment-proofs/a3c6nB5rrDADL7V0cfoGvk10osgnYCfbqlIAzE2s.jpg',1000.00,'',NULL,'2026-04-29 10:13:21',0,'completed','2026-04-29 10:13:21','2026-04-29 10:13:21'),
(11,53,'payment-proofs/RQeNNaHLh1eef1uHWQCh0L2QuLTTCnL5tTTp26FG.jpg',1000.00,'',NULL,'2026-04-29 20:38:28',0,'completed','2026-04-29 20:38:28','2026-04-29 20:38:28'),
(12,50,'payment-proofs/iZ2EKDq8K44HB32MJBMdh1IC8GLPo0z7GQ6tGImA.jpg',1000.00,'',NULL,'2026-05-04 15:53:56',0,'completed','2026-05-04 15:53:56','2026-05-04 15:53:56'),
(13,51,'payment-proofs/ER0PGsoyVnt0ZAeEc2gUe04eZD2WRHU1gHlnotDI.jpg',1000.00,'',NULL,'2026-05-04 15:54:22',0,'completed','2026-05-04 15:54:22','2026-05-04 15:54:22'),
(14,55,'payment-proofs/RHxuHS5oDpw5v0VH5t3vEYkwqLhNHqDFZx7eKNIE.jpg',1000.00,'',NULL,'2026-05-06 23:00:52',0,'completed','2026-05-06 23:00:52','2026-05-06 23:00:52'),
(15,56,'payment-proofs/kEVd243Z3US24ZFzWBhYeUG7VCjuhVg6Hejacvny.jpg',500.00,'',NULL,'2026-05-07 00:19:21',0,'completed','2026-05-07 00:19:21','2026-05-07 00:19:21');

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
) ENGINE=InnoDB AUTO_INCREMENT=498 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `personal_access_tokens` */

insert  into `personal_access_tokens`(`id`,`tokenable_type`,`tokenable_id`,`name`,`token`,`abilities`,`last_used_at`,`expires_at`,`created_at`,`updated_at`) values 
(1,'App\\Models\\User',7,'flutter-mobile-app','4c0b0a6e7d284b137bda9502f50c048a12bb061f75af4b1f385f6e5dca05204d','[\"*\"]',NULL,NULL,'2026-03-13 02:20:32','2026-03-13 02:20:32'),
(2,'App\\Models\\User',7,'flutter-mobile-app','8898e355447851ee9eb95c8597365758abe94e06d6baabae4421a6a7d021c455','[\"*\"]',NULL,NULL,'2026-03-13 03:36:02','2026-03-13 03:36:02'),
(3,'App\\Models\\User',7,'flutter-mobile-app','4b4c5c47651cfd40692f715c1f94b038a59cc02b387c1a59e5a73994879a5c6f','[\"*\"]',NULL,NULL,'2026-03-13 03:38:36','2026-03-13 03:38:36'),
(4,'App\\Models\\User',7,'flutter-mobile-app','73dcd67e1710ce1ff4b8494c1a39ecb909bf86485a11daa76dd45e2485f88075','[\"*\"]',NULL,NULL,'2026-03-13 06:02:52','2026-03-13 06:02:52'),
(5,'App\\Models\\User',7,'flutter-mobile-app','f99378ab688cde79ec5e72cb6ad84aaaa9779710cabac36800a789604a08bbbe','[\"*\"]',NULL,NULL,'2026-03-13 06:13:56','2026-03-13 06:13:56'),
(6,'App\\Models\\User',7,'flutter-mobile-app','f16bd7bacad7b16a2a84e1aca7bca70e76761d8a104ad433c357df91c61fa1dc','[\"*\"]',NULL,NULL,'2026-03-13 06:26:31','2026-03-13 06:26:31'),
(7,'App\\Models\\User',7,'flutter-mobile-app','92e8df958fa48c046f5caafdab68bb9d4495be1ff5354642bad0c576a1d69e21','[\"*\"]',NULL,NULL,'2026-03-13 07:06:21','2026-03-13 07:06:21'),
(8,'App\\Models\\User',7,'flutter-mobile-app','fe4e87d65e93fea1a8e47270febd434f8017233e2c59a134099a5148ed181ab9','[\"*\"]','2026-03-13 09:14:08',NULL,'2026-03-13 07:58:49','2026-03-13 09:14:08'),
(9,'App\\Models\\User',7,'flutter-mobile-app','67806086a3651c1670922edc3d25affa8d1624213ad94e05b1f4cf99a388f09f','[\"*\"]','2026-03-16 03:37:28',NULL,'2026-03-13 09:14:13','2026-03-16 03:37:28'),
(10,'App\\Models\\User',7,'flutter-mobile-app','995ea63c191272dff538b60019c77187bcb090ab8e585ede6e9e91d2165c1b14','[\"*\"]',NULL,NULL,'2026-03-16 03:37:03','2026-03-16 03:37:03'),
(11,'App\\Models\\User',7,'flutter-mobile-app','844c3b35b2cf8f97053e8244879953b5c6246fab5fa86f09cb48080d5cdf4ea1','[\"*\"]',NULL,NULL,'2026-03-16 08:26:00','2026-03-16 08:26:00'),
(12,'App\\Models\\User',7,'flutter-mobile-app','ca18ac77bf0b8fffb1e1ed582d4c7d30646a6b9e7c88a7464ca060d258d9bd5e','[\"*\"]','2026-03-16 08:28:36',NULL,'2026-03-16 08:26:35','2026-03-16 08:28:36'),
(13,'App\\Models\\User',24,'flutter-mobile-app','d0fd7851cce12366e6a3cefb644014b108fe0b466d1fee53df9eaace037c3a00','[\"*\"]',NULL,NULL,'2026-03-26 13:41:31','2026-03-26 13:41:31'),
(14,'App\\Models\\User',24,'flutter-mobile-app','ec018d4736aab0f021519db883ffd7a3d71dc6c227e0068c0ebbe63079f22870','[\"*\"]',NULL,NULL,'2026-03-26 14:02:49','2026-03-26 14:02:49'),
(15,'App\\Models\\User',24,'flutter-mobile-app','3baf7823cebbecccc0f78d2169dffede7ab4ce2563825d43e111a664465646ac','[\"*\"]',NULL,NULL,'2026-03-26 14:03:31','2026-03-26 14:03:31'),
(16,'App\\Models\\User',24,'flutter-mobile-app','82e001760672c6335113656613c3a2ed93682a7ded3a57e2501d6c7fbabb2da6','[\"*\"]',NULL,NULL,'2026-03-26 14:08:13','2026-03-26 14:08:13'),
(17,'App\\Models\\User',24,'flutter-mobile-app','57c844d98646358e1d19b7a070ce88bad2ceaab54fed26dfd43fed1234eae501','[\"*\"]','2026-03-26 14:39:53',NULL,'2026-03-26 14:09:37','2026-03-26 14:39:53'),
(18,'App\\Models\\User',25,'flutter-mobile-app','94a3264e4534429b3d2f398218d3911cb3914009ac744bd2ea247bc0a9166054','[\"*\"]','2026-03-26 14:40:52',NULL,'2026-03-26 14:38:28','2026-03-26 14:40:52'),
(19,'App\\Models\\User',24,'flutter-mobile-app','47bb8932035c4c18ac32139a325a1559930e5e5192359cf7289bd3e42df01266','[\"*\"]','2026-03-26 14:56:23',NULL,'2026-03-26 14:56:04','2026-03-26 14:56:23'),
(20,'App\\Models\\User',26,'flutter-mobile-app','84360fae0a371e9269df771084a5054756a3aa67b8be9851118580ed9468c3df','[\"*\"]','2026-03-27 00:55:17',NULL,'2026-03-26 15:11:24','2026-03-27 00:55:17'),
(21,'App\\Models\\User',26,'flutter-mobile-app','52b31e8d381a48dbce11aa5cde75c67e08b408faecf21d168dfbc326da02b3a5','[\"*\"]','2026-03-27 01:04:31',NULL,'2026-03-27 00:55:45','2026-03-27 01:04:31'),
(22,'App\\Models\\User',24,'flutter-mobile-app','60fe7202dca4a78c0efdaf69a47517c700fce23923cd74d60bd2f77bea51ad12','[\"*\"]','2026-03-27 01:23:32',NULL,'2026-03-27 01:23:31','2026-03-27 01:23:32'),
(23,'App\\Models\\User',24,'flutter-mobile-app','b8e538f79bce51494c448e5d9d0babb142a2da97bbb58a1a259eee367533fdd6','[\"*\"]','2026-03-27 01:33:37',NULL,'2026-03-27 01:33:35','2026-03-27 01:33:37'),
(24,'App\\Models\\User',24,'flutter-mobile-app','d5d561596fa3fcf629cab14b908f4a12ac0bcc5458621794d756c868de896993','[\"*\"]','2026-03-27 01:37:02',NULL,'2026-03-27 01:37:00','2026-03-27 01:37:02'),
(25,'App\\Models\\User',24,'flutter-mobile-app','5dd414e5ae4227798f33343af91f316731a47e93d67b8b837c1772197f8ed5da','[\"*\"]','2026-03-27 01:38:40',NULL,'2026-03-27 01:38:38','2026-03-27 01:38:40'),
(26,'App\\Models\\User',24,'flutter-mobile-app','fafc7e94d2e03cf542d5a656779d57c52c40aa83030182db61ea9d5a1a7cdbc8','[\"*\"]','2026-03-27 01:47:46',NULL,'2026-03-27 01:47:43','2026-03-27 01:47:46'),
(27,'App\\Models\\User',24,'flutter-mobile-app','3fee1fe1629a9f0920ad055b0f6833c1324fafa792a1ee0d5ab5784f00d1e65e','[\"*\"]','2026-03-27 01:51:53',NULL,'2026-03-27 01:51:27','2026-03-27 01:51:53'),
(28,'App\\Models\\User',24,'flutter-mobile-app','33ce817ef4fb556556fbe3346e5a2fbc7694058bf48409f9b5d8a8290288eb86','[\"*\"]',NULL,NULL,'2026-03-27 01:57:05','2026-03-27 01:57:05'),
(29,'App\\Models\\User',24,'flutter-mobile-app','5a35e6a01d60e9f9829069ff27e7b9f4aaf821abaafd9014afc1c23ac7b23393','[\"*\"]','2026-03-27 02:07:22',NULL,'2026-03-27 02:07:20','2026-03-27 02:07:22'),
(30,'App\\Models\\User',24,'flutter-mobile-app','d07057309f1b8ac01b1d8e945f9775803329096afcc2e35cd77e384e9e414cfe','[\"*\"]','2026-03-27 02:23:48',NULL,'2026-03-27 02:23:47','2026-03-27 02:23:48'),
(31,'App\\Models\\User',24,'flutter-mobile-app','893cef178f0d179483f6e774b4ca33dcca9cac22a1024578812246f5cd9d4a19','[\"*\"]','2026-03-27 02:37:26',NULL,'2026-03-27 02:37:25','2026-03-27 02:37:26'),
(32,'App\\Models\\User',24,'flutter-mobile-app','68184cb869b6035eb73e4d51e79566eed035d1fc20f8f702d2fdb0d9cfcaf9d0','[\"*\"]','2026-03-27 02:39:10',NULL,'2026-03-27 02:39:08','2026-03-27 02:39:10'),
(33,'App\\Models\\User',24,'flutter-mobile-app','a07e06e5f91a05e2ae8e57f3f83f9e046582b839a7d5698cecab9f8949d91845','[\"*\"]','2026-03-27 03:19:38',NULL,'2026-03-27 03:08:33','2026-03-27 03:19:38'),
(34,'App\\Models\\User',24,'flutter-mobile-app','ad2b9b2c04dea0a6c3edd45e547a9438e9be6d7a2c40196099e49c4ae0bc96b7','[\"*\"]','2026-03-27 03:25:39',NULL,'2026-03-27 03:19:54','2026-03-27 03:25:39'),
(35,'App\\Models\\User',24,'flutter-mobile-app','a0d76461a6c202510b71de1121c3bb28c7b3166587ac1f3f1776543eb74606b5','[\"*\"]','2026-03-27 03:26:56',NULL,'2026-03-27 03:26:42','2026-03-27 03:26:56'),
(36,'App\\Models\\User',24,'flutter-mobile-app','ff2e64f5ea1d1d53bcf04855468a8bc5e0fe8b1cfb9028eae39dfcf61553cd9a','[\"*\"]','2026-03-27 03:45:53',NULL,'2026-03-27 03:43:36','2026-03-27 03:45:53'),
(37,'App\\Models\\User',24,'flutter-mobile-app','6557474ecea8bd03270c07594d9a5058ef13dc450eba8aaaac98c4e4558a2567','[\"*\"]','2026-03-27 03:54:43',NULL,'2026-03-27 03:54:36','2026-03-27 03:54:43'),
(38,'App\\Models\\User',24,'flutter-mobile-app','c2dc50f9defcd6cf2f64842ffbd6f4f54078c070c93b01530aa19dd53aa7e05c','[\"*\"]','2026-03-27 03:59:51',NULL,'2026-03-27 03:58:17','2026-03-27 03:59:51'),
(39,'App\\Models\\User',24,'flutter-mobile-app','4d9843b08750c03e30f9810414457d44fedb7d7347c321c9f41e254cee1d823a','[\"*\"]','2026-03-27 04:13:31',NULL,'2026-03-27 04:09:31','2026-03-27 04:13:31'),
(40,'App\\Models\\User',24,'flutter-mobile-app','acb4153b3780052ebde8dac3ee834200bce34597eb24b8f1d61665d7bc4d8820','[\"*\"]','2026-03-27 04:30:40',NULL,'2026-03-27 04:29:00','2026-03-27 04:30:40'),
(41,'App\\Models\\User',24,'flutter-mobile-app','8201a3c67faf6efbe0a8538ac638037b3176899570bc1dda297bf62944c0b06a','[\"*\"]','2026-03-27 15:17:21',NULL,'2026-03-27 15:03:46','2026-03-27 15:17:21'),
(42,'App\\Models\\User',27,'flutter-mobile-app','f80c42677ed4a56d367d7d44d6b44b6fc738ea84ebc6c198e1dd8544e1764bef','[\"*\"]','2026-03-27 15:29:06',NULL,'2026-03-27 15:29:04','2026-03-27 15:29:06'),
(43,'App\\Models\\User',27,'flutter-mobile-app','e96ab928a6ffa118962b05157c6c705b1734d1980e640e40456de056f1968fc7','[\"*\"]','2026-03-27 15:54:04',NULL,'2026-03-27 15:31:41','2026-03-27 15:54:04'),
(44,'App\\Models\\User',27,'flutter-mobile-app','9016efedc313e486f18e195a134ea94fb3df64d12dbe6847bac078ce7383c3d1','[\"*\"]','2026-03-28 06:29:58',NULL,'2026-03-28 06:29:39','2026-03-28 06:29:58'),
(45,'App\\Models\\User',24,'flutter-mobile-app','4b7125fb56c84ac303f57f69d17830c4a03a6e715ca0a8edbeb7eb23a2c9cdf3','[\"*\"]','2026-03-28 06:41:54',NULL,'2026-03-28 06:30:24','2026-03-28 06:41:54'),
(46,'App\\Models\\User',24,'flutter-mobile-app','d54f1142d0c3734c8aafce409349c7bfaeed3e8a5c94a3bf355026d05b98f481','[\"*\"]','2026-03-28 07:50:43',NULL,'2026-03-28 07:25:07','2026-03-28 07:50:43'),
(47,'App\\Models\\User',24,'flutter-mobile-app','970fcc7cf89bd033e8658c53b814b26b1200c7b281c87ef7b53a4192a0398e92','[\"*\"]','2026-03-28 07:28:25',NULL,'2026-03-28 07:28:17','2026-03-28 07:28:25'),
(48,'App\\Models\\User',24,'flutter-mobile-app','773e15637e72e46ba8d539eb65c4fdff120c4ebfd06a367430b5a37a57fffad2','[\"*\"]','2026-03-28 07:49:32',NULL,'2026-03-28 07:49:26','2026-03-28 07:49:32'),
(49,'App\\Models\\User',24,'flutter-mobile-app','1d81fcfedffbc440f8e6d701cb487459e2187f6ea80e2d425e3f99e0fc96c357','[\"*\"]','2026-03-28 07:51:18',NULL,'2026-03-28 07:51:13','2026-03-28 07:51:18'),
(50,'App\\Models\\User',24,'flutter-mobile-app','a673ce7667bc3f1a8b1e0ecb718f11421282651fd53376ea738d47389e193959','[\"*\"]','2026-03-28 07:56:10',NULL,'2026-03-28 07:54:57','2026-03-28 07:56:10'),
(51,'App\\Models\\User',24,'flutter-mobile-app','e583a6027edbfa83a49610625e36f0d378b74795d5e19e63b7386065225d7f7d','[\"*\"]','2026-03-28 08:00:34',NULL,'2026-03-28 08:00:19','2026-03-28 08:00:34'),
(52,'App\\Models\\User',24,'flutter-mobile-app','669331ca85b906f759df310a853bff7ee582119bed58bceb8026ad516fc4fd0e','[\"*\"]','2026-03-28 09:37:06',NULL,'2026-03-28 09:30:51','2026-03-28 09:37:06'),
(53,'App\\Models\\User',24,'flutter-mobile-app','4aa5e8ba9324d5bc20d83ae88e4bb081cc801b3472fdc3524df11fb8132e0891','[\"*\"]','2026-03-28 09:38:50',NULL,'2026-03-28 09:38:22','2026-03-28 09:38:50'),
(54,'App\\Models\\User',27,'flutter-mobile-app','e185b364a0ce91a3233f74cf17e1c86f10d2b6f2e450465891f79173eba92c45','[\"*\"]','2026-03-28 09:42:01',NULL,'2026-03-28 09:41:31','2026-03-28 09:42:01'),
(55,'App\\Models\\User',24,'flutter-mobile-app','7e9723b578c246d76e4e0a43246d0f95ec0fd2a32192c8471ddd72253010d954','[\"*\"]','2026-03-28 09:47:55',NULL,'2026-03-28 09:43:10','2026-03-28 09:47:55'),
(56,'App\\Models\\User',24,'flutter-mobile-app','b2272609a0a40b51b9756da2bbeef14988d940ad1dbc4da9908f6fb4b8a89c4e','[\"*\"]','2026-03-30 02:59:49',NULL,'2026-03-30 02:44:57','2026-03-30 02:59:49'),
(57,'App\\Models\\User',24,'flutter-mobile-app','c821b1b934511c198e67ac541ed82d0f997e61204118c12cf0e8909be0dff64f','[\"*\"]','2026-03-30 03:04:37',NULL,'2026-03-30 03:04:35','2026-03-30 03:04:37'),
(58,'App\\Models\\User',24,'flutter-mobile-app','2dc547da16c537f4de95333b69e410c831acf63f2b905e8f4fbabbee8b684531','[\"*\"]','2026-03-30 03:05:55',NULL,'2026-03-30 03:05:53','2026-03-30 03:05:55'),
(59,'App\\Models\\User',24,'flutter-mobile-app','f4091be58a9d0f6bd341d8b8d5645d497327bf9edbca997f135aa3cea39fa228','[\"*\"]','2026-03-30 03:12:42',NULL,'2026-03-30 03:11:28','2026-03-30 03:12:42'),
(60,'App\\Models\\User',24,'flutter-mobile-app','d9047ad65aa8bca8389b8fc94209c065645a1c7973ad00094daebe3d2370da95','[\"*\"]','2026-03-30 03:20:19',NULL,'2026-03-30 03:20:17','2026-03-30 03:20:19'),
(61,'App\\Models\\User',24,'flutter-mobile-app','a332eaa789cbfa8e3f8f1d710d9a72a40b7ead730fc52585d12b849d8910f756','[\"*\"]','2026-03-30 03:43:12',NULL,'2026-03-30 03:30:51','2026-03-30 03:43:12'),
(62,'App\\Models\\User',24,'flutter-mobile-app','0cfee484abed79ff3b05412e387d7b52f62d0a066ed14013bf701ea6489bfd55','[\"*\"]','2026-03-30 04:01:03',NULL,'2026-03-30 04:00:45','2026-03-30 04:01:03'),
(63,'App\\Models\\User',24,'flutter-mobile-app','8663ac3f84a4c7e8ebdeb2ad8725aa16833253281f66e2198456dddda8a5a403','[\"*\"]','2026-03-30 04:39:32',NULL,'2026-03-30 04:02:23','2026-03-30 04:39:32'),
(64,'App\\Models\\User',24,'flutter-mobile-app','11d60719095615c08f57ec17e7ab80fa7bc1d13b56f68fe9c18e8cc5e8633d60','[\"*\"]','2026-03-30 04:44:18',NULL,'2026-03-30 04:41:59','2026-03-30 04:44:18'),
(65,'App\\Models\\User',24,'flutter-mobile-app','c761f31bcb2138a06173dcc751fb78c65e416ef7d26420706ea1d1b5b3537038','[\"*\"]','2026-03-30 04:51:16',NULL,'2026-03-30 04:50:31','2026-03-30 04:51:16'),
(66,'App\\Models\\User',24,'flutter-mobile-app','83bad2accfb6725683d481c17564f5eb8331705a2a5873a4df4fc764a8582ac5','[\"*\"]','2026-03-30 04:55:23',NULL,'2026-03-30 04:55:05','2026-03-30 04:55:23'),
(67,'App\\Models\\User',24,'flutter-mobile-app','eef13ad563450a9956ddb8aed06e6b55e27fae8aec195ce71626ebdfd9956e76','[\"*\"]','2026-03-30 05:11:17',NULL,'2026-03-30 05:00:20','2026-03-30 05:11:17'),
(68,'App\\Models\\User',24,'flutter-mobile-app','a74bdcec2cc24180ddc7a46497edb8a4001a6d507514a2a855c6c227fd64ed42','[\"*\"]','2026-03-30 05:22:53',NULL,'2026-03-30 05:20:58','2026-03-30 05:22:53'),
(69,'App\\Models\\User',24,'flutter-mobile-app','7343028617bcc5907529184246be2b341d27451b8ee806ec223c1541e6e90d1c','[\"*\"]','2026-03-30 05:23:50',NULL,'2026-03-30 05:23:16','2026-03-30 05:23:50'),
(70,'App\\Models\\User',24,'flutter-mobile-app','a8c90d9e57d05f8a81ed5d33e4389d3bdc63534ffbfe34d38a378fb52abddec0','[\"*\"]','2026-03-30 05:24:52',NULL,'2026-03-30 05:24:38','2026-03-30 05:24:52'),
(71,'App\\Models\\User',24,'flutter-mobile-app','b92666554ec5498f2fea2601b2ded42408c60b0ec789d23fcff2ca364baf1711','[\"*\"]','2026-03-30 05:25:37',NULL,'2026-03-30 05:25:15','2026-03-30 05:25:37'),
(72,'App\\Models\\User',24,'flutter-mobile-app','d6fd20b7ae9c63218f1bb5e558c7dd92227625f68f92e8e0460959a9a91744b2','[\"*\"]','2026-03-30 05:34:19',NULL,'2026-03-30 05:25:52','2026-03-30 05:34:19'),
(73,'App\\Models\\User',24,'flutter-mobile-app','8d8089a4accacfd98c2931c1c1a314c346a9d85ddbe27fb1b9c114263d11671f','[\"*\"]','2026-03-30 05:40:24',NULL,'2026-03-30 05:39:15','2026-03-30 05:40:24'),
(74,'App\\Models\\User',24,'flutter-mobile-app','d4b51e21f4c6f7c87fc4a67d889d49a00b38f365b57f2edaa1b1348b238846be','[\"*\"]','2026-03-30 05:47:18',NULL,'2026-03-30 05:41:57','2026-03-30 05:47:18'),
(75,'App\\Models\\User',24,'flutter-mobile-app','76516cc965c15d5bc616a291c7e61739f9f9eaa3a5acc3098006c729c0bb70d5','[\"*\"]','2026-03-30 05:50:31',NULL,'2026-03-30 05:47:51','2026-03-30 05:50:31'),
(76,'App\\Models\\User',24,'flutter-mobile-app','d7b74ad2992d14a7542ba3f17d8790798eb897aeabcecd2aa846c1b39863ec25','[\"*\"]','2026-03-30 12:06:50',NULL,'2026-03-30 12:06:40','2026-03-30 12:06:50'),
(77,'App\\Models\\User',32,'flutter-mobile-app','9f5346af29fc815577e84711eafcf9512ee430188a3ca3cf253ab5cd811bde91','[\"*\"]','2026-03-30 14:46:46',NULL,'2026-03-30 14:44:13','2026-03-30 14:46:46'),
(78,'App\\Models\\User',32,'flutter-mobile-app','fa599c256fd812889e806759fb08bdff24be2d3819a60561eee7625a9ec4a729','[\"*\"]','2026-03-30 14:51:53',NULL,'2026-03-30 14:51:50','2026-03-30 14:51:53'),
(79,'App\\Models\\User',32,'flutter-mobile-app','7292da335a5eaf43cfecd78714dcece2eb52093a841f7eb99983e5e80b216512','[\"*\"]','2026-03-30 14:57:54',NULL,'2026-03-30 14:57:54','2026-03-30 14:57:54'),
(80,'App\\Models\\User',24,'flutter-mobile-app','9b276e63d1e55cf8d9f217c923a43bb23ff51e85ead638ad1c948c6b67dfc6de','[\"*\"]','2026-03-30 15:01:15',NULL,'2026-03-30 14:59:56','2026-03-30 15:01:15'),
(81,'App\\Models\\User',24,'flutter-mobile-app','a2090c276dbec72cf7bb5191040fddf15f66763480aa3e8899788a2353f87e25','[\"*\"]','2026-03-30 15:04:47',NULL,'2026-03-30 15:01:33','2026-03-30 15:04:47'),
(82,'App\\Models\\User',32,'flutter-mobile-app','f4761fc2311f0786435aa659f9c5f09a9924619eac0b0160873fc1575c03c513','[\"*\"]','2026-03-30 15:05:51',NULL,'2026-03-30 15:05:50','2026-03-30 15:05:51'),
(83,'App\\Models\\User',24,'flutter-mobile-app','8a2822c937b085b5fca60359ddade7ff85595a67d460e6fbee266a87e5fc3536','[\"*\"]','2026-03-30 15:30:29',NULL,'2026-03-30 15:26:19','2026-03-30 15:30:29'),
(84,'App\\Models\\User',32,'flutter-mobile-app','de7aa69805a08e12060c6866b41ef295a983b816939ab992ea2ed4053d55f7de','[\"*\"]','2026-03-30 15:31:45',NULL,'2026-03-30 15:31:44','2026-03-30 15:31:45'),
(85,'App\\Models\\User',32,'flutter-mobile-app','8ae30bb91b58ea120ee30ea95ff1fdb96529bd3df5f86afe8629ce8d922a1c51','[\"*\"]','2026-03-31 04:20:01',NULL,'2026-03-31 04:19:58','2026-03-31 04:20:01'),
(86,'App\\Models\\User',32,'flutter-mobile-app','5ecb2b7c2f6645e2cc0a3f3dc09d2314ae4f4a23ac46868f1df5fe7b724787f8','[\"*\"]','2026-03-31 04:30:51',NULL,'2026-03-31 04:30:49','2026-03-31 04:30:51'),
(87,'App\\Models\\User',32,'flutter-mobile-app','a8b403ca32bc652e73c6d64194eb68a33fc07a340e225dd298d181585a3e080e','[\"*\"]','2026-03-31 05:20:39',NULL,'2026-03-31 05:20:37','2026-03-31 05:20:39'),
(88,'App\\Models\\User',32,'flutter-mobile-app','5da5e62bd231b18d62a393bb633f6aeb220f4d61b4f81a5cec8b4b304969e847','[\"*\"]','2026-03-31 05:36:52',NULL,'2026-03-31 05:36:23','2026-03-31 05:36:52'),
(89,'App\\Models\\User',32,'face-login','bd3b5b34568134924718911d3b1b188eb393478e7da902c2a451f4396a7562f3','[\"*\"]','2026-03-31 05:37:57',NULL,'2026-03-31 05:37:20','2026-03-31 05:37:57'),
(90,'App\\Models\\User',32,'face-login','969af8943a1a02e5532cf9f8341056df74a1d0e13d1ab405060554e6bca7eb84','[\"*\"]','2026-03-31 05:39:00',NULL,'2026-03-31 05:38:51','2026-03-31 05:39:00'),
(91,'App\\Models\\User',32,'face-login','b6a6f5a2f6badfc598575ca183c413188af0a3a1dd256147c56ec3b670339026','[\"*\"]','2026-03-31 05:39:48',NULL,'2026-03-31 05:39:47','2026-03-31 05:39:48'),
(92,'App\\Models\\User',32,'flutter-mobile-app','35282e08e49f9c90a94f602bbc54f1ba9372f85226e08bacca76abce7cc6f44d','[\"*\"]','2026-03-31 05:41:30',NULL,'2026-03-31 05:41:23','2026-03-31 05:41:30'),
(93,'App\\Models\\User',32,'face-login','95d95f9306c5c89d30177d57b82a7d6d809a890d001666dadc10e499666853db','[\"*\"]','2026-03-31 05:42:04',NULL,'2026-03-31 05:42:02','2026-03-31 05:42:04'),
(94,'App\\Models\\User',32,'face-login','e0b83c0ecdaac272751f57c0436ed399f9177768479cb146df0f75865be947bb','[\"*\"]','2026-03-31 13:33:44',NULL,'2026-03-31 13:33:37','2026-03-31 13:33:44'),
(95,'App\\Models\\User',32,'face-login','5211c4fe71219a8b9b1a19f2f4ca4e96ee8e9b51b0709284264ef26eadedfe85','[\"*\"]','2026-03-31 13:34:04',NULL,'2026-03-31 13:34:00','2026-03-31 13:34:04'),
(96,'App\\Models\\User',32,'face-login','2a85f99499a656a9f42f19282f4b1b6e068cec2200680f47c37daad7b30e59a4','[\"*\"]','2026-03-31 13:34:32',NULL,'2026-03-31 13:34:29','2026-03-31 13:34:32'),
(97,'App\\Models\\User',24,'flutter-mobile-app','73bf912910af2d2f53129fecdbf52467c6dca75f8554646d48dc9c3467dfa128','[\"*\"]','2026-03-31 13:36:02',NULL,'2026-03-31 13:36:00','2026-03-31 13:36:02'),
(98,'App\\Models\\User',32,'face-login','c355a11c98f293b885ec803e045bf10923485b6c48b5058bca11811f662b04e4','[\"*\"]','2026-03-31 13:51:37',NULL,'2026-03-31 13:51:35','2026-03-31 13:51:37'),
(99,'App\\Models\\User',32,'face-login','56fa2dddbe3786f7f46240ecb6332a231406b2b06c683cdfd8f74359bbf1fda9','[\"*\"]','2026-03-31 14:36:06',NULL,'2026-03-31 14:13:25','2026-03-31 14:36:06'),
(100,'App\\Models\\User',24,'flutter-mobile-app','223599cdf95eaafd074494a3d7293a4e70cb54aa94a2ce1b012af988636782e8','[\"*\"]','2026-03-31 14:38:54',NULL,'2026-03-31 14:38:03','2026-03-31 14:38:54'),
(101,'App\\Models\\User',32,'face-login','7a6d04251ff6991b3d64686853d0fc372d616b125d0cfd1d3f217f73be959202','[\"*\"]','2026-03-31 14:39:14',NULL,'2026-03-31 14:39:13','2026-03-31 14:39:14'),
(102,'App\\Models\\User',33,'flutter-mobile-app','7caf69be7a847aaaa7ea0addddaca780a576796b1f931a7d44b5adf2e224cd78','[\"*\"]','2026-03-31 14:40:40',NULL,'2026-03-31 14:40:27','2026-03-31 14:40:40'),
(103,'App\\Models\\User',33,'flutter-mobile-app','7e558f87d676310b8e9c925b6dd166118643f7dab767cae756d7881286010703','[\"*\"]','2026-03-31 14:43:02',NULL,'2026-03-31 14:43:01','2026-03-31 14:43:02'),
(104,'App\\Models\\User',33,'flutter-mobile-app','4102636e314d94fe8362f2ffab17460744be6b9c70eae882853954bfda69c6be','[\"*\"]','2026-03-31 14:56:02',NULL,'2026-03-31 14:55:38','2026-03-31 14:56:02'),
(105,'App\\Models\\User',33,'face-login','0912ab69b69928830727688849b84cdcdb13c72dff436c37f5cd2b791543865a','[\"*\"]','2026-03-31 14:57:06',NULL,'2026-03-31 14:57:06','2026-03-31 14:57:06'),
(106,'App\\Models\\User',33,'face-login','1ea05449f8c7984fe0fa2cc338c741f5ce6cc4b99f1cab8b9f2c4c8fdb69320b','[\"*\"]','2026-03-31 14:57:53',NULL,'2026-03-31 14:57:52','2026-03-31 14:57:53'),
(107,'App\\Models\\User',33,'face-login','d9b4ab5082897e906d716ff784fd8fd1e90e3d12986b38ef2ad723cc557e8c1d','[\"*\"]','2026-03-31 15:10:09',NULL,'2026-03-31 15:09:26','2026-03-31 15:10:09'),
(108,'App\\Models\\User',33,'face-login','a4d816ff76d9d2cd8a716d066a2e020e4ae6c02be1f76661840e89b5791ea879','[\"*\"]','2026-03-31 15:18:14',NULL,'2026-03-31 15:17:58','2026-03-31 15:18:14'),
(109,'App\\Models\\User',24,'flutter-mobile-app','5b68bf2573835c9020c67693d68ce70cffb4374b198d1c3525ca316027bc9be9','[\"*\"]','2026-04-10 15:24:56',NULL,'2026-04-10 15:22:09','2026-04-10 15:24:56'),
(110,'App\\Models\\User',24,'flutter-mobile-app','dcd83dea4de464f87b366c7f874f8d9cde4a6a0eb4a7bb51b86e430aba37ceb3','[\"*\"]','2026-04-10 15:29:25',NULL,'2026-04-10 15:26:28','2026-04-10 15:29:25'),
(111,'App\\Models\\User',24,'flutter-mobile-app','f1e8b7de0db0e540d5c278e31d428dd028d4278d19cadb217c6c56774d05483e','[\"*\"]','2026-04-10 15:34:38',NULL,'2026-04-10 15:29:47','2026-04-10 15:34:38'),
(112,'App\\Models\\User',40,'flutter-mobile-app','c4bfaf51d4cd95d74cb8b11faa75dcda9403325361710960c3a036b6c6d8927f','[\"*\"]','2026-04-10 15:43:33',NULL,'2026-04-10 15:43:06','2026-04-10 15:43:33'),
(113,'App\\Models\\User',40,'face-login','3f91ec131e9fc9f11ffaf035a49e46cdd12874560ddc924d2e7b3cc04f6a4bc2','[\"*\"]','2026-04-10 15:44:12',NULL,'2026-04-10 15:44:12','2026-04-10 15:44:12'),
(114,'App\\Models\\User',24,'flutter-mobile-app','fbf03d5616c73937039a212d2c289c82c2e347909d3e7cc5b38a21696ac7649a','[\"*\"]','2026-04-10 15:58:25',NULL,'2026-04-10 15:45:19','2026-04-10 15:58:25'),
(115,'App\\Models\\User',24,'flutter-mobile-app','f6a9c630c604bfe12e39e71584e523670e42c11c5197f52fff7eff3370818e04','[\"*\"]','2026-04-10 15:59:18',NULL,'2026-04-10 15:58:46','2026-04-10 15:59:18'),
(116,'App\\Models\\User',24,'flutter-mobile-app','364d00eebc80e002839cfcc755e420a6062a10090b535debfdc009e55e121b0c','[\"*\"]','2026-04-10 16:03:54',NULL,'2026-04-10 16:01:40','2026-04-10 16:03:54'),
(117,'App\\Models\\User',40,'flutter-mobile-app','8bef0b2665a32e9e53ea9eb2470ba0b18c107bb4ab253b39b07ffb82ad18e5ac','[\"*\"]','2026-04-12 14:11:05',NULL,'2026-04-12 14:10:53','2026-04-12 14:11:05'),
(118,'App\\Models\\User',40,'flutter-mobile-app','bc5a7df6f9a9282ae39675a4804a144fb109be79fc63e5fd1d5086f025e15298','[\"*\"]','2026-04-12 14:33:15',NULL,'2026-04-12 14:15:16','2026-04-12 14:33:15'),
(119,'App\\Models\\User',40,'flutter-mobile-app','420f865c964ccd40a304708b51b124d1e311d0ab83e40f53e28f5f9b43ef692a','[\"*\"]','2026-04-12 14:33:50',NULL,'2026-04-12 14:33:39','2026-04-12 14:33:50'),
(120,'App\\Models\\User',40,'flutter-mobile-app','fda0b7e4cd4c3665d2483b12dce236f4584ee4510ac31a741bcee6c8cc6e7db8','[\"*\"]','2026-04-12 14:39:54',NULL,'2026-04-12 14:39:42','2026-04-12 14:39:54'),
(121,'App\\Models\\User',40,'face-login','94a67b1ab34d50b9b205734f8d71a9d959850ff75a1609d290384b749fd9324c','[\"*\"]','2026-04-12 14:40:15',NULL,'2026-04-12 14:40:14','2026-04-12 14:40:15'),
(122,'App\\Models\\User',40,'face-login','6c98639d3aa3b11f3f6beb06998306270528b1f655b6d1e6ecfe363e4ae8600d','[\"*\"]','2026-04-12 14:41:22',NULL,'2026-04-12 14:41:21','2026-04-12 14:41:22'),
(123,'App\\Models\\User',40,'face-login','f95223a556d1174d3d682ad495973d7502a2d33ba823747e625529bc20aee246','[\"*\"]','2026-04-12 14:53:24',NULL,'2026-04-12 14:52:40','2026-04-12 14:53:24'),
(124,'App\\Models\\User',40,'face-login','1383a6980fd14c60303f62a41eb584fc05b29be571bf6b76622063db82aa1334','[\"*\"]','2026-04-12 14:54:22',NULL,'2026-04-12 14:53:50','2026-04-12 14:54:22'),
(125,'App\\Models\\User',40,'face-login','00165091ae0c79eeee7754394cfeba9320532ff77d722339ecb78353e734dce9','[\"*\"]','2026-04-12 15:10:29',NULL,'2026-04-12 15:03:51','2026-04-12 15:10:29'),
(126,'App\\Models\\User',40,'flutter-mobile-app','5bdc3b760a50932b64cdf9a4d4c6db1bf719cc4601b6c499bb5f0db5809eeca3','[\"*\"]','2026-04-12 15:18:45',NULL,'2026-04-12 15:18:37','2026-04-12 15:18:45'),
(127,'App\\Models\\User',40,'face-login','0cac637ded605e1447b9a2377d9819b4be2826ee4a3e987424efe920da142ef2','[\"*\"]','2026-04-12 15:23:35',NULL,'2026-04-12 15:23:34','2026-04-12 15:23:35'),
(128,'App\\Models\\User',33,'flutter-mobile-app','223554e94eaa90c4f1d17c0c46318d51de66d1c3fd5164bffb31d8a6394f85b6','[\"*\"]','2026-04-12 15:26:25',NULL,'2026-04-12 15:26:12','2026-04-12 15:26:25'),
(129,'App\\Models\\User',33,'face-login','d3e37cddfd860e9365fd4ebf96570991589c9adea37b1e1b9a37f6d42989c923','[\"*\"]','2026-04-12 15:26:59',NULL,'2026-04-12 15:26:58','2026-04-12 15:26:59'),
(130,'App\\Models\\User',33,'face-login','a2fa91cf84d6881049891537065bf7bc749864be181470e23e375eb7c803a305','[\"*\"]','2026-04-12 15:27:48',NULL,'2026-04-12 15:27:47','2026-04-12 15:27:48'),
(131,'App\\Models\\User',33,'face-login','510e379ba5d26f3d45be2b4b17e43bf8662819e318085478a95db3d3640d408e','[\"*\"]','2026-04-12 15:28:40',NULL,'2026-04-12 15:28:25','2026-04-12 15:28:40'),
(132,'App\\Models\\User',24,'flutter-mobile-app','f736cc178f372b5fa561bbdf36ded439d4b4580aa5f064f0588bca98d43901a5','[\"*\"]','2026-04-12 15:37:26',NULL,'2026-04-12 15:29:39','2026-04-12 15:37:26'),
(133,'App\\Models\\User',33,'face-login','0a14c345f43a278a8f19d60d3b5c74989ec03d0ff7fbce92c728207e68dbaf6a','[\"*\"]','2026-04-12 15:38:44',NULL,'2026-04-12 15:38:14','2026-04-12 15:38:44'),
(134,'App\\Models\\User',33,'face-login','9916e39c0ec344a44fd8f988e4f039c1f2b7fdc8457e7828ddc30d39758c4a10','[\"*\"]','2026-04-12 15:39:46',NULL,'2026-04-12 15:39:11','2026-04-12 15:39:46'),
(135,'App\\Models\\User',33,'face-login','71b587567720ad27e6e7e4c0880dd90f4ffd5e384f1476bbcd9516529ef0ba16','[\"*\"]','2026-04-12 15:44:40',NULL,'2026-04-12 15:44:24','2026-04-12 15:44:40'),
(136,'App\\Models\\User',33,'face-login','024b316cbff0aac8467d2db2c0fb1e249f4862deccac1cfd59c91e7b6a6b61b9','[\"*\"]','2026-04-12 15:45:50',NULL,'2026-04-12 15:45:48','2026-04-12 15:45:50'),
(137,'App\\Models\\User',33,'flutter-mobile-app','48b4150cc87820b71ee41bcb64c632b0e41657b594b6dc9f72968db1fddd9f4b','[\"*\"]','2026-04-13 02:19:48',NULL,'2026-04-13 02:14:33','2026-04-13 02:19:48'),
(138,'App\\Models\\User',33,'flutter-mobile-app','f844dade23d8c4421ff91894e3217266afbbad6f4134e4884703222c0a8502db','[\"*\"]','2026-04-13 02:20:16',NULL,'2026-04-13 02:20:14','2026-04-13 02:20:16'),
(139,'App\\Models\\User',24,'flutter-mobile-app','cb2b549f74cff91a575f7a6da80f61483fdf708555bcd0bb09010f42063edd49','[\"*\"]','2026-04-13 02:22:36',NULL,'2026-04-13 02:20:30','2026-04-13 02:22:36'),
(140,'App\\Models\\User',24,'flutter-mobile-app','e9c7582d6bde15ac8c8e599d3e4824f22ccbbf5f5e48b4f0a68390990f300ec3','[\"*\"]','2026-04-13 02:27:25',NULL,'2026-04-13 02:26:26','2026-04-13 02:27:25'),
(141,'App\\Models\\User',33,'flutter-mobile-app','272a32230392e23a94f45926ab65dceb14ed451cdfde22ee2b95c3c2504426ae','[\"*\"]','2026-04-13 02:30:45',NULL,'2026-04-13 02:28:51','2026-04-13 02:30:45'),
(142,'App\\Models\\User',33,'face-login','a01d4a168ffb795e9d528cede35d0800e862fa71b91d2bcec386ba09d4b6fdd1','[\"*\"]','2026-04-13 02:31:59',NULL,'2026-04-13 02:31:57','2026-04-13 02:31:59'),
(143,'App\\Models\\User',33,'face-login','44ecc94d0d69b87bb4fca6a4afd113731e5539066ae6741f3b0884572942d56b','[\"*\"]','2026-04-13 02:48:41',NULL,'2026-04-13 02:48:40','2026-04-13 02:48:41'),
(144,'App\\Models\\User',33,'face-login','d865b3207655f2953781d1fc605c62546564c62546039b1b93117e5e6ad92395','[\"*\"]','2026-04-13 03:26:34',NULL,'2026-04-13 03:26:33','2026-04-13 03:26:34'),
(145,'App\\Models\\User',24,'flutter-mobile-app','76811486b120bca027e16d9f176ee3bc141403e90224f59f978bdf3bdf28c3e1','[\"*\"]','2026-04-13 03:28:53',NULL,'2026-04-13 03:28:25','2026-04-13 03:28:53'),
(146,'App\\Models\\User',40,'flutter-mobile-app','08f7343b33a43a200da8126f2451509fc740257063ad1a4601765e5d8629b007','[\"*\"]','2026-04-13 03:30:36',NULL,'2026-04-13 03:30:03','2026-04-13 03:30:36'),
(147,'App\\Models\\User',40,'face-login','13f3ae404e662171326bf8d0255f99929a73d4e04ced202de626e905e923bda0','[\"*\"]','2026-04-13 03:31:10',NULL,'2026-04-13 03:31:09','2026-04-13 03:31:10'),
(148,'App\\Models\\User',24,'flutter-mobile-app','f89708ce9276ffbefdb95843cce67f1d96ea026e53b995c216df717c6cf96a2b','[\"*\"]','2026-04-13 04:01:46',NULL,'2026-04-13 03:32:58','2026-04-13 04:01:46'),
(149,'App\\Models\\User',33,'face-login','a6d2a993e25e0415b04b10d4600ff24bfd064e92e47efb1ef2ee8eeefab0dcea','[\"*\"]','2026-04-13 04:29:30',NULL,'2026-04-13 04:29:29','2026-04-13 04:29:30'),
(150,'App\\Models\\User',24,'flutter-mobile-app','a739a51928fd44be985a66f8cf50058778750c1f5524a48d88c8920732ec5167','[\"*\"]','2026-04-13 04:35:53',NULL,'2026-04-13 04:30:18','2026-04-13 04:35:53'),
(151,'App\\Models\\User',41,'flutter-mobile-app','4575980c01e8abd9e23f16c14ad4b1f5a0c05f9ecaa876d870ff766734dd9bc2','[\"*\"]','2026-04-13 04:36:31',NULL,'2026-04-13 04:36:30','2026-04-13 04:36:31'),
(152,'App\\Models\\User',24,'flutter-mobile-app','929c5ba8b3a9b1a183e41e7c15c79f7486cf8b9e27b0b77aa8fd033a85b7ea6a','[\"*\"]','2026-04-13 04:37:07',NULL,'2026-04-13 04:36:47','2026-04-13 04:37:07'),
(153,'App\\Models\\User',33,'face-login','7e1dac37dcd7485f3ce5d3d24ca1f94b541fb4b96564ecfddd7129cc2a31401e','[\"*\"]','2026-04-13 04:39:34',NULL,'2026-04-13 04:39:20','2026-04-13 04:39:34'),
(154,'App\\Models\\User',24,'flutter-mobile-app','717dec3338397df57dde402d760ac8e8c3256312f6373dd8be8746b268b02e88','[\"*\"]','2026-04-13 04:40:21',NULL,'2026-04-13 04:39:54','2026-04-13 04:40:21'),
(155,'App\\Models\\User',24,'flutter-mobile-app','fe67ddf33668d49558f75b1fa23867cb303db096d279bcefa31d90d1a8eebaf6','[\"*\"]','2026-04-13 12:59:39',NULL,'2026-04-13 04:48:56','2026-04-13 12:59:39'),
(156,'App\\Models\\User',33,'face-login','2ec3bdd08abcc333bee05a52d24050290ebd0614041b044b5118d5981dd7df39','[\"*\"]','2026-04-13 13:02:28',NULL,'2026-04-13 13:02:24','2026-04-13 13:02:28'),
(157,'App\\Models\\User',24,'flutter-mobile-app','31a42276d238e42c4c26b4aec38b4f0b535fdd4828d143a8ff4eb4f3b327d1ac','[\"*\"]','2026-04-13 13:05:26',NULL,'2026-04-13 13:02:47','2026-04-13 13:05:26'),
(158,'App\\Models\\User',40,'face-login','367a531740427f5a7732cec181717d0b6efcd0bbeabb2c00b8949c4708631a55','[\"*\"]','2026-04-13 13:08:33',NULL,'2026-04-13 13:08:32','2026-04-13 13:08:33'),
(159,'App\\Models\\User',24,'flutter-mobile-app','c601aa160141c96363bc48cf3f5cbb9d478a26f846b264e9a211c33958cb1d17','[\"*\"]','2026-04-13 13:18:53',NULL,'2026-04-13 13:08:57','2026-04-13 13:18:53'),
(160,'App\\Models\\User',40,'flutter-mobile-app','f0dbdd8e778c478870c8a3256ba63077afe681d1812fc9314f6767e7eac9c82f','[\"*\"]','2026-04-13 13:21:13',NULL,'2026-04-13 13:21:12','2026-04-13 13:21:13'),
(161,'App\\Models\\User',24,'flutter-mobile-app','02245c6bb0adb750eb08802a4fd2524bc19fbefab3ba7da056c5b4ce33a0b716','[\"*\"]','2026-04-13 13:22:31',NULL,'2026-04-13 13:21:42','2026-04-13 13:22:31'),
(162,'App\\Models\\User',40,'face-login','d799928c69c6bae85ff46e736cb23a4e0cdd701a8f1b9d99e132a78aecc83ec2','[\"*\"]','2026-04-13 13:27:20',NULL,'2026-04-13 13:27:19','2026-04-13 13:27:20'),
(163,'App\\Models\\User',24,'flutter-mobile-app','44722a4f3986dfb519758c1120de1f9c6dac20b79c67eaf475c040eb4265abf7','[\"*\"]','2026-04-13 13:28:39',NULL,'2026-04-13 13:27:43','2026-04-13 13:28:39'),
(164,'App\\Models\\User',33,'face-login','5d7f82eb74af11815ffa043af38c0f02926de7c9e67101cc6fd6463a2753d368','[\"*\"]','2026-04-13 13:30:32',NULL,'2026-04-13 13:30:31','2026-04-13 13:30:32'),
(165,'App\\Models\\User',24,'flutter-mobile-app','58886db1ff5ef7dc8703d7149523c74f3c5f5cb29a72e3c677f7caa5ece824ae','[\"*\"]','2026-04-13 13:31:15',NULL,'2026-04-13 13:31:03','2026-04-13 13:31:15'),
(166,'App\\Models\\User',42,'flutter-mobile-app','f01b9edded264dc24ff931d951c66bbeff0490322f618f2d49b70d6aa8877bf6','[\"*\"]','2026-04-13 22:15:05',NULL,'2026-04-13 22:14:36','2026-04-13 22:15:05'),
(167,'App\\Models\\User',42,'face-login','51235af42bcf9de0c4d1f1fad3baa71e611c38eea2f9ee9b65b50bfe9d7ede7d','[\"*\"]','2026-04-13 22:15:23',NULL,'2026-04-13 22:15:22','2026-04-13 22:15:23'),
(168,'App\\Models\\User',24,'flutter-mobile-app','b52a58bc04c8423e5defdb6c6fc3c44cfc73b4cda83ac28241f2137022282ae1','[\"*\"]','2026-04-13 22:38:25',NULL,'2026-04-13 22:17:33','2026-04-13 22:38:25'),
(169,'App\\Models\\User',43,'flutter-mobile-app','b0ccb443a0b5aad38bddc0ac2d6fc4d0f6f61d61f3f62bf1265aa87a669e10a8','[\"*\"]','2026-04-13 22:38:59',NULL,'2026-04-13 22:38:53','2026-04-13 22:38:59'),
(170,'App\\Models\\User',33,'face-login','75399d10963dde1d48b34aff4314189dbba86e29bc3bf10e40ea00ddae4ec119','[\"*\"]','2026-04-13 22:58:20',NULL,'2026-04-13 22:58:19','2026-04-13 22:58:20'),
(171,'App\\Models\\User',24,'flutter-mobile-app','0822c95d463b24d7fd8051a3d5103b0d67c455f00ec8adf279eadb628c4abd66','[\"*\"]','2026-04-13 23:03:41',NULL,'2026-04-13 23:03:34','2026-04-13 23:03:41'),
(172,'App\\Models\\User',33,'face-login','fb089be40e26d80c99eec99010e97234e7a25eb477e6a181e4ebf0d813d176b8','[\"*\"]','2026-04-14 16:52:21',NULL,'2026-04-14 16:49:01','2026-04-14 16:52:21'),
(173,'App\\Models\\User',33,'face-login','f54d1968a0847b9d56c507a66fb61b15ab3c64df9e825af91c95ec0770faae99','[\"*\"]','2026-04-14 16:53:14',NULL,'2026-04-14 16:53:13','2026-04-14 16:53:14'),
(174,'App\\Models\\User',33,'face-login','2a1435dbc4d56e44309d76d67917a538cc042de4a069ee77997b9e55c04e47e6','[\"*\"]','2026-04-14 17:17:27',NULL,'2026-04-14 17:16:55','2026-04-14 17:17:27'),
(175,'App\\Models\\User',33,'flutter-mobile-app','fd22e4db737bc6b39806171c4a926c2384f71540258db93189724e8a5bf1b94b','[\"*\"]','2026-04-14 17:21:58',NULL,'2026-04-14 17:19:30','2026-04-14 17:21:58'),
(176,'App\\Models\\User',33,'face-login','29ca3e9f36e230c28f36ce80deabcc0fd2a64c950c051564ad7c003b29a18d56','[\"*\"]','2026-04-14 17:37:41',NULL,'2026-04-14 17:26:37','2026-04-14 17:37:41'),
(177,'App\\Models\\User',33,'flutter-mobile-app','552849f0333882e8475619e5555648dbb21c858ff94c9677bed1ad81fb043abb','[\"*\"]','2026-04-14 17:44:49',NULL,'2026-04-14 17:39:15','2026-04-14 17:44:49'),
(178,'App\\Models\\User',33,'flutter-mobile-app','cca35505e827ac05c865061f92634d2cf457b09988b1e0d6e1687384e8e42aa3','[\"*\"]','2026-04-14 17:50:23',NULL,'2026-04-14 17:49:49','2026-04-14 17:50:23'),
(179,'App\\Models\\User',24,'flutter-mobile-app','f1bf04c891dd5f863b937c588140cfc0ccc00e63539ab0c5c4db76dd82a32623','[\"*\"]','2026-04-14 21:29:47',NULL,'2026-04-14 21:28:50','2026-04-14 21:29:47'),
(180,'App\\Models\\User',33,'flutter-mobile-app','4be6de0e74f77098bb7125d5a2d0e00904cb1f67bdb49ac762da299669c41f39','[\"*\"]','2026-04-14 21:34:32',NULL,'2026-04-14 21:30:47','2026-04-14 21:34:32'),
(181,'App\\Models\\User',24,'flutter-mobile-app','9234b5676d33fc89789d48e27dd8e3485685b9205fc7d75d5844317ee0fd3945','[\"*\"]','2026-04-14 22:00:05',NULL,'2026-04-14 21:35:28','2026-04-14 22:00:05'),
(182,'App\\Models\\User',24,'flutter-mobile-app','295ac6a62b9f8e482d498d81265e248e98fbbfb396fc08fec62c6105a3b1cab4','[\"*\"]','2026-04-14 22:03:35',NULL,'2026-04-14 22:03:33','2026-04-14 22:03:35'),
(183,'App\\Models\\User',33,'flutter-mobile-app','78ee34e75e8edfce983993442daed10f7124f660af3bfd4677af89ad313ee386','[\"*\"]','2026-04-14 22:07:36',NULL,'2026-04-14 22:07:35','2026-04-14 22:07:36'),
(184,'App\\Models\\User',33,'flutter-mobile-app','c507c11184eac60f6be590eea61677f11959cc4645b9d918435034d9f71f9671','[\"*\"]','2026-04-14 22:32:56',NULL,'2026-04-14 22:31:51','2026-04-14 22:32:56'),
(185,'App\\Models\\User',33,'flutter-mobile-app','f6ae5fe8935481054fba9838e002d58b32164953327147f5fb8346282e3c9d9c','[\"*\"]','2026-04-14 22:33:20',NULL,'2026-04-14 22:33:17','2026-04-14 22:33:20'),
(186,'App\\Models\\User',24,'flutter-mobile-app','7f1232433ea971b45f332d1352b0ee71ff634d93ce65fe8e8e5b39e5d221f0ef','[\"*\"]','2026-04-14 22:36:28',NULL,'2026-04-14 22:35:00','2026-04-14 22:36:28'),
(187,'App\\Models\\User',33,'flutter-mobile-app','4b6196f76113a7b2b6f934dc6aba59fad4c7711bc6ce17327da39d62307af8d2','[\"*\"]','2026-04-14 22:37:04',NULL,'2026-04-14 22:37:02','2026-04-14 22:37:04'),
(188,'App\\Models\\User',24,'flutter-mobile-app','351a6765f211614f63298161d52c53781ddca3a166d0650d9874f1892fbf5d85','[\"*\"]','2026-04-14 22:38:36',NULL,'2026-04-14 22:37:41','2026-04-14 22:38:36'),
(189,'App\\Models\\User',24,'flutter-mobile-app','272e013c37ea901d7554b15285e2a6ffd891dd2abb4b9bb0e5e876e73278ab2b','[\"*\"]','2026-04-14 22:39:32',NULL,'2026-04-14 22:39:30','2026-04-14 22:39:32'),
(190,'App\\Models\\User',33,'flutter-mobile-app','881e18e640778a171b9d27c857c22a5280f510af205406bab2225bbf2b2d27cf','[\"*\"]','2026-04-14 22:40:23',NULL,'2026-04-14 22:40:21','2026-04-14 22:40:23'),
(191,'App\\Models\\User',24,'flutter-mobile-app','273bf7bdcce13e8ecffc47b5538d828ed2254483984542d11127d1af7d4149b9','[\"*\"]','2026-04-14 22:45:39',NULL,'2026-04-14 22:45:37','2026-04-14 22:45:39'),
(192,'App\\Models\\User',24,'flutter-mobile-app','9a4f70e3ff6bf8072a1f419c65e1ff8e151e0508c62b99f183d55f426aaadd63','[\"*\"]','2026-04-14 23:04:40',NULL,'2026-04-14 23:02:01','2026-04-14 23:04:40'),
(193,'App\\Models\\User',33,'flutter-mobile-app','0b1b3f57cdd0fe91262b25b5b8ae43edbb4612c00aa297d862216f16ae98eca7','[\"*\"]','2026-04-14 23:04:56',NULL,'2026-04-14 23:04:55','2026-04-14 23:04:56'),
(194,'App\\Models\\User',24,'flutter-mobile-app','341739448c8baca1ec8eca112200a373020babd2d9014287f2e22f34d6fb1e80','[\"*\"]','2026-04-14 23:27:06',NULL,'2026-04-14 23:24:14','2026-04-14 23:27:06'),
(195,'App\\Models\\User',33,'flutter-mobile-app','f02e57b86926d5c87d785593d323f1da2a5c86cfea202cd8f5bc08ecafec7741','[\"*\"]','2026-04-14 23:37:13',NULL,'2026-04-14 23:27:54','2026-04-14 23:37:13'),
(196,'App\\Models\\User',33,'face-login','7c1f8aa9c9ffd789750123c6371ac6ad0b083e1f2a3a918f1439bb73801be1b9','[\"*\"]','2026-04-14 23:50:17',NULL,'2026-04-14 23:40:17','2026-04-14 23:50:17'),
(197,'App\\Models\\User',33,'face-login','024ffc2dae584025853dae4bd4cc45c0f3ac2bc74247d535b82eb6ab87acbb25','[\"*\"]','2026-04-15 21:54:36',NULL,'2026-04-15 21:54:33','2026-04-15 21:54:36'),
(198,'App\\Models\\User',24,'flutter-mobile-app','348b4014b73dd0435d1ad4ab8e72cc37dab2a288e426e3bfce6822653d63c83f','[\"*\"]','2026-04-15 22:06:28',NULL,'2026-04-15 22:05:00','2026-04-15 22:06:28'),
(199,'App\\Models\\User',44,'flutter-mobile-app','96177c5a5f102666a22896a714d241f1da65138c86d8547c548561537954bfbc','[\"*\"]','2026-04-15 22:29:13',NULL,'2026-04-15 22:26:45','2026-04-15 22:29:13'),
(200,'App\\Models\\User',24,'flutter-mobile-app','44c1ab67d900cdeb10e26ca121dc32d991240d629e4f9684470fe3e2c1e40686','[\"*\"]','2026-04-15 22:55:33',NULL,'2026-04-15 22:55:17','2026-04-15 22:55:33'),
(201,'App\\Models\\User',44,'flutter-mobile-app','e5ebb5f80a081a403eb518e3e56334a4543dda98a25811b4af6e1268a5bed285','[\"*\"]','2026-04-15 22:57:47',NULL,'2026-04-15 22:55:58','2026-04-15 22:57:47'),
(202,'App\\Models\\User',44,'flutter-mobile-app','135a79a6e7b0e24fd9fd3188fc52b09aa825fb7e8222628af91a6fef78b6d95e','[\"*\"]','2026-04-16 22:26:51',NULL,'2026-04-16 22:17:01','2026-04-16 22:26:51'),
(203,'App\\Models\\User',44,'flutter-mobile-app','97b7fe3c99522c8ef76a4572a85a312fecddd004d080668f79a62764264fbd62','[\"*\"]','2026-04-16 22:31:41',NULL,'2026-04-16 22:31:40','2026-04-16 22:31:41'),
(204,'App\\Models\\User',44,'flutter-mobile-app','0effa1b6a44a4fe0367e570403bafe291e61da63b6b84335934102df494cc86f','[\"*\"]','2026-04-16 22:37:46',NULL,'2026-04-16 22:36:57','2026-04-16 22:37:46'),
(205,'App\\Models\\User',44,'flutter-mobile-app','6b06be8bf9d47d48e94c72bef23bfe56e1e1fc2fdcb6961d32b011d6199f9d54','[\"*\"]','2026-04-16 22:53:21',NULL,'2026-04-16 22:52:06','2026-04-16 22:53:21'),
(206,'App\\Models\\User',44,'flutter-mobile-app','556ca278da305b4bb2d49b976edd70d9d54fb30e1c377642a2fd72dc15cb58c1','[\"*\"]','2026-04-17 16:20:25',NULL,'2026-04-17 16:17:35','2026-04-17 16:20:25'),
(207,'App\\Models\\User',44,'flutter-mobile-app','c18b995382f779fd192749cf8e800ed5220f2dfda20766022d8165bad899d666','[\"*\"]','2026-04-17 21:57:37',NULL,'2026-04-17 21:56:42','2026-04-17 21:57:37'),
(208,'App\\Models\\User',44,'flutter-mobile-app','3ffd9e123b1218cbe46e231cd7c7c9586f7bf0b0968fa476a716579249b174a4','[\"*\"]','2026-04-17 22:02:44',NULL,'2026-04-17 22:02:42','2026-04-17 22:02:44'),
(209,'App\\Models\\User',24,'flutter-mobile-app','5b4f6e4cacad38cb6da2c06bc6dcf99e96cf4ff007603a5bd428af3fd76cd777','[\"*\"]','2026-04-17 22:25:28',NULL,'2026-04-17 22:24:27','2026-04-17 22:25:28'),
(210,'App\\Models\\User',24,'flutter-mobile-app','9cb2762667095375531565d9ea131ef83febbce81b1c7d1263cb520f39db96be','[\"*\"]','2026-04-17 22:38:13',NULL,'2026-04-17 22:26:55','2026-04-17 22:38:13'),
(211,'App\\Models\\User',24,'flutter-mobile-app','7d6a07e8884e8c37a8013bba875e62bc5fb544e09b3ac8962c3900147d3c7723','[\"*\"]','2026-04-27 21:28:02',NULL,'2026-04-17 22:29:36','2026-04-27 21:28:02'),
(212,'App\\Models\\User',24,'flutter-mobile-app','722f80d675c5ad90e44cbe63a51d7033cdb44a5cf1808bac77f6d37217df46f4','[\"*\"]','2026-04-17 22:51:49',NULL,'2026-04-17 22:50:44','2026-04-17 22:51:49'),
(213,'App\\Models\\User',40,'flutter-mobile-app','00d6d0691b17fe7a39ac0a01e360c96ca0222871f37262c64af5834961775c67','[\"*\"]','2026-04-17 22:53:39',NULL,'2026-04-17 22:53:38','2026-04-17 22:53:39'),
(214,'App\\Models\\User',33,'flutter-mobile-app','ed6651acdefa403a4f4537ca9b0ab2dfe9e1e582a822b83ca4be72c7467c947c','[\"*\"]','2026-04-17 22:55:58',NULL,'2026-04-17 22:55:57','2026-04-17 22:55:58'),
(215,'App\\Models\\User',43,'flutter-mobile-app','7e8c86ff57b29e29f7c86510238a2ba95e2a4e50417a743e76e6b3cd0efba283','[\"*\"]','2026-04-17 22:56:33',NULL,'2026-04-17 22:56:32','2026-04-17 22:56:33'),
(216,'App\\Models\\User',24,'flutter-mobile-app','4f5450556e219d2d9b9551f92e6cbc4f032160d52f1e10c36570831818c82316','[\"*\"]','2026-04-17 22:57:44',NULL,'2026-04-17 22:56:56','2026-04-17 22:57:44'),
(217,'App\\Models\\User',24,'flutter-mobile-app','5286524a850d248e2562977a3c7e946d65c8b4c7fd4fb253be0d6d7739176444','[\"*\"]','2026-04-17 22:58:20',NULL,'2026-04-17 22:58:19','2026-04-17 22:58:20'),
(218,'App\\Models\\User',33,'flutter-mobile-app','63eb3162898da7c4f38a7f70a97e0b6d19d4bc136d446c40d8999530339449cb','[\"*\"]','2026-04-17 22:58:48',NULL,'2026-04-17 22:58:47','2026-04-17 22:58:48'),
(219,'App\\Models\\User',40,'flutter-mobile-app','bb91ef5bf849fd88250156a8234f46254bd228acc84adbe9f413bbc5806e488b','[\"*\"]','2026-04-17 22:59:41',NULL,'2026-04-17 22:59:40','2026-04-17 22:59:41'),
(220,'App\\Models\\User',24,'flutter-mobile-app','b811f21efffda24d7155f7c3875671ac9dc6db14b3b4d3a001e0cea3998d87ca','[\"*\"]','2026-04-17 23:02:53',NULL,'2026-04-17 23:02:04','2026-04-17 23:02:53'),
(221,'App\\Models\\User',40,'flutter-mobile-app','61b4e892b144f6ebf2a684d0542fe719278f1a5bd11d6344881773ae1253a414','[\"*\"]','2026-04-17 23:03:48',NULL,'2026-04-17 23:03:47','2026-04-17 23:03:48'),
(222,'App\\Models\\User',33,'flutter-mobile-app','a81b892f669909aa266a5e5dcd9da476560a76aa40ec51b07be9d0a7579ab6b6','[\"*\"]','2026-04-17 23:04:23',NULL,'2026-04-17 23:04:21','2026-04-17 23:04:23'),
(223,'App\\Models\\User',24,'flutter-mobile-app','f265ff1ca83145cb8393263fcb352f1cc2d7fc9854bc3633b1fce9cb64306fd5','[\"*\"]','2026-04-17 23:11:26',NULL,'2026-04-17 23:10:31','2026-04-17 23:11:26'),
(224,'App\\Models\\User',33,'flutter-mobile-app','718dd5686fe8ef154b8335862985ce1472c0bd216c89be66b56842637ba700a4','[\"*\"]','2026-04-17 23:12:00',NULL,'2026-04-17 23:11:58','2026-04-17 23:12:00'),
(225,'App\\Models\\User',33,'flutter-mobile-app','3c32cb4c8f07d0c0b2a235c113e79020198419e4f4ba4c5e644fba983caf86e3','[\"*\"]','2026-04-17 23:13:43',NULL,'2026-04-17 23:13:42','2026-04-17 23:13:43'),
(226,'App\\Models\\User',24,'flutter-mobile-app','c6806cbe75ef1d451703d07812907546767e5dfed20ceffbbce433c827206d73','[\"*\"]','2026-04-17 23:18:21',NULL,'2026-04-17 23:17:30','2026-04-17 23:18:21'),
(227,'App\\Models\\User',24,'flutter-mobile-app','6563397576845bc634954e26e96f762221cce7322a196dd9e8bffe97f8df2aac','[\"*\"]','2026-04-17 23:22:41',NULL,'2026-04-17 23:22:04','2026-04-17 23:22:41'),
(228,'App\\Models\\User',33,'flutter-mobile-app','f40536f3e78a54eb37003e785d8d24c8679afea6c0bdd01b8238ee724bd355c1','[\"*\"]','2026-04-17 23:23:12',NULL,'2026-04-17 23:23:11','2026-04-17 23:23:12'),
(229,'App\\Models\\User',40,'flutter-mobile-app','0ce6dc2788c7e5532b1f0b144acd7bd6410a96ffd15483892ed600f16376b184','[\"*\"]','2026-04-17 23:23:55',NULL,'2026-04-17 23:23:54','2026-04-17 23:23:55'),
(230,'App\\Models\\User',24,'flutter-mobile-app','9d9520ee6c302e24a2dd4fd8a0d7ab3a4c67f2046d46fd4f48b6b573e4697a8f','[\"*\"]','2026-04-17 23:31:57',NULL,'2026-04-17 23:26:56','2026-04-17 23:31:57'),
(231,'App\\Models\\User',43,'flutter-mobile-app','a303e233c312331f78424c267f13bdef9335bee795912796e133c7afd4e396a1','[\"*\"]','2026-04-17 23:32:32',NULL,'2026-04-17 23:32:31','2026-04-17 23:32:32'),
(232,'App\\Models\\User',24,'flutter-mobile-app','405bb5743c7ecd7da2a2d3d75790455a81ebf9d8a118a8eb251e304683d567ef','[\"*\"]','2026-04-20 10:38:43',NULL,'2026-04-20 10:36:51','2026-04-20 10:38:43'),
(233,'App\\Models\\User',24,'flutter-mobile-app','e92eb83a920e3e10830e116d8b87ca75cef9f15f266046d5f0863e5595904fca','[\"*\"]','2026-04-20 10:50:21',NULL,'2026-04-20 10:42:18','2026-04-20 10:50:21'),
(234,'App\\Models\\User',24,'flutter-mobile-app','d693a705c320b07539355eed2f4f61db5412584dabcf49cf8502140ff7f34d8d','[\"*\"]','2026-04-20 10:45:35',NULL,'2026-04-20 10:44:51','2026-04-20 10:45:35'),
(235,'App\\Models\\User',24,'flutter-mobile-app','792cb8e69688d13a42c185e7dfac10fd35350341a5ffa942acecce9e6316d2b4','[\"*\"]','2026-04-20 11:12:30',NULL,'2026-04-20 10:52:39','2026-04-20 11:12:30'),
(236,'App\\Models\\User',46,'flutter-mobile-app','a9bb1e400703ec3f001afdfd221eda6c1a59117907138d0ecbab8c78ce7610f9','[\"*\"]','2026-04-20 11:13:44',NULL,'2026-04-20 11:13:42','2026-04-20 11:13:44'),
(237,'App\\Models\\User',33,'flutter-mobile-app','0b7cdfc4acb524a1f9213fb2a6798e6f2c4dc21a32af75883836ff2c978082d0','[\"*\"]','2026-04-20 11:14:31',NULL,'2026-04-20 11:14:29','2026-04-20 11:14:31'),
(238,'App\\Models\\User',46,'flutter-mobile-app','73f7e3c236cacaa9ab6e25ccdc13cb0854d0923ff28580c29889eb4b837697df','[\"*\"]','2026-04-20 11:15:21',NULL,'2026-04-20 11:15:19','2026-04-20 11:15:21'),
(239,'App\\Models\\User',46,'flutter-mobile-app','edf71e68536c4c878997ef059975cf37d0d3abb6daa0697a89ce66fd64c456ab','[\"*\"]','2026-04-20 11:36:56',NULL,'2026-04-20 11:36:52','2026-04-20 11:36:56'),
(240,'App\\Models\\User',24,'flutter-mobile-app','c2c763d15844b6346499a7e95e037a92342a7145c6525d7a9eee692244f88c84','[\"*\"]','2026-04-20 11:40:31',NULL,'2026-04-20 11:39:52','2026-04-20 11:40:31'),
(241,'App\\Models\\User',24,'flutter-mobile-app','c054e1bca55dc9bb691155a8d17736bc767e1e181b9b64855ee477b3aeb0fd94','[\"*\"]','2026-04-20 11:40:55',NULL,'2026-04-20 11:40:53','2026-04-20 11:40:55'),
(242,'App\\Models\\User',46,'flutter-mobile-app','714f9a0055b930b04f6484396c9a941c22014c1d35286b6645e99c4e78551f39','[\"*\"]','2026-04-20 11:41:17',NULL,'2026-04-20 11:41:16','2026-04-20 11:41:17'),
(243,'App\\Models\\User',46,'flutter-mobile-app','482a60d4c8e3f4b98e6a8aa88635e0e42e9c7a5e75f191c5d8f2bc27a2044c71','[\"*\"]','2026-04-20 11:46:27',NULL,'2026-04-20 11:46:25','2026-04-20 11:46:27'),
(244,'App\\Models\\User',46,'flutter-mobile-app','b17399630fc24624fbd882ce11e163e44e357ebddf625e0e70dc8f869f552de4','[\"*\"]','2026-04-20 11:49:27',NULL,'2026-04-20 11:49:25','2026-04-20 11:49:27'),
(245,'App\\Models\\User',24,'flutter-mobile-app','64204143de416b343c26b3d0d87effd66afcd1dbce391be6a3283da41c8f05ad','[\"*\"]','2026-04-20 11:54:54',NULL,'2026-04-20 11:50:06','2026-04-20 11:54:54'),
(246,'App\\Models\\User',46,'flutter-mobile-app','3160e537ad27c993e20934e75cd4b8756dc611b68f1082731154eab02a89774e','[\"*\"]','2026-04-20 11:55:24',NULL,'2026-04-20 11:55:23','2026-04-20 11:55:24'),
(247,'App\\Models\\User',44,'flutter-mobile-app','13fe582a7d3ef14bff9a7236e1570d26032268c80e1e0d0a7392014025d75d14','[\"*\"]','2026-04-20 11:56:23',NULL,'2026-04-20 11:56:22','2026-04-20 11:56:23'),
(248,'App\\Models\\User',44,'flutter-mobile-app','e0b5ce50a4a831da40c9f1a7c8acf068821023a2cafe67a1dfc5d026c9d51834','[\"*\"]','2026-04-20 12:13:52',NULL,'2026-04-20 12:13:50','2026-04-20 12:13:52'),
(249,'App\\Models\\User',24,'flutter-mobile-app','83883abf07868a4e2cdbc40a3556e81c99fc287898971a60a93996e442b25e39','[\"*\"]','2026-04-20 12:18:01',NULL,'2026-04-20 12:15:58','2026-04-20 12:18:01'),
(250,'App\\Models\\User',44,'flutter-mobile-app','432584c79a7f2415908a1444264513011383cd66b7661c728a154a1f55f99243','[\"*\"]','2026-04-20 12:18:24',NULL,'2026-04-20 12:18:23','2026-04-20 12:18:24'),
(251,'App\\Models\\User',24,'flutter-mobile-app','e8a4e566cc53dea97de8a5fd2c7dd0314b944ff8bf284845a6ff63715a663a8e','[\"*\"]','2026-04-20 12:29:55',NULL,'2026-04-20 12:29:24','2026-04-20 12:29:55'),
(252,'App\\Models\\User',44,'flutter-mobile-app','84676b1f778e076e6bee8df266647351b8808ba6577d1bbcf3f63feac0a07755','[\"*\"]','2026-04-20 12:30:13',NULL,'2026-04-20 12:30:12','2026-04-20 12:30:13'),
(253,'App\\Models\\User',44,'flutter-mobile-app','dfe250c768d0f0b2720ed3996b3aeb037697121ee372b89a28cefd9377228232','[\"*\"]','2026-04-20 12:36:36',NULL,'2026-04-20 12:36:34','2026-04-20 12:36:36'),
(254,'App\\Models\\User',33,'flutter-mobile-app','71676b8ec3d167094a66609c971200469e2a227e88704d6a9d5055f4ebe0004b','[\"*\"]','2026-04-20 12:52:57',NULL,'2026-04-20 12:52:55','2026-04-20 12:52:57'),
(255,'App\\Models\\User',24,'flutter-mobile-app','9744e7db6734f567e9983f2c302b878ca3118acbe6232703022269ab70d528a9','[\"*\"]','2026-04-20 12:58:26',NULL,'2026-04-20 12:56:50','2026-04-20 12:58:26'),
(256,'App\\Models\\User',33,'flutter-mobile-app','25877e0bd7fece837ceb6e32f2c523fc574970542606cd33cabfd234049f095d','[\"*\"]','2026-04-20 12:58:50',NULL,'2026-04-20 12:58:48','2026-04-20 12:58:50'),
(257,'App\\Models\\User',33,'flutter-mobile-app','47aa01db56d64d03c745858487616bef52d6f49bcb0948da0ed6497e6b0c7b51','[\"*\"]','2026-04-20 13:00:46',NULL,'2026-04-20 13:00:44','2026-04-20 13:00:46'),
(258,'App\\Models\\User',24,'flutter-mobile-app','82910d98c86989a994330fd6339dd4c9d05a07fd658b2674069946cd352b3e50','[\"*\"]','2026-04-20 13:32:54',NULL,'2026-04-20 13:30:13','2026-04-20 13:32:54'),
(259,'App\\Models\\User',33,'flutter-mobile-app','feb852331b4d612be846363745f7821e8e9d07fa79700541aadd3d571545e336','[\"*\"]','2026-04-20 13:33:18',NULL,'2026-04-20 13:33:17','2026-04-20 13:33:18'),
(260,'App\\Models\\User',44,'flutter-mobile-app','ca53379c73a7841f35587c23616536eb595db4c3a8c28c42c54704dd35d282b0','[\"*\"]','2026-04-20 13:37:15',NULL,'2026-04-20 13:34:14','2026-04-20 13:37:15'),
(261,'App\\Models\\User',44,'flutter-mobile-app','5cfbe4d30dd33e14a9d18fa79114fac44ceff7b0c380ff852a56f89364c4bf85','[\"*\"]','2026-04-20 13:37:50',NULL,'2026-04-20 13:37:49','2026-04-20 13:37:50'),
(262,'App\\Models\\User',44,'flutter-mobile-app','2141b0795267ad02d7aa768afb11af90521143fa9a96fb985f1ff933dbf91dba','[\"*\"]','2026-04-20 13:43:49',NULL,'2026-04-20 13:43:47','2026-04-20 13:43:49'),
(263,'App\\Models\\User',33,'flutter-mobile-app','62a840f5a6f04f9c6846fd38d4326ab42a16507c3310b029ec281f9b12e09491','[\"*\"]','2026-04-20 13:45:42',NULL,'2026-04-20 13:45:41','2026-04-20 13:45:42'),
(264,'App\\Models\\User',33,'flutter-mobile-app','3a4de3395ab099858c519b90dbeab929a9f604096f6b1d789ce92fc787fbf7dc','[\"*\"]','2026-04-20 13:48:43',NULL,'2026-04-20 13:48:41','2026-04-20 13:48:43'),
(265,'App\\Models\\User',24,'flutter-mobile-app','cdb409133eca939c5b0c51fb1b462fcc7af322f6c0298f81803f14c27a4b4c27','[\"*\"]','2026-04-20 14:20:32',NULL,'2026-04-20 14:17:45','2026-04-20 14:20:32'),
(266,'App\\Models\\User',33,'flutter-mobile-app','b89b285e2ed0d7e947c3c533075ff11817841aab41f5d22bdd9354dd9ca916d9','[\"*\"]','2026-04-20 14:21:00',NULL,'2026-04-20 14:20:53','2026-04-20 14:21:00'),
(267,'App\\Models\\User',44,'flutter-mobile-app','e15af393bdddd371cdcced2fb89138f2406e584f7184be996fdebdab658af8ca','[\"*\"]','2026-04-20 14:25:15',NULL,'2026-04-20 14:25:10','2026-04-20 14:25:15'),
(268,'App\\Models\\User',24,'flutter-mobile-app','88ae75171db35e234142e1f8243242a181bbf8e0c0239b53b077893c7633a9d6','[\"*\"]','2026-04-20 14:31:32',NULL,'2026-04-20 14:26:04','2026-04-20 14:31:32'),
(269,'App\\Models\\User',33,'flutter-mobile-app','38efcf3cf3d788602690a73c118315d5286dcc3b1d205308c7d30a6b67240216','[\"*\"]','2026-04-20 14:32:06',NULL,'2026-04-20 14:31:58','2026-04-20 14:32:06'),
(270,'App\\Models\\User',24,'flutter-mobile-app','30bf3eb0bf1f144c701901846647e081fe9bb71bb2407284a6227c953173b4b8','[\"*\"]','2026-04-20 14:47:11',NULL,'2026-04-20 14:45:42','2026-04-20 14:47:11'),
(271,'App\\Models\\User',33,'flutter-mobile-app','6584275ee4ecef44221e2bd403e16ccb01f1392d39a0861e4e1b6641905f653b','[\"*\"]','2026-04-20 14:47:41',NULL,'2026-04-20 14:47:38','2026-04-20 14:47:41'),
(272,'App\\Models\\User',24,'flutter-mobile-app','3f3ee70bff268352cb9cbbbc41963ad7daeb50f0c37d471f77fe8f338a5dca81','[\"*\"]','2026-04-20 16:28:05',NULL,'2026-04-20 16:25:29','2026-04-20 16:28:05'),
(273,'App\\Models\\User',33,'flutter-mobile-app','690152224ac67dd1a43b430e5ec06dbef66d388c0387322cc5410d047160e5f3','[\"*\"]','2026-04-20 16:36:11',NULL,'2026-04-20 16:28:45','2026-04-20 16:36:11'),
(274,'App\\Models\\User',33,'flutter-mobile-app','4db29791a633b1c376cddd5897da24eb1ebabde9e2633b6d8883221f42a058b6','[\"*\"]','2026-04-20 16:37:29',NULL,'2026-04-20 16:37:25','2026-04-20 16:37:29'),
(275,'App\\Models\\User',24,'flutter-mobile-app','1e6d9adc2619737f7cebd503816575280caeedc8e294e9e1bc8b6103439e0956','[\"*\"]','2026-04-20 16:39:44',NULL,'2026-04-20 16:37:57','2026-04-20 16:39:44'),
(276,'App\\Models\\User',44,'flutter-mobile-app','1ae41427623689c5e217ec48036ec462e8aadeb7068fc7a1614da529edce8eea','[\"*\"]','2026-04-20 16:43:05',NULL,'2026-04-20 16:40:22','2026-04-20 16:43:05'),
(277,'App\\Models\\User',33,'flutter-mobile-app','fae792dc1ed1925c67bd733528333d1e5867ab27342487b3dab3483142ed9be5','[\"*\"]','2026-04-20 16:59:12',NULL,'2026-04-20 16:44:10','2026-04-20 16:59:12'),
(278,'App\\Models\\User',33,'flutter-mobile-app','dca34e9e6109adf6a2418d6da231fdd50d19e4b4d9a12fc145797bee3cb833dc','[\"*\"]','2026-04-20 17:10:21',NULL,'2026-04-20 17:01:51','2026-04-20 17:10:21'),
(279,'App\\Models\\User',33,'flutter-mobile-app','6f9b048af3e014779c013e9989e1963b0db439c4036267689405ee6904e07306','[\"*\"]','2026-04-20 17:12:18',NULL,'2026-04-20 17:11:19','2026-04-20 17:12:18'),
(280,'App\\Models\\User',24,'flutter-mobile-app','c7e366a67aeab03b805c73f632b85a19519714e919aad702e223f99bf4aab830','[\"*\"]','2026-04-20 17:14:19',NULL,'2026-04-20 17:13:03','2026-04-20 17:14:19'),
(281,'App\\Models\\User',33,'flutter-mobile-app','03593dea8084f13d9591208dbd27f8228aed3ebdbcf582d7b1741631e38b45bb','[\"*\"]','2026-04-20 17:14:51',NULL,'2026-04-20 17:14:41','2026-04-20 17:14:51'),
(282,'App\\Models\\User',40,'flutter-mobile-app','3e3adda43d5226be56c36d9e258be977961a69bdaae79e41002bef94750520c1','[\"*\"]','2026-04-20 17:17:21',NULL,'2026-04-20 17:15:26','2026-04-20 17:17:21'),
(283,'App\\Models\\User',24,'flutter-mobile-app','2ed7654450817af3caf0c45296ca84c55a26dc1d404abdc4b6c5be1ad07be224','[\"*\"]','2026-04-20 17:32:31',NULL,'2026-04-20 17:23:36','2026-04-20 17:32:31'),
(284,'App\\Models\\User',33,'flutter-mobile-app','cd71a680205b6cf0213cf9d48e036a7f78f3d18a8e1de15cf3a9fed97aad6c8e','[\"*\"]','2026-04-20 17:40:00',NULL,'2026-04-20 17:33:08','2026-04-20 17:40:00'),
(285,'App\\Models\\User',24,'flutter-mobile-app','8d50a7c646ceec56bb2868eed8391a4ee628662ec920f6da3a3bbb03dbb1c1a0','[\"*\"]','2026-04-21 17:03:07',NULL,'2026-04-21 16:59:32','2026-04-21 17:03:07'),
(286,'App\\Models\\User',33,'flutter-mobile-app','432c541d6216149f547de9842cd00ce9e0ad198ff051c65a87bdc6114bfd1f60','[\"*\"]','2026-04-21 17:03:59',NULL,'2026-04-21 17:03:29','2026-04-21 17:03:59'),
(287,'App\\Models\\User',24,'flutter-mobile-app','37c6b8986734503c4740d8efcf3018d0de6e27b963ff3b0da775d9002f03646e','[\"*\"]','2026-04-21 17:07:22',NULL,'2026-04-21 17:06:38','2026-04-21 17:07:22'),
(288,'App\\Models\\User',48,'flutter-mobile-app','96f9d7df9fc8b37bee20d66dae61c69c33fe2026406fc31bf6b983c3b8aa0d2d','[\"*\"]','2026-04-21 22:31:44',NULL,'2026-04-21 22:17:45','2026-04-21 22:31:44'),
(289,'App\\Models\\User',48,'flutter-mobile-app','fd1857bdbeccdb7d7678ad07d39a64c9b2c8bad1892e06df991f5976dd322196','[\"*\"]','2026-04-21 22:22:11',NULL,'2026-04-21 22:21:20','2026-04-21 22:22:11'),
(290,'App\\Models\\User',48,'flutter-mobile-app','5986ecac9a0e503ba3ea6f0ed348e4f5def5221b394675db65bdc726df933381','[\"*\"]','2026-04-21 22:33:16',NULL,'2026-04-21 22:27:39','2026-04-21 22:33:16'),
(291,'App\\Models\\User',48,'flutter-mobile-app','9022f5735e14c7fdc453e016b48ef883c94d34b451b35e49492086be89df1add','[\"*\"]','2026-04-21 22:35:46',NULL,'2026-04-21 22:35:36','2026-04-21 22:35:46'),
(292,'App\\Models\\User',48,'flutter-mobile-app','797a79e1c50d9662192cd3937c34e603f07ecd868cfb9add2bcf1e62d47368ea','[\"*\"]','2026-04-21 22:41:40',NULL,'2026-04-21 22:41:23','2026-04-21 22:41:40'),
(293,'App\\Models\\User',48,'flutter-mobile-app','316e6b9a201cd3b0fa0b45506c2d8214d155ffb1293506d28a7c9f7eab6c66a1','[\"*\"]','2026-04-21 22:53:11',NULL,'2026-04-21 22:51:45','2026-04-21 22:53:11'),
(294,'App\\Models\\User',48,'flutter-mobile-app','9f886a3a4aa6962860369374c81685e5419f65bb72c4eef7c2643f67f520976f','[\"*\"]','2026-04-21 23:23:33',NULL,'2026-04-21 23:03:18','2026-04-21 23:23:33'),
(295,'App\\Models\\User',33,'flutter-mobile-app','d32b0cd8c691c55d7257c36fce5a4a625d1eb88a2d78c3f4803bc393bdfcbfc9','[\"*\"]','2026-04-21 23:31:44',NULL,'2026-04-21 23:29:27','2026-04-21 23:31:44'),
(296,'App\\Models\\User',33,'flutter-mobile-app','c11e5437f67aed0db311d07867bd5b32a27e8e38d695509e9956547e6daad9be','[\"*\"]','2026-04-21 23:45:13',NULL,'2026-04-21 23:33:19','2026-04-21 23:45:13'),
(297,'App\\Models\\User',33,'flutter-mobile-app','336740abf4d7f6fd95fc831a88033c3aa958087ab6fff3cb322f2709c42d9237','[\"*\"]','2026-04-22 22:42:57',NULL,'2026-04-22 22:40:30','2026-04-22 22:42:57'),
(298,'App\\Models\\User',33,'flutter-mobile-app','71d646a371e125ce09dd935eb4110e43483d5fbe126bf8b0232e0772f2e1e379','[\"*\"]','2026-04-22 22:50:39',NULL,'2026-04-22 22:50:16','2026-04-22 22:50:39'),
(299,'App\\Models\\User',33,'flutter-mobile-app','147b5021d1c0fa26fa68bea43ca66e2394a7a3f075ebf879517deb359b939cdc','[\"*\"]','2026-04-23 20:25:41',NULL,'2026-04-23 20:23:18','2026-04-23 20:25:41'),
(300,'App\\Models\\User',33,'flutter-mobile-app','389eefde76ea5da27a6b4ede2ca9b327ba12cd294e018461294a2aeafbf7c767','[\"*\"]','2026-04-23 21:22:30',NULL,'2026-04-23 20:40:56','2026-04-23 21:22:30'),
(301,'App\\Models\\User',33,'flutter-mobile-app','0596c1fc5d758f19a3c367111675bf473b66dde625b150898a24ed9037bf6f64','[\"*\"]','2026-04-23 21:27:03',NULL,'2026-04-23 21:24:00','2026-04-23 21:27:03'),
(302,'App\\Models\\User',49,'flutter-mobile-app','5b2dcf550fa332731d265348bde5a0df1089872509064496eee786c31abe48e1','[\"*\"]','2026-04-23 23:23:45',NULL,'2026-04-23 21:35:02','2026-04-23 23:23:45'),
(303,'App\\Models\\User',24,'flutter-mobile-app','e33cff5072439345b3801b6e8df5698ad9dd34e919ba56d9cc8c6c7f1a90791e','[\"*\"]','2026-04-27 17:28:10',NULL,'2026-04-27 17:18:33','2026-04-27 17:28:10'),
(304,'App\\Models\\User',24,'flutter-mobile-app','3f34853ba9ec1a6b1347482680a2bc1ce9759c24109ba0609f798ca1335a6b88','[\"*\"]','2026-04-27 21:48:28',NULL,'2026-04-27 21:24:11','2026-04-27 21:48:28'),
(305,'App\\Models\\User',24,'flutter-mobile-app','c31028a717ba543f3ecacc93f99883f4bb1abff78779784c728b08a61fb4ebb7','[\"*\"]','2026-04-27 22:03:49',NULL,'2026-04-27 21:57:09','2026-04-27 22:03:49'),
(306,'App\\Models\\User',33,'flutter-mobile-app','9a985cb766e7d0bf789861be2e39ef0e38c0412020c9f7cf5cc92d93f73f665b','[\"*\"]','2026-04-27 22:17:34',NULL,'2026-04-27 22:17:32','2026-04-27 22:17:34'),
(307,'App\\Models\\User',33,'flutter-mobile-app','6543310fd15ffeed65e0bb5961680b7e1fd4bd8bf4f8d60215c4982142f78134','[\"*\"]','2026-04-27 22:44:55',NULL,'2026-04-27 22:44:43','2026-04-27 22:44:55'),
(308,'App\\Models\\User',33,'flutter-mobile-app','aa977651664daa965e7c8f8fba46571bc9387b584df2550ec529e2e43ce77ef8','[\"*\"]','2026-04-27 22:49:30',NULL,'2026-04-27 22:49:27','2026-04-27 22:49:30'),
(309,'App\\Models\\User',24,'flutter-mobile-app','7f03df0eb56e4f19673a321b2a346dc6918ea605c3c5f3d2be1273d3778b0873','[\"*\"]','2026-04-27 22:50:39',NULL,'2026-04-27 22:50:04','2026-04-27 22:50:39'),
(310,'App\\Models\\User',33,'flutter-mobile-app','a3f534150ddb7e578a18dc7efffd10764d9f32dc24aa832eb7f04cf98a062bca','[\"*\"]','2026-04-27 22:52:38',NULL,'2026-04-27 22:51:00','2026-04-27 22:52:38'),
(311,'App\\Models\\User',24,'flutter-mobile-app','24020763317c5d909013fba6a2e41f215c83d334e30af0d46443d5cf9cab52e4','[\"*\"]','2026-04-27 22:54:05',NULL,'2026-04-27 22:53:35','2026-04-27 22:54:05'),
(312,'App\\Models\\User',33,'flutter-mobile-app','9b451375a6a52bdac7d30b9c879a62e4aa294f7196cd9c953bdba659789dbd19','[\"*\"]','2026-04-27 22:55:37',NULL,'2026-04-27 22:54:27','2026-04-27 22:55:37'),
(313,'App\\Models\\User',24,'flutter-mobile-app','6930a09ab548f2afe6f0ada9b3b533c3f300635af477e8b6c3b8fdf96aa85920','[\"*\"]','2026-04-27 23:06:06',NULL,'2026-04-27 23:04:45','2026-04-27 23:06:06'),
(314,'App\\Models\\User',33,'flutter-mobile-app','47e9f90643ffa4e84bec57747a1e10e1ec6aa1886bac0093188fb9cb84b9b62a','[\"*\"]','2026-04-27 23:13:12',NULL,'2026-04-27 23:06:40','2026-04-27 23:13:12'),
(315,'App\\Models\\User',24,'flutter-mobile-app','c860ce72f8a55aa208487e96a7967e8f3a69c5ad946545862eaaf8a0304a17f8','[\"*\"]','2026-04-28 11:34:00',NULL,'2026-04-28 10:53:05','2026-04-28 11:34:00'),
(316,'App\\Models\\User',33,'flutter-mobile-app','129949d406bca9f3c922e0393c5045cb3b74f673a11af65ee1289789f2baa6ad','[\"*\"]','2026-04-28 11:34:30',NULL,'2026-04-28 11:34:27','2026-04-28 11:34:30'),
(317,'App\\Models\\User',24,'flutter-mobile-app','3522962daa4df8ebf2b6c09f365c9b302a30e12866a7d744ee4d49511f7a0dea','[\"*\"]','2026-04-28 11:50:22',NULL,'2026-04-28 11:36:11','2026-04-28 11:50:22'),
(318,'App\\Models\\User',24,'flutter-mobile-app','de531ab456bd0431f39e526953ceb42e2b26c51eb263fe1fac9abcd3b97b4654','[\"*\"]','2026-04-28 11:51:55',NULL,'2026-04-28 11:51:06','2026-04-28 11:51:55'),
(319,'App\\Models\\User',33,'flutter-mobile-app','ead2f474409b70901dc2877a2c8d7e5453bf59e1ad711f9918ab2d9da59e7745','[\"*\"]','2026-04-28 11:59:15',NULL,'2026-04-28 11:52:16','2026-04-28 11:59:15'),
(320,'App\\Models\\User',33,'flutter-mobile-app','51aa046c94357d173d7d55037fe7a1aaec747c285852264946e8f1e6a2189039','[\"*\"]','2026-04-28 12:01:09',NULL,'2026-04-28 11:59:43','2026-04-28 12:01:09'),
(321,'App\\Models\\User',24,'flutter-mobile-app','df89dbc6a972f6ea68a7588f5d2b66ed52d19cc28c5824e3aef6bd63d2d7537e','[\"*\"]','2026-04-28 12:05:08',NULL,'2026-04-28 12:01:31','2026-04-28 12:05:08'),
(322,'App\\Models\\User',24,'flutter-mobile-app','6c44a411f229a3c3d324b8c001a4eb69cefa79201aef28e1d69bbd22810ebc79','[\"*\"]','2026-04-28 12:17:07',NULL,'2026-04-28 12:09:34','2026-04-28 12:17:07'),
(323,'App\\Models\\User',24,'flutter-mobile-app','b767e76842a8eb13f03aa35e94accf02a2bacebd16c037f1f82a68da6912088f','[\"*\"]','2026-04-28 13:24:34',NULL,'2026-04-28 12:44:26','2026-04-28 13:24:34'),
(324,'App\\Models\\User',33,'flutter-mobile-app','0a6311ecf87a5dfe7d916d75b87b12da33e28b808a87ba7677fbb2659d59f6a0','[\"*\"]','2026-04-28 13:37:30',NULL,'2026-04-28 13:29:59','2026-04-28 13:37:30'),
(325,'App\\Models\\User',24,'flutter-mobile-app','b16328fef21f0e19252ddb538dd7ff7e53d91b06b6183cf86fe956547c9473c2','[\"*\"]','2026-04-28 13:39:34',NULL,'2026-04-28 13:39:33','2026-04-28 13:39:34'),
(326,'App\\Models\\User',24,'flutter-mobile-app','d7c18a1fcea1feea2253b9f9a63a60a56760b2beed543eebcbb774a2efebca03','[\"*\"]','2026-04-28 14:03:45',NULL,'2026-04-28 14:01:37','2026-04-28 14:03:45'),
(327,'App\\Models\\User',24,'flutter-mobile-app','c0858477e78ae2ee69d3332e32e1c66c21c869ce5a8e4f415da75e2c0506987e','[\"*\"]','2026-04-28 14:05:29',NULL,'2026-04-28 14:04:46','2026-04-28 14:05:29'),
(328,'App\\Models\\User',24,'flutter-mobile-app','2d980e267de527cb1197530087379764396d18b3a467dce20bb5f51a2b35a722','[\"*\"]','2026-04-28 14:14:04',NULL,'2026-04-28 14:13:40','2026-04-28 14:14:04'),
(329,'App\\Models\\User',24,'flutter-mobile-app','9f0c6926c706b26cbdbb14a346b40f464a3e3e0ec13dfeba8e25f4941125d69b','[\"*\"]','2026-04-28 14:19:23',NULL,'2026-04-28 14:18:38','2026-04-28 14:19:23'),
(330,'App\\Models\\User',33,'flutter-mobile-app','378087a020898407bf0450a97bd97325bb656bf2251d0bf5aa5d9a02ab4b2439','[\"*\"]','2026-04-28 14:20:09',NULL,'2026-04-28 14:19:55','2026-04-28 14:20:09'),
(331,'App\\Models\\User',24,'flutter-mobile-app','1a4fadf520f45f205c2bd0ec720413f57c2b8909a40462b8d862b31e41edde89','[\"*\"]','2026-04-28 22:25:47',NULL,'2026-04-28 22:17:13','2026-04-28 22:25:47'),
(332,'App\\Models\\User',24,'flutter-mobile-app','a6a116bc17d6848798dec651aebb00e4ddbbe803841495c89bfd5e238ac65ac5','[\"*\"]','2026-04-28 22:31:31',NULL,'2026-04-28 22:30:38','2026-04-28 22:31:31'),
(333,'App\\Models\\User',40,'flutter-mobile-app','0e7dfd498876effb8a6f3a2a0f3771883b41d972abcfe9fb0d30ad32b70606a1','[\"*\"]','2026-04-28 22:32:53',NULL,'2026-04-28 22:32:00','2026-04-28 22:32:53'),
(334,'App\\Models\\User',24,'flutter-mobile-app','7c549e0fe1bcbfec171410ac6e837837c47266379f1e5635e4d81b71de216269','[\"*\"]','2026-04-28 22:42:04',NULL,'2026-04-28 22:33:10','2026-04-28 22:42:04'),
(335,'App\\Models\\User',33,'flutter-mobile-app','caeddc3352976481fccfbd9edbd3e518458d87eed668a33aa1eb8c2679a0f5f3','[\"*\"]','2026-04-28 22:42:29',NULL,'2026-04-28 22:42:25','2026-04-28 22:42:29'),
(336,'App\\Models\\User',33,'flutter-mobile-app','5b972c34b1110fcda3cd906b06951b07772ba7d97b2f8c056af0de9386b28fd3','[\"*\"]','2026-04-28 22:52:07',NULL,'2026-04-28 22:52:04','2026-04-28 22:52:07'),
(337,'App\\Models\\User',24,'flutter-mobile-app','3e9d69b8ee43c98fdf428311598f82ccc3e5c5c9066d3cf14b5c8e9a108d19cc','[\"*\"]','2026-04-28 22:54:32',NULL,'2026-04-28 22:52:26','2026-04-28 22:54:32'),
(338,'App\\Models\\User',43,'flutter-mobile-app','8338e3f903927a6151dce617a50266b7b00733301bc974101fac21c4639bbeae','[\"*\"]','2026-04-28 22:55:27',NULL,'2026-04-28 22:55:06','2026-04-28 22:55:27'),
(339,'App\\Models\\User',24,'flutter-mobile-app','ff308f045bf4e7f0dfcd735efa1d2a2cd5c2479aa082a94f8fa025df737e6880','[\"*\"]','2026-04-28 22:56:03',NULL,'2026-04-28 22:55:49','2026-04-28 22:56:03'),
(340,'App\\Models\\User',43,'flutter-mobile-app','015bceebbb2936428e40ba2941212e3654bf7679a29ac8d10453fb8cde5fb9c0','[\"*\"]','2026-04-28 22:56:34',NULL,'2026-04-28 22:56:31','2026-04-28 22:56:34'),
(341,'App\\Models\\User',43,'flutter-mobile-app','28baeb20653efcf27e5d55611b66810ed25d03103038606782b1efc3b1d84bd9','[\"*\"]','2026-04-28 22:59:06',NULL,'2026-04-28 22:58:47','2026-04-28 22:59:06'),
(342,'App\\Models\\User',43,'flutter-mobile-app','36308ec07ede1f26ea7d1fdccea0040e0eb60a2e9c5bffcb7593ab56efc8285d','[\"*\"]','2026-04-28 23:01:05',NULL,'2026-04-28 23:01:02','2026-04-28 23:01:05'),
(343,'App\\Models\\User',43,'flutter-mobile-app','2f3cbb46f97a92049ebb43ea9eeb4da27edeca2144f710481fcff1d6919aa171','[\"*\"]','2026-04-28 23:03:04',NULL,'2026-04-28 23:03:02','2026-04-28 23:03:04'),
(344,'App\\Models\\User',33,'flutter-mobile-app','fb9678874590d840ee9f90854ea74031d93dedc0fa2febd6947f8c9de61c8061','[\"*\"]','2026-04-28 23:03:30',NULL,'2026-04-28 23:03:24','2026-04-28 23:03:30'),
(345,'App\\Models\\User',43,'flutter-mobile-app','aac035f4565d8f178eca6c2ccf646e6f01fe6a95557cdcd2db71e9acaa3dae9f','[\"*\"]','2026-04-28 23:03:55',NULL,'2026-04-28 23:03:52','2026-04-28 23:03:55'),
(346,'App\\Models\\User',40,'flutter-mobile-app','396ede0910c89904447ad636a90ab6c1ccd3ce9ff84f9fe10f437eca81d0a4a4','[\"*\"]','2026-04-28 23:05:57',NULL,'2026-04-28 23:05:45','2026-04-28 23:05:57'),
(347,'App\\Models\\User',24,'flutter-mobile-app','387c7ca28c8a1e6c286b80b7f14ea21b7d6545398d99a35507239e0776663112','[\"*\"]','2026-04-28 23:07:32',NULL,'2026-04-28 23:06:13','2026-04-28 23:07:32'),
(348,'App\\Models\\User',40,'flutter-mobile-app','5df4791c2078834033fe74dbbd35ae557c5e94d4770b08bd389474cf79c58009','[\"*\"]','2026-04-28 23:12:52',NULL,'2026-04-28 23:11:39','2026-04-28 23:12:52'),
(349,'App\\Models\\User',33,'flutter-mobile-app','3842b493a08392c70b1bd2e6d5c22f157d53dd7cd9225917a4b4fa4f2e652b0d','[\"*\"]','2026-04-28 23:14:25',NULL,'2026-04-28 23:14:15','2026-04-28 23:14:25'),
(350,'App\\Models\\User',24,'flutter-mobile-app','343236afd18d959cce49d21759cd3f7ae4c1aa55ea3e848dde336b5fefa58315','[\"*\"]','2026-04-28 23:30:40',NULL,'2026-04-28 23:22:43','2026-04-28 23:30:40'),
(351,'App\\Models\\User',24,'flutter-mobile-app','80b1297acee11d0507ec5b356baf843e2fc4f478dbc4d4382afe9c6e06fa2cd4','[\"*\"]','2026-04-28 23:35:07',NULL,'2026-04-28 23:34:34','2026-04-28 23:35:07'),
(352,'App\\Models\\User',40,'flutter-mobile-app','4763a70eefe400e20d31e2aa82b6d451717d392d12b75cc626e29ba81e94a084','[\"*\"]','2026-04-28 23:35:39',NULL,'2026-04-28 23:35:35','2026-04-28 23:35:39'),
(353,'App\\Models\\User',24,'flutter-mobile-app','edd237d772924332d9601fa190ac1ebc83278409e847cd34ab947f32900f9046','[\"*\"]','2026-04-28 23:49:12',NULL,'2026-04-28 23:36:03','2026-04-28 23:49:12'),
(354,'App\\Models\\User',40,'flutter-mobile-app','57497712dd09856e2bb0164432c08a3a45cea010c7afca89b9e3325ecd19fb90','[\"*\"]','2026-04-28 23:40:08',NULL,'2026-04-28 23:39:58','2026-04-28 23:40:08'),
(355,'App\\Models\\User',24,'flutter-mobile-app','b6cfb488b18cd53ab43b5a22a9f5921cae54b6e97c8f37da1976f105a383d87e','[\"*\"]','2026-04-28 23:40:43',NULL,'2026-04-28 23:40:33','2026-04-28 23:40:43'),
(356,'App\\Models\\User',41,'flutter-mobile-app','dea345b2d58e5846566a2abc09b5e2592fac0643e193ea925dd78b100c36ebcf','[\"*\"]','2026-04-28 23:51:06',NULL,'2026-04-28 23:51:04','2026-04-28 23:51:06'),
(357,'App\\Models\\User',50,'flutter-mobile-app','ba990b4f7669396fb116d7368b1ad493f61aa02933f44e6f9270356a866a9e87','[\"*\"]','2026-04-29 09:15:15',NULL,'2026-04-29 09:13:41','2026-04-29 09:15:15'),
(358,'App\\Models\\User',50,'face-login','7039f90d48ff2898bb98dbdc9fd800f62426a0e367e45e05ec896c643f96f0ce','[\"*\"]','2026-04-29 09:16:14',NULL,'2026-04-29 09:15:49','2026-04-29 09:16:14'),
(359,'App\\Models\\User',50,'face-login','377f8a672e69c4bf18ec9b5a0aa13d98bd13dfa188f8a6207bb94f431c967e41','[\"*\"]','2026-04-29 09:24:48',NULL,'2026-04-29 09:21:16','2026-04-29 09:24:48'),
(360,'App\\Models\\User',33,'flutter-mobile-app','ea52a1bcdd6a7810deb3eea19d624413b6eb219b6eacefb722dc0d0e9b65fca2','[\"*\"]','2026-04-29 09:27:59',NULL,'2026-04-29 09:27:53','2026-04-29 09:27:59'),
(361,'App\\Models\\User',53,'flutter-mobile-app','83c780ebec429c118a8c2139ed7a38ccfe49cee1d8adfa6e82872cbc8f55e01c','[\"*\"]','2026-04-29 09:39:24',NULL,'2026-04-29 09:38:04','2026-04-29 09:39:24'),
(362,'App\\Models\\User',51,'flutter-mobile-app','39495abce057fca788330f53c7ef4c45b2a2171fb214bba9172b9dfb9eb922cc','[\"*\"]','2026-04-29 09:41:43',NULL,'2026-04-29 09:41:31','2026-04-29 09:41:43'),
(363,'App\\Models\\User',44,'flutter-mobile-app','cf5c663c623bf73d31db6d311d4c176caf6a3fc6c93c01d0f93a3b6482779c95','[\"*\"]','2026-04-29 09:42:17',NULL,'2026-04-29 09:42:03','2026-04-29 09:42:17'),
(364,'App\\Models\\User',43,'flutter-mobile-app','7aed706aa803f7e39bdd52c6493990841eff891e502ff83d4ae2a3ea1e9e85e5','[\"*\"]','2026-04-29 09:45:21',NULL,'2026-04-29 09:45:01','2026-04-29 09:45:21'),
(365,'App\\Models\\User',51,'flutter-mobile-app','6e7d6ad3751b67ef00dfd06c93ec9b2eafe62a1299dc27ab10bd990b9d6e6a87','[\"*\"]','2026-04-29 09:45:51',NULL,'2026-04-29 09:45:50','2026-04-29 09:45:51'),
(366,'App\\Models\\User',33,'flutter-mobile-app','06bc0ca509af6ccafce8e34518417451e12f17756bc80069bce53bc6a8bf8365','[\"*\"]','2026-04-29 09:47:04',NULL,'2026-04-29 09:46:58','2026-04-29 09:47:04'),
(367,'App\\Models\\User',53,'flutter-mobile-app','154c3c5b1532838c03c85ad3532d5a7f0e6439f769708c28e556afe6224597e6','[\"*\"]','2026-04-29 09:54:38',NULL,'2026-04-29 09:47:26','2026-04-29 09:54:38'),
(368,'App\\Models\\User',50,'face-login','49df46c58224ba14078fbcd3c1194271d424fe7b44da34d6c54271976993a3f3','[\"*\"]','2026-04-29 09:57:35',NULL,'2026-04-29 09:54:59','2026-04-29 09:57:35'),
(369,'App\\Models\\User',53,'flutter-mobile-app','b18b35dfb8066617aaa94b49b5b17213c36d80be616d957059b16e67de2ba566','[\"*\"]','2026-04-29 09:58:45',NULL,'2026-04-29 09:58:00','2026-04-29 09:58:45'),
(370,'App\\Models\\User',44,'flutter-mobile-app','49bb500ad59427e0d158c6188a5bab6ee1daf3b2dc9e538d3345fb76746edd20','[\"*\"]','2026-04-29 10:01:08',NULL,'2026-04-29 09:59:19','2026-04-29 10:01:08'),
(371,'App\\Models\\User',24,'flutter-mobile-app','5029ce27e30bf789256d0e4d801dfdbc2156aedd6c05f68c1456f32a52549ae8','[\"*\"]','2026-04-29 10:04:19',NULL,'2026-04-29 10:01:36','2026-04-29 10:04:19'),
(372,'App\\Models\\User',50,'flutter-mobile-app','a34a7e4fa704c93e44872a6ec5d7e09855a2f72b9993d597662707b54297e251','[\"*\"]','2026-04-29 10:04:38',NULL,'2026-04-29 10:04:33','2026-04-29 10:04:38'),
(373,'App\\Models\\User',50,'face-login','714f8b024e99d541e358d1e940d477ca6a2316ab815ab6571c97f95bb1f3ac05','[\"*\"]','2026-04-29 20:24:45',NULL,'2026-04-29 20:24:32','2026-04-29 20:24:45'),
(374,'App\\Models\\User',51,'flutter-mobile-app','63fce5e41819a1a5f8f433ca68a5fa4347d66f69dcc1aff2812f5dbb6a4abdeb','[\"*\"]','2026-04-29 20:25:52',NULL,'2026-04-29 20:25:03','2026-04-29 20:25:52'),
(375,'App\\Models\\User',51,'flutter-mobile-app','c7e9b5e0040db1da855d8c9f31c2940f757e91d89ad28c022963db5713b54452','[\"*\"]',NULL,NULL,'2026-04-29 20:26:11','2026-04-29 20:26:11'),
(376,'App\\Models\\User',54,'flutter-mobile-app','e03b833778016ff0d71756bd910af41fbcc2f4d821f3d45ae1089304a07f6ece','[\"*\"]','2026-04-29 20:39:34',NULL,'2026-04-29 20:36:33','2026-04-29 20:39:34'),
(377,'App\\Models\\User',24,'flutter-mobile-app','6cd0361651348150e117a7c69d5fc359e546699bf44c94bc4418df07deba56ea','[\"*\"]','2026-04-29 22:00:43',NULL,'2026-04-29 21:58:16','2026-04-29 22:00:43'),
(378,'App\\Models\\User',40,'flutter-mobile-app','e368b54773787e978fb69eace9eaaa9f07499ce16ed44908bd273a6f3ee7d194','[\"*\"]','2026-04-29 22:05:35',NULL,'2026-04-29 22:01:12','2026-04-29 22:05:35'),
(379,'App\\Models\\User',40,'flutter-mobile-app','3675bf7f5ab7a793e3c9adc414f1cd97e0853e587fd24af4b3b830d8cd6007fc','[\"*\"]','2026-04-29 22:39:51',NULL,'2026-04-29 22:03:16','2026-04-29 22:39:51'),
(380,'App\\Models\\User',50,'face-login','2864405b60257c81e075940dfe71378c2a8fc7107a00a5ae07c5fd111d44f0f7','[\"*\"]','2026-04-29 22:07:55',NULL,'2026-04-29 22:07:51','2026-04-29 22:07:55'),
(381,'App\\Models\\User',40,'flutter-mobile-app','468f1db3a17f9788d25172d5ce92ad7c54f23f2e4e42083043ec6ff719dc9a7b','[\"*\"]','2026-04-29 22:40:39',NULL,'2026-04-29 22:40:17','2026-04-29 22:40:39'),
(382,'App\\Models\\User',24,'flutter-mobile-app','15edeab1f5cf8e4527ebc25c2ad75e8ca20bc071f2a5f8a666f2f439f157558d','[\"*\"]','2026-04-29 22:58:53',NULL,'2026-04-29 22:41:19','2026-04-29 22:58:53'),
(383,'App\\Models\\User',40,'flutter-mobile-app','535cf9934be5ecb9fb68e0f6034aa1aa1069d999f6eb4a71d2f17e6b651372e1','[\"*\"]','2026-04-29 22:59:21',NULL,'2026-04-29 22:59:19','2026-04-29 22:59:21'),
(384,'App\\Models\\User',50,'flutter-mobile-app','0b09be5c60bdb300264bdc27de61bbc8fed2a5eb11bdf1e1e6e95267c0ca789c','[\"*\"]','2026-04-29 23:00:18',NULL,'2026-04-29 23:00:16','2026-04-29 23:00:18'),
(385,'App\\Models\\User',24,'flutter-mobile-app','c33bc659acac806bb1d6a56b06f41a0e0d3b6630a179d1aeafd3fe2033771eb5','[\"*\"]','2026-04-29 23:04:53',NULL,'2026-04-29 23:00:39','2026-04-29 23:04:53'),
(386,'App\\Models\\User',24,'flutter-mobile-app','f31000968977d6f7278249662f16fc8e6f2799257d20fdd7d38e15bbaf4a82f4','[\"*\"]','2026-04-30 23:03:50',NULL,'2026-04-30 23:01:53','2026-04-30 23:03:50'),
(387,'App\\Models\\User',40,'flutter-mobile-app','215401a3ab67262bfceed026ef0c4e203ec29cb967e81b81c753a6c2aacfdfba','[\"*\"]','2026-04-30 23:04:19',NULL,'2026-04-30 23:04:16','2026-04-30 23:04:19'),
(388,'App\\Models\\User',24,'flutter-mobile-app','0cc80d50bcce2f8f14928f74a596579ee302d7ca56fd3987275fd77ac09c5962','[\"*\"]','2026-04-30 23:05:18',NULL,'2026-04-30 23:04:53','2026-04-30 23:05:18'),
(389,'App\\Models\\User',40,'flutter-mobile-app','a6b9168b0339f0ffe8af462fa605bc6c6defff4a23e3c822990358b36e5ece6e','[\"*\"]','2026-04-30 23:06:10',NULL,'2026-04-30 23:05:47','2026-04-30 23:06:10'),
(390,'App\\Models\\User',24,'flutter-mobile-app','53286e7dca2c14849c044e9b42e49a8e2e48a1ff0fe059f9b74ff756c2dd3d6f','[\"*\"]','2026-04-30 23:07:27',NULL,'2026-04-30 23:06:21','2026-04-30 23:07:27'),
(391,'App\\Models\\User',40,'flutter-mobile-app','17e4179a126b1fdeea1e5619bc6523cec25bd40f72d64a09837cb83666878330','[\"*\"]','2026-04-30 23:08:01',NULL,'2026-04-30 23:07:58','2026-04-30 23:08:01'),
(392,'App\\Models\\User',24,'flutter-mobile-app','6b557efaece5070941e8cae3effc4977e20f2ca2ab1257e5b1577ed48f6d915b','[\"*\"]','2026-04-30 23:09:10',NULL,'2026-04-30 23:08:27','2026-04-30 23:09:10'),
(393,'App\\Models\\User',53,'flutter-mobile-app','3374357fad9d151c4a80cc90244db35e40c0e137c57341d44aff295526725df2','[\"*\"]','2026-04-30 23:09:43',NULL,'2026-04-30 23:09:39','2026-04-30 23:09:43'),
(394,'App\\Models\\User',24,'flutter-mobile-app','8edb09dfd7ecd0eb3df7d066a228befef5cba998112adddb001741d4e9883030','[\"*\"]','2026-04-30 23:10:19',NULL,'2026-04-30 23:10:02','2026-04-30 23:10:19'),
(395,'App\\Models\\User',53,'flutter-mobile-app','961e6e39fc288357f98cf80d607d0b0f9fedd212b62d04e9c7b69aec155a04d4','[\"*\"]','2026-04-30 23:13:42',NULL,'2026-04-30 23:13:38','2026-04-30 23:13:42'),
(396,'App\\Models\\User',52,'flutter-mobile-app','44884897d088452fb83dd237fce1976442d0d6a3b1c76faa19a09e4dffe781ac','[\"*\"]','2026-04-30 23:21:01',NULL,'2026-04-30 23:20:58','2026-04-30 23:21:01'),
(397,'App\\Models\\User',24,'flutter-mobile-app','bfe68c2f14632a015f6f3b7893770cf43ba8bdff90e1457f512f5bb2835c8ecc','[\"*\"]','2026-04-30 23:23:10',NULL,'2026-04-30 23:22:27','2026-04-30 23:23:10'),
(398,'App\\Models\\User',52,'flutter-mobile-app','d25dc9333fe545d5afbb4a5c149732025db01a89a12c08b78b7c494133e67c54','[\"*\"]','2026-04-30 23:23:46',NULL,'2026-04-30 23:23:43','2026-04-30 23:23:46'),
(399,'App\\Models\\User',24,'flutter-mobile-app','7ca99de957e646160d11854b256c48fdcbbe13f6edcea76af3b0b21613a367a1','[\"*\"]','2026-04-30 23:25:10',NULL,'2026-04-30 23:24:19','2026-04-30 23:25:10'),
(400,'App\\Models\\User',41,'flutter-mobile-app','82530eebc94cb88630d9fbe088b3a354f43dfb8ea1f0b7739569ee69925454fe','[\"*\"]','2026-04-30 23:25:40',NULL,'2026-04-30 23:25:37','2026-04-30 23:25:40'),
(401,'App\\Models\\User',24,'flutter-mobile-app','7b59854bf676e01002f745681d409f9a4c4745f336ad51d04fe4f28de754fa65','[\"*\"]','2026-04-30 23:27:13',NULL,'2026-04-30 23:26:47','2026-04-30 23:27:13'),
(402,'App\\Models\\User',41,'flutter-mobile-app','d8b6225215dfaf979713885224ba93a064ad5bc55c7cc172a7f305f506cd8129','[\"*\"]','2026-04-30 23:27:54',NULL,'2026-04-30 23:27:50','2026-04-30 23:27:54'),
(403,'App\\Models\\User',24,'flutter-mobile-app','d6f3365cdc2d34d319988d7051527dffdb5a8fad07888a5df82f9a6f04048531','[\"*\"]','2026-05-04 11:08:12',NULL,'2026-05-04 11:05:57','2026-05-04 11:08:12'),
(404,'App\\Models\\User',33,'flutter-mobile-app','1db6a1f6f9eacf8bee2c7bf3f95ce77211ac768ecc6fc0819684297027ae3266','[\"*\"]','2026-05-04 11:08:34',NULL,'2026-05-04 11:08:31','2026-05-04 11:08:34'),
(405,'App\\Models\\User',33,'face-login','93d230ccc99d9f5cba77cc6fa0730115e782181a987f77d9b7fd2cce6707e864','[\"*\"]','2026-05-04 11:09:04',NULL,'2026-05-04 11:09:02','2026-05-04 11:09:04'),
(406,'App\\Models\\User',24,'flutter-mobile-app','7b2aa472deb76fa84e618d39e8ef1e7d1f21b6f0bfbc80d9c1d0d4addced481e','[\"*\"]','2026-05-04 11:37:11',NULL,'2026-05-04 11:09:28','2026-05-04 11:37:11'),
(407,'App\\Models\\User',24,'flutter-mobile-app','cd789ba534ad84935aa39bfa7f3400fa95ee1024909b8a6bbe07b304b6fa0bc6','[\"*\"]','2026-05-04 11:43:07',NULL,'2026-05-04 11:37:20','2026-05-04 11:43:07'),
(408,'App\\Models\\User',24,'flutter-mobile-app','ba8df26807813f505dddaf40860c1c229e6163c21fbdfbfc7c6f31e79b542685','[\"*\"]','2026-05-04 12:26:20',NULL,'2026-05-04 11:57:14','2026-05-04 12:26:20'),
(409,'App\\Models\\User',24,'flutter-mobile-app','018bdc188ebde5f0c686bb3c3087832bfdc72a0d11b7c132e7abb19e937c45e3','[\"*\"]','2026-05-04 12:34:04',NULL,'2026-05-04 12:33:58','2026-05-04 12:34:04'),
(410,'App\\Models\\User',33,'flutter-mobile-app','7135d1626a27ae920e269fc58a248c83536122ef30b57f51d1fbfa83b988e324','[\"*\"]','2026-05-04 12:40:41',NULL,'2026-05-04 12:40:39','2026-05-04 12:40:41'),
(411,'App\\Models\\User',33,'flutter-mobile-app','72af404488389d6ca904bee360c016d1f6b37e4c72d954889cc37b6638b092a1','[\"*\"]','2026-05-04 12:48:35',NULL,'2026-05-04 12:48:32','2026-05-04 12:48:35'),
(412,'App\\Models\\User',40,'flutter-mobile-app','80c21ef04ac194451c3079f6422f37ff184557cda903607b352e59d7b5ed13fd','[\"*\"]','2026-05-04 12:59:05',NULL,'2026-05-04 12:49:08','2026-05-04 12:59:05'),
(413,'App\\Models\\User',24,'flutter-mobile-app','79de40076dc1d9f8a34c6f274b236b736f04d5df66c2de3c586185b38b014e9c','[\"*\"]','2026-05-04 12:59:44',NULL,'2026-05-04 12:59:24','2026-05-04 12:59:44'),
(414,'App\\Models\\User',40,'flutter-mobile-app','8732f801f71aafa42a6140a86468124c8708e2aef1f8f6a10c116743639c4b92','[\"*\"]','2026-05-04 13:01:02',NULL,'2026-05-04 13:00:10','2026-05-04 13:01:02'),
(415,'App\\Models\\User',24,'flutter-mobile-app','76e2ac013de9ff41506984cfd6774df8979d0ad1b0deb7b24b79fe9061237662','[\"*\"]','2026-05-04 13:02:59',NULL,'2026-05-04 13:01:56','2026-05-04 13:02:59'),
(416,'App\\Models\\User',33,'flutter-mobile-app','893ab07654e3de618670e2a6b54c15f6e71596897839002340f5fce5c86e6249','[\"*\"]','2026-05-04 13:04:51',NULL,'2026-05-04 13:03:31','2026-05-04 13:04:51'),
(417,'App\\Models\\User',40,'flutter-mobile-app','376b71cc67c03d2faad167eaefc090ac4f30a87d884827cba153ead670b89aa9','[\"*\"]','2026-05-04 13:06:14',NULL,'2026-05-04 13:05:10','2026-05-04 13:06:14'),
(418,'App\\Models\\User',40,'flutter-mobile-app','b46ca41d9feea03c817e3eab6d5a0b5a3313fc5c40d684db1bdc0983d6c1a20b','[\"*\"]','2026-05-04 15:48:18',NULL,'2026-05-04 15:45:57','2026-05-04 15:48:18'),
(419,'App\\Models\\User',54,'flutter-mobile-app','3d0812ed3fee74812899ad3990884c16aedb9fc496855556e4ae06788320a3f1','[\"*\"]','2026-05-04 15:54:30',NULL,'2026-05-04 15:50:57','2026-05-04 15:54:30'),
(420,'App\\Models\\User',54,'flutter-mobile-app','f32a187d3b7e5a5b4274f1048bfce80e43bfe1d25af032cef49fcef521ec50a7','[\"*\"]','2026-05-04 16:14:08',NULL,'2026-05-04 15:57:59','2026-05-04 16:14:08'),
(421,'App\\Models\\User',54,'flutter-mobile-app','89bc407760a77a53afedde39f7443d4e7ff2298d32256742a598f82c41bc692d','[\"*\"]','2026-05-04 16:40:11',NULL,'2026-05-04 16:19:40','2026-05-04 16:40:11'),
(422,'App\\Models\\User',52,'flutter-mobile-app','a5ef901e8d9cc9e14fc29baee48776f1b14c1064f79e2d0e2acbf6410deff23f','[\"*\"]','2026-05-04 16:40:48',NULL,'2026-05-04 16:40:33','2026-05-04 16:40:48'),
(423,'App\\Models\\User',24,'flutter-mobile-app','e35e73b6aafeae5210e373576965b6637b5d61b7a36c91fd445687f17bffc694','[\"*\"]','2026-05-04 16:43:35',NULL,'2026-05-04 16:41:11','2026-05-04 16:43:35'),
(424,'App\\Models\\User',54,'flutter-mobile-app','eb1922f02ed9f10eaaba9794e9345c7f4043d0d3b5fa8c39fb42cbe25931ee49','[\"*\"]','2026-05-04 16:44:19',NULL,'2026-05-04 16:44:14','2026-05-04 16:44:19'),
(425,'App\\Models\\User',54,'flutter-mobile-app','15198414400cdd18953fb195abac7bfb6ccba3418288c2660e4eeecad285bb00','[\"*\"]','2026-05-04 16:48:58',NULL,'2026-05-04 16:47:36','2026-05-04 16:48:58'),
(426,'App\\Models\\User',52,'flutter-mobile-app','e159d9c0b49be42ab4f46f1ca7f014364bb0616d3962dfd4c1baa971a43aa6cd','[\"*\"]','2026-05-04 16:50:24',NULL,'2026-05-04 16:50:18','2026-05-04 16:50:24'),
(427,'App\\Models\\User',54,'flutter-mobile-app','1a7596385cc1ac547bf40482aac9d000b55c8e1afc142665fb098a1600f3d3ba','[\"*\"]','2026-05-04 16:56:44',NULL,'2026-05-04 16:51:29','2026-05-04 16:56:44'),
(428,'App\\Models\\User',54,'flutter-mobile-app','253d1e4edf3f942011bec3228cfadcccf2112c84bc702fcba7cbb0c63811369b','[\"*\"]','2026-05-04 17:06:26',NULL,'2026-05-04 16:57:03','2026-05-04 17:06:26'),
(429,'App\\Models\\User',54,'flutter-mobile-app','9a6aba433f786038fc82a325071b2fb3aaeff28f92389f1b735aa3f62cb3427d','[\"*\"]','2026-05-04 17:24:30',NULL,'2026-05-04 17:18:34','2026-05-04 17:24:30'),
(430,'App\\Models\\User',54,'flutter-mobile-app','caa02d968a7656c8406120fff3012aaee9d1cee25ba0180143cef5eadc1d54fd','[\"*\"]','2026-05-04 17:29:05',NULL,'2026-05-04 17:28:24','2026-05-04 17:29:05'),
(431,'App\\Models\\User',54,'flutter-mobile-app','724583415e2c286f8145491f82096a99cf1f24573832e8147cc5d2ba3ab781f6','[\"*\"]','2026-05-04 17:44:24',NULL,'2026-05-04 17:38:50','2026-05-04 17:44:24'),
(432,'App\\Models\\User',24,'flutter-mobile-app','9c7de5c3578f29fa8263c8557dabfc882a3e1ba6651c63266d5afcf14af992a4','[\"*\"]','2026-05-04 17:41:53',NULL,'2026-05-04 17:41:45','2026-05-04 17:41:53'),
(433,'App\\Models\\User',40,'flutter-mobile-app','62b16db053b0c5ff93f5cb4bb729c78a2ce5444faa616657a9ce93945fbdc803','[\"*\"]','2026-05-04 17:45:04',NULL,'2026-05-04 17:45:00','2026-05-04 17:45:04'),
(434,'App\\Models\\User',24,'flutter-mobile-app','a8666c84b73175a1113ee4e4177e5cce519774e16cd649e276ca127551c245ea','[\"*\"]','2026-05-05 14:16:12',NULL,'2026-05-05 14:15:32','2026-05-05 14:16:12'),
(435,'App\\Models\\User',24,'flutter-mobile-app','2dea2bc00106843a1eaf5b9ee727ad044e47f6c449d8b72fd7fbdd091e843556','[\"*\"]','2026-05-05 15:45:08',NULL,'2026-05-05 14:32:15','2026-05-05 15:45:08'),
(436,'App\\Models\\User',24,'flutter-mobile-app','bbac5a673a3136840b831e673fe1f01a281989532bb349dea771849f8d97cddb','[\"*\"]','2026-05-05 21:28:21',NULL,'2026-05-05 21:28:01','2026-05-05 21:28:21'),
(437,'App\\Models\\User',24,'flutter-mobile-app','7bdccbf67e056252a0cc96e401de9eb21a2c1794ab493009f91a5d0fe72e02a2','[\"*\"]','2026-05-05 21:38:59',NULL,'2026-05-05 21:32:09','2026-05-05 21:38:59'),
(438,'App\\Models\\User',54,'flutter-mobile-app','f39cb6f2111091005357e8d9de97aab7e6b84006f0d2e04fb56a6b2448244593','[\"*\"]','2026-05-05 21:41:53',NULL,'2026-05-05 21:40:37','2026-05-05 21:41:53'),
(439,'App\\Models\\User',52,'flutter-mobile-app','e88d6a4a770bb9692a3e5e215e6ed989f1780ebf5458b4ba123f7504bbd54039','[\"*\"]','2026-05-05 21:42:37',NULL,'2026-05-05 21:42:30','2026-05-05 21:42:37'),
(440,'App\\Models\\User',24,'flutter-mobile-app','4aaf855b23670fbb39e0daddd2eecf9a2783dd1df4ed0352551321b5f509dc03','[\"*\"]','2026-05-05 21:49:59',NULL,'2026-05-05 21:49:28','2026-05-05 21:49:59'),
(441,'App\\Models\\User',24,'flutter-mobile-app','c7a95665b85b828d2d8b6b276b05feb2a15dfbbd2e995a3a32537384061d01e2','[\"*\"]','2026-05-05 22:46:51',NULL,'2026-05-05 22:03:21','2026-05-05 22:46:51'),
(442,'App\\Models\\User',24,'flutter-mobile-app','11f7cadee152beb9944b7561985367262f7d04e9194518da3cd3d02dd75bf497','[\"*\"]','2026-05-05 22:49:39',NULL,'2026-05-05 22:12:28','2026-05-05 22:49:39'),
(443,'App\\Models\\User',24,'flutter-mobile-app','c43191b5c48f4357335026be6b263aba6d0261ab4ef32e5614d6611be9a7382a','[\"*\"]','2026-05-05 23:05:54',NULL,'2026-05-05 22:50:25','2026-05-05 23:05:54'),
(444,'App\\Models\\User',54,'flutter-mobile-app','beec6ba5251f42f02d1ebe096626b9216fde26e38aa5bd19bc446e4d23c81f2a','[\"*\"]','2026-05-05 23:07:10',NULL,'2026-05-05 23:06:10','2026-05-05 23:07:10'),
(445,'App\\Models\\User',52,'flutter-mobile-app','290d8175ce9c26ae69dad5dfa58f98f4d711913cd44e1d142304d0ac3ba7d30b','[\"*\"]','2026-05-05 23:09:13',NULL,'2026-05-05 23:07:43','2026-05-05 23:09:13'),
(446,'App\\Models\\User',24,'flutter-mobile-app','f20e345bcc2f761a1edea3a9070155bd50724d03c83accd478098ec7b7c71c12','[\"*\"]','2026-05-05 23:10:36',NULL,'2026-05-05 23:10:08','2026-05-05 23:10:36'),
(447,'App\\Models\\User',24,'flutter-mobile-app','e7155a0baa42bcc458e3fa2aa8422660fb62e01c28f45cef8136a5dd64e9b571','[\"*\"]','2026-05-05 23:41:08',NULL,'2026-05-05 23:40:23','2026-05-05 23:41:08'),
(448,'App\\Models\\User',54,'flutter-mobile-app','c7bf060894f47203ca9110ce515d9d3e04c87464d11fa09cbe45c25fa9439544','[\"*\"]','2026-05-05 23:48:13',NULL,'2026-05-05 23:41:34','2026-05-05 23:48:13'),
(449,'App\\Models\\User',33,'flutter-mobile-app','c41a70d166d8d620e6a4d8b889abcc6b64214d38ce4c0643e2ebaea34523a19d','[\"*\"]','2026-05-05 23:48:42',NULL,'2026-05-05 23:48:38','2026-05-05 23:48:42'),
(450,'App\\Models\\User',54,'flutter-mobile-app','f51bafd6e6da041485aa04a145243f9b8a94eb00f897b4966f89f23c2faaba34','[\"*\"]','2026-05-06 00:30:05',NULL,'2026-05-05 23:51:39','2026-05-06 00:30:05'),
(451,'App\\Models\\User',54,'flutter-mobile-app','8427fe1b43a4d9ce5f6d46a4192c493565b04228f6034f144e2128b31bcaae66','[\"*\"]','2026-05-06 00:15:40',NULL,'2026-05-06 00:15:28','2026-05-06 00:15:40'),
(452,'App\\Models\\User',44,'flutter-mobile-app','d05a03566f500c4d636e4fd0f7d6d57780f6c25ba8ef6e696f062c2cf2055be5','[\"*\"]','2026-05-06 00:35:21',NULL,'2026-05-06 00:34:29','2026-05-06 00:35:21'),
(453,'App\\Models\\User',52,'flutter-mobile-app','86b59309793c7af85d614e6ecd78c1df44f8e1a0b81947e1dc32e9db5fdbc504','[\"*\"]','2026-05-06 00:36:38',NULL,'2026-05-06 00:35:47','2026-05-06 00:36:38'),
(454,'App\\Models\\User',52,'flutter-mobile-app','30800db0dc64baced2f91dd6842b644a0af68863d852c66bd3ff44b8196310be','[\"*\"]','2026-05-06 00:36:58',NULL,'2026-05-06 00:36:56','2026-05-06 00:36:58'),
(455,'App\\Models\\User',54,'flutter-mobile-app','6f485a1426ee76aaa318e811e54d6103b2ff16d3c10218cfc8063d90cc1db207','[\"*\"]','2026-05-06 00:37:20',NULL,'2026-05-06 00:37:18','2026-05-06 00:37:20'),
(456,'App\\Models\\User',52,'flutter-mobile-app','d8f9a110181d69f18da3662c77659c4aebf78a0254cb609765085a88aa8edd84','[\"*\"]','2026-05-06 00:39:28',NULL,'2026-05-06 00:39:25','2026-05-06 00:39:28'),
(457,'App\\Models\\User',24,'flutter-mobile-app','64fed4452ccac67785855d003556b824de9aa03ce1d0a88380310439d6cbc32d','[\"*\"]','2026-05-06 00:40:48',NULL,'2026-05-06 00:39:43','2026-05-06 00:40:48'),
(458,'App\\Models\\User',54,'flutter-mobile-app','731c4b10f6332348eaee6af74a95560fa5efd79c3845da55a40ae943b92c5204','[\"*\"]','2026-05-06 00:41:56',NULL,'2026-05-06 00:41:21','2026-05-06 00:41:56'),
(459,'App\\Models\\User',24,'flutter-mobile-app','e021add4278d057a244b5316a9843e57f0cebf798a0eeddb85b95e95290285c0','[\"*\"]','2026-05-06 00:54:53',NULL,'2026-05-06 00:53:45','2026-05-06 00:54:53'),
(460,'App\\Models\\User',54,'flutter-mobile-app','7bcbba511503a206aee0302973c871ea74b98954f2f81235ef00ced5401a99b6','[\"*\"]','2026-05-06 00:56:01',NULL,'2026-05-06 00:55:06','2026-05-06 00:56:01'),
(461,'App\\Models\\User',24,'flutter-mobile-app','44d8c155b1ec77f1e5e6e80d2fe497ea6ec70bcb8c0a67dd1b4713617f358f2a','[\"*\"]','2026-05-06 16:19:27',NULL,'2026-05-06 16:19:08','2026-05-06 16:19:27'),
(462,'App\\Models\\User',54,'flutter-mobile-app','b8ab6d977b45b82a49a27a8f4995d1c8fd36ef859b726cb1cceccb8c9190ccf8','[\"*\"]','2026-05-06 16:25:34',NULL,'2026-05-06 16:19:51','2026-05-06 16:25:34'),
(463,'App\\Models\\User',54,'flutter-mobile-app','343ab4ed29bd783003208eddc85414ebc05f63782af589bddbc1f9ff1d90837c','[\"*\"]','2026-05-06 16:31:27',NULL,'2026-05-06 16:26:42','2026-05-06 16:31:27'),
(464,'App\\Models\\User',33,'flutter-mobile-app','3b3dcc4d815eb21224bddd21e976292bb3d97b4b42d697f23616bbd6a223732b','[\"*\"]','2026-05-06 16:33:19',NULL,'2026-05-06 16:33:00','2026-05-06 16:33:19'),
(465,'App\\Models\\User',54,'flutter-mobile-app','b4936994082c99385672c4b2a1ec4deafaba1f022580de655a1341f61ee6a731','[\"*\"]','2026-05-06 16:38:31',NULL,'2026-05-06 16:33:58','2026-05-06 16:38:31'),
(466,'App\\Models\\User',54,'flutter-mobile-app','e17ca14e8d5e3f05c8b2a8e2663099af43f3965c33f987ff2f0f1ff21d8cef7e','[\"*\"]','2026-05-06 17:27:14',NULL,'2026-05-06 17:13:10','2026-05-06 17:27:14'),
(467,'App\\Models\\User',52,'flutter-mobile-app','d3b29fef38d1ccd264bf14398905cb830dd6a66b6190c932b7e50b9c2a4e4359','[\"*\"]','2026-05-06 17:27:39',NULL,'2026-05-06 17:27:37','2026-05-06 17:27:39'),
(468,'App\\Models\\User',24,'flutter-mobile-app','06a699fb8b699955e52791fde540f1f8abe83045f4088713303ebae4c47f3ddb','[\"*\"]','2026-05-06 17:39:16',NULL,'2026-05-06 17:32:55','2026-05-06 17:39:16'),
(469,'App\\Models\\User',52,'flutter-mobile-app','aac75ed62236c3d77ab14361547053c2b891086cc604a0b225ef12cbcc32a992','[\"*\"]','2026-05-06 17:50:10',NULL,'2026-05-06 17:39:40','2026-05-06 17:50:10'),
(470,'App\\Models\\User',52,'flutter-mobile-app','3da5e5f2805c1d1cb50598d1fbbc389b5323db41935ed1c5f0f9d4e55ad9c3fe','[\"*\"]','2026-05-06 20:39:41',NULL,'2026-05-06 20:36:03','2026-05-06 20:39:41'),
(471,'App\\Models\\User',54,'flutter-mobile-app','d2e9a80459a16a481346af09437662f441a8d24a469f446a7f10005e64713545','[\"*\"]','2026-05-06 21:05:47',NULL,'2026-05-06 21:01:13','2026-05-06 21:05:47'),
(472,'App\\Models\\User',57,'flutter-mobile-app','9c114d40fd195032fcaf176e9eb12d7b07fcf13351f5c8572bdbe0f54ca1e74f','[\"*\"]','2026-05-06 21:16:29',NULL,'2026-05-06 21:08:56','2026-05-06 21:16:29'),
(473,'App\\Models\\User',54,'flutter-mobile-app','ffb5609445a1fa1f114844200fbf0c22c02cbe0a151ce9521d79616acda56bc9','[\"*\"]','2026-05-06 22:58:34',NULL,'2026-05-06 21:16:44','2026-05-06 22:58:34'),
(474,'App\\Models\\User',24,'flutter-mobile-app','4ac1065290d69cc4bf9b608f256c179e925d89ef64300e74f6cc40db346d31aa','[\"*\"]','2026-05-06 22:59:14',NULL,'2026-05-06 22:59:06','2026-05-06 22:59:14'),
(475,'App\\Models\\User',54,'flutter-mobile-app','4cc252f46ab187de61ba755e46c49e8264b12103c828590efa5c7679c658eca8','[\"*\"]','2026-05-06 23:01:08',NULL,'2026-05-06 22:59:50','2026-05-06 23:01:08'),
(476,'App\\Models\\User',52,'flutter-mobile-app','c7994dbb8d9089906db61dbb5ccb8fe7bee8f1090820e5d3f147043784a6138b','[\"*\"]','2026-05-06 23:01:31',NULL,'2026-05-06 23:01:29','2026-05-06 23:01:31'),
(477,'App\\Models\\User',52,'flutter-mobile-app','17eb5010038672b7d04bc513d2a43eb920e8745d44b523893496eef01d37d1fe','[\"*\"]','2026-05-06 23:17:07',NULL,'2026-05-06 23:14:22','2026-05-06 23:17:07'),
(478,'App\\Models\\User',33,'face-login','b9c9938485c4769868b768c20c2284ae64bb8c99e93b9ed72e13d162c5cbd5a6','[\"*\"]','2026-05-06 23:17:56',NULL,'2026-05-06 23:17:54','2026-05-06 23:17:56'),
(479,'App\\Models\\User',33,'face-login','14b9fa7580e978089431b0dd1102b4de0b8b92e437226536a95334f011bea783','[\"*\"]','2026-05-06 23:18:48',NULL,'2026-05-06 23:18:46','2026-05-06 23:18:48'),
(480,'App\\Models\\User',33,'face-login','53a108f9a0f73797ab493cf46451aefd20cfe19aa3e137148e6151cb8037303d','[\"*\"]','2026-05-06 23:19:23',NULL,'2026-05-06 23:19:21','2026-05-06 23:19:23'),
(481,'App\\Models\\User',24,'flutter-mobile-app','d16b881b7595c972e9efde11f5b71165175da41cc24f7a7b80a0281e5bdccc26','[\"*\"]','2026-05-06 23:20:00',NULL,'2026-05-06 23:19:41','2026-05-06 23:20:00'),
(482,'App\\Models\\User',33,'flutter-mobile-app','a60c346a2c9e7911bcbfbb1b48863269218bba6ab33d6904c90ca9441d89082e','[\"*\"]','2026-05-06 23:24:58',NULL,'2026-05-06 23:20:12','2026-05-06 23:24:58'),
(483,'App\\Models\\User',33,'flutter-mobile-app','14b9ce5c3d86a6808bf8a33776316c5ebb12afb2676b059271135a95286f09e8','[\"*\"]','2026-05-06 23:36:48',NULL,'2026-05-06 23:27:26','2026-05-06 23:36:48'),
(484,'App\\Models\\User',33,'face-login','d2923e5cc4382fe17ef6d6782b7789352947b3b0c4e38267835be21928579b0a','[\"*\"]','2026-05-06 23:39:50',NULL,'2026-05-06 23:37:12','2026-05-06 23:39:50'),
(485,'App\\Models\\User',33,'flutter-mobile-app','b3d285f3e16c5dc7776e90e64c4fc11278b8b4217b6f6a011ad6a954644aeb28','[\"*\"]','2026-05-06 23:40:19',NULL,'2026-05-06 23:40:16','2026-05-06 23:40:19'),
(486,'App\\Models\\User',59,'flutter-mobile-app','3477b34fe63f6c6480535512f43151ba8a180be1bc26b2896f08c5c818ea8d99','[\"*\"]','2026-05-06 23:52:53',NULL,'2026-05-06 23:41:44','2026-05-06 23:52:53'),
(487,'App\\Models\\User',33,'flutter-mobile-app','dfb4d7ec49dde1959a6e99298cef6cb7d9087f09b8f81cc5a6fac111e5e7d10b','[\"*\"]','2026-05-06 23:57:37',NULL,'2026-05-06 23:54:44','2026-05-06 23:57:37'),
(488,'App\\Models\\User',60,'flutter-mobile-app','59d9de419319e9be9486728d6c1d62650268a055d7621d6d86d8e76282f62dd8','[\"*\"]','2026-05-07 00:05:34',NULL,'2026-05-07 00:00:32','2026-05-07 00:05:34'),
(489,'App\\Models\\User',61,'flutter-mobile-app','378ba8373b311deec80a79974d0444e5af51531fd9977b0f6f233150b8b16f3a','[\"*\"]','2026-05-07 00:19:39',NULL,'2026-05-07 00:05:50','2026-05-07 00:19:39'),
(490,'App\\Models\\User',62,'flutter-mobile-app','8c7e0e89eb3d7aa37a2b0f2d03031cc8a3a407ce042eb65efff301de0d688c8f','[\"*\"]','2026-05-07 00:22:20',NULL,'2026-05-07 00:20:36','2026-05-07 00:22:20'),
(491,'App\\Models\\User',62,'flutter-mobile-app','9b47aeb1b80d550181a1c81b78716928e21b4a891dc5b52031b46d6f23b3522b','[\"*\"]','2026-05-07 00:23:31',NULL,'2026-05-07 00:22:41','2026-05-07 00:23:31'),
(492,'App\\Models\\User',62,'flutter-mobile-app','5ff840fafc73997b7f6a372b01ce7c2206cb2a8c5d3be68cb25b75b81f44c35c','[\"*\"]','2026-05-07 00:24:41',NULL,'2026-05-07 00:24:13','2026-05-07 00:24:41'),
(493,'App\\Models\\User',60,'flutter-mobile-app','a1fdac46b026c160fc58a80de918d9c599cc22a7965f8db6832b2188cf13db48','[\"*\"]','2026-05-07 00:28:11',NULL,'2026-05-07 00:25:10','2026-05-07 00:28:11'),
(494,'App\\Models\\User',52,'flutter-mobile-app','f7a553c3315a1407538f4c21accfd6286a3dc93b0f18e834f3bdb34d3db89794','[\"*\"]','2026-05-07 00:28:50',NULL,'2026-05-07 00:28:27','2026-05-07 00:28:50'),
(495,'App\\Models\\User',52,'flutter-mobile-app','0e853f9f72353b62d5ebd3784113b6d7d86def109953440df73d781ea79ca4e1','[\"*\"]','2026-05-07 00:30:10',NULL,'2026-05-07 00:29:50','2026-05-07 00:30:10'),
(496,'App\\Models\\User',24,'flutter-mobile-app','aebb717ecf36db6f24492d6ac464080a92c8a28a81bf1a99170773f633e02812','[\"*\"]','2026-05-07 00:31:21',NULL,'2026-05-07 00:30:23','2026-05-07 00:31:21'),
(497,'App\\Models\\User',54,'flutter-mobile-app','a69320e9c76653ef5edc04e6b7e68b9dc40ecd438f762343d5ec0c7a17a2789f','[\"*\"]','2026-05-07 00:31:46',NULL,'2026-05-07 00:31:44','2026-05-07 00:31:46');

/*Table structure for table `plans` */

DROP TABLE IF EXISTS `plans`;

CREATE TABLE `plans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class_id` int(11) DEFAULT NULL,
  `plan_name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `session_type` enum('unlimited','limited') NOT NULL DEFAULT 'unlimited',
  `sessions_count` int(11) DEFAULT 0,
  `expiry_value` int(11) DEFAULT 0,
  `expiry_unit` enum('weeks','months','years') DEFAULT 'months',
  `sessions_per_week` int(11) DEFAULT 0,
  `monthly_price` decimal(10,2) DEFAULT 0.00,
  `unlimited_flag` tinyint(1) DEFAULT 0,
  `billing_cycle` enum('monthly','quarterly','yearly') DEFAULT 'monthly',
  `active_flag` tinyint(1) DEFAULT 1,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `plans` */

insert  into `plans`(`id`,`class_id`,`plan_name`,`description`,`session_type`,`sessions_count`,`expiry_value`,`expiry_unit`,`sessions_per_week`,`monthly_price`,`unlimited_flag`,`billing_cycle`,`active_flag`,`created_at`,`updated_at`) values 
(1,NULL,'testing','this is a testing','unlimited',0,0,'months',0,100.00,0,'monthly',0,'2026-04-20','2026-04-20'),
(2,NULL,'testing again','testing 123','unlimited',0,0,'months',0,0.00,0,'monthly',0,'2026-04-20','2026-04-20'),
(3,NULL,'Beginner Class','Perfect for new students. Includes basic taekwondo training, foundational techniques, and access to group classes 2–3 times per week.','unlimited',0,0,'months',0,800.00,0,'monthly',0,'2026-04-20','2026-04-21'),
(4,NULL,'Intermediate Training','For students with basic experience. Includes advanced techniques, sparring sessions, and 3–5 classes per week with instructor guidance.','unlimited',0,0,'months',0,1200.00,0,'monthly',0,'2026-04-20','2026-04-21'),
(5,NULL,'Black Belt Program','Intensive training for dedicated students aiming for mastery. Unlimited classes, advanced sparring, and personalized coaching.','unlimited',0,0,'months',0,2000.00,0,'monthly',0,'2026-04-20','2026-04-21'),
(9,NULL,'Black Belt Mastery','Intensive training for advanced students preparing for competitions and black belt exams.','unlimited',0,0,'months',0,3000.00,0,'monthly',0,'2026-04-21','2026-04-21'),
(10,NULL,'Weekend Training','Training available only on weekends for busy students.','limited',8,1,'months',0,1000.00,0,'monthly',0,'2026-04-21','2026-04-21'),
(11,NULL,'Lifetime Access','One-time premium membership with unlimited access to all classes forever.','unlimited',0,12,'months',0,5000.00,0,'yearly',0,'2026-04-21','2026-04-21'),
(12,1,'beginner','beginner','limited',4,2,'months',0,1000.00,0,'monthly',1,'2026-04-21','2026-04-21'),
(13,14,'Basic','Learn fundamentals skills','limited',5,2,'months',0,500.00,0,'monthly',1,'2026-04-21','2026-04-21'),
(14,13,'Premium','More advance skills','limited',8,2,'months',0,1000.00,0,'monthly',1,'2026-04-21','2026-04-21'),
(15,6,'Premium','Acquired more skills','limited',8,1,'months',0,1499.97,0,'monthly',1,'2026-04-23','2026-04-23'),
(16,15,'Elite','Acquired fundamentals skills','limited',8,2,'months',0,500.00,0,'monthly',1,'2026-05-07','2026-05-07');

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

insert  into `role_permissions`(`id`,`role`,`module`,`can_view`,`can_create`,`can_edit`,`can_delete`) values 
(2,'staff','dashboard',1,1,0,0),
(3,'staff','students',1,0,0,0),
(4,'staff','parents',1,0,0,0),
(5,'staff','instructors',1,0,0,0),
(6,'staff','classes',1,0,0,0),
(7,'staff','branches',1,0,0,0),
(8,'staff','attendance',1,0,0,0),
(9,'staff','billing',1,0,0,0),
(10,'staff','certificates',1,0,0,0),
(11,'staff','competitions',1,0,0,0),
(12,'staff','announcements',1,0,0,0),
(13,'staff','chat',1,0,0,0),
(14,'staff','reports',1,0,0,0),
(15,'staff','users',0,1,0,0),
(16,'staff','settings',0,1,1,0),
(17,'admin','dashboard',1,1,1,1),
(18,'admin','students',1,1,1,1),
(19,'admin','parents',1,1,1,1),
(20,'admin','instructors',1,1,1,1),
(21,'admin','classes',1,1,1,1),
(22,'admin','branches',1,1,1,1),
(23,'admin','attendance',1,1,1,1),
(24,'admin','billing',1,1,1,1),
(25,'admin','certificates',1,1,1,1),
(26,'admin','competitions',1,1,1,1),
(27,'admin','announcements',1,1,1,1),
(28,'admin','chat',1,1,1,1),
(29,'admin','reports',1,1,1,1),
(30,'admin','users',1,1,1,1),
(31,'admin','settings',1,1,1,1);

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

insert  into `sessions`(`id`,`user_id`,`ip_address`,`user_agent`,`payload`,`last_activity`) values 
('c6tVMuAwFMVv9eKyWghP6sOn5cQIVQFU8ghudJUi',7,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0','YTo1OntzOjY6Il90b2tlbiI7czo0MDoieHpYNGJZeVRQcVN4ckNIVGlpWEpPWEpPUnVZUHlFQ0xLOVFSWndIRCI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjI5OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYmlsbGluZyI7czo1OiJyb3V0ZSI7czoxMzoiYmlsbGluZy5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjc7fQ==',1778084362);

/*Table structure for table `skill_checklist` */

DROP TABLE IF EXISTS `skill_checklist`;

CREATE TABLE `skill_checklist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `belt_level` varchar(50) NOT NULL,
  `skill_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `skill_checklist` */

insert  into `skill_checklist`(`id`,`belt_level`,`skill_name`,`description`) values 
(1,'White','Basic Stance',NULL),
(2,'White','Basic Punches',NULL),
(3,'White','Front Kick',NULL),
(4,'White','Forms 1 (Taegeuk 1)',NULL),
(5,'White-Yellow','Turning Kick Basics',NULL),
(6,'White-Yellow','Block Combinations',NULL),
(7,'White-Yellow','Basic Footwork',NULL),
(8,'White-Yellow','Forms 1 Review',NULL),
(9,'Yellow','Side Kick',NULL),
(10,'Yellow','Roundhouse Kick',NULL),
(11,'Yellow','Forms 2 (Taegeuk 2)',NULL),
(12,'Yellow','Sparring Basics',NULL),
(13,'Yellow-Green','Back Kick',NULL),
(14,'Yellow-Green','Combination Kicks',NULL),
(15,'Yellow-Green','Basic Breaking',NULL),
(16,'Yellow-Green','Forms 2 Review',NULL),
(17,'Green','Spinning Hook Kick',NULL),
(18,'Green','Forms 3 (Taegeuk 3)',NULL),
(19,'Green','Controlled Sparring',NULL),
(20,'Green','Board Breaking',NULL),
(21,'Green-Blue','Jump Front Kick',NULL),
(22,'Green-Blue','Advanced Footwork',NULL),
(23,'Green-Blue','Breaking Combinations',NULL),
(24,'Green-Blue','Forms 3 Review',NULL),
(25,'Blue','Forms 4 (Taegeuk 4)',NULL),
(26,'Blue','Advanced Sparring',NULL),
(27,'Blue','Self Defense Basics',NULL),
(28,'Blue','Jump Kicks',NULL),
(29,'Blue-Red','Jump Spinning Kick',NULL),
(30,'Blue-Red','Advanced Self Defense',NULL),
(31,'Blue-Red','Competition Sparring Basics',NULL),
(32,'Blue-Red','Forms 4 Review',NULL),
(33,'Red','Forms 5 (Taegeuk 5)',NULL),
(34,'Red','Full Sparring',NULL),
(35,'Red','Advanced Breaking',NULL),
(36,'Red','Poomsae Competition',NULL),
(37,'Red-Black','Forms 6 (Taegeuk 6)',NULL),
(38,'Red-Black','Black Belt Prep Sparring',NULL),
(39,'Red-Black','Advanced Poomsae',NULL),
(40,'Red-Black','Breaking Mastery',NULL),
(41,'Black','Black Belt Poomsae',NULL),
(42,'Black','Competition Sparring',NULL),
(43,'Black','Advanced Self Defense',NULL),
(44,'Black','Teaching Basics',NULL);

/*Table structure for table `student_evaluations` */

DROP TABLE IF EXISTS `student_evaluations`;

CREATE TABLE `student_evaluations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `instructor_id` int(11) NOT NULL,
  `class_id` int(11) DEFAULT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `student_evaluations` */

insert  into `student_evaluations`(`id`,`student_id`,`instructor_id`,`class_id`,`evaluation_date`,`technique_score`,`discipline_score`,`fitness_score`,`sparring_score`,`notes`,`belt_ready_flag`) values 
(1,5,6,6,'2026-04-27',7,8,6,5,'Good progress this week.',0),
(2,21,6,NULL,'2026-04-27',5,5,5,5,NULL,0),
(3,15,6,NULL,'2026-04-27',7,8,7,7,'Good job',0),
(4,15,6,NULL,'2026-04-27',8,8,8,8,'Good work',1),
(5,15,6,NULL,'2026-04-27',7,7,7,5,NULL,1),
(6,15,6,NULL,'2026-04-28',10,10,10,10,'Good job',0),
(7,20,6,NULL,'2026-04-28',9,9,8,10,'Good Job',0),
(8,23,6,NULL,'2026-04-28',10,10,10,10,NULL,0),
(9,20,6,NULL,'2026-04-28',10,10,10,10,'Good Job',1),
(10,20,6,NULL,'2026-04-28',9,9,8,10,'Good Job',1),
(11,20,6,NULL,'2026-04-28',9,9,8,10,'Good Job',1),
(12,20,6,NULL,'2026-04-28',9,9,8,10,'Good Job',1),
(13,20,6,NULL,'2026-04-28',9,9,8,10,'Good Job',1),
(14,9,6,NULL,'2026-04-28',10,10,10,10,NULL,0),
(15,9,6,NULL,'2026-04-28',10,10,10,10,NULL,1),
(16,9,6,NULL,'2026-04-28',10,10,10,10,NULL,1),
(17,21,6,NULL,'2026-04-28',10,10,10,10,NULL,0),
(18,26,6,NULL,'2026-04-29',7,6,8,5,'Pretty auntie',0),
(19,20,6,NULL,'2026-04-29',9,9,8,10,'Good Job',0),
(20,20,6,NULL,'2026-04-29',10,8,9,5,'Pretty Auntie',1),
(21,9,6,NULL,'2026-04-29',9,10,10,10,NULL,0),
(22,20,6,NULL,'2026-04-29',10,10,10,10,NULL,1),
(23,26,6,NULL,'2026-04-29',10,10,10,10,NULL,1),
(24,26,6,NULL,'2026-04-29',10,10,10,10,NULL,1),
(25,20,6,NULL,'2026-04-29',10,10,10,10,NULL,1),
(26,26,6,NULL,'2026-04-29',10,10,10,10,NULL,1),
(27,20,6,NULL,'2026-04-30',10,10,10,10,'Good Work',0),
(28,20,6,NULL,'2026-04-30',10,10,10,10,'Good Job',1),
(29,20,6,NULL,'2026-04-30',0,0,0,0,NULL,0),
(30,20,6,NULL,'2026-04-30',10,10,10,10,'Good Job',1),
(31,28,6,NULL,'2026-04-30',10,10,10,9,'Good job',0),
(32,27,6,NULL,'2026-04-30',10,10,10,10,NULL,0),
(33,21,6,NULL,'2026-04-30',10,10,10,10,'Good Job',0),
(34,20,6,NULL,'2026-05-04',10,10,10,10,NULL,0),
(35,28,6,NULL,'2026-05-05',7,5,7,5,NULL,0),
(36,26,6,NULL,'2026-05-05',10,10,10,10,NULL,0),
(37,27,6,NULL,'2026-05-05',10,10,10,10,NULL,0),
(38,28,6,NULL,'2026-05-05',10,10,10,10,NULL,1),
(39,33,10,NULL,'2026-05-07',8,8,8,5,'Good Job',0),
(40,27,6,NULL,'2026-05-07',8,7,7,5,'Good Job',0);

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
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `student_skill_progress` */

insert  into `student_skill_progress`(`id`,`student_id`,`skill_id`,`instructor_id`,`status`,`checked_at`) values 
(1,5,9,6,'completed','2026-04-27 21:44:57'),
(2,5,10,6,'completed','2026-04-27 21:44:57'),
(3,5,11,6,'in_progress','2026-04-27 21:44:57'),
(4,5,12,6,'in_progress','2026-04-27 21:44:57'),
(61,28,21,6,'completed','2026-05-05 22:20:56'),
(62,28,22,6,'completed','2026-05-05 22:20:56'),
(63,28,23,6,'completed','2026-05-05 22:20:56'),
(64,28,24,6,'completed','2026-05-05 22:20:56'),
(69,33,9,10,'completed','2026-05-07 00:25:44'),
(70,33,10,10,'completed','2026-05-07 00:25:45'),
(71,33,11,10,'in_progress','2026-05-07 00:25:45'),
(72,33,12,10,'in_progress','2026-05-07 00:25:45'),
(73,27,25,6,'completed','2026-05-07 00:30:49'),
(74,27,26,6,'in_progress','2026-05-07 00:30:49'),
(75,27,27,6,'completed','2026-05-07 00:30:49'),
(76,27,28,6,'in_progress','2026-05-07 00:30:49');

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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `student_subscriptions` */

insert  into `student_subscriptions`(`id`,`student_id`,`plan_id`,`start_date`,`end_date`,`status`,`auto_renew_flag`) values 
(2,19,12,'2026-04-01','2026-04-30','active',0),
(3,18,12,'2026-04-01','2026-04-30','active',0),
(4,25,13,'2026-04-01','2026-04-30','active',0),
(5,15,14,'2026-04-01','2026-04-30','active',0),
(6,23,15,'2026-04-01','2026-04-30','active',0),
(7,28,14,'2026-04-01','2026-04-30','active',0),
(8,27,14,'2026-04-01','2026-04-30','active',0),
(9,32,14,'2026-05-01','2026-05-31','active',0),
(10,33,16,'2026-05-01','2026-05-31','active',0);

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
  `luxand_person_id` varchar(100) DEFAULT NULL,
  `current_belt` varchar(50) DEFAULT NULL,
  `join_date` date NOT NULL,
  `status` enum('active','inactive','suspended') DEFAULT 'active',
  `medical_notes` text DEFAULT NULL,
  `allergies` text DEFAULT NULL,
  `emergency_contact_name` varchar(150) DEFAULT NULL,
  `emergency_contact_mobile` varchar(20) DEFAULT NULL,
  `primary_parent_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_promoted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `student_code` (`student_code`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `students` */

insert  into `students`(`id`,`user_id`,`branch_id`,`student_code`,`first_name`,`last_name`,`middle_name`,`birthdate`,`gender`,`photo_url`,`face_photo`,`luxand_person_id`,`current_belt`,`join_date`,`status`,`medical_notes`,`allergies`,`emergency_contact_name`,`emergency_contact_mobile`,`primary_parent_id`,`created_at`,`last_promoted_at`) values 
(1,NULL,1,'tkd-00001','Mechelle','Stoneman','Malakas','2026-03-13','male',NULL,NULL,NULL,'1','2026-03-13','active',NULL,NULL,'Tyesha Freitag','0915-123-4567',10,'2026-03-13 10:42:11',NULL),
(2,NULL,1,'tkd-00002','Hyunwoo','Lee',NULL,'2026-04-07','male',NULL,NULL,NULL,'4','2026-03-24','active','testing','testing','dwajhduwyhaddfiuopghwascliugjkbhvdawbkljvdfwa','09499374690',10,'2026-03-24 15:49:41',NULL),
(4,NULL,4,'tkd-00004','Nicky','Minaj',NULL,'2005-03-24','female',NULL,NULL,NULL,'10','2026-03-24','active','Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.','blha blah blha blha','eddu manzano','09207020903',17,'2026-03-24 16:25:33',NULL),
(5,NULL,1,NULL,'Juna','reyes',NULL,'2003-06-19','female',NULL,NULL,NULL,'3','2026-03-27','active','fracture','N/A','Roberto Reyes','09287364831',22,'2026-03-27 12:01:12',NULL),
(6,NULL,1,NULL,'joselito','Reyes',NULL,'2002-03-12','male',NULL,NULL,NULL,'3','2026-03-28','active','N/A','N/A','Menard Cruz','09287364831',19,'2026-03-28 17:30:03',NULL),
(7,NULL,2,NULL,'Harith','Requestas',NULL,'2018-07-13','male',NULL,NULL,NULL,'3','2026-03-30','active','N/A','N/A','Menard Cruz','09287364831',10,'2026-03-30 20:42:57',NULL),
(8,NULL,1,NULL,'Melai','Alvior',NULL,'2015-02-03','female',NULL,NULL,NULL,'3','2026-03-30','active','N/A','N/A','Menard Cruz','09287364831',19,'2026-03-30 21:14:36',NULL),
(9,NULL,1,NULL,'Lenard','Cruz',NULL,'2015-06-24','male',NULL,NULL,NULL,'7','2026-03-30','active','N/A','N/A','Menard Cruz','09287364831',20,'2026-03-30 21:21:29','2026-04-29 22:54:04'),
(10,NULL,1,NULL,'Berto','Cruz',NULL,'2014-02-06','male',NULL,NULL,NULL,'7','2026-03-30','active','N/A','N/A','Menard Cruz','09287364831',10,'2026-03-30 21:25:32',NULL),
(11,29,1,'TKD-0CWXF','benedick1','Caber',NULL,'2014-02-20','male',NULL,NULL,NULL,'4','2026-03-30','active','N/A','N/A','Roberto Reyes','09287364831',16,'2026-03-30 13:48:47',NULL),
(12,30,1,'TKD-KDDI5','Cyril','maldives',NULL,'2012-03-30','female',NULL,NULL,NULL,'2','2026-03-30','active','N/A','N/A','Dwayne wade','09218734930',10,'2026-03-30 14:11:24',NULL),
(13,31,1,'TKD-RZDVA','Lester','Reyes',NULL,'2017-05-17','male',NULL,NULL,NULL,'7','2026-03-30','active','N/A','N/A','kelson dee','09283743892',16,'2026-03-30 14:21:45',NULL),
(15,33,1,'TKD-DPMSM','Triever','Estareja',NULL,'2015-11-27','male','student-photos/acqdKbye3MbWsOZH2xhrDmUyrqFstvpvWuFwGY9l.png','face-photos/tPDDXZS3cU7SPsqo7RvuywWpCKoX8KNMhHNduPbn.jpg','2726409','11','2026-03-31','active','N/A','N/A','Emmalyn Ruga','09283948172',16,'2026-03-31 14:35:10',NULL),
(17,35,5,'TKD-I9FFF','Dennis','Cruz',NULL,'2026-04-01','male',NULL,NULL,NULL,'1','2026-04-01','active',NULL,NULL,'Mr. Asimo','09399012345',20,'2026-04-01 01:07:01',NULL),
(18,36,1,'26-00001','Princess Dianne','Garay',NULL,'2003-08-24','female',NULL,NULL,NULL,'11','2026-04-01','active',NULL,NULL,'Mr. Asimo','0999999999',16,'2026-04-01 01:47:23',NULL),
(19,37,2,'26-00002','Nathaniel','Custodio',NULL,'2003-08-03','male',NULL,NULL,NULL,'11','2026-04-01','active',NULL,NULL,'Mr. Asimo','0999999999',17,'2026-04-01 01:51:02',NULL),
(20,40,1,'TKD-WRNZF','edrich','carmello',NULL,'2015-02-11','male',NULL,'face-photos/SqafxfM774GH9SJOwvD4dfwtY3uQcG4hIBxcEE6z.jpg','2723962','8','2026-04-10','active','N/A','N/A','Jonas Lopez','09182739482',16,'2026-04-10 15:05:06','2026-05-04 13:02:59'),
(21,41,1,'TKD-4V3TK','kawhi','leonard',NULL,'2016-02-09','male',NULL,NULL,NULL,'4','2026-04-10','active','N/A','N/A','Mikey Delacruz','09281762836',10,'2026-04-10 15:07:37','2026-04-30 23:27:13'),
(22,42,1,'TKD-DHFQ3','Daniel','Vibar',NULL,'2010-06-15','male',NULL,'face-photos/B2odymx5CmaXcfAdZrWIR6lUBu3Ky5EHuriLtZdE.jpg','2725405','8','2026-04-13','active','N/A','N/A','Maria cruz','09298712398',16,'2026-04-13 22:10:48',NULL),
(23,43,1,'TKD-VARZE','Donald','Crumps',NULL,'1995-02-16','male',NULL,NULL,NULL,'11','2026-04-13','active','Asthma','Food allergy','Ana Crumps','09281692301',18,'2026-04-13 22:37:37','2026-04-28 22:56:02'),
(24,46,1,'TKD-E7PP4','Rene','Baterbonia',NULL,'2010-10-11','male',NULL,NULL,NULL,'2','2026-04-20','active','N/A','N/A','Donny Baterbonia','09291019273',45,'2026-04-20 11:01:12',NULL),
(25,48,1,'26-00003','Jomar','Bentonisis',NULL,'2001-10-23','male',NULL,NULL,NULL,'9','2026-04-21','active','N/A','N/A','Vivian Bentonisis','09180321091',47,'2026-04-21 17:14:49',NULL),
(26,50,1,'26-00004','mark','reyes',NULL,'2008-07-10','male',NULL,'face-photos/Asswl2IatNx4YrVJAbFAXFKj5X9YmW7CDkY9vGel.jpg','2743850','7','2026-04-29','active','N/A','N/A','keneth reyes','09382010078',44,'2026-04-29 09:12:47','2026-05-05 22:18:59'),
(27,52,1,'26-00005','jonard','dela Cruz',NULL,'2012-05-16','male','student-photos/t31BClTFy6bOZ7M9iYacrPSEPSUSoN0idHBstd39.jpg','face-photos/XlROoLLatjJqWOp6mh2ae2WN17CSOOpUOXVM9ajM.jpg','2750574','7','2026-04-29','active','N/A','N/A','maria dela cruz','09123456789',54,'2026-04-29 09:34:26','2026-05-05 22:20:06'),
(28,53,1,'26-00006','juan','dela Cruz',NULL,'2011-02-10','male',NULL,'face-photos/bkiJcWapLbGEOZJxPkguGxLrgP95h3gnpIOBoaI8.jpg','2743864','6','2026-04-29','active','N/A','N/A','maria dela cruz','09384952937',54,'2026-04-29 09:37:42','2026-04-30 23:10:13'),
(29,55,1,'26-00007','Carlo','dela cruz',NULL,'2010-10-12','male','student-photos/elXPYm0EeeqywEnvitRi7T5Fo3pi49nTsA86Hpkb.png',NULL,NULL,'3','2026-05-06','active','N/A','N/A','Rowena dela cruz','09882019032',54,'2026-05-06 20:54:51',NULL),
(30,56,1,'26-00008','Johnny','dela cruz',NULL,'2017-02-01','male','student-photos/2YsqpLPanp2ADblbjcuFmL6CI56Y4EGwS9ytxktB.png',NULL,NULL,'3','2026-05-06','active','N/A','N/A','Rowena dela cruz','09872831927',54,'2026-05-06 21:05:20',NULL),
(31,58,1,'26-00009','Bernard','dela cruz',NULL,'2012-06-12','male','student-photos/Cd0DyqUnOJTjKoNt0FC9eixOl0G8EStHMzDOzQy0.png',NULL,NULL,'4','2026-05-06','active','N/A','N/A','Rowena','09873782901',54,'2026-05-06 21:16:04',NULL),
(32,59,1,'26-00010','joner','dela cruz',NULL,'2016-06-29','male','student-photos/YRhg80mhtsWXwRhp4rvLZ6g1llujoqoOiOGuGuhd.jpg',NULL,NULL,'4','2026-05-06','active','N/A','N/A','Rowena guanzon','09872039013',18,'2026-05-06 22:53:39',NULL),
(33,62,2,'26-00011','Caryl','Santos',NULL,'2016-01-12','female','student-photos/iFX3c49VJQOtOZLEWlYPVQlB40DMcJJmZ7WFeT2d.jpg',NULL,NULL,'3','2026-05-07','active','N/A','N/A','Annie Sanntos','09873902910',20,'2026-05-07 00:09:51',NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `users` */

insert  into `users`(`id`,`branch_id`,`role`,`username`,`fname`,`lname`,`email`,`mobile`,`password`,`photo_url`,`status`,`last_login_at`,`created_at`,`updated_at`) values 
(7,2,'admin','1234admin','Nataniel','Herras','testing@gmail.com','09499333000','$2y$12$93a4KdYWPG1OSuQe0DtOyOzD7dW5HLaVLEMslAQGuV8Hq.knHl9Dq','profile-photos/1774326137_h9uoElzpCo.png',1,'2026-05-06 20:52:45','2026-03-03 06:58:17','2026-05-06 20:52:45'),
(8,2,'staff','admin','Princess','Valdez','Valdez@gmail.com','09444444444','$2y$12$TX1QJw1.7///Sj0I93nSgOpr2rCLkDGo6Am.sPbOxtIYW3SSmxPTm','profile-photos/1775534251_LJ7GSmkN6T.jpg',1,'2026-04-12 15:31:46','2026-03-03 07:02:26','2026-04-12 15:31:46'),
(10,1,'parent','parent1234','Cletus','Christopher','magulang123@gmail.com','09329329939','$2y$12$I8ofrlo50mdWeSWCOxac8.7T4AKyJILkquPZJS5KqxrR7i4AoLP5q',NULL,1,'2026-03-11 03:58:31','2026-03-10 08:48:46','2026-03-25 02:19:21'),
(11,1,'instructor','jdoe','John','Doe','jdoe@example.com','09171234567','hashed_pw1','',0,NULL,'2026-03-17 11:55:06','2026-03-18 16:57:47'),
(12,1,'instructor','asmith','Alice','Smith','asmith@example.com','09181234567','hashed_pw2','',0,NULL,'2026-03-17 11:55:06','2026-03-18 16:57:46'),
(13,2,'instructor','bchan','Brian','Chan','bchan@example.com','09191234567','hashed_pw3','',0,NULL,'2026-03-17 11:55:06','2026-03-18 16:57:46'),
(14,2,'instructor','cmendoza','Carla','Mendoza','cmendoza@example.com','09201234567','hashed_pw4','',0,NULL,'2026-03-17 11:55:06','2026-03-18 16:57:45'),
(15,3,'instructor','dlee','David','Lee','dlee@example.com','09211234567','hashed_pw5','',0,NULL,'2026-03-17 11:55:06','2026-03-18 16:57:43'),
(16,1,'parent','msantos','Maria','Santos','msantos@example.com','09170000046','hashed_pw46','',0,NULL,'2026-03-17 12:05:55','2026-03-18 16:57:50'),
(17,1,'parent','jcruz','Jose','Cruz','jcruz@example.com','09170000047','hashed_pw47','',0,NULL,'2026-03-17 12:05:55','2026-03-18 16:57:42'),
(18,2,'parent','atan','Anna','Tan','atan@example.com','09170000048','hashed_pw48','',0,NULL,'2026-03-17 12:05:55','2026-03-18 16:57:43'),
(19,2,'parent','cgarcia','Carlos','Garcia','cgarcia@example.com','09170000049','hashed_pw49','',0,NULL,'2026-03-17 12:05:55','2026-03-18 16:57:41'),
(20,3,'parent','glee','Grace','Lee','glee@example.com','09170000050','hashed_pw50','',0,NULL,'2026-03-17 12:05:55','2026-03-18 16:57:41'),
(21,5,'instructor','dwadwadawd','Lady Joy','Porrs','porras@porrs','09171234567','$2y$12$dvFe7NxBRoycJBI22wY0eenI/EWHoeYRqpGnls1u9tTvOOEZcq5YO',NULL,1,NULL,'2026-03-24 17:11:38','2026-03-25 03:09:32'),
(22,1,'parent','','Roberto','Magdangal','roberto@gmail.com','09876521465','$2y$12$aFdvkK8AAqqxbzujur.N2umCcB6gdPBYm7wRrSBBw7EtBkZZCNAlW',NULL,1,NULL,'2026-03-26 13:29:58','2026-03-26 13:29:58'),
(23,1,'parent','','Julious','Magdangal','magdangal@gmail.com','09876521465','$2y$12$QOXNjpOolI3hwi5b7HMyRu3Fm51dRzSSvySi4skxWUWEHZwQH2jxS',NULL,1,NULL,'2026-03-26 13:38:56','2026-04-06 10:14:35'),
(24,1,'instructor','Draken','Eduard','Estareja','eduardestarejajr@gmail.com','09826717854','$2y$12$cmVhWkunC.vtJXlnp9s7aem1.OuqCGbF5nmSL.nUnYK2VO84Nb9i.','/storage/instructor_photos/eFHVHpiUhXgepXezoIHD6JAeoLceKRq0P27mIVYK.png',1,NULL,'2026-03-26 13:40:09','2026-05-05 23:04:36'),
(25,2,'instructor','janjan','john','alvior','john@gmail.com','09874783672','$2y$12$ZFbpX2RZJy2dRBGBp1r0AeJ.cZY8A0TDSr0dYJKNONBnoM03/sgVm',NULL,1,NULL,'2026-03-26 14:34:39','2026-03-26 14:34:39'),
(26,3,'instructor','everr','triever','ruga','triever@gmail.com','0987462713','$2y$12$Q1iuc99JEMX7.4f4aofFM.D8b3OS7BtCkHnt5z2auYJalZyadhMcG',NULL,1,NULL,'2026-03-26 15:10:13','2026-03-26 15:10:13'),
(27,1,'instructor','micah','Mica','Barrientos','mica@gmail.com','0987462713','$2y$12$UOzLdBD3Sw1j0hb6ADHCyuCPIyETKlGivemR0mX47SOXlOqkAz4Ue',NULL,1,NULL,'2026-03-27 15:28:29','2026-03-27 15:28:29'),
(28,1,'','benedick@gmail.com','benedick','Caber','benedick@gmail.com',NULL,'$2y$12$bAIdXMSW1Rf2njYkCk6Heunh.4Hb8935tQvAvijlSKaE0zii1zRKW',NULL,0,NULL,'2026-03-30 13:34:47','2026-03-30 13:34:47'),
(29,1,'','ben@gmail.com','benedick1','Caber','ben@gmail.com',NULL,'$2y$12$ARrJNOobw2B0k2vMT0LxDumeBtYQo3hcmjHWT82tQ7WRQkGU3rLw2',NULL,0,NULL,'2026-03-30 13:48:47','2026-03-30 13:48:47'),
(30,1,'','cyril@gmail.com','Cyril','maldives','cyril@gmail.com',NULL,'$2y$12$.eP1IrTKEj/ZM9/bcKxED.dnybf4KT163gFT42sb/XtmJEEwvFUjC',NULL,0,NULL,'2026-03-30 14:11:24','2026-03-30 14:11:24'),
(31,1,'student','lester@gmail.com','Lester','Reyes','lester@gmail.com',NULL,'$2y$12$jLU9r/ASwj.R.SAZe5TG3ewjdgNydYSDBdxH5KzJVQue6.DfMIHdO',NULL,0,NULL,'2026-03-30 14:21:45','2026-03-30 14:21:45'),
(33,1,'student','triever.estareja356','Triever','Estareja','triever2015@gmail.com',NULL,'$2y$12$ppjllEgwXbFYNJZO0FpvEO.Z0kV8Tkrwh56xIuDWKJuI1QxsWouWu','student-photos/acqdKbye3MbWsOZH2xhrDmUyrqFstvpvWuFwGY9l.png',0,NULL,'2026-03-31 14:35:10','2026-05-06 23:54:58'),
(34,7,'student','testing.testing968','testing','testing','testing123@gmail.com','0999999999','$2y$12$2UMyyNk/DrQhPdM/XKnFKeaqj42Yz/baLysGC8oobAU01ye2ttp82',NULL,1,NULL,'2026-04-01 01:00:00','2026-04-01 01:00:00'),
(36,1,'student','princess dianne.garay161','Princess Dianne','Garay','DiannePrincess@gmail.com','0999999999','$2y$12$v3zed601e19jfrowK.ZCg.jTpJ5fPoG00lnrXFZtmrt5w6J813RtC',NULL,1,NULL,'2026-04-01 01:47:22','2026-04-01 01:47:22'),
(37,2,'student','nathaniel.custodio815','Nathaniel','Custodio','Nathaniel@gmail.com','0999999999','$2y$12$7O.mtCLHVbwOjMTcdkTVPuBPfcFyyTgxm43Y7qCw/.I5f//Lf/xe.',NULL,1,NULL,'2026-04-01 01:51:02','2026-04-01 01:51:02'),
(38,5,'admin','','Garay','Princess','Princess@gmail.com','09473258232','$2y$12$9P5OQRWVYIpV8RRrZq3/QeDthaJLTTqhh20aVnl56DWf4pZ/4AjS2','profile-photos/1775030025_MHmDaGY8El.jpg',1,NULL,'2026-04-01 15:53:45','2026-04-01 15:53:45'),
(40,1,'student','edrich.carmello518','edrich','carmello','edrich@gmail.com',NULL,'$2y$12$HsY8NaUPBz7L.vbj95SjD.kk6y2LRYcMe128wMZ6Hkm7wRwHRuZyi',NULL,0,NULL,'2026-04-10 15:05:06','2026-04-10 15:05:06'),
(41,1,'student','kawhi.leonard705','kawhi','leonard','kawhi@gmail.com',NULL,'$2y$12$/tEKa.fQPt6Mtlg3Gk47ne7v1Yyx7XR6ukksn6R3c64nBhvmhG9GS',NULL,0,NULL,'2026-04-10 15:07:37','2026-04-10 15:07:37'),
(42,1,'student','daniel.vibar966','Daniel','Vibar','dandan@gmail.com',NULL,'$2y$12$gtTb0TQ7TeEKXgp8nhRx2uOOjHOYGWC92RUuj/KGa7i/.Zi5vpoXu',NULL,0,NULL,'2026-04-13 22:10:48','2026-04-13 22:10:48'),
(43,1,'student','donald.crumps922','Donald','Crumps','donald@gmail.com',NULL,'$2y$12$junng1rovqQmSKz1ksQKxuoYixb0Y6YGAvuye/R0tIlenMblgPvnW',NULL,0,NULL,'2026-04-13 22:37:36','2026-04-13 22:37:36'),
(44,1,'parent','','Peter','Pan','peter@gmail.com','09699623018','$2y$12$OfKnZ40ijMUPyUv3FGPMGuBcciOdWdkLp97x52.woFnA.YlQfMARe',NULL,1,NULL,'2026-04-15 22:13:21','2026-04-15 22:13:21'),
(45,1,'parent','','Donny','Baterbonia','donny@gmail.com','091010982037','$2y$12$g1jG4XUnA.J1L3RwR.9wAuXIMn.Z3a7lFyppoh9h8J45/0o/YN5NC',NULL,1,NULL,'2026-04-20 10:59:17','2026-04-20 10:59:17'),
(46,1,'student','rene.baterbonia543','Rene','Baterbonia','rene@gmail.com',NULL,'$2y$12$YcZYD5DAeMjfvnUDilfN9.TNC888gxujj5wCjY.KYOmqBJbxvXegC',NULL,0,NULL,'2026-04-20 11:01:12','2026-04-20 11:01:12'),
(47,1,'parent','','Vivian','Bentonisis','vivian@gmail.com','09109853927','$2y$12$JSpc/x4af2gfgEPPc001m.wuNhYl6iInKUaDBl/f3B.YTymQOdQ2m',NULL,1,NULL,'2026-04-21 17:13:48','2026-04-21 17:13:48'),
(48,1,'student','jomar.bentonisis430','Jomar','Bentonisis','jomar@gmail.com',NULL,'$2y$12$Z/9B0b9Pj0ZCnw9PDjxseOphhYUGHUZGvXYvOZkNa7LxfS13E7mW.',NULL,0,NULL,'2026-04-21 17:14:49','2026-04-21 17:14:49'),
(49,1,'parent','','Edwin','Mananabas','edwin@gmail.com','09698372017','$2y$12$W5wECJe1fw1hX14Bj0PBvefx.iXTJAVZezcyfkqhXsJLGFMhiLOFG',NULL,1,NULL,'2026-04-23 21:34:40','2026-04-23 21:34:40'),
(50,1,'student','mark.reyes436','mark','reyes','mark@gmail.com',NULL,'$2y$12$pBNSWoK9S3QJEnLOAcHA7eVN/9Z28h8zy664OEGydJSN/LT2oAyfC',NULL,0,NULL,'2026-04-29 09:12:47','2026-04-29 09:12:47'),
(52,1,'student','jonard.delacruz183','jonard','dela Cruz','jonard@gmail.com',NULL,'$2y$12$QaJzrTmMjc/9ouHn.bkw2uP2ctnSVJZEIjkQAe0mxDO5j8/UAv3XK','student-photos/t31BClTFy6bOZ7M9iYacrPSEPSUSoN0idHBstd39.jpg',0,NULL,'2026-04-29 09:34:26','2026-05-07 00:29:57'),
(53,1,'student','juan.delacruz113','juan','dela Cruz','juan@gmail.com',NULL,'$2y$12$KruwB16FcgvzsZczJijFSuo4uQNzAEZtLN4dKLYnXMsfsInJYu8JG',NULL,0,NULL,'2026-04-29 09:37:42','2026-04-29 09:37:42'),
(54,1,'parent','','Rowena','Guanzon','rowena@gmail.com','09123456789','$2y$12$OzXchDr6i3DlaBKsiy0h8uRD.VL3vbOFA2krkh/.rpmr/1rhWXFyC',NULL,1,NULL,'2026-04-29 20:35:16','2026-04-29 20:35:16'),
(55,1,'student','carlo.delacruz217','Carlo','dela cruz','carlo@gmail.com',NULL,'$2y$12$r2SBiuSpIVsHCl8iha9tbeRhf1Lu196HH.039LFQ5dVRp6gcgP7Om',NULL,0,NULL,'2026-05-06 20:54:51','2026-05-06 20:54:51'),
(56,1,'student','johnny.delacruz748','Johnny','dela cruz','johnny@gmail.com',NULL,'$2y$12$BOGu9JvSdXJmql/II25e4uOJ2nebXDvX9ZptWyTp391JZtBF7mzB.','student-photos/2YsqpLPanp2ADblbjcuFmL6CI56Y4EGwS9ytxktB.png',0,NULL,'2026-05-06 21:05:20','2026-05-06 21:05:20'),
(57,1,'parent','','Jona','dela cruz','jona@gmail.com','09283921843','$2y$12$rfgsbk8pf9j4DelaRM4nX.zT7zcSH4CD37irdY04Ny806MAeQtWtW',NULL,1,NULL,'2026-05-06 21:08:31','2026-05-06 21:08:31'),
(58,1,'student','bernard.delacruz269','Bernard','dela cruz','bernard@gmail.com',NULL,'$2y$12$Ehq5mpy3v4adOfAu7aP.J.vntq0EtKmKBTx3MBrVLIpYHOoykUzPi','student-photos/Cd0DyqUnOJTjKoNt0FC9eixOl0G8EStHMzDOzQy0.png',0,NULL,'2026-05-06 21:16:04','2026-05-06 21:16:04'),
(59,1,'student','joner.delacruz191','joner','dela cruz','joner@gmail.com',NULL,'$2y$12$kn.u5inD/yV6tu1V/Xlo0ehdbiqaTuD2elOslA22paNfkbr8hogyO','student-photos/YRhg80mhtsWXwRhp4rvLZ6g1llujoqoOiOGuGuhd.jpg',0,NULL,'2026-05-06 22:53:39','2026-05-06 23:47:43'),
(60,2,'instructor','Lee','Lee','Sin','lee@gmail.com','09860283764','$2y$12$1GxI6ghl.5g0rDkvq188D.vdO0qyhk2bw2yBQoYFoWJlqXwG7kN9e','/storage/instructor_photos/Mq0iF8YuY7XU43yGKLL12SEbNpvNXTFpFVd3zxPi.png',1,NULL,'2026-05-07 00:00:09','2026-05-07 00:00:41'),
(61,1,'parent','','Annie','Santos','annie@gmail.com','09387834785','$2y$12$LEJ7y6EKbmCcSE87EegewujSVeEaJfWudcgsx3vETM9c.jBZJ8BQi',NULL,1,NULL,'2026-05-07 00:05:29','2026-05-07 00:05:29'),
(62,2,'student','caryl.santos166','Caryl','Santos','caryl@gmail.com',NULL,'$2y$12$CWvSKSxOHd/kbyF58HTj.uaQnKZoyWl4jJr05t83DTQsloXApmLPm','student-photos/iFX3c49VJQOtOZLEWlYPVQlB40DMcJJmZ7WFeT2d.jpg',0,NULL,'2026-05-07 00:09:51','2026-05-07 00:22:03');

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
 `method` enum('face','qr','manual','face_scan','app','device') ,
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

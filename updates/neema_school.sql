/*
SQLyog Ultimate v12.09 (64 bit)
MySQL - 5.7.26 : Database - neema_school
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`neema_school` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `neema_school`;

/*Table structure for table `acc_coa_categories` */

DROP TABLE IF EXISTS `acc_coa_categories`;

CREATE TABLE `acc_coa_categories` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `lft` int(11) NOT NULL DEFAULT '0',
  `rgt` int(11) NOT NULL DEFAULT '0',
  `level` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(400) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '',
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `created_user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `created_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `tag_idx` (`published`),
  KEY `idx_left_right` (`lft`,`rgt`),
  KEY `idx_alias` (`slug`(100))
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `acc_coa_categories` */

insert  into `acc_coa_categories`(`id`,`parent_id`,`lft`,`rgt`,`level`,`title`,`slug`,`published`,`created_user_id`,`created_on`,`modified_on`) values (1,0,1,6,0,'Assets','assets',1,0,'2019-11-28 18:01:30','2019-11-28 18:02:14'),(2,1,2,3,1,'Fixed Assets','fixed-assets',1,0,'2019-11-28 18:01:48','2019-11-28 18:02:33'),(3,1,4,5,1,'Current Assets','current-assets',1,0,'2019-11-28 18:02:14','2019-11-28 18:02:14'),(4,0,7,8,0,'Liabilities','liabilities',1,0,'2019-11-29 09:54:53','2019-11-29 09:54:53'),(5,0,9,10,0,'Liabilities','liabilities-1',1,0,'2019-11-29 09:55:32','2019-11-29 09:55:32');

/*Table structure for table `acc_coa_master_categories` */

DROP TABLE IF EXISTS `acc_coa_master_categories`;

CREATE TABLE `acc_coa_master_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `created_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

/*Data for the table `acc_coa_master_categories` */

insert  into `acc_coa_master_categories`(`id`,`title`,`created_date`) values (1,'Assets','2019-11-27 16:59:00'),(2,'Liabilities','2019-11-27 16:59:05'),(3,'Incomes','2019-11-27 16:59:08'),(4,'Expenses','2019-11-27 16:59:11'),(5,'Charges and Taxes','2019-11-27 16:59:19');

/*Table structure for table `api_auths` */

DROP TABLE IF EXISTS `api_auths`;

CREATE TABLE `api_auths` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) CHARACTER SET ascii NOT NULL,
  `user_type` enum('staff','parent','student') NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `device_id` varchar(255) NOT NULL,
  `device_type` varchar(25) NOT NULL,
  `refresh_token` varchar(255) NOT NULL,
  `payload` varchar(255) NOT NULL,
  `created_on` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uuid_idx` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `api_auths` */

/*Table structure for table `attendence_type` */

DROP TABLE IF EXISTS `attendence_type`;

CREATE TABLE `attendence_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) DEFAULT NULL,
  `key_value` varchar(50) NOT NULL,
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Data for the table `attendence_type` */

insert  into `attendence_type`(`id`,`type`,`key_value`,`is_active`,`created_at`,`updated_at`) values (1,'Present','<b class=\"text text-success\">P</b>','yes','2016-06-23 23:56:37','0000-00-00 00:00:00'),(2,'Late With Excuse','<b class=\"text text-warning\">E</b>','no','2018-05-29 14:04:48','0000-00-00 00:00:00'),(3,'Late','<b class=\"text text-warning\">L</b>','yes','2016-06-23 23:57:28','0000-00-00 00:00:00'),(4,'Absent','<b class=\"text text-danger\">A</b>','yes','2016-10-11 17:20:40','0000-00-00 00:00:00'),(5,'Holiday','H','yes','2016-10-11 17:20:01','0000-00-00 00:00:00'),(6,'Half Day','<b class=\"text text-warning\">F</b>','yes','2016-06-23 23:57:28','0000-00-00 00:00:00');

/*Table structure for table `book_issues` */

DROP TABLE IF EXISTS `book_issues`;

CREATE TABLE `book_issues` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `book_id` int(11) DEFAULT NULL,
  `return_date` date DEFAULT NULL,
  `issue_date` date DEFAULT NULL,
  `issue_date_bs` varchar(255) NOT NULL,
  `return_date_bs` varchar(255) NOT NULL,
  `is_returned` int(11) DEFAULT '0',
  `member_id` int(11) DEFAULT NULL,
  `is_active` varchar(10) NOT NULL DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `book_issues` */

/*Table structure for table `books` */

DROP TABLE IF EXISTS `books`;

CREATE TABLE `books` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `book_title` varchar(100) NOT NULL,
  `book_no` varchar(50) NOT NULL,
  `isbn_no` varchar(100) NOT NULL,
  `subject` varchar(100) DEFAULT NULL,
  `rack_no` varchar(100) NOT NULL,
  `publish` varchar(100) DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `perunitcost` float(10,2) DEFAULT NULL,
  `postdate` date DEFAULT NULL,
  `postdate_bs` varchar(255) NOT NULL,
  `description` text,
  `available` varchar(10) DEFAULT 'yes',
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `books` */

/*Table structure for table `categories` */

DROP TABLE IF EXISTS `categories`;

CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(100) DEFAULT NULL,
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `categories` */

insert  into `categories`(`id`,`category`,`is_active`,`created_at`,`updated_at`) values (1,'Category 1','no','2019-11-21 11:38:29','0000-00-00 00:00:00'),(2,'Category 2','no','2019-11-21 11:38:36','0000-00-00 00:00:00');

/*Table structure for table `certificates` */

DROP TABLE IF EXISTS `certificates`;

CREATE TABLE `certificates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `certificate_name` varchar(100) NOT NULL,
  `certificate_text` text NOT NULL,
  `left_header` varchar(100) NOT NULL,
  `center_header` varchar(100) NOT NULL,
  `right_header` varchar(100) NOT NULL,
  `left_footer` varchar(100) NOT NULL,
  `right_footer` varchar(100) NOT NULL,
  `center_footer` varchar(100) NOT NULL,
  `background_image` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_for` tinyint(1) NOT NULL COMMENT '1 = staff, 2 = students',
  `status` tinyint(1) NOT NULL,
  `header_height` int(11) NOT NULL,
  `content_height` int(11) NOT NULL,
  `footer_height` int(11) NOT NULL,
  `content_width` int(11) NOT NULL,
  `enable_student_image` tinyint(1) NOT NULL COMMENT '0=no,1=yes',
  `enable_image_height` int(11) NOT NULL,
  `layout` tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `certificates` */

/*Table structure for table `class_sections` */

DROP TABLE IF EXISTS `class_sections`;

CREATE TABLE `class_sections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class_id` int(11) DEFAULT NULL,
  `section_id` int(11) DEFAULT NULL,
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `class_id` (`class_id`),
  KEY `section_id` (`section_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

/*Data for the table `class_sections` */

insert  into `class_sections`(`id`,`class_id`,`section_id`,`is_active`,`created_at`,`updated_at`) values (1,1,1,'no','2019-11-21 11:04:13','0000-00-00 00:00:00'),(2,1,2,'no','2019-11-21 11:04:13','0000-00-00 00:00:00'),(3,1,3,'no','2019-11-21 11:04:13','0000-00-00 00:00:00'),(4,1,4,'no','2019-11-21 11:04:13','0000-00-00 00:00:00'),(5,2,1,'no','2019-11-21 11:04:22','0000-00-00 00:00:00'),(6,2,2,'no','2019-11-21 11:04:22','0000-00-00 00:00:00'),(7,2,3,'no','2019-11-21 11:04:22','0000-00-00 00:00:00'),(8,2,4,'no','2019-11-21 11:04:22','0000-00-00 00:00:00');

/*Table structure for table `class_teacher` */

DROP TABLE IF EXISTS `class_teacher`;

CREATE TABLE `class_teacher` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `is_class_teacher` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `class_teacher` */

/*Table structure for table `classes` */

DROP TABLE IF EXISTS `classes`;

CREATE TABLE `classes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class` varchar(60) DEFAULT NULL,
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `classes` */

insert  into `classes`(`id`,`class`,`is_active`,`created_at`,`updated_at`) values (1,'1','no','2019-11-21 11:04:13','0000-00-00 00:00:00'),(2,'2','no','2019-11-21 11:04:22','0000-00-00 00:00:00');

/*Table structure for table `complaint` */

DROP TABLE IF EXISTS `complaint`;

CREATE TABLE `complaint` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `complaint_type` varchar(15) NOT NULL,
  `source` varchar(15) NOT NULL,
  `name` varchar(100) NOT NULL,
  `contact` varchar(15) NOT NULL,
  `email` varchar(200) NOT NULL,
  `date` date NOT NULL,
  `description` text NOT NULL,
  `action_taken` varchar(200) NOT NULL,
  `assigned` varchar(50) NOT NULL,
  `note` text NOT NULL,
  `image` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `complaint` */

/*Table structure for table `complaint_type` */

DROP TABLE IF EXISTS `complaint_type`;

CREATE TABLE `complaint_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `complaint_type` varchar(100) NOT NULL,
  `description` mediumtext NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `complaint_type` */

/*Table structure for table `content_for` */

DROP TABLE IF EXISTS `content_for`;

CREATE TABLE `content_for` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role` varchar(50) DEFAULT NULL,
  `content_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `content_id` (`content_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `content_for_ibfk_1` FOREIGN KEY (`content_id`) REFERENCES `contents` (`id`) ON DELETE CASCADE,
  CONSTRAINT `content_for_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `content_for` */

/*Table structure for table `contents` */

DROP TABLE IF EXISTS `contents`;

CREATE TABLE `contents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `is_public` varchar(10) DEFAULT 'No',
  `class_id` int(11) DEFAULT NULL,
  `cls_sec_id` int(10) NOT NULL,
  `file` varchar(250) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `note` text,
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `contents` */

/*Table structure for table `department` */

DROP TABLE IF EXISTS `department`;

CREATE TABLE `department` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `department_name` varchar(200) NOT NULL,
  `is_active` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `department` */

insert  into `department`(`id`,`department_name`,`is_active`) values (1,'Primary','yes'),(2,'Secondary','yes'),(3,'Admin','yes');

/*Table structure for table `dispatch_receive` */

DROP TABLE IF EXISTS `dispatch_receive`;

CREATE TABLE `dispatch_receive` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reference_no` varchar(50) NOT NULL,
  `to_title` varchar(100) NOT NULL,
  `address` varchar(500) NOT NULL,
  `note` varchar(500) NOT NULL,
  `from_title` varchar(200) NOT NULL,
  `date` varchar(20) NOT NULL,
  `image` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `type` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `dispatch_receive` */

/*Table structure for table `email_config` */

DROP TABLE IF EXISTS `email_config`;

CREATE TABLE `email_config` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `email_type` varchar(100) DEFAULT NULL,
  `smtpauth_type` enum('0','1') NOT NULL DEFAULT '0',
  `smtp_server` varchar(100) DEFAULT NULL,
  `smtp_port` varchar(100) DEFAULT NULL,
  `smtp_username` varchar(100) DEFAULT NULL,
  `smtp_password` varchar(100) DEFAULT NULL,
  `ssl_tls` varchar(100) DEFAULT NULL,
  `is_active` varchar(10) NOT NULL DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `email_config` */

/*Table structure for table `enquiry` */

DROP TABLE IF EXISTS `enquiry`;

CREATE TABLE `enquiry` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `contact` varchar(20) NOT NULL,
  `address` mediumtext NOT NULL,
  `reference` varchar(20) NOT NULL,
  `date` date NOT NULL,
  `description` varchar(500) NOT NULL,
  `follow_up_date` date NOT NULL,
  `note` mediumtext NOT NULL,
  `source` varchar(50) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `assigned` varchar(100) NOT NULL,
  `class` int(11) NOT NULL,
  `no_of_child` varchar(11) DEFAULT NULL,
  `status` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `enquiry` */

/*Table structure for table `enquiry_type` */

DROP TABLE IF EXISTS `enquiry_type`;

CREATE TABLE `enquiry_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `enquiry_type` varchar(100) NOT NULL,
  `description` mediumtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `enquiry_type` */

/*Table structure for table `events` */

DROP TABLE IF EXISTS `events`;

CREATE TABLE `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_title` varchar(200) NOT NULL,
  `event_description` varchar(300) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `start_date_bs` varchar(100) NOT NULL,
  `end_date_bs` varchar(100) NOT NULL,
  `event_type` varchar(100) NOT NULL,
  `event_color` varchar(200) NOT NULL,
  `event_for` varchar(100) NOT NULL,
  `is_active` varchar(100) NOT NULL,
  `image` varchar(250) NOT NULL,
  `is_holiday` enum('0','1') NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `events` */

/*Table structure for table `exam_results` */

DROP TABLE IF EXISTS `exam_results`;

CREATE TABLE `exam_results` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `attendence` varchar(10) NOT NULL,
  `exam_schedule_id` int(11) DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  `get_marks` float(10,2) DEFAULT NULL,
  `is_na` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `note` text,
  `is_active` varchar(255) DEFAULT 'no',
  `grade_id` int(11) NOT NULL,
  `exam_result_publish_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `exam_schedule_id` (`exam_schedule_id`),
  KEY `student_id` (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `exam_results` */

/*Table structure for table `exam_schedules` */

DROP TABLE IF EXISTS `exam_schedules`;

CREATE TABLE `exam_schedules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session_id` int(11) NOT NULL,
  `exam_id` int(11) DEFAULT NULL,
  `teacher_subject_id` int(11) DEFAULT NULL,
  `date_of_exam` date DEFAULT NULL,
  `date_of_exam_bs` varchar(255) NOT NULL,
  `start_to` varchar(50) DEFAULT NULL,
  `end_from` varchar(50) DEFAULT NULL,
  `room_no` varchar(50) DEFAULT NULL,
  `full_marks` int(11) DEFAULT NULL,
  `passing_marks` int(11) DEFAULT NULL,
  `note` text,
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `exam_publish_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `teacher_subject_id` (`teacher_subject_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `exam_schedules` */

/*Table structure for table `exam_weightage` */

DROP TABLE IF EXISTS `exam_weightage`;

CREATE TABLE `exam_weightage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `exam_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `weight_exam_id` int(11) NOT NULL,
  `weightage` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `exam_weightage` */

/*Table structure for table `exams` */

DROP TABLE IF EXISTS `exams`;

CREATE TABLE `exams` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) DEFAULT NULL,
  `sesion_id` int(11) NOT NULL,
  `note` text,
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `exams` */

/*Table structure for table `expense_head` */

DROP TABLE IF EXISTS `expense_head`;

CREATE TABLE `expense_head` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `exp_category` varchar(50) DEFAULT NULL,
  `description` text,
  `is_active` varchar(255) DEFAULT 'yes',
  `is_deleted` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `expense_head` */

/*Table structure for table `expenses` */

DROP TABLE IF EXISTS `expenses`;

CREATE TABLE `expenses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `exp_head_id` int(11) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `invoice_no` varchar(200) NOT NULL,
  `date` date DEFAULT NULL,
  `date_bs` varchar(255) NOT NULL,
  `amount` float(10,2) DEFAULT NULL,
  `documents` varchar(255) DEFAULT NULL,
  `note` text,
  `is_active` varchar(255) DEFAULT 'yes',
  `is_deleted` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `expenses` */

/*Table structure for table `fee_groups` */

DROP TABLE IF EXISTS `fee_groups`;

CREATE TABLE `fee_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) DEFAULT NULL,
  `bs_month` tinyint(3) unsigned NOT NULL,
  `is_system` int(1) NOT NULL DEFAULT '0',
  `description` text,
  `is_active` varchar(10) NOT NULL DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8;

/*Data for the table `fee_groups` */

insert  into `fee_groups`(`id`,`name`,`bs_month`,`is_system`,`description`,`is_active`,`created_at`) values (7,'Fee group 1',0,0,'','no','2019-11-21 11:43:20'),(8,'Fee Group 2',0,0,'','no','2019-11-21 11:43:27'),(9,'Transport A Baisakh fee',1,0,'Auto generated','no','2019-11-21 11:55:32'),(10,'Transport A Jestha fee',2,0,'Auto generated','no','2019-11-21 11:55:32'),(11,'Transport A Asar fee',3,0,'Auto generated','no','2019-11-21 11:55:32'),(12,'Transport A Shrawan fee',4,0,'Auto generated','no','2019-11-21 11:55:32'),(13,'Transport A Bhadra fee',5,0,'Auto generated','no','2019-11-21 11:55:32'),(14,'Transport A Ashwin fee',6,0,'Auto generated','no','2019-11-21 11:55:32'),(15,'Transport A Kartik fee',7,0,'Auto generated','no','2019-11-21 11:55:32'),(16,'Transport A Mangshir fee',8,0,'Auto generated','no','2019-11-21 11:55:32'),(17,'Transport A Poush fee',9,0,'Auto generated','no','2019-11-21 11:55:32'),(18,'Transport A Magh fee',10,0,'Auto generated','no','2019-11-21 11:55:32'),(19,'Transport A Falgun fee',11,0,'Auto generated','no','2019-11-21 11:55:32'),(20,'Transport A Chaitra fee',12,0,'Auto generated','no','2019-11-21 11:55:32'),(21,'Transport B Baisakh fee',1,0,'Auto generated','no','2019-11-21 11:55:32'),(22,'Transport B Jestha fee',2,0,'Auto generated','no','2019-11-21 11:55:32'),(23,'Transport B Asar fee',3,0,'Auto generated','no','2019-11-21 11:55:32'),(24,'Transport B Shrawan fee',4,0,'Auto generated','no','2019-11-21 11:55:32'),(25,'Transport B Bhadra fee',5,0,'Auto generated','no','2019-11-21 11:55:32'),(26,'Transport B Ashwin fee',6,0,'Auto generated','no','2019-11-21 11:55:32'),(27,'Transport B Kartik fee',7,0,'Auto generated','no','2019-11-21 11:55:32'),(28,'Transport B Mangshir fee',8,0,'Auto generated','no','2019-11-21 11:55:32'),(29,'Transport B Poush fee',9,0,'Auto generated','no','2019-11-21 11:55:32'),(30,'Transport B Magh fee',10,0,'Auto generated','no','2019-11-21 11:55:32'),(31,'Transport B Falgun fee',11,0,'Auto generated','no','2019-11-21 11:55:32'),(32,'Transport B Chaitra fee',12,0,'Auto generated','no','2019-11-21 11:55:32'),(33,'Transport C Baisakh fee',1,0,'Auto generated','no','2019-11-21 11:55:32'),(34,'Transport C Jestha fee',2,0,'Auto generated','no','2019-11-21 11:55:32'),(35,'Transport C Asar fee',3,0,'Auto generated','no','2019-11-21 11:55:32'),(36,'Transport C Shrawan fee',4,0,'Auto generated','no','2019-11-21 11:55:32'),(37,'Transport C Bhadra fee',5,0,'Auto generated','no','2019-11-21 11:55:32'),(38,'Transport C Ashwin fee',6,0,'Auto generated','no','2019-11-21 11:55:32'),(39,'Transport C Kartik fee',7,0,'Auto generated','no','2019-11-21 11:55:32'),(40,'Transport C Mangshir fee',8,0,'Auto generated','no','2019-11-21 11:55:32'),(41,'Transport C Poush fee',9,0,'Auto generated','no','2019-11-21 11:55:32'),(42,'Transport C Magh fee',10,0,'Auto generated','no','2019-11-21 11:55:32'),(43,'Transport C Falgun fee',11,0,'Auto generated','no','2019-11-21 11:55:32'),(44,'Transport C Chaitra fee',12,0,'Auto generated','no','2019-11-21 11:55:32');

/*Table structure for table `fee_groups_feetype` */

DROP TABLE IF EXISTS `fee_groups_feetype`;

CREATE TABLE `fee_groups_feetype` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fee_session_group_id` int(11) DEFAULT NULL,
  `fee_groups_id` int(11) DEFAULT NULL,
  `feetype_id` int(11) DEFAULT NULL,
  `session_id` int(11) DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `due_date_bs` varchar(255) NOT NULL,
  `due_day_bs` tinyint(3) unsigned NOT NULL,
  `due_month_bs` tinyint(3) unsigned NOT NULL,
  `due_year_bs` smallint(5) unsigned NOT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `is_active` varchar(10) NOT NULL DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fee_session_group_id` (`fee_session_group_id`),
  KEY `fee_groups_id` (`fee_groups_id`),
  KEY `feetype_id` (`feetype_id`),
  KEY `session_id` (`session_id`),
  CONSTRAINT `fee_groups_feetype_ibfk_1` FOREIGN KEY (`fee_session_group_id`) REFERENCES `fee_session_groups` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fee_groups_feetype_ibfk_2` FOREIGN KEY (`fee_groups_id`) REFERENCES `fee_groups` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fee_groups_feetype_ibfk_3` FOREIGN KEY (`feetype_id`) REFERENCES `feetype` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fee_groups_feetype_ibfk_4` FOREIGN KEY (`session_id`) REFERENCES `sessions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8;

/*Data for the table `fee_groups_feetype` */

insert  into `fee_groups_feetype`(`id`,`fee_session_group_id`,`fee_groups_id`,`feetype_id`,`session_id`,`due_date`,`due_date_bs`,`due_day_bs`,`due_month_bs`,`due_year_bs`,`amount`,`is_active`,`created_at`) values (1,7,7,8,14,'2019-11-30','',0,0,0,'2000.00','no','2019-11-21 11:43:47'),(2,7,7,9,14,'2019-11-30','',0,0,0,'500.00','no','2019-11-21 11:47:57'),(3,8,8,8,14,'2019-11-30','',0,0,0,'3000.00','no','2019-11-21 11:48:21'),(4,8,8,9,14,'2019-11-30','',0,0,0,'600.00','no','2019-11-21 11:48:33'),(5,9,9,10,14,'2019-04-14','2076-1-1',0,0,0,'400.00','no','2019-11-21 11:55:32'),(6,10,10,10,14,'2019-05-15','2076-2-1',0,0,0,'400.00','no','2019-11-21 11:55:32'),(7,11,11,10,14,'2019-06-16','2076-3-1',0,0,0,'400.00','no','2019-11-21 11:55:32'),(8,12,12,10,14,'2019-07-17','2076-4-1',0,0,0,'400.00','no','2019-11-21 11:55:32'),(9,13,13,10,14,'2019-08-18','2076-5-1',0,0,0,'400.00','no','2019-11-21 11:55:32'),(10,14,14,10,14,'2019-09-18','2076-6-1',0,0,0,'400.00','no','2019-11-21 11:55:32'),(11,15,15,10,14,'2019-10-18','2076-7-1',0,0,0,'400.00','no','2019-11-21 11:55:32'),(12,16,16,10,14,'2019-11-17','2076-8-1',0,0,0,'400.00','no','2019-11-21 11:55:32'),(13,17,17,10,14,'2019-12-17','2076-9-1',0,0,0,'400.00','no','2019-11-21 11:55:32'),(14,18,18,10,14,'2020-01-15','2076-10-1',0,0,0,'400.00','no','2019-11-21 11:55:32'),(15,19,19,10,14,'2020-02-13','2076-11-1',0,0,0,'400.00','no','2019-11-21 11:55:32'),(16,20,20,10,14,'2020-03-14','2076-12-1',0,0,0,'400.00','no','2019-11-21 11:55:32'),(17,21,21,10,14,'2019-04-14','2076-1-1',0,0,0,'500.00','no','2019-11-21 11:55:32'),(18,22,22,10,14,'2019-05-15','2076-2-1',0,0,0,'500.00','no','2019-11-21 11:55:32'),(19,23,23,10,14,'2019-06-16','2076-3-1',0,0,0,'500.00','no','2019-11-21 11:55:32'),(20,24,24,10,14,'2019-07-17','2076-4-1',0,0,0,'500.00','no','2019-11-21 11:55:32'),(21,25,25,10,14,'2019-08-18','2076-5-1',0,0,0,'500.00','no','2019-11-21 11:55:32'),(22,26,26,10,14,'2019-09-18','2076-6-1',0,0,0,'500.00','no','2019-11-21 11:55:32'),(23,27,27,10,14,'2019-10-18','2076-7-1',0,0,0,'500.00','no','2019-11-21 11:55:32'),(24,28,28,10,14,'2019-11-17','2076-8-1',0,0,0,'500.00','no','2019-11-21 11:55:32'),(25,29,29,10,14,'2019-12-17','2076-9-1',0,0,0,'500.00','no','2019-11-21 11:55:32'),(26,30,30,10,14,'2020-01-15','2076-10-1',0,0,0,'500.00','no','2019-11-21 11:55:32'),(27,31,31,10,14,'2020-02-13','2076-11-1',0,0,0,'500.00','no','2019-11-21 11:55:32'),(28,32,32,10,14,'2020-03-14','2076-12-1',0,0,0,'500.00','no','2019-11-21 11:55:32'),(29,33,33,10,14,'2019-04-14','2076-1-1',0,0,0,'600.00','no','2019-11-21 11:55:32'),(30,34,34,10,14,'2019-05-15','2076-2-1',0,0,0,'600.00','no','2019-11-21 11:55:32'),(31,35,35,10,14,'2019-06-16','2076-3-1',0,0,0,'600.00','no','2019-11-21 11:55:32'),(32,36,36,10,14,'2019-07-17','2076-4-1',0,0,0,'600.00','no','2019-11-21 11:55:32'),(33,37,37,10,14,'2019-08-18','2076-5-1',0,0,0,'600.00','no','2019-11-21 11:55:32'),(34,38,38,10,14,'2019-09-18','2076-6-1',0,0,0,'600.00','no','2019-11-21 11:55:32'),(35,39,39,10,14,'2019-10-18','2076-7-1',0,0,0,'600.00','no','2019-11-21 11:55:32'),(36,40,40,10,14,'2019-11-17','2076-8-1',0,0,0,'600.00','no','2019-11-21 11:55:32'),(37,41,41,10,14,'2019-12-17','2076-9-1',0,0,0,'600.00','no','2019-11-21 11:55:32'),(38,42,42,10,14,'2020-01-15','2076-10-1',0,0,0,'600.00','no','2019-11-21 11:55:32'),(39,43,43,10,14,'2020-02-13','2076-11-1',0,0,0,'600.00','no','2019-11-21 11:55:32'),(40,44,44,10,14,'2020-03-14','2076-12-1',0,0,0,'600.00','no','2019-11-21 11:55:32');

/*Table structure for table `fee_receipt_no` */

DROP TABLE IF EXISTS `fee_receipt_no`;

CREATE TABLE `fee_receipt_no` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `payment` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `fee_receipt_no` */

/*Table structure for table `fee_session_groups` */

DROP TABLE IF EXISTS `fee_session_groups`;

CREATE TABLE `fee_session_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fee_groups_id` int(11) DEFAULT NULL,
  `session_id` int(11) DEFAULT NULL,
  `is_active` varchar(10) NOT NULL DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fee_groups_id` (`fee_groups_id`),
  KEY `session_id` (`session_id`),
  CONSTRAINT `fee_session_groups_ibfk_1` FOREIGN KEY (`fee_groups_id`) REFERENCES `fee_groups` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fee_session_groups_ibfk_2` FOREIGN KEY (`session_id`) REFERENCES `sessions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8;

/*Data for the table `fee_session_groups` */

insert  into `fee_session_groups`(`id`,`fee_groups_id`,`session_id`,`is_active`,`created_at`) values (7,7,14,'no','2019-11-21 11:43:47'),(8,8,14,'no','2019-11-21 11:48:21'),(9,9,14,'no','2019-11-21 11:55:32'),(10,10,14,'no','2019-11-21 11:55:32'),(11,11,14,'no','2019-11-21 11:55:32'),(12,12,14,'no','2019-11-21 11:55:32'),(13,13,14,'no','2019-11-21 11:55:32'),(14,14,14,'no','2019-11-21 11:55:32'),(15,15,14,'no','2019-11-21 11:55:32'),(16,16,14,'no','2019-11-21 11:55:32'),(17,17,14,'no','2019-11-21 11:55:32'),(18,18,14,'no','2019-11-21 11:55:32'),(19,19,14,'no','2019-11-21 11:55:32'),(20,20,14,'no','2019-11-21 11:55:32'),(21,21,14,'no','2019-11-21 11:55:32'),(22,22,14,'no','2019-11-21 11:55:32'),(23,23,14,'no','2019-11-21 11:55:32'),(24,24,14,'no','2019-11-21 11:55:32'),(25,25,14,'no','2019-11-21 11:55:32'),(26,26,14,'no','2019-11-21 11:55:32'),(27,27,14,'no','2019-11-21 11:55:32'),(28,28,14,'no','2019-11-21 11:55:32'),(29,29,14,'no','2019-11-21 11:55:32'),(30,30,14,'no','2019-11-21 11:55:32'),(31,31,14,'no','2019-11-21 11:55:32'),(32,32,14,'no','2019-11-21 11:55:32'),(33,33,14,'no','2019-11-21 11:55:32'),(34,34,14,'no','2019-11-21 11:55:32'),(35,35,14,'no','2019-11-21 11:55:32'),(36,36,14,'no','2019-11-21 11:55:32'),(37,37,14,'no','2019-11-21 11:55:32'),(38,38,14,'no','2019-11-21 11:55:32'),(39,39,14,'no','2019-11-21 11:55:32'),(40,40,14,'no','2019-11-21 11:55:32'),(41,41,14,'no','2019-11-21 11:55:32'),(42,42,14,'no','2019-11-21 11:55:32'),(43,43,14,'no','2019-11-21 11:55:32'),(44,44,14,'no','2019-11-21 11:55:32');

/*Table structure for table `feecategory` */

DROP TABLE IF EXISTS `feecategory`;

CREATE TABLE `feecategory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(50) DEFAULT NULL,
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `feecategory` */

/*Table structure for table `feemasters` */

DROP TABLE IF EXISTS `feemasters`;

CREATE TABLE `feemasters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session_id` int(11) DEFAULT NULL,
  `feetype_id` int(11) NOT NULL,
  `class_id` int(11) DEFAULT NULL,
  `amount` float(10,2) DEFAULT NULL,
  `description` text,
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `feemasters` */

/*Table structure for table `fees_discounts` */

DROP TABLE IF EXISTS `fees_discounts`;

CREATE TABLE `fees_discounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session_id` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `code` varchar(100) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `type` varchar(10) DEFAULT NULL,
  `value` decimal(10,2) DEFAULT NULL,
  `description` text,
  `is_active` varchar(10) NOT NULL DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `session_id` (`session_id`),
  CONSTRAINT `fees_discounts_ibfk_1` FOREIGN KEY (`session_id`) REFERENCES `sessions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `fees_discounts` */

insert  into `fees_discounts`(`id`,`session_id`,`name`,`code`,`amount`,`type`,`value`,`description`,`is_active`,`created_at`) values (1,14,'Fee Discount 10%','FD-01',NULL,'percent','10.00','','no','2019-11-21 11:42:23'),(2,14,'Fee Discount Amt','FD-02',NULL,'amount','200.00','','no','2019-11-21 11:42:50');

/*Table structure for table `feetype` */

DROP TABLE IF EXISTS `feetype`;

CREATE TABLE `feetype` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `is_system` int(1) NOT NULL DEFAULT '0',
  `feecategory_id` int(11) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `code` varchar(100) NOT NULL,
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

/*Data for the table `feetype` */

insert  into `feetype`(`id`,`is_system`,`feecategory_id`,`type`,`code`,`is_active`,`created_at`,`updated_at`,`description`) values (8,0,NULL,'FeeType1','FT-01','no','2019-11-21 11:41:27','0000-00-00 00:00:00',''),(9,0,NULL,'FeeType2','FT-02','no','2019-11-21 11:41:41','0000-00-00 00:00:00',''),(10,0,NULL,'Transportation fee','TRANSPORT_FEE','no','2019-11-21 11:55:32','0000-00-00 00:00:00','Auto Generated');

/*Table structure for table `follow_up` */

DROP TABLE IF EXISTS `follow_up`;

CREATE TABLE `follow_up` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `enquiry_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `next_date` date NOT NULL,
  `response` text NOT NULL,
  `note` text NOT NULL,
  `followup_by` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `follow_up` */

/*Table structure for table `front_cms_media_gallery` */

DROP TABLE IF EXISTS `front_cms_media_gallery`;

CREATE TABLE `front_cms_media_gallery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image` varchar(300) DEFAULT NULL,
  `thumb_path` varchar(300) DEFAULT NULL,
  `dir_path` varchar(300) DEFAULT NULL,
  `img_name` varchar(300) DEFAULT NULL,
  `thumb_name` varchar(300) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `file_type` varchar(100) NOT NULL,
  `file_size` varchar(100) NOT NULL,
  `vid_url` mediumtext NOT NULL,
  `vid_title` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `front_cms_media_gallery` */

/*Table structure for table `front_cms_menu_items` */

DROP TABLE IF EXISTS `front_cms_menu_items`;

CREATE TABLE `front_cms_menu_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_id` int(11) NOT NULL,
  `menu` varchar(100) DEFAULT NULL,
  `page_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `ext_url` mediumtext,
  `open_new_tab` int(11) DEFAULT '0',
  `ext_url_link` mediumtext,
  `slug` varchar(200) DEFAULT NULL,
  `weight` int(11) DEFAULT NULL,
  `publish` int(11) NOT NULL DEFAULT '0',
  `description` mediumtext,
  `is_active` varchar(10) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `front_cms_menu_items` */

/*Table structure for table `front_cms_menus` */

DROP TABLE IF EXISTS `front_cms_menus`;

CREATE TABLE `front_cms_menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu` varchar(100) DEFAULT NULL,
  `slug` varchar(200) DEFAULT NULL,
  `description` mediumtext,
  `open_new_tab` int(10) NOT NULL DEFAULT '0',
  `ext_url` mediumtext NOT NULL,
  `ext_url_link` mediumtext NOT NULL,
  `publish` int(11) NOT NULL DEFAULT '0',
  `content_type` varchar(10) NOT NULL DEFAULT 'manual',
  `is_active` varchar(10) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `front_cms_menus` */

insert  into `front_cms_menus`(`id`,`menu`,`slug`,`description`,`open_new_tab`,`ext_url`,`ext_url_link`,`publish`,`content_type`,`is_active`,`created_at`) values (1,'Main Menu','main-menu','Main menu',0,'','',0,'default','no','2018-04-20 20:39:49'),(2,'Bottom Menu','bottom-menu','Bottom Menu',0,'','',0,'default','no','2018-04-20 20:39:55');

/*Table structure for table `front_cms_page_contents` */

DROP TABLE IF EXISTS `front_cms_page_contents`;

CREATE TABLE `front_cms_page_contents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_id` int(11) DEFAULT NULL,
  `content_type` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `page_id` (`page_id`),
  CONSTRAINT `front_cms_page_contents_ibfk_1` FOREIGN KEY (`page_id`) REFERENCES `front_cms_pages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `front_cms_page_contents` */

/*Table structure for table `front_cms_pages` */

DROP TABLE IF EXISTS `front_cms_pages`;

CREATE TABLE `front_cms_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_type` varchar(10) NOT NULL DEFAULT 'manual',
  `is_homepage` int(1) DEFAULT '0',
  `title` varchar(250) DEFAULT NULL,
  `url` varchar(250) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `slug` varchar(200) DEFAULT NULL,
  `meta_title` mediumtext,
  `meta_description` mediumtext,
  `meta_keyword` mediumtext,
  `feature_image` varchar(200) NOT NULL,
  `description` longtext,
  `publish_date` date NOT NULL,
  `publish` int(10) DEFAULT '0',
  `sidebar` int(10) DEFAULT '0',
  `is_active` varchar(10) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=79 DEFAULT CHARSET=utf8;

/*Data for the table `front_cms_pages` */

insert  into `front_cms_pages`(`id`,`page_type`,`is_homepage`,`title`,`url`,`type`,`slug`,`meta_title`,`meta_description`,`meta_keyword`,`feature_image`,`description`,`publish_date`,`publish`,`sidebar`,`is_active`,`created_at`) values (1,'default',1,'Home','page/home','page','home','','','','','<p>home page</p>','0000-00-00',1,NULL,'no','2018-07-11 23:49:33'),(2,'default',0,'Complain','page/complain','page','complain','Complain form','                                                                                                                                                                                    complain form                                                                                                                                                                                                                                ','complain form','','<p>\r\n[form-builder:complain]</p>','0000-00-00',1,1,'no','2018-05-09 20:59:34'),(54,'default',0,'404 page','page/404-page','page','404-page','','                                ','','','<html>\r\n<head>\r\n <title></title>\r\n</head>\r\n<body>\r\n<p>404 page found</p>\r\n</body>\r\n</html>','0000-00-00',0,NULL,'no','2018-05-18 20:31:04'),(76,'default',0,'Contact us','page/contact-us','page','contact-us','','','','','<title></title>\r\n<section class=\"contact\">\r\n<div class=\"container spacet50 spaceb50\">\r\n<div class=\"row\">\r\n<div class=\"col-md-12 col-sm-12\">[form-builder:contact_us]<!--./row--></div>\r\n<!--./col-md-12--></div>\r\n<!--./row--></div>\r\n<!--./container--></section>','0000-00-00',0,NULL,'no','2018-07-11 23:48:41'),(77,'manual',0,'Matcha','page/matcha','page','matcha','','','','http://school.neemacademy.com/uploads/gallery/media/c1fd74724eb65a19e4d09cb327474f6b--book-art-parakeets.jpg','<html>\r\n<head>\r\n	<title></title>\r\n</head>\r\n<body>\r\n<p>https://www.mathcha.io/editor</p>\r\n</body>\r\n</html>','0000-00-00',0,1,'no','2018-11-23 11:16:17'),(78,'manual',0,'Exercise 16.1','page/exercise-161','page','exercise-161','','','','http://school.neemacademy.com/uploads/gallery/media/1w.png','<html>\r\n<head>\r\n	<title></title>\r\n</head>\r\n<body>\r\n<p>Classified Questions for Practice</p>\r\n</body>\r\n</html>','0000-00-00',0,NULL,'no','2018-11-23 11:16:52');

/*Table structure for table `front_cms_program_photos` */

DROP TABLE IF EXISTS `front_cms_program_photos`;

CREATE TABLE `front_cms_program_photos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `program_id` int(11) DEFAULT NULL,
  `media_gallery_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `program_id` (`program_id`),
  CONSTRAINT `front_cms_program_photos_ibfk_1` FOREIGN KEY (`program_id`) REFERENCES `front_cms_programs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `front_cms_program_photos` */

/*Table structure for table `front_cms_programs` */

DROP TABLE IF EXISTS `front_cms_programs`;

CREATE TABLE `front_cms_programs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `url` mediumtext,
  `title` varchar(200) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `event_start` date DEFAULT NULL,
  `start_time` varchar(100) NOT NULL,
  `event_end` date DEFAULT NULL,
  `end_time` varchar(100) NOT NULL,
  `event_venue` mediumtext,
  `description` mediumtext,
  `is_active` varchar(10) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `meta_title` mediumtext NOT NULL,
  `meta_description` mediumtext NOT NULL,
  `meta_keyword` mediumtext NOT NULL,
  `feature_image` mediumtext NOT NULL,
  `publish_date` date NOT NULL,
  `publish` varchar(10) DEFAULT '0',
  `sidebar` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `front_cms_programs` */

/*Table structure for table `front_cms_settings` */

DROP TABLE IF EXISTS `front_cms_settings`;

CREATE TABLE `front_cms_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `theme` varchar(50) DEFAULT NULL,
  `is_active_rtl` int(10) DEFAULT '0',
  `is_active_front_cms` int(11) DEFAULT '0',
  `is_active_sidebar` int(1) DEFAULT '0',
  `logo` varchar(200) DEFAULT NULL,
  `contact_us_email` varchar(100) DEFAULT NULL,
  `complain_form_email` varchar(100) DEFAULT NULL,
  `sidebar_options` mediumtext NOT NULL,
  `fb_url` varchar(200) NOT NULL,
  `twitter_url` varchar(200) NOT NULL,
  `youtube_url` varchar(200) NOT NULL,
  `google_plus` varchar(200) NOT NULL,
  `instagram_url` varchar(200) NOT NULL,
  `pinterest_url` varchar(200) NOT NULL,
  `linkedin_url` varchar(200) NOT NULL,
  `google_analytics` mediumtext,
  `footer_text` varchar(500) DEFAULT NULL,
  `fav_icon` varchar(250) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `front_cms_settings` */

/*Table structure for table `general_calls` */

DROP TABLE IF EXISTS `general_calls`;

CREATE TABLE `general_calls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `contact` varchar(12) NOT NULL,
  `date` date NOT NULL,
  `description` varchar(500) NOT NULL,
  `follow_up_date` date NOT NULL,
  `call_dureation` varchar(50) NOT NULL,
  `note` mediumtext NOT NULL,
  `call_type` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `general_calls` */

/*Table structure for table `grades` */

DROP TABLE IF EXISTS `grades`;

CREATE TABLE `grades` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `point` float(10,1) DEFAULT NULL,
  `mark_from` float(10,2) DEFAULT NULL,
  `mark_upto` float(10,2) DEFAULT NULL,
  `description` text,
  `grade_remark` varchar(255) NOT NULL,
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `grades` */

/*Table structure for table `homework` */

DROP TABLE IF EXISTS `homework`;

CREATE TABLE `homework` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `homework_date` date NOT NULL,
  `submit_date` date NOT NULL,
  `homework_date_bs` varchar(255) NOT NULL,
  `submit_date_bs` varchar(255) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `description` varchar(500) NOT NULL,
  `create_date` date NOT NULL,
  `document` varchar(200) NOT NULL,
  `created_by` int(11) NOT NULL,
  `evaluated_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `homework` */

/*Table structure for table `homework_evaluation` */

DROP TABLE IF EXISTS `homework_evaluation`;

CREATE TABLE `homework_evaluation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `homework_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `date_bs` varchar(255) NOT NULL,
  `status` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `homework_evaluation` */

/*Table structure for table `hostel` */

DROP TABLE IF EXISTS `hostel`;

CREATE TABLE `hostel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hostel_name` varchar(100) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `address` text,
  `intake` int(11) DEFAULT NULL,
  `description` text,
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `hostel` */

/*Table structure for table `hostel_rooms` */

DROP TABLE IF EXISTS `hostel_rooms`;

CREATE TABLE `hostel_rooms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hostel_id` int(11) DEFAULT NULL,
  `room_type_id` int(11) DEFAULT NULL,
  `room_no` varchar(200) DEFAULT NULL,
  `no_of_bed` int(11) DEFAULT NULL,
  `cost_per_bed` float(10,2) DEFAULT '0.00',
  `title` varchar(200) DEFAULT NULL,
  `description` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `hostel_rooms` */

/*Table structure for table `id_card` */

DROP TABLE IF EXISTS `id_card`;

CREATE TABLE `id_card` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `school_name` varchar(100) NOT NULL,
  `school_address` varchar(500) NOT NULL,
  `background` varchar(100) NOT NULL,
  `logo` varchar(100) NOT NULL,
  `sign_image` varchar(100) NOT NULL,
  `header_color` varchar(100) NOT NULL,
  `enable_admission_no` tinyint(1) NOT NULL COMMENT '0=disable,1=enable',
  `enable_roll_no` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `enable_student_name` tinyint(1) NOT NULL COMMENT '0=disable,1=enable',
  `enable_class` tinyint(1) NOT NULL COMMENT '0=disable,1=enable',
  `enable_guardian_name` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `enable_fathers_name` tinyint(1) NOT NULL COMMENT '0=disable,1=enable',
  `enable_mothers_name` tinyint(1) NOT NULL COMMENT '0=disable,1=enable',
  `enable_address` tinyint(1) NOT NULL COMMENT '0=disable,1=enable',
  `enable_phone` tinyint(1) NOT NULL COMMENT '0=disable,1=enable',
  `enable_dob` tinyint(1) NOT NULL COMMENT '0=disable,1=enable',
  `enable_blood_group` tinyint(1) NOT NULL COMMENT '0=disable,1=enable',
  `status` tinyint(1) NOT NULL COMMENT '0=disable,1=enable',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `id_card` */

/*Table structure for table `income` */

DROP TABLE IF EXISTS `income`;

CREATE TABLE `income` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `inc_head_id` varchar(11) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `invoice_no` varchar(200) NOT NULL,
  `date` date DEFAULT NULL,
  `date_bs` varchar(255) NOT NULL,
  `amount` float DEFAULT NULL,
  `note` text,
  `is_active` varchar(255) DEFAULT 'yes',
  `is_deleted` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `documents` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `income` */

/*Table structure for table `income_head` */

DROP TABLE IF EXISTS `income_head`;

CREATE TABLE `income_head` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `income_category` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_active` varchar(255) NOT NULL DEFAULT 'yes',
  `is_deleted` varchar(255) NOT NULL DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `income_head` */

insert  into `income_head`(`id`,`income_category`,`description`,`is_active`,`is_deleted`,`created_at`,`updated_at`) values (1,'Income head','','yes','no','2019-11-21 16:00:51','0000-00-00 00:00:00');

/*Table structure for table `item` */

DROP TABLE IF EXISTS `item`;

CREATE TABLE `item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_category_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `item_photo` varchar(225) DEFAULT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `item_store_id` int(11) DEFAULT NULL,
  `item_supplier_id` int(11) DEFAULT NULL,
  `quantity` int(100) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `item` */

/*Table structure for table `item_category` */

DROP TABLE IF EXISTS `item_category`;

CREATE TABLE `item_category` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `item_category` varchar(255) NOT NULL,
  `is_active` varchar(255) NOT NULL DEFAULT 'yes',
  `description` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `item_category` */

/*Table structure for table `item_issue` */

DROP TABLE IF EXISTS `item_issue`;

CREATE TABLE `item_issue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `issue_type` varchar(15) DEFAULT NULL,
  `issue_to` varchar(100) DEFAULT NULL,
  `issue_by` varchar(100) DEFAULT NULL,
  `issue_date` date DEFAULT NULL,
  `return_date` date DEFAULT NULL,
  `item_category_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `quantity` int(10) NOT NULL,
  `note` text NOT NULL,
  `is_returned` int(2) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_active` varchar(10) DEFAULT 'no',
  PRIMARY KEY (`id`),
  KEY `item_id` (`item_id`),
  KEY `item_category_id` (`item_category_id`),
  CONSTRAINT `item_issue_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`) ON DELETE CASCADE,
  CONSTRAINT `item_issue_ibfk_2` FOREIGN KEY (`item_category_id`) REFERENCES `item_category` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `item_issue` */

/*Table structure for table `item_stock` */

DROP TABLE IF EXISTS `item_stock`;

CREATE TABLE `item_stock` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `symbol` varchar(10) NOT NULL DEFAULT '+',
  `store_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `date` date NOT NULL,
  `attachment` varchar(250) DEFAULT NULL,
  `description` text NOT NULL,
  `is_active` varchar(10) DEFAULT 'yes',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `item_id` (`item_id`),
  KEY `supplier_id` (`supplier_id`),
  KEY `store_id` (`store_id`),
  CONSTRAINT `item_stock_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`) ON DELETE CASCADE,
  CONSTRAINT `item_stock_ibfk_2` FOREIGN KEY (`supplier_id`) REFERENCES `item_supplier` (`id`) ON DELETE CASCADE,
  CONSTRAINT `item_stock_ibfk_3` FOREIGN KEY (`store_id`) REFERENCES `item_store` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `item_stock` */

/*Table structure for table `item_store` */

DROP TABLE IF EXISTS `item_store`;

CREATE TABLE `item_store` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `item_store` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `item_store` */

/*Table structure for table `item_supplier` */

DROP TABLE IF EXISTS `item_supplier`;

CREATE TABLE `item_supplier` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `item_supplier` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `contact_person_name` varchar(255) NOT NULL,
  `contact_person_phone` varchar(255) NOT NULL,
  `contact_person_email` varchar(255) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `item_supplier` */

/*Table structure for table `languages` */

DROP TABLE IF EXISTS `languages`;

CREATE TABLE `languages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language` varchar(50) DEFAULT NULL,
  `is_deleted` varchar(10) NOT NULL DEFAULT 'yes',
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=91 DEFAULT CHARSET=utf8;

/*Data for the table `languages` */

insert  into `languages`(`id`,`language`,`is_deleted`,`is_active`,`created_at`,`updated_at`) values (1,'Azerbaijan','no','no','2017-04-06 10:53:33','0000-00-00 00:00:00'),(2,'Albanian','no','no','2017-04-06 10:53:33','0000-00-00 00:00:00'),(3,'Amharic','no','no','2017-04-06 10:53:33','0000-00-00 00:00:00'),(4,'English','no','no','2017-04-06 10:53:33','0000-00-00 00:00:00'),(5,'Arabic','no','no','2017-04-06 10:53:33','0000-00-00 00:00:00'),(7,'Afrikaans','no','no','2017-04-06 10:53:33','0000-00-00 00:00:00'),(8,'Basque','no','no','2017-04-06 10:53:33','0000-00-00 00:00:00'),(11,'Bengali','no','no','2017-04-06 10:53:33','0000-00-00 00:00:00'),(13,'Bosnian','no','no','2017-04-06 10:53:33','0000-00-00 00:00:00'),(14,'Welsh','no','no','2017-04-06 10:53:33','0000-00-00 00:00:00'),(15,'Hungarian','no','no','2017-04-06 10:53:33','0000-00-00 00:00:00'),(16,'Vietnamese','no','no','2017-04-06 10:53:33','0000-00-00 00:00:00'),(17,'Haitian (Creole)','no','no','2017-04-06 10:53:33','0000-00-00 00:00:00'),(18,'Galician','no','no','2017-04-06 10:53:33','0000-00-00 00:00:00'),(19,'Dutch','no','no','2017-04-06 10:53:33','0000-00-00 00:00:00'),(21,'Greek','no','no','2017-04-06 10:53:33','0000-00-00 00:00:00'),(22,'Georgian','no','no','2017-04-06 10:53:33','0000-00-00 00:00:00'),(23,'Gujarati','no','no','2017-04-06 10:53:33','0000-00-00 00:00:00'),(24,'Danish','no','no','2017-04-06 10:53:33','0000-00-00 00:00:00'),(25,'Hebrew','no','no','2017-04-06 10:53:33','0000-00-00 00:00:00'),(26,'Yiddish','no','no','2017-04-06 10:53:33','0000-00-00 00:00:00'),(27,'Indonesian','no','no','2017-04-06 10:53:33','0000-00-00 00:00:00'),(28,'Irish','no','no','2017-04-06 10:53:33','0000-00-00 00:00:00'),(29,'Italian','no','no','2017-04-06 10:53:33','0000-00-00 00:00:00'),(30,'Icelandic','no','no','2017-04-06 10:53:33','0000-00-00 00:00:00'),(31,'Spanish','no','no','2017-04-06 10:53:33','0000-00-00 00:00:00'),(33,'Kannada','no','no','2017-04-06 10:53:33','0000-00-00 00:00:00'),(34,'Catalan','no','no','2017-04-06 10:53:33','0000-00-00 00:00:00'),(36,'Chinese','no','no','2017-04-06 10:53:33','0000-00-00 00:00:00'),(37,'Korean','no','no','2017-04-06 10:53:33','0000-00-00 00:00:00'),(38,'Xhosa','no','no','2017-04-06 10:53:33','0000-00-00 00:00:00'),(39,'Latin','no','no','2017-04-06 10:53:33','0000-00-00 00:00:00'),(40,'Latvian','no','no','2017-04-06 10:53:33','0000-00-00 00:00:00'),(41,'Lithuanian','no','no','2017-04-06 10:53:33','0000-00-00 00:00:00'),(43,'Malagasy','no','no','2017-04-06 10:53:33','0000-00-00 00:00:00'),(44,'Malay','no','no','2017-04-06 10:53:33','0000-00-00 00:00:00'),(45,'Malayalam','no','no','2017-04-06 10:53:33','0000-00-00 00:00:00'),(46,'Maltese','no','no','2017-04-06 10:53:33','0000-00-00 00:00:00'),(47,'Macedonian','no','no','2017-04-06 10:53:33','0000-00-00 00:00:00'),(48,'Maori','no','no','2017-04-06 10:53:33','0000-00-00 00:00:00'),(49,'Marathi','no','no','2017-04-06 10:53:33','0000-00-00 00:00:00'),(51,'Mongolian','no','no','2017-04-06 10:53:33','0000-00-00 00:00:00'),(52,'German','no','no','2017-04-06 10:53:33','0000-00-00 00:00:00'),(53,'Nepali','no','no','2017-04-06 10:53:33','0000-00-00 00:00:00'),(54,'Norwegian','no','no','2017-04-06 10:53:33','0000-00-00 00:00:00'),(55,'Punjabi','no','no','2017-04-06 10:53:33','0000-00-00 00:00:00'),(57,'Persian','no','no','2017-04-06 10:53:33','0000-00-00 00:00:00'),(59,'Portuguese','no','no','2017-04-06 10:53:33','0000-00-00 00:00:00'),(60,'Romanian','no','no','2017-04-06 10:53:33','0000-00-00 00:00:00'),(61,'Russian','no','no','2017-04-06 10:53:33','0000-00-00 00:00:00'),(62,'Cebuano','no','no','2017-04-06 10:53:33','0000-00-00 00:00:00'),(64,'Sinhala','no','no','2017-04-06 10:53:33','0000-00-00 00:00:00'),(65,'Slovakian','no','no','2017-04-06 10:53:33','0000-00-00 00:00:00'),(66,'Slovenian','no','no','2017-04-06 10:53:33','0000-00-00 00:00:00'),(67,'Swahili','no','no','2017-04-06 10:53:33','0000-00-00 00:00:00'),(68,'Sundanese','no','no','2017-04-06 10:53:33','0000-00-00 00:00:00'),(70,'Thai','no','no','2017-04-06 10:53:33','0000-00-00 00:00:00'),(71,'Tagalog','no','no','2017-04-06 10:53:33','0000-00-00 00:00:00'),(72,'Tamil','no','no','2017-04-06 10:53:33','0000-00-00 00:00:00'),(74,'Telugu','no','no','2017-04-06 10:53:33','0000-00-00 00:00:00'),(75,'Turkish','no','no','2017-04-06 10:53:33','0000-00-00 00:00:00'),(77,'Uzbek','no','no','2017-04-06 10:53:33','0000-00-00 00:00:00'),(79,'Urdu','no','no','2017-04-06 10:53:33','0000-00-00 00:00:00'),(80,'Finnish','no','no','2017-04-06 10:53:33','0000-00-00 00:00:00'),(81,'French','no','no','2017-04-06 10:53:33','0000-00-00 00:00:00'),(82,'Hindi','no','no','2017-04-06 10:53:33','0000-00-00 00:00:00'),(84,'Czech','no','no','2017-04-06 10:53:33','0000-00-00 00:00:00'),(85,'Swedish','no','no','2017-04-06 10:53:33','0000-00-00 00:00:00'),(86,'Scottish','no','no','2017-04-06 10:53:33','0000-00-00 00:00:00'),(87,'Estonian','no','no','2017-04-06 10:53:33','0000-00-00 00:00:00'),(88,'Esperanto','no','no','2017-04-06 10:53:33','0000-00-00 00:00:00'),(89,'Javanese','no','no','2017-04-06 10:53:33','0000-00-00 00:00:00'),(90,'Japanese','no','no','2017-04-06 10:53:33','0000-00-00 00:00:00');

/*Table structure for table `leave_types` */

DROP TABLE IF EXISTS `leave_types`;

CREATE TABLE `leave_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(200) NOT NULL,
  `is_active` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `leave_types` */

/*Table structure for table `libarary_members` */

DROP TABLE IF EXISTS `libarary_members`;

CREATE TABLE `libarary_members` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `library_card_no` varchar(50) DEFAULT NULL,
  `member_type` varchar(50) DEFAULT NULL,
  `member_id` int(11) DEFAULT NULL,
  `is_active` varchar(10) NOT NULL DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `libarary_members` */

/*Table structure for table `messages` */

DROP TABLE IF EXISTS `messages`;

CREATE TABLE `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) DEFAULT NULL,
  `message` text,
  `send_mail` varchar(10) DEFAULT '0',
  `send_sms` varchar(10) DEFAULT '0',
  `is_group` varchar(10) DEFAULT '0',
  `is_individual` varchar(10) DEFAULT '0',
  `is_class` int(10) NOT NULL DEFAULT '0',
  `group_list` text,
  `user_list` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `messages` */

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `version` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `migrations` */

/*Table structure for table `notification_roles` */

DROP TABLE IF EXISTS `notification_roles`;

CREATE TABLE `notification_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `send_notification_id` int(11) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `is_active` int(11) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `send_notification_id` (`send_notification_id`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `notification_roles_ibfk_1` FOREIGN KEY (`send_notification_id`) REFERENCES `send_notification` (`id`) ON DELETE CASCADE,
  CONSTRAINT `notification_roles_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `notification_roles` */

/*Table structure for table `notification_setting` */

DROP TABLE IF EXISTS `notification_setting`;

CREATE TABLE `notification_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(100) DEFAULT NULL,
  `is_mail` varchar(10) DEFAULT '0',
  `is_sms` varchar(10) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Data for the table `notification_setting` */

insert  into `notification_setting`(`id`,`type`,`is_mail`,`is_sms`,`created_at`) values (1,'student_admission','1','0','2018-05-22 18:45:07'),(2,'exam_result','1','0','2018-05-22 18:45:07'),(3,'fee_submission','1','0','2018-05-22 18:45:07'),(4,'absent_attendence','1','0','2018-07-11 23:28:02'),(5,'login_credential','1','0','2018-05-22 18:49:19');

/*Table structure for table `payment_settings` */

DROP TABLE IF EXISTS `payment_settings`;

CREATE TABLE `payment_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `payment_type` varchar(200) NOT NULL,
  `api_username` varchar(200) DEFAULT NULL,
  `api_secret_key` varchar(200) NOT NULL,
  `salt` varchar(200) NOT NULL,
  `api_publishable_key` varchar(200) NOT NULL,
  `api_password` varchar(200) DEFAULT NULL,
  `api_signature` varchar(200) DEFAULT NULL,
  `api_email` varchar(200) DEFAULT NULL,
  `paypal_demo` varchar(100) NOT NULL,
  `account_no` varchar(200) NOT NULL,
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Data for the table `payment_settings` */

insert  into `payment_settings`(`id`,`payment_type`,`api_username`,`api_secret_key`,`salt`,`api_publishable_key`,`api_password`,`api_signature`,`api_email`,`paypal_demo`,`account_no`,`is_active`,`created_at`,`updated_at`) values (1,'paypal','','','','','','',NULL,'','','yes','2018-07-12 11:11:13','0000-00-00 00:00:00'),(2,'stripe',NULL,'','','',NULL,NULL,NULL,'','','no','2018-07-12 11:11:26','0000-00-00 00:00:00'),(3,'payu',NULL,'','','',NULL,NULL,NULL,'','','no','2018-07-12 11:11:35','0000-00-00 00:00:00'),(4,'ccavenue',NULL,'','','',NULL,NULL,NULL,'','','no','2018-07-12 11:11:45','0000-00-00 00:00:00'),(5,'Prabhupay',NULL,'','','',NULL,NULL,NULL,'','','no','2018-07-12 11:11:55','0000-00-00 00:00:00');

/*Table structure for table `payslip_allowance` */

DROP TABLE IF EXISTS `payslip_allowance`;

CREATE TABLE `payslip_allowance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `payslip_id` int(11) NOT NULL,
  `allowance_type` varchar(200) NOT NULL,
  `amount` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `cal_type` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `payslip_allowance` */

/*Table structure for table `permission_category` */

DROP TABLE IF EXISTS `permission_category`;

CREATE TABLE `permission_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `perm_group_id` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `short_code` varchar(100) DEFAULT NULL,
  `enable_view` int(11) DEFAULT '0',
  `enable_add` int(11) DEFAULT '0',
  `enable_edit` int(11) DEFAULT '0',
  `enable_delete` int(11) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=141 DEFAULT CHARSET=utf8;

/*Data for the table `permission_category` */

insert  into `permission_category`(`id`,`perm_group_id`,`name`,`short_code`,`enable_view`,`enable_add`,`enable_edit`,`enable_delete`,`created_at`) values (1,1,'Student','student',1,1,1,1,'2018-06-22 21:32:11'),(2,1,'Import Student','import_student',1,0,0,0,'2018-06-22 21:32:19'),(3,1,'Student Categories','student_categories',1,1,1,1,'2018-06-22 21:32:36'),(4,1,'Student Houses','student_houses',1,1,1,1,'2018-06-22 21:32:53'),(5,2,'Collect Fees','collect_fees',1,1,0,1,'2018-06-22 21:36:03'),(6,2,'Fees Carry Forward','fees_carry_forward',1,0,0,0,'2018-06-27 11:33:15'),(7,2,'Fees Master','fees_master',1,1,1,1,'2018-06-27 11:33:57'),(8,2,'Fees Group','fees_group',1,1,1,1,'2018-06-22 21:36:46'),(9,3,'Income','income',1,1,1,1,'2018-06-22 21:38:21'),(10,3,'Income Head','income_head',1,1,1,1,'2018-06-22 21:37:44'),(11,3,'Search Income','search_income',1,0,0,0,'2018-06-22 21:38:00'),(12,4,'Expense','expense',1,1,1,1,'2018-06-22 21:39:06'),(13,4,'Expense Head','expense_head',1,1,1,1,'2018-06-22 21:38:47'),(14,4,'Search Expense','search_expense',1,0,0,0,'2018-06-22 21:39:13'),(15,5,'Student Attendance','student_attendance',1,1,1,0,'2018-06-22 21:39:49'),(16,5,'Student Attendance Report','student_attendance_report',1,0,0,0,'2018-06-22 21:39:26'),(17,6,'Exam','exam',1,1,1,1,'2018-06-22 21:41:02'),(19,6,'Marks Register','marks_register',1,1,1,0,'2018-06-22 21:41:19'),(20,6,'Marks Grade','marks_grade',1,1,1,1,'2018-06-22 21:40:25'),(21,7,'Class Timetable','class_timetable',1,1,1,0,'2018-06-22 21:46:36'),(22,7,'Assign Subject','assign_subject',1,1,1,1,'2018-06-22 21:46:57'),(23,7,'Subject','subject',1,1,1,1,'2018-06-22 21:47:17'),(24,7,'Class','class',1,1,1,1,'2018-06-22 21:47:35'),(25,7,'Section','section',1,1,1,1,'2018-06-22 21:46:10'),(26,7,'Promote Student','promote_student',1,0,0,0,'2018-06-22 21:47:47'),(27,8,'Upload Content','upload_content',1,1,0,1,'2018-06-22 21:48:19'),(28,9,'Books','books',1,1,1,1,'2018-06-22 21:49:04'),(29,9,'Issue Return Student','issue_return',1,0,0,0,'2018-06-22 21:48:41'),(30,9,'Add Staff Member','add_staff_member',1,0,0,0,'2018-07-02 22:52:00'),(31,10,'Issue Item','issue_item',1,0,0,0,'2018-06-22 21:49:51'),(32,10,'Item Stock','item_stock',1,1,1,1,'2018-06-22 21:50:17'),(33,10,'Item','item',1,1,1,1,'2018-06-22 21:50:40'),(34,10,'Store','store',1,1,1,1,'2018-06-22 21:51:02'),(35,10,'Supplier','supplier',1,1,1,1,'2018-06-22 21:51:25'),(37,11,'Routes','routes',1,1,1,1,'2018-06-22 21:54:17'),(38,11,'Vehicle','vehicle',1,1,1,1,'2018-06-22 21:54:36'),(39,11,'Assign Vehicle','assign_vehicle',1,1,1,1,'2018-06-27 15:54:20'),(40,12,'Hostel','hostel',1,1,1,1,'2018-06-22 21:55:49'),(41,12,'Room Type','room_type',1,1,1,1,'2018-06-22 21:55:27'),(42,12,'Hostel Rooms','hostel_rooms',1,1,1,1,'2018-06-25 17:38:03'),(43,13,'Notice Board','notice_board',1,1,1,1,'2018-06-22 21:56:17'),(44,13,'Email / SMS','email_sms',1,0,0,0,'2018-06-22 21:55:54'),(46,13,'Email / SMS Log','email_sms_log',1,0,0,0,'2018-06-22 21:56:23'),(47,1,'Student Report','student_report',1,0,0,0,'2018-07-03 22:04:36'),(48,14,'Transaction Report','transaction_report',1,0,0,0,'2018-07-06 22:58:32'),(49,14,'User Log','user_log',1,0,0,0,'2018-07-06 22:58:53'),(53,15,'Languages','languages',0,1,0,0,'2018-06-22 21:58:18'),(54,15,'General Setting','general_setting',1,0,1,0,'2018-07-05 20:23:35'),(55,15,'Session Setting','session_setting',1,1,1,1,'2018-06-22 21:59:15'),(56,15,'Notification Setting','notification_setting',1,0,1,0,'2018-07-05 20:23:41'),(57,15,'SMS Setting','sms_setting',1,0,1,0,'2018-07-05 20:23:47'),(58,15,'Email Setting','email_setting',1,0,1,0,'2018-07-05 20:23:51'),(59,15,'Front CMS Setting','front_cms_setting',1,0,1,0,'2018-07-05 20:23:55'),(60,15,'Payment Methods','payment_methods',1,0,1,0,'2018-07-05 20:23:59'),(61,16,'Menus','menus',1,1,0,1,'2018-07-09 15:05:06'),(62,16,'Media Manager','media_manager',1,1,0,1,'2018-07-09 15:05:26'),(63,16,'Banner Images','banner_images',1,1,0,1,'2018-06-22 22:01:02'),(64,16,'Pages','pages',1,1,1,1,'2018-06-22 22:01:21'),(65,16,'Gallery','gallery',1,1,1,1,'2018-06-22 22:02:02'),(66,16,'Event','event',1,1,1,1,'2018-06-22 22:02:20'),(67,16,'News','notice',1,1,1,1,'2018-07-03 19:54:34'),(68,2,'Fees Group Assign','fees_group_assign',1,0,0,0,'2018-06-22 21:35:42'),(69,2,'Fees Type','fees_type',1,1,1,1,'2018-06-22 21:34:34'),(70,2,'Fees Discount','fees_discount',1,1,1,1,'2018-06-22 21:35:10'),(71,2,'Fees Discount Assign','fees_discount_assign',1,0,0,0,'2018-06-22 21:35:17'),(72,2,'Fees Statement','fees_statement',1,0,0,0,'2018-06-22 21:33:56'),(73,2,'Search Fees Payment','search_fees_payment',1,0,0,0,'2018-06-22 21:35:27'),(74,2,'Search Due Fees','search_due_fees',1,0,0,0,'2018-06-22 21:35:35'),(75,2,'Balance Fees Report','balance_fees_report',1,0,0,0,'2018-06-22 21:33:50'),(76,6,'Exam Schedule','exam_schedule',1,1,1,0,'2018-06-22 21:40:40'),(77,7,'Assign Class Teacher','assign_class_teacher',1,1,1,1,'2018-06-22 21:45:52'),(78,17,'Admission Enquiry','admission_enquiry',1,1,1,1,'2018-06-22 22:06:24'),(79,17,'Follow Up Admission Enquiry','follow_up_admission_enquiry',1,1,0,1,'2018-06-22 22:06:39'),(80,17,'Visitor Book','visitor_book',1,1,1,1,'2018-06-22 22:03:58'),(81,17,'Phone Call Log','phone_call_log',1,1,1,1,'2018-06-22 22:05:57'),(82,17,'Postal Dispatch','postal_dispatch',1,1,1,1,'2018-06-22 22:05:21'),(83,17,'Postal Receive','postal_receive',1,1,1,1,'2018-06-22 22:05:04'),(84,17,'Complain','complaint',1,1,1,1,'2018-07-03 19:55:55'),(85,17,'Setup Font Office','setup_font_office',1,1,1,1,'2018-06-22 22:04:24'),(86,18,'Staff','staff',1,1,1,1,'2018-06-22 22:08:31'),(87,18,'Disable Staff','disable_staff',1,0,0,0,'2018-06-22 22:08:12'),(88,18,'Staff Attendance','staff_attendance',1,1,1,0,'2018-06-22 22:08:10'),(89,18,'Staff Attendance Report','staff_attendance_report',1,0,0,0,'2018-06-22 22:07:54'),(90,18,'Staff Payroll','staff_payroll',1,1,0,1,'2018-06-22 22:07:51'),(91,18,'Payroll Report','payroll_report',1,0,0,0,'2018-06-22 22:07:34'),(93,19,'Homework','homework',1,1,1,1,'2018-06-22 22:08:50'),(94,19,'Homework Evaluation','homework_evaluation',1,1,0,0,'2018-06-27 14:22:21'),(95,19,'Homework Report','homework_report',1,0,0,0,'2018-06-22 22:08:54'),(96,20,'Student Certificate','student_certificate',1,1,1,1,'2018-07-06 21:56:07'),(97,20,'Generate Certificate','generate_certificate',1,0,0,0,'2018-07-06 21:52:16'),(98,20,'Student ID Card','student_id_card',1,1,1,1,'2018-07-06 21:56:28'),(99,20,'Generate ID Card','generate_id_card',1,0,0,0,'2018-07-06 21:56:49'),(102,21,'Calendar To Do List','calendar_to_do_list',1,1,1,1,'2018-06-22 22:09:41'),(104,10,'Item Category','item_category',1,1,1,1,'2018-06-22 21:49:33'),(105,1,'Student Parent Login Details','student_parent_login_details',1,0,0,0,'2018-06-22 21:33:01'),(106,22,'Quick Session Change','quick_session_change',1,0,0,0,'2018-06-22 22:09:45'),(107,1,'Disable Student','disable_student',1,0,0,0,'2018-06-25 17:36:34'),(108,18,' Approve Leave Request','approve_leave_request',1,1,1,1,'2018-07-02 21:32:41'),(109,18,'Apply Leave','apply_leave',1,1,1,1,'2018-06-26 15:08:32'),(110,18,'Leave Types ','leave_types',1,1,1,1,'2018-07-02 21:32:56'),(111,18,'Department','department',1,1,1,1,'2018-06-26 15:12:07'),(112,18,'Designation','designation',1,1,1,1,'2018-06-26 15:12:07'),(113,22,'Fees Collection And Expense Monthly Chart','fees_collection_and_expense_monthly_chart',1,0,0,0,'2018-07-03 18:23:15'),(114,22,'Fees Collection And Expense Yearly Chart','fees_collection_and_expense_yearly_chart',1,0,0,0,'2018-07-03 18:23:15'),(115,22,'Monthly Fees Collection Widget','Monthly fees_collection_widget',1,0,0,0,'2018-07-03 18:28:35'),(116,22,'Monthly Expense Widget','monthly_expense_widget',1,0,0,0,'2018-07-03 18:28:35'),(117,22,'Student Count Widget','student_count_widget',1,0,0,0,'2018-07-03 18:28:35'),(118,22,'Staff Role Count Widget','staff_role_count_widget',1,0,0,0,'2018-07-03 18:28:35'),(119,1,'Guardian Report','guardian_report',1,0,0,0,'2018-07-03 19:57:29'),(120,1,'Student History','student_history',1,0,0,0,'2018-07-03 19:57:29'),(121,1,'Student Login Credential','student_login_credential',1,0,0,0,'2018-07-03 19:57:29'),(122,5,'Attendance By Date','attendance_by_date',1,0,0,0,'2018-07-03 19:57:29'),(123,9,'Add Student','add_student',1,0,0,0,'2018-07-03 19:57:29'),(124,11,'Student Transport Report','student_transport_report',1,0,0,0,'2018-07-03 19:57:29'),(125,12,'Student Hostel Report','student_hostel_report',1,0,0,0,'2018-07-03 19:57:29'),(126,15,'User Status','user_status',1,0,0,0,'2018-07-03 19:57:29'),(127,18,'Can See Other Users Profile','can_see_other_users_profile',1,0,0,0,'2018-07-03 19:57:29'),(128,1,'Student Timeline','student_timeline',0,1,0,1,'2018-07-05 19:23:52'),(129,18,'Staff Timeline','staff_timeline',0,1,0,1,'2018-07-05 19:23:52'),(130,15,'Backup','backup',1,1,0,1,'2018-07-09 15:32:17'),(131,15,'Restore','restore',1,0,0,0,'2018-07-09 15:32:17'),(133,6,'Examination Weightage','examination_weightage',1,1,1,1,'2019-03-28 15:18:13'),(134,6,'Marksheet','marksheet',1,0,0,0,'2019-03-28 15:18:31'),(135,6,'Grade Setting','grade_setting',1,1,1,1,'2019-03-28 15:19:48'),(136,23,'Account','account',1,1,1,1,'2019-11-27 14:56:50'),(138,23,'Account General Setting','account_general_setting',1,1,1,1,'2019-11-27 16:23:39'),(139,23,'Account Chart of Accounts','account_chart_of_accounts',1,1,1,1,'2019-11-27 16:24:08'),(140,23,'Account Categories','account_categories',1,1,1,1,'2019-11-28 17:34:36');

/*Table structure for table `permission_group` */

DROP TABLE IF EXISTS `permission_group`;

CREATE TABLE `permission_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `short_code` varchar(100) NOT NULL,
  `is_active` int(11) DEFAULT '0',
  `system` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;

/*Data for the table `permission_group` */

insert  into `permission_group`(`id`,`name`,`short_code`,`is_active`,`system`,`created_at`) values (1,'Student Information','student_information',1,1,'2018-06-27 14:54:31'),(2,'Fees Collection','fees_collection',1,0,'2019-11-21 12:49:48'),(3,'Income','income',1,0,'2018-06-27 12:04:05'),(4,'Expense','expense',1,0,'2018-07-04 12:52:33'),(5,'Student Attendance','student_attendance',1,0,'2018-07-02 19:03:08'),(6,'Examination','examination',1,0,'2018-07-11 14:04:08'),(7,'Academics','academics',1,1,'2018-07-02 18:40:43'),(8,'Download Center','download_center',1,0,'2018-07-02 19:04:29'),(9,'Library','library',1,0,'2018-06-28 22:28:14'),(10,'Inventory','inventory',1,0,'2018-12-17 17:47:24'),(11,'Transport','transport',1,0,'2018-06-27 19:06:26'),(12,'Hostel','hostel',1,0,'2018-07-02 19:04:32'),(13,'Communicate','communicate',1,0,'2018-07-02 19:05:00'),(14,'Reports','reports',1,1,'2018-06-27 14:55:22'),(15,'System Settings','system_settings',1,1,'2018-06-27 14:55:28'),(16,'Front CMS','front_cms',1,0,'2018-07-10 16:31:54'),(17,'Front Office','front_office',1,0,'2018-12-21 14:06:34'),(18,'Human Resource','human_resource',1,1,'2018-06-27 14:56:02'),(19,'Homework','homework',1,0,'2018-06-27 12:04:38'),(20,'Certificate','certificate',1,0,'2018-06-27 19:06:29'),(21,'Calendar To Do List','calendar_to_do_list',1,0,'2018-06-27 14:57:25'),(22,'Dashboard and Widgets','dashboard_and_widgets',1,1,'2018-06-27 14:56:17'),(23,'Account','account',1,0,'2019-11-28 11:11:53');

/*Table structure for table `prabhupay_init` */

DROP TABLE IF EXISTS `prabhupay_init`;

CREATE TABLE `prabhupay_init` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` char(36) CHARACTER SET ascii NOT NULL,
  `student_fees_master_id` int(11) NOT NULL,
  `fee_groups_feetype_id` int(11) NOT NULL,
  `TransactionId` varchar(255) NOT NULL,
  `ProcessURL` varchar(255) NOT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `created_on` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `prabhupay_init` */

/*Table structure for table `read_notification` */

DROP TABLE IF EXISTS `read_notification`;

CREATE TABLE `read_notification` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) DEFAULT NULL,
  `parent_id` int(10) DEFAULT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `notification_id` int(11) DEFAULT NULL,
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `read_notification` */

/*Table structure for table `reference` */

DROP TABLE IF EXISTS `reference`;

CREATE TABLE `reference` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reference` varchar(100) NOT NULL,
  `description` mediumtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `reference` */

/*Table structure for table `roles` */

DROP TABLE IF EXISTS `roles`;

CREATE TABLE `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `slug` varchar(150) DEFAULT NULL,
  `is_active` int(11) DEFAULT '0',
  `is_system` int(1) NOT NULL DEFAULT '0',
  `is_superadmin` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

/*Data for the table `roles` */

insert  into `roles`(`id`,`name`,`slug`,`is_active`,`is_system`,`is_superadmin`,`created_at`,`updated_at`) values (1,'Admin',NULL,0,1,0,'2018-06-30 21:24:11','0000-00-00 00:00:00'),(2,'Teacher',NULL,0,1,0,'2018-06-30 21:24:14','0000-00-00 00:00:00'),(3,'Accountant',NULL,0,1,0,'2018-06-30 21:24:17','0000-00-00 00:00:00'),(4,'Librarian',NULL,0,1,0,'2018-06-30 21:24:21','0000-00-00 00:00:00'),(6,'Receptionist',NULL,0,1,0,'2018-07-02 11:24:03','0000-00-00 00:00:00'),(7,'Super Admin',NULL,0,1,1,'2018-07-11 19:56:29','0000-00-00 00:00:00'),(8,'NewRole',NULL,0,0,0,'2018-12-17 12:31:57','0000-00-00 00:00:00'),(9,'General',NULL,0,0,0,'2018-12-17 16:55:06','0000-00-00 00:00:00');

/*Table structure for table `roles_permissions` */

DROP TABLE IF EXISTS `roles_permissions`;

CREATE TABLE `roles_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) DEFAULT NULL,
  `perm_cat_id` int(11) DEFAULT NULL,
  `can_view` int(11) DEFAULT NULL,
  `can_add` int(11) DEFAULT NULL,
  `can_edit` int(11) DEFAULT NULL,
  `can_delete` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=644 DEFAULT CHARSET=utf8;

/*Data for the table `roles_permissions` */

insert  into `roles_permissions`(`id`,`role_id`,`perm_cat_id`,`can_view`,`can_add`,`can_edit`,`can_delete`,`created_at`) values (3,1,3,1,1,1,1,'2018-07-06 20:57:08'),(4,1,4,1,1,1,1,'2018-07-06 20:58:01'),(6,1,5,1,1,0,1,'2018-07-02 22:34:46'),(8,1,7,1,1,1,1,'2018-07-06 20:58:29'),(9,1,8,1,1,1,1,'2018-07-06 20:58:53'),(10,1,17,1,1,1,1,'2018-07-06 21:03:56'),(11,1,78,1,1,1,1,'2018-07-03 12:04:43'),(13,1,69,1,1,1,1,'2018-07-06 20:59:15'),(14,1,70,1,1,1,1,'2018-07-06 20:59:39'),(23,1,12,1,1,1,1,'2018-07-06 21:00:38'),(24,1,13,1,1,1,1,'2018-07-06 21:03:28'),(26,1,15,1,1,1,0,'2018-07-02 22:39:21'),(28,1,19,1,1,1,0,'2018-07-02 22:46:10'),(29,1,20,1,1,1,1,'2018-07-06 21:04:50'),(30,1,76,1,1,1,0,'2018-07-02 22:46:10'),(31,1,21,1,1,1,0,'2018-07-02 22:46:38'),(32,1,22,1,1,1,1,'2018-07-02 22:47:05'),(33,1,23,1,1,1,1,'2018-07-06 21:05:17'),(34,1,24,1,1,1,1,'2018-07-06 21:05:39'),(35,1,25,1,1,1,1,'2018-07-06 21:07:35'),(37,1,77,1,1,1,1,'2018-07-06 21:04:50'),(43,1,32,1,1,1,1,'2018-07-06 21:37:05'),(44,1,33,1,1,1,1,'2018-07-06 21:37:29'),(45,1,34,1,1,1,1,'2018-07-06 21:38:59'),(46,1,35,1,1,1,1,'2018-07-06 21:39:34'),(47,1,104,1,1,1,1,'2018-07-06 21:38:08'),(48,1,37,1,1,1,1,'2018-07-06 21:40:30'),(49,1,38,1,1,1,1,'2018-07-09 16:30:27'),(53,1,43,1,1,1,1,'2018-07-10 20:45:31'),(58,1,52,1,1,0,1,'2018-07-09 14:34:43'),(61,1,55,1,1,1,1,'2018-07-02 20:39:16'),(67,1,61,1,1,0,1,'2018-07-09 17:14:19'),(68,1,62,1,1,0,1,'2018-07-09 17:14:19'),(69,1,63,1,1,0,1,'2018-07-09 15:06:38'),(70,1,64,1,1,1,1,'2018-07-09 14:17:19'),(71,1,65,1,1,1,1,'2018-07-09 14:26:21'),(72,1,66,1,1,1,1,'2018-07-09 14:28:09'),(73,1,67,1,1,1,1,'2018-07-09 14:29:47'),(74,1,79,1,1,0,1,'2018-07-02 23:19:53'),(75,1,80,1,1,1,1,'2018-07-06 20:56:23'),(76,1,81,1,1,1,1,'2018-07-06 20:56:23'),(78,1,83,1,1,1,1,'2018-07-06 20:56:23'),(79,1,84,1,1,1,1,'2018-07-06 20:56:23'),(80,1,85,1,1,1,1,'2018-07-12 11:31:00'),(83,1,88,1,1,1,0,'2018-07-03 23:19:20'),(87,1,92,1,1,1,1,'2018-06-26 14:48:43'),(88,1,93,1,1,1,1,'2018-07-09 12:39:20'),(94,1,82,1,1,1,1,'2018-07-06 20:56:23'),(120,1,39,1,1,1,1,'2018-07-06 21:41:28'),(140,1,110,1,1,1,1,'2018-07-06 21:10:08'),(141,1,111,1,1,1,1,'2018-07-06 21:11:28'),(142,1,112,1,1,1,1,'2018-07-06 21:11:28'),(145,1,94,1,1,0,0,'2018-07-09 12:35:40'),(147,2,43,1,1,1,1,'2018-06-30 19:01:24'),(148,2,44,1,0,0,0,'2018-06-27 22:32:09'),(149,2,46,1,0,0,0,'2018-06-28 11:41:41'),(156,1,9,1,1,1,1,'2018-07-06 20:59:53'),(157,1,10,1,1,1,1,'2018-07-06 21:00:12'),(159,1,40,1,1,1,1,'2018-07-09 16:24:40'),(160,1,41,1,1,1,1,'2018-07-06 21:42:09'),(161,1,42,1,1,1,1,'2018-07-09 16:28:14'),(169,1,27,1,1,0,1,'2018-07-02 22:51:58'),(178,1,54,1,0,1,0,'2018-07-05 20:24:22'),(179,1,56,1,0,1,0,'2018-07-05 20:24:22'),(180,1,57,1,0,1,0,'2018-07-05 20:24:22'),(181,1,58,1,0,1,0,'2018-07-05 20:24:22'),(182,1,59,1,0,1,0,'2018-07-05 20:24:22'),(183,1,60,1,0,1,0,'2018-07-05 20:24:22'),(190,1,105,1,0,0,0,'2018-07-02 22:28:25'),(193,1,6,1,0,0,0,'2018-07-02 22:34:46'),(194,1,68,1,0,0,0,'2018-07-02 22:34:46'),(196,1,72,1,0,0,0,'2018-07-02 22:34:46'),(197,1,73,1,0,0,0,'2018-07-02 22:34:46'),(198,1,74,1,0,0,0,'2018-07-02 22:34:46'),(199,1,75,1,0,0,0,'2018-07-02 22:34:46'),(201,1,14,1,0,0,0,'2018-07-02 22:37:03'),(203,1,16,1,0,0,0,'2018-07-02 22:39:21'),(204,1,26,1,0,0,0,'2018-07-02 22:47:05'),(206,1,29,1,0,0,0,'2018-07-02 22:58:54'),(207,1,30,1,0,0,0,'2018-07-02 22:58:54'),(208,1,31,1,0,0,0,'2018-07-02 23:00:36'),(215,1,50,1,0,0,0,'2018-07-02 23:19:53'),(216,1,51,1,0,0,0,'2018-07-02 23:19:53'),(222,1,1,1,1,1,1,'2018-07-10 20:45:31'),(225,1,108,1,1,1,1,'2018-07-09 13:32:26'),(227,1,91,1,0,0,0,'2018-07-03 13:04:27'),(229,1,89,1,0,0,0,'2018-07-03 13:15:53'),(230,10,53,0,1,0,0,'2018-07-03 15:07:55'),(231,10,54,0,0,1,0,'2018-07-03 15:07:55'),(232,10,55,1,1,1,1,'2018-07-03 15:13:42'),(233,10,56,0,0,1,0,'2018-07-03 15:07:55'),(235,10,58,0,0,1,0,'2018-07-03 15:07:55'),(236,10,59,0,0,1,0,'2018-07-03 15:07:55'),(239,10,1,1,1,1,1,'2018-07-03 15:31:43'),(241,10,3,1,0,0,0,'2018-07-03 15:38:56'),(242,10,2,1,0,0,0,'2018-07-03 15:39:39'),(243,10,4,1,0,1,1,'2018-07-03 15:46:24'),(245,10,107,1,0,0,0,'2018-07-03 15:51:41'),(246,10,5,1,1,0,1,'2018-07-03 15:53:18'),(247,10,7,1,1,1,1,'2018-07-03 15:57:07'),(248,10,68,1,0,0,0,'2018-07-03 15:57:53'),(249,10,69,1,1,1,1,'2018-07-03 16:04:46'),(250,10,70,1,0,0,1,'2018-07-03 16:07:40'),(251,10,72,1,0,0,0,'2018-07-03 16:11:46'),(252,10,73,1,0,0,0,'2018-07-03 16:11:46'),(253,10,74,1,0,0,0,'2018-07-03 16:13:34'),(254,10,75,1,0,0,0,'2018-07-03 16:13:34'),(255,10,9,1,1,1,1,'2018-07-03 16:17:22'),(256,10,10,1,1,1,1,'2018-07-03 16:18:09'),(257,10,11,1,0,0,0,'2018-07-03 16:18:09'),(258,10,12,1,1,1,1,'2018-07-03 16:23:40'),(259,10,13,1,1,1,1,'2018-07-03 16:23:40'),(260,10,14,1,0,0,0,'2018-07-03 16:23:53'),(261,10,15,1,1,1,0,'2018-07-03 16:26:28'),(262,10,16,1,0,0,0,'2018-07-03 16:27:12'),(263,10,17,1,1,1,1,'2018-07-03 16:29:30'),(264,10,19,1,1,1,0,'2018-07-03 16:30:45'),(265,10,20,1,1,1,1,'2018-07-03 16:33:51'),(266,10,76,1,0,0,0,'2018-07-03 16:36:21'),(267,10,21,1,1,1,0,'2018-07-03 16:37:45'),(268,10,22,1,1,1,1,'2018-07-03 16:40:00'),(269,10,23,1,1,1,1,'2018-07-03 16:42:16'),(270,10,24,1,1,1,1,'2018-07-03 16:42:49'),(271,10,25,1,1,1,1,'2018-07-03 16:42:49'),(272,10,26,1,0,0,0,'2018-07-03 16:43:25'),(273,10,77,1,1,1,1,'2018-07-03 16:44:57'),(274,10,27,1,1,0,1,'2018-07-03 16:45:36'),(275,10,28,1,1,1,1,'2018-07-03 16:48:09'),(276,10,29,1,0,0,0,'2018-07-03 16:49:03'),(277,10,30,1,0,0,0,'2018-07-03 16:49:03'),(278,10,31,1,0,0,0,'2018-07-03 16:49:03'),(279,10,32,1,1,1,1,'2018-07-03 16:50:42'),(280,10,33,1,1,1,1,'2018-07-03 16:51:32'),(281,10,34,1,1,1,1,'2018-07-03 16:53:03'),(282,10,35,1,1,1,1,'2018-07-03 16:53:41'),(283,10,104,1,1,1,1,'2018-07-03 16:55:43'),(284,10,37,1,1,1,1,'2018-07-03 16:57:42'),(285,10,38,1,1,1,1,'2018-07-03 16:58:56'),(286,10,39,1,1,1,1,'2018-07-03 17:00:39'),(287,10,40,1,1,1,1,'2018-07-03 17:02:22'),(288,10,41,1,1,1,1,'2018-07-03 17:03:54'),(289,10,42,1,1,1,1,'2018-07-03 17:04:31'),(290,10,43,1,1,1,1,'2018-07-03 17:06:15'),(291,10,44,1,0,0,0,'2018-07-03 17:07:06'),(292,10,46,1,0,0,0,'2018-07-03 17:07:06'),(293,10,50,1,0,0,0,'2018-07-03 17:07:59'),(294,10,51,1,0,0,0,'2018-07-03 17:07:59'),(295,10,60,0,0,1,0,'2018-07-03 17:10:05'),(296,10,61,1,1,1,1,'2018-07-03 17:11:52'),(297,10,62,1,1,1,1,'2018-07-03 17:13:53'),(298,10,63,1,1,0,0,'2018-07-03 17:14:37'),(299,10,64,1,1,1,1,'2018-07-03 17:15:27'),(300,10,65,1,1,1,1,'2018-07-03 17:17:51'),(301,10,66,1,1,1,1,'2018-07-03 17:17:51'),(302,10,67,1,0,0,0,'2018-07-03 17:17:51'),(303,10,78,1,1,1,1,'2018-07-04 15:25:04'),(307,1,126,1,0,0,0,'2018-07-03 20:41:13'),(310,1,119,1,0,0,0,'2018-07-03 21:30:00'),(311,1,120,1,0,0,0,'2018-07-03 21:30:00'),(312,1,107,1,0,0,0,'2018-07-03 21:30:12'),(313,1,122,1,0,0,0,'2018-07-03 21:34:37'),(315,1,123,1,0,0,0,'2018-07-03 21:42:03'),(317,1,124,1,0,0,0,'2018-07-03 21:44:14'),(320,1,47,1,0,0,0,'2018-07-03 22:16:12'),(321,1,121,1,0,0,0,'2018-07-03 22:16:12'),(322,1,109,1,1,1,1,'2018-07-03 22:25:54'),(369,1,102,1,1,1,1,'2018-07-11 23:16:47'),(372,10,79,1,1,0,0,'2018-07-04 15:25:04'),(373,10,80,1,1,1,1,'2018-07-04 15:38:09'),(374,10,81,1,1,1,1,'2018-07-04 15:38:50'),(375,10,82,1,1,1,1,'2018-07-04 15:41:54'),(376,10,83,1,1,1,1,'2018-07-04 15:42:55'),(377,10,84,1,1,1,1,'2018-07-04 15:45:26'),(378,10,85,1,1,1,1,'2018-07-04 15:47:54'),(379,10,86,1,1,1,1,'2018-07-04 16:01:18'),(380,10,87,1,0,0,0,'2018-07-04 16:04:49'),(381,10,88,1,1,1,0,'2018-07-04 16:06:20'),(382,10,89,1,0,0,0,'2018-07-04 16:06:51'),(383,10,90,1,1,0,1,'2018-07-04 16:10:01'),(384,10,91,1,0,0,0,'2018-07-04 16:10:01'),(385,10,108,1,1,1,1,'2018-07-04 16:12:46'),(386,10,109,1,1,1,1,'2018-07-04 16:13:26'),(387,10,110,1,1,1,1,'2018-07-04 16:17:43'),(388,10,111,1,1,1,1,'2018-07-04 16:18:21'),(389,10,112,1,1,1,1,'2018-07-04 16:20:06'),(390,10,127,1,0,0,0,'2018-07-04 16:20:06'),(391,10,93,1,1,1,1,'2018-07-04 16:22:14'),(392,10,94,1,1,0,0,'2018-07-04 16:23:02'),(394,10,95,1,0,0,0,'2018-07-04 16:23:44'),(395,10,102,1,1,1,1,'2018-07-04 16:26:02'),(396,10,106,1,0,0,0,'2018-07-04 16:26:39'),(397,10,113,1,0,0,0,'2018-07-04 16:27:37'),(398,10,114,1,0,0,0,'2018-07-04 16:27:37'),(399,10,115,1,0,0,0,'2018-07-04 16:33:45'),(400,10,116,1,0,0,0,'2018-07-04 16:33:45'),(401,10,117,1,0,0,0,'2018-07-04 16:34:43'),(402,10,118,1,0,0,0,'2018-07-04 16:34:43'),(411,1,2,1,0,0,0,'2018-07-04 19:31:10'),(412,1,11,1,0,0,0,'2018-07-04 20:09:05'),(416,2,3,1,1,1,1,'2018-07-10 18:02:12'),(428,2,4,1,1,1,1,'2018-07-05 13:25:38'),(432,1,128,0,1,0,1,'2018-07-05 19:24:50'),(434,1,125,1,0,0,0,'2018-07-06 21:14:26'),(435,1,96,1,1,1,1,'2018-07-09 12:18:54'),(437,1,98,1,1,1,1,'2018-07-09 12:29:17'),(444,1,99,1,0,0,0,'2018-07-06 22:56:22'),(445,1,48,1,0,0,0,'2018-07-06 23:04:35'),(446,1,49,1,0,0,0,'2018-07-06 23:04:35'),(448,1,71,1,0,0,0,'2018-07-08 15:02:06'),(453,1,106,1,0,0,0,'2018-07-09 12:02:33'),(454,1,113,1,0,0,0,'2018-07-09 12:02:33'),(455,1,114,1,0,0,0,'2018-07-09 12:02:33'),(456,1,115,1,0,0,0,'2018-07-09 12:02:33'),(457,1,116,1,0,0,0,'2018-07-09 12:02:33'),(458,1,117,1,0,0,0,'2018-07-09 12:02:33'),(459,1,118,1,0,0,0,'2018-07-09 12:02:33'),(461,1,97,1,0,0,0,'2018-07-09 12:15:16'),(462,1,95,1,0,0,0,'2018-07-09 12:33:41'),(464,1,86,1,1,1,1,'2018-07-09 17:24:48'),(466,1,129,0,1,0,1,'2018-07-09 12:54:30'),(467,1,87,1,0,0,0,'2018-07-09 12:56:59'),(468,1,90,1,1,0,1,'2018-07-09 13:07:50'),(471,1,53,0,1,0,0,'2018-07-09 14:35:44'),(474,1,130,1,1,0,1,'2018-07-09 22:11:36'),(476,1,131,1,0,0,0,'2018-07-09 16:08:32'),(477,2,1,1,1,1,1,'2018-07-11 18:11:27'),(478,2,2,1,0,0,0,'2018-07-10 18:02:12'),(479,2,47,1,0,0,0,'2018-07-10 18:02:12'),(480,2,105,1,0,0,0,'2018-07-10 18:02:12'),(482,2,119,1,0,0,0,'2018-07-10 18:02:12'),(483,2,120,1,0,0,0,'2018-07-10 18:02:12'),(485,2,15,1,1,1,0,'2018-07-10 18:02:12'),(486,2,16,1,0,0,0,'2018-07-10 18:02:12'),(487,2,122,1,0,0,0,'2018-07-10 18:02:12'),(492,2,21,1,0,0,0,'2018-07-12 11:35:27'),(493,2,22,1,0,0,0,'2018-07-12 11:35:27'),(494,2,23,1,0,0,0,'2018-07-12 11:35:27'),(495,2,24,1,0,0,0,'2018-07-12 11:35:27'),(496,2,25,1,0,0,0,'2018-07-12 11:35:27'),(498,2,77,1,0,0,0,'2018-07-12 11:35:27'),(499,2,27,1,1,0,1,'2018-07-10 18:02:12'),(502,2,93,1,1,1,1,'2018-07-10 18:02:12'),(503,2,94,1,1,0,0,'2018-07-10 18:02:12'),(504,2,95,1,0,0,0,'2018-07-10 18:02:12'),(505,3,5,1,1,0,1,'2018-07-10 18:22:30'),(506,3,6,1,0,0,0,'2018-07-10 18:22:30'),(507,3,7,1,1,1,1,'2018-07-10 18:22:30'),(508,3,8,1,1,1,1,'2018-07-10 18:22:30'),(509,3,68,1,0,0,0,'2018-07-10 18:22:30'),(510,3,69,1,1,1,1,'2018-07-10 18:22:30'),(511,3,70,1,1,1,1,'2018-07-10 18:22:30'),(512,3,71,1,0,0,0,'2018-07-10 18:22:30'),(513,3,72,1,0,0,0,'2018-07-10 18:22:30'),(514,3,73,1,0,0,0,'2018-07-10 18:22:30'),(515,3,74,1,0,0,0,'2018-07-10 18:22:30'),(517,3,75,1,0,0,0,'2018-07-10 18:25:38'),(518,3,9,1,1,1,1,'2018-07-10 18:25:38'),(519,3,10,1,1,1,1,'2018-07-10 18:25:38'),(520,3,11,1,0,0,0,'2018-07-10 18:25:38'),(521,3,12,1,1,1,1,'2018-07-10 18:32:00'),(522,3,13,1,1,1,1,'2018-07-10 18:32:00'),(523,3,14,1,0,0,0,'2018-07-10 18:32:00'),(524,3,86,1,1,1,1,'2018-07-10 18:33:44'),(525,3,87,1,0,0,0,'2018-07-10 18:33:44'),(526,3,88,1,1,1,0,'2018-07-10 18:33:44'),(527,3,89,1,0,0,0,'2018-07-10 18:33:44'),(528,3,90,1,1,0,1,'2018-07-10 18:33:44'),(529,3,91,1,0,0,0,'2018-07-10 18:33:44'),(530,3,108,1,1,1,1,'2018-07-10 18:33:44'),(531,3,109,1,1,1,1,'2018-07-10 18:33:44'),(532,3,110,1,1,1,1,'2018-07-10 18:33:44'),(533,3,111,1,1,1,1,'2018-07-10 18:33:44'),(534,3,112,1,1,1,1,'2018-07-10 18:33:44'),(535,3,127,1,0,0,0,'2018-07-10 18:33:44'),(536,3,129,0,1,0,1,'2018-07-10 18:33:44'),(537,3,43,1,1,1,1,'2018-07-10 18:34:49'),(538,3,44,1,0,0,0,'2018-07-10 18:34:49'),(539,3,46,1,0,0,0,'2018-07-10 18:34:49'),(540,3,31,1,0,0,0,'2018-07-10 18:35:38'),(541,3,32,1,1,1,1,'2018-07-10 18:35:38'),(542,3,33,1,1,1,1,'2018-07-10 18:35:38'),(543,3,34,1,1,1,1,'2018-07-10 18:35:38'),(544,3,35,1,1,1,1,'2018-07-10 18:35:38'),(545,3,104,1,1,1,1,'2018-07-10 18:35:38'),(546,3,37,1,1,1,1,'2018-07-10 18:37:17'),(547,3,38,1,1,1,1,'2018-07-10 18:37:17'),(548,3,39,1,1,1,1,'2018-07-10 18:37:17'),(549,3,124,1,0,0,0,'2018-07-10 18:37:17'),(553,6,78,1,1,1,1,'2018-07-10 18:47:18'),(554,6,79,1,1,0,1,'2018-07-10 18:47:18'),(555,6,80,1,1,1,1,'2018-07-10 18:47:18'),(556,6,81,1,1,1,1,'2018-07-10 18:47:18'),(557,6,82,1,1,1,1,'2018-07-10 18:47:18'),(558,6,83,1,1,1,1,'2018-07-10 18:47:18'),(559,6,84,1,1,1,1,'2018-07-10 18:47:18'),(560,6,85,1,1,1,1,'2018-07-10 18:47:18'),(561,6,86,1,0,0,0,'2018-07-10 18:56:10'),(574,6,43,1,1,1,1,'2018-07-10 18:50:33'),(575,6,44,1,0,0,0,'2018-07-10 18:50:33'),(576,6,46,1,0,0,0,'2018-07-10 18:50:33'),(578,6,102,1,1,1,1,'2018-07-10 19:05:33'),(579,4,28,1,1,1,1,'2018-07-10 19:08:54'),(580,4,29,1,0,0,0,'2018-07-10 19:08:54'),(581,4,30,1,0,0,0,'2018-07-10 19:08:54'),(582,4,123,1,0,0,0,'2018-07-10 19:08:54'),(583,4,86,1,0,0,0,'2018-07-10 19:09:13'),(584,4,43,1,1,1,1,'2018-07-10 19:10:14'),(585,4,44,1,0,0,0,'2018-07-10 19:10:14'),(586,4,46,1,0,0,0,'2018-07-10 19:10:14'),(588,2,102,1,1,1,1,'2018-07-12 11:32:45'),(589,2,106,1,0,0,0,'2018-07-10 19:10:37'),(590,2,117,1,0,0,0,'2018-07-10 19:10:37'),(591,3,40,1,1,1,1,'2018-07-10 19:13:12'),(592,3,41,1,1,1,1,'2018-07-10 19:13:12'),(593,3,42,1,1,1,1,'2018-07-10 19:13:12'),(594,3,125,1,0,0,0,'2018-07-10 19:13:12'),(595,3,48,1,0,0,0,'2018-07-10 19:13:12'),(596,3,49,1,0,0,0,'2018-07-10 19:13:12'),(597,3,102,1,1,1,1,'2018-07-10 19:13:12'),(598,3,106,1,0,0,0,'2018-07-10 19:13:12'),(599,3,113,1,0,0,0,'2018-07-10 19:13:12'),(600,3,114,1,0,0,0,'2018-07-10 19:13:12'),(601,3,115,1,0,0,0,'2018-07-10 19:13:12'),(602,3,116,1,0,0,0,'2018-07-10 19:13:12'),(603,3,117,1,0,0,0,'2018-07-10 19:13:12'),(604,3,118,1,0,0,0,'2018-07-10 19:13:12'),(609,6,117,1,0,0,0,'2018-07-10 19:15:48'),(611,2,86,1,0,0,0,'2018-07-10 19:23:49'),(612,1,44,1,0,0,0,'2018-07-10 20:45:31'),(613,1,46,1,0,0,0,'2018-07-10 20:45:31'),(616,1,127,1,0,0,0,'2018-07-11 14:07:46'),(617,2,17,1,1,1,1,'2018-07-11 18:10:14'),(618,2,19,1,1,1,0,'2018-07-11 18:10:14'),(619,2,20,1,1,1,1,'2018-07-11 18:10:14'),(620,2,76,1,1,1,0,'2018-07-11 18:10:14'),(621,2,107,1,0,0,0,'2018-07-11 18:11:27'),(622,2,121,1,0,0,0,'2018-07-11 18:11:27'),(623,2,128,0,1,0,1,'2018-07-11 18:11:27'),(625,1,28,1,1,1,1,'2018-07-11 20:42:18'),(626,6,1,1,0,0,0,'2018-07-12 11:38:47'),(627,6,21,1,0,0,0,'2018-07-12 11:38:47'),(628,6,22,1,0,0,0,'2018-07-12 11:38:47'),(629,6,23,1,0,0,0,'2018-07-12 11:38:47'),(630,6,24,1,0,0,0,'2018-07-12 11:38:47'),(631,6,25,1,0,0,0,'2018-07-12 11:38:47'),(632,6,77,1,0,0,0,'2018-07-12 11:38:47'),(633,6,106,1,0,0,0,'2018-07-12 11:38:47'),(634,4,102,1,1,1,1,'2018-07-12 11:39:23'),(635,4,106,1,0,0,0,'2018-07-12 11:39:23'),(636,4,117,1,0,0,0,'2018-07-12 11:39:23'),(637,9,1,1,1,1,1,'2018-12-17 17:03:12'),(638,9,86,1,1,1,1,'2018-12-17 17:03:12'),(640,3,136,1,1,1,1,'2019-11-21 14:19:30'),(641,3,138,1,1,1,1,'2019-11-27 16:25:35'),(642,3,139,1,1,1,1,'2019-11-27 16:25:35'),(643,3,140,1,1,1,1,'2019-11-28 17:34:58');

/*Table structure for table `room_types` */

DROP TABLE IF EXISTS `room_types`;

CREATE TABLE `room_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `room_type` varchar(200) DEFAULT NULL,
  `description` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `room_types` */

/*Table structure for table `sch_settings` */

DROP TABLE IF EXISTS `sch_settings`;

CREATE TABLE `sch_settings` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `header_image` varchar(250) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `address` text,
  `lang_id` int(11) DEFAULT NULL,
  `dise_code` varchar(50) DEFAULT NULL,
  `date_format` varchar(50) NOT NULL,
  `currency` varchar(50) NOT NULL,
  `currency_symbol` varchar(50) NOT NULL,
  `is_rtl` varchar(10) DEFAULT 'disabled',
  `timezone` varchar(30) DEFAULT 'UTC',
  `session_id` int(11) DEFAULT NULL,
  `start_month` varchar(40) NOT NULL,
  `image` varchar(100) DEFAULT NULL,
  `theme` varchar(200) NOT NULL DEFAULT 'default.jpg',
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `cron_secret_key` varchar(100) NOT NULL,
  `fee_due_days` int(3) DEFAULT '0',
  `class_teacher` varchar(100) NOT NULL,
  `public_holiday` varchar(100) NOT NULL,
  `is_conventional` enum('yes','no') NOT NULL DEFAULT 'no',
  `facebook_link` varchar(250) NOT NULL,
  `youtube_link` varchar(250) NOT NULL,
  `twitter_link` varchar(250) NOT NULL,
  `linkedin_link` varchar(250) NOT NULL,
  `tag_line` text NOT NULL,
  `first_day` varchar(10) NOT NULL,
  `datechooser` enum('ad','bs') NOT NULL DEFAULT 'ad',
  `show_gpa` tinyint(1) NOT NULL DEFAULT '1',
  `show_marks` tinyint(1) NOT NULL DEFAULT '1',
  `show_grade` tinyint(1) NOT NULL DEFAULT '1',
  `pan_no` varchar(255) NOT NULL,
  `established_on` date DEFAULT NULL,
  `established_on_bs` varchar(255) NOT NULL,
  KEY `lang_id` (`lang_id`),
  KEY `session_id` (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `sch_settings` */

insert  into `sch_settings`(`id`,`name`,`header_image`,`email`,`phone`,`address`,`lang_id`,`dise_code`,`date_format`,`currency`,`currency_symbol`,`is_rtl`,`timezone`,`session_id`,`start_month`,`image`,`theme`,`is_active`,`created_at`,`updated_at`,`cron_secret_key`,`fee_due_days`,`class_teacher`,`public_holiday`,`is_conventional`,`facebook_link`,`youtube_link`,`twitter_link`,`linkedin_link`,`tag_line`,`first_day`,`datechooser`,`show_gpa`,`show_marks`,`show_grade`,`pan_no`,`established_on`,`established_on_bs`) values (1,'Budhanilkantha School','headerlogo.png','yourschoolemail@yoursite.com','014256484','Narayanthan - Baaghdwar Rd, Budhanilkantha 44622',4,'BKNS','m/d/Y','NPR','Rs.','disabled','Asia/Kathmandu',14,'4','logo.png','default.jpg','no','2019-11-21 11:53:42','0000-00-00 00:00:00','',60,'no','Saturday','no','','','','','Center for excellence','Sunday','ad',1,1,1,'1245687465412','2019-11-21','');

/*Table structure for table `school_houses` */

DROP TABLE IF EXISTS `school_houses`;

CREATE TABLE `school_houses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `house_name` varchar(200) NOT NULL,
  `description` varchar(400) NOT NULL,
  `is_active` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `school_houses` */

insert  into `school_houses`(`id`,`house_name`,`description`,`is_active`) values (1,'Red','','yes'),(2,'Blue','','yes'),(3,'Yellow','','yes');

/*Table structure for table `sections` */

DROP TABLE IF EXISTS `sections`;

CREATE TABLE `sections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `section` varchar(60) DEFAULT NULL,
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `sections` */

insert  into `sections`(`id`,`section`,`is_active`,`created_at`,`updated_at`) values (1,'A','no','2019-11-21 11:03:58','0000-00-00 00:00:00'),(2,'B','no','2019-11-21 11:04:00','0000-00-00 00:00:00'),(3,'C','no','2019-11-21 11:04:02','0000-00-00 00:00:00'),(4,'D','no','2019-11-21 11:04:04','0000-00-00 00:00:00');

/*Table structure for table `send_notification` */

DROP TABLE IF EXISTS `send_notification`;

CREATE TABLE `send_notification` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(256) DEFAULT NULL,
  `publish_date` date DEFAULT NULL,
  `date` date DEFAULT NULL,
  `date_bs` varchar(255) NOT NULL,
  `publish_date_bs` varchar(255) NOT NULL,
  `message` text,
  `visible_student` varchar(10) NOT NULL DEFAULT 'no',
  `visible_staff` varchar(10) NOT NULL DEFAULT 'no',
  `visible_parent` varchar(10) NOT NULL DEFAULT 'no',
  `created_by` varchar(60) DEFAULT NULL,
  `created_id` int(11) DEFAULT NULL,
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `send_notification` */

/*Table structure for table `sessions` */

DROP TABLE IF EXISTS `sessions`;

CREATE TABLE `sessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session` varchar(60) DEFAULT NULL,
  `session_bs` varchar(60) DEFAULT NULL,
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

/*Data for the table `sessions` */

insert  into `sessions`(`id`,`session`,`session_bs`,`is_active`,`created_at`,`updated_at`) values (7,'2016-17',NULL,'no','2017-04-20 12:27:19','0000-00-00 00:00:00'),(11,'2017-18',NULL,'no','2017-04-20 12:26:37','0000-00-00 00:00:00'),(13,'2018-19',NULL,'no','2016-08-25 01:11:44','0000-00-00 00:00:00'),(14,'2019-20',NULL,'no','2016-08-25 01:11:55','0000-00-00 00:00:00'),(15,'2020-21',NULL,'no','2016-10-01 11:13:08','0000-00-00 00:00:00'),(16,'2021-22',NULL,'no','2016-10-01 11:13:20','0000-00-00 00:00:00'),(18,'2022-23',NULL,'no','2016-10-01 11:14:02','0000-00-00 00:00:00'),(19,'2023-24',NULL,'no','2016-10-01 11:14:10','0000-00-00 00:00:00'),(20,'2024-25',NULL,'no','2016-10-01 11:14:18','0000-00-00 00:00:00'),(21,'2025-26',NULL,'no','2016-10-01 11:15:10','0000-00-00 00:00:00'),(22,'2026-27',NULL,'no','2016-10-01 11:15:18','0000-00-00 00:00:00'),(23,'2027-28',NULL,'no','2016-10-01 11:15:24','0000-00-00 00:00:00'),(24,'2028-29',NULL,'no','2016-10-01 11:15:30','0000-00-00 00:00:00'),(25,'2029-30',NULL,'no','2016-10-01 11:15:37','0000-00-00 00:00:00');

/*Table structure for table `sms_config` */

DROP TABLE IF EXISTS `sms_config`;

CREATE TABLE `sms_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `api_id` varchar(100) NOT NULL,
  `authkey` varchar(100) NOT NULL,
  `senderid` varchar(100) NOT NULL,
  `contact` text,
  `username` varchar(150) DEFAULT NULL,
  `url` varchar(150) DEFAULT NULL,
  `password` varchar(150) DEFAULT NULL,
  `is_active` varchar(255) DEFAULT 'disabled',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `sms_config` */

/*Table structure for table `source` */

DROP TABLE IF EXISTS `source`;

CREATE TABLE `source` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `source` varchar(100) NOT NULL,
  `description` mediumtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `source` */

/*Table structure for table `staff` */

DROP TABLE IF EXISTS `staff`;

CREATE TABLE `staff` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` varchar(200) NOT NULL,
  `department` varchar(100) NOT NULL,
  `designation` varchar(100) NOT NULL,
  `qualification` varchar(200) NOT NULL,
  `work_exp` varchar(200) NOT NULL,
  `stn_title` varchar(10) NOT NULL,
  `name` varchar(200) NOT NULL,
  `surname` varchar(200) NOT NULL,
  `father_name` varchar(200) NOT NULL,
  `mother_name` varchar(200) NOT NULL,
  `contact_no` varchar(200) NOT NULL,
  `emergency_contact_no` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `dob` date NOT NULL,
  `marital_status` varchar(100) NOT NULL,
  `date_of_joining` date NOT NULL,
  `date_of_leaving` date NOT NULL,
  `local_address` varchar(300) NOT NULL,
  `permanent_address` varchar(200) NOT NULL,
  `note` varchar(200) NOT NULL,
  `image` varchar(200) NOT NULL,
  `password` varchar(250) NOT NULL,
  `gender` varchar(50) NOT NULL,
  `account_title` varchar(200) NOT NULL,
  `bank_account_no` varchar(200) NOT NULL,
  `bank_name` varchar(200) NOT NULL,
  `ifsc_code` varchar(200) NOT NULL,
  `bank_branch` varchar(100) NOT NULL,
  `payscale` varchar(200) NOT NULL,
  `basic_salary` varchar(200) NOT NULL,
  `epf_no` varchar(200) NOT NULL,
  `contract_type` varchar(100) NOT NULL,
  `shift` varchar(100) NOT NULL,
  `location` varchar(100) NOT NULL,
  `facebook` varchar(200) NOT NULL,
  `twitter` varchar(200) NOT NULL,
  `linkedin` varchar(200) NOT NULL,
  `instagram` varchar(200) NOT NULL,
  `resume` varchar(200) NOT NULL,
  `joining_letter` varchar(200) NOT NULL,
  `resignation_letter` varchar(200) NOT NULL,
  `other_document_name` varchar(200) NOT NULL,
  `other_document_file` varchar(200) NOT NULL,
  `user_id` int(11) NOT NULL,
  `is_active` int(11) NOT NULL,
  `verification_code` varchar(100) NOT NULL,
  `dob_bs` varchar(255) NOT NULL,
  `date_of_joining_bs` varchar(255) NOT NULL,
  `date_of_leaving_bs` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `employee_id` (`employee_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Data for the table `staff` */

insert  into `staff`(`id`,`employee_id`,`department`,`designation`,`qualification`,`work_exp`,`stn_title`,`name`,`surname`,`father_name`,`mother_name`,`contact_no`,`emergency_contact_no`,`email`,`dob`,`marital_status`,`date_of_joining`,`date_of_leaving`,`local_address`,`permanent_address`,`note`,`image`,`password`,`gender`,`account_title`,`bank_account_no`,`bank_name`,`ifsc_code`,`bank_branch`,`payscale`,`basic_salary`,`epf_no`,`contract_type`,`shift`,`location`,`facebook`,`twitter`,`linkedin`,`instagram`,`resume`,`joining_letter`,`resignation_letter`,`other_document_name`,`other_document_file`,`user_id`,`is_active`,`verification_code`,`dob_bs`,`date_of_joining_bs`,`date_of_leaving_bs`) values (2,'STF-001','3','3','','','','Super Admin','','','','','','admin@admin.com','0000-00-00','','1970-01-01','0000-00-00','','','','2.jpg','$2y$10$2GIyV8GDB8jpFX3Elqzy8.PcVSBke8uq4MgBVnnXHo/d0pv2z40CW','Male','','','','','','','','','','','','','','','','','','','Other Document','',0,1,'','','',''),(3,'STF-01','1','1','','','Mr.','Donald','Richards','Jonathan Richards','Linda Obrien','1234567890','2345678910','teacher2@test.com','1989-02-07','Single','2019-11-01','0000-00-00','','','','3.jpg','$2y$10$naYXIPDzp6VE63DpztQtN.uO0tGGJqYpIaLZuovvCzTwK4d7d12NK','Male','','','','','','','','','','','','','','','','','','','Other Document','',0,1,'','','',''),(4,'STF-02','1','1','','','Miss','Grace','Simpson','Michael Simpson','Elizabeth Welch','','','teacher1@test.com','1995-07-20','Married','2019-10-03','0000-00-00','','','','4.jpg','$2y$10$mUyW07VQaRoyXaKn6JMji.Tzd6CgFQIBPRJMKxr8rQzio4DTDiZYi','Female','','','','','','','','','','','','','','','','','','','Other Document','',0,1,'','','',''),(5,'STF-03','3','3','','','Miss','Anna','King','Bryan Wade','Janice Diaz','','','account@test.com','1994-12-09','Married','2019-02-27','0000-00-00','','','','5.jpg','$2y$10$Jyy9Xf/2Dyhie/YmjXbkSeAB7lXuGeVFPM1138ITsY9BtVwUduI8O','Female','','','','','','','','','','','','','','','','','','','Other Document','',0,1,'','','',''),(6,'STF-04','1','1','','','Mr.','Stephen','Carr','','','','','stephen@test.com','1999-08-13','','1970-01-01','0000-00-00','','','','','$2y$10$CpiWpRbJHfVj6MAm6GkiZO88wtTzxbT.0PY8Vx9NRl5OUwPehMuJW','Male','','','','','','','','','','','','','','','','','','','Other Document','',0,1,'','','','');

/*Table structure for table `staff_attendance` */

DROP TABLE IF EXISTS `staff_attendance`;

CREATE TABLE `staff_attendance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `date_bs` varchar(255) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `staff_attendance_type_id` int(11) NOT NULL,
  `remark` varchar(200) NOT NULL,
  `is_active` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `staff_attendance` */

/*Table structure for table `staff_attendance_type` */

DROP TABLE IF EXISTS `staff_attendance_type`;

CREATE TABLE `staff_attendance_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(200) NOT NULL,
  `key_value` varchar(200) NOT NULL,
  `is_active` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Data for the table `staff_attendance_type` */

insert  into `staff_attendance_type`(`id`,`type`,`key_value`,`is_active`,`created_at`,`updated_at`) values (1,'Present','<b class=\"text text-success\">P</b>','yes','0000-00-00 00:00:00','0000-00-00 00:00:00'),(2,'Late','<b class=\"text text-warning\">L</b>','yes','0000-00-00 00:00:00','0000-00-00 00:00:00'),(3,'Absent','<b class=\"text text-danger\">A</b>','yes','0000-00-00 00:00:00','0000-00-00 00:00:00'),(4,'Half Day','<b class=\"text text-warning\">F</b>','yes','2018-05-07 07:41:16','0000-00-00 00:00:00'),(5,'Holiday','H','yes','0000-00-00 00:00:00','0000-00-00 00:00:00');

/*Table structure for table `staff_designation` */

DROP TABLE IF EXISTS `staff_designation`;

CREATE TABLE `staff_designation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `designation` varchar(200) NOT NULL,
  `is_active` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `staff_designation` */

insert  into `staff_designation`(`id`,`designation`,`is_active`) values (1,'Primary Teacher','yes'),(2,'Secondary Teacher','yes'),(3,'Admin','yes');

/*Table structure for table `staff_leave_details` */

DROP TABLE IF EXISTS `staff_leave_details`;

CREATE TABLE `staff_leave_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `staff_id` int(11) NOT NULL,
  `leave_type_id` int(11) NOT NULL,
  `alloted_leave` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `staff_leave_details` */

/*Table structure for table `staff_leave_request` */

DROP TABLE IF EXISTS `staff_leave_request`;

CREATE TABLE `staff_leave_request` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `staff_id` int(11) NOT NULL,
  `leave_type_id` int(11) NOT NULL,
  `leave_from` date NOT NULL,
  `leave_to` date NOT NULL,
  `leave_days` int(11) NOT NULL,
  `employee_remark` varchar(200) NOT NULL,
  `admin_remark` varchar(200) NOT NULL,
  `status` varchar(100) NOT NULL,
  `applied_by` varchar(200) NOT NULL,
  `document_file` varchar(200) NOT NULL,
  `date` date NOT NULL,
  `date_bs` varchar(255) NOT NULL,
  `leave_from_bs` varchar(255) NOT NULL,
  `leave_to_bs` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `staff_leave_request` */

/*Table structure for table `staff_payroll` */

DROP TABLE IF EXISTS `staff_payroll`;

CREATE TABLE `staff_payroll` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `basic_salary` int(11) NOT NULL,
  `pay_scale` varchar(200) NOT NULL,
  `grade` varchar(50) NOT NULL,
  `is_active` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `staff_payroll` */

/*Table structure for table `staff_payslip` */

DROP TABLE IF EXISTS `staff_payslip`;

CREATE TABLE `staff_payslip` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `staff_id` int(11) NOT NULL,
  `basic` int(11) NOT NULL,
  `total_allowance` int(11) NOT NULL,
  `total_deduction` int(11) NOT NULL,
  `leave_deduction` int(11) NOT NULL,
  `tax` varchar(200) NOT NULL,
  `net_salary` int(11) NOT NULL,
  `status` varchar(100) NOT NULL,
  `month` varchar(200) NOT NULL,
  `year` varchar(200) NOT NULL,
  `payment_mode` varchar(200) NOT NULL,
  `payment_date` date NOT NULL,
  `payment_date_bs` varchar(255) NOT NULL,
  `remark` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `staff_payslip` */

/*Table structure for table `staff_roles` */

DROP TABLE IF EXISTS `staff_roles`;

CREATE TABLE `staff_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) DEFAULT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `is_active` int(11) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`),
  KEY `staff_id` (`staff_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Data for the table `staff_roles` */

insert  into `staff_roles`(`id`,`role_id`,`staff_id`,`is_active`,`created_at`,`updated_at`) values (2,7,2,0,'2019-11-21 10:36:35','0000-00-00 00:00:00'),(3,2,3,0,'2019-11-21 11:10:17','0000-00-00 00:00:00'),(4,2,4,0,'2019-11-21 11:19:48','0000-00-00 00:00:00'),(5,3,5,0,'2019-11-21 14:21:38','0000-00-00 00:00:00'),(6,2,6,0,'2019-11-27 15:52:19','0000-00-00 00:00:00');

/*Table structure for table `staff_timeline` */

DROP TABLE IF EXISTS `staff_timeline`;

CREATE TABLE `staff_timeline` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `staff_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `timeline_date` date NOT NULL,
  `description` varchar(300) NOT NULL,
  `document` varchar(200) NOT NULL,
  `status` varchar(200) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `staff_timeline` */

/*Table structure for table `student_attendences` */

DROP TABLE IF EXISTS `student_attendences`;

CREATE TABLE `student_attendences` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_session_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `date_bs` varchar(255) NOT NULL,
  `attendence_type_id` int(11) DEFAULT NULL,
  `remark` varchar(200) NOT NULL,
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `student_session_id` (`student_session_id`),
  KEY `attendence_type_id` (`attendence_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `student_attendences` */

insert  into `student_attendences`(`id`,`student_session_id`,`date`,`date_bs`,`attendence_type_id`,`remark`,`is_active`,`created_at`,`updated_at`) values (1,1,'2019-11-27','',1,'','no','2019-11-27 15:54:02','0000-00-00 00:00:00'),(2,2,'2019-11-27','',1,'','no','2019-11-27 15:54:02','0000-00-00 00:00:00');

/*Table structure for table `student_doc` */

DROP TABLE IF EXISTS `student_doc`;

CREATE TABLE `student_doc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) DEFAULT NULL,
  `title` varchar(200) DEFAULT NULL,
  `doc` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `student_doc` */

/*Table structure for table `student_fees` */

DROP TABLE IF EXISTS `student_fees`;

CREATE TABLE `student_fees` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_session_id` int(11) DEFAULT NULL,
  `feemaster_id` int(11) DEFAULT NULL,
  `amount` float(10,2) DEFAULT NULL,
  `amount_discount` float(10,2) NOT NULL,
  `amount_fine` float(10,2) NOT NULL DEFAULT '0.00',
  `description` text,
  `date` date DEFAULT '0000-00-00',
  `payment_mode` varchar(50) NOT NULL,
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `student_fees` */

/*Table structure for table `student_fees_deposite` */

DROP TABLE IF EXISTS `student_fees_deposite`;

CREATE TABLE `student_fees_deposite` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_fees_master_id` int(11) DEFAULT NULL,
  `fee_groups_feetype_id` int(11) DEFAULT NULL,
  `amount_detail` text,
  `is_active` varchar(10) NOT NULL DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `student_fees_master_id` (`student_fees_master_id`),
  KEY `fee_groups_feetype_id` (`fee_groups_feetype_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Data for the table `student_fees_deposite` */

insert  into `student_fees_deposite`(`id`,`student_fees_master_id`,`fee_groups_feetype_id`,`amount_detail`,`is_active`,`created_at`) values (1,2,1,'{\"1\":{\"amount\":\"1800.00\",\"date\":\"2019-11-21\",\"amount_discount\":\"200\",\"amount_fine\":\"0\",\"description\":\" Collected By:  Super Admin \",\"payment_mode\":\"Cash\",\"inv_no\":1}}','no','2019-11-21 16:09:26'),(2,2,2,'{\"1\":{\"amount\":\"500\",\"date\":\"2019-11-21\",\"amount_discount\":\"0\",\"amount_fine\":\"0\",\"description\":\" Collected By:  Super Admin \",\"payment_mode\":\"Cash\",\"inv_no\":1}}','no','2019-11-21 16:09:42'),(4,1,2,'{\"1\":{\"amount\":\"500\",\"date\":\"2019-11-21\",\"amount_discount\":\"0\",\"amount_fine\":\"0\",\"description\":\" Collected By: Miss Anna King\",\"payment_mode\":\"Cash\",\"inv_no\":1}}','no','2019-11-21 16:11:55'),(6,1,1,'{\"1\":{\"amount\":\"2000\",\"date\":\"2019-11-21\",\"amount_discount\":\"0\",\"amount_fine\":\"0\",\"description\":\" Collected By: Miss Anna King\",\"payment_mode\":\"Cash\",\"inv_no\":1}}','no','2019-11-21 16:41:09');

/*Table structure for table `student_fees_discounts` */

DROP TABLE IF EXISTS `student_fees_discounts`;

CREATE TABLE `student_fees_discounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_session_id` int(11) DEFAULT NULL,
  `fees_discount_id` int(11) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'assigned',
  `payment_id` varchar(50) DEFAULT NULL,
  `description` text,
  `is_active` varchar(10) NOT NULL DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `student_session_id` (`student_session_id`),
  KEY `fees_discount_id` (`fees_discount_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `student_fees_discounts` */

insert  into `student_fees_discounts`(`id`,`student_session_id`,`fees_discount_id`,`status`,`payment_id`,`description`,`is_active`,`created_at`) values (1,2,1,'applied','1/1',' Collected By:  Super Admin ','no','2019-11-21 16:09:26');

/*Table structure for table `student_fees_master` */

DROP TABLE IF EXISTS `student_fees_master`;

CREATE TABLE `student_fees_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `is_system` int(1) NOT NULL DEFAULT '0',
  `student_session_id` int(11) DEFAULT NULL,
  `fee_session_group_id` int(11) DEFAULT NULL,
  `amount` float(10,2) DEFAULT '0.00',
  `is_active` varchar(10) NOT NULL DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `student_session_id` (`student_session_id`),
  KEY `fee_session_group_id` (`fee_session_group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `student_fees_master` */

insert  into `student_fees_master`(`id`,`is_system`,`student_session_id`,`fee_session_group_id`,`amount`,`is_active`,`created_at`) values (1,0,1,7,0.00,'no','2019-11-21 16:05:27'),(2,0,2,7,0.00,'no','2019-11-21 16:05:27');

/*Table structure for table `student_leaves` */

DROP TABLE IF EXISTS `student_leaves`;

CREATE TABLE `student_leaves` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` int(11) unsigned NOT NULL,
  `student_session_id` int(11) unsigned NOT NULL,
  `class_teacher_id` int(11) unsigned NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `applied_on` datetime NOT NULL,
  `from_date_bs` varchar(255) NOT NULL,
  `to_date_bs` varchar(255) NOT NULL,
  `applied_on_bs` varchar(255) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `student_leaves` */

/*Table structure for table `student_session` */

DROP TABLE IF EXISTS `student_session`;

CREATE TABLE `student_session` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session_id` int(11) DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `section_id` int(11) DEFAULT NULL,
  `route_id` int(11) NOT NULL,
  `hostel_room_id` int(11) NOT NULL,
  `vehroute_id` int(10) DEFAULT NULL,
  `transport_fees` float(10,2) NOT NULL DEFAULT '0.00',
  `fees_discount` float(10,2) NOT NULL DEFAULT '0.00',
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `student_session` */

insert  into `student_session`(`id`,`session_id`,`student_id`,`class_id`,`section_id`,`route_id`,`hostel_room_id`,`vehroute_id`,`transport_fees`,`fees_discount`,`is_active`,`created_at`,`updated_at`) values (1,14,1,1,1,0,0,NULL,0.00,0.00,'no','2019-11-21 11:33:26','0000-00-00 00:00:00'),(2,14,2,1,1,0,0,NULL,0.00,0.00,'no','2019-11-21 11:37:28','0000-00-00 00:00:00'),(3,14,3,2,2,0,0,NULL,0.00,0.00,'no','2019-11-22 10:43:33','0000-00-00 00:00:00');

/*Table structure for table `student_sibling` */

DROP TABLE IF EXISTS `student_sibling`;

CREATE TABLE `student_sibling` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) DEFAULT NULL,
  `sibling_student_id` int(11) DEFAULT NULL,
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `student_sibling` */

/*Table structure for table `student_timeline` */

DROP TABLE IF EXISTS `student_timeline`;

CREATE TABLE `student_timeline` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `timeline_date` date NOT NULL,
  `description` varchar(200) NOT NULL,
  `document` varchar(200) NOT NULL,
  `status` varchar(200) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `student_timeline` */

/*Table structure for table `student_transport_fees` */

DROP TABLE IF EXISTS `student_transport_fees`;

CREATE TABLE `student_transport_fees` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_session_id` int(11) DEFAULT NULL,
  `amount` float(10,2) DEFAULT NULL,
  `amount_discount` float(10,2) NOT NULL,
  `amount_fine` float(10,2) NOT NULL DEFAULT '0.00',
  `description` text,
  `date` date DEFAULT '0000-00-00',
  `is_active` varchar(255) DEFAULT 'no',
  `payment_mode` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `student_transport_fees` */

/*Table structure for table `students` */

DROP TABLE IF EXISTS `students`;

CREATE TABLE `students` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL,
  `admission_no` varchar(100) DEFAULT NULL,
  `roll_no` smallint(5) unsigned NOT NULL,
  `admission_date` date DEFAULT NULL,
  `admission_date_bs` varchar(255) NOT NULL,
  `firstname` varchar(100) DEFAULT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `rte` varchar(20) NOT NULL DEFAULT 'No',
  `image` varchar(100) DEFAULT NULL,
  `mobileno` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `pincode` varchar(100) DEFAULT NULL,
  `religion` varchar(100) DEFAULT NULL,
  `cast` varchar(50) NOT NULL,
  `dob` date DEFAULT NULL,
  `dob_bs` varchar(255) NOT NULL,
  `gender` varchar(100) DEFAULT NULL,
  `current_address` text,
  `permanent_address` text,
  `category_id` varchar(100) DEFAULT NULL,
  `route_id` int(11) NOT NULL,
  `school_house_id` int(11) NOT NULL,
  `blood_group` varchar(200) NOT NULL,
  `vehroute_id` int(11) NOT NULL,
  `hostel_room_id` int(11) NOT NULL,
  `adhar_no` varchar(100) DEFAULT NULL,
  `samagra_id` varchar(100) DEFAULT NULL,
  `bank_account_no` varchar(100) DEFAULT NULL,
  `bank_name` varchar(100) DEFAULT NULL,
  `ifsc_code` varchar(100) DEFAULT NULL,
  `guardian_is` varchar(100) NOT NULL,
  `father_name` varchar(100) DEFAULT NULL,
  `father_phone` varchar(100) DEFAULT NULL,
  `father_occupation` varchar(100) DEFAULT NULL,
  `mother_name` varchar(100) DEFAULT NULL,
  `mother_phone` varchar(100) DEFAULT NULL,
  `mother_occupation` varchar(100) DEFAULT NULL,
  `guardian_name` varchar(100) DEFAULT NULL,
  `guardian_relation` varchar(100) DEFAULT NULL,
  `guardian_phone` varchar(100) DEFAULT NULL,
  `guardian_occupation` varchar(150) NOT NULL,
  `guardian_address` text,
  `guardian_email` varchar(100) NOT NULL,
  `father_pic` varchar(200) NOT NULL,
  `mother_pic` varchar(200) NOT NULL,
  `guardian_pic` varchar(200) NOT NULL,
  `is_active` varchar(255) DEFAULT 'no',
  `previous_school` text,
  `height` varchar(100) NOT NULL,
  `weight` varchar(100) NOT NULL,
  `measurement_date` date NOT NULL,
  `measurement_date_bs` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `disable_at` date NOT NULL,
  `note` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `students` */

insert  into `students`(`id`,`parent_id`,`admission_no`,`roll_no`,`admission_date`,`admission_date_bs`,`firstname`,`lastname`,`rte`,`image`,`mobileno`,`email`,`state`,`city`,`pincode`,`religion`,`cast`,`dob`,`dob_bs`,`gender`,`current_address`,`permanent_address`,`category_id`,`route_id`,`school_house_id`,`blood_group`,`vehroute_id`,`hostel_room_id`,`adhar_no`,`samagra_id`,`bank_account_no`,`bank_name`,`ifsc_code`,`guardian_is`,`father_name`,`father_phone`,`father_occupation`,`mother_name`,`mother_phone`,`mother_occupation`,`guardian_name`,`guardian_relation`,`guardian_phone`,`guardian_occupation`,`guardian_address`,`guardian_email`,`father_pic`,`mother_pic`,`guardian_pic`,`is_active`,`previous_school`,`height`,`weight`,`measurement_date`,`measurement_date_bs`,`created_at`,`updated_at`,`disable_at`,`note`) values (1,135,'STD-01',1,'2019-10-01','','Pamela','Freeman','No','uploads/student_images/1.jpg','','student1@test.com',NULL,NULL,NULL,'','','2014-07-24','','Female','','','1',0,1,'A+',0,0,'','','','','','mother','Dylan Freeman','','','Grace Simpson','5477985456','','Grace Simpson','Mother','5477985456','','','grace@test.com','uploads/student_images/1father.jpg','uploads/student_images/1mother.jpg','uploads/student_images/1guardian.jpg','yes','','','','2019-11-21','','2019-11-27 15:45:07','0000-00-00 00:00:00','0000-00-00',''),(2,137,'STD-02',2,'2019-09-01','','Bobby','Harris','No','uploads/student_images/2.jpg','','bobby@test.com',NULL,NULL,NULL,'','','2014-06-18','','Male','','','2',0,2,'',0,0,'','','','','','father','Brian Hill','654654654','','Christina Perez','','','Brian Hill','Father','654654654','','','brian@test.com','uploads/student_images/2father.jpg','uploads/student_images/2mother.jpg','uploads/student_images/2guardian.jpg','yes','','','','2019-11-21','','2019-11-21 11:59:34','0000-00-00 00:00:00','0000-00-00',''),(3,139,'STD-03',1,'2019-11-22','','Marie','Hunt','No','uploads/student_images/3.jpg','','marie@test.com',NULL,NULL,NULL,'','','2013-05-13','','Female','','','1',0,0,'',0,0,'','','','','','mother','','','','Brenda Hamilton','4654122154','','Brenda Hamilton','Mother','4654122154','','','brenda@test.com','','uploads/student_images/3mother.jpg','uploads/student_images/3guardian.jpg','yes','','','','2019-11-22','','2019-11-22 10:44:26','0000-00-00 00:00:00','0000-00-00','');

/*Table structure for table `subjects` */

DROP TABLE IF EXISTS `subjects`;

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `code` varchar(100) NOT NULL,
  `type` varchar(100) NOT NULL,
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `subjects` */

insert  into `subjects`(`id`,`name`,`code`,`type`,`is_active`,`created_at`,`updated_at`) values (1,'Nepali','NEP-01','Theory','no','2019-11-21 11:04:44','0000-00-00 00:00:00'),(2,'English','ENG-01','Theory','no','2019-11-21 11:04:58','0000-00-00 00:00:00');

/*Table structure for table `tbl_exam_publish` */

DROP TABLE IF EXISTS `tbl_exam_publish`;

CREATE TABLE `tbl_exam_publish` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `tbl_exam_publish` */

/*Table structure for table `tbl_exam_result_publish` */

DROP TABLE IF EXISTS `tbl_exam_result_publish`;

CREATE TABLE `tbl_exam_result_publish` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `tbl_exam_result_publish` */

/*Table structure for table `tbl_grade` */

DROP TABLE IF EXISTS `tbl_grade`;

CREATE TABLE `tbl_grade` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `grade_letter` varchar(50) NOT NULL,
  `credit_point` float(10,2) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `tbl_grade` */

/*Table structure for table `tbl_neema_user` */

DROP TABLE IF EXISTS `tbl_neema_user`;

CREATE TABLE `tbl_neema_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `institute_code` varchar(250) NOT NULL,
  `institute_userid` int(11) NOT NULL,
  `registration_code` varchar(250) NOT NULL,
  `institute_roleid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `tbl_neema_user` */

/*Table structure for table `teacher_subjects` */

DROP TABLE IF EXISTS `teacher_subjects`;

CREATE TABLE `teacher_subjects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session_id` int(11) DEFAULT NULL,
  `class_section_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `credit_hour` int(11) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `class_section_id` (`class_section_id`),
  KEY `session_id` (`session_id`),
  KEY `subject_id` (`subject_id`),
  KEY `teacher_id` (`teacher_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `teacher_subjects` */

/*Table structure for table `test_categories` */

DROP TABLE IF EXISTS `test_categories`;

CREATE TABLE `test_categories` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `left_node` int(11) NOT NULL,
  `right_node` int(11) NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

/*Data for the table `test_categories` */

insert  into `test_categories`(`category_id`,`name`,`left_node`,`right_node`) values (1,'electronics',1,20),(2,'televisions',2,9),(3,'tube',3,4),(4,'lcd',5,6),(5,'plasma',7,8),(6,'portable electronics',10,19),(7,'mp3 players',11,14),(8,'flash',12,13),(9,'cd players',15,16),(10,'2 way radios',17,18);

/*Table structure for table `timetables` */

DROP TABLE IF EXISTS `timetables`;

CREATE TABLE `timetables` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `teacher_subject_id` int(20) DEFAULT NULL,
  `day_name` varchar(50) DEFAULT NULL,
  `start_time` varchar(50) DEFAULT NULL,
  `end_time` varchar(50) DEFAULT NULL,
  `room_no` varchar(50) DEFAULT NULL,
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `timetables` */

/*Table structure for table `transport_route` */

DROP TABLE IF EXISTS `transport_route`;

CREATE TABLE `transport_route` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `route_title` varchar(100) DEFAULT NULL,
  `no_of_vehicle` int(11) DEFAULT NULL,
  `fare` float(10,2) DEFAULT NULL,
  `note` text,
  `is_active` varchar(255) DEFAULT 'no',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `transport_route` */

/*Table structure for table `userlog` */

DROP TABLE IF EXISTS `userlog`;

CREATE TABLE `userlog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(100) DEFAULT NULL,
  `role` varchar(100) DEFAULT NULL,
  `ipaddress` varchar(100) DEFAULT NULL,
  `user_agent` varchar(500) DEFAULT NULL,
  `login_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8;

/*Data for the table `userlog` */

insert  into `userlog`(`id`,`user`,`role`,`ipaddress`,`user_agent`,`login_datetime`) values (1,'superadmin@yopmail.com','','172.18.29.105','Chrome 73.0.3683.86, Windows 10','2019-04-04 15:39:25'),(2,'superadmin@yopmail.com','','172.18.29.105','Chrome 73.0.3683.86, Windows 10','2019-04-04 15:41:23'),(3,'superadmin@yopmail.com','','172.18.29.105','Chrome 73.0.3683.86, Windows 10','2019-04-04 15:42:27'),(4,'superadmin@yopmail.com','','172.18.29.105','Firefox 66.0, Windows 10','2019-04-04 15:43:30'),(5,'superadmin@yopmail.com','','172.18.29.105','Firefox 66.0, Windows 10','2019-04-04 15:50:01'),(6,'admin@admin.com','Super Admin','::1','Chrome 78.0.3904.97, Windows 10','2019-11-21 10:36:56'),(7,'admin@admin.com','Super Admin','::1','Chrome 78.0.3904.97, Windows 10','2019-11-21 11:54:13'),(8,'grace@test.com','Teacher','::1','Chrome 78.0.3904.97, Windows 10','2019-11-21 14:14:44'),(9,'admin@admin.com','Super Admin','::1','Chrome 78.0.3904.97, Windows 10','2019-11-21 14:15:14'),(10,'grace@test.com','Teacher','::1','Chrome 78.0.3904.97, Windows 10','2019-11-21 14:17:51'),(11,'admin@admin.com','Super Admin','::1','Chrome 78.0.3904.97, Windows 10','2019-11-21 14:18:23'),(12,'admin@admin.com','Super Admin','::1','Chrome 78.0.3904.97, Windows 10','2019-11-21 14:22:04'),(13,'anna@test.com','Accountant','::1','Chrome 78.0.3904.97, Windows 10','2019-11-21 14:23:40'),(14,'admin@admin.com','Super Admin','::1','Chrome 78.0.3904.97, Windows 10','2019-11-21 16:00:28'),(15,'anna@test.com','Accountant','::1','Chrome 78.0.3904.97, Windows 10','2019-11-21 16:10:11'),(16,'admin@admin.com','Super Admin','::1','Chrome 78.0.3904.97, Windows 10','2019-11-21 17:01:57'),(17,'admin@admin.com','Super Admin','::1','Chrome 78.0.3904.97, Windows 10','2019-11-22 10:37:33'),(18,'admin@admin.com','Super Admin','::1','Chrome 78.0.3904.108, Windows 10','2019-11-25 09:58:28'),(19,'admin@admin.com','Super Admin','::1','Chrome 78.0.3904.108, Windows 10','2019-11-25 16:40:17'),(20,'admin@admin.com','Super Admin','::1','Chrome 78.0.3904.108, Windows 10','2019-11-25 17:27:23'),(21,'admin@admin.com','Super Admin','::1','Chrome 78.0.3904.108, Windows 10','2019-11-25 17:29:11'),(22,'admin@admin.com','Super Admin','::1','Chrome 78.0.3904.108, Windows 10','2019-11-26 10:24:47'),(23,'admin@admin.com','Super Admin','::1','Chrome 78.0.3904.108, Windows 10','2019-11-27 10:23:25'),(24,'admin@admin.com','Super Admin','::1','Chrome 78.0.3904.108, Windows 10','2019-11-27 14:42:06'),(25,'anna@test.com','Accountant','::1','Firefox 70.0, Windows 10','2019-11-27 14:45:51'),(26,'grace@test.com','Teacher','::1','Firefox 70.0, Windows 10','2019-11-27 14:46:20'),(27,'admin@admin.com','Super Admin','::1','Chrome 78.0.3904.108, Windows 10','2019-11-27 14:49:17'),(28,'grace@test.com','Teacher','::1','Firefox 70.0, Windows 10','2019-11-27 14:49:39'),(29,'anna@test.com','Accountant','::1','Firefox 70.0, Windows 10','2019-11-27 14:50:39'),(30,'grace@test.com','Teacher','::1','Firefox 70.0, Windows 10','2019-11-27 14:53:42'),(31,'anna@test.com','Accountant','::1','Firefox 70.0, Windows 10','2019-11-27 14:53:59'),(32,'account@test.com','Accountant','::1','Firefox 70.0, Windows 10','2019-11-27 14:55:10'),(33,'admin@admin.com','Super Admin','::1','Chrome 78.0.3904.108, Windows 10','2019-11-27 15:05:30'),(34,'account@test.com','Accountant','::1','Firefox 70.0, Windows 10','2019-11-27 15:10:23'),(35,'admin@admin.com','Super Admin','::1','Chrome 78.0.3904.108, Windows 10','2019-11-27 15:14:30'),(36,'admin@admin.com','Super Admin','::1','Firefox 70.0, Windows 10','2019-11-27 15:15:26'),(37,'account@test.com','Accountant','::1','Firefox 70.0, Windows 10','2019-11-27 15:18:06'),(38,'pamela','student','::1','Firefox 70.0, Windows 10','2019-11-27 15:45:59'),(39,'teacher1@test.com','Teacher','::1','Firefox 70.0, Windows 10','2019-11-27 15:53:42'),(40,'account@test.com','Accountant','::1','Firefox 70.0, Windows 10','2019-11-27 16:13:15'),(41,'admin@admin.com','Super Admin','::1','Firefox 70.0, Windows 10','2019-11-28 09:31:47'),(42,'admin@admin.com','Super Admin','::1','Chrome 78.0.3904.108, Windows 10','2019-11-28 10:07:26'),(43,'teacher1@test.com','Teacher','::1','Firefox 70.0, Windows 10','2019-11-28 10:09:09'),(44,'admin@admin.com','Super Admin','::1','Chrome 78.0.3904.108, Windows 10','2019-11-28 14:09:31'),(45,'admin@admin.com','Super Admin','::1','Chrome 78.0.3904.108, Windows 10','2019-11-29 09:41:17');

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `childs` text NOT NULL,
  `role` varchar(30) NOT NULL,
  `verification_code` varchar(200) NOT NULL,
  `is_active` varchar(255) DEFAULT 'yes',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=140 DEFAULT CHARSET=utf8;

/*Data for the table `users` */

insert  into `users`(`id`,`user_id`,`username`,`password`,`childs`,`role`,`verification_code`,`is_active`,`created_at`,`updated_at`) values (134,1,'pamela','pamela','','student','','yes','2019-11-21 11:33:26','0000-00-00 00:00:00'),(135,0,'grace','grace','1','parent','','yes','2019-11-21 11:33:26','0000-00-00 00:00:00'),(136,2,'bobby','bobby','','student','','yes','2019-11-21 11:37:28','0000-00-00 00:00:00'),(137,0,'brian','brian','2','parent','','yes','2019-11-21 11:37:28','0000-00-00 00:00:00'),(138,3,'marie','marie','','student','','yes','2019-11-22 10:43:33','0000-00-00 00:00:00'),(139,0,'brenda','brenda','3','parent','','yes','2019-11-22 10:43:33','0000-00-00 00:00:00');

/*Table structure for table `vehicle_routes` */

DROP TABLE IF EXISTS `vehicle_routes`;

CREATE TABLE `vehicle_routes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `route_id` int(11) DEFAULT NULL,
  `vehicle_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `vehicle_routes` */

/*Table structure for table `vehicles` */

DROP TABLE IF EXISTS `vehicles`;

CREATE TABLE `vehicles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `vehicle_no` varchar(20) DEFAULT NULL,
  `vehicle_model` varchar(100) NOT NULL DEFAULT 'None',
  `manufacture_year` varchar(4) DEFAULT NULL,
  `driver_name` varchar(50) DEFAULT NULL,
  `driver_licence` varchar(50) NOT NULL DEFAULT 'None',
  `driver_contact` varchar(20) DEFAULT NULL,
  `note` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `vehicles` */

/*Table structure for table `visitors_book` */

DROP TABLE IF EXISTS `visitors_book`;

CREATE TABLE `visitors_book` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `source` varchar(100) DEFAULT NULL,
  `purpose` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `contact` varchar(12) NOT NULL,
  `id_proof` varchar(50) NOT NULL,
  `no_of_pepple` int(11) NOT NULL,
  `date` date NOT NULL,
  `in_time` varchar(20) NOT NULL,
  `out_time` varchar(20) NOT NULL,
  `note` mediumtext NOT NULL,
  `image` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `visitors_book` */

/*Table structure for table `visitors_purpose` */

DROP TABLE IF EXISTS `visitors_purpose`;

CREATE TABLE `visitors_purpose` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `visitors_purpose` varchar(100) NOT NULL,
  `description` mediumtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `visitors_purpose` */

/*Table structure for table `weekdays` */

DROP TABLE IF EXISTS `weekdays`;

CREATE TABLE `weekdays` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `dayname` varchar(10) NOT NULL,
  `daykey` varchar(10) NOT NULL,
  `sunday` tinyint(3) unsigned NOT NULL,
  `monday` tinyint(3) unsigned NOT NULL,
  `tuesday` tinyint(3) unsigned NOT NULL,
  `wednesday` tinyint(3) unsigned NOT NULL,
  `thursday` tinyint(3) unsigned NOT NULL,
  `friday` tinyint(3) unsigned NOT NULL,
  `saturday` tinyint(3) unsigned NOT NULL,
  `is_holiday` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

/*Data for the table `weekdays` */

insert  into `weekdays`(`id`,`dayname`,`daykey`,`sunday`,`monday`,`tuesday`,`wednesday`,`thursday`,`friday`,`saturday`,`is_holiday`) values (1,'Sunday','sunday',1,7,6,5,4,3,2,0),(2,'Monday','monday',2,1,7,6,5,4,3,0),(3,'Tuesday','tuesday',3,2,1,7,6,5,4,0),(4,'Wednesday','wednesday',4,3,2,1,7,6,5,0),(5,'Thursday','thursday',5,4,3,2,1,7,6,0),(6,'Friday','friday',6,5,4,3,2,1,7,0),(7,'Saturday','saturday',7,6,5,4,3,2,1,0);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

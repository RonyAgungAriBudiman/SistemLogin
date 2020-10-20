/*
SQLyog Community v12.4.1 (64 bit)
MySQL - 10.4.13-MariaDB : Database - pendaftaran
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`pendaftaran` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;

USE `pendaftaran`;

/*Table structure for table `menu` */

DROP TABLE IF EXISTS `menu`;

CREATE TABLE `menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

/*Data for the table `menu` */

insert  into `menu`(`id`,`menu`) values 
(1,'admin'),
(2,'user'),
(3,'menu');

/*Table structure for table `sub_menu` */

DROP TABLE IF EXISTS `sub_menu`;

CREATE TABLE `sub_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_id` int(11) DEFAULT NULL,
  `title` varchar(128) DEFAULT NULL,
  `url` varchar(128) DEFAULT NULL,
  `icon` varchar(128) DEFAULT NULL,
  `is_active` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;

/*Data for the table `sub_menu` */

insert  into `sub_menu`(`id`,`menu_id`,`title`,`url`,`icon`,`is_active`) values 
(1,1,'Dashboard','admin','fas fa-fw fa-tachometer-alt',1),
(2,2,'My Profile','user','fas fa-fw fa-user',1),
(3,2,'Edit Profile','user/edit','fas fa-user-edit',1),
(4,3,'Menu Management','menu','fas fa-folder',1),
(5,3,'Submenu Management','menu/submenu','fas fa-folder-open',1),
(7,1,'Role','admin/role','fas fa-fw fa-user-tie',1),
(8,2,'Ubah password','user/ubahpassword','fas fa-fw fa-key',1);

/*Table structure for table `user` */

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Nama` varchar(128) DEFAULT NULL,
  `Email` varchar(128) DEFAULT NULL,
  `Image` varchar(128) DEFAULT NULL,
  `Password` varchar(256) DEFAULT NULL,
  `RoleId` int(11) DEFAULT NULL,
  `IsActive` int(11) DEFAULT 1,
  `DateCreated` int(11) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;

/*Data for the table `user` */

insert  into `user`(`Id`,`Nama`,`Email`,`Image`,`Password`,`RoleId`,`IsActive`,`DateCreated`) values 
(5,'Rony Agung','ronyagungaribudiman@gmail.com','me1.jpg','$2y$10$j0FB0K/3xkjyLBKI/vLuuO6hOmw4ZbS6bQHfEhoo/qQrN9jHITos6',1,1,1601869452),
(6,'Rizky Gibran','rizky@gmail.com','default.jpg','$2y$10$uyTKQh4VZPD7ch6Pe2QnAuCQ8.t8XqX.7rxxbz1i8kFEIKlN/Tgem',2,1,1601890157),
(7,'Rony Agung Ari Budiman','bloke326@gmail.com','default.jpg','$2y$10$gRX7G7VvrGUPAt5CLOFrCukDyxEy.POFNzgNhdqOm2YGTF9LMlLnu',2,1,1603096139);

/*Table structure for table `user_access_menu` */

DROP TABLE IF EXISTS `user_access_menu`;

CREATE TABLE `user_access_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) DEFAULT NULL,
  `menu_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4;

/*Data for the table `user_access_menu` */

insert  into `user_access_menu`(`id`,`role_id`,`menu_id`) values 
(1,1,1),
(2,1,2),
(3,2,2),
(4,1,3);

/*Table structure for table `user_role` */

DROP TABLE IF EXISTS `user_role`;

CREATE TABLE `user_role` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Role` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

/*Data for the table `user_role` */

insert  into `user_role`(`Id`,`Role`) values 
(1,'Administrator'),
(2,'User');

/*Table structure for table `user_token` */

DROP TABLE IF EXISTS `user_token`;

CREATE TABLE `user_token` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(128) DEFAULT NULL,
  `token` varchar(128) DEFAULT NULL,
  `date_created` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

/*Data for the table `user_token` */

insert  into `user_token`(`id`,`email`,`token`,`date_created`) values 
(5,'bloke326@gmail.com','BvpBvKlL8ekrEhdzcCQJc76gAXBpGAxAiDqxC9cPy48=',1603164292);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 100113
 Source Host           : localhost
 Source Database       : bookreader

 Target Server Type    : MySQL
 Target Server Version : 100113
 File Encoding         : utf-8

 Date: 11/01/2016 17:30:52 PM
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `table_of_contnet_qidian`
-- ----------------------------
DROP TABLE IF EXISTS `table_of_contnet_qidian`;
CREATE TABLE `table_of_contnet_qidian` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `book_id` int(11) NOT NULL,
  `juan_title` varchar(255) DEFAULT NULL,
  `chapter_title` varchar(255) DEFAULT NULL,
  `chapter_url` varchar(255) NOT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`,`chapter_url`),
  KEY `bookId` (`book_id`),
  CONSTRAINT `bookId` FOREIGN KEY (`book_id`) REFERENCES `book_qidian` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=137 DEFAULT CHARSET=utf8;

SET FOREIGN_KEY_CHECKS = 1;

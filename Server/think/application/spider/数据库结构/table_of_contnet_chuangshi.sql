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

 Date: 11/03/2016 18:39:59 PM
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `table_of_contnet_chuangshi`
-- ----------------------------
DROP TABLE IF EXISTS `table_of_contnet_chuangshi`;
CREATE TABLE `table_of_contnet_chuangshi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `book_id` int(11) NOT NULL,
  `juan_title` varchar(255) DEFAULT NULL,
  `chapter_title` varchar(255) DEFAULT NULL,
  `chapter_url` varchar(255) NOT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `chapter_url` (`chapter_url`) USING BTREE,
  KEY `bookId` (`book_id`),
  CONSTRAINT `table_of_contnet_chuangshi_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `book_chuangshi` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20477 DEFAULT CHARSET=utf8;

SET FOREIGN_KEY_CHECKS = 1;

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

 Date: 11/03/2016 15:26:29 PM
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `user_books`
-- ----------------------------
DROP TABLE IF EXISTS `user_books`;
CREATE TABLE `user_books` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `index` varchar(255) NOT NULL COMMENT '书架中的排序',
  `qidian_book_id` int(11) DEFAULT NULL,
  `chuangshi_book_id` int(11) DEFAULT NULL,
  `add_time` datetime NOT NULL COMMENT '添加到书架的时间',
  `last_read_time` datetime DEFAULT NULL COMMENT '最后一次阅读时间',
  `read_times` int(11) NOT NULL DEFAULT '0' COMMENT '阅读次数',
  `last_read_chapter` varchar(255) DEFAULT NULL COMMENT '最后一次阅读的章节',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `user_books_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

SET FOREIGN_KEY_CHECKS = 1;

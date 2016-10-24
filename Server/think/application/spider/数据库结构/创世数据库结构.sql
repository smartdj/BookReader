/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 100113
 Source Host           : localhost
 Source Database       : spider

 Target Server Type    : MySQL
 Target Server Version : 100113
 File Encoding         : utf-8

 Date: 09/29/2016 15:26:25 PM
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `chuang_shi_book`
-- ----------------------------
DROP TABLE IF EXISTS `chuang_shi_book`;
CREATE TABLE `chuang_shi_book` (
  `book_id` int(11) NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `cover_img_url` varchar(255) DEFAULT NULL,
  `short_description` varchar(255) DEFAULT NULL,
  `long_description` varchar(255) DEFAULT NULL,
  `book_name` varchar(255) DEFAULT NULL,
  `author_url` varchar(255) DEFAULT NULL,
  `author_name` varchar(255) DEFAULT NULL,
  `main_category` varchar(255) DEFAULT NULL,
  `sub_category` varchar(255) DEFAULT NULL,
  `book_status` varchar(255) DEFAULT NULL,
  `written_words` varchar(255) DEFAULT '0',
  `lastest_update_time` datetime DEFAULT NULL,
  `lastest_chapter` varchar(255) DEFAULT NULL,
  `lastest_chapter_url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`book_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

SET FOREIGN_KEY_CHECKS = 1;

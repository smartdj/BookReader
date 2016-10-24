/*
Navicat MySQL Data Transfer

Source Server         : local
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : bookreader

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2016-10-02 20:38:01
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for chuang_shi_table_content
-- ----------------------------
DROP TABLE IF EXISTS `chuang_shi_table_content`;
CREATE TABLE `chuang_shi_table_content` (
  `book_id` int(11) DEFAULT NULL,
  `voluem_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `chapter_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `chapter_url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  KEY `boo_id` (`book_id`),
  CONSTRAINT `boo_id` FOREIGN KEY (`book_id`) REFERENCES `chuang_shi_book` (`book_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

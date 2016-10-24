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

 Date: 10/24/2016 17:55:13 PM
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `book_chuangshi`
-- ----------------------------
DROP TABLE IF EXISTS `book_chuangshi`;
CREATE TABLE `book_chuangshi` (
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

-- ----------------------------
--  Table structure for `book_qidian`
-- ----------------------------
DROP TABLE IF EXISTS `book_qidian`;
CREATE TABLE `book_qidian` (
  `id` int(11) NOT NULL,
  `URL` varchar(255) DEFAULT NULL,
  `imgURL` varchar(255) DEFAULT NULL,
  `shortDescription` varchar(255) DEFAULT NULL,
  `longDescription` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `authorURL` varchar(255) DEFAULT NULL,
  `authorName` varchar(255) DEFAULT NULL,
  `mainCategory` varchar(255) DEFAULT NULL,
  `subCategory` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `writtenWords` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `spiderstatus`
-- ----------------------------
DROP TABLE IF EXISTS `spiderstatus`;
CREATE TABLE `spiderstatus` (
  `name` enum('创世','起点') COLLATE utf8_unicode_ci NOT NULL,
  `status` enum('已结束','运行中') COLLATE utf8_unicode_ci DEFAULT NULL,
  `current_page` int(11) DEFAULT NULL,
  `max_page` int(11) DEFAULT NULL,
  `lastUpdateTime` datetime DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
--  Table structure for `table_of_contnet_qidian`
-- ----------------------------
DROP TABLE IF EXISTS `table_of_contnet_qidian`;
CREATE TABLE `table_of_contnet_qidian` (
  `bookId` int(11) NOT NULL,
  `juanTitle` varchar(255) DEFAULT NULL,
  `chapterTitle` varchar(255) DEFAULT NULL,
  `chapterURL` varchar(255) NOT NULL,
  `updateTime` datetime DEFAULT NULL,
  PRIMARY KEY (`chapterURL`),
  KEY `bookId` (`bookId`),
  CONSTRAINT `bookId` FOREIGN KEY (`bookId`) REFERENCES `book_qidian` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

SET FOREIGN_KEY_CHECKS = 1;

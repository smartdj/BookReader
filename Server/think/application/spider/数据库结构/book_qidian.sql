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

 Date: 11/01/2016 17:30:39 PM
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

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

SET FOREIGN_KEY_CHECKS = 1;

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

 Date: 11/01/2016 17:30:46 PM
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

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

SET FOREIGN_KEY_CHECKS = 1;

/*
Navicat MySQL Data Transfer

Source Server         : local
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : bookreader

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2016-10-02 18:27:19
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for spiderstatus
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

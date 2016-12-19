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

 Date: 12/19/2016 10:05:34 AM
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `book_chuangshi`
-- ----------------------------
DROP TABLE IF EXISTS `book_chuangshi`;
CREATE TABLE `book_chuangshi` (
  `id` int(11) NOT NULL,
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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `profile`
-- ----------------------------
DROP TABLE IF EXISTS `profile`;
CREATE TABLE `profile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `nick_name` varchar(255) NOT NULL,
  `avator_img` varchar(255) DEFAULT NULL COMMENT '头像',
  `mail` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `user_id_2` (`user_id`),
  KEY `user_id_3` (`user_id`),
  KEY `user_id_4` (`user_id`),
  KEY `user_id_5` (`user_id`),
  CONSTRAINT `user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `qidian_books`
-- ----------------------------
DROP TABLE IF EXISTS `qidian_books`;
CREATE TABLE `qidian_books` (
  `id` int(11) NOT NULL,
  `lastest_chapter` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `author` varchar(255) DEFAULT NULL,
  `main_category` varchar(255) DEFAULT NULL,
  `sub_category` varchar(255) DEFAULT NULL,
  `relationship_category` varchar(255) DEFAULT NULL,
  `lastest_cahpter_description` varchar(255) DEFAULT NULL,
  `lastest_update_time` varchar(255) DEFAULT NULL,
  `chapter_count` varchar(255) DEFAULT NULL,
  `cover_image_url` varchar(255) DEFAULT NULL,
  `words_count` varchar(255) DEFAULT NULL,
  `status` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `qidian_chapters`
-- ----------------------------
DROP TABLE IF EXISTS `qidian_chapters`;
CREATE TABLE `qidian_chapters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `section_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `section_id` (`section_id`),
  KEY `book_id` (`book_id`),
  KEY `section_id_2` (`section_id`),
  CONSTRAINT `bookId` FOREIGN KEY (`book_id`) REFERENCES `qidian_books` (`id`),
  CONSTRAINT `section_id` FOREIGN KEY (`section_id`) REFERENCES `qidian_sections` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=137 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `qidian_sections`
-- ----------------------------
DROP TABLE IF EXISTS `qidian_sections`;
CREATE TABLE `qidian_sections` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `book_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `book_id` (`book_id`),
  CONSTRAINT `book_id` FOREIGN KEY (`book_id`) REFERENCES `qidian_books` (`id`)
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
) ENGINE=InnoDB AUTO_INCREMENT=399877 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `user`
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `identity_type` enum('weibo','weixin','qq','phone','mail','username') NOT NULL COMMENT '登录类型（手机号 邮箱 用户名）或第三方应用名称（微信 微博等）',
  `identifier` varchar(255) DEFAULT NULL COMMENT '标识（手机号 邮箱 用户名或第三方应用的唯一标识）',
  `credential` varchar(255) DEFAULT NULL COMMENT ' 密码凭证（站内的保存密码，站外的不保存或保存token）',
  `register_time` datetime NOT NULL,
  `login_time` datetime DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='用户授权表，用来解决使用第三方平台登录（例如：微博、QQ、微信等）';

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

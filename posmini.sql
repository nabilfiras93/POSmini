/*
Navicat MySQL Data Transfer

Source Server         : Localhost
Source Server Version : 50505
Source Host           : 127.0.0.1:3306
Source Database       : posmini

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2021-10-13 18:42:56
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for api
-- ----------------------------
DROP TABLE IF EXISTS `api`;
CREATE TABLE `api` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uri` varchar(255) DEFAULT NULL,
  `type` varchar(100) DEFAULT '',
  `token` varchar(100) DEFAULT NULL,
  `userkey` varchar(255) DEFAULT '',
  `status` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of api
-- ----------------------------

-- ----------------------------
-- Table structure for d_group
-- ----------------------------
DROP TABLE IF EXISTS `d_group`;
CREATE TABLE `d_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of d_group
-- ----------------------------
INSERT INTO `d_group` VALUES ('1', 'Owner');
INSERT INTO `d_group` VALUES ('2', 'Merchant');
INSERT INTO `d_group` VALUES ('3', 'Outlet');

-- ----------------------------
-- Table structure for d_setting
-- ----------------------------
DROP TABLE IF EXISTS `d_setting`;
CREATE TABLE `d_setting` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `pesan` text DEFAULT NULL,
  `created_on` timestamp NULL DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `api_type` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of d_setting
-- ----------------------------
INSERT INTO `d_setting` VALUES ('1', null, null, null, null, 'dev');

-- ----------------------------
-- Table structure for outlet
-- ----------------------------
DROP TABLE IF EXISTS `outlet`;
CREATE TABLE `outlet` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT '',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of outlet
-- ----------------------------
INSERT INTO `outlet` VALUES ('1', 'Outlet 1', '2021-10-13 01:34:32', null);
INSERT INTO `outlet` VALUES ('2', 'Outlet 2', '2021-10-13 07:43:06', null);
INSERT INTO `outlet` VALUES ('3', 'Outlet 3', '2021-10-13 09:40:50', null);
INSERT INTO `outlet` VALUES ('4', 'Outlet 4', '2021-10-13 09:40:52', null);

-- ----------------------------
-- Table structure for product
-- ----------------------------
DROP TABLE IF EXISTS `product`;
CREATE TABLE `product` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT '',
  `sku` varchar(100) DEFAULT '',
  `image` varchar(255) DEFAULT '',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `sku` (`sku`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of product
-- ----------------------------
INSERT INTO `product` VALUES ('12', 'Sabun', 'sbn-12', '1634124182_c276091f02ccd6821787.jpg', '2021-10-13 18:23:02', null);

-- ----------------------------
-- Table structure for product_price
-- ----------------------------
DROP TABLE IF EXISTS `product_price`;
CREATE TABLE `product_price` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` varchar(100) DEFAULT '',
  `price` decimal(10,0) DEFAULT NULL,
  `outlet_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of product_price
-- ----------------------------
INSERT INTO `product_price` VALUES ('15', '12', '5000', '1', '2021-10-13 18:23:02', null);
INSERT INTO `product_price` VALUES ('16', '12', '3000', '2', '2021-10-13 18:23:02', null);
INSERT INTO `product_price` VALUES ('17', '12', '3000', '3', '2021-10-13 18:23:02', null);
INSERT INTO `product_price` VALUES ('18', '12', '3000', '4', '2021-10-13 18:23:02', null);

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `group_id` int(10) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `is_active` varchar(5) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_login` tinyint(1) DEFAULT NULL,
  `outlet_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `group_id` (`group_id`),
  KEY `group_id_2` (`group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('1', 'Merchant', 'merchant', '$2y$12$PPyDnhkqXFjwFoZ78KvVb.1nkdDofD3trzVYfnjuY9GIX9brYpN4q', '2', '2021-09-04 14:17:58', null, '1', null, null, null, null);
INSERT INTO `user` VALUES ('3', 'Outlet 1', 'outlet1', '$2y$12$ZArs6ajGkS62UUFOr198qe3q3JgfYjyPYtb98LVtZkCKRUZdfIesS', '3', '2021-10-13 12:07:42', '1634119926_44b332191137ad00c884.jpg', '', '', '0000-00-00 00:00:00', null, '1');
INSERT INTO `user` VALUES ('7', 'Outlet 2', 'outlet2', '$2y$12$cUA2xvYBnQTJ625zb.YtQeQZd5Xo6CLrG8rUDnHfIlhSGW3zHKUFa', '3', '2021-10-13 17:11:50', '1634119910_a6db199f66ab5f88bd05.jpg', null, null, null, null, '2');
INSERT INTO `user` VALUES ('8', 'Outlet 3', 'outlet3', '$2y$12$9Y1SFeJunopBCkbvCMaI2eJlHvufLDXszdl0T/MXKAptLZPBU/z3a', '3', '2021-10-13 17:12:26', '1634119946_75b4ead6b89f69cee372.jpg', null, null, null, null, '3');


CREATE INDEX list_product ON product(id);
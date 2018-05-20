/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : admintest

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2018-05-20 22:23:35
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for elk_admin_nav
-- ----------------------------
DROP TABLE IF EXISTS `elk_admin_nav`;
CREATE TABLE `elk_admin_nav` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(11) unsigned DEFAULT '0' COMMENT '所属菜单',
  `name` varchar(15) DEFAULT '' COMMENT '菜单名称',
  `mca` varchar(255) DEFAULT '' COMMENT '模块、控制器、方法',
  `ico` varchar(20) DEFAULT '' COMMENT 'font-awesome图标',
  `order_number` int(11) unsigned DEFAULT NULL COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='菜单表';

-- ----------------------------
-- Records of elk_admin_nav
-- ----------------------------
INSERT INTO `elk_admin_nav` VALUES ('1', '0', '系统设置', '', 'cog', '1');
INSERT INTO `elk_admin_nav` VALUES ('2', '1', '菜单管理', 'admin/nav/index', null, null);
INSERT INTO `elk_admin_nav` VALUES ('3', '0', '权限控制', '', 'expeditedssl', '2');
INSERT INTO `elk_admin_nav` VALUES ('4', '3', '权限管理', 'admin/rule/index', '', '1');
INSERT INTO `elk_admin_nav` VALUES ('5', '3', '用户组管理', 'admin/rule/group', '', '2');
INSERT INTO `elk_admin_nav` VALUES ('6', '3', '管理员列表', 'admin/rule/admin_user_list', '', '3');

-- ----------------------------
-- Table structure for elk_auth_group
-- ----------------------------
DROP TABLE IF EXISTS `elk_auth_group`;
CREATE TABLE `elk_auth_group` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` char(100) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `rules` text COMMENT '规则id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='用户组表';

-- ----------------------------
-- Records of elk_auth_group
-- ----------------------------
INSERT INTO `elk_auth_group` VALUES ('1', '超级管理员', '1', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69');
INSERT INTO `elk_auth_group` VALUES ('4', '全部数据查看', '1', '1,2,26,27,28,29,53,30,31,32,33,34,35,36,40,54,37,41,42,43,44,45,46,47');
INSERT INTO `elk_auth_group` VALUES ('5', '测试权限', '1', '1,2,26,27,28,29,53,30,31,32,33,34,35,36,40,54,37,41,42,43,44,45,52,46,47,49,51');

-- ----------------------------
-- Table structure for elk_auth_group_access
-- ----------------------------
DROP TABLE IF EXISTS `elk_auth_group_access`;
CREATE TABLE `elk_auth_group_access` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL COMMENT '用户id',
  `group_id` int(11) unsigned NOT NULL COMMENT '用户组id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='用户组明细表';

-- ----------------------------
-- Records of elk_auth_group_access
-- ----------------------------
INSERT INTO `elk_auth_group_access` VALUES ('1', '1', '1');

-- ----------------------------
-- Table structure for elk_auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `elk_auth_rule`;
CREATE TABLE `elk_auth_rule` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父级id',
  `name` char(80) NOT NULL DEFAULT '' COMMENT '规则唯一标识',
  `title` char(20) NOT NULL DEFAULT '' COMMENT '规则中文名称',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态：为1正常，为0禁用',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `condition` char(100) NOT NULL DEFAULT '' COMMENT '规则表达式，为空表示存在就验证，不为空表示按照条件验证',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COMMENT='规则表';

-- ----------------------------
-- Records of elk_auth_rule
-- ----------------------------
INSERT INTO `elk_auth_rule` VALUES ('1', '0', 'admin/admin/index', '后台管理', '1', '1', '');
INSERT INTO `elk_auth_rule` VALUES ('2', '1', 'admin/admin/sys_admin', '后台首页', '1', '1', '');
INSERT INTO `elk_auth_rule` VALUES ('3', '0', '', '系统设置', '1', '1', '');
INSERT INTO `elk_auth_rule` VALUES ('4', '3', '', '菜单管理', '1', '1', '');
INSERT INTO `elk_auth_rule` VALUES ('5', '4', 'admin/nav/index', '菜单列表', '1', '1', '');
INSERT INTO `elk_auth_rule` VALUES ('6', '4', 'admin/nav/add', '添加菜单', '1', '1', '');
INSERT INTO `elk_auth_rule` VALUES ('7', '4', 'admin/nav/edit', '修改菜单', '1', '1', '');
INSERT INTO `elk_auth_rule` VALUES ('8', '4', 'admin/nav/delete', '删除菜单', '1', '1', '');
INSERT INTO `elk_auth_rule` VALUES ('9', '4', 'admin/nav/order', '菜单排序', '1', '1', '');
INSERT INTO `elk_auth_rule` VALUES ('10', '0', '', '权限控制', '1', '1', '');
INSERT INTO `elk_auth_rule` VALUES ('11', '10', 'admin/rule/index', '权限管理', '1', '1', '');
INSERT INTO `elk_auth_rule` VALUES ('12', '11', 'admin/rule/add', '添加权限', '1', '1', '');
INSERT INTO `elk_auth_rule` VALUES ('13', '11', 'admin/rule/edit', '修改权限', '1', '1', '');
INSERT INTO `elk_auth_rule` VALUES ('14', '11', 'admin/rule/delete', '删除权限', '1', '1', '');
INSERT INTO `elk_auth_rule` VALUES ('15', '10', 'admin/rule/group', '用户组管理', '1', '1', '');
INSERT INTO `elk_auth_rule` VALUES ('16', '15', 'admin/rule/add_group', '添加用户组', '1', '1', '');
INSERT INTO `elk_auth_rule` VALUES ('17', '15', 'admin/rule/edit_group', '修改用户组', '1', '1', '');
INSERT INTO `elk_auth_rule` VALUES ('18', '15', 'admin/rule/delete_group', '删除用户组', '1', '1', '');
INSERT INTO `elk_auth_rule` VALUES ('19', '15', 'admin/rule/rule_group', '分配权限', '1', '1', '');
INSERT INTO `elk_auth_rule` VALUES ('20', '15', 'admin/rule/check_user', '添加成员', '1', '1', '');
INSERT INTO `elk_auth_rule` VALUES ('21', '15', 'admin/rule/add_user_to_group', '设置为管理员', '1', '1', '');
INSERT INTO `elk_auth_rule` VALUES ('22', '15', 'admin/rule/add_admin', '添加管理员', '1', '1', '');
INSERT INTO `elk_auth_rule` VALUES ('23', '15', 'admin/rule/edit_admin', '修改管理员', '1', '1', '');
INSERT INTO `elk_auth_rule` VALUES ('24', '10', 'admin/rule/admin_user_list', '管理员列表', '1', '1', '');
INSERT INTO `elk_auth_rule` VALUES ('25', '15', 'admin/rule/delete_admin', '删除管理员', '1', '1', '');

-- ----------------------------
-- Table structure for elk_users
-- ----------------------------
DROP TABLE IF EXISTS `elk_users`;
CREATE TABLE `elk_users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(60) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(256) NOT NULL DEFAULT '' COMMENT '登录密码；mb_password加密',
  `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '用户头像，相对于upload/avatar目录',
  `email` varchar(100) NOT NULL DEFAULT '' COMMENT '登录邮箱',
  `email_code` varchar(60) DEFAULT NULL COMMENT '激活码',
  `phone` bigint(11) unsigned DEFAULT NULL COMMENT '手机号',
  `status` tinyint(1) NOT NULL DEFAULT '2' COMMENT '用户状态 0：禁用； 1：正常 ；2：未验证',
  `register_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
  `last_login_ip` varchar(16) NOT NULL DEFAULT '' COMMENT '最后登录ip',
  `last_login_time` int(10) unsigned NOT NULL COMMENT '最后登录时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='用户表';

-- ----------------------------
-- Records of elk_users
-- ----------------------------
INSERT INTO `elk_users` VALUES ('1', 'admin', '02754fb0d28c9524c527c28140b54299', '', '', '', null, '1', '1449199996', '', '0');

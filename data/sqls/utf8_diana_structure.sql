/*
SQLyog Enterprise - MySQL GUI v7.15 
MySQL - 5.5.28-log : Database - utf8_diana
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

CREATE DATABASE /*!32312 IF NOT EXISTS*/`utf8_diana` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `utf8_diana`;

/*Table structure for table `tb_bulletin` */

CREATE TABLE `tb_bulletin` (
  `bulletin_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '流水号',
  `bulletin_channelId` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '公告频道',
  `bulletin_access` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '4是www公告,2是client公告,1是admin公告,他们的总合是',
  `bulletin_click` int(11) unsigned NOT NULL DEFAULT '0',
  `bulletin_top` int(11) DEFAULT '0' COMMENT '置顶级别',
  `bulletin_title` varchar(128) NOT NULL COMMENT '标题',
  `bulletin_author` varchar(32) NOT NULL,
  `bulletin_lock_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '锁定时间',
  `bulletin_insert_time` int(11) unsigned NOT NULL DEFAULT '0',
  `bulletin_insert_manId` int(11) unsigned NOT NULL DEFAULT '0',
  `bulletin_insert_manName` varchar(32) DEFAULT NULL,
  `bulletin_insert_manEmail` varchar(32) DEFAULT NULL,
  `bulletin_insert_ip` varchar(32) DEFAULT NULL,
  `bulletin_insert_addr` varchar(32) DEFAULT NULL,
  `bulletin_update_time` int(11) unsigned NOT NULL DEFAULT '0',
  `bulletin_update_manId` int(11) unsigned NOT NULL DEFAULT '0',
  `bulletin_update_manName` varchar(32) DEFAULT NULL,
  `bulletin_update_manEmail` varchar(32) DEFAULT NULL,
  `bulletin_update_ip` varchar(32) DEFAULT NULL,
  `bulletin_update_addr` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`bulletin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC COMMENT='公告';

/*Table structure for table `tb_bulletin_channel` */

CREATE TABLE `tb_bulletin_channel` (
  `channel_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `channel_fatherId` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父频道ID',
  `channel_label_zh-cn` varchar(32) NOT NULL,
  `channel_label_zh-tw` varchar(32) DEFAULT NULL,
  `channel_label_en-us` varchar(32) DEFAULT NULL,
  `channel_order` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `channel_count` int(11) unsigned NOT NULL DEFAULT '0',
  `channel_insert_time` int(11) unsigned NOT NULL DEFAULT '0',
  `channel_insert_manId` int(11) unsigned NOT NULL DEFAULT '0',
  `channel_insert_manName` varchar(32) DEFAULT NULL,
  `channel_insert_manEmail` varchar(32) DEFAULT NULL,
  `channel_insert_ip` varchar(32) DEFAULT NULL,
  `channel_insert_addr` varchar(32) DEFAULT NULL,
  `channel_update_time` int(11) unsigned NOT NULL DEFAULT '0',
  `channel_update_manId` int(11) unsigned NOT NULL DEFAULT '0',
  `channel_update_manName` varchar(32) DEFAULT NULL,
  `channel_update_manEmail` varchar(32) DEFAULT NULL,
  `channel_update_ip` varchar(32) DEFAULT NULL,
  `channel_update_addr` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`channel_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC COMMENT='公告频道';

/*Table structure for table `tb_bulletin_content` */

CREATE TABLE `tb_bulletin_content` (
  `bulletin_id` int(11) unsigned NOT NULL,
  `bulletin_time` int(11) NOT NULL DEFAULT '0',
  `bulletin_content` text NOT NULL,
  PRIMARY KEY (`bulletin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC COMMENT='公告内容';

/*Table structure for table `tb_config` */

CREATE TABLE `tb_config` (
  `conf_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '流水号',
  `conf_fatherId` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父级',
  `conf_key` varchar(64) NOT NULL COMMENT '键',
  `conf_label` varchar(64) NOT NULL COMMENT '名称',
  `conf_value` varchar(128) NOT NULL COMMENT '值',
  `conf_default` varchar(128) DEFAULT NULL COMMENT '默认值',
  `conf_input_type` enum('input','select','textarea','checkbox','radio') DEFAULT 'input' COMMENT '表单类型',
  `conf_options` varchar(512) DEFAULT NULL COMMENT '备选项',
  `conf_remark` varchar(128) DEFAULT NULL COMMENT '备注',
  `conf_order` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `conf_insert_time` int(11) NOT NULL DEFAULT '0',
  `conf_insert_manId` int(11) DEFAULT '0',
  `conf_insert_manName` varchar(32) DEFAULT NULL,
  `conf_insert_manEmail` varchar(32) DEFAULT NULL,
  `conf_insert_ip` varchar(32) DEFAULT NULL,
  `conf_insert_addr` varchar(32) DEFAULT NULL,
  `conf_update_time` int(11) DEFAULT '0',
  `conf_update_manId` int(11) DEFAULT NULL,
  `conf_update_manName` varchar(32) DEFAULT NULL,
  `conf_update_manEmail` varchar(32) DEFAULT NULL,
  `conf_update_ip` varchar(32) DEFAULT NULL,
  `conf_update_addr` varchar(32) DEFAULT NULL,
  `conf_alter_time` int(11) DEFAULT '0',
  `conf_alter_manId` int(11) DEFAULT '0',
  `conf_alter_manName` varchar(32) DEFAULT NULL,
  `conf_alter_manEmail` varchar(32) DEFAULT NULL,
  `conf_alter_ip` varchar(32) DEFAULT NULL,
  `conf_alter_addr` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`conf_id`),
  UNIQUE KEY `ConfKey` (`conf_key`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8;

/*Table structure for table `tb_config_update_history` */

CREATE TABLE `tb_config_update_history` (
  `history_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `history_configId` int(11) DEFAULT NULL,
  `history_configKey` varchar(64) NOT NULL,
  `history_configValue` varchar(64) NOT NULL,
  `history_insert_time` int(11) unsigned NOT NULL DEFAULT '0',
  `history_insert_manId` int(11) unsigned NOT NULL DEFAULT '0',
  `history_insert_manName` varchar(32) DEFAULT NULL,
  `history_insert_manEmail` varchar(32) DEFAULT NULL,
  `history_insert_ip` varchar(32) NOT NULL,
  `history_insert_addr` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`history_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `tb_config_update_history_2014` */

CREATE TABLE `tb_config_update_history_2014` (
  `history_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `history_configId` int(11) DEFAULT NULL,
  `history_configKey` varchar(64) NOT NULL,
  `history_configValue` varchar(64) NOT NULL,
  `history_insert_time` int(11) unsigned NOT NULL DEFAULT '0',
  `history_insert_manId` int(11) unsigned NOT NULL DEFAULT '0',
  `history_insert_manName` varchar(32) DEFAULT NULL,
  `history_insert_manEmail` varchar(32) DEFAULT NULL,
  `history_insert_ip` varchar(32) NOT NULL,
  `history_insert_addr` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`history_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Table structure for table `tb_domain` */

CREATE TABLE `tb_domain` (
  `domain_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '流水号',
  `domain_name` varchar(32) DEFAULT NULL COMMENT '域名',
  `domain_website_id` int(11) unsigned NOT NULL COMMENT '域名所属站点',
  `domain_click` int(11) unsigned NOT NULL COMMENT '域名点击量',
  `domain_insert_time` int(11) NOT NULL DEFAULT '0' COMMENT '域名创建时间',
  `domain_update_time` int(11) NOT NULL DEFAULT '0' COMMENT '域名修改时间',
  PRIMARY KEY (`domain_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC COMMENT='域名';

/*Table structure for table `tb_front_channel` */

CREATE TABLE `tb_front_channel` (
  `channel_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '流水号',
  `channel_fatherId` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父ID',
  `channel_link` varchar(64) NOT NULL COMMENT '链接',
  `channel_name_zh-cn` varchar(32) DEFAULT NULL COMMENT '频道名-简中',
  `channel_name_zh-tw` varchar(32) DEFAULT NULL COMMENT '频道名-繁中',
  `channel_name_en-us` varchar(32) DEFAULT NULL COMMENT '频道名-英美',
  `channel_enable` int(11) DEFAULT '0' COMMENT '是否开通，1一直开通，2根据时间来确定，3关闭',
  `channel_order` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `channel_insert_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `channel_insert_man` varchar(32) DEFAULT NULL,
  `channel_insert_ip` varchar(32) DEFAULT NULL,
  `channel_insert_addr` varchar(32) DEFAULT NULL,
  `channel_update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '最后更新时间',
  `channel_update_man` varchar(32) DEFAULT NULL,
  `channel_update_ip` varchar(32) DEFAULT NULL,
  `channel_update_addr` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`channel_id`)
) ENGINE=InnoDB AUTO_INCREMENT=608 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC COMMENT='前端-频道';

/*Table structure for table `tb_front_channel_enable` */

CREATE TABLE `tb_front_channel_enable` (
  `enable_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `enable_channelId` int(11) NOT NULL DEFAULT '0',
  `enable_time_min` int(11) NOT NULL DEFAULT '0',
  `enable_time_max` int(11) NOT NULL DEFAULT '0',
  `enabel_insert_time` int(11) DEFAULT '0',
  `enable_insert_man` varchar(32) DEFAULT NULL,
  `enable_insert_ip` varchar(32) DEFAULT NULL,
  `enable_update_time` int(11) DEFAULT NULL,
  `enable_update_man` varchar(32) DEFAULT NULL,
  `enable_update_ip` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`enable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `tb_manager` */

CREATE TABLE `tb_manager` (
  `manager_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '流水号',
  `manager_roleId` int(11) unsigned NOT NULL COMMENT '角色',
  `manager_name` varchar(32) NOT NULL COMMENT '名称',
  `manager_email` varchar(32) NOT NULL COMMENT '邮箱',
  `manager_passwd` varchar(32) NOT NULL COMMENT '密码',
  `manager_passwd_change_count` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '密码修改次数',
  `manager_passwd_change_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '密码修改时间',
  `manager_passwd_change_ip` varchar(32) DEFAULT NULL COMMENT '密码修改IP',
  `manager_login_count` int(11) unsigned NOT NULL DEFAULT '0',
  `manager_login_last_time` int(11) NOT NULL DEFAULT '0',
  `manager_login_last_ip` varchar(32) DEFAULT NULL,
  `manager_lock_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '锁定时间',
  `manager_active_email` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '是否经过邮箱验证',
  `manager_insert_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
  `manager_insert_manId` int(11) DEFAULT '0',
  `manager_insert_manName` varchar(32) DEFAULT NULL,
  `manager_insert_manEmail` varchar(32) DEFAULT NULL,
  `manager_insert_ip` varchar(32) DEFAULT NULL,
  `manager_update_time` int(11) unsigned NOT NULL DEFAULT '0',
  `manager_update_manId` int(11) DEFAULT '0',
  `manager_update_manName` varchar(32) DEFAULT NULL,
  `manager_update_manEmail` varchar(32) DEFAULT NULL,
  `manager_update_ip` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`manager_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1002 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Table structure for table `tb_manager_log` */

CREATE TABLE `tb_manager_log` (
  `log_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '流水号',
  `log_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '登录时间',
  `log_ip` varchar(64) NOT NULL COMMENT '登录IP',
  `log_type` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '日志类型',
  `log_sessionId` varchar(64) DEFAULT NULL COMMENT '会话ID',
  `log_managerId` int(10) unsigned DEFAULT '0' COMMENT '管理员ID',
  `log_managerName` varchar(64) DEFAULT NULL COMMENT '管理员帐号',
  `log_managerEmail` varchar(64) DEFAULT NULL COMMENT '管理员邮箱',
  PRIMARY KEY (`log_id`),
  KEY `Type_ManagerId` (`log_type`,`log_managerId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC COMMENT='管理员登录日志';

/*Table structure for table `tb_manager_log_2014` */

CREATE TABLE `tb_manager_log_2014` (
  `log_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '流水号',
  `log_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '登录时间',
  `log_ip` varchar(64) NOT NULL COMMENT '登录IP',
  `log_type` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '日志类型',
  `log_sessionId` varchar(64) DEFAULT NULL COMMENT '会话ID',
  `log_managerId` int(10) unsigned DEFAULT '0' COMMENT '管理员ID',
  `log_managerName` varchar(64) DEFAULT NULL COMMENT '管理员帐号',
  `log_managerEmail` varchar(64) DEFAULT NULL COMMENT '管理员邮箱',
  PRIMARY KEY (`log_id`),
  KEY `Type_ManagerId` (`log_type`,`log_managerId`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC COMMENT='管理员登录日志';

/*Table structure for table `tb_manager_log_remark` */

CREATE TABLE `tb_manager_log_remark` (
  `log_id` int(11) unsigned NOT NULL COMMENT '流水号',
  `log_user_agent` varchar(256) NOT NULL COMMENT '浏览器信息',
  `log_remark` text COMMENT '备注',
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `tb_manager_log_remark_2014` */

CREATE TABLE `tb_manager_log_remark_2014` (
  `log_id` int(11) unsigned NOT NULL COMMENT '流水号',
  `log_user_agent` varchar(256) NOT NULL COMMENT '浏览器信息',
  `log_remark` text COMMENT '备注',
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `tb_manager_log_resetpwd` */

CREATE TABLE `tb_manager_log_resetpwd` (
  `log_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '流水号',
  `log_time` int(10) DEFAULT NULL COMMENT '活动时间',
  `log_ip` varchar(64) DEFAULT NULL COMMENT '密码修改IP',
  `log_managerId` int(10) DEFAULT NULL COMMENT '用户ID',
  `log_managerName` varchar(64) DEFAULT NULL COMMENT '用户帐号',
  `log_managerEmail` varchar(64) DEFAULT NULL COMMENT '用户邮箱',
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC COMMENT='用户密码修改日志';

/*Table structure for table `tb_manager_menu` */

CREATE TABLE `tb_manager_menu` (
  `menu_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `menu_fatherId` int(11) unsigned NOT NULL DEFAULT '0',
  `menu_label_zh-cn` varchar(16) NOT NULL COMMENT '名称',
  `menu_label_zh-tw` varchar(16) DEFAULT NULL,
  `menu_label_en-us` varchar(16) DEFAULT NULL,
  `menu_link` varchar(64) DEFAULT NULL COMMENT 'module/controller/action',
  `menu_show` int(11) unsigned NOT NULL DEFAULT '0',
  `menu_order` int(11) unsigned NOT NULL DEFAULT '0',
  `menu_insert_time` int(11) unsigned NOT NULL DEFAULT '0',
  `menu_insert_manId` int(11) DEFAULT '0',
  `menu_insert_manName` varchar(32) DEFAULT NULL,
  `menu_insert_manEmail` varchar(32) DEFAULT NULL,
  `menu_insert_ip` varchar(32) DEFAULT NULL,
  `menu_update_time` int(11) NOT NULL DEFAULT '0',
  `menu_update_manId` int(11) DEFAULT '0',
  `menu_update_manName` varchar(32) DEFAULT NULL,
  `menu_update_manEmail` varchar(32) DEFAULT NULL,
  `menu_update_ip` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`menu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=165 DEFAULT CHARSET=utf8;

/*Table structure for table `tb_manager_msg` */

CREATE TABLE `tb_manager_msg` (
  `msg_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `msg_source` int(11) NOT NULL DEFAULT '0' COMMENT '发送人',
  `msg_subject` varchar(64) NOT NULL COMMENT '标题',
  `msg_insert_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `msg_insert_ip` varchar(32) DEFAULT NULL COMMENT '创建IP',
  `msg_update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '保存时间',
  `msg_update_ip` varchar(32) DEFAULT NULL COMMENT '保存IP',
  `msg_delete_inbox` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '发件人是否已经删除了',
  `msg_delete_outbox` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '收件人是否已经全部删除了',
  PRIMARY KEY (`msg_id`),
  KEY `Delete_Inbox_Outbox` (`msg_delete_inbox`,`msg_delete_outbox`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC COMMENT='发件箱';

/*Table structure for table `tb_manager_msg_content` */

CREATE TABLE `tb_manager_msg_content` (
  `msg_id` int(11) unsigned NOT NULL COMMENT '消息流水号',
  `msg_content` varchar(512) NOT NULL COMMENT '消息内容',
  PRIMARY KEY (`msg_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `tb_manager_msg_dest` */

CREATE TABLE `tb_manager_msg_dest` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `msg_id` int(11) unsigned NOT NULL DEFAULT '0',
  `msg_dest` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC COMMENT='收件人';

/*Table structure for table `tb_manager_msg_inbox` */

CREATE TABLE `tb_manager_msg_inbox` (
  `inbox_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `inbox_msgId` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '消息ID',
  `inbox_manId` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '收件人',
  `inbox_msg_accept_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '收件时间',
  `inbox_msg_read_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '读件时间',
  PRIMARY KEY (`inbox_id`),
  UNIQUE KEY `MsgId_ManagerId` (`inbox_msgId`,`inbox_manId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC COMMENT='收件箱';

/*Table structure for table `tb_manager_msg_outbox` */

CREATE TABLE `tb_manager_msg_outbox` (
  `outbox_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `outbox_msgId` int(11) unsigned NOT NULL DEFAULT '0',
  `outbox_manId` int(11) unsigned NOT NULL DEFAULT '0',
  `outbox_msg_send_time` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`outbox_id`),
  UNIQUE KEY `MsgId_ManagerId` (`outbox_msgId`,`outbox_manId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `tb_manager_role` */

CREATE TABLE `tb_manager_role` (
  `role_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `role_name` varchar(16) DEFAULT NULL,
  `role_admin` int(11) unsigned NOT NULL DEFAULT '0',
  `role_lock_time` int(11) unsigned NOT NULL DEFAULT '0',
  `role_insert_time` int(11) unsigned NOT NULL DEFAULT '0',
  `role_insert_manId` int(11) DEFAULT NULL,
  `role_insert_manName` varchar(32) DEFAULT NULL,
  `role_insert_manEmail` varchar(32) DEFAULT NULL,
  `role_insert_ip` varchar(32) DEFAULT NULL,
  `role_update_time` int(11) NOT NULL DEFAULT '0',
  `role_update_manId` int(11) DEFAULT NULL,
  `role_update_manName` varchar(32) DEFAULT NULL,
  `role_update_manEmail` varchar(64) DEFAULT NULL,
  `role_update_ip` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Table structure for table `tb_manager_role_menu` */

CREATE TABLE `tb_manager_role_menu` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(11) unsigned NOT NULL DEFAULT '0',
  `menu_id` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `RoleId_MenuId` (`role_id`,`menu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=828 DEFAULT CHARSET=utf8;

/*Table structure for table `tb_member` */

CREATE TABLE `tb_member` (
  `member_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `member_roleId` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '角色ID',
  `member_email` varchar(32) NOT NULL COMMENT '会员邮箱',
  `member_name` varchar(32) NOT NULL COMMENT '会员名称',
  `member_passwd` varchar(32) NOT NULL COMMENT '密码',
  `member_passwd_change_count` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '密码修改次数',
  `member_passwd_change_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '密码最后修改时间',
  `member_passwd_change_ip` varchar(32) DEFAULT NULL COMMENT '密码最后修改IP',
  `member_login_count` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '登录次数',
  `member_login_last_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `member_login_last_ip` varchar(32) DEFAULT NULL COMMENT '最后登录IP',
  `member_lock_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '会员锁定时间',
  `member_pass_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '会员审核通过时间',
  `member_activeEmail_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '邮箱激活时间',
  `member_insert_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
  `member_insert_manId` int(11) DEFAULT '0',
  `member_insert_manName` varchar(32) DEFAULT NULL,
  `member_insert_manEmail` varchar(32) DEFAULT NULL,
  `member_insert_ip` varchar(32) DEFAULT NULL COMMENT '注册IP',
  `member_insert_addr` varchar(32) DEFAULT NULL COMMENT '注册地点',
  `member_update_time` int(11) DEFAULT '0',
  `member_update_manId` int(11) DEFAULT '0',
  `member_update_manName` varchar(32) DEFAULT NULL,
  `member_update_manEmail` varchar(32) DEFAULT NULL,
  `member_update_ip` varchar(32) DEFAULT NULL,
  `member_update_addr` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`member_id`),
  UNIQUE KEY `Email` (`member_email`),
  UNIQUE KEY `Name` (`member_name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Table structure for table `tb_member_config` */

CREATE TABLE `tb_member_config` (
  `conf_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '流水号',
  `conf_fatherId` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父级',
  `conf_key` varchar(32) NOT NULL COMMENT '键',
  `conf_label` varchar(32) NOT NULL COMMENT '名称',
  `conf_default` varchar(128) DEFAULT NULL COMMENT '默认值',
  `conf_input_type` enum('input','select','textarea','checkbox','radio') DEFAULT 'input' COMMENT '表单类型',
  `conf_options` varchar(512) DEFAULT NULL COMMENT '备选项',
  `conf_remark` varchar(128) DEFAULT NULL COMMENT '备注',
  `conf_order` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `conf_create_time` int(11) NOT NULL DEFAULT '0',
  `conf_create_manId` int(11) DEFAULT '0',
  `conf_create_manName` varchar(32) DEFAULT NULL,
  `conf_create_manEmail` varchar(32) DEFAULT NULL,
  `conf_create_ip` varchar(32) DEFAULT NULL,
  `conf_create_addr` varchar(32) DEFAULT NULL,
  `conf_alter_time` int(11) DEFAULT '0',
  `conf_alter_manId` int(11) DEFAULT '0',
  `conf_alter_manName` varchar(32) DEFAULT NULL,
  `conf_alter_manEmail` varchar(32) DEFAULT NULL,
  `conf_alter_ip` varchar(32) DEFAULT NULL,
  `conf_alter_addr` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`conf_id`),
  UNIQUE KEY `ConfKey` (`conf_key`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8;

/*Table structure for table `tb_member_config_update_history` */

CREATE TABLE `tb_member_config_update_history` (
  `history_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `history_memberId` int(11) DEFAULT NULL,
  `history_configId` int(11) DEFAULT NULL,
  `history_configKey` varchar(64) NOT NULL,
  `history_configValue` varchar(64) NOT NULL,
  `history_insert_time` int(11) unsigned NOT NULL DEFAULT '0',
  `history_insert_ip` varchar(32) NOT NULL,
  `history_insert_addr` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`history_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `tb_member_config_value` */

CREATE TABLE `tb_member_config_value` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `conf_id` int(11) unsigned NOT NULL DEFAULT '0',
  `conf_memberId` int(11) unsigned NOT NULL DEFAULT '0',
  `conf_value` varchar(128) NOT NULL,
  `conf_insert_time` int(11) unsigned NOT NULL DEFAULT '0',
  `conf_insert_ip` varchar(32) DEFAULT NULL,
  `conf_update_time` int(11) unsigned NOT NULL DEFAULT '0',
  `conf_update_ip` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ConfigMember` (`conf_id`,`conf_memberId`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Table structure for table `tb_member_favorite` */

CREATE TABLE `tb_member_favorite` (
  `favorite_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `favorite_memberId` int(11) unsigned NOT NULL DEFAULT '0',
  `favorite_websiteId` int(11) unsigned NOT NULL DEFAULT '0',
  `favorite_insert_time` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`favorite_id`),
  UNIQUE KEY `MemberId_WebsiteId` (`favorite_memberId`,`favorite_websiteId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `tb_member_friend` */

CREATE TABLE `tb_member_friend` (
  `friend_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `friend_master_memberId` int(11) unsigned NOT NULL DEFAULT '0',
  `friend_guest_memberId` int(11) unsigned NOT NULL DEFAULT '0',
  `friend_request_time` int(11) unsigned NOT NULL DEFAULT '0',
  `friend_request_ip` varchar(32) DEFAULT NULL,
  `friend_insert_time` int(11) unsigned NOT NULL DEFAULT '0',
  `friend_insert_ip` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`friend_id`),
  UNIQUE KEY `Master_Guest` (`friend_master_memberId`,`friend_guest_memberId`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;

/*Table structure for table `tb_member_friend_request` */

CREATE TABLE `tb_member_friend_request` (
  `request_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '流水号',
  `request_source` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '请求发起人',
  `request_dest` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '请求接收人',
  `request_remark` varchar(256) DEFAULT NULL COMMENT '请求备注',
  `request_insert_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '发送时间',
  `request_insert_ip` varchar(32) DEFAULT NULL COMMENT '发送IP',
  `request_pass` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '处理结果，3未处理，1接受，2拒绝',
  `request_pass_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '通过时间',
  `request_pass_ip` varchar(32) DEFAULT NULL COMMENT '通过IP',
  PRIMARY KEY (`request_id`),
  KEY `Source` (`request_source`),
  KEY `Dest` (`request_dest`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC COMMENT='会员交友请求';

/*Table structure for table `tb_member_history_password` */

CREATE TABLE `tb_member_history_password` (
  `history_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '流水号',
  `history_time` int(11) NOT NULL DEFAULT '0' COMMENT '时间',
  `history_memberId` int(11) NOT NULL DEFAULT '0' COMMENT '会员ID',
  `history_memberPassword` varchar(32) NOT NULL COMMENT '旧密码',
  PRIMARY KEY (`history_id`),
  KEY `MemberId` (`history_memberId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `tb_member_log` */

CREATE TABLE `tb_member_log` (
  `log_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '流水号',
  `log_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '登录时间',
  `log_ip` varchar(64) NOT NULL COMMENT '登录IP',
  `log_type` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '日志类型',
  `log_sessionId` varchar(64) DEFAULT NULL COMMENT '会话ID',
  `log_memberId` int(10) unsigned DEFAULT '0' COMMENT '用户ID',
  `log_memberName` varchar(64) DEFAULT NULL COMMENT '用户帐号',
  `log_memberEmail` varchar(64) DEFAULT NULL COMMENT '用户邮箱',
  PRIMARY KEY (`log_id`),
  KEY `MemberId` (`log_memberId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC COMMENT='用户登录日志';

/*Table structure for table `tb_member_log_2014` */

CREATE TABLE `tb_member_log_2014` (
  `log_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '流水号',
  `log_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '登录时间',
  `log_ip` varchar(64) NOT NULL COMMENT '登录IP',
  `log_type` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '日志类型',
  `log_sessionId` varchar(64) DEFAULT NULL COMMENT '会话ID',
  `log_memberId` int(10) unsigned DEFAULT '0' COMMENT '用户ID',
  `log_memberName` varchar(64) DEFAULT NULL COMMENT '用户帐号',
  `log_memberEmail` varchar(64) DEFAULT NULL COMMENT '用户邮箱',
  PRIMARY KEY (`log_id`),
  KEY `MemberId` (`log_memberId`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC COMMENT='用户登录日志';

/*Table structure for table `tb_member_log_remark` */

CREATE TABLE `tb_member_log_remark` (
  `log_id` int(11) unsigned NOT NULL COMMENT '流水号',
  `log_user_agent` varchar(256) NOT NULL COMMENT '浏览器信息',
  `log_remark` text COMMENT '备注',
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `tb_member_log_remark_2014` */

CREATE TABLE `tb_member_log_remark_2014` (
  `log_id` int(11) unsigned NOT NULL COMMENT '流水号',
  `log_user_agent` varchar(256) NOT NULL COMMENT '浏览器信息',
  `log_remark` text COMMENT '备注',
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `tb_member_menu` */

CREATE TABLE `tb_member_menu` (
  `menu_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `menu_fatherId` int(11) unsigned NOT NULL DEFAULT '0',
  `menu_label_zh-cn` varchar(16) NOT NULL COMMENT '名称',
  `menu_label_zh-tw` varchar(16) DEFAULT NULL,
  `menu_label_en-us` varchar(16) DEFAULT NULL,
  `menu_link` varchar(64) DEFAULT NULL COMMENT 'module/controller/action',
  `menu_show` int(11) unsigned NOT NULL DEFAULT '0',
  `menu_order` int(11) unsigned NOT NULL DEFAULT '0',
  `menu_insert_time` int(11) unsigned NOT NULL DEFAULT '0',
  `menu_insert_manId` int(11) DEFAULT '0',
  `menu_insert_manName` varchar(32) DEFAULT NULL,
  `menu_insert_manEmail` varchar(32) DEFAULT NULL,
  `menu_insert_ip` varchar(32) DEFAULT NULL,
  `menu_update_time` int(11) NOT NULL DEFAULT '0',
  `menu_update_manId` int(11) DEFAULT '0',
  `menu_update_manName` varchar(32) DEFAULT NULL,
  `menu_update_manEmail` varchar(32) DEFAULT NULL,
  `menu_update_ip` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`menu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=192 DEFAULT CHARSET=utf8;

/*Table structure for table `tb_member_msg` */

CREATE TABLE `tb_member_msg` (
  `msg_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `msg_source` int(11) NOT NULL DEFAULT '0' COMMENT '发送人',
  `msg_subject` varchar(64) NOT NULL COMMENT '标题',
  `msg_insert_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `msg_insert_ip` varchar(32) DEFAULT NULL COMMENT '创建IP',
  `msg_update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '保存时间',
  `msg_update_ip` varchar(32) DEFAULT NULL COMMENT '保存IP',
  `msg_delete_inbox` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '发件人是否已经删除了',
  `msg_delete_outbox` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '收件人是否已经全部删除了',
  PRIMARY KEY (`msg_id`),
  KEY `Delete_Inbox_Outbox` (`msg_delete_inbox`,`msg_delete_outbox`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC COMMENT='发件箱';

/*Table structure for table `tb_member_msg_content` */

CREATE TABLE `tb_member_msg_content` (
  `msg_id` int(11) unsigned NOT NULL COMMENT '消息流水号',
  `msg_content` varchar(512) NOT NULL COMMENT '消息内容',
  PRIMARY KEY (`msg_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `tb_member_msg_dest` */

CREATE TABLE `tb_member_msg_dest` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `msg_id` int(11) unsigned NOT NULL DEFAULT '0',
  `msg_dest` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC COMMENT='收件人';

/*Table structure for table `tb_member_msg_inbox` */

CREATE TABLE `tb_member_msg_inbox` (
  `inbox_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `inbox_msgId` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '消息ID',
  `inbox_manId` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '收件人',
  `inbox_msg_accept_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '收件时间',
  `inbox_msg_read_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '读件时间',
  PRIMARY KEY (`inbox_id`),
  UNIQUE KEY `MsgId_MemberId` (`inbox_msgId`,`inbox_manId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC COMMENT='收件箱';

/*Table structure for table `tb_member_msg_outbox` */

CREATE TABLE `tb_member_msg_outbox` (
  `outbox_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `outbox_msgId` int(11) unsigned NOT NULL DEFAULT '0',
  `outbox_manId` int(11) unsigned NOT NULL DEFAULT '0',
  `outbox_msg_send_time` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`outbox_id`),
  UNIQUE KEY `MsgId_MemberId` (`outbox_msgId`,`outbox_manId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `tb_member_role` */

CREATE TABLE `tb_member_role` (
  `role_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `role_name` varchar(16) DEFAULT NULL,
  `role_admin` int(11) unsigned NOT NULL DEFAULT '0',
  `role_lock_time` int(11) unsigned NOT NULL DEFAULT '0',
  `role_insert_time` int(11) unsigned NOT NULL DEFAULT '0',
  `role_insert_manId` int(11) DEFAULT NULL,
  `role_insert_manName` varchar(32) DEFAULT NULL,
  `role_insert_manEmail` varchar(32) DEFAULT NULL,
  `role_insert_ip` varchar(32) DEFAULT NULL,
  `role_update_time` int(11) NOT NULL DEFAULT '0',
  `role_update_manId` int(11) DEFAULT NULL,
  `role_update_manName` varchar(32) DEFAULT NULL,
  `role_update_manEmail` varchar(64) DEFAULT NULL,
  `role_update_ip` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

/*Table structure for table `tb_member_role_menu` */

CREATE TABLE `tb_member_role_menu` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(11) unsigned NOT NULL DEFAULT '0',
  `menu_id` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `RoleId_MenuId` (`role_id`,`menu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=164 DEFAULT CHARSET=utf8;

/*Table structure for table `tb_member_trend_login` */

CREATE TABLE `tb_member_trend_login` (
  `trend_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `trend_eventId` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_year` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_month` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_day` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_0` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_1` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_2` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_3` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_4` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_5` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_6` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_7` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_8` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_9` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_10` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_11` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_12` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_13` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_14` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_15` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_16` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_17` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_18` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_19` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_20` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_21` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_22` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_23` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_total` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`trend_id`),
  UNIQUE KEY `Month_Day_Event` (`trend_month`,`trend_day`,`trend_eventId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `tb_member_trend_register` */

CREATE TABLE `tb_member_trend_register` (
  `trend_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `trend_year` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_month` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_day` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_0` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_1` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_2` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_3` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_4` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_5` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_6` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_7` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_8` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_9` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_10` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_11` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_12` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_13` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_14` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_15` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_16` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_17` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_18` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_19` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_20` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_21` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_22` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_23` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_total` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`trend_id`),
  UNIQUE KEY `Month_Day` (`trend_month`,`trend_day`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `tb_safe_filter_word` */

CREATE TABLE `tb_safe_filter_word` (
  `word_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '流水号',
  `word_val` varchar(32) NOT NULL COMMENT '词库',
  `word_count` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '这个词库的替换次数',
  `word_insert_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '插入时间',
  `word_update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`word_id`),
  UNIQUE KEY `Val` (`word_val`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC COMMENT='敏感词库';

/*Table structure for table `tb_state` */

CREATE TABLE `tb_state` (
  `state_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '流水号',
  `state_fatherId` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父级',
  `state_key` varchar(32) NOT NULL COMMENT '键',
  `state_label` varchar(32) NOT NULL COMMENT '名称',
  `state_value` varchar(128) NOT NULL COMMENT '值',
  `state_remark` varchar(128) DEFAULT NULL COMMENT '备注',
  `state_order` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `state_insert_time` int(11) NOT NULL DEFAULT '0',
  `state_update_time` int(11) DEFAULT '0',
  PRIMARY KEY (`state_id`),
  UNIQUE KEY `ConfKey` (`state_key`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC COMMENT='网站的状况';

/*Table structure for table `tb_website` */

CREATE TABLE `tb_website` (
  `website_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '流水号',
  `website_memberId` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '归属ID',
  `website_name` varchar(32) NOT NULL COMMENT '网站名',
  `website_domain` varchar(128) NOT NULL COMMENT '网站主域名',
  `website_logo` varchar(128) DEFAULT NULL COMMENT '网站标志',
  `website_cover` varchar(128) DEFAULT NULL COMMENT '网站封面图片',
  `website_tag` varchar(512) DEFAULT NULL COMMENT '标签',
  `website_areaId` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '区域ID',
  `website_categoryId` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '类别ID',
  `website_click_in` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '流入点击',
  `website_click_out` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '流出点击',
  `website_lock_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '锁定时间',
  `website_insert_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '写入时间',
  `website_insert_man` varchar(32) DEFAULT NULL,
  `website_insert_ip` varchar(32) DEFAULT NULL,
  `website_update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `website_update_man` varchar(32) DEFAULT NULL,
  `website_update_ip` varchar(32) DEFAULT NULL,
  `website_update_stat` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改状态1无修改申请2有修改申请3拒绝此次修改',
  `website_update_count` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改次数',
  `website_delete_stat` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '删除状态0无删除申请1已提交申请',
  `website_applyId` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '申请ID',
  `website_apply_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '申请时间',
  `website_apply_ip` varchar(32) NOT NULL COMMENT '申请IP',
  PRIMARY KEY (`website_id`),
  UNIQUE KEY `Name` (`website_name`),
  UNIQUE KEY `Domain` (`website_domain`),
  KEY `CetgoryId_Continent_Country` (`website_categoryId`),
  KEY `Id_Tag` (`website_id`,`website_tag`(255))
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Table structure for table `tb_website_apply_delete` */

CREATE TABLE `tb_website_apply_delete` (
  `delete_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '流水号',
  `delete_source` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '删除请求的来源，1是前台，2是后台',
  `delete_sourceId` int(11) DEFAULT '0' COMMENT '前台帐号ID或是后台帐号ID',
  `delete_insert_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '删除申请提交时间',
  `delete_insert_ip` varchar(64) NOT NULL COMMENT '删除申请提交IP',
  `delete_pass_time` int(11) unsigned NOT NULL COMMENT '删除申请时间',
  `delete_pass_ip` varchar(64) DEFAULT NULL COMMENT '删除申请IP',
  `delete_pass_man` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '后台审核的ID',
  `delete_pass` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '状态0未处理1同意2拒绝',
  `delete_reply` varchar(128) DEFAULT NULL COMMENT '删除回复',
  `website_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '网站ID',
  `website_row` text NOT NULL COMMENT 'JSON处理过的website表纪录',
  PRIMARY KEY (`delete_id`),
  KEY `Pass_WebsiteId` (`delete_pass`,`website_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `tb_website_apply_register` */

CREATE TABLE `tb_website_apply_register` (
  `register_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '流水号',
  `website_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '网站ID',
  `website_memberId` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '归属ID',
  `website_name` varchar(32) NOT NULL COMMENT '网站名',
  `website_domain` varchar(128) NOT NULL COMMENT '网站主域名',
  `website_logo` varchar(128) DEFAULT NULL COMMENT '网站标志',
  `website_cover` varchar(128) DEFAULT NULL COMMENT '网站封面图片',
  `website_tag` varchar(512) DEFAULT NULL COMMENT '标签',
  `website_categoryId` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '类别',
  `website_areaId` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '哪个国家',
  `register_insert_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '写入时间',
  `register_insert_ip` varchar(32) DEFAULT NULL COMMENT '写入IP',
  `register_insert_addr` varchar(32) DEFAULT NULL,
  `register_update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '提交时间',
  `register_update_ip` varchar(32) DEFAULT NULL COMMENT '更新IP',
  `register_update_addr` varchar(32) DEFAULT NULL,
  `register_pass_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '处理时间',
  `register_pass_ip` varchar(32) DEFAULT NULL COMMENT '处理IP',
  `register_pass_addr` varchar(32) DEFAULT NULL,
  `register_pass` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '是否通过申请（3未处理，1通过，2未通过）',
  PRIMARY KEY (`register_id`),
  KEY `WebsiteId` (`website_id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Table structure for table `tb_website_apply_register_intro` */

CREATE TABLE `tb_website_apply_register_intro` (
  `register_id` int(11) unsigned NOT NULL,
  `website_intro` text NOT NULL,
  PRIMARY KEY (`register_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC COMMENT='网站注册申请-简介';

/*Table structure for table `tb_website_apply_update` */

CREATE TABLE `tb_website_apply_update` (
  `update_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '更新流水号',
  `update_insert_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新的创建时间',
  `update_insert_ip` varchar(64) NOT NULL COMMENT '更新的创建IP',
  `update_pass_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '通过时间',
  `update_pass_ip` varchar(64) NOT NULL COMMENT '通过IP',
  `update_update_time` int(11) DEFAULT '0' COMMENT '更新时间',
  `update_update_ip` varchar(64) DEFAULT NULL COMMENT '更新IP',
  `update_count` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '当前纪录在通过之前又改了几次',
  `update_pass` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '是否通过，0未处理，1通过，2不通过',
  `update_reply` varchar(128) DEFAULT NULL COMMENT '更新回复',
  `website_id` int(11) unsigned NOT NULL COMMENT '流水号',
  `website_memberId` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '归属ID',
  `website_name` varchar(32) NOT NULL COMMENT '网站名',
  `website_domain` varchar(128) NOT NULL COMMENT '网站主域名',
  `website_tag` varchar(512) DEFAULT NULL COMMENT '标签',
  `website_categoryId` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '类别',
  `website_continent` enum('as','eu','na','sa','oa','af','an') NOT NULL DEFAULT 'as' COMMENT '哪五大洲',
  `website_country` varchar(32) DEFAULT NULL COMMENT '哪个国家',
  PRIMARY KEY (`update_id`),
  KEY `Pass_WebsiteId` (`update_pass`,`website_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC COMMENT='网站更新申请';

/*Table structure for table `tb_website_apply_update_intro` */

CREATE TABLE `tb_website_apply_update_intro` (
  `update_id` int(11) unsigned NOT NULL,
  `website_intro` text,
  PRIMARY KEY (`update_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `tb_website_area` */

CREATE TABLE `tb_website_area` (
  `area_id` int(11) unsigned NOT NULL,
  `area_fatherId` int(11) NOT NULL DEFAULT '0' COMMENT '父亲ID',
  `area_name_zh-cn` varchar(64) DEFAULT NULL,
  `area_name_zh-tw` varchar(64) DEFAULT NULL,
  `area_name_en-us` varchar(64) DEFAULT NULL,
  `area_count_website` int(11) NOT NULL DEFAULT '0',
  `area_count_click_in` int(11) NOT NULL DEFAULT '0',
  `area_count_click_out` int(11) NOT NULL DEFAULT '0',
  `area_order` int(11) NOT NULL DEFAULT '0',
  `area_insert_time` int(11) NOT NULL DEFAULT '0',
  `area_insert_man` varchar(32) DEFAULT NULL,
  `area_insert_ip` varchar(32) DEFAULT NULL,
  `area_update_time` int(11) NOT NULL DEFAULT '0',
  `area_update_man` varchar(32) DEFAULT NULL,
  `area_update_ip` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`area_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `tb_website_category` */

CREATE TABLE `tb_website_category` (
  `category_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `category_fatherId` int(11) unsigned NOT NULL DEFAULT '0',
  `category_name_zh-cn` varchar(32) DEFAULT NULL,
  `category_name_zh-tw` varchar(32) DEFAULT NULL,
  `category_name_en-us` varchar(32) DEFAULT NULL,
  `category_count_website` int(11) unsigned NOT NULL DEFAULT '0',
  `category_count_click_in` int(11) unsigned NOT NULL DEFAULT '0',
  `category_count_click_out` int(11) unsigned NOT NULL DEFAULT '0',
  `category_order` int(11) unsigned NOT NULL DEFAULT '0',
  `category_insert_time` int(11) unsigned NOT NULL DEFAULT '0',
  `category_update_tme` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2401 DEFAULT CHARSET=utf8;

/*Table structure for table `tb_website_history` */

CREATE TABLE `tb_website_history` (
  `history_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `history_websiteId` int(11) DEFAULT NULL,
  `history_website` text,
  `history_insert_time` int(11) DEFAULT NULL,
  `history_insert_man` int(11) DEFAULT NULL,
  PRIMARY KEY (`history_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `tb_website_intro` */

CREATE TABLE `tb_website_intro` (
  `website_id` int(11) unsigned NOT NULL,
  `website_intro` text,
  `website_intro_insert_time` int(11) NOT NULL DEFAULT '0',
  `website_intro_insert_ip` varchar(32) DEFAULT NULL,
  `website_intro_update_time` int(11) unsigned NOT NULL DEFAULT '0',
  `website_intro_update_ip` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`website_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `tb_website_keyword` */

CREATE TABLE `tb_website_keyword` (
  `keyword_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `keyword_label` varchar(32) NOT NULL,
  `keyword_count_enter` int(11) unsigned NOT NULL DEFAULT '0',
  `keyword_insert_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '插入时间',
  `keyword_update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '最后查询时间',
  PRIMARY KEY (`keyword_id`),
  UNIQUE KEY `Label` (`keyword_label`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

/*Table structure for table `tb_website_meta` */

CREATE TABLE `tb_website_meta` (
  `website_id` int(11) unsigned NOT NULL,
  `website_meta_keywords` varchar(512) DEFAULT NULL,
  `website_meta_description` varchar(512) DEFAULT NULL,
  PRIMARY KEY (`website_id`),
  UNIQUE KEY `WebsiteId` (`website_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `tb_website_tag` */

CREATE TABLE `tb_website_tag` (
  `tag_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '流水号',
  `tag_name` varchar(64) NOT NULL COMMENT '标签名',
  `tag_count` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '标签被使用数',
  `tag_insert_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '标签时间',
  `tag_update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '标签更新时间',
  PRIMARY KEY (`tag_id`),
  UNIQUE KEY `Name` (`tag_name`)
) ENGINE=InnoDB AUTO_INCREMENT=192 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Table structure for table `tb_website_tag_relation` */

CREATE TABLE `tb_website_tag_relation` (
  `relation_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `relation_websiteId` int(11) unsigned NOT NULL,
  `relation_tagId` int(11) unsigned NOT NULL,
  `relation_time` int(11) unsigned NOT NULL,
  PRIMARY KEY (`relation_id`),
  UNIQUE KEY `WebId_TagId` (`relation_websiteId`,`relation_tagId`)
) ENGINE=InnoDB AUTO_INCREMENT=204 DEFAULT CHARSET=utf8;

/*Table structure for table `tb_website_trend_apply_register` */

CREATE TABLE `tb_website_trend_apply_register` (
  `trend_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `trend_year` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_month` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_day` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_0` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_1` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_2` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_3` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_4` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_5` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_6` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_7` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_8` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_9` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_10` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_11` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_12` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_13` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_14` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_15` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_16` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_17` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_18` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_19` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_20` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_21` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_22` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_23` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_total` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`trend_id`),
  UNIQUE KEY `Month_Day` (`trend_month`,`trend_day`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `tb_website_trend_apply_register_2014` */

CREATE TABLE `tb_website_trend_apply_register_2014` (
  `trend_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `trend_year` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_month` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_day` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_0` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_1` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_2` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_3` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_4` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_5` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_6` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_7` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_8` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_9` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_10` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_11` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_12` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_13` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_14` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_15` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_16` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_17` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_18` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_19` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_20` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_21` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_22` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_23` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_total` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`trend_id`),
  UNIQUE KEY `Month_Day` (`trend_month`,`trend_day`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Table structure for table `tb_website_trend_click_in` */

CREATE TABLE `tb_website_trend_click_in` (
  `trend_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `trend_eventId` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_year` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_month` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_day` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_0` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_1` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_2` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_3` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_4` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_5` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_6` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_7` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_8` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_9` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_10` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_11` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_12` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_13` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_14` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_15` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_16` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_17` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_18` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_19` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_20` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_21` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_22` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_23` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_total` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`trend_id`),
  UNIQUE KEY `Month_Day_Event` (`trend_month`,`trend_day`,`trend_eventId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `tb_website_trend_click_out` */

CREATE TABLE `tb_website_trend_click_out` (
  `trend_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `trend_eventId` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_year` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_month` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_day` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_0` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_1` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_2` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_3` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_4` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_5` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_6` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_7` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_8` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_9` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_10` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_11` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_12` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_13` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_14` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_15` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_16` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_17` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_18` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_19` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_20` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_21` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_22` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_23` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_total` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`trend_id`),
  UNIQUE KEY `Month_Day_Event` (`trend_month`,`trend_day`,`trend_eventId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `tb_website_trend_click_out_2014` */

CREATE TABLE `tb_website_trend_click_out_2014` (
  `trend_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `trend_eventId` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_year` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_month` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_day` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_0` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_1` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_2` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_3` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_4` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_5` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_6` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_7` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_8` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_9` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_10` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_11` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_12` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_13` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_14` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_15` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_16` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_17` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_18` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_19` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_20` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_21` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_22` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_23` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_total` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`trend_id`),
  UNIQUE KEY `Month_Day_Event` (`trend_month`,`trend_day`,`trend_eventId`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Table structure for table `tb_website_trend_register` */

CREATE TABLE `tb_website_trend_register` (
  `trend_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `trend_year` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_month` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_day` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_0` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_1` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_2` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_3` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_4` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_5` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_6` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_7` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_8` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_9` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_10` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_11` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_12` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_13` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_14` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_15` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_16` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_17` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_18` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_19` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_20` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_21` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_22` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_23` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_total` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`trend_id`),
  UNIQUE KEY `Month_Day` (`trend_month`,`trend_day`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `tb_website_trend_register_2014` */

CREATE TABLE `tb_website_trend_register_2014` (
  `trend_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `trend_year` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_month` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_day` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_0` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_1` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_2` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_3` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_4` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_5` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_6` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_7` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_8` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_9` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_10` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_11` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_12` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_13` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_14` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_15` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_16` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_17` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_18` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_19` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_20` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_21` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_22` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_23` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_total` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`trend_id`),
  UNIQUE KEY `Month_Day` (`trend_month`,`trend_day`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Table structure for table `tb_website_trend_search` */

CREATE TABLE `tb_website_trend_search` (
  `trend_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `trend_eventId` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_year` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_month` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_day` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_0` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_1` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_2` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_3` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_4` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_5` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_6` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_7` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_8` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_9` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_10` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_11` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_12` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_13` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_14` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_15` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_16` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_17` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_18` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_19` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_20` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_21` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_22` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_23` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_total` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`trend_id`),
  UNIQUE KEY `Month_Day_Event` (`trend_month`,`trend_day`,`trend_eventId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `tb_website_trend_search_2014` */

CREATE TABLE `tb_website_trend_search_2014` (
  `trend_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `trend_eventId` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_year` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_month` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_day` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_0` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_1` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_2` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_3` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_4` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_5` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_6` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_7` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_8` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_9` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_10` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_11` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_12` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_13` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_14` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_15` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_16` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_17` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_18` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_19` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_20` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_21` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_22` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_23` int(11) unsigned NOT NULL DEFAULT '0',
  `trend_hour_total` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`trend_id`),
  UNIQUE KEY `Month_Day_Event` (`trend_month`,`trend_day`,`trend_eventId`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;

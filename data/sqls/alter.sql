/* 2013-12-21 给网站注册表增加字段website_id ，并设为唯一值 */
alter table `utf8_diana`.`tb_website_apply_register` add column `website_id` int(11) UNSIGNED DEFAULT '0' NOT NULL COMMENT '网站ID' after `register_id`;
alter table `utf8_diana`.`tb_website_apply_register` add unique `WebsiteId` (`website_id`);


alter table `utf8_diana`.`tb_bulletin` change `bulletin_type` `bulletin_type` int(11) UNSIGNED default '1' NOT NULL comment '4是www公告,2是client公告,1是admin公告,他们的总合是';
alter table `utf8_diana`.`tb_bulletin` change `bulletin_type` `bulletin_access` int(11) UNSIGNED default '1' NOT NULL comment '4是www公告,2是client公告,1是admin公告,他们的总合是';
alter table `utf8_diana`.`tb_bulletin` change `bulletin_access` `bulletin_access` int(11) UNSIGNED default '1' NOT NULL comment '4是www公告,2是client公告,1是admin公告,他们的总合是';

/*添加简繁英字段*/
alter table `utf8_diana`.`tb_bulletin_channel` add column `channel_label_zh-tw` varchar(32) NULL after `channel_label_zh-cn`, add column `channel_label_en-us` varchar(32) NULL after `channel_label_zh-tw`,change `channel_label` `channel_label_zh-cn` varchar(32) character set utf8 collate utf8_general_ci NOT NULL;
alter table `utf8_diana`.`tb_bulletin_channel` add column `channel_fatherId` int(11) UNSIGNED DEFAULT '0' NOT NULL COMMENT '父频道ID' after `channel_id`;
alter table `utf8_diana`.`tb_bulletin_channel` add column `channel_order` int(11) UNSIGNED NOT NULL COMMENT '排序' after `channel_label_en-us`;

/*给公告添加锁定日期字段*/
alter table `utf8_diana`.`tb_bulletin` change `bulletin_channel` `bulletin_channelId` int(11) UNSIGNED default '0' NOT NULL comment '公告频道';
alter table `utf8_diana`.`tb_bulletin` add column `bulletin_lock_time` int(11) UNSIGNED DEFAULT '0' NOT NULL COMMENT '锁定时间' after `bulletin_author`,change `bulletin_insert_time` `bulletin_insert_time` int(11) UNSIGNED default '0' NOT NULL;

/*公告频道给排序添加默认值*/
alter table `utf8_diana`.`tb_bulletin_channel` change `channel_order` `channel_order` int(11) UNSIGNED default '0' NOT NULL comment '排序';
/*website_update_stat附以新意义*/
alter table `utf8_diana`.`tb_website` change `website_update_stat` `website_update_stat` int(11) UNSIGNED default '0' NOT NULL comment '修改状态1无修改申请2有修改申请3拒绝此次修改', change `website_update_count` `website_update_count` int(11) UNSIGNED default '0' NOT NULL comment '修改次数';
/*更新会员消息功能*/
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

/*Table structure for table `tb_member_msg` */

DROP TABLE IF EXISTS `tb_member_msg`;

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

DROP TABLE IF EXISTS `tb_member_msg_content`;

CREATE TABLE `tb_member_msg_content` (
  `msg_id` int(11) unsigned NOT NULL COMMENT '消息流水号',
  `msg_content` varchar(512) NOT NULL COMMENT '消息内容',
  PRIMARY KEY (`msg_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `tb_member_msg_dest` */

DROP TABLE IF EXISTS `tb_member_msg_dest`;

CREATE TABLE `tb_member_msg_dest` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `msg_id` int(11) unsigned NOT NULL DEFAULT '0',
  `msg_dest` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC COMMENT='收件人';

/*Table structure for table `tb_member_msg_inbox` */

DROP TABLE IF EXISTS `tb_member_msg_inbox`;

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

DROP TABLE IF EXISTS `tb_member_msg_outbox`;

CREATE TABLE `tb_member_msg_outbox` (
  `outbox_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `outbox_msgId` int(11) unsigned NOT NULL DEFAULT '0',
  `outbox_manId` int(11) unsigned NOT NULL DEFAULT '0',
  `outbox_msg_send_time` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`outbox_id`),
  UNIQUE KEY `MsgId_MemberId` (`outbox_msgId`,`outbox_manId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;

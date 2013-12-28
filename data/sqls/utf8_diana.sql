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

DROP TABLE IF EXISTS `tb_bulletin`;

CREATE TABLE `tb_bulletin` (
  `bulletin_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '流水号',
  `bulletin_channel` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '公告频道',
  `bulletin_type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '1是www公告,2是client公告,3是admin公告',
  `bulletin_click` int(11) unsigned NOT NULL DEFAULT '0',
  `bulletin_top` int(11) DEFAULT '0' COMMENT '置顶级别',
  `bulletin_title` varchar(128) NOT NULL COMMENT '标题',
  `bulletin_author` varchar(32) NOT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC COMMENT='公告';

/*Data for the table `tb_bulletin` */

/*Table structure for table `tb_bulletin_channel` */

DROP TABLE IF EXISTS `tb_bulletin_channel`;

CREATE TABLE `tb_bulletin_channel` (
  `channel_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `channel_label` varchar(32) NOT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC COMMENT='公告频道';

/*Data for the table `tb_bulletin_channel` */

insert  into `tb_bulletin_channel`(`channel_id`,`channel_label`,`channel_count`,`channel_insert_time`,`channel_insert_manId`,`channel_insert_manName`,`channel_insert_manEmail`,`channel_insert_ip`,`channel_insert_addr`,`channel_update_time`,`channel_update_manId`,`channel_update_manName`,`channel_update_manEmail`,`channel_update_ip`,`channel_update_addr`) values (1,'Test',0,0,0,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL);

/*Table structure for table `tb_bulletin_content` */

DROP TABLE IF EXISTS `tb_bulletin_content`;

CREATE TABLE `tb_bulletin_content` (
  `bulletin_id` int(11) unsigned NOT NULL,
  `bulletin_time` int(11) NOT NULL DEFAULT '0',
  `bulletin_content` text NOT NULL,
  PRIMARY KEY (`bulletin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC COMMENT='公告内容';

/*Data for the table `tb_bulletin_content` */

/*Table structure for table `tb_config` */

DROP TABLE IF EXISTS `tb_config`;

CREATE TABLE `tb_config` (
  `conf_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '流水号',
  `conf_fatherId` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父级',
  `conf_key` varchar(32) NOT NULL COMMENT '键',
  `conf_label` varchar(32) NOT NULL COMMENT '名称',
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
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8;

/*Data for the table `tb_config` */

insert  into `tb_config`(`conf_id`,`conf_fatherId`,`conf_key`,`conf_label`,`conf_value`,`conf_default`,`conf_input_type`,`conf_options`,`conf_remark`,`conf_order`,`conf_insert_time`,`conf_insert_manId`,`conf_insert_manName`,`conf_insert_manEmail`,`conf_insert_ip`,`conf_insert_addr`,`conf_update_time`,`conf_update_manId`,`conf_update_manName`,`conf_update_manEmail`,`conf_update_ip`,`conf_update_addr`,`conf_alter_time`,`conf_alter_manId`,`conf_alter_manName`,`conf_alter_manEmail`,`conf_alter_ip`,`conf_alter_addr`) values (1,0,'captcha','验证码','',NULL,'input',NULL,'验证码开关',0,0,0,NULL,NULL,'113.107.76.19','广东省潮州市--电信',0,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL),(2,1,'captcha_font','字体目录','VI_DIR_DATA_FONT \"/faktos.ttf\"',NULL,'input',NULL,'字体目录',0,0,0,NULL,NULL,'113.107.76.19','广东省潮州市--电信',0,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL),(3,1,'captcha_fontsize','字体大小','20',NULL,'input',NULL,NULL,0,0,0,NULL,NULL,'113.107.76.19','广东省潮州市--电信',0,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL),(4,1,'captcha_imgdir ','图片存放目录','VI_DIR_TEMP_CAPTCHA',NULL,'input',NULL,NULL,0,0,0,NULL,NULL,'113.107.16.19','广东省潮州市--电信',0,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL),(5,1,'captcha_imgUrl','图片URL','VI_DIR_TEMP_CAPTCHA',NULL,'input',NULL,NULL,0,0,0,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL),(6,1,'captcha_width','图片的宽','100',NULL,'input',NULL,NULL,0,0,0,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL),(7,1,'captcha_height','图片的高','40',NULL,'input',NULL,NULL,0,0,0,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL),(8,1,'captcha_gcFreq','垃圾收集运行的频度','1000',NULL,'input',NULL,NULL,0,0,0,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL),(9,1,'captcha_wordlen','多少长度的验证码','4',NULL,'input',NULL,NULL,0,0,0,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL),(10,1,'captcha_useNumbers','使用数字否','false',NULL,'input',NULL,'输出限制',0,0,0,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL),(11,1,'captcha_DotNoiseLevel','噪点数目','1',NULL,'input',NULL,NULL,0,0,0,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL),(12,1,'captcha_lineNoiseLevel','横线条数目','1',NULL,'input',NULL,NULL,0,0,0,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL),(13,1,'captcha_Suffix','图片后缀','.jpg',NULL,'input',NULL,NULL,0,0,0,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL),(14,0,'sendmail','邮件发送','',NULL,'input',NULL,NULL,0,0,0,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL),(15,14,'sendmail_transport.type','发送方式','smtp',NULL,'input',NULL,NULL,0,0,0,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL),(16,14,'sendmail_transport.host','服务器地址','smtp.gmail.com',NULL,'input',NULL,NULL,0,0,0,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL),(17,14,'sendmail_transport.auth','服务器登录','login',NULL,'input',NULL,NULL,0,0,0,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL),(18,14,'sendmail_transport.username','服务器帐号','sendmail.sampan',NULL,'input',NULL,NULL,0,0,0,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL),(19,14,'sendmail_transport.password','服务器密码','gobyfeel',NULL,'input',NULL,NULL,0,0,0,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL),(20,14,'sendmail_transport.ssl','服务器安全','ssl',NULL,'input',NULL,NULL,0,0,0,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL),(21,14,'sendmail_transport.port','服务器端口','578',NULL,'input',NULL,NULL,0,0,0,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL),(22,14,'sendmail_defaultFrom.email','默认发送帐号','sendmail.sampan@gmail.com',NULL,'input',NULL,NULL,0,0,0,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL),(23,14,'sendmail_defaultFrom.name','默认发送人','sampan\'s sendmailer',NULL,'input',NULL,NULL,0,0,0,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL),(24,14,'sendmail_defaultReplyTo.email','默认接收回复帐号','sendmail.sampan@gmail.com',NULL,'input',NULL,NULL,0,0,0,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL),(25,14,'sendmail_defaultReplyTo.name','默认接收回复人','sampan\'s sendmailer',NULL,'input',NULL,NULL,0,0,0,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL),(26,0,'message','消息设置','',NULL,'input',NULL,NULL,0,0,0,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL),(27,26,'message_send_count_manager','管理员的消息数量限制','5000',NULL,'input',NULL,NULL,0,0,0,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL),(28,26,'message_send_count_member','一级会员的消息数量限制','5000',NULL,'input',NULL,NULL,0,0,0,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL),(29,0,'filter_word','敏感词库','',NULL,'input',NULL,NULL,0,0,0,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL),(30,29,'filter_word_size','敏感词库大小','small','small','select','small,medium,largen,huge',NULL,0,0,0,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL),(31,29,'filter_word_count_small','small词库量','1','1000','input',NULL,NULL,0,0,0,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL),(32,29,'filter_word_count_medium','medium词库量','2','2000','input',NULL,NULL,0,0,0,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL),(33,29,'filter_word_count_largen','largen词库量','4','4000','input',NULL,NULL,0,0,0,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL),(34,29,'filter_word_count_huge','huge词库量','8','8000','input',NULL,NULL,0,0,0,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL),(35,29,'filter_word_replace','敏感词替换字符','*','*','input',NULL,NULL,0,0,0,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL),(36,0,'user-safe','帐号安全','',NULL,'input',NULL,NULL,0,0,0,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL),(37,36,'user-safe_name_len_min','帐号长度最小值','2','4','input',NULL,'帐号长度最小值',0,0,0,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL),(38,36,'user-safe_name_len_max','帐号长度最大值','16','16','input',NULL,NULL,0,0,0,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL),(39,36,'user-safe_email_len_min','邮箱长度最小值','6',NULL,'input',NULL,NULL,0,0,0,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL),(42,36,'user-safe_email_len_max','邮箱长度最大值','32',NULL,'input',NULL,NULL,0,0,0,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL),(43,0,'member','会员相关','','','input','','',0,1386074268,1000,'审判长烧鸭','bay.sampanhuang@gmail.com','127.0.0.1','本机地址-- ',1386074268,1000,'审判长烧鸭','bay.sampanhuang@gmail.com','127.0.0.1','Array',1386074268,1000,'审判长烧鸭','bay.sampanhuang@gmail.com','127.0.0.1','Array'),(44,43,'member-register_default_role','会员注册默认的权限角色','10','10','input','','会员在注册的时候，默认是哪个角色',1,1386074391,1000,'审判长烧鸭','bay.sampanhuang@gmail.com','127.0.0.1','本机地址-- ',1386074391,1000,'审判长烧鸭','bay.sampanhuang@gmail.com','127.0.0.1','Array',1386074438,1000,'审判长烧鸭','bay.sampanhuang@gmail.com','127.0.0.1','Array');

/*Table structure for table `tb_config_update_history` */

DROP TABLE IF EXISTS `tb_config_update_history`;

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

/*Data for the table `tb_config_update_history` */

/*Table structure for table `tb_domain` */

DROP TABLE IF EXISTS `tb_domain`;

CREATE TABLE `tb_domain` (
  `domain_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '流水号',
  `domain_name` varchar(32) DEFAULT NULL COMMENT '域名',
  `domain_website_id` int(11) unsigned NOT NULL COMMENT '域名所属站点',
  `domain_click` int(11) unsigned NOT NULL COMMENT '域名点击量',
  `domain_insert_time` int(11) NOT NULL DEFAULT '0' COMMENT '域名创建时间',
  `domain_update_time` int(11) NOT NULL DEFAULT '0' COMMENT '域名修改时间',
  PRIMARY KEY (`domain_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC COMMENT='域名';

/*Data for the table `tb_domain` */

/*Table structure for table `tb_front_channel` */

DROP TABLE IF EXISTS `tb_front_channel`;

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

/*Data for the table `tb_front_channel` */

insert  into `tb_front_channel`(`channel_id`,`channel_fatherId`,`channel_link`,`channel_name_zh-cn`,`channel_name_zh-tw`,`channel_name_en-us`,`channel_enable`,`channel_order`,`channel_insert_time`,`channel_insert_man`,`channel_insert_ip`,`channel_insert_addr`,`channel_update_time`,`channel_update_man`,`channel_update_ip`,`channel_update_addr`) values (100,0,'/december/index','首页','首頁',NULL,3,10,1382622383,NULL,NULL,NULL,0,NULL,NULL,NULL),(200,0,'/december/new','最新收录','最新收錄',NULL,3,9,1382622383,NULL,NULL,NULL,0,NULL,NULL,NULL),(300,0,'/december/area','地区目录','地區目錄',NULL,3,8,1382622383,NULL,NULL,NULL,0,NULL,NULL,NULL),(400,0,'/december/category','分类目录','分類目錄',NULL,3,7,1382622383,NULL,NULL,NULL,0,NULL,NULL,NULL),(500,0,'/december/tag','热门标签','熱門標籤',NULL,3,6,1382622383,NULL,NULL,NULL,0,NULL,NULL,NULL),(600,0,'/december/hotkey','搜索热词','搜索熱詞',NULL,3,0,1382622383,NULL,NULL,NULL,0,NULL,NULL,NULL),(601,0,'/default/website','首页','首頁','Index',1,900,1382922383,NULL,NULL,NULL,0,NULL,NULL,NULL),(602,0,'/default/website/list/area_father/1000','亚洲','亞洲','Asia',1,800,1382922383,NULL,NULL,NULL,0,NULL,NULL,NULL),(603,0,'/default/website/list/area_father/2000','欧洲','歐洲','Europe',1,700,1382922383,NULL,NULL,NULL,0,NULL,NULL,NULL),(604,0,'/default/website/list/area_father/3000','北美洲','北美洲','North America',1,600,1382922383,NULL,NULL,NULL,0,NULL,NULL,NULL),(605,0,'/default/website/list/area_father/4000','拉丁美洲','拉丁美洲','Latin America',1,500,1382922383,NULL,NULL,NULL,0,NULL,NULL,NULL),(606,0,'/default/website/list/area_father/5000','大洋洲','大洋洲','Oceania',1,400,1382922383,NULL,NULL,NULL,0,NULL,NULL,NULL),(607,0,'/default/website/list/area_father/6000','非洲','非洲','Africa',1,300,1382922383,NULL,NULL,NULL,0,NULL,NULL,NULL);

/*Table structure for table `tb_front_channel_enable` */

DROP TABLE IF EXISTS `tb_front_channel_enable`;

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

/*Data for the table `tb_front_channel_enable` */

/*Table structure for table `tb_manager` */

DROP TABLE IF EXISTS `tb_manager`;

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

/*Data for the table `tb_manager` */

insert  into `tb_manager`(`manager_id`,`manager_roleId`,`manager_name`,`manager_email`,`manager_passwd`,`manager_passwd_change_count`,`manager_passwd_change_time`,`manager_passwd_change_ip`,`manager_login_count`,`manager_login_last_time`,`manager_login_last_ip`,`manager_lock_time`,`manager_active_email`,`manager_insert_time`,`manager_insert_manId`,`manager_insert_manName`,`manager_insert_manEmail`,`manager_insert_ip`,`manager_update_time`,`manager_update_manId`,`manager_update_manName`,`manager_update_manEmail`,`manager_update_ip`) values (1000,1,'审判长烧鸭','bay.sampanhuang@gmail.com','bb35a99fb3909dd82f24925fbbc93403',29,1385353039,'127.0.0.1',134,1388161420,'127.0.0.1',1383465443,0,1374930769,0,NULL,NULL,'127.0.0.01',0,NULL,NULL,NULL,NULL),(1001,5,'貌若潘安','m.sampanhuang@gmail.com','bb35a99fb3909dd82f24925fbbc93403',0,0,NULL,6,1385950639,'127.0.0.1',0,0,0,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL);

/*Table structure for table `tb_manager_log` */

DROP TABLE IF EXISTS `tb_manager_log`;

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

/*Data for the table `tb_manager_log` */

/*Table structure for table `tb_manager_log_remark` */

DROP TABLE IF EXISTS `tb_manager_log_remark`;

CREATE TABLE `tb_manager_log_remark` (
  `log_id` int(11) unsigned NOT NULL COMMENT '流水号',
  `log_user_agent` varchar(256) NOT NULL COMMENT '浏览器信息',
  `log_remark` text COMMENT '备注',
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `tb_manager_log_remark` */

/*Table structure for table `tb_manager_log_resetpwd` */

DROP TABLE IF EXISTS `tb_manager_log_resetpwd`;

CREATE TABLE `tb_manager_log_resetpwd` (
  `log_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '流水号',
  `log_time` int(10) DEFAULT NULL COMMENT '活动时间',
  `log_ip` varchar(64) DEFAULT NULL COMMENT '密码修改IP',
  `log_managerId` int(10) DEFAULT NULL COMMENT '用户ID',
  `log_managerName` varchar(64) DEFAULT NULL COMMENT '用户帐号',
  `log_managerEmail` varchar(64) DEFAULT NULL COMMENT '用户邮箱',
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC COMMENT='用户密码修改日志';

/*Data for the table `tb_manager_log_resetpwd` */

/*Table structure for table `tb_manager_menu` */

DROP TABLE IF EXISTS `tb_manager_menu`;

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
) ENGINE=InnoDB AUTO_INCREMENT=158 DEFAULT CHARSET=utf8;

/*Data for the table `tb_manager_menu` */

insert  into `tb_manager_menu`(`menu_id`,`menu_fatherId`,`menu_label_zh-cn`,`menu_label_zh-tw`,`menu_label_en-us`,`menu_link`,`menu_show`,`menu_order`,`menu_insert_time`,`menu_insert_manId`,`menu_insert_manName`,`menu_insert_manEmail`,`menu_insert_ip`,`menu_update_time`,`menu_update_manId`,`menu_update_manName`,`menu_update_manEmail`,`menu_update_ip`) values (1,0,'系统设置','系統设置','','',1,1500,1374154702,NULL,NULL,NULL,NULL,1386064612,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL),(2,122,'管理成员','管理成員','','',1,4,1374154702,NULL,NULL,NULL,NULL,1385465784,1000,NULL,NULL,NULL),(3,2,'查询管理员','查詢管理員',NULL,'system/manager/index',1,9,1374154702,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL),(4,2,'添加新成员','添加新成員',NULL,'system/manager/insert',1,8,1374154702,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL),(5,2,'编辑成员','編輯成員',NULL,'system/manager/update',0,7,1374154702,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL),(6,2,'删除成员','刪除成員',NULL,'system/manager/delete',0,6,1374154702,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL),(7,2,'明细详情','明細詳情',NULL,'system/manager/detail',0,5,1374154702,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL),(8,2,'管理员日志','管理員日誌',NULL,'system/manager/log',1,4,1374154702,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL),(9,122,'权限角色','角色','','',1,3,1374154702,NULL,NULL,NULL,NULL,1385465798,1000,NULL,NULL,NULL),(10,9,'查询角色','查詢角色',NULL,'system/manager-role/index',1,8,1374154702,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL),(11,9,'添加新角色','添加新角色',NULL,'system/manager-role/insert',1,7,1374154702,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL),(12,9,'编辑角色','編輯角色',NULL,'system/manager-role/update',0,6,1374154702,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL),(13,9,'删除角色','刪除角色',NULL,'system/manager-role/delete',0,5,1374154702,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL),(14,9,'明细详情','明細詳情',NULL,'system/manager-role/detail',0,2,1374154702,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL),(15,122,'后台菜单','後臺菜單','','',1,5,1374154702,NULL,NULL,NULL,NULL,1385465768,1000,NULL,NULL,NULL),(16,15,'菜单索引','菜單索引',NULL,'system/manager-menu/index',1,9,1374154702,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL),(17,15,'添加菜单','添加菜單',NULL,'system/manager-menu/insert',1,8,1374154702,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL),(18,15,'编辑菜单','編輯菜單','','system/manager-menu/update',0,7,1374154702,NULL,NULL,NULL,NULL,1385465854,1000,NULL,NULL,NULL),(19,15,'处理菜单（删除）','刪除菜單','','system/manager-menu/handle',0,6,1374154702,NULL,NULL,NULL,NULL,1385484458,1000,NULL,NULL,NULL),(20,15,'菜单明细','菜單明細',NULL,'system/manager-menu/detail',0,5,1374154702,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL),(22,0,'会员管理','會員管理',NULL,NULL,1,1600,1374154702,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL),(23,22,'注册会员','註冊會員','','',1,500,1374154702,NULL,NULL,NULL,NULL,1386042818,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL),(24,23,'查询会员','查詢會員','','member/default/index',1,4,1374154702,NULL,NULL,NULL,NULL,1386074753,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL),(25,23,'会员明细','會員明細','','member/default/detail',1,3,1374154702,NULL,NULL,NULL,NULL,1386074765,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL),(26,22,'会员动态','會員動態','','',1,400,1374154702,NULL,NULL,NULL,NULL,1386042811,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL),(27,26,'会员登录','會員登錄',NULL,'member/trend/login',1,4,1374154702,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL),(28,26,'会员注册','會員註冊',NULL,'member/trend/register',1,3,1374154702,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL),(33,0,'网站导航','網站导航','','',1,1700,1374154802,NULL,NULL,NULL,NULL,1386007790,1000,NULL,NULL,NULL),(35,33,'注册网站','註冊網站',NULL,NULL,1,9,1374154802,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL),(36,35,'查询网站','查詢網站','','website/default/index',1,9,1374154802,NULL,NULL,NULL,NULL,1387164535,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL),(37,35,'网站明细','網站明細','','website/default/detail',1,8,1374154702,NULL,NULL,NULL,NULL,1387164776,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL),(38,33,'网站动态','網站動態',NULL,NULL,1,8,1374154802,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL),(39,38,'网站注册','網站註冊',NULL,'website/trend/register',1,9,1374154802,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL),(40,38,'点击流入','點擊流入',NULL,'website/trend/click-in',1,8,1374154802,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL),(41,38,'点击流出','點擊流出',NULL,'website/trend/click-out',1,7,1374154802,NULL,NULL,NULL,NULL,1374155802,NULL,NULL,NULL,NULL),(42,1,'配置参数','配置参数',NULL,NULL,1,6,1374154802,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL),(43,42,'参数索引','參數索引',NULL,'system/config/index',1,9,1374154802,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL),(44,42,'添加参数','添加參數',NULL,'system/config/insert',1,8,1374154802,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL),(45,42,'修改参数','修改參數','','system/config/update',0,7,1374154802,NULL,NULL,NULL,NULL,1385486897,1000,NULL,NULL,NULL),(46,42,'参数处理（删除）','参数处理（删除）','','system/config/handle',0,6,1374154802,NULL,NULL,NULL,NULL,1385900635,1000,NULL,NULL,NULL),(47,42,'输入设置','输入设置','','system/config/alter',0,5,1374154802,NULL,NULL,NULL,NULL,1385900613,1000,NULL,NULL,NULL),(49,0,'个人中心','個人中心',NULL,NULL,1,1300,1374154802,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL),(50,49,'消息中心','消息中心',NULL,NULL,1,4,1374155802,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL),(51,50,'收件箱','收件箱',NULL,'profile/message/inbox',1,8,1374155802,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL),(52,50,'发件箱','發件箱',NULL,'profile/message/outbox',1,7,1374155802,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL),(53,50,'发消息','發消息',NULL,'profile/message/send',1,5,1374155802,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL),(54,50,'草稿箱','草稿箱',NULL,'profile/message/draft',1,6,1374155802,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL),(55,49,'用户安全','用戶安全',NULL,NULL,1,3,1374155802,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL),(56,55,'密码变更','密碼變更',NULL,'profile/safe/resetpwd',1,4,1374155802,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL),(57,55,'帐号变更','帳號變更',NULL,'profile/safe/update?type=name',1,3,1374155802,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL),(58,55,'邮箱变更','郵箱變更',NULL,'profile/safe/update?type=email',1,2,1374155802,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL),(60,50,'收件处理（删除、设为已读）','收件處理（刪除、設為已讀）',NULL,'profile/message/inbox-handle',0,4,1374155802,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL),(61,49,'个人资料','個人資料',NULL,NULL,1,2,1374155802,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL),(62,61,'资料明细','資料明細',NULL,'profile/intro/index',1,2,1374155802,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL),(63,61,'日志查询','日誌查詢',NULL,'profile/intro/log',1,1,1374155802,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL),(64,102,'网站提交','網站提交',NULL,NULL,1,5,1374154802,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL),(65,64,'尚未外理','尚未外理','','apply/website-register/index?register_pass=3',1,9,1374154802,NULL,NULL,NULL,NULL,1387096351,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL),(66,64,'通过审核','通過審核',NULL,'apply/website-register/index?register_pass=1',1,8,1374154802,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL),(67,64,'未通过审核','未通過審核',NULL,'apply/website-register/index?register_pass=2',1,7,1374154802,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL),(68,64,'申请明细','申請明細',NULL,'apply/website-register/detail',1,6,1374154802,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL),(69,64,'申请处理','申請處理','','apply/website-register/handle',0,5,1374154802,NULL,NULL,NULL,NULL,1387097362,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL),(71,38,'网站申请','網站申請',NULL,'website/trend/apply',1,6,0,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL),(72,33,'网站类型','網站類型',NULL,NULL,1,7,1374154802,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL),(73,72,'类型索引','類型索引',NULL,'website/category/index',1,1,1374154802,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL),(74,33,'网站区域','網站區域',NULL,NULL,1,6,0,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL),(75,74,'区域索引','區域索引',NULL,'website/area/index',1,3,0,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL),(76,74,'历史快照','歷史快照',NULL,'website/area/history',1,2,0,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL),(77,150,'会员菜单','會員菜單','','',1,100,1374154702,NULL,NULL,NULL,NULL,1386064556,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL),(78,77,'菜单索引','菜單索引','','member/member-menu/index',1,6,0,NULL,NULL,NULL,NULL,1386042657,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL),(79,77,'添加菜单','添加菜單','','member/member-menu/insert',1,5,0,NULL,NULL,NULL,NULL,1386042683,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL),(80,77,'编辑菜单','編輯菜單','','member/member-menu/update',0,4,0,NULL,NULL,NULL,NULL,1386042712,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL),(81,77,'删除菜单','刪除菜單','','member/member-menu/delete',0,3,0,NULL,NULL,NULL,NULL,1386042703,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL),(82,33,'网站标签','網站標籤',NULL,NULL,1,5,1374154802,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL),(83,33,'搜索关键字','搜索關鍵字',NULL,NULL,1,4,1374154802,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL),(84,82,'标签索引','標籤索引',NULL,'website/tag/index',1,3,0,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL),(85,82,'标签明细','標籤明細',NULL,'website/tag/detail',1,2,0,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL),(86,83,'关键字索引','關鍵字索引','','website/keywork/index',1,8,0,NULL,NULL,NULL,NULL,1387871934,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL),(88,38,'网站搜索','網站搜索',NULL,'website/trend/search',1,5,1374154802,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL),(89,35,'点击流入','點擊流入','','website/default/click-in',1,5,1374151802,NULL,NULL,NULL,NULL,1387164802,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL),(90,35,'点击流出','點擊流出','','website/default/click-out',1,4,1374154402,NULL,NULL,NULL,NULL,1387164809,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL),(91,83,'搜索动态','搜索動態','','website/keywork/trend',1,7,0,NULL,NULL,NULL,NULL,1388059337,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL),(92,35,'资料变更','資料變更','','website/default/update',1,7,1374153802,NULL,NULL,NULL,NULL,1387164785,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL),(93,35,'网站删除','網站刪除','','website/default/delete',1,6,1374154302,NULL,NULL,NULL,NULL,1387164793,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL),(94,1,'敏感词库','敏感詞庫',NULL,NULL,1,1,1374354802,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL),(96,94,'敏感词查询','敏感詞查询','','system/filter-word/index',1,6,1379507707,NULL,NULL,NULL,NULL,1385488007,1000,NULL,NULL,NULL),(97,94,'导入敏感词','导入敏感詞','','system/filter-word/import',1,7,1379507707,NULL,NULL,NULL,NULL,1385488036,1000,NULL,NULL,NULL),(102,0,'业务审批','业务審批','','',1,1800,0,NULL,NULL,NULL,NULL,1386007828,1000,NULL,NULL,NULL),(103,102,'网站更新','網站更新',NULL,NULL,1,4,1374154802,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL),(104,103,'未确认','未確認',NULL,NULL,1,1,0,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL),(106,102,'会员注册','會員註冊',NULL,NULL,1,3,1374154802,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL),(107,106,'未确认','未確認',NULL,NULL,1,1,0,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL),(108,9,'锁定角色','鎖定角色',NULL,'system/role/lock',0,4,1374154702,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL),(109,9,'解锁角色','解鎖角色',NULL,'system/role/unlock',0,3,1374154702,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL),(110,152,'公告管理','公告管理','','',1,200,0,NULL,NULL,NULL,NULL,1388127336,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL),(111,110,'公告查询','公告查询','','bulletin/index/index',1,8,0,NULL,NULL,NULL,NULL,1388133203,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL),(112,110,'添加公告','添加公告','','bulletin/index/insert',1,7,0,NULL,NULL,NULL,NULL,1388133213,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL),(113,110,'编辑公告','編輯公告','','bulletin/index/update',0,6,0,NULL,NULL,NULL,NULL,1388133222,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL),(114,110,'公告处理','公告处理','','bulletin/index/handle',0,5,0,NULL,NULL,NULL,NULL,1388133230,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL),(117,50,'发件处理（删除）','發件處理（刪除）',NULL,'profile/message/outbox-handle',0,0,0,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL),(118,1,'数据清理','數據清理',NULL,NULL,0,0,0,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL),(120,118,'过期日志','過期日誌',NULL,'system/data-clear/log',1,2,0,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL),(121,50,'草稿处理（删除）','草稿處理（刪除）',NULL,'profile/message/draft-handle',0,0,0,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL),(122,0,'后台权限','','','',1,1400,1385458695,1000,NULL,NULL,NULL,1385458695,1000,NULL,NULL,NULL),(144,94,'敏感词处理','敏感词处理','','system/filter-word/word-handle',0,1,1385491006,1000,NULL,NULL,NULL,1385491006,1000,NULL,NULL,NULL),(145,42,'参数明细','参数明细','','system/config/detail',1,1,1385645873,1000,NULL,NULL,NULL,1386008463,1000,NULL,NULL,NULL),(146,118,'配置变更日志','配置变更日志','配置变更日志','system/data-clear/config-update-history',1,2,1386007604,1000,NULL,NULL,NULL,1386007604,1000,NULL,NULL,NULL),(147,150,'权限角色','权限角色','权限角色','',1,200,1386008049,1000,NULL,NULL,NULL,1386063834,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL),(148,147,'查询角色','查询角色','查询角色','member/member-role/index',1,2,1386008124,1000,NULL,NULL,NULL,1386008124,1000,NULL,NULL,NULL),(149,147,'添加新角色','添加新角色','添加新角色','member/member-role/insert',1,1,1386008338,1000,NULL,NULL,NULL,1386008338,1000,NULL,NULL,NULL),(150,0,'会员权限','','','',1,1550,1386063772,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL,1386063810,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL),(151,23,'会员日志','会员日志','会员日志','member/default/log',1,1,1386141861,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL,1386141861,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL),(152,0,'公告发布','公告发布','','',1,1540,1388126515,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL,1388126515,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL),(153,152,'频道管理','','','',1,100,1388127121,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL,1388127344,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL),(154,153,'频道索引','频道索引','','bulletin/channel/index',1,8,1388127183,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL,1388127387,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL),(155,153,'频道管理','','','bulletin/channel/handle',0,7,1388127240,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL,1388127398,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL),(156,153,'添加频道','','','bulletin/channel/insert',1,6,1388127267,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL,1388127267,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL),(157,153,'更新频道','','','bulletin/channel/update',0,5,1388127292,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL,1388127292,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL);

/*Table structure for table `tb_manager_msg` */

DROP TABLE IF EXISTS `tb_manager_msg`;

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

/*Data for the table `tb_manager_msg` */

/*Table structure for table `tb_manager_msg_content` */

DROP TABLE IF EXISTS `tb_manager_msg_content`;

CREATE TABLE `tb_manager_msg_content` (
  `msg_id` int(11) unsigned NOT NULL COMMENT '消息流水号',
  `msg_content` varchar(512) NOT NULL COMMENT '消息内容',
  PRIMARY KEY (`msg_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `tb_manager_msg_content` */

/*Table structure for table `tb_manager_msg_dest` */

DROP TABLE IF EXISTS `tb_manager_msg_dest`;

CREATE TABLE `tb_manager_msg_dest` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `msg_id` int(11) unsigned NOT NULL DEFAULT '0',
  `msg_dest` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC COMMENT='收件人';

/*Data for the table `tb_manager_msg_dest` */

/*Table structure for table `tb_manager_msg_inbox` */

DROP TABLE IF EXISTS `tb_manager_msg_inbox`;

CREATE TABLE `tb_manager_msg_inbox` (
  `inbox_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `inbox_msgId` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '消息ID',
  `inbox_manId` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '收件人',
  `inbox_msg_accept_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '收件时间',
  `inbox_msg_read_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '读件时间',
  PRIMARY KEY (`inbox_id`),
  UNIQUE KEY `MsgId_ManagerId` (`inbox_msgId`,`inbox_manId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC COMMENT='收件箱';

/*Data for the table `tb_manager_msg_inbox` */

/*Table structure for table `tb_manager_msg_outbox` */

DROP TABLE IF EXISTS `tb_manager_msg_outbox`;

CREATE TABLE `tb_manager_msg_outbox` (
  `outbox_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `outbox_msgId` int(11) unsigned NOT NULL DEFAULT '0',
  `outbox_manId` int(11) unsigned NOT NULL DEFAULT '0',
  `outbox_msg_send_time` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`outbox_id`),
  UNIQUE KEY `MsgId_ManagerId` (`outbox_msgId`,`outbox_manId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `tb_manager_msg_outbox` */

/*Table structure for table `tb_manager_role` */

DROP TABLE IF EXISTS `tb_manager_role`;

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

/*Data for the table `tb_manager_role` */

insert  into `tb_manager_role`(`role_id`,`role_name`,`role_admin`,`role_lock_time`,`role_insert_time`,`role_insert_manId`,`role_insert_manName`,`role_insert_manEmail`,`role_insert_ip`,`role_update_time`,`role_update_manId`,`role_update_manName`,`role_update_manEmail`,`role_update_ip`) values (1,'Administrator',1,1383192050,1374154102,0,NULL,NULL,'127.0.0.1',1386051719,1000,'审判长烧鸭','bay.sampanhuang@gmail.com','127.0.0.1'),(2,'Developer',1,1383358396,1374164102,0,NULL,NULL,'127.0.0.1',0,NULL,NULL,NULL,NULL),(4,'Customer Service',0,1385233788,1374267102,0,NULL,NULL,'127.0.0.1',1383242773,NULL,NULL,'bay.sampanhuang@gmail.com','127.0.0.1'),(5,'Accountant',0,1383358396,1374967102,0,NULL,NULL,'127.0.0.1',1383242793,NULL,NULL,'bay.sampanhuang@gmail.com','127.0.0.1'),(6,'Tester',0,1373595196,1374967102,0,NULL,NULL,'127.0.0.1',1383242823,NULL,NULL,'bay.sampanhuang@gmail.com','127.0.0.1');

/*Table structure for table `tb_manager_role_menu` */

DROP TABLE IF EXISTS `tb_manager_role_menu`;

CREATE TABLE `tb_manager_role_menu` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(11) unsigned NOT NULL DEFAULT '0',
  `menu_id` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `RoleId_MenuId` (`role_id`,`menu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=742 DEFAULT CHARSET=utf8;

/*Data for the table `tb_manager_role_menu` */

insert  into `tb_manager_role_menu`(`id`,`role_id`,`menu_id`) values (717,1,3),(718,1,4),(719,1,5),(720,1,6),(721,1,7),(722,1,8),(723,1,10),(724,1,11),(725,1,12),(726,1,13),(729,1,14),(712,1,16),(713,1,17),(714,1,18),(715,1,19),(716,1,20),(682,1,24),(683,1,25),(684,1,27),(685,1,28),(663,1,36),(664,1,37),(669,1,39),(670,1,40),(671,1,41),(695,1,43),(696,1,44),(697,1,45),(698,1,46),(699,1,47),(730,1,51),(731,1,52),(733,1,53),(732,1,54),(737,1,56),(738,1,57),(739,1,58),(734,1,60),(740,1,62),(741,1,63),(656,1,65),(657,1,66),(658,1,67),(659,1,68),(660,1,69),(672,1,71),(674,1,73),(675,1,75),(676,1,76),(691,1,78),(692,1,79),(693,1,80),(694,1,81),(677,1,84),(678,1,85),(681,1,86),(680,1,87),(673,1,88),(667,1,89),(668,1,90),(679,1,91),(665,1,92),(666,1,93),(708,1,96),(707,1,97),(686,1,99),(687,1,100),(688,1,101),(661,1,104),(662,1,107),(727,1,108),(728,1,109),(701,1,111),(702,1,112),(703,1,113),(704,1,114),(705,1,115),(706,1,116),(736,1,117),(711,1,120),(735,1,121),(709,1,144),(700,1,145),(710,1,146),(689,1,148),(690,1,149),(260,4,3),(261,4,4),(262,4,5),(263,4,6),(264,4,7),(265,4,8),(266,4,10),(267,4,11),(268,4,12),(269,4,13),(272,4,14),(255,4,16),(256,4,17),(257,4,18),(258,4,19),(259,4,20),(238,4,24),(239,4,25),(240,4,27),(241,4,28),(219,4,36),(220,4,37),(225,4,39),(226,4,40),(227,4,41),(249,4,43),(250,4,44),(251,4,45),(252,4,46),(253,4,47),(254,4,48),(276,4,51),(277,4,52),(278,4,53),(279,4,54),(281,4,56),(282,4,57),(283,4,58),(280,4,60),(284,4,62),(285,4,63),(212,4,65),(213,4,66),(214,4,67),(215,4,68),(216,4,69),(228,4,71),(230,4,73),(231,4,75),(232,4,76),(245,4,78),(246,4,79),(247,4,80),(248,4,81),(233,4,84),(234,4,85),(237,4,86),(236,4,87),(229,4,88),(223,4,89),(224,4,90),(235,4,91),(221,4,92),(222,4,93),(275,4,95),(273,4,96),(274,4,97),(242,4,99),(243,4,100),(244,4,101),(217,4,104),(218,4,107),(270,4,108),(271,4,109),(312,5,24),(313,5,25),(314,5,27),(315,5,28),(293,5,36),(294,5,37),(299,5,39),(300,5,40),(301,5,41),(323,5,51),(324,5,52),(325,5,53),(326,5,54),(328,5,56),(329,5,57),(330,5,58),(327,5,60),(331,5,62),(332,5,63),(286,5,65),(287,5,66),(288,5,67),(289,5,68),(290,5,69),(302,5,71),(304,5,73),(305,5,75),(306,5,76),(319,5,78),(320,5,79),(321,5,80),(322,5,81),(307,5,84),(308,5,85),(311,5,86),(310,5,87),(303,5,88),(297,5,89),(298,5,90),(309,5,91),(295,5,92),(296,5,93),(316,5,99),(317,5,100),(318,5,101),(291,5,104),(292,5,107),(381,6,3),(382,6,4),(383,6,5),(384,6,6),(385,6,7),(386,6,8),(387,6,10),(388,6,11),(389,6,12),(390,6,13),(393,6,14),(376,6,16),(377,6,17),(378,6,18),(379,6,19),(380,6,20),(359,6,24),(360,6,25),(361,6,27),(362,6,28),(340,6,36),(341,6,37),(346,6,39),(347,6,40),(348,6,41),(370,6,43),(371,6,44),(372,6,45),(373,6,46),(374,6,47),(375,6,48),(397,6,51),(398,6,52),(399,6,53),(400,6,54),(402,6,56),(403,6,57),(404,6,58),(401,6,60),(405,6,62),(406,6,63),(333,6,65),(334,6,66),(335,6,67),(336,6,68),(337,6,69),(349,6,71),(351,6,73),(352,6,75),(353,6,76),(366,6,78),(367,6,79),(368,6,80),(369,6,81),(354,6,84),(355,6,85),(358,6,86),(357,6,87),(350,6,88),(344,6,89),(345,6,90),(356,6,91),(342,6,92),(343,6,93),(396,6,95),(394,6,96),(395,6,97),(363,6,99),(364,6,100),(365,6,101),(338,6,104),(339,6,107),(391,6,108),(392,6,109);

/*Table structure for table `tb_member` */

DROP TABLE IF EXISTS `tb_member`;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `tb_member` */

/*Table structure for table `tb_member_favorite` */

DROP TABLE IF EXISTS `tb_member_favorite`;

CREATE TABLE `tb_member_favorite` (
  `favorite_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `favorite_memberId` int(11) unsigned NOT NULL DEFAULT '0',
  `favorite_websiteId` int(11) unsigned NOT NULL DEFAULT '0',
  `favorite_insert_time` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`favorite_id`),
  UNIQUE KEY `MemberId_WebsiteId` (`favorite_memberId`,`favorite_websiteId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `tb_member_favorite` */

/*Table structure for table `tb_member_history_password` */

DROP TABLE IF EXISTS `tb_member_history_password`;

CREATE TABLE `tb_member_history_password` (
  `history_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '流水号',
  `history_time` int(11) NOT NULL DEFAULT '0' COMMENT '时间',
  `history_memberId` int(11) NOT NULL DEFAULT '0' COMMENT '会员ID',
  `history_memberPassword` varchar(32) NOT NULL COMMENT '旧密码',
  PRIMARY KEY (`history_id`),
  KEY `MemberId` (`history_memberId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `tb_member_history_password` */

/*Table structure for table `tb_member_log` */

DROP TABLE IF EXISTS `tb_member_log`;

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

/*Data for the table `tb_member_log` */

/*Table structure for table `tb_member_log_remark` */

DROP TABLE IF EXISTS `tb_member_log_remark`;

CREATE TABLE `tb_member_log_remark` (
  `log_id` int(11) unsigned NOT NULL COMMENT '流水号',
  `log_user_agent` varchar(256) NOT NULL COMMENT '浏览器信息',
  `log_remark` text COMMENT '备注',
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `tb_member_log_remark` */

/*Table structure for table `tb_member_menu` */

DROP TABLE IF EXISTS `tb_member_menu`;

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
) ENGINE=InnoDB AUTO_INCREMENT=172 DEFAULT CHARSET=utf8;

/*Data for the table `tb_member_menu` */

insert  into `tb_member_menu`(`menu_id`,`menu_fatherId`,`menu_label_zh-cn`,`menu_label_zh-tw`,`menu_label_en-us`,`menu_link`,`menu_show`,`menu_order`,`menu_insert_time`,`menu_insert_manId`,`menu_insert_manName`,`menu_insert_manEmail`,`menu_insert_ip`,`menu_update_time`,`menu_update_manId`,`menu_update_manName`,`menu_update_manEmail`,`menu_update_ip`) values (150,0,'个人中心','个人中心','个人中心','',1,1000,1386060549,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL,1386063000,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL),(151,150,'个人资料','个人资料','','',1,100,1386060566,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL,1386062806,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL),(152,151,'资料明细','','','profile/intro/index',1,10,1386060593,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL,1386063287,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL),(153,151,'日志查询','','','profile/intro/log',1,20,1386060612,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL,1386063279,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL),(154,150,'用户安全','','','',1,200,1386060672,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL,1386060672,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL),(155,154,'密码变更','','','profile/safe/resetpwd',1,30,1386063199,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL,1386063199,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL),(156,154,'帐号变更','','','profile/safe/update?type=name',1,20,1386063238,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL,1386063238,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL),(157,154,'邮箱变更','','','profile/safe/update?type=email',1,10,1386063254,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL,1386063254,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL),(158,150,'消息中心','','','',1,300,1386063333,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL,1386063333,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL),(159,158,'收件箱','','','profile/message/inbox',1,80,1386063355,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL,1386063355,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL),(160,158,'发件箱','','','profile/message/outbox',1,70,1386063374,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL,1386063374,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL),(161,158,'草稿箱','','','profile/message/draft',1,60,1386063394,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL,1386063394,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL),(162,158,'发消息','','','profile/message/send',1,50,1386063415,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL,1386063415,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL),(163,158,'收件处理（删除、设为已读）','','','profile/message/inbox-handle',0,40,1386063440,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL,1386063513,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL),(164,158,'发件处理（删除）','','','profile/message/outbox-handle',0,11,1386063472,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL,1386063472,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL),(165,158,'草稿处理（删除）','','','profile/message/draft-handle',0,2,1386063490,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL,1386063490,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL),(166,0,'网站导航','网站导航','','',1,700,1387624236,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL,1387624236,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL),(167,166,'注册网站','','','',1,50,1387624260,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL,1387624425,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL),(168,167,'网站查询','','','website/default/index',1,0,1387624343,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL,1387624395,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL),(169,168,'网站详情','','','website/default/detail',1,0,1387624356,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL,1387624377,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL),(170,167,'网站明细','','','webiste/default/detail',1,0,1387630102,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL,1387630102,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL),(171,167,'网站处理','','','website/default/handle',0,0,1387630157,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL,1387630157,1000,'审判长烧鸭','bay.sampanhuang@gmail.com',NULL);

/*Table structure for table `tb_member_msg` */

DROP TABLE IF EXISTS `tb_member_msg`;

CREATE TABLE `tb_member_msg` (
  `msg_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `msg_source` int(11) NOT NULL DEFAULT '0' COMMENT '发送人',
  `msg_dest` int(11) NOT NULL DEFAULT '0' COMMENT '接收人',
  `msg_subject` varchar(64) NOT NULL COMMENT '标题',
  `msg_content` varchar(512) DEFAULT NULL COMMENT '内容',
  `msg_insert_time` int(11) NOT NULL DEFAULT '0' COMMENT '保存时间',
  `msg_send_time` int(11) NOT NULL DEFAULT '0' COMMENT '发送时间',
  `msg_read_time` int(11) NOT NULL DEFAULT '0' COMMENT '阅读时间',
  PRIMARY KEY (`msg_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `tb_member_msg` */

/*Table structure for table `tb_member_role` */

DROP TABLE IF EXISTS `tb_member_role`;

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

/*Data for the table `tb_member_role` */

insert  into `tb_member_role`(`role_id`,`role_name`,`role_admin`,`role_lock_time`,`role_insert_time`,`role_insert_manId`,`role_insert_manName`,`role_insert_manEmail`,`role_insert_ip`,`role_update_time`,`role_update_manId`,`role_update_manName`,`role_update_manEmail`,`role_update_ip`) values (7,'Alpha',1,1386064676,1386064888,1000,'审判长烧鸭','bay.sampanhuang@gmail.com','127.0.0.1',1386065368,1000,'审判长烧鸭','bay.sampanhuang@gmail.com','127.0.0.1'),(8,'Beta',1,1386065011,1386065024,1000,'审判长烧鸭','bay.sampanhuang@gmail.com','127.0.0.1',1386065024,1000,'审判长烧鸭','bay.sampanhuang@gmail.com','127.0.0.1'),(9,'Release ',1,1386065488,1386065496,1000,'审判长烧鸭','bay.sampanhuang@gmail.com','127.0.0.1',1386065496,1000,'审判长烧鸭','bay.sampanhuang@gmail.com','127.0.0.1'),(10,'Stable',0,1386065496,1386065535,1000,'审判长烧鸭','bay.sampanhuang@gmail.com','127.0.0.1',1386065535,1000,'审判长烧鸭','bay.sampanhuang@gmail.com','127.0.0.1'),(11,'VIP_1',0,1386324785,1386065597,1000,'审判长烧鸭','bay.sampanhuang@gmail.com','127.0.0.1',1386065853,1000,'审判长烧鸭','bay.sampanhuang@gmail.com','127.0.0.1');

/*Table structure for table `tb_member_role_menu` */

DROP TABLE IF EXISTS `tb_member_role_menu`;

CREATE TABLE `tb_member_role_menu` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(11) unsigned NOT NULL DEFAULT '0',
  `menu_id` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `RoleId_MenuId` (`role_id`,`menu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8;

/*Data for the table `tb_member_role_menu` */

insert  into `tb_member_role_menu`(`id`,`role_id`,`menu_id`) values (24,7,152),(23,7,153),(20,7,155),(21,7,156),(22,7,157),(13,7,159),(14,7,160),(15,7,161),(16,7,162),(17,7,163),(18,7,164),(19,7,165),(12,8,152),(11,8,153),(8,8,155),(9,8,156),(10,8,157),(1,8,159),(2,8,160),(3,8,161),(4,8,162),(5,8,163),(6,8,164),(7,8,165),(36,9,152),(35,9,153),(32,9,155),(33,9,156),(34,9,157),(25,9,159),(26,9,160),(27,9,161),(28,9,162),(29,9,163),(30,9,164),(31,9,165),(48,10,152),(47,10,153),(44,10,155),(45,10,156),(46,10,157),(37,10,159),(38,10,160),(39,10,161),(40,10,162),(41,10,163),(42,10,164),(43,10,165),(72,11,152),(71,11,153),(68,11,155),(69,11,156),(70,11,157),(61,11,159),(62,11,160),(63,11,161),(64,11,162),(65,11,163),(66,11,164),(67,11,165);

/*Table structure for table `tb_member_trend_login` */

DROP TABLE IF EXISTS `tb_member_trend_login`;

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

/*Data for the table `tb_member_trend_login` */

/*Table structure for table `tb_member_trend_register` */

DROP TABLE IF EXISTS `tb_member_trend_register`;

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

/*Data for the table `tb_member_trend_register` */

/*Table structure for table `tb_safe_filter_word` */

DROP TABLE IF EXISTS `tb_safe_filter_word`;

CREATE TABLE `tb_safe_filter_word` (
  `word_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '流水号',
  `word_val` varchar(32) NOT NULL COMMENT '词库',
  `word_count` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '这个词库的替换次数',
  `word_insert_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '插入时间',
  `word_update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`word_id`),
  UNIQUE KEY `Val` (`word_val`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC COMMENT='敏感词库';

/*Data for the table `tb_safe_filter_word` */

insert  into `tb_safe_filter_word`(`word_id`,`word_val`,`word_count`,`word_insert_time`,`word_update_time`) values (3,'法轮功',2,1379269654,1389269654),(4,'法轮功大法',3,1379269654,1389269654),(5,'李洪志',4,1379269654,1389269654),(6,'鸡巴毛',5,1379269654,1389269654),(7,'鸡巴',8,1379269654,1389269654),(8,'屌毛',9,1379269654,1389269654),(9,'草榴',7,1379269654,1389269654),(10,'操你妈',11,1379269654,1399269654),(14,'西藏独立',0,1381318467,0),(16,'2',0,1385491426,0),(17,'3',0,1385491426,0),(18,'4',0,1385491426,0);

/*Table structure for table `tb_state` */

DROP TABLE IF EXISTS `tb_state`;

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

/*Data for the table `tb_state` */

insert  into `tb_state`(`state_id`,`state_fatherId`,`state_key`,`state_label`,`state_value`,`state_remark`,`state_order`,`state_insert_time`,`state_update_time`) values (36,0,'safe_filter_word','敏感词过滤','1',NULL,0,0,0),(37,36,'safe_filter_word_import_count','敏感词过滤次数','3','敏感词过滤次数',0,0,1385491426),(38,0,'data-clear','数据清理','0',NULL,0,0,0),(41,38,'data-clear_log-count','过期日志清理次数','0',NULL,0,0,0),(42,38,'data-clear_log-lasttime','过期日志清理最后时间','0',NULL,0,0,0);

/*Table structure for table `tb_website` */

DROP TABLE IF EXISTS `tb_website`;

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
  `website_update_stat` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改状态1无修改申请2已修改申请3拒绝此次修改',
  `website_update_count` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改次数',
  `website_delete_stat` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '删除状态0无删除申请1已提交申请',
  `website_applyId` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '申请ID',
  `website_apply_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '申请时间',
  `website_apply_ip` varchar(32) NOT NULL COMMENT '申请IP',
  PRIMARY KEY (`website_id`),
  UNIQUE KEY `Name` (`website_name`),
  UNIQUE KEY `Domain` (`website_domain`),
  KEY `CetgoryId_Continent_Country` (`website_categoryId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `tb_website` */

/*Table structure for table `tb_website_apply_delete` */

DROP TABLE IF EXISTS `tb_website_apply_delete`;

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

/*Data for the table `tb_website_apply_delete` */

/*Table structure for table `tb_website_apply_register` */

DROP TABLE IF EXISTS `tb_website_apply_register`;

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
  UNIQUE KEY `WebsiteId` (`website_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC COMMENT='网站注册申请';

/*Data for the table `tb_website_apply_register` */

/*Table structure for table `tb_website_apply_register_intro` */

DROP TABLE IF EXISTS `tb_website_apply_register_intro`;

CREATE TABLE `tb_website_apply_register_intro` (
  `register_id` int(11) unsigned NOT NULL,
  `website_intro` text NOT NULL,
  PRIMARY KEY (`register_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC COMMENT='网站注册申请-简介';

/*Data for the table `tb_website_apply_register_intro` */

/*Table structure for table `tb_website_apply_update` */

DROP TABLE IF EXISTS `tb_website_apply_update`;

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

/*Data for the table `tb_website_apply_update` */

/*Table structure for table `tb_website_apply_update_intro` */

DROP TABLE IF EXISTS `tb_website_apply_update_intro`;

CREATE TABLE `tb_website_apply_update_intro` (
  `update_id` int(11) unsigned NOT NULL,
  `website_intro` text,
  PRIMARY KEY (`update_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `tb_website_apply_update_intro` */

/*Table structure for table `tb_website_area` */

DROP TABLE IF EXISTS `tb_website_area`;

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

/*Data for the table `tb_website_area` */

insert  into `tb_website_area`(`area_id`,`area_fatherId`,`area_name_zh-cn`,`area_name_zh-tw`,`area_name_en-us`,`area_count_website`,`area_count_click_in`,`area_count_click_out`,`area_order`,`area_insert_time`,`area_insert_man`,`area_insert_ip`,`area_update_time`,`area_update_man`,`area_update_ip`) values (1000,0,'亚洲','亞洲','Asia',0,0,0,10,1382196024,NULL,NULL,0,NULL,NULL),(1110,1000,'中国','中國','China',0,0,0,790,1382546968,NULL,NULL,0,NULL,NULL),(1120,1000,'台湾','臺灣','Taiwan',0,0,0,780,1382546968,NULL,NULL,0,NULL,NULL),(1130,1000,'香港','香港','Hong Kong',0,0,0,770,1382546968,NULL,NULL,0,NULL,NULL),(1140,1000,'澳门','澳門','Macau',0,0,0,760,1382546968,NULL,NULL,0,NULL,NULL),(1150,1000,'日本','日本','Japan',0,0,0,750,1382546968,NULL,NULL,0,NULL,NULL),(1160,1000,'北韩','北韓','Korea Republic Of',0,0,0,740,1382546968,NULL,NULL,0,NULL,NULL),(1170,1000,'新加坡','新加坡','Singapore',0,0,0,730,1382546968,NULL,NULL,0,NULL,NULL),(1180,1000,'马来群岛','馬來群島','Malaysia',0,0,0,720,1382546968,NULL,NULL,0,NULL,NULL),(1190,1000,'菲律宾群岛','菲律賓群島','Philippines',0,0,0,710,1382546968,NULL,NULL,0,NULL,NULL),(1200,1000,'越南','越南','Viet Nam',0,0,0,700,1382546968,NULL,NULL,0,NULL,NULL),(1210,1000,'泰国','泰國','Thailand',0,0,0,690,1382546968,NULL,NULL,0,NULL,NULL),(1220,1000,'老挝','寮國',NULL,0,0,0,680,1382546968,NULL,NULL,0,NULL,NULL),(1230,1000,'高棉(柬埔寨)','高棉(柬埔寨)',NULL,0,0,0,670,1382546968,NULL,NULL,0,NULL,NULL),(1240,1000,'土耳其','土耳其',NULL,0,0,0,660,1382546968,NULL,NULL,0,NULL,NULL),(1250,1000,'印尼','印尼',NULL,0,0,0,650,1382546968,NULL,NULL,0,NULL,NULL),(1260,1000,'文莱(东亚)','汶萊(東亞)',NULL,0,0,0,640,1382546968,NULL,NULL,0,NULL,NULL),(1270,1000,'南韩','南韓',NULL,0,0,0,630,1382546968,NULL,NULL,0,NULL,NULL),(1280,1000,'蒙古','蒙古','Mongolia',0,0,0,620,1382546968,NULL,NULL,0,NULL,NULL),(1290,1000,'印度','印度','India',0,0,0,610,1382546968,NULL,NULL,0,NULL,NULL),(1300,1000,'巴基斯坦','巴基斯坦','Pakistan',0,0,0,600,1382546968,NULL,NULL,0,NULL,NULL),(1310,1000,'尼泊尔','尼泊爾','Nepal',0,0,0,590,1382546968,NULL,NULL,0,NULL,NULL),(1320,1000,'苏丹','蘇丹','Sudan',0,0,0,580,1382546968,NULL,NULL,0,NULL,NULL),(1330,1000,'不丹(印度北部)','不丹(印度北部)','Bhutan',0,0,0,570,1382546968,NULL,NULL,0,NULL,NULL),(1340,1000,'阿联酋','阿聯酋','United Arab Emirates',0,0,0,560,1382546968,NULL,NULL,0,NULL,NULL),(1350,1000,'亚美尼亚(西南亚)','亞美尼亞(西南亞)','Armenia',0,0,0,550,1382546968,NULL,NULL,0,NULL,NULL),(1360,1000,'亚塞拜然共和国(西南亚)','亞塞拜然共和國(西南亞)','Azerbaijan',0,0,0,540,1382546968,NULL,NULL,0,NULL,NULL),(1370,1000,'孟加拉','孟加拉','Bangladesh',0,0,0,530,1382546968,NULL,NULL,0,NULL,NULL),(1380,1000,'巴林','巴林','Bahrain',0,0,0,520,1382546968,NULL,NULL,0,NULL,NULL),(1390,1000,'塞浦路斯(土耳其西南方)','賽普勒斯(土耳其西南方)','Cyprus',0,0,0,510,1382546968,NULL,NULL,0,NULL,NULL),(1400,1000,'也门','葉門','Yemen',0,0,0,500,1382546968,NULL,NULL,0,NULL,NULL),(1410,1000,'叙利亚','敘利亞','Syrian Arab Republic',0,0,0,490,1382546968,NULL,NULL,0,NULL,NULL),(1420,1000,'乌兹别克斯坦','烏茲別克斯坦','Uzbekistan',0,0,0,480,1382546968,NULL,NULL,0,NULL,NULL),(1430,1000,'土库曼(中亚)','土庫曼(中亞)','Turkmenistan',0,0,0,470,1382546968,NULL,NULL,0,NULL,NULL),(1440,1000,'以色列','以色列','Israel',0,0,0,460,1382546968,NULL,NULL,0,NULL,NULL),(1450,1000,'伊拉克','伊拉克','Iraq',0,0,0,450,1382546968,NULL,NULL,0,NULL,NULL),(1460,1000,'伊朗','伊朗','Iran (Islamic Republic Of)',0,0,0,440,1382546968,NULL,NULL,0,NULL,NULL),(1470,1000,'约旦','約旦','Jordan',0,0,0,430,1382546968,NULL,NULL,0,NULL,NULL),(1480,1000,'黎巴嫩','黎巴嫩','Lebanon',0,0,0,420,1382546968,NULL,NULL,0,NULL,NULL),(1490,1000,'哈萨克斯坦','哈薩克','Kazakhstan',0,0,0,410,1382546968,NULL,NULL,0,NULL,NULL),(1500,1000,'斯里兰卡','斯里蘭卡','Sri Lanka',0,0,0,400,1382546968,NULL,NULL,0,NULL,NULL),(1510,1000,'沙特阿拉伯','沙烏地阿拉伯','Saudi Arabia',0,0,0,390,1382546968,NULL,NULL,0,NULL,NULL),(1520,1000,'阿曼','阿曼','Oman',0,0,0,380,1382546968,NULL,NULL,0,NULL,NULL),(1530,1000,'格鲁吉亚','格魯吉亞','Georgia',0,0,0,370,1382546968,NULL,NULL,0,NULL,NULL),(1540,1000,'英属印度洋领域','英屬印度洋領域','British Indian Ocean Territory',0,0,0,360,1382546968,NULL,NULL,0,NULL,NULL),(1550,1000,'葛摩伊斯兰联邦共和国(印度洋西部)','葛摩伊斯蘭聯邦共和國(印度洋西部)','Comoros',0,0,0,350,1382546968,NULL,NULL,0,NULL,NULL),(1560,1000,'科威特','科威特','Kuwait',0,0,0,340,1382546968,NULL,NULL,0,NULL,NULL),(2000,0,'欧洲','歐洲','Europe',0,0,0,9,1382196044,NULL,NULL,0,NULL,NULL),(2110,2000,'英国（联合王国）','英國','United Kingdom',0,0,0,790,1382546968,NULL,NULL,0,NULL,NULL),(2120,2000,'英国（大不列颠）','英國','United Kingdom',0,0,0,780,1382546968,NULL,NULL,0,NULL,NULL),(2130,2000,'爱尔兰','愛爾蘭','Ireland',0,0,0,770,1382546968,NULL,NULL,0,NULL,NULL),(2140,2000,'法国','法國','France',0,0,0,760,1382546968,NULL,NULL,0,NULL,NULL),(2150,2000,'法国，大都市的','法國，大都市的','France, Metropolitan',0,0,0,750,1382546968,NULL,NULL,0,NULL,NULL),(2160,2000,'法国的玻里尼西亚','法國的玻里尼西亞','French Polynesia',0,0,0,740,1382546968,NULL,NULL,0,NULL,NULL),(2170,2000,'*法国的南方的领域','*法國的南方的領域','French Southern Territories',0,0,0,730,1382546968,NULL,NULL,0,NULL,NULL),(2180,2000,'德国','德國','Germany',0,0,0,720,1382546968,NULL,NULL,0,NULL,NULL),(2190,2000,'荷兰','荷蘭','Netherlands',0,0,0,710,1382546968,NULL,NULL,0,NULL,NULL),(2200,2000,'挪威','挪威','Norway',0,0,0,700,1382546968,NULL,NULL,0,NULL,NULL),(2210,2000,'瑞典','瑞典','Sweden',0,0,0,690,1382546968,NULL,NULL,0,NULL,NULL),(2220,2000,'瑞士(中欧)','瑞士(中歐)','Switzerland',0,0,0,680,1382546968,NULL,NULL,0,NULL,NULL),(2230,2000,'丹麦(西北欧)','丹麥(西北歐)','Denmark',0,0,0,670,1382546968,NULL,NULL,0,NULL,NULL),(2240,2000,'芬兰(东北欧)','芬蘭(東北歐)','Finland',0,0,0,660,1382546968,NULL,NULL,0,NULL,NULL),(2250,2000,'俄罗斯','俄羅斯','Russian Federation',0,0,0,650,1382546968,NULL,NULL,0,NULL,NULL),(2260,2000,'意大利','義大利','Italy',0,0,0,640,1382546968,NULL,NULL,0,NULL,NULL),(2270,2000,'西班牙','西班牙','Spain',0,0,0,630,1382546968,NULL,NULL,0,NULL,NULL),(2280,2000,'安道尔','安道爾','Andorra',0,0,0,620,1382546968,NULL,NULL,0,NULL,NULL),(2290,2000,'阿尔巴尼亚','阿爾巴尼亞','Albania',0,0,0,610,1382546968,NULL,NULL,0,NULL,NULL),(2300,2000,'葡萄牙','葡萄牙','Portugal',0,0,0,600,1382546968,NULL,NULL,0,NULL,NULL),(2310,2000,'希腊','希臘','Greece',0,0,0,590,1382546968,NULL,NULL,0,NULL,NULL),(2320,2000,'梵蒂冈','梵蒂岡','Vatican City State (Holy See)',0,0,0,580,1382546968,NULL,NULL,0,NULL,NULL),(2330,2000,'直布罗陀海峡','直布羅陀海峽','Gibraltar',0,0,0,570,1382546968,NULL,NULL,0,NULL,NULL),(2340,2000,'奥地利(中欧)','奧地利(中歐)','Austria',0,0,0,560,1382546968,NULL,NULL,0,NULL,NULL),(2350,2000,'波斯尼亚','波斯尼亞','Bosnia And Herzegowina',0,0,0,550,1382546968,NULL,NULL,0,NULL,NULL),(2360,2000,'比利时','比利時','Belgium',0,0,0,540,1382546968,NULL,NULL,0,NULL,NULL),(2370,2000,'保加利亚(东欧)','保加利亞(東歐)','Bulgaria',0,0,0,530,1382546968,NULL,NULL,0,NULL,NULL),(2380,2000,'捷克(中欧)','捷克(中歐)','Czech Republic',0,0,0,520,1382546968,NULL,NULL,0,NULL,NULL),(2390,2000,'南斯拉夫','南斯拉夫','Yugoslavia',0,0,0,510,1382546968,NULL,NULL,0,NULL,NULL),(2400,2000,'爱沙尼亚(波罗的海)','愛沙尼亞(波羅的海)','Estonia',0,0,0,500,1382546968,NULL,NULL,0,NULL,NULL),(2410,2000,'司瓦尔巴特群岛及扬马延岛','冷岸和央麥恩島','Svalbard And Jan Mayen Islands',0,0,0,490,1382546968,NULL,NULL,0,NULL,NULL),(2420,2000,'乌克兰','烏克蘭','Ukraine',0,0,0,480,1382546968,NULL,NULL,0,NULL,NULL),(2430,2000,'波兰','波蘭','Poland',0,0,0,470,1382546968,NULL,NULL,0,NULL,NULL),(2440,2000,'匈牙利','匈牙利','Hungary',0,0,0,460,1382546968,NULL,NULL,0,NULL,NULL),(2450,2000,'冰岛','冰島','Iceland',0,0,0,450,1382546968,NULL,NULL,0,NULL,NULL),(2460,2000,'马其顿','馬其頓','M acedonia The Former Yugoslav Rep Of',0,0,0,440,1382546968,NULL,NULL,0,NULL,NULL),(2470,2000,'立陶宛','立陶宛','Lithuania',0,0,0,430,1382546968,NULL,NULL,0,NULL,NULL),(2480,2000,'格陵兰(北大西洋)','格陵蘭(北大西洋)','Greenland',0,0,0,420,1382546968,NULL,NULL,0,NULL,NULL),(2490,2000,'卢森堡','盧森堡','Luxembourg',0,0,0,410,1382546968,NULL,NULL,0,NULL,NULL),(2500,2000,'摩纳哥','摩納哥','Monaco',0,0,0,400,1382546968,NULL,NULL,0,NULL,NULL),(2510,2000,'罗马尼亚','羅馬尼亞','Romania',0,0,0,390,1382546968,NULL,NULL,0,NULL,NULL),(2520,2000,'圣马利诺','聖馬利諾','San Marino',0,0,0,380,1382546968,NULL,NULL,0,NULL,NULL),(2530,2000,'斯洛伐克','斯洛法克人共和國','Slovakia (Slovak Republic)',0,0,0,370,1382546968,NULL,NULL,0,NULL,NULL),(2540,2000,'斯洛法尼亚','斯洛法尼亞','Slovenia',0,0,0,360,1382546968,NULL,NULL,0,NULL,NULL),(2550,2000,'白俄罗斯','柏勞斯','Belarus',0,0,0,350,1382546968,NULL,NULL,0,NULL,NULL),(2560,2000,'克罗埃西亚','克羅埃西亞','CROATIA (Local Name: Hrvatska)',0,0,0,340,1382546968,NULL,NULL,0,NULL,NULL),(2570,2000,'列支敦斯登','列支敦斯登','Liechtenstein',0,0,0,330,1382546968,NULL,NULL,0,NULL,NULL),(2580,2000,'拉脱维亚','拉脫維亞','Latvia',0,0,0,320,1382546968,NULL,NULL,0,NULL,NULL),(2590,2000,'马耳他','馬爾他','Malta',0,0,0,310,1382546968,NULL,NULL,0,NULL,NULL),(3000,0,'北美洲','北美洲','North America',0,0,0,8,1382196064,NULL,NULL,0,NULL,NULL),(3110,3000,'美国','美國','United States',0,0,0,790,1382546968,NULL,NULL,0,NULL,NULL),(3120,3000,'安提瓜及巴尔布达(加勒比海)','安提瓜及巴爾布達(加勒比海)','Antigua And Barbuda',0,0,0,780,1382546968,NULL,NULL,0,NULL,NULL),(3130,3000,'安圭拉岛','安圭拉島','Anguilla',0,0,0,770,1382546968,NULL,NULL,0,NULL,NULL),(3140,3000,'安的列斯（荷）','安的列斯（荷）','Netherlands Antilles',0,0,0,760,1382546968,NULL,NULL,0,NULL,NULL),(3150,3000,'阿鲁巴岛','亞魯伯','Aruba',0,0,0,750,1382546968,NULL,NULL,0,NULL,NULL),(3160,3000,'巴贝多(加勒比海)','巴貝多(加勒比海)','Barbados',0,0,0,740,1382546968,NULL,NULL,0,NULL,NULL),(3170,3000,'加拿大','加拿大','Canada',0,0,0,730,1382546968,NULL,NULL,0,NULL,NULL),(3180,3000,'哥斯达黎加(中美洲)','哥斯大黎加(中美洲)','Costa Rica',0,0,0,720,1382546968,NULL,NULL,0,NULL,NULL),(3190,3000,'巴拿马','巴拿馬','Panama',0,0,0,710,1382546968,NULL,NULL,0,NULL,NULL),(3200,3000,'英属维尔京群岛','英屬維爾京群島','Virgin Islands (British)',0,0,0,700,1382546968,NULL,NULL,0,NULL,NULL),(3210,3000,'美属维尔京群岛','美屬維爾京群島','Virgin Islands (U.S.)',0,0,0,690,1382546968,NULL,NULL,0,NULL,NULL),(3220,3000,'海地','海地','Haiti',0,0,0,680,1382546968,NULL,NULL,0,NULL,NULL),(3230,3000,'危地马拉','瓜地馬拉','Guatemala',0,0,0,670,1382546968,NULL,NULL,0,NULL,NULL),(3240,3000,'牙买加','牙買加','Jamaica',0,0,0,660,1382546968,NULL,NULL,0,NULL,NULL),(3250,3000,'几内亚比','幾內亞比索','Guinea-Bissau',0,0,0,650,1382546968,NULL,NULL,0,NULL,NULL),(3260,3000,'巴哈马群岛','巴哈馬群島','Bahamas',0,0,0,640,1382546968,NULL,NULL,0,NULL,NULL),(3270,3000,'土克斯和开卡斯群岛','土克斯和開卡斯群島','Turks And Caicos Islands',0,0,0,630,1382546968,NULL,NULL,0,NULL,NULL),(3280,3000,'伯利兹(加勒比海)','貝里斯(加勒比海)','Belize',0,0,0,620,1382546968,NULL,NULL,0,NULL,NULL),(3290,3000,'多米尼克(加勒比海)','多明尼克島(加勒比海)','Dominica',0,0,0,610,1382546968,NULL,NULL,0,NULL,NULL),(3300,3000,'尼加拉瓜','尼加拉瓜','Nicaragua',0,0,0,600,1382546968,NULL,NULL,0,NULL,NULL),(3310,3000,'瓜德罗普（法属）','瓜達康納爾島','Guadeloupe',0,0,0,590,1382546968,NULL,NULL,0,NULL,NULL),(3320,3000,'盖亚那','蓋亞那','Guyana',0,0,0,580,1382546968,NULL,NULL,0,NULL,NULL),(3330,3000,'洪都拉斯','宏都拉斯','Honduras',0,0,0,570,1382546968,NULL,NULL,0,NULL,NULL),(3340,3000,'开曼群岛','開曼群島','Cayman Islands',0,0,0,560,1382546968,NULL,NULL,0,NULL,NULL),(3350,3000,'圣路其亚','聖路其亞','Saint Lucia',0,0,0,550,1382546968,NULL,NULL,0,NULL,NULL),(3360,3000,'马提尼克','聖馬丁節','Martinique',0,0,0,540,1382546968,NULL,NULL,0,NULL,NULL),(3370,3000,'蒙塞拉特岛','蒙特色納島','Montserrat',0,0,0,530,1382546968,NULL,NULL,0,NULL,NULL),(4000,0,'拉丁美洲','拉丁美洲','South America',0,0,0,7,1382196084,NULL,NULL,0,NULL,NULL),(4110,4000,'阿根廷','阿根廷','Argentina',0,0,0,790,1382546968,NULL,NULL,0,NULL,NULL),(4120,4000,'玻利维亚(南美洲)','玻利維亞(南美洲)','Bolivia',0,0,0,780,1382546968,NULL,NULL,0,NULL,NULL),(4130,4000,'巴西(南美)','巴西(南美)','Brazil',0,0,0,770,1382546968,NULL,NULL,0,NULL,NULL),(4140,4000,'哥伦比亚','哥倫比亞','Colombia',0,0,0,760,1382546968,NULL,NULL,0,NULL,NULL),(4150,4000,'古巴(加勒比海)','古巴(加勒比海)','Cuba',0,0,0,750,1382546968,NULL,NULL,0,NULL,NULL),(4160,4000,'智利(南美洲西南部)','智利(南美洲西南部)','Chile',0,0,0,740,1382546968,NULL,NULL,0,NULL,NULL),(4170,4000,'秘鲁','秘魯','Peru',0,0,0,730,1382546968,NULL,NULL,0,NULL,NULL),(4180,4000,'墨西哥','墨西哥','Mexico',0,0,0,720,1382546968,NULL,NULL,0,NULL,NULL),(4190,4000,'乌拉圭','烏拉圭','Uruguay',0,0,0,710,1382546968,NULL,NULL,0,NULL,NULL),(4200,4000,'厄瓜多尔(南美洲西北部)','厄瓜多爾(南美洲西北部)','Ecuador',0,0,0,700,1382546968,NULL,NULL,0,NULL,NULL),(4210,4000,'委内瑞拉(南美洲北部)','委內瑞拉(南美洲北部)','Venezuela',0,0,0,690,1382546968,NULL,NULL,0,NULL,NULL),(4220,4000,'法属圭亚那(南美洲东北部)','法屬圭亞那(南美洲東北部)','French Guiana',0,0,0,680,1382546968,NULL,NULL,0,NULL,NULL),(4230,4000,'萨尔瓦多(中南美洲)','薩爾瓦多(中南美洲)','El Salvador',0,0,0,670,1382546968,NULL,NULL,0,NULL,NULL),(4240,4000,'特立尼达和多巴哥','千理達和托貝哥共和國','Trinidad And Tobago',0,0,0,660,1382546968,NULL,NULL,0,NULL,NULL),(4250,4000,'波多黎各','波多黎各','Puerto Rico',0,0,0,650,1382546968,NULL,NULL,0,NULL,NULL),(4260,4000,'多米尼加(加勒比海)','多明尼加(加勒比海)','Dominican Republic',0,0,0,640,1382546968,NULL,NULL,0,NULL,NULL),(4270,4000,'福克兰群岛','福克蘭群島','Falkland Islands(Malvinas)',0,0,0,630,1382546968,NULL,NULL,0,NULL,NULL),(4280,4000,'格林纳达(西印度群岛东南部)','格瑞那達(西印度群島東南部)','Grenada',0,0,0,620,1382546968,NULL,NULL,0,NULL,NULL),(5000,0,'大洋洲','大洋洲','Oceania',0,0,0,6,1382196124,NULL,NULL,0,NULL,NULL),(5110,5000,'澳大利亚','澳大利亞','Australia',0,0,0,790,1382546968,NULL,NULL,0,NULL,NULL),(5120,5000,'东萨摩亚','美屬薩摩亞','American Samoa',0,0,0,780,1382546968,NULL,NULL,0,NULL,NULL),(5130,5000,'百慕达群岛(大西洋西部)','百慕達群島(大西洋西部)','Bermuda',0,0,0,770,1382546968,NULL,NULL,0,NULL,NULL),(5140,5000,'萨摩亚群岛','薩摩亞群島','Samoa',0,0,0,760,1382546968,NULL,NULL,0,NULL,NULL),(5150,5000,'沃利斯和富图纳群岛','沃利斯和富圖納群島','Wallis And Futuna Islands',0,0,0,750,1382546968,NULL,NULL,0,NULL,NULL),(5160,5000,'梵尼瓦土;万那杜(南太平洋)','梵尼瓦土;萬那杜(南太平洋)','Vanuatu',0,0,0,740,1382546968,NULL,NULL,0,NULL,NULL),(5170,5000,'关岛','關島','Guam',0,0,0,730,1382546968,NULL,NULL,0,NULL,NULL),(5180,5000,'新西兰','新西蘭','New Zealand',0,0,0,720,1382546968,NULL,NULL,0,NULL,NULL),(5190,5000,'托克劳群岛','托客勞群島','Tokelau',0,0,0,710,1382546968,NULL,NULL,0,NULL,NULL),(5200,5000,'巴拉圭','巴拉圭','Paraguay',0,0,0,700,1382546968,NULL,NULL,0,NULL,NULL),(5210,5000,'美国本土外小岛屿','聯合的狀況微小的在外的島嶼','United States Minor Outlying Islands',0,0,0,690,1382546968,NULL,NULL,0,NULL,NULL),(5220,5000,'图瓦鲁(西南太平洋)','吐瓦魯(西南太平洋)','Tuvalu',0,0,0,680,1382546968,NULL,NULL,0,NULL,NULL),(5230,5000,'汤加王国(西南太平洋)','東加王國(西南太平洋)','Tonga',0,0,0,670,1382546968,NULL,NULL,0,NULL,NULL),(5240,5000,'所罗门群岛','所羅門群島','Solomon Islands',0,0,0,660,1382546968,NULL,NULL,0,NULL,NULL),(5250,5000,'帕劳','帛琉','Palau',0,0,0,650,1382546968,NULL,NULL,0,NULL,NULL),(5260,5000,'巴布亚新几内亚','巴布亞新幾內亞','Papua New Guinea',0,0,0,640,1382546968,NULL,NULL,0,NULL,NULL),(5270,5000,'科科斯（基林）群岛','可可斯群島(椰子島)','Cocos (Keeling) Islands',0,0,0,630,1382546968,NULL,NULL,0,NULL,NULL),(5280,5000,'库克群岛','科克群島','Cook Islands',0,0,0,620,1382546968,NULL,NULL,0,NULL,NULL),(5290,5000,'瑙鲁','諾魯','Nauru',0,0,0,610,1382546968,NULL,NULL,0,NULL,NULL),(5300,5000,'纽埃','尼烏亞島','Niue',0,0,0,600,1382546968,NULL,NULL,0,NULL,NULL),(5310,5000,'诺福克岛屿','諾福克島嶼','Norfolk Island',0,0,0,590,1382546968,NULL,NULL,0,NULL,NULL),(5320,5000,'裴济(西南太平洋)','裴濟(西南太平洋)','Fiji',0,0,0,580,1382546968,NULL,NULL,0,NULL,NULL),(5330,5000,'密克罗尼西亚(太平洋西部)','密克羅尼西亞(太平洋西部)','Micronesia, Federated States Of',0,0,0,570,1382546968,NULL,NULL,0,NULL,NULL),(5340,5000,'吉里巴斯','吉里巴斯','Kiribati',0,0,0,560,1382546968,NULL,NULL,0,NULL,NULL),(5350,5000,'马绍尔群岛','馬紹爾群島','Marshall Islands',0,0,0,550,1382546968,NULL,NULL,0,NULL,NULL),(5360,5000,'北马里亚纳群岛','馬里亞納群島','Northern Mariana Islands',0,0,0,540,1382546968,NULL,NULL,0,NULL,NULL),(5370,5000,'新喀里多尼亚','新喀里多尼亚','New Caledonia',0,0,0,530,1382546968,NULL,NULL,0,NULL,NULL),(6000,0,'非洲','非洲','Africa',0,0,0,5,1382197024,NULL,NULL,0,NULL,NULL),(6110,6000,'埃及','埃及','Egypt',0,0,0,790,1382546968,NULL,NULL,0,NULL,NULL),(6120,6000,'突尼西亚(北非)','突尼西亞(北非)','Tunisia',0,0,0,780,1382546968,NULL,NULL,0,NULL,NULL),(6130,6000,'南非','南非','South Africa',0,0,0,770,1382546968,NULL,NULL,0,NULL,NULL),(6140,6000,'赞比亚','尚比亞','Zambia',0,0,0,760,1382546968,NULL,NULL,0,NULL,NULL),(6150,6000,'扎伊尔','扎伊爾','Zaire',0,0,0,750,1382546968,NULL,NULL,0,NULL,NULL),(6160,6000,'安哥拉','安哥拉','Angola',0,0,0,740,1382546968,NULL,NULL,0,NULL,NULL),(6170,6000,'布基纳法索(南非)','布吉納法索(南非)','Burkina Faso',0,0,0,730,1382546968,NULL,NULL,0,NULL,NULL),(6180,6000,'蒲隆地(中非)','蒲隆地(中非)','Burundi',0,0,0,720,1382546968,NULL,NULL,0,NULL,NULL),(6190,6000,'贝南(西非)','貝南(西非)','Benin',0,0,0,710,1382546968,NULL,NULL,0,NULL,NULL),(6200,6000,'波札那(南非)','波札那(南非)','Botswana',0,0,0,700,1382546968,NULL,NULL,0,NULL,NULL),(6210,6000,'吉布地(东非)','吉布地(東非)','Djibouti',0,0,0,690,1382546968,NULL,NULL,0,NULL,NULL),(6220,6000,'喀麦隆(西非)','喀麥隆(西非)','Cameroon',0,0,0,680,1382546968,NULL,NULL,0,NULL,NULL),(6230,6000,'中非共和国','中非共和國','Central African Republic',0,0,0,670,1382546968,NULL,NULL,0,NULL,NULL),(6240,6000,'刚果民主共和国','剛果民主共和國','Congo',0,0,0,660,1382546968,NULL,NULL,0,NULL,NULL),(6250,6000,'土哥(西非)','土哥(西非)','Togo',0,0,0,650,1382546968,NULL,NULL,0,NULL,NULL),(6260,6000,'津巴布韦(南非)','辛巴威(南非)','Zimbabwe',0,0,0,640,1382546968,NULL,NULL,0,NULL,NULL),(6270,6000,'加彭(中非西部)','加彭(中非西部)','Gabon',0,0,0,630,1382546968,NULL,NULL,0,NULL,NULL),(6280,6000,'几内亚(西非)','幾內亞(西非)','Guinea',0,0,0,620,1382546968,NULL,NULL,0,NULL,NULL),(6290,6000,'赤道几内亚(西非)','赤道幾內亞(西非)','Equatorial Guinea',0,0,0,610,1382546968,NULL,NULL,0,NULL,NULL),(6300,6000,'梅约特','梅約特','Mayotte',0,0,0,600,1382546968,NULL,NULL,0,NULL,NULL),(6310,6000,'阿尔及利亚','阿爾及利亞','Algeria',0,0,0,590,1382546968,NULL,NULL,0,NULL,NULL),(6320,6000,'country_code_gs','country_code_gs','S. Georgia And The S. Sandwich Islands',0,0,0,580,1382546968,NULL,NULL,0,NULL,NULL),(6330,6000,'乌干达','烏干達','Uganda',0,0,0,570,1382546968,NULL,NULL,0,NULL,NULL),(6340,6000,'厄立特里亚(东北非)','厄立特里亞(東北非)','Eritrea',0,0,0,560,1382546968,NULL,NULL,0,NULL,NULL),(6350,6000,'查德(中北非)','查德(中北非)','Chad',0,0,0,550,1382546968,NULL,NULL,0,NULL,NULL),(6360,6000,'迦纳(西非)','迦納(西非)','Ghana',0,0,0,540,1382546968,NULL,NULL,0,NULL,NULL),(6370,6000,'利比亚','利比亞','Libyan Arab Jamahiriya',0,0,0,530,1382546968,NULL,NULL,0,NULL,NULL),(6380,6000,'摩洛哥','摩洛哥','Morocco',0,0,0,520,1382546968,NULL,NULL,0,NULL,NULL),(6390,6000,'坦桑尼亚','坦尚尼亞','Tanzania United Republic Of',0,0,0,510,1382546968,NULL,NULL,0,NULL,NULL),(6400,6000,'斯威士兰','史瓦濟蘭','Swaziland',0,0,0,500,1382546968,NULL,NULL,0,NULL,NULL),(6410,6000,'苏里南','蘇利南','Suriname',0,0,0,490,1382546968,NULL,NULL,0,NULL,NULL),(6420,6000,'索马利亚','索馬利亞','Somalia',0,0,0,480,1382546968,NULL,NULL,0,NULL,NULL),(6430,6000,'塞内加尔','塞內加爾','Senegal',0,0,0,470,1382546968,NULL,NULL,0,NULL,NULL),(6440,6000,'塞拉利昂','獅子山','Sierra Leone',0,0,0,460,1382546968,NULL,NULL,0,NULL,NULL),(6450,6000,'塞舌尔','賽席爾群島','Seychelles',0,0,0,450,1382546968,NULL,NULL,0,NULL,NULL),(6460,6000,'卢旺达','盧安達','Rwanda',0,0,0,440,1382546968,NULL,NULL,0,NULL,NULL),(6470,6000,'圣赫勒拿岛','聖赫勒拿島','St. Helena St．Helena',0,0,0,430,1382546968,NULL,NULL,0,NULL,NULL),(6480,6000,'留尼汪','留尼旺島','Reunion',0,0,0,420,1382546968,NULL,NULL,0,NULL,NULL),(6490,6000,'卡达','卡達','Qatar',0,0,0,410,1382546968,NULL,NULL,0,NULL,NULL),(6500,6000,'维德角(大西洋东部)','維德角(大西洋東部)','Cape Verde',0,0,0,400,1382546968,NULL,NULL,0,NULL,NULL),(6510,6000,'圣诞岛屿','聖誕島嶼','Christmas Island',0,0,0,390,1382546968,NULL,NULL,0,NULL,NULL),(6520,6000,'西撒哈拉','西撒哈拉沙漠','Western Sahara',0,0,0,380,1382546968,NULL,NULL,0,NULL,NULL),(6530,6000,'尼日利亚','奈及利亞','Nigeria',0,0,0,370,1382546968,NULL,NULL,0,NULL,NULL),(6540,6000,'埃塞俄比亚','衣索比亞','Ethiopia',0,0,0,360,1382546968,NULL,NULL,0,NULL,NULL),(6550,6000,'冈比亚','甘比亞','Gambia',0,0,0,350,1382546968,NULL,NULL,0,NULL,NULL),(6560,6000,'利比里亚','賴比瑞亞','Liberia',0,0,0,340,1382546968,NULL,NULL,0,NULL,NULL),(6570,6000,'肯尼亚','肯亞','Kenya',0,0,0,330,1382546968,NULL,NULL,0,NULL,NULL),(6580,6000,'莱索托','賴索托','Lesotho',0,0,0,320,1382546968,NULL,NULL,0,NULL,NULL),(6590,6000,'马达加斯加','馬達加斯加','Madagascar',0,0,0,310,1382546968,NULL,NULL,0,NULL,NULL),(6600,6000,'马里共和国','馬利','Mali',0,0,0,300,1382546968,NULL,NULL,0,NULL,NULL),(6610,6000,'毛里塔尼亚','茅利塔尼亞','Mauritania',0,0,0,290,1382546968,NULL,NULL,0,NULL,NULL),(6620,6000,'毛里求斯','模里西斯','Mauritius',0,0,0,280,1382546968,NULL,NULL,0,NULL,NULL),(6630,6000,'马尔代夫','馬爾地夫','Maldives',0,0,0,270,1382546968,NULL,NULL,0,NULL,NULL),(6640,6000,'马拉维','馬拉威','Malawi',0,0,0,260,1382546968,NULL,NULL,0,NULL,NULL),(6650,6000,'莫桑比克','莫三比克','Mozambique',0,0,0,250,1382546968,NULL,NULL,0,NULL,NULL),(6660,6000,'纳米比亚','納米比亞','Namibia',0,0,0,240,1382546968,NULL,NULL,0,NULL,NULL),(6670,6000,'尼日','尼日','Niger',0,0,0,230,1382546968,NULL,NULL,0,NULL,NULL),(6680,6000,'南极洲','南極洲','Antarctica',0,0,0,790,1382546968,NULL,NULL,0,NULL,NULL),(6690,6000,'布干维尔岛','布干維爾島','Bouvet Island',0,0,0,780,1382546968,NULL,NULL,0,NULL,NULL);

/*Table structure for table `tb_website_category` */

DROP TABLE IF EXISTS `tb_website_category`;

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
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

/*Data for the table `tb_website_category` */

insert  into `tb_website_category`(`category_id`,`category_fatherId`,`category_name_zh-cn`,`category_name_zh-tw`,`category_name_en-us`,`category_count_website`,`category_count_click_in`,`category_count_click_out`,`category_order`,`category_insert_time`,`category_update_tme`) values (3,0,'门户/导航/黄页','門戶/導航/黃頁',NULL,0,0,0,23,1375587654,0),(4,0,'论坛/社区','論壇/社區',NULL,0,0,0,22,1375587654,0),(5,0,'社团/宗教','社團/宗教',NULL,0,0,0,21,1375587654,0),(6,0,'律师/移民/留学','律師/移民/留學',NULL,0,0,0,20,1375587654,0),(7,0,'报刊/文学/媒体','報刊/文學/媒體',NULL,0,0,0,19,1375587654,0),(8,0,'餐饮/购物/商家','餐飲/購物/商家',NULL,0,0,0,18,1375587654,0),(9,0,'财经/保险/地产','財經/保險/地產',NULL,0,0,0,17,1375587654,0),(10,0,'商业专业服务','商業專業服務',NULL,0,0,0,16,1375587654,0),(11,0,'交通旅游','交通旅遊',NULL,0,0,0,15,1375587654,0),(12,0,'教育/外语/求职','教育/外語/求職',NULL,0,0,0,14,1375587654,0),(13,0,'休闲/娱乐/交友','休閒/娛樂/交友',NULL,0,0,0,13,1375587654,0),(14,0,'政府及使领馆','政府及使領館',NULL,0,0,0,12,1375587654,0),(15,0,'个人博客其它','個人博客其他',NULL,0,0,0,11,1375587654,0),(16,0,'企业贸易B2B','企業貿易B2B',NULL,0,0,0,10,1375587654,0);

/*Table structure for table `tb_website_history` */

DROP TABLE IF EXISTS `tb_website_history`;

CREATE TABLE `tb_website_history` (
  `history_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `history_websiteId` int(11) DEFAULT NULL,
  `history_website` text,
  `history_insert_time` int(11) DEFAULT NULL,
  `history_insert_man` int(11) DEFAULT NULL,
  PRIMARY KEY (`history_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `tb_website_history` */

/*Table structure for table `tb_website_intro` */

DROP TABLE IF EXISTS `tb_website_intro`;

CREATE TABLE `tb_website_intro` (
  `website_id` int(11) unsigned NOT NULL,
  `website_intro` text,
  `website_intro_insert_time` int(11) NOT NULL DEFAULT '0',
  `website_intro_insert_ip` varchar(32) DEFAULT NULL,
  `website_intro_update_time` int(11) unsigned NOT NULL DEFAULT '0',
  `website_intro_update_ip` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`website_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `tb_website_intro` */

/*Table structure for table `tb_website_keyword` */

DROP TABLE IF EXISTS `tb_website_keyword`;

CREATE TABLE `tb_website_keyword` (
  `keyword_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `keyword_label` varchar(32) NOT NULL,
  `keyword_count_enter` int(11) unsigned NOT NULL DEFAULT '0',
  `keyword_insert_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '插入时间',
  `keyword_update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '最后查询时间',
  PRIMARY KEY (`keyword_id`),
  UNIQUE KEY `Label` (`keyword_label`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `tb_website_keyword` */

/*Table structure for table `tb_website_meta` */

DROP TABLE IF EXISTS `tb_website_meta`;

CREATE TABLE `tb_website_meta` (
  `website_id` int(11) unsigned NOT NULL,
  `website_meta_keywords` varchar(512) DEFAULT NULL,
  `website_meta_description` varchar(512) DEFAULT NULL,
  PRIMARY KEY (`website_id`),
  UNIQUE KEY `WebsiteId` (`website_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `tb_website_meta` */

/*Table structure for table `tb_website_tag` */

DROP TABLE IF EXISTS `tb_website_tag`;

CREATE TABLE `tb_website_tag` (
  `tag_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '流水号',
  `tag_name` varchar(64) NOT NULL COMMENT '标签名',
  `tag_count` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '标签被使用数',
  `tag_insert_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '标签时间',
  `tag_update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '标签更新时间',
  PRIMARY KEY (`tag_id`),
  UNIQUE KEY `Name` (`tag_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `tb_website_tag` */

/*Table structure for table `tb_website_tag_relation` */

DROP TABLE IF EXISTS `tb_website_tag_relation`;

CREATE TABLE `tb_website_tag_relation` (
  `relation_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `relation_websiteId` int(11) unsigned NOT NULL,
  `relation_tagId` int(11) unsigned NOT NULL,
  `relation_time` int(11) unsigned NOT NULL,
  PRIMARY KEY (`relation_id`),
  UNIQUE KEY `WebId_TagId` (`relation_websiteId`,`relation_tagId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `tb_website_tag_relation` */

/*Table structure for table `tb_website_trend_apply_register` */

DROP TABLE IF EXISTS `tb_website_trend_apply_register`;

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

/*Data for the table `tb_website_trend_apply_register` */

/*Table structure for table `tb_website_trend_click_in` */

DROP TABLE IF EXISTS `tb_website_trend_click_in`;

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

/*Data for the table `tb_website_trend_click_in` */

/*Table structure for table `tb_website_trend_click_out` */

DROP TABLE IF EXISTS `tb_website_trend_click_out`;

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

/*Data for the table `tb_website_trend_click_out` */

/*Table structure for table `tb_website_trend_register` */

DROP TABLE IF EXISTS `tb_website_trend_register`;

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

/*Data for the table `tb_website_trend_register` */

/*Table structure for table `tb_website_trend_search` */

DROP TABLE IF EXISTS `tb_website_trend_search`;

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

/*Data for the table `tb_website_trend_search` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;

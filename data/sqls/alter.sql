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
/* 2013-12-21 给网站注册表增加字段website_id ，并设为唯一值 */
alter table `utf8_diana`.`tb_website_apply_register` add column `website_id` int(11) UNSIGNED DEFAULT '0' NOT NULL COMMENT '网站ID' after `register_id`;
alter table `utf8_diana`.`tb_website_apply_register` add unique `WebsiteId` (`website_id`);

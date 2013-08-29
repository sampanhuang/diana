/*清空网站会员*/
DELETE FROM tb_member;
DELETE FROM tb_member_favorite;
DELETE FROM tb_member_log_login;
DELETE FROM tb_member_log_resetpwd;
DELETE FROM tb_member_msg;
DROP TABLE tb_member_trend_login_2013;
DROP TABLE tb_member_trend_register_2013;


/*清空网站*/
DELETE FROM tb_website;
UPDATE tb_website_category SET category_count_website = 0,category_count_click_in = 0,category_count_click_out = 0;
DELETE FROM tb_website_country;
DELETE FROM tb_website_intro;
DELETE FROM tb_website_meta;
DROP TABLE tb_website_trend_register_2013;
DROP TABLE tb_website_trend_click_in_2013;
DROP TABLE tb_website_trend_click_out_2013;
/*清空网站标签*/
DELETE FROM tb_website_tag;
DELETE FROM tb_website_tag_relation;
/*清空网站搜索*/
DELETE FROM tb_website_keyword;
DROP TABLE tb_website_trend_search_2013;

/*清空网站申请*/
DELETE FROM tb_website_apply;
DELETE FROM tb_website_apply_intro;
DELETE FROM tb_website;
DROP TABLE tb_website_trend_apply_2013;

/*清除管理员垃圾数据*/
DELETE FROM tb_manager_log_login;
DELETE FROM tb_manager_log_resetpwd;
DELETE FROM tb_manager_msg;


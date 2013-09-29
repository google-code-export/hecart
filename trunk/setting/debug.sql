truncate table `oscart`.`os_language`;
insert into `os_language` (`name`, `code`, `locale`, `image`, `directory`, `filename`, `sort_order`, `status`) values('简体中文','cn','zh_CN.UTF-8,zh_CN,zh-cn,chinese','cn.png','chinese','chinese','0','1');
insert into `os_language` (`name`, `code`, `locale`, `image`, `directory`, `filename`, `sort_order`, `status`) values('English','en','en_US.UTF-8,en_US,en-gb,english','gb.png','english','english','1','1');

truncate table `oscart`.`os_category`;
truncate table `oscart`.`os_category_description`;
truncate table `oscart`.`os_category_to_store`;

insert oscart.os_category (parent_id, top, `column`, `status`) SELECT ParentID, IF(ParentID=0, 1,0), 4, 1 from ishop.web_product_category LIMIT 1100;
insert oscart.os_category_description (category_id, language_id, `name`, description) SELECT CategoryID, 1, CategoryName, CategoryName from ishop.web_product_category LIMIT 1100;
insert oscart.os_category_to_store (category_id, store_id) SELECT CategoryID, 0 from ishop.web_product_category LIMIT 1100;


UPDATE os_category_description SET language_id = 3 where language_id = 1;
UPDATE os_category_description SET language_id = 1 where language_id = 2;
UPDATE os_category_description SET language_id = 2 where language_id = 3;


insert oscart.os_city (country_id, zone_id, `code`, `name`)
SELECT 44, Z.zone_id, C.code, C.CityName
from ishop.sys_area_city C 
INNER JOIN ishop.sys_area_province P ON P.AID = C.ProvinceID
INNER JOIN oscart.os_zone Z ON SUBSTRING(Z.name, 1, 2) = SUBSTRING(P.ProvinceName, 1, 2)
WHERE Z.country_id = 44;


truncate table `oscart`.`os_address`;
truncate table `oscart`.`os_customer`;
truncate table `oscart`.`os_customer_ip`;
truncate table `oscart`.`os_order`;
truncate table `oscart`.`os_order_download`;
truncate table `oscart`.`os_order_fraud`;
truncate table `oscart`.`os_order_history`;
truncate table `oscart`.`os_order_option`;
truncate table `oscart`.`os_order_product`;
truncate table `oscart`.`os_order_total`;
truncate table `oscart`.`os_order_voucher`;

insert into `os_customer_ip` (`customer_ip_id`, `customer_id`, `ip`, `date_added`) values('1','1','127.0.0.1','2012-10-16 10:44:40');
insert into `os_customer`(`customer_id`,`store_id`,`fullname`,`nickname`,`email`,`telephone`,`fax`,`password`,`salt`,`cart`,`wishlist`,`newsletter`,`address_id`,`customer_group_id`,`ip`,`status`,`approved`,`token`,`date_added`) values (1,0,'张树林','阳光小树','hoojar@163.com','13798563133','','579d526cf841e1f71d84e584fa3c2c9d7370ea0c','dce6a6e39','a:1:{i:46;i:1;}','',0,1,1,'127.0.0.1',1,1,'','2012-10-26 15:27:28');
insert  into `os_address`(`address_id`,`customer_id`,`fullname`,`telephone`,`company`,`company_id`,`tax_id`,`address_1`,`address_2`,`city`,`postcode`,`country_id`,`zone_id`) values (1,1,'张树林','13798563133','','','','福田区','','深圳市','518000',44,689);
insert  into `os_address`(`address_id`,`customer_id`,`fullname`,`telephone`,`company`,`company_id`,`tax_id`,`address_1`,`address_2`,`city`,`postcode`,`country_id`,`zone_id`) values (2,1,'张江华','13798563122','','','','摩托车大市场','','株洲市县级','',44,698);



TRUNCATE `os_address`;
TRUNCATE `os_attribute`;
TRUNCATE `os_attribute_description`;
TRUNCATE `os_attribute_group`;
TRUNCATE `os_attribute_group_description`;
TRUNCATE `os_banner`;
TRUNCATE `os_banner_image`;
TRUNCATE `os_banner_image_description`;
TRUNCATE `os_customer_ip`;
TRUNCATE `os_manufacturer`;
TRUNCATE `os_manufacturer_to_store`;
TRUNCATE `os_option_description`;
TRUNCATE `os_option_value`;
TRUNCATE `os_option_value_description`;

TRUNCATE `os_order`;
TRUNCATE `os_order_download`;
TRUNCATE `os_order_fraud`;
TRUNCATE `os_order_history`;
TRUNCATE `os_order_option`;
TRUNCATE `os_order_product`;
TRUNCATE `os_order_total`;

TRUNCATE `os_product`;
TRUNCATE `os_product_attribute`;
TRUNCATE `os_product_description`;
TRUNCATE `os_product_discount`;
TRUNCATE `os_product_image`;
TRUNCATE `os_product_option`;
TRUNCATE `os_product_option_value`;
TRUNCATE `os_product_related`;
TRUNCATE `os_product_reward`;
TRUNCATE `os_product_special`;
TRUNCATE `os_product_to_category`;
TRUNCATE `os_product_to_download`;
TRUNCATE `os_product_to_layout`;
TRUNCATE `os_product_to_store`;

TRUNCATE `os_return`;
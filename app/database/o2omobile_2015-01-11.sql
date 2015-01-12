# ************************************************************
# Sequel Pro SQL dump
# Version 4135
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 173.255.204.183 (MySQL 5.5.40-MariaDB)
# Database: o2omobile
# Generation Time: 2015-01-11 02:37:12 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table o2omobile_apply_service
# ------------------------------------------------------------

DROP TABLE IF EXISTS `o2omobile_apply_service`;

CREATE TABLE `o2omobile_apply_service` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `service_type_id` int(11) NOT NULL COMMENT '服务id',
  `firstclass_service_category_id` int(11) NOT NULL COMMENT '申请的一级服务类目id',
  `secondclass_service_category_id` int(11) NOT NULL COMMENT '申请的二级服务类目id',
  `state` tinyint(4) NOT NULL COMMENT '0 处理中   1 通过   2 不通过',
  `note` varchar(255) NOT NULL COMMENT '失败原因 推送消息',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户申请认证更多服务';



# Dump of table o2omobile_backup
# ------------------------------------------------------------

DROP TABLE IF EXISTS `o2omobile_backup`;

CREATE TABLE `o2omobile_backup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL,
  `groupname` varchar(255) NOT NULL COMMENT '组名',
  `is_v` int(11) NOT NULL COMMENT '是否分卷或几卷',
  `v_kb` int(11) NOT NULL COMMENT '每卷多少kb',
  `all_kb` int(11) NOT NULL COMMENT '共多少kb',
  `user_id` int(11) NOT NULL COMMENT '处理者',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='数据备份';



# Dump of table o2omobile_bigc
# ------------------------------------------------------------

DROP TABLE IF EXISTS `o2omobile_bigc`;

CREATE TABLE `o2omobile_bigc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `order_id` int(11) NOT NULL COMMENT '订单id',
  `years` int(11) NOT NULL COMMENT '年',
  `month` varchar(10) NOT NULL COMMENT '月',
  `times` int(11) NOT NULL COMMENT '年月',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户按月统计成交量';



# Dump of table o2omobile_certify
# ------------------------------------------------------------

DROP TABLE IF EXISTS `o2omobile_certify`;

CREATE TABLE `o2omobile_certify` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '名字',
  `ename` varchar(255) NOT NULL COMMENT '英文名 对应图标用',
  `is_valid` tinyint(4) NOT NULL DEFAULT '1' COMMENT '0是不可用',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='认证表';



# Dump of table o2omobile_client
# ------------------------------------------------------------

DROP TABLE IF EXISTS `o2omobile_client`;

CREATE TABLE `o2omobile_client` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(100) NOT NULL COMMENT '设备id',
  `user_id` int(10) unsigned NOT NULL,
  `client_type` varchar(100) NOT NULL COMMENT '1:iOS  0: android',
  `token` varchar(255) DEFAULT NULL COMMENT '设备token',
  `push_switch` tinyint(3) unsigned DEFAULT '1' COMMENT '推送通知开关 0 关 1 开',
  `created_time` int(10) unsigned DEFAULT '0' COMMENT '添加时间',
  `version` tinyint(5) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT '2013-12-02 16:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '2013-12-02 16:00:00',
  PRIMARY KEY (`id`),
  KEY `user_id_idx` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='客户端信息';



# Dump of table o2omobile_client_session
# ------------------------------------------------------------

DROP TABLE IF EXISTS `o2omobile_client_session`;

CREATE TABLE `o2omobile_client_session` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `session_id` varchar(100) DEFAULT NULL,
  `expired_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table o2omobile_comments
# ------------------------------------------------------------

DROP TABLE IF EXISTS `o2omobile_comments`;

CREATE TABLE `o2omobile_comments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `category` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '0:订单,1:其它',
  `o_id` int(10) unsigned NOT NULL DEFAULT '0',
  `s_user` int(10) unsigned NOT NULL DEFAULT '0',
  `o_user` int(10) unsigned NOT NULL DEFAULT '0',
  `content` varchar(255) DEFAULT NULL,
  `rank` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table o2omobile_comments.bak
# ------------------------------------------------------------

DROP TABLE IF EXISTS `o2omobile_comments.bak`;

CREATE TABLE `o2omobile_comments.bak` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `category` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '0:订单,1:其它',
  `o_id` int(10) unsigned NOT NULL DEFAULT '0',
  `s_user` int(10) unsigned NOT NULL DEFAULT '0',
  `o_user` int(10) unsigned NOT NULL DEFAULT '0',
  `content` varchar(255) DEFAULT NULL,
  `rank` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table o2omobile_failed_jobs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `o2omobile_failed_jobs`;

CREATE TABLE `o2omobile_failed_jobs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `connection` varchar(255) NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` text NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='队列失败记录';



# Dump of table o2omobile_feedback
# ------------------------------------------------------------

DROP TABLE IF EXISTS `o2omobile_feedback`;

CREATE TABLE `o2omobile_feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `text` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='意见反馈';



# Dump of table o2omobile_history
# ------------------------------------------------------------

DROP TABLE IF EXISTS `o2omobile_history`;

CREATE TABLE `o2omobile_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL COMMENT '订单id',
  `user_id` int(11) NOT NULL COMMENT '操作者id',
  `order_status` tinyint(3) unsigned NOT NULL COMMENT '订单状态',
  `note` text NOT NULL COMMENT '取消订单的原因',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '处理时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='订单处理状态记录表';



# Dump of table o2omobile_log
# ------------------------------------------------------------

DROP TABLE IF EXISTS `o2omobile_log`;

CREATE TABLE `o2omobile_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '操作人员ID',
  `object_id` varchar(256) DEFAULT NULL,
  `action` varchar(256) NOT NULL,
  `ip` varchar(30) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table o2omobile_message
# ------------------------------------------------------------

DROP TABLE IF EXISTS `o2omobile_message`;

CREATE TABLE `o2omobile_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `content` varchar(255) NOT NULL,
  `type` tinyint(2) NOT NULL COMMENT '消息类型 1系统消息 2订单,3其他',
  `url` varchar(255) NOT NULL,
  `order_id` int(10) unsigned NOT NULL DEFAULT '0',
  `is_readed` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否已读',
  `is_pushed` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `type` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='消息表';



# Dump of table o2omobile_my_certify
# ------------------------------------------------------------

DROP TABLE IF EXISTS `o2omobile_my_certify`;

CREATE TABLE `o2omobile_my_certify` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `certify_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='我的认证表';



# Dump of table o2omobile_my_services
# ------------------------------------------------------------

DROP TABLE IF EXISTS `o2omobile_my_services`;

CREATE TABLE `o2omobile_my_services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `services_id` int(11) NOT NULL COMMENT '服务id',
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='我的服务';



# Dump of table o2omobile_orders
# ------------------------------------------------------------

DROP TABLE IF EXISTS `o2omobile_orders`;

CREATE TABLE `o2omobile_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_sn` varchar(255) NOT NULL COMMENT '订单序列号',
  `service_type` int(11) NOT NULL COMMENT '服务类型id',
  `employer` int(11) NOT NULL COMMENT '雇主id',
  `employee` int(11) NOT NULL COMMENT '雇员id',
  `text` text NOT NULL,
  `voice` varchar(255) NOT NULL,
  `duration` int(11) NOT NULL COMMENT '音频时间长度 秒',
  `location` varchar(255) NOT NULL,
  `lon` varchar(255) NOT NULL COMMENT '经度',
  `lat` varchar(255) NOT NULL COMMENT '纬度',
  `offer_price` decimal(10,2) NOT NULL COMMENT '价格',
  `transaction_price` decimal(10,2) NOT NULL COMMENT '最终成交价',
  `pay_code` tinyint(3) unsigned NOT NULL COMMENT '0在线支付 1线下支付',
  `appointment_time` varchar(255) NOT NULL COMMENT '约定时间',
  `accept_time` varchar(255) NOT NULL COMMENT '接单时间',
  `order_status` int(11) NOT NULL DEFAULT '0' COMMENT '0,// 客户发单 1,// 已确认接单 2,	// 工作完成 3,	// 已付款 4,	// 付款已确认 5,	// 订单结束 6		// 订单取消',
  `push_number` int(11) NOT NULL COMMENT '此订单推送给了多少人',
  `default_receiver_id` int(11) NOT NULL COMMENT '默认的接单人，由请他帮忙触发',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `TransID` varchar(255) NOT NULL COMMENT '支付成功的订单id',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='订单表';



# Dump of table o2omobile_permission_group
# ------------------------------------------------------------

DROP TABLE IF EXISTS `o2omobile_permission_group`;

CREATE TABLE `o2omobile_permission_group` (
  `id` smallint(4) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `permissions` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table o2omobile_report
# ------------------------------------------------------------

DROP TABLE IF EXISTS `o2omobile_report`;

CREATE TABLE `o2omobile_report` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `operate_id` int(11) NOT NULL COMMENT '投诉人',
  `user_id` int(11) NOT NULL COMMENT '被投诉',
  `order_id` int(11) NOT NULL COMMENT '被投诉的订单',
  `text` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='投诉 举报';



# Dump of table o2omobile_services
# ------------------------------------------------------------

DROP TABLE IF EXISTS `o2omobile_services`;

CREATE TABLE `o2omobile_services` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL COMMENT '父id',
  `name` char(32) NOT NULL,
  `desc` char(255) NOT NULL,
  `imgurl` char(255) NOT NULL,
  `state` tinyint(2) NOT NULL DEFAULT '0',
  `usort` smallint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table o2omobile_test
# ------------------------------------------------------------

DROP TABLE IF EXISTS `o2omobile_test`;

CREATE TABLE `o2omobile_test` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` text NOT NULL,
  `input1` varchar(255) NOT NULL,
  `times` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='测试用的';



# Dump of table o2omobile_users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `o2omobile_users`;

CREATE TABLE `o2omobile_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mobile` char(20) DEFAULT NULL,
  `username` char(50) DEFAULT NULL,
  `password` char(50) NOT NULL,
  `role` tinyint(2) unsigned NOT NULL COMMENT '0:普通用户，1:自由人审核中，2:自由人，99:管理员',
  `name` varchar(255) NOT NULL COMMENT '真实姓名',
  `bankcard` varchar(255) NOT NULL COMMENT '银行卡',
  `identity_card` varchar(255) NOT NULL COMMENT '身份证',
  `location` varchar(255) NOT NULL COMMENT '用户位置 每次登陆要更新',
  `comment_goodrate` float NOT NULL COMMENT '好评率',
  `comment_count` int(11) NOT NULL COMMENT '被评论数',
  `lon` varchar(255) NOT NULL COMMENT '经度',
  `lat` varchar(255) NOT NULL COMMENT '纬度',
  `balance` decimal(10,2) NOT NULL COMMENT '余额',
  `test` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '1是禁用',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `remember_token` char(255) DEFAULT NULL,
  `nickname` char(50) NOT NULL,
  `signature` varchar(255) NOT NULL,
  `brief` varchar(255) NOT NULL,
  `avatar` varchar(255) NOT NULL,
  `invite_uid` int(10) unsigned NOT NULL DEFAULT '0',
  `group_id` int(10) unsigned NOT NULL DEFAULT '0',
  `gender` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '0是男',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table o2omobile_users_invitecode
# ------------------------------------------------------------

DROP TABLE IF EXISTS `o2omobile_users_invitecode`;

CREATE TABLE `o2omobile_users_invitecode` (
  `user_id` int(10) unsigned NOT NULL,
  `invite_code` char(16) NOT NULL,
  `invite_counts` int(10) unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `invite_code` (`invite_code`),
  UNIQUE KEY `user_id_2` (`user_id`),
  KEY `user_id` (`user_id`),
  KEY `invite_code_2` (`invite_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table o2omobile_users.bak
# ------------------------------------------------------------

DROP TABLE IF EXISTS `o2omobile_users.bak`;

CREATE TABLE `o2omobile_users.bak` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mobile` char(20) DEFAULT NULL,
  `username` char(50) DEFAULT NULL,
  `password` char(50) NOT NULL,
  `role` tinyint(2) unsigned NOT NULL COMMENT '0:普通用户，1:自由人审核中，2:自由人，99:管理员',
  `name` varchar(255) NOT NULL COMMENT '真实姓名',
  `bankcard` varchar(255) NOT NULL COMMENT '银行卡',
  `identity_card` varchar(255) NOT NULL COMMENT '身份证',
  `location` varchar(255) NOT NULL COMMENT '用户位置 每次登陆要更新',
  `comment_goodrate` float NOT NULL COMMENT '好评率',
  `comment_count` int(11) NOT NULL COMMENT '被评论数',
  `lon` varchar(255) NOT NULL COMMENT '经度',
  `lat` varchar(255) NOT NULL COMMENT '纬度',
  `balance` decimal(10,2) NOT NULL COMMENT '余额',
  `test` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '1是禁用',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `remember_token` char(255) DEFAULT NULL,
  `nickname` char(50) NOT NULL,
  `signature` varchar(255) NOT NULL,
  `brief` varchar(255) NOT NULL,
  `avatar` varchar(255) NOT NULL,
  `invite_uid` int(10) unsigned NOT NULL DEFAULT '0',
  `group_id` int(10) unsigned NOT NULL DEFAULT '0',
  `gender` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '0是男',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table o2omobile_users.bak2
# ------------------------------------------------------------

DROP TABLE IF EXISTS `o2omobile_users.bak2`;

CREATE TABLE `o2omobile_users.bak2` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mobile` char(20) DEFAULT NULL,
  `username` char(50) DEFAULT NULL,
  `password` char(50) NOT NULL,
  `role` tinyint(2) unsigned NOT NULL COMMENT '0:普通用户，1:自由人审核中，2:自由人，99:管理员',
  `name` varchar(255) NOT NULL COMMENT '真实姓名',
  `bankcard` varchar(255) NOT NULL COMMENT '银行卡',
  `identity_card` varchar(255) NOT NULL COMMENT '身份证',
  `location` varchar(255) NOT NULL COMMENT '用户位置 每次登陆要更新',
  `comment_goodrate` float NOT NULL COMMENT '好评率',
  `comment_count` int(11) NOT NULL COMMENT '被评论数',
  `lon` varchar(255) NOT NULL COMMENT '经度',
  `lat` varchar(255) NOT NULL COMMENT '纬度',
  `balance` decimal(10,2) NOT NULL COMMENT '余额',
  `test` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '1是禁用',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `remember_token` char(255) DEFAULT NULL,
  `nickname` char(50) NOT NULL,
  `signature` varchar(255) NOT NULL,
  `brief` varchar(255) NOT NULL,
  `avatar` varchar(255) NOT NULL,
  `invite_uid` int(10) unsigned NOT NULL DEFAULT '0',
  `group_id` int(10) unsigned NOT NULL DEFAULT '0',
  `gender` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '0是男',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table o2omobile_withdraw
# ------------------------------------------------------------

DROP TABLE IF EXISTS `o2omobile_withdraw`;

CREATE TABLE `o2omobile_withdraw` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '0是提现 1是注册奖励，2邀请，3是提成',
  `user_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `state` tinyint(4) NOT NULL DEFAULT '0' COMMENT ' 0 处理中   1 提现成功   2 提现失败',
  `note` text NOT NULL COMMENT '失败原因',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='我的提现表';




--
-- Dumping routines (FUNCTION) for database 'o2omobile'
--
DELIMITER ;;

# Dump of FUNCTION GetDistance
# ------------------------------------------------------------

/*!50003 DROP FUNCTION IF EXISTS `GetDistance` */;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO"*/;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 FUNCTION `GetDistance`(`lat1` FLOAT, `lon1` FLOAT, `lat2` FLOAT, `lon2` FLOAT) RETURNS float
BEGIN
    DECLARE Distance FLOAT;
    SET Distance = round(((2 * asin(sqrt(pow(sin((lat1 * 3.1415926535898 / 180.0 - lat2 * 3.1415926535898 / 180.0)/2),2) +  
    cos((lat1 * 3.1415926535898 / 180.0))*cos((lat2 * 3.1415926535898 / 180.0))*pow(sin((lon1 * 3.1415926535898 / 180.0  - lon2 * 3.1415926535898 / 180.0)/2),2))))*6378.137)*10000)/10000;
    RETURN Distance;
END */;;

/*!50003 SET SESSION SQL_MODE=@OLD_SQL_MODE */;;
# Dump of FUNCTION hello
# ------------------------------------------------------------

/*!50003 DROP FUNCTION IF EXISTS `hello` */;;
/*!50003 SET SESSION SQL_MODE="NO_AUTO_VALUE_ON_ZERO"*/;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 FUNCTION `hello`() RETURNS varchar(255) CHARSET utf8
BEGIN
RETURN 'Hello  world,i am mysql';
end */;;

/*!50003 SET SESSION SQL_MODE=@OLD_SQL_MODE */;;
DELIMITER ;

/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

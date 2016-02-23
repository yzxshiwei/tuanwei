/*
SQLyog Ultimate v11.27 (32 bit)
MySQL - 10.1.8-MariaDB : Database - tuanwei
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`tuanwei` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `tuanwei`;

/*Table structure for table `collection` */

DROP TABLE IF EXISTS `collection`;

CREATE TABLE `collection` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键表',
  `investor_id` int(11) DEFAULT NULL COMMENT '投资人ID',
  `project_id` int(11) DEFAULT NULL COMMENT '项目ID',
  `state` tinyint(1) DEFAULT NULL COMMENT '收藏状态1:收藏项目2取消收藏项目',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `collection` */

/*Table structure for table `judges` */

DROP TABLE IF EXISTS `judges`;

CREATE TABLE `judges` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `project_id` int(11) DEFAULT NULL COMMENT '用来索引的比赛id',
  `judge_id` int(11) DEFAULT NULL COMMENT '评委id',
  `state` tinyint(1) DEFAULT NULL COMMENT '0:未通过1:通过',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `judges` */

/*Table structure for table `match` */

DROP TABLE IF EXISTS `match`;

CREATE TABLE `match` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `project_id` int(11) DEFAULT NULL COMMENT '比赛项目ID',
  `name` varchar(225) DEFAULT NULL COMMENT '比赛名称',
  `sub_title` varchar(225) DEFAULT NULL COMMENT '副标题',
  `project_start_time` int(11) NOT NULL DEFAULT '0' COMMENT '比赛开始时间',
  `project_end_time` int(11) NOT NULL DEFAULT '0' COMMENT '比赛结束时间',
  `sign_start_time` int(11) NOT NULL DEFAULT '0' COMMENT '比赛报名开始',
  `sign_end_time` int(11) NOT NULL DEFAULT '0' COMMENT '比赛报名结束时间',
  `cover_src` varchar(255) DEFAULT NULL COMMENT '标题图地址',
  `start_file_src` varchar(255) DEFAULT NULL COMMENT '报名表地址',
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '比赛状态0:未发布1:发布2:撤销',
  `judge_amount` int(11) DEFAULT '0' COMMENT '评委数量',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `match` */

/*Table structure for table `message` */

DROP TABLE IF EXISTS `message`;

CREATE TABLE `message` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '消息主键',
  `from_user` int(11) NOT NULL DEFAULT '0' COMMENT '发送人ID',
  `to_user` int(11) NOT NULL DEFAULT '0' COMMENT '接收人ID',
  `msg_type` varchar(32) NOT NULL DEFAULT '' COMMENT '消息类型system/系统消息user/用户消息team/团队消息',
  `title` varchar(64) NOT NULL DEFAULT '' COMMENT '消息标题',
  `content` text COMMENT '消息内容',
  `read_time` int(10) NOT NULL DEFAULT '0' COMMENT '已读时间',
  `create_time` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `message` */

insert  into `message`(`id`,`from_user`,`to_user`,`msg_type`,`title`,`content`,`read_time`,`create_time`) values (1,3,1,'team','','不错，有豆逼的潜质！',0,1456121961);

/*Table structure for table `news` */

DROP TABLE IF EXISTS `news`;

CREATE TABLE `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '新闻主键',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '新闻标题',
  `subtitle` varchar(255) NOT NULL DEFAULT '' COMMENT '新闻副标题',
  `col` varchar(36) NOT NULL DEFAULT '' COMMENT '新闻栏目',
  `sub_col` varchar(36) NOT NULL DEFAULT '' COMMENT '新闻副栏目',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '新闻类型',
  `source` varchar(255) NOT NULL DEFAULT '' COMMENT '消息来源',
  `flag` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态(0:未处理1:统一发布2:拒绝发布)',
  `public_t` int(11) NOT NULL DEFAULT '0' COMMENT '发布时间',
  `top_s` int(11) NOT NULL DEFAULT '0' COMMENT '置顶开始时间',
  `top_e` int(11) NOT NULL DEFAULT '0' COMMENT '置顶结束时间',
  `img_url` varchar(255) NOT NULL DEFAULT '' COMMENT '图片地址',
  `content` text COMMENT '新闻内容',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `news` */

insert  into `news`(`id`,`title`,`subtitle`,`col`,`sub_col`,`type`,`source`,`flag`,`public_t`,`top_s`,`top_e`,`img_url`,`content`) values (2,'刚好那个V','和规范很高','2','1',1,'四川大学教务处',0,1456212520,1456156800,1456502400,'','<p>更换机油有今天已经一天</p>'),(3,'地方发生的风格的','规范的过地方','2','2',3,'四川大学教务处',0,1456215892,1456156800,1456416000,'Upload/img/20160223162452778.gif','<p>个地方个地方感到反感的的风格都是的风格的方式对方感到舒服收到</p>');

/*Table structure for table `packet` */

DROP TABLE IF EXISTS `packet`;

CREATE TABLE `packet` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `project_id` int(11) DEFAULT NULL COMMENT '用来索引的比赛id',
  `class_name` varchar(255) DEFAULT NULL COMMENT '比赛组名称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `packet` */

/*Table structure for table `project` */

DROP TABLE IF EXISTS `project`;

CREATE TABLE `project` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `file_url` varchar(256) NOT NULL DEFAULT '' COMMENT '项目文件存储地址',
  `intro` text COMMENT '项目简介',
  `name` varchar(128) DEFAULT NULL COMMENT '项目名字',
  `sub_title` varbinary(256) DEFAULT NULL COMMENT '副标题',
  `state` tinyint(1) DEFAULT NULL COMMENT '项目状态',
  `state_id` int(11) DEFAULT NULL COMMENT '项目进度表id',
  `team_id` int(11) DEFAULT NULL COMMENT '项目团队表id',
  `teacher_id` int(11) DEFAULT NULL COMMENT '指导老师表id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `project` */

insert  into `project`(`id`,`file_url`,`intro`,`name`,`sub_title`,`state`,`state_id`,`team_id`,`teacher_id`) values (1,'Upload/file/201602191102222592.zip','山东分分分爽歪歪气氛','太发改委范德萨','各位各位各位方式访问方式对付微',NULL,NULL,NULL,NULL),(2,'Upload/file/201602191104404681.rar','防化服还好法规和认同和法规和他人合法很突然的歌好听的好听的好听的好听的好听的黑金帝国vb他的歌vb的他会觉得vb的','聚会与合同 ','要好好一塌糊涂个人和他一样突然',NULL,NULL,NULL,NULL),(3,'Upload/file/201602191315593128.rar','符合人体和法规和认同和符合人体呵呵很反感','非官方呵呵','就如同符合人体后发货',NULL,NULL,NULL,NULL);

/*Table structure for table `project_status` */

DROP TABLE IF EXISTS `project_status`;

CREATE TABLE `project_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `project_id` int(11) DEFAULT NULL COMMENT '项目id',
  `officer` int(11) DEFAULT NULL COMMENT '处理人id',
  `content` text COMMENT '内容',
  `created_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `result` varchar(16) DEFAULT NULL COMMENT '结果',
  `score` int(4) NOT NULL DEFAULT '0' COMMENT '项目评分',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `project_status` */

insert  into `project_status`(`id`,`project_id`,`officer`,`content`,`created_time`,`result`,`score`) values (1,1,4,'很不错',1455868966,NULL,9),(2,2,4,'垃圾到家了！！！',1455869119,NULL,5);

/*Table structure for table `students` */

DROP TABLE IF EXISTS `students`;

CREATE TABLE `students` (
  `student_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '学生编号',
  `stu_card` varbinary(32) NOT NULL DEFAULT '' COMMENT '学生证号',
  `user_id` int(11) unsigned NOT NULL COMMENT '用户编号',
  `college` varchar(50) DEFAULT NULL COMMENT '学院',
  `major` int(5) unsigned DEFAULT NULL COMMENT '专业',
  `degree` int(2) unsigned DEFAULT NULL COMMENT '学历',
  `enrollment` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '入学时间',
  PRIMARY KEY (`student_id`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='学生表';

/*Data for the table `students` */

insert  into `students`(`student_id`,`stu_card`,`user_id`,`college`,`major`,`degree`,`enrollment`) values (1,'587545564',3,'大风车',1,1,1455779038);

/*Table structure for table `teacher_team` */

DROP TABLE IF EXISTS `teacher_team`;

CREATE TABLE `teacher_team` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `user_id` int(11) DEFAULT NULL COMMENT '学生的公用id',
  `project_id` int(11) DEFAULT NULL COMMENT '项目id',
  `teacher_type` tinyint(1) DEFAULT NULL COMMENT '职能(1:指导,2:评审)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `teacher_team` */

/*Table structure for table `teachers` */

DROP TABLE IF EXISTS `teachers`;

CREATE TABLE `teachers` (
  `teacher_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '教师编号',
  `user_id` int(11) unsigned NOT NULL COMMENT '用户ID',
  `union` varchar(50) DEFAULT NULL COMMENT '单位',
  PRIMARY KEY (`teacher_id`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='教师表';

/*Data for the table `teachers` */

insert  into `teachers`(`teacher_id`,`user_id`,`union`) values (1,4,'都比学校');

/*Table structure for table `team` */

DROP TABLE IF EXISTS `team`;

CREATE TABLE `team` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `user_id` int(11) DEFAULT NULL COMMENT '学生的公用id',
  `project_id` int(11) DEFAULT NULL COMMENT '项目id',
  `user_type` varchar(32) DEFAULT NULL COMMENT '身份标识',
  `state` varchar(32) DEFAULT NULL COMMENT '状态(邀请,拒绝,同意)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `team` */

insert  into `team`(`id`,`user_id`,`project_id`,`user_type`,`state`) values (1,3,1,'6','0'),(2,5,2,'6','0'),(3,6,3,'6','0');

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `user_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户编号',
  `user_name` varchar(40) NOT NULL COMMENT '用户名称',
  `user_type` tinyint(2) unsigned NOT NULL COMMENT '用户类型',
  `email` varchar(50) DEFAULT '' COMMENT '用户邮箱',
  `passwd` char(40) NOT NULL COMMENT '用户密码',
  `tel` varchar(20) DEFAULT NULL COMMENT '手机号',
  `sex` tinyint(1) unsigned DEFAULT NULL COMMENT '性别',
  `birth` date DEFAULT NULL COMMENT '生日',
  `nation` varchar(20) DEFAULT NULL COMMENT '民族',
  `card_id` varchar(50) DEFAULT NULL COMMENT '证件号码',
  `card_type` tinyint(2) unsigned DEFAULT NULL COMMENT '证件类型',
  `last_ip` varchar(20) NOT NULL DEFAULT '127.0.0.1' COMMENT '最后登录IP',
  `last_time` int(11) NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='用户主表';

/*Data for the table `users` */

insert  into `users`(`user_id`,`user_name`,`user_type`,`email`,`passwd`,`tel`,`sex`,`birth`,`nation`,`card_id`,`card_type`,`last_ip`,`last_time`,`create_time`) values (1,'admin',1,'admin@123.com','48ce7c53c65993cd85d0cb53eda698ad712e6556',NULL,NULL,NULL,NULL,NULL,NULL,'127.0.0.1',1455779204,0),(3,'牛逼的',6,'123@qq.com','48ce7c53c65993cd85d0cb53eda698ad712e6556',NULL,2,'2006-02-18','汉族','587545564',1,'127.0.0.1',1456190612,1455779038),(4,'逗币',3,'456@qq.com','3cff590668f67090f70dae0c8b338baca744920d',NULL,2,'2006-02-18','汉族','55874495',1,'127.0.0.1',1456105296,1455780000),(5,'是V',6,'2513308339@qq.com','61839941d560e0fde151c502cdcd25cb7fc29ad3',NULL,0,'0000-00-00','','2147483647',0,'127.0.0.1',1455785967,1455785967),(6,'方法',6,'710414018@qq.com','61839941d560e0fde151c502cdcd25cb7fc29ad3',NULL,0,'0000-00-00','','2147483647',0,'127.0.0.1',1455786037,1455786037);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

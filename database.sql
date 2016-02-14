-- --------------------------------------------------------
-- 主机:                           127.0.0.1
-- 服务器版本:                        5.7.9 - MySQL Community Server (GPL)
-- 服务器操作系统:                      Win64
-- HeidiSQL 版本:                  9.3.0.4984
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- 导出 tuanwei 的数据库结构
CREATE DATABASE IF NOT EXISTS `tuanwei` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `tuanwei`;


-- 导出  表 tuanwei.students 结构
CREATE TABLE IF NOT EXISTS `students` (
  `student_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '学生编号',
  `user_id` int(11) unsigned NOT NULL COMMENT '用户编号',
  `college` varchar(50) DEFAULT NULL COMMENT '学院',
  `major` int(5) unsigned DEFAULT NULL COMMENT '专业',
  `degree` int(2) unsigned DEFAULT NULL COMMENT '学历',
  `enrollment` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '入学时间',
  PRIMARY KEY (`student_id`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='学生表';

-- 正在导出表  tuanwei.students 的数据：~0 rows (大约)
/*!40000 ALTER TABLE `students` DISABLE KEYS */;
/*!40000 ALTER TABLE `students` ENABLE KEYS */;


-- 导出  表 tuanwei.teachers 结构
CREATE TABLE IF NOT EXISTS `teachers` (
  `teacher_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '教师编号',
  `user_id` int(11) unsigned NOT NULL COMMENT '用户ID',
  `union` varchar(50) DEFAULT NULL COMMENT '单位',
  PRIMARY KEY (`teacher_id`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='教师表';

-- 正在导出表  tuanwei.teachers 的数据：~0 rows (大约)
/*!40000 ALTER TABLE `teachers` DISABLE KEYS */;
/*!40000 ALTER TABLE `teachers` ENABLE KEYS */;


-- 导出  表 tuanwei.users 结构
CREATE TABLE IF NOT EXISTS `users` (
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='用户主表';

-- 正在导出表  tuanwei.users 的数据：~0 rows (大约)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`user_id`, `user_name`, `user_type`, `email`, `passwd`, `tel`, `sex`, `birth`, `nation`, `card_id`, `card_type`, `last_ip`, `last_time`, `create_time`) VALUES
	(1, 'admin', 1, '', '552007f7c13b8dcb3c67415ab338031cd4e2a36d', NULL, NULL, NULL, NULL, NULL, NULL, '127.0.0.1', 1455454195, 0);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

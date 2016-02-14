<?php
return array(
	//'配置项'=>'配置值'
    "allows_login"=>array(//允许后台登录的用户类型
        \Common\Model\UsersModel::TYPE_MANAGE,//管理员
        \Common\Model\UsersModel::TYPE_PERSONNEL,//工作人员
        \Common\Model\UsersModel::TYPE_TEACHER,//指导老师
        \Common\Model\UsersModel::TYPE_JUDGES,//评审专家
        \Common\Model\UsersModel::TYPE_INVESTMENT,//投资人
        \Common\Model\UsersModel::TYPE_STUDENT,//学生
    ),
);
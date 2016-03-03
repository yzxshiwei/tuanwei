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
    'MAIL_HOST' =>'smtp.163.com',//smtp服务器的名称
    'MAIL_SMTPAUTH' =>true, //启用smtp认证
    'MAIL_USERNAME' =>'15884572902@163.com',//你的邮箱名
    'MAIL_FROM' =>'15884572902@163.com',//发件人地址
    'MAIL_FROMNAME'=>'校团委',//发件人姓名
    'MAIL_PASSWORD' =>'yzx972479',//邮箱密码
    'MAIL_CHARSET' =>'utf-8',//设置邮件编码
    'MAIL_ISHTML' =>TRUE, // 是否HTML格式邮件
);
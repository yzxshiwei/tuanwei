<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/2/14
 * Time: 19:47
 */
namespace Common\Model;
/**
 * Class UsersModel 用户模型
 * @package Common\Model
 */
class UsersModel extends \Common\Helper\Model{

    const TYPE_MANAGE = 1;//管理员
    const TYPE_PERSONNEL = 2;//工作人员
    const TYPE_TEACHER = 3;//指导老师
    const TYPE_JUDGES = 4;//评审专家
    const TYPE_INVESTMENT = 5;//投资人
    const TYPE_STUDENT = 6;//学生

}
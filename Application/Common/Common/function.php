<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/2/7
 * Time: 13:26
 */
/**
 * @param $passwd   创建密码
 */
function create_password($passwd){
    $passwd = ~md5($passwd);
    return sha1($passwd);
}
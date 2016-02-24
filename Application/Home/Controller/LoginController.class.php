<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/2/7
 * Time: 13:36
 */
namespace Home\Controller;
/**
 * Class LoginController    登录控制器
 * @package Admin\Controller
 */
class LoginController extends \Common\Helper\Controller{
    public function __construct()
    {
        parent::__construct();
        if(!\Common\Helper\RunUser::newInstantiation()->isTourist() && IS_AJAX){
            return $this->ajaxReturn(array("status"=>0,"msg"=>"请不要重复登录"));
        }elseif(!\Common\Helper\RunUser::newInstantiation()->isTourist()){
            return $this->redirect("Index/Index");
        }
    }

    /**
     * 显示登录页面
     */
    public function index(){
        $this->display();
    }

    /**
     * 登录提交
     */
    public function login(){
        $user = trim(I("post.user"));
        $pwd = trim(I("post.pwd"));
        $jump = trim(I("get.jump"));
        $jump = $jump ? rawurldecode($jump) : U("Index/index");
        if(!$user || !$pwd){
            return $this->error("账号或密码不能为空",U("Login/index"));
        }
        $userModel = D("Users");
        $condition = array();
        $condition["email"] = $user;
        $condition["_logic"] = "OR";
        $data = $userModel->where($condition)->find();
        if(!$data || create_password($pwd) != $data["passwd"]){
            return $this->error("账号或密码错误",U("Login/index"));
        }
        \Common\Helper\RunUser::newInstantiation()->setUser($data["user_id"]);
        return $this->success("登录成功",U("Index/index"));
    }
}
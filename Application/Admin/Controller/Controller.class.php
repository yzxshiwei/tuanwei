<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/2/7
 * Time: 13:24
 */
namespace Admin\Controller;

/**
 * Class Controller 后台公共控制器
 * @package Admin
 */
class Controller extends \Common\Helper\Controller{
    public function __construct()
    {
        parent::__construct();
        $allows = C("allows_login");
        $info = \Common\Helper\RunUser::newInstantiation()->getInfo();
        $isAllowsLogin = $info && in_array(intval($info["user_type"]),$allows);
        if(!$isAllowsLogin && \Common\Helper\RunUser::newInstantiation()->isTourist() && IS_AJAX ){//游客AJAX
            return $this->ajaxReturn(array("status"=>0,"msg"=>"请登录后在操作"));
        }elseif(!$isAllowsLogin && \Common\Helper\RunUser::newInstantiation()->isTourist()){//游客访问页面
            return $this->redirect("Home/Login/Index",array("jump"=>rawurlencode(__SELF__)));
        }elseif(!$isAllowsLogin && IS_AJAX){//非法用户类型AJAX
            return $this->ajaxReturn(array("status"=>0,"msg"=>"当前用户不能进行后台操作"));
        }elseif(!$isAllowsLogin){//非法用户类型访问页面
            return $this->redirect("Home/Index/Index");
        }
    }
}
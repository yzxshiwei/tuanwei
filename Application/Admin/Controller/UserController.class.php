<?php
namespace Admin\Controller;
/**
 * 后台用户控制器
 * 添加时间10:42:08
 * 
 * @author yzx
 *
 */
class UserController extends Controller{
    public function logout() {
        \Common\Helper\RunUser::newInstantiation()->signOut();
        $this->success('退出成功',U('Home/Index/Index'));
    }
}
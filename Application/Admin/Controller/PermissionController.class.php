<?php
namespace Admin\Controller;
/**
 * 用户权限控制器
 * 添加时间2016-2-26
 * 
 * @author yzx
 *
 */
class PermissionController extends Controller{
    /**
     * 添加分组
     * 添加时间2016-2-26
     * 
     * @author yzx
     */
    public function addgroup() {
        $this->display();
    }
    /**
     * 分组列表
     * 添加时间2016-2-26
     * 
     * @author yzx
     */
    public function grouplist() {
        $this->display();
    }
    /**
     * 添加权限
     * 添加时间2016-2-26
     * 
     * @author yzx
     */
    public function addpermission() {
        $this->display();
    }
    /**
     * 用户添加分组
     * 添加时间2016-2-26
     * 
     * @author yzx
     */
    public function usergroup() {
        $user_id = I('user_id',0,'intval');
        $userModel = new \Common\Model\UsersModel();
        $result = $userModel->where(array('user_id'=>$user_id))->find();
        $this->assign('data',$result);
        $this->display();
    }
}
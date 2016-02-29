<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/2/14
 * Time: 19:40
 */
namespace Common\Helper;
/**
 * Class RunUser    当前用户
 * @package Common\Helper
 */
class RunUser{

    protected $_save_id = "__login_id__";

    protected function __construct()
    {
    }

    /**
     * 实例化
     * @return RunUser|null
     */
    public static function newInstantiation(){
        static $instantiation = null;
        if(is_null($instantiation)){
            $instantiation = new self();
        }
        return $instantiation;
    }

    /**
     * 取得用户编号
     * @return bool|mixed
     */
    protected function _getUserId(){
        $userId = session($this->_save_id);
        if(!$userId || !is_numeric($userId)){
            return false;
        }
        return $userId;
    }
    /**
     * 取得用户基本信息
     * @return bool|mixed
     */
    protected function _getBaseInfo(){
        $userId = $this->_getUserId();
        if(!$userId){
            return false;
        }
        $helper = new \Common\Helper\User();
        return $helper->getBaseInfo($userId);
    }

    /**
     * 是否是游客(未登录)
     * @return bool
     */
    public function isTourist(){
        if(!$this->_getUserId()){
            return true;
        }
        return false;
    }

    /**
     * 是否是管理员
     * @return bool
     */
    public function isManage(){
        $info = $this->_getBaseInfo();
        if(!$info || $info["user_type"] != \Common\Model\UsersModel::TYPE_MANAGE){
            return false;
        }
        return true;
    }

    /**
     * 是否是工作人员
     * @return bool
     */
    public function isPersonnel(){
        $info = $this->_getBaseInfo();
        if(!$info || $info["user_type"] != \Common\Model\UsersModel::TYPE_PERSONNEL){
            return false;
        }
        return true;
    }

    /**
     * 是否是指导老师
     * @return bool
     */
    public function isTeacher(){
        $info = $this->_getBaseInfo();
        if(!$info || $info["user_type"] != \Common\Model\UsersModel::TYPE_TEACHER){
            return false;
        }
        return true;
    }

    /**
     * 是否是评审专家
     * @return bool
     */
    public function isJudges(){
        $info = $this->_getBaseInfo();
        if(!$info || $info["user_type"] != \Common\Model\UsersModel::TYPE_JUDGES){
            return false;
        }
        return true;
    }

    /**
     * 是否是投资人
     * @return bool
     */
    public function isInvestment(){
        $info = $this->_getBaseInfo();
        if(!$info || $info["user_type"] != \Common\Model\UsersModel::TYPE_INVESTMENT){
            return false;
        }
        return true;
    }

    /**
     * 是否是学生
     * @return bool
     */
    public function isStudent(){
        $info = $this->_getBaseInfo();
        if(!$info || $info["user_type"] != \Common\Model\UsersModel::TYPE_STUDENT){
            return false;
        }
        return true;
    }

    /**
     * 取得信息
     * @return array|bool|mixed
     */
    public function getInfo(){
        $userId = $this->_getUserId();
        if(!$userId){
            return false;
        }
        $helper = new \Common\Helper\User();
        return $helper->getInfo($userId);
    }

    /**
     * 退出
     */
    public function signOut(){
        session($this->_save_id,null);
    }
    /**
     * 设置用户
     * @param $userid
     * @return bool
     */
    public function setUser($userid){
        $userModel = D("Users");
        $baseInfo = $userModel->find(array("user_id"=>$userid));
        if(!$baseInfo){
            return false;
        }
        session($this->_save_id,$userid);
        $userModel->where(array("user_id"=>$userid))->save(array("last_time"=>time(),"last_ip"=>get_client_ip()));
        return true;
    }
    /**
     * 获取用户权限
     * 添加时间2016-2-29
     * 
     * @author yzx
     * @return array
     */
    public function getAuthor() {
        $user_info = $this->getInfo();
        $PermissionModel = new \Common\Model\PermissionModel();
        if (!empty($user_info)) {
           $permission_info = $PermissionModel->where(array('group_id' => $user_info['group_id']))->select();
           return $permission_info;
        }
        return array();
    }
}
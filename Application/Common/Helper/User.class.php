<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/2/14
 * Time: 20:25
 */
namespace Common\Helper;

/**
 * Class User   用户助手
 * @package Common\Helper
 */
class User{

    /**
     * @param $usertype 取得用户附加模型
     * @return \Model|null|\Think\Model
     */
    protected function _getExModel($usertype){
        $tables = array(
            \Common\Model\UsersModel::TYPE_TEACHER => "teachers",//教师
            \Common\Model\UsersModel::TYPE_STUDENT => "students",//学生
        );
        if(!isset($tables[$usertype])){
            return null;
        }
        return D(ucwords($tables[$usertype]));
    }

    /**
     * 取得用户信息
     * @param $userid
     * @return array|bool|mixed
     */
    public function getInfo($userid){
        $info = $this->getBaseInfo($userid);
        if(!$info){
            return false;
        }
        $exModel = $this->_getExModel($info["user_type"]);
        if(!$exModel){
            return $info;
        }
        $exInfo = $exModel->where(array("user_id"=>$userid))->find();
        return array_merge($info,$exInfo?$exInfo:array());
    }

    /**
     * 取得用户基本信息
     * @param $userid
     * @return bool|mixed
     */
    public function getBaseInfo($userid){
        $userModel = D("Users");
        $info = $userModel->where(array("user_id"=>$userid))->find();
        return $info ? $info : false;
    }

    /**
     * 添加用户
     * @param array $data
     * @return bool|mixed
     */
    public function add(array $data){
        $necessary = array(//必须的字段
            "user_name",
            "user_type",
            "passwd"
        );
        if(count(array_intersect_key(array_fill_keys($necessary,null),$data)) < count($necessary)){
            return false;
        }

        $userModel = D("Users");
        $baseFields = $userModel->getDbFields();
        $baseFields = array_fill_keys(array_keys($baseFields),null);
        $baseValues = array_merge($baseFields,array_intersect_key($data,$baseFields));
        $baseValues = array_filter($baseValues,function($value){return $value !== null;});

        $userModel->startTrans();
        $userId = $userModel->add($baseValues);
        if(!$userId){
            $userModel->rollback();
            return false;
        }

        $exModel = $this->_getExModel($data["user_type"]);
        if(!$exModel){
            $userModel->commit();
            return $userId;
        }
        $exFields = $exModel->getDbFields();
        $exFields = array_fill_keys(array_keys($exFields),null);
        $exValues = array_merge($exFields,array_intersect_key($data,$exFields));
        $exValues = array_filter($exValues,function($value){return $value !== null;});
        if($exValues && $exModel->add($exValues)){
            $userModel->commit();
            return $userId;
        }
        $userModel->rollback();
        return false;
    }

    /**
     * 保存用户
     * @param $userid
     * @param array $data
     * @return bool
     */
    public function save($userid,array $data){
        $userModel = D("Users");
        $info = $this->getInfo($userid);
        if(!$info){
            return false;
        }

        $baseFields = $userModel->getDbFields();
        $baseFields = array_fill_keys(array_keys($baseFields),null);
        $baseValues = array_merge($baseFields,array_intersect_key($data,$baseFields));
        $baseValues = array_filter($baseValues,function($value){return $value !== null;});

        $userModel->startTrans();
        if($baseValues && !$userModel->where(array("user_id"=>$userid))->save($baseValues)){
            $userModel->rollback();
            return false;
        }

        $exModel = $this->_getExModel($info["user_type"]);
        if(!$exModel){
            $userModel->commit();
            return true;
        }
        $exFields = $exModel->getDbFields();
        $exFields = array_fill_keys(array_keys($exFields),null);
        $exValues = array_merge($exFields,array_intersect_key($data,$exFields));
        $exValues = array_filter($exValues,function($value){return $value !== null;});
        if($exValues && !$exModel->where(array("user_id"=>$userid))->save($exValues)){
            $userModel->rollback();
            return false;
        }
        $userModel->commit();
        return true;
    }

}
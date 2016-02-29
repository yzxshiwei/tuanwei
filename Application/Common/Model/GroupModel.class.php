<?php
namespace Common\Model;
class GroupModel extends \Common\Helper\Model{
    protected $tableName = 'group';
    /**
     * 删除分组
     * 添加时间2016-2-29
     * 
     * @author yzx
     * @param int $group_id
     * @return bool
     */
    public function deleteGroup($group_id){
        $this->startTrans();
        $group_res = $this->where(array('id' => $group_id))->delete();
        if (!$group_res) {
            $this->rollback();
            return false;
        }
        $userModel = new \Common\Model\UsersModel();
        $user_data = array();
        $user_data['group_id']  = 0;
        $user_res = $userModel->where(array('group_id' => $group_id))->save($user_data);
        if (!$user_res) {
            $this->rollback();
            return false;
        }
        $PermissionModel = new \Common\Model\PermissionModel();
        $cout = $PermissionModel->where(array('group_id' => $group_id))->count();
        if ($cout > 0){
            $permission_res = $PermissionModel->where(array('group_id' => $group_id))->save(array('group_id'=>0));
            if (!$permission_res) {
                $this->rollback();
                return false;
            }
        }
        $this->commit();
        return  true;
    }
    /**
     * 获取分组列表
     * 添加时间2016-2-29
     * 
     * @author yzx
     * @return Ambigous <mixed, boolean, string, NULL, multitype:, unknown, object>
     */
    public function listData() {
        $count = $this->count();
        $Page = new \Think\Page($count,10);
        $page_show = $Page->show();
        $result = $this->limit($Page->firstRow.','.$Page->listRows)->select();
        return array('Page' => $page_show, 'list_data' => $result);
    }
}
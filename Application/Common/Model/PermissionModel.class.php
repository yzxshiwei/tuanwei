<?php
namespace Common\Model;
class PermissionModel extends \Common\Helper\Model{
    protected $tableName = 'permission';
    /**
     * 添加权限
     * 添加时间2016-2-29
     * 
     * @author yzx
     * @param int $groupId
     * @param array $permission
     * @return bool
     */
    public function addData($groupId,$permission) {
        if (is_array($permission)&&!empty($permission) && $groupId>0){
            $delete_res = $this->where(array('group_id'=>$groupId))->delete();
            if (!$delete_res){
                return false;
            }
            $add_data = array();
            $add_data['group_id'] = $groupId;
            foreach ($permission as $key => $val){
                $add_data['permission'] = $val;
                $result = $this->add($add_data);
                if (!$result){
                    return false;
                }
            }
            return true;
        }
    }
}
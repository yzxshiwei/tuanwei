<?php
namespace Common\Model;
class TeamModel extends \Common\Helper\Model{
    protected $tableName = 'team';
    /**
     * 用户类型
     * @author yzx
     * @var 队员
     */
    const USER_TYPE_MEMBER = 'member';
    /**
     * 用户类型
     * @author yzx
     * @var 队长
     */
    const USER_TYPE_CAPTAIN = 'captain';
    /**
     * 添加时间2016-2-24
     * 
     * @author yzx
     * @var array
     */
    public $user_type = array(self::USER_TYPE_MEMBER => '队员',
                              self::USER_TYPE_CAPTAIN => '队长');
    /**
     * 删除团队
     * 添加时间2016-2-24
     * 
     * @author yzx
     * @param int $team_id
     * @return bool
     */
    public function deleteTeam($team_id){
        $one_tema = $this->where(array('id' => $team_id))->find();
        if (!is_array($one_tema) || empty($one_tema)) {
            return false;
        }
        return $this->where(array('project_id' => $one_tema['project_id']))->delete();
    }
}
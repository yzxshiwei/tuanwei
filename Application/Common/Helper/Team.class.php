<?php
namespace Common\Helper;
class Team{
    /**
     * 获取团队列表
     * 添加时间13:20:52
     * 
     * @author yzx
     * @param int $user_id
     * @return array
     */
    public function teamList($user_id) {
        $teamModel = new \Common\Model\TeamModel();
        $result = $teamModel->field("p.name,team.*")->join("project as p on(team.project_id=p.id)",'left')
                            ->where(array('user_id'=>$user_id))->select();
        return $result;
    }
}
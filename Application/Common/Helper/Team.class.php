<?php
namespace Common\Helper;
class Team{
    /**
     * 获取团队交流列表
     * 添加时间13:20:52
     * 
     * @author yzx
     * @param int $user_id
     * @return array
     */
    public function teamList($user_id) {
        $teamModel = new \Common\Model\TeamModel();
        $result = $teamModel->distinct(true)
                            ->field("p.name,team.*")->join("project as p on(team.project_id=p.id)",'left')
                            ->where(array('user_id'=>$user_id))->select();
        return $result;
    }
    /**
     * 获取团队列表
     * 添加时间2016-2-24
     *
     * @author yzx
     * @param int $user_id
     * @return array
     */
    public function listData($user_id) {
        $teamModel = new \Common\Model\TeamModel();
        $count = $teamModel->count();
        $Page = new \Think\Page($count);
        $Page_show = $Page->show();
        $result = $teamModel
        ->distinct(true)
        ->field('p.name,team.id,u.user_name,u.create_time,team.team_name')
        ->join('project as p on (team.project_id = p.id)','left')
        ->join("users as u on (team.user_id = u.user_id)",'left')
        ->where(array('team.user_id' => $user_id,'team.user_type' => $teamModel::USER_TYPE_CAPTAIN))
        ->limit($Page->firstRow.','.$Page->listRows)
        ->select();
        return array("Page" => $Page_show,"list_data" => $result);
    }
}
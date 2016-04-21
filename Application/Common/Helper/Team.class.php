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
                            ->field("p.name,p.id as p_id,team.*")->join("project as p on(team.id=p.team_id)",'left')
                            ->where(array('user_id'=>$user_id))
                            ->order('team.id')
                            ->select();
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
    public function listData($user_id=NULL) {
        $teamModel = new \Common\Model\TeamModel();
        $count = $teamModel
	        ->distinct(true)
	        ->field('p.name,team.id,u.user_name,team.create_time,team.team_name,team.user_type,team.project_id,team.tops')
	        ->join('project as p on (team.project_id = p.id)','left')
	        ->join("users as u on (team.user_id = u.user_id)",'left');
		if($user_id){
			$count = $count->where(array('team.user_id' => $user_id));
		}else{
			$count = $count->where(array('team.user_type' => \Common\Model\TeamModel::USER_TYPE_CAPTAIN));
		}
		$count = $count->count();
	    
        $Page = new \Think\Page($count,12);
        $Page_show = $Page->show();
        $result = $teamModel
        ->distinct(true)
        ->field('p.name,team.id,u.user_name,team.create_time,team.team_name,team.user_type,team.project_id,team.tops')
        ->join('project as p on (team.project_id = p.id)','left')
        ->join("users as u on (team.user_id = u.user_id)",'left');

		if($user_id){
			$result = $result->where(array('team.user_id' => $user_id));
		}else{
			$result = $result->where(array('team.user_type' => \Common\Model\TeamModel::USER_TYPE_CAPTAIN));
		}
        $result = $result->limit($Page->firstRow.','.$Page->listRows)->select();
        return array("Page" => $Page_show,"list_data" => $result);
    }
    
    /**
	 * 团队列表
	 */
	public function lists($user_id=NULL,$where=NULL){
		$teamModel = new \Common\Model\TeamModel();
		$field = 'id,create_time,team_name,user_type,leader_id,tops';
        $where["id"] = array("neq",0);
		if($user_id){
            $where['user_id'] = $user_id;
		}else{
            $where['user_type'] = \Common\Model\TeamModel::USER_TYPE_CAPTAIN;
			$teamModel = $teamModel->where($where)->group("leader_id");
		}        
        $count = $teamModel->field($field)->count();
	    
        $Page = new \Think\Page($count,12);
        $Page_show = $Page->show();

		if($user_id){
            $teamModel = $teamModel->where($where);
        }else{
            $teamModel = $teamModel->where($where)->group("leader_id");
        }
        $result = $teamModel->field($field)->limit($Page->firstRow.','.$Page->listRows)->select();

        return array("Page" => $Page_show,"list_data" => $result);
	}
	
	
	
	
	
	
	
}
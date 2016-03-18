<?php
namespace Admin\Controller;
/**
 * 后台团队控制器
 * 添加时间15:12:32
 * 
 * @author yzx
 *
 */
class TeamController extends Controller{
    /**
     * 团队交流
     * 添加时间15:14:14
     * 
     * @author yzx
     */
   public function teamdiscuss() {
       $team = new \Common\Helper\Team();
       $user_id = $this->user['user_id'];
       $list_data = $team->teamList($user_id); 
       $this->assign('list_data',$list_data);
       $this->display();
   }
   /**
    * 删除团队
    * 添加时间2016-2-24
    * 
    * @author yzx
    */
   public function delete() {
       $temModel = new \Common\Model\TeamModel();
	   $projectModel = new \Common\Model\ProjectModel();
	   $pjModel = new \Common\Model\Project_StatusModel();
	   $mpModel = new \Common\Model\Match_ProjectModel();
	   $tmModel = new \Common\Model\Teacher_TeamModel();
	   
       $team_id = I('post.team_id',0,'intval');
	   
	   $pid = $projectModel->where(array("team_id"=>$team_id))->field("id")->find();
	   
       $temModel->where(array("leader_id"=>$team_id))->delete();
	   $projectModel->where(array("team_id"=>$team_id))->delete();
	   $pjModel->where(array("project_id"=>$pid["id"]))->delete();
	   $mpModel->where(array("project_id"=>$pid["id"]))->delete();
	   $mpModel->where(array("project_id"=>$pid["id"]))->delete();

       $this->ajaxReturn(array('status' => 1));
   }
}
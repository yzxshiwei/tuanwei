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
       $team_id = I('post.team_id',0,'intval');
       $result = $temModel->where(array("leader_id"=>$team_id))->delete();
       if ($result){
           $this->ajaxReturn(array('status' => 1));
       }else {
           $this->ajaxReturn(array('status' => 0));
       }
   }
}
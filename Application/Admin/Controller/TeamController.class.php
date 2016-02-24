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
   
}
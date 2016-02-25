<?php
namespace Admin\Controller;
class ProjectController extends Controller{
    /**
     * 项目管理
     * 添加时间14:53:48
     *
     * @author yzx
     */
    public function projectmanage() {
		
        $project = new \Common\Helper\Project();
		$userModel = D('users');
		$teacTeamModel = M("teacher_team");
		
		if(IS_POST){

			$file_res = uploadFile('project_file');
            if (!$file_res['status']){
               $this->error($file_res['msg']);
            }else {

               $posjectModel = M("project");
			   //开启事务
			   $posjectModel->startTrans();
			   
               $post_data = array();
               $post_data['name'] = I('post.name','','string');
               $post_data['sub_title'] = I('post.sub_title','','string');
               $post_data['file_url'] = $file_res['file_path'];
               $post_data['intro'] = I('post.intro','','string');
			   $pid = I("post.pid",'','string');
			   
			   $result = $posjectModel->where(array("id"=>$pid))->save($post_data);  
			   if ($result){
				   //先此项目的团队删除 再添加
				   $userid = I("post.userid");
				   $team = new \Common\Model\TeamModel;

				   $team->where(array("project_id"=>$pid))->delete();
				   $res = $team->addTeam($pid,$userid,$this->user["user_id"],FALSE);
				   
				   //将项目老师 删除 再添加
				   $teach_id = I('post.teacher_id');
				   $teachModel = new \Common\Model\Teacher_TeamModel;
				   
				   $teachModel->where(array("project_id"=>$pid,"team_type"=>1))->delete();
				   $return = $teachModel->addTeam($teach_id,$pid,1);
				   
				   if($res && $return){
				   	  $posjectModel->commit();
				   	  $this->success('更新项目成功',U('Project/projectmanage'));
				   }else{
				   	  $posjectModel->rollback();
				   	  $this->error('更新项目失败');
				   }
               }else {
               	   $posjectModel->rollback();
                   $this->error('更新项目失败');
               }
		    }
		}else{
			
			$result = $project->listData();
	        $user_data = $userModel->where(array('user_type' => \Common\Model\UsersModel::TYPE_STUDENT))->select();
			$teacher_list = $userModel->where(array('user_type'=>\Common\Model\UsersModel::TYPE_TEACHER))->select();

			//拼 老师 姓名与id
			foreach($result['list_data'] as $_k=>$v){

				$team = $teacTeamModel->join("users on users.user_id=teacher_team.user_id")->where(array("teacher_team.project_id"=>13))->field("users.user_id,users.user_name")->select();
				
				foreach($team as $_v){
					$result['list_data'][$_k]['u_name'] .= $_v["user_name"].",";
				}
				$result['list_data'][$_k]['teac_info'] = $team;
			}

			$this->assign("teacher_list",$teacher_list);
	        $this->assign('Page' , $result['Page']);
	        $this->assign('list_data',$result['list_data']);
			$this->assign('user_data',$user_data);
			$this->assign("userid",$this->user["user_id"]);
	        $this->display();
		}
    }
    /**
     * 作品评审
     * 添加时间15:50:59
     * 
     * @author yzx
     */
    public function workreview() {
        if (IS_POST){
            $input_data = array();
            $input_data['project_id'] = I('post.id',0,'intval');
            $input_data['officer'] = $this->user['user_id'];
            $input_data['content'] = I('post.content','','string');
            $input_data['score'] = I('post.score','','intval');
            $input_data['created_time'] = time();
            $projectStatus = D('project_status');
            $result = $projectStatus->add($input_data);
            if ($result){
                $this->success('评审成功',U('Project/projectmanage'));
            }else {
                $this->error('评审失败');
            }
        }else {
            $id = I('id',0,'intval');
            $project = new \Common\Helper\Project();
            $result = $project->getData($id);
            $this->assign('result',$result);
            $this->assign('id',$id);
            $this->display();
        }
    }
     
	/**
     * 创建项目
     * 添加时间15:34:58
     * 
     * @author yzx
     */
    public function createproject() {

        if (IS_POST){
        	
           $file_res = uploadFile('project_file');
           if (!$file_res['status']){
               $this->error($file_res['msg']);
           }else {
               $posjectModel = new \Common\Model\ProjectModel();
			   
			   //开启事务
			   $posjectModel->startTrans();
			   
               $post_data = array();
               $post_data['name'] = I('post.name','','string');
               $post_data['sub_title'] = I('post.sub_title','','string');
               $post_data['file_url'] = $file_res['file_path'];
               $post_data['intro'] = I('post.intro','','string');
			   
			   
               $result = $posjectModel->addPorject($post_data);
               if ($result){
               	   
				   //添加项目团队信息(成员)
				   $userid = I("post.userid");
				   $team = new \Common\Model\TeamModel;
				   $res = $team->addTeam($result,$userid,$this->user["user_id"],true);
				   
				   //添加项目团队 老师信息
				   $teach_id = I('post.teacher_id');
				   $teachModel = new \Common\Model\Teacher_TeamModel;
				   $return = $teachModel->addTeam($teach_id,$result,1);
				   
				   if($res && $return){
				   	  $posjectModel->commit();
				   	  $this->success('创建项目成功',U('Project/projectmanage'));
				   }else{
				   	  $posjectModel->rollback();
					  
				   	  $this->error('创建项目失败');
				   }
				   
               }else {
               	   $posjectModel->rollback();
                   $this->error('创建项目失败2');
               }
			   
           }
        }else {
            $userModel = D('users');
            $list_data = $userModel->where(array('user_type' => \Common\Model\UsersModel::TYPE_STUDENT))->select();
			
			$teacher_list = $userModel->where(array('user_type'=>\Common\Model\UsersModel::TYPE_TEACHER))->select();

			$this->assign("teacher_list",$teacher_list);
            $this->assign("list_data",$list_data);
			$this->assign("userid",$this->user["user_id"]);
            $this->display();
        }
    }

    /**
	 * 查询项目团队成员
	 * 添加时间 2016-2-24
	 * 
	 * @param pid int 项目id
	 * @author zlj
	 * @return array
	 */
	 public function find_user(){
	 	if(IS_AJAX){
	 		
	 		$pid = I("post.pid","","string");
			$team = M("team");

			$field = "team.user_type,users.user_id,users.user_name,students.college,students.stu_card";
			$user_list = $team->join("students on students.user_id=team.user_id")->join("users on users.user_id=team.user_id")->where(array("team.project_id"=>$pid))->field($field)->select();

            echo json_encode($user_list);
	 	}
	 }
}
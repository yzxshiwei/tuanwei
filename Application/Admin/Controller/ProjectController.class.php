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

			$posjectModel = M("project");
			
			$pid = I("post.pid",'','string');
		    //开启事务
		    $posjectModel->startTrans();
		   
            $post_data = array();

			$file_res = uploadFile('project_file');
            if ($file_res['status']){
               $post_data['file_url'] = $file_res['file_path'];
				
			   //删除原先的图片
			   $file_url = $posjectModel->where(array("id"=>$pid))->field("file_url")->find();
			   delfile($file_url["file_url"]);
            }
			
            $post_data['name'] = I('post.name','','string');
            $post_data['sub_title'] = I('post.sub_title','','string');
            $post_data['intro'] = I('post.intro','','string');

		    $result = $posjectModel->where(array("id"=>$pid))->save($post_data);

		    //先此项目的团队删除 再添加
		    $userid = I("post.userid");
	    	$team = new \Common\Model\TeamModel;
            unset($userid[array_search($this->user["user_id"],$userid)]);
			
		    $team->where(array("project_id"=>$pid,"state"=>array("neq",\Common\Model\TeamModel::STATE_PASS)))->delete();
			
			
		    $res = $team->addTeam($pid,$userid,$this->user["user_id"],FALSE);
		   
		    //将项目老师 删除 再添加
		    $teach_id = I('post.teacher_id');
		    $teachModel = new \Common\Model\Teacher_TeamModel;
		   
		    $teachModel->where(array("project_id"=>$pid,"teacher_type"=>1))->delete();
		    $return = $teachModel->addTeam($teach_id,$pid,1);
		   
		    if($res || $return || $result){
		   	   $posjectModel->commit();
		   	   $this->success('更新项目成功',U('Project/projectmanage'));
		    }else{
		   	   $posjectModel->rollback();
		   	   $this->error('更新项目失败');
			}
		}else{
			
			$result = $project->listData($this->user);
	        $user_data = $userModel->where(array('user_type' => \Common\Model\UsersModel::TYPE_STUDENT,"state"=>1))->select();
			$teacher_list = $userModel->where(array('user_type'=>\Common\Model\UsersModel::TYPE_TEACHER,"state"=>1))->field("user_id,user_name")->select();

			//拼 老师 姓名与id
			foreach($result['list_data'] as $_k=>$v){
				$team = $teacTeamModel->join("users on users.user_id=teacher_team.user_id")->where(array("teacher_team.project_id"=>$v['id']))->field("users.user_id,users.user_name")->select();
                $datas = array();
				foreach($team as $_v){
					$result['list_data'][$_k]['u_name'] .= $_v["user_name"].",";
					$datas[$_v["user_id"]] = $_v["user_id"];
				}
				$result['list_data'][$_k]['teac_info'] = $datas;
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
            $project_id = I('post.id',0,'intval');
            $input_data['project_id'] = $project_id;
            $input_data['officer'] = $this->user['user_id'];
            $input_data['content'] = I('post.content','','string');
            $input_data['score'] = I('post.score','','intval');
            $input_data['created_time'] = time();
            $projectStatus = D('project_status');
            $old_data = $projectStatus->where(array('project_id' => $project_id))->find();
            if (!empty($old_data)){
                $result = $projectStatus->where(array('project_id' => $project_id))->save($input_data);
            }else {
                $result = $projectStatus->add($input_data);
            }
            if ($result){
                $this->success('评审成功',U('Project/projectmanage'));
            }else {
                $this->error('评审失败');
            }
        }else {
            $id = I('id',0,'intval');
            $project = new \Common\Helper\Project();
            $projectStatusModel = new \Common\Model\Project_StatusModel();
            $status_data = $projectStatusModel->where(array('project_id' => $id))->find();
            $result = $project->getData($id);
			
			$flag = FALSE; 
			if($this->user['user_type']==\Common\Model\UsersModel::TYPE_TEACHER){
				$flag = TRUE;
			}

			$teamModel = new \Common\Model\TeamModel;
			$field = "users.user_id,users.user_name,students.stu_card,students.college";
			$userList = $teamModel->join("users on users.user_id=team.user_id")->join("students on students.user_id=users.user_id")->where(array("project_id"=>$id))->field($field)->select();

            $this->assign("flag",$flag);
			$this->assign('staus_data' , $status_data);
            $this->assign('ulist',$userList);
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
            $teach_id = I('post.teacher_id');
			$posjectModel = new \Common\Model\ProjectModel();
			//开启事务
			$posjectModel->startTrans();
			
		   	if($_FILES['project_file']['tmp_name']){
				$file_res1 = Upload($_FILES['project_file']);
				if($file_res1["status"]){
					$data["file_url"] = $file_res1['file_path'];
				}else{
					$this->error($file_res1['msg']);
				}
			}

           $post_data = array();
           $post_data['name'] = I('post.name','','string');
           $post_data['sub_title'] = I('post.sub_title','','string');
           $post_data['intro'] = I('post.intro','','string');
		   $post_data['creat_id'] = $this->user['user_id'];
		   
           $result = $posjectModel->addPorject($post_data);
           if ($result){
           	   
			   //添加项目团队信息(成员)
			   $userid = I("post.userid");
			   $userid = array_unique($userid);
			   $team = new \Common\Model\TeamModel;
			   $res = $team->addTeam($result,$userid,$this->user["user_id"],true);
			   
			   //添加项目团队 老师信息
			   $teach_id = I('post.teacher_id');
			   $teach_id = array_unique($teach_id);
			   $teachModel = new \Common\Model\Teacher_TeamModel;
			   $return = $teachModel->addTeam($teach_id,$result,1);
			   if($res && $return){
			   	  $posjectModel->commit();
			   	  //发送消息提示
			   	  $messageModel = new \Common\Model\MessageModel();
			   	  //发送邀请指导消息
			   	  $messageModel->sendMsg($teach_id, $this->user['user_id'], $messageModel::TYPE_TEACHER_PROJECT, '你有项目指导邀请',$result);
			   	  //发送邀请学生消息(此方法再Messagemodel里面)
			   	  
			   	  $this->success('创建项目成功',U('Project/projectmanage'));
			   }else{
			   	  $posjectModel->rollback();
				  
			   	  $this->error('创建项目失败');
			   }
			   
           }else {
           	   $posjectModel->rollback();
               $this->error('创建项目失败');
           }
			   
        }else {
            $userModel = D('users');
            $list_data = $userModel->where(array('user_type' => \Common\Model\UsersModel::TYPE_STUDENT,"state"=>1))->field("user_id,user_name")->select();
			
			$teacher_list = $userModel->where(array('user_type'=>\Common\Model\UsersModel::TYPE_TEACHER,"state"=>1))->field("user_id,user_name")->select();

			$this->assign("teacher_list",$teacher_list);
            $this->assign("list_data",$list_data);
			$this->assign("userid",$this->user["user_id"]);
            $this->display();
        }
    }
    /**
     * 修改项目比赛状态
     * 添加时间2016-3-2
     * 
     * @author yzx
     */
    public function modifystatus(){
        $id = I('post.id',0,'intval');
        $status = I('post.status',0,'intval');
        $projectStatusModel = new \Common\Model\Project_StatusModel();
        $status_data = $projectStatusModel->where(array('project_id' => $id ))->find();
        if (empty($status_data)){
            $this->ajaxReturn(array('sattus' => 0,'msg' => '没有该项目'));
        }
        if (!in_array($status, array_keys($projectStatusModel->result))){
            $this->ajaxReturn(array('sattus' => 0,'msg' => '状态不对'));
        }
        $save_data['result'] = $status;
        $result = $projectStatusModel->where(array('project_id' => $id ))->save($save_data);
        
        $student_id = M("team")->where(array("project_id"=>$id,"user_type"=>\Common\Model\TeamModel::USER_TYPE_CAPTAIN))->field("user_id")->find();
		$pro_name = M("project")->where(array("id"=>$id))->field("name")->find();
		$messageModel = new \Common\Model\MessageModel();
		
		if($status=='1'){
			$mess = "项目".$pro_name["name"]."已通过比赛";
		}elseif($status=='0'){
			$mess = "项目".$pro_name["name"]."暂未通过比赛";
		}
		
        if($result){
           $messageModel->sendMsg($student_id, $this->user['user_id'], $messageModel::TYPE_USER, $mess,$id);
           $this->ajaxReturn(array('status' => 1,'msg'=>'修改成功')); 
        }else {
           $this->ajaxReturn(array('sattus' => 0,'msg' => '修改失败'));
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
            $where["team.project_id"] = $pid;
			$where["team.state"] = \Common\Model\TeamModel::STATE_PASS;
			$field = "team.user_type,users.user_id,users.user_name,students.college,students.stu_card";
			$user_list = $team->join("students on students.user_id=team.user_id")->join("users on users.user_id=team.user_id")->where($where)->field($field)->select();

            echo json_encode($user_list);
	 	}
	 }
	 
	 
	/**
	 * 项目下载
	 * 
	 * @author zlj
	 */
	public function projectDown(){
       if(IS_GET){
       	
       	    $pid = I("get.id","","string");
			$project = new \Common\Helper\Project();
            $result = $project->getData($id);
            
			downloads($result["file_url"]);
       }
	}
	/**
	 * 公开和关闭公开项目
	 * 添加时间2016-3-3
	 * 
	 */
	public function isopen(){
	    $proid = I('post.proid',0,'intval');
	    $projectModel = new \Common\Model\ProjectModel();
	    $result = $projectModel->isOpen($proid);
	    if($result['status']){
	        $this->ajaxReturn(array('status' => 1,'msg' => $result['msg']));
	    }else {
	        $this->ajaxReturn(array('status' => 0,'msg' => $result['msg']));
	    }
	}
	
	
	
	
	
}
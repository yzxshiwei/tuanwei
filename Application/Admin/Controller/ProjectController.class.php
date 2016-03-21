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
		$result = $project->lists($this->user);

        $this->assign('Page' , $result['Page']);
        $this->assign('list_data',$result["list_data"]);
        $this->display();
		
    }

   /**
    * 项目修改
    * 添加时间 2016-03-17
    * @author zlj
    */
    public function editproject(){
    	$projectModel = new \Common\Model\ProjectModel;
		$teamModel = new \Common\Model\TeamModel;
		$userModel = new \Common\Model\UsersModel;
		$teachModel = new \Common\Model\Teacher_TeamModel;
		
    	if(IS_POST){

			$pid = I("post.pid",'','string');
		    //开启事务
		    $projectModel->startTrans();
		   
            $post_data = array();
			$file_res = uploadFile('project_file');
            if ($file_res['status']){
               $post_data['file_url'] = $file_res['file_path'];
			   //删除原先的图片
			   $file_url = $projectModel->where(array("id"=>$pid))->field("file_url")->find();
			   delfile($file_url["file_url"]);
            }
			
            $post_data['name'] = I('post.name','','string');
            $post_data['sub_title'] = I('post.sub_title','','string');
            $post_data['intro'] = I('post.intro','','string');
			$post_data['team_id'] = I('post.tid','','string');
		    $res = $projectModel->where(array("id"=>$pid))->save($post_data);
		   
		    //将项目老师 删除 再添加
		    $teach_id = I('post.teacher_id');
			
			$tlist = $teachModel->where(array("project_id"=>$pid,"teacher_type"=>1))->field("user_id")->select();
			foreach($tlist as $_k=>$_vs){
				$tlist[$_k] = $_vs["user_id"];
			}
			//取差集
			$teachid1 = array_diff($teach_id,$tlist);
			$teachid2 = array_diff($tlist,$teach_id);
			$result = TRUE;
			if($teachid1){
				$return = $teachModel->addTeam($teachid1,$pid,1);
				//发送消息提示,发送邀请指导消息
	   	        $messageModel = new \Common\Model\MessageModel();
	   	        $messageModel->sendMsg($teachid1, $this->user['user_id'], $messageModel::TYPE_TEACHER_PROJECT, '你有项目指导邀请',$pid);
			}
			
			foreach($teachid2 as $_t){
				$teachModel->where(array("project_id"=>$pid,"teacher_type"=>1,"user_id"=>$_t))->delete();
			}

		    if($res || $result){
		   	   $projectModel->commit();
		   	   $this->success('更新项目成功',U('Project/projectmanage'));
		    }else{
		   	   $projectModel->rollback();
		   	   $this->error('更新项目失败');
			}
    	}else{
            $id = I('id',0,'intval');
            $pinfo = $projectModel->where(array("id"=>$id))->find();
            $tlist = $teamModel->where(array("user_id"=>$pinfo["creat_id"],"user_type"=>\Common\Model\TeamModel::USER_TYPE_CAPTAIN))->select();
            $teachlist = $userModel->where(array('user_type'=>$userModel::TYPE_TEACHER,"state"=>1))->field("user_id,user_name")->select();
			$zlist = $teachModel->alias('t')->join("users AS u ON (u.user_id=t.user_id)","left")->where(array("project_id"=>$id))->field("u.user_id,u.user_name")->select();
            
			$uname = "";
			$arr = array();
			foreach($zlist as $_v){
				$arr[$_v['user_id']]["user_id"] = $_v["user_id"];
				$arr[$_v['user_id']]["user_name"] = $_v["user_name"];
				$uname .= $_v["user_name"].","; 
			}

            $this->assign('uname',$uname);
            $this->assign('zlist',$arr);
			$this->assign('pinfo',$pinfo);
			$this->assign('teacher_list',$teachlist);
            $this->assign('tlist',$tlist);
            $this->assign('id',$id);
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
        	$userid = $this->user["user_id"];
			$user_type = $this->user["user_type"];
            $id = I('id',0,'intval');
            $project = new \Common\Helper\Project();
            $projectStatusModel = new \Common\Model\Project_StatusModel();
            $status_data = $projectStatusModel->where(array('project_id' => $id))->find();
            $result = $project->getData($id);

			$res = M("judges")->alias('j')->join("match_project AS mp ON (j.project_id=mp.match_id)","left")->where(array("j.judge_id"=>$userid,"mp.project_id"=>$id))->find();
			
			$flag = FALSE; 
			if($user_type==\Common\Model\UsersModel::TYPE_JUDGES && $res){
				$flag = TRUE;
			}

			$teamModel = new \Common\Model\TeamModel;
			$field = "users.user_id,users.user_name,students.stu_card,students.college,users.img_url";
			$userList = $teamModel->join("users on users.user_id=team.user_id")->join("students on students.user_id=users.user_id")->where(array("leader_id"=>$result["team_id"]))->field($field)->select();

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
            $post_data = array();
            $post_data['name'] = I('post.name','','string');
            $post_data['sub_title'] = I('post.sub_title','','string');
            $post_data['intro'] = I('post.intro','','string');
		    $post_data['creat_id'] = $this->user['user_id'];
		    $post_data['team_id'] = I('post.tid','','string');
		    if(!$post_data['team_id']){
		    	$this->error("请选团队,若无请先创建团队");
		    }
		   	if($_FILES['project_file']['tmp_name']){
				$file_res1 = Upload($_FILES['project_file']);
				if($file_res1["status"]){
					$post_data["file_url"] = $file_res1['file_path'];
				}else{
					$this->error($file_res1['msg']);
				}
			}
           $result = $posjectModel->addPorject($post_data);
           if ($result){
			   //添加项目团队 老师信息
			   $teach_id = I('post.teacher_id');
			   $teach_id = array_unique($teach_id);
			   $teachModel = new \Common\Model\Teacher_TeamModel;
			   $return = $teachModel->addTeam($teach_id,$result,1);
			   if($return){
			   	  $posjectModel->commit();
			   	  //发送消息提示、邀请指导消息
			   	  $messageModel = new \Common\Model\MessageModel();
			   	  $messageModel->sendMsg($teach_id, $this->user['user_id'], $messageModel::TYPE_TEACHER_PROJECT, '你有项目指导邀请',$result);
			   	  
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
			$teamModel = M('team');
			$userid = $this->user["user_id"];
			$teacher_list = $userModel->where(array('user_type'=>\Common\Model\UsersModel::TYPE_TEACHER,"state"=>1))->field("user_id,user_name")->select();
            $teamList = $teamModel->where(array("user_id"=>$userid,"user_type"=>\Common\Model\TeamModel::USER_TYPE_CAPTAIN))->field("id,team_name")->select();
			$this->assign("tlist",$teamList);
			$this->assign("teacher_list",$teacher_list);
			$this->assign("userid",$userid);
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
		$pid = M("project")->where(array("id"=>$id))->field("team_id")->find();
        $student_id = M("team")->where(array("id"=>$pid["team_id"]))->field("user_id")->find();

		$pro_name = M("project")->where(array("id"=>$id))->field("name")->find();
		$messageModel = new \Common\Model\MessageModel();

		if($status=='1'){
			$mess = "项目".$pro_name["name"]."已通过比赛";
		}elseif($status=='0'){
			$mess = "项目".$pro_name["name"]."暂未通过比赛";
		}
		
        if($result){
           $messageModel->sendMsg($student_id["user_id"], $this->user['user_id'], $messageModel::TYPE_USER, $mess,$id);
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
			$field = "team.user_type,users.user_id,users.user_name,students.college,students.stu_card,users.img_url";
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
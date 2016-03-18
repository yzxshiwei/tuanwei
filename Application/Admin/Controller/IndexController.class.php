<?php
namespace Admin\Controller;
/**
 * 后台控制器
 * 添加时间10:53:18
 * 
 * @author yzx
 *
 */
class IndexController extends \Admin\Controller\Controller {
    /**
     * 登录消息提示页面
     * 添加时间2016-2-17
     * 
     * @author yzx
     */
    public function index(){
        $message = new \Common\Helper\Message();
        $msg_list = $message->listData($this->user);
        $this->assign('list_data',$msg_list);
        $this->display();
    }
    /**
     * 人员管理
     * 添加时间2016-2-17
     * 
     * @author yzx
     */
    public function usermanage() {
        $user = new \Common\Helper\User();
        $result = $user->getUserList();
        $this->assign('Page' , $result['Page']);
        $this->assign('list_data' ,$result['list_data']);
        $this->display();
    }
	
	/**
	 * 添加团队
	 */
	public function addteam(){
		if(IS_POST){
			$teamModel = new \Common\Model\TeamModel;
			$post_data = array();
			//开启事务
			$teamModel->startTrans();

		   	if($_FILES['selectFiles']['tmp_name']){
				$file_res1 = Upload($_FILES['selectFiles']);
				if($file_res1["status"]){
					$post_data["img_url"] = $file_res1['file_path'];
				}else{
					$this->error($file_res1['msg']);
				}
			}
			
		    $post_data['team_name'] = I('post.name','','string');
            $post_data['contents'] = I('post.intro','','string');
			$post_data['user_id'] = $this->user["user_id"];
			$post_data['user_type'] = \Common\Model\TeamModel::USER_TYPE_CAPTAIN;
			$post_data['state'] = \Common\Model\TeamModel::STATE_PASS;
			
			//先添加队长
			$res = $teamModel->data($post_data)->add();
			if($res){
				$teamModel->where(array("id"=>$res))->save(array("leader_id"=>$res));
				$userid = I("post.userid");
				$userid = array_unique($userid);
				$result = $teamModel->teamAdd($userid,$res,$this->user["user_name"]);
				if($result){
					$teamModel->commit();
			   	    $this->success('创建团队成功',U('Index/teammanage'));
				}else{
					$teamModel->rollback();
				    $this->error('创建团队失败');
				}
			}else{
				$teamModel->rollback();
			   	$this->error('创建团队失败');
			}
		}else{
			$userModel = D('users');
			$list_data = $userModel->where(array('user_type' => \Common\Model\UsersModel::TYPE_STUDENT,"state"=>1))->field("user_id,user_name")->select();
			$this->assign("list_data",$list_data);
			$this->assign("userid",$this->user["user_id"]);
			$this->display();
		}
	}
	
	/**
	 * 团队管理
	 * 添加时间11:01:20
	 * 
	 * @author yzx
	 */
	public function teammanage() {
	    $team = new \Common\Helper\Team();
		$teamModel = new \Common\Model\TeamModel();
		$userModel = new \Common\Model\UsersModel();
		$uid = $this->user['user_id'];
		$user_type = $this->user['user_type'];
		
		$flag = TRUE;	
		if($user_type == $userModel::TYPE_TEACHER || $user_type == $userModel::TYPE_JUDGES){
			$flag = FALSE;
		}elseif($user_type == $userModel::TYPE_MANAGE){
			//若是管理员 查所有团队
			$uid = NULL;
		}

	    $result = $team->lists($uid);
		$utype = \Common\Model\TeamModel::USER_TYPE_CAPTAIN;

        foreach($result['list_data'] as $_k=>$_v){
			$where["team.user_type"] = $utype;
			$where["team.leader_id"] = $_v["leader_id"];
        	$name = $teamModel->join("users as u on (team.user_id = u.user_id)",'left')->field("u.user_name,team.team_name")->where($where)->find();
			$result['list_data'][$_k]["user_name"] = $name["user_name"];
			$result['list_data'][$_k]["team_name"] = $name["team_name"];
        }


        $this->assign('flag',$flag);
        $this->assign('top',$uid);
		$this->assign('utype',$utype);
	    $this->assign('list_data',$result['list_data']);
	    $this->assign('Page', $result['Page']);
		$this->display();
	}
	
	/**
	 * 团队编辑
	 * 添加时间2016-02-29
	 * @author zlj
	 */
	public function updateteam(){
		
		$team = new \Common\Model\TeamModel();
		
		if(IS_POST){

			$team->startTrans();
			$data = array();
			$data["contents"] = I("post.intro","","string");
			$data["team_name"] = I("post.name","","string");
			$id = I("post.id","","string");
			
			//开启事务
			if($_FILES['selectFiles']['tmp_name']){
				$file_res = Upload($_FILES['selectFiles']);
				if($file_res["status"]){
					$data["img_url"] = $file_res['file_path'];
					//删除原先的图片
					$img_url = $team->where(array("id"=>$id))->field("img_url")->find();
					delfile($img_url["img_url"]);
				}else{
					$this->error($file_res['msg']);
				}
			}
			
			$res = $team->where(array("id"=>$id))->save($data);

	    	//修改团队学员
	    	$userid = I("post.userid");
			$userid = array_unique($userid);
			$team->where(array("leader_id"=>$id,"state"=>array("neq"=>\Common\Model\TeamModel::STATE_PASS)))->delete();
			$result = $team->teamAdd($userid,$id,$this->user["user_name"]);
			if($result || $res){
				$team->commit();
		    	$this->success('更新团队成功',U('Index/teammanage'));
			}else{
	   	        $team->rollback();
	   	        $this->error('更新团队失败');
			}
			
		}else{
			$tid = I("get.tid","","string");
			$tinfo = $team->where(array("id"=>$tid))->find();

            $userModel = D('users');
			$list_data = $userModel->where(array('user_type' => \Common\Model\UsersModel::TYPE_STUDENT,"state"=>1))->field("user_id,user_name")->select();
			$where = array();
			$where["leader_id"] = $tid;
			$where["user_type"] = array("neq",\Common\Model\TeamModel::USER_TYPE_CAPTAIN);
			$ulist = $team->where($where)->select();

            $uarray = array();
            foreach($ulist as $_k=>$_v){
            	$field = "users.user_id,users.user_name,users.img_url,s.stu_card,s.college,s.major";
            	$uinfo = $userModel->join("students as s on (s.user_id = users.user_id)",'left')->where(array("users.user_id"=>$_v["user_id"]))->field($field)->find();
				
				$uarray[$uinfo["user_id"]]["user_id"] = $uinfo["user_id"];
				$uarray[$uinfo["user_id"]]["user_name"] = $uinfo["user_name"];
				$uarray[$uinfo["user_id"]]["img_url"] = $uinfo["img_url"];
				$uarray[$uinfo["user_id"]]["stu_card"] = $uinfo["stu_card"];
				$uarray[$uinfo["user_id"]]["college"] = $uinfo["college"];
				$uarray[$uinfo["user_id"]]["major"] = $uinfo["major"];
            }

            $this->assign("tid",$tid);
            $this->assign("ulist",$uarray);
			$this->assign("list_data",$list_data);
			$this->assign("tinfo",$tinfo);
			$this->assign("userid",$this->user["user_id"]);
			$this->display();
		}
	}
	
	/**
	 * 个人信息
	 * 添加时间15:27:05
	 * 
	 * @author yzx
	 */
	public function person() {
		 if (IS_POST){
		 	$userModel = new \Common\Model\UsersModel();

		 	//开启事务
			$userModel->startTrans();
			$post_data = array();
			
	        $old_pwd = I('post.old_pwd',"",'string');
	        $new_pwd = I("post.new_pwd","",'string');
	        $post_data['user_name'] = I('post.user_name',"",'string');
	        $post_data['tel'] = I('post.tel',"",'string');
	        $post_data['birth'] = I('post.birth',"",'string');
	        $post_data['nation'] = I('post.nation',"",'string');
	        $post_data['card_id'] = I('post.card_id',"",'string');
	        if ($new_pwd != null){
	            $old_pwd = create_password($old_pwd);
	            if ($this->user['passwd'] != $old_pwd){
	                $this->error('原始密码错误');
	            }else {
	                $post_data['passwd'] = create_password($new_pwd);
	            }
	        }
			
			if($post_data['card_id'] != NULL){
				$cardid = $userModel->where(array('card_id' => $post_data['card_id'],"user_id"=>array('neq'=>$this->user['user_id'])))->find();
				if($cardid){
					$this->error("此证件号已被注册");
				}
			}

			if($_FILES['selectFiles']['tmp_name']){
				$file_res1 = Upload($_FILES['selectFiles']);
				if($file_res1["status"]){
					$post_data["img_url"] = $file_res1['file_path'];

					//删除原先的图片
			       $file_url = $userModel->where(array("user_id"=>$this->user['user_id']))->field("img_url")->find();
			       delfile($file_url["img_url"]);
				}else{
					$this->error($file_res1['msg']);
				}
			}

	        $result = $userModel->where(array('user_id' => $this->user['user_id']))->save($post_data);
	        if (!$result){
	        	$userModel->rollback();
	            $this->error('修改失败');
	        }else {
	            if ($new_pwd != null){
	            	$userModel->commit();
	                $this->success('修改成功',U('Admin/User/logout'));
	            }else {
	            	$userModel->commit();
	                $this->success('修改成功',U("Index/usermanage"));
	            }
	        }
	    }

	    $this->display();
	}
	/**
	 * 同意邀请
	 * 添加时间2016-3-2
	 * 
	 * @author yzx
	 */
	public function agree() {
	    $id = I('id',0,'intval');
	    $proid = I('proid',0,'intval');
	    $messageModel = new \Common\Model\MessageModel();
	    $result = $messageModel->agree($this->user,$id, $proid);
	    if ($result){
	        $this->success('已经同意',U("Index/index"));
	    }else {
	        $this->error('同意失败');
	    }
	}
	/**
	 * 拒绝邀请
	 * 添加时间2016-3-2
	 * 
	 * @author yzx
	 */
	public function refuse() {
	    $id = I('id',0,'intval');
	    $proid = I('proid',0,'intval');
		$messageModel = new \Common\Model\MessageModel();
		$result = $messageModel->deny($this->user,$id, $proid);
	    if ($result){
	        $this->success('已经拒绝');
	    }else {
	        $this->error('拒绝失败');
	    }
	}
	/**
	 * 阅读信息
	 * 添加时间2016-3-3
	 * 
	 * @author yzx
	 */
	public function read() {
	    $id = I("id",0,'intval');
	    $messageModel = new \Common\Model\MessageModel();
	    $save_data = array();
	    $save_data['read_time'] = time();
	    $result = $messageModel->where(array('id'=>$id))->save($save_data);
	    if ($result){
	        $this->success('阅读成功');
	    }else {
	        $this->error('阅读失败');
	    }
	}
	
	/**
	 * 修改用户状态(正常或静止或删除)
	 * 添加时间 2016-03-07
	 * @param types string  1:正常 2禁止 0 删除
	 * @author zlj
	 */
	public function user_state(){
		if(IS_AJAX){
			$id = I("post.id",0,'string');
			$types = I("types","","string");
			$userModel = new \Common\Model\UsersModel();

			$res = $userModel->where(array("user_id"=>$id))->save(array("state"=>$types));
			if($res){
				echo 1;
			}else{
				echo 2;
			}
		}
	}
	
   /**
	 * 团队是否禁止(正常或静止或删除)
	 * 添加时间 2016-03-07
	 * @param types string  1:正常 2禁止 0 删除
	 * @author zlj
	 */
	public function teamTops(){
		if(IS_AJAX){
			$id = I("post.id",0,'string');
			$types = I("types","","string");
			$teamModel = new \Common\Model\TeamModel();

            if($types==1){
            	$num = $teamModel->where(array("tops"=>1))->count();
				if($num<4){
					$res = $teamModel->where(array("id"=>$id))->save(array("tops"=>1));
					if($res){
						$this->ajaxReturn(array("state"=>TRUE,"msg"=>"修改成功"));
					}else{
						$this->ajaxReturn(array("state"=>FALSE,"msg"=>"修改失败"));
					}
				}else{
					$this->ajaxReturn(array("state"=>FALSE,"msg"=>"置顶数已达上限"));
				}
            }else{
            	$res = $teamModel->where(array("id"=>$id))->save(array("tops"=>0));
				if($res){
					$this->ajaxReturn(array("state"=>TRUE,"msg"=>"修改成功"));
				}else{
					$this->ajaxReturn(array("state"=>FALSE,"msg"=>"修改失败"));
				}
            }
		}
	}
	
	
	
	
}
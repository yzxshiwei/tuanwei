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
	 * 团队管理
	 * 添加时间11:01:20
	 * 
	 * @author yzx
	 */
	public function teammanage() {
	    $team = new \Common\Helper\Team();
		
		$uid = $this->user['user_id'];
		$user_type = $this->user['user_type'];
		//若是管理员 查所有团队
		if($this->user['user_type'] == \Common\Model\UsersModel::TYPE_MANAGE){
			$uid = NULL;
		}

	    $result = $team->listData($uid);
		$utype = \Common\Model\TeamModel::USER_TYPE_CAPTAIN;

        $teamModel = new \Common\Model\TeamModel();

        foreach($result['list_data'] as $_k=>$_v){
        	$where["team.project_id"] = $_v["project_id"];
			$where["team.user_type"] = $utype;
        	$name = $teamModel->join("users as u on (team.user_id = u.user_id)",'left')->field("u.user_name")->where($where)->find();
			$result['list_data'][$_k]["user_name"] = $name["user_name"];
        }
	    
		$flag = FALSE;	
		if($user_type == \Common\Model\UsersModel::TYPE_TEACHER || $user_type == \Common\Model\UsersModel::TYPE_JUDGES){
			$flag = TRUE;
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
				
			$data = array();
			$data["contents"] = I("post.intro","","string");
			$data["team_name"] = I("post.name","","string");
			$id = I("post.id","","string");
			//开启事务
			$team->startTrans();
			if($_FILES['project_file']['tmp_name']){
				$file_res = Upload($_FILES['project_file']);
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
		    if($res){
		        $team->commit();
		    	$this->success('更新团队成功',U('Index/teammanage'));
		    }else{
		   	    $team->rollback();
		   	    $this->error('更新团队失败');
		    }
			
		}else{
			$tid = I("get.tid","","string");
			$tinfo = $team->where(array("id"=>$tid))->find();

			$this->assign("tinfo",$tinfo);
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
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
	    $result = $team->listData($this->user['user_id']);
		$utype = \Common\Model\TeamModel::USER_TYPE_CAPTAIN;

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
			$data["contents"] = I("post.intro","","string");
			$data["team_name"] = I("post.name","","string");
			$id = I("post.id","","string");
			
			//开启事务
			$team->startTrans();
			$data = array();
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
	        $post_data = array();
	        $old_pwd = I('post.old_pwd',null,'string');
	        $new_pwd = I("post.new_pwd",null,'string');
	        $post_data['user_name'] = I('post.user_name',null,'string');
	        $post_data['tel'] = I('post.tel',null,'string');
	        $post_data['birth'] = I('post.birth',null,'string');
	        $post_data['nation'] = I('post.nation',null,'string');
	        $post_data['card_id'] = I('post.card_id',null,'string');
	        if ($new_pwd != null){
	            $old_pwd = create_password($old_pwd);
	            if ($this->user['passwd'] != $old_pwd){
	                $this->error('原始密码错误');
	            }else {
	                $post_data['passwd'] = create_password($new_pwd);
	            }
	        }
	        $userModel = new \Common\Model\UsersModel();
	        $result = $userModel->where(array('user_id' => $this->user['user_id']))->save($post_data);
	        if (!$result){
	            $this->error('修改失败');
	        }else {
	            if ($new_pwd != null){
	                $this->success('修改成功',U('Home/Login/index'));
	            }else {
	                $this->success('修改成功');
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
	        $this->success('已经同意');
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
//			echo $userModel->getlastsql();
			exit;
			if($res){
				echo 1;
			}else{
				echo 2;
			}
		}
	}
	
	
	
	
}
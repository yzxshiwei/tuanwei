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
			//开启事务
			$team->startTrans();
			$data = array();
			if($_FILES['project_file']['tmp_name']){
				$file_res = Upload($_FILES['project_file']);
				if($file_res["status"]){
					$data["img_url"] = $file_res['file_path'];
				}else{
					$this->error($file_res['msg']);
				}
			}
			$data["contents"] = I("post.intro","","string");
			$data["team_name"] = I("post.name","","string");
			$id = I("post.id","","string");
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
	    $result = $messageModel->agree($id, $proid);
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
}
<?php
namespace Admin\Controller;
/**
 * 后台比赛控制器
 * 添加时间13:23:13
 * 
 * @author yzx
 */
class FundsController extends Controller{
 
    public function createfunds() {
    	
	    $userModel = D('users');
		$project = D('project');
		
		if(IS_POST){

			$match = M("funds");
			//开启事务
		    $match->startTrans();

            $data = array();

			if($_FILES['selectFiles']['tmp_name']){
				$file_res1 = Upload($_FILES['selectFiles']);
				if($file_res1["status"]){
					$data["cover_src"] = $file_res1['file_path'];
				}else{
					$this->error($file_res1['msg']);
				}
			}

			$data["name"] = I('post.name','','string');
			$data["sub_title"] = I('post.sub_title','','string');
			$data["project_start_time"] = strtotime(I('post.race-start-date','','string'));
			$data["project_end_time"] = strtotime(I('post.race-end-date','','string'));
			$data["judge_amount"] = I("post.amount","","string");
			$data["rules"] = I('post.editorValue');
			$data["sign_start_time"] = strtotime(I("post.reg-start-date",'','string'));
			$data["sign_end_time"] = strtotime(I("post.reg-end-date","","string"));
			$relust = $match->add($data);
			
			if($relust){
				
				$teacherid = I("post.teacherid");
                $packet = I("post.packet");

                if($packet[0]){
                	//添加比赛组名称
				    $packetModel = new \Common\Model\Funds_packetModel;
				    $p_res = $packetModel->addName($packet,$relust);
                }

                if($teacherid[0]){
                	//添加评委老师
					$jugedsModel = new \Common\Model\Funds_judgesModel;
					$j_res = $jugedsModel->addJudges($teacherid,$relust);
			    	$messageModel = new \Common\Model\MessageModel();
			    	$messageModel->sendMsg($teacherid, $this->user['user_id'],$messageModel::TYPE_JUDGES_PROJECT, '你有资金申请评审邀请',$relust);
                }
				
				$match->commit();
		    	$this->success('添加申请成功',U('Funds/fundsmanage'));
			}else{
			    $match->rollback();
                $this->error('添加申请失败');
			}
            
		}else{
			$teacher_list = $userModel->where(array('user_type'=>\Common\Model\UsersModel::TYPE_JUDGES))->field("user_id,user_name")->select();
			$prolist = $project->field("id,name")->select();

			$this->assign("prolist",$prolist);
			$this->assign("teacher_list",$teacher_list);	
			$this->display();
		}
    }

    /**
     * 比赛管理
     * 添加时间13:31:40
     * 
     * @author yzx
     */
    public function fundsmanage() {
		$matchModel = new \Common\Helper\Funds();
		$result = $matchModel->listData($this->user);
		
		$user_type = $this->user['user_type'];
		$flag = FALSE;	
		if($user_type == \Common\Model\UsersModel::TYPE_TEACHER || $user_type == \Common\Model\UsersModel::TYPE_JUDGES){
			$flag = TRUE;
		}
		
		$this->assign('flag',$flag);
		$this->assign('list_data',$result['list_data']);
		$this->assign('Page' , $result['Page']);
        $this->display();
    }
   
    /**
     * 编辑比赛
     * 添加时间13:38:32
     * 
     * @author yzx
     */
    public function editfunds() {
    	
    	$matchModel = new \Common\Model\FundsModel;
		$packetModel = new \Common\Model\Funds_packetModel;
		$judgesModel = new \Common\Model\Funds_judgesModel;
		$userModel = new \Common\Model\UsersModel;
		$project = new \Common\Model\ProjectModel;

    	if(IS_POST){
    		
			$mid = I("post.mid","","string");
			$file_url = $matchModel->where(array("id"=>$mid))->field("cover_src,start_file_src,template_src")->find();
			
    		$data = array();
			//开启事务
		    $matchModel->startTrans();
			
			if($_FILES['selectFiles']['tmp_name']){
				$file_res1 = Upload($_FILES['selectFiles']);
				if($file_res1["status"]){
					
					$data["cover_src"] = $file_res1['file_path'];
					delfile($file_url["cover_src"]);
				}else{
					$this->error($file_res1['msg']);
				}
			}
			
			$data["name"] = I('post.name','','string');
			$data["sub_title"] = I('post.sub_title','','string');
			$data["project_start_time"] = strtotime(I('post.race-start-date'));
			$data["project_end_time"] = strtotime(I('post.race-end-date'));
			$data["judge_amount"] = I("post.amount","","string");
			$data["rules"] = I('post.editorValue');
			$data["sign_start_time"] = strtotime(I("post.reg-start-date",'','string'));
			$data["sign_end_time"] = strtotime(I("post.reg-end-date","","string"));

			$relust = $matchModel->where(array("id"=>$mid))->save($data);
			
			$teacherid = I("post.teacherid");
            $packet = I("post.packet");

            if($packet[0]){
            	//添加比赛组名称
				$packetModel->where(array("project_id"=>$mid))->delete();
				$p_res = $packetModel->addName($packet,$mid);
            }

            if($teacherid[0]){
            	//添加评委老师 
				$judgesModel->where(array("project_id"=>$mid))->delete();
				$j_res = $judgesModel->addJudges($teacherid,$mid);
		    	$messageModel = new \Common\Model\MessageModel();
			    $messageModel->sendMsg($teacherid, $this->user['user_id'],$messageModel::TYPE_JUDGES_PROJECT, '你有资金申请评审邀请',$mid);
            }

		    $matchModel->commit();
		    $this->success('修改申请成功',U('Funds/fundsmanage'));
    	}else{
    		$mid = I("get.mid","","string");
			
			$match_info = $matchModel->where(array("id"=>$mid))->find();
			$packet_info = $packetModel->where(array("project_id"=>$mid))->select();
			
			$judges_info = $judgesModel->join("users on user_id=funds_judges.judge_id")->where(array("funds_judges.project_id"=>$mid))->field("funds_judges.judge_id,users.user_name")->select();
			
			$teacher_list = $userModel->where(array('user_type'=>\Common\Model\UsersModel::TYPE_JUDGES))->field("user_id,user_name")->select();
			
			$match_info["rules"] = htmlspecialchars_decode($match_info["rules"]);
			$prolist = $project->field("id,name")->select();
		    
			$match_info["start_file_src"] = explode("file/",$match_info["start_file_src"])[1];

			$this->assign("prolist",$prolist);
            $this->assign("glist",$teacher_list);
            $this->assign("jinfo",$judges_info);
			$this->assign("pinfo",$packet_info);
			$this->assign("minfo",$match_info);
			$this->display();
    	}
    }
	
	/**
	 * 删除比赛
	 * @author zlj
	 */
	public function delFunds(){

		if(IS_AJAX){
			
			$matchModel = M("funds");
			$judgesModel = M("funds_judges");
			$packetModel = M("funds_packet");
			
			//开启事务
		    $matchModel->startTrans();

			$mid = I("post.mid","","string");
			
            //删除比赛 评委老师信息
			$res2 = $judgesModel->where(array("project_id"=>$mid))->delete();
			//删除比赛 组信息
			$res3 = $packetModel->where(array("project_id"=>$mid))->delete();
			//删除比赛信息
			$res1 = $matchModel->where(array("id"=>$mid))->delete();
			
			if($res1){
				$matchModel->commit();
				echo 1;
			}else{
				$matchModel->rollback();
				echo 2;
			}
		}
	}


    /**
	 * 修改比赛的发布状态
	 * @author zlj
	 */
	public function updateState(){
		if(IS_AJAX){
		    
			$mid = I("post.mid","","string");
			$type = I("post.types","","string");
			$match = M("funds");
		    $match->where(array("id"=>$mid))->save(array("state"=>$type));
		}
	}
}
<?php
namespace Admin\Controller;
/**
 * 后台比赛控制器
 * 添加时间13:23:13
 * 
 * @author yzx
 */
class MatchController extends Controller{
    /**
     * 创建比赛
     * 添加时间13:24:27
     * 
     * @author yzx
     */
    public function creatematch() {
    	
	    $userModel = D('users');
		$project = D('project');
		
		if(IS_POST){

			$match = M("match");
			//开启事务
		    $match->startTrans();

			$file_res1 = Upload($_FILES['selectFiles']);
            $file_res2 = Upload($_FILES['upload_template']);
			$file_res3 = Upload($_FILES['upload_reg_page']);
			
            if (!$file_res1['status'] || !$file_res2['status'] || !$file_res3['status']){
                $this->error($file_res1['msg']);
            }else {
            	
				$data = array();
				$data["name"] = I('post.name','','string');
				$data["sub_title"] = I('post.sub_title','','string');
				$data["project_start_time"] = strtotime(I('post.race-start-date','','string'));
				$data["project_end_time"] = strtotime(I('post.project_end_time','','string'));
				$data["judge_amount"] = I("post.amount","","string");
				$data["rules"] = htmlspecialchars(I('post.editorValue','','string'));
				$data["sign_start_time"] = strtotime(I("post.reg-start-date",'','string'));
				$data["sign_end_time"] = strtotime(I("post.reg-end-date","","string"));
				$data["cover_src"] = $file_res1['file_path'];
				$data["start_file_src"] = $file_res2['file_path'];
				$data["template_src"] = $file_res3['file_path'];
				$data["project_id"] = I('post.proid','','string');
				
				$relust = $match->add($data);
				
				if($relust){
					
					$teacherid = I("post.teacherid");
                    $packet = I("post.packet");
					//添加评委老师
					$jugedsModel = new \Common\Model\JudgesModel;
					$j_res = $jugedsModel->addJudges($teacherid,$relust);
					//添加比赛组名称
					$packetModel = new \Common\Model\PacketModel;
					$p_res = $packetModel->addName($packet,$relust);
					
				    if($j_res && $p_res){
				    	$match->commit();
				    	$this->success('添加比赛成功',U('Match/matchmanage'));
				    }else{
				    	$match->rollback();
				    	$this->error('添加比赛失败');
				    }
				}else{
				    $match->rollback();
                    $this->error('添加比赛失败');
				}
            }
		}else{
			$teacher_list = $userModel->where(array('user_type'=>\Common\Model\UsersModel::TYPE_TEACHER))->field("user_id,user_name")->select();
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
    public function matchmanage() {
    	
		$matchModel = new \Common\Helper\Match();
		$result = $matchModel->listData();
		

		
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
    public function editmatch() {
        $this->display();
    }
	
	/**
	 * 删除比赛
	 * @author zlj
	 */
	public function delMatch(){

		if(IS_AJAX){
			
			$matchModel = M("match");
			$judgesModel = M("judges");
			$packetModel = M("packet");
			
			//开启事务
		    $matchModel->startTrans();

			$mid = I("post.mid","","string");
			
            //删除比赛 评委老师信息
			$res2 = $judgesModel->where(array("project_id"=>$mid))->delete();
			//删除比赛 组信息
			$res3 = $packetModel->where(array("project_id"=>$mid))->delete();
			//删除比赛信息
			$res1 = $matchModel->where(array("id"=>$mid))->delete();
			
			if($res1 && $res2 && $res3){
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
			$match = M("match");
		    $match->where(array("id"=>$mid))->save(array("state"=>$type));
		}
	}
}
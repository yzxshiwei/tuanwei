<?php
namespace Home\Controller;
class TeamController extends \Common\Helper\Controller{
    /**
     * 团队组建
     * 添加时间2016-2-16
     * 
     * @author yzx
     */
    public function teamcreate() {

		if(IS_POST){
			$wordsModel = new \Common\Model\WordsModel;
			
			$data = array();
			$data["user_id"] = $this->user["user_id"];
			$data["words"] = I("post.contents","","string");
			$res = $wordsModel->add($data);
			if($res){
				$this->success("添加成功",U('Team/teamcreate'));
			}else{
				$this->error("添加失败");
			}
		}else{
			$words = new \Common\Helper\Words();
			$result = $words->listData();

			$this->assign('Page' , $result['Page']);
			$this->assign('list_data',$result['list_data']);
			$this->display();
		}
    }
    /**
     * 比赛信息列表
     * 添加时间2016-2-16
     * 
     * @author MuTao
     */
    public function matchlist() {
    	$Match = M('match');
    	$timestamp = time();
    	$data = $Match->field('id, name, sub_title, cover_src, start_file_src, rules, template_src')->where("state=2 AND $timestamp < project_end_time")->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
    	
    	foreach ($data as $k=>&$v){
    		$v['rules'] = htmlspecialchars_decode(substr($v['rules'],0, 150));
    		unset($v);
    	}
    	$img_url = $Match->field('id, cover_src')->where("cover_src is not null and state=2 AND $timestamp < project_end_time")->limit(5)->select();
    	
    	$count = $Match->where("state=2 AND $timestamp < project_end_time")->count();
    	//分页
    	$page = new \Think\Page($count, 3);
    	$show = $page->show();

    	$this->assign('img_url',$img_url);
    	$this->assign('data', $data);	
    	$this->assign('page', $show);
        $this->display();
    }
    /**
     * 申请报名
     * 添加时间2016-2-16
     * 
     * @author yzx
     */
    public function matchdetails() {
    	if(IS_POST){
    		$mpModel = M("match_project");
			
    		$data = array();
			$data["match_id"] = I("post.mid","","string");
			$data["project_id"] = I("post.project_id","","string");
			$data["class_id"] = I("post.class_id","","string");
			$res = $mpModel->add($data);
			if($res){
				$this->success("申请成功",U('Team/matchlist'));
			}else{
				$this->error("申请失败");
			}
    	}else{
    		$projectModel = new \Common\Model\ProjectModel;
			$packetModel = new \Common\Model\PacketModel;
			
    		$mid = I("get.id","","string");
    		$userid = $this->user["user_id"];
			$project_list = $projectModel->where(array("creat_id"=>$userid))->field("id,name")->select();
			$packet_list = $packetModel->where(array("project_id"=>$mid))->field("id,class_name")->select();
			
			$this->assign("id",$mid);
			$this->assign("project_list",$project_list);
            $this->assign("packet_list",$packet_list);
    		$this->display();
    	}
    }
    /**
     * 往期活动
     * 添加时间2016-2-16
     * 
     * @author yzx
     */
    public function oldactivity() {
        $this->display();
    }
    /**
     * 场地申请
     * 添加时间16:53:59
     * 
     * @author yzx
     */
    public function occupy() {
        $this->display();
    }
}
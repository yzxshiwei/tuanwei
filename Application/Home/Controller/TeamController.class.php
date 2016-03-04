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
    	$data = $Match->field('id, name, sub_title, cover_src, start_file_src, rules, template_src')->where("state=1 AND $timestamp < project_end_time")->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
    	
    	foreach ($data as $k=>&$v){
    		$v['rules'] = htmlspecialchars_decode(substr($v['rules'],0, 150));
    		unset($v);
    	}
    	$img_url = $Match->field('id, cover_src')->where("cover_src is not null and state=1 AND $timestamp < project_end_time")->limit(5)->select();
    	
    	$count = $Match->where("state=1 AND $timestamp < project_end_time")->count();
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
			$matchModel = new \Common\Model\MatchModel;
			
			$times_info = $matchModel->where(array("id"=>$data["match_id"]))->field("sign_start_time,sign_end_time")->find();
			if($times_info["sign_start_time"]<time() && $times_info["sign_end_time"]>time()){
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
				$this->error("此比赛报名时间已过");
			}

    	}else{
    		$projectModel = new \Common\Model\ProjectModel;
			$packetModel = new \Common\Model\PacketModel;
			$matchModel = new \Common\Model\MatchModel;
			
    		$mid = I("get.id","","string");
    		$userid = $this->user["user_id"];
			$project_list = $projectModel->where(array("creat_id"=>$userid))->field("id,name")->select();
			$packet_list = $packetModel->where(array("project_id"=>$mid))->field("id,class_name")->select();
			$minfo = $matchModel->where(array("id"=>$mid))->field("id,rules")->find();
			$minfo["rules"] = htmlspecialchars_decode($minfo["rules"]);

			$this->assign("minfo",$minfo);
			$this->assign("project_list",$project_list);
            $this->assign("packet_list",$packet_list);
    		$this->display();
    	}
    }
    
	/**
	 * 了解详情
	 * 添加时间 2016-3-3
	 * 
	 * @author zlj
	 */
	public function matchinfo(){
		$mid = I("get.id","","string");
		$matchModel = new \Common\Model\MatchModel;
		$minfo = $matchModel->where(array("id"=>$mid))->field("id,rules")->find();
		$minfo["rules"] = htmlspecialchars_decode($minfo["rules"]);

		$this->assign("minfo",$minfo);
		$this->display();
	}
	
	
    /**
     * 往期活动
     * 添加时间2016-2-16
     * 
     * @author yzx
     */
    public function oldactivity() {
        $Match = M('match');
    	$timestamp = time();
    	$data = $Match->field('id, name, sub_title, cover_src, start_file_src, rules, template_src')->where("state=1 AND $timestamp > project_end_time")->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
    	$count = $Match->where("state=1 AND $timestamp > project_end_time")->count();
    	
    	foreach ($data as $k=>&$v){
    	    $v['rules'] = htmlspecialchars_decode(substr($v['rules'],0, 150));
    	    unset($v);
    	}
    	//分页
    	$page = new \Think\Page($count, 5);
    	$show = $page->show();
    	$this->assign('data', $data);	
    	$this->assign('page', $show);
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
    /**
     * 团队详情
     */
    public function teamDails() {
        $project_id = I('get.project_id', false,'intval');
        if(!$project_id){
            $this->error('页面错误');
        }
        
        $Team = M('team');
        $data = $Team->field('img_url,team_name,contents')->where(array(
            'user_type' => 'captain',
            'project_id' => $project_id
        ))->select();
        
        $members = $Team->table('team t')->join('users u on t.user_id=u.user_id')->field('u.user_name')->where(array(
            'project_id' => $project_id
        ))->select();
        
        foreach ($members as $k => $v){
            $names .= $v['user_name'].' '; 
        }
//         var_dump($data);
//         exit;
        $this->assign('data', $data[0]);
        $this->assign('names', $names);
        $this->display();
    }
}
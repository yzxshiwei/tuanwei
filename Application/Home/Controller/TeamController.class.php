<?php
namespace Home\Controller;
class TeamController extends \Common\Helper\Controller{
    /**
	 * 队员招聘 (毛遂自荐)
	 *
     * 添加时间2016-2-16
     * @author yzx
     */
    public function teamcreate() {

		if(IS_POST){
			//队员招聘 (毛遂自荐)
			$wordsModel = new \Common\Model\WordsModel;
			
			$data = array();
			$data["user_id"] = $this->user["user_id"];
			
			if(!$data["user_id"]){
				$this->error("请用户先登录");
			}
			
			$data["words"] = I("post.contents","","string");
			$data["type_id"] = I("post.type_id","","string");
			$res = $wordsModel->add($data);
			if($res){
				$this->success("添加成功",U('Team/teamcreate'));
			}else{
				$this->error("添加失败");
			}
		}else{
			$words = new \Common\Helper\Words();
			$result = $words->listData(array("type_id"=>1));

			$this->assign('Page' , $result['Page']);
			$this->assign('list_data',$result['list_data']);
			$this->display();
		}
    }
	
	/**
	 * 毛遂自荐
	 * 添加时间2016-03-14
	 * @author zlj
	 */
	public function createam(){
		$words = new \Common\Helper\Words();
		$result = $words->listData(array("type_id"=>2));

		$this->assign('Page' , $result['Page']);
		$this->assign('list_data',$result['list_data']);
		$this->display();
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
    	$day = date("Y-m-d",$timestamp);
    	$data = $Match->field('id, name, sub_title, cover_src, start_file_src, rules, template_src,sign_start_time,sign_end_time')->where("state=1")->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();

    	foreach ($data as $k => $v){
			$data[$k]['rules'] = mb_substr(htmlspecialchars_decode($data[$k]['rules']), 0, 150, "utf-8");
    		if($v["sign_start_time"] <= strtotime($day) && $v["sign_end_time"] > strtotime($day)){
    			$data[$k]["times"] = TRUE;
    		}else{
    			$data[$k]["times"] = FALSE;
    		}
    	}
    	$img_url = $Match->field('id, cover_src')->where("cover_src is not null and state=1 AND $timestamp < project_end_time")->limit(5)->select();
    	
    	$count = $Match->where("state=1 AND $timestamp < project_end_time")->count();
    	//分页
    	$page = new \Think\Page($count, 5);
    	$show = $page->show();
		$flag = $this->user["user_type"]==\Common\Model\UsersModel::TYPE_MANAGE?FALSE:TRUE;
    	$this->assign('img_url',$img_url);
		$this->assign('flag',$flag);
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
    	
    	$matchModel = new \Common\Model\MatchModel;
    	
    	if(IS_POST){
    		$mpModel = M("match_project");
		
		    if(!$this->user["user_id"]){
		    	$this->error("请用户先登录");
		    }
		
    		$data = array();
			$data["match_id"] = I("post.mid","","string");
			$data["project_id"] = I("post.project_id","","string");
			$data["class_id"] = I("post.class_id","","string");
			
			$minfo = $matchModel->where(array("id"=>$data["match_id"]))->field("id,rules,sign_start_time,sign_end_time")->find();

			if($minfo["sign_start_time"]>time() || $minfo["sign_end_time"]<time()){
				$this->error("不在报名时间段");
			}
			if(!$data["project_id"] || !$data["class_id"]){
				$this->error("请进行选择");
			}
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
			$v['rules'] = mb_substr(htmlspecialchars_decode($v['rules']), 0, 150, "utf-8");
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
	 * 场地类别
	 * 添加时间 206-03-07
	 * @author zlj
	 */
	public function occupytwo(){
		
		$new = new \Common\Helper\News();
		$where["col"] = \Common\Model\NewsModel::COL_6;
		$where["flag"] =1;
		$result = $new->listData($where);

		$this->assign('Page' , $result['Page']);
        $this->assign('list_data' ,$result['list_data']);
		$this->display();
	}
	
    /**
     * 团队详情
     */
    public function teamDails() {
        $id = I('get.id', false,'intval');
        if(!$id){
            $this->error('页面错误');
        }
        
        $Team = M('team');
        $data = $Team->field('img_url,team_name,contents')->where(array(
            'user_type' => 'captain',
            'id' => $id
        ))->select();
        
        $members = $Team->table('team t')->join('users u on t.user_id=u.user_id')->field('u.user_name')->where(array(
            'id' => $id
        ))->select();
        
        foreach ($members as $k => $v){
            $names .= $v['user_name'].' '; 
        }

        $this->assign('data', $data[0]);
        $this->assign('names', $names);
        $this->display();
    }
	
	/**
	 * 比赛相关文件下载
	 */
	public function downFile(){
		$match = M("match");
		$mid = I("get.mid","","string");
		$file_url = $match->where(array("id"=>$mid))->field("start_file_src")->find();
		downloads($file_url);
	}
}
<?php
namespace Home\Controller;
class FundsController extends \Common\Helper\Controller {

    /**
     * 资金申请
     * @return [type] [description]
     */
	public function funds(){
        $Match = M('funds');
    	$timestamp = time();
    	$day = date("Y-m-d",$timestamp);
    	$data = $Match->field('id, name, sub_title, cover_src, start_file_src,template_src,sign_start_time,sign_end_time,project_start_time,project_end_time')->where("state=1")->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();

    	foreach ($data as $k => $v){
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
     * 了解详情
     * 添加时间 2016-3-3
     * 
     * @author zlj
     */
    public function fundsinfo(){
        $mid = I("get.id","","string");
        $matchModel = new \Common\Model\FundsModel;
        $minfo = $matchModel->where(array("id"=>$mid))->field("id,rules")->find();
        $minfo["rules"] = htmlspecialchars_decode($minfo["rules"]);

        $this->assign("minfo",$minfo);
        $this->display();
    }
}
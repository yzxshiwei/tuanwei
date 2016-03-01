<?php
namespace Home\Controller;
class IndexController extends \Common\Helper\Controller {
    public function index(){
    	
		$newModel = new \Common\Model\NewsModel;
		$newList = $newModel->order("public_t desc")->field("id,title,img_url")->select();
		
		$teamModel = new \Common\Model\TeamModel;
		$teamList = $teamModel->order("id desc")->limit(4)->select();

		$this->assign("teamList",$teamList);
        $this->assign("newList",$newList);
        $this->display();
    }
}
<?php
namespace Home\Controller;
class IndexController extends \Common\Helper\Controller {
    public function index(){
    	
		$newModel = new \Common\Model\NewsModel;
		$newList = $newModel->order("public_t desc")->field("id,title")->select();
		
        $this->assign("newList",$newList);
        $this->display();
    }
}
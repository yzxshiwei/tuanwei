<?php
namespace Home\Controller;
class IndexController extends \Common\Helper\Controller {
    public function index(){
    	
		$newModel = new \Common\Model\NewsModel;
		
		$where["flag"] = 1;
		$time = time();
	    $where["top_s"] = array("elt",$time);
		$where["top_e"] = array("egt",$time);

		$newList = $newModel->where($where)->order("public_t desc")->field("id,title,img_url")->limit(5)->select();
		
		if(count($newList)<5 && count($newList)>0 ){
			
			$num = 5-count($newList);
		    $newList2 = $newModel->where("(top_s > $time or top_e<$time ) and flag=1")->order("public_t desc")->field("id,title,img_url")->limit($num)->select();
			$newList = array_merge($newList,$newList2);
		}else{
		   $newList = $newModel->where(array("flag"=>1))->order("public_t desc")->field("id,title,img_url")->limit(5)->select();
		}
		$teamModel = new \Common\Model\TeamModel;
		$utype = \Common\Model\TeamModel::USER_TYPE_CAPTAIN;
		$teamList = $teamModel->where(array("user_type"=>$utype))->order("id desc")->limit(4)->select();
		
		$this->assign("teamList",$teamList);
        $this->assign("newList",$newList);
        $this->display();
    }
}
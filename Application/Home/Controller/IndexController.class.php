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
		}
		
		$teamModel = new \Common\Model\TeamModel;
		$utype = \Common\Model\TeamModel::USER_TYPE_CAPTAIN;
		$teamList = $teamModel->where(array("user_type"=>$utype,"tops"=>1))->order("id desc")->limit(4)->select();
		if(count($teamList)<4 && count($teamList)>0 ){
			$num = 4-count($teamList);
			$data["user_type"] = $utype;
			$data["tops"] = array("neq",1); 
			$teamList2 = $teamModel->where($data)->order("id desc")->limit($num)->select();
			$teamList = array_merge($teamList,$teamList2);
		}
		
		$this->assign("teamList",$teamList);
        $this->assign("newList",$newList);
        $this->display();
    }
}
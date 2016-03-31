<?php
namespace Home\Controller;
class IndexController extends \Common\Helper\Controller {
    public function index(){
    	
//		ini_set('memory_limit',"1024M");
//		
//		$his = M("his_data");
//		
//		$t1 = microtime(true);
		
		
//		$his->startTrans();
//		$where["log_date"] = array("between",array("2015-01-01","2016-03-10"));
//		$info = $his->where($where)->field("id,time")->limit(900000)->select();
//		echo $his->getlastsql();
//		foreach($info as $v){
//			$data["log_time"] = date("Y-m-d H:i:s",$v["time"]);
//			$data["log_date"] = date("Y-m-d",$v["time"]);
//			$his->where(array("id"=>$v["id"]))->save($data);
//		}
//		$his->commit();



//
//
//$t2 = microtime(true);
//echo '耗时'.round($t2-$t1,3).'秒';


		
		
		
		
		
		
		
		
		
		
		
		
		
		
//		exit;
		$newModel = new \Common\Model\NewsModel;
		
		$where["flag"] = 1;
		$time = time();
	    $where["top_s"] = array("elt",$time);
		$where["top_e"] = array("egt",$time);

        //新闻
		$newList = $newModel->where($where)->order("public_t desc")->field("id,title,img_url")->limit(5)->select();
		if(count($newList)<5 && count($newList)>0 ){
			
			$num = 5-count($newList);
		    $newList2 = $newModel->where("(top_s > $time or top_e<$time ) and flag=1")->order("public_t desc")->field("id,title,img_url")->limit($num)->select();
			$newList = array_merge($newList,$newList2);
		}elseif(count($newList)!=5){
			$newList = $newModel->where(array("flag"=>1))->order("public_t desc")->field("id,title,img_url")->limit(5)->select();
		}
        //团队风采
		$teamModel = new \Common\Model\TeamModel;
		$utype = \Common\Model\TeamModel::USER_TYPE_CAPTAIN;
		$teamList = $teamModel->where(array("user_type"=>$utype,"tops"=>1))->order("id desc")->limit(4)->select();
		if(count($teamList)<4 && count($teamList)>0 ){
			$num = 4-count($teamList);
			$data["user_type"] = $utype;
			$data["tops"] = array("neq",1); 
			$teamList2 = $teamModel->where($data)->order("id desc")->limit($num)->select();
			$teamList = array_merge($teamList,$teamList2);
		}elseif(count($teamList)!=4){
			$teamList = $teamModel->where(array("user_type"=>$utype))->order("id desc")->limit(4)->select();
		}
		
		$this->assign("teamList",$teamList);
        $this->assign("newList",$newList);
        $this->display();
    }
}
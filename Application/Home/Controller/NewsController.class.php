<?php
namespace Home\Controller;
class NewsController extends \Common\Helper\Controller{
    /**
     * 培训讲座
     * 添加时间2016-2-16
     * 
     * @author yzx
     */
    public function train(){
    		
    	$new = new \Common\Helper\News();
		$where["col"] = \Common\Model\NewsModel::COL_3;
		$where["flag"] =1;
		$result = $new->listData($where);

		$this->assign('Page' , $result['Page']);
        $this->assign('list_data' ,$result['list_data']);
        $this->display();
    }
    /**
     * 新闻详情
     * 添加时间2016-2-16
     * 
     * @author yzx
     */
    public function details() {
    	
		$newModel = new \Common\Model\NewsModel;
		
		$nid = I("get.id","",'string');
		$result = $newModel->where(array("id"=>$nid))->find();
		
        $result['content'] = htmlspecialchars_decode($result['content']);
		
		$this->assign("ninfo",$result);
        $this->display();
    }
    /**
     * 资料库
     * 添加时间2016-2-16
     * 
     * @author yzx
     */
    public function library() {
		
		
		$new = new \Common\Helper\News();
		$where["col"] = \Common\Model\NewsModel::COL_5;
		$where["flag"] =1;
		$result = $new->listData($where);

		$this->assign('Page' , $result['Page']);
        $this->assign('list_data' ,$result['list_data']);
        $this->display();
    }
    
    
    /**
     * 创业动态
     * 添加时间2016-2-16
     * 
     * @author MuTao
     * 
     */
    public function dynamicbusiness() {
    	$News = M('news');
    	$timestamp = time();
    	$dataTop = $News->field('id,img_url,title')->where("col=2 and flag=1 and $timestamp > top_s AND $timestamp < top_e")->order('public_t desc')->limit(5)->select();
    	$count = $News->field('id,title')->where("col=2 and flag=1 and $timestamp > top_s AND $timestamp < top_e")->order('public_t desc')->limit(5)->count();
    	
    	if($count<5){
    		$dataNormal = $News->field('id,img_url,title')->where("col=2 and flag=1 and top_s=0 and top_e=0")->order('public_t desc')->limit(5-$count)->select();
    	
	    	if(!is_null($dataNormal)){
	    		$data = array_merge($dataTop, $dataNormal);
	    	}
	    	else {
	    		//该栏目没有非置顶新闻
	    		$data = $dataTop;
	    	}
	    	
    	}
    	else{
    		$data = $dataTop;
    	}
    	
    	$img_url = array();
    	foreach ($data as $k => $v){
    		if($v['img_url']!= '0'){
    			$img_url[] = array('id' => $v['id'],'img_url' => $v['img_url']);
    		}
    		
    	}
    	$this->assign('img_url',$img_url);
    	$this->assign ( 'data', $data );
		$this->display ();
    }
    /**
     * 团队风采
     * 添加时间2016-2-16
     * 
     * @author yzx
     */
    public function teamstyle() {
        $this->display();
    }
    /**
     * 创业政策
     * 添加时间2016-2-16
     * 
     * @author yzx
     */
    public function businesspolicy() {
    	
		$new = new \Common\Helper\News();
     
        $res3 = $new->newList(array("sub_col"=>\Common\Model\NewsModel::SUB_COL_3,"flag"=>1),NULL,NULL,11);
		$res2 = $new->newList(array("sub_col"=>\Common\Model\NewsModel::SUB_COL_2,"flag"=>1),NULL,NULL,11);
		$res1 = $new->newList(array("sub_col"=>\Common\Model\NewsModel::SUB_COL_1,"flag"=>1),NULL,NULL,11);

		$model = new \Common\Model\NewsModel;
		$where['top_s'] = array('elt', time());
		$where['top_e'] = array('egt', time());
		$where["flag"] =1;
		$img = $model->where($where)->field("img_url")->limit(5)->select();
				
		$this->assign("img",$img);
		$this->assign("res3",$res3);
		$this->assign("res2",$res2);
		$this->assign("res1",$res1);
        $this->display();
    }
    /**
     * 学校政策
     * 添加时间2016-2-16
     * 
     * @author yzx
     */
    public function schoolspolicy() {
    	
		$new = new \Common\Helper\News();
		$where["sub_col"] = \Common\Model\NewsModel::SUB_COL_1;
		$where["flag"] =1;
		$result = $new->listData($where);

		$this->assign('Page' , $result['Page']);
        $this->assign('list_data' ,$result['list_data']);
        $this->display();
    }
    /**
     * 地方支持
     * 添加时间2016-2-16
     * 
     * @author yzx
     */
    public function placespolicy(){
    	$new = new \Common\Helper\News();
		$where["sub_col"] = \Common\Model\NewsModel::SUB_COL_2;
		$where["flag"] =1;
		$result = $new->listData($where);

		$this->assign('Page' , $result['Page']);
        $this->assign('list_data' ,$result['list_data']);
        $this->display();
    }
    /**
     * 国家政策
     * 添加时间2016-2-16
     *
     * @author yzx
     */
    public function countryspolicy() {
    	$new = new \Common\Helper\News();
		$where["sub_col"] = \Common\Model\NewsModel::SUB_COL_3;
		$where["flag"] =1;
		$result = $new->listData($where);

		$this->assign('Page' , $result['Page']);
        $this->assign('list_data' ,$result['list_data']);
        $this->display();
    }
}
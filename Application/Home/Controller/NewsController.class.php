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
        $this->display();
    }
    /**
     * 新闻详情
     * 添加时间2016-2-16
     * 
     * @author yzx
     */
    public function details() {
        $this->display();
    }
    /**
     * 资料库
     * 添加时间2016-2-16
     * 
     * @author yzx
     */
    public function library() {
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
        $this->display();
    }
    /**
     * 学校政策
     * 添加时间2016-2-16
     * 
     * @author yzx
     */
    public function schoolspolicy() {
        $this->display();
    }
    /**
     * 地方支持
     * 添加时间2016-2-16
     * 
     * @author yzx
     */
    public function placespolicy(){
        $this->display();
    }
    /**
     * 国家政策
     * 添加时间2016-2-16
     *
     * @author yzx
     */
    public function countryspolicy() {
        $this->display();
    }
}
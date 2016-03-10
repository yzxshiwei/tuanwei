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
    	$dataTop = $News->field('id,img_url,title')->where("col=4 and flag=1 and $timestamp > top_s AND $timestamp < top_e")->order('public_t desc')->limit(5)->select();
    	$count = $News->field('id,title')->where("col=4 and flag=1 and $timestamp > top_s AND $timestamp < top_e")->order('public_t desc')->limit(5)->count();
    	
    	$dataNormal = $News->field('id,img_url,title')->where("col=4 and flag=1 and top_s=0 and top_e=0")->order('public_t desc')->limit(5-$count)->select();
    	
    	$p = I('get.p',1,'intval');
    	
    	$data = array_merge((array)$dataTop, (array)$dataNormal);
    	
    	//分页
    	$page = new \Think\Page(count($data),5);
    	$show = $page->show();
    	//轮播图
    	$i = 0;
    	foreach ($data as $k => $v){
    		if($v['img_url']!= '0'){
    			if($i == 6){
    				break;
    			}
    			$img_url[$i] = array('id' => $v['id'],'img_url' => $v['img_url']);
				$i++;
    		}
    	}
    	
    	$this->assign('page', $show);
    	$data = array_slice($data, (--$p)*5, 5);
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
        $Team = M('team');
        $count = $Team->where(array('user_type' => 'captain'))->count();
        $page = new \Think\Page($count, 5);
        $data = $Team->field('project_id,img_url,team_name,contents')->where(array('user_type' => 'captain'))->limit($Page->firstRow.','.$Page->listRows)->select();
        $show = $page->show();
        $img_url = $Team->field('project_id,img_url')->where("user_type='captain' AND img_url is not null")->limit(5)->select();
        $this->assign('data', $data);
        $this->assign('img_url', $img_url);
        $this->assign('page', $show);
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
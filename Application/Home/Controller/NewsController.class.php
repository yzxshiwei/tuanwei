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
		$result = $new->listData($where);

		$this->assign('Page' , $result['Page']);
        $this->assign('list_data' ,$result['list_data']);
        $this->display();
    }
    /**
     * 创业动态
     * 添加时间2016-2-16
     * 
     * @author yzx
     */
    public function dynamicbusiness() {
        $this->display();
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

        $res3 = $new->newList(array("sub_col"=>\Common\Model\NewsModel::SUB_COL_3),NULL,NULL,11);
		$res2 = $new->newList(array("sub_col"=>\Common\Model\NewsModel::SUB_COL_2),NULL,NULL,11);
		$res1 = $new->newList(array("sub_col"=>\Common\Model\NewsModel::SUB_COL_1),NULL,NULL,11);

		$model = new \Common\Model\NewsModel;
		$where['top_s'] = array('elt', time());
		$where['top_e'] = array('egt', time());
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
		$result = $new->listData($where);

		$this->assign('Page' , $result['Page']);
        $this->assign('list_data' ,$result['list_data']);
        $this->display();
    }
}
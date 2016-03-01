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
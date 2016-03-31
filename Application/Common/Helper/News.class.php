<?php
namespace Common\Helper;
class News{
    /**
     * 获取新闻信息列表
     * 添加时间2016-02-23
	 * 
     * @param where array 条件
     * @author zlj
     * @return Ambigous <mixed, boolean, string, NULL, multitype:, unknown, object>
     */
    public function listData($where=NULL){

        $newsModel = new \Common\Model\NewsModel();
		
		if($where){
			$count = $newsModel->where($where)->count();
		}else{
			$count = $newsModel->count();
		}
		
        $Page = new \Think\Page($count,5);
        $page_show = $Page->show();
		
		if($where){
			$list_data = $newsModel->limit($Page->firstRow.','.$Page->listRows)->where($where)->select();
		}else{
			$list_data = $newsModel->limit($Page->firstRow.','.$Page->listRows)->select();
		}

        return array('Page' => $page_show , 'list_data' => $list_data);
    }
	
	/**
	 * 获取新闻列表
	 * 添加时间2016-03-01
	 * 
	 * @author zlj
	 */
	public function newList($where=NULL,$field=NULL,$order=NULL,$limit=NULL){
		
	    $newsModel = new \Common\Model\NewsModel();
		$thiss = $newsModel;
     	
     	if($where){
			$thiss = $thiss->where($where);
		}
		if($field){
			$thiss = $thiss->field($field);
		}
		if($order){
			$thiss = $thiss->order($order);
		}
		if($limit){
			$thiss = $thiss->limit($limit);
		}
		
		$list_data = $thiss->select();

		return $list_data;
	}
}
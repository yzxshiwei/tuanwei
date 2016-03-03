<?php
namespace Common\Helper;
class Words{
    /**
     * 获取团队组建消息
     * 添加时间2016-03-01
	 * 
     * @param where array 条件
     * @author zlj
     * @return Ambigous <mixed, boolean, string, NULL, multitype:, unknown, object>
     */
    public function listData($where=NULL){

        $wordsModel = new \Common\Model\WordsModel();
		
		if($where){
			$count = $wordsModel->where($where)->count();
		}else{
			$count = $wordsModel->count();
		}

        $Page = new \Think\Page($count,2);
        $page_show = $Page->show();
		
		if($where){
			$list_data = $wordsModel->join("users on users.user_id=words.user_id")->order("id desc")->limit($Page->firstRow.','.$Page->listRows)->where($where)->field("words.*,users.user_name")->select();
		}else{
			$list_data = $wordsModel->join("users on users.user_id=words.user_id")->order("id desc")->limit($Page->firstRow.','.$Page->listRows)->field("words.*,users.user_name")->select();
		}

        return array('Page' => $page_show , 'list_data' => $list_data);
    }
	
}
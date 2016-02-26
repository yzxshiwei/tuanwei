<?php
namespace Common\Helper;
class Match{
    /**
     * 获取比赛列表
     * 添加时间13:07:56
     * 
     * @author zlj
     * @return Ambigous <mixed, boolean, string, NULL, multitype:, unknown, object>
     */
    public function listData(){
        $matchModel = new \Common\Model\MatchModel();
        $count = $matchModel->count();
        $Page = new \Think\Page($count,10);
        $page_show = $Page->show();
        $list_data = $matchModel->limit($Page->firstRow.','.$Page->listRows)->select();
        return array('Page' => $page_show , 'list_data' => $list_data);
    }
}
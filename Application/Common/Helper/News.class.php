<?php
namespace Common\Helper;
class News{
    /**
     * 获取新闻信息列表
     * 添加时间2016-02-23
     * 
     * @author zlj
     * @return Ambigous <mixed, boolean, string, NULL, multitype:, unknown, object>
     */
    public function listData(){
        $newsModel = new \Common\Model\NewsModel();
        $count = $newsModel->count();
        $Page = new \Think\Page($count,3);
        $page_show = $Page->show();
        $list_data = $newsModel->limit($Page->firstRow.','.$Page->listRows)->select();
        return array('Page' => $page_show , 'list_data' => $list_data);
    }
}
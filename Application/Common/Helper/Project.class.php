<?php
namespace Common\Helper;
class Project{
    /**
     * 获取项目列表
     * 添加时间13:07:56
     * 
     * @author yzx
     * @return Ambigous <mixed, boolean, string, NULL, multitype:, unknown, object>
     */
    public function listData(){
        $porjectModel = new \Common\Model\ProjectModel();
        $count = $porjectModel->count();
        $Page = new \Think\Page($count,2);
        $page_show = $Page->show();
        $list_data = $porjectModel->limit($Page->firstRow.','.$Page->listRows)->select();
        return array('Page' => $page_show , 'list_data' => $list_data);
    }
    /**
     * 获取一条项目数据
     * 添加时间15:07:10
     * 
     * @author yzx
     * @param int $id
     * @return Ambigous <mixed, boolean, NULL, string, unknown, multitype:, object>
     */
    public function getData($id) {
        $porjectModel = new \Common\Model\ProjectModel();
        $result = $porjectModel->where(array('id' => $id))->find();
        return $result;
    }
}
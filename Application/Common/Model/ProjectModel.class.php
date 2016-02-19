<?php
namespace Common\Model;
class ProjectModel extends \Common\Helper\Model{
    protected $tableName = 'project';
    /**
     * 添加新项目
     * 添加时间10:53:10
     * 
     * @author yzx
     * @param array $data
     * @return int
     */
    public function addPorject($data) {
        $result = $this->add($data);
        return $result;
    }
}
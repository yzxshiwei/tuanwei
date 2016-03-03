<?php
namespace Common\Model;
class ProjectModel extends \Common\Helper\Model{
    protected $tableName = 'project';
    const IS_OPEN_TRUE = 1;
    const IS_OPEN_FALSE = 0;
    /**
     * 是否公开
     * @var array
     */
    public $is_open = array(
        self::IS_OPEN_TRUE => '公开',
        self::IS_OPEN_FALSE => '不公开'
    );
    /**
     * 添加新项目
     * 添加时间10:53:10
     * 
     * @author yzx
     * @param array $data
     * @return int
     */
    public function addPorject($data) {
        $this->getDbFields();
        $result = $this->add($data);
        return $result;
    }
    /**
     * 公开关闭公开
     * 添加时间2016-3-3
     * @author yzx
     * @param int $id
     * 
     * @return bool
     */
    public function isOpen($id) {
        $project_data = $this->where(array('id' => $id))->find();
        $save_data = array();
        if ($project_data['is_open'] == self::IS_OPEN_TRUE) {
            $save_data['is_open'] = self::IS_OPEN_FALSE;
            $MSG = '关闭成功';
        }
        if ($project_data['is_open'] == self::IS_OPEN_FALSE) {
            $save_data['is_open'] = self::IS_OPEN_TRUE;
            $MSG = '公开成功';
        }
        $result = $this->where(array('id' => $id))->save($save_data);
        return array('status' => $result ,'msg' => $MSG);
    }
}
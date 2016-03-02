<?php
namespace Common\Model;
class Project_StatusModel extends \Common\Helper\Model{
    protected $tableName = 'project_status';
    const RESULT_PASS = 1;
    const RESULT_NOT_PASS = 0;
    /**
     * 比赛状态
     * @var array
     */
    public $result = array(
        self::RESULT_PASS => '通过',
        self::RESULT_NOT_PASS => '不通过',
    );
}
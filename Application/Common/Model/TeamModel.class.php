<?php
namespace Common\Model;
class TeamModel extends \Common\Helper\Model{
    protected $tableName = 'team';
    /**
     * 用户类型
     * 添加时间2016-2-24
     * 
     * @author yzx
     * @var unknown
     */
    const USER_TYPE_MEMBER = 'member';
    const USER_TYPE_CAPTAIN = 'captain';
    /**
     * 添加时间2016-2-24
     * 
     * @author yzx
     * @var array
     */
    public $user_type = array(self::USER_TYPE_MEMBER => '队员',
                              self::USER_TYPE_CAPTAIN => '队长');
}
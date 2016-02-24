<?php
namespace Common\Model;
class MessageModel extends \Common\Helper\Model{
    const TYPE_SYSTEM = 'system';
    const TYPE_USER = 'user';
    const TYPE_TEAM = 'team';
    /**
     * 消息类型
     * @var array
     */
    public $type = array(
        self::TYPE_SYSTEM => '系统消息',
        self::TYPE_USER => '用户消息',
        self::TYPE_TEAM => '团队消息',
    );
    protected $tableName = 'message';
    /**
     * 发送消息
     * 添加时间14:10:30
     * 
     * @author yzx
     * @param array $data
     * @param string $type
     * @param int $user_id
     * @return multitype:boolean string |multitype:Ambigous <mixed, boolean, unknown, string>
     */
    public function send($data,$type,$user_id) {
        $time = time();
        if (in_array($type, array_keys($this->type))){
           $add_data = array();
           $add_data['from_user'] = $user_id;
           $add_data['create_time'] = $time;
           $add_data['to_user'] = $data['team_id'];
           $add_data['msg_type'] = $type;
           $add_data['content'] = $data['content'];
           $message_id = $this->add($add_data);
           if (!$message_id){
               return array('status' => false,'msg' => '新增消息失败');
           }
           return array('status' => $message_id);
        }else {
            return array('status' => false,'msg' => '消息类型错误');
        }
    }
   /**
    * 获取用户信息
    * 添加时间2016-2-24
    * 
    * @author yzx
    * @param int $user_id
    * @return Ambigous <mixed, boolean, string, NULL, multitype:, unknown, object>
    */
    public function getMessage($user_id) {
        $result = $this->where(array('to_user' => $user_id))->select();
        return $result;
    }
}
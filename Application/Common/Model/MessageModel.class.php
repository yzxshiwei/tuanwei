<?php
namespace Common\Model;
class MessageModel extends \Common\Helper\Model{
    const TYPE_SYSTEM = 'system';
    const TYPE_USER = 'user';
    const TYPE_TEAM = 'team';
    const TYPE_JUDGES_PROJECT = 'judges_project';
    const TYPE_TEACHER_PROJECT = 'teacher_project';
    const TYPE_USER_PROJECT = 'user_project';
	const TYPE__APPLY = 'user_apply';
    /**
     * 消息类型
     * @var array
     */
    public $type = array(
        self::TYPE_SYSTEM => '系统消息',
        self::TYPE_USER => '用户消息',
        self::TYPE_TEAM => '团队消息',
        self::TYPE_JUDGES_PROJECT => '评委消息',
        self::TYPE_TEACHER_PROJECT => '指导老师消息',
        self::TYPE_USER_PROJECT => '团队邀请信息',
        self::TYPE__APPLY => '注册用户申请信息',
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
   * 发送提醒消息
   * @param array|int $toUser
   * @param int $fromUser
   * @param string $type
   * @param string $content
   */
    public function sendMsg($toUser,$fromUser,$type,$content,$project_id){
        $time = time();
        if (is_array($toUser) && !empty($toUser)){
            foreach ($toUser as $k){
                $add_data = array();
                $add_data['from_user'] = $fromUser;
                $add_data['create_time'] = $time;
                $add_data['to_user'] = $k;
                $add_data['msg_type'] = $type;
                $add_data['content'] = $content;
                $add_data['project_id'] = $project_id;
                $this->add($add_data);
            }
        }else {
            $add_data = array();
            $add_data['from_user'] = $fromUser;
            $add_data['create_time'] = $time;
            $add_data['to_user'] = $toUser;
            $add_data['msg_type'] = $type;
            $add_data['content'] = $content;
            $add_data['project_id'] = $project_id;
            $this->add($add_data);
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
    public function getMessage($user_id,$type = self::TYPE_USER) {
        $result = $this->where(array('to_user' => $user_id,'msg_type'=>$type))->select();
        return $result;
    }
    /**
     * 设置消息为已读
     * 添加时间2016-3-2
     * 
     * @author yzx
     * @param int $id
     * @return boolean
     */
    public function readMsg($id) {
        $time = time();
        $save_data['read_time'] = $time;
        return $this->where(array('id' => $id))->save($save_data);
    }
    /**
     * 同意邀请
     * 添加时间2016-3-2
     * 
     * @author yzx
     * @param unknown $param
     * @return bool
     */
    public function agree($user,$id,$proid) {
        $flag = false;
	    $judgesModel = new \Common\Model\JudgesModel();
	    $teacherTeamModel = new \Common\Model\Teacher_TeamModel();
	    $teamModel = new \Common\Model\TeamModel();
		$userModel = new \Common\Model\UsersModel();
	    $message_data = $this->where(array('id' => $id))->find();
	    //评委老师同意
	    if (self::TYPE_JUDGES_PROJECT == $message_data['msg_type']){
	        $judges_data['state'] = $judgesModel::STATE_ADOPT; 
	        $jd_res = $judgesModel->where(array('project_id' => $proid,'judge_id' => $user['user_id']))->save($judges_data);

	        $flag = $this->readMsg($id);
	    };
	    //指导老师同意
	   if (self::TYPE_TEACHER_PROJECT == $message_data['msg_type']){
	       $teacher_team_data['state'] = $teacherTeamModel::STATUS_PASS;
	       $tt_res = $teacherTeamModel->where(array('project_id' => $proid,'user_id' => $user['user_id']))->save($teacher_team_data);
		   
	       $flag = $this->readMsg($id);
	   }
	   //团队邀请同意
	   if (self::TYPE_USER_PROJECT == $message_data['msg_type']){
	       $team_data['state'] = $teamModel::STATE_PASS;
	       $t_res = $teamModel->where(array('leader_id' => $proid,'user_id' => $user['user_id']))->save($team_data);

	       $flag = $this->readMsg($id);
	   }
	   
	   //同意注册用户申请为  投资人/指导老师/评审庄家
	   if (self::TYPE__APPLY == $message_data['msg_type']){
	       $team_data['state'] = $teamModel::STATE_PASS;
	       $t_res = $userModel->where(array('user_id' => $proid))->save($team_data);

	       $flag = $this->readMsg($id);
	   }
	   
	   return $flag;
    }
    
	/**
     * 拒绝邀请
     * 添加时间2016-3-9
     * 
     * @author zlj
     * @param unknown $param
     * @return bool
     */
    public function deny($user,$id,$proid) {
        $flag = false;
	    $judgesModel = new \Common\Model\JudgesModel();
	    $teacherTeamModel = new \Common\Model\Teacher_TeamModel();
	    $teamModel = new \Common\Model\TeamModel();
	    $message_data = $this->where(array('id' => $id))->find();
	    //评委老师拒绝
	    if (self::TYPE_JUDGES_PROJECT == $message_data['msg_type']){

	        $jd_res = $judgesModel->where(array('project_id' => $proid,'judge_id' => $user['user_id']))->delete();

	        $flag = $this->readMsg($id);
	    };
	    //指导老师拒绝
	   if (self::TYPE_TEACHER_PROJECT == $message_data['msg_type']){
	       $tt_res = $teacherTeamModel->where(array('project_id' => $proid,'user_id' => $user['user_id']))->delete();

	       $flag = $this->readMsg($id);
	   }
	   //团队邀请拒绝
	   if (self::TYPE_USER_PROJECT == $message_data['msg_type']){
	       $t_res = $teamModel->where(array('project_id' => $proid,'user_id' => $user['user_id']))->delete();

	       $flag = $this->readMsg($id);
	   }
	   
	   //拒绝注册用户申请为  投资人/指导老师/评审庄家(不做任何操作)
	   if (self::TYPE__APPLY == $message_data['msg_type']){
	        $flag = $this->readMsg($id);
	   }
	   return $flag;
    }
	
}